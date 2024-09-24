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
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_zatuk\lib;
use curl;
use moodle_exception;
use mod_zatuk\zatuk_constants as zc;
use Exception;
use stdClass;
use context_system;
/**
 * class uploader
 */
class uploader {


    /**
     * @var object $db
     */
    public $db;
    /**
     * Uploader constructor
     * @return void
     */
    public function __construct() {
        global $DB;
        $this->db = $DB;
    }
    /**
     * Publish zatuk videos from lms to zatuk site.
     * @return bool|null|array
     */
    public function publish_video() {
        $videoinfosql = "SELECT uv.*, f.id as fileid FROM {zatuk_uploaded_videos} uv
                            JOIN {files} f ON f.itemid = uv.id
                            WHERE f.filename != '.' AND
                            uv.status = :filestatus AND
                            f.component = :component AND
                            f.filearea = :filearea";
        $videosinfo = $this->db->get_records_sql($videoinfosql, ['filestatus' => zc::DEFAULTSTATUS ,
                                                                'component' => 'mod_zatuk',
                                                                'filearea' => 'video']);
        foreach ($videosinfo as $videoinfo) {
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

            $params['CURLOPT_RETURNTRANSFER'] = zc::STATUSA;
            $params['CURLOPT_POST'] = zc::STATUSA;
            $params += (array)$videoinfo;
            $params['key'] = $zatukobj->zatuklib->clientid;
            $params['secret'] = $zatukobj->zatuklib->secret;
            try {
                $contents = $c->post($searchurl, $params);
                $content = json_decode($contents, true);
                if (isset($content)) {
                    if (empty($content['error']) || is_null($content['error'])) {
                        $params['video']->delete();
                        $videoinfo->status = zc::STATUSA;
                        $videoinfo->uploaded_on = $videoinfo->timemodified = time();
                        $response = $this->db->update_record('zatuk_uploaded_videos', $videoinfo);
                    }
                } else {
                    throw new moodle_exception(get_string('servererror'));
                }
                $context = context_system::instance();
                $error = (!isset($content)) ? get_string('servererror') :
                ((!empty($content['error']) && !is_null($content['error'])) ? $content['error'] : '');
                $message = (!isset($content)) ? get_string('servererror') :
                ((!empty($content['message']) && !is_null($content['message'])) ? $content['message'] : '');
                $params = [
                    'context' => $context,
                    'objectid' => $videoinfo->id,
                    'other' => ['error' => $error, 'msg' => $message],
                ];
                $event = \mod_zatuk\event\video_synced::create($params);
                $event->trigger();
            } catch (Exception $e) {
                throw new moodle_exception($e->getMessage());
            }
        }
    }
    /**
     * Get zatuk video file data.
     * @param stdclass|curl $curlobj
     * @param int $fileid
     * @param string||null $filetype
     * @return bool|\stored_file
     */
    public function get_zatuk_video_file_object($curlobj, $fileid, $filetype) {
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
            throw new moodle_exception(get_string('wrongmimetypedetected', 'mod_zatuk'));
        }
        $fileinfo->postname = $filename;
        $fileinfo->mime = $mimetype;
        return $fileinfo;
    }
    /**
     * Publish zatuk video based on video id
     * @param int $id
     * @return bool|null|array
     */
    public function publish_video_by_id($id) {

        $videoinfosql = " SELECT uv.*, f.id as fileid FROM {zatuk_uploaded_videos} uv
                          JOIN {files} f ON f.itemid = uv.id
                           WHERE f.filename != '.' AND
                           uv.status = :filestatus AND
                           f.component = :component AND
                           f.filearea = :filearea AND
                           uv.id = :id ";
        $videoinfo = $this->db->get_record_sql($videoinfosql, ['filestatus' => zc::DEFAULTSTATUS ,
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
        $params['CURLOPT_RETURNTRANSFER'] = zc::STATUSA;
        $params['CURLOPT_POST'] = zc::STATUSA;
        $params += (array)$videoinfo;
        $params['key'] = $zatukobj->zatuklib->clientid;
        $params['secret'] = $zatukobj->zatuklib->secret;
        $response = false;
        try {
            $contents = $c->post($searchurl, $params);
            $content = json_decode($contents, true);
            if (isset($content)) {
                if (empty($content['error']) || is_null($content['error'])) {
                    $params['video']->delete();
                    $videoinfo->status = zc::STATUSA;
                    $videoinfo->uploaded_on = $videoinfo->timemodified = time();
                    $response = $this->db->update_record('zatuk_uploaded_videos', $videoinfo);
                }
            } else {
                $response = false;
            }
            $context = context_system::instance();
            $error = (!isset($content)) ? get_string('servererror') :
            ((!empty($content['error']) && !is_null($content['error'])) ? $content['error'] : '');
            $message = (!isset($content)) ? get_string('servererror') :
            ((!empty($content['message']) && !is_null($content['message'])) ? $content['message'] : '');
            $params = [
                    'context' => $context,
                    'objectid' => $videoinfo->id,
                    'other' => ['error' => $error, 'msg' => $message],
                ];
            $event = \mod_zatuk\event\video_synced::create($params);
            $event->trigger();
            return $response;
        } catch (Exception $e) {
            throw new moodle_exception($e->getMessage());
        }
    }
}

