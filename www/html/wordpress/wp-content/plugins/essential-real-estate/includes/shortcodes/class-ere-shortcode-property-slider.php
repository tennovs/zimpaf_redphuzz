<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property_Slider')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property_Slider
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			wp_enqueue_style( ERE_PLUGIN_PREFIX . 'property-slider');
			return ere_get_template_html('shortcodes/property-slider/property-slider.php', array('atts' => $atts));
		}
	}
}