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
 * Defines player script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery',
        'core/str',
        'media_videojs/video-lazy',
        'core/ajax'],
        function($, Str,videojs, Ajax){
            return {
                load: function(args){
                    var values = JSON.parse(args);
                    const player = videojs(values.identifier);
                    var myVideoPlayer = document.getElementById('mod_zatuk_form_video');
                    player.src({
                        src: values.src,
                        type: 'application/x-mpegURL'
                    });
                     player.hlsQualitySelector({
                       displayCurrentQuality: true,
                    });
                    if(typeof(myVideoPlayer) != 'undefined'  && myVideoPlayer !== null){
                        myVideoPlayer.onloadedmetadata = function() {
                        };
                        myVideoPlayer.addEventListener('loadedmetadata', function () {
                            $('#zatuk_duration').val(myVideoPlayer.duration.toFixed(0));
                        });
                    }
                    player.on("pause", function() {
                        var currenttime = player.currentTime();
                        var lengthOfVideo = player.duration();
                            var promises = Ajax.call([{
                                methodname: 'mod_zatukattempts',
                                args: {
                                    moduleid:values.cm,
                                    courseid:values.course,
                                    duration:lengthOfVideo,
                                    currenttime: currenttime,
                                    event:'pause'
                                },
                            }]);
                            promises[0].done(function() {
                            }).fail(function() {
                            });
                    });
            }
        };
    });
