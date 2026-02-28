<?php
/*
 * Plugin Name: Web To Print Shop : uDraw
 * Plugin URI: http://www.webtoprintshop.com/
 * Description: Browser based online designer and Web To Print technology for any product.
 * Version: 3.3.2
 * Author: Racad Tech, Inc.
 * Author URI: http://webtoprint.solutions/
 * 
 * Requires at least: 4.1
 * Requires PHP: 7.1.30
 * Tested up to: 5.9
 * Stable tag: 3.3.2
 * 
 * @package uDraw
 * @author Amram Bentolila, Crystal Ng, Rehana Khalid
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!defined('SITE_CDN_DOMAIN')) {
    define('SITE_CDN_DOMAIN','https://' . DB_NAME . '.v2.pressablecdn.com');
}

if (!defined('UDRAW_PLUGIN_DIR')) {
    define('UDRAW_PLUGIN_DIR', dirname(__FILE__));
}

if (!defined('UDRAW_PLUGIN_URL')) {
    define('UDRAW_PLUGIN_URL', plugins_url('/', __FILE__));
}

if (!defined('UDRAW_ORDERS_DIR')) {    
    define('UDRAW_ORDERS_DIR', WP_CONTENT_DIR . '/udraw/orders/');
}

if (!defined('UDRAW_ORDERS_URL')) {
    define('UDRAW_ORDERS_URL', content_url() . '/udraw/orders/');
}

if (!defined('UDRAW_STORAGE_DIR')) {
    define('UDRAW_STORAGE_DIR', WP_CONTENT_DIR . '/udraw/storage/');
}

if (!defined('UDRAW_STORAGE_URL')) {
    define('UDRAW_STORAGE_URL', content_url() . '/udraw/storage/');
}

if (!defined('UDRAW_DESIGN_STORAGE_DIR')) {
    define('UDRAW_DESIGN_STORAGE_DIR', WP_CONTENT_DIR . '/udraw/storage/_designs_/');
}

if (!defined('UDRAW_DESIGN_STORAGE_URL')) {
    define('UDRAW_DESIGN_STORAGE_URL', content_url() . '/udraw/storage/_designs_/');
}

if (!defined('UDRAW_FONTS_DIR')) {
    define('UDRAW_FONTS_DIR', WP_CONTENT_DIR . '/udraw/fonts/');
}

if (!defined('UDRAW_FONTS_URL')) {
    define('UDRAW_FONTS_URL', content_url() . '/udraw/fonts/');
}

if (!defined('UDRAW_CLIPART_DIR')) {
    define('UDRAW_CLIPART_DIR', WP_CONTENT_DIR . '/udraw/clipart/');
}

if (!defined('UDRAW_CLIPART_URL')) {
    define('UDRAW_CLIPART_URL', content_url() . '/udraw/clipart/');
}

if (!defined('UDRAW_TEMP_UPLOAD_DIR')) {
    define('UDRAW_TEMP_UPLOAD_DIR', WP_CONTENT_DIR . '/udraw/uploads/');    
}

if (!defined('UDRAW_TEMP_UPLOAD_URL')) {
    define('UDRAW_TEMP_UPLOAD_URL', content_url() . '/udraw/uploads/');    
}

if (!defined('UDRAW_LANGUAGES_DIR')) {
    define('UDRAW_LANGUAGES_DIR', WP_CONTENT_DIR . '/udraw/languages/');    
}

if (!defined('UDRAW_LANGUAGES_URL')) {
    define('UDRAW_LANGUAGES_URL', content_url() . '/udraw/languages/');    
}

if (!defined('UDRAW_BOOTSTRAP_JS')) {
    define('UDRAW_BOOTSTRAP_JS', UDRAW_PLUGIN_URL . 'assets/bootstrap/js/bootstrap.min.js');
}
if (!defined('UDRAW_BOOTSTRAP_CSS')) {
    define('UDRAW_BOOTSTRAP_CSS', UDRAW_PLUGIN_URL . 'designer/includes/css/udraw-bootstrap.css');
}
if (!defined('UDRAW_JQUERY_UI_CSS')) {
    define('UDRAW_JQUERY_UI_CSS', UDRAW_PLUGIN_URL . 'assets/jquery-ui-1.11.1.custom/jquery-ui.min.css');
}

if (!defined('UDRAW_JQUERY_UI_THEME_CSS')) {
    define('UDRAW_JQUERY_UI_THEME_CSS', UDRAW_PLUGIN_URL . 'assets/jquery-ui-1.11.1.custom/jquery-ui.theme.min.css');
}

if (!defined('UDRAW_FONTAWESOME_CSS')) {
    define('UDRAW_FONTAWESOME_CSS', UDRAW_PLUGIN_URL . 'assets/font-awesome-5.7.0/css/all.min.css');
}

if (!defined('UDRAW_WEBFONT_JS')) {
    define('UDRAW_WEBFONT_JS', UDRAW_PLUGIN_URL . 'assets/webfont-1.5.3/webfont.js');
}

if (!defined('UDRAW_MAGNIFIC_POPUP_JS')) {
    define('UDRAW_MAGNIFIC_POPUP_JS', UDRAW_PLUGIN_URL . 'assets/magnific-popup/jquery.magnific-popup.js');
}

if (!defined('UDRAW_MAGNIFIC_POPUP_CSS')) {
    define('UDRAW_MAGNIFIC_POPUP_CSS', UDRAW_PLUGIN_URL . 'assets/magnific-popup/magnific-popup.css');
}

if (!defined('UDRAW_CHOSEN_JS')) {
    define('UDRAW_CHOSEN_JS', UDRAW_PLUGIN_URL . 'assets/chosen_v1.4.2/chosen.jquery.min.js');    
}

if (!defined('UDRAW_CHOSEN_CSS')) {
    define('UDRAW_CHOSEN_CSS', UDRAW_PLUGIN_URL . 'assets/chosen_v1.4.2/chosen.min.css');    
}

if (!defined('UDRAW_SELECT2_JS')) {
    define('UDRAW_SELECT2_JS', UDRAW_PLUGIN_URL . 'assets/select-js/select2.js');    
}

if (!defined('UDRAW_SELECT2_CSS')) {
    define('UDRAW_SELECT2_CSS', UDRAW_PLUGIN_URL . 'assets/select-js/select2.css');    
}

if (!defined('UDRAW_ACE_JS')) {
    define('UDRAW_ACE_JS', UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/js/ace.js');    
}

if (!defined('UDRAW_ACE_MODE_JAVASCRIPT_JS')) {
    define('UDRAW_ACE_MODE_JAVASCRIPT_JS', UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/js/mode-javascript.js');    
}

if (!defined('UDRAW_ACE_MODE_CSS_JS')) {
    define('UDRAW_ACE_MODE_CSS_JS', UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/js/mode-css.js');    
}

if (!defined('UDRAW_ACE_THEME_CHROME_JS')) {
    define('UDRAW_ACE_THEME_CHROME_JS', UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/js/theme-chrome.js');   
}

if (!defined('UDRAW_ACE_WORKER_JAVASCRIPT_JS')) {
    define('UDRAW_ACE_WORKER_JAVASCRIPT_JS', UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/js/worker-javascript.js');   
}

if (!defined('UDRAW_ACE_WORKER_CSS_JS')) {
    define('UDRAW_ACE_WORKER_CSS_JS', UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/js/worker-css.js');   
}

if (!defined('UDRAW_ACE_THEME_PATH')) {
    define('UDRAW_ACE_THEME_PATH', wp_make_link_relative(UDRAW_PLUGIN_URL . 'assets/ace-1.1.01/theme/'));
}

if (!defined('UDRAW_TAGS_INPUT_CSS')) {
    define('UDRAW_TAGS_INPUT_CSS', UDRAW_PLUGIN_URL . 'assets/jQuery-Tags-Input/jquery.tagsinput.css');    
}

if (!defined('UDRAW_TAGS_INPUT_JS')) {
    define('UDRAW_TAGS_INPUT_JS', UDRAW_PLUGIN_URL . 'assets/jQuery-Tags-Input/jquery.tagsinput.min.js');    
}

if (!defined('UDRAW_DESIGNER_IMG_PATH')) {
    define('UDRAW_DESIGNER_IMG_PATH', plugins_url('designer/includes/img/', __FILE__));	
}

if (!defined('UDRAW_DESIGNER_INCLUDE_PATH')) {
    define('UDRAW_DESIGNER_INCLUDE_PATH', plugins_url('designer/includes/', __FILE__));
}

if (!defined('UDRAW_IMAGE_CROPPER_JS')) {
    define('UDRAW_IMAGE_CROPPER_JS', UDRAW_PLUGIN_URL . 'assets/image-cropper-1.0.0/js/cropper.min.js');    
}
if (!defined('UDRAW_IMAGE_CROPPER_CSS')) {
    define('UDRAW_IMAGE_CROPPER_CSS', UDRAW_PLUGIN_URL . 'assets/image-cropper-1.0.0/css/cropper.min.css');    
}

if (!defined('UDRAW_JQUERY_SMOOTHNESS_CSS')) {
    define('UDRAW_JQUERY_SMOOTHNESS_CSS', UDRAW_PLUGIN_URL . 'assets/jQuery-smoothness/jquery-ui.min.css');
}

if (!defined('UDRAW_PANZOOM_JS')) {
    define('UDRAW_PANZOOM_JS', UDRAW_PLUGIN_URL . 'assets/panzoom/jquery.panzoom.js');
}

if (!defined('UDRAW_CHECKLIST_JS')) {
    define('UDRAW_CHECKLIST_JS', UDRAW_PLUGIN_URL . 'assets/ui-checklist/ui.dropdownchecklist.js');
}

if (!defined('UDRAW_CONVERT_URL')) {
    define('UDRAW_CONVERT_URL', 'https://udraw-server.w2pstore.com/convert.php?');
}
if (!defined('UDRAW_CONVERT_SERVER_URL')) {
    define('UDRAW_CONVERT_SERVER_URL', 'https://udraw-convert-server.racadtech.com');
}


if (!defined('UDRAW_DRAW_SERVER_URL')) {
    if (substr(OPENSSL_VERSION_TEXT, 0, 10) === "OpenSSL 0.") {
        // OpenSSL version too old. Doesn't support TLSv1.1/1.2
        define('UDRAW_DRAW_SERVER_URL' , 'http://draw.racadtech.com');
        define('UDRAW_API_1_SERVER_URL', 'http://udraw-api.goepower.com');
        define('UDRAW_API_2_SERVER_URL', 'http://udraw-api.w2pshop.com');
        //define('UDRAW_API_3_SERVER_URL', 'http://live.goepower.eu/');
        define('UDRAW_API_4_SERVER_URL', 'http://live.webtoprintcloud.com/');
    } else {
        define('UDRAW_DRAW_SERVER_URL' , 'https://draw.racadtech.com');
        define('UDRAW_API_1_SERVER_URL', 'https://udraw-api.goepower.com');
        define('UDRAW_API_2_SERVER_URL', 'https://udraw-api.w2pshop.com');
        //define('UDRAW_API_3_SERVER_URL', 'https://live.goepower.eu/');
        define('UDRAW_API_4_SERVER_URL', 'https://live.webtoprintcloud.com/');        
    }
}

if (!defined('UDRAW_SYSTEM_WEB_PROTOCOL')) {
    if (isset($_SERVER['HTTPS']) &&  ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        define('UDRAW_SYSTEM_WEB_PROTOCOL','https://');
    } else {
        define('UDRAW_SYSTEM_WEB_PROTOCOL','http://');
    }
}

if (!class_exists('uDraw')) {

    class uDraw {
        
        public $udraw_version = "3.3.2";
        
        public function __construct() { }
        
        public function init_udraw_plugin() {
            // Include Required Classes.
            require_once(dirname(__FILE__). '/classes/uDrawAjaxBase.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawUtil.class.php');
            require_once(dirname(__FILE__). '/classes/GoPrint2.class.php');
            require_once(dirname(__FILE__). '/classes/GoSendEx.class.php');
            require_once(dirname(__FILE__). '/classes/GoEpower.class.php');            
            require_once(dirname(__FILE__). '/classes/uDrawSettings.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawTemplates.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawUpload.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawClipart.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawCustomerDesigns.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawConnect.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawDesignHandler.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawAdminOrders.class.php');
            require_once(dirname(__FILE__). '/classes/uDrawLocalConvert.class.php');
            
            //Translator
            require_once(dirname(__FILE__) . '/vendor/autoload.php');
            
            require_once(dirname(__FILE__). '/classes/tables/uDrawTemplatesTable.class.php');
            require_once(dirname(__FILE__). '/classes/tables/uDrawClipartTable.class.php');
            require_once(dirname(__FILE__). '/classes/tables/uDrawPublicTemplatesTable.class.php');
            
            require_once(dirname(__FILE__). '/pdf-xmpie/uDrawXmPieTemplatesTable.class.php');
            require_once(dirname(__FILE__). '/pdf-blocks/uDrawBlockTemplatesTable.class.php');            
            
            // Include Price Matrix
            require_once(dirname(__FILE__). '/price-matrix/uDrawPriceMatrix.class.php');
            // init the Price Matrix Plugin.            
            $udrawPriceMatrix = new uDrawPriceMatrix();
            $udrawPriceMatrix->init();
            
            
            require_once(dirname(__FILE__). '/pdf-blocks/uDrawPDFBlocks.class.php');
            require_once(dirname(__FILE__). '/pdf-xmpie/uDrawPDFXMPie.class.php');
            
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings(); 
            if ( strlen($_udraw_settings['goepower_api_key']) > 1 && strlen($_udraw_settings['goepower_producer_id']) > 0 ) {
                // Include PDF Blocks
                // init the PDF Block Plugin.
                $udrawPDFBlocks = new uDrawPDFBlocks();
                $udrawPDFBlocks->init();

                // Include XmPie Blocks
                // init the XmPie Block Plugin.
                $udrawPDFXmPie = new uDrawPdfXMPie();
                $udrawPDFXmPie->init();
            }
            
            //Init udraw util
            $uDrawUtil = new uDrawUtil();
            $uDrawUtil->init_actions();
                       
            // init uDraw Connect
            $udrawConnect = new uDrawConnect();
            $udrawConnect->init();

            // init uDraw Templates Class
            $uDrawTemplates = new uDrawTemplates();
            $uDrawTemplates->init_actions();
            
            // init uDraw Clipart Class
            $uDrawClipart = new uDrawClipart();
            $uDrawClipart->init_actions();            
            
            // init uDraw CustomerDesigns Class
            $uDrawCustomerDesigns = new uDrawCustomerDesigns();
            $uDrawCustomerDesigns->init_actions();
            
            // init uDraw Design Handler Class
            $uDrawDesignHandler = new uDrawDesignHandler();
            $uDrawDesignHandler->init_actions();
            
            // init GoPrint2 Class
            $GoPrint2 = new GoPrint2();
            $GoPrint2->init();

            // init GoSendEx Class
            $GoSendEx = new GoSendEx();
            $GoSendEx->init();
            
            // init uDraw Upload Class
            $uDrawUpload = new uDrawUpload();
            $uDrawUpload->init();
            
            //Init uDrawAdminOrders
            $uDrawAdminOrders = new uDraw_Admin_Orders();
            $uDrawAdminOrders->init();
            
            //Init uDrawLocalConvert
            $uDrawLocalConvert = new uDrawLocalConvert();
            $uDrawLocalConvert->init_actions();
            
            // init uDraw Bootstrap Mobile Skin
            require_once(dirname(__FILE__). '/designer/bootstrap-mobile/udraw-designer-mobile-ui.php');
            //Init uDraw SVG
            require_once(dirname(__FILE__). '/udraw-svg/udraw-svg.php');
            $udraw_svg = new uDraw_SVG();
            $udraw_svg->init();
            //Init uDraw Excel
            require_once(dirname(__FILE__). '/excel-in-udraw/excel-in-udraw.php');
            $udraw_excel = new uDraw_Excel();
            $udraw_excel->init();
            
            require_once(dirname(__FILE__). '/classes/uDrawTextTemplates.class.php');
            $uDrawTextTemplatesHandler = new uDrawTextTemplatesHandler();
            $uDrawTextTemplatesHandler->init_actions();
                        
            // Wordpress Actions and Filters
            add_filter('post_class', array(&$this, 'add_udraw_class'));            

            // Wordpress Admin Actions and Filters
            add_action('plugins_loaded', array($this, 'udraw_plugins_loaded'));
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'admin_add_menu_pages'));
            add_action('wp_before_admin_bar_render', array(&$this, 'udraw_before_admin_bar_render' ));
            add_action('add_meta_boxes', array(&$this, 'udraw_add_meta_boxes'), 10, 2);            
            add_filter('plugin_row_meta', array(&$this, 'udraw_plugin_row_meta'), 10, 2);            
            add_filter('product_type_options', array(&$this, 'woo_udraw_add_proudct_type'));
            add_action('woocommerce_product_write_panel_tabs', array(&$this, 'woo_udraw_add_product_data_tab'));
            add_action('woocommerce_process_product_meta', array(&$this, 'woo_udraw_save_custom_fields'), 10, 2);
            add_action('woocommerce_checkout_update_order_meta', array( &$this, 'woo_udraw_checkout_update_order_meta'), 10, 1);
            add_action('woocommerce_admin_order_item_headers', array( &$this, 'woo_udraw_admin_order_item_headers') );
            add_action('woocommerce_order_status_processing', array(&$this, 'woo_udraw_order_status_processing'), 10, 1);
            add_action('woocommerce_email_before_order_table', array(&$this, 'woo_udraw_email_before_order_table'), 99, 4);
            add_action('admin_notices', array(&$this, 'check_folder_permissions'));
            //Admin order thumbnail
            add_filter('woocommerce_admin_order_item_thumbnail', array(&$this, 'admin_order_item_thumbnail'), 10, 3);
            
            // WooCommerce Frontend Action and Filters
            add_filter('woocommerce_loop_add_to_cart_link', array(&$this, 'woo_udraw_add_to_cart_cat_text'), 10, 2);
            add_filter('template_include', array(&$this, 'woo_udraw_use_custom_template'), 99);
            add_filter('woocommerce_add_cart_item', array(&$this, 'woo_udraw_add_cart_item'), 10);
            add_filter('woocommerce_get_cart_item_from_session', array(&$this, 'woo_udraw_get_cart_item_from_session'), 10, 2);
            add_filter('woocommerce_add_cart_item_data', array(&$this, 'woo_udraw_add_cart_item_data'), 10, 2);
            add_filter('woocommerce_get_item_data', array(&$this, 'woo_udraw_get_item_data'), 30, 2);
            add_filter('woocommerce_cart_item_product', array(&$this, 'woo_udraw_cart_item_product'), 10, 3);
            add_filter('woocommerce_cart_item_thumbnail', array(&$this, 'woo_udraw_cart_item_thumbnail'), 10, 3);
            add_filter('woocommerce_cart_item_name', array(&$this, 'woo_udraw_cart_item_name'), 10, 3);            
            add_filter('woocommerce_continue_shopping_redirect', array(&$this,'wc_custom_redirect_continue_shopping'), 10, 3);            
            add_action('woocommerce_before_single_product', array(&$this, 'woo_udraw_add_product_designer'), 15);            
            add_action('woocommerce_before_add_to_cart_button', array(&$this, 'woo_udraw_add_product_designer_form'));
            add_action('woocommerce_after_cart', array(&$this, 'woo_udraw_after_cart'));    
            add_action('woocommerce_add_to_cart', array(&$this, 'woo_udraw_add_to_cart'), 99, 6);
            add_action('woocommerce_order_details_after_order_table', array(&$this, 'woo_udraw_after_order_details'), 10, 1);
            add_action('woocommerce_order_item_quantity_html', array(&$this, 'woo_order_item_quantity_html'), 10, 2);
            
            // WooCommerce Frontend Order Details
            add_filter('woocommerce_order_item_name', array(&$this, 'woo_udraw_order_item_name'), 10, 2);
            add_filter('woocommerce_order_again_cart_item_data', array(&$this, 'woo_udraw_order_again_cart_item_data'), 10, 3);
            
            // WooCommerce Frontend Products Filter
            add_filter('woocommerce_product_is_visible', array(&$this, 'woo_udraw_product_is_visible'), 10, 2);
            add_filter('woocommerce_is_purchasable', array(&$this, 'woo_udraw_is_purchasable'), 10, 2);
            
            // Wordpress Footer Action
            add_action('wp_footer', array(&$this,'udraw_wp_footer'), 100);
            
            // Wordpress Menu Filter
            add_filter('wp_nav_menu_objects', array(&$this, 'udraw_wp_nav_menu_objects'), 10, 2);
            
            // uDraw Shortcodes
            add_shortcode( 'udraw_private_templates', array(&$this, 'shortcode_udraw_private_templates') );
            add_shortcode( 'udraw_customer_saved_designs', array(&$this, 'shortcode_udraw_customer_saved_designs') );
            add_shortcode( 'udraw_list_product_categories', array(&$this, 'shortcode_udraw_list_categories') );            
            
            // Dequeue conflicting scripts on certain pages.
            add_action( 'wp_print_scripts', array( &$this, 'udraw_dequeue_scripts'), 100 );
            
            //Localization & Languages
            add_action('init', array(&$this, 'load_plugin_textdomain'), 99);
            
            // Login
            add_action('wp_logout', array(&$this, 'udraw_session_end'), 99);
            add_action('wp_login', array(&$this, 'udraw_session_end'), 99);
            
            //Add to cart error with XMPie products
            add_filter('udraw_cart_redirect_after_error', array(&$this, 'udraw_add_product_error_redirect'), 10, 3 );
            
            //Schedule a daily event to clean up empty folders
            add_action('udraw_clean_empty_folders', array(&$this, 'clean_empty_folders'), 10, 1);
            if (! wp_next_scheduled ( 'udraw_clean_empty_folders' )) {
                wp_schedule_event(time() + 1, 'daily', 'udraw_clean_empty_folders');
            }

            //Schedule a bi-weekly event to clean up old production files.
            add_action( 'wp_ajax_udraw_cleanup_old_production_files', array(&$this,'udraw_cleanup_old_production_files' ) );
            add_action( 'udraw_cleanup_old_production_files', array(&$this, 'udraw_cleanup_old_production_files'), 10, 1);
            if (! wp_next_scheduled ( 'udraw_cleanup_old_production_files' )) {
                wp_schedule_event(time() + 1, 'daily', 'udraw_cleanup_old_production_files');
            }
        }
        
        public function load_plugin_textdomain() {
		    $locale = apply_filters( 'plugin_locale', get_locale(), 'udraw' );
		    $dir    = trailingslashit( WP_LANG_DIR );
            //		/**
            //		 * Admin Locale. Looks in:
            //		 *
            //		 * 		- WP_LANG_DIR/woocommerce/woocommerce-admin-LOCALE.mo
            //		 * 		- WP_LANG_DIR/plugins/woocommerce-admin-LOCALE.mo
            //		 */
		    if ( is_admin() ) {
			    load_textdomain( 'udraw', $dir . 'udraw/' . $locale . '.mo' );
			    load_textdomain( 'udraw', $dir . 'udraw/' . $locale . '.mo' );
		    }

		    /**
		     * Frontend/global Locale. Looks in:
		     *
		     * 		- WP_LANG_DIR/woocommerce/woocommerce-LOCALE.mo
		     * 	 	- woocommerce/i18n/languages/woocommerce-LOCALE.mo (which if not found falls back to:)
		     * 	 	- WP_LANG_DIR/plugins/woocommerce-LOCALE.mo
		     */
		    load_textdomain( 'udraw', $dir . 'udraw/' . $locale . '.mo' );
		    load_plugin_textdomain( 'udraw', false, plugin_basename( dirname( __FILE__ ) ) . "/languages" );  
            
            // start session if not already started.
            if (version_compare(phpversion(), '5.4.0', '<')) {
                if ( session_id() == '') {
                    session_start();
                }
            } else {
                if ( !headers_sent() && '' == session_id() ) {
                    session_start();
                }
            }
	    }
        
        public function udraw_session_end() {
            session_destroy();
        }
        
        /**
         * Show row meta on the plugin screen.
         *
         * @param	mixed $links Plugin Row Meta
         * @param	mixed $file  Plugin Base file
         * @return	array
         */
        public function udraw_plugin_row_meta( $links, $file ) {            
            if ( strtolower($file) == 'udraw/udraw.php' ) {
                $row_meta = array(
                    //Broken link.
                    //'docs'    => '<a href="' . esc_url('https://racadtech.atlassian.net/wiki/display/UDDOC/uDraw+Documentation') . '" title="' . esc_attr( __( 'View uDraw Designer Documentation', 'uDraw' ) ) . '">' . __( 'Docs', 'uDraw' ) . '</a>',
                    'ticket' => '<a href="' . esc_url('https://racadtech.freshdesk.com/support/tickets/new') . '" title="' . esc_attr( __( 'Submit a Support Ticket', 'uDraw' ) ) . '">' . __( 'Submit a Ticket', 'uDraw' ) . '</a>',
                );
                if (!uDraw::is_udraw_okay()) {
                    array_push($row_meta,'<a style="color:red;" href="' . esc_url('https://draw.racadtech.com/payment/Pricing.aspx?param=uDraw-wp') . '" title="' . esc_attr( __( 'Upgrade to Premium Version Now!', 'uDraw' ) ) . '">' . __( 'Premium Upgrade', 'uDraw' ) . '</a>');
                }

                return array_merge( $links, $row_meta );
            }            
            return (array) $links;
        }

        /*
        public function woo_udraw_pre_current_active_plugins() {
            global $wp_list_table;
            $myplugins = $wp_list_table->items;
            foreach ($myplugins as $key => $val) {
                if ($wp_list_table->items[$key]["Name"] == "uDraw - Racad Tech, Inc.") {
                    unset($wp_list_table->items[$key]);
                }
            }
        }*/          
        
        public function udraw_wp_footer() {                                                          
            ?>
	            <script type="text/javascript" src="<?php echo UDRAW_MAGNIFIC_POPUP_JS; ?>"></script>
	            <link rel="stylesheet" type="text/css" href="<?php echo UDRAW_MAGNIFIC_POPUP_CSS; ?>" media="screen" />
                <script>
                    jQuery(document).ready(function($) {
                        
                        // Hook popup on GoPrint2 Webclient.
                        jQuery("a[href*=webclient]").on('click', function (e) {
                            e.preventDefault();
                            var win = window.open(e.currentTarget.href, '_blank');
                            win.focus();
                            return false;                            
                        });
                    });
                </script>
            <?php
        }               

        /**
         * Add uDraw Product Class to the body
         */
        public function add_udraw_class($classes) {
            global $post;
            
            //$this->registerBootstrapJS();
            
            if (self::is_udraw_product($post->ID)) {
                $classes[] = 'udraw-product';
            }
            return $classes;
        }

        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        // ------------------- WooCommerce Admin Methods ------------------------ //
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//

        /**
         * Plugins Loaded Hook
         */
        public function udraw_plugins_loaded() {
            global $woocommerce;
            if (version_compare( $woocommerce->version, '2.6.0', ">=" )) {
                add_action('woocommerce_product_data_panels', array(&$this, 'woo_udraw_add_product_data_panel'));
            } else {
                add_action('woocommerce_product_write_panels', array(&$this, 'woo_udraw_add_product_data_panel'));
            }
            if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                add_action('woocommerce_add_order_item_meta', array(&$this, 'woo_udraw_add_order_item_meta'), 30, 3);
            } else {
                add_action('woocommerce_new_order_item', array(&$this, 'woo_udraw_add_order_item_meta'), 30, 3);
            }
            
            $udraw_db_version = '';
            if (is_multisite()) {
                $udraw_db_version = get_site_option( 'udraw_db_version' );
            } else {
                $udraw_db_version = get_option( 'udraw_db_version' );
            }
            
            if ($udraw_db_version != $this->udraw_version) {
                // Create table.
                global $wpdb, $charset_collate;
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                // uDraw Templates Table
                $sql = "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        name TEXT COLLATE utf8_general_ci NOT NULL,
                        design LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        preview TEXT COLLATE utf8_general_ci NULL,
                        pdf TEXT COLLATE utf8_general_ci NULL,
                        create_date DATETIME COLLATE utf8_general_ci NOT NULL,
                        create_user TEXT COLLATE utf8_general_ci NOT NULL,
                        modify_date DATETIME COLLATE utf8_general_ci NULL,
                        design_width TEXT COLLATE utf8_general_ci NULL,
                        design_height TEXT COLLATE utf8_general_ci NULL,
                        design_pages TEXT COLLATE utf8_general_ci NULL,
                        public_key VARCHAR(64) COLLATE utf8_general_ci NULL,
                        tags TEXT COLLATE utf8_general_ci NULL,
                        category TEXT COLLATE utf8_general_ci NULL,
                        PRIMARY KEY  (id)";
                $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_templates ($sql) $charset_collate;";
                dbDelta($sql);
                
                // uDraw Customer Designs Table                
                $sql = "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        post_id BIGINT(20) NOT NULL,
                        customer_id BIGINT(20) NOT NULL,
                        preview_data LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        design_data LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        create_date DATETIME COLLATE utf8_general_ci NOT NULL,
                        modify_date DATETIME COLLATE utf8_general_ci NULL,
                        access_key VARCHAR(50) COLLATE utf8_general_ci NOT NULL,
                        name VARCHAR(255) COLLATE utf8_general_ci NULL,
                        variation_options VARCHAR(255) COLLATE utf8_general_ci NULL,
                        price_matrix_options LONGTEXT COLLATE utf8_general_ci NULL,
                        PRIMARY KEY  (id)";                
                $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_customer_designs ($sql) $charset_collate;";                
                dbDelta($sql);
                
                // uDraw Clipart Table                
                $sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        image_name LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        user_uploaded VARCHAR(255) COLLATE utf8_general_ci NOT NULL,
                        date DATETIME COLLATE utf8_general_ci NOT NULL,
                        tags LONGTEXT COLLATE utf8_general_ci NULL,
                        category LONGTEXT COLLATE utf8_general_ci NULL,
                        access_key VARCHAR(64) COLLATE utf8_general_ci NOT NULL,
                        PRIMARY KEY  (ID)";                
                $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_clipart ($sql) $charset_collate;";                
                dbDelta($sql);
                
                // uDraw Clipart Category Table                
                $sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        category_name LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        parent_id BIGINT(20) NULL,
                        PRIMARY KEY  (ID)";                
                $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_clipart_category ($sql) $charset_collate;";                
                dbDelta($sql);
                
                // uDraw Template Category Table                
                $sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        category_name LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        parent_id BIGINT(20) NULL,
                        PRIMARY KEY  (ID)";                
                $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_templates_category ($sql) $charset_collate;";                
                dbDelta($sql);
                
                // uDraw Temporary DesignData Table                
                $sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        designData LONGTEXT COLLATE utf8_general_ci NOT NULL,
                        data_key VARCHAR(64) COLLATE utf8_general_ci NOT NULL,
                        PRIMARY KEY  (ID)";                
                $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_temporary_designdata ($sql) $charset_collate;";                
                dbDelta($sql);
                
                $tags_table = $wpdb->prefix . "udraw_templates_tags";
                $migrateTags = false;
                if (!$wpdb->get_var("SHOW TABLES LIKE '$tags_table'")) {
                    $migrateTags = true;
                }

                // Template tags table
                $tags_sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                                name TEXT COLLATE utf8_general_ci NOT NULL,
                                template_id BIGINT(20) COLLATE utf8_general_ci NOT NULL,
                                PRIMARY KEY  (ID)";
                $delta_sql = "CREATE TABLE $tags_table ($tags_sql) $charset_collate;";
                dbDelta($delta_sql);

                if ($migrateTags) {
                    $this->migrateTemplateTags();
                }
                
                
                
                // update uDraw Price Matrix DB.
                $udrawPriceMatrix = new uDrawPriceMatrix();
                $udrawPriceMatrix->init_db();
            
                // Update option to set DB version to current version.
                if (is_multisite()) {
                    update_site_option(get_current_blog_id(), 'udraw_db_version', $this->udraw_version );
                } else {
                    update_option( 'udraw_db_version', $this->udraw_version );
                }
                
                
                $udraw_designer_role = add_role(
                    'udraw_designer',
                    __( 'uDraw Designer' ),
                    array(
                        'read' => true,
                        'edit_posts' => true,
                        'delete_posts' => false,
                        
                        'read_udraw_templates' => true,
                        'read_udraw_fonts' => true,
                        'read_udraw_global_templates' => true,
                        'read_udraw_block_templates' => true,
                        'read_udraw_price_matrix' => true,
                        'read_udraw_clipart_upload' => true,
                                                
                        'edit_udraw_templates' => true,
                        'edit_udraw_fonts' => true,
                        'edit_udraw_global_templates' => false,
                        'edit_udraw_block_templates' => false,
                        'edit_udraw_settings' => false,
                        'edit_udraw_price_matrix' => false,
                        'edit_udraw_clipart_upload' => false,
                        
                        'delete_udraw_templates' => false,
                        'delete_udraw_fonts' => false,
                        'delete_udraw_price_matrix' => false,
                        'delete_udraw_clipart_upload' => false
                    )
                );
                if (null === $udraw_designer_role) {
                    // Role exists, we'll update capabilities.
                    $udraw_designer_role = get_role('udraw_designer');
                    
                    $udraw_designer_role->add_cap('read_udraw_templates');
                    $udraw_designer_role->add_cap('read_udraw_fonts');
                    $udraw_designer_role->add_cap('read_udraw_global_templates');
                    $udraw_designer_role->add_cap('read_udraw_block_templates');
                    $udraw_designer_role->add_cap('read_udraw_price_matrix');
                    $udraw_designer_role->add_cap('read_udraw_clipart_upload');
                    
                    $udraw_designer_role->add_cap('edit_udraw_templates');
                    $udraw_designer_role->add_cap('edit_udraw_fonts');                
                    $udraw_designer_role->remove_cap('edit_udraw_global_templates');
                    $udraw_designer_role->remove_cap('edit_udraw_block_templates');
                    $udraw_designer_role->remove_cap('edit_udraw_settings');
                    $udraw_designer_role->remove_cap('edit_udraw_price_matrix');
                    $udraw_designer_role->remove_cap('edit_udraw_clipart_upload');
                    
                    
                    $udraw_designer_role->remove_cap('delete_udraw_templates');
                    $udraw_designer_role->remove_cap('delete_udraw_fonts');
                    $udraw_designer_role->remove_cap('delete_udraw_price_matrix');
                    $udraw_designer_role->remove_cap('delete_udraw_clipart_upload');
                }
                
                
                $udraw_manager_role = add_role(
                    'udraw_manager',
                    __( 'uDraw Manager' ),
                    array (
                        'read' => true,
                        'edit_posts' => true,
                        'delete_posts' => false,
                        
                        'read_udraw_templates' => true,
                        'read_udraw_fonts' => true,
                        'read_udraw_block_templates' => true,
                        'read_udraw_global_templates' => true,
                        'read_udraw_price_matrix' => true,
                        'read_udraw_clipart_upload' => true,
                        
                        'edit_udraw_templates' => true,
                        'edit_udraw_fonts' => true,
                        'edit_udraw_block_templates' => true,
                        'edit_udraw_global_templates' => true,
                        'edit_udraw_settings' => true,
                        'edit_udraw_price_matrix' => true,
                        'edit_udraw_clipart_upload' => true,
                        
                        'delete_udraw_templates' => true,
                        'delete_udraw_fonts' => true,
                        'delete_udraw_price_matrix' => true,
                        'delete_udraw_clipart_upload' => true
                    )
                );
                if (null === $udraw_manager_role) {
                    // Role exists, we'll update capabilities.
                    $udraw_manager_role = get_role('udraw_manager');
                    
                    $udraw_manager_role->add_cap('read_udraw_templates');
                    $udraw_manager_role->add_cap('read_udraw_fonts');
                    $udraw_manager_role->add_cap('read_udraw_global_templates');
                    $udraw_manager_role->add_cap('read_udraw_block_templates');
                    $udraw_manager_role->add_cap('read_udraw_price_matrix');
                    $udraw_manager_role->add_cap('read_udraw_clipart_upload');
                                        
                    $udraw_manager_role->add_cap('edit_udraw_templates');
                    $udraw_manager_role->add_cap('edit_udraw_fonts');
                    $udraw_manager_role->add_cap('edit_udraw_global_templates');
                    $udraw_manager_role->add_cap('edit_udraw_block_templates');
                    $udraw_manager_role->add_cap('edit_udraw_settings');
                    $udraw_manager_role->add_cap('edit_udraw_price_matrix');
                    $udraw_manager_role->add_cap('edit_udraw_clipart_upload');
                    
                    $udraw_manager_role->add_cap('delete_udraw_templates');
                    $udraw_manager_role->add_cap('delete_udraw_fonts');                    
                    $udraw_manager_role->add_cap('delete_udraw_price_matrix');                    
                    $udraw_manager_role->add_cap('delete_udraw_clipart_upload');
                }                
                
                $shop_manager_role = get_role('shop_manager');
                if (null != $shop_manager_role) {
                    $shop_manager_role->add_cap('read_udraw_templates');
                    $shop_manager_role->add_cap('read_udraw_fonts');
                    $shop_manager_role->add_cap('read_udraw_global_templates');
                    $shop_manager_role->add_cap('read_udraw_block_templates');
                    $shop_manager_role->add_cap('read_udraw_price_matrix');
                    $shop_manager_role->add_cap('read_udraw_clipart_upload');
                                        
                    $shop_manager_role->add_cap('edit_udraw_templates');
                    $shop_manager_role->add_cap('edit_udraw_fonts');
                    $shop_manager_role->add_cap('edit_udraw_global_templates');
                    $shop_manager_role->add_cap('edit_udraw_block_templates');
                    $shop_manager_role->add_cap('edit_udraw_settings');
                    $shop_manager_role->add_cap('edit_udraw_price_matrix');
                    $shop_manager_role->add_cap('edit_udraw_clipart_upload');
                    
                    $shop_manager_role->add_cap('delete_udraw_templates');
                    $shop_manager_role->add_cap('delete_udraw_fonts');                    
                    $shop_manager_role->add_cap('delete_udraw_price_matrix');                    
                    $shop_manager_role->add_cap('delete_udraw_clipart_upload');                  
                }
                
                $admin_role = get_role('administrator');
                if (null != $admin_role) 
                {
                    $admin_role->add_cap('read_udraw_templates');
                    $admin_role->add_cap('read_udraw_fonts');
                    $admin_role->add_cap('read_udraw_global_templates');
                    $admin_role->add_cap('read_udraw_block_templates');
                    $admin_role->add_cap('read_udraw_price_matrix');
                    $admin_role->add_cap('read_udraw_clipart_upload');
                                        
                    $admin_role->add_cap('edit_udraw_templates');
                    $admin_role->add_cap('edit_udraw_fonts');
                    $admin_role->add_cap('edit_udraw_global_templates');
                    $admin_role->add_cap('edit_udraw_block_templates');
                    $admin_role->add_cap('edit_udraw_settings');
                    $admin_role->add_cap('edit_udraw_price_matrix');
                    $admin_role->add_cap('edit_udraw_clipart_upload');
                    
                    $admin_role->add_cap('delete_udraw_templates');
                    $admin_role->add_cap('delete_udraw_fonts');                    
                    $admin_role->add_cap('delete_udraw_price_matrix');                    
                    $admin_role->add_cap('delete_udraw_clipart_upload');                    
                }
                
                $uDrawSettings = new uDrawSettings();
                $uDrawSettings->__logAccess();
                
                $uDrawUtil = new uDrawUtil();
                
                // Setup uDraw folders and init them if needed.
                if (!file_exists(UDRAW_ORDERS_DIR)) {
                    wp_mkdir_p(UDRAW_ORDERS_DIR);
                }
                
                if (!file_exists(UDRAW_STORAGE_DIR)) {
                    wp_mkdir_p(UDRAW_STORAGE_DIR);
                }
                
                $installFonts = false;
                if (!file_exists(UDRAW_FONTS_DIR)) {
                    wp_mkdir_p(UDRAW_FONTS_DIR);
                    $installFonts = true;
                }
                if (!file_exists(UDRAW_LANGUAGES_DIR)) {
                    wp_mkdir_p(UDRAW_LANGUAGES_DIR);
                }
                if ($uDrawUtil->is_dir_empty(UDRAW_FONTS_DIR)) { 
                    $installFonts = true;
                }
                
                if ($installFonts) {
                    // unzip default fonts ( init )
                    $uDrawConnect = new uDrawConnect();
                    $defaultFontsZip = UDRAW_PLUGIN_DIR . '/default-fonts.zip';
                    $uDrawConnect->__downloadFile('https://draw.racadtech.com/default-fonts.zip', $defaultFontsZip);
                    if (file_exists($defaultFontsZip)) {
                        $zip = new ZipArchive;
                        $res = $zip->open($defaultFontsZip);
                        if ($res === TRUE) {
                            // extract it to the path we determined above
                            $zip->extractTo(UDRAW_FONTS_DIR);
                            $zip->close();
                        }
                        unlink($defaultFontsZip);
                    }
                }
                
                if (!file_exists(UDRAW_CLIPART_DIR)) {
                    wp_mkdir_p(UDRAW_CLIPART_DIR);
                }                
                
                if (!file_exists(UDRAW_TEMP_UPLOAD_DIR)) {
                    wp_mkdir_p(UDRAW_TEMP_UPLOAD_DIR);
                }
            
                //Run this function to do trigger clipart cleanup
                $this->clean_clipart_directory();
            }
        }
        
        /**
         * uDraw Admin Init Hook.
         */
        public function admin_init() {
            // Register and Enqueue Admin Script.
            wp_register_script('udraw_admin_js', plugins_url('assets/includes/uDrawAdmin.js', __FILE__));
            wp_enqueue_script('udraw_admin_js');
        }

        public function udraw_add_meta_boxes($post_type, $post) {
            if ($post_type == "product") {
                if (isset($_GET['udraw_template_id']) && isset($_GET['udraw_action'])) {
                    if ($_GET['udraw_action'] == "new-product") {
                        $this->replace_all_product_images($_GET['udraw_template_id'], $post->ID);                        
                    } else if ($_GET['udraw_action'] == "new-block-product") {
                        $this->replace_block_product_image($_GET['udraw_template_id'], $post->ID);
                    }else if ($_GET['udraw_action'] == "new-xmpie-product") {
                        $this->replace_xmpie_product_image($_GET['udraw_template_id'], $post->ID);
                    }
                }
            }
        }
        
        /**
         * Add uDraw Admin Navigation.
         */
        public function admin_add_menu_pages() {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            add_menu_page('uDraw Suite', 'W2P: uDraw', 'read_udraw_templates', 'udraw', array(&$this, 'uDraw_manage_templates_page'), 'dashicons-format-image', 59.5);
            //uDraw Cloud
            //add_submenu_page('udraw', __('uDraw Cloud', 'udraw'), __('uDraw Cloud (beta)', 'udraw'), 'read_udraw_templates', 'udraw', array(&$this, 'uDraw_manage_templates_page'));
            //uDraw Templates
            add_submenu_page('udraw', __('uDraw 4.0', 'udraw'), __('uDraw 4.0', 'udraw'), 'read_udraw_templates', 'udraw', array(&$this, 'uDraw_manage_templates_page'));
            if ($this->is_udraw_okay() || count($this->get_udraw_templates()) < 2) {
                add_submenu_page('udraw', __('- Add New Template', 'udraw'), __('- Add New Template', 'udraw'), 'edit_udraw_templates', 'udraw_add_template', array(&$this, 'uDraw_designer_admin_page'));          
            }
            //Global uDraw Templates
            if (uDraw::is_udraw_okay()) {
                add_submenu_page('udraw', __('- Global Templates', 'udraw'), __('- Global Templates', 'udraw'), 'read_udraw_global_templates', 'udraw_global_template', array(&$this, 'uDraw_manage_global_templates_page'));
            }
            //Manage Fonts
            add_submenu_page('udraw', __('- Manage Fonts', 'udraw'), __('- Manage Fonts', 'udraw'), 'read_udraw_fonts', 'udraw_manage_fonts', array(&$this, 'uDraw_manage_fonts'));
            //Private Image Library
            add_submenu_page('udraw', __('- Private Image Library', 'udraw'), __('- Private Image Library', 'udraw'), 'read_udraw_clipart_upload', 'upload_private_image_collection', array(&$this, 'uDraw_upload_clipart_page'));
            //Text Templates
            add_submenu_page('udraw', __('- Text Templates', 'udraw'), __('- Text Templates', 'udraw'), 'edit_udraw_templates', 'udraw_text_template', array(&$this, 'udraw_text_templates'));
            add_submenu_page(null, __('Modify Text Template','udraw'), __('Modify Text Template','udraw'), 'edit_udraw_templates', 'udraw_edit_text_template', array(&$this, 'udraw_edit_text_template'));
            // Hidden pages.
            add_submenu_page(null, __('Modify Template','udraw'), __('Modify Template','udraw'), 'edit_udraw_templates', 'udraw_modify_template', array(&$this, 'uDraw_designer_admin_page'));
            
            //GoEPower [PDF & XMPie Templates]
            if ( strlen($_udraw_settings['goepower_api_key']) > 1 && strlen($_udraw_settings['goepower_producer_id']) > 0 &&
                strlen($_udraw_settings['goepower_company_id']) > 0) {            
                add_submenu_page('udraw', __('PDF Templates', 'udraw'), __('PDF Templates', 'udraw'), 'read_udraw_block_templates', 'udraw_block_template', array(&$this, 'uDraw_manage_block_templates_page'));
                add_submenu_page('udraw', __('XMPie Templates', 'udraw'), __('XMPie Templates', 'udraw'), 'read_udraw_block_templates', 'udraw_xmpie_template', array(&$this, 'uDraw_manage_xmpie_templates_page'));
            }

            //Add action for inserting pages before the about page
            do_action('udraw_add_menu_pages');

            //Plugin Settings
            add_submenu_page('udraw', __('Settings', 'udraw'), __('Settings', 'udraw'), 'edit_udraw_settings', 'edit_udraw_settings', array(&$this, 'uDraw_settings_page'));
            //About Page
            add_submenu_page('udraw', __('About', 'udraw'), __('About', 'udraw'), 'read_udraw_templates', 'about_udraw', array(&$this, 'uDraw_about_page'));
        }
        
        public function udraw_before_admin_bar_render() {
            global $wp_admin_bar, $post, $product;            
            if (is_single() && !is_admin() && is_product()) {
                $templateId = $this->get_udraw_template_ids($post->ID);
                if (count($templateId) > 0) {
                    if (is_user_logged_in()) {
                        if (current_user_can('edit_udraw_templates')) {
                            $wp_admin_bar->add_node(array(
                                'id'    => 'udraw-edit-template',
                                'title' => 'Edit uDraw Template',
                                'href'  => admin_url() . 'admin.php?page=udraw_modify_template&template_id='. $templateId[0],
                                'meta' => array ( 'class' => 'ab-item' )
                            ));
                        }
                    }                    
                }
            }
        }

        public function uDraw_about_page() {
            require_once("templates/admin/uDraw-about.php");
        }
        
        public function uDraw_manage_templates_page() {
            $this->registerJQueryTagsInput();
            $this->registerFontAwesome();
            $this->registerSelect2JS();
            wp_register_script('udraw_base64_js', plugins_url('assets/Base64.js', __FILE__));
            wp_enqueue_script('udraw_base64_js');
            require_once("templates/admin/uDraw-manage-templates.php");
        }
        
        public function uDraw_manage_global_templates_page() {
            require_once("templates/admin/uDraw-manage-global-templates.php");            
        } 
        
        public function uDraw_manage_block_templates_page() {
            require_once("pdf-blocks/templates/admin/uDraw-manage-block-templates.php");            
        }        
        public function uDraw_manage_xmpie_templates_page() {
            require_once("pdf-xmpie/templates/admin/uDraw-manage-xmpie-templates.php");
        }

        public function uDraw_designer_admin_page() {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            
            $this->register_jquery_css();
            $this->registerBootstrapJS();
            $this->registerStyles();
            if ($_udraw_settings['udraw_designer_enable_threed']) {
                $this->register_designer_threed_min_js();
            }
            $this->registerScripts();
            $this->register_designer_min_js();
            $this->registerDesignerDefaultStyles();

            // Load up Designer
            require_once("designer/designer-admin.php");
        }
        
        public function udraw_text_templates() {
            $this->registerJQueryTagsInput();
            $this->registerSelect2JS();
            require_once('text-builder/manage_text_templates.php');
        }
        
        public function udraw_edit_text_template() {
            $this->register_jquery_ui();
            $this->registerBootstrapJS();
            wp_enqueue_style('bootstrap_css', plugins_url('assets/bootstrap/css/bootstrap.min.css', __FILE__));
            wp_enqueue_style('textbuilder_css', plugins_url('text-builder/css/textbuilder.min.css', __FILE__));
            wp_enqueue_style('udraw_fontawesome_css', UDRAW_FONTAWESOME_CSS);
            wp_enqueue_script('webfont_js', UDRAW_WEBFONT_JS);
            wp_enqueue_script('textbuilder_js', plugins_url('text-builder/js/textbuilder.min.js', __FILE__));
            
            require_once('text-builder/text-builder.php');
        }
        
        public function uDraw_settings_page() {
            $this->register_jquery_ui();
            $this->registerChosenJS();
            $this->registerAceJS();
            $this->registerStyles();
            $this->registerChecklistUI();
            wp_register_script('languageList_js', plugins_url('assets/languageList.js', __FILE__));
            wp_enqueue_script('languageList_js');
            
            require_once("templates/admin/uDraw-settings.php");
        }
        
        public function uDraw_upload_clipart_page() {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            $this->registerjQueryFileUpload();
            $this->registerJQueryTagsInput();
            $this->registerFontAwesome();
            $this->registerSelect2JS();            
            require_once('templates/admin/uDraw-upload-clipart.php');
        }
        
        public function uDraw_manage_fonts() {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            $this->registerStyles();
            $this->registerScripts();
            $this->registerjQueryFileUpload();
            require_once("templates/admin/uDraw-manage-fonts.php");
        }

        /**
         * Add uDraw check box on Product page as a product type.
         */
        public function woo_udraw_add_proudct_type($types) {
            $types['udraw_proudct'] = array(
                'id' => '_udraw_product',
                'wrapper_class' => 'show_if_udraw_product hide_if_grouped',
                'label' => __('uDraw Product', 'udraw'),
                'description' => __('Select if you want to designate this product as a uDraw product.', 'udraw')
            );

            return $types;
        }

        /**
         * This will add the uDraw tab in the products page, but it will only show if checked via Javascript in the method 'woo_udraw_add_product_data_panel()'.
         */
        public function woo_udraw_add_product_data_tab() {
            ?>
            <li class="udraw_product_tab hide_if_udraw_product udraw_product_options"><a href="#udraw_product_data"><?php _e('uDraw Product', 'udraw'); ?></a></li>
            <?php
        }
        
        public function woo_udraw_admin_order_item_headers() {
            if (isset($_GET['udraw_rebuild_pdf']) && isset($_GET['post']) ) {
                if ($_GET['udraw_rebuild_pdf'] == "true") {
                    ?>            
                    <div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Request to regenerate production PDF was sent. &nbsp;&nbsp; This page will be redirected automatically in <span id="timespan">45 seconds</span>. </strong></p></div>
                    <script>

                        var udraw_rebuild_order_id = '<?php echo $_GET['post']; ?>';
                        jQuery.getJSON(ajaxurl + '?action=udraw_designer_rebuild_order_pdf&order_id=' + udraw_rebuild_order_id,
                            function (data) {
                                setTimeout(function () {
                                    //window.location.replace('<?php //echo remove_query_arg('udraw_rebuild_pdf'); ?>');
                                    window.history.pushState(null, '', '<?php echo remove_query_arg('udraw_rebuild_pdf'); ?>');  
                                    window.location.reload(true); 
                                }, 45000);

                                timeout_countdown(45);
                            }
                        );

                        function timeout_countdown(time) {
                            if (time > 0) {
                                setTimeout(function(){
                                    var secondText = ((time - 1) > 1 ) ? 'seconds' : 'second';
                                    jQuery('#timespan').html((time - 1) + ' ' + secondText);
                                    timeout_countdown(time - 1);
                                }, 1000);
                            }
                        }                        
                    </script>
                    <?php
                }
            }

            if (isset($_GET['udraw_updating_design']) && isset($_GET['post']) ) {
                if ($_GET['udraw_updating_design'] == "true") {
                    ?>            
                    <div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Request to update design was sent. &nbsp;&nbsp; This page will be redirected automatically in <span id="update-timespan">45 seconds</span>. </strong></p></div>
                    <script>
                        jQuery(document).ready(function() {
                            setTimeout(function () {
                                //Waiting for new production file to be generated.
                                window.history.pushState(null, '', '<?php echo remove_query_arg('udraw_updating_design'); ?>');  
                                window.location.reload(true); 
                            }, 45000);

                            timeout_countdown(45);
                        });

                        function timeout_countdown(time) {
                            if (time > 0) {
                                setTimeout(function(){
                                    var secondText = ((time - 1) > 1 ) ? 'seconds' : 'second';
                                    jQuery('#update-timespan').html((time - 1) + ' ' + secondText);
                                    timeout_countdown(time - 1);
                                }, 1000);
                            }
                        }                        
                    </script>
                    <?php
                }
            }
        }
        
        public function woo_udraw_email_before_order_table($order, $sent_to_admin, $plain_text, $email ) {
            global $woocommerce;
            $items = $order->get_items();
            $item_keys = array_keys($items);
            $udraw_settings = new uDrawSettings();
            $_settings = $udraw_settings->get_settings();

            if ( uDraw::is_udraw_okay() && $sent_to_admin ) {
                for ($x = 0; $x < count($item_keys); $x++) {
                    $product_type = "";
                    if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                        $udraw_data = $items[$item_keys[$x]]['udraw_data'];
                    } else {
                        $udraw_data = unserialize($items[$item_keys[$x]]['udraw_data']);
                    }

                    // uDraw designer.
                    if (isset($udraw_data['udraw_product_data']) && strlen($udraw_data['udraw_product_data']) > 0) {
                        $product_type = 'designer';
                        if (isset($udraw_data['udraw_options_uploaded_excel']) && strlen($udraw_data['udraw_options_uploaded_excel']) > 0) {
                            // uDraw Excel Uploads not supported.
                            $product_type = '';
                        }
                    }
                    // Price Matrix.
                    if (isset($udraw_data['udraw_price_matrix_design_data']) && strlen($udraw_data['udraw_price_matrix_design_data']) > 0) {
                        $product_type = 'designer';
                    }		
                    //Upload Artwork.
                    if (isset($udraw_data['udraw_options_uploaded_files']) && strlen($udraw_data['udraw_options_uploaded_files']) > 0) {
					$product_type = 'upload';
                    }
                    // PDF product.
                    if (isset($udraw_data['udraw_pdf_block_product_id']) && strlen($udraw_data['udraw_pdf_block_product_id']) > 0) {
                        $product_type = 'blocks';
                    }
                    // XMPie product.
                    if (isset($udraw_data['udraw_pdf_xmpie_product_id']) && strlen($udraw_data['udraw_pdf_xmpie_product_id']) > 0) {
                        $product_type = 'xmpie';
                    }

                    // Book Upload product.
                    if (isset($udraw_data['udraw-book-product-url']) && strlen($udraw_data['udraw-book-product-url']) > 0) {
                        $product_type = 'book-upload';
                    }
                                        
                    if($product_type == 'designer' || $product_type == 'blocks' || $product_type == 'xmpie'){
                        $order_id = $order->get_id();
						$itemQty = $order->get_item_meta($item_keys[$x], '_qty', true);
                        if (isset($_settings['udraw_order_document_format'])) {
                            if (strlen($_settings['udraw_order_document_format']) > 0) {
                                $outputFilename = $_settings['udraw_order_document_format'];
                                $outputFilename = str_replace('%_ORDER_ID_%', $order_id, $outputFilename);
                                $outputFilename = str_replace('%_JOB_ID_%', $item_keys[$x], $outputFilename);
                                $outputFilename = str_replace('%_ITEM_INDEX_%', $x + 1, $outputFilename);
                                $outputFilename = str_replace('%_QUANTITY_%', $itemQty, $outputFilename);
                                if (strlen($outputFilename) > 2) {
                                    $pdf_download_link = $outputFilename . ".pdf";
                                }                        
                            } else {
								$pdf_download_link = "uDraw-Order-" . $order_id . "-" . $item_keys[$x] . ".pdf";
							}
                        } else {
                            $pdf_download_link = "uDraw-Order-" . $order_id . "-" . $item_keys[$x] . ".pdf";
                        }
                        echo '<br /><strong>'. $items[$item_keys[$x]]["name"] . '</strong> : <a class="downloadPDF" href="' . UDRAW_ORDERS_URL . $pdf_download_link . '">Download PDF</a>';
                    } else if ($product_type == 'upload') {
                        $uploaded_files = (isset($udraw_data['udraw_options_uploaded_files'])) ? json_decode(stripcslashes($udraw_data['udraw_options_uploaded_files'])) : NULL;
                        if (count($uploaded_files) > 0) {
                            foreach ($uploaded_files as $upload_file) {
								echo '<p>Attached: '. $upload_file->name . "<a href='". $upload_file->url ."'> Download File</a></p>";
                            }
			            }
                    } else if ($product_type == 'book-upload') {
						$uploaded_doc_url = $udraw_data['udraw-book-product-url'];
						echo '<p>Attached: ' . "<a href='". $uploaded_doc_url ."'>Download File</a></p>";
					}
                }
            } else if (($email->id == 'customer_invoice') || ($email->id == 'customer_processing_order')) {
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $order_id = $order->get_id();
		        } else {
                    $order_id = $order->id;
                }

                $udrawSettings = new uDrawSettings();
                $_udraw_settings = $udrawSettings->get_settings();
                $orderItems = $order->get_items();
                $item_keys = array_keys($items);
                    
                foreach($orderItems as $key => $product) {
                    $item_id = $key;
                    if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                        $product_id = ($product['variation_id']) ? $product['variation_id'] : $product->get_product_id();
                        $udraw_data = $orderItems[$item_id]['udraw_data'];
                    } else {
                        $product_id = ($product['variation_id']) ? $product['variation_id'] : $product['product_id'];
                        $udraw_data = unserialize($orderItems[$item_id]['udraw_data']);
                    }
                
                    $downloadable = get_post_meta( $product_id, '_udraw_allow_post_payment_download', true );
                    if ($downloadable === 'yes') {
                        $pdf_download_link = "uDraw-Order-" . $order_id . "-" . $item_id . ".pdf";
                        // Price Matrix.
                        if (isset($udraw_data['udraw_price_matrix_design_data']) && strlen($udraw_data['udraw_price_matrix_design_data']) > 0) {
                            $product_type = 'designer';
                        }		
                        //Upload Artwork.
                        if (isset($udraw_data['udraw_options_uploaded_files']) && strlen($udraw_data['udraw_options_uploaded_files']) > 0) {
                            $product_type = 'upload';
                        }
                        // PDF product.
                        if (isset($udraw_data['udraw_pdf_block_product_id']) && strlen($udraw_data['udraw_pdf_block_product_id']) > 0) {
                            $product_type = 'blocks';
                        }
                        // XMPie product.
                        if (isset($udraw_data['udraw_pdf_xmpie_product_id']) && strlen($udraw_data['udraw_pdf_xmpie_product_id']) > 0) {
                            $product_type = 'xmpie';
                        }
                        if($product_type == 'designer' || $product_type == 'blocks' || $product_type == 'xmpie'){
                            echo '<strong>'. $orderItems[$item_id]["name"] . '</strong> : <a class="downloadPDF" href="' . UDRAW_ORDERS_URL . $pdf_download_link . '">Download PDF</a>';
                            echo '<p>If the Download File does not exist, please wait for 15-20 seconds and try again as the file is still being generated.</p>';
                        }
                    }
                }
            }
        }

        /**
         * WooCommerce Product Panel for uDraw Products.
         * 
         * @global type $wpdb
         * @global type $post
         */
        public function woo_udraw_add_product_data_panel() {            
            require_once("templates/admin/udraw_product_panel.php");
        }

        public function woo_udraw_checkout_update_order_meta($order_id) {  
            $uDrawSettings = new uDrawSettings();
            $udraw_settings = $uDrawSettings->get_settings();
            $submit_order = true;
            if (isset($udraw_settings['goepower_submit_on_status']) && $udraw_settings['goepower_submit_on_status'] == "paid") { $submit_order = false; }
            if ($submit_order) {
                error_log('[uDraw Order Placed] Generating Order Documents.');
                $this->generate_pdf_from_order($order_id, false);
            }
        }
        
        public function woo_udraw_order_status_processing($order_id) {
            $uDrawSettings = new uDrawSettings();
            $udraw_settings = $uDrawSettings->get_settings();
            error_log('[uDraw Order Processing] Send Request to generate PDF Documents.');
            $this->generate_pdf_from_order($order_id, false);
        }
        
        /**
         * Save custom form data when saving product.
         * 
         * @param type $post_id
         * @param type $post
         */
        public function woo_udraw_save_custom_fields($post_id, $post) {
            $uDrawProduct = isset($_POST['_udraw_product']) ? 'true' : 'false';
            
            if ($uDrawProduct == 'true') {
                // Update Template Id if Product is uDraw Product.
                $template_id = (isset($_POST['udraw_template_id'])) ? $_POST['udraw_template_id'] : NULL;
                $block_id = (isset($_POST['udraw_block_template_id'])) ? $_POST['udraw_block_template_id'] : NULL;
                $xmpie_id = (isset($_POST['udraw_xmpie_template_id'])) ? $_POST['udraw_xmpie_template_id'] : NULL;
                $use_colour_palette = (isset($_POST['udraw_pdf_xmpie_use_colour_palette'])) ? $_POST['udraw_pdf_xmpie_use_colour_palette'] : NULL;
                $allow_print_save = (isset($_POST['udraw_pdf_allow_print_save'])) ? $_POST['udraw_pdf_allow_print_save'] : NULL;
                $allow_download_design = (isset($_POST['udraw_allow_customer_download_design'])) ? $_POST['udraw_allow_customer_download_design'] : NULL;
                $udraw_designer_skin_override = (isset($_POST['udraw_designer_skin_override'])) ? $_POST['udraw_designer_skin_override'] : false;
                $udraw_designer_skin = (isset($_POST['udraw_designer_skin'])) ? $_POST['udraw_designer_skin'] : NULL;
                $udraw_pdf_layout_override = (isset($_POST['udraw_pdf_layout_override'])) ? $_POST['udraw_pdf_layout_override'] : false;
                $udraw_pdf_layout = (isset($_POST['udraw_pdf_layout'])) ? $_POST['udraw_pdf_layout'] : NULL;
                update_post_meta($post_id, '_udraw_template_id', $template_id);
                update_post_meta($post_id, '_udraw_block_template_id', $block_id);
                update_post_meta($post_id, '_udraw_xmpie_template_id', $xmpie_id);
                update_post_meta($post_id, '_udraw_pdf_xmpie_use_colour_palette', $use_colour_palette);
                update_post_meta($post_id, '_udraw_pdf_allow_print_save', $allow_print_save);
                update_post_meta($post_id, '_udraw_pdf_layout_override', $udraw_pdf_layout_override);
                update_post_meta($post_id, '_udraw_pdf_layout', $udraw_pdf_layout);
                update_post_meta($post_id, '_udraw_allow_customer_download_design', $allow_download_design);
                update_post_meta($post_id, '_udraw_designer_skin_override', $udraw_designer_skin_override);
                update_post_meta($post_id, '_udraw_designer_skin', $udraw_designer_skin);
                
                // Update Options settings.
                $options_first = (isset($_POST['udraw_display_options_page_first'])) ? $_POST['udraw_display_options_page_first'] : false;
                $allow_upload = (isset($_POST['udraw_allow_upload_artwork'])) ? $_POST['udraw_allow_upload_artwork'] : false;
                $allow_double_upload = (isset($_POST['udraw_double_allow_upload_artwork'])) ? $_POST['udraw_double_allow_upload_artwork'] : false;
                $files_allowed = (isset($_POST['max_files_allowed'])) ? $_POST['max_files_allowed'] : NULL;
                $allow_convert = (isset($_POST['udraw_allow_convert_pdf'])) ? $_POST['udraw_allow_convert_pdf'] : false;
                $allow_post_payment_download = (isset($_POST['udraw_allow_post_payment_download'])) ? $_POST['udraw_allow_post_payment_download'] : false;
                $allow_design_bypass = (isset($_POST['udraw_allow_design_bypass'])) ? 'yes' : false;
                $preset_private_image_categories = (isset($_POST['_udraw_preset_private_image_categories'])) ? $_POST['_udraw_preset_private_image_categories'] : false;
                update_post_meta($post_id, '_udraw_display_options_page_first', $options_first);
                update_post_meta($post_id, '_udraw_allow_upload_artwork', $allow_upload);
                update_post_meta($post_id, '_udraw_double_allow_upload_artwork', $allow_double_upload);
                update_post_meta($post_id, '_max_files_allowed', $files_allowed);
                update_post_meta($post_id, '_udraw_allow_convert_pdf', $allow_convert);
                update_post_meta($post_id, '_udraw_allow_post_payment_download', $allow_post_payment_download);
                update_post_meta($post_id, '_udraw_allow_design_bypass', $allow_design_bypass);
                update_post_meta($post_id, '_udraw_preset_private_image_categories', $preset_private_image_categories);
                
                // Update Private User Settings.
                $private_product = (isset($_POST['udraw_is_private_product'])) ? $_POST['udraw_is_private_product'] : NULL;
                update_post_meta($post_id, '_udraw_is_private_product', $private_product);
                $privateUserList = array();
                if (isset($_POST['udraw_private_users_list'])) {
                    foreach ($_POST['udraw_private_users_list'] as $user_id) {
                        array_push($privateUserList, $user_id);
                    }
                }
                update_post_meta($post_id, '_udraw_private_users_list', $privateUserList);
                
                // Update Price Matrix Settings.
                $price_matrix_isset = (isset($_POST['udraw_is_price_matrix_set'])) ? $_POST['udraw_is_price_matrix_set'] : NULL;
                $price_matrix_list = (isset($_POST['udraw_price_matrix_list'])) ? $_POST['udraw_price_matrix_list'] : NULL;
                $disable_price_matrix_size_check = (isset($_POST['udraw_price_matrix_disable_size_check'])) ? $_POST['udraw_price_matrix_disable_size_check'] : NULL;
                update_post_meta($post_id, '_udraw_is_price_matrix_set', $price_matrix_isset);
                update_post_meta($post_id, '_udraw_price_matrix_list', $price_matrix_list);
                update_post_meta($post_id, '_udraw_price_matrix_disable_size_check', $disable_price_matrix_size_check);
                
                // Global Template Check
                if (isset($_POST['udraw_public_key'])) {
                    update_post_meta($post_id, '_udraw_public_key', $_POST['udraw_public_key']);
                }
                
                do_action('udraw_admin_save_custom_fields', $post_id);
            }
            update_post_meta($post_id, '_udraw_product', $uDrawProduct);
        }
        
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        // ----------------- WooCommerce Frontend Methods ----------------------- //
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//

        /**
         * Overrides the default "Continue Shopping" button to return to main WooCommerce Shop page instead of default previous product page.
         * 
         * @return bool|string
         */
        public function wc_custom_redirect_continue_shopping() {
            global $woocommerce;
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                $page_id = wc_get_page_id( 'shop' );
            } else {
                $page_id = woocommerce_get_page_id( 'shop' );
            }
            $shop_page_url = get_permalink( $page_id );
            return $shop_page_url;
        }
        
        /**
         * If product is a uDraw product, we will update the add to cart text and link.
         * 
         * @param type $handler
         * @param type $product
         * 
         * @return Add to cart text
         */
        public function woo_udraw_add_to_cart_cat_text($handler, $product) {
            global $woocommerce;
            $udrawPriceMatrix = new uDrawPriceMatrix();
            
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                $product_type = $product->get_type();
                $product_id = $product->get_id();
            } else {
                $product_type = $product->product_type;
                $product_id = $product->id;
            }
            
            if (self::is_udraw_product($product_id)) {
                // Skip if this is a price matrix product
                if ($product_id == $udrawPriceMatrix->get_price_matrix_product_id()) { return $handler; }
                                
                return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button product_type_%s">%s</a>', esc_url(get_permalink($product_id)), esc_attr($product_id), esc_attr($product->get_sku()), esc_attr($product_type), esc_html('Order Now'));
            }
            
            // Check if this is a static product and has a price matrix assigned to it.            
            $udraw_price_matrix_access_key = $udrawPriceMatrix->get_product_price_matrix_key($product_id);
            if (strlen($udraw_price_matrix_access_key) > 0) {
                // found price matrix product
                return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button product_type_%s">%s</a>', esc_url(get_permalink($product_id)), esc_attr($product_id), esc_attr($product->get_sku()), esc_attr($product_type), esc_html('View Now'));
            }
            return $handler;
        }

        /**
         * This will tell WooCommerce to load in a custom template if it's a uDraw product.
         */
        public function woo_udraw_use_custom_template($template) {
            global $post;
            $template_slug = basename(rtrim($template, '.php'));     
            
            if ( ( ($template_slug == 'single-product') || ($template_slug == 'woocommerce') ) && self::is_udraw_product($post->ID)) {
                $updateTitlePosition = true;
                if (self::is_udraw_product($post->ID)) {
                    $displayOptionsFirst = get_post_meta($post->ID, '_udraw_display_options_page_first', true);
                    if ($displayOptionsFirst == "yes") { $updateTitlePosition = false; }
                }
                
                if ($updateTitlePosition) {
                    if (!uDrawPDFBlocks::is_pdf_block_product($post->ID)) {
                        //set ptoduct title above product designer
                        $this->udraw_remove_hooked_action('woocommerce_template_single_title');
                        add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 10);     
                    }
                }
                
                // Load up Designer -- Not really needed. The designer will load up from antother action.
                //$template = UDRAW_PLUGIN_DIR . '/templates/frontend/uDraw-single-product.php';
                
            }            
            return $template;
        }                       
        
        
        public function woo_udraw_load_variable_options_ui() {
            // Enqueue variation scripts
            wp_enqueue_script( 'wc-add-to-cart-variation' );                            
            
            require_once("templates/frontend/uDraw-variable-add-to-cart-V2.php");                            
            
            $foundDisplayMethod = true;            
        }
        
        public function woo_udraw_load_simple_price_matrix_ui() {
            $uDrawPriceMatrix = new uDrawPriceMatrix();
            $uDrawPriceMatrix->registerScripts();
            $this->registerFontAwesome();
            $this->registerChecklistUI();
            require_once("price-matrix/templates/frontend/price-matrix-simple-add-to-cart.php");
            $foundDisplayMethod = true;            
        }
        
        public function woo_udraw_load_simple_upload_ui() {
            require_once("templates/frontend/uDraw-simple-add-to-cart-upload.php");
            $foundDisplayMethod = true;  
        }
        
        private function udraw_find_function_hook ($name = '', $not_filter = '') {
            global $wp_filter;
            $return_array = array();
            foreach ($wp_filter as $filter=>$value) {
                foreach($value as $priority => $function) {
                    foreach($function as $name=>$name_value) {
                        if ($name === 'woocommerce_show_product_images' && $filter !== $not_filter) {
                            array_push($return_array, array($filter, $priority));
                        }
                    }
                }
            }
            return $return_array;
        }
        
        private function udraw_remove_hooked_action ($name = '', $not_filter = '') {
            $filter_array = $this->udraw_find_function_hook($name, $not_filter);
            for ($i = 0; $i < count($filter_array); $i++) {
                $hook = $filter_array[$i][0];
                $priority = $filter_array[$i][1];
                remove_action($hook, $name, $priority);
            }
        }
        
        ///**
        // * This method will load up the designer template interface.
        // */
        public function woo_udraw_add_product_designer() {
            global $post, $wpdb, $product, $woocommerce, $udraw_price_matrix_access_key, $user_session_id;
            //Set global user_session_id
            $user_session_id = uniqid();
            
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                $product_type = $product->get_type();
                $product_id = $product->get_id();
            } else {
                $product_type = $product->product_type;
                $product_id = $product->id;
            }
            
            $registered_pm_scripts = false;

            $udrawSettings = new uDrawSettings();
            $udrawPriceMatrix = new uDrawPriceMatrix();
            $_udraw_settings = $udrawSettings->get_settings();
            
            $udraw_price_matrix_access_key = $udrawPriceMatrix->get_product_price_matrix_key($post->ID);
            $isPriceMatrix = false;
            if (strlen($udraw_price_matrix_access_key) > 0) { $isPriceMatrix = true; }
            $allowCustomerDownloadDesign = get_post_meta($post->ID, '_udraw_allow_customer_download_design', true);
            
            if (self::is_udraw_product($post->ID)) {
                $templateId = $this->get_udraw_template_ids($post->ID);
                $templateCount = sizeof($templateId);               
                echo "<script>var templateCount=".$templateCount.";</script>";
                
                $displayOptionsFirst = get_post_meta($post->ID, '_udraw_display_options_page_first', true);
                
                if ($displayOptionsFirst != "yes") {
                    $this->udraw_remove_hooked_action('woocommerce_show_product_images');
                    $this->udraw_remove_hooked_action('woocommerce_template_single_price');
                }
                
                // Register Jquery UI js and styles
                $this->register_jquery_css();
                $this->registerStyles();
                $this->registerBootstrapJS();
                $this->registerScripts();
                
                // Get Template Id's ( PDF, XMPie, Designer )
                $designTemplateId = get_post_meta($post->ID, '_udraw_template_id', true); 
                $blockProductId = get_post_meta($post->ID, '_udraw_block_template_id', true);
                $xmpieProductId = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);
                
                // If blockProduct is an array, we'll just grab first instace to just for block
                if (gettype($blockProductId) == "array") { 
                    if (count($blockProductId) > 0) {
                        $_blockArray = get_post_meta($post->ID, '_udraw_block_template_id', true);
                        $_blockProductId = $_blockArray[0];
                    }                    
                } else {
                    $_blockProductId = $blockProductId;
                }

                // If xmpieProduct is an array, we'll just grab first instace to just for block
                if (gettype($xmpieProductId) == "array") { 
                    if (count($xmpieProductId) > 0) {
                        $_xmpieArray = get_post_meta($post->ID, '_udraw_xmpie_template_id', true);
                        $_xmpieProductId = $_xmpieArray[0];
                    }                    
                } else {
                    $_xmpieProductId = $xmpieProductId;
                }
                
                // If designTemplateId is an array, we'll just grab first instace to just for block
                if (gettype($designTemplateId) == "array") { 
                    if (count($designTemplateId) > 0) {
                        $_uDrawTemplateArray = get_post_meta($post->ID, '_udraw_template_id', true);
                        $_designTemplateId = $_uDrawTemplateArray[0];
                    }                    
                } else {
                    $_designTemplateId = $designTemplateId;
                }
                
                $override_template = apply_filters('udraw_product_page_override', false, $_designTemplateId, $_blockProductId, $_xmpieProductId, $product);
                $isTemplatelessProduct = get_post_meta($post->ID, '_udraw_templateless_product', true);
                
                if ($override_template) { return; }
                if ( (strlen($_blockProductId) > 0) || ( is_array($_blockProductId) && count($_blockProductId) > 0)  ){
                    $this->registerPDFBlocksJS();
                    $this->registerjQueryFileUpload();
                    $this->registerBootstrapJS();
                    $this->registerImageCropper();
                    $this->registerSelect2JS();
                    $this->registerPanzoomJS();
                    wp_register_style('slim_css', plugins_url('pdf-blocks\includes\slim.min.css', __FILE__));
                    wp_register_style('bootstrap_css', plugins_url('assets\bootstrap\css\bootstrap.min.css', __FILE__));
                    wp_enqueue_style('slim_css');
                    wp_enqueue_style('bootstrap_css');
                    
                    require_once("pdf-blocks/templates/frontend/pdf-block-product.php");
                } else if ( (strlen($_xmpieProductId) > 0) || ( is_array($_xmpieProductId) && count($_xmpieProductId) > 0)  ){
                    $this->registerjQueryFileUpload();
                    $this->registerXMPieColourPicker();
                    $this->registerPDFXmPieJS();                   
                    $this->registerBootstrapJS();
                    $this->registerImageCropper();
                    $this->registerSelect2JS();
                    $this->registerPanzoomJS();
                    
                    wp_register_style('bootstrap_css', plugins_url('assets\bootstrap\css\bootstrap.min.css', __FILE__));
                    wp_enqueue_style('bootstrap_css');
                    
                    require_once("pdf-xmpie/templates/frontend/pdf-xmpie-product.php");
                } else {
                    //Check if allow convert PDF to uDraw Design
                    $allow_convert = get_post_meta($post->ID, '_udraw_allow_convert_pdf', true);
                    if (strlen($_designTemplateId) > 0 || $isTemplatelessProduct || $allow_convert) {                        
                        $this->register_designer_min_js();
                        if ($_udraw_settings['udraw_designer_enable_threed']) {
                            $this->register_designer_threed_min_js();
                        }                        
                        $designerSkinOverride = get_post_meta($post->ID, '_udraw_designer_skin_override', true);
                        if ($designerSkinOverride == 'yes') {
                            $selected_skin = get_post_meta($post->ID, '_udraw_designer_skin', true);
                            $this->load_designer_skin($_designTemplateId, $selected_skin, $displayOptionsFirst,$allowCustomerDownloadDesign,$isPriceMatrix,$templateCount,$isTemplatelessProduct, false);
                        } else {
                            $this->load_designer_skin($_designTemplateId, $_udraw_settings['designer_skin'], $displayOptionsFirst,$allowCustomerDownloadDesign,$isPriceMatrix,$templateCount,$isTemplatelessProduct, false);
                        }
                        if ($product_type == "simple") {
                            $removed_cart = remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
                        }
                    } else {
                        //if (get_post_meta($product_id, '_udraw_is_private_product', true) == "yes") {
                            //return;
                        //}
                        require_once("templates/frontend/uDraw-general-hooks.php");
                        if (!$displayOptionsFirst && !$isPriceMatrix) {
                            // Didn't find a design template id or a block template id.
                            // We will try to load up a price matrix as a product.
                            require_once("price-matrix/templates/frontend/price-matrix-product.php");
                            remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
                            
                            if (!$registered_pm_scripts) { $udrawPriceMatrix->registerScripts(); $registered_pm_scripts = true; }
                            $this->registerjQueryFileUpload();
                            
                        }
                    }                    
                }
                
                // Remove default select options for variable items only for design and block templates
                if ( ( (strlen($_blockProductId) > 0) || (strlen($_designTemplateId) > 0) || strlen($_xmpieProductId) > 0 ) || ( $displayOptionsFirst && $isPriceMatrix) || $isTemplatelessProduct) {     
                    if ($displayOptionsFirst) {
                        if ($product_type == "variable") {
                            remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
                            
                            add_action('woocommerce_variable_add_to_cart', array(&$this, 'woo_udraw_load_variable_options_ui'), 30);
                        } else {
                            if ($isPriceMatrix) {
                                if (!$registered_pm_scripts) { $udrawPriceMatrix->registerScripts(); $registered_pm_scripts = true; }
                                $this->registerjQueryFileUpload();
                                remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
                                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                                add_action('woocommerce_simple_add_to_cart', array(&$this, 'woo_udraw_load_simple_price_matrix_ui'), 30);
                            } else {
                                // This is a "simple" product type and display Options is set first.  We will roll our own "Design Now" button 
                                // which is replacing the default "Add to Cart" button.                               
                                $this->registerjQueryFileUpload();
                                remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
                                add_action('woocommerce_simple_add_to_cart', array(&$this, 'woo_udraw_load_simple_upload_ui'), 30);
                            }
                        }
                    } else {
                        //if ($_udraw_settings['improved_display_options'] || (strlen($_blockProductId) > 0) ) {                                            
                            if ($product_type == "variable") {
                                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

                                remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
                                require_once("templates/frontend/uDraw-variable-add-to-cart.php");
                            } else if ($product_type == "simple") {                                
                                // If access key is defined, it found a price matrix match and we'll load up the UI and components.
                                if ($isPriceMatrix) {
                                    // Category has a price matrix assigned to it.
                                    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                                    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

                                    remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);

                                    // Define static product because this is a pdf block product.
                                    if ( strlen($_blockProductId) > 0 || strlen($_xmpieProductId) > 0 )  { $isStaticProduct = true; }
                                    
                                    if (!$registered_pm_scripts) { $udrawPriceMatrix->registerScripts(); $registered_pm_scripts = true; }
                                    $this->registerjQueryFileUpload();
                                    require_once("price-matrix/templates/frontend/price-matrix.php");                            
                                }
                            }
                        //}
                    }
                    
                } else {
                    
                    // This is a not a uDraw or PDF template, but maybe has a price matrix attached to it.     
                    // Simple products are only supported for price matrix products, as price matrix takes care of the variable pricing for you.
                    if ($product_type == "simple") {                        
                        // If access key is defined, it found a price matrix match and we'll load up the UI and components.
                        if ($isPriceMatrix) {
                            
                            // Category has a price matrix assigned to it.
                            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

                            remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);

                            add_action('woocommerce_simple_add_to_cart', array(&$this, 'display_price_matrix_UI'), 30);
                        } else {
                            // This is a uDraw Simple Product, but no templates selected. We will just allow file upload on a simple product.
                            $this->registerjQueryFileUpload();
                            remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
                            add_action('woocommerce_simple_add_to_cart', array(&$this, 'woo_udraw_load_simple_upload_ui'), 30);
                        }
                    }
                    
                }
            }
        }
        
        public function isMobileDev(){
            if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
                $user_ag = $_SERVER['HTTP_USER_AGENT'];
                if(preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis',$user_ag)){
                    return true;
                } else {
                    return false;
                };
            } else {
                    return false;    
            };
        }
        
        private function load_designer_skin ($template_id, $skin,$displayOptionsFirst,$allowCustomerDownloadDesign,$isPriceMatrix,$templateCount,$isTemplatelessProduct,$isuDrawApparelProduct) {
            global $post;
            $override_skin = apply_filters('udraw_designer_ui_override', false, $template_id, $skin, $displayOptionsFirst,$allowCustomerDownloadDesign,$isPriceMatrix,$templateCount,$isTemplatelessProduct,$isuDrawApparelProduct);
            if (!$override_skin) {
                if ($skin === 'default' || $skin === 'fullscreen') {
                    $this->registerDesignerDefaultStyles();
                    require_once("designer/bootstrap-default/designer-frontend.php");
                } else if ($skin === "simple") {
                    $this->register_designer_simple_styles();
                    require_once("designer/bootstrap-simple/designer-frontend.php");
                } else if ($skin === "optimal") {
                    $this->register_designer_optimal_styles();
                    require_once("designer/bootstrap-optimal/designer.php");
                } else if ($skin === "sleek") {
                    $this->register_designer_sleek_styles();
                    if ($this->isMobileDev()) {
                        require_once("designer/bootstrap-sleek/sleek-mobile/designer.php");
                    } else {
                        require_once("designer/bootstrap-sleek/sleek-desktop/designer.php");
                    }
                } else if ($skin === "slim") {
                    $this->register_designer_slim_styles();
                    if ($this->isMobileDev()) {
                        require_once("designer/bootstrap-slim/slim-mobile/designer.php");
                    } else {
                        require_once("designer/bootstrap-slim/slim-desktop/designer.php");
                    }
                }
            }
        }
        
        public function display_price_matrix_UI() 
        {
            global $post;
            $udrawPriceMatrix = new uDrawPriceMatrix();
            $udraw_price_matrix_access_key = $udrawPriceMatrix->get_product_price_matrix_key($post->ID);
            $this->registerStyles();
            $this->registerDesignerDefaultStyles();
            $udrawPriceMatrix->registerScripts();
            $isStaticProduct = true;
            require_once("price-matrix/templates/frontend/price-matrix-product.php");
            ?>
            <script>
                jQuery(document).ready(function() {
                    display_udraw_price_matrix_preview();
                    jQuery('#udraw-price-matrix-ui').fadeIn();                                
                });
            </script>
            <?php            
        }

        /**
         * Custom form data for the uDraw product. Added to the "Add To Cart" form.
         */
        public function woo_udraw_add_product_designer_form() {
            global $post;
            if (self::is_udraw_product($post->ID)) {
                ?>
                <input type="hidden" value="" name="udraw_product" />
                <input type="hidden" value="" name="udraw_product_data" />
                <input type="hidden" value="" name="udraw_product_svg" />
                <input type="hidden" value="" name="udraw_product_preview" />
                <input type="hidden" value="" name="udraw_product_cart_item_key" />
                <?php
            }
        }

        public function woo_udraw_add_cart_item($cart_item) {
            return $cart_item;
        }

        /**
         * Insert extra values from form to $cart_item_meta
         */
        public function woo_udraw_add_cart_item_data($cart_item_meta, $product_id) {           
            global $woocommerce;
            if (self::is_udraw_product($product_id)) {
                $uDrawConnect = new uDrawConnect();
                $uDrawUtil = new uDrawUtil();

                // Check to see if udraw data is already defined in cart item meta array.                                
                if (isset($cart_item_meta['udraw_data'])) {
                    if (isset($cart_item_meta['udraw_data']['reorder'])) {
                        return $cart_item_meta; // This is a re-order, no need to touch cart item meta array.
                    }
                }
                
                if (!file_exists(UDRAW_DESIGN_STORAGE_DIR)) {
                    wp_mkdir_p(UDRAW_DESIGN_STORAGE_DIR);
                }
                $cart_item_meta['udraw_data']['udraw_product'] = $_POST['udraw_product'];
                $cart_item_meta['udraw_data']['udraw_product_cart_item_key'] = $_POST['udraw_product_cart_item_key'];
                
                // Store Design Data on the file system.
                if (strlen($_POST['udraw_product_data']) > 0) {
                    $file_path = str_replace(UDRAW_STORAGE_URL, UDRAW_STORAGE_DIR, $_POST['udraw_product_data']);
                    //check file extension. If it's not xml, skip process. We can assume it's an update
                    $ext = pathinfo($file_path, PATHINFO_EXTENSION);
                    if ($ext === 'xml') {
                        if (file_exists($file_path)) {
                            $design_data = file_get_contents($file_path);
                        } else {
                            $design_data = base64_decode($_POST['udraw_product_data']);
                        }
                        $unid_id = uniqid();
                        $udraw_product_data_file = $unid_id . '_udf';
                        $udraw_preview_file = $unid_id . '_udp.png';
                        //As of 2.8.1 - We can skip this process since we export the preview images when we create the xml files
                        // Extract Images from the design and store on file system.
                        /*$uDrawDesignHandler = new uDrawDesignHandler();
                        $xmlStr = $uDrawDesignHandler->extract_images_from_design(UDRAW_STORAGE_DIR . '_designs_/', UDRAW_STORAGE_URL . '_designs_/', $unid_id, $design_data);                                                            
                        file_put_contents(UDRAW_STORAGE_DIR . '_designs_/' . $udraw_product_data_file, base64_encode($xmlStr));*/
                        file_put_contents(UDRAW_STORAGE_DIR . '_designs_/' . $udraw_product_data_file, base64_encode($design_data));
                        $cart_item_meta['udraw_data']['udraw_product_data'] = '_designs_/' . $udraw_product_data_file;
                        //We will leave this as it is
                        if ($this->startsWith($_POST['udraw_product_preview'], 'data:image')) {
                            $preview_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['udraw_product_preview']));
                            file_put_contents(UDRAW_TEMP_UPLOAD_DIR . $udraw_preview_file, $preview_data);
                            $cart_item_meta['udraw_data']['udraw_product_preview'] = UDRAW_TEMP_UPLOAD_URL . $udraw_preview_file;
                        } else {
                            $cart_item_meta['udraw_data']['udraw_product_preview'] = $_POST['udraw_product_preview'];
                        }
                    } else {
                        $cart_item_meta['udraw_data']['udraw_product_preview'] = $_POST['udraw_product_preview'];
                        $cart_item_meta['udraw_data']['udraw_product_data'] = str_replace(UDRAW_STORAGE_URL, '', $_POST['udraw_product_data']);
                    }
                }
                
                                
                // PDF Blocks Options
                $cart_item_meta['udraw_data']['udraw_pdf_block_product_id'] = (isset($_POST['udraw_pdf_block_product_id'])) ? $_POST['udraw_pdf_block_product_id'] : NULL;
                //Check if product has block product id attached to it
				$block_post_meta = get_post_meta($product_id, '_udraw_block_template_id', true);
                //if (metadata_exists('post', $product_id, '_udraw_block_template_id') && get_post_meta($product_id, '_udraw_block_template_id', true) !== NULL ){
                if (metadata_exists('post', $product_id, '_udraw_block_template_id') && !isset($block_post_meta) && get_post_meta($product_id, '_udraw_block_template_id', true) !== '' ){
                    if (!isset($_POST['udraw_pdf_block_product_id'])) {
                        error_log('Block Product ID is missing.');
                        error_log(print_r($_SERVER, true));
                        error_log(print_r($_POST, true));
                        //Redirect back to the product page and display error
                        $error_object   = array(
                            'error'     => true,
                            'type'      => 'pdf_block'
                        );
                        apply_filters( 'udraw_cart_redirect_after_error', get_permalink( $product_id ), $product_id, $error_object );
                    }
                }
                if (isset($_POST['udraw_pdf_block_product_id']) && strlen($_POST['udraw_pdf_block_product_id']) > 0) {
                    // Attempt to download the thumbnail locally.
                    //$uDrawConnect->__downloadFile($_POST['udraw_pdf_block_product_thumbnail'], UDRAW_TEMP_UPLOAD_DIR . $previewImage);
                    $pdfThumb = $_POST['udraw_pdf_block_product_thumbnail'];
                    if ($this->endsWith($pdfThumb, '.pdf')){
                        $previewImage = uniqid('preview');
                        // Pass the PDF document to remote converting server and convert to PNG document.
                        $data = array(
                            'pdfDocument' => $pdfThumb,
                            'key' => uDraw::get_udraw_activation_key(),
                            'resolution' => 'low'
                        );
                        $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2PNG', http_build_query($data)));                
                        if ($udraw_convert_response->isSuccess) {
                            if (is_array($udraw_convert_response->data)) {
                                $uDrawConnect->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[0], UDRAW_TEMP_UPLOAD_DIR . $previewImage . '.png');
                                $cart_item_meta['udraw_data']['udraw_pdf_block_product_thumbnail'] = UDRAW_TEMP_UPLOAD_URL . $previewImage . '.png';
                            }
                        }                        
                    } else {
                        $previewImage = uniqid('preview') . '.png';
                        $uDrawConnect->__downloadFile($pdfThumb, UDRAW_TEMP_UPLOAD_DIR . $previewImage);
                        $cart_item_meta['udraw_data']['udraw_pdf_block_product_thumbnail'] = UDRAW_TEMP_UPLOAD_URL . $previewImage;
                    }
                    $cart_item_meta['udraw_data']['udraw_pdf_block_product_data'] = mb_convert_encoding($_POST['udraw_pdf_block_product_data'],'HTML-ENTITIES','utf-8');
                    if (isset($_POST['udraw_pdf_order_info']) && strlen($_POST['udraw_pdf_order_info']) > 0) {
                        $cart_item_meta['udraw_data']['udraw_pdf_order_info'] = $_POST['udraw_pdf_order_info'];
                    }
                }
                
                // XMPie Options
                $cart_item_meta['udraw_data']['udraw_pdf_xmpie_product_id'] = (isset($_POST['udraw_pdf_xmpie_product_id'])) ? $_POST['udraw_pdf_xmpie_product_id'] : NULL;   
                //Check if product has xmpie template id attached to it
                $xmpie_post_meta = get_post_meta($product_id, '_udraw_xmpie_template_id', true);
                //if (metadata_exists('post', $product_id, '_udraw_xmpie_template_id') && get_post_meta($product_id, '_udraw_xmpie_template_id', true) !== NULL && get_post_meta($product_id, '_udraw_xmpie_template_id', true) !== '' ){
                if (metadata_exists('post', $product_id, '_udraw_xmpie_template_id') && !isset($xmpie_post_meta) && get_post_meta($product_id, '_udraw_xmpie_template_id', true) !== '' ){
                    if (!isset($_POST['udraw_pdf_xmpie_product_id'])) {
                        error_log('XMPie Product ID is missing.');
                        error_log(print_r($_SERVER, true));
                        error_log(print_r($_POST, true));
                        //Redirect back to the product page and display error
                        $error_object   = array(
                            'error'     => true,
                            'type'      => 'xmpie'
                        );
                        apply_filters( 'udraw_cart_redirect_after_error', get_permalink( $product_id ), $product_id, $error_object );
                    }
                }
                if (isset($_POST['udraw_pdf_xmpie_product_id']) && strlen($_POST['udraw_pdf_xmpie_product_id']) > 0) {
                    // Download the thumbnail locally.
                    $previewImage = uniqid('preview') . '.png';                    
                    $uDrawConnect->__downloadFile($_POST['udraw_pdf_xmpie_product_thumbnail'], UDRAW_TEMP_UPLOAD_DIR . $previewImage);                            
                    $cart_item_meta['udraw_data']['udraw_pdf_xmpie_product_thumbnail'] = UDRAW_TEMP_UPLOAD_URL . $previewImage;
                    $cart_item_meta['udraw_data']['udraw_pdf_xmpie_product_data'] = mb_convert_encoding($_POST['udraw_pdf_xmpie_product_data'],'HTML-ENTITIES','utf-8');
                }
                
                // Attached Uploaded Files
                $cart_item_meta['udraw_data']['udraw_options_uploaded_files'] = (isset($_POST['udraw_options_uploaded_files'])) ? $_POST['udraw_options_uploaded_files'] : NULL;
                // Note if its a converted PDF design
                $cart_item_meta['udraw_data']['udraw_options_converted_pdf'] = (isset($_POST['udraw_options_converted_pdf'])) ? $_POST['udraw_options_converted_pdf'] : NULL;
                // If file structure have been uploaded
                $cart_item_meta['udraw_data']['udraw_options_uploaded_excel'] = (isset($_POST['udraw_options_uploaded_excel'])) ? $_POST['udraw_options_uploaded_excel'] : NULL;
                
                $cart_item_meta['udraw_data']['udraw_options_uploaded_files_preview'] = (isset($_POST['udraw_options_uploaded_files_preview'])) ? $_POST['udraw_options_uploaded_files_preview'] : NULL;
                
                // Detect if uploaded files is a PDF document. If so, lets try to convert it to a preview image.
                if (strlen($cart_item_meta['udraw_data']['udraw_options_uploaded_files']) > 0) {
                    $uploaded_files = json_decode(stripcslashes($cart_item_meta['udraw_data']['udraw_options_uploaded_files']));
                    foreach ($uploaded_files as $upload_file) {
                        if ($this->endsWith($upload_file->url, ".pdf")) {          
                            // Pass the PDF document to remote converting server and convert to PNG document.
                            $data = array(
                                'pdfDocument' => $upload_file->url,
                                'key' => uDraw::get_udraw_activation_key(),
                                'resolution' => 'low'
                            );
                            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2JPG', http_build_query($data)));                
                            if ($udraw_convert_response->isSuccess) {
                                if (is_array($udraw_convert_response->data)) {
                                    $uDrawConnect->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[0], UDRAW_TEMP_UPLOAD_DIR . $upload_file->name . '.png');
                                    $cart_item_meta['udraw_data']['udraw_options_uploaded_files_preview'] = UDRAW_TEMP_UPLOAD_URL . $upload_file->name . '.png';
                                }
                            }                            
                            break;
                        }
                    }
                }
                
                $cart_item_meta = apply_filters('udraw_add_cart_item_data', $cart_item_meta);
            }
            
            return $cart_item_meta;
        }

        public function woo_udraw_get_cart_item_from_session($cart_item, $values) {
            // Check for udraw_data in session
            if (isset($values['udraw_data'])) {
                $cart_item['udraw_data'] = $values['udraw_data'];
            }

            // Check if item is uDraw product
            if (isset($cart_item['udraw_data'])) {
                $this->woo_udraw_add_cart_item($cart_item);
            }
            return $cart_item;
        }
        public function woo_udraw_get_item_data($other_data, $cart_item) {
            // Get uDraw Data           
            $udraw_data = $cart_item['udraw_data'];
            
            if (isset($udraw_data)) {
                $other_data = apply_filters('udraw_get_item_data', $other_data, $udraw_data);
            }
            // Make sure that the uDraw product contains the design data.
            if ( (isset($udraw_data['udraw_product_data']) && $udraw_data['udraw_product_data']) ||
                 (isset($udraw_data['udraw_pdf_block_product_id'])) || (isset($udraw_data['udraw_options_uploaded_files']) && strlen($udraw_data['udraw_options_uploaded_files']) > 0) || isset($udraw_data['udraw_options_uploaded_excel'])) {
                                
                if (isset($cart_item['udraw_data'])) {
                    
                    global $woocommerce;
                    //get cart item key
                    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                        if ($values === $cart_item) {
                            $cik = $cart_item_key;
                        }
                    }
                    
                    if (isset($udraw_data['udraw_options_uploaded_files']) && strlen($udraw_data['udraw_options_uploaded_files']) > 0) {
                        $uploaded_files = json_decode(stripcslashes($udraw_data['udraw_options_uploaded_files']));
                        foreach ($uploaded_files as $upload_file) {
							array_push($other_data, array('name' => "Attached", 'value' => $upload_file->name));
                        }
                    } else {
                        if (isset($udraw_data['udraw_pdf_order_info'])) {
                            if (strlen($udraw_data['udraw_pdf_order_info']) > 0) {
                                $pdf_order_info = json_decode(stripcslashes($udraw_data['udraw_pdf_order_info']));
                                if (gettype($pdf_order_info) == "array") {
                                    for ($x = 0; $x < count($pdf_order_info); $x++) {
                                        array_push($other_data, array(
                                            'name' => $pdf_order_info[$x]->name,
                                            'value' => $pdf_order_info[$x]->value
                                        ));  
                                    }
                                }
                            }
                        }
                        
                        $displayOptionsFirst = get_post_meta($cart_item['product_id'], '_udraw_display_options_page_first', true); 
                        // Disable  'Update Design' feature if option '_udraw_display_options_page_first' is enabled.
                        
                        if ($displayOptionsFirst != "yes") {
                            array_push($other_data, array(
                                'name' => 'Design',
                                'value' => '<a href="' . esc_url(add_query_arg(array('cart_item_key' => $cik), get_permalink($cart_item['product_id']))) . '">Update</a>'
                            ));                        
                        }
                    }

                    if (isset($udraw_data['old-order-number']) && strlen($udraw_data['old-order-number']) > 0) {
                        array_push($other_data, array('name' => "Old Order #", 'value' => $udraw_data['old-order-number']));
                    }
                }
            }
            
            if (isset($cart_item['udraw_data'])) {
                $other_data = apply_filters('udraw_add_cart_item_meta', $other_data, $udraw_data);
            }
            
            return $other_data;
        }
        
        /**
         * Action to handle cart data before showing it to the customer. 
         * If the customer is updating the product, we will remove the new cart item and merge it's data to the original cart item.
         * This is really the only way to update the product meta data with out creating a new cart item.
         */
        public function woo_udraw_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) 
        {
            global $woocommerce;
            if (isset($cart_item_data['udraw_data'])) {
                if (isset($cart_item_data['udraw_data']['udraw_product_cart_item_key'])) {                        
                    if (strlen($cart_item_data['udraw_data']['udraw_product_cart_item_key']) > 1 ) {
                        // This item is an update item.
                        $orig_cart_item_key = $cart_item_data['udraw_data']['udraw_product_cart_item_key'];
                        
                        $foundMatch = false;
                        foreach ($woocommerce->cart->get_cart() as $key => $values) {
                            if ($key == $orig_cart_item_key) {
                                // Found original item. 
                                $foundMatch = true;
                            }
                        }
                        
                        
                        if ($foundMatch) {
                            // remove original cart item and add the new one.
                            $woocommerce->cart->remove_cart_item($orig_cart_item_key);
                        }
                    }
                }
            }
        }
        
        public function woo_udraw_cart_item_product($product, $cart_item, $cart_item_key) {
            return $product;
        }    
        
        public function woo_udraw_after_order_details($order) {
            global $woocommerce;
            if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                $order_id = $order->get_id();
            } else {
                $order_id = $order->id;
            }
            wp_register_script('udraw_base64_js', plugins_url('assets/Base64.js', __FILE__));
            wp_enqueue_script('udraw_base64_js');
            
            $udrawSettings = new uDrawSettings();
            $uDrawUtil = new uDrawUtil();
            $_udraw_settings = $udrawSettings->get_settings();
            $fbAppId = $_udraw_settings['designer_facebook_app_id'];
            $orderItems = $order->get_items();
            $status = get_post_status($order->get_id());
            
            //echo '<h2 style="padding-top: 25px;">Download Files</h2>';
            echo '<table class="shop_table download_files"><tbody>';
            foreach($orderItems as $key => $product) {
                $item_id = $key;
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $product_id = ($product['variation_id']) ? $product['variation_id'] : $product->get_product_id();
                } else {
                   $product_id = ($product['variation_id']) ? $product['variation_id'] : $product['product_id'];
                }
                
                $downloadable = get_post_meta( $product_id, '_udraw_allow_post_payment_download', true );
                if ($downloadable === 'yes') {
                    echo '<h2 style="padding-top: 25px;">Download Files</h2>';
                    //unserialize($orderItem['udraw_data'])['udraw_product_data'] == data-product in admin->order
                    //$udraw_product_data = unserialize($product['udraw_data'])['udraw_product_data'];
                    $order_id = trim(str_replace('#', '', $order->get_order_number()));
                    //$_pdf_path = "uDraw-Order-" . $order_id . "-" . $key . ".pdf";
                    $_png_path = "uDraw-Order-" . $order_id . "-" . $key . ".png";
                    $_pdf_path = wc_get_order_item_meta($item_id, '_udraw_pdf_path', true);
                    $_jpg_path = wc_get_order_item_meta($item_id, '_udraw_product_jpg', true);
                    
                    echo '<tr>';
                    echo '<td><label>'.$product["name"].'</label></td>';
                    echo '<td>';
                    if (strlen(unserialize($product['udraw_data'])['udraw_options_uploaded_excel'])) {
                        $order_dir = UDRAW_ORDERS_DIR.'uDraw-Order-'.$order_id.'-'.$item_id;
                        if (file_exists($order_dir)) {
                            if (!$uDrawUtil->is_dir_empty($order_dir)) {
                                echo '<a class="pdfPackage" href="#" onclick="javascript: download_package('.$order_id.','.$item_id.'); return false;">Download PDF package</a>';
                            } else {
                                echo '<label>Sorry, PDF download is unavailable for this order.</label>';
                            }
                        } else {
                            echo '<label>Sorry, PDF download is unavailable for this order.</label>';
                        }
                    } else {
                        if (strlen($_pdf_path) > 0 ) {
                            echo '<a href="' . $_pdf_path . '" target="_blank"  class="pdfDownload">Download PDF</a>';
                        } else {
                            echo '<div id="loadingFile" style="text-align: center;"><p>Please wait while we load your file!</p><img src="data:image/gif;base64,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" style="width:auto;margin: 0 auto;display: block;"></div>';
                            header("Refresh:15");
                        }
                    }
                    echo '</td>';
                    if (strlen($_png_path) > 0) {
                    echo '<td><a href="' . UDRAW_ORDERS_URL . $_png_path . '" target="_blank" class="pngDownload">Download PNG</a></td>';
                    }

                    if (strlen($_jpg_path) > 0) {
                        echo '<td><a href="#" onclick="window.open(\''.$_jpg_path.'\'); return false;" class="jpgDownload">Download JPG</a></td>';
                    } /*else {
                        echo '<td><label>Sorry, JPG download is unavailable for this order</label></td>';
                    }*/
                    echo '<td>';
                    //Share PNG
                    if (strlen($fbAppId) > 0) {
                        ?>
                        <div class="fb-share-button" data-href="<?php echo $_png_path ?>" data-layout="button" data-mobile-iframe="true" style="padding-bottom: 5px;"></div>
                        <div id="fb-root"></div>
                        <script>
                        (function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=<?php echo $fbAppId ?>";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                        </script>
                        <?php
                    }
                    ?>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $_png_path ?>" data-text="My design | ">Tweet</a>
                    <a data-pin-do="buttonBookmark" data-pin-custom="true" data-pin="true" href="https://www.pinterest.com/pin/create/button/" style="display: block;"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_28.png" /></a>
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                    <script type="IN/Share" data-url="<?php echo $_png_path ?>"></script>
                    <a href="mailto:?body=<?php echo $_png_path ?>" class="button" style="padding: 10px; height: initial;" onclick="window.open(this.href, 'Email',
'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); open_email_tip(this); return false;"><span style="font-size: 12px;">Email</span></a>
                    <span class="email-tip" style="font-size: 10px; line-height: 1.5em; display: none;"><?php echo _e('Be sure that an email handler is set in your browser settings.', 'udraw')?></span>
                    <?php
                    echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody></table>';
            ?>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
            <script>
                function open_email_tip(element) {
                    setTimeout(function(){
                        jQuery(element).siblings('.email-tip').css('display','inline-block');
                    }, 1000);
                }
                function download_package(order_id, item_id) {
                    var order_dir = '<?php echo str_replace('\\', '\\\\', UDRAW_ORDERS_DIR); ?>';
                    var order_url = '<?php echo str_replace('\\', '\\\\', UDRAW_ORDERS_URL); ?>';
                    var uniqueID = '<?php echo uniqid() ?>';

                    var destination_dir = order_dir + 'uDraw-Order-' + order_id + '-' + item_id + '-' + uniqueID + '.zip';
                    var destination_url = order_url + 'uDraw-Order-' + order_id + '-' + item_id + '-' + uniqueID + '.zip';
                    jQuery.ajax({
                        url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                        data: {
                            action: 'udraw_package_excel_designs',
                            target_dir: Base64.encode(order_dir + 'uDraw-Order-' + order_id + '-' + item_id + '/'),
                            destination: destination_dir,
                            overwrite: true
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response) {
                                window.open(destination_url);
                            } else {
                                window.alert("An error had occurred.");
                            }
                        }
                    });
                };
            </script>
            <?php
        }
        
        
        /**
         * Function to display dynamic image of product image ( if defined in meta data ).
         * 
         * @param mixed $orig_html (Prefix) HTML if required
         * @param mixed $cart_item Cart Item Object
         * @param mixed $cart_item_key Cart Item Key Object
         * @return mixed
         */
        private function _display_cart_item_thumbnail($cart_item, $cart_item_key) {
            $thumbnail = "";
            
            // Support for variations and uDraw data
            $url_params = array();
            if (count($cart_item['variation']) > 0) {
                if (is_array($cart_item['variation'])) {
                    $url_params = $cart_item['variation'];   
                } else {
                    array_push($url_params, $cart_item['variation']);
                }
            }
            
            array_push($url_params, array('cart_item_key' => $cart_item_key));  
                     
            if (isset($cart_item['udraw_data'])) {
                $udraw_data = $cart_item['udraw_data'];
                $dt = new DateTime();
                $timestamp = $dt->getTimestamp();
                
                if (isset($cart_item['udraw_data']['udraw_options_uploaded_files']) && strlen($cart_item['udraw_data']['udraw_options_uploaded_files']) > 0) {
                    // Uploaded Thumbnail
                    // TODO: Make this optional.                    
                    $uploaded_files = json_decode(stripcslashes($cart_item['udraw_data']['udraw_options_uploaded_files']));
                    
                    if (strlen($cart_item['udraw_data']['udraw_options_uploaded_files_preview']) > 0) {
                        // Use converted PDF preview image
                        $thumbnail .= '<a href="' . esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))) . '">';
                        $thumbnail .= '<img src="'. $cart_item['udraw_data']['udraw_options_uploaded_files_preview'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product" style="width:250px; height: auto" />';
                        $thumbnail .= '</a>';
                        return $thumbnail;
                    }
                    
                    if (!$this->endsWith(strtolower($uploaded_files[0]->url), ".pdf")) {
                        if ($this->endsWith(strtolower($uploaded_files[0]->url), "png") || $this->endsWith(strtolower($uploaded_files[0]->url), "jpg") ||
                            $this->endsWith(strtolower($uploaded_files[0]->url), "jpeg") || $this->endsWith(strtolower($uploaded_files[0]->url), "gif") ) {
                            $thumbnail .= '<a href="' . esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))) . '">';
                            $thumbnail .= '<img src="'. $uploaded_files[0]->url .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product" style="width:250px;" />';
                            $thumbnail .= '</a>';
                            return $thumbnail;
                        } else {
                            return $thumbnail;
                        }
                    }                    
                } else if (isset($udraw_data['udraw_pdf_block_product_thumbnail']) && strlen($udraw_data['udraw_pdf_block_product_thumbnail']) > 0) {
                    // PDF Block Thumbnail
                    $thumbnail .= '<a href="' . esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))) . '">';
                    $thumbnail .= '<img src="'. $udraw_data['udraw_pdf_block_product_thumbnail'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product" style="width:250px;">';
                    $thumbnail .= '</a>';
                    return $thumbnail;
                } else if (isset($udraw_data['udraw_pdf_xmpie_product_thumbnail']) && strlen($udraw_data['udraw_pdf_xmpie_product_thumbnail']) > 0) {
                    // XMPie Block Thumbnail
                    $thumbnail .= '<a href="' . esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))) . '">';
                    $thumbnail .= '<img src="'. $udraw_data['udraw_pdf_xmpie_product_thumbnail'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product" style="width:250px;">';
                    $thumbnail .= '</a>';
                    return $thumbnail; 
                } else if (isset($udraw_data['udraw_product_preview']) && $udraw_data['udraw_product_preview']) {
                    // uDraw Designer Thumbnail
                    $thumbnail .= '<a href="' . esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))) . '">';
                    $thumbnail .= '<img src="'. $udraw_data['udraw_product_preview'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product" style="width:250px;">';
                    $thumbnail .= '</a>';
                    return $thumbnail;
                } else {
                    return $thumbnail;
                }
            }
            
            // Default Image, If didn't find any uDraw related product images thumbnails.
            return $thumbnail;
        }
        
        /**
         * Update product image if uDraw product on the cart page.
         * 
         * @param type $image
         * @param type $cart_item
         * @return type
         */
        public function woo_udraw_cart_item_thumbnail($image, $cart_item, $cart_item_key) {
            $thumbnail = $this->_display_cart_item_thumbnail($cart_item, $cart_item_key);
            if ($thumbnail == "") { return $image; }
            
            return $thumbnail;
        }
        
        /**
         * Update product name if uDraw Product on the cart page.
         * 
         * @param type $name
         * @param type $cart_item
         * @param type $cart_item_key
         * @return type
         */
        public function woo_udraw_cart_item_name($name, $cart_item, $cart_item_key) {
            global $woocommerce;
            
            // Support for variations and uDraw data
            $url_params = array();
            if (count($cart_item['variation']) > 0) {
                if (is_array($cart_item['variation'])) {
                    $url_params = $cart_item['variation'];   
                } else {
                    array_push($url_params, $cart_item['variation']);
                }
            }
            
            array_push($url_params, array('cart_item_key' => $cart_item_key));
            
            if (isset($cart_item['udraw_data'])) {
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $_post = get_post( $cart_item['data']->get_id() );
                } else {
                    $_post = $cart_item['data']->post;
                }
                $thumbnail = $this->_display_cart_item_thumbnail($cart_item, $cart_item_key);
                $item_name = sprintf('<a href="%s">%s</a>', esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))), $_post->post_title);
                
                if (is_cart()) { return $item_name; }
                
                return $thumbnail . $item_name;
            } else {
                return $name;
            }
        }
        
        /**
         * Code executed after cart template has loaded.
         */
        public function woo_udraw_after_cart() {
            
            // Remove the link of the images on the cart page. 
            // I had to do this becasue there was no filter to override url for thumbnail.  
            //echo "<script>jQuery('.product-thumbnail a').removeAttr('href').css('cursor','default');</script>";
            echo "<script>jQuery('input[type=number]').css('width', '60px');</script>";
        }
        
        /**
         * Add order meta from the cart
         */
        public function woo_udraw_add_order_item_meta( $item_id, $values, $item_key) {
            global $woocommerce; $getIndex = 0;
            foreach($woocommerce->cart->get_cart() as $key => $cart_item) {
                //Get line item index
                $getIndex = $getIndex + 1;
                if ( $item_key == $key ) { 
                    break;
                }
            }

            if( isset( $values['udraw_data']) ) {
                $udraw_data = $values['udraw_data'];
                $uploaded_files = (isset($udraw_data['udraw_options_uploaded_files'])) ? json_decode(stripcslashes($udraw_data['udraw_options_uploaded_files'])) : NULL;
                if ($uploaded_files != NULL) {
                    if (count($uploaded_files) > 0) {
                        $uDrawSettings = new uDrawSettings();
                        $_settings = $uDrawSettings->get_settings();
                        $doc_formatted_name = "";
                        if (!is_null($_settings['udraw_order_document_format'])) {
                            if (strlen($_settings['udraw_order_document_format']) > 0) {
                                $qty = (isset($udraw_data['udraw_price_matrix_qty'])) ? $udraw_data['udraw_price_matrix_qty'] : wc_get_order_item_meta($item_id, '_qty', true);
                                $doc_formatted_name = $_settings['udraw_order_document_format'];
                                $doc_formatted_name = str_replace('%_ORDER_ID_%', wc_get_order_id_by_order_item_id($item_id), $doc_formatted_name);
                                $doc_formatted_name = str_replace('%_JOB_ID_%', $item_id, $doc_formatted_name);
                                $doc_formatted_name = str_replace('%_ITEM_INDEX_%', $getIndex, $doc_formatted_name);    
                                $doc_formatted_name = str_replace('%_QUANTITY_%', $qty, $doc_formatted_name);                                                 
                            }
                        }

                        if (strlen($doc_formatted_name) > 3) {
                            for ($x = 0; $x < count($uploaded_files); $x++) {
                                $_upload_file = str_replace(UDRAW_TEMP_UPLOAD_URL, UDRAW_TEMP_UPLOAD_DIR, $uploaded_files[$x]->url);
                                if (file_exists($_upload_file)) {
                                    $_formated_uploaded_file = $doc_formatted_name . "_" . pathinfo($_upload_file, PATHINFO_FILENAME) . "." . pathinfo($_upload_file, PATHINFO_EXTENSION);                                    
                                    $_order_id = wc_get_order_id_by_order_item_id($item_id);

                                    wp_mkdir_p(UDRAW_ORDERS_DIR . '/' . $_order_id);                                    
                                    copy($_upload_file, UDRAW_ORDERS_DIR . '/' . $_order_id . '/' . $_formated_uploaded_file);

                                    $uploaded_files[$x]->name = $_formated_uploaded_file;
                                    $uploaded_files[$x]->url = UDRAW_ORDERS_URL . '/' . $_order_id . '/' . $_formated_uploaded_file;                                     
                                }
                            }

                            $udraw_data['udraw_options_uploaded_files'] = json_encode($uploaded_files);
                        }
                        
                        foreach ($uploaded_files as $upload_file) {
                            wc_add_order_item_meta($item_id, "Attached (" . $upload_file->name .")",
                                '<a href="'. $upload_file->url .'">Download</a>', false);
                        }
                    }
                }
                
                if (isset($udraw_data['udraw_pdf_order_info'])) {
                    if (strlen($udraw_data['udraw_pdf_order_info']) > 0) {
                        $pdf_order_info = json_decode(stripcslashes($udraw_data['udraw_pdf_order_info']));
                        if (gettype($pdf_order_info) == "array") {
                            for ($x = 0; $x < count($pdf_order_info); $x++) {
                                wc_add_order_item_meta($item_id,$pdf_order_info[$x]->name, $pdf_order_info[$x]->value, false);
                            }
                        }
                    }
                }

                if (isset($udraw_data['old-order-number']) && strlen($udraw_data['old-order-number']) > 0) {
                    wc_add_order_item_meta($item_id, "Old Order #", $udraw_data['old-order-number'], false);
                }

                wc_add_order_item_meta($item_id, 'udraw_data', $udraw_data);

                do_action('udraw_add_order_item_meta', $item_id, $values, $udraw_data);
            }
        }
        
        /**
         * Filter for Order Details on Frontend so Customers can view their design instead of default template for their orders.
         */
        public function woo_udraw_order_item_name($default , $item) {
            global $woocommerce;
            if( isset( $item['udraw_data']) ) {
                if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                    $udrawData = $item['udraw_data'];
                } else {
                    $udrawData = unserialize($item['udraw_data']);
                }
                $custom_thumbnail = apply_filters('udraw_order_item_name', $default, $udrawData, $item);
                if ($custom_thumbnail !== $default) {
                    return $custom_thumbnail;
                }
                if (isset($udrawData['udraw_price_matrix_name'])) {
                    $udrawPriceMatrix = new uDrawPriceMatrix();
                    return $udrawPriceMatrix->order_item_thumbnail( $default, $item);
                } else {
                    
                    $thumbnail = $default . '<br />';
                    
                    // Handle 1st uploaded image as thumbnail.
                    if (isset($udrawData['udraw_options_uploaded_files'])) {
                        $uploaded_files = json_decode(stripcslashes($udrawData['udraw_options_uploaded_files']));
                        
                        if (count($uploaded_files) > 0 && !$this->endsWith(strtolower($uploaded_files[0]->url), ".pdf")) {
                            if ($this->endsWith(strtolower($uploaded_files[0]->url), "png") || $this->endsWith(strtolower($uploaded_files[0]->url), "jpg") ||
                                $this->endsWith(strtolower($uploaded_files[0]->url), "jpeg") || $this->endsWith(strtolower($uploaded_files[0]->url), "gif") ) {
                                $thumbnail = '<a href="' . esc_url(add_query_arg(array(), get_permalink($item['product_id']))) . '">';
                                $thumbnail .= '<img src="'. $uploaded_files[0]->url .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product" style="max-width:240px; max-height:140px;" />';
                                $thumbnail .= '</a>';
                                return $default . '<br />' . $thumbnail;
                            }
                        }
                    }
                    
                    if (isset($udrawData['udraw_pdf_block_product_thumbnail']) && strlen($udrawData['udraw_pdf_block_product_thumbnail']) > 0) {
                        // PDF Block Thumbnail
                        return $default . '<br />' . '<img style="max-width:440px;max-height:340px;" src="'. $udrawData['udraw_pdf_block_product_thumbnail'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product">';
                    } else if (isset($udrawData['udraw_pdf_xmpie_product_thumbnail']) && strlen($udrawData['udraw_pdf_xmpie_product_thumbnail']) > 0) {
                        // XMPie Thumbnail
                        return $default . '<br />' . '<img style="max-width:440px;max-height:340px;" src="'. $udrawData['udraw_pdf_xmpie_product_thumbnail'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product">';
                    } else if (isset($udrawData['udraw_options_uploaded_files_preview']) && strlen($udrawData['udraw_options_uploaded_files_preview']) > 0) {
                        // Uploaded PDF preview image
                        return $default . '<br />' . '<img style="max-width:440px;max-height:340px;" src="'. $udrawData['udraw_options_uploaded_files_preview'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="'. $item['name'] .'">';
                    } else {
                        if (isset($udrawData['udraw_product_preview']) && strlen($udrawData['udraw_product_preview']) > 0) {
                        // uDraw Designer Thumbnail
                            return $default . '<br />' . '<img style="max-width:240px;max-height:140px;" src="'. get_bloginfo('wpurl') . $udrawData['udraw_product_preview'] . '?' . $timestamp .'" class="attachment-shop_thumbnail wp-post-image" alt="'. $item['name'] .'">';
                        }
                    }
                    return $thumbnail;
                }
                
            } else {
                return $default;
            }            
        }
        
        public function woo_order_item_quantity_html($default, $item) {
            global $woocommerce;
            if( isset( $item['udraw_data']) ) {
                if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                    $udrawData = $item['udraw_data'];
                } else {
                    $udrawData = unserialize($item['udraw_data']);
                }
                if (isset($udrawData['udraw_price_matrix_qty'])) {
                    if (strlen($udrawData['udraw_price_matrix_qty']) > 0) { return ''; }
                }
            }
            
            return $default;
        }
        
        /**
         * Filter for cart item data when customer wants to re-order their previous order.
         */
        public function woo_udraw_order_again_cart_item_data ( $array, $item, $order ) {
            global $woocommerce;
            if( isset( $item['udraw_data']) ) {
                if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                    $udrawData = $item['udraw_data'];
                } else {
                    $udrawData = unserialize($item['udraw_data']);
                }
                $udrawData['old-order-number'] = $order->get_id();
                $udrawData['reorder'] = true;
                $udrawDataArray = array();
                $udrawDataArray['udraw_data'] = $udrawData;
                return $udrawDataArray;                
            }
            return $array;            
        }    
        
        /**
         * Filter for product visibility. This will hide private products.
         */
        public function woo_udraw_loop_shop_product() {
            global $product, $woocommerce;
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                $product_id = $product->get_id();
            } else {
                $product_id = $product->id;
            }
            if (get_post_meta($product_id, '_udraw_is_private_product', true) == "yes") {                
                $product = null;
            }
        }
        public function woo_udraw_product_is_visible($visible, $id) {
            global $udraw_is_private_list;
            
            if (isset($udraw_is_private_list)) { return $visible; }
            
            if (get_post_meta($id, '_udraw_is_private_product', true) == "yes") {                
                $visible = false;
            }
            return $visible;
        }
        
        public function woo_udraw_is_purchasable($purchasable, $product) {
            global $woocommerce;
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                $product_type = $product->get_type();
                $product_id = $product->get_id();
            } else {
                $product_type = $product->product_type;
                $product_id = $product->id;
            }
            $udrawPriceMatrix = new udrawPriceMatrix();
            $udraw_price_matrix_key = $udrawPriceMatrix->get_product_price_matrix_key($product_id);
            if ($product_type == "simple") {
                if (strlen($udraw_price_matrix_key) > 0) {
                   return true;
                }
            }
            return $purchasable;
        }
        

        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        // ------------------ Wordpress Frontend Methods ------------------------ //
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//        

        public function udraw_wp_nav_menu_objects($sorted_menu_items, $args) {
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            
            $current_user = wp_get_current_user();
            if ( !($current_user instanceof WP_User) )
                return $sorted_menu_items;            
            
            // Look for Private Template Page. If user is not logged in remove it from menu.
            // Also if user is logged in, but doesn't have any private templates, we should also remvoe it.
            $_private_template_page_id = $_udraw_settings['udraw_private_template_page_id'];
            $_private_template_to_remove = -1;
            
            $_customer_saved_design_page_id = $_udraw_settings['udraw_customer_saved_design_page_id'];
            $_customer_saved_to_remove = -1;
            
            for ($x = 1; $x <= count($sorted_menu_items); $x++) {
                
                // Handle Priavte Template Menu Item.
                if ($sorted_menu_items[$x]->object_id == $_private_template_page_id) {
                    if ($current_user->ID == 0) {
                        // User is not logged in.
                        $_private_template_to_remove = $x; break;
                    } else {
                        // User is logged in. We will check to see if they have any private templates.                        
                        if (!$this->user_has_private_templates($current_user->ID)) {
                            // User doesn't have any private templates. We will hide this menu.
                            $_private_template_to_remove = $x; break;
                        }
                    }                    
                }
            }
            
            for ($x = 1; $x <= count($sorted_menu_items); $x++) {
                // Handle Customer Template Menu Item.
                if ($sorted_menu_items[$x]->object_id == $_customer_saved_design_page_id) {
                    if ($current_user->ID == 0) {
                        // User is not logged in.
                        $_customer_saved_to_remove = $x; break;
                    } else {
                        // User is logged in. We will check to see if they have any saved designs.
                        if (!$this->user_has_saved_designs($current_user->ID)) {
                            // User doesn't have any saved designs. We will hide this menu.
                            $_customer_saved_to_remove = $x; break;
                        }
                    }                    
                }                
            }
            
            // Remove any page id, if needed.
            if ($_private_template_to_remove > 0) {
                unset($sorted_menu_items[$_private_template_to_remove]);
            }
            
            if ($_customer_saved_to_remove > 0) {
                unset($sorted_menu_items[$_customer_saved_to_remove]);
            }
            
            return $sorted_menu_items;
        }
        
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        // ------------------------ Helper Methods ------------------------------ //
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        
        public function startsWith($haystack, $needle) {
            // search backwards starting from haystack length characters from the end
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
        }
        
        public function endsWith($haystack, $needle) {
            // search forward starting from end minus needle length characters
            return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
        }
        
        public function generateRandomString($length = 18) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }        
        
		function udraw_dequeue_scripts() {
			if (function_exists("get_current_screen") && !is_null(get_current_screen())) {
				$_base_page = get_current_screen()->base;
				
				if ($_base_page == "udraw_page_udraw_add_template" || $_base_page == "admin_page_udraw_modify_template") {				
					// DeQueue some conflicting scripts.
					wp_dequeue_script("cloudfw-functions");
					wp_dequeue_script("cloudfw-script");	
				}
			}
			/*
			else {			
				if (get_post_type() == "product") {
						// DeQueue some conflicting scripts.
						wp_dequeue_script("cloudfw-functions");
						wp_dequeue_script("cloudfw-script");	
				}
			}*/
		}
        
        public function register_designer_min_js()
        {
            $dt = new DateTime();
            $timestamp = $dt->getTimestamp();
            wp_register_script('udraw_designer_js', plugins_url('designer/includes/js/Designer.min.js?version=' . $timestamp, __FILE__));
            //wp_register_script('udraw_designer_js', plugins_url('designer/includes/js/Designer.min.js', __FILE__));
            wp_enqueue_script('udraw_designer_js');
            
            do_action('udraw_enqueue_extra_designer_script');
        }

        public function register_designer_threed_min_js()
        {
            wp_register_script('udraw_designer_threed_js', plugins_url('designer/includes/js/Designer3D.min.js', __FILE__));
            wp_enqueue_script('udraw_designer_threed_js');
        }

        public function registerScripts() {
            // Register Scripts
            wp_register_script('udraw_webfont_js', UDRAW_WEBFONT_JS);

            // Enqueue Scripts
            $this->register_jquery_ui();
            wp_enqueue_script('udraw_webfont_js');   
        }

        public function registerStyles() {
            // Register Styles
            wp_register_style('udraw_bootstrap_css', UDRAW_BOOTSTRAP_CSS);
            wp_register_style('udraw_bootstrap_theme_css', plugins_url('designer/includes/css/udraw-bootstrap-theme.css', __FILE__));
            wp_register_style('udraw_jquery_css', UDRAW_JQUERY_UI_CSS);
            wp_register_style('udraw_jquery_theme_css', UDRAW_JQUERY_UI_THEME_CSS);
            wp_register_style('udraw_fontawesome_css', UDRAW_FONTAWESOME_CSS);
            wp_register_style('udraw_designer_css', plugins_url('designer/includes/css/Designer.min.css', __FILE__));
            wp_register_style('udraw_common_css' , plugins_url('assets/includes/uDraw.css', __FILE__));

            // Enqueue Styles
            wp_enqueue_style('udraw_bootstrap_css');
            wp_enqueue_style('udraw_bootstrap_theme_css');
            wp_enqueue_style('udraw_jquery_css');
            wp_enqueue_style('udraw_jquery_theme_css');
            wp_enqueue_style('udraw_fontawesome_css');
            wp_enqueue_style('udraw_designer_css');
            wp_enqueue_style('udraw_common_css');         
        }
        
        public function registerDesignerDefaultStyles(){
            //Dequeue custom bootstrap sheet
            wp_dequeue_style('udraw_bootstrap_css');
            
            wp_register_style('bootstrap_css', plugins_url('assets\bootstrap\css\bootstrap.min.css', __FILE__));
            wp_register_style('default_designer_css' , plugins_url('designer/bootstrap-default/designer.css', __FILE__));
            wp_register_script('default_designer_js', plugins_url('designer/bootstrap-default/designer.js', __FILE__));
            wp_enqueue_style('bootstrap_css');
            wp_enqueue_style('default_designer_css');
            wp_enqueue_script('default_designer_js');
        }
                
        public function register_designer_simple_styles() {
            wp_register_style('udraw_designer_ui_css' , plugins_url('designer/bootstrap-simple/simple_designer_css.css', __FILE__));
            wp_register_style('bootstrap_css', plugins_url('assets\bootstrap\css\bootstrap.min.css', __FILE__));
            wp_register_script('simple_designer_js', plugins_url('designer/bootstrap-simple/simple_designer_js.js', __FILE__));
            wp_enqueue_style('udraw_designer_ui_css');
            wp_enqueue_style('bootstrap_css');
            wp_enqueue_script('simple_designer_js');
        }
        
        public function register_designer_optimal_styles() {
            wp_register_style('udraw_designer_ui_css' , plugins_url('designer/bootstrap-optimal/optimal_designer_css.css', __FILE__));
            if (file_exists(plugin_dir_path(__FILE__) . 'designer/bootstrap-optimal/raw js/optimal_designer_js.js')) {
                wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-optimal/raw js/optimal_designer_js.js', __FILE__));
            } else {
                wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-optimal/optimal_designer_js.js', __FILE__));
            }
            wp_enqueue_style('udraw_designer_ui_css');
            wp_enqueue_script('udraw_designer_ui_js');
        }
        
        public function register_designer_sleek_styles() {
            if ($this->isMobileDev()) {
                wp_register_style('udraw_designer_ui_css' , plugins_url('designer/bootstrap-sleek/sleek-mobile/sleek_stylesheet.css', __FILE__));
                if (file_exists(plugin_dir_path(__FILE__) . 'designer/bootstrap-sleek/sleek-mobile/raw js/sleek_designer.js')) {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-sleek/sleek-mobile/raw js/sleek_designer.js', __FILE__));
                } else {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-sleek/sleek-mobile/sleek_designer.js', __FILE__));
                }
            } else {
                wp_register_style('udraw_designer_ui_css' , plugins_url('designer/bootstrap-sleek/sleek-desktop/sleek_stylesheet.css', __FILE__));
                if (file_exists(plugin_dir_path(__FILE__) . 'designer/bootstrap-sleek/sleek-desktop/raw js/sleek_designer.js')) {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-sleek/sleek-desktop/raw js/sleek_designer.js', __FILE__));
                } else {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-sleek/sleek-desktop/sleek_designer.js', __FILE__));
                }
            }
            wp_enqueue_style('udraw_designer_ui_css');
            wp_enqueue_script('udraw_designer_ui_js');
        }

        public function register_designer_slim_styles() {
            if ($this->isMobileDev()) {
                wp_register_style('udraw_designer_ui_css' , plugins_url('designer/bootstrap-slim/slim-mobile/stylesheet.css', __FILE__));
                if (file_exists(plugin_dir_path(__FILE__) . 'designer/bootstrap-slim/slim-mobile/raw js/designer.js')) {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-slim/slim-mobile/raw js/designer.js', __FILE__));
                } else {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-slim/slim-mobile/designer.js', __FILE__));
                }
            } else {
                wp_register_style('udraw_designer_ui_css' , plugins_url('designer/bootstrap-slim/slim-desktop/stylesheet.css', __FILE__));
                if (file_exists(plugin_dir_path(__FILE__) . 'designer/bootstrap-slim/slim-desktop/raw js/designer.js')) {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-slim/slim-desktop/raw js/designer.js', __FILE__));
                } else {
                    wp_register_script('udraw_designer_ui_js', plugins_url('designer/bootstrap-slim/slim-desktop/designer.js', __FILE__));
                }
            }
            wp_enqueue_style('udraw_designer_ui_css');
            wp_enqueue_script('udraw_designer_ui_js');
        }
        
        public function registerChosenJS()
        {
            wp_register_style('udraw_chosen_css', UDRAW_CHOSEN_CSS);
            wp_register_script('udraw_chosen_js', UDRAW_CHOSEN_JS);
            
            wp_enqueue_style('udraw_chosen_css');
            wp_enqueue_script('udraw_chosen_js');
        }
        
        public function registerSelect2JS()
        {
            wp_register_style('udraw_select2_css', UDRAW_SELECT2_CSS);
            wp_register_script('udraw_select2_js', UDRAW_SELECT2_JS);
            
            wp_enqueue_style('udraw_select2_css');
            wp_enqueue_script('udraw_select2_js');
        }
        
        public function registerFontAwesome()
        {
            wp_register_style('udraw_fontawesome_css', UDRAW_FONTAWESOME_CSS);
            wp_add_inline_script('udraw_fontawesome_css', 'FontAwesomeConfig = { searchPseudoElements: true }', 'before');
            wp_enqueue_style('udraw_fontawesome_css');
        }
        
        public function registerAceJS()
        {
            wp_register_script('udraw_ace_js', UDRAW_ACE_JS);
            wp_register_script('udraw_ace_mode_javascript_js', UDRAW_ACE_MODE_JAVASCRIPT_JS);
            wp_register_script('udraw_ace_mode_css_js', UDRAW_ACE_MODE_CSS_JS);
            wp_register_script('udraw_ace_theme_chrome_js', UDRAW_ACE_THEME_CHROME_JS);
            wp_register_script('udraw_ace_worker_javascript_js', UDRAW_ACE_WORKER_JAVASCRIPT_JS);
            wp_register_script('udraw_ace_worker_css_js', UDRAW_ACE_WORKER_CSS_JS);

            wp_enqueue_script('udraw_ace_js');
            wp_enqueue_script('udraw_ace_mode_javascript_js');
            wp_enqueue_script('udraw_ace_mode_css_js');
            wp_enqueue_script('udraw_ace_theme_chrome_js');
            wp_enqueue_script('udraw_ace_worker_javascript_js');
            wp_enqueue_script('udraw_ace_worker_css_js');
        }

        public function registerImageCropper()
        {
            wp_register_script('udraw_image_cropper_js', UDRAW_IMAGE_CROPPER_JS);
            wp_register_style('udraw_image_cropper_css', UDRAW_IMAGE_CROPPER_CSS);

            wp_enqueue_script('udraw_image_cropper_js');
            wp_enqueue_style('udraw_image_cropper_css');
        }
        
        public function registerPanzoomJS()
        {
            wp_register_script('udraw_panzoom_js', UDRAW_PANZOOM_JS);

            wp_enqueue_script('udraw_panzoom_js');
        }

        public function registerjQueryFileUpload() {            
            wp_register_style('udraw_custom_fonts_css', admin_url( 'admin-ajax.php' ) . '?action=udraw_designer_local_fonts_css&localFontPath='. wp_make_link_relative(UDRAW_FONTS_URL));
            wp_register_style('udraw_fileuploader_css', plugins_url('assets/jquery-fileupload/jquery.fileupload.css', __FILE__));
            
            wp_register_script('udraw_fileuploader_js', plugins_url('assets/jquery-fileupload/jquery.fileupload.js', __FILE__));
            wp_register_script('udraw_iframe-transport_js', plugins_url('assets/jquery-fileupload/jquery.iframe-transport.js', __FILE__));
            wp_register_script('udraw_xdr-transport_js', plugins_url('assets/jquery-fileupload/jquery.xdr-transport.js', __FILE__));   
            
            $this->register_jquery_css();
            wp_enqueue_style('udraw_custom_fonts_css');
            wp_enqueue_style('udraw_fileuploader_css');
            
            $this->register_jquery_ui();
            wp_enqueue_script('udraw_iframe-transport_js');
            wp_enqueue_script('udraw_xdr-transport_js');
            wp_enqueue_script('udraw_fileuploader_js');
        }
        
        public function registerXMPieColourPicker() {
            wp_register_style('udraw_xmpie_colour_picker_css', plugins_url('assets/ColorPicker/jquery.colorpicker.css', __FILE__));
            wp_register_script('udraw_xmpie_colour_picker_js', plugins_url('assets/ColorPicker/jquery.colorpicker.js', __FILE__));
            
            wp_enqueue_style('udraw_xmpie_colour_picker_css');
            wp_enqueue_script('udraw_xmpie_colour_picker_js');
            
            //Also include wp color picker
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
        }
        
        public function registerJQueryTagsInput() 
        {
            wp_register_style('udraw_tags_input_css', UDRAW_TAGS_INPUT_CSS);
            wp_register_script('udraw_tags_input_js', UDRAW_TAGS_INPUT_JS);
            
            wp_enqueue_style('udraw_tags_input_css');
            wp_enqueue_script('udraw_tags_input_js');            
        }
        
        public function registerBootstrapJS() {
            //Bootstrap 4.0.0+ drop downs requires popper js
            wp_register_script('popper_js', plugins_url('assets/popper.min.js', __FILE__));
            wp_register_script('udraw_bootstrap_js', UDRAW_BOOTSTRAP_JS);
            wp_enqueue_script('popper_js');
            wp_enqueue_script('udraw_bootstrap_js');            
        }
        
        public function registerChecklistUI() {
            wp_register_script('udraw_checklist_js', UDRAW_CHECKLIST_JS);
            wp_enqueue_script('udraw_checklist_js');     
        }

        public function registerPDFBlocksJS() {
            wp_register_script('udraw_pdf_blocks_js', plugins_url('pdf-blocks/includes/js/GoEpower.PDFLib.jquery.min.js', __FILE__));
            wp_register_script('udraw_base64_js', plugins_url('assets/webtoolkit.base64.js', __FILE__));
            
            wp_enqueue_script('udraw_pdf_blocks_js');            
            wp_enqueue_script('udraw_base64_js');            
        }
        public function registerPDFXmPieJS() {
            wp_register_script('udraw_pdf_xmpie_js', plugins_url('pdf-xmpie/includes/js/pdf-xmpie.js', __FILE__));
            wp_register_script('udraw_base64_js', plugins_url('assets/webtoolkit.base64.js', __FILE__));
            
            wp_enqueue_script('udraw_pdf_xmpie_js');            
            wp_enqueue_script('udraw_base64_js');
            //Also enqueue wp-color-picker, but do so manually
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script(
                'iris',
                admin_url( 'js/iris.min.js' ),
                array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
                false,
                1
            );
            wp_enqueue_script(
                'wp-color-picker',
                admin_url( 'js/color-picker.min.js' ),
                array( 'iris' ),
                false,
                1
            );
            $colorpicker_l10n = array(
                'clear' => __( 'Clear' ),
                'defaultString' => __( 'Default' ),
                'pick' => __( 'Select Color' ),
                'current' => __( 'Current Color' ),
            );
            wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
        }
        public function register_jquery_ui () {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-selectable' );
            wp_enqueue_script( 'jquery-ui-widget' );
            wp_enqueue_script( 'jquery-ui-accordion' );
            wp_enqueue_script( 'jquery-ui-autocomplete' );
            wp_enqueue_script( 'jquery-ui-button' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_script( 'jquery-ui-menu' );
            wp_enqueue_script( 'jquery-ui-mouse' );
            wp_enqueue_script( 'jquery-ui-position' );
            wp_enqueue_script( 'jquery-ui-progressbar' );
            wp_enqueue_script( 'jquery-ui-resizable' );
            wp_enqueue_script( 'jquery-ui-selectmenu' );
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'jquery-ui-tooltip' );
            wp_enqueue_script( 'jquery-ui-tabs' );
        }
        
        public function register_jquery_css ()
        {
            wp_register_style('udraw_jquery_smoothness_css', UDRAW_JQUERY_SMOOTHNESS_CSS);
            wp_enqueue_style('udraw_jquery_smoothness_css');    
        }
        
        public function get_udraw_products() {
            $args = array('post_type' => 'product',
                          'posts_per_page' => 9999,
                          'meta_query' => array(
                                array(
                                    'key' => '_udraw_product',
                                    'value' => array( 'true' ),
                                    'compare' => 'IN'
                                )
                           ));    
            
            $products = new WP_Query( $args );
            
            return $products;
        }
        
        public function get_udraw_templates($id=null) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_templates'];
            if (isset($id)) {
                $sql = "SELECT * FROM $table_name WHERE id = '$id'";
            } else {
                $sql = "SELECT * FROM $table_name ORDER BY modify_date, create_date DESC";
            }
            
            $results = $wpdb->get_results($sql);
            
            return $results;
        }
        
        public function get_udraw_template_tags($id=null) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_templates'];
            $tags = "";
            if (isset($id)) {
                $templateId = $this->get_udraw_template_ids($id);                
                if (count($templateId) > 0) {
                    $tags = $wpdb->get_var("SELECT tags FROM $table_name WHERE id = '". $templateId[0] ."'");
                }
            }
            
            return $tags;            
        }
        
        public function get_templates_categories($id=null){
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $category_table_name = $_udraw_settings['udraw_db_udraw_templates_category'];
            if (isset($id)) {
                $sql = "SELECT * FROM $category_table_name WHERE id = '$id'";
            } else {
                $sql = "SELECT * FROM $category_table_name";
            }
            
            $results = $wpdb->get_results($sql);
            
            return $results;
        }
        
        public function get_udraw_clipart($id=null) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_clipart'];
            if (isset($id)) {
                $sql = "SELECT * FROM $table_name WHERE ID = '$id'";
            } else {
                $sql = "SELECT * FROM $table_name ORDER BY date DESC";
            }
            
            $results = $wpdb->get_results($sql);
            return $results;
        }
        
        function getDomain() {
            $sURL    = site_url(); // WordPress function
            $asParts = parse_url( $sURL ); // PHP function

            if ( ! $asParts )
              wp_die( 'ERROR: Path corrupt for parsing.' ); // replace this with a better error result

            $sScheme = $asParts['scheme'];
            $nPort   = $asParts['port'];
            $sHost   = $asParts['host'];
            $nPort   = 80 == $nPort ? '' : $nPort;
            $nPort   = 'https' == $sScheme AND 443 == $nPort ? '' : $nPort;
            $sPort   = ! empty( $sPort ) ? ":$nPort" : '';
            $sReturn = $sScheme . '://' . $sHost . $sPort;

            return $sReturn;
        }
        
        public function duplicate_udraw_template($id) {
            global $wpdb;
            $uDrawUtil = new uDrawUtil();
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            if (!isset($id)) { return false; }
            
            $templates = $this->get_udraw_templates($id);
            
            // Physical Path
            $_output_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/output/';
            
            // Web Path
            $_output_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/output/';
            
            // Create folders if doesn't exist.
            if (!file_exists($_output_path)) { wp_mkdir_p($_output_path); }
            
            $design_url = $this->getDomain() . $templates[0]->design;
            $preview_url = $this->getDomain() . $templates[0]->preview;
            $pdf_url = $this->getDomain() . $templates[0]->pdf;
            
            // Store files into local variable
            $designDocument = $uDrawUtil->get_web_contents($design_url);
            $previewDocument = $uDrawUtil->get_web_contents($preview_url);
            $pdfDocument = $uDrawUtil->get_web_contents($pdf_url);

            // Create new document names.
            $randomString = $this->generateRandomString();
            $design_file = $_output_path . $randomString . '.xml';
            $preview_file = $_output_path . $randomString . '.jpg';
            $pdf_file = $_output_path . $randomString . '.pdf';

            // Save locally desgin, preview and pdf documents
            file_put_contents($design_file, $designDocument);
            file_put_contents($preview_file, $previewDocument);
            file_put_contents($pdf_file, $pdfDocument);
            
            // Create new record in DB.
            $dt = new DateTime();
            $table_name = $_udraw_settings['udraw_db_udraw_templates'];    
            $wpdb->insert($table_name, array(
                'name' => $templates[0]->name . " Copy",
                'design' => wp_make_link_relative($_output_path_url) . $randomString . '.xml',
                'preview' => wp_make_link_relative($_output_path_url) . $randomString . '.jpg',
                'pdf' => wp_make_link_relative($_output_path_url) . $randomString . '.pdf',
                'create_date' => $dt->format('Y-m-d H:i:s'),
                'create_user' => wp_get_current_user()->user_login,
                'design_width' => $templates[0]->design_width,
                'design_height' => $templates[0]->design_height,
                'design_pages' => $templates[0]->design_pages					
            ));
            
            return true;
        }
        
        public function get_udraw_private_templates($id) {
            $args = array('post_type' => 'product',
                          'posts_per_page' => 9999,
                          'meta_query' => array(
                                array(
                                    'key' => '_udraw_is_private_product',
                                    'value' => array( 'yes' ),
                                    'compare' => 'IN'
                                )
                           ));    
            
            $products = new WP_Query( $args );
            $filteredPosts = array();
            foreach ($products->posts as $post) {
                if (in_array($id, get_post_meta($post->ID, '_udraw_private_users_list', true))) {
                    array_push($filteredPosts, $post);
                }
            }
            $products->posts = $filteredPosts;
            return $products;            
        }
        
        public function get_udraw_template_ids($post_id) {
            $templateIds = get_post_meta($post_id, '_udraw_template_id', true);
            if (gettype($templateIds) != "array") {
                $templates = array();
                if (strlen($templateIds) > 0) {
                    array_push($templates, $templateIds);
                }
                return $templates;
            }
            
            return $templateIds;
        }
        
        public function user_has_private_templates($id) {
            $products = $this->get_udraw_private_templates($id);
            if (count($products->posts) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function get_udraw_customer_designs($id) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_customer_designs'];
            $sql = "SELECT * FROM $table_name WHERE customer_id = '$id' ORDER BY modify_date, create_date DESC";
            $results = $wpdb->get_results($sql);
            return $results;
        }
        
        public function user_has_saved_designs($id) {
            if ( count($this->get_udraw_customer_designs($id)) >= 1) { return true; } else { return false; }
        }        
        
        function replace_all_product_images($template_id, $additional_post_id) {
            // Get all uDraw Products
            $uDrawProducts = $this->get_udraw_products();
            $posts_to_modify = array();
            if ($additional_post_id > 0) {
                array_push($posts_to_modify, $additional_post_id);
            }
            
            // Get all products that contain the template id.
            foreach ($uDrawProducts->posts as $post) {
                $linkedTemplateId = $this->get_udraw_template_ids($post->ID);
                if (count($linkedTemplateId) > 0) { 
                    if ($template_id == $linkedTemplateId[0]) {
                        // Found linked products.
                        array_push($posts_to_modify, $post->ID);
                    }
                }
            }
            

            // Continue if there are any products to update.
            if (count($posts_to_modify) > 0) {
                // Remove product(s) image if any.
                $template = $this->get_udraw_templates($template_id);
                if (count($template) > 0) {
                    $this->remove_product_image($template[0]->preview);
                    // Generate a new attachment id.
                    $attach_id = $this->create_attachment_image($template[0]->preview);
                    
                    // Set product image of all products with template id.
                    foreach ($posts_to_modify as $key => $value) {
                        // set attachment to post.
                        set_post_thumbnail( $value, $attach_id );
                    }
                }
            }
        }
        
        function replace_block_product_image($product_id, $post_id) {
            $uDrawPDFBlocks = new uDrawPDFBlocks();            
            $block_template = $uDrawPDFBlocks->get_product($product_id);
            
            if (!is_null($block_template)) {
                $this->remove_product_image($block_template['ThumbnailLarge']);
                
                // Generate a new attachment id.
                $attach_id = $this->create_attachment_image($block_template['ThumbnailLarge']);
                
                // set attachment to post.
                set_post_thumbnail( $post_id, $attach_id);
            }
        }
        function replace_xmpie_product_image($product_id, $post_id) {
            $uDrawPDFXmPie = new uDrawPdfXMPie();            
            $block_template = $uDrawPDFXmPie->get_product($product_id);
            
            if (!is_null($block_template)) {
                $this->remove_product_image($block_template['ThumbnailLarge']);
                
                // Generate a new attachment id.
                $attach_id = $this->create_attachment_image($block_template['ThumbnailLarge']);
                
                // set attachment to post.
                set_post_thumbnail( $post_id, $attach_id);
            }
        }
                
        function remove_product_image($_previewURL) {
            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();

            // Update the preview URL to add http protocol if not in the original URL.
            if (!$this->startsWith($_previewURL, "http")) {
                $_previewURL = "http://". $_SERVER['SERVER_NAME'] . $_previewURL;
            }        
                
            // Remove previous attachment if exists.
            $_previous_attach_id = $this->get_attachment_from_name("udraw_preview_" . basename ($_previewURL));
            if ($_previous_attach_id > 0) {
                wp_delete_attachment($_previous_attach_id);
            }
        }
        
        function create_attachment_image($_previewURL) {
            if (strlen($_previewURL) > 0) {                 
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Update the preview URL to add http protocol if not in the original URL.
                if (!$this->startsWith($_previewURL, "http")) {
                    
                    $_serverPort = "";
                    if (isset($_SERVER['SERVER_PORT'])) {
                        if (intval($_SERVER['SERVER_PORT']) != 80 || intval($_SERVER['SERVER_PORT'] != 443) ) {
                            $_serverPort = ":" . $_SERVER['SERVER_PORT'];
                        }
                    }
                    $_previewURL = UDRAW_SYSTEM_WEB_PROTOCOL . $_SERVER['SERVER_NAME'] . $_serverPort . $_previewURL;
                }                

                // Remove previous attachment if exists.
                $_previous_attach_id = $this->get_attachment_from_name("udraw_preview_" . basename ($_previewURL));
                if ($_previous_attach_id > 0) {
                    return $_previous_attach_id;
                } else {                
                    // Specify physical path to file.
                    $filename = $wp_upload_dir['path'] . "/udraw_preview_" . basename ( $_previewURL ); 

                    // Download image based on http url.
                    $this->download_udraw_preview($_previewURL, $filename);

                    // The ID of the post this attachment is for.
                    $parent_post_id = (isset($post_id)) ? $post_id : 0;

                    // Check the type of tile. We'll use this as the 'post_mime_type'.
                    $filetype = wp_check_filetype( basename( $filename ), null );

                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                        'post_mime_type' => $filetype['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );        

                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    return $attach_id;
                }
            } else {
                return 0;
            }            
        }
        
        public function get_attachment_from_name($name) {
            $args = array(
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_status' => null,
                'post_parent' => null, // any parent
            ); 
            $attachments = get_posts($args);
            if ($attachments) {
                foreach ($attachments as $post) {
                    if ($post->post_name.".jpg" == strtolower($name)) {
                        return $post->ID;
                    }
                    if ($post->post_name.".png" == strtolower($name)) {
                        return $post->ID;
                    }
                }
            }
            return 0;
        }

        public function is_udraw_valid() 
        {
            if (uDraw::is_udraw_okay()) { return true; }
            
            if (!uDraw::is_udraw_okay() && (count($this->get_udraw_templates()) <= 1) ) { return true; }
            
            return false;
        }
        
        public function download_udraw_preview ($url, $path) {
            $udraw_util = new uDrawUtil();
            $udraw_util->download_file($url, $path);
        }

        public function generate_pdf_from_order($order_id, $build_pdf_only) {
            wp_schedule_single_event(time(), 'process_udraw_order', array( $order_id, $build_pdf_only) );
        }
        
        public static function fixBlocksJSONValues($json_str){
            $json_str = addSlashes(str_replace('\\', '', $json_str));
            /*$parts =  explode('\"Value\":\"', $json_str);
            foreach($parts as $part) {
                $sub_parts = explode('\"},{', $part);
                if (count($sub_parts) > 1) {
                    if (strpos($sub_parts[0], '"') > 0) {
                        $json_str = str_replace($sub_parts[0],strip_tags(html_entity_decode($sub_parts[0])), $json_str);
                    }                    
                }
            }*/
            
            // Create a test to see if we can decode this JSON Data.
            if (is_null(json_decode($json_str))) {
                $json_str = stripcslashes($json_str);
            }            
            return $json_str;
        }
        
        public static function accessHelper($response) {
            if ($response == 'valid') {
                return true;
            }
            
            return false;
        }
        
        public function _is_u_valid() 
        {
            if (uDraw::is_udraw_okay()) { return true; }
            
            if (!uDraw::is_udraw_okay() && (count($this->get_udraw_templates()) <= 1) ) { return true; }
            
            return false;
        }

		public function str_replace_first($from, $to, $subject)
		{
			$from = '/'.preg_quote($from, '/').'/';
			return preg_replace($from, $to, $subject, 1);
		}
        
        public function get_physical_path($relative_path) {
            if (strpos($relative_path, SITE_CDN_DOMAIN) !== false) {
                $relative_path = str_replace((SITE_CDN_DOMAIN . '/wp-content'), '' , $relative_path);
            } else {
                $relative_path = str_replace(($this->get_virtual_path() . '/wp-content'), '' , $relative_path);
            }            
            return WP_CONTENT_DIR . $relative_path;            
        }
        
        public function get_virtual_path() {
            $virtual_path = "";
            if (get_home_url() != wp_make_link_relative(get_home_url())) {
                $virtual_path = wp_make_link_relative(get_home_url());
            }
            return $virtual_path;
        }
        
        public function clean_clipart_directory () {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $clipartDB = $_udraw_settings['udraw_db_udraw_clipart'];
            $clipartCategoryDB = $_udraw_settings['udraw_db_udraw_clipart_category'];
            $directories = glob(UDRAW_CLIPART_DIR . '/*' , GLOB_ONLYDIR);
            //Do some Clipart moving stuff; If subdirectories are found in the clipart folder, run this:
            if (count($directories) > 0) {
                foreach ($directories as $directory) {
                    //Create a new entry in the clipart category table for each folder found
                    $category_name = str_replace(UDRAW_CLIPART_DIR.'/', '', $directory);
                    $category_results = $wpdb->get_results("SELECT * FROM $clipartCategoryDB WHERE category_name='$category_name'");
                    if (count($category_results) > 0) {
                        $category_id = $category_results[0]->ID;
                    } else {
                        $wpdb->insert($clipartCategoryDB, array('category_name' => $category_name, 'parent_id' => 0));
                        $category_id = $wpdb->insert_id;
                    }
                    if ($category_id === '0') {
                        $category_id = '';
                    }
                    $clipart_results = $wpdb->get_results("SELECT * FROM $clipartDB WHERE category='$category_name'");
                    $id_array = array();
                    foreach($clipart_results as $result) {
                        array_push($id_array, $result->ID);
                        //Move the file out of the folder and into clipart_dir
                        if (file_exists(UDRAW_CLIPART_DIR.'/'.$category_name.'/'.$result->image_name)) {
                            rename(UDRAW_CLIPART_DIR.'/'.$category_name.'/'.$result->image_name, UDRAW_CLIPART_DIR.$result->image_name);
                        }
                    }
                    $ids = implode(',', $id_array);
                    $wpdb->query("UPDATE $clipartDB SET category=$category_id WHERE ID IN($ids)");
                    $folder_contents = array_diff(scandir(UDRAW_CLIPART_DIR.'/'.$category_name), array('..', '.'));
                    //Make sure folder is empty before deleting
                    if (count($folder_contents) > 0) {
                        //If for some reason a clipart did not move from the previous lines, try to move them now
                        for ($i = 0; $i < count($folder_contents) - 1; $i++) {
                            rename(UDRAW_CLIPART_DIR.'/'.$category_name.'/'.$folder_contents[$i], UDRAW_CLIPART_DIR.'/'.$folder_contents[$i]);
                        }
                    }
                    //Now delete the folder
                    rmdir(UDRAW_CLIPART_DIR.'/'.$category_name);
                }
            }
        }
        
        public function migrateTemplateTags () {
            global $wpdb;
            // Get all the templates and update the table
            $tags_table = $wpdb->prefix . "udraw_templates_tags";
            $template_table_name = $wpdb->prefix . "udraw_templates";$templates = $wpdb->get_results("SELECT id, tags FROM $template_table_name where length(tags) > 0", ARRAY_A);
            for ($i = 0; $i < count($templates); $i++) {
                $template_id = $templates[$i]['id'];
                $_tags = explode(',', $templates[$i]['tags']);
                for ($j = 0; $j < count($_tags); $j++) {
                    $row = $wpdb->get_row("SELECT * FROM $tags_table where name='$_tags[$j]' and template_id=$template_id", ARRAY_A);
                    if ($row === null) {
                        $wpdb->insert($tags_table,
                                array(
                                    'template_id' => $template_id,
                                    'name' => $_tags[$j]
                                ),
                                array('%d','%s')
                        );
                    }
                }
            }
        }
        
        public function check_folder_permissions () {
            $folder_array = [
                array(
                    'directory' => UDRAW_STORAGE_DIR,
                    'url' => UDRAW_STORAGE_URL
                ),
                array(
                    'directory' => UDRAW_DESIGN_STORAGE_DIR,
                    'url' => UDRAW_DESIGN_STORAGE_URL
                ),
                array(
                    'directory' => UDRAW_CLIPART_DIR,
                    'url' => UDRAW_CLIPART_URL
                ),
                array(
                    'directory' => UDRAW_FONTS_DIR,
                    'url' => UDRAW_FONTS_URL
                ),
                array(
                    'directory' => UDRAW_ORDERS_DIR,
                    'url' => UDRAW_ORDERS_URL
                ),
                array(
                    'directory' => UDRAW_TEMP_UPLOAD_DIR,
                    'url' => UDRAW_TEMP_UPLOAD_URL
                )
            ];
            
            for ($i = 0; $i < count($folder_array); $i++) {
                if (!file_exists($folder_array[$i]['directory'])) {
                    wp_mkdir_p($folder_array[$i]['directory']);
                }
                
                if (!is_writable($folder_array[$i]['directory'])) {
                    $this->display_unwritable_alert($folder_array[$i]['url']);
                }
            }
        }
        
        public function admin_order_item_thumbnail($image, $order_item_id, $order_item) {
            $udraw_data = wc_get_order_item_meta($order_item_id, 'udraw_data', true);
            if (isset($udraw_data) && $this->is_udraw_okay()) {
                if (isset($udraw_data['udraw_product_preview'])) {
                    $preview_file = $udraw_data['udraw_product_preview'];
                    $thumbnail = '<img height="150" src="'. $preview_file .'" class="attachment-thumbnail size-thumbnail wp-post-image" alt="" title="">';
                    return $thumbnail;
                }
            }
            return $image;
        }
        
        public function display_unwritable_alert($folder_name = '') {
            ?>
            <div class="notice notice-error" style="background-color: #ffe8e8; padding: 10px;">
                <span><?php echo wp_make_link_relative($folder_name) ?><?php _e(' does not have sufficient permissions.', 'udraw'); ?></span>
            </div>
            <?php
        }
        
        public function udraw_add_product_error_redirect ($redirect_url, $product_id, $error_object) {
            if ($error_object['error']) {
                if ($error_object['type'] === 'xmpie' || $error_object['type'] === 'pdf_block') {
                    $redirect_url .= '?display_error=1';
                    wp_redirect( $redirect_url );
                    exit;
                }
            }
        }

        public function strposa($haystack, $needle, $offset=0) {
            if(!is_array($needle)) $needle = array($needle);
            foreach($needle as $query) {
                if(strpos($haystack, $query, $offset) !== false) return true;
            }
            return false;
        }

        public function udraw_cleanup_old_production_files() {
            //Should delete file on 1st and 15th of every month.
            error_log(print_r('Deleting old production files from FTP funtion triggered', true));
            $todaysDate = date('d');
			error_log(print_r('Todays Date: ' . $todaysDate, true));
			//if ($todaysDate == '01' || $todaysDate == '1' || $todaysDate == '15') {
				error_log(print_r('Deleting old production files from FTP', true));
				$uDrawSettings = new uDrawSettings();
				$settings = $uDrawSettings->get_settings();
				WP_Filesystem();
				global $wp_filesystem;
				$udraw_dir_size = 0; $udraw_dir_size_after_cleanup = 0;

				$args = array(
					'role'    => 'Administrator',
					'orderby' => 'user_login',
					'order'   => 'ASC'
				);
				$users = get_users( $args ); $user_logins = array();
				foreach ( $users as $user ) {
					array_push($user_logins, $user->user_login);
				}

				if (isset($settings['udraw_production_file_cleanup'])) {
					if ($settings['udraw_production_file_cleanup']) {
						$expiry_days = $settings['udraw_production_files_to_keep'];
						if ($expiry_days == '90days') {
							$expiry_time = 90;
						} else if ($expiry_days == '60days') {
							$expiry_time = 60;
						} else if ($expiry_days == '30days') {
							$expiry_time = 30;
						} else if ($expiry_days == 'custom') {
							if (isset($settings['udraw_custom_duration_days']) && strlen($settings['udraw_custom_duration_days'] > 0)) {
								$expiry_time = $settings['udraw_custom_duration_days'];
							} else {
                                $expiry_time = 90;
                            }
						}
						error_log(print_r('Expiry Time ' . $expiry_time, true));
						$converted_expiry_time = 60 * 60 * 24 * $expiry_time; //in seconds

						$storage_dir_folders = list_files(UDRAW_STORAGE_DIR, 1 );
						$orders_dir_folders = list_files(UDRAW_ORDERS_DIR, 1 );
						$uploads_dir_folders = list_files(UDRAW_TEMP_UPLOAD_DIR, 1 );

						//Cleaning up Orders Folder.
						$ordersCount = count($orders_dir_folders);
						for ($x = 0; $x < $ordersCount; $x++ ) {
							if(is_dir($orders_dir_folders[$x])) {
								//Is a folder
								$folder = $orders_dir_folders[$x];
								$last_modded = filemtime($folder);
								$now = time();
								$expired = ($now - $last_modded >= $converted_expiry_time) ? true : false;

								if( $expired ) { $wp_filesystem->delete($folder, true); }

							} else {
								//It is a file
								$file_path = $orders_dir_folders[$x];
								$last_modded = filemtime($file_path);
								$now = time();
								$expired = ($now - $last_modded >= $converted_expiry_time) ? true : false;

								if( $expired ) { wp_delete_file( $file_path ); }
							}
						}

						//Cleaning up Uploads Folder.
						$uploadCount = count($uploads_dir_folders);
						for ($y = 0; $y < $uploadCount; $y++ ) {
							if(is_dir($uploads_dir_folders[$y])) {
								//Is a folder
								$folder = $uploads_dir_folders[$y];
								$last_modded = filemtime($folder);
								$now = time();
								$expired = ($now - $last_modded >= $converted_expiry_time) ? true : false;

								if( $expired ) { $wp_filesystem->delete($folder, true); }

							} else {
								//It is a file
								$file_path = $uploads_dir_folders[$y];
								$last_modded = filemtime($file_path);
								$now = time();
								$expired = ($now - $last_modded >= $converted_expiry_time) ? true : false;

								if( $expired ) { wp_delete_file( $file_path ); }

							}
						}

						//Cleaning up Storage Folder.
						$storageCount = count($storage_dir_folders);
						for ($z = 0; $z < $storageCount; $z++ ) {
							$admin_user_folder = $this->strposa($storage_dir_folders[$z], $user_logins);
							if (strrpos($storage_dir_folders[$z], '_templates_') === false && strrpos($storage_dir_folders[$z], '_designs_') === false && $admin_user_folder === false) {
								//Not an admin user and folder hasn't been modified for the set period of time.
								if(is_dir($storage_dir_folders[$z])) {
									$folder = $storage_dir_folders[$z];
									$last_modded = filemtime($folder);
									$now = time();
									$expired = ($now - $last_modded >= $converted_expiry_time) ? true : false;
		
									if( $expired ) { error_log(print_r($folder, true)); $wp_filesystem->delete($folder, true); }
		
								}
							}
						}

					}
					error_log(print_r('Cleanup complete', true));
				}
			//}
        }
        
        public function clean_empty_folders () {
            error_log('Cleaning empty folders');
            $folders = list_files(UDRAW_STORAGE_DIR, 1 );
            $to_remove = array();
            $expire_time = 60 * 60 * 24 * 2; //2 days
            for ($i = 0; $i < count($folders); $i++ ) {
                if (strrpos($folders[$i], '_templates_') === false && strrpos($folders[$i], '_designs_') === false) {
                    $folder = $folders[$i];
                    $_folders = list_files($folder);
                    $last_modded = filemtime($folder);
                    $now = time();
                    $expired = ($now - $last_modded >= $expire_time) ? true : false;
                    $has_files = false;
                    if ($expired) {     
                        for ($j = 0; $j < count($_folders); $j++) {
                            $_file = $_folders[$j];
                            $pathinfo = pathinfo($_file);
                            if (isset($pathinfo['extension'])) {
                                $has_files = true;
                            }
                        }
                        if (!$has_files) {
                            error_log($folder . ' does not have any files');
                            array_push($to_remove, $folder);
                        }
                    }
                }
            }
            
            $count = 0;
            if (count($to_remove) > 0) {
                WP_Filesystem();
                global $wp_filesystem;
                while (count($to_remove) > 0) {
                    $folder = array_pop($to_remove);
                    error_log('Removing ' . $folder);
                    //Delete
                    $wp_filesystem->delete($folder, true);
                    $count++;
                }
            }
            error_log('Removed ' . $count . ' folders.');
        }
        
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        // ------------------------ Static Methods ------------------------------ //
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//

        /**
         * Returns true if product is a uDraw Product.
         * 
         * @param type $product_id
         * @return true | false
         */
        public static function is_udraw_product($product_id) {
            return get_post_meta($product_id, '_udraw_product', true) == 'true';
        }        
       
        public static function get_udraw_customer_design($access_key) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_customer_designs'];
            $sql = "SELECT * FROM $table_name WHERE access_key = '$access_key'";
            $results = $wpdb->get_row($sql, ARRAY_A);
            return $results;            
        }
        
        public static function get_udraw_activation_key() {
            $udraw_access_key = "";
            if (is_multisite()) {
                $udraw_access_key = get_blog_option(get_current_blog_id(), 'udraw_access_key' );
            } else {
                $udraw_access_key = get_option( 'udraw_access_key' );
            }
            return $udraw_access_key;
        }
        
        public static function set_udraw_activation_key($key) {
            if (is_multisite()) {
                update_blog_option(get_current_blog_id(), 'udraw_access_key', $key);
            } else {
                update_option('udraw_access_key', $key);
            }
        }
        
        public static function is_udraw_okay() 
        {
            $udraw_access_key = uDraw::get_udraw_activation_key();
            if (strlen($udraw_access_key) == 0) { $udraw_access_key = "default"; }           
            $udrawSettings = new uDrawSettings();
            $response = $udrawSettings->__checkAccess($udraw_access_key);   
            return uDraw::accessHelper($response);
        }
        
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//
        // ------------------------ Shortcode Methods --------------------------- //
        //------------------------------------------------------------------------//
        //------------------------------------------------------------------------//        
        function shortcode_udraw_list_categories($atts, $content = null) {
            ob_start();
            $this->registerStyles();
            $this->registerDesignerDefaultStyles();
            require('templates/shortcodes/list-product-categories.php');
            return ob_get_clean();
        }
        
        function shortcode_udraw_private_templates( $atts, $content = null ) {
            $GLOBALS['udraw_is_private_list'] = true;            
            ob_start();
            require('templates/shortcodes/list-private-templates.php');
            return ob_get_clean();
            
            unset($GLOBALS['udraw_is_private_list']);
        }
        
        function shortcode_udraw_customer_saved_designs( $atts, $content = null ) {
            $GLOBALS['udraw_is_private_list'] = true;            
            ob_start();             
            require('templates/shortcodes/customer-saved-designs.php');
            return ob_get_clean();            
            unset($GLOBALS['udraw_is_private_list']);
        }
        
    }

}

