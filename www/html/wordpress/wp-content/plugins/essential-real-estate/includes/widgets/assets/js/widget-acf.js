var ERE_Widget_Acf = ERE_Widget_Acf || {};
(function($){
	"use strict";
	ERE_Widget_Acf = {
		templates : [],
		init : function() {

			this.collapse();
			this.changeTitle();
			this.uploadImage();
			this.addSection();
			this.deleteSection();
			$(document).on('widget_acf_make_template_done',ERE_Widget_Acf.initControls);
			$(document).on('widget-added', ERE_Widget_Acf.makeTemplate);
			$(document).on('widget-updated', ERE_Widget_Acf.makeTemplate);
		},
		makeTemplate: function(event, widget){
			var $wrapper = widget;
			if (typeof (widget) == "undefined") {
				$wrapper = 	$('#widgets-right .widget_acf_wrap');
			}

			var index = 0;
			ERE_Widget_Acf.templates = [];
			$('.accordion-wrap',$wrapper).each(function(){
				var clone = $('.widget_acf_accordion',this).last();
				if (clone.length) {
					ERE_Widget_Acf.templates[index] = clone[0].outerHTML;
					$(this).attr('data-template',index);
					index++;
				}
			});
			$(document).trigger('widget_acf_make_template_done',[$wrapper]);
		},
		sortable : function($wrap) {
			if ($.isFunction($.fn.sortable)){
				$('.accordion-wrap',$wrap).each(function(){
					var $this = $(this);
					$this.sortable({
						update: function(){
							ERE_Widget_Acf.reIndexSection($this);
						}
					});
				});
			}
		},
		collapse : function() {
			$(document).on('click','.widget_acf_accordion h3.title',function(){
				var $parent = $(this).parent();
				var $fieldset = $('.fieldset', $parent);
				var $collapse = $fieldset.attr('data-collapse');
				if ((typeof $collapse) == 'undefined' || $collapse == '1') {
					$fieldset.slideDown();
					$fieldset.attr('data-collapse', '0');
					$('span', $(this)).removeClass('collapse-in');
					$('span', $(this)).addClass('collapse-out');
				} else {
					$fieldset.slideUp();
					$fieldset.attr('data-collapse', '1');
					$('span', $(this)).removeClass('collapse-out');
					$('span', $(this)).addClass('collapse-in');
				}
			});
		},
		changeTitle : function() {
			$(document).on('keyup','.widget_acf_accordion input[data-title="1"]',function(){
				var $title = $(this).val();
				var $parent = $(this).attr('data-section-id');
				if ($title == ''){
					$title = 'New Section';
				}
				$('span:last-child', '#' + $parent + ' h3.title').text($title);
			});
		},
		addSection : function() {
			$(document).on('click','.widget_acf_wrap .button.add',function(){
				var $section_wrap = $(this).parent().prev();
				var templateIndex = $section_wrap.data('template');
				var $element = $(ERE_Widget_Acf.templates[templateIndex]);
				$section_wrap.append($element);
				ERE_Widget_Acf.reIndexSection($section_wrap);
				ERE_Widget_Acf.initControls(null,$element);
			});
		},
		deleteSection : function(){
			$(document).on('click','.widget_acf_accordion .button.deletion',function(){
				var $wrap = $(this).parent().parent().parent().parent();
				var $items = $('.widget_acf_accordion', $wrap).length;
				var $data_section_id = $(this).parent().parent().parent();
				if ($items > 1) {
					$data_section_id.remove();
					ERE_Widget_Acf.reIndexSection($wrap);
				} else {
					$('input', $($wrap)).each(function () {
						$(this).val('');
					});
					$('h3.title span', $($wrap)).last().html('New Section');
				}

				$($wrap).find('input').trigger('change');
			});
		},
		reIndexSection : function($wrap) {
			var $section = '.widget_acf_accordion';
			var $prefix_id = 'widget_acf_accordion_';
			$($section, $wrap).each(function ($index) {
				$(this).attr('id', $prefix_id + $index);
				$('input,textarea', $(this)).each(function () {
					if (typeof $(this).attr('name') != 'undefined') {
						$(this).attr(
							"name", $(this).attr("name").replace(/\[(\d+)\](?!.*\[\d+\])/, '[' + $index + ']')
						);
					}

					if (typeof($(this).attr('id'))  != 'undefined'){
						$(this).attr(
							"id", $(this).attr("id").replace(/[0-9]$/, $index)
						);
					}

					$(this).attr(
						"data-section-id", ($prefix_id + $index)
					);
					$(this).trigger('change');
				});

				$('div[data-require-element-id]', $(this)).each(function () {
					$(this).attr(
						"data-require-element-id", $(this).attr("data-require-element-id").replace(/[0-9]$/, $index)
					);
				});


				$('select', $(this)).each(function () {
					if (typeof $(this).attr('name') != 'undefined') {
						$(this).attr(
							"name", $(this).attr("name").replace(/\[(\d+)\](?!.*\[\d+\])/, '[' + $index + ']')
						);
					}
					$(this).attr(
						"id", $(this).attr("id").replace(/[0-9]$/, $index)
					);
				});

				$('label', $(this)).each(function () {
					if (typeof $(this).attr('for') != 'undefined') {
						$(this).attr(
							"for", $(this).attr("for").replace(/\[(\d+)\](?!.*\[\d+\])/, '[' + $index + ']')
						);
					}
				});

				$('a.button.deletion').each(function () {
					$(this).attr(
						"data-section-id", ($prefix_id + $index)
					);
				});
			});
		},
		initSelectize: function($wrap) {
			$('select.selectize',$wrap).each(function(){
				var value = $(this).data('value'),
					multiple = $(this).attr('multiple');
				if (typeof(multiple) == 'undefined') {
					$(this).selectize();
				} else {
					var $select =  $(this).selectize({
						plugins: ['remove_button', 'drag_drop']
					});
					if (typeof (value) !== 'undefined') {
						$select[0].selectize.setValue(value);
					}
				}
			});
		},
		initCheckbox : function($wrap){
			$('.checkbox', $wrap).each(function () {
				var $checkbox = $(this);
				$checkbox.off();
				$checkbox.change(function () {
					if ($checkbox.is(':checked')) {
						$checkbox.val('1');
					} else {
						$checkbox.val('0');
					}
				})
			});
		},
		registerRequireElement : function($wrap) {
			$('input, select', $wrap).each(function() {
				ERE_Widget_Acf.initRequireElement($(this));
				$(this).on('change', function () {
					ERE_Widget_Acf.initRequireElement($(this));
				});
			});
		},
		initRequireElement : function($wrap) {
			var id = $wrap.attr('id');
			var value = $wrap.val();

			$('div[data-require-element-id="' + id + '"]').each(function () {
				var compare = $(this).attr('data-require-compare');
				var values = $(this).attr('data-require-values');
				if (typeof values != 'undefined' && values != '') {
					values = values.split(',');
				}
				var isShow = false;
				if (compare == '!=')
					isShow = true;

				$.each(values, function ($i, $v) {
					if ($v == value) {
						isShow = compare == '=';
						return;
					}
				});
				if (isShow) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		},
		uploadImage: function () {
			$('.widget-acf-upload-button').each(function () {
				$(this).off('click').on('click',function(event){
					event.preventDefault();

					// check for media manager instance
					if (wp.media.frames.gk_frame) {
						wp.media.frames.gk_frame.open();
						wp.media.frames.gk_frame.clicked_button = $(this);
						return;
					}
					// configuration of the media manager new instance
					wp.media.frames.gk_frame = wp.media({
						title: 'Select image',
						multiple: false,
						library: {
							type: 'image'
						},
						button: {
							text: 'Use selected image'
						}
					});

					wp.media.frames.gk_frame.clicked_button = $(this);
					// Function used for the image selection and media manager closing
					var gk_media_set_image = function () {
						var selection = wp.media.frames.gk_frame.state().get('selection');

						// no selection
						if (!selection) {
							return;
						}

						// iterate through selected elements
						selection.each(function (attachment) {
							var url = attachment.attributes.url;
							var parent = $(wp.media.frames.gk_frame.clicked_button).parent();
							var img = $('img', parent);
							var buttonRemove = $('a.remove-media', parent);

							var inputId = $('input[data-type="id"]', parent);
							var inputUrl = $('input[data-type="url"]', parent);
							var width = wp.media.frames.gk_frame.clicked_button.attr('data-width');
							var height = wp.media.frames.gk_frame.clicked_button.attr('data-height');
							if (typeof width == 'undefined') {
								width = 46;
							}
							if (typeof height == 'undefined') {
								height = 28;
							}
							if (img.length <= 0) {
								img = '<img src="" width="' + width + '" height="' + height + '">';
								img = $(img);
							}
							img.attr('src', url);
							inputUrl.val(url);
							inputId.val(attachment.attributes.id);
							inputUrl.trigger('change');
							inputId.trigger('change');
							parent.prepend(img);



							if (buttonRemove.length <= 0) {
								buttonRemove = $('<a href="javascript:void(0);" class="button remove-media">Remove</a>');
								buttonRemove.insertAfter(wp.media.frames.gk_frame.clicked_button);
							}
							ERE_Widget_Acf.removeImage(parent);
						});
					};

					// closing event for media manger
					//wp.media.frames.gk_frame.on('close', gk_media_set_image);
					// image selection event
					wp.media.frames.gk_frame.on('select', gk_media_set_image);
					// showing media manager
					wp.media.frames.gk_frame.open();
				});

				ERE_Widget_Acf.removeImage($(this).parent());
			});
		},
		removeImage: function (parent) {
			$('.remove-media', parent).off('click').on('click',function(){
				var inputId = $('input[data-type="id"]', parent);
				var inputUrl = $('input[data-type="url"]', parent);
				var img = $('img', parent);
				img.remove();
				inputUrl.val('');
				inputId.val('');
				inputUrl.trigger('change');
				inputId.trigger('change');
				$(this).remove();
			});
		},
		initControls : function(event, widget){
			var $wrapper = $('#widgets-right .widget_acf_wrap');
			if (typeof (widget) !== 'undefined') {
				$wrapper = widget;
			}
			ERE_Widget_Acf.sortable($wrapper);
			ERE_Widget_Acf.initCheckbox($wrapper);
			ERE_Widget_Acf.registerRequireElement($wrapper);
			ERE_Widget_Acf.initSelectize($wrapper);
		}
	};

	$(document).ready(function(){
		ERE_Widget_Acf.init();
	});
})(jQuery);