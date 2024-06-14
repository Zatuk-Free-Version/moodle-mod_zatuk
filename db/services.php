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
define('MOODLE_ZATUK_WEB_SERVICE', 'zatuk_web_service');

$functions = [

    'mod_zatuk_view_url' => [
        'classname'     => 'mod_zatuk_external',
        'methodname'    => 'view_zatuk_url',
        'description'   => 'Trigger the course module viewed event and update the module completion status.',
        'type'          => 'write',
        'capabilities'  => 'mod/zatuk:view',
        'services'      => MOODLE_ZATUK_WEB_SERVICE,
    ],
    'mod_zatuk_get_zatuk_in_mobile' => [
        'classname'     => 'mod_zatuk_external',
        'methodname'    => 'get_zatuk_content',
        'description'   => 'Returns a list of Streams.',
        'type'          => 'read',
        'services'      => MOODLE_ZATUK_WEB_SERVICE,
    ],

    'mod_zatukattempts' => [
        'classname' => 'mod_zatuk_external',
        'methodname' => 'mod_zatukattempts',
        'description' => 'Inserting zatuking data into database',
        'type' => 'write',
        'ajax'        => true,
        'capabilities' => 'mod/zatuk:write',
    ],
    'zatuk_timeperiod' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'zatuk_timeperiod',
        'description' => 'Transfer report information',
        'type'        => 'read',
        'services' => MOODLE_ZATUK_WEB_SERVICE,
        'ajax' => true,
    ],
    'mod_zatuk_get_zatuk_by_courses' => [
        'classname'     => 'mod_zatuk_external',
        'methodname'    => 'get_zatuk_by_courses',
        'description'   => 'Returns a list of urls in a provided list of courses, if no list is provided all streams that the user
                            can view will be returned.',
        'type'          => 'read',
        'capabilities'  => 'mod/zatuk:view',
        'services'      => MOODLE_ZATUK_WEB_SERVICE,
    ],
    'mod_zatuk_view_zatuk' => [
        'classname'     => 'mod_zatuk_external',
        'methodname'    => 'view_zatuk',
        'description'   => 'Trigger the course module viewed event and update the module completion status.',
        'type'          => 'write',
        'capabilities'  => 'mod/zatuk:view',
        'services'      => MOODLE_ZATUK_WEB_SERVICE,
    ],
    'mod_zatuk_blocktablecontent' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'tablecontentblock',
        'description' => 'Table content render',
        'classpath'   => 'mod/zatuk/classes/external.php',
        'type'        => 'read',
        'ajax' => true,
    ],
    'mod_zatuk_upload_video' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'upload_video',
        'description' => 'Upload video',
        'classpath'   => 'mod/zatuk/classes/external.php',
        'type'        => 'write',
        'ajax' => true,
    ],
    'mod_zatuk_delete_video' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'delete_video',
        'description' => 'Delete uploaded video',
        'classpath'   => 'mod/zatuk/classes/external.php',
        'type'        => 'write',
        'ajax' => true,
    ],
    'mod_zatuk_update_video_zatuk' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'update_video',
        'description' => 'Update uploaded video',
        'classpath'   => 'mod/zatuk/classes/external.php',
        'type'        => 'write',
        'ajax' => true,
    ],
    'mod_zatuk_move_to_zatuk' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'move_tozatuk',
        'description' => 'Move the Video to the zatuk',
        'classpath'   => 'mod/zatuk/classes/external.php',
        'type'        => 'write',
        'ajax' => true,
    ],

    'mod_zatuk_validatezatukinstance' => [
        'classname'   => 'mod_zatuk_external',
        'methodname'  => 'validatezatukinstance',
        'description' => 'Validating zatuk instance',
        'classpath'   => 'mod/zatuk/classes/external.php',
        'type'        => 'write',
        'ajax' => true,
        'services'      => MOODLE_ZATUK_WEB_SERVICE,
    ],

];

$services = [
   'Zatuk Webservices'  => [
        'functions' => [], // Unused as we add the service in each function definition, third party services would use this.
        'enabled' => 1,
        'restrictedusers' => 0,
        'shortname' => MOODLE_ZATUK_WEB_SERVICE,
        'downloadfiles' => 1,
        'uploadfiles' => 1,
    ],
];
