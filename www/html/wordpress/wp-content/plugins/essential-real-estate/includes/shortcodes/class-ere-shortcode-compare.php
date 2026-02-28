<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Compare')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Compare
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return ere_get_template_html('property/compare.php', array('atts' => $atts));
		}
	}
}