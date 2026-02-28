<?php

//Define some constants
if (!defined('UDRAW_SVG_URL')) {
    define('UDRAW_SVG_URL', plugins_url('/', __FILE__));
}
if (!defined('UDRAW_SVG_DIR')) {
    define('UDRAW_SVG_DIR', dirname(__FILE__));
}
if (!defined('UDRAW_STORAGE_DIR')) {
    define('UDRAW_STORAGE_DIR', WP_CONTENT_DIR . '/udraw/storage/');
}
if (!defined('UDRAW_STORAGE_URL')) {
    define('UDRAW_STORAGE_URL', content_url() . '/udraw/storage/');
}
if (!defined('UDRAW_SVG_LOCALE_URL')) {
    define('UDRAW_SVG_LOCALE_URL', plugins_url('/SVGDesigner/locales/', __FILE__));
}
if (!defined('UDRAW_SVG_IMAGE_URL')) {
    define('UDRAW_SVG_IMAGE_URL', plugins_url('/SVGDesigner/images/', __FILE__));
}
if (!defined('GOOGLE_PLATFORM_JS')) {
    define('GOOGLE_PLATFORM_JS', 'https://apis.google.com/js/platform.js');
}
if (!defined('GOOGLE_CLIENT_JS')) {
    define('GOOGLE_CLIENT_JS', 'https://apis.google.com/js/client.js');
}