$passed_sanity_check = true;

// Check PHP version 5.4.0 + 
if (version_compare(phpversion(), '5.4.0', '<')) {
    // php version isn't high enough
    add_action('admin_notices', 'udraw_php_admin_notice');
    $passed_sanity_check = false;
}

// Check to see if WooCommerce is Activated.
if (is_multisite()) {
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if ( !is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
        add_action('admin_notices', 'udraw_woocommerce_admin_notice');
        $passed_sanity_check = false;
    }
} else {
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        add_action('admin_notices', 'udraw_woocommerce_admin_notice');
        $passed_sanity_check = false;
    }
}

if ($passed_sanity_check) {
    $uDraw = new uDraw();
    $uDraw->init_udraw_plugin();
}

function udraw_php_admin_notice() {
?>
<div class="notice notice-error">
    <p style="font-size: 14px;">
        <strong>Important:</strong> uDraw Plugin requires PHP version 5.4 or higher. PHP version currently installed is <strong><?php echo phpversion(); ?></strong> .
    </p>
</div>
<?php
}

//let user know that he needs the woocommerce plugin
function udraw_woocommerce_admin_notice() {
?>
<div class="notice notice-error">
        <h4>
            <strong><?php _e('Important notice:', 'udraw'); ?></strong><br>
            <?php _e('Web To Print Shop : uDraw requires Woocommerce to be installed and activated. Get it ', 'udraw'); ?>
            <a href="<?php echo wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');?>"><?php _e('here', 'udraw') ?></a>
            .
        </h4>
    </div>
<?php
}

