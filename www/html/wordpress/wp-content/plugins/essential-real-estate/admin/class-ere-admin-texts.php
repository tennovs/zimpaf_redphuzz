<?php

/**
 * Class ERE_Admin_Texts
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin_Texts')) {
	class ERE_Admin_Texts
	{
		public function __construct()
		{
			$this->plugin_file = plugin_basename(ERE_PLUGIN_FILE);
		}
		/**
		 * Add hooks
		 */
		public function add_hooks()
		{
			global $pagenow;
			// Hooks for Plugins overview page
			if ($pagenow === 'plugins.php') {
				add_filter('plugin_action_links_' . $this->plugin_file, array($this, 'add_plugin_settings_link'), 10, 2);
				add_filter('plugin_row_meta', array($this, 'add_plugin_meta_links'), 10, 2);
			}
		}
		/**
		 * Add the settings link to the Plugins overview
		 *
		 * @param array $links
		 * @param       $file
		 *
		 * @return array
		 */
		public function add_plugin_settings_link($links, $file)
		{
			if ($file !== $this->plugin_file) {
				return $links;
			}
			$settings_link = '<a href="' . esc_url(admin_url('themes.php?page=ere_options')) . '">' . esc_html__('Settings', 'essential-real-estate') . '</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		/**
		 * Adds meta links to the plugin in the WP Admin > Plugins screen
		 *
		 * @param array $links
		 * @param string $file
		 *
		 * @return array
		 */
		public function add_plugin_meta_links($links, $file)
		{
			if ($file !== $this->plugin_file) {
				return $links;
			}
			$links[] = '<a target="_blank" href="http://document.g5plus.net/essential-real-estate">' . esc_html__('Documentation', 'essential-real-estate') . '</a>';
			$links[] = '<a target="_blank" href="http://plugins.g5plus.net/ere/add-ons/">' . esc_html__('Add-ons', 'essential-real-estate') . '</a>';
			$links[] = '<a target="_blank" href="https://themeforest.net/item/beyot-wordpress-real-estate-theme/19514964?ref=g5theme">' . esc_html__('Premium Theme', 'essential-real-estate') . '</a>';
			$links = (array)apply_filters('ere_admin_plugin_meta_links', $links);
			return $links;
		}
	}
}