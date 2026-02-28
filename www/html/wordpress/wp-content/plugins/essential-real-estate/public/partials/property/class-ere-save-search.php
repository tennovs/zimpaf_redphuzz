<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Save_Search')) {
    /**
     * Class ERE_Search
     */
    class ERE_Save_Search
    {
	    /*
		 * loader instances
		 */
	    private static $_instance;

	    public static function getInstance()
	    {
		    if (self::$_instance == null) {
			    self::$_instance = new self();
		    }

		    return self::$_instance;
	    }

        public static function create_table_save_search()
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name         = $wpdb->prefix . 'ere_save_search';
            $sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
              title longtext DEFAULT '' NOT NULL,
              params longtext DEFAULT '' NOT NULL,
			  user_id mediumint(9) NOT NULL,
			  email longtext DEFAULT '' NOT NULL,
			  url longtext DEFAULT '' NOT NULL,
			  query longtext NOT NULL,
			  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  PRIMARY KEY  (id)
			) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        function save_search_ajax() {

            $nonce = isset($_REQUEST['ere_save_search_ajax']) ? ere_clean(wp_unslash($_REQUEST['ere_save_search_ajax'])) : '';
            if( !wp_verify_nonce( $nonce, 'ere_save_search_nonce_field' ) ) {
                echo json_encode(array(
                    'success' => false,
                    'message' => esc_html__("Permission error!", 'essential-real-estate'),
                ));
                wp_die();
            }
            global $wpdb, $current_user;
            wp_get_current_user();
            $query  =  isset($_REQUEST['ere_query']) ? ere_clean(wp_unslash($_REQUEST['ere_query'])) : '';
            $table_name         = $wpdb->prefix . 'ere_save_search';
            $url  = isset($_REQUEST['ere_url']) ? esc_url_raw(wp_unslash($_REQUEST['ere_url'])) : '';
            $title  = isset($_REQUEST['ere_title']) ?  ere_clean(wp_unslash($_REQUEST['ere_title'])) : '';
            $params  = isset($_REQUEST['ere_params']) ?  ere_clean(wp_unslash($_REQUEST['ere_params'])) : '';
            $wpdb->insert(
                $table_name,
                array(
                    'title'     => $title,
                    'params'    => $params,
                    'user_id'   => $current_user->ID,
                    'email'     => $current_user->user_email,
                    'url'       => $url,
                    'query'     => $query,
                    'time'      => current_time( 'mysql' ),
                ),
                array(
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            echo json_encode( array( 'success' => true, 'msg' => esc_html__('Save successfully', 'essential-real-estate') ) );
            wp_die();
        }
        public function get_total_save_search(){
            $user_id = get_current_user_id();
            global $wpdb;
            $table_name         = $wpdb->prefix . 'ere_save_search';
            $results       = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE user_id = ' . $user_id, OBJECT );
            return count($results);
        }
    }
}