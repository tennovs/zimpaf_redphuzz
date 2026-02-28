<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property_Advanced_Search')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property_Advanced_Search
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			$enable_filter_location = ere_get_option('enable_filter_location', 0);
			if($enable_filter_location==1)
			{
				wp_enqueue_style( 'select2_css');
				wp_enqueue_script('select2_js');
			}

			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'advanced_search_js');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-advanced-search');

			return ere_get_template_html('shortcodes/property-advanced-search/property-advanced-search.php', array('atts' => $atts));
		}
	}
}