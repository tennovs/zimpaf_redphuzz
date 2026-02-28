var ERE_property_featured = ERE_property_featured || {};
(function ($) {
	"use strict";
	var checkFilter = true,
		isRTL = $('body').hasClass('rtl');
	ERE_property_featured = {
		vars: {
			filter_id: '',
			property_items: []
		},
		init: function () {
			this.filterCarousel();
			this.resize();
			ERE.select_term();
			this.cityFilterScrollBar();
			var $propertySyncWrap = $('.property-sync-content-wrap');
			$propertySyncWrap.each(function () {
				ERE_property_featured.syncPropertyCarousel($(this));
			});
			this.calcPaddingTopBottom();
			setTimeout( this.calcPaddingTopBottom,300 );
			setTimeout( this.calcPaddingTopBottom,1000 );
		},
		filterCarousel: function () {
			var property_owl_filter = $('.ere-property-featured [data-filter-type="carousel"]');
			property_owl_filter.each(function () {
				var objectClick = $('a', $(this));
				ERE_property_featured.executeFilter(objectClick);
			});
		},
		executeFilter: function (objectClick) {
			objectClick.on('click', function (event) {
				event.preventDefault();
				var thisObject = $(this),
					filterId = thisObject.parent().attr('data-filter_id'),
					$property_content = $('.property-content[data-filter_id="'+filterId+'"]'),
					ere_property = $property_content.parent();
				if (thisObject.hasClass('active-filter')) {
					thisObject.css('cursor', 'not-allowed');
					return false;
				} else {
					thisObject.parent().children('a').css('cursor', 'wait');
					if (checkFilter) {
						checkFilter = false;
						var dataFilter = thisObject.data('filter'),
							select_filter = thisObject.parent().next().children('select');
						ERE_property_featured.vars.filter_id = thisObject.parent().data('filter_id');
						thisObject.parent().find('.active-filter').removeClass('active-filter');
						thisObject.addClass('active-filter');
						ere_property.css('height',ere_property.outerHeight());
						if (typeof ERE_property_featured.vars.property_items[dataFilter + '-' + ERE_property_featured.vars.filter_id] == 'undefined') {
							thisObject.css('width', thisObject.outerWidth());
							var $ajax_url = objectClick.closest('.filter-wrap').data('admin-url');
							$.ajax({
								url: $ajax_url,
								data: {
									action: 'ere_property_featured_fillter_city_ajax',
									layout_style: thisObject.parent().data('layout_style'),
									property_type: thisObject.parent().data('property_type'),
									property_status: thisObject.parent().data('property_status'),
									property_feature: thisObject.parent().data('property_feature'),
									property_cities : thisObject.parent().data('property_cities'),
									property_state: thisObject.parent().data('property_state'),
									property_neighborhood : thisObject.parent().data('property_neighborhood'),
									property_label : thisObject.parent().data('property_label'),
									color_scheme: thisObject.parent().data('color_scheme'),
									item_amount : thisObject.parent().data('item_amount'),
									image_size: thisObject.parent().data('image_size'),
									include_heading: thisObject.parent().data('include_heading'),
									heading_sub_title : thisObject.parent().data('heading_sub_title'),
									heading_title : thisObject.parent().data('heading_title'),
									heading_text_align : thisObject.parent().data('heading_text_align'),
									property_city: thisObject.data('filter'),
									el_class: thisObject.parent().data('el_class')
								},
								success: function (html) {
									var $newElems = $('.property-item', html);
									ERE_property_featured.vars.property_items[dataFilter + '-' + ERE_property_featured.vars.filter_id] = html;

									$property_content.css('opacity', 0);
									$property_content.trigger('destroy.owl.carousel');
									$property_content.html($newElems);
									$property_content.css('opacity', 1);
									$property_content.imagesLoaded(function () {
										ERE.set_item_effect($newElems, 'hide');
										ERE_Carousel.owlCarousel();
										$newElems = $('.property-item', $property_content);
										ERE.set_item_effect($newElems, 'show');
										setTimeout(function(){
											ere_property.css('height','auto');
										}, 200);
									});
									setTimeout(function () {
										thisObject.css('width', 'auto');
									},100);
									checkFilter = true;
									select_filter.removeAttr('disabled');
									select_filter.children('option').removeAttr('selected');
									select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');

									thisObject.parent().children('a').css('cursor', 'pointer');
									thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
								},
								error: function () {
									checkFilter = true;
								}
							});
						} else {
							var old_data = ERE_property_featured.vars.property_items[dataFilter + '-' + ERE_property_featured.vars.filter_id],
								$newElems = $('.property-item', old_data);
							$property_content.css('opacity', 0);
							$property_content.trigger('destroy.owl.carousel');
							$property_content.html($newElems);
							ERE.set_item_effect($newElems, 'hide');
							$property_content.css('opacity', 1);
							ERE_Carousel.owlCarousel();
							$property_content.imagesLoaded(function () {
								$newElems = $('.property-item', $property_content);
								ERE.set_item_effect($newElems, 'show');
								setTimeout(function(){
									ere_property.css('height','auto');
								}, 200);
							});
							checkFilter = true;
							select_filter.removeAttr('disabled');
							select_filter.children('option').removeAttr('selected');
							select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
							thisObject.parent().children('a').css('cursor', 'pointer');
							thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
						}
					}
				}
			});
		},
		resize: function () {
			$(window).resize(function () {
				ERE_property_featured.executeResize();
			});
			$(window).on('orientationchange', function () {
				ERE_property_featured.executeResize();
			});
		},
		executeResize: function () {
			$('.property-content.owl-carousel').each(function () {
				var container = $(this);
				setTimeout(function () {
					var $items = $('.property-item', container);
					ERE.set_item_effect($items, 'show');
				}, 500);
			});
			ERE_property_featured.cityFilterScrollBar();
			var $propertySyncWrap = $('.property-sync-content-wrap');
			$propertySyncWrap.each(function () {
				ERE_property_featured.syncPropertyCarousel($(this));
			});
			ERE_property_featured.calcPaddingTopBottom();
		},
		cityFilterScrollBar: function () {
			$('.property-filter-content', '.property-cities-filter').each(function () {
				var $this = $(this);
				if ($this.outerHeight() > 530 ) {
					$this.css('height', '530px');
					$this.css('overflow-y', 'auto');
					if ($.isFunction($.fn.perfectScrollbar)) {
						$this.perfectScrollbar({
							wheelSpeed: 0.5,
							suppressScrollX: true
						});
					}
				} else {
					$this.css('height', 'auto');
				}
			});
		},
		syncPropertyCarousel: function($propertySyncWrap){
			var $sliderMain = $propertySyncWrap.find('.property-content-carousel'),
				$sliderThumb = $propertySyncWrap.find('.property-image-carousel');
			$sliderMain.owlCarousel({
				items: 1,
				nav: false,
				navElement : 'div',
				dots:false,
				loop: false,
				smartSpeed: 500,
				rtl: isRTL
			}).on('changed.owl.carousel', syncPosition);

			$sliderThumb.on('initialized.owl.carousel', function () {
				$sliderThumb.find(".owl-item").eq(0).addClass("current");
			}).owlCarousel({
				items : 1,
				nav:true,
				navElement : 'div',
				navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
				dots: false,
				rtl: isRTL,
				margin: 0
			}).on('changed.owl.carousel', syncPosition2);

			function syncPosition(el){
				//if you set loop to false, you have to restore this next line
				var current = el.item.index;

				$sliderThumb
					.find(".owl-item")
					.removeClass("current")
					.eq(current)
					.addClass("current");
				var onscreen = $sliderThumb.find('.owl-item.active').length - 1;
				var start = $sliderThumb.find('.owl-item.active').first().index();
				var end = $sliderThumb.find('.owl-item.active').last().index();

				if (current > end) {
					$sliderThumb.data('owl.carousel').to(current, 500, true);
				}
				if (current < start) {
					$sliderThumb.data('owl.carousel').to(current - onscreen, 500, true);
				}
			}

			function syncPosition2(el) {
				var number = el.item.index;
				$sliderMain.data('owl.carousel').to(number, 500, true);
			}
		},
		calcPaddingTopBottom: function () {
			$('.main-content-inner', '.property-sync-carousel').each(function () {
				var $this = $(this),
					$thisHeight = $this.height(),
					$parentHeight = $this.parent().next('.property-image-content').children().outerHeight(),
					$differenceHeight = parseInt($parentHeight) - parseInt($thisHeight);
				if ($differenceHeight > 0 && window.matchMedia('(min-width: 1200px)').matches) {
					$this.css({
						'padding-top': $differenceHeight / 2 + 'px',
						'padding-bottom': $differenceHeight / 2 + 'px'
					});
				} else {
					$this.css({
						'padding-top': '',
						'padding-bottom': ''
					});
				}
			});
		}
	};
	$(document).ready(function () {
		if (!$('body').hasClass('elementor-editor-active')) {
			ERE_property_featured.init();
		}
	});
})(jQuery);