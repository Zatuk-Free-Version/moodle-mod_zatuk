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
 * The mod_zatuk zatuk played event.
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\event;

/**
 * class zatuk_played
 */
class zatuk_played extends \core\event\base {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['objecttable'] = 'zatuk';
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = \core\event\base::LEVEL_PARTICIPATING;
    }
    /**
     * Get name
     * @return string
     */
    public static function get_name() {
        return get_string('eventvideoplayed', 'mod_zatuk');
    }
    /**
     * Get description
     * @return string
     */
    public function get_description() {
        return get_string('videoplayed', 'mod_zatuk', ['userid' => $this->userid, 'objectid' => $this->objectid]);
    }
    /**
     * Get url
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/mod/zatuk/view.php',
            ['id' => $this->objectid]);
    }
}
