(function ($) {

    if (typeof Drupal != 'undefined') {
        Drupal.behaviors.crescentAmeriflowCustom = {
            attach: function (context, settings) {
                init();
            },

            completedCallback: function () {
                // Do nothing. But it's here in case other modules/themes want to override it.
            }
        }
    }


    function init() {
        careers_resume_auto_upload();
    }


    function careers_resume_auto_upload() {
        $('.form-item input.form-submit[value=Upload]', ".form-careers").hide();
        $('.form-item input.form-file', ".form-careers").change(function () {
            $parent = $(this).closest('.form-item');

            //setTimeout to allow for validation
            //would prefer an event, but there isn't one
            setTimeout(function () {
                //if (!$('.error', $parent).length) {
                    $('input.form-submit[value=Upload]', $parent).mousedown();
               // }
            }, 300);
        });

    }


})(jQuery);