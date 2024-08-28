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
 * URL external API
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
     * Describes the parameters for viewzatukcontent.
     *
     */
    public static function viewzatukcontent_parameters() {
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
    public static function viewzatukcontent($args) {
        global $PAGE;
        $params = self::validate_parameters(self::viewzatukcontent_parameters(),
                                            [
                                                'args' => $args,
                                            ]);
        self::validate_context(context_system::instance());
        $PAGE->set_context(\context_system::instance());
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
     * Describes the viewzatukcontent return value.
     *
     */
    public static function viewzatukcontent_returns() {

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
     * Describes the parameters for delete_video.
     *
     */
    public static function delete_video_parameters() {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'The id of the video uploaded'),
            ]
        );
    }
    /**
     * Delete zatuk video.
     * @param int $id
     * @return bool
     */
    public static function delete_video($id) {
        $params = self::validate_parameters(self::delete_video_parameters(),
                                            [
                                                'id' => $id,
                                            ]);
        $systemcontext = context_system::instance();
        self::validate_context(context_system::instance());
        if (is_siteadmin() && has_capability('mod/zatuk:deletevideo', $systemcontext)) {
            $zatuk = new \mod_zatuk\zatuk();
            return $zatuk->delete_zatuk_content($id);
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
        }
    }
    /**
     * Describes the delete_video return value.
     *
     */
    public static function delete_video_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }
    /**
     * Describes the parameters for update_video.
     *
     */
    public static function update_video_parameters() {
        return new external_function_parameters(
            [
                'videoid' => new external_value(PARAM_RAW, 'The videoid of the uploaded video'),
                'zatukurl' => new external_value(PARAM_RAW, 'The zatukurl of the uploaded video'),
            ]
        );
    }
    /**
     * Update zatuk video based on video id.
     * @param string||null $videoid
     * @param string||null $zatukurl
     * @return bool
     */
    public static function update_video($videoid, $zatukurl) {
        global $DB;
        $params = self::validate_parameters(self::update_video_parameters(),
                                            [
                                                'videoid' => $videoid,
                                                'zatukurl' => $zatukurl,
                                            ]);
        $systemcontext = context_system::instance();
        self::validate_context($systemcontext);
        try {
            $dataobj = new stdClass();
            $dataobj->id = $DB->get_field('zatuk_uploaded_videos', 'id', ['videoid' => $videoid], MUST_EXIST);
            $dataobj->zatukurl = $zatukurl;
            if (is_siteadmin() && has_capability('mod/zatuk:editvideo', $systemcontext)) {
                $DB->update_record('zatuk_uploaded_videos', $dataobj);
                return true;
            } else {
                throw new moodle_exception('actionpermission', 'mod_zatuk');
            }
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Describes the update_video return value.
     *
     */
    public static function update_video_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }
    /**
     * Describes the parameters for move_tozatuk.
     *
     */
    public static function move_tozatuk_parameters() {
        return new external_function_parameters(
            [
               'id' => new external_value(PARAM_INT, 'The id of the video uploaded'),
            ]
        );
    }
    /**
     * Move zatuk video from lms to zatuk site based on id.
     * @param int $id
     * @return bool|null
     */
    public static function move_tozatuk($id) {
        $params = self::validate_parameters(self::move_tozatuk_parameters(),
                                            [
                                                'id' => $id,
                                            ]);
        $systemcontext = context_system::instance();
        self::validate_context($systemcontext);
        if (is_siteadmin() && has_capability('mod/zatuk:uploadvideo', $systemcontext)) {
            $uploader = new \mod_zatuk\lib\uploader();
            $uploader->publish_video_by_id($id);
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
        }
    }
    /**
     * Describes the move_tozatuk return value.
     *
     */
    public static function move_tozatuk_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }
    /**
     * Describes the parameters for validatezatukinstance.
     *
     */
    public static function validatezatukinstance_parameters() {
        return new external_function_parameters(
            []
        );
    }
    /**
     * Describes the zatuk instance validation.
     * @return bool
     */
    public static function validatezatukinstance() {
        $systemcontext = context_system::instance();
        self::validate_context($systemcontext);
        if (is_siteadmin() && has_capability('mod/zatuk:manageactions', $systemcontext)) {
            return true;
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
        }
    }
    /**
     * Describes the validatezatukinstance return value.
     *
     */
    public static function validatezatukinstance_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }

}

