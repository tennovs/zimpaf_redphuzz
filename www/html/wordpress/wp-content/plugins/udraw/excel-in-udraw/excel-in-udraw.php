<?php
if (!defined('ABSPATH')) {
    exit;
}
//Define some constants
if (!defined('UDRAW_EXCEL_URL')) {
    define('UDRAW_EXCEL_URL', plugins_url('/', __FILE__));
}
if (!defined('UDRAW_EXCEL_DIR')) {
    define('UDRAW_EXCEL_DIR', dirname(__FILE__));
}
if (!class_exists('uDraw_Excel')) {
    class uDraw_Excel {
        public function __construct() { }
        
        public function init (){
            add_action( 'udraw_designer_admin_product_panel', array(&$this, 'udraw_designer_admin_product_panel') );
            add_action( 'udraw_admin_save_custom_fields', array(&$this, 'udraw_admin_save_custom_fields'), 1, 10 );
            add_action( 'udraw_frontend_extra_items', array(&$this, 'udraw_frontend_extra_items'), 10, 1);
            
            add_filter('woocommerce_cart_item_quantity', array(&$this, 'cart_item_quantity'), 10, 3);
            add_filter('woocommerce_widget_cart_item_quantity', array(&$this, 'widget_cart_item_quantity'), 10, 3);
                        
            require_once(UDRAW_EXCEL_DIR . '/classes/excelHandler.class.php');
            $uDrawExcelHandler = new uDrawExcelHandler();
            $uDrawExcelHandler->init_actions();
            
            //Update tables
            global $wpdb, $charset_collate;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            // Jobs table
            $sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                order_id BIGINT(20) NOT NULL,
                item_id BIGINT(20) NOT NULL,
                xmlFiles LONGTEXT COLLATE utf8_general_ci NOT NULL,
                totalCount BIGINT(20) NOT NULL,
                PRIMARY KEY  (ID)";                
            $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_excel_jobs ($sql) $charset_collate;";                
            dbDelta($sql);
        }
        
        
        public function udraw_designer_admin_product_panel () {
            require_once(UDRAW_EXCEL_DIR . '/templates/admin/product_panel_options.php');
        }
        public function udraw_admin_save_custom_fields ($post_id) {
            $uDrawProduct = isset($_POST['_udraw_product']) ? 'true' : 'false';
            if ($uDrawProduct == 'true') {
                $allow_structure_file = (isset($_POST['udraw_allow_structure_file'])) ? $_POST['udraw_allow_structure_file'] : NULL;
                update_post_meta($post_id, '_udraw_allow_structure_file', $allow_structure_file);
                if ($allow_structure_file) {
                    update_post_meta($post_id, '_udraw_display_options_page_first', 'yes');
                }
            }
        }
        public function udraw_frontend_extra_items () {
            global $post;
            $uDraw = new uDraw();
            $designTemplateId = $uDraw->get_udraw_template_ids($post->ID);
            $is_design_product = false;
            if (count($designTemplateId) > 0) { $is_design_product = true; } 
            $allow_structure_file = false;
            if (get_post_meta($post->ID, '_udraw_allow_structure_file', true) == "yes") { $allow_structure_file = true; }
            if ($is_design_product && $allow_structure_file) {
                require_once(UDRAW_EXCEL_DIR . '/templates/frontend/design_generator_modal.php');

                wp_register_style('design_generator_css', plugins_url('/templates/frontend/css/design_generator.css', __FILE__));
                if (file_exists(plugin_dir_path(__FILE__) . 'templates/frontend/raw js/frontend.js')) {
                    wp_register_script('frontend_js', plugins_url('templates/frontend/raw js/frontend.js', __FILE__));
                } else {
                    wp_register_script('frontend_js', plugins_url('templates/frontend/frontend.js', __FILE__));
                }

                wp_enqueue_style('design_generator_css');
                wp_enqueue_script('frontend_js');
            }
        }
        
        public function cart_item_quantity($product_quantity, $cart_item_key, $cart_item ) {
            if (isset($cart_item['udraw_data']['udraw_options_uploaded_excel']) && strlen($cart_item['udraw_data']['udraw_options_uploaded_excel']) > 0) {
                return '<span class="quantity">'. $cart_item['quantity'] .'</span>';
            } else {
                return $product_quantity;
            }
        }
        
        public function widget_cart_item_quantity($default, $cart_item, $cart_item_key ) {
            if (isset($cart_item['udraw_data']['udraw_options_uploaded_excel']) && strlen($cart_item['udraw_data']['udraw_options_uploaded_excel']) > 0) {
                return '<span class="quantity">'. $cart_item['quantity'] .'</span>';
            } else {
                return $default;
            }
        }
    }
}