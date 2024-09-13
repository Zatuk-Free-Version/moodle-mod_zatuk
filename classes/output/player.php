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
 * mod_zatuk player class
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\output;

use renderable;
use templatable;
use stdClass;
/**
 * class player
 */
class player implements renderable, templatable {
    /**
     * @var $data
     */
    private $data;
    /**
     * Player constructor.
     * @param stdclass $cm
     * @return void
     */
    public function __construct($cm) {

        $this->data['itemid'] = $cm->instance;
    }
    /**
     * Export data from template.
     * @param stdclass $ouput  //renderer_base
     * @return string|array
     */
    public function export_for_template($ouput) {
        return $this->data;
    }

}

