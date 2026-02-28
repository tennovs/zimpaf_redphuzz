<?php

/**
 * The public-facing functionality of the plugin.
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Public')) {
    /**
     * The public-facing functionality of the plugin
     * Class ERE_Public
     */
    class ERE_Public
    {


	    /*
		 * loader instances
		 */
	    private static $_instance;

	    public static function getInstance()
	    {
		    if (self::$_instance == null) {
			    self::$_instance = new self();
		    }

		    return self::$_instance;
	    }


	    /**
         * Initialize the class and set its properties.
         */
        public function __construct()
        {
            require_once ERE_PLUGIN_DIR . 'public/class-ere-template-hooks.php';
        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         */
        public function enqueue_styles()
        {
            $min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
            wp_enqueue_style('jquery-ui', ERE_PLUGIN_URL . 'public/assets/packages/jquery-ui/jquery-ui.min.css', array(), '1.11.4', 'all');


            wp_enqueue_style('owl.carousel', ERE_PLUGIN_URL . 'public/assets/packages/owl-carousel/assets/owl.carousel.min.css', array(), '2.3.4', 'all');
	        wp_enqueue_style('light-gallery', ERE_PLUGIN_URL . 'public/assets/packages/light-gallery/css/lightgallery.min.css', array(), '1.2.18', 'all');


	        wp_register_style('select2_css', ERE_PLUGIN_URL . 'public/assets/packages/select2/css/select2.min.css', array(), '4.0.6-rc.1', 'all');

            wp_enqueue_style(ERE_PLUGIN_PREFIX . 'main', ERE_PLUGIN_URL . 'public/assets/css/main' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');

            // shortcode
            wp_register_style(ERE_PLUGIN_PREFIX . 'agency', ERE_PLUGIN_URL . 'public/templates/shortcodes/agency/assets/css/agency' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'agent', ERE_PLUGIN_URL . 'public/templates/shortcodes/agent/assets/css/agent' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property', ERE_PLUGIN_URL . 'public/templates/shortcodes/property/assets/css/property' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-carousel', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-carousel/assets/css/property-carousel' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-featured', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-featured/assets/css/property-featured' . $min_suffix . '.css', array('owl.carousel'), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-gallery', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/css/property-gallery' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'google-map-property', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-map/assets/css/property-map' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-advanced-search', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-advanced-search/assets/css/property-advanced-search' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-search', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-search/assets/css/property-search' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-search-map', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-search-map/assets/css/property-search-map' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-mini-search', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-mini-search/assets/css/property-mini-search' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-slider', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-slider/assets/css/property-slider' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-type', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-type/assets/css/property-type' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');

            // Widget
            wp_register_style(ERE_PLUGIN_PREFIX . 'listing-property-taxonomy-widget', ERE_PLUGIN_URL . 'public/templates/widgets/listing-property-taxonomy/assets/css/listing-property-taxonomy' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'mortgage-calculator', ERE_PLUGIN_URL . 'public/templates/widgets/mortgage-calculator/assets/css/mortgage-calculator' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'search-form-widget', ERE_PLUGIN_URL . 'public/templates/widgets/search-form/assets/css/search-form' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'top-agents', ERE_PLUGIN_URL . 'public/templates/widgets/top-agents/assets/css/top-agents' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');

            // Archive, Single
            wp_register_style(ERE_PLUGIN_PREFIX . 'archive-agent', ERE_PLUGIN_URL . 'public/assets/css/archive-agent' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'archive-property', ERE_PLUGIN_URL . 'public/assets/css/archive-property' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'single-agent', ERE_PLUGIN_URL . 'public/assets/css/single-agent' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'single-invoice', ERE_PLUGIN_URL . 'public/assets/css/single-invoice' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'single-property', ERE_PLUGIN_URL . 'public/assets/css/single-property' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'dashboard', ERE_PLUGIN_URL . 'public/assets/css/dashboard' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            wp_register_style(ERE_PLUGIN_PREFIX . 'submit-property', ERE_PLUGIN_URL . 'public/assets/css/submit-property' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');


	        if (class_exists('Elementor\Plugin') && Elementor\Plugin::$instance->preview->is_preview_mode()){
		        // Widget
		        wp_enqueue_style(ERE_PLUGIN_PREFIX . 'listing-property-taxonomy-widget');
		        wp_enqueue_style(ERE_PLUGIN_PREFIX . 'mortgage-calculator');
		        wp_enqueue_style(ERE_PLUGIN_PREFIX . 'search-form-widget');
		        wp_enqueue_style(ERE_PLUGIN_PREFIX . 'top-agents');
	        }
        }

        /**
         * enqueue RTL css
         */
        public function enqueue_styles_rtl()
        {
            $min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
            $enable_rtl_mode = ere_get_option('enable_rtl_mode', 0);
            if (is_rtl() || ($enable_rtl_mode == 1) || isset($_GET['RTL'])) {
                wp_enqueue_style(ERE_PLUGIN_PREFIX . 'rtl', ERE_PLUGIN_URL . 'public/assets/css/rtl' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            }
        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         */
        public function enqueue_scripts()
        {
            $min_suffix = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
            wp_enqueue_style('bootstrap');

	        wp_enqueue_script('light-gallery', ERE_PLUGIN_URL . 'public/assets/packages/light-gallery/js/lightgallery-all.min.js', array('jquery'), '1.2.18', true);
            wp_register_script('moment', ERE_PLUGIN_URL . 'public/assets/packages/bootstrap/js/moment.min.js', array('jquery'), '2.11.1', true);
            wp_register_script('bootstrap-datetimepicker', ERE_PLUGIN_URL . 'public/assets/packages/bootstrap/js/bootstrap-datetimepicker.min.js', array('jquery', 'moment'), '4.17.42', true);
            wp_register_script('bootstrap-tabcollapse', ERE_PLUGIN_URL . 'public/assets/packages/bootstrap/js/bootstrap-tabcollapse.min.js', array('jquery'), '1.0', true);
            wp_enqueue_script('jquery-validate', ERE_PLUGIN_URL . 'public/assets/js/jquery.validate.min.js', array('jquery'), '1.17.0', true);
            wp_register_script('jquery-geocomplete', ERE_PLUGIN_URL . 'public/assets/js/jquery.geocomplete.min.js', array('jquery'), '1.7.0', true);
            wp_enqueue_script('imagesloaded', ERE_PLUGIN_URL . 'public/assets/js/imagesloaded.pkgd.min.js', array('jquery'), '4.1.3', true);

	        wp_register_script('jquery-ui-touch-punch', ERE_PLUGIN_URL . 'public/assets/packages/jquery-ui/jquery.ui.touch-punch.min.js', array('jquery'), '0.2.3', true);

            $enable_filter_location = ere_get_option('enable_filter_location', 0);

	        wp_register_script('select2_js', ERE_PLUGIN_URL . 'public/assets/packages/select2/js/select2.full.min.js', array('jquery'), '4.0.6-rc.1', true);


            wp_enqueue_script('infobox', ERE_PLUGIN_URL . 'public/assets/js/infobox.min.js', array('jquery', 'google-map'), '1.1.13', true);
            wp_enqueue_script('jquery-core');
            wp_enqueue_script('owl.carousel', ERE_PLUGIN_URL . 'public/assets/packages/owl-carousel/owl.carousel.min.js', array('jquery'), '2.3.4', true);

            $dec_point = ere_get_price_decimal_separator();
            $thousands_sep = ere_get_option('thousand_separator', ',');
	        $decimals = ere_get_option('number_of_decimals', 0);
            $currency = ere_get_option('currency_sign', esc_html__('$', 'essential-real-estate'));
	        $currency_position = ere_get_option('currency_position', 'before');

	        $ere_main_vars = apply_filters('ere_main_vars', array(
		        'ajax_url' => ERE_AJAX_URL,
		        'confirm_yes_text' => esc_html__('Yes', 'essential-real-estate'),
		        'confirm_no_text' => esc_html__('No', 'essential-real-estate'),
		        'loading_text' => esc_html__('Processing, Please wait...', 'essential-real-estate'),
		        'sending_text' => esc_html__('Sending email, Please wait...', 'essential-real-estate'),
		        'decimals' => $decimals,
		        'dec_point' => $dec_point,
		        'thousands_sep' => $thousands_sep,
		        'currency' => $currency,
		        'currency_position' => $currency_position
	        ));

	        wp_enqueue_script(ERE_PLUGIN_PREFIX . 'main', ERE_PLUGIN_URL . 'public/assets/js/ere-main' . $min_suffix . '.js', array('jquery', 'wp-util', 'jquery-validate','jquery-ui-core','jquery-ui-slider','jquery-ui-dialog','jquery-ui-sortable','jquery-ui-touch-punch','bootstrap'), ERE_PLUGIN_VER, true);
            wp_localize_script(ERE_PLUGIN_PREFIX . 'main', 'ere_main_vars', $ere_main_vars);
            //Login
            wp_register_script(ERE_PLUGIN_PREFIX . 'login', ERE_PLUGIN_URL . 'public/assets/js/account/ere-login' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
            wp_localize_script(ERE_PLUGIN_PREFIX . 'login', 'ere_login_vars',
                array(
                    'ajax_url' => ERE_AJAX_URL,
                    'loading' => esc_html__('Sending user info, please wait...', 'essential-real-estate'),
                )
            );
            //Register
            wp_register_script(ERE_PLUGIN_PREFIX . 'register', ERE_PLUGIN_URL . 'public/assets/js/account/ere-register' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
            wp_localize_script(ERE_PLUGIN_PREFIX . 'register', 'ere_register_vars',
                array(
                    'ajax_url' => ERE_AJAX_URL,
                    'loading' => esc_html__('Sending user info, please wait...', 'essential-real-estate'),
                )
            );
            wp_enqueue_script(ERE_PLUGIN_PREFIX . 'compare', ERE_PLUGIN_URL . 'public/assets/js/property/ere-compare' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
            wp_localize_script(ERE_PLUGIN_PREFIX . 'compare', 'ere_compare_vars',
                array(
                    'ajax_url' => ERE_AJAX_URL,
                    'compare_button_url' => ere_get_permalink('compare'),
                    'alert_title' => esc_html__('Information!', 'essential-real-estate'),
                    'alert_message' => esc_html__('Only allowed to compare up to 4 properties!', 'essential-real-estate'),
                    'alert_not_found' => esc_html__('Compare Page Not Found!', 'essential-real-estate')
                )
            );
            //Profile
	        $profile_ajax_upload_url = add_query_arg( 'action', 'ere_profile_image_upload_ajax', ERE_AJAX_URL );
			$profile_ajax_upload_url = add_query_arg( 'nonce', wp_create_nonce('ere_allow_upload_nonce'), $profile_ajax_upload_url );

            wp_register_script(ERE_PLUGIN_PREFIX . 'profile', ERE_PLUGIN_URL . 'public/assets/js/account/ere-profile' . $min_suffix . '.js', array('jquery', 'plupload', 'jquery-validate'), ERE_PLUGIN_VER, true);
            $user_profile_data = array(
                'ajax_url' => ERE_AJAX_URL,
                'ajax_upload_url' => $profile_ajax_upload_url,
                'file_type_title' => esc_html__('Valid file formats', 'essential-real-estate'),
                'ere_site_url' => site_url(),
                'confirm_become_agent_msg' => esc_html__('Are you sure you want to become an agent.', 'essential-real-estate'),
                'confirm_leave_agent_msg' => esc_html__('Are you sure you want to leave agent account and comeback normal account.', 'essential-real-estate'),
            );
            wp_localize_script(ERE_PLUGIN_PREFIX . 'profile', 'ere_profile_vars', $user_profile_data);
            //Property
            wp_register_script(ERE_PLUGIN_PREFIX . 'property', ERE_PLUGIN_URL . 'public/assets/js/property/ere-property' . $min_suffix . '.js', array('jquery', 'plupload', 'jquery-ui-sortable', 'jquery-validate', 'jquery-geocomplete'), ERE_PLUGIN_VER, true);

            $googlemap_zoom_level = ere_get_option('googlemap_zoom_level', '12');
            $google_map_style = ere_get_option('googlemap_style', '');
            $map_icons_path_marker = ERE_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
            $googlemap_default_country = ere_get_option('default_country', 'US');
	        $googlemap_coordinate_default = ere_get_option('googlemap_coordinate_default', '-33.868419, 151.193245');
            $default_marker = ere_get_option('marker_icon', '');
            if ($default_marker != '') {
                if (is_array($default_marker) && $default_marker['url'] != '') {
                    $map_icons_path_marker = $default_marker['url'];
                }
            }


            $property_upload_nonce = wp_create_nonce('property_allow_upload');
	        $property_ajax_upload_url = add_query_arg( 'action', 'ere_property_img_upload_ajax', ERE_AJAX_URL );
	        $property_ajax_upload_url = add_query_arg( 'nonce', $property_upload_nonce, $property_ajax_upload_url );

	        $property_attachment_ajax_upload_url = add_query_arg( 'action', 'ere_property_attachment_upload_ajax', ERE_AJAX_URL );
	        $property_attachment_ajax_upload_url = add_query_arg( 'nonce', $property_upload_nonce, $property_attachment_ajax_upload_url );

	        $ere_property_vars = apply_filters('ere_property_vars', array(
		        'ajax_url' => ERE_AJAX_URL,
		        'ajax_upload_url' => $property_ajax_upload_url,
		        'ajax_upload_attachment_url' => $property_attachment_ajax_upload_url,
		        'googlemap_zoom_level' => $googlemap_zoom_level,
		        'google_map_style' => $google_map_style,
		        'googlemap_marker_icon' => $map_icons_path_marker,
		        'googlemap_default_country' => $googlemap_default_country,
		        'googlemap_coordinate_default' => $googlemap_coordinate_default,
		        'upload_nonce' => $property_upload_nonce,
		        'file_type_title' => esc_html__('Valid file formats', 'essential-real-estate'),
		        'max_property_images' => ere_get_option('max_property_images', '10'),
		        'image_max_file_size' => ere_get_option('image_max_file_size', '1000kb'),
		        'max_property_attachments' => ere_get_option('max_property_attachments', '2'),
		        'attachment_max_file_size' => ere_get_option('attachment_max_file_size', '1000kb'),
		        'attachment_file_type' => ere_get_option('attachment_file_type', 'pdf,txt,doc,docx'),
		        'floor_name_text' => esc_html__('Floor Name', 'essential-real-estate'),
		        'floor_size_text' => esc_html__('Floor Size', 'essential-real-estate'),
		        'floor_size_postfix_text' => esc_html__('Floor Size Postfix', 'essential-real-estate'),
		        'floor_bedrooms_text' => esc_html__('Floor Bedrooms', 'essential-real-estate'),
		        'floor_bathrooms_text' => esc_html__('Floor Bathrooms', 'essential-real-estate'),
		        'floor_price_text' => esc_html__('Floor Price', 'essential-real-estate'),
		        'floor_price_postfix_text' => esc_html__('Floor Price Postfix', 'essential-real-estate'),
		        'floor_image_text' => esc_html__('Floor Image', 'essential-real-estate'),
		        'floor_description_text' => esc_html__('Floor Description', 'essential-real-estate'),
		        'floor_upload_text' => esc_html__('Choose image', 'essential-real-estate'),
		        'ere_metabox_prefix' => ERE_METABOX_PREFIX,
		        'enable_filter_location'=>$enable_filter_location
	        ));

            wp_localize_script(ERE_PLUGIN_PREFIX . 'property', 'ere_property_vars', $ere_property_vars);
            wp_register_script(ERE_PLUGIN_PREFIX . 'property_steps', ERE_PLUGIN_URL . 'public/assets/js/property/ere-property-steps' . $min_suffix . '.js', array('jquery', 'jquery-validate', ERE_PLUGIN_PREFIX . 'property'), ERE_PLUGIN_VER, true);
            $property_req_fields = ere_get_option('required_fields', array('property_title', 'property_type', 'property_price', 'property_map_address'));
            if (!is_array($property_req_fields)) {
                $property_req_fields = array();
            }
            wp_localize_script(ERE_PLUGIN_PREFIX . 'property_steps', 'ere_property_steps_vars', array(
                'property_title' => in_array("property_title", $property_req_fields),
                'property_type' => in_array("property_type", $property_req_fields),
                'property_status' => in_array("property_status", $property_req_fields),
                'property_label' => in_array("property_label", $property_req_fields),
                'property_price' => in_array("property_price", $property_req_fields),
                'property_price_prefix' => in_array("property_price_prefix", $property_req_fields),
                'property_price_postfix' => in_array("property_price_postfix", $property_req_fields),
                'property_rooms' => in_array("property_rooms", $property_req_fields),
                'property_bedrooms' => in_array("property_bedrooms", $property_req_fields),
                'property_bathrooms' => in_array("property_bathrooms", $property_req_fields),
                'property_size' => in_array("property_size", $property_req_fields),
                'property_land' => in_array("property_land", $property_req_fields),
                'property_garage' => in_array("property_garage", $property_req_fields),
                'property_year' => in_array("property_year", $property_req_fields),
                'property_address' => in_array("property_map_address", $property_req_fields),
                'property_country' => in_array("country", $property_req_fields),
                'state' => in_array("state", $property_req_fields),
                'city' => in_array("city", $property_req_fields),
	            'neighborhood' => in_array("neighborhood", $property_req_fields),
                'postal_code' => in_array("postal_code", $property_req_fields),
            ));




            //Payment
            wp_register_script(ERE_PLUGIN_PREFIX . 'payment', ERE_PLUGIN_URL . 'public/assets/js/payment/ere-payment' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
            $payment_data = array(
                'ajax_url' => ERE_AJAX_URL,
                'processing_text' => esc_html__('Processing, Please wait...', 'essential-real-estate')
            );
            wp_localize_script(ERE_PLUGIN_PREFIX . 'payment', 'ere_payment_vars', $payment_data);

            wp_register_script(ERE_PLUGIN_PREFIX . 'owl_carousel', ERE_PLUGIN_URL . 'public/assets/js/ere-carousel' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
            wp_enqueue_script(ERE_PLUGIN_PREFIX . 'owl_carousel');
            $enable_captcha = ere_get_option('enable_captcha', array());
            if (is_array($enable_captcha) && count($enable_captcha) > 0) {

                $recaptcha_src = esc_url_raw(add_query_arg(array(
                    'render' => 'explicit',
                    'onload' => 'ere_recaptcha_onload_callback'
                ), 'https://www.google.com/recaptcha/api.js'));

                // enqueue google reCAPTCHA API
                wp_register_script(
                    'ere-google-recaptcha',
                    $recaptcha_src,
                    array(),
                    ERE_PLUGIN_VER,
                    true
                );
            }
            wp_register_script('star-rating', ERE_PLUGIN_URL . 'public/assets/js/star-rating.min.js', array('jquery'), '4.0.3', true);


	        wp_register_script('isotope', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/js/isotope.pkgd.min.js', array('jquery'), '3.0.6', true);
	        wp_register_script('imageLoaded', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/js/imagesloaded.pkgd.min.js', array('jquery'), '4.1.4', true);


            // shortcodes
	        wp_register_script(ERE_PLUGIN_PREFIX . 'search_map', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-search-map/assets/js/property-search-map' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);

	        wp_register_script(ERE_PLUGIN_PREFIX . 'search_js_map', ERE_PLUGIN_URL.'public/templates/shortcodes/property-search/assets/js/property-search-map' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
	        wp_register_script(ERE_PLUGIN_PREFIX . 'search_js', ERE_PLUGIN_URL.'public/templates/shortcodes/property-search/assets/js/property-search' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);

	        wp_register_script(ERE_PLUGIN_PREFIX . 'property_featured', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-featured/assets/js/property-featured' . $min_suffix . '.js', array('jquery', ERE_PLUGIN_PREFIX . 'owl_carousel'), ERE_PLUGIN_VER, true);


	        wp_register_script(ERE_PLUGIN_PREFIX . 'advanced_search_js', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-advanced-search/assets/js/property-advanced-search' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);

	        wp_register_script(ERE_PLUGIN_PREFIX . 'property_gallery', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/js/property-gallery' . $min_suffix . '.js', array('jquery','imageLoaded','isotope',ERE_PLUGIN_PREFIX.'owl_carousel'), ERE_PLUGIN_VER, true);


        }

        public function register_assets() {
            wp_register_script('stripe-checkout','https://checkout.stripe.com/checkout.js',array(),null,true);

            $cdn_bootstrap_css = ere_get_option('cdn_bootstrap_css', '');
            if (!empty($cdn_bootstrap_css)) {
                wp_register_style('bootstrap', $cdn_bootstrap_css);
            } else {
                wp_register_style('bootstrap', ERE_PLUGIN_URL . 'public/assets/packages/bootstrap/css/bootstrap.min.css',array(),'3.4.1');
            }

            $cdn_bootstrap_js = ere_get_option('cdn_bootstrap_js', '');
            if (!empty($cdn_bootstrap_css)) {
                wp_register_script('bootstrap', $cdn_bootstrap_js, array('jquery'),null,true);
            } else {
                wp_register_script('bootstrap', ERE_PLUGIN_URL . 'public/assets/packages/bootstrap/js/bootstrap.min.js', array('jquery'),'3.4.1',true);
            }

            wp_register_style(ERE_PLUGIN_PREFIX . 'property-print', ERE_PLUGIN_URL . '/public/assets/css/property-print.css',array(),ERE_PLUGIN_VER);
            wp_register_style(ERE_PLUGIN_PREFIX . 'property-print-rtl', ERE_PLUGIN_URL . '/public/assets/css/property-print-rtl.css',array(),ERE_PLUGIN_VER);


            wp_register_style(ERE_PLUGIN_PREFIX . 'single-invoice', ERE_PLUGIN_URL . '/public/assets/css/single-invoice.css',array(),ERE_PLUGIN_VER);
            wp_register_style(ERE_PLUGIN_PREFIX . 'invoice-print-rtl', ERE_PLUGIN_URL . '/public/assets/css/invoice-print-rtl.css',array(),ERE_PLUGIN_VER);
        }

        public function register_assets_google_map() {
	        $googlemap_ssl = ere_get_option('googlemap_ssl', 0);
	        $googlemap_api_key = ere_get_option('googlemap_api_key', 'AIzaSyBqmFdSPp4-iY_BG14j_eUeLwOn9Oj4a4Q');
	        $googlemap_pin_cluster = ere_get_option('googlemap_pin_cluster', 1);
	        if (esc_html($googlemap_ssl) == 1 || is_ssl()) {
		        wp_register_script('google-map', 'https://maps-api-ssl.google.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key), array('jquery'), ERE_PLUGIN_VER, true);
	        } else {
		        wp_register_script('google-map', 'http://maps.googleapis.com/maps/api/js?libraries=places&language=' . get_locale() . '&key=' . esc_html($googlemap_api_key), array('jquery'), ERE_PLUGIN_VER, true);
	        }
	        if ($googlemap_pin_cluster != 0) {
		        wp_register_script('markerclusterer', ERE_PLUGIN_URL . 'public/assets/js/markerclusterer.min.js', array('jquery', 'google-map'), '2.1.1', true);
	        }
        }

        /**
         * @return bool
         */
        function is_property_taxonomy()
        {
            return is_tax(get_object_taxonomies('property'));
        }

        /**
         * @return bool
         */
        function is_agent_taxonomy()
        {
            return is_tax(get_object_taxonomies('agent'));
        }

        /**
         * @param $template
         * @return string
         */
        public function template_loader($template)
        {

            $find = array();
            $file = '';

            if (is_embed()) {
                return $template;
            }

            if (is_single() && (get_post_type() == 'property' || get_post_type() == 'agent' || get_post_type() == 'invoice')) {
                if (get_post_type() == 'property') {
                    $file = 'single-property.php';
                }
                if (get_post_type() == 'agent') {
                    $file = 'single-agent.php';
                }
                if (get_post_type() == 'invoice') {
                    $file = 'single-invoice.php';
                }
                $find[] = $file;
                $find[] = ERE()->template_path() . $file;

            } elseif ($this->is_property_taxonomy()) {

                $term = get_queried_object();

                if (is_tax('property-type') || is_tax('property-status') || is_tax('property-feature') || is_tax('property-city') || is_tax('property-state') || is_tax('property-label') || is_tax('property-neighborhood')) {
                    $file = 'taxonomy-' . $term->taxonomy . '.php';
                } else {
                    $file = 'archive-property.php';
                }

                $find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = ERE()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = ERE()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = $file;
                $find[] = ERE()->template_path() . $file;

            } elseif ($this->is_agent_taxonomy()) {

                $term = get_queried_object();

                if (is_tax('agency')) {
                    $file = 'taxonomy-' . $term->taxonomy . '.php';
                } else {
                    $file = 'archive-agent.php';
                }

                $find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = ERE()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
                $find[] = 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = ERE()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
                $find[] = $file;
                $find[] = ERE()->template_path() . $file;

            } elseif (is_post_type_archive('property') || is_page('properties')) {
                $file = 'archive-property.php';
                $find[] = $file;
                $find[] = ERE()->template_path() . $file;

            } elseif (is_post_type_archive('agent') || is_page('agents')) {

                $file = 'archive-agent.php';
                $find[] = $file;
                $find[] = ERE()->template_path() . $file;
            } elseif (is_author()) {
                $file = 'author.php';
                $find[] = $file;
                $find[] = ERE()->template_path() . $file;
            }

            if ($file) {
                $template = locate_template(array_unique($find));
                if (!$template) {
                    $template = ERE_PLUGIN_DIR . '/public/templates/' . $file;
                }
            }


            return $template;
        }

        /**
         * @param $query
         * @return mixed
         */
        public function set_posts_per_page($query)
        {
            global $wp_the_query;
            if ((!is_admin()) && ($query === $wp_the_query) && ($query->is_archive() || $query->is_tax())) {
                if (is_post_type_archive('agent')) {
                    $archive_agent_item_amount = ere_get_option('archive_agent_item_amount', 12);
                    $query->set('posts_per_page', $archive_agent_item_amount);
                } elseif (is_post_type_archive('property') || is_tax('property-type') || is_tax('property-status') || is_tax('property-feature')
                    || is_tax('property-label') || is_tax('property-state') || is_tax('property-city') || is_tax('property-neighborhood')) {
                    $custom_property_items_amount = ere_get_option('archive_property_items_amount', 6);
                    $query->set('posts_per_page', $custom_property_items_amount);
                }
            }
            return $query;
        }

        public function print_tmpl_template() {
	        ere_get_template('global/tmpl-template.php');
        }
    }
}