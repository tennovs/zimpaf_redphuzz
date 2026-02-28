<?php
if (!class_exists('uDrawSettings')) {
    class uDrawSettings {
        
        function __contsruct() {
        }

        public function __checkAccess($key) {
            $uDrawUtil = new uDrawUtil();
            $host = $_SERVER['HTTP_HOST'];
            if(strpos($host,':'.$_SERVER['SERVER_PORT'])!== false){
                $host=str_replace(':'.$_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
            }
            $json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL .'/api/access/check/'. $key . '/'. str_replace(':','-',str_replace('.', '-', $host)));
            $response = json_decode($json);
            return $response;
        }
        
        public function get_setting($name) {
            $settings = $this->get_settings();
            $value = "";
            if (array_key_exists($name, $settings)) {
                $value = $settings[$name];
            }
            return $value;
        }
        
        public function get_setting_with_default($name, $default) {
            $value = $this->get_setting($name);
             if (strlen($value) == 0) {
                 return $default;
             } else {
                 return $value;
             }
        }
        
        public function get_settings() {
            $settings = array();
            if (is_multisite()) {
                $settings = get_blog_option(get_current_blog_id(), 'udraw_settings');
            } else {
                $settings = get_option('udraw_settings');
            }
            
            $default_settings = array();
            // General Settings
            $default_settings['show_product_title'] = true;
            $default_settings['show_product_breadcrumbs'] = true;
            $default_settings['show_customer_preview_before_adding_to_cart'] = false;
            $default_settings['show_product_description'] = false;                
            $default_settings['split_variations_2_step'] = false;
            $default_settings['improved_display_options'] = true;
            $default_settings['update_product_images'] = true;

            // Designer UI Settings
            $default_settings['designer_disable_global_clipart'] = false;
            $default_settings['designer_enable_facebook_photos'] = false;
            $default_settings['designer_enable_instagram_photos'] = false;
            $default_settings['designer_enable_flickr_photos'] = false;
            $default_settings['designer_enable_google_photos'] = false;
            $default_settings['designer_enable_local_clipart'] = false;
            $default_settings['designer_disable_qrqode'] = false;
            $default_settings['designer_disable_shapes'] = false;
            $default_settings['designer_disable_image_cropper'] = false;
            $default_settings['designer_disable_image_replace'] = false;
            $default_settings['designer_disable_image_filters'] = false;
            $default_settings['designer_disable_image_fill'] = false;
            $default_settings['designer_disable_text_gradient'] = false;
            $default_settings['designer_disable_ruler'] = false;
            $default_settings['designer_enable_optimize_large_images'] = false;
            $default_settings['designer_skin'] = 'default';
            $default_settings['udraw_designer_language'] = 'en';
            $default_settings['udraw_designer_display_orientation'] = 'ltr';
            $default_settings['udraw_handler_file'] = admin_url( 'admin-ajax.php' );
            $default_settings['udraw_price_matrix_upload_path'] = wp_make_link_relative(UDRAW_TEMP_UPLOAD_URL);
            $default_settings['udraw_designer_css_hook'] = '';
            $default_settings['udraw_designer_js_hook'] = '';
            $default_settings['udraw_designer_global_template_key'] = '';
            $default_settings['udraw_generate_jpg_production'] = '';
            $default_settings['udraw_generate_png_production'] = '';
            $default_settings['udraw_production_png_color_replacement'] = '';
            $default_settings['udraw_designer_display_linked_template_name'] = false;
            $default_settings['udraw_designer_enable_threed'] = false;
            $default_settings['udraw_debug_pdf_production'] = false;
            $default_settings['udraw_order_document_format'] = '';
            $default_settings['udraw_design_page_names'] = '';
            $default_settings['udraw_production_file_cleanup'] = '';
            $default_settings['udraw_production_files_to_keep'] = '90days';
            $default_settings['udraw_custom_duration_days'] = 90;
            //$default_settings['udraw_send_cleanup_report'] = '';

            // Pages Settings
            $default_settings['udraw_private_template_page_id'] = 0;
            $default_settings['udraw_customer_saved_design_page_id'] = 0;  

            // Social Media Settings
            $default_settings['designer_enable_facebook_functions'] = false;
            $default_settings['designer_enable_instagram_functions'] = false;
            $default_settings['designer_enable_flickr_functions'] = false;
            $default_settings['designer_facebook_app_id'] = '';
            $default_settings['designer_instagram_client_id'] = '';
            $default_settings['designer_flickr_client_id'] = '';
            $default_settings['designer_flickr_secret_id'] = '';
            $default_settings['designer_enable_google_functions'] = false;
            $default_settings['designer_google_api_key'] = '';
            $default_settings['designer_google_client_id'] = '';

            // GoPrint2 Settings
            $default_settings['goprint2_api_key'] = '';
            $default_settings['goprint2_send_file_after_order'] = true;
            $validExt = array (
                'jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'psd' => 'application/octet-stream', 'pdf' => 'application/pdf',
                'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ai' => 'application/postscript', 'cdr' => 'application/octet-stream', 'eps' => 'application/postscript', 'ps' => 'application/postscript',
                'indd' => 'application/octet-stream', 'doc|docx' => 'application/msword', 'xls|xlsx' => 'application/excel', 'ppt|pptx' => 'application/mspowerpoint',
                'obj' => 'application/octet-stream', 'zip' => 'application/octet-stream'
            );                
            $default_settings['goprint2_file_upload_types'] = $validExt;
            $default_settings['goprint2_file_upload_min_dpi'] = 150;

            // GoSendEx Settings
            $default_settings['gosendex_api_key'] = '';
            $default_settings['gosendex_send_file_after_order'] = false;
            $default_settings['gosendex_send_email_after_order_sent'] = false;
            $default_settings['gosendex_email_to_send_notification'] = '';
            $default_settings['gosendex_domain'] = '';

            // GoEpower Settings
            $default_settings['goepower_username'] = '';
            $default_settings['goepower_password'] = '';
            $default_settings['goepower_api_key'] = '';
            $default_settings['goepower_send_file_after_order'] = false;
            $default_settings['goepower_producer_id'] = '';
            $default_settings['goepower_company_id'] = '';
            $default_settings['goepower_additional_notify_email'] = '';
            $default_settings['goepower_api_url'] = 'https://udraw-api.goepower.com';

            // GoEpower PDF Settings
            $default_settings['goepower_pdf_preview_auto_update'] = false;
            $default_settings['goepower_pdf_disable_refresh_button'] = false;
            $default_settings['goepower_approve_button_placement'] = "top";
            $default_settings['goepower_preview_mode'] = "image";
            $default_settings['goepower_submit_on_status'] = "submitted";
            $default_settings['udraw_pdf_template_css_hook'] = '';
            $default_settings['udraw_pdf_template_js_hook'] = '';
            $default_settings['udraw_pdf_template_html_hook'] = '';
            $default_settings['update_product_images'] = true;
            $default_settings['designer_enable_optimize_large_images'] = false;
            $default_settings['designer_facebook_app_id'] = '';
            $default_settings['designer_enable_facebook_functions'] = false;
            $default_settings['designer_enable_facebook_photos'] = false;
            $default_settings['designer_enable_instagram_functions'] = false;
            $default_settings['designer_instagram_client_id'] = '';
            $default_settings['designer_enable_instagram_photos'] = false;
            $default_settings['designer_exclude_bleed'] = false;
            $default_settings['designer_impose_bleed'] = false;
            $default_settings['designer_bleed'] = 0;
            $default_settings['designer_bleed_metric'] = 'in';
            $default_settings['goepower_designer_location'] = '';
            $default_settings['loading_animation_link'] = '';
            $default_settings['approve_proof_text'] = '';
            // Check if some options are not defined and assign default values
            $default_settings['udraw_price_matrix_upload_path'] = wp_make_link_relative(UDRAW_TEMP_UPLOAD_URL);
            // For update of uDraw Handler.
            $default_settings['udraw_handler_file'] = admin_url( 'admin-ajax.php' );
            // Update for GoPrint2 File Upload
            $default_settings['goprint2_file_upload_min_dpi'] = 150;
            //Price Matrix Settings
            $default_settings['udraw_price_matrix_placement'] = '';
            $default_settings['udraw_price_matrix_settings_placement'] = 'top';
            $default_settings['udraw_price_matrix_css_hook'] = 'div.udraw_price_matrix_container { }';
            $default_settings['udraw_price_matrix_js_hook'] = '';
            $default_settings['goepower_submit_on_status'] = "submitted";
            $default_settings['designer_out_of_bounds_warning'] = false;
            //DPI checker
            $default_settings['designer_check_dpi'] = false;
            $default_settings['designer_minimum_dpi'] = 300;
            $default_settings['designer_enforce_dpi_requirement'] = false;
            
            foreach($default_settings as $key => $value) {
                if (!isset($settings[$key])) {
                    $settings[$key] = $value;
                }
                
                // Renamed Fullscreen to 'Default'
                if ($settings['designer_skin'] === 'fullscreen') {
                    $settings['designer_skin'] = 'default';
                }
            }
            
            // Update existing settings with new db mappings.
            if (!isset($settings['udraw_db_udraw_templates'])) {
                $settings['udraw_is_multisite'] = false;
                $settings = $this->set_db_tables($settings, $settings['udraw_is_multisite']);
            }                        
            
            if (is_multisite()) {
                update_blog_option(get_current_blog_id(), 'udraw_settings', $settings);
            } else {
                update_option('udraw_settings', $settings);
            }            
            
            return $settings;
        }
        
        public function update_setting($key, $value) {
            $settings = $this->get_settings();
            $settings[$key] = $value;
            
            if (is_multisite()) {
                update_blog_option(get_current_blog_id(), 'udraw_settings', $settings);
                restore_current_blog();
            } else {
                update_option('udraw_settings', $settings);
            }            
        }
        
        public function update_settings() {
            if (isset($_POST['save_udraw_settings']) ) {                
                $setting_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
                $settings = $this->get_settings();
                switch ( $setting_tab ) {
                    case "general":
                        // General Settings                    
                        $settings = $this->__setBooleanFromPost($settings, 'show_customer_preview_before_adding_to_cart');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_exclude_bleed');
                        $settings = $this->__setBooleanFromPost($settings, 'show_product_title');
                        $settings = $this->__setBooleanFromPost($settings, 'show_product_breadcrumbs');                    
                        $settings = $this->__setBooleanFromPost($settings, 'show_product_description');                    
                        $settings = $this->__setBooleanFromPost($settings, 'improved_display_options');
                        $settings = $this->__setBooleanFromPost($settings, 'split_variations_2_step');
                        $settings = $this->__setBooleanFromPost($settings, 'update_product_images');                                                
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_is_multisite');   
                        
                        // Update Settings and DB based on mutlisite option                        
                        $settings = $this->set_db_tables($settings, $settings['udraw_is_multisite']);

                        $settings['udraw_general_css_hook'] = stripslashes($_POST['udraw_general_css_hook']);
                        $settings['udraw_general_js_hook'] = stripslashes($_POST['udraw_general_js_hook']);
                        break;
                    case "designer-ui" :
                        $settings['udraw_designer_language'] = $_POST['udraw_designer_language'];
                        $settings['udraw_designer_display_orientation'] = $_POST['udraw_designer_display_orientation'];
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_qrqode');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_shapes');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_image_cropper');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_image_replace');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_image_filters');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_image_fill');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_text_gradient');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_ruler');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_disable_global_clipart');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_local_clipart');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_facebook_photos');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_instagram_photos');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_flickr_photos');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_google_flickr_photos');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_optimize_large_images');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_impose_bleed');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_out_of_bounds_warning');
                        $settings = $this->__setBooleanFromPost($settings, 'designer_check_dpi');
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_designer_display_linked_template_name');
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_designer_enable_threed');
                        $settings['udraw_designer_css_hook'] = stripslashes($_POST['udraw_designer_css_hook']);
                        $settings['udraw_designer_js_hook'] = stripslashes($_POST['udraw_designer_js_hook']);
                        $settings['designer_skin'] = stripslashes($_POST['designer_skin']);
                        $settings['udraw_designer_global_template_key'] = stripslashes($_POST['udraw_designer_global_template_key']);
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_generate_jpg_production');
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_generate_png_production');
                        $settings['udraw_production_png_color_replacement'] = stripslashes($_POST['udraw_production_png_color_replacement']);
                        $settings['designer_bleed'] = stripslashes($_POST['designer_bleed']);
                        $settings['designer_bleed_metric'] = stripslashes($_POST['designer_bleed_metric']);
                        $settings['designer_minimum_dpi'] = stripslashes($_POST['designer_minimum_dpi']);
                        $settings['designer_enforce_dpi_requirement'] = isset($_POST['designer_enforce_dpi_requirement']);
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_debug_pdf_production');
                        $settings['udraw_order_document_format'] = stripslashes($_POST['udraw_order_document_format']);
                        $settings['udraw_design_page_names'] = stripslashes($_POST['udraw_design_page_names']);
                        $settings = $this->__setBooleanFromPost($settings, 'udraw_production_file_cleanup');
                        $settings['udraw_production_files_to_keep'] = stripslashes($_POST['udraw_production_files_to_keep']);
                        $settings['udraw_custom_duration_days'] = stripslashes($_POST['udraw_custom_duration_days']);
                        //$settings['udraw_send_cleanup_report'] = stripslashes($_POST['udraw_send_cleanup_report']);
                        break;
                    case "pages" :
                        $settings['udraw_private_template_page_id'] = stripslashes($_POST['udraw_private_template_page_id']);
                        $settings['udraw_customer_saved_design_page_id'] = stripslashes($_POST['udraw_customer_saved_design_page_id']);
                        break;
                    case "social_media":
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_facebook_functions');
                        $settings['designer_facebook_app_id'] = $_POST['designer_facebook_app_id'];
                        
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_instagram_functions');
                        $settings['designer_instagram_client_id'] = $_POST['designer_instagram_client_id'];
                        
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_flickr_functions');
                        $settings['designer_flickr_client_id'] = $_POST['designer_flickr_client_id'];
                        $settings['designer_flickr_secret_id'] = $_POST['designer_flickr_secret_id'];
                        
                        $settings = $this->__setBooleanFromPost($settings, 'designer_enable_google_functions');
                        $settings['designer_google_api_key'] = $_POST['designer_google_api_key'];
                        $settings['designer_google_client_id'] = $_POST['designer_google_client_id'];
                        break;
                    case "price_matrix":
                        if ($_POST['price_matrix_placement'] === 'custom') {
                            $settings['udraw_price_matrix_placement'] = $_POST['price_matrix_placement_input'];
                        } else {
                            $settings['udraw_price_matrix_placement'] = '';
                        }
                        $settings['udraw_price_matrix_settings_placement'] = $_POST['price_matrix_settings_placement'];
                        $settings['udraw_price_matrix_css_hook'] = stripslashes($_POST['udraw_price_matrix_css_hook']);
                        $settings['udraw_price_matrix_js_hook'] = stripslashes($_POST['udraw_price_matrix_js_hook']);
                        $settings = $this->__setNKVFromPost($settings, 'goprint2_file_upload_types');
                        $settings['goprint2_file_upload_min_dpi'] = $_POST['goprint2_file_upload_min_dpi'];
                        break;
                    case "gosendex" :
                        $settings['gosendex_api_key'] = $_POST['gosendex_api_key'];
                        $settings['gosendex_send_file_after_order'] = $_POST['gosendex_send_file_after_order'];
                        $settings['gosendex_send_email_after_order_sent'] = $_POST['gosendex_send_email_after_order_sent'];
                        $settings['gosendex_email_to_send_notification'] = $_POST['gosendex_email_to_send_notification'];
                        $settings['gosendex_domain'] = $_POST['gosendex_domain'];
                        break;                        
                    case "goprint2" :
                        $settings['goprint2_api_key'] = $_POST['goprint2_api_key'];
                        $settings['goprint2_send_file_after_order'] = $_POST['goprint2_send_file_after_order'];
                        $settings = $this->__setNKVFromPost($settings, 'goprint2_file_upload_types');
                        $settings['goprint2_file_upload_min_dpi'] = $_POST['goprint2_file_upload_min_dpi'];
                        break;
                    case "goepower" :
                        $settings['goepower_username'] = $_POST['goepower_username'];
                        if (strlen($_POST['goepower_password']) > 0) {
                            $settings['goepower_password'] = $_POST['goepower_password'];
                        }
                        $settings['goepower_api_key'] = $_POST['goepower_api_key'];
                        $settings['goepower_producer_id'] = $_POST['goepower_producer_id'];
                        $settings['goepower_company_id'] = $_POST['goepower_company_id'];
                        $settings['goepower_send_file_after_order'] = $_POST['goepower_send_file_after_order'];
                        $settings['goepower_additional_notify_email'] = $_POST['goepower_additional_notify_email'];
                        $settings = $this->__setBooleanFromPost($settings,'goepower_pdf_preview_auto_update');
                        $settings['goepower_preview_mode'] = $_POST['goepower_preview_mode'];
                        $settings['goepower_approve_button_placement'] = $_POST['goepower_approve_button_placement'];
                        $settings['goepower_submit_on_status'] = $_POST['goepower_submit_on_status'];
                        $settings = $this->__setBooleanFromPost($settings, 'goepower_pdf_disable_refresh_button');
                        $settings = $this->__setBooleanFromPost($settings, 'goepower_hide_labels_on_text_input');
                        $settings['udraw_pdf_template_css_hook'] = stripslashes($_POST['udraw_pdf_template_css_hook']);
                        $settings['udraw_pdf_template_js_hook'] = stripslashes($_POST['udraw_pdf_template_js_hook']);
                        $settings['udraw_pdf_template_html_hook'] = stripslashes($_POST['udraw_pdf_template_html_hook']);
                        $settings['goepower_designer_location'] = $_POST['goepower_designer_location'];
                        $settings['loading_animation_link'] = $_POST['loading_animation_link'];
                        $settings['approve_proof_text'] = $_POST['approve_proof_text'];
                        break;
                    case "activation" :
                        uDraw::set_udraw_activation_key(trim($_POST['udraw_activation_key']));
                        $this->__activateAccess(uDraw::get_udraw_activation_key());
                        break;
                    default :
                        break;
                }
                
                $settings = apply_filters('udraw_save_settings', $settings, $setting_tab);
                
                // We are hard coding these settings for now.
                $settings['udraw_handler_file'] = admin_url( 'admin-ajax.php' );
                $settings['udraw_price_matrix_upload_path'] = wp_make_link_relative(UDRAW_TEMP_UPLOAD_URL);
                
                // Update Settings.
                if (is_multisite()) {
                    update_blog_option(get_current_blog_id(), 'udraw_settings', $settings);
                } else {
                    update_option('udraw_settings', $settings);
                }                
            }            
        }
        
        public function create_wp_post($user_id, $slug, $title, $content, $post_type) {
            // Initialize the post ID to -1. This indicates no action has been taken.
            $post_id = -1;

            // If the page doesn't already exist, then create it
            if( null == get_page_by_title( $title ) ) {

                // Set the page ID so that we know the page was created successfully
                $post_id = wp_insert_post(
                    array(
                        'comment_status' =>	'closed',
                        'ping_status' => 'closed',
                        'post_author' => $user_id,
                        'post_name' => $slug,
                        'post_title' => $title,
                        'post_content' => $content,
                        'post_status' => 'publish',
                        'post_type' => $post_type
                    )
                );
            } else {
                // Return existing page id with the same title.
                $page = get_page_by_title( $title );
                $post_id = $page->ID;
            }
            
            return $post_id;
        }
        
        public function set_db_tables($settings, $is_multisite) {
            global $wpdb;
            $db_prefix = $wpdb->prefix;
            if ($is_multisite) { $db_prefix = 'udraw_shared_'; }
            
            $settings['udraw_db_udraw_templates'] = $db_prefix . 'udraw_templates';
            $settings['udraw_db_udraw_customer_designs'] = $db_prefix . 'udraw_customer_designs';
            $settings['udraw_db_udraw_clipart'] = $db_prefix . 'udraw_clipart';
            $settings['udraw_db_udraw_clipart_category'] = $db_prefix . 'udraw_clipart_category';
            $settings['udraw_db_udraw_templates_category'] = $db_prefix . 'udraw_templates_category';
            $settings['udraw_db_udraw_temporary_designdata'] = $db_prefix . 'udraw_temporary_designdata';
            $settings['udraw_db_udraw_price_matrix'] = $db_prefix . 'udraw_price_matrix';
            $settings['udraw_db_udraw_price_matrix_in_categories'] = $db_prefix . 'udraw_price_matrix_in_categories';    

            if ($is_multisite) {
                $tables_to_clone = array($settings['udraw_db_udraw_templates'], $settings['udraw_db_udraw_customer_designs'], $settings['udraw_db_udraw_clipart'],
                    $settings['udraw_db_udraw_clipart_category'], $settings['udraw_db_udraw_templates_category'], $settings['udraw_db_udraw_temporary_designdata'],
                    $settings['udraw_db_udraw_price_matrix'], $settings['udraw_db_udraw_price_matrix_in_categories']);
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                foreach ($tables_to_clone as $table_name) {
                    // Init the DB if this is the first time.
                    $old_table = str_replace($db_prefix, $wpdb->prefix, $table_name);
                    
                    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                        // Create and clone tables into the shared tables.
                        $sql = "CREATE TABLE $table_name LIKE $old_table;";
                        dbDelta($sql);
                        $sql = "INSERT $table_name SELECT * FROM $old_table;";
                        $wpdb->query($sql);
                    }
                }                
            }
            
            return $settings;
        }       
        
        public function __setBooleanFromPost($settings, $setting_name) 
        {
            if (isset($_POST[$setting_name])) {
                $settings[$setting_name] = true;
            } else {
                $settings[$setting_name] = false;
            }
            if ($setting_name == 'improved_display_options' || $setting_name == 'split_variations_2_step') {
                $settings[$setting_name] = true;
            }
            return $settings;
        }
        
        public function __setNKVFromPost($settings, $setting_name)
        {
            if (isset($_POST[$setting_name])) {                
                if (is_array($_POST[$setting_name])) {
                    $nkv = array();
                    foreach ($_POST[$setting_name] as $value) {
                        $parts = explode(":", $value);
                        $nkv[$parts[0]] = $parts[1];
                    }
                    $settings[$setting_name] = $nkv;
                }                
            }
            
            return $settings;
        }
        
        public function __activateAccess($key) {
            $uDrawUtil = new uDrawUtil();
            $host =$_SERVER['HTTP_HOST'];
            if(strpos($host,':'.$_SERVER['SERVER_PORT'])!== false){
                $host=str_replace(':'.$_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
            }
            $json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL . '/api/access/activate/'. $key . '/'. str_replace('.', '-', $host));
            $response = json_decode($json);
        }
        
        public function __logAccess() {
            $uDrawUtil = new uDrawUtil();
            $host =$_SERVER['HTTP_HOST'];
            if(strpos($host,':'.$_SERVER['SERVER_PORT'])!== false){
                $host=str_replace(':'.$_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
            }
            $json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL .'/api/access/log/default/'. str_replace('.', '-', $host));
            $response = json_decode($json);
        }
        
        public function checkFacebookAppID ($appid) {
            if (ctype_digit($appid)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
?>