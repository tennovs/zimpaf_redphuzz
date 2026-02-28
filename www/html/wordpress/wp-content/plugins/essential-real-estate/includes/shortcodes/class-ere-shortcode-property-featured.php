<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property_Featured')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property_Featured
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-featured');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');
			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property_featured');

			return ere_get_template_html('shortcodes/property-featured/property-featured.php', array('atts' => $atts));
		}
	}
}