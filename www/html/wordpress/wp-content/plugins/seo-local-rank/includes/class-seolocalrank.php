<?php


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 * @package    seolocalrank
 * @subpackage seolocalrank/includes
 * 
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 * @package    seolocalrank
 * @author     Optimizza <proyectos@optimizza.com>
 */
class SeoLocalRank {
    

    /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
        
        
        
        public function __construct() {
		if ( defined( 'SEOLOCALRANK_PLUGIN_NAME_VERSION' ) ) {
			$this->version = SEOLOCALRANK_PLUGIN_NAME_VERSION;
		} else {
			$this->version = '2.1.5';
		}
                
                
                
		$this->plugin_name = 'seolocalrank';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
                
               
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
            
            
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
                /**
		 * The class responsible for defining all constants
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-seolocalrank-constants.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-seolocalrank-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-seolocalrank-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-seolocalrank-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-seolocalrank-public.php';
		$this->loader = new Seolocalrank_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Seolocalrank_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Seolocalrank_Admin( $this->get_plugin_name(), $this->get_version() );
                $this->loader->add_action('init', $plugin_admin, 'start_session', 1);
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
                $this->loader->add_action('admin_menu', $plugin_admin ,'seolocalrank_setup_menu');
                $this->loader->add_action('admin_menu', $plugin_admin ,'seolocalrank_setup_submenu');
                $this->loader->add_action('check_api_key', $plugin_admin, 'seolocalrank_check_api_key');
                $this->loader->add_action('wp_ajax_activate_keyword', $plugin_admin, 'activate_keyword');
                $this->loader->add_action('wp_ajax_pause_keyword', $plugin_admin, 'pause_keyword');
                $this->loader->add_action('wp_ajax_delete_keyword', $plugin_admin, 'delete_keyword');
                $this->loader->add_action('wp_ajax_update_keyword', $plugin_admin, 'update_keyword');
                $this->loader->add_action('wp_ajax_keyword_history', $plugin_admin, 'keyword_history');
                $this->loader->add_action('wp_ajax_search_location', $plugin_admin, 'search_location');
                $this->loader->add_action('wp_ajax_send_keyword', $plugin_admin, 'send_keyword');
                $this->loader->add_action('wp_ajax_send_domain', $plugin_admin, 'send_domain');
                $this->loader->add_action('wp_ajax_delete_domain', $plugin_admin, 'delete_domain');
                $this->loader->add_action('wp_ajax_slr_contact', $plugin_admin, 'contact');
                $this->loader->add_action('wp_ajax_slr_get_sale_id', $plugin_admin, 'get_sale_id');
                $this->loader->add_action('wp_loaded', $plugin_admin,'redirect_to_init');
                $this->loader->add_action('wp_ajax_slr_start', $plugin_admin, 'slr_start');
                $this->loader->add_action('wp_ajax_slr_kw_history', $plugin_admin, 'keyword_history');
                $this->loader->add_action('wp_ajax_get_update_keyword_data', $plugin_admin, 'get_update_keyword_data');
                
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Plugin_Name_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
        
        public static function apiRequest($method, $data=[], $showResponse=false){
            
            $locale = get_locale();
            $locale = explode("_", $locale);

            $lang = 'en';
            if($locale[0] == 'es')
            {
                $lang = 'es';
            }
            
            if(!isset($data["token"]) && isset($_SESSION["slr_token"]))
            {
                $data["token"] = $_SESSION["slr_token"];
            }
            
            
            
            $response = wp_remote_post(SeoLocalRankConstants::API_URL.'/'.$lang.$method, array(
                    'method' => 'POST',
                    'timeout' => 500,
                    'body' => $data,
                    'sslverify'   => false,
                   
            ) );
            
            
            if($showResponse)
            {
                echo 'url: '.SeoLocalRankConstants::API_URL.'/'.$lang.$method.'<br>';
                echo '<pre>';
                var_export($data);
                echo '</pre>';
                echo '<pre>';
                var_export($response);
                echo '</pre>';
            }
            $body = json_decode(wp_remote_retrieve_body( $response ), true);
            return $body;
        }
        
        
}