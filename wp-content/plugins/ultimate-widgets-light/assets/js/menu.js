var $j = jQuery.noConflict();

$j(document).ready(function() {
	"use strict";
	$j('ul.uwl-menu li.has-sub .uwl-sub-icon, ul.uwl-menu li.has-sub a[href*="#"]').on('click', function(e){
	    e.preventDefault();
	    if ($j(this).closest('li.has-sub').find("> ul.uwl-sub-menu").is(":visible")){
			$j(this).closest('li.has-sub').find("> ul.uwl-sub-menu").slideUp(200);
			$j(this).closest('li.has-sub').removeClass('open-sub');
		} else {
			$j(this).closest('li.has-sub').addClass('open-sub');
			$j(this).closest('li.has-sub').find("> ul.uwl-sub-menu").slideDown(200);
		}
	});
});