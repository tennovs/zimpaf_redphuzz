<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Advanced_Search')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Advanced_Search
	{
		public static function output( $atts )
		{
			return ere_get_template_html('property/advanced-search.php', array('atts' => $atts));
		}
	}
}