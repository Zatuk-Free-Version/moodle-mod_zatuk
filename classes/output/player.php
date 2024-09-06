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

use mod_zatuk\local\zatuk as zatuk;
use renderable;
use templatable;
/**
 * class player
 */
class player implements renderable, templatable {
    /**
     * @var $data
     */
    private $data;
    /**
     * @var $zatuk
     */
    private $zatuk;
    /**
     * @var $properties
     */
    private $properties;
    /**
     * @var $width
     */
    private $width = 640;
    /**
     * @var $height
     */
    private $height = 268;
    /**
     * Player constructor.
     * @param object $zatukinstance
     * @param \stdclass $cm
     * @return void
     */
    public function __construct($zatukinstance, $cm) {

        $this->properties = (array)json_decode($zatukinstance->displayoptions);
        $this->zatuk = new zatuk();
        $this->data['itemid'] = $cm->instance;
        $this->set_width();
        $this->set_height();
    }
    /**
     * Export data from template.
     * @param \stdclass $ouput  //renderer_base
     * @return string|array
     */
    public function export_for_template($ouput) {
        return $this->data;
    }
    /**
     * Set width
     * @return string|array|null
     */
    public function set_width() {
        if (isset($this->properties['width']) && $this->properties['width'] != '') {
            $this->width = $this->properties['width'];
        }
        $this->data['width'] = $this->width;
    }
    /**
     * Set height
     * @return string|array|null
     */
    public function set_height() {
        if (isset($this->properties['height']) && $this->properties['height'] != '') {
            $this->height = $this->properties['height'];
        }
        $this->data['height'] = $this->height;
    }
}

