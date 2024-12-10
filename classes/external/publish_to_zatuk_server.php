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
use context_course;
use mod_zatuk\lib\uploader;

/**
 * zatuk module external API
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class publish_to_zatuk_server extends external_api {
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'The id of the video uploaded'),
            'courseid' => new external_value(PARAM_INT, 'The id of the current course'),
        ]);
    }

    /**
     * Move zatuk video from lms to zatuk site based on id.
     * @param int $id
     * @param int $courseid
     * @return array
     */
    public static function execute(
        $id,
        $courseid
    ): array {

        [
            'id' => $id,
            'courseid' => $courseid,
        ] = self::validate_parameters(self::execute_parameters(), [
            'id' => $id,
            'courseid' => $courseid,
        ]);
        self::validate_context(context_course::instance($courseid));
        require_capability('mod/zatuk:uploadvideo', context_course::instance($courseid));
        $uploader = new uploader();
        $response = $uploader->publish_video_by_id($id, $courseid);
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
