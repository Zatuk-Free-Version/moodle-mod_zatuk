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
 * This file contains the moodle hooks for the zatuk module.
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * function mod_zatuk_pluginfile
 * @param stdclass $course
 * @param stdclass $cm
 * @param stdclass $context
 * @param string||null $filearea
 * @param array $args
 * @param string||null $forcedownload
 * @param array $options
 */
function mod_zatuk_pluginfile($course,
                            $cm,
                            $context,
                            $filearea,
                            $args,
                            $forcedownload,
                            array $options=[]) {
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'mod_zatuk', $filearea, $args[0], '/', $args[1]);
    if ($file) {
        send_stored_file($file, 0, 0, true, $options); // Download MUST be forced - security.
    } else {
        return false;
    }

}

/**
 * Supported features
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function zatuk_supports($feature) {
    switch($feature) {
        case FEATURE_COMPLETION_TRACKS_VIEWS :
        case FEATURE_COMPLETION_HAS_RULES :
        case FEATURE_BACKUP_MOODLE2 :
        return true;
        default :
        return null;
    }
}
/**
 * List the actions that correspond to a post of this module.
 * This is used by the participation report.
 *
 * Note: This is not used by new logging system. Event with
 *       crud = ('c' || 'u' || 'd') and edulevel = LEVEL_PARTICIPATING
 *       will be considered as post action.
 *
 * @return array
 */
function zatuk_get_post_actions() {
    return ['update', 'add'];
}

/**
 * Add url instance.
 * @param object $data
 * @param object $mform
 * @return int new zatuk instance id
 */
function zatuk_add_instance($data, $mform = null) {
    global $CFG, $DB, $USER;

    require_once($CFG->dirroot.'/mod/zatuk/locallib.php');

    $displayoptions = [];
    $displayoptions['width']  = $data->width;
    $displayoptions['height'] = $data->height;
    $data->displayoptions = json_encode($displayoptions);
    $externalurl = $data->externalurl;
    $data->externalurl = zatuk_fix_submitted_url($externalurl);
    $data->usercreated = $USER->id;
    $data->timecreated = time();
    $data->id = $DB->insert_record('zatuk', $data);

    return $data->id;
}

/**
 * Update url instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function zatuk_update_instance($data, $mform = null) {
    global $CFG, $DB, $USER;
    require_once($CFG->dirroot.'/mod/zatuk/locallib.php');

    $displayoptions = ['width' => $data->width,
                      'height' => $data->height];
    $data->displayoptions = json_encode($displayoptions);
    $externalurl = $data->externalurl;
    $data->externalurl = zatuk_fix_submitted_url($externalurl);
    $data->usermodified = $USER->id;
    $data->timemodified = time();
    $data->id           = $data->instance;

    $DB->update_record('zatuk', $data);

    return true;
}

/**
 * Delete url instance.
 * @param int $id
 * @return bool true
 */
function zatuk_delete_instance($id) {
    global $DB;

    if (!$zatuk = $DB->get_record('zatuk', ['id' => $id])) {
        return false;
    }

    // Note: all context files are deleted automatically.

    $DB->delete_records('zatuk', ['id' => $id]);

    return true;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 *
 * @param object $coursemodule
 */
function zatuk_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;
    require_once("$CFG->dirroot/mod/zatuk/locallib.php");

    $dbparams = ['id' => $coursemodule->instance];
    $fields = 'id, name, display, displayoptions, externalurl, parameters, intro, introformat';
    if (!$zatuk = $DB->get_record('zatuk', $dbparams, $fields)) {
        return false;
    }
    $info = new cached_cm_info();
    $info->name = $zatuk->name;

    // Note: there should be a way to differentiate links from normal resources.
    $info->icon = zatuk_guess_icon($zatuk->externalurl, 24);

    $display = zatuk_get_final_display_type($zatuk);

    if ($coursemodule->showdescription) {
        // Convert intro to html. Do not filter cached version, filters run at display time.
        $info->content = format_module_intro('zatuk', $uzatukrl, $coursemodule->id, false);
    }

    return $info;
}

/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
function zatuk_page_type_list($pagetype, $parentcontext, $currentcontext) {
    $modulepagetype = ['mod-url-*' => get_string('page-mod-url-x', 'url')];
    return $modulepagetype;
}

/**
 * Export URL resource contents
 * @param object $cm
 * @param string||null $baseurl
 */
function zatuk_export_contents($cm, $baseurl) {
    global $CFG, $DB;
    require_once("$CFG->dirroot/mod/zatuk/locallib.php");
    $contents = [];
    $context = context_module::instance($cm->id);

    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $urlrecord = $DB->get_record('zatuk', ['id' => $cm->instance], '*', MUST_EXIST);

    $fullurl = $urlrecord->externalurl;
    $isurl = clean_param($fullurl, PARAM_URL);
    if (empty($isurl)) {
        return null;
    }

    $url = [];
    $url['type'] = get_string('url', 'zatuk');
    $url['filename']     = clean_param(format_string($urlrecord->name), PARAM_FILE);
    $url['filepath']     = null;
    $url['filesize']     = 0;
    $url['fileurl']      = $fullurl;
    $url['timecreated']  = null;
    $url['timemodified'] = $urlrecord->timemodified;
    $url['sortorder']    = null;
    $url['userid']       = null;
    $url['author']       = null;
    $url['license']      = null;
    $contents[] = $url;

    return $contents;
}

/**
 * Register the ability to handle drag and drop file uploads
 * @return array containing details of the files / types the mod can handle
 */
function zatuk_dndupload_register() {
    return ['types' => [
                     ['identifier' => get_string('url', 'zatuk'), 'message' => get_string('createurl', 'url')],
                 ]];
}

