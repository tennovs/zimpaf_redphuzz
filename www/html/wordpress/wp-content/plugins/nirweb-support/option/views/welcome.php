<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Welcome' ) ) {
  class CSFTICKET_Welcome{

    private static $instance = null;

    public function __construct() {

      if ( CSFTICKET::$premium && ( ! CSFTICKET::is_active_plugin( 'codestar-framework/codestar-framework.php' ) || apply_filters( 'CSFTICKET_welcome_page', true ) === false ) ) { return; }

      add_action( 'admin_menu', array( &$this, 'add_about_menu' ), 0 );
      add_filter( 'plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 5 );
      add_filter( 'plugin_row_meta', array( &$this, 'add_plugin_row_meta' ), 10, 2 );

      $this->set_demo_mode();

    }

    // instance
    public static function instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    public function add_about_menu() {
      add_management_page( 'Codestar Framework', 'Codestar Framework', 'manage_options', 'CSFTICKET-welcome', array( &$this, 'add_page_welcome' ) );
    }

    public function add_page_welcome() {

      $section = ( ! empty( $_GET[ 'section' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'section' ] ) ) : '';

      CSFTICKET::include_plugin_file( 'views/header.php' );

      // safely include pages
      switch ( $section ) {

        case 'quickstart':
          CSFTICKET::include_plugin_file( 'views/quickstart.php' );
        break;

        case 'documentation':
          CSFTICKET::include_plugin_file( 'views/documentation.php' );
        break;

        case 'relnotes':
          CSFTICKET::include_plugin_file( 'views/relnotes.php' );
        break;

        case 'support':
          CSFTICKET::include_plugin_file( 'views/support.php' );
        break;

        case 'free-vs-premium':
          CSFTICKET::include_plugin_file( 'views/free-vs-premium.php' );
        break;

        default:
          CSFTICKET::include_plugin_file( 'views/about.php' );
        break;

      }

      CSFTICKET::include_plugin_file( 'views/footer.php' );

    }

    public static function add_plugin_action_links( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['CSFTICKET--welcome'] = '<a href="'. esc_url( admin_url( 'tools.php?page=CSFTICKET-welcome' ) ) .'">Settings</a>';
        if ( ! CSFTICKET::$premium ) {
          $links['CSFTICKET--upgrade'] = '<a href="http://codestarframework.com/">Upgrade</a>';
        }
      }

      return $links;

    }

    public static function add_plugin_row_meta( $links, $plugin_file ) {

      if ( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['CSFTICKET--docs'] = '<a href="http://codestarframework.com/documentation/" target="_blank">Documentation</a>';
      }

      return $links;

    }

    public function set_demo_mode() {

      $demo_mode = get_option( 'CSFTICKET_demo_mode', false );

      $demo_activate = ( ! empty( $_GET[ 'CSFTICKET-demo' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'CSFTICKET-demo' ] ) ) : '';

      if ( ! empty( $demo_activate ) ) {

        $demo_mode = ( $demo_activate === 'activate' ) ? true : false;

        update_option( 'CSFTICKET_demo_mode', $demo_mode );

      }

      if ( ! empty( $demo_mode ) ) {

        CSFTICKET::include_plugin_file( 'samples/options.samples.php' );

        if ( CSFTICKET::$premium ) {

          CSFTICKET::include_plugin_file( 'samples/customize-options.samples.php' );
          CSFTICKET::include_plugin_file( 'samples/metabox.samples.php'           );
          CSFTICKET::include_plugin_file( 'samples/profile-options.samples.php'   );
          CSFTICKET::include_plugin_file( 'samples/shortcoder.samples.php'        );
          CSFTICKET::include_plugin_file( 'samples/taxonomy-options.samples.php'  );
          CSFTICKET::include_plugin_file( 'samples/widgets.samples.php'           );
          CSFTICKET::include_plugin_file( 'samples/comment-metabox.samples.php'   );

        }

      }

    }

  }

  CSFTICKET_Welcome::instance();
}
