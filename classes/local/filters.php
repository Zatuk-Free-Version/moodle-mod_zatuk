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
 * mod_zatuk filters class
 *
 * @since Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\local;
/**
 * class filters
 */
class filters {
    /**
     * function get_rolecourses
     * @param int $userid
     * @param int $role
     * @param int $courseid
     * @param string $concatsql
     * @param string $limitconcat
     * @param string $count
     * @param string $check
     * @param string $datefiltersql
     * @param string $menu
     */
    public function get_rolecourses($userid,
                          $role,
                          $courseid = SITEID,
                          $concatsql = '',
                           $limitconcat = '',
                            $count = false,
                             $check = false,
                              $datefiltersql = '',
                               $menu = false) {
        global $DB;
        $params = ['courseid' => $courseid];
        $params['contextlevel'] = CONTEXT_COURSE;
        $params['userid'] = $userid;
        $params['userid1'] = $params['userid'];
        $params['role'] = $role;
        $params['active'] = ENROL_USER_ACTIVE;
        $params['enabled'] = ENROL_INSTANCE_ENABLED;
        $params['now1'] = round(time(), -2); // Improves db caching.
        $params['now2'] = $params['now1'];
        if ($count) {
            $coursessql = "SELECT COUNT(c.id) as totalcount ";
        } else {
            $coursessql = "SELECT c.id, c.fullname, FROM_UNIXTIME(c.timecreated) as timecreated ";
        }
        $coursessql .= " FROM {course} AS c
                           JOIN (SELECT DISTINCT e.courseid
                                   FROM {enrol} AS e
                                   JOIN {user_enrolments} AS ue ON (ue.enrolid = e.id AND ue.userid = :userid1)
                                  WHERE ue.status = :active AND e.status = :enabled AND ue.timestart < :now1 AND
                                  (ue.timeend = 0 OR ue.timeend > :now2) $datefiltersql) en ON (en.courseid = c.id)
                     LEFT JOIN {context} AS ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)
                          JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
                          JOIN {role} AS r ON r.id = ra.roleid
                         WHERE c.id <> :courseid AND c.visible = 1 AND ra.userid = :userid AND r.shortname = :role
                         $concatsql ORDER BY c.id ASC $limitconcat";
        try {
            if ($count) {
                $courses = $DB->count_records_sql($coursessql, $params);
            } else {
                if ($menu) {
                    $courses = $DB->get_records_sql_menu($coursessql, $params);
                } else {
                    $courses = $DB->get_records_sql($coursessql, $params);
                }
            }
        } catch (dml_exception $ex) {
            moodle_exception(get_string('querywrong', 'zatuk'));
        }
        if ($check) {
            return !empty($courses) ? true : false;
        }
        return $courses;
    }
    /**
     * function filter_get_activities
     * @param int $courseid
     * @param int $cmid
     */
    public function filter_get_activities($courseid, $cmid=0) {
        global $DB;
        $sql = "SELECT cm.id, s.name FROM {course_modules} AS cm
          JOIN {modules} AS m ON m.id = cm.module AND m.name LIKE 'zatuk'
          JOIN {zatuk} AS s ON s.id = cm.instance
          WHERE cm.course = {$courseid} AND s.enableanalytics=1";
        $activities = $DB->get_records_sql_menu($sql);
        $activityioptions = [];
        $activityioptions[] = ['id' => 0, 'name' => get_string('byactivity', 'zatuk')];
        foreach ($activities as $id => $name) {
            $selected = $cmid == $id ? get_string('selected', 'zatuk') : '';
            $activityioptions[] = ['id' => $id, 'name' => $name, 'selected' => $selected];
        }
        return $activityioptions;

    }
    /**
     * function filter_get_courses
     * @param int $courseid
     */
    public function filter_get_courses($courseid = SITEID) {
        global $DB, $USER;
        if (is_siteadmin() || $_SESSION['role'] == get_string('manager', 'zatuk')) {
            $courselist = array_keys($DB->get_records_sql("SELECT * FROM {course} WHERE visible = 1"));
        } else if (!empty($_SESSION['role'])) {
            $allcourses = $this->get_rolecourses($USER->id, $_SESSION['role']);
            $courselist = array_keys($allcourses);
        } else {
            $courselist = array_keys(enrol_get_users_courses($USER->id));
        }

        $courseoptions = [];

        if (!empty($courselist)) {
            list($usql, $params) = $DB->get_in_or_equal($courselist);
            $courses = $DB->get_records_select('course', "id $usql", $params);
            $courseoptions = [];
            $courseoptions[] = ['id' => 0, 'name' => get_string('bycourse', 'zatuk')];
            foreach ($courses as $c) {
                if ($c->id == SITEID) {
                    continue;
                }
                $selected = $c->id == $courseid ? get_string('selected', 'zatuk') : '';
                $courseoptions[] = ['id' => $c->id,
                                 'name' => format_string($c->fullname),
                                 'selected' => $selected,
                               ];
            }
        }
        return $courseoptions;
    }
}

