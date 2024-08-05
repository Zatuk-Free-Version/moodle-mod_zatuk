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
 * Defines zatuk player script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery',
        'media_videojs/video-lazy', 
        'core/ajax',
        'mod_zatuk/videojs-playbackrate-adjuster',
        'mod_zatuk/videojs-contrib-quality-levels',
        'mod_zatuk/videojs-hls-quality-selector'], 
    function($,videojs, Ajax){
        return {
            load: function(args){
                var values = JSON.parse(args);
                const player = videojs(values.identifier);
                player.src({
                    src: values.src,
                    type: 'application/x-mpegURL'
                });

                 player.hlsQualitySelector({
                   displayCurrentQuality: true,
                });

                player.on('loadedmetadata', function() {
                     $('#zatuk_duration').val(player.duration().toFixed(0));
                });
            }
        }
    });
