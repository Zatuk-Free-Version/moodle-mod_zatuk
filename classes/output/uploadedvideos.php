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
 * @since     Moodle 2.0
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\output;

use renderable;
use renderer_base;
use templatable;
use mod\zatuk;
/**
 * Class uploadedvideos.
 *
 */
class uploadedvideos implements renderable, templatable {

    /**
     * @var object $context
     */
    protected $context;
    /**
     * Uploadvideos constructor.
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
     * @return \stdClass
     */
    public function export_for_template(renderer_base $output) {

        $data = new \stdClass();
        $apikey = trim(get_config('repository_zatuk', 'zatuk_key'));
        $data->all = true;
        $datalength = (new \mod_zatuk\zatuk)->zatuk_uploaded_video_data();
        $data->length = $datalength['length'];
        $data->statusfilter = 'all';
        $data->addcapability = (is_siteadmin() || has_capability('mod/zatuk:uploadvideo', $this->context)) ? true : false;
        $data->zatukrepoenabled = $apikey ? true : false;
        return $data;
    }
}
