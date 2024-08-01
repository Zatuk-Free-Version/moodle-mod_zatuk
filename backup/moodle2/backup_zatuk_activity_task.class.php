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
 * Defines backup_zatuk_activity_task class
 *
 * @since      Moodle 2.0
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/mod/zatuk/backup/moodle2/backup_zatuk_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the content instance
 */
class backup_zatuk_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the content.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_zatuk_activity_structure_step('zatuk_structure', 'zatuk.xml'));
    }

    /**
     * Encodes URLs to the index.php, view.php and discuss.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    public static function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot.'/mod/zatuk', '#');

        // Access a list of all links in a course.
        $pattern = '#('.$base.'/index\.php\?id=)([0-9]+)#';
        $replacement = '$@ZATUKINDEX*$2@$';
        $content = preg_replace($pattern, $replacement, $content);

        // Access the link supplying a course module id.
        $pattern = '#('.$base.'/view\.php\?id=)([0-9]+)#';
        $replacement = '$@SZATUKVIEWBYID*$2@$';
        $content = preg_replace($pattern, $replacement, $content);

        // Access the link supplying an instance id.
        $pattern = '#('.$base.'/view\.php\?u=)([0-9]+)#';
        $replacement = '$@ZATUKVIEWBYU*$2@$';
        $content = preg_replace($pattern, $replacement, $content);

        return $content;
    }
}
