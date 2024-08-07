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
 * Privacy Subsystem implementation for mod_zatuk.
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_zatuk\privacy;

use core_privacy\local\metadata\collection;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/zatuk/locallib.php');

/**
 * Privacy API implementation for the zatuk activity module.
 *
 */
class provider implements
        \core_privacy\local\metadata\provider {
    /**
     * Describe all the places where the zatuk module stores some personal data.
     *
     * @param collection $collection Collection of items to add metadata to.
     * @return collection Collection with our added items.
     */
    public static function get_metadata(collection $collection): collection {

        $collection->add_database_table('zatuk_uploaded_videos', [
            'organization' => 'privacy:metadata:organization',
            'videoid' => 'privacy:metadata:videoid',
            'title' => 'privacy:metadata:title',
            'description' => 'privacy:metadata:description',
            'tags' => 'privacy:metadata:tags',
            'filename' => 'privacy:metadata:filename',
            'filepath' => 'privacy:metadata:filepath',
            'organisationname' => 'privacy:metadata:organisationname',
            'tagsname' => 'privacy:metadata:tagsname',
            'status' => 'privacy:metadata:status',
            'published' => 'privacy:metadata:published',
            'usercreated' => 'privacy:metadata:usercreated',
        ], 'privacy:metadata:zatukuploadvideos');

        $collection->link_subsystem('core_files', 'privacy:metadata:core_files');
        $collection->link_subsystem('core_tag', 'privacy:metadata:core_tag');
        return $collection;
    }
}

