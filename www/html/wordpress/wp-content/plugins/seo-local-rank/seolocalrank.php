<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://trueranker.com
 * @since             1.0.0
 * @package           seolocalrank
 *
 * @wordpress-plugin
 * Plugin Name:       True Ranker
 * Plugin URI:        https://trueranker.com
 * Description:       ¡SEO Local Rank ahora es True Ranker! Descubre tu verdadera posición en Google. Analiza por país, provincia o ciudad y descubre tu posición real en Google en más de 40.000 localizaciones.
 * Version:           2.2.2
 * Author:            TrueRanker
 * Author URI:        https://trueranker.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       seo-local-rank
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SEOLOCALRANK_PLUGIN_NAME_VERSION', '2.2.2' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_seolocalrank() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-seolocalrank-activator.php';
	Seolocalrank_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_seolocalrank() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-seolocalrank-desactivator.php';
	Seolocalrank_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_seolocalrank' );
register_deactivation_hook( __FILE__, 'deactivate_seolocalrank' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-seolocalrank.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_seolocalrank() {
	$plugin = new Seolocalrank();
	$plugin->run();
}



run_seolocalrank();


 

 

