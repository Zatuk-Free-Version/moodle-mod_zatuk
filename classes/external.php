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
     * Describes the parameters for view_zatuk.
     *
     */
    public static function view_zatuk_parameters() {
        return new external_function_parameters(
            [
                'zatukid' => new external_value(PARAM_INT, 'zatuk instance id'),
            ]
        );
    }

    /**
     * Trigger the course module viewed event and update the module completion status.
     *
     * @param int $zatukid the zatuk instance id
     * @return array of warnings and status result
     * @throws moodle_exception
     */
    public static function view_zatuk($zatukid) {
        global $DB, $CFG;
        require_once($CFG->dirroot . "/mod/zatuk/lib.php");

        $params = self::validate_parameters(self::view_zatuk_parameters(),
                                            [
                                                'zatukid' => $zatukid,
                                            ]);
        self::validate_context(context_system::instance());
        $warnings = [];
        // Request and permission validation.
        $zatuk = $DB->get_record('zatuk', ['id' => $params['zatukid']], '*', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($zatuk, 'zatuk');

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/zatuk:view', $context);

        // Call the zatuk/lib API.
        zatuk_view($zatuk, $course, $cm, $context);

        $result = [];
        $result['status'] = true;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function view_zatuk_returns() {
        return new external_single_structure(
            [
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            ]
        );
    }

    /**
     * Describes the parameters for tablecontentblock.
     *
     */
    public static function tablecontentblock_parameters() {
        return new external_function_parameters(
            [
               'args' => new external_value(PARAM_RAW, 'The data from datatables encoded as a json array', false),
            ]
        );
    }
    /**
     * function tablecontentblock
     * @param string $args
     */
    public static function tablecontentblock($args) {
        global $PAGE;
        require_login();
        self::validate_context(context_system::instance());
        $PAGE->set_context(\context_system::instance());
        $params = json_decode($args);
        $zatuk = new \mod_zatuk\zatuk();
        if ($params->args->action == "updatePreferences") {
            $countonly = true;
        } else {
            $countonly = false;
        }
        $zatukdata = $zatuk->zatuk_uploaded_video_data($params->args, $countonly);

        return $zatukdata;

    }
    /**
     * function tablecontentblock returns.
     *
     */
    public static function tablecontentblock_returns() {

        return new external_single_structure(
            [
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        [
                         'id' => new external_value(PARAM_INT, 'Video id'),
                         'title' => new external_value(PARAM_RAW, 'Video title'),
                         'thumbnail' => new external_value(PARAM_RAW, 'Thumbnail'),
                         'timecreated' => new external_value(PARAM_RAW, 'Created date/time'),
                         'usercreated' => new external_value(PARAM_RAW, 'Created user'),
                         'path' => new external_value(PARAM_RAW, 'Video path'),
                         'videoid' => new external_value(PARAM_RAW, 'Video unique id'),
                         'status' => new external_value(PARAM_BOOL, 'Video publish status'),
                         'deleteoption' => new external_value(PARAM_BOOL, 'Delete option'),
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
                'contextid' => new external_value(PARAM_INT, 'The context id for the service', false),
                'id' => new external_value(PARAM_INT, 'The id of the video uploaded', false),
            ]
        );
    }
    /**
     * function delete_video
     * @param int $contextid
     * @param int $id
     */
    public static function delete_video($contextid, $id) {
        $systemcontext = context_system::instance();
        self::validate_context(context_system::instance());
        if (is_siteadmin() && has_capability('mod/zatuk:deletevideo', $systemcontext)) {
            $zatuk = new \mod_zatuk\zatuk();
            return $zatuk->delete_uploaded_video($id);
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
        }
    }
    /**
     * function delete_video_returns
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
                'videoid' => new external_value(PARAM_RAW, 'The videoid of the uploaded video', false),
                'zatukurl' => new external_value(PARAM_RAW, 'The zatukurl of the uploaded video', false),
            ]
        );
    }
    /**
     * function update_video
     * @param string||null $videoid
     * @param string||null $zatukurl
     */
    public static function update_video($videoid, $zatukurl) {
        global $DB;
        $systemcontext = context_system::instance();
        self::validate_context(context_system::instance());
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
     * function update_video_returns
     *
     */
    public static function update_video_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }
    /**
     * Describes the parameters for move_tozatuk.
     *
     */
    public function move_tozatuk_parameters() {
        return new external_function_parameters(
            [
               'id' => new external_value(PARAM_INT, 'The id of the video uploaded', false),
            ]
        );
    }
    /**
     * function move_tozatuk
     * @param int $id
     */
    public function move_tozatuk($id) {
        $systemcontext = context_system::instance();
        self::validate_context(context_system::instance());
        if (is_siteadmin() && has_capability('mod/zatuk:deletevideo', $systemcontext)) {
            $uploader = new \mod_zatuk\lib\uploader();
            $uploader->publish_video_by_id($id);
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
        }
    }
    /**
     * function move_tozatuk_returns
     *
     */
    public function move_tozatuk_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }
    /**
     * Describes the parameters for validatezatukinstance.
     *
     */
    public function validatezatukinstance_parameters() {
        return new external_function_parameters(
            []
        );
    }
    /**
     * function validatezatukinstance
     *
     */
    public function validatezatukinstance() {
        $systemcontext = context_system::instance();
        self::validate_context(context_system::instance());
        if (is_siteadmin() && has_capability('mod/zatuk:manageactions', $systemcontext)) {
            return true;
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
        }
    }
    /**
     * function validatezatukinstance_returns
     *
     */
    public function validatezatukinstance_returns() {
        return new external_value(PARAM_BOOL, 'data');
    }

}

