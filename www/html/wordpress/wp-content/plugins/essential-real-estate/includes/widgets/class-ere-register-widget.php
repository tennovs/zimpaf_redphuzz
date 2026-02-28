<?php
/**
 * WooCommerce Widget Functions
 *
 * Widget related functions and widget registration.
 *
 * @author 		WooThemes
 * @category 	Core
 * @package 	WooCommerce/Functions
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Register_Widgets')) {
	class ERE_Register_Widgets
	{
		/**
		 * Construct
		 */
		public function __construct()
		{
			require_once ERE_PLUGIN_DIR . 'includes/abstracts/abstract-ere-widget.php';
			require_once ERE_PLUGIN_DIR . 'includes/abstracts/abstract-ere-widget-acf.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-login-menu.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-my-package.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-mortgage-calculator.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-top-agents.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-recent-properties.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-featured-properties.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-search-form.php';
			require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-widget-listing-property-taxonomy.php';
		}

		/**
		 * Register Widgets.
		 */
		public function register_widgets()
		{
			register_widget('ERE_Widget_Login_Menu');
			register_widget('ERE_Widget_My_Package');
			register_widget('ERE_Widget_Mortgage_Calculator');
			register_widget('ERE_Widget_Top_Agents');
			register_widget('ERE_Widget_Recent_Properties');
			register_widget('ERE_Widget_Featured_Properties');
			register_widget('ERE_Widget_Search_Form');
			register_widget('ERE_Widget_Listing_Property_Taxonomy');
		}
	}
}