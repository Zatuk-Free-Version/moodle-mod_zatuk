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

 require(__DIR__.'/../../config.php');

global $OUTPUT, $PAGE;
$id = required_param('id', PARAM_INT); // Course Id.

$course = get_course($id);
require_course_login($course, false);

$context = context_course::instance($course->id);
require_capability('mod/zatuk:managezatukactivity', $context);

$pagetitle = get_string('zatukuploadedvideos', 'mod_zatuk');
$pageurl = new moodle_url('/mod/zatuk/index.php', ['id' => $course->id]);
$PAGE->set_pagetype('mod-zatuk-incourse');
$PAGE->add_body_classes(['limitedwidth']);
$PAGE->set_context($context);
$PAGE->set_url($pageurl);
$PAGE->set_title($pagetitle);
$PAGE->set_heading(format_string($course->fullname, true, ['context' => $context]));


$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'init');
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'registerSelector');
$PAGE->requires->js_call_amd('mod_zatuk/upload', 'init');
$PAGE->requires->js_call_amd('mod_zatuk/renderzatuk', 'init');

$isrepositoryenabled = (new \repository_zatuk\video_service)->isrepositoryenabled();
$apikey = trim(get_config('repository_zatuk', 'zatuk_key'));

if (!$isrepositoryenabled || !$apikey) {
    if (is_siteadmin()) {
        redirect(new moodle_url($CFG->wwwroot .'/admin/repository.php'));
    } else {
        redirect(new moodle_url($CFG->wwwroot));
    }
} else {
    \core\notification::add(get_string('zatukusersuggestmessage', 'mod_zatuk'), \core\notification::INFO);
}

echo $OUTPUT->header();
$uploadedvideos = new \mod_zatuk\output\uploadedvideos($context);
$zatukoutput = $PAGE->get_renderer('mod_zatuk');
echo $zatukoutput->render($uploadedvideos);

echo $OUTPUT->footer();
