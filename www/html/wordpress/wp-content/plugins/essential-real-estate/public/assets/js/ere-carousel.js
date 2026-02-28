var ERE_Carousel = ERE_Carousel || {};
(function( $ ) {
	'use strict';
	var isRTL = $('body').hasClass('rtl');
	ERE_Carousel = {
		init: function () {
			this.owlCarousel();
		},
		owlCarousel: function () {
			$('.owl-carousel:not(.ere-carousel-manual):not(.owl-loaded)').each(function () {
				var slider = $(this);
				var defaults = {
					items: 4,
					nav: false,
					navElement: 'div',
					navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
					dots: false,
					loop: false,
					center: false,
					mouseDrag: true,
					touchDrag: true,
					pullDrag: true,
					freeDrag: false,
					margin: 0,
					stagePadding: 0,
					merge: false,
					mergeFit: true,
					autoWidth: false,
					startPosition: 0,
					rtl: isRTL,
					smartSpeed: 250,
					fluidSpeed: false,
					dragEndSpeed: false,
					autoplayHoverPause: false,
					onInitialized:function(event){
						var element   = event.target;
						$(element).trigger('owlInitialized');
					}
				};
				var config = $.extend({}, defaults, slider.data("plugin-options"));
				// Initialize Slider
				slider.owlCarousel(config);
			});
		}
	};
	$(document).ready(function () {
		ERE_Carousel.init();
	});

})( jQuery );