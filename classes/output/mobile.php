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
 * mod_zatuk mobile class
 *
 * @since     Moodle 2.0
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\output;

use context_module;
/**
 * class mobile
 */
class mobile {
    /**
     * Zatuk View.
     *
     * @param array $args
     * @return array
     */
    public static function zatuk_view($args) {
        global $OUTPUT, $USER, $DB;

        $args = (object) $args;
        $cm = get_coursemodule_from_id('zatuk', $args->cmid);

        // Capabilities check.
        require_login($args->courseid , false , $cm, true, true);

        $context = context_module::instance($cm->id);

        require_capability ('mod/zatuk:view', $context);
        if ($args->userid != $USER->id) {
            require_capability('mod/zatuk:manage', $context);
        }
        $zatuk = $DB->get_record('zatuk', ['id' => $cm->instance]);

        $zatuk->name = format_string($zatuk->name);
        list($zatuk->intro, $zatuk->introformat) =
                        external_format_text($zatuk->intro, $zatuk->introformat, $context->id, 'mod_zatuk', 'intro');
        $data = [
            'zatuk' => $zatuk,
            'cmid' => $cm->id,
            'courseid' => $args->courseid,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_zatuk/zatuk', $data),
                ],
            ],
            'javascript' => '',
            'otherdata' => '',
        ];
    }
}
