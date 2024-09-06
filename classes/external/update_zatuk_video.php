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

namespace mod_zatuk\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;
use context_system;
use stdClass;
use Exception;

/**
 * zatuk module external API
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class update_zatuk_video extends external_api {
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'videoid' => new external_value(PARAM_RAW, 'The videoid of the uploaded video'),
            'zatukurl' => new external_value(PARAM_RAW, 'The zatukurl of the uploaded video'),
        ]);
    }

    /**
     * Update zatuk video based on video id.
     * @param string||null $videoid
     * @param string||null $zatukurl
     * @return array
     */
    public static function execute($videoid, $zatukurl) {
        global $DB;

        [
            'videoid' => $videoid,
            'zatukurl' => $zatukurl,
        ] = self::validate_parameters(self::execute_parameters(), [
            'videoid' => $videoid,
            'zatukurl' => $zatukurl,
        ]);
        self::validate_context(context_system::instance());
        require_capability('mod/zatuk:editvideo', context_system::instance());
        try {
            $dataobj = new stdClass();
            $dataobj->id = $DB->get_field('zatuk_uploaded_videos', 'id', ['videoid' => $videoid], MUST_EXIST);
            $dataobj->zatukurl = $zatukurl;
            $response = $DB->update_record('zatuk_uploaded_videos', $dataobj);
        } catch (Exception $e) {
            $response = false;
        }
        $result = ($response) ? true : false;
        return ['result' => $result];
    }
    /**
     * Describe the return structure of the external service.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'result'  => new external_value(PARAM_RAW, 'result', VALUE_OPTIONAL),
        ]);
    }
}
