var ERE_Compare = ERE_Compare || {};
(function ($) {
	'use strict';
	if (typeof ere_compare_vars !== "undefined") {
		var ajax_url = ere_compare_vars.ajax_url,
			compare_button_url = ere_compare_vars.compare_button_url,
			alert_title = ere_compare_vars.alert_title,
			alert_message = ere_compare_vars.alert_message,
			alert_not_found = ere_compare_vars.alert_not_found,
			compare_listings = $('#compare-listings'),
			item = $('.compare-property', '#compare-properties-listings').length;
	}
	ERE_Compare = {
		init: function () {
			this.register_event_compare();
			this.compare_property();
			this.open_compare();
			this.close_compare();
			this.compare_listing();
		},
		register_event_compare: function () {
			$(document).on('click','a.compare-property', function (e) {
				if (!$(this).hasClass('on-handle')) {
					e.preventDefault();
					var $this = $(this).addClass('on-handle'),
						property_inner = $this.closest('.property-inner').addClass('property-active-hover'),
						property_id = $this.data('property-id');
					$('.listing-btn').removeClass('hidden');

					if (item == 4) {
						if ($this.children().hasClass('plus')) {
							item--;
							$this.find('i.fa-minus').removeClass('fa-minus').addClass('fa-spinner fa-spin');
						}
						else {
							ERE.popup_alert('fa fa-check-squaere-o', alert_title, alert_message);
						}
					}
					else {
						if (!($this.children().hasClass('plus'))) {
							item++;
							$this.find('i.fa-plus').removeClass('fa-plus').addClass('fa-spinner fa-spin minus');
						}
						else {
							item--;
							$this.find('i.fa-minus').removeClass('fa-minus').addClass('fa-spinner fa-spin');
						}
					}

					$.ajax ({
						url: ajax_url,
						method: 'post',
						data: {
							action: 'ere_compare_add_remove_property_ajax',
							property_id: property_id
						},
						success: function (html) {
							if (($this.children().hasClass('minus'))) {
								$this.find('i.minus').removeClass('fa-spinner fa-spin minus').addClass('fa-minus plus');
							} else {
								$this.find('i.fa-spinner').removeClass('fa-spinner fa-spin plus').addClass('fa-plus');
							}
							$('div#compare-properties-listings').replaceWith(html);
							ERE_Compare.compare_listing();
							if (item == 0) {
								$('.listing-btn').addClass('hidden');
								ERE_Compare.close_compare();
							} else {
								ERE_Compare.open_compare();
							}
							$this.removeClass('on-handle');
							property_inner.removeClass('property-active-hover');
						}
					});
				}
			});
		},
		compare_listing: function () {
			$('.listing-btn').off('click').on('click', function () {
				if (compare_listings.hasClass('listing-open')) {
					compare_listings.removeClass('listing-open');
					$('.listing-btn').find('i.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-left');
				} else {
					compare_listings.addClass('listing-open');
					$('.listing-btn').find('i.fa-angle-left').removeClass('fa-angle-left').addClass('fa-angle-right');
				}
			});
		},
		open_compare: function () {
			compare_listings.addClass('listing-open');
			$('.listing-btn').find('i.fa-angle-left').removeClass('fa-angle-left').addClass('fa-angle-right');
		},
		close_compare: function () {
			if (compare_listings.hasClass('listing-open')) {
				compare_listings.removeClass('listing-open');
				$('.listing-btn').find('i.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-left');
			}
		},
		compare_property: function () {
			if (compare_listings.length == 1) {
				$('div.compare-property').each(function () {
					var property_id = $(this).attr('data-property-id'),
						property = $("a[data-property-id='" + property_id + "']");
					$('i.fa-plus', property).removeClass('fa-plus').addClass('fa-minus plus');
				});

				ERE_Compare.compare_listing();

				if ($('.compare-property').length > 0) {
					// Add, update Element compare to listing
					var handle = true;

					ERE_Compare.register_event_compare(item);
					// Delete element from compare listing
					var $handle = true;
					$(document).on('click', '#compare-properties-listings .compare-property-remove', function (e) {
						e.preventDefault();
						if($handle) {
							$handle = false;
							var $this = $(this),
								property_id = $this.parent().attr('data-property-id'),
								property = $("a[data-property-id='" + property_id + "']");
							$this.parent().addClass('remove');
							$('i.plus', property).removeClass('fa-minus plus').addClass('fa-plus');

							item--;
							if (item == 0) {
								$('#compare-properties-listings').addClass('hidden');
								$('.listing-btn').addClass('hidden');
								ERE_Compare.close_compare();
							}
							$.ajax({
								url: ajax_url,
								method: 'post',
								data: {
									action: 'ere_compare_add_remove_property_ajax',
									property_id: property_id
								},
								success: function (html) {
									$('div#compare-properties-listings').replaceWith(html);
									ERE_Compare.compare_listing();
									if (item == 0) {
										$('.listing-btn').addClass('hidden');
										ERE_Compare.close_compare();
									} else {
										ERE_Compare.open_compare();
									}
									$handle = true;
								},
								error: function () {
									$handle = true;
								}
							});
						}
					});

					// Go to Page Compare
					$(document).on('click', '.compare-properties-button', function () {
						if (compare_button_url != "") {
							window.location.href = compare_button_url;
						} else {
							alert(alert_not_found);
						}
						return false;
					});
				}
			}
		}
	};
	$(document).ready(function () {
		ERE_Compare.init();
	});
})(jQuery);