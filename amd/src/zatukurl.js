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
 * Defines zatuk content script.
 *
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
M.zatuk_url = {};

M.zatuk_url.init = function(Y, options) {
    require(['jquery', 'mod_zatuk/messagemodal', 'core/str'], function($, messagemodal, Str){
       let MessageModal = new messagemodal();
        options.formcallback = M.zatuk_url.callback;
        if(typeof(options.client_id) == 'undefined'){
            MessageModal.confirmbox(Str.get_string('enablezatuk','mod_zatuk'));
        }
        if (!M.core_filepicker.instances[options.client_id]) {
            M.core_filepicker.init(Y, options);
        }
        Y.on('click', function(e, client_id) {
            e.preventDefault();
            M.core_filepicker.instances[client_id].show();
        }, '#filepicker-button-js-'+options.client_id, null, options.client_id);
    });
};

M.zatuk_url.callback = function (params) {
    require(['media_videojs/video-lazy', 'jquery'], function(videojs, $){
        var videoparams = params.url.split('/');
        var videoidIndex = videoparams.length-2;
        $('#zatuk_external_url').val(params.url);
        $('#zatuk_external_videoid').val(videoparams[videoidIndex]);
        $('.zatuk_file_selector').show();
        const player = videojs('mod_zatuk_form_video');
        player.src({
            autoplay:true,
            src: params.url,
            type: 'application/x-mpegURL'
        });
       player.on('loadedmetadata', function() {
         $('#zatuk_duration').val(player.duration().toFixed(0));
        });
    });
};
