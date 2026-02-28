<?php
/**
 * Plugin Name: Essential Real Estate
 * Plugin URI: https://wordpress.org/plugins/essential-real-estate
 * Description: The latest plugins Real Estate you want. Completely all features, easy customize and override layout, functions. Supported global payment, build market, single, list property, single agent...etc. All fields are defined dynamic, they will help you can build any kind of Real Estate website.
 * Version: 3.9.5
 * Author: G5Theme
 * Author URI: http://themeforest.net/user/g5theme
 * Text Domain: essential-real-estate
 * Domain Path: /languages/
 * License: GPLv2 or later
 */
/*
Copyright 2016-2018 by G5Theme

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
if (!defined('WPINC')) {
    die;
}

if (!defined('ERE_PLUGIN_VER')) {
    define('ERE_PLUGIN_VER', '3.9.5');
}
if (!defined('ERE_PLUGIN_FILE')) {
    define('ERE_PLUGIN_FILE', __FILE__);
}
if (!defined('ERE_PLUGIN_NAME')) {
    $plugin_dir_name = dirname(__FILE__);
    $plugin_dir_name = str_replace('\\', '/', $plugin_dir_name);
    $plugin_dir_name = explode('/', $plugin_dir_name);
    $plugin_dir_name = end($plugin_dir_name);
    define('ERE_PLUGIN_NAME', $plugin_dir_name);
}

if (!defined('ERE_PLUGIN_DIR')) {
    $plugin_dir = plugin_dir_path(__FILE__);
    define('ERE_PLUGIN_DIR', $plugin_dir);
}
if (!defined('ERE_PLUGIN_URL')) {
    $plugin_url = plugins_url('/', __FILE__);
    define('ERE_PLUGIN_URL', $plugin_url);
}

if (!defined('ERE_PLUGIN_PREFIX')) {
    define('ERE_PLUGIN_PREFIX', 'ere_');
}

if (!defined('ERE_METABOX_PREFIX')) {
    define('ERE_METABOX_PREFIX', 'real_estate_');
}

if (!defined('ERE_OPTIONS_NAME')) {
    define('ERE_OPTIONS_NAME', 'ere_options');
}
if (!defined('ERE_AJAX_URL')) {
    $ajax_url = admin_url('admin-ajax.php', 'relative');
	$my_current_lang = apply_filters( 'wpml_current_language', NULL );
	if ($my_current_lang) {
		$ajax_url = add_query_arg( 'ere_wpml_lang', $my_current_lang, $ajax_url );
	}
    define('ERE_AJAX_URL', $ajax_url);
}

if (!defined('ERE_ROUNDING_PRECISION')) {
	define('ERE_ROUNDING_PRECISION', 6);
}

/**
 * The code that runs during plugin activation.
 */
function ere_activate()
{
    require_once ERE_PLUGIN_DIR . 'includes/class-ere-activator.php';
    ERE_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function ere_deactivate()
{
    require_once ERE_PLUGIN_DIR . 'includes/class-ere-deactivator.php';
    ERE_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'ere_activate');
register_deactivation_hook(__FILE__, 'ere_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ERE_PLUGIN_DIR . 'includes/class-essential-real-estate.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
ERE()->run();

add_filter('gsf_google_map_api_url', 'ere_google_map_api_url', 1);
function ere_google_map_api_url()
{
    $googlemap_ssl = ere_get_option('googlemap_ssl', 0);
    $googlemap_api_key = ere_get_option('googlemap_api_key', 'AIzaSyBqmFdSPp4-iY_BG14j_eUeLwOn9Oj4a4Q');
    if (esc_html($googlemap_ssl) == 1 || is_ssl()) {
        return 'https://maps-api-ssl.google.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key);
    } else {
        return 'http://maps.googleapis.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key);
    }
}
