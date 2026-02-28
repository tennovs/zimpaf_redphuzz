<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property_Carousel')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property_Carousel
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property-carousel');
			wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property');

			return ere_get_template_html('shortcodes/property-carousel/property-carousel.php', array('atts' => $atts));
		}
	}
}