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
class restore_handraise_activity_structure_step extends restore_activity_structure_step {
    /**
     * Method define_structure.
     *
     * @return array Return value.
     */
    protected function define_structure(): array {
        $paths = [new restore_path_element("handraise", "/activity/handraise")];
        if ($this->get_setting_value("userinfo")) {
            $paths[] = new restore_path_element("handraise_queue_entry", "/activity/handraise/queue/entry");
        }
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Method process_handraise.
     *
     * @param mixed $data Parameter data.
     * @return void Return value.
     */
    protected function process_handraise($data): void {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record("handraise", $data);
        $this->apply_activity_instance($newitemid);
        $this->set_mapping("handraise", $oldid, $newitemid, true);
    }

    /**
     * Method process_handraise_queue_entry.
     *
     * @param mixed $data Parameter data.
     * @return void Return value.
     */
    protected function process_handraise_queue_entry($data): void {
        global $DB;

        $data = (object)$data;
        $data->handraiseid = $this->get_new_parentid("handraise");
        $data->userid = $this->get_mappingid("user", $data->userid, 0);
        if (!$data->userid) {
            return;
        }
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $DB->insert_record("handraise_queue", $data);
    }

    /**
     * Method after_execute.
     *
     * @return void Return value.
     */
    protected function after_execute(): void {
        $this->add_related_files("mod_handraise", "intro", null);
    }
}
