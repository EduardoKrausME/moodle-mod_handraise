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
use mod_handraise\queue_manager;

/**
 * Tests for queue manager.
 *
 * @package mod_handraise
 * @copyright 2026 Eduardo Kraus
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @coversDefaultClass \mod_handraise\local\queue_manager
 */
final class queue_manager_test extends \advanced_testcase {
    /**
     * Tests that the queue preserves the request order.
     *
     * @covers ::toggle
     * @covers ::get_state
     */
    public function test_queue_preserves_request_order(): void {
        global $DB;

        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $activity = $this->getDataGenerator()->create_module("handraise", ["course" => $course->id]);
        $cm = get_coursemodule_from_instance("handraise", $activity->id, $course->id, false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        $student1 = $this->getDataGenerator()->create_and_enrol($course, "student");
        $student2 = $this->getDataGenerator()->create_and_enrol($course, "student");
        $teacher = $this->getDataGenerator()->create_and_enrol($course, "editingteacher");
        $manager = new queue_manager();

        $this->setUser($student1);
        $manager->toggle($cm, $context, $student1->id);
        $first = $DB->get_record("handraise_queue", ["handraiseid" => $activity->id, "userid" => $student1->id]);
        $DB->set_field("handraise_queue", "timecreated", time() - 10, ["id" => $first->id]);

        $this->setUser($student2);
        $manager->toggle($cm, $context, $student2->id);

        $this->setUser($teacher);
        $state = $manager->get_state($cm, $context, $teacher->id);
        $this->assertCount(2, $state["queue"]);
        $this->assertSame((int)$student1->id, $state["queue"][0]["userid"]);
        $this->assertSame((int)$student2->id, $state["queue"][1]["userid"]);
    }
}
