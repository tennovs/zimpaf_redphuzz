/**
 * ICON POPUP AddOns
 *
 * @type {GSF_ICON_POPUP|*|{}}
 */
var GSF_ICON_POPUP = GSF_ICON_POPUP || {};

(function ($) {
	"use strict";

	GSF_ICON_POPUP = {
		_$popup: [],
		_callback: function () {},
		_currentFontId: 0,
		_fonts: [],
		_currentSection: '',
		_currentIcon: '',

		init: function () {
			GSF_ICON_POPUP._$popup = $('#gsf-popup-icon-wrapper');

			if (GSF_ICON_POPUP._$popup.length === 0) {
				$.ajax({
					url: GSF_POPUP_DATA.ajaxUrl,
					type: 'post',
					data: {
						action: 'gsf_get_font_icons',
						nonce: GSF_POPUP_DATA.nonce
					},
					success: function (res) {
						var data = JSON.parse(res),
							template = wp.template('gsf-icons-popup'),
							fontName,
							groupIndex,
							groupLength,
							iconsIndex,
							iconsLength,
							iconKey;
						GSF_ICON_POPUP._fonts = data;

						if ($('#tmpl-gsf-icons-popup').length == 0) {
							return;
						}

						for (fontName in data) {
							if (GSF_ICON_POPUP._currentFontId === 0) {
								GSF_ICON_POPUP._currentFontId = fontName;
							}
							var data_all = {};
							data[fontName]['groups'] = [];

							groupLength = data[fontName].iconGroup.length;

							for (groupIndex = 0; groupIndex < groupLength; groupIndex++) {

								iconsLength = data[fontName].iconGroup[groupIndex].icons.length;
								data[fontName]['groups'][data[fontName].iconGroup[groupIndex]['id']] = iconKey = data[fontName].iconGroup[groupIndex].icons;

								for (iconsIndex = 0; iconsIndex < iconsLength; iconsIndex++) {
									iconKey = data[fontName].iconGroup[groupIndex].icons[iconsIndex];
									data_all[iconKey] = true;

								}
							}

							data[fontName]['groups'][''] = [];
							for (var icon in data_all) {
								data[fontName]['groups'][''].push(icon);
							}
						}

						var html = template(data);
						$('body').append(html);

						for (fontName in data) {
							delete GSF_ICON_POPUP._fonts[fontName].iconGroup;
							delete GSF_ICON_POPUP._fonts[fontName].label;
							delete GSF_ICON_POPUP._fonts[fontName].total;
						}
						GSF_ICON_POPUP._fonts = data;


						GSF_ICON_POPUP._$popup = $('#gsf-popup-icon-wrapper');

						GSF_ICON_POPUP.settingPopup();
						GSF_ICON_POPUP.popupListener();
					}
				});
			}

			GSF_ICON_POPUP.svg_icon();
			$('body').on('gsf_field_control_changed',function (e) {
				GSF_ICON_POPUP.svg_icon();
			});

		},
		settingPopup: function() {
			var $fontLinkInner = this._$popup.find('.gsf-popup-icon-font-link-inner'),
				$groupLink = $fontLinkInner.find('.gsf-popup-icon-group-link'),
				$sectionGroup = this._$popup.find('.gsf-popup-icon-group-section'),
				$iconsListing = this._$popup.find('.gsf-popup-icon-listing');

			$fontLinkInner.perfectScrollbar({
				wheelSpeed: 0.5,
				suppressScrollX: true
			});

			$iconsListing.perfectScrollbar({
				wheelSpeed: 0.5,
				suppressScrollX: true
			});

			$groupLink.css('display', 'none');
			$groupLink.first().css('display', 'block');

			$sectionGroup.css('display', 'none');
			$sectionGroup.first().css('display', 'block');

		},
		popupListener: function() {
			var $searchField = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-search > input'),
				$sectionLinkItem = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-group-link a'),
				$groupTitle = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-group-title'),
				$selectFontField = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-font > select');

			/**
			 * Search icon
			 */
			$searchField.on('keyup', function () {
				var filter = $(this).val().toLowerCase();

				GSF_ICON_POPUP._currentSection = '';

				if (filter === '') {
					$groupTitle.text($groupTitle.data('msg-all'));
				} else {
					$groupTitle.text($groupTitle.data('msg-search').replace('{0}', filter));
				}

				var icons_match = [];
				if (GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId] && GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups']) {
					icons_match = GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups'][''].filter(function (s) {
						return s.indexOf(filter) !== -1;
					});
				}

				GSF_ICON_POPUP.bindListFont(icons_match, false);

				/**
				 * Update Scroll Bar
				 */
				GSF_ICON_POPUP.updateListingScroll();
			});

			/**
			 * Filter icon by group
			 */
			$sectionLinkItem.on('click', function() {
				var $this = $(this),
					idSection = $this.data('id');

				GSF_ICON_POPUP._currentSection = idSection;

				$groupTitle.text($this.text());
				$searchField.val('');
				var icons_match = [];
				if (GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId] && GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups']) {
					icons_match = GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups'][idSection];
				}
				GSF_ICON_POPUP.bindListFont(icons_match, false);
				/**
				 * Update Scroll Bar
				 */
				GSF_ICON_POPUP.updateListingScroll();
			});

			/**
			 * Change font icon
			 */
			$selectFontField.on('change', function() {
				var $fontLinkInner = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-font-link-inner'),
					$groupLink = $fontLinkInner.find('.gsf-popup-icon-group-link'),
					$sectionGroup = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-group-section'),
					$searchField = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-search > input');

				GSF_ICON_POPUP._currentFontId = $(this).val();
				GSF_ICON_POPUP._currentSection = '';

				$groupLink.fadeOut();
				$sectionGroup.fadeOut();

				$groupLink.each(function() {
					var $this = $(this);
					if ($this.data('font-id') === GSF_ICON_POPUP._currentFontId) {
						$this.fadeIn(function() {
							GSF_ICON_POPUP.updateLinkScroll();
						});

					}
				});

				$sectionGroup.each(function() {
					var $this = $(this);
					if ($this.data('font-id') === GSF_ICON_POPUP._currentFontId) {
						$this.fadeIn(function() {
							GSF_ICON_POPUP.updateListingScroll();
						});

					}
				});

				$searchField.val('');
				$searchField.trigger('keyup');
			});

			/**
			 * Load more
			 */

			this.iconLoadMore();

		},
		iconLoadMore: function() {
			GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-group-load-more >  button').on('click', function () {
				var $this = $(this),
					$currentFont = $this.closest('.gsf-popup-icon-group-section'),
					keySearch = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-search > input').val(),
					total = $currentFont.find(' > ul > li').length;

				var icons_match = [];
				if (keySearch !== '') {
					if (GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId] && GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups']) {
						icons_match = GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups'][''].filter(function (s, i) {
							return (i >= total ) && (s.indexOf(keySearch) !== -1);
						});
					}
					GSF_ICON_POPUP.bindListFont(icons_match, true);
				}
				else {
					if (GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId] && GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups']) {
						icons_match = GSF_ICON_POPUP._fonts[GSF_ICON_POPUP._currentFontId]['groups'][GSF_ICON_POPUP._currentSection].filter(function (s, i) {
							return (i >= total );
						});
					}
					GSF_ICON_POPUP.bindListFont(icons_match, true);
				}
			});
		},
		iconClickEvent: function($elements) {
			$elements.find('i').on('click', function() {
				GSF_ICON_POPUP._$popup.find('.mfp-close').trigger('click');
				GSF_ICON_POPUP._callback($(this).attr('class'));
			});
		},
		updateLinkScroll: function() {
			$('.gsf-popup-icon-font-link-inner').perfectScrollbar('update');
		},
		updateListingScroll: function() {
			$('.gsf-popup-icon-listing').perfectScrollbar('update');
		},
		open: function (icon, callback) {
			var $searchField = GSF_ICON_POPUP._$popup.find('.gsf-popup-icon-search > input');
			GSF_ICON_POPUP._currentIcon = icon;
			$searchField.val('');
			$searchField.trigger('keyup');

			if (typeof (callback) === "function") {
				GSF_ICON_POPUP._callback = callback;
			}

			$.magnificPopup.open({
				items: {
					src: '#gsf-popup-icon-wrapper',
					type: 'inline'
				},
				mainClass: 'mfp-move-horizontal',
				callbacks: {
					open: function() {
						GSF_ICON_POPUP.updateLinkScroll();
						GSF_ICON_POPUP.updateListingScroll();
					}
				},
				openDelay: 0,
				removalDelay: 100,
				midClick: true
			});
		},
		close: function() {

		},
		bindListFont: function (arr, append) {
			var $currentFont = this._$popup.find('.gsf-popup-icon-group-section[data-font-id="' + this._currentFontId + '"]'),
				$loadMore = $currentFont.find(' > .gsf-popup-icon-group-load-more > button');
			var html = '';
			var count = 0;
			for (var i in arr) {
				count++;
				if (count > 160) {
					break;
				}
				if (arr[i] === this._currentIcon) {
					html += '<li title="' + arr[i] + '" class="active"><i class="' + arr[i] + '"></i></li>';
				}
				else {
					html += '<li title="' + arr[i] + '"><i class="' + arr[i] + '"></i></li>';
				}
			}
			var $html = $(html);

			GSF_ICON_POPUP.svg_icon($html);


			if (append) {
				$currentFont.find(' > ul').append($html);
			}
			else {
				$currentFont.find(' > ul').html($html);
			}

			this.iconClickEvent($html);


			if (count > 160) {
				$loadMore.show();
			}
			else {
				$loadMore.hide();
			}

		},
		svg_icon: function ($wrap) {
			if (typeof $wrap === "undefined") {
				$wrap = $('body');
			}

			$wrap.find('.svg-icon').each(function () {
				var $this = $(this),
					_class = $this.attr('class'),
					id = _class.replace('svg-icon svg-icon-',''),
					_html = '<svg class="' + _class + '" aria-hidden="true" role="img"> <use href="#'+ id +'" xlink:href="#'+ id +'"></use> </svg>';
				$this.html(_html);
			});
		}
	};
	$(document).ready(function () {
		GSF_ICON_POPUP.init();
	});
})(jQuery);