/**
 * Handle a file that has been uploaded
 * @param object $uploadinfo details of the file / content that has been uploaded
 * @return int instance id of the newly created mod
 */
function zatuk_dndupload_handle($uploadinfo) {
    // Gather all the required data.
    $data = new stdClass();
    $data->course = $uploadinfo->course->id;
    $data->name = $uploadinfo->displayname;
    $data->intro = '<p>'.$uploadinfo->displayname.'</p>';
    $data->introformat = FORMAT_HTML;
    $data->externalurl = clean_param($uploadinfo->content, PARAM_URL);
    $data->timemodified = time();

    // Set the display options to the site defaults.
    $config = get_config('repository_zatuk');
    $data->display = $config->display;
    $data->popupwidth = $config->popupwidth;
    $data->popupheight = $config->popupheight;
    $data->printintro = $config->printintro;

    return zatuk_add_instance($data);
}

/**
 * Mark the activity completed (if required) and trigger the course_module_viewed event.
 *
 * @param  stdClass $zatuk        url object
 * @param  stdClass $course     course object
 * @param  stdClass $cm         course module object
 * @param  stdClass $context    context object
 * @since Moodle 3.0
 */
function zatuk_view($zatuk, $course, $cm, $context) {
    $params = [
        'context' => $context,
        'objectid' => $zatuk->id,
    ];

    $event = \mod_zatuk\event\course_module_viewed::create($params);
    $event->add_record_snapshot('course_modules', $cm);
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot('zatuk', $zatuk);
    $event->trigger();
}
/**
 * extend an assigment navigation settings
 *
 * @param settings_navigation $settings
 * @param navigation_node $navref
 * @return void
 */
function zatuk_extend_settings_navigation(settings_navigation $settings, navigation_node $navref) {
    global $PAGE, $DB;

    // We want to add these new nodes after the Edit settings node, and before the
    // Locally assigned roles node. Of course, both of those are controlled by capabilities.
    $keys = $navref->get_children_key_list();
    $beforekey = null;
    $i = array_search('modedit', $keys);
    if ($i === false && array_key_exists(0, $keys)) {
        $beforekey = $keys[0];
    } else if (array_key_exists($i + 1, $keys)) {
        $beforekey = $keys[$i + 1];
    }

    $cm = $PAGE->cm;
    if (!$cm) {
        return;
    }

    $context = $cm->context;
    $course = $PAGE->course;

    if (!$course) {
        return;
    }

}
/**
 * function zatuk_extend_navigation_course
 * @param stdclass $navigation
 * @param stdclass $course
 * @param stdclass $context
 */
function zatuk_extend_navigation_course($navigation, $course, $context) {
    global $USER, $DB;
    $getanalyticsetting = $DB->get_record('config_plugins', ['plugin' => 'zatuk', 'name' => 'enableanalytics']);

}
/**
 * function mod_zatuk_get_browsevideo_form_html
 * @param stdclass $mform
 */
function mod_zatuk_get_browsevideo_form_html($mform) {
    global $PAGE, $OUTPUT, $DB, $CFG;
    $cmid = optional_param('update', 0,  PARAM_INT);

    if ($cmid) {
        $extrenalurl = $DB->get_field_sql("SELECT s.externalurl FROM {zatuk} s
                                          JOIN {course_modules} cm on cm.instance = s.id WHERE cm.id = :id ",
                                          ['id' => $cmid]);
        $params = json_encode(['identifier' => 'mod_zatuk_form_video', 'src' => $extrenalurl]);
        $PAGE->requires->js_call_amd('mod_zatuk/player', 'load', [$params]);
        $class = '';
        $straddlink = 'Update video';
    } else {
        $class = 'hidden';
        $straddlink = 'Choose video';
    }

    $clientid = uniqid();
    $args = new stdClass();
    $args->accepted_types = '*';
    $args->return_types = FILE_EXTERNAL;
    $args->context = $PAGE->context;
    $args->client_id = $clientid;
    $args->env = 'filepicker';
    $fp = new file_picker($args);
    $options = $fp->options;
    $zatukingid = array_search('zatuk', array_column($options->repositories, 'type', 'id'));
    if (!$zatukingid) {
        return html_writer::div(get_string('nozatukrepository',
        'mod_zatuk',
        $CFG->wwwroot . '/admin/repository.php'),
        'alert alert-danger');
    }
    $options->repositories = [$zatukingid => $options->repositories[$zatukingid]];

    $module = ['name' => 'zatuk_url', 'fullpath' => '/mod/zatuk/js/zatukurl.js', 'requires' => ['core_filepicker']];
    $PAGE->requires->js_init_call('M.zatuk_url.init', [$options], true, $module);
    $bvdata = [
        'class' => $class,
        'clientid' => $clientid,
        'straddlink' => $straddlink,
        'printoutfilepicker' => $OUTPUT->render($fp),
    ];
    return $OUTPUT->render_from_template('mod_zatuk/browse_video', $bvdata);
}
/**
 * function mod_zatuk_coursemodule_standard_elements
 * @param object $formwrapper
 * @param object $mform
 */
function mod_zatuk_coursemodule_standard_elements($formwrapper, $mform) {
    global $CFG, $COURSE;
    if ($formwrapper->get_current()->modulename != 'zoom') {
        return false;
    }
    $mform->addElement('header', 'zatukingapp', get_string('zatukingapp', 'mod_zatuk'));

    $mform->addElement('checkbox', 'recordsession', get_string('recordsession', 'mod_zatuk'));
}
/**
 * function mod_zatuk_get_api_formdata
 *
 */
function mod_zatuk_get_api_formdata() {
    $zatukobj = new mod_zatuk\zatuk();
    $uploaddata = $zatukobj->zatuklib->get_upload_data();
    return json_decode($uploaddata);
}

