<?php

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('Essential_Real_Estate')) {
    /**
     * The core plugin class
     * Class Essential_Real_Estate
     */
    class Essential_Real_Estate
    {
        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         */
        protected $loader;
        protected $forms;
        /**
         * Instance variable for singleton pattern
         */
        private static $instance = null;
        /**
         * Return class instance
         * @return Essential_Real_Estate|null
         */
        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        /**
         * Define the core functionality of the plugin
         */
        private function __construct()
        {
            $this->include_library();
            $this->set_locale();
            $this->admin_hooks();
            $this->public_hooks();
        }
        /**
         * Load the required dependencies for this plugin
         */
        private function include_library()
        {
            include_once ERE_PLUGIN_DIR . 'includes/class-ere-autoloader.php';
            if (!is_admin()) {
                // wp_handle_upload
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                // wp_generate_attachment_metadata
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // image_add_caption
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                // submit_button
                require_once(ABSPATH . 'wp-admin/includes/template.php');
            }
            // add_screen_option
            require_once(ABSPATH . 'wp-admin/includes/screen.php');
            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-loader.php';
            $this->loader = new ERE_Loader();
            require_once ERE_PLUGIN_DIR . 'includes/ere-core-functions.php';
	        require_once ERE_PLUGIN_DIR . 'includes/ere-formatting-functions.php';
            /**
             * The class responsible for defining internationalization functionality
             * of the plugin.
             */
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-i18n.php';
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-updater.php';

            require_once ERE_PLUGIN_DIR . 'public/class-ere-public.php';

            /**
             * The class include all Shortcodes
             */
            require_once ERE_PLUGIN_DIR . 'includes/vc-params/ere-vc-params.php';
            include_once ERE_PLUGIN_DIR . 'includes/class-ere-shortcodes.php';
            /**
             * The class defining Widget
             */
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-widgets.php';
            //class-ere-shortcode-my-properties
            require_once ERE_PLUGIN_DIR . 'includes/shortcodes/class-ere-vcmap.php';
            if(ere_get_option('enable_add_shortcode_tool', '1')=='1')
            {
                require_once ERE_PLUGIN_DIR . 'includes/insert-shortcode/class-ere-insert-shortcode.php';
            }
            /**
             * The class responsible for defining all actions that occur in the public-facing
             * side of the site.
             */
            require_once ERE_PLUGIN_DIR . 'includes/forms/class-ere-forms.php';

            require_once ERE_PLUGIN_DIR . 'includes/class-ere-schedule.php';

            require_once ERE_PLUGIN_DIR . 'includes/class-ere-captcha.php';
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-background-emailer.php';
            global $ere_background_emailer;
            $ere_background_emailer= new ERE_Background_Emailer();
            $this->forms = new ERE_Forms();
        }
        /**
         * Define the locale for this plugin for internationalization.
         */
        private function set_locale()
        {
            $plugin_i18n = new ERE_i18n();
            $plugin_i18n->set_domain(ERE_PLUGIN_NAME);
            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        }

        /**
         * Register all of the hooks related to the admin area functionality
         */
        private function admin_hooks()
        {
            add_action( 'init', array( 'ERE_Shortcodes', 'init' ) );
            $plugin_updater = new ERE_Updater();
            $this->loader->add_action('admin_init', $plugin_updater, 'updater');

            $plugin_texts= new ERE_Admin_Texts();
            $this->loader->add_action('current_screen', $plugin_texts, 'add_hooks');

            $plugin_admin = new ERE_Admin();

            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
            $this->loader->add_action('init', $plugin_admin, 'register_post_status');
            //Countries
            $admin_location = new ERE_Admin_Location();
            $this->loader->add_action('admin_menu', $admin_location, 'countries_create_menu');
            $this->loader->add_action('admin_init', $admin_location, 'countries_register_setting');
            //Provice / State
            $this->loader->add_action('property-city_add_form_fields',  $admin_location, 'add_form_fields_property_city', 10, 2 );
            $this->loader->add_action('created_property-city',  $admin_location, 'save_property_city_meta', 10, 2 );
            $this->loader->add_action('property-city_edit_form_fields',  $admin_location, 'edit_form_fields_property_city', 10, 2 );
            $this->loader->add_action('edited_property-city',  $admin_location, 'update_property_city_meta', 10, 2 );
            $this->loader->add_filter('manage_edit-property-city_columns', $admin_location,  'add_columns_property_city');
            $this->loader->add_filter('manage_property-city_custom_column', $admin_location,  'add_columns_property_city_content', 10, 3 );
            $this->loader->add_filter('manage_edit-property-city_sortable_columns',  $admin_location, 'add_columns_property_city_sortable' );
            //City
            $this->loader->add_action('property-neighborhood_add_form_fields',  $admin_location, 'add_form_fields_property_neighborhood', 10, 2 );
            $this->loader->add_action('created_property-neighborhood',  $admin_location, 'save_property_neighborhood_meta', 10, 2 );
            $this->loader->add_action('property-neighborhood_edit_form_fields',  $admin_location, 'edit_form_fields_property_neighborhood', 10, 2 );
            $this->loader->add_action('edited_property-neighborhood',  $admin_location, 'update_property_neighborhood_meta', 10, 2 );
            $this->loader->add_filter('manage_edit-property-neighborhood_columns', $admin_location,  'add_columns_property_neighborhood');
            $this->loader->add_filter('manage_property-neighborhood_custom_column', $admin_location,  'add_columns_property_neighborhood_content', 10, 3 );
            $this->loader->add_filter('manage_edit-property-neighborhood_sortable_columns',  $admin_location, 'add_columns_property_neighborhood_sortable' );

            $this->loader->add_action('created_property-status',$plugin_admin,'add_meta_property_status_order_number',10,2);


            $widgets = new ERE_Widgets();
            $this->loader->add_action('widgets_init', $widgets, 'register_widgets');

            $this->loader->add_filter('gsf_register_post_type', $plugin_admin, 'register_post_type');
            $this->loader->add_filter('gsf_meta_box_config', $plugin_admin, 'register_meta_boxes');
            $this->loader->add_filter('gsf_register_taxonomy', $plugin_admin, 'register_taxonomy');
            $this->loader->add_action('admin_head-edit-tags.php', $plugin_admin, 'remove_taxonomy_parent_category');
            $this->loader->add_action('admin_head-term.php', $plugin_admin, 'remove_taxonomy_parent_category');
            $this->loader->add_filter('gsf_term_meta_config', $plugin_admin, 'register_term_meta');
            $this->loader->add_filter('gsf_option_config', $plugin_admin, 'register_options_config');
            $this->loader->add_filter('gsf_image_default_dir', $plugin_admin, 'image_default_dir');

            // Property Post Type
            $admin_property = new ERE_Admin_Property();
            $this->loader->add_action('restrict_manage_posts', $admin_property, 'filter_restrict_manage_property');
            $this->loader->add_filter('parse_query', $admin_property, 'property_filter');
            $this->loader->add_filter('pre_get_posts', $admin_property, 'post_types_admin_order');
            $this->loader->add_action('admin_init', $admin_property, 'approve_property');
            $this->loader->add_action('admin_init', $admin_property, 'expire_property');
            $this->loader->add_action('admin_init', $admin_property, 'hidden_property');
            $this->loader->add_action('admin_init', $admin_property, 'show_property');
	        $this->loader->add_action('wp_ajax_ere_admin_feature_property', $admin_property, 'feature');
            // Filters to modify URL slugs
            $this->loader->add_filter('ere_property_slug', $admin_property, 'modify_property_slug');
            $this->loader->add_filter('ere_property_type_slug', $admin_property, 'modify_property_type_slug');
            $this->loader->add_filter('ere_property_status_slug', $admin_property, 'modify_property_status_slug');
            $this->loader->add_filter('ere_property_feature_slug', $admin_property, 'modify_property_feature_slug');
            $this->loader->add_filter('ere_property_city_slug', $admin_property, 'modify_property_city_slug');
            $this->loader->add_filter('ere_property_neighborhood_slug', $admin_property, 'modify_property_neighborhood_slug');
            $this->loader->add_filter('ere_property_state_slug', $admin_property, 'modify_property_state_slug');
            $this->loader->add_filter('ere_property_label_slug', $admin_property, 'modify_property_label_slug');
            // Agent Post Type
            $admin_agent = new ERE_Admin_Agent();
            $this->loader->add_filter('ere_agent_slug', $admin_agent, 'modify_agent_slug');
            $this->loader->add_filter('ere_agency_slug', $admin_agent, 'modify_agency_slug');
            $this->loader->add_filter('init', $admin_agent, 'modify_author_slug');

            $this->loader->add_action('restrict_manage_posts', $admin_agent, 'filter_restrict_manage_agent');
            $this->loader->add_filter('parse_query', $admin_agent, 'agent_filter');
            $this->loader->add_filter('pre_get_posts', $admin_agent, 'post_types_admin_order');

            $this->loader->add_action('save_post', $admin_agent, 'save_agent_meta', 10, 2);
            $this->loader->add_action('admin_init', $admin_agent, 'approve_agent');
            // Package Post Type
            $admin_package = new ERE_Admin_Package();
            $this->loader->add_filter('ere_package_slug', $admin_package, 'modify_package_slug');

            // Agent Packages Post Type
            $admin_user_package = new ERE_Admin_User_Package();
            $this->loader->add_filter('ere_user_package_slug', $admin_user_package, 'modify_user_package_slug');
            $this->loader->add_action('restrict_manage_posts', $admin_user_package, 'filter_restrict_manage_user_package');
            $this->loader->add_filter('parse_query', $admin_user_package, 'user_package_filter');
	        $this->loader->add_action('before_delete_post',$admin_user_package,'delete_user_package');

            // Invoice Post Type
            $admin_invoice = new ERE_Admin_Invoice();
            $this->loader->add_filter('ere_invoice_slug', $admin_invoice, 'modify_invoice_slug');
            $this->loader->add_action('restrict_manage_posts', $admin_invoice, 'filter_restrict_manage_invoice');
            $this->loader->add_filter('parse_query', $admin_invoice, 'invoice_filter');
            // Trans Log Post Type
            $admin_trans_log = new ERE_Admin_Trans_Log();
            $this->loader->add_filter('ere_trans_log_slug', $admin_trans_log, 'modify_trans_log_slug');
            $this->loader->add_action('restrict_manage_posts', $admin_trans_log, 'filter_restrict_manage_trans_log');
            $this->loader->add_filter('parse_query', $admin_trans_log, 'trans_log_filter');
            if (is_admin()) {
                global $pagenow;
                $setup_page = new ERE_Admin_Setup();
                $this->loader->add_action('admin_menu', $setup_page, 'admin_menu', 12);
                $this->loader->add_action('admin_init', $setup_page, 'redirect');

                // property custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'property') {
                    $this->loader->add_filter('manage_edit-property_columns', $admin_property, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $admin_property, 'display_custom_column');
                    $this->loader->add_filter('manage_edit-property_sortable_columns', $admin_property, 'sortable_columns');
                    $this->loader->add_filter('request', $admin_property, 'column_orderby');
                    $this->loader->add_filter('post_row_actions', $admin_property, 'modify_list_row_actions',10,2);
                }

                // agent custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'agent') {
                    $this->loader->add_filter('manage_edit-agent_columns', $admin_agent, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $admin_agent, 'display_custom_column');
                    $this->loader->add_filter('post_row_actions', $admin_agent, 'modify_list_row_actions',10,2);
                }
                // package custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'package') {
                    $this->loader->add_filter('manage_edit-package_columns', $admin_package, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $admin_package, 'display_custom_column');
                    $this->loader->add_filter('post_row_actions', $admin_package, 'modify_list_row_actions',10,2);
                }
                // agent package custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'user_package') {
                    $this->loader->add_filter('manage_edit-user_package_columns', $admin_user_package, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $admin_user_package, 'display_custom_column');
                    $this->loader->add_filter('post_row_actions', $admin_user_package, 'modify_list_row_actions',10,2);
                }
                // Invoice custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'invoice') {
                    $this->loader->add_filter('manage_edit-invoice_columns', $admin_invoice, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $admin_invoice, 'display_custom_column');
                    $this->loader->add_filter('manage_edit-invoice_sortable_columns', $admin_invoice, 'sortable_columns');
                    $this->loader->add_filter('request', $admin_invoice, 'column_orderby');
                    $this->loader->add_filter('post_row_actions', $admin_invoice, 'modify_list_row_actions',10,2);
                }
                // Trans_log custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'trans_log') {
                    $this->loader->add_filter('manage_edit-trans_log_columns', $admin_trans_log, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $admin_trans_log, 'display_custom_column');
                    $this->loader->add_filter('manage_edit-trans_log_sortable_columns', $admin_trans_log, 'sortable_columns');
                    $this->loader->add_filter('request', $admin_trans_log, 'column_orderby');
                    $this->loader->add_filter('post_row_actions', $admin_trans_log, 'modify_list_row_actions',10,2);
                }
                $setup_metaboxes = new ERE_Admin_Setup_Metaboxes();
                $this->loader->add_action('load-post.php', $setup_metaboxes, 'meta_boxes_setup');
                $this->loader->add_action('load-post-new.php', $setup_metaboxes, 'meta_boxes_setup');



            }
            $vc_map = new ERE_Vc_map();
            $this->loader->add_action('vc_before_init', $vc_map, 'register_vc_map');

            // ERE DES
	        add_filter( 'ere_description', 'wptexturize' );
	        add_filter( 'ere_description', 'convert_smilies' );
	        add_filter( 'ere_description', 'convert_chars' );
	        add_filter( 'ere_description', 'wpautop' );
	        add_filter( 'ere_description', 'shortcode_unautop' );
	        add_filter( 'ere_description', 'prepend_attachment' );
	        add_filter( 'ere_description', 'do_shortcode', 11 ); // After wpautop().

	        add_filter('gsf_save_metabox_meta_field_keys', array($plugin_admin, 'gsf_save_metabox_meta_field_keys'), 10, 3);
        }
        /**
         * Register all of the hooks related to the public-facing functionality
         */
        private function public_hooks()
        {
            $this->loader->add_action('init', $this, 'do_output_buffer');
            $plugin_public = ERE_Public::getInstance();

            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
            $this->loader->add_action('wp_footer', $plugin_public, 'enqueue_styles_rtl');
	        $this->loader->add_action('wp_footer', $plugin_public, 'print_tmpl_template');
            $this->loader->add_action('init', $plugin_public, 'register_assets');
	        $this->loader->add_action('init', $plugin_public, 'register_assets_google_map',8);
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
            $this->loader->add_filter('template_include', $plugin_public, 'template_loader');
            $this->loader->add_action('pre_get_posts', $plugin_public, 'set_posts_per_page');

            $profile = ERE_Profile::getInstance();
            $this->loader->add_filter('show_user_profile', $profile, 'custom_user_profile_fields');
            $this->loader->add_filter('edit_user_profile', $profile, 'custom_user_profile_fields');
            $this->loader->add_action('profile_update', $profile, 'profile_update');
            $this->loader->add_action('edit_user_profile_update', $profile, 'update_custom_user_profile_fields');
            $this->loader->add_action('personal_options_update', $profile, 'update_custom_user_profile_fields');

            $this->loader->add_action('wp_ajax_ere_profile_image_upload_ajax', $profile, 'profile_image_upload_ajax');

            $this->loader->add_action('wp_ajax_ere_update_profile_ajax', $profile, 'update_profile_ajax');

            $this->loader->add_action('wp_ajax_ere_change_password_ajax', $profile, 'change_password_ajax');

            $this->loader->add_action('wp_ajax_ere_register_user_as_agent_ajax', $profile, 'register_user_as_agent_ajax');

            $this->loader->add_action('wp_ajax_ere_leave_agent_ajax', $profile, 'leave_agent_ajax');

            $login_register = ERE_Login_Register::getInstance();
            $this->loader->add_action('init', $login_register, 'hide_admin_bar', 9);
            $this->loader->add_action('admin_init', $login_register, 'restrict_admin_access');
            $this->loader->add_action('wp_footer', $login_register, 'login_register_modal');
            $this->loader->add_action('wp_ajax_ere_login_ajax', $login_register, 'login_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_login_ajax', $login_register, 'login_ajax');

            $this->loader->add_action('wp_ajax_ere_register_ajax', $login_register, 'register_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_register_ajax', $login_register, 'register_ajax');

            $this->loader->add_action('wp_ajax_ere_reset_password_ajax', $login_register, 'reset_password_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_reset_password_ajax', $login_register, 'reset_password_ajax');
	        $this->loader->add_action('template_redirect', $login_register, 'redirect_is_login');

            $shortcodes= ERE_Shortcodes::getInstance();
            $this->loader->add_action('wp', $shortcodes, 'shortcode_property_action_handler');
            $this->loader->add_action('ere_my_properties_content_edit', $shortcodes, 'edit_property');
            $this->loader->add_action('wp_ajax_ere_property_gallery_fillter_ajax', $shortcodes, 'property_gallery_fillter_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_gallery_fillter_ajax', $shortcodes, 'property_gallery_fillter_ajax');

            $this->loader->add_action('wp_ajax_ere_property_featured_fillter_city_ajax', $shortcodes, 'property_featured_fillter_city_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_featured_fillter_city_ajax', $shortcodes, 'property_featured_fillter_city_ajax');

            $this->loader->add_action('wp_ajax_ere_property_paging_ajax', $shortcodes, 'property_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_paging_ajax', $shortcodes, 'property_paging_ajax');

            $this->loader->add_action('wp_ajax_ere_agent_paging_ajax', $shortcodes, 'agent_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_agent_paging_ajax', $shortcodes, 'agent_paging_ajax');

            $this->loader->add_action('wp_ajax_ere_property_set_session_view_as_ajax', $shortcodes, 'property_set_session_view_as_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_set_session_view_as_ajax', $shortcodes, 'property_set_session_view_as_ajax');

            $this->loader->add_action('wp_ajax_ere_agent_set_session_view_as_ajax', $shortcodes, 'agent_set_session_view_as_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_agent_set_session_view_as_ajax', $shortcodes, 'agent_set_session_view_as_ajax');

            $property = ERE_Property::getInstance();
            $this->loader->add_action('wp_ajax_ere_property_img_upload_ajax', $property, 'property_img_upload_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_img_upload_ajax', $property, 'property_img_upload_ajax');

            $this->loader->add_action('wp_ajax_ere_property_attachment_upload_ajax', $property, 'property_attachment_upload_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_attachment_upload_ajax', $property, 'property_attachment_upload_ajax');

            $this->loader->add_action('wp_ajax_ere_remove_property_attachment_ajax', $property, 'remove_property_attachment_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_remove_property_attachment_ajax', $property, 'remove_property_attachment_ajax');
            $this->loader->add_filter('ere_submit_property', $property, 'submit_property');
            $this->loader->add_action('wp_ajax_ere_contact_agent_ajax', $property, 'contact_agent_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_contact_agent_ajax', $property, 'contact_agent_ajax');
            $this->loader->add_action('wp_ajax_property_print_ajax', $property, 'property_print_ajax');
            $this->loader->add_action('wp_ajax_nopriv_property_print_ajax', $property, 'property_print_ajax');
            $this->loader->add_action('before_delete_post', $property, 'delete_property_attachments');
            $this->loader->add_action('template_redirect', $property, 'set_views_counter',9999);

            $this->loader->add_action('wp_ajax_ere_get_states_by_country_ajax', $property, 'get_states_by_country_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_get_states_by_country_ajax', $property, 'get_states_by_country_ajax');

            $this->loader->add_action('wp_ajax_ere_get_cities_by_state_ajax', $property, 'get_cities_by_state_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_get_cities_by_state_ajax', $property, 'get_cities_by_state_ajax');

            $this->loader->add_action('wp_ajax_ere_get_neighborhoods_by_city_ajax', $property, 'get_neighborhoods_by_city_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_get_neighborhoods_by_city_ajax', $property, 'get_neighborhoods_by_city_ajax');

            $this->loader->add_action('wp_ajax_ere_property_submit_review_ajax', $property, 'submit_review_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_submit_review_ajax', $property, 'submit_review_ajax');

            $this->loader->add_action( 'ere_property_rating_meta',$property, 'rating_meta_filter', 4, 9 );
            $this->loader->add_action('deleted_comment', $property, 'delete_review',10,1);
            $this->loader->add_action('transition_comment_status', $property, 'approve_review', 10, 3);
            //favorites
            $this->loader->add_action('wp_ajax_ere_favorite_ajax', $property, 'favorite_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_favorite_ajax', $property, 'favorite_ajax');

            //view gallery
            $this->loader->add_action('wp_ajax_ere_view_gallery_ajax', $property, 'view_gallery_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_view_gallery_ajax', $property, 'view_gallery_ajax');

            $invoice= ERE_Invoice::getInstance();
            $this->loader->add_action('wp_ajax_ere_invoice_print_ajax', $invoice, 'invoice_print_ajax');

            //compare
            $compare = ERE_Compare::getInstance();
            $this->loader->add_action('wp_logout', $compare, 'close_session');
            $this->loader->add_action('ere_show_compare', $compare, 'show_compare_listings', 5);

            $this->loader->add_action('wp_ajax_ere_compare_add_remove_property_ajax', $compare, 'compare_add_remove_property_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_compare_add_remove_property_ajax', $compare, 'compare_add_remove_property_ajax');

            $this->loader->add_action('wp_footer', $compare, 'template_compare_listing');

            $this->loader->add_action('init', $this->forms, 'load_posted_form');

            $payment = ERE_Payment::getInstance();
            $this->loader->add_action('wp_ajax_ere_paypal_payment_per_listing_ajax', $payment, 'paypal_payment_per_listing_ajax');
            $this->loader->add_action('wp_ajax_ere_paypal_payment_per_package_ajax', $payment, 'paypal_payment_per_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_paypal_payment_per_package_ajax', $payment, 'paypal_payment_per_package_ajax');

            $this->loader->add_action('wp_ajax_ere_wire_transfer_per_package_ajax', $payment, 'wire_transfer_per_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_wire_transfer_per_package_ajax', $payment, 'wire_transfer_per_package_ajax');

            $this->loader->add_action('wp_ajax_ere_wire_transfer_per_listing_ajax', $payment, 'wire_transfer_per_listing_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_wire_transfer_per_listing_ajax', $payment, 'wire_transfer_per_listing_ajax');

            $this->loader->add_action('wp_ajax_ere_free_package_ajax', $payment, 'free_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_free_package_ajax', $payment, 'free_package_ajax');

	        $search = ERE_Search::getInstance();
            $this->loader->add_action('wp_ajax_ere_property_search_ajax', $search, 'ere_property_search_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_search_ajax', $search, 'ere_property_search_ajax');

            $this->loader->add_action('wp_ajax_ere_property_search_map_ajax', $search, 'ere_property_search_map_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_search_map_ajax', $search, 'ere_property_search_map_ajax');




            $this->loader->add_action('wp_ajax_ere_ajax_change_price_on_status_change', $search, 'ere_ajax_change_price_on_status_change');
            $this->loader->add_action('wp_ajax_nopriv_ere_ajax_change_price_on_status_change', $search, 'ere_ajax_change_price_on_status_change');

            $save_search= ERE_Save_Search::getInstance();
            $this->loader->add_action('wp_ajax_ere_save_search_ajax', $save_search, 'save_search_ajax');

            $schedule = ERE_Schedule::getInstance();
            $this->loader->add_action('init', $schedule, 'scheduled_hook');
            $this->loader->add_action('ere_per_listing_check_expire', $schedule, 'per_listing_check_expire');
            $this->loader->add_action('ere_saved_search_check_result', $schedule, 'saved_search_check_result');

            $captcha= ERE_Captcha::getInstance();
            $this->loader->add_action('wp_footer', $captcha, 'render_recaptcha');
            $this->loader->add_action('ere_verify_recaptcha', $captcha, 'verify_recaptcha');
            $this->loader->add_action('ere_generate_form_recaptcha', $captcha, 'form_recaptcha');

	        if (ere_enable_captcha('login')) {
		        $this->loader->add_action( 'login_form', $captcha, 'form_recaptcha' );
		        $this->loader->add_action( 'login_footer', $captcha, 'render_recaptcha_wp_login' );
		        $this->loader->add_action('authenticate', $captcha, 'verify_recaptcha_wp_login', 999, 3 );

	        }

	        if (ere_enable_captcha('reset_password')) {
		        $this->loader->add_action('lostpassword_form', $captcha, 'form_recaptcha');
		        $this->loader->add_action( 'login_footer', $captcha, 'render_recaptcha_wp_login' );
		        $this->loader->add_action( 'lostpassword_post', $captcha, 'verify_recaptcha_wp_lostpassword' );
	        }

	        if (ere_enable_captcha('register')) {
		        $this->loader->add_action( 'register_form', $captcha, 'form_recaptcha' );
		        $this->loader->add_action( 'login_footer', $captcha, 'render_recaptcha_wp_login' );
		        $this->loader->add_filter( 'registration_errors', $captcha, 'verify_recaptcha_wp_registration',10,3 );
	        }

            $agent= ERE_Agent::getInstance();
            $this->loader->add_action('wp_ajax_ere_agent_submit_review_ajax', $agent, 'submit_review_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_agent_submit_review_ajax', $agent, 'submit_review_ajax');

	        $this->loader->add_action('ere_agent_rating_meta',$agent,'rating_meta_filter',4,9);
            $this->loader->add_action('deleted_comment', $agent, 'delete_review',10,1);
            $this->loader->add_action('transition_comment_status', $agent, 'approve_review', 10, 3);
            add_action('admin_init',array($this,'set_current_lang_ajax'));

        }

        public function set_current_lang_ajax() {
	        if ( isset( $_GET[ 'ere_wpml_lang' ] ) ) {
		        do_action( 'wpml_switch_language',  $_GET[ 'ere_wpml_lang' ] ); // switch the content language
	        }
        }

        /**
         * Run the loader to execute all of the hooks with WordPress
         */
        public function run()
        {
	        add_filter( 'gsf_loader_framework', array( $this, 'loader_framework' ) );
	        include_once ERE_PLUGIN_DIR . 'lib/smart-framework/init.php';
	        add_action('gsf_after_setup_framework',array($this,'after_setup_framework'));

        }

	    public function loader_framework($frameworks) {
		    $frameworks[] = array(
			    'version' => '2.5',
			    'path' => ERE_PLUGIN_DIR . 'lib/smart-framework/',
			    'uri' => ERE_PLUGIN_URL . 'lib/smart-framework/',
			    'plugin_file' => ERE_PLUGIN_FILE,
		    );
		    return $frameworks;
	    }

        public function after_setup_framework() {
	        $this->loader->run();
        }

        /**
         * The reference to the class that orchestrates the hooks with the plugin.
         */
        public function get_loader()
        {
            return $this->loader;
        }

        /**
         * do_output_buffer
         */
        function do_output_buffer()
        {
            ob_start();
        }

        /**
         * Get forms
         * @return mixed
         */
        public function get_forms()
        {
            return $this->forms;
        }

        /**
         * Get template path
         * @return mixed
         */
        public function template_path()
        {
            return apply_filters('ere_template_path', 'ere-templates/');
        }
    }
}
if(!function_exists('ERE'))
{
    function ERE() {
        return Essential_Real_Estate::get_instance();
    }
}
// Global for backwards compatibility.
$GLOBALS['Essential_Real_Estate'] = ERE();
