<?php

/**
 * Fired during plugin deactivation
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
if (!class_exists('ERE_Deactivator')) {
	require_once ERE_PLUGIN_DIR . 'includes/class-ere-role.php';
	require_once ERE_PLUGIN_DIR . 'includes/class-ere-schedule.php';
	/**
	 * Fired during plugin deactivation
	 * Class ERE_Deactivator
	 */
	class ERE_Deactivator
	{
		/**
		 * Run when plugin deactivated
		 */
		public static function deactivate()
		{
		 ERE_Role::remove_roles();
		 ERE_Schedule::clear_scheduled_hook();
		}
	}
}