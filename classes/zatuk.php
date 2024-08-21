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
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk;
use stdClass;
use moodle_exception;
use context_system;
use phpzatuk;
use curl;
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
        require_once($CFG->dirroot.'/repository/zatuk/zatuklib.php');
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
        $systemcontext = context_system::instance();
        $curlparams = [];
        $videossql = "SELECT uv.id, uv.title, uv.timecreated,uv.videoid,
                      uv.public,uv.status,uv.filepath AS itemid,
                      u.id AS userid";
        $videoidsql = "SELECT uv.videoid ";
        $countsql = "SELECT count(uv.id) ";

        $uploadedvideossql = " FROM {zatuk_uploaded_videos} uv
                               JOIN {user} u on u.id = uv.usercreated WHERE 1=1 ";
        $queryparams = [];
        $sortvideosql = '';
        if (!is_siteadmin() && has_capability('mod/zatuk:accessedbyfaculty', $systemcontext)) {

            $uploadedvideossql .= " AND CASE
                                       WHEN  uv.public IS NULL THEN uv.usercreated = :usercreated
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
            $queryparams['pstatus'] = 0;
        } else if (!is_null($params) && !empty($params['statusfilter']) && $params['statusfilter'] == 'published') {
            $uploadedvideossql .= " AND uv.status = :pstatus";
            $queryparams['pstatus'] = 1;
        }
        if (!is_null($params) && !empty($params['sort']) && $params['sort'] == 'fullname') {
            $uploadedvideossql .= " ORDER BY uv.title ASC ";
        }
        if (!is_null($params) && !empty($params['sort']) && $params['sort'] == 'uploadeddate') {
            $uploadedvideossql .= " ORDER BY uv.timecreated DESC ";
        }

        $sortvideosql = " ORDER BY uv.id DESC ";

        $total = $this->db->count_records_sql($countsql . $uploadedvideossql, $queryparams);
        if ($onlycount) {
            return ['data' => [], 'length' => $total];
        }
        $offset = (!empty($params['offset'])) ? $params['offset'] : 0;
        $limit = (!empty($params['limit'])) ? $params['limit'] : 10;
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
                $thumb = 0;
            }
            $image = $OUTPUT->image_url('video', 'mod_zatuk')->out(false);
            $videopath = '';
            if ($thumb && $content['data'][$thumb]['videoid'] == $data->videoid) {
                $image = $this->zatuklib->apiurl.$content['data'][$thumb]['thumbnail'];
                $videopath = $content['data'][$thumb]['path'];
            }
            if (!is_siteadmin() && has_capability('mod/zatuk:accessedbyfaculty', $systemcontext)) {
                if ($data->public == "0" || $data->public == "") {
                    $deleteoption = true;
                } else {
                    $deleteoption = false;
                }
            } else {
                $deleteoption = true;
            }
            $apikey = trim(get_config('repository_zatuk', 'zatuk_key'));
            $user = $this->db->get_record('user', ['id' => $data->userid]);
            $iszatukrepoenabled = ($apikey) ? 1 : 0;
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
                             'iszatukrepoenabled' => $iszatukrepoenabled,
                         ];

        }
        return ['data' => $returndata, 'length' => $total];
    }
    /**
     * get module content from api.
     * @return array
     */
    public function mod_content() {
        $searchurl = $this->zatuklib->createsearchapiurl();
        $curlparams = $this->zatuklib->get_listing_params();
        $curlparams['q'] = '';
        $curlparams['perpage'] = 1;
        $curlparams['page'] = 1;
        $c = new curl();
        try {
            $content = $c->post($searchurl, $curlparams);
            $content = json_decode($content, true);
            $totalvideos = $content['meta']['total'];
            $uploadedvideos = $this->db->count_records('zatuk_uploaded_videos');
            $syncedvideos = $this->db->count_records('zatuk_uploaded_videos', ['status' => 1]);
            $systemcontext = context_system::instance();
            $viewcap = is_siteadmin() || has_capability('mod/zatuk:viewvideos', $systemcontext);
            return ['totalVideos' => $totalvideos,
            'uploadedVideos' => $uploadedvideos,
            'syncedVideos' => $syncedvideos,
            'viewcap' => $viewcap];
        } catch (\Exception $e) {
            throw new moodle_exception($e->getMessage());
        }

    }
    /**
     * Delete uploaded zatuk video.
     * @param int $id
     * @return bool
     */
    public function delete_uploaded_video($id) {
        try {
            $zatukdata = $this->db->get_record('zatuk_uploaded_videos', ['id' => $id], 'id, filepath', MUST_EXIST);
            $this->delete_file_instance($zatukdata->filepath, 'video');
            $context = context_system::instance();
            $params = [
                'context' => $context,
                'objectid' => $id,
            ];
            $event = \mod_zatuk\event\video_deleted::create($params);
            $event->trigger();
            $this->db->delete_records('zatuk_uploaded_videos', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
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
     * Insert zatuk content
     * @param object $validateddata
     * @param array $tags
     * @param object $context
     * @return bool
     */
    public function insert_zatuk_content($validateddata, $tags, $context) {
        global $USER;
        $uploaddata = mod_zatuk_get_api_formdata();
        $organisations = (array)$uploaddata->organisations;
        $tags = (array)$uploaddata->tags;
        $systemcontext = context_system::instance();
        if (!empty($validateddata)) {
            if (is_siteadmin() || has_capability('mod/zatuk:addinstance', $systemcontext)) {
                if ((int)$validateddata->id <= 0 || is_null($validateddata->id)) {
                    $insertdata = new stdClass();
                    $insertdata->videoid = uniqid();
                    $insertdata->title = $validateddata->title;
                    $insertdata->public = $validateddata->public;
                    $insertdata->organization = $validateddata->organization;
                    $insertdata->organisationname = $organisations[$validateddata->organization];
                    $insertdata->tags = is_array($validateddata->tags) ? implode(',', $validateddata->tags) : $validateddata->tags;
                    $insertdata->description = $validateddata->description['text'];
                    $insertdata->filepath = $validateddata->filepath;
                    $insertdata->filename = $this->db->get_field_sql("SELECT filename FROM {files} WHERE
                                                    itemid =:itemid AND filename != '.'", ['itemid' => $validateddata->filepath]);
                    if (empty($insertdata->title)) {
                        $insertdata->title = preg_replace('/\\.[^.\\s]{3,4}$/', '', $insertdata->filename);
                    }
                    $tagsname = [];
                    foreach ($validateddata->tags as $tag) {
                        $tagsname[] = $tags[$tag];
                    }
                    $insertdata->tagsname = implode(',', $tagsname);
                    $insertdata->timecreated = time();
                    $insertdata->usercreated = $USER->id;
                    $insertdata->status = 0;
                    $uploadid = $this->db->insert_record('zatuk_uploaded_videos', $insertdata);
                    return $uploadid;
                } else {
                    $insertdata = new stdClass();
                    $insertdata->id = $validateddata->id;
                    $insertdata->title = $validateddata->title;
                    $insertdata->public = $validateddata->public;
                    $insertdata->organization = $validateddata->organization;
                    $insertdata->organisationname = $organisations[$validateddata->organization];
                    $insertdata->tags = is_array($validateddata->tags) ? implode(',', $validateddata->tags) : $validateddata->tags;
                    $insertdata->description = $validateddata->description['text'];
                    if (empty($insertdata->title)) {
                        $insertdata->title = preg_replace('/\\.[^.\\s]{3,4}$/', '', $insertdata->filename);
                    }
                    $tagsname = [];
                    foreach ($validateddata->tags as $tag) {
                        $tagsname[] = $tags[$tag];
                    }
                    $insertdata->tagsname = implode(',', $tagsname);
                    $insertdata->timecreated = time();
                    $insertdata->usercreated = $USER->id;
                    $insertdata->status = 0;
                    $uploadid = $this->db->update_record('zatuk_uploaded_videos', $insertdata);
                    return $uploadid;
                }

            } else {
                throw new moodle_exception('actionpermission', 'mod_zatuk');
            }
        } else {
            throw new moodle_exception('uploaderror', 'mod_zatuk');
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
        $uploaddata = mod_zatuk_get_api_formdata();
        $organisations = (array)$uploaddata->organisations;
        $tags = (array)$uploaddata->tags;
        $data = $this->db->get_record('zatuk_uploaded_videos', ['id' => $id], '*', MUST_EXIST);
        $row['id'] = $data->id;
        $row['title'] = $data->title;
        $row['description'] = ['text' => $data->description];
        if ((int)$data->organization) {
            $row['organization'] = (int)$data->organization;
        } else {
            $row['organization'] = $organisations;
        }
        if (!empty($data->tags)) {
            $row['tags'] = $data->tags;
        } else {
            $row['tags'] = $tags;
        }
        $row['public'] = $data->public;
        $row['category'] = $data->category;
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
        $sdata->apiurl = trim(get_config('repository_zatuk', 'zatuk_api_url'));
        $sdata->emailaddress  = trim(get_config('repository_zatuk', 'email'));
        $sdata->username  = trim(get_config('repository_zatuk', 'name'));
        return $sdata;
    }
}

