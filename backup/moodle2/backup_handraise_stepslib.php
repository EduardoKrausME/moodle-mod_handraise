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
class backup_handraise_activity_structure_step extends backup_activity_structure_step {
    /**
     * Method define_structure.
     *
     * @return backup_nested_element Return value.
     */
    protected function define_structure(): backup_nested_element {
        $handraise = new backup_nested_element("handraise", ["id"], [
            "name", "intro", "introformat", "timecreated", "timemodified",
        ]);
        $queue = new backup_nested_element("queue");
        $entry = new backup_nested_element("entry", ["id"], ["userid", "timecreated"]);

        $handraise->add_child($queue);
        $queue->add_child($entry);

        $handraise->set_source_table("handraise", ["id" => backup::VAR_ACTIVITYID]);
        if ($this->get_setting_value("userinfo")) {
            $entry->set_source_table("handraise_queue", ["handraiseid" => backup::VAR_ACTIVITYID]);
            $entry->annotate_ids("user", "userid");
        }

        $handraise->annotate_files("mod_handraise", "intro", null);
        return $this->prepare_activity_structure($handraise);
    }
}
