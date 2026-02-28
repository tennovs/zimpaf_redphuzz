/* global pieregister, pieregisterRecaptchaLoad, grecaptcha */

'use strict';

var PieRgisterElementorFrontend = window.PieRgisterElementorFrontend || ( function( document, window, $ ) {

	var vars = {};
	var i = 0;

	/**
	 * Public functions and properties.
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Start the engine.
		 */
		init: function() {

			app.events();
		},

		/**
		 * Register JS events.
		 */
		events: function() {
			
			$( window ).on( 'elementor/frontend/init', function( event, id, instance ) {

				// Widget frontend events.
				elementorFrontend.hooks.addAction( 'frontend/element_ready/PieRegister.default', app.widgetPreviewEvents );
				
			} );
		},

		widgetPreviewEvents: function( $scope ) {

			$scope.each(function(){
				var $form_ids = pie_pr_dec_vars.prRegFormsIds;
				var $reCaptcha_public_key = pie_pr_dec_vars.reCaptcha_public_key;
				var $reCaptcha_language = pie_pr_dec_vars.reCaptcha_language;
	
				for(i=0;i<=$form_ids.length;i++){
					var $_reg_form_id;
					$_reg_form_id = $scope.find('#'+$form_ids[i]);
					if($_reg_form_id != null && $_reg_form_id.length != 0 && $_reg_form_id.find(".piereg_recaptcha_reg_div")) {
						$regforms[i] = grecaptcha.render($form_ids[i], {
							"sitekey" : $reCaptcha_public_key,
							'theme' : pie_pr_dec_vars.reg_forms_theme[$form_ids[i]],
							  'hl' : $reCaptcha_language
						});
					}
				}
			});
				
		},
	};

	return app;

}( document, window, jQuery ) );

// Initialize.
PieRgisterElementorFrontend.init();