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
 *
 * mod_zatuk data generator class.
 *
 * @package    mod_zatuk
 * @category   test
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_zatuk_generator extends testing_module_generator {

    /**
     * Create a new instance of the zatuk activity.
     *
     * @param array $record
     * @param array $options
     * @return stdClass
     */
    public function create_instance($record = [], array $options = []) {
        global $CFG;

        require_once($CFG->dirroot.'/lib/resourcelib.php');

        $record = (object)(array)$record;

        if (!isset($record->intro)) {
            $record->intro = get_string('zatukcontent', 'mod_zatuk');
        }
        if (!isset($record->introformat )) {
            $record->introformat = FORMAT_MOODLE;
        }
        if (!isset($record->display)) {
            $record->display = RESOURCELIB_DISPLAY_AUTO;
        }
        if (!isset($record->externalurl)) {
            $record->externalurl = 'http://moodle.org/';
        }
        if (!isset($record->videoid)) {
            $record->videoid = file_get_unused_draft_itemid();
        }
        return parent::create_instance($record, (array)$options);
    }
}
