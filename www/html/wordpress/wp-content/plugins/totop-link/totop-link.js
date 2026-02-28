jQuery(function() {
	jQuery(window).scroll(function() {
		var soffset = jQuery('#toTop').data('scroll-offset');
		soffset = (soffset) ? soffset : 0;
		if(jQuery(this).scrollTop() > soffset) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
 
	jQuery('#toTop').click(function() {
		var sspeed = jQuery(this).data('scroll-speed');
		jQuery('body,html').animate({scrollTop:0},sspeed);
	});	
});