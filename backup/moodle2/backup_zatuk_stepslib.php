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
 * Defines backup_zatuk_activity_structure_step class
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_zatuk_activity_task
 */
class backup_zatuk_activity_structure_step extends backup_activity_structure_step {
    /**
     * Define the complete content structure for backup
     */
    protected function define_structure() {

         // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

         // Define each element separated.
        $fields = ['course',
                   'name',
                   'intro',
                   'introformat',
                    'externalurl',
                    'videoid',
                    'display',
                    'displayoptions',
                    'parameters',
                    'usercreated',
                    'usermodified',
                    'timecreated',
                    'timemodified',
                    'duration',
                    'completionvideoenabled',
                ];
        $zatuk = new backup_nested_element('zatuk', ['id'], $fields );
        // Build the tree.
        // Define sources.
        $zatuk->set_source_table('zatuk', ['id' => backup::VAR_ACTIVITYID]);

        return $this->prepare_activity_structure($zatuk);
    }
}