function woocommerce_wp_select_multiple( $field ) {
    global $thepostid, $post;

    $thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
    $field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
    $field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['value']         = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? get_post_meta( $thepostid, $field['id'], true ) : array() );

    echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" multiple="multiple">';

    foreach ( $field['options'] as $key => $value ) {

        echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';

    }

    echo '</select> ';

    if ( ! empty( $field['description'] ) ) {

        if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
            echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
        } else {
            echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
        }

    }
    echo '</p>';
}

function FileStartsWith($Haystack, $Needle){
	return strpos($Haystack, $Needle) === 0;
}

//Get Upgrade notices/warnings
function udraw_update_notice ($args, $response) {
    $new_version = $response->new_version;
    $transient_name = 'udraw_upgrade_notice_' . $new_version;
    $upgrade_notice = get_transient( $transient_name );

    if ( false === $upgrade_notice ) {
        $response = wp_safe_remote_get( 'https://plugins.svn.wordpress.org/udraw/trunk/readme.txt' );

        if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
            $upgrade_notice = udraw_get_update_notice( $response['body'], $new_version );
            set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );
        }
    }
    ?>
        <br/>
        <?php _e($upgrade_notice, 'udraw'); ?>
    <?php
}

function udraw_get_update_notice( $content, $new_version ) {
    $version_parts     = explode( '.', $new_version );
    $check_for_notices = array(
        $version_parts[0] . '.0', // Major.
        $version_parts[0] . '.0.0', // Major.
        $version_parts[0] . '.' . $version_parts[1], // Minor.
        $version_parts[0] . '.' . $version_parts[1] . '.' . $version_parts[2], // Patch.
    );
    $notice_regexp     = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( $new_version ) . '\s*=|$)~Uis';
    $upgrade_notice    = '';

    foreach ( $check_for_notices as $check_version ) {
        if ( version_compare( WC_VERSION, $check_version, '>' ) ) {
            continue;
        }

        $matches = null;
        if ( preg_match( $notice_regexp, $content, $matches ) ) {
            $notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

            if ( version_compare( trim( $matches[1] ), $check_version, '=' ) ) {

                foreach ( $notices as $index => $line ) {
                        $upgrade_notice .= preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line );
                }

                $upgrade_notice .= '<br />';
            }
            break;
        }
    }
    return wp_kses_post( $upgrade_notice );
}

$udraw_plugin_name = plugin_basename(__FILE__);
add_action(sprintf('in_plugin_update_message-%s', $udraw_plugin_name), 'udraw_update_notice', 10, 2 );