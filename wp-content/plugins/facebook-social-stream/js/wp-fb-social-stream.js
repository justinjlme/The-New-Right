jQuery(document).ready(function() {
	jQuery.ajax({
		type:'POST',
		data:{action:'wp_fb_social_stream_update'},
		url:wp_fb_social_stream_js_vars.ajaxurl,
		success: function(data) {}
	});		
});