<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Package')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Package
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return ere_get_template_html('package/package.php', array('atts' => $atts));
		}
	}
}