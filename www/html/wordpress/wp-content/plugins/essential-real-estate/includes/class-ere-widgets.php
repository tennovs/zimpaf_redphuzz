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
if (!class_exists('ERE_Widgets')) {
	class ERE_Widgets
	{
		public function __construct()
		{
			require_once ERE_PLUGIN_DIR . 'includes/abstracts/abstract-ere-widget.php';
			require_once ERE_PLUGIN_DIR . 'includes/abstracts/abstract-ere-widget-acf.php';
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