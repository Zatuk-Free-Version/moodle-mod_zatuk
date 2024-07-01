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
 * mod_zatuk uploader class
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\lib;
use curl;
use phpzatuk;
/**
 * class uploader
 */
class uploader {


    /**
     * @var object $db
     */
    public $db;
    /**
     * function __construct
     *
     */
    public function __construct() {
        global $DB;
        $this->db = $DB;
    }
    /**
     * function videosSync
     *
     */
    public function publish_video() {
        global $CFG;
        $videoinfosql = "SELECT uv.*, f.id as fileid FROM {zatuk_uploaded_videos} uv
                          JOIN {files} f ON f.itemid = uv.id
                           WHERE f.filename != '.' AND
                           uv.status = :filestatus AND
                           f.component = :component AND
                           f.filearea = :filearea";
        $videoinfo = $this->db->get_record_sql($videoinfosql, ['filestatus' => 0 ,
                                                             'component' => 'mod_zatuk',
                                                             'filearea' => 'video']);

        if (empty($videoinfo)) {
            return;
        }
        $zatukobj = new \mod_zatuk\zatuk();
        $params = $zatukobj->zatuklib->get_listing_params();

        $searchurl = $zatukobj->zatuklib->apiurl."/api/v1/videos/importVideo";
        $c = new curl();
        $header[] = "Content-Type: multipart/form-data";
        $c->setHeader($header);
        $c->setopt(['CURLOPT_HEADER' => false]);
        $c->setopt(['CURLOPT_VERBOSE' => true]);
        $files = [];
        $params['video'] = $this->get_zatuk_video_file_object($c, $videoinfo->fileid, 'video');

        $params['CURLOPT_RETURNTRANSFER'] = 1;
        $params['CURLOPT_POST'] = 1;
        $params += (array)$videoinfo;
        $params['key'] = $zatukobj->zatuklib->clientid;
        $params['secret'] = $zatukobj->zatuklib->secret;

        $contents = $c->post($searchurl, $params);
        $content = json_decode($contents, true);

        if (empty($content['error']) || is_null($content['error'])) {
            $params['video']->delete();
            $videoinfo->status = 1;
            $videoinfo->uploaded_on = $videoinfo->timemodified = time();
            $status = $this->db->update_record('zatuk_uploaded_videos', $videoinfo);
        }
        $context = \context_system::instance();
        $error = (!empty($content['error']) && !is_null($content['error'])) ? $content['error'] : '';
        $message = (!empty($content['message']) && !is_null($content['message'])) ? $content['message'] : '';
        $params = [
                'context' => $context,
                'objectid' => $videoinfo->id,
                'other' => ['error' => $error, 'msg' => $message],
            ];
            $event = \mod_zatuk\event\video_synced::create($params);
            $event->trigger();
    }
    /**
     * function get_zatuk_video_file_object
     * @param \stdclass $curlobj
     * @param int $fileid
     * @param string||null $filetype
     */
    public function get_zatuk_video_file_object(&$curlobj, $fileid, $filetype) {
        $fs = get_file_storage();
        $fileinfo = $fs->get_file_by_id($fileid);
        $fileinfo->add_to_curl_request($curlobj, $filetype);
        $source = unserialize($fileinfo->get_source());
        $filename = '';
        if (is_object($source)) {
            $filename = $source->source;
        } else {
            // If source is not a serialised object, it is a string containing only the filename.
            $filename = $fileinfo->get_source();
        }
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        if (empty($extension)) {
            $extension = mimeinfo_from_type('extension', $fileinfo->get_mimetype());
            $filename .= '.' . $extension;
        }
        $mimetype = mimeinfo('type', $filename);
        list($mediatype, $subtype) = explode('/', $mimetype);
        if ($mediatype != $filetype) {
            throw new \moodle_exception('wrongmimetypedetected', 'mod_zatuk');
        }
        $fileinfo->postname = $filename;
        $fileinfo->mime = $mimetype;
        return $fileinfo;
    }
    /**
     * function videosSyncById
     * @param int $id
     */
    public function publish_video_by_id($id) {
        global $CFG;

        $videoinfosql = " SELECT uv.*, f.id as fileid FROM {zatuk_uploaded_videos} uv
                          JOIN {files} f ON f.itemid = uv.id
                           WHERE f.filename != '.' AND
                           uv.status = :filestatus AND
                           f.component = :component AND
                           f.filearea = :filearea AND
                           uv.id = :id ";
        $videoinfo = $this->db->get_record_sql($videoinfosql, ['filestatus' => 0 ,
                                                             'component' => 'mod_zatuk',
                                                             'filearea' => 'video',
                                                             'id' => $id ]);
        if (empty($videoinfo)) {
            return;
        }
        $zatukobj = new \mod_zatuk\zatuk();
        $params = $zatukobj->zatuklib->get_listing_params();

        $searchurl = $zatukobj->zatuklib->apiurl."/api/v1/videos/importVideo";
        $c = new curl();
        $header[] = "Content-Type: multipart/form-data";
        $c->setHeader($header);
        $c->setopt(['CURLOPT_HEADER' => false]);
        $c->setopt(['CURLOPT_VERBOSE' => true]);
        $files = [];
        $params['video'] = $this->get_zatuk_video_file_object($c, $videoinfo->fileid, 'video');
        $params['CURLOPT_RETURNTRANSFER'] = 1;
        $params['CURLOPT_POST'] = 1;
        $params += (array)$videoinfo;
        $params['key'] = $zatukobj->zatuklib->clientid;
        $params['secret'] = $zatukobj->zatuklib->secret;

        $contents = $c->post($searchurl, $params);

        $content = json_decode($contents, true);

        if (empty($content['error']) || is_null($content['error'])) {
            $params['video']->delete();
            $videoinfo->status = 1;
            $videoinfo->uploaded_on = $videoinfo->timemodified = time();
            $status = $this->db->update_record('zatuk_uploaded_videos', $videoinfo);
        }
        $context = \context_system::instance();
        $error = (!empty($content['error']) && !is_null($content['error'])) ? $content['error'] : '';
        $message = (!empty($content['message']) && !is_null($content['message'])) ? $content['message'] : '';
        $params = [
                'context' => $context,
                'objectid' => $videoinfo->id,
                'other' => ['error' => $error, 'msg' => $message],
            ];
        $event = \mod_zatuk\event\video_synced::create($params);
        $event->trigger();
    }
}

