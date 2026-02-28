(function ($) {
	"use strict";
	$(document).ready(function () {
		function ere_agent_paging() {
			var handle = true;
			$('.paging-navigation', '.agent-paging-wrap').each(function () {
				$('a', $(this)).off('click').on('click', function (event) {
					event.preventDefault();
					if(handle) {
						handle = false;
						var $this = $(this);
						var href = $this.attr('href'),
							data_paged = ERE.get_page_number_from_href(href),
							data_contain = $this.closest('.agent-paging-wrap'),
							agent_content = $this.closest('.ere-agent');

						$.ajax({
							url: data_contain.data('admin-url'),
							data: {
								action: 'ere_agent_paging_ajax',
								layout: data_contain.data('layout'),
								items: data_contain.data('items'),
								item_amount: data_contain.data('item-amount'),
								image_size: data_contain.data('image-size'),
								show_paging: data_contain.data('show-paging'),
								post_not_in: data_contain.data('post-not-in'),
								paged: data_paged
							},
							success: function (html) {
								var $newElems = $('.agent-item', html),
									paging = $('.agent-paging-wrap', html);

								agent_content.css('opacity', 0);

								agent_content.html($newElems);
								ERE.set_item_effect($newElems, 'hide');
								var contentTop = agent_content.offset().top - 30;
								$('html,body').animate({scrollTop: +contentTop + 'px'}, 500);
								agent_content.css('opacity', 1);
								agent_content.imagesLoaded(function () {
									$newElems = $('.agent-item', agent_content);
									ERE.set_item_effect($newElems, 'show');
									paging.insertAfter(agent_content.children('div:last-child'));
									paging.before('<div class="clearfix"></div>');
									ere_agent_paging();
									ere_agent_paging_control();
								});
								handle = true;
							},
							error: function () {
								handle = true;
							}
						});
					}
				})
			});
		}
		ere_agent_paging();
		function ere_agent_paging_control() {
			$('.paging-navigation', '.ere-agent').each(function () {
				var $this = $(this);
				if($this.find('a.next').length === 0) {
					$this.addClass('next-disable');
				} else {
					$this.removeClass('next-disable');
				}
			});
		}
	});
})(jQuery);