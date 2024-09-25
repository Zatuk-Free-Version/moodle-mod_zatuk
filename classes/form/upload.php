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
 * mod_zatuk video upload form
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_zatuk\form;

use core_form\dynamic_form;
use moodle_url;
use context;
use context_system;
use mod_zatuk\zatuk as mz;
use mod_zatuk\zatuk_constants as zc;

/**
 * class upload
 */
class upload extends dynamic_form {
    /**
     *
     * Define this form - called by the parent constructor
     */
    public function definition() {
        global $CFG;
        $mform = $this->_form;
        $id = $this->optional_param('id', 0, PARAM_INT);

        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'title', get_string('title', 'mod_zatuk'));
        $mform->addHelpButton('title', 'titlehelp', 'mod_zatuk');
        $mform->setType('title', PARAM_RAW);
        $mform->addRule('title', get_string('required'), 'required', null, 'client');

        if ((int)$id <= zc::DEFAULTSTATUS || is_null($id)) {

            $videoformats = ['accepted_types' => zc::ACCEPTED_VIDEO_TYPES];
            $mform->addElement('filepicker', 'filepath', get_string('filepath', 'mod_zatuk'), null, $videoformats);
            $mform->addHelpButton('filepath', 'filepathhelp', 'mod_zatuk');
            $mform->addRule('filepath', get_string('filepathrequired', 'mod_zatuk'), 'required', null, 'client');
        }
        $pstring = get_string('public', 'mod_zatuk');
        $mform->addElement('checkbox', 'public', $pstring, null, [zc::DEFAULTSTATUS, zc::STATUSA]);
        $mform->addHelpButton('public', 'publichelp', 'mod_zatuk');
        $mform->setType('public', PARAM_BOOL);

        $mform->addElement('editor', 'description', get_string('videodescription', 'mod_zatuk'));
        $mform->addHelpButton('description', 'descriptionhelp', 'mod_zatuk');
        $mform->setType('description', PARAM_RAW);

    }

    /**
     * Validate incoming data.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;

    }
    /**
     * Returns form context
     *
     * If context depends on the form data, it is available in $this->_ajaxformdata or
     * by calling $this->optional_param()
     *
     * @return \context
     */
    protected function get_context_for_dynamic_submission(): context {
        return context_system::instance();
    }

    /**
     * Check if current user has access to this form, otherwise throw exception
     *
     * Sometimes permission check may depend on the action and/or id of the entity.
     * If necessary, form data is available in $this->_ajaxformdata or
     * by calling $this->optional_param()
     * @return void
     */
    protected function check_access_for_dynamic_submission(): void {

        is_siteadmin() || has_capability('mod/zatuk:addinstance', $this->get_context_for_dynamic_submission());

    }
    /**
     * Process the form submission, used if form was submitted via AJAX
     *
     * This method can return scalar values or arrays that can be json-encoded, they will be passed to the caller JS.
     *
     * Submission data can be accessed as: $this->get_data()
     *
     * @return mixed
     */
    public function process_dynamic_submission() {
        $data = $this->get_data();
        $systemcontext = context_system::instance();
        $context = context::instance_by_id($systemcontext->id, MUST_EXIST);
        if (!empty($data)) {
            if ((int)$data->id <= zc::DEFAULTSTATUS || is_null($data->id)) {
                $id = (new mz)->add_zatuk_content($data);
                $this->save_stored_file('filepath', $context->id, 'mod_zatuk', 'video',  $id);
            } else {
                $id = (new mz)->update_zatuk_content($data);
            }
            if ($id) {
                $params = [
                'context' => $context,
                'objectid' => $id,
                ];
                $event = \mod_zatuk\event\video_uploaded::create($params);
                $event->trigger();
            }
        }
    }
    /**
     * Load in existing data as form defaults
     *
     * Can be overridden to retrieve existing values from db by entity id and also
     * to preprocess editor and filemanager elements
     *
     * Example:
     *     $this->set_data(get_entity($this->_ajaxformdata['id']));
     */
    public function set_data_for_dynamic_submission(): void {
        if ($id = $this->optional_param('id', 0, PARAM_INT)) {
            $setdata = (new mz)->set_data($id);
            $this->set_data($setdata);
        }
    }
    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * This is used in the form elements sensitive to the page url, such as Atto autosave in 'editor'
     *
     * If the form has arguments (such as 'id' of the element being edited), the URL should
     * also have respective argument.
     *
     * @return \moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        $id = $this->optional_param('id', 0, PARAM_INT);
        return new moodle_url('/mod/zatuk/index.php');
    }

}
