(function($){
	$(document).ready(function () {
		$('.ere-insert-shortcode-button').on('click',function(){
			ERE_POPUP.required_element();
			ERE_POPUP.reset_fileds();
			$.magnificPopup.open({
				mainClass: 'mfp-zoom-in',
				items: {
					src: '#ere-input-shortcode'
				},
				type: 'inline',
				removalDelay: 500
			}, 0);
		});
	});
})(jQuery);
