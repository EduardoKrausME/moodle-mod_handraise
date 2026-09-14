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
 * Displays the Hand raise activity.
 *
 * @package mod_handraise
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . "/../../config.php");

use mod_handraise\queue_manager;

$id = required_param("id", PARAM_INT);
$cm = get_coursemodule_from_id("handraise", $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$handraise = $DB->get_record("handraise", ["id" => $cm->instance], "*", MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$canraise = has_capability("mod/handraise:raisehand", $context);
$canmanage = has_capability("mod/handraise:managequeue", $context);
if (!$canraise && !$canmanage) {
    throw new required_capability_exception($context, "mod/handraise:raisehand", "nopermissions", "");
}

$PAGE->set_url("/mod/handraise/view.php", ["id" => $cm->id]);
$PAGE->set_title(format_string($handraise->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_activity_record($handraise);

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$manager = new queue_manager();
$state = $manager->get_state($cm, $context, $USER->id);

$PAGE->requires->strings_for_js([
    "raisehand",
    "lowerhand",
    "serve",
    "emptyqueue",
    "positionlabel",
    "queuecountlabel",
    "requestfailed",
], "mod_handraise");
$PAGE->requires->js_call_amd("mod_handraise/handraise", "init", [[
    "cmid" => $cm->id,
    "pollinterval" => 2000,
    "canraise" => $canraise,
    "canmanage" => $canmanage,
]]);

$templatecontext = [
    "cmid" => $cm->id,
    "intro" => format_module_intro("handraise", $handraise, $cm->id),
    "hasintro" => trim((string)$handraise->intro) !== "",
    "canraise" => $canraise,
    "canmanage" => $canmanage,
    "inqueue" => !empty($state["inqueue"]),
    "position" => $state["position"],
    "queuecount" => $state["queuecount"],
    "queue" => $state["queue"],
    "hasqueue" => !empty($state["queue"]),
];

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($handraise->name));
echo $OUTPUT->render_from_template("mod_handraise/view", $templatecontext);
echo $OUTPUT->footer();
