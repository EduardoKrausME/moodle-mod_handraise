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

namespace mod_handraise\external;

use context_module;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
use mod_handraise\queue_manager;

/**
 * Class get_state.
 */
class get_state extends external_api {
    /**
     * Method execute_parameters.
     *
     * @return external_function_parameters Return value.
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            "cmid" => new external_value(PARAM_INT, "Course module ID"),
        ]);
    }

    /**
     * Method execute.
     *
     * @param int $cmid Parameter cmid.
     * @return array Return value.
     */
    public static function execute(int $cmid): array {
        global $USER;

        ["cmid" => $cmid] = self::validate_parameters(self::execute_parameters(), ["cmid" => $cmid]);
        $cm = get_coursemodule_from_id("handraise", $cmid, 0, false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $canraise = has_capability("mod/handraise:raisehand", $context);
        $canmanage = has_capability("mod/handraise:managequeue", $context);
        if (!$canraise && !$canmanage) {
            throw new \required_capability_exception($context, "mod/handraise:raisehand", "nopermissions", "");
        }

        return (new queue_manager())->get_state($cm, $context, $USER->id);
    }

    /**
     * Method execute_returns.
     *
     * @return external_single_structure Return value.
     */
    public static function execute_returns(): external_single_structure {
        return self::state_structure();
    }

    /**
     * Method state_structure.
     *
     * @return external_single_structure Return value.
     */
    public static function state_structure(): external_single_structure {
        return new external_single_structure([
            "inqueue" => new external_value(PARAM_BOOL, "Whether the current user is in the queue"),
            "position" => new external_value(PARAM_INT, "Current user's position, or zero"),
            "queuecount" => new external_value(PARAM_INT, "Number of people waiting"),
            "canraise" => new external_value(PARAM_BOOL, "Whether the current user can raise a hand"),
            "canmanage" => new external_value(PARAM_BOOL, "Whether the current user can manage the queue"),
            "queue" => new external_multiple_structure(new external_single_structure([
                "id" => new external_value(PARAM_INT, "Queue entry ID"),
                "userid" => new external_value(PARAM_INT, "User ID"),
                "fullname" => new external_value(PARAM_TEXT, "Full name"),
                "position" => new external_value(PARAM_INT, "Queue position"),
                "timecreated" => new external_value(PARAM_INT, "Request timestamp"),
                "requestedat" => new external_value(PARAM_TEXT, "Formatted request time"),
                "waiting" => new external_value(PARAM_TEXT, "Formatted waiting time"),
            ])),
        ]);
    }
}
