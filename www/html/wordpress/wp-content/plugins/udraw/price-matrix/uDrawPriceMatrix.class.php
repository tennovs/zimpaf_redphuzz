<?php

if (!class_exists('uDrawPriceMatrix')) {
    class uDrawPriceMatrix {
        function __contsruct() { }
        
        // ------------------------------------------------------------- //
        // -------------------------- Init ----------------------------- //
        // ------------------------------------------------------------- //        
        public function init() {
            global $woocommerce;
            add_action('udraw_add_menu_pages', array(&$this, 'admin_add_menu_pages'), 20);
            add_action('admin_head', array(&$this, 'admin_head') );
            add_action('get_header', array(&$this, 'process_header_request'));
            add_action('plugins_loaded', array($this, 'plugins_loaded'));
            
            add_action('wp_before_admin_bar_render', array(&$this, 'udraw_price_matrix_admin_bar' ));
            
            add_action('woocommerce_before_calculate_totals', array(&$this, 'before_calculate_totals'), 50);
            add_action('woocommerce_after_cart', array(&$this, 'after_cart'), 50);
            add_action('woocommerce_after_cart_contents', array(&$this, 'after_cart'), 50);
            add_action('woocommerce_checkout_update_order_meta', array( &$this, 'checkout_update_order_meta'), 5, 1);
            
            add_filter('woocommerce_get_item_data', array(&$this, 'get_item_data'), 15, 2);
            add_filter('woocommerce_add_cart_item_data', array(&$this, 'add_cart_item_data'), 20, 2);
            add_filter('woocommerce_cart_item_thumbnail', array(&$this, 'cart_item_thumbnail'), 11, 3);
            add_filter('woocommerce_in_cart_product_thumbnail', array(&$this, 'cart_item_thumbnail'), 11, 3);            
            add_filter('woocommerce_cart_item_name', array(&$this, 'cart_item_name'), 11, 3);            
            add_filter('woocommerce_in_cart_product_title', array(&$this, 'cart_item_name'), 51, 3);
            add_filter('woocommerce_order_item_quantity_html', array(&$this, 'order_item_quantity'), 11, 2);            
            add_filter('woocommerce_order_get_items', array(&$this, 'order_get_items'), 11, 2);            
            add_filter('woocommerce_hidden_order_itemmeta', array(&$this, 'woo_udraw_hide_order_itemmeta'), 99, 1);
            add_filter('woocommerce_cart_shipping_packages', array(&$this, 'woocommerce_cart_shipping_packages'), 10, 1 );
            add_filter('woocommerce_order_items_meta_display', array(&$this, 'woocommerce_order_items_meta_display'), 10, 2);
            add_filter('woocommerce_widget_cart_item_quantity', array(&$this, 'woocommerce_widget_cart_item_quantity'), 10, 3);
            add_filter('woocommerce_cart_item_quantity', array(&$this, 'woocommerce_cart_item_quantity'), 10, 3);
            add_action('udraw_admin_order_item_extras', array(&$this, 'admin_order_item_values'), 10,2);
            
            add_shortcode('display_udraw_price_matrix', array(&$this, 'shortcode_display_udraw_price_matrix') );            
            
            add_action( 'wp_ajax_udraw_price_matrix_get', array(&$this,'handle_ajax_get') );
            add_action( 'wp_ajax_udraw_price_matrix_get_all', array(&$this,'handle_ajax_get_all') );
            add_action( 'wp_ajax_udraw_price_matrix_update_categories', array(&$this,'handle_ajax_update_categories') );
            add_action( 'wp_ajax_udraw_price_matrix_update_preview_settings', array(&$this,'handle_ajax_update_preview_settings') );
            add_action( 'wp_ajax_udraw_price_matrix_upload', array(&$this,'handle_ajax_upload') );            
            add_action( 'wp_ajax_udraw_price_matrix_save', array(&$this, 'handle_ajax_save') );
            add_action( 'wp_ajax_udraw_price_matrix_refresh_xml', array(&$this, 'handle_ajax_refresh_xml') );
            
            add_action( 'wp_ajax_nopriv_udraw_price_matrix_get', array(&$this,'handle_ajax_get') );
            add_action( 'wp_ajax_nopriv_udraw_price_matrix_upload', array(&$this,'handle_ajax_upload') );
            
            //WooForce FedEx plugin filter
            add_filter('wf_fedex_packages', array(&$this, 'wooforce_fedex_package'), 10, 2);
            add_filter('woocommerce_shipping_fedex_rate', array(&$this, 'woocommerce_shipping_fedex_rate'), 10, 4);
            add_filter('woocommerce_fedex_parcel_data', array(&$this, 'woocommerce_fedex_parcel_data'), 10, 3);
        }
        
        public function init_db() {
            global $wpdb, $charset_collate;
            
            // uDraw Price Matrix Table                
            $sql = "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    name TEXT COLLATE utf8_general_ci NOT NULL,
                    xml_structure LONGTEXT COLLATE utf8_general_ci NOT NULL,
                    create_date DATETIME COLLATE utf8_general_ci NOT NULL,
                    create_user TEXT COLLATE utf8_general_ci NOT NULL,
                    modify_date DATETIME COLLATE utf8_general_ci NULL,                    
                    access_key VARCHAR(50) COLLATE utf8_general_ci NOT NULL,
                    font_color VARCHAR(20) COLLATE utf8_general_ci NULL,
                    background_color VARCHAR(20) COLLATE utf8_general_ci NULL,                    
                    disable_file_upload BIT COLLATE utf8_general_ci NULL,
                    disable_design_online BIT COLLATE utf8_general_ci NULL,
                    udraw_template_id BIGINT(20) COLLATE utf8_general_ci NULL,
                    measurement_label VARCHAR(20) COLLATE utf8_general_ci NULL,
                    PRIMARY KEY  (id)";                
            $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_price_matrix ($sql) $charset_collate;";
            dbDelta($sql);
            
            // uDraw Price Matrix -> Category Relationship Table
            $sql = "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    category_id BIGINT(20) NOT NULL,
                    access_key VARCHAR(50) COLLATE utf8_general_ci NOT NULL,
                    PRIMARY KEY  (id)";
            $sql = "CREATE TABLE " . $wpdb->prefix . "udraw_price_matrix_in_categories ($sql) $charset_collate;";            
            dbDelta($sql);            
        }
        
        // ------------------------------------------------------------- //
        // -------------------- Actions and Filters -------------------- //
        // ------------------------------------------------------------- //    
        
        public function plugins_loaded() {
            global $woocommerce;
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                add_filter('woocommerce_product_get_weight', array(&$this, 'woocommerce_product_weight'), 10, 2 );
            } else {
                add_filter('woocommerce_product_weight', array(&$this, 'woocommerce_product_weight'), 10, 2 );
            }
        }
        
        public function process_header_request() {
            global $post, $woocommerce;
            $uDrawPriceMatrix = new uDrawPriceMatrix();
            
            // Handle POST and add to cart for Price Matrix Product.
            // This ensures that when adding the item to cart, it is done before any output has been rednered on the page.
            if (isset($_POST["udraw_price_matrix_submit"])) {
                if ($_POST["udraw_price_matrix_submit"] == "true") {
                    $price_matrix_product_id = $uDrawPriceMatrix->get_price_matrix_product_id();
                    $price_matrix_data = array();
                    $price_matrix_data['udraw_data'] = array();
                    $price_matrix_data['udraw_data']['udraw_price_matrix_selected_options_idx'] = $_POST['udraw_price_matrix_selected_options_idx'];
                    $price_matrix_data['udraw_data']['udraw_price_matrix_selected_options'] = $_POST['udraw_price_matrix_selected_options'];
                    $price_matrix_data['udraw_data']['udraw_price_matrix_selected_options_object'] = $_POST['udraw_price_matrix_selected_options_object'];
                    $price_matrix_data['udraw_data']['udraw_price_matrix_projected_pricing'] = $_POST['udraw_price_matrix_projected_pricing'];
                    if (!is_null($_POST['udraw_price_matrix_width'])) {
                        $price_matrix_data['udraw_data']['udraw_price_matrix_width'] = $_POST['udraw_price_matrix_width'];
                        $price_matrix_data['udraw_data']['udraw_price_matrix_height'] = $_POST['udraw_price_matrix_height'];
                    }
                    $price_matrix_data['udraw_data']['udraw_price_matrix_price'] = $_POST['udraw_price_matrix_price'];
                    $price_matrix_data['udraw_data']['udraw_price_matrix_qty'] = $_POST['udraw_price_matrix_qty'];
                    $price_matrix_data['udraw_data']['udraw_price_matrix_records'] = $_POST['udraw_price_matrix_records'];
                    if (isset($_POST['udraw_price_matrix_uploaded_files'])) {
                        $price_matrix_data['udraw_data']['udraw_price_matrix_uploaded_files'] = $_POST['udraw_price_matrix_uploaded_files'];
                    }
                    if (isset($_POST['udraw_price_matrix_design_data'])) {
                        $price_matrix_data['udraw_data']['udraw_price_matrix_design_data'] = $_POST['udraw_price_matrix_design_data'];
                    }
                    if (isset($_POST['udraw_price_matrix_design_preview'])) {
                        $price_matrix_data['udraw_data']['udraw_price_matrix_design_preview'] = $_POST['udraw_price_matrix_design_preview'];
                    }
                    if (isset($_POST['udraw_custom_design_name'])) {
                        $price_matrix_data['udraw_data']['udraw_custom_design_name'] = $_POST['udraw_custom_design_name'];
                    }
                    $price_matrix_data['udraw_data']['udraw_price_matrix_url'] = get_permalink( $post->ID );            
                    $price_matrix_data['udraw_data']['udraw_price_matrix_name'] = $_POST['udraw_price_matrix_name'];

                    if (isset($_POST['cart_item_key'])) {
                        // removes the previous item from the cart.
                        $woocommerce->cart->set_quantity($_POST['cart_item_key'], 0);
                    }
                    // adds the product to cart. If updating, we just removed the previous one.
                    $woocommerce->cart->add_to_cart( $price_matrix_product_id, 1, '','', $price_matrix_data);            
                }
            }           
        }
        
        public function wooforce_fedex_package ($packages) {
            global $woocommerce;
            $cart_array = $woocommerce->cart->get_cart();
            foreach ($cart_array as $key => $value) {
                if (isset($value['udraw_data'])) {
                    if (isset($value['udraw_data']['udraw_price_matrix_shipping_dimensions'])) {
                        $parsed = json_decode(stripcslashes($value['udraw_data']['udraw_price_matrix_shipping_dimensions']));
                        //Starting at index 1 because the values in index 0 is being used for product dimensions already
                        for ($i = 1; $i < count($parsed); $i++) {
                            $packages[] = $this->add_wooforce_package($parsed[$i]->width, $parsed[$i]->length,$parsed[$i]->height, $parsed[$i]->weight, 0);
                        }
                    }
                }
            }
            return $packages;
        }
        
        
        private function add_wooforce_package ($width = 0, $length = 0, $height = 0, $weight = 0, $insured_value = 0) {
            $weight_unit = strtoupper(get_site_option('woocommerce_weight_unit', 'kg'));
            $dimension_unit = strtoupper(get_site_option('woocommerce_dimension_unit', 'cm'));
            $currency = strtoupper(get_site_option('woocommerce_currency', 'USD'));
            if ($weight_unit === 'LBS') {
                $weight_unit = 'LB';
            }
            $package = Array( 
				'GroupNumber' => 1, 
				'GroupPackageCount' => 1,
				'Weight' => Array(
                    'Value' => $weight,
                    'Units' => $weight_unit
				),
				'packed_products' => Array(),
				'Dimensions' => Array(
                    'Length' => $length,
                    'Width' => $width,
                    'Height' => $height,
                    'Units'=> $dimension_unit
				),
				'InsuredValue' => Array(
				'Amount' => $insured_value,
				'Currency' => $currency
				)
            );
            return $package;
        }

        public function woocommerce_shipping_fedex_rate($rates, $currency, $details, $instance) {
            return $rates;
        }

        public function woocommerce_fedex_parcel_data($parcel, $cart_item_key, $current_qty) {
            global $woocommerce;
            $cart = $woocommerce->cart->get_cart();
            
            if (array_key_exists($cart_item_key, $cart)) {
                $cart_item = $cart[$cart_item_key];
                if (isset($cart_item['udraw_data'])) {
                    if (isset($cart_item['udraw_data']["udraw_price_matrix_shipping_dimensions"])) {
                        if (strlen($cart_item['udraw_data']["udraw_price_matrix_shipping_dimensions"]) > 0) {
                            $shipping_boxes = json_decode(stripslashes($cart_item['udraw_data']["udraw_price_matrix_shipping_dimensions"]));
                            if (count($shipping_boxes) >= $current_qty) {
                                $parcel["length"] = wc_get_dimension( $shipping_boxes[$current_qty]->length, 'in' );
                                $parcel["width"] = wc_get_dimension( $shipping_boxes[$current_qty]->width, 'in' );
                                $parcel["height"] = wc_get_dimension( $shipping_boxes[$current_qty]->height, 'in' );
                                $parcel["weight"] = wc_get_weight ($shipping_boxes[$current_qty]->weight, 'lbs' );
                            }
                        }
                    }            
                }
            }
            return $parcel;
        }
        
        public function woocommerce_cart_shipping_packages($packages) {

            for ($x = 0; $x < count($packages); $x++) {
                $keys = array_keys($packages[$x]['contents']);
                for ($i = 0; $i < count($keys); $i++) {
                    if (isset($packages[$x]['contents'][$keys[$i]]['udraw_data'])) {
                        $udraw_data = $packages[$x]['contents'][$keys[$i]]['udraw_data'];
                        if (isset($udraw_data["udraw_price_matrix_weight"])) {
                            if (strlen($udraw_data["udraw_price_matrix_weight"]) > 0) {
                                $packages[$x]['contents'][$keys[$i]]['data']->weight = $udraw_data["udraw_price_matrix_weight"];
                                $packages[$x]['contents'][$keys[$i]]['data']->length = $udraw_data["udraw_price_matrix_length"];
                            }
                        }
                        if (isset($udraw_data["udraw_price_matrix_width"])) {
                            if (strlen($udraw_data["udraw_price_matrix_width"]) > 0) {
                                $packages[$x]['contents'][$keys[$i]]['data']->width = $udraw_data["udraw_price_matrix_width"];
                                $packages[$x]['contents'][$keys[$i]]['data']->height = $udraw_data["udraw_price_matrix_height"];
                            }
                        }
                        if (isset($udraw_data["udraw_price_matrix_shipping_dimensions"])) {
                            if (strlen($udraw_data["udraw_price_matrix_shipping_dimensions"]) > 0) {
                                $parsed = json_decode(stripslashes($udraw_data["udraw_price_matrix_shipping_dimensions"]));
                                $cart_item_data = $packages[$x]['contents'];                                
                                if (count($parsed) == 1) {
                                    $packages[$x]['contents'][$keys[$i]]['data']->weight = $parsed[0]->weight;
                                    $packages[$x]['contents'][$keys[$i]]['data']->length = $parsed[0]->length;
                                    $packages[$x]['contents'][$keys[$i]]['data']->width = $parsed[0]->width;
                                    $packages[$x]['contents'][$keys[$i]]['data']->height = $parsed[0]->height;
                                } else if (count($parsed) > 1) {
                                    // Set Qty to trigger mutliple parcels.
                                    $packages[$x]['contents'][$keys[$i]]['quantity'] = $packages[$x]['contents'][$keys[$i]]['quantity'] + (count($parsed) - 1);
                                    //$packages = array();
                                    //for( $j = 0; $j < count($parsed); $j++ ){
                                    //    $packages[$j] = $this->add_additional_package($cart_item_data, $keys[0], $parsed[$j]->width, $parsed[$j]->length,$parsed[$j]->height, $parsed[$j]->weight);
                                    //}
                                }                                
                            }
                        }
                    }
                }
            }
            //echo '<pre>' . var_export($packages, true) . '</pre>';
            return $packages;
        }

        private function add_additional_package ($cart_data = [], $key = '', $width = 0, $length = 0, $height = 0, $weight = 0) {
            $cart_data[$key]['data']->width = $width;
            $cart_data[$key]['data']->length = $length;
            $cart_data[$key]['data']->height = $height;
            $cart_data[$key]['data']->weight = $weight;
            $package = array(
                'contents' => $cart_data,
                'contents_cost' => array_sum( wp_list_pluck( $cart_data, 'line_total' ) ),
                'applied_coupons' => WC()->cart->applied_coupons,
                'destination' => array(
                    'country' => WC()->customer->get_shipping_country(),
                    'state' => WC()->customer->get_shipping_state(),
                    'postcode' => WC()->customer->get_shipping_postcode(),
                    'city' => WC()->customer->get_shipping_city(),
                    'address' => WC()->customer->get_shipping_address(),
                    'address_2' => WC()->customer->get_shipping_address_2()
                )
            );
            return $package;
        }
        
        public function woocommerce_product_weight( $this_weight, $instance ) {
            global $woocommerce;
            if ($woocommerce->cart !== null) {
                foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                    if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                        $_post = get_post( $instance->get_id() );
                    } else {
                        $_post = $instance->post;
                    }
                    if ($_post->ID == $values["product_id"]) {
                        if (isset($values["udraw_data"])) {
                            if (isset($values["udraw_data"]["udraw_price_matrix_weight"])) {
                                if (strlen($values["udraw_data"]["udraw_price_matrix_weight"]) > 0) {
                                    return $values["udraw_data"]["udraw_price_matrix_weight"];
                                }
                            }
                            if (isset($values["udraw_data"]["udraw_price_matrix_shipping_dimensions"])) {
                                if (strlen($values["udraw_data"]["udraw_price_matrix_shipping_dimensions"]) > 0) {
                                    $parsed = json_decode(stripslashes($values["udraw_data"]["udraw_price_matrix_shipping_dimensions"]));
                                    if (is_array($parsed)) {
                                        if (count($parsed) > 0) {
                                            $instance->weight = $parsed[0]->weight;
                                            $instance->length = $parsed[0]->length;
                                            $instance->width = $parsed[0]->width;
                                            $instance->height = $parsed[0]->height;
                                            $instance->set_weight($parsed[0]->weight);
                                            $instance->set_width($parsed[0]->width);
                                            $instance->set_height($parsed[0]->height);
                                            $instance->set_length($parsed[0]->length);
                                            return $parsed[0]->weight;        
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $this_weight;
        }
        
        public function admin_head() {            
            // check user permissions
            if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
                return;
            }
            
            // check if WYSIWYG is enabled
            if ( 'true' == get_user_option( 'rich_editing' ) ) {
                add_filter( 'mce_external_plugins', array(&$this, 'add_tinymce_plugin') );
                add_filter( 'mce_buttons', array(&$this, 'register_mce_button') );
            }
        }
           
        // Declare script for new button
        public function add_tinymce_plugin( $plugin_array ) {
            $plugin_array['udraw_price_matrix_shortcode'] = plugins_url('includes/udraw-mce-shortcode.js', __FILE__);
            return $plugin_array;
        }

        // Register new button in the editor
        public function register_mce_button( $buttons ) {
            array_push( $buttons, 'udraw_price_matrix_shortcode' );
            return $buttons;
        }                         
        
        public function admin_add_menu_pages() {
            $uDraw = new uDraw();
            if (uDraw::is_udraw_okay()) {
                add_submenu_page('udraw', __('Price Matrix', 'udraw'), __('Price Matrix', 'udraw'), 'read_udraw_price_matrix', 'udraw_price_matrix', array(&$this, 'uDraw_price_matrix_page'));
                
                // Hidden pages.            
                add_submenu_page(null, __('Manage Price Matrix'), __('Manage Price Matrix'), 'edit_udraw_price_matrix', 'udraw_manage_price_matrix', array(&$this, 'uDraw_price_matrix_manage_page'));
            }
        }
        
        public function uDraw_price_matrix_page() {
            require_once("uDrawPriceMatrixTable.class.php");
            require_once("templates/admin/manage-price-sets.php");
        }
        
        public function uDraw_price_matrix_manage_page() {
            $uDraw = new uDraw();
            $uDraw->register_jquery_ui();
            $uDraw->register_jquery_css();
            $uDraw->registerStyles();
            $uDraw->registerChosenJS();
            $this->registerScripts();
            $uDraw->registerAceJS();
            $uDraw->registerBootstrapJS();
            
            wp_register_style('price_matrix_ui_css', plugins_url('templates/admin/price_matrix_ui.css', __FILE__));            
            
            wp_register_script('price-matrix-js', plugins_url('includes/price-matrix.js', __FILE__));
            wp_register_script('price-matrix-ui-js', plugins_url('includes/price-matrix-ui.js', __FILE__));            
            
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style('price_matrix_ui_css');
            
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('price-matrix-js');            
            wp_enqueue_script('price-matrix-ui-js');

            require_once("templates/admin/create-price-set.php");    
        }     
        
        public function registerScripts() {           
            $this->register_price_matrix_frontend();
            wp_register_style('price_matrix_css', plugins_url('includes/price-matrix.css', __FILE__));
            wp_enqueue_style('price_matrix_css');
        }
        
        public function register_price_matrix_frontend() {
            //Get uDraw license key
            $uDraw = new uDraw();
			//$uDraw->registerAceJS();
            $license_key = $uDraw->get_udraw_activation_key();
            echo '<script>var license_key = "' . $license_key .'";</script>';
            // Register Scripts
            wp_register_script('udraw-base64-js', plugins_url('includes/webtoolkit.base64.js', __FILE__));
            if (file_exists(plugin_dir_path(__FILE__) . 'includes/raw js/price-matrix.js')) {
                wp_register_script('price-matrix-js', plugins_url('includes/raw js/price-matrix.js', __FILE__));
            } else {
                wp_register_script('price-matrix-js', plugins_url('includes/price-matrix.js?l=2', __FILE__));
            }
                        
            $dt = new DateTime();
            $time_stamp = $dt->getTimestamp();
            if (file_exists(plugin_dir_path(__FILE__) . 'includes/raw js/udraw-price-matrix.js')) {
                wp_register_script('udraw-price-matrix-js', plugins_url('includes/raw js/udraw-price-matrix.js', __FILE__));
            } else {
                wp_register_script('udraw-price-matrix-js', plugins_url('includes/udraw-price-matrix.js?l=2', __FILE__));
            }

            // Enqueue Scripts            
            wp_enqueue_script('udraw-base64-js');
            wp_enqueue_script('price-matrix-js');
            wp_enqueue_script('udraw-price-matrix-js');
        }
        
        public function before_calculate_totals($cart) {
            global $woocommerce;            
            foreach ( $cart->cart_contents as $key => $value ) {                
                // Check if uDraw product.
                if (isset($value['udraw_data'])) {
                    if (isset($value['udraw_data']['udraw_price_matrix_price'])) {
                        $_new_price = $value['udraw_data']['udraw_price_matrix_price'];
                        if ($_new_price > 0) {                            
                            // Update cart price based on price matrix price.
                            if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                                $value['data']->set_price($_new_price);
                            } else {
                                $value['data']->price = $_new_price;
                            }                            
                        }
                    }
                }
            }
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                //Added because of shipping issues on some sites (If somehow still cart item has no weights and dimensions)
                if ($cart_item['data']->get_weight() === '' || $cart_item['data']->get_width() === '' || $cart_item['data']->get_height() === '' || $cart_item['data']->get_length() === '') { 
                    if (isset($cart_item["udraw_data"])) {
                        if (isset($cart_item["udraw_data"]["udraw_price_matrix_shipping_dimensions"])) {
                            if (strlen($cart_item["udraw_data"]["udraw_price_matrix_shipping_dimensions"]) > 0) {
                                $parsed = json_decode(stripslashes($cart_item["udraw_data"]["udraw_price_matrix_shipping_dimensions"]));
                                if (is_array($parsed)) {                              
                                    if (count($parsed) > 0) {
                                        $cart_item['data']->set_weight($parsed[0]->weight);
                                        $cart_item['data']->set_width($parsed[0]->width);
                                        $cart_item['data']->set_height($parsed[0]->height);
                                        $cart_item['data']->set_length($parsed[0]->length);
                                    }   
                                }                     
                            }
                        }
                    }
                }                
            }
        }
        
        public function after_cart() {
        }
        
        public function woocommerce_widget_cart_item_quantity($default, $cart_item, $cart_item_key ) {
            global $woocommerce;
            if ($cart_item['udraw_data']['udraw_price_matrix_price'] == null) {
                return $default;
            } else {
                if ($cart_item['udraw_data']['udraw_price_matrix_qty'] == null) {
                    return '<span class="quantity"></span>';
                } else {
                    return '<span class="quantity">'. $cart_item['udraw_data']['udraw_price_matrix_qty'] .'</span>';
                }
                
            }
        }
        
        public function woocommerce_cart_item_quantity($product_quantity, $cart_item_key, $cart_item ) {
            global $woocommerce;
            if ($cart_item['udraw_data']['udraw_price_matrix_price'] == null) {
                return $product_quantity;
            } else {
                if ($cart_item['udraw_data']['udraw_price_matrix_qty'] == null) {
                    return '<span class="quantity"></span>';
                } else {
                    return '<span class="quantity">'. $cart_item['udraw_data']['udraw_price_matrix_qty'] .'</span>';
                }
            }
        }
        
        public function woocommerce_order_items_meta_display($output, $that) {
            //In admin, WC->orders
            if (strlen($output) === 0) {
                $exta_meta_list = array();
                
                if (isset($that->meta['udraw_data'])) {
                    $udraw_data = $that->meta['udraw_data'];
                    $udraw_data = unserialize($that->meta['udraw_data'][0]);

                    // Make sure that the uDraw product contains the design data.
                    if (isset($udraw_data['udraw_price_matrix_selected_options']) && isset($udraw_data['udraw_price_matrix_qty']) ) {                                
                        //Price matrix selected quantity
                        $exta_meta_list[] = wp_kses_post('Quantity: ' . $udraw_data['udraw_price_matrix_qty'] );
                        
                        if (isset($udraw_data['udraw_price_matrix_selected_options_object'])) {
                            $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options_object']));
                            for ($x = 0; $x < count($selected_options); $x++) {
                                if ($selected_options[$x]->name == "_CanvasRatio") { continue; }
                                $exta_meta_list[] = wp_kses_post($selected_options[$x]->name . ': '.  $selected_options[$x]->value);
                            }
                        } else {
                            // will later on be depreciated ( udraw_price_matrix_selected_options )
                            $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options']));
                            foreach ($selected_options as $option => $value) {
                                // Price matrix selected options
                                if ($option == "_CanvasRatio") { continue; }
                                $exta_meta_list[] = wp_kses_post( $option . ': ' . $value[0] );
                            }
                        }
                        
                        $output .= implode( "<br />", $exta_meta_list );
                    }
                }
            }
            
            return $output;
        }
        
        public function get_item_data($other_data, $cart_item) {
            //In cart page
            global $woocommerce;
            // Get uDraw Data            
            $udraw_data = $cart_item['udraw_data'];
            // Make sure that the uDraw product contains the design data.
            if (isset($udraw_data['udraw_price_matrix_selected_options']) && isset($udraw_data['udraw_price_matrix_qty']) ) {                                
                //Price matrix selected quantity
                array_push($other_data, array( 'name' => 'Total Quantity', 'value' => $udraw_data['udraw_price_matrix_qty']));

                if (isset($udraw_data['udraw_price_matrix_selected_options_object'])) {
                    $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options_object']));
                    for ($x = 0; $x < count($selected_options); $x++) {
                        if (isset($selected_options[$x]->name)) {
                            if ($selected_options[$x]->name == "_CanvasRatio") { continue; }
                            array_push($other_data, array('name' => $selected_options[$x]->name,'value' => $selected_options[$x]->value . '<br />'));
                        }
                    }                     
                } else {
                    // will later on be depreciated ( udraw_price_matrix_selected_options ) 
                    $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options']));
                    foreach ($selected_options as $option => $value) {
                        // Price matrix selected options
                        if ($option == "_CanvasRatio") { continue; }
                        $sub_val_str = '';
                        foreach($value as $sub_val) {
                            $sub_val_str .= $sub_val . '</br>';
                        }
                        array_push($other_data, array('name' => $option,'value' => $sub_val_str));
                    }                    
                }
                
                if ($cart_item["product_id"] == $this->get_price_matrix_product_id()) {                    
                    if (strlen($udraw_data['udraw_price_matrix_uploaded_files']) > 0) {
                        $uploaded_files = json_decode(stripcslashes($udraw_data['udraw_price_matrix_uploaded_files']));
                        foreach ($uploaded_files as $upload_file) {
                            /*array_push($other_data, array('name' => "Attached (" . $upload_file->name . ")",
                                                          'value' => "http://". $_SERVER['HTTP_HOST'] . "/" . $upload_file->url));
                            */ 
                            array_push($other_data, array('name' => "Attached:", 'value' => $upload_file->name));
                        }                    
                    }
                    
                    if (strlen($udraw_data['udraw_price_matrix_design_data']) > 0) {
                        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                            if ($values === $cart_item) {
                                $cik = $cart_item_key;
                                array_push($other_data, array('name' => 'Design', 'value' => '<a href="'. $udraw_data['udraw_price_matrix_url'] . '&cart_item_key=' . $cik . '">Update</a>'));
                            }
                        }                        
                    }
                    
                }                
            }
            return $other_data;
        }
        
        public function add_cart_item_data($cart_item_meta, $product_id) {
            if (isset($cart_item_meta['udraw_data'])) {
                if (isset($cart_item_meta['udraw_data']['reorder'])) {
                    return $cart_item_meta; // This is a re-order, no need to touch cart item meta array.
                }
            }
            
            $updateCartMeta = false;
            if (isset($cart_item_meta['udraw_data']['udraw_product_data'])) {
                $updateCartMeta = true;
            } else {
                if (!isset($cart_item_meta['udraw_data'])) {
                    $cart_item_meta['udraw_data'] = array();
                }
                $updateCartMeta = true;
            }
            
            if ($updateCartMeta) {
                $cart_item_meta['udraw_data']['udraw_price_matrix_name'] = (isset($_POST['udraw_price_matrix_name'])) ? $_POST['udraw_price_matrix_name'] : NULL;
                $cart_item_meta['udraw_data']['udraw_custom_design_name'] = (isset($_POST['udraw_custom_design_name'])) ? $_POST['udraw_custom_design_name'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_selected_options_idx'] = (isset($_POST['udraw_price_matrix_selected_options_idx'])) ? $_POST['udraw_price_matrix_selected_options_idx'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_selected_options'] = (isset($_POST['udraw_price_matrix_selected_options'])) ? $_POST['udraw_price_matrix_selected_options'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_selected_options_object'] = (isset($_POST['udraw_price_matrix_selected_options_object'])) ? $_POST['udraw_price_matrix_selected_options_object'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_projected_pricing'] = (isset($_POST['udraw_price_matrix_projected_pricing'])) ? $_POST['udraw_price_matrix_projected_pricing'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_price'] = (isset($_POST['udraw_price_matrix_price'])) ? $_POST['udraw_price_matrix_price'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_qty'] = (isset($_POST['udraw_price_matrix_qty'])) ? $_POST['udraw_price_matrix_qty'] : NULL;
                $cart_item_meta['udraw_data']['udraw_price_matrix_records'] = (isset($_POST['udraw_price_matrix_records'])) ? $_POST['udraw_price_matrix_records'] : NULL;

                if (isset($_POST['udraw_price_matrix_weight'])) {
                    if (!is_null($_POST['udraw_price_matrix_weight'])) {
                        $cart_item_meta['udraw_data']['udraw_price_matrix_weight'] = $_POST['udraw_price_matrix_weight'];
                        $cart_item_meta['udraw_data']['udraw_price_matrix_length'] = $_POST['udraw_price_matrix_length'];
                    }
                }
                if (isset($_POST['udraw_price_matrix_width'])) {
                    if (!is_null($_POST['udraw_price_matrix_width'])) {
                        $cart_item_meta['udraw_data']['udraw_price_matrix_width'] = $_POST['udraw_price_matrix_width'];
                        $cart_item_meta['udraw_data']['udraw_price_matrix_height'] = $_POST['udraw_price_matrix_height'];
                    }
                }
                if (isset($_POST['udraw_price_matrix_shipping_dimensions'])) {
                    if (!is_null($_POST['udraw_price_matrix_shipping_dimensions'])) {
                        $cart_item_meta['udraw_data']['udraw_price_matrix_shipping_dimensions'] = $_POST['udraw_price_matrix_shipping_dimensions'];
                    }
                }
            }
            return $cart_item_meta;
        }
        
        public function admin_order_item_values ( $item, $item_id) {
            global $woocommerce, $post;
            if (isset($item['udraw_data'])) {
                if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                    $product = $item['udraw_data'];
                } else {
                    $product = unserialize($item['udraw_data']);
                }
                if( isset($item['udraw_data']) ) {
                    if (isset($item['udraw_data']['udraw_price_matrix_selected_options_object'])&& strlen($item['udraw_data']['udraw_price_matrix_selected_options_object']) > 0) {
                        add_thickbox();
                        //Add button to view selected price matrix options
                        $unique_price_matrix_id = uniqid('price_matrix');
                        ?>
                        <a class='button button-small button-secondary udraw-price-matrix thickbox' href="#TB_inline?width=600&height=550&inlineId=<?php echo $unique_price_matrix_id; ?>" onclick="javascript:window.tb_show('Price Matrix Options', '#TB_inline?width=600&height=550&inlineId=<?php echo $unique_price_matrix_id; ?>');" style='width: 125px; text-align: center;'>Price Matrix Options</a>
                        <div id="<?php echo $unique_price_matrix_id; ?>" style="display:none;">
                            <div style="width: 100%; height: auto;">
                                <table style="width: 100%; height: auto; border: 1px solid black;">
                                    <tbody>
                                        <tr>
                                            <th style="border: 1px solid grey;"><span>Option Name</span></th>
                                            <th style="border: 1px solid grey;"><span>Option Value</span></th>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid grey;">Selected Quantity</td>
                                            <td style="border: 1px solid grey;"><?php echo $item['udraw_data']['udraw_price_matrix_qty'] ?></td>
                                        </tr>
                                        <?php
											$options = json_decode(stripcslashes($item['udraw_data']['udraw_price_matrix_selected_options_object']));
											for ($x = 0; $x < count($options); $x++) {
												?>
                                                <tr>
                                                    <td style="border: 1px solid grey;"><?php echo $options[$x]->name; ?></td>
                                                    <td style="border: 1px solid grey;"><?php echo $options[$x]->value; ?></td>
                                                </tr>
                                                <?php
											}
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php 
                    }                    
                }
            }
        }
        
        public function checkout_update_order_meta($order_id) {
            //Checkout; Add order meta to item
            global $woocommerce;
            
            $order = new WC_Order($order_id);
            $items = $order->get_items();
            $item_keys = array_keys($items);
            for ($x = 0; $x < count($item_keys); $x++) {
                if (version_compare( $woocommerce->version, '3.0.0', ">=" )) {
                    $udraw_data = $items[$item_keys[$x]]['udraw_data'];
                } else {
                    $udraw_data = unserialize($items[$item_keys[$x]]['udraw_data']);
                }
                if (strlen($udraw_data['udraw_price_matrix_qty'])) {
                    wc_add_order_item_meta($item_keys[$x], "Selected Quantity", $udraw_data['udraw_price_matrix_qty'], false);
                }
                if (isset($udraw_data['udraw_price_matrix_selected_options_object'])) {
                    $selected_options = json_decode(stripcslashes($udraw_data['udraw_price_matrix_selected_options_object']));
                    if (is_null($selected_options)) {
                        $selected_options = json_decode($udraw_data['udraw_price_matrix_selected_options_object']);
                    }
                    for ($y = 0; $y < count($selected_options); $y++) {
                        if (isset($selected_options[$y]->name)) {
                            wc_add_order_item_meta($item_keys[$x], $selected_options[$y]->name, $selected_options[$y]->value, false);
                        }
                    }
                } else {
                    if (strlen($udraw_data['udraw_price_matrix_selected_options'])) {
                        $selected_options = json_decode($udraw_data['udraw_price_matrix_selected_options']);
                        foreach ($selected_options as $option => $value) {
                            $sub_val_str = '';
                            foreach($value as $sub_val) {
                                $sub_val_str .= $sub_val . '</br>';
                            }
                            wc_add_order_item_meta($item_keys[$x], $option, $sub_val_str, false);
                        }                    
                    }
                }
                $uploaded_files = (isset($udraw_data['udraw_price_matrix_uploaded_files'])) ? json_decode(stripcslashes($udraw_data['udraw_price_matrix_uploaded_files'])) : NULL;

                if (strlen($uploaded_files) > 0) {
                    for ($z = 0; $z < count($uploaded_files); $z++) {
                        wc_add_order_item_meta($item_keys[$x], "Attached (" . $uploaded_files[$z]->name . ")" ,
                            '<a href="'. wp_make_link_relative($uploaded_files[$z]->url) .'" download>Download</a>', false);
                    }
                }
            }
        }
        
        public function cart_item_thumbnail($image, $cart_item, $cart_item_key) {
            if ($cart_item["product_id"] == $this->get_price_matrix_product_id()) {
                $_previewURL = UDRAW_PLUGIN_URL . 'assets/includes/attachment_icon.png';
                       
                if (strlen($cart_item['udraw_data']['udraw_price_matrix_design_preview']) > 0) {
                    $_previewURL = $cart_item['udraw_data']['udraw_price_matrix_design_preview'];
                }
                return '<img style="width:250px;" src="'. $_previewURL .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product">';
            } else {
                return $image;
            }
        }
        
        public function cart_item_name($name, $cart_item, $cart_item_key) {
            if ($cart_item["product_id"] == $this->get_price_matrix_product_id()) {
                if (isset($cart_item['udraw_data']['udraw_price_matrix_name'])) {
                    return $cart_item['udraw_data']['udraw_price_matrix_name'];
                } else {
                    return "";
                }                
            } else {
                if (isset($cart_item['udraw_data'])) {
                    if (isset($cart_item['udraw_data']['udraw_price_matrix_selected_options_idx'])) {
                        if (isset($cart_item['data'])) {
                            $_pf = new WC_Product_Factory();
                            $product = $_pf->get_product($cart_item['data']->get_id());
                            $cart_item_name = $name;
                            $site_option = get_site_option('permalink_structure');
                            $l_c = ($site_option === '') ? '&' : '?';
                            $cart_item_name .= '&nbsp;' . sprintf( '<a href="%s" style="color:red;">%s</a>', esc_url( $product->get_permalink( $cart_item ) . $l_c . 'cart_item_key=' . $cart_item_key ), '[Edit]' );
                            
                            return $cart_item_name;
                        }
                    }
                }
            }
            
            return $name;
        }  
        
        public function order_item_name( $default , $item) {
            if ($item["product_id"] == $this->get_price_matrix_product_id()) {
                if (isset($item['udraw_data']['udraw_price_matrix_name'])) {
                    return $item['udraw_data']['udraw_price_matrix_name'];
                } else {
                    return "";
                }                
            } else {
                return $default;
            }            
        }
        
        public function order_item_thumbnail( $default, $item ) {
            global $woocommerce;
            //if ($item["product_id"] == $this->get_price_matrix_product_id()) {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->get_product_id() ), 'single-post-thumbnail' );
            $_previewURL = UDRAW_PLUGIN_URL . 'assets/includes/attachment_icon.png';
                
            if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
                $udraw_data = $item['udraw_data'];
            } else {
                $udraw_data = unserialize($item['udraw_data']);
            }
            if (isset($udraw_data['udraw_price_matrix_design_preview']) && strlen($udraw_data['udraw_price_matrix_design_preview']) > 1) {
                $_previewURL = get_bloginfo('wpurl') . $udraw_data['udraw_price_matrix_design_preview'];
            } else if (isset($udraw_data['udraw_product_preview']) && strlen($udraw_data['udraw_product_preview']) > 1){
                $_previewURL = get_bloginfo('wpurl') . $udraw_data['udraw_product_preview'];
            } else if (isset($udraw_data['udraw_options_uploaded_files']) && strlen($udraw_data['udraw_options_uploaded_files']) > 1){
                try {
                    $uploaded_files = json_decode($udraw_data['udraw_options_uploaded_files'])[0];
                    if (isset($uploaded_files->url) && strlen($uploaded_files->url) > 1) {
                        if (!$this->endsWith(strtolower($uploaded_files->url), ".pdf")){
                            $_previewURL = $uploaded_files->url;
                        } else {
                            $_previewURL = $udraw_data['udraw_options_uploaded_files_preview'];
                        }
                    }
                } catch (Exception $ex) {
                    error_log(print_r($ex));
                }
            } else if (isset($udraw_data['udraw_pdf_block_product_thumbnail']) && strlen($udraw_data['udraw_pdf_block_product_thumbnail']) > 1) {
                $_previewURL = $udraw_data['udraw_pdf_block_product_thumbnail'];
            } else if (isset($udraw_data['udraw_pdf_xmpie_product_thumbnail']) && strlen($udraw_data['udraw_pdf_xmpie_product_thumbnail']) > 1) {
                $_previewURL = $udraw_data['udraw_pdf_xmpie_product_thumbnail'];
            } else {
                $_previewURL = $image[0];
            }
                
            $response = '<div class="preview_thumb" style="vertical-align: top"><img style="width:150px; height:auto;" src="'. $_previewURL .'" class="attachment-shop_thumbnail wp-post-image" alt="uDraw Product">';                
            
            $response .= '<br /><label><strong>' . $item->get_name() . '</strong></label></div>';

            /*if (isset($udraw_data['udraw_price_matrix_name'])) {
                $response .= '<br /><label><strong>' . $udraw_data['udraw_price_matrix_name'] . '</strong></label></div>';
            }*/
            return $response;
//          } else {
//              return $default;
//          }            
        }
        public function endsWith($haystack, $needle) {
                return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
        }
        
        public function order_item_quantity($default , $item) {
            if ($item["product_id"] == $this->get_price_matrix_product_id()) {
                return '';
            } else {
                return $default;
            }
        }
        
        public function order_get_items($items, $order) {
            $order_item_keys = array_keys($items);
            for ($x = 0; $x < count($order_item_keys); $x++) {                
                if ($items[$order_item_keys[$x]]['name'] == "_udraw_price_matrix_product") {                    
                    $udraw_data = unserialize($items[$order_item_keys[$x]]['udraw_data']);
                    if (strlen($udraw_data['udraw_price_matrix_name']) > 2) {                        
                        $items[$order_item_keys[$x]]['name'] = $udraw_data['udraw_price_matrix_name'];
                    } else {
                        $items[$order_item_keys[$x]]['name'] = "Price Matrix Product";
                    }
                }
            }
            return $items;
        }
        
        public function woo_udraw_hide_order_itemmeta($meta = array()) {            
            $meta[] = '_CanvasRatio';        
            return $meta;
        }
        
        public function udraw_price_matrix_admin_bar(){
            global $wp_admin_bar, $post, $product, $wpdb; 
            if (is_single() && !is_admin() && is_product()) {
                $price_matrix_access_key = $this->get_product_price_matrix_key($post->ID);
                if (strlen($price_matrix_access_key)) {
                    if (is_user_logged_in() && current_user_can('edit_udraw_price_matrix')) {
                        $wp_admin_bar->add_node(array(
                            'id'    => 'udraw_edit_price_matrix',
                            'title' => 'Edit uDraw Price Matrix',
                            'href'  => admin_url() . 'admin.php?page=udraw_manage_price_matrix&access_key=' . $price_matrix_access_key,
                            'meta' => array ( 'class' => 'ab-item' )
                        ));
                    }                    
                }
            }
        }
        
        // ----------------------------------------------------------- //
        // -------------------- DB Helper Methods -------------------- //
        // ----------------------------------------------------------- //        
        public function get_price_matrix() {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];  
            $sql = "SELECT * FROM $table_name ORDER BY name";            
            $results = $wpdb->get_results($sql);
            return $results;
        }
        
        public function get_price_matrix_by_key($key) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];        
            $sql = "SELECT * FROM $table_name WHERE access_key = '$key'";            
            $results = $wpdb->get_results($sql);
            return $results;
        }
        
        public function get_price_matrix_by_cat($cat_id) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $price_matrix_cat_table_name = $_udraw_settings['udraw_db_udraw_price_matrix_in_categories'];
            $sql = "SELECT * FROM $price_matrix_cat_table_name WHERE category_id = $cat_id LIMIT 1";
            $cat_results = $wpdb->get_results($sql);
            $results = '';
            if (count($cat_results) > 0) {
                $price_matrix_table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];
                $sql2 = "SELECT * FROM $price_matrix_table_name WHERE access_key = '". $cat_results[0]->access_key ."'";
                $results = $wpdb->get_results($sql2);
            }
            return $results;            
        }
        
        public function get_cat_by_price_matrix($key) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $price_matrix_cat_table_name = $_udraw_settings['udraw_db_udraw_price_matrix_in_categories'];
            $sql = "SELECT * FROM $price_matrix_cat_table_name WHERE access_key = '$key'";
            $cat_results = $wpdb->get_results($sql);            
            return $cat_results;
        }
        
        public function create_price_matrix($name, $xml, $user) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];
            $access_key = uniqid('udraw_');
            $dt = new DateTime();
            
            // Insert Price Matrix.
            $wpdb->insert($table_name, array(
                'name' => $name,
                'xml_structure' => $xml,
                'create_date' => $dt->format('Y-m-d H:i:s'),
                'create_user' => $user,
                'access_key' => $access_key
            ));
            
            return $access_key;
        }
        
        public function update_price_matrix($name, $xml, $access_key) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];
            $dt = new DateTime();
            
            // Update Price Matrix.
            $wpdb->update($table_name, array(
                'name' => $name,
                'xml_structure' => $xml,
                'modify_date' => $dt->format('Y-m-d H:i:s')),
                array(
                    'access_key' => $access_key
                )
            );
            
            return $access_key;
        }
        
        public function update_price_matrix_preview_settings($access_key, $font_color, $background_color, $disable_file_upload, $disable_design_online, $udraw_template_id, $measurement_unit) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix'];

            $_disable_file_upload = false;
            $_disable_design_online = false;
            if ($disable_file_upload == "true") { $_disable_file_upload = 1; }
            if ($disable_design_online == "true") { $_disable_design_online = 1; }
            
            // Update Price Matrix.
            $wpdb->update($table_name, array(
                'font_color' => $font_color,
                'background_color' => $background_color,
                'disable_file_upload' => $_disable_file_upload,
                'disable_design_online' => $_disable_design_online,
                'measurement_label' => $measurement_unit,
                'udraw_template_id' => $udraw_template_id),
                array(
                    'access_key' => $access_key
                )
            );
            
            return $access_key;            
        }
        
        public function update_price_matrix_in_categories($access_key, $cats) {                        
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix_in_categories'];
            
            // Remove existing items.
            $this->remove_price_matrix_in_categories($access_key, $cats);
            
            // Add updated categories.
            foreach ( $cats as $cat_item ) {
                $wpdb->insert($table_name, array(
                    'category_id' => $cat_item,
                    'access_key' => $access_key
                ));
            }
            
        }
        
        public function remove_price_matrix_in_categories($access_key) {
            global $wpdb;
            $uDrawSettings = new uDrawSettings();
            $_udraw_settings = $uDrawSettings->get_settings();
            $table_name = $_udraw_settings['udraw_db_udraw_price_matrix_in_categories'];
            
            $wpdb->query("DELETE FROM $table_name WHERE access_key = '$access_key'");
        }
        
        public function get_price_matrix_product_id() {
            $current_hidden_product_id = 0;
            if (is_multisite()) {
                $current_hidden_product_id = get_site_option('udraw_price_matrix_product_id', 0);
            } else {
                $current_hidden_product_id = get_option('udraw_price_matrix_product_id', 0);
            }
            return $current_hidden_product_id;
        }
        
        public function get_product_price_matrix_key($product_id) {
            // Check to see if we need to load up the price matrix module.
            // There are 2 ways the price matrix can be assigned to a product.
            // Frist: Assigned to category level
            // Second: Assigned directly to product.

            $udraw_price_matrix_access_key = "";

            // First, we'll check globally if defined by category.
            $terms = get_the_terms( $product_id, 'product_cat' );
            if ($terms) {
                foreach ($terms as $term) {
                    $price_matrix_object = $this->get_price_matrix_by_cat($term->term_id);
                    if ($price_matrix_object !== '' && count($price_matrix_object) > 0) {
                        // define access key for price matrix module
                        $udraw_price_matrix_access_key = $price_matrix_object[0]->access_key;
                    }
                }
            }

            // Override global/category if defined on the product directly.
            $is_price_matrix_set_on_product = get_post_meta($product_id, '_udraw_is_price_matrix_set', true);
            if ($is_price_matrix_set_on_product == "yes") {
                $selected_price_matrix_on_product = get_post_meta($product_id, '_udraw_price_matrix_list', true);
                if (strlen($selected_price_matrix_on_product[0]) > 0) {
                    // define access key from product level
                    $udraw_price_matrix_access_key = $selected_price_matrix_on_product[0];                               
                }
            }

            return $udraw_price_matrix_access_key;
        }
        
        // ----------------------------------------------------------- //
        // ------------------- Short Code Functions ------------------ //
        // ----------------------------------------------------------- //  
        public function shortcode_display_udraw_price_matrix($atts) {
            global $content;
            ob_start();
            
            $attributes = shortcode_atts( array(
                'id' => '0',
                'display' => ''
            ), $atts );

            $udraw_price_matrix_access_key = $attributes['id'];
            
            $uDraw = new uDraw();
            $uDraw->registerStyles();
            $uDraw->registerDesignerDefaultStyles();
            $this->registerScripts();
            $uDraw->registerjQueryFileUpload();
            require_once("templates/frontend/price-matrix-product.php");
            
            $output = ob_get_clean();
            return $output;            
        }

        // ----------------------------------------------------------- //
        // ------------------- AJAX Functions ------------------------ //
        // ----------------------------------------------------------- // 
        public function handle_ajax_get() {
            header('Content-Type: application/xml');
            if (isset($_REQUEST['price_matrix_id'])) {
                $price_matrix_set = $this->get_price_matrix_by_key($_REQUEST['price_matrix_id']);
                echo stripcslashes(base64_decode($price_matrix_set[0]->xml_structure));
            } else {
                echo "<xml></xml>";
            }
            wp_die();
        }
        
        public function handle_ajax_get_all() {
            $priceMatrixList = $this->get_price_matrix();
            
            echo json_encode($priceMatrixList);
            
            wp_die();
        }
        
        public function handle_ajax_save() {
            if (isset($_REQUEST['name']) && isset($_REQUEST['xml'])) {
                header('Content-Type: application/json');
                if (isset($_REQUEST['access_key']) && strlen($_REQUEST['access_key']) > 0) {
                    $access_key = $this->update_price_matrix(
                        $_POST['name'], 
                        $_POST['xml'],
                        $_POST['access_key']
                    );
                } else {
                    $access_key = $this->create_price_matrix(
                        $_REQUEST['name'], 
                        $_REQUEST['xml'], 
                        wp_get_current_user()->user_login
                    );
                }
                
                if (isset($_REQUEST['cats_form']) && $access_key) {
                    $this->handle_ajax_update_categories($_REQUEST['cats_form'], $access_key);
                }

                if ($access_key && isset($_REQUEST['font_color']) && isset($_REQUEST['background_color']) &&
                isset($_REQUEST['disable_file_upload']) && isset($_REQUEST['disable_design_online']) && isset($_REQUEST['measurement_label']) ) {
                    if (isset($_REQUEST['udraw_template_id'])) {
                        $linked_template = $_REQUEST['udraw_template_id'];
                    } else {
                        $linked_template = NULL;
                    }

                    $this->handle_ajax_update_preview_settings($access_key, $_REQUEST['font_color'], $_REQUEST['background_color'], $_REQUEST['disable_file_upload'], $_REQUEST['disable_design_online'], $linked_template, $_REQUEST['measurement_label']);
                }
                echo json_encode($access_key);
            }
            wp_die();
        }
        
        public function handle_ajax_update_categories($_form, $access_key) {
            header('Content-Type: application/json');    

            // Check which Categories have been selected        
            $tax_name = 'product_cat';
            $args = array( 'hide_empty' => false );
            $terms = get_terms( $tax_name, $args );
            $cats = array();
            $cats_form = array();
            if (is_array($_form)) { $cats_form = $_form; }

            // See which terms were included
            foreach ( $terms as $term ) {
                $term_name = '_udraw_price_matrix_cat_' . $term->term_id;
                foreach ( $cats_form as $cat_item ) {
                    if ( $cat_item["name"] == $term_name && $cat_item["value"] == 'on' ) {
                        array_push($cats, $term->term_id);
                        break;
                    }
                }        
            }

            $this->update_price_matrix_in_categories($access_key, $cats);
        }        
        
        public function handle_ajax_update_preview_settings($access_key, $font_colour, $background_colour, $disable_file_upload, $disable_design_online, $udraw_template_id, $measurement_label) {
            header('Content-Type: application/json');
            $this->update_price_matrix_preview_settings(
                $access_key,
                $font_colour, 
                $background_colour,
                $disable_file_upload,
                $disable_design_online,
                $udraw_template_id[0],
                $measurement_label
            );
        }
        
        public function handle_ajax_upload() {            
            $uDrawUpload = new uDrawUpload();
            $udrawSettings = new uDrawSettings();
            $_udraw_settings = $udrawSettings->get_settings();
            
            $files = array();
            $fileObj = new stdClass();
            
            $_session_id = uniqid();
            if (isset($_REQUEST['session'])) {
                $_session_id = $_REQUEST['session'];
            }

            // Set both upload folders and url location.
            $upload_dir = UDRAW_TEMP_UPLOAD_DIR . $_session_id . "/";
            $upload_url = UDRAW_TEMP_UPLOAD_URL . $_session_id . "/";

            // Create directory if doesn't exist.
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir);
            }
            
            // Check file exstension
            $fileName = pathinfo($_FILES['files']['name'][0], PATHINFO_FILENAME);
            $fileExt = strtolower(pathinfo($_FILES['files']['name'][0], PATHINFO_EXTENSION));
            
            // New Filename
            $newFile = rand(1, 32) .'_'. str_replace(' ','', $fileName) . '.' . $fileExt;
            $fileObj->name = $newFile;
            
            $validExt = array (
                'jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'psd' => 'application/octet-stream', 'pdf' => 'application/pdf',
                'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ai' => 'application/postscript', 'cdr' => 'application/octet-stream', 'eps' => 'application/postscript', 'ps' => 'application/postscript',
                'indd' => 'application/octet-stream', 'doc|docx' => 'application/msword', 'xls|xlsx' => 'application/excel', 'ppt|pptx' => 'application/mspowerpoint', 'obj' => 'application/octet-stream'
            );
            if (!is_null($_udraw_settings['goprint2_file_upload_types'])) {
                if (is_array($_udraw_settings['goprint2_file_upload_types'])) {
                    $validExt = $_udraw_settings['goprint2_file_upload_types'];
                }
            }
            
            $post_files = (is_null($_FILES['files'])) ? $_FILES['fileupload'] : $_FILES['files'];
            
            $custom_filename = '';
            if (isset($_REQUEST['filename']) && strlen($_REQUEST['filename']) > 0) {
                $custom_filename = $this->get_rand_num($upload_dir, $_REQUEST['filename']) . '_' . $_REQUEST['filename'];
            }
            
            $uploaded_files = $uDrawUpload->handle_upload($post_files, $upload_dir, $upload_url, $validExt, $custom_filename);
            if (is_array($uploaded_files)) {
                if ( !key_exists('error', $uploaded_files[0]) ) {
                    $fileObj->name = basename($uploaded_files[0]['file']);
                    $fileObj->size = filesize($uploaded_files[0]['file']);
                    $fileObj->url = $uploaded_files[0]['url'];
                    $fileObj->original_name = $uploaded_files[0]['original_name'];
                    
                    $output_file = $upload_dir . $newFile;
                    
                    // attempt to convert image/pdf to greyscale. If set.
                    if (isset($_REQUEST['greyscale'])) {
                        if (strtolower($fileExt) == 'pdf') {
                            $uDrawUtil = new uDrawUtil();
                            $uDrawConnect = new uDrawConnect();
                            $data = array(
                                'pdfDocument' => $fileObj->url,
                                'key' => uDraw::get_udraw_activation_key()
                            );
                            $udraw_convert_response = json_decode($uDrawUtil->get_web_contents(UDRAW_CONVERT_SERVER_URL . '/PDF2BW', http_build_query($data)));                
                            if ($udraw_convert_response->isSuccess) {
                                if (is_array($udraw_convert_response->data)) {
                                    $uDrawConnect->__downloadFile(UDRAW_CONVERT_SERVER_URL . $udraw_convert_response->data[0], $output_file);
                                    $fileObj->size = filesize($output_file);
                                }
                            }
                            
                            $fileObj->name = $newFile;
                            $fileObj->size = filesize($output_file);
                            $fileObj->url = $upload_url . $newFile;
                        } else if (strtolower($fileExt) == 'jpg') {
                            $im = imagecreatefromjpeg($uploaded_files[0]['file']);
                            if($im && imagefilter($im, IMG_FILTER_GRAYSCALE))
                            {
                                imagejpeg($im, $output_file);
                                $fileObj->name = $newFile;
                                $fileObj->size = filesize($output_file);
                                $fileObj->url = $upload_url . $newFile;
                            }
                            imagedestroy($im);
                        } else if (strtolower($fileExt) == 'png') {
                            $im = imagecreatefrompng($uploaded_files[0]['file']);
                            if ($im) {
                                $newimg = imagecreatetruecolor(imagesx($im), imagesy($im));
                                $transparent = imagecolorallocate($newimg, 0,0,0);
                                imagecolortransparent($newimg, $transparent);
                                imagecopy($newimg, $im, 0, 0, 0, 0,imagesx($im), imagesx($im));
                                imagefilter($newimg, IMG_FILTER_GRAYSCALE);
                                imagepng($newimg, $output_file);
                                imagedestroy($newimg);
                                $fileObj->name = $newFile;
                                $fileObj->size = filesize($output_file);
                                $fileObj->url = $upload_url . $newFile;
                            }
                            imagedestroy($im);
                        }
                    }
                    
                } else {
                    $fileObj->error = "Upload Failed";
                }
            } else {
                $fileObj->error = "Upload Failed";
            }
                        
            array_push($files, $fileObj);
            echo json_encode($files);
            
            wp_die();
        }
        
        public function handle_ajax_refresh_xml () {
            if (isset($_REQUEST['settings'])) {
                $_settings = json_decode(stripslashes($_REQUEST['settings']));
            } else {
                exit();
            }
            $_fields = $_settings->Fields;
            $_field = $_settings->Field;
            $_options = $_settings->Option;
            $_prices = $_settings->Price;
            //Build new XML with all settings
            $product_xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><Product></Product>");
            $newFields = $product_xml->addChild('Fields');
            //Add the attributes of Fields
            for ($i = 0; $i < count($_fields); $i++) {
                if (!isset($newFields->attributes()[$_fields[$i]->setting])) {
                    $newFields->addAttribute($_fields[$i]->setting, $_fields[$i]->value);
                }
            }
            //Add in Field child
            for ($i = 0; $i < count($_field); $i++) {
                $option_name = $this->remove_space($_field[$i]->option_name);
                $display_name = $this->remove_space($_field[$i]->display_name);
                $field_type = $this->remove_space($_field[$i]->field_type);
                $tooltip_text = $this->remove_space($_field[$i]->tooltip_text);
                $new_field = $newFields->addChild('Field', $display_name);
                $new_field->addAttribute('Options', $option_name);
                $new_field->addAttribute('Type', $field_type);
                if ($tooltip_text !== '' && $tooltip_text !== 'undefined' && $tooltip_text !== 'undefined') {
                    $new_field->addAttribute('Tooltip', $tooltip_text);
                }
            }
            $newOptions = $product_xml->addChild('Options');
            for ($i = 0; $i < count($_options); $i++) {
                $display_name = $this->remove_space($_options[$i]->display_name);
                $new_option = $newOptions->addChild('Option', $display_name);
                
                $parent_name = $this->remove_space($_options[$i]->parent_name);
                $new_option->addAttribute('Name', $parent_name);
                if (isset($_options[$i]->price_name)) {
                    $price_name = $this->remove_space($_options[$i]->price_name);
                    $new_option->addAttribute('Prices', $price_name);
                }
                if (isset($_options[$i]->options_name)) {
                    $option_name = $this->remove_space($_options[$i]->options_name);
                    $new_option->addAttribute('Options', $option_name);
                }
                if (property_exists($_options[$i], 'settings')) {
                    foreach($_options[$i]->settings as $setting => $value) {
                        $value = $this->remove_space($value);
                        $new_option->addAttribute($setting, $value);
                    }
                }
            }
            $newPrices = $product_xml->addChild('Prices');
            for ($i = 0; $i < count($_prices); $i++) {
                $new_price = $newPrices->addChild('Price');
                $price_name = $this->remove_space($_prices[$i]->price_name);
                $new_price->addAttribute('Name', $price_name);
                $new_price->addAttribute('Break', $_prices[$i]->price_break);
                $new_price->addAttribute($_prices[$i]->price_type, $_prices[$i]->unit_price);
                if (property_exists($_prices[$i], 'dimensions')) {
                    //If there is only 1 dimension object in the array
                    /*if (count($_prices[$i]->dimensions) === 1) {
                        foreach($_prices[$i]->dimensions[0] as $setting => $value) {
                            if (floatval($value) > 0) {
                                $new_price->addAttribute($setting, $value);
                            }
                        }
                    } else if (count($_prices[$i]->dimensions) > 1) {*/
                        $_encoded = json_encode($_prices[$i]->dimensions);
                        $new_price->addAttribute('ShippingDimensions', $_encoded);
                    //}
                }
            }
            //Now format it so it can be read easily
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($product_xml->asXML());
            echo json_encode(base64_encode($dom->saveXML()));
            wp_die();
        }
        
        private function remove_space ($string = '') {
            if (isset($string)) {
                if ($string[0] === ' ') {
                    $string = substr($string, 1);
                    $string = $this->remove_space($string);
                }
            }
            return $string;
        }
        
        private function get_rand_num ($dir, $filename) {
            $rand = rand(1,32);
            if (file_exists($dir . $rand . '_' . $filename)) {
                $rand = $this->get_rand_num($dir, $filename);
            }
            return $rand;
        }
    }
}
?>