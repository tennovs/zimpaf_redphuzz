<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://trueranker.com
 * @since      1.0.0
 *
 * @package    seolocalrank
 * @subpackage seolocalrank/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    seolocalrank
 * @subpackage seolocalrank/admin/partials
 * @author     Optimizza <proyectos@optimizza.com>
 */


use Serps\SearchEngine\Google\GoogleClient as GoogleClient;
use Serps\HttpClient\CurlClient as CurlClient; 
use Serps\SearchEngine\Google\GoogleUrl as GoogleUrl;
use Serps\Core\Browser\Browser as Browser;
use Serps\SearchEngine\Google\NaturalResultType as NaturalResultType;
    
class Seolocalrank_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
        
        private $token = "";
        private $error = "";
        private $displayError = 'none';
        private $logo;
        private $siteUrl;
        private $siteName;
        private $loader;
        private $greenCheck;
        private $lang;
        private $loadMainDomain = false;
        private $domainListPage = '';
        
        
	public function __construct( $plugin_name, $version ) {
		
                $this->plugin_name = $plugin_name;
		$this->version = $version;
                
                $this->logo = plugin_dir_url( __FILE__ ).'partials/images/logo-landing.png';
                $this->siteName = get_bloginfo("","");
                
                $this->siteUrl = network_site_url( '/' );
                $this->siteUrl = str_replace('http://','',$this->siteUrl);
                $this->siteUrl = str_replace('https://','',$this->siteUrl);
                $this->loader = plugin_dir_url( __FILE__ ).'partials/images/loader.gif';
                $this->greenCheck = plugin_dir_url( __FILE__ ).'partials/images/green-check.png';
                
                $locale = get_locale();
                $locale = explode("_", $locale);

                $this->lang = 'en';
                if($locale[0] == 'es')
                {
                    $this->lang = 'es';
                }
                
                
                $role = get_role('administrator');
               // var_dump($role);
               // exit();
                // add a new capability
                $role->add_cap('work_in_seolocalrank', true);
                $role->add_cap('wpseo_manager', true);
                $role->add_cap('wpseo_editor', true);
                


	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
              

            if(isset($_GET["page"]) && ($_GET["page"] == 'seolocalrank' || $_GET["page"] == 'seolocalrank-upgrade'))
            {
                wp_enqueue_style( $this->plugin_name.'_select2', plugin_dir_url( __FILE__ ) . 'vendor/select2/css/core.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/seolocalrank-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name.'_datatables',plugin_dir_url( __FILE__ ) . 'vendor/datatables/media/css/dataTables.bootstrap.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name.'_fontawesome',plugin_dir_url( __FILE__ ) . 'vendor/fontawesome/css/all.min.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name.'_jquery_confirm',plugin_dir_url( __FILE__ ) . 'vendor/jquery-confirm/dist/jquery-confirm.min.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name.'_bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), $this->version, 'all' );
            }
                
        }
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            
                
                 //die();
               
              
                if(isset($_GET["page"]) && ($_GET["page"] == 'seolocalrank' || $_GET["page"] == 'seolocalrank-upgrade') || $_GET["page"] == 'seolocalrank-settings')
                {
                    wp_enqueue_script( $this->plugin_name.'_sparkline', plugin_dir_url( __FILE__ ) . 'vendor/sparkline/jquery.sparkline.min.js', array( 'jquery' ), $this->version, false );
                   // wp_enqueue_script( $this->plugin_name.'_highcharts', plugin_dir_url( __FILE__ ) . 'vendor/highcharts/highcharts.js', array( 'jquery' ), $this->version, false );
                   // wp_enqueue_script( $this->plugin_name.'_circles', plugin_dir_url( __FILE__ ) . 'vendor/circles/circles.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_select2', plugin_dir_url( __FILE__ ) . 'vendor/select2/select2.min.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/seolocalrank-admin.js?v='.$this->version, array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_echarts', plugin_dir_url( __FILE__ ) . 'vendor/echarts/echarts.simple.min.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_jquery_datatables', plugin_dir_url( __FILE__ ) . 'vendor/datatables/media/js/jquery.dataTables.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_datatables', plugin_dir_url( __FILE__ ) . 'vendor/datatables/media/js/dataTables.bootstrap.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_jquery_confirm', plugin_dir_url( __FILE__ ) . 'vendor/jquery-confirm/dist/jquery-confirm.min.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( $this->plugin_name.'_popper', 'https://unpkg.com/@popperjs/core@2', array( 'jquery' ), $this->version, false );

                    wp_localize_script( $this->plugin_name, 'ajax_var', array(
                    'url'    => admin_url( 'admin-ajax.php' ),
                    'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
                    'loader' => $this->loader,
                    'delete_keyword_sure' => esc_html__( 'Do you want to delete the keyword tracking?', 'seolocalrank' ),
                    'general_error' => esc_html__( 'Ha ocurrido un error', 'seolocalrank' ),
                    'today' => esc_html__( 'Today', 'seolocalrank' ),
                    'delete_domain_sure' => esc_html__( 'Do you want to delete this domain?', 'seolocalrank' ),
                    'lang' => $this->lang,
                    'confirm' => esc_html__( 'Ok', 'seolocalrank' ),
                    'cancel' => esc_html__( 'Cancel', 'seolocalrank' ),
                    'delete_keyword_title' => esc_html__( 'Delete tracking keyword', 'seolocalrank' ),
                ));       
                }
                
                
                
                
               
	}
        
        function seolocalrank_setup_menu(){
            
            
            $icon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAAAXNSR0IB2cksfwAAARRQTFRFXmBiXWBkXmBj3+HjZ2ptaWxvX2JkcnV5WWJsXWBjXmBiXWBjtLe6c3d7naOoYWNmcXR3yMzPd3p+5OborrO5a25x7/Hzlpyhq7G3k5ec6uztoKOmXmFji5CUh4yRnKCjmZ2hhIeK////f4OHmqCmYmVo7e7wjZGWqK6z+Pn6houPlJqg0NPWv8PI6Ors/v7/iY+Wg4eLzc/Ro6mvxMfLu77B4+XnrrG0eX2CfYKGgIWKdXl8jJOZzNDU09XYtbm9q7C1j5SZZWdqb3N3fYGEys3P8fP07/Dx5efpur7DlJuif4KGtbvCn6SpeX+Fj5Wa0tfdbHB0vsLF2NveqKuv+/z8wsnPhomNuLy/xsnNoqarsLS3Sx0r7AAAAFx0Uk5T/3L8//////8L0/Xc/////////////////////////////////v////////////////////////////////////////////////////7////////////////////pMyaFAAABFElEQVR4nG2Rh27CQAyGHS4DyCCbkEAIKWVT9gi7UGba0j3f/z16VEKK2vyST+dP598nG6IEG4GAIjEiCkQ8iGQnDXEC2HN+SeLwGHxjAdemcQqJYpaixo+/DqeDmvMJcrgiee4YcKLJSen0mk6fCfPS++apjqqqAlBOb+AnsOGnvjfbMirP2hz90XjebDMY1srSvsigzcKqyAoSLfcJl/uS7q6H6PVKWdiFLniNGm7drV9M9Xe0TeVuGGTmOJfDHQcm2s35WaHfXy9Tys6UxvjTjP9lj7RmptJKaqKXP96vAEjr2jCM5ls2J9aTt8K09NACWGYzji1MOtWkdjjkq0LqbgThioSh2H8Y+zPkk/CQw9bxA9NVHslGZGI5AAAAAElFTkSuQmCC";
            
            add_menu_page( 'TrueRanker', 'TrueRanker', 'manage_options', 'seolocalrank', array($this, 'seolocalrank_admin_init' ), 'https://app.trueranker.com//new-landing/img/favicon-tr.png' );
            //add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-project-domain', array($this,'seolocalrank_get_project_domain'));
            /*remove_menu_page('seolocalrank-project-domain');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-add-keyword', array($this,'seolocalrank_add_keyword'));
            remove_menu_page('seolocalrank-add-keyword');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-keyword', array($this,'seolocalrank_keyword_stats'));
            remove_menu_page('seolocalrank-keyword');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-all-domains', array($this,'seolocalrank_all_domains'));
            remove_menu_page('seolocalrank-all-domains');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-upgrade', array($this,'seolocalrank_upgrade'));
            remove_menu_page('seolocalrank-upgrade');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-add-domain', array($this,'seolocalrank_add_domains'));
            remove_menu_page('seolocalrank-add-domain');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-settings', array($this,'seolocalrank_settings'));
            remove_menu_page('seolocalrank-settings');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-help', array($this,'seolocalrank_help'));
            remove_menu_page('seolocalrank-help');
            add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-signout', array($this,'seolocalrank_signout'));
            remove_menu_page('seolocalrank-signout');
            //add_menu_page('Seo Local Rank', 'SEO Local Rank', 'administrator','seolocalrank-seo-analyzer', array($this,'seolocalrank_seo_analyzer'));
            //remove_menu_page('seolocalrank-seo-analyzer');*/
            
        }
        function seolocalrank_setup_submenu(){
            if(isset($_SESSION["slr_user"]))
            {
                
                if($_SESSION["slr_project_domain_id"] > 0)
                {
                    //add_submenu_page( 'seolocalrank', str_replace('/', '', $this->siteUrl), str_replace('/', '', $this->siteUrl), 'manage_options', 'seolocalrank');
                    //add_submenu_page('seolocalrank', esc_html__( 'My domains', 'seolocalrank' ), esc_html__( 'My domains', 'seolocalrank' ), 'manage_options', 'seolocalrank-all-domains' );
                    //$this->domainListPage = "seolocalrank-all-domains";
                }
                else
                {
                    //add_submenu_page('seolocalrank', esc_html__( 'My domains', 'seolocalrank' ), esc_html__( 'My domains', 'seolocalrank' ), 'manage_options', 'seolocalrank' );  
                    //$this->domainListPage = "seolocalrank";
                }
                
                 /* if($_SESSION["slr_seo_analyzer"] == "true")
                {
                    add_submenu_page('seolocalrank', esc_html__( 'SEO analyzer', 'seolocalrank' ), esc_html__( 'SEO analyzer', 'seolocalrank' ), 'manage_options', 'seolocalrank-seo-analyzer' );
                }*/
                
                //add_submenu_page('seolocalrank', esc_html__( 'Add domain', 'seolocalrank' ), esc_html__( 'Add domain', 'seolocalrank' ), 'manage_options', 'seolocalrank-add-domain' );
               // add_submenu_page('seolocalrank', esc_html__( 'Keywords', 'seolocalrank' ), esc_html__( 'Keywords', 'seolocalrank' ), 'manage_options', 'seolocalrank-project-domain' );
                add_submenu_page('seolocalrank', esc_html__( 'Dashboard', 'seolocalrank' ), esc_html__( 'Dashboard', 'seolocalrank' ), 'manage_options', 'seolocalrank');
                add_submenu_page('seolocalrank', esc_html__( 'Upgrade', 'seolocalrank' ), esc_html__( 'Upgrade', 'seolocalrank' ), 'manage_options', 'seolocalrank-upgrade', array($this, 'seolocalrank_upgrade' ) );
                add_submenu_page('seolocalrank', esc_html__( 'Settings', 'seolocalrank' ), esc_html__( 'Settings', 'seolocalrank' ), 'manage_options', 'seolocalrank-settings', array($this, 'seolocalrank_settings' ) );
                //add_submenu_page('seolocalrank', esc_html__( 'Help', 'seolocalrank' ), esc_html__( 'Help', 'seolocalrank' ), 'manage_options', 'seolocalrank-help', array($this, 'seolocalrank_help' ) );
            }
        }
        
        /*************
         * DB functions
         */
        
        function saveAPIKey($key)
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'seolocalrank';
            
            $wpdb->insert( 
                    $table_name, 
                    array( 
                            'property' => SeoLocalRankConstants::API_KEY_NAME, 
                            'value' => $key, 
                            'date' => current_time( 'mysql' ), 
                    ) 
            );
        }
        
        function deleteAPIKey()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'seolocalrank';
            
            $wpdb->delete( $table_name, array( 'property' => SeoLocalRankConstants::API_KEY_NAME ) );
        }
        
        function getAPIKey()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'seolocalrank';
            
            $apiKey = $wpdb->get_var( "SELECT value FROM $table_name WHERE property='".SeoLocalRankConstants::API_KEY_NAME."' ORDER BY id DESC" );
            return $apiKey;
        }
        
        /*************************
         * END db functions
         */
        
        function seolocalrank_admin_init(){
             
           $_SESSION["slr_user"] = null;
            if(current_user_can('work_in_seolocalrank'))
            {
               
                //if in session
                if(isset($_SESSION["slr_user"]) && $_SESSION["slr_expires"] > time())
                {
                    
                    $this->seolocalrank_admin_dashboard();
                    die();
                }


                if(isset($_POST["key"]))
                {

                    $apiKey = sanitize_text_field($_POST["key"]);
                    $save = TRUE;
                }
                else
                {
                    $apiKey = $this->getAPIKey();
                    $save = FALSE;
                }

                if(isset($apiKey) && $apiKey != "")
                {
                    $data = array( 
                        'apiKey' => $apiKey,
                        'deviceId' => 'wp',
                        'langCode' => get_locale()
                    );
                    
                    
                    
                   

                    //tiene q ser una función especial de login wp.
                    $body = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_USER_LOGIN_WITH_API_KEY, $data);

                    //var_export($body);
                    
                    if($body["ok"])
                    {

                        

                        $_SESSION["slr_user"] = $body["data"];
                        $_SESSION["slr_token"] = $body["token"];
                        $_SESSION["slr_expires"] = time() + 60 * 60;
                        $_SESSION["slr_seo_analyzer"] = $body["seo_analyzer"];

                        if($save)
                        {
                            $this->saveAPIKey($apiKey);
                        }
                        $this->seolocalrank_admin_dashboard($body);

                        die();
                    }
                    else
                    {
                        if(isset($body["error"]))
                        {
                            $this->error = $body["error"];
                        }
                        else {
                            $this->error = "La clave API no es válida";
                        }
                        $this->displayError = 'block';
                    }
                }

                $privacy_url = "https://trueranker.com/privacy-policy/";
                if($this->lang == 'es')
                {
                    $privacy_url = "https://trueranker.com/es/politica-de-privacidad/";
                }
                $admin_email = get_option( 'admin_email', $default = false );
                require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-display.php';
            }
            else
            {
                echo esc_html__( 'You do not have enough permissions to work with SEO Local Rank', 'seolocalrank' );
            }
        }
        
        
        function getMainDomain()
        {
            
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_PROJECT_DOMAIN_GET_BY_NAME, ['domain' => 'http://'.$this->siteUrl]);
            if($response["ok"])
            {
                $this->loadMainDomain = true;
                return $response["data"]["project_domain_id"];
            }
            return 0;
                
        }
        
        
        function seolocalrank_admin_dashboard($data=null)
        {
          
            $project_domain_id = $this->getMainDomain();
            $_SESSION["slr_project_domain_id"] = $project_domain_id;
            
           
            
            if($project_domain_id > 0)
            {
                $domainName = $this->siteUrl;
                $domainId = $project_domain_id;
                $data= array(
                    "project_domain_id" => $project_domain_id
                );
                
                
                
                $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_PROJECT_DOMAIN_DASHBOARD_DATA, $data);
                
                /*echo '<pre>';
                var_export($response["data"]["keywords"]);
                echo '</pre>';
                die();*/
                if($response["ok"])
                {
                    $project_domain_id = $project_domain_id;
                    $domain = $response["data"]["domain"];
                    $tops = $response["data"]["tops"];
                    $keywords = $response["data"]["keywords"];
                    $available_keywords = $response["data"]["available_keywords"];
                    $coupon = null;
                    if(isset($response["data"]["available_coupon"]))
                    {
                        $coupon = $response["data"]["available_coupon"];
                        $coupon_link = $response["data"]["link"];
                    }
                    
                   
                    
                   
                }
                else {
                    $domainId = 0;
                    $this->error = $response["error"];
                    $this->displayError = 'block';
                }
                
                
                //require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-project-domain-display.php'; 
                require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-project-domain-dashboard.php'; 
            }
            else
            {
                 $this->seolocalrank_all_domains();
            }
            
            
            
        }
       
        
        function seolocalrank_get_project_domain()
        {
            
            if(isset($_GET["domainId"]))
            {
                $domainId = sanitize_text_field($_GET["domainId"]);
                $domainName = sanitize_text_field($_GET["domainName"]);
                $data= array(
                    "project_domain_id" => $domainId
                );
                
                $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORDS_LIST, $data);
                if($response["ok"])
                {
                    $keywords = $response["data"];
                    
                }
                else {
                    $this->error = $response["error"];
                    $this->displayError = 'block';
                }
                
            }
            else
            {
                $this->error = __('Ther was an error with domain identificator', 'seolocalrank' );
                $this->displayError = 'block';
            }
            
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-project-domain-display.php'; 
        }
        
        function seolocalrank_add_keyword()
        {
            //get available keywords
            $availableKeywords = 0;
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_GET_USER_AVAILABLE_KEYWORDS);
            if($response["ok"])
                $availableKeywords = $response["data"]["available_keywords"];
            
            
            
            //load add keyword
            if($availableKeywords > 0)
            {
                if(isset($_GET["domainId"]))
                {
                    $domainId = sanitize_text_field($_GET["domainId"]);
                    $domainName = sanitize_text_field($_GET["domainName"]);
                    $countries = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_COUNTRIES_LIST);
                    $countries = $countries["data"];


                }
                else
                {
                    $error = __('Ther was an error with domain identificator', 'seolocalrank' );
                    $displayError = 'block';
                }

                require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-add-keyword.php'; 
            }
            //load upgrade with error
            else
            {
               
                $this->seolocalrank_upgrade(__('You are currently using all the keywords that your plan supports. Get a superior plan and add more keywords.', 'seolocalrank' ));
            }
        }
        
        function seolocalrank_keyword_stats()
        {
            $keyword = null;
            $domain = null;
            if(isset($_GET["tracking_keyword_id"]))
            {
                
                $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_GET_EXTRA, $_GET);
                
                
                if($response["ok"])
                {
                    $data = $response["data"];
                    $keyword = $data["tracking_keyword"];
                    $domain = $data["domain"];
                }
                else
                {
                    $this->error = __('Ther was an error getting keyword stats', 'seolocalrank' );
                    $this->displayError = 'block';
                }
              
            }
            else
            {
                $this->error = __('Ther was an error with keyword identificator', 'seolocalrank' );
                $this->displayError = 'block';
            }
            
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-keyword.php'; 
        }
        
        function seolocalrank_upgrade($error = '')
        {
            
            $apiKey = $this->getAPIKey();
            $pricingUrl = "https://app.trueranker.com/en/admin/pricing?apikey=".$apiKey;
            if($this->lang == 'es')
            {
                $pricingUrl = "https://app.trueranker.com/admin/precios?apikey=".$apiKey;
            }
            
            
            /*if($error != "")
            {
                $this->error = $error;
                $this->displayError = 'block';
            }
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_PLAN_PERIOD_LIST);
            $periods = array();
            if($response["ok"])
            {
                $apiKey = $this->getAPIKey();
                $pricingUrl = "/admin/pricing?apikey=".$apiKey;
                if($this->lang == 'es')
                {
                    $pricingUrl = "/admin/precios?apikey=".$apiKey;
                }
                $periods = $response["data"];
                
               
                
            }
            else
            {
                $this->displayError="block";
                $this->error = $response["error"];
            }*/
            
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-upgrade.php'; 
            
        }
        
        function seolocalrank_all_domains()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_DOMAIN_LIST_ALL);
            $domains = array();
            if($response["ok"])
            {
                $data = $response["data"];
                $domains = $data["domains"];
                
            }
            else
            {
                $this->displayError="block";
                $this->error = $response["error"];
            }
             require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-domains-list.php'; 
        }
        
        function seolocalrank_add_domains()
        {
            //get available keywords
           
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-add-domain.php'; 
           
        }
        
        function seolocalrank_settings()
        {
            
            $privacy_url = "https://trueranker.com/privacy-policy/";
            if($this->lang == 'es')
            {
                $privacy_url = "https://trueranker.com/es/politica-de-privacidad/";
            }
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-settings.php'; 
           
        }
        
        function seolocalrank_help()
        {
            //get available keywords
           
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-admin-help.php'; 
           
        }
        
        function seolocalrank_signout()
        {
           
           //get available keywords
           $_SESSION["slr_user"] = null;
           $_SESSION["slr_token"] = null;
           $_SESSION["slr_expires"] = null;
           $_SESSION["slr_seo_analyzer"] = null;
           
           $this->end_session();
           $this->deleteAPIKey();
           
           
           
           
        }
        
        function redirect_to_init()
        {
           
            if(isset($_GET["page"]) && $_GET["page"] == 'seolocalrank-signout')
            {
                $this->seolocalrank_signout();
                wp_redirect(admin_url('admin.php?page=seolocalrank'));
                exit();
            }
        }
        
        function seolocalrank_seo_analyzer()
        {
            require plugin_dir_path( __FILE__ ) . 'partials/seolocalrank-seo-analyzer.php'; 
        }
        
       
        function activate_keyword()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_ACTIVATE, $_POST);
            wp_send_json($response);
        }
        
        function pause_keyword()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_PAUSE, $_POST);
            wp_send_json($response);
        }
        
        function delete_keyword()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_REMOVE, $_POST);
            wp_send_json($response);
        }
        
        function update_keyword()
        {
            $tracking_keyword_id = sanitize_text_field($_POST["tracking_keyword_id"]);
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_UPDATE, $_POST);
            $response["post"] = $_POST;
            /*if($response["ok"])
            {
                $data = $response["data"];
                /*$params = [
                    
                    'extra_info' => 1,
                    'version' => 'wp.'.SeoLocalRankConstants::PLUGIN_VERSION,
                    'tracking_keyword_id' => $tracking_keyword_id
                ];
                
                $response = $this->sendHtmlToScrapper($data["google_url"],$params);
            }*/
            
            
            
            wp_send_json($response);
        }
        
        private function sendHtmlToScrapper($url, $params){
            
            
            $userAgent = "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36";
            $browserLanguage = "es-ES";

            $browser = new Browser(new CurlClient(), $userAgent, $browserLanguage);


            $googleUrl = GoogleUrl::fromString($url);
            $googleResponse = $browser->navigateToUrl($googleUrl);
            $pageContent = $googleResponse->getPageContent();
            $pageContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $pageContent);
            $pageContent = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $pageContent);

            $params["pageContent"] = $pageContent;
            $response["pageContent"] = $pageContent;
                    
            
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_SEARCH_UPDATE_SCRAPER, $params);
            
            return $response;
        }
        
        function keyword_history()
        {
            
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_RANK_HISTORY, $_POST);
            wp_send_json($response);
        }
        
        function search_location()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_PROVINCES_SEARCH, $_POST);
            wp_send_json($response);
        }
        
        function send_keyword()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_CITIES_ADD, $_POST);
            /*if($response["ok"]){
                foreach ($response["data"] as $id=>$tk)
                {
                    
                    $params = [
                        'extra_info' => 0,
                        'version' => 'wp.'.SeoLocalRankConstants::PLUGIN_VERSION,
                        'tracking_keyword_id' => $tk["tracking_keyword_id"]
                    ];
                    $response["params"][$id] = $this->sendHtmlToScrapper($tk["search_url"], $params);
                    $response["search"][$id] = $this->sendHtmlToScrapper($tk["search_url"], $params);
                }
            }*/
            wp_send_json($response);
        }
        
        function send_domain()
        {
            
            $_POST["name"] = 'http://'.$_POST["name"];
            //$response["post"] = $_POST;
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_PROJECT_DOMAIN_ADD_DEFAULT, $_POST);
            //$response["post"] = $_POST;
            wp_send_json($response);
        }
        
        function delete_domain()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_PROJECT_DOMAIN_REMOVE, $_POST);
            wp_send_json($response);
        }
        
        function contact()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_USER_CONTACT, $_POST);
            wp_send_json($response);
        }
        
        function get_sale_id()
        {
            $_POST["payment_method_id"] = SeoLocalRankConstants::PAYMENT_METHOD_STRIPE;
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_SALE_ADD_SUBSCRIPTION, $_POST);
            wp_send_json($response);
        }
        
        function get_update_keyword_data()
        {
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_KEYWORD_GET_UPDATED_DATA , $_POST);
            wp_send_json($response);
        }
        
        function slr_start()
        {
            //$response["post"] = $_POST;
            $response = SeoLocalRank::apiRequest(SeoLocalRankConstants::METHOD_USER_SEND_API_KEY_BY_EMAIL, $_POST);
            wp_send_json($response);
        }
        
       
        
        function seolocalrank_check_api_key(){
            
        }
        
        function start_session() {
            if(!session_id()) {
                session_start();
            }
        }
        
        function end_session() {
            session_destroy ();
        }
        
        
        
        
 
       
}
