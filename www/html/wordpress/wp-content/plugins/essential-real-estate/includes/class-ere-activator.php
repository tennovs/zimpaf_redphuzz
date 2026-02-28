<?php

/**
 * Fired during plugin activation
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
if (!class_exists('ERE_Activator')) {
	require_once ERE_PLUGIN_DIR . 'includes/class-ere-role.php';
	require_once ERE_PLUGIN_DIR . 'includes/class-ere-updater.php';
	/**
	 * Fired during plugin activation
	 * Class ERE_Activator
	 */
	class ERE_Activator
	{
		/**
		 * Run when plugin activated
		 */
		public static function activate()
		{
		 	ERE_Role::create_roles();
			self::setup_page();
		 	ERE_Save_Search::create_table_save_search();
		}

		private static function setup_page()
		{
			// Redirect to setup screen for new setup_pages
			if (!get_option('ere_version')) {
				set_transient('_ere_activation_redirect', 1, HOUR_IN_SECONDS);
			}
			ERE_Updater::updater();
			update_option('ere_version', ERE_PLUGIN_VER);
		}
	}
}