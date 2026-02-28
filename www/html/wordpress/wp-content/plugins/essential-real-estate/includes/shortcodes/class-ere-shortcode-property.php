<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Property')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Property
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return ere_get_template_html('shortcodes/property/property.php', array('atts' => $atts));
		}
	}
}