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
 * Defines video render script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import $ from 'jquery';
import videojs from 'media_videojs/video-lazy';
import ModalEvents from 'core/modal_events';
import Templates from 'core/templates';
import Modal from 'core/modal';

export const init = async () => {
    $(document).on('click', '.renderervideo', function(){
        var data = $(this).data();
        const getData = async () => {
            const modal = await Modal.create({
                title: data.title,
                body: Templates.render('mod_zatuk/preview_block', data),
            });
            modal.show();
            modal.getRoot().on(ModalEvents.bodyRendered, function(){
                const player = videojs('rendervideo_'+data.id);
                player.src({
                    autoplay:true,
                    src: data.zatukurl,
                    type: 'application/x-mpegURL'
                });
                player.on('loadedmetadata', function() {
                   $('#zatuk_duration').val(player.duration().toFixed(0));
                });
            });
            modal.getRoot().on(ModalEvents.hidden, function(){
                var currentVideo = videojs('rendervideo_'+data.id);
                currentVideo.dispose();
            });
        };
        getData();
    });
};
