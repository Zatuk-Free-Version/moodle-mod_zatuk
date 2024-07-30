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
 * Administration settings definitions for the zatuk module.
 *
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
global $ADMIN, $PAGE, $CFG;
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', false);
}
if (!defined('WS_SERVER')) {
    define('WS_SERVER', false);
}
if (!CLI_SCRIPT && !WS_SERVER) {
    if ($ADMIN->fulltree) {
        $urlparam = (object)$PAGE->url->params();
        if (!empty($urlparam)) {
            if (isset($urlparam->section) &&  $urlparam->section == 'modsettingzatuk') {
                return redirect($CFG->wwwroot.'/mod/zatuk/index.php');
            }
        }
    }
}


