(function ($) {

    $(function () {
        $(document).ajaxSuccess(function (event, xhr, settings) {
            if (settings.data.match(/action=add-tag/) && !xhr.responseText.match(/wp_error/)) {
                $('#term-color').wpColorPicker('color', '#FFFFFF');
                $('#term-color').val('');
            }
        });
    });

})(jQuery);


inlineEditTax.cmraedit = inlineEditTax.edit;


inlineEditTax.edit = function (id) {
    var tag_id = id;
    if (typeof (tag_id) === 'object') {
        tag_id = this.getId(tag_id);
    }

    inlineEditTax.cmraedit(id);

    var val = jQuery('td.cmra_color', '#tag-' + tag_id).text();
    val = val ? val : '#FFFFFF';

    //jQuery(':input[name="slug"]', '#edit-' + tag_id).closest('label').hide();

    jQuery(':input[name="cmra_color"]', '#edit-' + tag_id).wpColorPicker();
    jQuery(':input[name="cmra_color"]', '#edit-' + tag_id).wpColorPicker('color', val);

    return false;

};
