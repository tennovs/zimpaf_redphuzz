(function ($) {
	"use strict";
	vc.atts.ere_selectize = {
		init: function (param, $field) {
			var $selectField = $field.find('[data-selectize="true"]'),
				config = {
					plugins: ['remove_button','drag_drop'],
					onChange: function() {
					}
				};

			if ($selectField.data('tags')) {
				config.create = true;
				config.persist = false;
			}

			var $select = $selectField.selectize(config);
			var control = $select[0].selectize;
			var val = $selectField.data('value');
			if (typeof (val) !== "undefined") {
				control.setValue(val);
			}
		}
	}
})(jQuery);
