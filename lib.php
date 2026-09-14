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
 * Public callbacks for the Hand raise activity.
 *
 * @package mod_handraise
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Declares Moodle features supported by this activity.
 *
 * @param string $feature
 * @return mixed
 */
function handraise_supports(string $feature): mixed {
    return match ($feature) {
        FEATURE_MOD_ARCHETYPE => MOD_ARCHETYPE_OTHER,
        FEATURE_MOD_INTRO => true,
        FEATURE_SHOW_DESCRIPTION => true,
        FEATURE_BACKUP_MOODLE2 => true,
        FEATURE_COMPLETION_TRACKS_VIEWS => true,
        FEATURE_MOD_PURPOSE => MOD_PURPOSE_COMMUNICATION,
        default => null,
    };
}

/**
 * Adds a Hand raise activity.
 *
 * @param stdClass $data
 * @param mod_handraise_mod_form|null $mform
 * @return int
 */
function handraise_add_instance(stdClass $data, ?mod_handraise_mod_form $mform = null): int {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;

    return $DB->insert_record("handraise", $data);
}

/**
 * Updates a Hand raise activity.
 *
 * @param stdClass $data
 * @param mod_handraise_mod_form|null $mform
 * @return bool
 */
function handraise_update_instance(stdClass $data, ?mod_handraise_mod_form $mform = null): bool {
    global $DB;

    $data->id = $data->instance;
    $data->timemodified = time();

    return $DB->update_record("handraise", $data);
}

/**
 * Deletes a Hand raise activity and its queue.
 *
 * @param int $id
 * @return bool
 */
function handraise_delete_instance(int $id): bool {
    global $DB;

    if (!$DB->record_exists("handraise", ["id" => $id])) {
        return false;
    }

    $DB->delete_records("handraise_queue", ["handraiseid" => $id]);
    $DB->delete_records("handraise", ["id" => $id]);

    return true;
}

/**
 * Adds Hand raise options to the course reset form.
 *
 * @param MoodleQuickForm $mform
 */
function handraise_reset_course_form_definition(&$mform): void {
    $mform->addElement("header", "handraiseheader", get_string("modulenameplural", "mod_handraise"));
    $mform->addElement("advcheckbox", "handraise_reset_queue", get_string("resetqueue", "mod_handraise"));
}

/**
 * Returns the default values for course reset.
 *
 * @param stdClass $course
 * @return array
 */
function handraise_reset_course_form_defaults(stdClass $course): array {
    return ["handraise_reset_queue" => 1];
}

/**
 * Resets user data in Hand raise activities.
 *
 * @param stdClass $data
 * @return array
 */
function handraise_reset_userdata(stdClass $data): array {
    global $DB;

    $status = [];
    if (!empty($data->handraise_reset_queue)) {
        $sql = "DELETE FROM {handraise_queue}
                 WHERE handraiseid IN (
                     SELECT id
                       FROM {handraise}
                      WHERE course = :courseid
                 )";
        $DB->execute($sql, ["courseid" => $data->courseid]);
        $status[] = [
            "component" => get_string("modulenameplural", "mod_handraise"),
            "item" => get_string("resetqueue", "mod_handraise"),
            "error" => false,
        ];
    }

    return $status;
}
