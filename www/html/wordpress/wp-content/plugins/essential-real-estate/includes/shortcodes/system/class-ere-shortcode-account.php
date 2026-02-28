<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Shortcode_Account')) {
	/**
	 * Class ERE_Shortcode_Account
	 */
	class ERE_Shortcode_Account
	{

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			add_shortcode('ere_login', array($this, 'login_shortcode'));
			add_shortcode('ere_register', array($this, 'register_shortcode'));
			add_shortcode('ere_profile', array($this, 'update_profile_shortcode'));
			add_shortcode('ere_reset_password', array($this, 'reset_password_shortcode'));
		}

		/**
		 * Login shortcode
		 */
		public function login_shortcode($atts)
		{
			return ere_get_template_html('account/login.php', array('atts' => $atts));
		}

		/**
		 * Register shortcode
		 */
		public function register_shortcode($atts)
		{
			return ere_get_template_html('account/register.php', array('atts' => $atts));
		}

		/**
		 * Update profile shortcode
		 */
		public function update_profile_shortcode()
		{
			return ere_get_template_html('account/my-profile.php');
		}

		/**
		 * Reset password shortcode
		 */
		public function reset_password_shortcode()
		{
			return ere_get_template_html('account/reset-password.php');
		}
	}
}
new ERE_Shortcode_Account();