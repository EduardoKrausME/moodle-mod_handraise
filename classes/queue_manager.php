<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Hand raise activity module implementation.
 *
 * @package mod_handraise
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_handraise;

use context_module;
use core_user\fields;
use dml_write_exception;
use stdClass;

/**
 * Class queue_manager.
 */
class queue_manager {
    /**
     * Method get_state.
     *
     * @param stdClass $cm Parameter cm.
     * @param context_module $context Parameter context.
     * @param int $userid Parameter userid.
     * @return array Return value.
     */
    public function get_state(stdClass $cm, context_module $context, int $userid): array {
        global $DB;

        $canmanage = has_capability("mod/handraise:managequeue", $context);
        $canraise = has_capability("mod/handraise:raisehand", $context);
        $ownrecord = $DB->get_record("handraise_queue", [
            "handraiseid" => $cm->instance,
            "userid" => $userid,
        ]);

        $queuecount = $DB->count_records("handraise_queue", ["handraiseid" => $cm->instance]);
        $position = 0;
        if ($ownrecord) {
            $sql = "SELECT COUNT(1)
                      FROM {handraise_queue}
                     WHERE handraiseid = :handraiseid
                       AND (timecreated < :timecreated1
                            OR (timecreated = :timecreated2 AND id <= :queueid))";
            $position = $DB->count_records_sql($sql, [
                "handraiseid" => $cm->instance,
                "timecreated1" => $ownrecord->timecreated,
                "timecreated2" => $ownrecord->timecreated,
                "queueid" => $ownrecord->id,
            ]);
        }

        $queue = [];
        if ($canmanage) {
            $queue = $this->get_queue($cm->instance);
        }

        return [
            "inqueue" => (bool)$ownrecord,
            "position" => $position,
            "queuecount" => $queuecount,
            "canraise" => $canraise,
            "canmanage" => $canmanage,
            "queue" => $queue,
        ];
    }

    /**
     * Method toggle.
     *
     * @param stdClass $cm Parameter cm.
     * @param context_module $context Parameter context.
     * @param int $userid Parameter userid.
     * @return array Return value.
     */
    public function toggle(stdClass $cm, context_module $context, int $userid): array {
        global $DB;

        require_capability("mod/handraise:raisehand", $context);

        $existing = $DB->get_record("handraise_queue", [
            "handraiseid" => $cm->instance,
            "userid" => $userid,
        ]);

        if ($existing) {
            $DB->delete_records("handraise_queue", ["id" => $existing->id]);
            \mod_handraise\event\hand_lowered::create([
                "objectid" => $existing->id,
                "context" => $context,
                "relateduserid" => $userid,
                "other" => ["handraiseid" => $cm->instance],
            ])->trigger();

            return $this->get_state($cm, $context, $userid);
        }

        $record = (object)[
            "handraiseid" => $cm->instance,
            "userid" => $userid,
            "timecreated" => time(),
        ];

        try {
            $record->id = $DB->insert_record("handraise_queue", $record);
        } catch (dml_write_exception $exception) {
            $record = $DB->get_record("handraise_queue", [
                "handraiseid" => $cm->instance,
                "userid" => $userid,
            ], "*", IGNORE_MISSING);
            if (!$record) {
                throw $exception;
            }
        }

        \mod_handraise\event\hand_raised::create([
            "objectid" => $record->id,
            "context" => $context,
            "relateduserid" => $userid,
            "other" => ["handraiseid" => $cm->instance],
        ])->trigger();

        return $this->get_state($cm, $context, $userid);
    }

    /**
     * Method serve.
     *
     * @param stdClass $cm Parameter cm.
     * @param context_module $context Parameter context.
     * @param int $queueid Parameter queueid.
     * @param int $teacherid Parameter teacherid.
     * @return array Return value.
     */
    public function serve(stdClass $cm, context_module $context, int $queueid, int $teacherid): array {
        global $DB;

        require_capability("mod/handraise:managequeue", $context);
        $record = $DB->get_record("handraise_queue", [
            "id" => $queueid,
            "handraiseid" => $cm->instance,
        ], "*", MUST_EXIST);

        $DB->delete_records("handraise_queue", ["id" => $record->id]);
        \mod_handraise\event\hand_served::create([
            "objectid" => $record->id,
            "context" => $context,
            "userid" => $teacherid,
            "relateduserid" => $record->userid,
            "other" => ["handraiseid" => $cm->instance],
        ])->trigger();

        return $this->get_state($cm, $context, $teacherid);
    }

    /**
     * Method get_queue.
     *
     * @param int $handraiseid Parameter handraiseid.
     * @return array Return value.
     */
    private function get_queue(int $handraiseid): array {
        global $DB;

        $userfields = fields::for_name()->get_sql("u", true);

        $sql = "
            SELECT q.id, q.userid, q.timecreated{$userfields->selects}
              FROM {handraise_queue} q
              JOIN {user} u ON u.id = q.userid
             WHERE q.handraiseid = :handraiseid
          ORDER BY q.timecreated ASC, q.id ASC";
        $params = array_merge(
            $userfields->params,
            ["handraiseid" => $handraiseid]
        );
        $records = $DB->get_records_sql($sql, $params);

        $queue = [];
        $position = 0;
        foreach ($records as $record) {
            $position++;
            $queue[] = [
                "id" => (int)$record->id,
                "userid" => (int)$record->userid,
                "fullname" => fullname($record),
                "position" => $position,
                "timecreated" => (int)$record->timecreated,
                "requestedat" => userdate($record->timecreated, get_string("strftimetime24", "langconfig")),
                "waiting" => format_time(max(0, time() - $record->timecreated)),
            ];
        }

        return $queue;
    }
}
