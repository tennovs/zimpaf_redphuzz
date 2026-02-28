var GSF_WIDGET;
(function($) {
	"use strict";

	GSF_WIDGET = {
		init: function() {
			this.widgetUpdate();
			this.saveGroupState();
			$(document).on('widget-added widget-updated', GSF_WIDGET.widgetUpdate);

		},
		widgetUpdate: function (event, $widget) {
			if ($widget == null) {
				return;
			}
			GSF.fields.initFields($widget.find('.gsf-meta-box-wrap'));
			$widget.find('.gsf-field').trigger('gsf_check_required');
			$widget.find('.gsf-field').trigger('gsf_check_preset');
		},
		saveGroupState: function () {
            $( document).on( 'click', '#widgets-right .gsf-field-group-title', function() {
                var $this   = $(this),
                    $_group = $this.closest('.gsf-field-group'),
                    groupID = $_group.attr('id'),
                    isOpen  = !$_group.hasClass('in');

                var $form    = $this.closest('form'),
                    inoutVal = isOpen ? 'open' : 'close',
                    $input   = $('input[name="gsf_group_status[' + groupID + ']"]', $form);

                if ($input.length) {
                    $input.val(inoutVal);
                } else {
                    $('<input />', {
                        type: 'hidden',
                        name: 'gsf_group_status[' + groupID + ']'
                    }).val(inoutVal).appendTo($form);
                }
			});
        }
	};
	$(document).ready(function() {
		GSF_WIDGET.init();
	});
})(jQuery);