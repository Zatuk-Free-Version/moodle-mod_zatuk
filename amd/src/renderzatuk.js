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
define(['jquery',
        'media_videojs/video-lazy',
        'core/modal_events',
        'core/modal_factory',
        'core/templates'
        ], function($,
        videojs,
        ModalEvents,
        ModalFactory,
        Templates) {
    return {
        init: function() {
          $(document).on('click', '.renderervideo', function(){
            var data = $(this).data();
            ModalFactory.create({
                title: data.title,
                type: ModalFactory.types.DEFAULT,
                body: Templates.render('mod_zatuk/previewblock', data),
            }).done(function(modal) {
                modal.show();
                modal.getRoot().on(ModalEvents.shown, function(){
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
            }.bind(this));
          });

        }
    };
});
