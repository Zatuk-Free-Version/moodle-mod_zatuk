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
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_zatuk\form;
defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/mod/zatuk/lib.php');

use core_form\dynamic_form;
use moodle_url;
use context;
use context_system;
use mod_zatuk\zatuk as tp;

/**
 * class upload
 */
class upload extends dynamic_form {
    /**
     * function definition
     */
    public function definition() {
         global $USER, $CFG, $DB, $PAGE;
        $mform = $this->_form;
        $id = $this->optional_param('id', 0, PARAM_INT);

        $uploaddata = mod_zatuk_get_api_formdata();
        $organisations = (array)$uploaddata->organisations;
        $tags = (array)$uploaddata->tags;

        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'title', get_string('title', 'mod_zatuk'));
        $mform->setType('title', PARAM_RAW);

        if ((int)$id <= 0 || is_null($id)) {

            $videoformats = ['accepted_types' => ['.mp4', '.avi', '.m4v', '.mov']];
            $mform->addElement('filepicker', 'filepath', get_string('filepath', 'mod_zatuk'), null, $videoformats);
            $mform->addHelpButton('filepath', 'filepathhelp', 'mod_zatuk');
            $mform->addRule('filepath', get_string('filepathrequired', 'mod_zatuk'), 'required', null, 'client');
        }
        $pstring = get_string('public', 'mod_zatuk');
        $mform->addElement('checkbox', 'public', $pstring, null, [0, 1]);
        $mform->setType('public', PARAM_BOOL);

        $mform->addElement('header', 'advancedhdr', get_string('advancedfields', 'mod_zatuk'));
        $mform->setExpanded('advancedhdr', false);
        $organizationoptions = [
            'class' => 'organisationnameselect',
            'data-class' => 'organisationselect',
            'multiple' => false,
            'placeholder' => get_string('selectcategory', 'mod_zatuk'),
        ];
        $organisations[0] = get_string('selectcategory', 'mod_zatuk');
        ksort($organisations);

        $mform->addElement('autocomplete', 'organization', get_string('category'), $organisations, $organizationoptions);
        $mform->addHelpButton('organization', 'organisationzatuk', 'mod_zatuk');
        $mform->setType('organization', PARAM_INT);

        $tagsoptions = [
            'class' => 'tagnameselect',
            'data-class' => 'tagselect',
            'multiple' => true,
            'placeholder' => 'Select Tags',
        ];

        $mform->addElement('autocomplete', 'tags', get_string('tags', 'mod_zatuk'), $tags, $tagsoptions);
        $mform->addHelpButton('tags', 'tagszatukhelp', 'mod_zatuk');
        $mform->setType('tags', PARAM_INT);

        $mform->addElement('editor', 'description', get_string('videodescription', 'mod_zatuk'));
        $mform->addHelpButton('description', 'descriptionhelp', 'mod_zatuk');
        $mform->setType('description', PARAM_RAW);

    }

    /**
     * Perform some moodle validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        return $errors;

    }
    /**
     * Returns context where this form is used
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context {
        return context_system::instance();
    }

    /**
     * Checks if current user has access to this form, otherwise throws exception
     */
    protected function check_access_for_dynamic_submission(): void {

    }
    /**
     * process submission
     *
     */
    public function process_dynamic_submission() {
        global $CFG, $DB;

        $data = $this->get_data();
        $systemcontext = context_system::instance();
        $context = context::instance_by_id($systemcontext->id, MUST_EXIST);
        $organisations = (array)json_decode($data->organisationoptions);
        $tags = (array)json_decode($data->tagsoptions);
        $validateddata = $data;
        if (!empty($validateddata)) {
            $zatuk = new tp;
            $id = $zatuk->insert_zatuk_content($validateddata, $tags, $context);
            if ((int)$validateddata->id <= 0 || is_null($validateddata->id)) {
                $this->save_stored_file('filepath', $context->id, 'mod_zatuk', 'video',  $id);
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
     * process set data
     *
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB;

        if ($id = $this->optional_param('id', 0, PARAM_INT)) {
            $setdata = (new tp)->set_data($id);
            $this->set_data($setdata);
        }
    }
    /**
     * process redirect url
     *
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        $id = $this->optional_param('id', 0, PARAM_INT);
        return new moodle_url('/local/zatuk/index.php',
            ['action' => 'uploadvideo', 'id' => $id]);
    }

}
