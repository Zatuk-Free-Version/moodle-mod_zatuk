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
 * zatuk external functions and service definitions.
 *
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot.'/repository/zatuk/lib.php');
$functions = [

    'mod_zatuk_view_zatuk_uploaded_video_data' => [
        'classname'     => 'mod_zatuk\external\uploaded_video_data',
        'methodname'    => 'execute',
        'description'   => 'Table content render',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
        'capabilities'  => 'mod/zatuk:viewvideos',
        'services'      => [MOODLE_ZATUK_WEB_SERVICE],
    ],
    'mod_zatuk_delete_video' => [
        'classname'   => 'mod_zatuk\external\delete_zatuk_video',
        'methodname'  => 'execute',
        'description' => 'Delete uploaded video',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
        'capabilities'  => 'mod/zatuk:deletevideo',
        'services'    => [MOODLE_ZATUK_WEB_SERVICE],
    ],
    'mod_zatuk_move_to_zatuk' => [
        'classname'   => 'mod_zatuk\external\publish_to_zatuk_server',
        'methodname'  => 'execute',
        'description' => 'Move the Video to the zatuk',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
        'capabilities'  => 'mod/zatuk:uploadvideo',
        'services'    => [MOODLE_ZATUK_WEB_SERVICE],
    ],

];
