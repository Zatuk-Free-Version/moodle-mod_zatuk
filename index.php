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
 * @since     Moodle 2.0
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
use moodle_url;
global $OUTPUT, $PAGE;
require_login();
$systemcontext = context_system::instance();
require_capability('mod/zatuk:view', context_system::instance());
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'init', ['[data-region="zatuk-list-container"]', 10]);
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'registerSelector');
$PAGE->requires->js_call_amd('mod_zatuk/upload', 'init');
$PAGE->requires->js_call_amd('mod_zatuk/renderzatuk', 'init');

$pageurl = new moodle_url('/mod/zatuk/index.php');
$PAGE->set_url($pageurl);
$PAGE->set_pagelayout('standard');
$PAGE->set_context($systemcontext);
$heading = get_string('uploadedvideos', 'mod_zatuk');
$PAGE->set_title($heading);

$PAGE->set_heading($heading);
$PAGE->navbar->add($heading);

echo $OUTPUT->header();
if (is_siteadmin() || has_capability('mod/zatuk:viewuploadedvideo', $systemcontext)) {
    $uploadedvideos = new \mod_zatuk\output\uploadedvideos($systemcontext);
    $zatukoutput = $PAGE->get_renderer('mod_zatuk');
    echo $zatukoutput->render($uploadedvideos);
} else {
    throw new \moodle_exception(get_string('nopermissions', 'mod_zatuk'));
}
echo $OUTPUT->footer();
