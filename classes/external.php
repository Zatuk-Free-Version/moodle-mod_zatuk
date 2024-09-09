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
 * zatuk external API
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/lib/completionlib.php');
/**
 * class mod_zatuk_external
 */
class mod_zatuk_external extends external_api {

    /**
     * Describes the parameters for uploaded_video_data.
     *
     */
    public static function uploaded_video_data_parameters() {
        return new external_function_parameters(
            [
               'args' => new external_value(PARAM_RAW, 'The data from datatables encoded as a json array'),
            ]
        );
    }
    /**
     * Get zatuk uploaded video data.
     * @param string $args
     * @return array
     */
    public static function uploaded_video_data($args) {
        global $PAGE;
        $params = self::validate_parameters(self::uploaded_video_data_parameters(),
                                            [
                                                'args' => $args,
                                            ]);
        self::validate_context(context_system::instance());
        require_capability('mod/zatuk:viewvideos', context_system::instance());
        $PAGE->set_context(context_system::instance());
        $params = json_decode($args);

        $zatuk = new \mod_zatuk\zatuk();
        if ($params->args->action == "updatePreferences") {
            $countonly = true;
        } else {
            $countonly = false;
        }
        $zatukdata = $zatuk->zatuk_uploaded_video_data((array)$params->args, $countonly);

        return $zatukdata;

    }
    /**
     * Describes the uploaded_video_data return value.
     *
     */
    public static function uploaded_video_data_returns() {

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
                        ]
                    ), 'Data'
                ),
                'length' => new external_value(PARAM_RAW, 'Number of videos'),
            ]
        );
    }
    /**
     * Describes the parameters for delete_zatuk_video.
     *
     */
    public static function delete_zatuk_video_parameters() {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'The id of the video uploaded'),
            ]
        );
    }
    /**
     * Delete zatuk video.
     * @param int $id
     * @return array
     */
    public static function delete_zatuk_video($id) {
        $params = self::validate_parameters(self::delete_zatuk_video_parameters(),
                                            [
                                                'id' => $id,
                                            ]);
        self::validate_context(context_system::instance());
        require_capability('mod/zatuk:deletevideo', context_system::instance());
        $zatuk = new \mod_zatuk\zatuk();
        $response = $zatuk->delete_zatuk_content($id);
        $result = ($response) ? true : false;
        return ['result' => $result];
    }
    /**
     * Describes the delete_zatuk_video return value.
     *
     */
    public static function delete_zatuk_video_returns() {
        return new external_single_structure([
            'result'  => new external_value(PARAM_RAW, 'result', VALUE_OPTIONAL),
        ]);
    }
    /**
     * Describes the parameters for publish_to_zatuk_server.
     *
     */
    public static function publish_to_zatuk_server_parameters() {
        return new external_function_parameters(
            [
               'id' => new external_value(PARAM_INT, 'The id of the video uploaded'),
            ]
        );
    }
    /**
     * Move zatuk video from lms to zatuk site based on id.
     * @param int $id
     * @return array
     */
    public static function publish_to_zatuk_server($id) {
         global $CFG;
        require_once($CFG->dirroot.'/mod/zatuk/lib/uploader.php');
        $params = self::validate_parameters(self::publish_to_zatuk_server_parameters(),
                                            [
                                                'id' => $id,
                                            ]);
        self::validate_context(context_system::instance());
        require_capability('mod/zatuk:uploadvideo', context_system::instance());
        $uploader = new mod_zatuk\lib\uploader();
        $response = $uploader->publish_video_by_id($id);
        $result = ($response) ? true : false;
        return ['result' => $result];
    }
    /**
     * Describes the publish_to_zatuk_server return value.
     *
     */
    public static function publish_to_zatuk_server_returns() {
        return new external_single_structure([
            'result'  => new external_value(PARAM_RAW, 'result', VALUE_OPTIONAL),
        ]);
    }

}

