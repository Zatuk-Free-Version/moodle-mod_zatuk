M.zatuk_url = {};

M.zatuk_url.init = function(Y, options) {
    require(['jquery', 'core/modal_factory', 'core/str'], function($, ModalFactory, Str){
        const confirmbox = (message) => {
            ModalFactory.create({
                body: message,
                type: ModalFactory.types.ALERT,
                buttons: {
                    ok: Str.get_string('Thank_you'),
                },
                removeOnClose: true,
            }).done(function(modal) {
                modal.show();
            });
        };
        options.formcallback = M.zatuk_url.callback;
        if(typeof(options.client_id) == 'undefined'){
            confirmbox(Str.get_string('enablezatuk','mod_zatuk'));
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
        videoparams = params.url.split('/');
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
