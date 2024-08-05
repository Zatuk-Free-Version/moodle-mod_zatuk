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
 * mod_zatuk zatuk class
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\local;

use html_writer;
use context_course;
use context_module;

/**
 * zatuk class
 */
class zatuk {
    /**
     * Check to participate.
     * @param int $itemid
     * @return array
     */
    public function can_participate($itemid) {
        global $USER, $DB;
        $enroll = true;
        $disable = '';
        $result = '';

        $module = $DB->get_field('modules', 'id', ['name' => 'zatuk']);
        $cm = $DB->get_record('course_modules', ['instance' => $itemid, 'module' => $module]);
        $context = context_module::instance($cm->id);
        $coursecontext = context_course::instance($cm->course);
        if (!is_enrolled($coursecontext, $USER->id)) {
            $enroll = false;
        }
        if (!$enroll) {
            $disable = get_string('disabled', 'zatuk');
        }
        return ['result' => $result, 'attribute' => $disable , 'enroll' => $enroll];
    }
}

