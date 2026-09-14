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

defined('MOODLE_INTERNAL') || die();

$functions = [
    "mod_handraise_get_state" => [
        "classname" => "mod_handraise\\external\\get_state",
        "description" => "Returns the current Hand raise queue state.",
        "type" => "read",
        "ajax" => true,
        "loginrequired" => true,
    ],
    "mod_handraise_toggle_hand" => [
        "classname" => "mod_handraise\\external\\toggle_hand",
        "description" => "Raises or lowers the current user's hand.",
        "type" => "write",
        "ajax" => true,
        "loginrequired" => true,
    ],
    "mod_handraise_serve" => [
        "classname" => "mod_handraise\\external\\serve",
        "description" => "Removes one participant from the Hand raise queue.",
        "type" => "write",
        "ajax" => true,
        "loginrequired" => true,
    ],
];
