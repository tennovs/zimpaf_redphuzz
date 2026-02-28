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
		 * Constructor.
		 */
		public function __construct()
		{
			add_shortcode('ere_package', array($this, 'package_shortcode'));
		}

		/**
		 * Package shortcode
		 */
		public function package_shortcode()
		{
			return ere_get_template_html('package/package.php');
		}
	}
}
new ERE_Shortcode_Package();