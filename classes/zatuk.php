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
 * mod_zatuk zatuk class
 *
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk;
use stdClass;
use moodle_exception;
use context_course;
use repository_zatuk\phpzatuk;
use Exception;
use mod_zatuk\zatuk_constants as zc;
/**
 * class zatuk
 */
class zatuk {

    /**
     * @var object $db
     */
    public $db;
    /**
     * @var object $zatuklib
     */
    public $zatuklib;

    /**
     * Zatuk Constructor
     * @return void
     */
    public function __construct() {
        global $DB, $CFG;
        $this->db = $DB;
        $repodata = $this->get_repository_data();
        $this->zatuklib = new phpzatuk($repodata->apiurl, $repodata->apikey, $repodata->secret);
    }
    /**
     * Get zatuk uploaded videos from zatuk video table.
     * @param array $params
     * @param bool $onlycount
     * @return array
     */
    public function zatuk_uploaded_video_data($params = [], $onlycount = false) {
        global $OUTPUT, $USER;

        $curlparams = [];
        $queryparams = [];
        $videossql = "SELECT uv.id, uv.title, uv.timecreated,uv.videoid,
                      uv.public,uv.status,uv.filepath AS itemid, uv.course AS courseid,
                      u.id AS userid";
        $videoidsql = "SELECT uv.videoid ";
        $countsql = "SELECT count(uv.id) ";

        $uploadedvideossql = " FROM {zatuk_uploaded_videos} uv
                               JOIN {user} u on u.id = uv.usercreated WHERE 1=1 ";

        $courseid = (int)$params['courseid'];
        $uploadedvideossql .= " AND uv.course = :courseid ";
        $queryparams['courseid'] = $courseid;

        $sortvideosql = '';
        if (!is_siteadmin() && has_capability('mod/zatuk:accessedbyfaculty', context_course::instance($courseid))) {

            $uploadedvideossql .= " AND CASE
                                       WHEN  uv.public <= 0 THEN uv.usercreated = :usercreated
                                       ELSE  uv.id  <> -1
                                    END ";
            $queryparams['usercreated'] = $USER->id;
        }
        if (!is_null($params) && !empty($params['search'])) {

            $uploadedvideossql .= " AND ".$this->db->sql_like('uv.title', ':titlesearch', false)." ";
            $queryparams['titlesearch']  = '%'.$params['search'].'%';
        }
        if (!is_null($params) && !empty($params['statusfilter']) && $params['statusfilter'] == 'inprogress') {
            $uploadedvideossql .= " AND uv.status = :pstatus";
            $queryparams['pstatus'] = zc::DEFAULTSTATUS;
        } else if (!is_null($params) && !empty($params['statusfilter']) && $params['statusfilter'] == 'published') {
            $uploadedvideossql .= " AND uv.status = :pstatus";
            $queryparams['pstatus'] = zc::STATUSA;
        }
        if (!is_null($params) && !empty($params['sort']) && $params['sort'] == 'fullname') {
            $sortvideosql = " ORDER BY uv.title ASC, uv.id DESC ";
        } else if (!is_null($params) && !empty($params['sort']) && $params['sort'] == 'uploadeddate') {
            $sortvideosql = " ORDER BY uv.timecreated DESC, uv.id DESC ";
        } else if (is_null($params) || empty($params['sort'])) {
            $sortvideosql = " ORDER BY uv.id DESC ";
        }
        $total = $this->db->count_records_sql($countsql . $uploadedvideossql, $queryparams);
        if ($onlycount) {
            return ['data' => [], 'length' => $total];
        }
        $offset = (!empty($params['offset'])) ? $params['offset'] : zc::DEFAULTSTATUS;
        $limit = (!empty($params['limit'])) ? $params['limit'] : zc::DEFAULTPAGELIMIT;
        $uploadedvideos = $this->db->get_records_sql($videossql . $uploadedvideossql.$sortvideosql, $queryparams, $offset, $limit);
        $videoids = $this->db->get_fieldset_sql($videoidsql . $uploadedvideossql.$sortvideosql, $queryparams);

        $videoidsarray = json_encode(['ids' => $videoids]);

        $curlparams['videoids'] = $videoidsarray;

        $curlparams['perpage'] = $total;
        $content = $this->zatuklib->get_videos($curlparams);
        if (!empty($content['data'])) {
            $content['data'] = array_combine(range(1, count($content['data'])), array_values($content['data']));
        }
        $returndata = [];

        foreach ($uploadedvideos as $data) {
            if (!empty($content['data'])) {
                $contentvideoids = array_column($content['data'], 'videoid');
                $contentvideoids = array_combine(range(1, count($contentvideoids)), array_values($contentvideoids));
                $thumb = array_search($data->videoid, $contentvideoids);
            } else {
                $thumb = zc::DEFAULTSTATUS;
            }
            $image = $OUTPUT->image_url('video', 'mod_zatuk')->out(false);
            $videopath = '';
            if ($thumb && $content['data'][$thumb]['videoid'] == $data->videoid) {
                $image = $this->zatuklib->apiurl.$content['data'][$thumb]['thumbnail'];
                $videopath = $content['data'][$thumb]['path'];
            }
            if (!is_siteadmin() && has_capability('mod/zatuk:accessedbyfaculty', context_course::instance($courseid))) {
                if ($data->public == 0) {
                    $deleteoption = true;
                } else {
                    $deleteoption = false;
                }
            } else {
                $deleteoption = true;
            }
            $apikey = trim(get_config('repository_zatuk', 'zatuk_key'));
            $user = $this->db->get_record('user', ['id' => $data->userid]);
            $iszatukrepoenabled = ($apikey) ? zc::STATUSA : zc::DEFAULTSTATUS;
            $returndata[] = ['id' => $data->id,
                             'title' => $data->title,
                             'thumbnail' => $image,
                             'timecreated' => date('jS F Y', $data->timecreated),
                             'userfullname' => fullname($user),
                             'path' => $videopath,
                             'videoid' => $data->videoid,
                             'status' => $data->status,
                             'public' => $data->public,
                             'deleteoption' => $deleteoption,
                             'courseid' => $data->courseid,
                             'iszatukrepoenabled' => $iszatukrepoenabled,
                             'canviewvideo' => ($thumb > zc::DEFAULTSTATUS) ? zc::STATUSA : zc::DEFAULTSTATUS,
                         ];

        }
        return ['data' => $returndata, 'length' => $total];
    }
    /**
     * Add zatuk content
     * @param object $sdata
     * @return mixed false if an error occurs or the int id of the new instance
     */
    public function add_zatuk_content($sdata) {
        global $USER;
        try {
            $insertdata = new stdClass();
            $insertdata->course = $sdata->courseid;
            $insertdata->videoid = uniqid();
            $insertdata->title = $sdata->title;
            $insertdata->public = (isset($sdata->public)) ? $sdata->public : 0;
            $insertdata->description = $sdata->description['text'];
            $insertdata->filepath = $sdata->filepath;
            $insertdata->filename = $this->db->get_field_sql("SELECT filename FROM {files} WHERE
                                            itemid =:itemid AND filename != '.'", ['itemid' => $sdata->filepath]);
            if (empty($insertdata->title)) {
                $insertdata->title = preg_replace('/\\.[^.\\s]{3,4}$/', '', $insertdata->filename);
            }
            $insertdata->timecreated = time();
            $insertdata->usercreated = $USER->id;
            $insertdata->status = 0;
            $uploadid = $this->db->insert_record('zatuk_uploaded_videos', $insertdata);
            return $uploadid;
        } catch (Exception $e) {
            throw new moodle_exception($e->getMessage());
        }

    }
    /**
     * Update zatuk content
     * @param object $sdata
     * @return mixed false if an error occurs or the int id of the new instance
     */
    public function update_zatuk_content($sdata) {
        global $USER;
        try {
            $updatedata = new stdClass();
            $updatedata->id = $sdata->id;
            $updatedata->course = $sdata->courseid;
            $updatedata->title = $sdata->title;
            $updatedata->public = (isset($sdata->public)) ? $sdata->public : 0;
            $updatedata->description = $sdata->description['text'];
            if (empty($updatedata->title)) {
                $updatedata->title = preg_replace('/\\.[^.\\s]{3,4}$/', '', $updatedata->filename);
            }
            $updatedata->timecreated = time();
            $updatedata->usercreated = $USER->id;
            $updatedata->status = zc::DEFAULTSTATUS;
            $uploadid = $this->db->update_record('zatuk_uploaded_videos', $updatedata);
            return $sdata->id;
        } catch (Exception $e) {
            throw new moodle_exception($e->getMessage());
        }

    }
    /**
     * Delete uploaded zatuk content.
     * @param int $id
     * @param int $courseid
     * @return bool
     */
    public function delete_zatuk_content($id, $courseid) {

        try {
            $zatukdata = $this->db->get_record('zatuk_uploaded_videos', ['id' => $id], 'id, filepath', MUST_EXIST);
            if ($zatukdata->filepath) {
                $this->delete_file_instance($zatukdata->filepath, 'video');
            }
            $context = context_course::instance($courseid);
            $params = [
                'context' => $context,
                'objectid' => $id,
            ];
            $event = \mod_zatuk\event\video_deleted::create($params);
            $event->trigger();
            $this->db->delete_records('zatuk_uploaded_videos', ['id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Delete uploaded zatuk file instnace.
     * @param int $itemid
     * @param string $filearea
     * @return bool|null
     */
    private function delete_file_instance($itemid, $filearea) {
        $fileid = $this->db->get_field_sql("SELECT id FROM {files}
                                           where itemid = :itemid AND
                                            component LIKE :component AND
                                             filearea LIKE :filearea AND
                                              filename <> '.' ",
                                              ['itemid' => $itemid, 'component' => 'mod_zatuk', 'filearea' => $filearea]);
        if ($fileid) {
                $filesystem = get_file_storage();
                $fileinfo = $filesystem->get_file_by_id($fileid);
                $fileinfo->delete();
        }
    }
    /**
     * Describes to set zatuk video content data
     * @param int $id
     * @return array
     */
    public function set_data($id) {
        global $CFG;
        require_once($CFG->dirroot.'/mod/zatuk/lib.php');
        $data = $this->db->get_record('zatuk_uploaded_videos', ['id' => $id], '*', MUST_EXIST);
        $row['id'] = $data->id;
        $row['title'] = $data->title;
        $row['description'] = ['text' => $data->description];
        $row['public'] = $data->public;
        return $row;
    }
    /**
     * Get zatuk repository data from config plugin table.
     * @return stdclass
     */
    public function get_repository_data() {
        $sdata = new stdClass();
        $sdata->apikey = trim(get_config('repository_zatuk', 'zatuk_key'));
        $sdata->secret  = trim(get_config('repository_zatuk', 'zatuk_secret'));
        $sdata->apiurl = trim(get_config('repository_zatuk', 'zatukapiurl'));
        $sdata->emailaddress  = trim(get_config('repository_zatuk', 'email'));
        $sdata->username  = trim(get_config('repository_zatuk', 'name'));
        return $sdata;
    }
}

