(function($) {
	
	$(document).ready(function($){
		
		// Hide wrap and form background if style three is not selected
		$('body').on('change', '.uwl-container select[id$="mailchimpstyle"]', function(e){
			var mailchimpstyle = $(this);
			if ( mailchimpstyle.val() != 'style-three' ) {
				mailchimpstyle.closest('.uwl-container').find('.style_wrap').val('').animate({opacity: 'hide' , height: 'hide'}, 200);
				mailchimpstyle.closest('.uwl-container').find('input[id$="mailchimpbtn"]').parent().animate({opacity: 'show' , height: 'show'}, 200);
				mailchimpstyle.closest('.uwl-container').find('input[id$="style_three_btn"]').parent().animate({opacity: 'hide' , height: 'hide'}, 200);
			} else {
				mailchimpstyle.closest('.uwl-container').find('.style_wrap').animate({opacity: 'show' , height: 'show'}, 200);
				mailchimpstyle.closest('.uwl-container').find('input[id$="mailchimpbtn"]').parent().animate({opacity: 'hide' , height: 'hide'}, 200);
				mailchimpstyle.closest('.uwl-container').find('input[id$="style_three_btn"]').parent().animate({opacity: 'show' , height: 'show'}, 200);
			}
		});

	}); // Document Ready

})(jQuery);