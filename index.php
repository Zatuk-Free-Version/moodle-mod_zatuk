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
 * Displays information about all uploaded videos.
 *
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('../../course/format/lib.php');
global $OUTPUT, $PAGE;
use context_system;
require_login();
$systemcontext = context_system::instance();
require_capability('mod/zatuk:viewuploadedvideo', context_system::instance());
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'init', ['[data-region="zatuk-list-container"]', 10]);
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'registerSelector');
$PAGE->requires->js_call_amd('mod_zatuk/upload', 'init');
$PAGE->requires->js_call_amd('mod_zatuk/renderzatuk', 'init');
$PAGE->set_url('/mod/zatuk/index.php');
$PAGE->set_context($systemcontext);
$PAGE->add_body_classes(['limitedwidth']);
$PAGE->set_title(get_string('zatukuploadedvideos', 'mod_zatuk'));
$PAGE->set_heading(get_string('zatukuploadedvideos', 'mod_zatuk'));
$isrepositoryenabled = (new \repository_zatuk\video_service)->isrepositoryenabled();
if (!$isrepositoryenabled) {
    if (is_siteadmin()) {
        redirect(new moodle_url($CFG->wwwroot .'/admin/repository.php'));
    } else {
        redirect(new moodle_url($CFG->wwwroot));
    }

} else {
    \core\notification::add(get_string('zatukusersuggestmessage', 'mod_zatuk'), \core\notification::INFO);
}
echo $OUTPUT->header();
$uploadedvideos = new \mod_zatuk\output\uploadedvideos($systemcontext);
$zatukoutput = $PAGE->get_renderer('mod_zatuk');
echo $zatukoutput->render($uploadedvideos);

echo $OUTPUT->footer();
