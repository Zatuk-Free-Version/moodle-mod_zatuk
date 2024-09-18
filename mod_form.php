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
 * This file contains the forms to create and edit an instance of this module
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/course/moodleform_mod.php');
use mod_zatuk\zatuk_constants as zc;
/**
 * mod_zatuk_mod_form
 */
class mod_zatuk_mod_form extends moodleform_mod {
    /**
     * Defines the zatuk instance configuration form
     *
     * @return void
     */
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        require_once($CFG->dirroot.'/repository/lib.php');

        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), ['size' => zc::MOD_FORM_NAME_SIZE]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', zc::ELEMENTMAXSIZE), 'maxlength', zc::ELEMENTMAXSIZE, 'client');

        $mform->addElement('hidden', 'externalurl', '', ['id' => 'zatuk_external_url']);
        $mform->setType('externalurl', PARAM_URL);
        $mform->addElement('hidden', 'duration', '', ['id' => 'zatuk_duration']);
        $mform->setType('duration', PARAM_TEXT);
        $mform->addElement('hidden', 'videoid', '', ['id' => 'zatuk_external_videoid']);
        $mform->setType('videoid', PARAM_INT);

        $html = mod_zatuk_get_browsevideo_form_html($mform);
        $mform->addElement('html',  $html);

        $this->standard_intro_elements();
        $element = $mform->getElement('introeditor');
        $attributes = $element->getAttributes();
        $attributes['rows'] = zc::STATUSE;
        $element->setAttributes($attributes);

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
    /**
     * Validates the form input
     *
     * @param array $data submitted data
     * @param array $files submitted files
     * @return array eventual errors indexed by the field name
     */
    public function validation($data, $files) {
        global $PAGE, $CFG;
        require_once($CFG->dirroot.'/mod/zatuk/locallib.php');
        $errors = parent::validation($data, $files);
        if (empty($data['externalurl'])) {
            $errors['videoid'] = get_string('required');
            echo $PAGE->requires->js_call_amd('mod_zatuk/videomissing', 'init');
        }
        if (!empty($data['externalurl'])) {
            $url = $data['externalurl'];

            if (preg_match('|^[a-z]+://|i', $url) || preg_match('|^https?:|i', $url) || preg_match('|^ftp:|i', $url)) {
                // Normal URL.
                if (!zatuk_appears_valid_url($url)) {
                    $errors['externalurl'] = get_string('invalidurl', 'zatuk');
                }

            } else {
                if (!preg_match('|^/|', $url) && !preg_match('|^[a-z]+:|i', $url)) {
                    if (!zatuk_appears_valid_url('http://'.$url)) {
                        $errors['externalurl'] = get_string('invalidurl', 'zatuk');
                    }

                }
            }
        }
        return $errors;
    }
    /**
     * Return submitted data if properly submitted or returns NULL if validation fails or
     * if there is no submitted data.
     *
     * Do not override this method, override data_postprocessing() instead.
     *
     * @return object submitted data; NULL if not valid or not submitted or cancelled
     */
    public function get_data() {
        $data = parent::get_data();
        if (!$data) {
            return $data;
        }

        return $data;
    }
}
