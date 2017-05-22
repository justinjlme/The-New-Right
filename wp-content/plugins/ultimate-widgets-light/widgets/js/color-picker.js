jQuery(document).ready(function($) {

    $('#widgets-right .color-picker, .inactive-sidebar .color-picker').wpColorPicker();

    // Executes wpColorPicker function after AJAX is fired on saving the widget
    $(document).ajaxComplete(function() {
        $('#widgets-right .color-picker, .inactive-sidebar .color-picker').wpColorPicker();
    });
});