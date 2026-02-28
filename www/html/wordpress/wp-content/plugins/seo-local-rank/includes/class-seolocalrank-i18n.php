<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 * @package    seolocalrank
 * @subpackage seolocalrank/includes
 * 
 */
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 * @package    seolocalrank
 * @author     Optimizza <proyectos@optimizza.com>
 */
class Seolocalrank_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
               

            $load = load_plugin_textdomain(
                    'seolocalrank',
                    false,
                    dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
            );

           
	}
}