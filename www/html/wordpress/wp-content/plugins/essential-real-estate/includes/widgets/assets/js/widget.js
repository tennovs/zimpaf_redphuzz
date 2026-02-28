(function($){
	"use strict";
	var ERE_Widget = {
		init: function(){
			this.event();
			this.widget_select2();
		},
		event: function(){
			$(document).on('widget-added', ERE_Widget.widget_select2);
			$(document).on('widget-updated', ERE_Widget.widget_select2);
		},
		widget_select2: function(event, widget){
			if (typeof (widget) == "undefined") {
				$('#widgets-right select.widget-select2:not(.select2-ready)').each(function(){
					ERE_Widget.widget_select2_item(this);
				});
			}
			else {
				$('select.widget-select2:not(.select2-ready)', widget).each(function(){
					ERE_Widget.widget_select2_item(this);
				});
			}
		},
		widget_select2_item: function(target){
			$(target).addClass('select2-ready');
			$(target).select2({width : '100%'});
			var $multiple = $(target).attr('multiple');
			if (typeof($multiple) != 'undefined') {

				var data_value = $(target).attr('data-value').split(',');
				for (var i = 0; i < data_value.length; i++) {
					var $element = $(target).find('option[value="'+ data_value[i] +'"]');
					$element.detach();
					$(target).append($element);
				}
				$(target).val(data_value).trigger('change');
				$(target).on('select2:selecting',function(e){
					var ids = $('input',$(this).parent()).val();
					if (ids != "") {
						ids +=",";
					}
					ids += e.params.args.data.id;
					$('input',$(this).parent()).val(ids);
				}).on('select2:unselecting',function(e){
					var ids = $('input',$(this).parent()).val();
					var arr_ids = ids.split(",");
					var newIds = "";
					for(var i = 0 ; i < arr_ids.length; i++) {
						if (arr_ids[i] != e.params.args.data.id){
							if (newIds != "") {
								newIds +=",";
							}
							newIds += arr_ids[i];
						}
					}
					$('input',$(this).parent()).val(newIds);
				}).on('select2:select',function(e){
					var element = e.params.data.element;
					var $element = $(element);

					$element.detach();
					$(this).append($element);
					$(this).trigger("change");
				});
			}
		}
	};

	$(document).ready(function(){
		ERE_Widget.init();
	});
})(jQuery);