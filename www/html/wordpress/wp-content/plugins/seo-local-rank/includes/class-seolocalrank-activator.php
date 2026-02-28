<?php
/**
 * Fired during plugin activation
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 * @package    seolocalrank
 * @subpackage seolocalrank/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 * @package    seolocalrank
 * @author     Optimizza <proyectos@optimizza.com>
 */
class Seolocalrank_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            global $wpdb;

            $table_name = $wpdb->prefix . "seolocalrank"; 
            
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
              id int(9) NOT NULL AUTO_INCREMENT,
              property tinytext,
              value text,
              date datetime,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            
	}
        
        
}