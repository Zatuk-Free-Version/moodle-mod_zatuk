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
 * zatuk module renderer.
 *
 * @since      Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_zatuk_renderer extends plugin_renderer_base {
    /**
     * Get zatuk data.
     * @param array $zatukinfo
     * @param array $params
     * @return array
     */
    public function zatukrender($zatukinfo, $params) {
        $content = $zatukinfo['returndata'] ? $zatukinfo['returndata'] : 0;
        $total = $zatukinfo['total'] ? $zatukinfo['total'] : 0;
        $data = [];
        foreach ($content as $zatuk) {
            $data[] = $this->render_from_template('mod_zatuk/video_card', $zatuk);
        }
        if (!empty($data)) {
            $data = [$this->handleemptyelements($data, $params['length'] - 1)];
        }
        $outputs = [
            "draw" => isset($params['draw']) ? intval($params['draw']) : 1,
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $total,
            "data" => json_encode($data, true),
        ];
        return $outputs;
    }
    /**
     * Get uploaded zatuk data.
     * @param array $uploaddata
     * @param array $params
     * @return array
     */
    public function uploadrender($uploaddata, $params) {
        global $DB;
        $systemcontext = \context_system::instance();
        $content = $uploaddata['content'];
        $total = $uploaddata['total'];
        $tdata = [];
        foreach ($content as $video) {
            $data = [];
            $data['id'] = $video->id;
            $data['title'] = $video->title;
            $data['tagsname'] = $video->tagsname;
            $thumbnaillogourl = $this->get_thumbnail_url();
            $data['thumbnail'] = $thumbnaillogourl;
            $data['usercreated'] = $video->usercreated;
            $data['timecreated'] = date('d M Y', $video->timecreated);
            $data['status'] = $video->status == 0 ? 'Not Synced' : 'Synced at '.date('d M Y', $video->uploaded_on);
            $conditiona = ($video->status == 0 &&
                             (is_siteadmin() ||
                              has_capability('mod/zatuk:deletevideo', $systemcontext))
                            );
            $data['delete_enable'] = $conditiona ? true : false;
            $conditionb = ($video->status == 0 &&
                          (is_siteadmin() ||
                          has_capability('mod/zatuk:editvideo', $systemcontext))
                        );
            $data['edit_enable'] = $conditionb ? true : false;
            $tdata[] = $this->render_from_template('mod_zatuk/video_card', $data);
        }
        if (!empty($tdata)) {
            $tdata = [$this->handleemptyelements($tdata, $params['length'])];
        }
        $outputs = [
            "draw" => isset($params['draw']) ? intval($params['draw']) : 1,
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $total,
            "data" => json_encode($tdata, true),
        ];
        return $outputs;
    }
    /**
     * Get thumbnail url.
     * @return string
     */
    public function get_thumbnail_url() {
        global $DB;

        $thumbnaillogourl = $this->image_url('video', 'mod_zatuk');

        return $thumbnaillogourl;
    }
    /**
     * Describes the empty element handle function.
     * @param array $data
     * @param int $count
     * @return array
     */
    public function handleemptyelements($data, $count) {
        if (count($data) == $count) {
            $lists = [];
        } else {
            $lists = range(count($data) - 1, $count);
            $returndata = $data;
            foreach ($lists as $list) {
                $returndata[] = null;
            }
        }
        return $returndata;
    }
    /**
     * Upload zatuk video.
     * @return string
     */
    public function uploadedvideos() {
         $condition = ['tableid' => 'zatuk_uploaded_videos_data',
                     'function' => 'zatuk_uploaded_videos_data',
                     'nodatastring' => get_string('novideosuploadedyet', 'mod_zatuk'),
                    ];
         return $this->render_from_template('mod_zatuk/zatuk_videos', $condition);
    }
    /**
     * Get zatuk videos.
     * @return string
     */
    public function zatukvideos() {
        $condition = ['tableid' => 'get_zatuk_data',
                            'function' => 'get_zatuk_data',
                            'nodatastring' => get_string('zatukingnotyetset', 'mod_zatuk'),
                        ];
        return $this->render_from_template('mod_zatuk/zatuk_videos', $condition);
    }
    /**
     * Render zatuk content.
     * @return string
     */
    public function render_content() {
        $zatuk = new \mod_zatuk\zatuk();
        $content = $zatuk->mod_content();
        return $this->render_from_template('mod_zatuk/block_content', $content);
    }
}


