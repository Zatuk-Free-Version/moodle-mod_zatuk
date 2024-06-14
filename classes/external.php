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
 * @package    mod_zatuk
 * @category   external
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");
require_once($CFG->dirroot.'/lib/completionlib.php');
/**
 * URL external functions
 *
 * @package    mod_zatuk
 * @category   external
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */
class mod_zatuk_external extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function view_zatuk_url_parameters() {
        return new external_function_parameters(
            [
              'urlid' => new external_value(PARAM_INT, 'url instance id'),
            ]
        );
    }

    /**
     * Trigger the course module viewed event and update the module completion status.
     *
     * @param int $urlid the url instance id
     * @return array of warnings and status result
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function view_zatuk_url($urlid) {
        global $DB, $CFG;
        require_once($CFG->dirroot . "/mod/url/lib.php");
        $params = self::validate_parameters(self::view_url_parameters(), ['urlid' => $urlid]);
        $warnings = [];
        // Request and permission validation.
        $url = $DB->get_record('url', ['id' => $params['urlid']], '*', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($url, 'url');

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        require_capability('mod/url:view', $context);

        // Call the url/lib API.
        url_view($url, $course, $cm, $context);

        $result = [];
        $result['status'] = true;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function view_zatuk_url_returns() {
        return new external_single_structure(
            [
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            ]
        );
    }
    /**
     * Returns description of content parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_zatuk_content_parameters() {
        return new external_function_parameters (
            [
              'cmid' => new external_value(PARAM_INT, 'UserID'),
            ]
        );
    }
    /**
     * Trigger the course module viewed event and update the module completion status.
     *
     * @param int $cmid the module instance id
     * @return array of media result
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function get_zatuk_content($cmid) {
        global $USER, $DB, $CFG;
        require_once($CFG->dirroot.'/mod/zatuk/locallib.php');
        $data = $DB->get_record_sql("SELECT s.* FROM {zatuk} s
        JOIN {course_modules} cm ON cm.instance = s.id WHERE cm.id = ".$cmid);
        $result[] = [
        'id' => $data->id,
        'course' => $data->course,
        'name' => $data->name,
        'intro' => $data->intro,
        'introformat' => $data->introformat,
        'externalurl' => $data->externalurl,
        'display' => $data->display,
        'displayoptions' => $data->displayoptions,
        'parameters' => $data->parameters,
        'timemodified' => $data->timemodified,
        ];
        return ['media' => $result];
    }
    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_zatuk_content_returns() {
        return new external_single_structure(
            [
                'media' => new external_multiple_structure(
                    new external_single_structure(
                        ['id' => new external_value(PARAM_INT, 'Module id'),
                        'course' => new external_value(PARAM_INT, 'Course module id'),
                        'name' => new external_value(PARAM_RAW, 'saranyu name'),
                        'intro' => new external_value(PARAM_RAW, 'Summary'),
                        'introformat' => new external_format_value('intro', 'Summary format'),
                        'externalurl' => new external_value(PARAM_RAW, 'saranyu content'),
                        'display' => new external_value(PARAM_RAW, 'saranyu content'),
                        'displayoptions' => new external_value(PARAM_RAW, 'saranyu content'),
                        'parameters' => new external_value(PARAM_RAW, 'saranyu content'),
                        'timemodified' => new external_value(PARAM_RAW, 'saranyu content'),
                        ]
                    )
                ),
            ]
        );
    }
    /**
     * function mod_zatukattempts_parameters
     *
     */
    public static function mod_zatukattempts_parameters() {
        return new external_function_parameters (
            [
                'moduleid' => new external_value(PARAM_INT, 'moduleid'),
                'courseid' => new external_value(PARAM_INT, 'courseid'),
                'duration' => new external_value(PARAM_RAW, 'whether to return courses that the user can see
                													even if is not enroled in. This requires the parameter courseids
                													to not be empty.', VALUE_DEFAULT, false),
                'currenttime' => new external_value(PARAM_RAW, 'whether to return courses that the user can see
                													even if is not enroled in. This requires the parameter courseids
                													to not be empty.', VALUE_DEFAULT, false),
                'event' => new external_value(PARAM_RAW, 'whether to return courses that the user can see
                													even if is not enroled in. This requires the parameter courseids
                													to not be empty.', VALUE_DEFAULT, false),
            ]
        );
    }
    /**
     * Inserting and Updating data into database
     * @param int $moduleid - Course module id
     * @param int $courseid - course id
     * @param int $duration - Length of the video
     * @param int $currenttime - shows the current time when event triggered(pause)
     * @param int $event - sending static values like play/pause/completed
     * @return Update the data into database
     */
    public static function mod_zatukattempts($moduleid, $courseid=null, $duration=null, $currenttime=null, $event=null) {
        global $DB, $USER;
        $context = context_module::instance($moduleid);
        $course = get_course($courseid);
        $cm = get_coursemodule_from_id('zatuk', $moduleid);
        $zatuk = $DB->get_record('zatuk', ['id' => $cm->instance]);
        if (!is_siteadmin() && has_capability('mod/zatuk:create', $context)) {
            $percentage = $DB->get_field_sql("SELECT percentage FROM {zatuk_attempts}
                                             where moduleid = ".$moduleid." AND userid =".$USER->id." ORDER BY id desc");
            if ($percentage == '' || $percentage == '100') {
                if ($event == 'play') {
                    $attempt = $DB->get_field_sql("SELECT attempt FROM {zatuk_attempts}
                                                  where moduleid = ".$moduleid." AND userid =".$USER->id." ORDER BY id desc");
                    try {
                        $data = new stdclass();
                        $data->moduleid = $moduleid;
                        $data->courseid = $courseid;
                        $data->userid = $USER->id;
                        $data->timecreated = time();
                        $data->duration = $duration;
                        if ($attempt == '') {
                            $data->attempt = '1';
                        } else {
                            $data->attempt = ++$attempt;
                        }
                        $data->usercreated = $USER->id;
                        $status['recordid'] = $DB->insert_record('zatuk_attempts', $data);
                        $params = [
                            'context' => $context,
                            'objectid' => $status['recordid'],
                        ];
                        $eventcheck = \mod_zatuk\event\zatuk_played::create($params);
                        $eventcheck->trigger();
                        return $status;
                    } catch (\Exception $e) {
                        $error = true;
                        $report = 'Message: ' .$e->getMessage();
                    }
                } else {
                    $status['recordid'] = '1';
                    return $status;
                }
            } else if ($event == 'pause') {
                try {
                    $record = $DB->get_record_sql("SELECT id, attempt FROM {zatuk_attempts}
                                                  where moduleid = ".$moduleid." AND userid =".$USER->id." ORDER BY id desc");
                    $data = new stdclass();
                    $data->id = $record->id;
                    $pauselog = ($currenttime / $duration) * 100;
                    $data->percentage = $pauselog;
                    $data->completedduration = $currenttime;
                    $data->last_accessed = time();
                    $data->timemodified = time();
                    $DB->update_record('zatuk_attempts', $data);
                    $status['recordid'] = $data->id;
                    $params = [
                        'context' => $context,
                        'objectid' => $status['recordid'],
                    ];
                    if ($currenttime == $duration) {
                        $completion = new completion_info($course);
                        if ($completion->is_enabled($cm) && $zatuk->completionvideoenabled) {
                            $completion->update_state($cm, COMPLETION_COMPLETE);
                        }
                        $eventcheck = \mod_zatuk\event\video_completed::create($params);
                    } else {
                        $eventcheck = \mod_zatuk\event\zatuk_paused::create($params);
                    }
                    $eventcheck->trigger();
                    return $status;
                } catch (\Exception $e) {
                    $error = true;
                    $report = 'Message: ' .$e->getMessage();
                }
            } else {
                $duration = $DB->get_field_sql("SELECT completedduration FROM {zatuk_attempts}
                where moduleid = ".$moduleid." AND userid =".$USER->id." ORDER BY id desc");
                $status['recordid'] = $duration;
                $params = [
                    'context' => $context,
                    'objectid' => $status['recordid'],
                ];
                $eventcheck = \mod_zatuk\event\zatuk_played::create($params);
                $eventcheck->trigger();
                return $status;
            }
        }

    }


    /**
     * Describes the parameters for get_zatukvideos_by_courses.
     *
     * @return external_function_parameters
     */
    public static function get_zatuk_by_courses_parameters() {
        return new external_function_parameters (
            [
                'courseids' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'Course id'),
                    'Array of course ids', VALUE_DEFAULT, []
                ),
            ]
        );
    }

    /**
     * Returns a list of zatukvideos in a provided list of courses.
     * If no list is provided all zatukvideos that the user can view will be returned.
     *
     * @param array $courseids course ids
     * @return array of warnings and zatukvideos
     */
    public static function get_zatuk_by_courses($courseids = []) {

        $warnings = [];
        $returnedzatukvideos = [];

        $params = [
            'courseids' => $courseids,
        ];
        $params = self::validate_parameters(self::get_zatuk_by_courses_parameters(), $params);

        $mycourses = [];
        if (empty($params['courseids'])) {
            $mycourses = enrol_get_my_courses();
            $params['courseids'] = array_keys($mycourses);
        }

        // Ensure there are courseids to loop through.
        if (!empty($params['courseids'])) {

            list($courses, $warnings) = external_util::validate_courses($params['courseids'], $mycourses);

            // Get the zatukvideos in this course, this function checks users visibility permissions.
            // We can avoid then additional validate_context calls.
            $zatukvideos = get_all_instances_in_courses("zatuk", $courses);
            foreach ($zatukvideos as $zatuk) {
                $context = context_module::instance($zatuk->coursemodule);
                // Entry to return.
                $zatuk->name = external_format_string($zatuk->name, $context->id);

                $options = ['noclean' => true];
                list($zatuk->intro, $zatuk->introformat) =
                    external_format_text($zatuk->intro, $zatuk->introformat, $context->id, 'mod_zatuk', 'intro', null, $options);
                $zatuk->introfiles = external_util::get_area_files($context->id, 'mod_zatuk', 'intro', false, false);

                $returnedzatukvideos[] = $zatuk;
            }
        }

        $result = [
            'zatukvideos' => $returnedzatukvideos,
            'warnings' => $warnings,
        ];
        return $result;
    }

    /**
     * Describes the get_urls_by_courses return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_zatuk_by_courses_returns() {
        return new external_single_structure(
            [
                'zatukvideos' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'id' => new external_value(PARAM_INT, 'Module id'),
                            'coursemodule' => new external_value(PARAM_INT, 'Course module id'),
                            'course' => new external_value(PARAM_INT, 'Course id'),
                            'name' => new external_value(PARAM_RAW, 'URL name'),
                            'intro' => new external_value(PARAM_RAW, 'Summary'),
                            'introformat' => new external_format_value('intro', 'Summary format'),
                            'externalurl' => new external_value(PARAM_RAW_TRIMMED, 'External URL'),
                            'display' => new external_value(PARAM_INT, 'How to display the url'),
                            'displayoptions' => new external_value(PARAM_RAW, 'Display options (width, height)'),
                            'parameters' => new external_value(PARAM_RAW, 'Parameters to append to the URL'),
                            'timemodified' => new external_value(PARAM_INT, 'Last time the url was modified'),
                            'section' => new external_value(PARAM_INT, 'Course section id'),
                            'visible' => new external_value(PARAM_INT, 'Module visibility'),
                            'groupmode' => new external_value(PARAM_INT, 'Group mode'),
                            'groupingid' => new external_value(PARAM_INT, 'Grouping id'),
                        ]
                    )
                ),
                'warnings' => new external_warnings(),
            ]
        );
    }
    /**
     * function view_zatuk_parameters
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
     * function tablecontentblock_parameters
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
     * @param array $args
     */
    public static function tablecontentblock($args) {
        global $PAGE;
        require_login();
        $PAGE->set_context(\context_system::instance());
        $renderer = $PAGE->get_renderer('mod_zatuk');
        $params = json_decode($args);

        $zatuk = new \mod_zatuk\zatuk();

        if ($params->args->action == "updatePreferences") {
            $countonly = true;
        } else {
            $countonly = false;
        }
        $zatukdata = $zatuk->uploadedvideodata($search, $params->args, $countonly);

        if ($zatukdata['length'] <= 0 ) {
            throw new moodle_exception('nodata', 'mod_zatuk');
        }

        return $zatukdata;

    }
    /**
     * function tablecontentblock
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
     * function delete_video_parameters
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
        if (is_siteadmin() && has_capability('mod/zatuk:deletevideo', $systemcontext)) {
            $zatuk = new \mod_zatuk\zatuk();
            return $zatuk->delete_uploaded_video($id);
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
            return false;
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
     * function delete_video_returns
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
        try {
            $dataobj = new stdClass();
            $dataobj->id = $DB->get_field('zatuk_uploaded_videos', 'id', ['videoid' => $videoid], MUST_EXIST);
            $dataobj->zatukurl = $zatukurl;
            if (is_siteadmin() && has_capability('mod/zatuk:editvideo', $systemcontext)) {
                $DB->update_record('zatuk_uploaded_videos', $dataobj);
                return true;
            } else {
                throw new moodle_exception('actionpermission', 'mod_zatuk');
                return false;
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
     * function move_tozatuk_parameters
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
        if (is_siteadmin() && has_capability('mod/zatuk:deletevideo', $systemcontext)) {
            $uploader = new \mod_zatuk\lib\uploader();
            $uploader->videossyncbyid($id);
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
            return false;
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
     * function validatezatukinstance_parameters
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
        if (is_siteadmin() && has_capability('mod/zatuk:manageactions', $systemcontext)) {
            return true;
        } else {
            throw new moodle_exception('actionpermission', 'mod_zatuk');
            return false;
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

