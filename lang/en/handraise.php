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

defined('MOODLE_INTERNAL') || die;

$string['emptyqueue'] = 'No one is waiting.';
$string['handlowered'] = 'Hand lowered';
$string['handraise:addinstance'] = 'Add a Hand raise activity';
$string['handraise:managequeue'] = 'Manage the speaking queue';
$string['handraise:raisehand'] = 'Request to speak';
$string['handraised'] = 'Hand raised';
$string['handraisename'] = 'Activity name';
$string['handserved'] = 'Queue request completed';
$string['lowerhand'] = 'Cancel request';
$string['modulename'] = 'Hand raise';
$string['modulenameplural'] = 'Hand raise activities';
$string['pluginadministration'] = 'Hand raise administration';
$string['pluginname'] = 'Hand raise';
$string['positionlabel'] = 'Your position: ';
$string['privacy:metadata:handraise_queue'] = 'Stores the current speaking queue for a Hand raise activity.';
$string['privacy:metadata:handraise_queue:handraiseid'] = 'The Hand raise activity where the request was made.';
$string['privacy:metadata:handraise_queue:timecreated'] = 'The time when the user requested to speak.';
$string['privacy:metadata:handraise_queue:userid'] = 'The user who requested to speak.';
$string['queue'] = 'Speaking queue';
$string['queuecountlabel'] = 'People waiting: ';
$string['raisehand'] = 'I need to speak';
$string['requestedat'] = 'Requested ';
$string['requestfailed'] = 'The queue could not be updated. Please try again.';
$string['resetqueue'] = 'Clear Hand raise queues';
$string['serve'] = 'Done';
$string['waitingtime'] = 'Waiting ';
