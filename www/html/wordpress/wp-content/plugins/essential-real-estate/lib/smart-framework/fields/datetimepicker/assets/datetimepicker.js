/**
 * color field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_DatetimepickerClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_DatetimepickerClass.prototype = {
		init: function() {
			var config = this.$container.find('[data-field-control]').data("options");
			$.datetimepicker.setLocale(gsf_datetimepicker_variable.locale);
			this.$container.find('[data-field-control]').datetimepicker(config);
		},
		getValue: function() {
			return this.$container.find('[data-field-control]').val();
		},
		changeField: function () {
			this.$container.find('[data-field-control]').trigger('gsf_field_control_changed');
		}
	};
})(jQuery);