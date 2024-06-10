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
 * zatuk module upgrade code
 *
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * upgrade this assignment instance - this function could be skipped but it will be needed later
 * @param int $oldversion The old version of the assign module
 * @return bool
 */
function xmldb_zatuk_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016052307) {
        $table = new xmldb_table('zatuk');
        $field = new xmldb_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2016052307, 'zatuk');
    }

    if ($oldversion < 2021031618) {
        $table = new xmldb_table('zatuk');
        $field = new xmldb_field('duration', XMLDB_TYPE_CHAR, '255', null, null, null, null);

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2021031618, 'zatuk');
    }
    if ($oldversion < 2016052311) {
        $table = new xmldb_table('zatuk_zatuk_uploaded_videos');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('organization', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
            $table->add_field('videoid', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('tags', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('filename', XMLDB_TYPE_CHAR, '255', null, null, null, '0');
            $table->add_field('filepath', XMLDB_TYPE_CHAR, '255', null, null, null, '0');
            $table->add_field('thumbnail', XMLDB_TYPE_CHAR, '255', null, null, null, '0');
            $table->add_field('organisationname', XMLDB_TYPE_CHAR, '255', null, null, null, '0');
            $table->add_field('tagsname', XMLDB_TYPE_CHAR, '255', null, null, null, '0');
            $table->add_field('status', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
            $table->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $result = $dbman->create_table($table);
        }
        upgrade_mod_savepoint(true, 2016052311, 'zatuk');
    }
    if ($oldversion < 2016052312) {
        $table = new xmldb_table('zatuk_zatuk_uploaded_videos');
        $field = new xmldb_field('uploaded_on', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2016052312, 'zatuk');
    }
    if ($oldversion < 2016052313) {
        $table = new xmldb_table('zatuk_uploaded_videos');
        $field = new xmldb_field('zatukurl', XMLDB_TYPE_CHAR, '255', null, null, null, '0');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2016052313, 'zatuk');
    }

    if ($oldversion < 2021031619) {
        $table = new xmldb_table('zatuk_recordings');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('type', XMLDB_TYPE_CHAR, '55', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('activityid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
            $table->add_field('videoid', XMLDB_TYPE_CHAR, '155', null, null, null, '0');
            $table->add_field('recordon', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
            $table->add_field('filepath', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('filename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('status', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $result = $dbman->create_table($table);
        }
        upgrade_mod_savepoint(true, 2021031619, 'zatuk');
    }

    if ($oldversion < 2021031623.38) {
        $table = new xmldb_table('zatuk_zatuk_uploaded_videos');
        $field = new xmldb_field('public', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2021031623.38, 'zatuk');
    }

    if ($oldversion < 2021031623.62) {
        $table = new xmldb_table('zatuk');
        $field = new xmldb_field('settings', XMLDB_TYPE_TEXT, null, null, null, null, '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2021031623.62, 'zatuk');
    }

    if ($oldversion < 2021031623.63) {

        $dbman = $DB->get_manager();
        $tablea = new xmldb_table('zatuk');

        $fielda = new xmldb_field('settings', XMLDB_TYPE_TEXT, null, null, null, null, '0');

        if ($dbman->field_exists($tablea, $fielda)) {
            $dbman->drop_field($tablea, $fielda);
        }
    }

    if ($oldversion < 2021031623.64) {
        $table = new xmldb_table('zatuk');
        $field = new xmldb_field('enableratings', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field1 = new xmldb_field('enableanalytics', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        $field2 = new xmldb_field('enablelikes', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        upgrade_mod_savepoint(true, 2021031623.64, 'zatuk');
    }
    if ($oldversion < 2021031623.80) {
        $table = new xmldb_table('zatuk');
        $field = new xmldb_field('completionvideoenabled', XMLDB_TYPE_INTEGER, 2, null, null, null, '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2021031623.80, 'zatuk');
    }

    if ($oldversion < 2021031623.84) {
        $table = new xmldb_table('zatuk');
        $field = new xmldb_field('enableratings', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $field1 = new xmldb_field('enableanalytics', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        $field2 = new xmldb_field('enablelikes', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        if ($dbman->field_exists($table, $field2)) {
            $dbman->drop_field($table, $field2);
        }

        $liketable = new xmldb_table('zatuk_like');
        if ($dbman->table_exists($liketable)) {
            $dbman->drop_table($liketable);
        }
        $zatukratingslikes = new xmldb_table('zatuk_ratings_likes');
        if ($dbman->table_exists($zatukratingslikes)) {
            $dbman->drop_table($zatukratingslikes);
        }

        $zatukcomment = new xmldb_table('zatuk_comment');
        if ($dbman->table_exists($zatukcomment)) {
            $dbman->drop_table($zatukcomment);
        }

        $zatukrating = new xmldb_table('zatuk_rating');
        if ($dbman->table_exists($zatukrating)) {
            $dbman->drop_table($zatukrating);
        }
        upgrade_mod_savepoint(true, 2021031623.84, 'zatuk');
    }
    if ($oldversion < 2021031623.86) {
        $table = new xmldb_table('zatuk_uploaded_videos');
        $field = new xmldb_field('thumbnail', XMLDB_TYPE_CHAR, '255', null, null, null, '0');

        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2021031623.86, 'zatuk');
    }
    return true;
}


