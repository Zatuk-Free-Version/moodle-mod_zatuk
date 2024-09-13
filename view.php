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
 * zatuk module main user interface
 *
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once("$CFG->dirroot/mod/zatuk/lib.php");
require_once("$CFG->dirroot/mod/zatuk/locallib.php");
use moodle_url;
use completion_info;
$id = optional_param('id', 0, PARAM_INT);
$u  = optional_param('u', 0, PARAM_INT);
global $DB, $PAGE, $OUTPUT;
if ($u) {
    $zatuk = $DB->get_record('zatuk', ['id' => $u], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('zatuk', $zatuk->id, $zatuk->course, false, MUST_EXIST);

} else {
    $cm = get_coursemodule_from_id('zatuk', $id, 0, false, MUST_EXIST);
    $zatuk = $DB->get_record('zatuk', ['id' => $cm->instance], '*', MUST_EXIST);
}

$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$context = context_module::instance($cm->id);

require_course_login($course, true, $cm);
require_capability('mod/zatuk:view', $context);
zatuk_view($zatuk, $course, $cm, $context);
$PAGE->set_url('/mod/zatuk/view.php', ['id' => $cm->id]);

$PAGE->set_pagelayout('incourse');
$PAGE->set_title($course->shortname.': '.$zatuk->name);
$PAGE->set_heading($zatuk->name);
$PAGE->requires->jquery();
$params = json_encode(['identifier' => 'my_video_1', 'src' => $zatuk->externalurl, 'cm' => $cm->id, 'course' => $cm->course]);
$PAGE->requires->js_call_amd('mod_zatuk/player', 'load', [$params]);
// Completion.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);
   echo $OUTPUT->header();
    $params = [
        'context' => $context,
        'objectid' => $cm->id,
    ];
    $event = \mod_zatuk\event\zatuk_instance_viewed::create($params);
    $event->trigger();
    $exturl = trim($zatuk->externalurl);
    if (empty($exturl) || $exturl === 'http://') {
        notice(get_string('invalidstoredurl', 'zatuk'), new moodle_url('/course/view.php', ['id' => $cm->course]));
        die;
    }
    unset($exturl);
    zatuk_view($zatuk, $course, $cm, $context);
    $player = new mod_zatuk\output\player($cm);
    echo $OUTPUT->render($player);
    echo $OUTPUT->footer();

