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
/**
 * class zatuk
 */
class zatuk {
    /**
     * function __construct
     *
     */
    public function __construct() {

        global $DB, $CFG;
        $this->db = $DB;
        require_once($CFG->dirroot.'/repository/zatuk/zatuklib.php');
        $zatukconfig = get_config('repository_zatuk');
        $apikey = $zatukconfig->zatuk_key;
        $apiurl = $zatukconfig->zatuk_api_url;
        $secret = $zatukconfig->zatuk_secret;
        $email  = $zatukconfig->email;
        $name = $zatukconfig->name;
        $this->zatuklib = new \phpzatuk($apiurl, $apikey, $secret, '', '');
    }
    /**
     * function uploadedVideoData
     * @param array $search
     * @param array $params
     * @param array $onlycount
     */
    public function uploadedvideodata($search=[], $params=[], $onlycount = true) {
        global $DB, $OUTPUT, $USER;
        $systemcontext = \context_system::instance();
        $userid = $USER->id;
        $curlparams = [];
        $videossql = "SELECT uv.id, uv.title, uv.timecreated,uv.videoid,uv.public,uv.status,uv.filepath AS itemid,
                     concat(u.firstname,' ',u.lastname) AS usercreated";
        $videoidsql = "SELECT uv.videoid ";
        $countsql = "SELECT count(uv.id) ";

        $uploadedvideossql = " FROM {zatuk_uploaded_videos} uv
                               JOIN {user} u on u.id = uv.usercreated WHERE 1=1 ";
        if (!is_siteadmin() && has_capability('mod/zatuk:editingteacher', $systemcontext)) {

            $uploadedvideossql .= " AND CASE
                                       WHEN  uv.public IS NULL THEN uv.usercreated = $USER->id
                                       ELSE  uv.id  <> -1
                                    END ";
        }
        if (!empty($params->search)) {
            $uploadedvideossql .= " AND uv.title LIKE '%{$params->search}%' ";
        }
        if ($params->statusfilter == 'inprogress') {
            $uploadedvideossql .= " AND uv.status=0";
        } else if ($params->statusfilter == 'published') {
            $uploadedvideossql .= " AND uv.status=1";
        }
        if ($params->sort == 'fullname') {
            $uploadedvideossql .= " ORDER BY uv.title ASC ";
        } else if ($params->sort == 'uploadeddate') {
            $uploadedvideossql .= " ORDER BY uv.timecreated DESC ";
        } else {
            $uploadedvideossql .= " ORDER BY uv.id DESC ";
        }

        $total = $this->db->count_records_sql($countsql . $uploadedvideossql);

        if ($onlycount) {
            return ['data' => [], 'length' => $total];
        }

        $uploadedvideos = $this->db->get_records_sql($videossql . $uploadedvideossql, [], $params->offset, $params->limit);
        $videoids = $this->db->get_fieldset_sql($videoidsql . $uploadedvideossql . 'LIMIT ' . $params->offset .','. $params->limit);

        $videoidsarray = json_encode(['ids' => $videoids]);

        $curlparams['videoids'] = $videoidsarray;

        $curlparams['perpage'] = $total;
        $content = $this->zatuklib->get_videos($curlparams);

        foreach ($uploadedvideos as $data) {
            $thumb = array_search($data->videoid, array_column($content['data'], 'videoid'));
            $image = $OUTPUT->image_url('video', 'mod_zatuk')->out(false);

            if (isset($thumb) && $content['data'][$thumb]['videoid'] == $data->videoid) {
                $image = $this->zatuklib->apiurl.$content['data'][$thumb]['thumbnail'];
                $videopath = $content['data'][$thumb]['path'];
            }
            if ($data->status == 1 && $content['data'][$thumb]['status'] >= 2) {
                $DB->delete_records('zatuk_uploaded_videos', ['id' => $data->id]);
                continue;
            }
            if (!is_siteadmin() && has_capability('mod/zatuk:editingteacher', $systemcontext)) {
                if ($data->public == "0" || $data->public == "") {
                    $deleteoption = true;
                } else {
                    $deleteoption = false;
                }
            } else {
                $deleteoption = true;
            }
            $returndata[] = ['id' => $data->id,
                             'title' => $data->title,
                             'thumbnail' => $image,
                             'timecreated' => date('jS F Y', $data->timecreated),
                             'usercreated' => $data->usercreated,
                             'path' => $videopath,
                             'videoid' => $data->videoid,
                             'status' => $data->status,
                             'public' => $data->public,
                             'deleteoption' => $deleteoption,
                         ];
        }
        return ['data' => $returndata, 'length' => $total];
    }
    /**
     * function mod_content
     *
     */
    public function mod_content() {
        $searchurl = $this->zatuklib->createsearchapiurl();
        $curlparams = $this->zatuklib->get_listing_params();
        $curlparams['q'] = '';
        $curlparams['perpage'] = 1;
        $curlparams['page'] = 1;
        $c = new \curl();
        $content = $c->post($searchurl, $curlparams);
        $content = json_decode($content, true);
        $totalvideos = $content['meta']['total'];
        $uploadedvideos = $this->db->count_records('zatuk_uploaded_videos');
        $syncedvideos = $this->db->count_records('zatuk_uploaded_videos', ['status' => 1]);
        $systemcontext = \context_system::instance();
        $viewcap = is_siteadmin() || has_capability('mod/zatuk:viewvideos', $systemcontext);
        return ['totalVideos' => $totalvideos,
        'uploadedVideos' => $uploadedvideos,
        'syncedVideos' => $syncedvideos,
        'viewcap' => $viewcap];
    }
    /**
     * function delete_uploaded_video
     * @param int $id
     */
    public function delete_uploaded_video($id) {
        try {
            $zatukdata = $this->db->get_record('zatuk_uploaded_videos', ['id' => $id], 'id, filepath', MUST_EXIST);
            $this->delete_file_instance($zatukdata->filepath, 'video');
            $context = \context_system::instance();
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
     * function delete_file_instance
     * @param int $itemid
     * @param string $filearea
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
     * function insert_zatuk_content
     * @param object $validateddata
     * @param array $tags
     * @param object $context
     */
    public function insert_zatuk_content($validateddata, $tags, $context) {
        global $_SESSION, $USER;
        $systemcontext = \context_system::instance();
        if (!empty($validateddata)) {
            $condition = (is_siteadmin() ||
                          has_capability('mod/zatuk:editingteacher', $systemcontext) ||
                          has_capability('mod/zatuk:manageactions', $systemcontext));
            if ($condition) {
                if ($validateddata->id == 0) {
                    $insertdata = new stdClass();
                    $insertdata->videoid = uniqid();
                    $insertdata->title = $validateddata->title;
                    $insertdata->public = $validateddata->public;
                    $insertdata->organization = $validateddata->organization;
                    $insertdata->tags = is_array($validateddata->tags) ? implode(',', $validateddata->tags) : $validateddata->tags;
                    $insertdata->description = $validateddata->description['text'];
                    $insertdata->filepath = $validateddata->filepath;
                    $insertdata->filename = $this->db->get_field_sql("SELECT filename FROM {files} WHERE
                                               itemid = {$validateddata->filepath} AND filename != '.' ");
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
                return false;
            }
        } else {
            throw new moodle_exception('uploaderror', 'mod_zatuk');
            return false;
        }

    }
    /**
     * function set_data
     * @param int $id
     */
    public function set_data($id) {
        global $DB, $CFG;
        require_once($CFG->dirroot.'/mod/zatuk/lib.php');
        $uploaddata = mod_zatuk_get_api_formdata();
        $organisations = (array)$uploaddata->organisations;
        $tags = (array)$uploaddata->tags;
        $data = $DB->get_record('zatuk_uploaded_videos', ['id' => $id], '*', MUST_EXIST);
        $row['id'] = $data->id;
        $row['title'] = $data->title;
        $row['description'] = ['text' => $data->description];
        if ((int)$data->organization) {
            $row['organization'] = (int)$data->organization;
        } else {
            $row['organization'] = $organisations;
        }
        if ((int)$data->tags) {
            $row['tags'] = (int)$data->tags;
        } else {
            $row['tags'] = $tags;
        }
        $row['public'] = $data->public;
        $row['category'] = $data->category;
        return $row;
    }
}


