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
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/zatuk/locallib.php');

/**
 * Privacy API implementation for the zatuk activity module.
 *
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\core_userlist_provider,
        \core_privacy\local\request\user_preference_provider,
        \core_privacy\local\request\plugin\provider {
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

    /**
     * Get the list of contexts that contain personal data for the specified user.
     * @param int $userid ID of the user.
     * @return contextlist List of contexts containing the user's personal data.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {

        $contextlist = new contextlist();

        return $contextlist;
    }

    /**
     * Get the list of users within a specific context.
     *
     * @param userlist $userlist To be filled list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();

        if (!$context instanceof \context_module) {
            return;
        }

        $params = [
            'instanceid' => $context->instanceid,
            'module' => 'zatuk',
        ];
        // One query to fetch them all, one query to find them, one query to bring them all and into the userlist add them.
    }

    /**
     * Export personal data stored in the given contexts.
     *
     * @param approved_contextlist $contextlist List of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
    }

    /**
     * Export user preferences controlled by this plugin.
     *
     * @param int $userid ID of the user we are exporting data for
     */
    public static function export_user_preferences(int $userid) {
    }


    /**
     * Export all user's submissions and example submissions he/she created in the given contexts.
     *
     * @param approved_contextlist $contextlist List of contexts approved for export.
     */
    protected static function export_submissions(approved_contextlist $contextlist) {
    }

    /**
     * Export all assessments given by the user.
     *
     * @param approved_contextlist $contextlist List of contexts approved for export.
     */
    protected static function export_assessments(approved_contextlist $contextlist) {
    }
    /**
     * Delete personal data for all users in the context.
     *
     * @param \context $context Context to delete personal data from.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
    }

    /**
     * Delete personal data for the user in a list of contexts.
     *
     * Removing assessments of submissions from the zatuk is not trivial. Removing one user's data can easily affect
     * other users' grades and completion criteria. So we replace the non-essential contents with a "deleted" message,
     * but keep the actual info in place. The argument is that one's right for privacy should not overweight others'
     * right for accessing their own personal data and be evaluated on their basis.
     *
     * @param approved_contextlist $contextlist List of contexts to delete data from.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
    }

    /**
     * Delete personal data for multiple users within a single zatuk context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
    }

    /**
     * Get the user preferences.
     *
     * @return array List of user preferences
     */
    protected static function get_user_prefs(): array {
        return [];
    }
}
