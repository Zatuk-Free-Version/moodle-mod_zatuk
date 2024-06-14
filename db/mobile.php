<?php
// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Mobile app definition for zatuk.
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'mod_zatuk' => [ // Plugin identifier.
        'handlers' => [ // Different places where the plugin will display content..
            'zatuk' => [ // Handler unique name (alphanumeric).
                'displaydata' => [
                    'icon' => $CFG->wwwroot . '/mod/zatuk/pix/icon.png',
                    'class' => '',
                ],

                'delegate' => 'CoreCourseModuleDelegate', // Delegate (where to display the link to the plugin).
                'method' => 'zatuk_view', // Main function in \mod_zatuk\output\mobile.
            ],
        ],
        'lang' => [ // Language strings that are used in all the handlers.
            ['pluginname', 'zatuk'],
        ],
    ],
];
