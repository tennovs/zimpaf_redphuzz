<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Agent')) {
	/**
	 * Class ERE_Shortcode_Package
	 */
	class ERE_Shortcode_Agent
	{
		/**
		 * Package shortcode
		 */
		public static function output( $atts )
		{
			return ere_get_template_html('shortcodes/agent/agent.php', array('atts' => $atts));
		}
	}
}