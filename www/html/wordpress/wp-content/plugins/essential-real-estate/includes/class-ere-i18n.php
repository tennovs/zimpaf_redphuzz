<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://themeforest.net/user/G5Themes
 * @since      1.0.0
 *
 * @package    Essential_Real_Estate
 * @subpackage Essential_Real_Estate/includes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_i18n')) {
	/**
	 * Define the internationalization functionality
	 * Class ERE_i18n
	 */
	class ERE_i18n
	{

		/**
		 * The domain specified for this plugin.
		 */
		private $domain;

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain()
		{
			load_plugin_textdomain(
				$this->domain,
				false,
				dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
			);
		}

		/**
		 * Set the domain equal to that of the specified domain.
		 */
		public function set_domain($domain)
		{
			$this->domain = $domain;
		}
	}
}