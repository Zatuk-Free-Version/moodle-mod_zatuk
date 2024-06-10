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
 * mod_zatuk uploadedvideos class
 *
 * @package   mod_zatuk
 * @copyright 2021 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\output;

use renderable;
use renderer_base;
use templatable;
use mod\zatuk;
/**
 * Class containing data for myprofile mod.
 *
 * @copyright  2021 2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class uploadedvideos implements renderable, templatable {

    /**
     * @var object $context
     */
    protected $context;
    /**
     *
     * @param object $context
     * @return object
     */
    public function __construct($context) {
        $this->context = $context;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {

        $data = new \stdClass();

        $data->all = true;
        $datalength = (new \mod_zatuk\zatuk)->uploadedvideodata();
        $data->length = $datalength['length'];
        $data->statusfilter = 'all';
        $condition = (is_siteadmin() ||
                      has_capability('mod/zatuk:editingteacher', $this->context) ||
                      has_capability('mod/zatuk:manageactions', $systemcontext)
                    );
        $data->addcapability = $condition ? true : false;
        return $data;
    }
}