if (!class_exists('uDraw_SVG')) {
    class uDraw_SVG {
        public function __construct() { }
        
        public function init (){
            add_action('init', array(&$this, 'init_session_id'));
            add_action('plugins_loaded', array($this, 'plugins_loaded'));
            add_action('udraw_add_menu_pages', array(&$this, 'add_admin_pages'), 20);
            add_action( 'admin_enqueue_scripts', array(&$this, 'wp_enqueue_color_picker') );
            
            add_action('udraw_SVG_clean_design_files', array(&$this, 'clean_design_files'),10,1);
            if (! wp_next_scheduled ( 'udraw_SVG_clean_design_files' )) {
                wp_schedule_event(time() + 1, 'daily', 'udraw_SVG_clean_design_files');
            }
            
            //Woocommerce product panel
            add_filter('product_type_options', array(&$this, 'add_SVG_product_type'));
            add_filter('woocommerce_product_data_tabs', array(&$this, 'product_panel_tab'), 10, 1);
            add_action('woocommerce_product_data_panels', array(&$this, 'product_panel'));
            add_action('woocommerce_process_product_meta', array(&$this, 'save_custom_fields'), 10, 2);
            //Frontend category page
            add_filter( 'woocommerce_loop_add_to_cart_link', array(&$this, 'add_to_cart_link'), 10, 2 );
            //Front-end Product page
            add_action('woocommerce_before_single_product', array(&$this, 'check_price_matrix_product'));
            add_action('woocommerce_before_add_to_cart_button', array(&$this, 'add_SVG_product_designer_form'));
            add_action('woocommerce_before_single_product', array(&$this, 'add_SVG_product_designer'), 15);
            add_action('woocommerce_after_add_to_cart_button', array(&$this, 'add_design_now_button'));
            //Woocommerce cart item
            add_filter('woocommerce_add_cart_item', array(&$this, 'add_cart_item'), 10);
            add_filter('woocommerce_add_cart_item_data', array(&$this, 'add_cart_item_data'), 10, 2);
            add_filter('woocommerce_cart_item_thumbnail', array(&$this, 'cart_item_thumbnail'), 99, 3);
            add_filter('woocommerce_cart_item_name', array(&$this, 'cart_item_name'), 99, 3);
            add_action('woocommerce_add_to_cart', array(&$this, 'update_cart_item'), 99, 6); //Update cart item
            add_action('woocommerce_after_cart_table', array(&$this, 'after_cart_table')); 
            //add_action('woocommerce_remove_cart_item', array(&$this, 'remove_cart_item'), 10, 1); //When cart item is removed (before)
            //add_action('woocommerce_cart_item_restored', array(&$this, 'restored_cart_item'), 10, 1); //When cart item is restored (after)
            //Woocommerce order item meta
            add_filter('woocommerce_hidden_order_itemmeta', array(&$this, 'hidden_order_itemmeta'), 99, 1);
            add_action('woocommerce_add_order_item_meta', array(&$this, 'add_order_item_meta'), 30, 2); //Don't use 'woocommerce_new_order_item' dispite warning
            //Woocommerce order item thumbnail
            add_filter('udraw_order_item_name', array($this, 'udraw_order_item_name'), 99, 3);
            //Woocommerce frontend order item details (account->orders)
            add_filter('woocommerce_order_item_name', array(&$this, 'account_order_item_name'), 10, 2);
            //Set visibility of product
            add_filter('woocommerce_product_is_visible', array(&$this, 'svg_product_visible'), 10, 2);
            //uDraw before update thirdparty systems
            add_action('udraw_before_update_thirdparty_systems', array(&$this, 'before_update_thirdparty_systems'));
            add_filter('udraw_thirdparty_system_job_type', array(&$this, 'thirdparty_system_job_type'), 10, 2);
            //Add settings tab to uDraw Settings
            add_filter('udraw_add_settings_tab', function($tabs){
                $tabs['svg_designer'] = __('SVG Designer UI');
                return $tabs;
            });
            add_action('udraw_handle_settings_tab', array($this, 'udraw_svg_settings'), 10, 2);
            //Email order
            add_action( 'woocommerce_email_after_order_table', array(&$this, 'email_after_order_table'), 10, 4 );
            
            require_once(dirname(__FILE__). '/classes/tables/svg_templates_table.class.php');
            require_once(dirname(__FILE__). '/classes/tables/svg_global_templates_table.class.php');
            require_once(dirname(__FILE__). '/classes/svg_designer_handler.class.php');
            require_once(dirname(__FILE__). '/classes/svg_templates_handler.class.php');
            require_once(dirname(__FILE__). '/classes/udraw_svg_settings.class.php');
            require_once(dirname(__FILE__). '/classes/admin_order.class.php');
            
            $SVGDesigner_handler = new SVGDesigner_handler();
            $SVGDesigner_handler->init_actions();
            
            $SVG_templates_handler = new SVG_templates_handler();
            $SVG_templates_handler->init_actions();
            
            $SVG_admin_orders = new SVG_admin_orders();
            $SVG_admin_orders->init_actions();
        }
        
        public function init_session_id() {
            if( !headers_sent() && '' == session_id() ) {
                session_start();
            }
        }
        
        public function get_session_id() {
            global $udrawSVG_session_id;
            $session_id = session_id();
            if ($session_id === '') {
                if ($udrawSVG_session_id == '') {
                    $udrawSVG_session_id = uniqid('udrawSVG_');
                }
                $session_id = $udrawSVG_session_id;
            }
            return $session_id;
        }
        
        public function plugins_loaded () {
            global $wpdb, $charset_collate;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    name TEXT COLLATE utf8_general_ci NOT NULL,
                    design_path LONGTEXT COLLATE utf8_general_ci NOT NULL,
                    preview TEXT COLLATE utf8_general_ci NOT NULL,
                    design_summary TEXT COLLATE utf8_general_ci NOT NULL,
                    date DATETIME COLLATE utf8_general_ci NOT NULL,
                    create_user_id BIGINT(20) COLLATE utf8_general_ci NOT NULL,
                    access_key VARCHAR(64) COLLATE utf8_general_ci NOT NULL,
                    tags TEXT COLLATE utf8_general_ci NULL,
                    category TEXT COLLATE utf8_general_ci NULL,
                    PRIMARY KEY  (ID)";
            $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_svg_templates ($sql) $charset_collate;";
            dbDelta($sql);
        }
        
        public function add_admin_pages() {
            add_submenu_page('udraw', __('SVG Templates', 'udraw_svg'), __('SVG Templates (beta)', 'udraw_svg'), 'read_udraw_templates', 'udraw_svg', array(&$this, 'main_menu_page'));
            add_submenu_page('udraw', __('- Global Templates', 'udraw_svg'), __('- Global Templates', 'udraw_svg'), 'read_udraw_templates', 'udraw_svg_global_templates', array(&$this, 'global_templates_page'));
        }
        
        public function main_menu_page (){
            $this->register_jquery();
            $this->register_bootstrap();
            $this->register_SVGDesigner();
            wp_register_script('fileuploader_js', UDRAW_PLUGIN_URL . '/assets/jquery-fileupload/jquery.fileupload.js');
            wp_register_script('admin_js', plugins_url('/templates/admin/add_templates.js', __FILE__));
            wp_enqueue_script('admin_js');
            wp_enqueue_script('fileuploader_js');
            require_once(dirname(__FILE__). '/templates/admin/svg_templates_list.php');
        }
        
        public function global_templates_page () {
            $this->register_jquery();
            $this->register_bootstrap();
            require_once(dirname(__FILE__). '/templates/admin/svg_global_templates_list.php');
        }
        
        public function udraw_svg_settings($current_tab, $_udraw_settings){
            if ($current_tab === 'svg_designer') {
                $uDraw = new uDraw();
                $this->register_jquery();
                $uDraw->registerAceJS();
                require_once(dirname(__FILE__). '/templates/admin/udraw_svg_settings.php');
            }
        }
        function wp_enqueue_color_picker( ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker');
        }
        public function add_schedule_weekly($schedules) {
            $schedules['weekly'] = array(
                'interval' => 604800,
                'display' => __('Once Weekly')
            );
            return $schedules;
        }
        public function clear_trash ($folder_dir = '') {
            if (file_exists($folder_dir)) {
                $files = glob($folder_dir);
                foreach($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                error_log('cleared trash');
                wp_clear_scheduled_hook('udraw_SVG_clear_trash', array($folder_dir));
            }
        }
        //Add checkbox for SVG Product
        public function add_SVG_product_type($type) {
            $type['udraw_SVG_product'] = array(
                'id' => '_udraw_SVG_product',
                'label' => __('uDraw SVG Product', 'udraw_svg'),
                'wrapper_class' => 'hide_if_virtual hide_if_grouped hide_if_external',
                'target' => 'udraw_SVG_product_data',
                'description' => __('Select if you want to designate this product as a uDraw SVG product.', 'udraw_svg')
            );

            return $type;
        }
        //Add tab in product panel
        public function product_panel_tab($tabs) {
            $tabs['udraw_SVG'] = array(
                'label' => __('uDraw SVG Product', 'udraw_svg'),
                'target' => 'udraw_SVG_product_data',
                'class' => array('hide_if_virtual', 'hide_if_grouped', 'hide_if_external'),
                'priority' => 99
            );
            return $tabs;
        }
        public function product_panel () {
            $uDraw = new uDraw();
            $uDraw->registerFontAwesome();
            wp_enqueue_media();
            $this->register_jquery();
            require_once(dirname(__FILE__). '/templates/admin/product_panel.php');
        }
        //Save changes made in product panel
        public function save_custom_fields($post_id, $post) {
            $SVG_product = isset($_POST['_udraw_SVG_product']) ? true : false;
            if ($SVG_product) {
                $template_id = (isset($_POST['udraw_SVG_template_id'])) ? $_POST['udraw_SVG_template_id'] : '';
                $price_matrix_isset = false;
                $access_key = '';
                if (isset($_POST['udraw_SVG_price_matrix_set'])) {
                    $price_matrix_isset = $_POST['udraw_SVG_price_matrix_set'];
                    if (isset($_POST['udraw_SVG_price_matrix_access_key'])) {
                        $access_key = $_POST['udraw_SVG_price_matrix_access_key'];
                        if (strlen($_POST['_regular_price']) === 0) {
                            $_POST['_regular_price'] = '0.00';
                        }
                    }
                }
                
                $is_private_product = false;
                $selected_customers = '';
                if (isset($_POST['udraw_SVG_private_product'])) {
                    $is_private_product = $_POST['udraw_SVG_private_product'];
                    if (isset($_POST['udraw_SVG_private_users_list'])) {
                        $selected_customers = $_POST['udraw_SVG_private_users_list'];
                    }
                }
                
                $selected_background_colour = '';
                if (isset($_POST['udraw_SVG_selected_background_colour'])) {
                    $selected_background_colour = $_POST['udraw_SVG_selected_background_colour'];
                }
                $isset_background_image = false;
                $selected_background_image = '';
                if (isset($_POST['udraw_SVG_use_background_image'])) {
                    $isset_background_image = $_POST['udraw_SVG_use_background_image'];
                    if (isset($_POST['udraw_SVG_selected_background_image'])) {
                        $selected_background_image = $_POST['udraw_SVG_selected_background_image'];
                    }
                }
                
                $selected_editing_tips_colour = '#000';
                if (isset($_POST['udraw_SVG_editing_tips_colour'])) {
                    $selected_editing_tips_colour = $_POST['udraw_SVG_editing_tips_colour'];
                }
                
                $allow_custom_object = false;
                if (isset($_POST['udraw_SVG_allow_custom_objects'])) {
                    $allow_custom_object = $_POST['udraw_SVG_allow_custom_objects'];
                }
                $allow_background_colour = false;
                if (isset($_POST['udraw_SVG_allow_background_colour'])) {
                    $allow_background_colour = $_POST['udraw_SVG_allow_background_colour'];
                }
                $allow_rotate_template = false;
                if (isset($_POST['udraw_SVG_allow_rotate_template'])) {
                    $allow_rotate_template = $_POST['udraw_SVG_allow_rotate_template'];
                }
                
                $allow_upload_artwork = false;
                $saved_pages = json_encode(array());
                if (isset($_POST['udraw_SVG_allow_upload_artwork'])) {
                    $allow_upload_artwork = $_POST['udraw_SVG_allow_upload_artwork'];
                    $saved_pages = $_POST['udraw_SVG_upload_pages_list_input'];
                }
                
                $allow_download_template = false;
                if (isset($_POST['udraw_SVG_allow_download_template'])) {
                    $allow_download_template = $_POST['udraw_SVG_allow_download_template'];
                }
                
                $allow_upload_artwork_single_document = false;
                if (isset($_POST['udraw_SVG_allow_upload_artwork_single_document'])) {
                    $allow_upload_artwork_single_document = $_POST['udraw_SVG_allow_upload_artwork_single_document'];
                }
                
                update_post_meta($post_id, '_udraw_SVG_template_id', $template_id);
                update_post_meta($post_id, '_udraw_SVG_price_matrix_set', $price_matrix_isset);
                update_post_meta($post_id, '_udraw_SVG_price_matrix_access_key', $access_key);
                update_post_meta($post_id, '_udraw_SVG_private_product', $is_private_product);
                update_post_meta($post_id, '_udraw_SVG_private_users_list', $selected_customers);
                update_post_meta($post_id, '_udraw_SVG_selected_background_colour', $selected_background_colour);
                update_post_meta($post_id, '_udraw_SVG_use_background_image', $isset_background_image);
                update_post_meta($post_id, '_udraw_SVG_selected_background_image', $selected_background_image);
                update_post_meta($post_id, '_udraw_SVG_editing_tips_colour', $selected_editing_tips_colour);
                update_post_meta($post_id, '_udraw_SVG_allow_custom_objects', $allow_custom_object);
                update_post_meta($post_id, '_udraw_SVG_allow_background_colour', $allow_background_colour);
                update_post_meta($post_id, '_udraw_SVG_allow_rotate_template', $allow_rotate_template);
                update_post_meta($post_id, '_udraw_SVG_allow_upload_artwork', $allow_upload_artwork);
                update_post_meta($post_id, '_udraw_SVG_upload_artwork_pages', $saved_pages);
                update_post_meta($post_id, '_udraw_SVG_allow_download_template', $allow_download_template);
                update_post_meta($post_id, '_udraw_SVG_allow_upload_artwork_single_document', $allow_upload_artwork_single_document);
                
                do_action('udraw_svg_admin_save_custom_fields', $post_id);
            }
            update_post_meta($post_id, '_udraw_SVG_product', $SVG_product);
        }
        public function get_udraw_svg_templates () {
            global $wpdb;
            $table_name = $wpdb->prefix . 'udraw_svg_templates';
            $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            for ($i = 0; $i < count($results); $i++) {
                $result = $results[$i];
                $result['design_summary'] = unserialize($result['design_summary']);
                $results[$i] = $result;
            }
            return $results;
        }
        public function get_SVG_products() {
            $args = array('post_type' => 'product',
                            'posts_per_page' => 9999,
                            'meta_query' => array(
                                array(
                                    'key' => '_udraw_SVG_product',
                                    'value' => array( true ),
                                    'compare' => 'IN'
                                )
                            ));    
            
            $products = new WP_Query( $args );
            
            return $products;
        }
        public function check_price_matrix_product() {
            global $post, $woocommerce;
            if (get_post_meta($post->ID, '_udraw_SVG_price_matrix_set', true)) {
                remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
                add_action('woocommerce_simple_add_to_cart', array(&$this, 'add_price_matrix_select_form'), 30);
            }
        }
        public function add_price_matrix_select_form () {
            global $post, $woocommerce;
            if (get_post_meta($post->ID, '_udraw_SVG_price_matrix_set', true)) {
                $uDrawPriceMatrix = new uDrawPriceMatrix();
                $uDrawPriceMatrix->registerScripts();

                $uDraw = new uDraw();
                $uDraw->registerFontAwesome();
                $uDraw->registerChecklistUI();

                include_once(UDRAW_PLUGIN_DIR . '/price-matrix/templates/frontend/__price-matrix-header.php');
                include_once(UDRAW_PLUGIN_DIR . '/price-matrix/templates/frontend/__price-matrix-script.php');
                require_once(UDRAW_SVG_DIR . '/templates/frontend/svg_price_matrix.php');
            }
        }
        public function add_SVG_product_designer_form () {
            global $post;
            if (get_post_meta($post->ID, '_udraw_SVG_product', true)) {
                $template_id = get_post_meta($post->ID, '_udraw_SVG_template_id', true);
                $has_template = strlen($template_id) > 0;
                $allow_upload = get_post_meta($post->ID, '_udraw_SVG_allow_upload_artwork', true);
                $allow_upload_single_doc = get_post_meta($post->ID, '_udraw_SVG_allow_upload_artwork_single_document', true);
                ?>
                <input type="hidden" value="" name="udraw_SVG_product" />
                <input type="hidden" value="" name="udraw_svg_product_cart_item_key" />
                <input type="hidden" value="" name="udraw_SVG_session_id" />
                <?php
                if (strlen($has_template) > 0) {
                    ?>
                    <input type="hidden" value="" name="udraw_SVG_product_data" />
                    <input type="hidden" value="" name="udraw_SVG_product_preview" />
                    <?php
                }
                if ($allow_upload || $allow_upload_single_doc) {
                    ?>
                    <input type="hidden" value="" name="udraw_SVG_uploaded_artwork" />
                    <?php
                }
                do_action('udraw_svg_product_designer_form_add_inputs', $post->ID);
            }
        }
        public function add_SVG_product_designer () {
            global $product, $post;
            $product_type = $product->get_type();
            $product_id = $product->get_id();
            if (get_post_meta($post->ID, '_udraw_SVG_product', true)) {
                $this->register_jquery();
                $this->register_SVGDesigner();
                require_once(dirname(__FILE__). '/templates/frontend/svg_designer.php');
                do_action('udraw_svg_load_extra_js', $post->ID);
            }
        }
        public function add_design_now_button() {
            global $post;
            if (get_post_meta($post->ID, '_udraw_SVG_product', true)) {
                include_once(UDRAW_SVG_DIR . '/templates/frontend/product_page.php');
            }
        }
        public function include_svg_designer ($admin = false, $allow_custom_objects = false, $allow_background_colour = false, $allow_rotate_template = false) {
            global $product;
            $uDraw_SVG_settings = new uDraw_SVG_settings();
            $settings = $uDraw_SVG_settings->get_settings();
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $dt = new DateTime();
            $time_stamp = $dt->getTimestamp();
            
            $_override_skin = false;
            $override_skin = apply_filters('udraw_svg_override_skin', $_override_skin);
            
            $edit_text_tab = $settings['udraw_SVGDesigner_tab_text_editor'];
            $display_tools_top = $settings['udraw_SVGDesigner_display_tools_top'];
            $display_layers = $settings['udraw_SVGDesigner_display_layers'];
            
            if ($override_skin) {
                do_action('udraw_svg_load_designer', $admin, $allow_custom_objects, $settings, $_udraw_settings, $allow_background_colour, $allow_rotate_template);
            } else {
                $skin = $settings['udraw_SVGDesigner_skin'];
                if (!is_dir(UDRAW_SVG_DIR . '/SVGDesigner/skins/'. $skin) || $admin || strlen($skin) === 0) {
                    $skin = 'widescreen';
                }
                wp_register_style('designer_css', plugins_url('/SVGDesigner/skins/'. $skin .'/styles.css?version=' . $time_stamp, __FILE__));
                wp_enqueue_style('designer_css');
                if (file_exists(UDRAW_SVG_DIR . '/SVGDesigner/skins/'. $skin .'/designer.js')) {
                    wp_register_script('designer_js', plugins_url('/SVGDesigner/skins/'. $skin .'/designer.js?version=' . $time_stamp, __FILE__));
                    wp_enqueue_script('designer_js');
                }
                include_once(UDRAW_SVG_DIR . '/SVGDesigner/skins/'. $skin .'/designer.php');
                
                if ($skin === 'widescreen') {
                    if ($settings['udraw_SVGDesigner_load_tutorial']) {
                        wp_register_style('hopscotch_css', plugins_url('/SVGDesigner/css/hopscotch.css', __FILE__));
                        wp_enqueue_style('hopscotch_css');
                        
                        wp_register_script('hopscotch_js', plugins_url('/SVGDesigner/js/hopscotch.js', __FILE__));
                        wp_register_script('tour_js', plugins_url('/SVGDesigner/js/tour.js', __FILE__));
                        wp_enqueue_script('hopscotch_js');
                        wp_enqueue_script('tour_js');
                    }
                }
            }
        }
        
        public function add_to_cart_link ($html, $product) {
            if (get_post_meta($product->get_id(), '_udraw_SVG_product', true)) {
                return '<a href="' . get_permalink( $product->get_id() ) . '" class="button">' . __('Order Now', 'udraw_apparel') . '</a>';
            }
            return $html;
        }
        
        public function add_cart_item($cart_item_meta) {
            global $woocommerce;
            if (isset($cart_item_data['udraw_SVG_data'])) {
                if (isset($cart_item_data['udraw_SVG_data']['udraw_svg_product_cart_item_key'])) {                        
                    if (strlen($cart_item_data['udraw_SVG_data']['udraw_svg_product_cart_item_key']) > 1 ) {
                        // This item is an update item.
                        $orig_cart_item_key = $cart_item_data['udraw_SVG_data']['udraw_svg_product_cart_item_key'];
                        
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
            return $cart_item_meta;
        }
        public function add_cart_item_data ($cart_item_meta, $product_id) {
            if (isset($_POST['udraw_SVG_product']) && $_POST['udraw_SVG_product']) {
                if (!isset($cart_item_meta['udraw_SVG_data'])) {
                    $cart_item_meta['udraw_SVG_data'] = array();
                }
                $design_data = (isset($_POST['udraw_SVG_product_data'])) ? $_POST['udraw_SVG_product_data'] : '';
                $session_id = $_POST['udraw_SVG_session_id'];
                if (isset($design_data) && strlen($design_data) > 0) {
                    $cart_item_meta['udraw_SVG_data']['udraw_SVG_design_data'] = $_POST['udraw_SVG_product_data'];
                    $cart_item_meta['udraw_SVG_data']['udraw_SVG_design_preview'] = $_POST['udraw_SVG_product_preview'];
                    
                    if (substr($design_data, strlen($design_data) - 4) === 'json') {
                        $_url = $design_data;
                        $_replace = wp_make_link_relative(UDRAW_STORAGE_URL);
                        if (strpos($_url, UDRAW_STORAGE_URL) !== false) {
                            $_replace = UDRAW_STORAGE_URL;
                        }
                        $_dir = str_replace($_replace, UDRAW_STORAGE_DIR, $_url);

                        $contents = json_decode(file_get_contents($_dir));
                        $contents->modified = true;
                        file_put_contents($_dir, json_encode($contents));

                        $cart_item_meta['udraw_SVG_data']['udraw_SVG_design_preview'] = wp_make_link_relative($_POST['udraw_SVG_product_preview']);
                    }
                }
                if (isset($_POST['udraw_SVG_uploaded_artwork']) && strlen($_POST['udraw_SVG_uploaded_artwork']) > 0) {
                    $cart_item_meta['udraw_SVG_data']['udraw_SVG_uploaded_artwork'] = json_decode(stripslashes($_POST['udraw_SVG_uploaded_artwork']));
                }
                $cart_item_meta['udraw_SVG_data']['udraw_svg_product_cart_item_key'] = $_POST['udraw_svg_product_cart_item_key'];
                $cart_item_meta['udraw_SVG_data']['session_id'] = $session_id;
                $cart_item_meta = apply_filters('udraw_svg_add_cart_item_data', $cart_item_meta);
            }
            return $cart_item_meta;
        }
        public function cart_item_name ($image, $cart_item, $cart_item_key) {
            global $woocommerce;
            $url_params = array();
            if (count($cart_item['variation']) > 0) {
                if (is_array($cart_item['variation'])) {
                    $url_params = $cart_item['variation'];   
                } else {
                    array_push($url_params, $cart_item['variation']);
                }
            }
            
            array_push($url_params, array('cart_item_key' => $cart_item_key));
            if (isset($cart_item['udraw_SVG_data'])) {
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $_post = get_post( $cart_item['data']->get_id() );
                } else {
                    $_post = $cart_item['data']->post;
                }
                $item_name = sprintf('<a href="%s">%s</a>', esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))), $_post->post_title);
                $SVG_data = $cart_item['udraw_SVG_data'];
                if (isset($SVG_data['udraw_SVG_design_preview'])) {
                    if (is_cart()) { return $item_name; }

                    $preview = $SVG_data['udraw_SVG_design_preview'];
                    $domain_url = $this->getDomain();
                    $thumbnail = '<img src="'. $domain_url . $preview .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw SVG Product" style="width:250px;" />';
                    return $item_name . $thumbnail;
                }
                if (isset($SVG_data['udraw_SVG_uploaded_artwork'])) {
                    $files = $SVG_data['udraw_SVG_uploaded_artwork'];
                    $count = 0;
                    foreach ($files as $page_name => $page_object) {
                        $count++;
                    }
                    
                    $page_name_td = '';
                    if ($count > 1) {
                        $page_name_td = sprintf('<th class="page_name">%s</th>', __('Page Name', 'udraw_svg'));
                    }
                    $uploads_table = sprintf('<table class="udraw_svg_upload_artwork_table"><thead><tr>%s<th>%s</th><th>%s</th></tr><tbody>', $page_name_td, __('Uploaded File', 'udraw_svg'), __('Preview', 'udraw_svg'));
                    foreach ($files as $page_name => $page_object) {
                        $path_parts = pathinfo($page_object->url);
                        $page_name_td = '';
                        if ($count > 1) {
                            $page_name_td = sprintf('<td class="page_name">%s</td>', $page_name);
                        }
                        $img_td = '';
                        if ($path_parts['extension'] !== 'pdf') {
                            $img_td = sprintf('<td><img src="%s" /></td>', $page_object->url);
                        }
                        
                        $uploads_table .= sprintf('<tr>%s<td>%s</td>%s</tr>', $page_name_td, $page_object->original_name, $img_td);
                    }
                    $uploads_table .= '</tbody></table>';
                    
                    return $item_name . '<br />' . $uploads_table;
                }
            }
            return $image;
        }
        public function after_cart_table (){
            ?>
                    <style>
                        table.udraw_svg_upload_artwork_table {
                            font-size: 0.875em;
                        }
                        table.udraw_svg_upload_artwork_table th,
                        table.udraw_svg_upload_artwork_table td {
                            padding-left: 0;
                            padding-bottom: 0;
                            vertical-align: middle;
                        }
                    </style>
            <?php
        }
        public function cart_item_thumbnail ($image, $cart_item, $cart_item_key) {
            $url_params = array();
            if (count($cart_item['variation']) > 0) {
                if (is_array($cart_item['variation'])) {
                    $url_params = $cart_item['variation'];   
                } else {
                    array_push($url_params, $cart_item['variation']);
                }
            }
            
            array_push($url_params, array('cart_item_key' => $cart_item_key));
            if (isset($cart_item['udraw_SVG_data'])) {
                $SVG_data = $cart_item['udraw_SVG_data'];
                if (isset($SVG_data['udraw_SVG_design_preview'])) {
                    $preview = $SVG_data['udraw_SVG_design_preview'];
                    $domain_url = $this->getDomain();
                    $thumbnail = '<a href="' . esc_url(add_query_arg($url_params, get_permalink($cart_item['product_id']))) . '">';
                    $thumbnail .= '<img src="'. $domain_url . $preview .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw SVG Product" style="width:250px;" />';
                    $thumbnail .= '</a>';
                    return $thumbnail;
                }
            }
            return $image;
        }
        public function update_cart_item($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) 
        {
            global $woocommerce;
            if (isset($cart_item_data['udraw_SVG_data'])) {
                if (isset($cart_item_data['udraw_SVG_data']['udraw_svg_product_cart_item_key'])) {                        
                    if (strlen($cart_item_data['udraw_SVG_data']['udraw_svg_product_cart_item_key']) > 1 ) {
                        // This item is an update item.
                        $orig_cart_item_key = $cart_item_data['udraw_SVG_data']['udraw_svg_product_cart_item_key'];
                        
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
        public function clean_design_files (){
            error_log('Cleaning design files');
            $folders = list_files(UDRAW_STORAGE_DIR, 1 );
            $to_remove = array();
            $expire_time = 60 * 60 * 24 * 2; //2 days
            for ($i = 0; $i < count($folders); $i++ ) {
                if (strrpos($folders[$i], '_templates_') === false && strrpos($folders[$i], '_designs_') === false) {
                    $_folders = list_files($folders[$i] . 'output/', 1 );
                    for ($j = 0; $j < count($_folders); $j++) {
                        $has_json = false;
                        $is_modified = false;
                        $is_multipage_doc = false;
                        $last_modded = 0;
                        $expired = false;
                        //Get all files under the output folder
                        $files = list_files($_folders[$j], 1 );
                        for ($f = 0; $f < count($files); $f++) {
                            $file = $files[$f];
                            $_pathinfo = pathinfo($file);
                            if (isset($_pathinfo['extension']) && $_pathinfo['extension'] === 'json') {
                                $last_modded = filemtime($file);
                                $now = time();
                                $expired = ($now - $last_modded >= $expire_time) ? true : false;
                                $has_json = true;
                                $contents = json_decode(file_get_contents($file));
                                if (isset($contents->modified) && $contents->modified) {
                                    $is_modified = true;
                                }
                                if (isset($contents->pages) && count($contents->pages) > 0) {
                                    $is_multipage_doc = true;
                                }
                            }
                        }
                        //At least 2 days old and triple checking the content of the file
                        if ($expired && $has_json && !$is_modified && $is_multipage_doc) { 
                            //push path to array for removal
                            error_log('Added for removal: ' . $_folders[$j]);
                            array_push($to_remove, $_folders[$j]);
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
        public function remove_cart_item ($cart_item_key = '') {
            global $woocommerce;
            // Remove attached JSON file
            $cart_item = $woocommerce->cart->get_cart()[$cart_item_key];
            if (isset($cart_item['udraw_SVG_data'])) {
                $file_path = $cart_item['udraw_SVG_data']['udraw_SVG_design_data'];
                $file_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $file_path);
                $basename = pathinfo($file_dir, PATHINFO_BASENAME);
                $storage_dir = pathinfo($file_dir, PATHINFO_DIRNAME);
                $trash_dir = $storage_dir . '/trash/';
                if (file_exists($file_dir)) {
                    //Move file to temporary trash folder
                    if (!file_exists($trash_dir)) {
                        wp_mkdir_p($trash_dir);
                    }
                    //Clear previously added clear_trash hook and add new one
                    wp_clear_scheduled_hook('udraw_SVG_clear_trash', array($trash_dir));
                    wp_schedule_single_event(time() + 604800, 'udraw_SVG_clear_trash', array($trash_dir)); // 604800 seconds = 1 week
                    rename($file_dir, $trash_dir . $basename);
                }
            }
        }
        public function restored_cart_item($cart_item_key = '') {
            global $woocommerce;
            //Move the JSON file out of trash
            $cart_item = $woocommerce->cart->get_cart()[$cart_item_key];
            if (isset($cart_item['udraw_SVG_data'])) {
                $file_path = $cart_item['udraw_SVG_data']['udraw_SVG_design_data'];
                $file_dir = str_replace(wp_make_link_relative(UDRAW_STORAGE_URL), UDRAW_STORAGE_DIR, $file_path);
                $basename = pathinfo($file_dir, PATHINFO_BASENAME);
                $storage_dir = pathinfo($file_dir, PATHINFO_DIRNAME);
                $trash_dir = $storage_dir . '/trash/';
                $trashed_path = $trash_dir . $basename;
                $restore_path = $storage_dir . '/' . $basename;
                
                rename($trashed_path, $restore_path);
            }
        }
        
        public function hidden_order_itemmeta ($array = array()) {
            $array[] = '_udraw_SVG_PDF';
            return $array;
        }
        public function add_order_item_meta ($item_id, $item) {
            if (isset($item['udraw_SVG_data'])) {
                wc_add_order_item_meta($item_id, 'udraw_SVG_data', $item['udraw_SVG_data']);
            }
        }
        
        public function account_order_item_name ($default , $item) {
            $custom_thumbnail = apply_filters('udraw_svg_order_item_name', $default, $item);
            if ($custom_thumbnail !== $default) {
                return $custom_thumbnail;
            }
            if (isset($item['udraw_SVG_data'])) {
                $domain_url = $this->getDomain();
                $data = $item['udraw_SVG_data'];
                if (isset($data['udraw_SVG_design_preview'])) {
                    $preview = $data['udraw_SVG_design_preview'];
                    return $default . '<br />' . '<img style="max-width:250px;max-height:250px;" src="'. $domain_url . $preview .'" class="attachment-shop_thumbnail wp-post-image" alt="'. $item['name'] .'" />';
                }
                if (isset($data['udraw_SVG_uploaded_artwork'])) {
                    $files = $data['udraw_SVG_uploaded_artwork'];
                    $uploads_table = sprintf('<table><thead><tr><th>%s</th><th>%s</th><th>%s</th></tr><tbody>', __('Page Name', 'udraw_svg'), __('Uploaded File', 'udraw_svg'), __('Preview', 'udraw_svg'));
                    foreach ($files as $page_name => $page_object) {
                        $uploads_table .= sprintf('<tr><td>%s</td><td>%s</td><td><img src="%s" style="max-width: 100px;" /></td></tr>', $page_name, $page_object->original_name, $page_object->url);
                    }
                    $uploads_table .= '</tbody></table>';
                    return $default . '<br />' . $uploads_table;
                }
            } else {
                return $default;
            }
        }
        
        public function udraw_order_item_name ($default, $udrawData, $item) {
            if (isset($item['udraw_SVG_data'])) {
                $domain_url = $this->getDomain();
                if (isset($item['udraw_SVG_data']['udraw_SVG_design_preview'])) {
                    $preview = $item['udraw_SVG_data']['udraw_SVG_design_preview'];
                    return '<img src="' . $domain_url . $preview.'" class="attachment-thumbnail size-thumbnail wp-post-image" style="width: auto; height: auto; vertical-align: -webkit-baseline-middle; max-width:250px;max-height:250px;" />';
                }
            }
            return $default;
        }
        public function svg_product_visible($visible, $id) {
            global $udraw_is_private_list;
            
            if (isset($udraw_is_private_list)) { return $visible; }
            
            if (get_post_meta($id, '_udraw_SVG_private_product', true)) {                
                $visible = false;
            }
            return $visible;
        }
        public function before_update_thirdparty_systems ($order_id = '') {
            $SVG_templates_handler = new SVG_templates_handler();
            $order = new WC_Order($order_id);
            $items = $order->get_items();
            $item_keys = array_keys($items);
            for ($x = 0; $x < count($item_keys); $x++) {
                //$item_keys[$x] = order item id
                $item = $items[$item_keys[$x]];
                if (isset($item['udraw_SVG_data']) && isset($item['udraw_SVG_data']['udraw_SVG_design_data'])) {
                    $SVG_templates_handler->build_pdf($item_keys[$x], $order_id);
                }
            }
        }
        public function thirdparty_system_job_type ($type, $item) {
            if (isset($item['udraw_SVG_data'])) {
                $type = 'udraw_SVG';
            }
            return $type;
        }
        public function get_udraw_SVG_template_access_key ($post_id) {
            global $wpdb;
            $template_id = (metadata_exists('post', $post_id, '_udraw_SVG_template_id')) ? get_post_meta($post_id, '_udraw_SVG_template_id', true) : 0;
            $table_name = $wpdb->prefix . 'udraw_svg_templates';
            if (strlen($template_id) > 0 && $template_id > 0) {
                $template = $wpdb->get_row("SELECT * FROM $table_name WHERE ID=$template_id", ARRAY_A);
                return $template['access_key'];
            } else {
                return false;
            }
        }
        public function get_udraw_SVG_template_count () {
            global $wpdb;
            $table_name = $wpdb->prefix . 'udraw_svg_templates';
            $count = count($wpdb->get_results("SELECT * FROM $table_name"));
            return $count;
        }
        public function email_after_order_table ( $order, $sent_to_admin, $plain_text, $email ) {
            if ($sent_to_admin) {
                $order_id = $order->get_id();
                $order_items = $order->get_items();
                $has_dl_link = false;
                foreach ($order_items as $order_item_id => $data) {
                    if (isset($data['udraw_SVG_data'])) {
                        $pdf_file = 'uDraw-SVG-Order_' . $order_id . '-' . $order_item_id . '.pdf';
                        $pdf_url = UDRAW_ORDERS_URL . $pdf_file;
                        $text = sprintf('%s %s%s<br />', sprintf('<a href="%s">%s</a>', $pdf_url, __('Download the PDF', 'udraw_svg')), __('for order item #', 'udraw_svg'), $order_item_id);
                        echo $text;
                        
                        $has_dl_link = true;
                    }
                }
                if ($has_dl_link) {
                    echo sprintf('%s %s</br>', 
                              __('If download link has no file attached, the PDF file may not have been generated yet. Please try again after some time.', 'udraw_svg'),
                              __('If the problem persists, please log into your site and try to download the file directly, or rebuild the PDf file.', 'udraw_svg'));
                }
            }
        }
        
        public function convert_base64_image ($image_string = '') {
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_string));
            return $image;
        }
        
        public function register_SVGDesigner () {
            $dt = new DateTime();
            $time_stamp = $dt->getTimestamp();
            //Include font awesome and bootstrap
            wp_register_style('udraw_fontawesome_css', UDRAW_FONTAWESOME_CSS);
            wp_register_style('SVGDesigner_css', plugins_url('/SVGDesigner/css/SVGDesigner.min.css', __FILE__));
            wp_register_script('google_client_js', GOOGLE_CLIENT_JS);
            if (file_exists(UDRAW_SVG_DIR . '/SVGDesigner/js/SVGDesigner.js')) {
                wp_register_script('SVGDesigner_js', plugins_url('/SVGDesigner/js/SVGDesigner.js?version=' . $time_stamp, __FILE__));
            } else {
                wp_register_script('SVGDesigner_js', plugins_url('/SVGDesigner/js/SVGDesigner.min.js?version=' . $time_stamp, __FILE__));
            }
            
            wp_enqueue_style('udraw_fontawesome_css');
            wp_enqueue_style('SVGDesigner_css');
            wp_enqueue_script('google_client_js');
            wp_enqueue_script('SVGDesigner_js');
            $this->register_bootstrap();
        }
        
        public function register_bootstrap () {
            wp_register_script('popper_js', UDRAW_PLUGIN_URL . '/assets/popper.min.js');
            wp_register_style('bootstrap_css', UDRAW_PLUGIN_URL . '/assets/bootstrap/css/bootstrap.min.css');
            wp_register_script('bootstrap_js', UDRAW_BOOTSTRAP_JS);
            
            wp_enqueue_script('popper_js');
            wp_enqueue_style('bootstrap_css');
            wp_enqueue_script('bootstrap_js');
        }
        
        public function get_private_templates($id) {
            $args = array('post_type' => 'product',
                          'posts_per_page' => 9999,
                          'meta_query' => array(
                                array(
                                    'key' => '_udraw_SVG_private_product',
                                    'value' => array( true ),
                                    'compare' => 'IN'
                                )
                           ));    
            
            $products = new WP_Query( $args );
            $filteredPosts = array();
            foreach ($products->posts as $post) {
                if (in_array($id, get_post_meta($post->ID, '_udraw_SVG_private_users_list', true))) {
                    array_push($filteredPosts, $post);
                }
            }
            $products->posts = $filteredPosts;
            return $products;            
        }
                
        public function register_jquery () {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
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
            wp_enqueue_script( 'jquery-ui-selectable' );
            wp_enqueue_script( 'jquery-ui-resizable' );
            wp_enqueue_script( 'jquery-ui-selectmenu' );
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-tooltip' );
            wp_enqueue_script( 'jquery-ui-tabs' );
            
            wp_register_style( 'jquery_css' , plugins_url('/SVGDesigner/css/jquery-ui.min.css', __FILE__));
            wp_enqueue_style('jquery_css');
        }
        
        private function get_free_menu_position($start, $increment = 1) {
            foreach ($GLOBALS['menu'] as $key => $menu) {
                $menus_positions[] = $key;
            }
            if (!in_array($start, $menus_positions))
                return $start;
            /* the position is already reserved find the closet one */
            while (in_array($start, $menus_positions)) {
                $start += $increment;
            }
            return $start;
        }
        
        public function getDomain() {
            $sURL    = site_url(); // WordPress function
            $asParts = parse_url( $sURL ); // PHP function

            if ( ! $asParts )
              wp_die( 'ERROR: Path corrupt for parsing.' ); // replace this with a better error result

            $sScheme = $asParts['scheme'];
            //$nPort   = $asParts['port'];
            $nPort   = $asParts['path'];
            $sHost   = $asParts['host'];
            $nPort   = 80 == $nPort ? '' : $nPort;
            $nPort   = 'https' == $sScheme AND 443 == $nPort ? '' : $nPort;
            $sPort   = ! empty( $sPort ) ? ":$nPort" : '';
            $sReturn = $sScheme . '://' . $sHost . $sPort;

            return $sReturn;
        }
    }
}

function udraw_svg_php_version_admin_notice() {
    if (version_compare(phpversion(), '7.2.0', '<')) {
        ?>
            <div class="notice notice-warning" style="padding: 10px;">
                <p style="font-size: 14px;">
                    We recommend using PHP version 7.2.0 or higher for uDraw SVG. Current PHP version is <?php echo phpversion(); ?>.
                    <?php if (!extension_loaded('exif')) { ?>
                        We also recommend enabling the PHP extension 'exif'.
                    <?php } ?>
                </p>
            </div>
        <?php
    }
}
if (version_compare(phpversion(), '7.2.0', '<')) {
    // php version isn't high enough
    add_action('admin_notices', 'udraw_svg_php_version_admin_notice');
}
?>