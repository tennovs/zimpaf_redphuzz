<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('GSF_Framework_Init')) {
    class GSF_Framework_Init {
        private $frameworks = array();

        private $active_framework;

        /*
     * loader instances
     */
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function init() {
            add_action('plugins_loaded', array($this,'setup_framework'), 0);
        }

        public function setup_framework() {
            $this->frameworks = apply_filters( 'gsf_loader_framework', array() );

            $count = count( $this->frameworks );

            if ( ! $count ) {
                return FALSE;
            }

            $latest_version = NULL;

            foreach ( $this->frameworks as $framework ) {

                if ( $latest_version == NULL ) {
                    $latest_version = $framework;
                    continue;
                }

                if ( version_compare( $latest_version['version'], $framework['version'] ) <= 0 ) {
                    $latest_version = $framework;
                }
            }
            $this->load_framework( $latest_version );

            do_action( 'gsf_after_setup_framework' );
        }

        public function load_framework($framework) {
	        if (!class_exists('GSF_SmartFramework')) {
		        if (!defined('GSF_PLUGIN_URI')) {
			        define('GSF_PLUGIN_URI',$framework['uri']);
			        define('GSF_PLUGIN_OWNER_FILE', $framework['plugin_file']);

			        include_once $framework['path'] . 'smart-framework.php';
		        }
	        }
        }
    }
    GSF_Framework_Init::getInstance()->init();
}