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
 * Defines restore_zatuk_activity_structure_step class
 *
 * @since Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_zatuk_activity_task
 */
/**
 * Structure step to restore one content activity
 */
class restore_zatuk_activity_structure_step extends restore_activity_structure_step {
    /**
     * define_structure
     */
    protected function define_structure() {

        $paths = [];
        $paths[] = new restore_path_element('zatuk', '/activity/zatuk');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }
    /**
     * process_zatuk
     * @param object $data
     */
    protected function process_zatuk($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $newitemid = $DB->insert_record('zatuk', $data);
        $this->apply_activity_instance($newitemid);
    }
    /**
     * after_execute
     */
    protected function after_execute() {
        // Add content related files, no need to match by itemname (just internally handled context).
    }
}
