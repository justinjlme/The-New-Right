/**
 * This script is used to load the WordPress media library
 * @author: Dolatabadi
 */
jQuery(document).ready(function ($) {

    $(document).on('click', '.smw-add-media-button', function (event) {
        event.preventDefault();

        var button = $(this);
        var type = $(button).data('type');

        var frame = wp.media({
            multiple: false,

            library: {
                type: type
            }
        });

        frame.on('select', function () {

            var attachment = frame.state().get('selection').first().toJSON();
            button.prev().val(attachment.url);

        });
        frame.open();
    });

});