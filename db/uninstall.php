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
 * zatuk module upgrade code
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * This is called at the beginning of the uninstallation process to give the module
 * a chance to clean-up its hacks, bits etc. where possible.
 *
 */
function xmldb_mod_zatuk_uninstall() {
    global $DB;
    // Delete all zatuk modules.
    $sql = 'SELECT com.* FROM {course_modules} com
            JOIN  {modules} mo ON mo.id = com.module
            WHERE mo.name = :modulename';
    $zatukmodules = $DB->get_records($sql, ['modulename' => 'zatuk']);
    if ($zatukmodules) {
        foreach ($zatukmodules as $module) {
            if ((int)$module->id) {
                course_delete_module((int)$module->id);
            }
        }
    }
}

