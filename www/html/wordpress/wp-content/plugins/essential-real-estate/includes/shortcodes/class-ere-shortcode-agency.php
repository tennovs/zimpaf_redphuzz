<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Agency')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Agency
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return ere_get_template_html('shortcodes/agency/agency.php', array('atts' => $atts));
		}
	}
}