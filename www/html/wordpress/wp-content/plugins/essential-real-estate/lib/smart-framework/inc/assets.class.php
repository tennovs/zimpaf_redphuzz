<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('GSF_Inc_Assets')) {
	class GSF_Inc_Assets {
		private static $_instance;
		public static function getInstance()
		{
			if (self::$_instance == NULL) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_action('init', array($this, 'registerScript'), 0);
			add_action('init', array($this, 'registerStyle'), 0);
		}

		public function registerScript() {
			/**
			 * Vendors script
			 */
			wp_register_script('magnific-popup', GSF()->helper()->getAssetUrl('assets/vendors/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), '1.1.0', true);
			wp_register_script('nouislider', GSF()->helper()->getAssetUrl('assets/vendors/noUiSlider/nouislider.min.js'), array(), '9.0.0', true);
			wp_register_script('perfect-scrollbar', GSF()->helper()->getAssetUrl('assets/vendors/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'), array('jquery'), '0.6.15', true);
			wp_register_script('selectize', GSF()->helper()->getAssetUrl('assets/vendors/selectize/js/selectize.min.js'), array('jquery'), '0.12.3', true);
			wp_register_script('wp-color-picker-alpha', GSF()->helper()->getAssetUrl('assets/vendors/wp-color-picker-alpha.min.js'), array('jquery'), '2.1.2', true);
			wp_register_script('hc-sticky', GSF()->helper()->getAssetUrl('assets/vendors/hc-sticky/hc-sticky.min.js'), array('jquery'), '2.2.3', true);

			/**
			 * Framework script
			 */
			wp_register_script(GSF()->assetsHandle('media'), GSF()->helper()->getAssetUrl('assets/js/media.min.js'), array('jquery'), GSF()->pluginVer(), true);
			wp_register_script(GSF()->assetsHandle('fields'), GSF()->helper()->getAssetUrl('assets/js/fields.min.js'), array('jquery', 'wp-util', 'hc-sticky'), GSF()->pluginVer(), true);
			wp_register_script(GSF()->assetsHandle('options'), GSF()->helper()->getAssetUrl('assets/js/options.min.js'), array('jquery', 'jquery-form'), GSF()->pluginVer(), true);
			wp_register_script(GSF()->assetsHandle('term-meta'), GSF()->helper()->getAssetUrl('assets/js/term-meta.min.js'), array('jquery'), GSF()->pluginVer(), true);
			wp_register_script(GSF()->assetsHandle('widget'), GSF()->helper()->getAssetUrl('assets/js/widget.min.js'), array('jquery'), GSF()->pluginVer(), true);
			wp_register_script(GSF()->assetsHandle('user-meta'), GSF()->helper()->getAssetUrl('assets/js/user-meta.min.js'), array('jquery'), GSF()->pluginVer(), true);
            wp_register_script(GSF()->assetsHandle('meta-box'), GSF()->helper()->getAssetUrl('assets/js/meta-box.min.js'), array('jquery'), GSF()->pluginVer(), true);

            /**
             * Fields
             */
			wp_register_script(GSF()->assetsHandle('field_image'), GSF()->helper()->getAssetUrl('fields/image/assets/image.min.js'), array(GSF()->assetsHandle('media')), GSF()->pluginVer(), true);
			wp_register_script(GSF()->assetsHandle('field-select-popup'), GSF()->helper()->getAssetUrl('fields/select_popup/assets/select-popup.min.js'), array('magnific-popup','perfect-scrollbar'), GSF()->pluginVer(), true);

			global $wp_version;
			if ( version_compare($wp_version,'5.5') >= 0) {
				wp_localize_script('wp-color-picker-alpha',
					'wpColorPickerL10n',
					array(
						'clear'            => esc_html__( 'Clear','smart-framework' ),
						'clearAriaLabel'   => esc_html__( 'Clear color','smart-framework'  ),
						'defaultString'    => esc_html__( 'Default','smart-framework'  ),
						'defaultAriaLabel' => esc_html__( 'Select default color','smart-framework'  ),
						'pick'             => esc_html__( 'Select Color','smart-framework'  ),
						'defaultLabel'     => esc_html__( 'Color value','smart-framework'  ),
					));
			}



		}
		public function registerStyle() {
			/**
			 * Vendors style
			 */
			wp_register_style('magnific-popup', GSF()->helper()->getAssetUrl('assets/vendors/magnific-popup/magnific-popup.min.css'), array(), '1.1.0');
			wp_register_style('nouislider', GSF()->helper()->getAssetUrl('assets/vendors/noUiSlider/nouislider.min.css'), array(), '9.0.0');
			wp_register_style('perfect-scrollbar', GSF()->helper()->getAssetUrl('assets/vendors/perfect-scrollbar/css/perfect-scrollbar.min.css'), array(), '0.6.15');
			wp_register_style('selectize', GSF()->helper()->getAssetUrl('assets/vendors/selectize/css/selectize.min.css'), array(), '0.12.3');
			wp_register_style('selectize-default', GSF()->helper()->getAssetUrl('assets/vendors/selectize/css/selectize.default.min.css'), array(), '0.12.3');

			/**
			 * Framework style
			 */
			wp_register_style(GSF()->assetsHandle('fields'), GSF()->helper()->getAssetUrl('assets/css/fields.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('field_button_set'), GSF()->helper()->getAssetUrl('fields/button_set/assets/button-set.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('field_image_set'), GSF()->helper()->getAssetUrl('fields/image_set/assets/image-set.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('field_switch'), GSF()->helper()->getAssetUrl('fields/switch/assets/switch.min.css'), array(), GSF()->pluginVer());

			wp_register_style(GSF()->assetsHandle('options'), GSF()->helper()->getAssetUrl('assets/css/options.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('term-meta'), GSF()->helper()->getAssetUrl('assets/css/term-meta.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('widget'), GSF()->helper()->getAssetUrl('assets/css/widget.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('user-meta'), GSF()->helper()->getAssetUrl('assets/css/user-meta.min.css'), array(), GSF()->pluginVer());

			/**
			 * Fields
			 */
			wp_register_style(GSF()->assetsHandle('field_image'), GSF()->helper()->getAssetUrl('fields/image/assets/image.min.css'), array(), GSF()->pluginVer());
			wp_register_style(GSF()->assetsHandle('field-select-popup'), GSF()->helper()->getAssetUrl('fields/select_popup/assets/select-popup.min.css'), array('magnific-popup','perfect-scrollbar'), GSF()->pluginVer());
		}
	}
}