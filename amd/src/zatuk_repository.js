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
 * Defines zatuk repository script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {

    var DEFAULT_LIMIT = 10;
    var uploadedVideos = function(args) {
        // if (!args.hasOwnProperty('limit')) {
            args.limit = DEFAULT_LIMIT;
        // }
        args.action = 'zatuk_uploaded_videos_data';
        // args.lastId = args.aftereventid;
        let arg = {args: JSON.stringify({args})};
        // delete args.limit;

        var request = {
            methodname: 'mod_zatuk_blocktablecontent',
            args: arg
        };
        var promise = Ajax.call([request])[0];
        return promise;
    };
    var updatePreferences = function(args) {
        // if (!args.hasOwnProperty('limit')) {
            // This is intentionally smaller than the default limit.
            args.limit = 10;
        // }
        args.action = 'updatePreferences';
        args.limitnum = args.limit;
        delete args.limit;
        let arg = {args: JSON.stringify({args})};
        var request = {
            methodname: 'mod_zatuk_blocktablecontent',
            args: arg
        };
        var promise = Ajax.call([request])[0];
        promise.fail(Notification.exception);
        return promise;
    };
    return {
        uploadedVideos: uploadedVideos,
        updatePreferences: updatePreferences
    };
});
