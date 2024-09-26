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
use core_external\external_multiple_structure;
use core_external\external_value;
use context_system;
use mod_zatuk\zatuk;

/**
 * zatuk module external API
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class uploaded_video_data extends external_api {
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
           'args' => new external_value(PARAM_RAW, 'The data from datatables encoded as a json array'),
        ]);
    }

    /**
     * Get zatuk uploaded video data.
     * @param string $args
     * @return array
     */
    public static function execute(
        $args
    ): array {

        [
            'args' => $args,
        ] = self::validate_parameters(self::execute_parameters(), [
            'args' => $args,
        ]);
        self::validate_context(context_system::instance());
        $params = json_decode($args);
        if ($params->args->action == "updatePreferences") {
            $countonly = true;
        } else {
            $countonly = false;
        }
        $zatuk = new zatuk();
        $zatukdata = $zatuk->zatuk_uploaded_video_data((array)$params->args, $countonly);

        return $zatukdata;
    }

    /**
     * Describe the return structure of the external service.
     *
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure(
            [
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        [
                         'id' => new external_value(PARAM_INT, 'Video id'),
                         'title' => new external_value(PARAM_RAW, 'Video title'),
                         'thumbnail' => new external_value(PARAM_RAW, 'Thumbnail'),
                         'timecreated' => new external_value(PARAM_RAW, 'Created date/time'),
                         'userfullname' => new external_value(PARAM_RAW, 'Created user'),
                         'path' => new external_value(PARAM_RAW, 'Video path'),
                         'videoid' => new external_value(PARAM_RAW, 'Video unique id'),
                         'status' => new external_value(PARAM_BOOL, 'Video publish status'),
                         'deleteoption' => new external_value(PARAM_BOOL, 'Delete option'),
                         'iszatukrepoenabled' => new external_value(PARAM_INT, 'Is zatuk repository enabled'),
                         'canviewvideo' => new external_value(PARAM_INT, 'Is video plublished to streaming application.'),
                        ]
                    ), 'Data'
                ),
                'length' => new external_value(PARAM_RAW, 'Number of videos'),
            ]
        );
    }
}
