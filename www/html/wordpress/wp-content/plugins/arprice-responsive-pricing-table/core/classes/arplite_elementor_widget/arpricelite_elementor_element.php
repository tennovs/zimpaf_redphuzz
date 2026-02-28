<?php
namespace ElementorARPRICELITEELEMENT;

if ( ! defined( 'ABSPATH' ) ){
    exit;
}


class elementor_arpricelite_element{

   
   private static $_instance = null;

   
   public function __construct() {

      add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );

      add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
   }
    
   public static function instance() {
      if ( is_null( self::$_instance ) ) {
         self::$_instance = new self();
      }
      return self::$_instance;
   }

   
   public function widget_scripts() {
      global $arprice_version;
      wp_register_script('elementor-arpricelite-element', ARPLITE_PRICINGTABLE_URL . '/js/arpricelite-element.js', array('jquery'), $arprice_version, true);
   }

   
   private function include_widgets_files() {
      require_once( __DIR__ . '/arpricelite_element_add.php' );
   }

   
   public function register_widgets() {
      $this->include_widgets_files();

      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\arpricelite_element_shortcode() );
   }

}

elementor_arpricelite_element::instance();