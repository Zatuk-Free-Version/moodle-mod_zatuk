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
use moodle_url;
global $OUTPUT, $PAGE;
$courseid = required_param('courseid', PARAM_INT);
require_login();
$systemcontext = context_system::instance();
require_capability('mod/zatuk:viewuploadedvideo', context_system::instance());
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'init', ['[data-region="zatuk-list-container"]', 10]);
$PAGE->requires->js_call_amd('mod_zatuk/zatukcontent', 'registerSelector');
$PAGE->requires->js_call_amd('mod_zatuk/upload', 'init');
$PAGE->requires->js_call_amd('mod_zatuk/renderzatuk', 'init');
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$pageurl = new moodle_url('/mod/zatuk/index.php', ['courseid' => $courseid]);
$PAGE->set_url($pageurl);
$PAGE->set_pagelayout('course');
$PAGE->add_body_class('limitedwidth');
$format = course_get_format($course);
$course->format = $format->get_format();

$PAGE->set_pagetype('course-view-' . $course->format);
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_course(get_course($courseid));

echo $OUTPUT->header();
    $uploadedvideos = new \mod_zatuk\output\uploadedvideos($systemcontext);
    $zatukoutput = $PAGE->get_renderer('mod_zatuk');
    echo $zatukoutput->render($uploadedvideos);
echo $OUTPUT->footer();
