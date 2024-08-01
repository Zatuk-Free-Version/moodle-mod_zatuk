/**
 * Streaming video js
 *
 * @module     mod_zatuk/zatuk
 * @class      zatuk
 * @package    mod_zatuk
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
