<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin')) {
    /**
     * Class ERE_Admin
     */
    class ERE_Admin
    {
        /**
         * Check if it is a property edit page.
         * @return bool
         */
        public function is_ere_admin()
        {
            if (is_admin()) {
                global $pagenow;
                if (in_array($pagenow, array('edit.php', 'post.php', 'post-new.php','edit-tags.php'))) {
                    global $post_type;
                    if (('property' == $post_type) || ('agent' == $post_type) || ('package' == $post_type) || ('user_package' == $post_type) || ('invoice' == $post_type)) {
                        return true;
                    }
                }
	            if (($pagenow === 'admin.php') && isset($_GET['page']) && ($_GET['page'] === 'ere_options')) {
					return true;
	            }
            }
            return false;
        }
        /**
         * Register the stylesheets for the admin area.
         */
        public function enqueue_styles()
        {
            $min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
            wp_enqueue_style(ERE_PLUGIN_PREFIX . 'admin-css', ERE_PLUGIN_URL . 'admin/assets/css/admin' . $min_suffix . '.css', array(), ERE_PLUGIN_VER, 'all');
            if (isset($_GET['page']) && (($_GET['page'] == 'ere_setup') || ($_GET['page'] == 'ere_welcome'))) {
                wp_enqueue_style(ERE_PLUGIN_PREFIX . 'setup_css', ERE_PLUGIN_URL . 'admin/assets/css/setup' . $min_suffix . '.css', array('dashicons'), ERE_PLUGIN_VER, 'all');
            }
            if($this->is_ere_admin())
            {
                $enable_filter_location = ere_get_option('enable_filter_location', 0);
                if($enable_filter_location==1)
                {
                    //select2
                    wp_enqueue_style('select2', ERE_PLUGIN_URL . 'public/assets/packages/select2/css/select2.min.css', array(), '4.0.6-rc.1', 'all');
                }
            }
        }

        /**
         * Register the JavaScript for the admin area.
         */
        public function enqueue_scripts()
        {
            $min_suffix = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
            wp_enqueue_script('jquery-tipTip', ERE_PLUGIN_URL . 'admin/assets/js/jquery-tiptip/jquery.tipTip' . $min_suffix . '.js', array('jquery', 'jquery-ui-sortable'), '1.3', true);
            if($this->is_ere_admin())
            {
                $enable_filter_location = ere_get_option('enable_filter_location', 0);
                if($enable_filter_location==1)
                {
                    wp_enqueue_script('select2', ERE_PLUGIN_URL . 'public/assets/packages/select2/js/select2.full.min.js', array('jquery'), '4.0.6-rc.1', true);
                }
                wp_enqueue_script(ERE_PLUGIN_PREFIX . 'admin-js', ERE_PLUGIN_URL . 'admin/assets/js/ere-admin' . $min_suffix . '.js', array('jquery'), ERE_PLUGIN_VER, true);
                wp_localize_script(ERE_PLUGIN_PREFIX . 'admin-js', 'ere_admin_vars',
                    array(
                        'ajax_url' => ERE_AJAX_URL,
                        'enable_filter_location'=>$enable_filter_location
                    )
                );
            }
        }

        /**
         * Get default directory image
         * @param $args
         * @return array
         */
        public function image_default_dir($args)
        {
            return array('url' => ERE_PLUGIN_URL . 'admin/assets/images/nearby-places/', 'dir' => ERE_PLUGIN_DIR . 'admin/assets/images/nearby-places/');
        }

        /**
         * Get user_package capabilities
         * @return mixed
         */
        private function get_user_package_capabilities()
        {
            $caps = array(
                'create_posts' => 'do_not_allow',
                'edit_post' => 'edit_user_packages',
                'delete_posts' => 'delete_user_packages'
            );
            return apply_filters('get_user_package_capabilities', $caps);
        }

        /**
         * Get invoice capabilities
         * @return mixed
         */
        private function get_invoice_capabilities()
        {
            $caps = array(
                'create_posts' => 'do_not_allow',
                'edit_post' => 'edit_invoices',
                'delete_posts' => 'delete_invoices'
            );
            return apply_filters('get_invoice_capabilities', $caps);
        }

        private function get_trans_log_capabilities()
        {
            $caps = array(
                'create_posts' => 'do_not_allow',
                'edit_post' => 'edit_trans_logs',
                'delete_posts' => 'delete_trans_logs'
            );
            return apply_filters('get_trans_log_capabilities', $caps);
        }

        /**
         * Register property post status
         */
        public function register_post_status()
        {
            register_post_status('expired', array(
                'label' => _x('Expired', 'post status', 'essential-real-estate'),
                'public' => true,
                'protected' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'essential-real-estate'),
            ));
            register_post_status('hidden', array(
                'label' => _x('Hidden', 'post status', 'essential-real-estate'),
                'public' => true,
                'protected' => true,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Hidden <span class="count">(%s)</span>', 'Hidden <span class="count">(%s)</span>', 'essential-real-estate'),
            ));
        }

        /**
         * Register post_type
         * @param $post_types
         * @return mixed
         */
        public function register_post_type($post_types)
        {
            $post_types ['property'] = apply_filters('ere_register_post_type_property', array(
                'label' => esc_html__('Properties', 'essential-real-estate'),
                'singular_name' => esc_html__('Property', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_slug', 'property'),
                ),
                'supports' => array('title', 'editor', 'author', 'thumbnail', 'revisions', 'page-attributes', 'comments'),
                'menu_icon' => 'dashicons-building',
                'can_export' => true,
                'capability_type' => 'property',
                'map_meta_cap' => true,
            ));
            $post_types ['agent'] = apply_filters('ere_register_post_type_agent', array(
                'label' => esc_html__('Agents', 'essential-real-estate'),
                'singular_name' => esc_html__('Agent', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_agent_slug', 'agent'),
                ),
                'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', 'revisions', 'comments'),
                'menu_icon' => 'dashicons-businessman',
                'can_export' => true,
                'capability_type' => 'agent',
                'map_meta_cap' => true
            ));
            $post_types ['package'] = apply_filters('ere_register_post_type_package', array(
                'label' => esc_html__('Packages', 'essential-real-estate'),
                'singular_name' => esc_html__('Package', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_package_slug', 'package'),
                ),
                'supports' => array('title'),
                'menu_icon' => 'dashicons-editor-table',
                'capability_type' => 'package',
                'map_meta_cap' => true,
                'exclude_from_search' => true,
                'public'              => false,
            ));
            $post_types ['user_package'] = apply_filters('ere_register_post_type_user_package', array(
                'label' => esc_html__('User Packages', 'essential-real-estate'),
                'singular_name' => esc_html__('User Packages', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_user_package_slug', 'user_package'),
                ),
                'supports' => array('title', 'excerpt'),
                'menu_icon' => 'dashicons-money',
                'can_export' => true,
                'capabilities' => $this->get_user_package_capabilities(),
                'map_meta_cap' => true,
                'exclude_from_search' => true,
                'public'              => false,
            ));
            $post_types ['invoice'] = apply_filters('ere_register_post_type_invoice', array(
                'label' => esc_html__('Invoices', 'essential-real-estate'),
                'singular_name' => esc_html__('Invoice', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_invoice_slug', 'invoice'),
                ),
                'supports' => array('title', 'excerpt'),
                'menu_icon' => 'dashicons-list-view',
                'capabilities' => $this->get_invoice_capabilities(),
                'map_meta_cap' => true,
                'exclude_from_search' => true,
                'public'              => false,
            ));
            $post_types ['trans_log'] = apply_filters('ere_register_post_type_trans_log', array(
                'label' => esc_html__('Transaction logs', 'essential-real-estate'),
                'singular_name' => esc_html__('Transaction logs', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_trans_log_slug', 'trans_log'),
                ),
                'supports' => array('title', 'excerpt'),
                'menu_icon' => 'dashicons-media-text',
                'can_export' => true,
                'capabilities' => $this->get_trans_log_capabilities(),
                'map_meta_cap' => true,
                'exclude_from_search' => true,
                'public'              => false,
            ));
            return apply_filters('ere_register_post_type', $post_types);
        }

        function additional_details_field($meta_prefix) {
	        if (!class_exists('GSF_SmartFramework')) {
        		return array(
			        'id' => "{$meta_prefix}additional_features",
			        'title' => esc_html__('Additional details:', 'essential-real-estate'),
			        'type' => 'custom',
			        'default' => array(),
			        'template' => ERE_PLUGIN_DIR . '/admin/templates/additional-details-field.php',
		        );
	        }
	        return array(
		        'id' => "{$meta_prefix}additional_features",
		        'type' => 'repeater',
		        'title' => esc_html__('Additional details:', 'essential-real-estate'),
		        'col' => '6',
		        'sort' => true,
		        'fields' => array(
			        array(
				        'id' => "{$meta_prefix}additional_feature_title",
				        'title' => esc_html__('Title:', 'essential-real-estate'),
				        'desc' => esc_html__('Enter additional title', 'essential-real-estate'),
				        'type' => 'text',
				        'default' => '',
				        'col' => '5',
			        ),
			        array(
				        'id' => "{$meta_prefix}additional_feature_value",
				        'title' => esc_html__('Value', 'essential-real-estate'),
				        'desc' => esc_html__('Enter additional value', 'essential-real-estate'),
				        'type' => 'text',
				        'default' => '',
				        'col' => '7',
			        ),
		        )
	        );

        }

        /**
         * Register meta boxes
         * @param $configs
         * @return mixed
         */
        public function register_meta_boxes($configs)
        {
            $meta_prefix = ERE_METABOX_PREFIX;
            $measurement_units = ere_get_measurement_units();
            $measurement_units_land_area = ere_get_measurement_units_land_area();
            $dec_point = ere_get_price_decimal_separator();
            $format_number = '^[0-9]+([' . $dec_point . '][0-9]+)?$';
            $price_unit = array();
            $enable_price_unit = ere_get_option('enable_price_unit', '1');
            $price_short_col = '6';
            if ($enable_price_unit == '1') {
                $price_short_col = '3';
                $price_unit = array(
                    'id' => "{$meta_prefix}property_price_unit",
                    'title' => esc_html__('Price Unit', 'essential-real-estate'),
                    'type' => 'button_set',
                    'options' => array(
                        '1' => esc_html__('None', 'essential-real-estate'),
                        '1000' => esc_html__('Thousand', 'essential-real-estate'),
                        '1000000' => esc_html__('Million', 'essential-real-estate'),
                        '1000000000' => esc_html__('Billion', 'essential-real-estate'),
                    ),
                    'default' => '1',
                    'col' => '9',
                    'required' => array("{$meta_prefix}property_price_on_call", '=', '0'),
                );
            }
            $render_additional_fields = ere_render_additional_fields();
            $additional_fields = array();
            if (count($render_additional_fields) > 0) {
                $additional_fields = array(
                    array(
                        'id' => "{$meta_prefix}additional_fields_tab",
                        'title' => esc_html__('Additional Fields', 'essential-real-estate'),
                        'icon' => 'dashicons dashicons-welcome-add-page',
                        'fields' => $render_additional_fields
                    ),
                );
            }
            $configs['property_meta_boxes'] = apply_filters('ere_register_meta_boxes_property', array(
                'name' => esc_html__('Property Information', 'essential-real-estate'),
                'post_type' => array('property'),
                'layout'    => 'full',
                'section' => array_merge(
                    apply_filters('ere_register_meta_boxes_property_top', array()),
                    apply_filters('ere_register_meta_boxes_property_main',
                        array_merge(
                            array(
                                array(
                                    'id' => "{$meta_prefix}details_tab",
                                    'title' => esc_html__('Basic Infomation', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-admin-home',
                                    'fields' => array(
                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_price_short",
                                                    'title' => esc_html__('Price', 'essential-real-estate'),
                                                    'desc' => sprintf(esc_html__('Example Value: 12345%s05','essential-real-estate'),$dec_point),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
	                                                'format_value' => 'ere_format_localized_price',
                                                    'default' => '',
                                                    'col' => $price_short_col,
                                                    'required' => array("{$meta_prefix}property_price_on_call", '=', '0'),
                                                ),
                                                $price_unit
                                            )
                                        ),
                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_price_prefix",
                                                    'title' => esc_html__('Before Price Label', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: Start From', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'default' => '',
                                                    'required' => array("{$meta_prefix}property_price_on_call", '=', '0'),
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_price_postfix",
                                                    'title' => esc_html__('After Price Label', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: Per Month', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'default' => '',
                                                    'required' => array("{$meta_prefix}property_price_on_call", '=', '0'),
                                                ),
                                            )
                                        ),
                                        array(
                                            'type' => 'row',
                                            'col' => '12',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_price_on_call",
                                                    'title' => esc_html__('Price on Call ?', 'essential-real-estate'),
                                                    'type' => 'button_set',
                                                    'options' => array(
                                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                                        '0' => esc_html__('No', 'essential-real-estate'),
                                                    ),
                                                    'default' => '0',
                                                ),
                                            )
                                        ),
                                        array(
                                            'type' => 'divide'
                                        ),
                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_size",
                                                    'title' => wp_kses_post(sprintf(__('Size (%s)', 'essential-real-estate'),$measurement_units)) ,
                                                    'desc' => esc_html__('Example Value: 200', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_land",
                                                    'title' => wp_kses_post(sprintf(__('Land Area (%s)', 'essential-real-estate'), $measurement_units_land_area)),
                                                    'desc' => esc_html__('Example Value: 2000', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                            )
                                        ),

                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_rooms",
                                                    'title' => esc_html__('Rooms', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: 6', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_bedrooms",
                                                    'title' => esc_html__('Bedrooms', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: 4', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_bathrooms",
                                                    'title' => esc_html__('Bathrooms', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: 2', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                            )
                                        ),

                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_garage",
                                                    'title' => esc_html__('Garages', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: 1', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_garage_size",
                                                    'title' => wp_kses_post(sprintf(esc_html__('Garages Size (%s)', 'essential-real-estate'), $measurement_units)),
                                                    'type' => 'text',
                                                    'pattern' => "{$format_number}",
                                                    'default' => '',
                                                ),
                                            )
                                        ),
                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_year",
                                                    'title' => esc_html__('Year Built', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'default' => '',
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_identity",
                                                    'title' => esc_html__('Property ID', 'essential-real-estate'),
                                                    'desc' => esc_html__('Property ID will help to search property directly (default=postId)', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'default' => '',
                                                ),
                                            )
                                        ),
                                        array(
                                            'type' => 'divide'
                                        ),
                                        $this->additional_details_field($meta_prefix),
                                        array(
                                            'type' => 'divide'
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_featured",
                                            'title' => esc_html__('Mark this property as featured ?', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '0',
                                        ),
                                    )
                                )
                            ),
                            $additional_fields,
                            array(
	                            "{$meta_prefix}location_tab" => array(
                                    'id' => "{$meta_prefix}location_tab",
                                    'title' => esc_html__('Location', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-location',
                                    'fields' => array(
                                        array(
                                            'type' => 'row',
                                            'col' => '6',
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}property_address",
                                                    'title' => esc_html__('Property Address', 'essential-real-estate'),
                                                    'desc' => esc_html__('Full Address', 'essential-real-estate'),
                                                    'type' => 'text',
                                                ),
                                                array(
                                                    'id' => "{$meta_prefix}property_zip",
                                                    'title' => esc_html__('Zip', 'essential-real-estate'),
                                                    'type' => 'text',
                                                ),
                                            )
                                        ),
	                                    "{$meta_prefix}property_location" => array(
                                            'id' => "{$meta_prefix}property_location",
                                            'title' => esc_html__('Property Location at Google Map', 'essential-real-estate'),
                                            'desc' => esc_html__('Drag the google map marker to point your property location. You can also use the address field above to search for your property', 'essential-real-estate'),
                                            'type' => 'map',
	                                        'default' => ere_get_option('googlemap_coordinate_default', '-33.868419, 151.193245'),
                                            'address_field' => "{$meta_prefix}property_address",
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}floors_tab",
                                    'title' => esc_html__('Floor Plans', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-grid-view',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}floors_enable",
                                            'title' => esc_html__('Enable Floors', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '0',
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}floors",
                                            'type' => 'panel',
                                            'title' => esc_html__('Floor', 'essential-real-estate'),
                                            'sort' => true,
                                            'required' => array("{$meta_prefix}floors_enable", '=', '1'),
                                            'fields' => array(
                                                array(
                                                    'id' => "{$meta_prefix}floor_name",
                                                    'title' => esc_html__('Floor Name', 'essential-real-estate'),
                                                    'desc' => esc_html__('Example Value: Ground Floor', 'essential-real-estate'),
                                                    'type' => 'text',
                                                    'default' => '',
                                                    'panel_title' => true,
                                                ),
                                                array(
                                                    'type' => 'row',
                                                    'col' => '6',
                                                    'fields' => array(
                                                        array(
                                                            'id' => "{$meta_prefix}floor_price",
                                                            'title' => esc_html__('Floor Price', 'essential-real-estate'),
                                                            'desc' => esc_html__('Example Value: 4200', 'essential-real-estate'),
                                                            'type' => 'text',
                                                            'pattern' => "{$format_number}",
                                                            'default' => '',
                                                        ),
                                                        array(
                                                            'id' => "{$meta_prefix}floor_price_postfix",
                                                            'title' => esc_html__('Price Postfix', 'essential-real-estate'),
                                                            'desc' => esc_html__('Example Value: Per Month', 'essential-real-estate'),
                                                            'type' => 'text',
                                                            'default' => '',
                                                        ),
                                                    )
                                                ),
                                                array(
                                                    'type' => 'row',
                                                    'col' => '6',
                                                    'fields' => array(
                                                        array(
                                                            'id' => "{$meta_prefix}floor_size",
                                                            'title' => esc_html__('Floor Size', 'essential-real-estate'),
                                                            'desc' => esc_html__('Example Value: 4200', 'essential-real-estate'),
                                                            'type' => 'text',
                                                            'pattern' => "{$format_number}",
                                                            'default' => '',
                                                        ),
                                                        array(
                                                            'id' => "{$meta_prefix}floor_size_postfix",
                                                            'title' => esc_html__('Size Postfix', 'essential-real-estate'),
                                                            'desc' => esc_html__('Example Value: Sq Ft', 'essential-real-estate'),
                                                            'type' => 'text',
                                                            'default' => '',
                                                        ),
                                                    )
                                                ),

                                                array(
                                                    'type' => 'row',
                                                    'col' => '6',
                                                    'fields' => array(
                                                        array(
                                                            'id' => "{$meta_prefix}floor_bedrooms",
                                                            'title' => esc_html__('Bedrooms', 'essential-real-estate'),
                                                            'desc' => esc_html__('Example Value: 4', 'essential-real-estate'),
                                                            'type' => 'text',
                                                            'pattern' => "{$format_number}",
                                                            'default' => '',
                                                        ),
                                                        array(
                                                            'id' => "{$meta_prefix}floor_bathrooms",
                                                            'title' => esc_html__('Bathrooms', 'essential-real-estate'),
                                                            'desc' => esc_html__('Example Value: 2', 'essential-real-estate'),
                                                            'type' => 'text',
                                                            'pattern' => "{$format_number}",
                                                            'default' => '',
                                                        ),
                                                    )
                                                ),

                                                array(
                                                    'id' => "{$meta_prefix}floor_description",
                                                    'title' => esc_html__('Description', 'essential-real-estate'),
                                                    'type' => 'textarea',
                                                    'default' => '',
                                                ),

                                                array(
                                                    'id' => "{$meta_prefix}floor_image",
                                                    'title' => esc_html__('Floor Plan Image', 'essential-real-estate'),
                                                    'type' => 'image',
                                                ),
                                            )
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}gallery_tab",
                                    'title' => esc_html__('Gallery Images', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-format-gallery',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}property_images",
                                            'title' => esc_html__('Property Gallery Images', 'essential-real-estate'),
                                            'type' => 'gallery',
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}documents_tab",
                                    'title' => esc_html__('File Attachments', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-media-default',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}property_attachments",
                                            'title' => esc_html__('File Attachments', 'essential-real-estate'),
                                            'type' => 'file',
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}video_tab",
                                    'title' => esc_html__('Property Video', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-video-alt3',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}property_video_url",
                                            'title' => esc_html__('Video URL', 'essential-real-estate'),
                                            'desc' => esc_html__('Input only URL. YouTube, Vimeo', 'essential-real-estate'),
                                            'type' => 'text',
                                            'col' => 12,
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_video_image",
                                            'title' => esc_html__('Video Image', 'essential-real-estate'),
                                            'type' => 'gallery',
                                            'col' => 12,
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}virtual_tour_tab",
                                    'title' => esc_html__('Virtual Tour', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-format-image',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}property_virtual_tour_type",
                                            'title' => esc_html__('Virtual Tour', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Embeded code', 'essential-real-estate'),
                                                '0' => esc_html__('Upload image', 'essential-real-estate'),
                                            ),
                                            'default' => '0',
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_virtual_tour",
                                            'title' => esc_html__('Enter virtual tour embeded code', 'essential-real-estate'),
                                            'type' => 'textarea',
                                            'default' => '',
                                            'required' => array("{$meta_prefix}property_virtual_tour_type", '=', '1')
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_image_360",
                                            'title' => esc_html__('Property Image 360', 'essential-real-estate'),
                                            'type' => 'image',
                                            'required' => array("{$meta_prefix}property_virtual_tour_type", '=', '0'),
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}agent_tab",
                                    'title' => esc_html__('Agent', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-admin-users',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}agent_display_option",
                                            'title' => esc_html__('What to display in contact information box ?', 'essential-real-estate'),
                                            'type' => 'radio',
                                            'options' => array(
                                                'author_info' => esc_html__('Author information', 'essential-real-estate'),
                                                'agent_info' => esc_html__('Agent Information. (Select the agent below)', 'essential-real-estate'),
                                                'other_info' => esc_html__('Other contact', 'essential-real-estate'),
                                                'no' => esc_html__('Hide contact information', 'essential-real-estate'),
                                            ),
                                            'default' => 'agent_info',
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_agent",
                                            'title' => esc_html__('Agent:', 'essential-real-estate'),
                                            'type' => 'selectize',
                                            'multiple' => false,
                                            'data' => 'agent',
                                            'data_args' => array(
                                                'numberposts' => -1,
                                            ),
                                            'required' => array("{$meta_prefix}agent_display_option", '=', 'agent_info')
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_other_contact_name",
                                            'title' => esc_html__('Other contact Name', 'essential-real-estate'),
                                            'type' => 'text',
                                            'default' => '',
                                            'required' => array("{$meta_prefix}agent_display_option", '=', 'other_info')
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_other_contact_mail",
                                            'title' => esc_html__('Other contact Email', 'essential-real-estate'),
                                            'type' => 'text',
                                            'default' => '',
                                            'required' => array("{$meta_prefix}agent_display_option", '=', 'other_info')
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_other_contact_phone",
                                            'title' => esc_html__('Other contact Phone', 'essential-real-estate'),
                                            'type' => 'text',
                                            'default' => '',
                                            'required' => array("{$meta_prefix}agent_display_option", '=', 'other_info')
                                        ),
                                        array(
                                            'id' => "{$meta_prefix}property_other_contact_description",
                                            'title' => esc_html__('Other contact more info', 'essential-real-estate'),
                                            'type' => 'textarea',
                                            'default' => '',
                                            'required' => array("{$meta_prefix}agent_display_option", '=', 'other_info')
                                        ),
                                    )
                                ),
                                array(
                                    'id' => "{$meta_prefix}private_note_tab",
                                    'title' => esc_html__('Private Note', 'essential-real-estate'),
                                    'icon' => 'dashicons dashicons-testimonial',
                                    'fields' => array(
                                        array(
                                            'id' => "{$meta_prefix}private_note",
                                            'title' => esc_html__('Private Note', 'essential-real-estate'),
                                            'desc' => esc_html__('Create a private note for this property, it will not be displayed to public', 'essential-real-estate'),
                                            'type' => 'textarea',
                                        ),
                                    )
                                )
                            )
                        )
                    ),
                    apply_filters('ere_register_meta_boxes_property_bottom', array())
                )
            ));
            $configs['agent_meta_boxes'] = apply_filters('ere_register_meta_boxes_agent', array(
                'name' => esc_html__('Agent Information', 'essential-real-estate'),
                'post_type' => array('agent'),
                'layout'    => 'full',
                'section' => array_merge(
                    apply_filters('ere_register_meta_boxes_agent_top', array()),
                    apply_filters('ere_register_meta_boxes_agent_main', array(
                            array(
                                'id' => "{$meta_prefix}agent_general_tab",
                                'title' => esc_html__('Basic Infomation', 'essential-real-estate'),
                                'icon' => 'dashicons dashicons-businessman',
                                'fields' => array(
                                    array(
                                        'type' => 'row',
                                        'col' => '12',
                                        'fields' => array(
                                            array(
                                                'id' => "{$meta_prefix}agent_description",
                                                'title' => esc_html__('Description', 'essential-real-estate'),
                                                'type' => 'editor',
                                            ),
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'id' => "{$meta_prefix}agent_position",
                                                'title' => esc_html__('Position', 'essential-real-estate'),
                                                'type' => 'text',
                                            ),

                                            array(
                                                'id' => "{$meta_prefix}agent_email",
                                                'title' => esc_html__('Email', 'essential-real-estate'),
                                                'type' => 'text',
                                                'input_type' => 'email',
                                            ),
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => esc_html__('Mobile Number', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_mobile_number",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => esc_html__('Fax Number', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_fax_number",
                                                'type' => 'text',
                                            ),
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('Company Name', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_company",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => __('Office Number', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_office_number",
                                                'type' => 'text',
                                            ),
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('Office Address', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_office_address",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => __('Website', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_website_url",
                                                'type' => 'text',
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('Licenses', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_licenses",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => __('Skype', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_skype",
                                                'type' => 'text',
                                            ),
                                        )
                                    ),
                                )
                            ),
                            array(
                                'id' => "{$meta_prefix}agent_social_profiles_tab",
                                'title' => esc_html__('Social Profiles', 'essential-real-estate'),
                                'icon' => 'dashicons dashicons-share',
                                'fields' => array(
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('Facebook URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_facebook_url",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => __('Twitter URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_twitter_url",
                                                'type' => 'text',
                                            ),
                                        )
                                    ),

                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('Pinterest URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_pinterest_url",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => __('Instagram URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_instagram_url",
                                                'type' => 'text',
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('Vimeo URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_vimeo_url",
                                                'type' => 'text',
                                            ),
                                            array(
                                                'title' => __('Youtube URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_youtube_url",
                                                'type' => 'text',
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'row',
                                        'col' => '6',
                                        'fields' => array(
                                            array(
                                                'title' => __('LinkedIn URL', 'essential-real-estate'),
                                                'id' => "{$meta_prefix}agent_linkedin_url",
                                                'type' => 'text',
                                            ),
                                        )
                                    ),
                                )
                            )
                        )
                    ),
                    apply_filters('ere_register_meta_boxes_agent_bottom', array())
                ),
            ));
            $configs['package_meta_boxes'] = apply_filters('ere_register_meta_boxes_package', array(
                'name' => esc_html__('Package Setting', 'essential-real-estate'),
                'post_type' => array('package'),
                'layout'    => 'full',
                'fields' => array_merge(
                    apply_filters('ere_register_meta_boxes_package_top', array()),
                    apply_filters('ere_register_meta_boxes_package_main', array(
                        array(
                            'type' => 'row',
                            'col' => '4',
                            'fields' => array(
                                array(
                                    'id' => "{$meta_prefix}package_free",
                                    'title' => esc_html__('Free package', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => "{$meta_prefix}package_price",
                                    'title' => esc_html__('Package Price', 'essential-real-estate'),
                                    'type' => 'text',
                                    'required' => array("{$meta_prefix}package_free", '=', '0'),
                                ),
                            )
                        ),
                        array(
                            'type' => 'divide'
                        ),
                        array(
                            'type' => 'row',
                            'col' => '4',
                            'fields' => array(
                                array(
                                    'id' => "{$meta_prefix}package_unlimited_time",
                                    'title' => esc_html__('Unlimited time', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => "{$meta_prefix}package_time_unit",
                                    'title' => esc_html__('Time Unit', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        'Day' => esc_html__('Day', 'essential-real-estate'),
                                        'Week' => esc_html__('Week', 'essential-real-estate'),
                                        'Month' => esc_html__('Month', 'essential-real-estate'),
                                        'Year' => esc_html__('Year', 'essential-real-estate'),
                                    ),
                                    'default' => 'Day',
                                    'required' => array("{$meta_prefix}package_unlimited_time", '=', '0'),
                                ),
                                array(
                                    'id' => "{$meta_prefix}package_period",
                                    'title' => esc_html__('Number Of "Time Unit"', 'essential-real-estate'),
                                    'type' => 'text',
                                    'default' => '1',
                                    'pattern' => '[0-9]*',
                                    'required' => array("{$meta_prefix}package_unlimited_time", '=', '0'),
                                ),
                            )
                        ),
                        array(
                            'type' => 'divide'
                        ),
                        array(
                            'type' => 'row',
                            'col' => '4',
                            'fields' => array(
                                array(
                                    'id' => "{$meta_prefix}package_unlimited_listing",
                                    'title' => esc_html__('Unlimited listings', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => "{$meta_prefix}package_number_listings",
                                    'title' => esc_html__('Number Listings', 'essential-real-estate'),
                                    'type' => 'text',
                                    'default' => '',
                                    'pattern' => '[0-9]*',
                                    'required' => array("{$meta_prefix}package_unlimited_listing", '=', '0'),
                                ),
                            )
                        ),
                        array(
                            'type' => 'divide'
                        ),
                        array(
                            'type' => 'row',
                            'col' => '4',
                            'fields' => array(
                                array(
                                    'id' => "{$meta_prefix}package_number_featured",
                                    'title' => esc_html__('Number Featured Listings', 'essential-real-estate'),
                                    'type' => 'text',
                                    'default' => '',
                                    'pattern' => '[0-9]*',
                                ),
                                array(
                                    'id' => "{$meta_prefix}package_order_display",
                                    'title' => esc_html__('Order Number Display Via Frontend', 'essential-real-estate'),
                                    'type' => 'text',
                                    'default' => '1',
                                    'pattern' => '[0-9]*',
                                ),
                            )
                        ),
                        array(
                            'type' => 'row',
                            'col' => '4',
                            'fields' => array(
                                array(
                                    'id' => "{$meta_prefix}package_featured",
                                    'title' => esc_html__('Is Featured?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => "{$meta_prefix}package_visible",
                                    'title' => esc_html__('Is Visible?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_meta_boxes_package_bottom', array())
                ),
            ));
            return apply_filters('ere_register_meta_boxes', $configs);
        }

        /**
         * Register taxonomy
         * @param $taxonomies
         * @return mixed
         */
        public function register_taxonomy($taxonomies)
        {
            $taxonomies['property-type'] = apply_filters('ere_register_taxonomy_property_type', array(
                'post_type' => 'property',
                'hierarchical' => true,
                'label' => esc_html__('Property Type', 'essential-real-estate'),
                'singular_name' => esc_html__('Property Type', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_type_slug', 'property-type'),
                ),
            ));
            $taxonomies['property-status'] = apply_filters('ere_register_taxonomy_property_status', array(
                'post_type' => 'property',
                'hierarchical' => true,
                'label' => esc_html__('Property Status', 'essential-real-estate'),
                'singular_name' => esc_html__('Property Status', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_status_slug', 'property-status'),
                ),
            ));
            $taxonomies['property-feature'] = apply_filters('ere_register_taxonomy_property_feature', array(
                'post_type' => 'property',
                'hierarchical' => true,
                'label' => esc_html__('Property Feature', 'essential-real-estate'),
                'singular_name' => esc_html__('Property Feature', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_feature_slug', 'property-feature'),
                ),
            ));
            $taxonomies['property-label'] = apply_filters('ere_register_taxonomy_property_label', array(
                'post_type' => 'property',
                'hierarchical' => true,
                'label' => esc_html__('Property Label', 'essential-real-estate'),
                'singular_name' => esc_html__('Property Label', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_label_slug', 'property-label'),
                ),
            ));
            $taxonomies['property-state'] = apply_filters('ere_register_taxonomy_property_state', array(
                'post_type' => 'property',
                'hierarchical' => false,
                'meta_box_cb' => array($this, 'taxonomy_select_meta_box'),
                'label' => esc_html__('Province / State', 'essential-real-estate'),
                'singular_name' => esc_html__('Province / State', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_state_slug', 'property-state'),
                ),
            ));
            $taxonomies['property-city'] = apply_filters('ere_register_taxonomy_property_city', array(
                'post_type' => 'property',
                'hierarchical' => false,
                'meta_box_cb' => array($this, 'taxonomy_select_meta_box'),
                'label' => esc_html__('City / Town', 'essential-real-estate'),
                'singular_name' => esc_html__('City / Town', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_city_slug', 'property-city'),
                ),
            ));
            $taxonomies['property-neighborhood'] = apply_filters('ere_register_taxonomy_property_neighborhood', array(
                'post_type' => 'property',
                'hierarchical' => false,
                'meta_box_cb' => array($this, 'taxonomy_select_meta_box'),
                'label' => esc_html__('Neighborhood', 'essential-real-estate'),
                'singular_name' => esc_html__('Neighborhood', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_property_neighborhood_slug', 'property-neighborhood'),
                ),
            ));
            $taxonomies['agency'] = apply_filters('ere_register_taxonomy_property_agency', array(
                'post_type' => 'agent',
                'hierarchical' => true,
                'label' => esc_html__('Agency', 'essential-real-estate'),
                'singular_name' => esc_html__('Agency', 'essential-real-estate'),
                'rewrite' => array(
                    'slug' => apply_filters('ere_agency_slug', 'agency'),
                ),
            ));
            return apply_filters('ere_register_taxonomy', $taxonomies);
        }
        /**
         * Remove taxonomy parent category
         */
        public function remove_taxonomy_parent_category()
        {
            if (!in_array($_GET['taxonomy'], array('property-type', 'property-status', 'property-label'))) {
                return;
            }
            $screen = get_current_screen();

            if ( 'edit-tags' == $screen->base ) {
                $parent = "$('label[for=parent]').parent()";
            } elseif ( 'term' == $screen->base ) {
                $parent = "$('label[for=parent]').parent().parent()";
            }
            ?>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    <?php echo esc_js($parent); ?>.remove();
                });
            </script>

            <?php

        }
        /**
         * taxonomy_select_meta_box
         */
        public function taxonomy_select_meta_box($post, $box)
        {
            $defaults = array('taxonomy' => 'category');

            if (!isset($box['args']) || !is_array($box['args']))
                $args = array();
            else
                $args = $box['args'];
            extract(wp_parse_args($args, $defaults), EXTR_SKIP);
            $tax = get_taxonomy($taxonomy);
            $selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
            $hierarchical = $tax->hierarchical;
            ?>
            <div id="taxonomy-<?php echo esc_attr($taxonomy); ?>" class="selectdiv ere-property-select-meta-box-wrap">
                <?php if (current_user_can($tax->cap->edit_terms)): ?>
                    <?php
                    $class = 'widefat';
                    if ($taxonomy == 'property-state') {
                        $class .= ' ere-property-state-ajax';
                    } elseif ($taxonomy == 'property-city') {
                        $class .= ' ere-property-city-ajax';
                    } elseif (($taxonomy == 'property-neighborhood')) {
                        $class .= ' ere-property-neighborhood-ajax';
                    }
                    if ($hierarchical) {
                        wp_dropdown_categories(array(
                            'taxonomy' => $taxonomy,
                            'class' => $class,
                            'hide_empty' => false,
                            'name' => "tax_input[$taxonomy][]",
                            'selected' => count($selected) >= 1 ? $selected[0] : '',
                            'orderby' => 'name',
                            'hierarchical' => false,
                            'show_option_all' => esc_html__('None', 'essential-real-estate')
                        ));
                    } else {
                        ?>
                        <select name="<?php echo "tax_input[$taxonomy][]"; ?>" class="<?php echo esc_attr($class); ?>"
                                data-selected="<?php echo ere_get_taxonomy_slug_by_post_id($post->ID, $taxonomy); ?>">
                            <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                            <?php
                            $terms = get_categories(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'parent' => 0
                                )
                            );
                            foreach ($terms as $term): ?>
                                <option
                                    value="<?php echo esc_attr($term->slug); ?>" <?php echo selected($term->term_id, count($selected) >= 1 ? $selected[0] : ''); ?>><?php echo esc_html($term->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php
                    }
                    ?>
                <?php endif; ?>
            </div>
            <?php
        }

        /**
         * Register term_meta
         * @param $configs
         * @return mixed
         */
        public function register_term_meta($configs)
        {
            $countries = ere_get_selected_countries();
            $default_country = ere_get_option('default_country', 'US');
            $configs['property-type-settings'] = apply_filters('ere_register_term_meta_property_type', array(
                'name' => esc_html__('Taxonomy Setting', 'essential-real-estate'),
                'layout' => 'horizontal',
                'taxonomy' => array('property-type'),
                'fields' => array(
                    array(
                        'id' => 'property_type_icon',
                        'title' => esc_html__('Icon image', 'essential-real-estate'),
                        'desc' => esc_html__('Icon display on map', 'essential-real-estate'),
                        'type' => 'image',
                        'default' => '',
                    ),
                )
            ));

            $configs['property-state-settings'] = apply_filters('ere_register_term_meta_property_state', array(
                'name' => '',
                'layout' => 'horizontal',
                'taxonomy' => array('property-state'),
                'fields' => array(
                    array(
                        'id' => 'property_state_country',
                        'title' => esc_html__('Country', 'essential-real-estate'),
                        'default' => $default_country,
                        'type' => 'select',
                        'options' => $countries,
                    ),
                )
            ));
            $configs['property-label-settings'] = apply_filters('ere_register_term_meta_property_label', array(
                'name' => '',
                'layout' => 'horizontal',
                'taxonomy' => array('property-label'),
                'fields' => array(
                    array(
                        'id' => 'property_label_color',
                        'title' => esc_html__('Background Color', 'essential-real-estate'),
                        'subtitle' => esc_html__('Set background color for label', 'essential-real-estate'),
                        'type' => 'color',
                        'default' => '#888',
                    ),
                )
            ));
            $configs['property-status-settings'] = apply_filters('ere_register_term_meta_property_status', array(
                'name' => '',
                'layout' => 'horizontal',
                'taxonomy' => array('property-status'),
                'fields' => array(
                    array(
                        'id' => 'property_status_color',
                        'title' => esc_html__('Background Color', 'essential-real-estate'),
                        'subtitle' => esc_html__('Set background color for label status', 'essential-real-estate'),
                        'type' => 'color',
                        'default' => '#888',
                    ),
                    array(
                        'title' => __('Order Number', 'essential-real-estate'),
                        'subtitle' => esc_html__('The number to set orderby', 'essential-real-estate'),
                        'id' => "property_status_order_number",
                        'type' => 'text',
                        'default' => '1',
                        'pattern' => '[0-9]*'
                    ),
                )
            ));
            $configs['agency-settings'] = apply_filters('ere_register_term_meta_agency', array(
                'name' => '',
                'layout' => 'horizontal',
                'taxonomy' => array('agency'),
                'fields' => array(
                    array(
                        'type' => 'row',
                        'col' => '12',
                        'fields' => array(
                            array(
                                'id' => 'agency_des',
                                'title' => esc_html__('Content', 'essential-real-estate'),
                                'type' => 'editor',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '12',
                        'fields' => array(
                            array(
                                'id' => 'agency_logo',
                                'title' => esc_html__('Logo', 'essential-real-estate'),
                                'type' => 'image',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '12',
                        'fields' => array(
                            array(
                                'title' => __('Address', 'essential-real-estate'),
                                'id' => "agency_address",
                                'type' => 'text',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '12',
                        'fields' => array(
                            array(
                                'title' => __('Licenses', 'essential-real-estate'),
                                'id' => "agency_licenses",
                                'type' => 'text',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '12',
                        'fields' => array(
                            array(
                                'id' => 'agency_map_address',
                                'title' => esc_html__('Google Map Address', 'essential-real-estate'),
                                'type' => 'map',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'id' => "agency_email",
                                'title' => esc_html__('Email', 'essential-real-estate'),
                                'type' => 'text',
                                'input_type' => 'email',
                            ),
                            array(
                                'title' => __('Mobile Number', 'essential-real-estate'),
                                'id' => "agency_mobile_number",
                                'type' => 'text',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'title' => __('Fax Number', 'essential-real-estate'),
                                'id' => "agency_fax_number",
                                'type' => 'text',
                            ),
                            array(
                                'title' => __('Office Number', 'essential-real-estate'),
                                'id' => "agency_office_number",
                                'type' => 'text',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'title' => __('Website', 'essential-real-estate'),
                                'id' => "agency_website_url",
                                'type' => 'text',
                            ),
                            array(
                                'title' => __('Vimeo URL', 'essential-real-estate'),
                                'id' => "agency_vimeo_url",
                                'type' => 'text',
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'title' => __('Facebook URL', 'essential-real-estate'),
                                'id' => "agency_facebook_url",
                                'type' => 'text',
                            ),
                            array(
                                'title' => __('Twitter URL', 'essential-real-estate'),
                                'id' => "agency_twitter_url",
                                'type' => 'text',
                            ),
                        )
                    ),

                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'title' => __('Pinterest URL', 'essential-real-estate'),
                                'id' => "agency_pinterest_url",
                                'type' => 'text',
                            ),
                            array(
                                'title' => __('Instagram URL', 'essential-real-estate'),
                                'id' => "agency_instagram_url",
                                'type' => 'text',
                            )
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'title' => __('Skype', 'essential-real-estate'),
                                'id' => "agency_skype",
                                'type' => 'text',
                            ),
                            array(
                                'title' => __('Youtube URL', 'essential-real-estate'),
                                'id' => "agency_youtube_url",
                                'type' => 'text',
                            )
                        )
                    ),
                    array(
                        'type' => 'row',
                        'col' => '6',
                        'fields' => array(
                            array(
                                'title' => __('LinkedIn URL', 'essential-real-estate'),
                                'id' => "agency_linkedin_url",
                                'type' => 'text',
                            ),
                        )
                    ),
                )
            ));
            return apply_filters('ere_register_term_meta', $configs);
        }

        /**
         * Register options config
         * @param $configs
         * @return mixed
         */
        public function register_options_config($configs)
        {
            $cities = array();
            $all_cities = get_categories(array('taxonomy' => 'property-city', 'hide_empty' => 0, 'orderby' => 'ASC'));
            if (is_array($all_cities)) {
                $cities[''] = esc_html__('None', 'essential-real-estate');
                foreach ($all_cities as $city) {
                    $cities[$city->slug] = $city->name;
                }
            }
            $configs[ERE_OPTIONS_NAME] = array(
                'layout' => 'horizontal',
                'page_title' => esc_html__('Real Estate Options', 'essential-real-estate'),
                'menu_title' => esc_html__('Real Estate Options', 'essential-real-estate'),
                'option_name' => ERE_OPTIONS_NAME,
                'permission' => 'edit_theme_options',
                'section' => array_merge(
                    apply_filters('ere_register_options_config_top', array()),
                    apply_filters('ere_register_options_config_main', array(
                        'ere_general_option' => $this->general_option($cities),
                        'ere_setup_page_option' => $this->setup_page_option(),
                        'ere_url_slugs_option' => $this->url_slugs_option(),
                        'ere_price_format_option' => $this->price_format_option(),
                        'ere_login_register_option' => $this->login_register_option(),
                        'ere_property_option' => $this->property_option(),
                        'ere_additional_fields_option' => $this->additional_fields_option(),
                        'ere_search_option' => $this->search_option(),
                        'ere_payment_option' => $this->payment_option(),
                        'ere_payment_complete_option' => $this->payment_complete_option(),
                        'ere_invoices_option' => $this->invoices_option(),
                        'ere_compare_option' => $this->compare_option(),
                        'ere_favorite_option' => $this->favorite_option(),
                        'ere_social_share_option' => $this->social_share_option(),
                        'ere_print_option' => $this->print_option(),
                        'ere_nearby_places_option' => $this->nearby_places_option(),
                        'ere_walk_score_option' => $this->walk_score_option(),
                        'ere_google_map_directions_option' => $this->google_map_directions_option(),
                        'ere_comments_reviews_option' => $this->comments_reviews_option(),
                        'ere_google_map_option' => $this->google_map_option(),
                        'ere_captcha_option' => $this->captcha_option(),
                        'ere_property_page_option' => $this->property_page_option(),
                        'ere_archive_agent' => $this->agent_page_option(),
                        'ere_agency_page_option' => $this->agency_page_option(),
                        'ere_email_management_option' => $this->email_management_option(),
                    )),
                    apply_filters('ere_register_options_config_bottom', array())
                )
            );
            return apply_filters('ere_register_options_config', $configs);
        }

        /**
         * @return mixed|void
         */
        private function general_option($cities = array())
        {
            $date_languages = array(
                'af' => 'Afrikaans',
                'ar' => 'Arabic',
                'ar-DZ' => 'Algerian',
                'az' => 'Azerbaijani',
                'be' => 'Belarusian',
                'bg' => 'Bulgarian',
                'bs' => 'Bosnian',
                'ca' => 'Catalan',
                'cs' => 'Czech',
                'cy-GB' => 'Welsh/UK',
                'da' => 'Danish',
                'de' => 'German',
                'el' => 'Greek',
                'en-AU' => 'English/Australia',
                'en-GB' => 'English/UK',
                'en-NZ' => 'English/New Zealand',
                'eo' => 'Esperanto',
                'es' => 'Spanish',
                'et' => 'Estonian',
                'eu' => 'Karrikas-ek',
                'fa' => 'Persian',
                'fi' => 'Finnish',
                'fo' => 'Faroese',
                'fr' => 'French',
                'fr-CA' => 'Canadian-French',
                'fr-CH' => 'Swiss-French',
                'gl' => 'Galician',
                'he' => 'Hebrew',
                'hi' => 'Hindi',
                'hr' => 'Croatian',
                'hu' => 'Hungarian',
                'hy' => 'Armenian',
                'id' => 'Indonesian',
                'ic' => 'Icelandic',
                'it' => 'Italian',
                'it-CH' => 'Italian-CH',
                'ja' => 'Japanese',
                'ka' => 'Georgian',
                'kk' => 'Kazakh',
                'km' => 'Khmer',
                'ko' => 'Korean',
                'ky' => 'Kyrgyz',
                'lb' => 'Luxembourgish',
                'lt' => 'Lithuanian',
                'lv' => 'Latvian',
                'mk' => 'Macedonian',
                'ml' => 'Malayalam',
                'ms' => 'Malaysian',
                'nb' => 'Norwegian',
                'nl' => 'Dutch',
                'nl-BE' => 'Dutch-Belgium',
                'nn' => 'Norwegian-Nynorsk',
                'no' => 'Norwegian',
                'pl' => 'Polish',
                'pt' => 'Portuguese',
                'pt-BR' => 'Brazilian',
                'rm' => 'Romansh',
                'ro' => 'Romanian',
                'ru' => 'Russian',
                'sk' => 'Slovak',
                'sl' => 'Slovenian',
                'sq' => 'Albanian',
                'sr' => 'Serbian',
                'sr-SR' => 'Serbian-i18n',
                'sv' => 'Swedish',
                'ta' => 'Tamil',
                'th' => 'Thai',
                'tj' => 'Tajiki',
                'tr' => 'Turkish',
                'uk' => 'Ukrainian',
                'vi' => 'Vietnamese',
                'zh-CN' => 'Chinese',
                'zh-HK' => 'Chinese-Hong-Kong',
                'zh-TW' => 'Chinese Taiwan',
            );
            return apply_filters('ere_register_option_general', array(
                'id' => 'ere_general_option',
                'title' => esc_html__('General', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-admin-multisite',
                'fields' => array_merge(
                    apply_filters('ere_register_option_general_top', array()),
                    apply_filters('ere_register_option_general_main', array(
                        array(
                            'id' => 'default_country',
                            'type' => 'select',
                            'title' => esc_html__('Country', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select default country', 'essential-real-estate'),
                            'options' => ere_get_selected_countries(),
                            'default' => 'US'
                        ),
                        array(
                            'id' => 'default_city',
                            'type' => 'select',
                            'title' => esc_html__('City / Town', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select default city', 'essential-real-estate'),
                            'options' => $cities,
                            'default' => '',
                        ),
                        array(
                            'id' => 'enable_filter_location',
                            'type' => 'button_set',
                            'title' => esc_html__('Enable Filter Location', 'essential-real-estate'),
                            'subtitle' => esc_html__('Enable/Disable filter Country, State, City, Neighborhood on Search form and Submit property page', 'essential-real-estate'),
                            'desc' => '',
                            'options' => array(
                                '1' => esc_html__('On', 'essential-real-estate'),
                                '0' => esc_html__('Off', 'essential-real-estate'),
                            ),
                            'default' => 0
                        ),
                        array(
                            'id' => 'date_language',
                            'type' => 'select',
                            'title' => esc_html__('Language for datepicker', 'essential-real-estate'),
                            'options' => $date_languages,
                            'default' => 'en-GB'
                        ),
                        array(
                            'id' => 'measurement_units',
                            'type' => 'select',
                            'title' => esc_html__('Measurement units for Property Size, Garage Size', 'essential-real-estate'),
                            'subtitle' => esc_html__('Choose Measurement units for Property Size, Garage Size', 'essential-real-estate'),
                            'options' => array(
                                'SqFt' => esc_html__('Square Feet (SqFt)', 'essential-real-estate'),
                                'm2' => esc_html__('Square Meters (m2)', 'essential-real-estate'),
                                'custom' => esc_html__('Custom Units', 'essential-real-estate'),
                            ),
                            'default' => 'SqFt'
                        ),
                        array(
                            'id' => 'custom_measurement_units',
                            'type' => 'text',
                            'required' => array('measurement_units', '=', 'custom'),
                            'title' => esc_html__('Custom Measurement Units', 'essential-real-estate'),
                            'default' => 'SqFt',
                        ),
                        array(
                            'id' => 'measurement_units_land_area',
                            'type' => 'select',
                            'title' => esc_html__('Measurement units for Land Area', 'essential-real-estate'),
                            'subtitle' => esc_html__('Choose Measurement units for Land Area', 'essential-real-estate'),
                            'options' => array(
                                'SqFt' => esc_html__('Square Feet (SqFt)', 'essential-real-estate'),
                                'm2' => esc_html__('Square Meters (m2)', 'essential-real-estate'),
                                'custom' => esc_html__('Custom Units', 'essential-real-estate'),
                            ),
                            'default' => 'SqFt'
                        ),
                        array(
                            'id' => 'custom_measurement_units_land_area',
                            'type' => 'text',
                            'required' => array('measurement_units_land_area', '=', 'custom'),
                            'title' => esc_html__('Custom Measurement Units for Land Area', 'essential-real-estate'),
                            'default' => 'SqFt',
                        ),
                        array(
                            'id' => 'ere_other_options',
                            'title' => esc_html__('Other Options', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'enable_rtl_mode',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Enable RTL mode', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enable/Disable RTL mode', 'essential-real-estate'),
                                    'desc' => '',
                                    'options' => array(
                                        '1' => esc_html__('On', 'essential-real-estate'),
                                        '0' => esc_html__('Off', 'essential-real-estate'),
                                    ),
                                    'default' => 0
                                ),
                                array(
                                    'id' => 'enable_min_js',
                                    'title' => esc_html__('Enable Mini File JS', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enable/Disable Mini File JS', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('On', 'essential-real-estate'),
                                        '0' => esc_html__('Off', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'enable_min_css',
                                    'title' => esc_html__('Enable Mini File CSS', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enable/Disable Mini File CSS', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('On', 'essential-real-estate'),
                                        '0' => esc_html__('Off', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'cdn_bootstrap_js',
                                    'type' => 'text',
                                    'title' => esc_html__('CDN Bootstrap Script', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Url CDN Bootstrap Script', 'essential-real-estate'),
                                    'desc' => '',
                                    'default' => '',
                                ),
                                array(
                                    'id' => 'cdn_bootstrap_css',
                                    'type' => 'text',
                                    'title' => esc_html__('CDN Bootstrap Stylesheet', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Url CDN Bootstrap Stylesheet', 'essential-real-estate'),
                                    'desc' => '',
                                    'default' => '',
                                ),
                                array(
                                    'id' => 'cdn_font_awesome',
                                    'type' => 'text',
                                    'title' => esc_html__('CDN Font Awesome', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Url CDN Font Awesome', 'essential-real-estate'),
                                    'desc' => '',
                                    'default' => '',
                                ),
                                array(
                                    'id' => 'enable_add_shortcode_tool',
                                    'title' => esc_html__('Enable Add Shortcode Tool', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enable/Disable Add Shortcode Tool For Editor', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('On', 'essential-real-estate'),
                                        '0' => esc_html__('Off', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_option_general_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function setup_page_option()
        {
        	$page_setup_config = ERE_Admin_Setup::get_page_setup_config();
        	$config = array();
        	foreach ($page_setup_config as $k => $v) {
		        $config[] = array(
			        'id' => "ere_{$k}_page_id",
			        'title' => $v['title'],
			        'type' => 'select',
			        'data' => 'page',
			        'data_args' => array(
				        'numberposts' => -1,
			        )
		        );
	        }
        	$config = apply_filters('ere_register_option_setup_page_main',$config);
	        return apply_filters('ere_register_option_setup_page', array(
		        'id' => 'ere_setup_page_option',
		        'title' => esc_html__('Setup Page', 'essential-real-estate'),
		        'icon' => 'dashicons dashicons-admin-page',
		        'fields' => array_merge(
			        apply_filters('ere_register_option_setup_page_top', array()),
			        $config,
			        apply_filters('ere_register_option_setup_page_bottom', array())
		        )
	        ));
        }

        /**
         * @return mixed|void
         */
        private function url_slugs_option()
        {
            return apply_filters('ere_register_option_url_slugs', array(
                'id' => 'ere_url_slugs_option',
                'title' => esc_html__('URL Slug', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-admin-links',
                'fields' => array_merge(
                    apply_filters('ere_register_option_url_slugs_top', array()),
                    apply_filters('ere_register_option_url_slugs_main', array(
                        array(
                            'id' => 'property_url_slug',
                            'title' => esc_html__('Property Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property',
                        ),
                        array(
                            'id' => 'property_type_url_slug',
                            'title' => esc_html__('Property Type Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-type',
                        ),
                        array(
                            'id' => 'property_status_url_slug',
                            'title' => esc_html__('Property Status Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-status',
                        ),
                        array(
                            'id' => 'property_feature_url_slug',
                            'title' => esc_html__('Property Feature Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-feature',
                        ),
                        array(
                            'id' => 'property_label_url_slug',
                            'title' => esc_html__('Property Label Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-label',
                        ),
                        array(
                            'id' => 'property_state_url_slug',
                            'title' => esc_html__('Province / State Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-state',
                        ),
                        array(
                            'id' => 'property_city_url_slug',
                            'title' => esc_html__('City Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-city',
                        ),
                        array(
                            'id' => 'property_neighborhood_url_slug',
                            'title' => esc_html__('Neighborhood Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'property-neighborhood',
                        ),
                        array(
                            'id' => 'agent_url_slug',
                            'title' => esc_html__('Agent Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'agent',
                        ),
                        array(
                            'id' => 'agency_url_slug',
                            'title' => esc_html__('Agency Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'agency',
                        ),
                        array(
                            'id' => 'author_url_slug',
                            'title' => esc_html__('Author Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'author',
                        ),
                        array(
                            'id' => 'package_url_slug',
                            'title' => esc_html__('Package Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'package',
                        ),
                        array(
                            'id' => 'user_package_url_slug',
                            'title' => esc_html__('Agent Packages Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'user_package',
                        ),
                        array(
                            'id' => 'invoice_url_slug',
                            'title' => esc_html__('Invoice Slug', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'invoice',
                        )
                    )),
                    apply_filters('ere_register_option_url_slugs_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function price_format_option()
        {
            return apply_filters('ere_register_option_price_format', array(
                'id' => 'ere_price_format_option',
                'title' => esc_html__('Price Format', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-money',
                'fields' => array_merge(
                    apply_filters('ere_register_option_price_format_top', array()),
                    apply_filters('ere_register_option_price_format_main', array(
                        array(
                            'id' => 'enable_price_unit',
                            'title' => esc_html__('Enable Price Unit', 'essential-real-estate'),
                            'subtitle' => esc_html__('Enable/Disable Price Unit: "Thousand, Million, Billion" on Property Submit form via backend and frontend', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'thousand_text',
                            'title' => esc_html__('Thousand Text', 'essential-real-estate'),
                            'subtitle' => esc_html__('K or Thousand', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'K',
                            'required' => array('enable_price_unit', '=', '1')
                        ),
                        array(
                            'id' => 'million_text',
                            'title' => esc_html__('Million Text', 'essential-real-estate'),
                            'subtitle' => esc_html__('M or Million', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'M',
                            'required' => array('enable_price_unit', '=', '1')
                        ),
                        array(
                            'id' => 'billion_text',
                            'title' => esc_html__('Billion Text', 'essential-real-estate'),
                            'subtitle' => esc_html__('B or Billion', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'B',
                            'required' => array('enable_price_unit', '=', '1')
                        ),
                        array(
                            'id' => 'currency_sign',
                            'title' => esc_html__('Currency Sign', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => '$',
                        ),
                        array(
                            'id' => 'currency_position',
                            'title' => esc_html__('Currency Sign Position', 'essential-real-estate'),
                            'type' => 'select',
                            'options' => array(
                                'before' => esc_html__('Before ($450,000)', 'essential-real-estate'),
                                'after' => esc_html__('After (450,000$)', 'essential-real-estate'),
                            ),
                            'default' => 'before',
                        ),
                        array(
                            'id' => 'thousand_separator',
                            'title' => esc_html__('Thousand Separator', 'essential-real-estate'),
                            'subtitle' => esc_html__('This sets the thousand separator of displayed prices.','essential-real-estate'),
                            'type' => 'text',
                            'default' => ',',
                        ),
                        array(
                            'id' => 'decimal_separator',
                            'title' => esc_html__('Decimal Separator', 'essential-real-estate'),
                            'subtitle' => esc_html__('This sets the decimal separator of displayed prices.','essential-real-estate'),
                            'type' => 'text',
                            'default' => '.',
                        ),
	                    array(
		                    'id' => 'number_of_decimals',
		                    'title' => esc_html__('Number of decimals', 'essential-real-estate'),
		                    'subtitle' => esc_html__('This sets the number of decimal points shown in displayed prices.','essential-real-estate'),
		                    'type' => 'text',
		                    'input_type' => 'number',
		                    'default' => '0',
	                    ),
                        array(
                            'id' => 'empty_price_text',
                            'title' => esc_html__('Price on Call Text', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => 'Price on call',
                        )
                    )),
                    apply_filters('ere_register_option_price_format_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function login_register_option()
        {
            return apply_filters('ere_register_option_login_register', array(
                'id' => 'ere_login_register_option',
                'title' => esc_html__('User & Agent', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-admin-network',
                'fields' => array_merge(
                    apply_filters('ere_register_option_login_register_top', array()),
                    apply_filters('ere_register_option_login_register_main', array(
                        array(
                            'id' => 'enable_submit_property_via_frontend',
                            'title' => esc_html__('Allow to submit property via frontend', 'essential-real-estate'),
                            'subtitle' => esc_html__('If "no", only allow to submit property via backend', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'user_can_submit',
                            'title' => esc_html__('All User can submit property', 'essential-real-estate'),
                            'subtitle' => esc_html__('If "no", only agent can submit property', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                            'required' => array('enable_submit_property_via_frontend', '=', '1')
                        ),
                        array(
                            'id' => 'user_as_agent',
                            'title' => esc_html__('User can register as agent', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                            'required' => array('enable_submit_property_via_frontend', '=', '1')
                        ),
                        array(
                            'id' => 'auto_approved_agent',
                            'title' => esc_html__('Automatically approved after user register as agent?', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                            'required' => array('user_as_agent', '=', '1')
                        ),
                        array(
                            'id' => 'enable_password',
                            'title' => esc_html__('Users can type Password on registration form', 'essential-real-estate'),
                            'subtitle' => esc_html__('If "no", users will get an auto generated password via email', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '0',
                        ),
                        array(
                            'id' => 'register_terms_condition',
                            'title' => esc_html__('Register Terms & Conditions', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select terms & conditions page', 'essential-real-estate'),
                            'type' => 'select',
                            'data' => 'page',
                            'data_args' => array(
                                'numberposts' => -1,
                            )
                        ),
                        array(
                            'id' => 'become_agent_terms_condition',
                            'title' => esc_html__('Become an agent Terms & Conditions', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select terms & conditions page', 'essential-real-estate'),
                            'type' => 'select',
                            'data' => 'page',
                            'data_args' => array(
                                'numberposts' => -1,
                            ),
                            'required' => array('user_as_agent', '=', '1')
                        ),
                        array(
                            'id' => 'enable_social_login',
                            'title' => esc_html__('Enable Social Login', 'essential-real-estate'),
                            'subtitle' => sprintf(__('Please activate %s WordPress Social Login %s plugin', 'essential-real-estate'),
                                '<a href="https://wordpress.org/plugins/wordpress-social-login/" target="_blank">',
                                '</a>'
                            ),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'enable_register_tab',
                            'title' => esc_html__('Enable Register tab on Login & Register popup', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'default_user_avatar',
                            'type' => 'image',
                            'url' => true,
                            'title' => esc_html__('Default User Avatar', 'essential-real-estate'),
                            'subtitle' => esc_html__('Display this if no user avatar', 'essential-real-estate'),
                            'default' => ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png'
                        ),
                        array(
                            'id' => 'section_user_info_hide_fields',
                            'title' => esc_html__('Hide User Information Fields', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'hide_user_info_fields',
                                    'type' => 'checkbox_list',
                                    'title' => esc_html__('Hide User Information Fields', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Choose which fields you want to hide on My Profile page?', 'essential-real-estate'),
                                    'options' => array(
                                        'user_company' => esc_html__('Company (For Agent)', 'essential-real-estate'),
                                        'user_position' => esc_html__('Position (For Agent)', 'essential-real-estate'),
                                        'user_office_number' => esc_html__('Office Number (For Agent)', 'essential-real-estate'),
                                        'user_office_address' => esc_html__('Office Address (For Agent)', 'essential-real-estate'),
                                        'user_licenses' => esc_html__('Licenses (For Agent)', 'essential-real-estate'),
                                        'user_fax_number' => esc_html__('Fax', 'essential-real-estate'),
                                        'user_website_url' => esc_html__('Website URL', 'essential-real-estate'),
                                        'user_skype' => esc_html__('Skype', 'essential-real-estate'),
                                        'user_facebook_url' => esc_html__('Facebook URL', 'essential-real-estate'),
                                        'user_twitter_url' => esc_html__('Twitter URL', 'essential-real-estate'),
                                        'user_linkedin_url' => esc_html__('Linkedin URL', 'essential-real-estate'),
                                        'user_instagram_url' => esc_html__('Instagram URL', 'essential-real-estate'),
                                        'user_pinterest_url' => esc_html__('Pinterest URL', 'essential-real-estate'),
                                        'user_youtube_url' => esc_html__('Youtube URL', 'essential-real-estate'),
                                        'user_vimeo_url' => esc_html__('Vimeo URL', 'essential-real-estate'),
                                    ),
                                    'value_inline' => false,
                                    'default' => array()
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_option_login_register_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function property_option()
        {
            return apply_filters('ere_register_option_property', array(
                'id' => 'ere_property_option',
                'title' => esc_html__('Property', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-building',
                'fields' => array_merge(
                    apply_filters('ere_register_option_property_top', array()),
                    apply_filters('ere_register_option_property_main', array(
                        array(
                            'id' => 'section_property_main_option',
                            'title' => esc_html__('Main Options', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'auto_publish',
                                    'title' => esc_html__('Automatically publish the submitted property?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'auto_publish_edited',
                                    'title' => esc_html__('Automatically publish the edited property?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'auto_approve_request_publish',
                                    'title' => esc_html__('Automatically approve Reactivating property request?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => 'property_form_sections',
                                    'type' => 'sortable',
                                    'title' => esc_html__('Submission Form Layout Manager', 'essential-real-estate'),
                                    'desc' => esc_html__('Drag and drop layout manager, to quickly organize your property submission form layout', 'essential-real-estate'),
                                    'options' => array(
                                        'title_des' => esc_html__('Title & Description', 'essential-real-estate'),
                                        'location' => esc_html__('Property Location', 'essential-real-estate'),
                                        'type' => esc_html__('Property Type', 'essential-real-estate'),
                                        'price' => esc_html__('Property Price', 'essential-real-estate'),
                                        'features' => esc_html__('Property Features', 'essential-real-estate'),
                                        'details' => esc_html__('Property Details', 'essential-real-estate'),
                                        'media' => esc_html__('Property Media', 'essential-real-estate'),
                                        'floors' => esc_html__('Floor Plans', 'essential-real-estate'),
                                        'contact' => esc_html__('Contact Information', 'essential-real-estate'),
                                    ),
                                    'default' => array(
                                        'title_des', 'location', 'type', 'price', 'features', 'details', 'media', 'floors', 'contact'
                                    )
                                ),
                                array(
                                    'id' => 'location_dropdowns',
                                    'title' => esc_html__('Show dropdowns for Property Location?', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Show dropdowns for Property Location ( Neighborhood, City, Province / State, country )?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                    'required' => array('property_form_sections', 'contain', 'location')
                                ),
                                array(
                                    'id' => 'max_property_images',
                                    'type' => 'text',
                                    'title' => esc_html__('Maximum Images', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Maximum number of images allowed for single property', 'essential-real-estate'),
                                    'default' => '10',
                                    'required' => array(
                                        array(
                                            array('property_form_sections', 'contain', 'media'),
                                            array('property_form_sections', 'contain', 'floors'),
                                        )
                                    ),
                                ),
                                array(
                                    'id' => 'image_max_file_size',
                                    'type' => 'text',
                                    'title' => esc_html__('Maximum File Size', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Maximum upload image file size. For example 10kb, 500kb, 1mb, 10m, 100mb', 'essential-real-estate'),
                                    'default' => '1000kb',
                                    'required' => array(
                                        array(
                                            array('property_form_sections', 'contain', 'media'),
                                            array('property_form_sections', 'contain', 'floors'),
                                        )
                                    ),
                                ),
                                array(
                                    'id' => 'max_property_attachments',
                                    'type' => 'text',
                                    'title' => esc_html__('Maximum Attachments', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Maximum number of attachments allowed for single property', 'essential-real-estate'),
                                    'default' => '2',
                                    'required' => array(
                                        array(
                                            array('property_form_sections', 'contain', 'media'),
                                            array('property_form_sections', 'contain', 'floors'),
                                        )
                                    ),
                                ),
                                array(
                                    'id' => 'attachment_max_file_size',
                                    'type' => 'text',
                                    'title' => esc_html__('Maximum File Size', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Maximum upload attachment file size. For example 10kb, 500kb, 1mb, 10m, 100mb', 'essential-real-estate'),
                                    'default' => '1000kb',
                                    'required' => array(
                                        array(
                                            array('property_form_sections', 'contain', 'media'),
                                            array('property_form_sections', 'contain', 'floors'),
                                        )
                                    ),
                                ),
                                array(
                                    'id' => 'attachment_file_type',
                                    'type' => 'text',
                                    'title' => esc_html__('File Type', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Allow only comma separated numbers. Ex: pdf,txt,doc,docx', 'essential-real-estate'),
                                    'default' => 'pdf,txt,doc,docx',
                                    'required' => array(
                                        array(
                                            array('property_form_sections', 'contain', 'media'),
                                            array('property_form_sections', 'contain', 'floors'),
                                        )
                                    ),
                                ),
                                array(
                                    'id' => 'default_property_image',
                                    'type' => 'image',
                                    'url' => true,
                                    'title' => esc_html__('Default Property Image', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Display this if no property image', 'essential-real-estate'),
                                    'default' => ERE_PLUGIN_URL . 'public/assets/images/map-marker-icon.png'
                                ),
                                array(
                                    'id' => 'featured_toplist',
                                    'title' => esc_html__('Show featured properties at the top of the list?', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Show featured properties at the top of the list', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                            )
                        ),
                        array(
                            'id' => 'section_property_hide_fields',
                            'title' => esc_html__('Hide Submit Form Fields', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'hide_property_fields',
                                    'type' => 'checkbox_list',
                                    'title' => esc_html__('Hide Submit Form Fields', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Choose which fields you want to hide on New Property page?', 'essential-real-estate'),
                                    'options' => array(
                                        'property_identity' => esc_html__('Property ID', 'essential-real-estate'),
                                        'property_des' => esc_html__('Description', 'essential-real-estate'),
                                        //Type
                                        'property_type' => esc_html__('Type', 'essential-real-estate'),
                                        'property_status' => esc_html__('Status', 'essential-real-estate'),
                                        'property_label' => esc_html__('Label', 'essential-real-estate'),
                                        //Price
                                        'property_price' => esc_html__('Price', 'essential-real-estate'),
                                        'property_price_prefix' => esc_html__('Before Price Label', 'essential-real-estate'),
                                        'property_price_postfix' => esc_html__('After Price Label', 'essential-real-estate'),
                                        'property_price_on_call' => esc_html__('Price on Call', 'essential-real-estate'),
                                        //Detail
                                        'property_size' => esc_html__('Size', 'essential-real-estate'),
                                        'property_land' => esc_html__('Land Area', 'essential-real-estate'),
                                        'property_rooms' => esc_html__('Rooms', 'essential-real-estate'),
                                        'property_bedrooms' => esc_html__('Bedrooms', 'essential-real-estate'),
                                        'property_bathrooms' => esc_html__('Bathrooms', 'essential-real-estate'),
                                        'property_garage' => esc_html__('Garage', 'essential-real-estate'),
                                        'property_garage_size' => esc_html__('Garage Size', 'essential-real-estate'),
                                        'property_year' => esc_html__('Year Built', 'essential-real-estate'),
                                        'property_attachments' => esc_html__('Fie Attachments', 'essential-real-estate'),
                                        'property_video_url' => esc_html__('Video Url', 'essential-real-estate'),
                                        'property_image_360' => esc_html__('Image 360', 'essential-real-estate'),
                                        'additional_details' => esc_html__('Additional Details', 'essential-real-estate'),
                                        //Location
                                        'property_map_address' => esc_html__('Map Address', 'essential-real-estate'),
                                        'country' => esc_html__('Country', 'essential-real-estate'),
                                        'state' => esc_html__('Province / State', 'essential-real-estate'),
                                        'city' => esc_html__('City / Town', 'essential-real-estate'),
                                        'neighborhood' => esc_html__('Neighborhood', 'essential-real-estate'),
                                        'postal_code' => esc_html__('Postal code', 'essential-real-estate'),
                                        //Contact
                                        'author_info' => esc_html__('My profile information', 'essential-real-estate'),
                                        'other_info' => esc_html__('Other contact', 'essential-real-estate'),
                                        'private_note' => esc_html__('Private Note', 'essential-real-estate'),
                                    ),
                                    'value_inline' => false,
                                    'default' => array()
                                ),
                            )
                        ),
                        array(
                            'id' => 'section_property_required_fields',
                            'title' => esc_html__('Required Fields', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'required_fields',
                                    'title' => esc_html__('Required Fields', 'essential-real-estate'),
                                    'type' => 'checkbox_list',
                                    'options' => $this->get_property_required_fields(),
                                    'value_inline' => false,
                                    'default' => array(
                                        'property_title',
                                        'property_type',
                                        'property_price',
                                        'property_map_address',
                                    )
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_option_property_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function additional_fields_option()
        {
            return apply_filters('ere_register_option_additional_fields', array(
                'id' => 'ere_additional_fields_option',
                'title' => esc_html__('Additional Fields', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-welcome-add-page',
                'fields' => array_merge(
                    apply_filters('ere_register_option_additional_fields_top', array()),
                    apply_filters('ere_register_option_additional_fields_main', array(
                        array(
                            'id' => "additional_fields",
                            'type' => 'panel',
                            'title' => esc_html__('Property Field', 'essential-real-estate'),
                            'sort' => true,
                            'panel_title' => 'label',
                            'fields' => array(
                                array(
                                    'title' => esc_html__('Label', 'essential-real-estate'),
                                    'id' => "label",
                                    'type' => 'text',
                                    'default' => '',
                                ),
	                            array(
		                            'title' => esc_html__('ID', 'essential-real-estate'),
		                            'id' => "id",
		                            'type' => 'text',
		                            'placeholder' => esc_html__('Enter field ID','essential-real-estate'),
		                            'desc' => esc_html__('ID values cannot be changed after being set!','essential-real-estate'),
		                            'default' => '',
	                            ),
                                array(
                                    'title' => esc_html__('Field Type', 'essential-real-estate'),
                                    'id' => "field_type",
                                    'type' => 'select',
                                    'default' => 'text',
                                    'options' => array(
                                        'text' => esc_html__('Text', 'essential-real-estate'),
                                        'textarea' => esc_html__('Text Multiple Line', 'essential-real-estate'),
                                        'select' => esc_html__('Select', 'essential-real-estate'),
                                        'checkbox_list' => esc_html__('Checkbox List', 'essential-real-estate'),
                                        'radio' => esc_html__('Radio', 'essential-real-estate'),
                                    )
                                ),
                                array(
                                    'title' => esc_html__('Options Value', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Input each per line', 'essential-real-estate'),
                                    'id' => "select_choices",
                                    'type' => 'textarea',
                                    'default' => '',
                                    'required' => array(
                                        "additional_fields_field_type",
                                        'in',
                                        array('checkbox_list', 'radio', 'select')
                                    ),
                                ),
	                            array(
		                            'id' => 'is_search',
		                            'type' => 'button_set',
		                            'title' => esc_html__('Make available for searches?', 'essential-real-estate'),
		                            'options' => array(
			                            'on' => esc_html__('On', 'essential-real-estate'),
			                            'off' => esc_html__('Off', 'essential-real-estate'),
		                            ),
		                            'default' => 'off'
	                            ),
                            )
                        )
                    )),
                    apply_filters('ere_register_option_additional_fields_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function search_option()
        {
            return apply_filters('ere_register_option_search', array(
                'id' => 'ere_search_option',
                'title' => esc_html__('Search', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-search',
                'fields' => array_merge(
                    apply_filters('ere_register_option_search_top', array()),
                    apply_filters('ere_register_option_search_main', array(
                        array(
                            'id' => 'section_search_field_option',
                            'title' => esc_html__('Show / Hide / Arrange Search Fields', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'search_fields',
                                    'type' => 'sortable',
                                    'title' => esc_html__('Search Fields', 'essential-real-estate'),
                                    'desc' => esc_html__('Drag and drop layout manager, to quickly organize your form search layout', 'essential-real-estate'),
                                    'options' => ere_get_search_form_fields_config(),
                                    'default' => array(
                                        'property_status', 'property_type', 'property_title', 'property_address', 'property_country', 'property_state', 'property_city', 'property_neighborhood', 'property_bedrooms', 'property_bathrooms', 'property_price', 'property_size', 'property_land', 'property_label', 'property_garage', 'property_identity', 'property_feature'
                                    )
                                ),
                            )
                        ),
                        array(
                            'id' => 'section_search_form_option',
                            'title' => esc_html__('Search Form Options', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
	                            array(
		                            'id'       => 'keyword_field',
		                            'type'     => 'select',
		                            'title'    => esc_html__('Keyword Field', 'essential-real-estate'),
		                            'desc' =>   esc_html__('Select the search criteria for the keyword field', 'essential-real-estate'),
		                            'options'  => array(
			                            'prop_title' => esc_html__('Property Title or Content', 'essential-real-estate'),
			                            'prop_address' => esc_html__('Property address, street, zip or property ID', 'essential-real-estate'),
			                            'prop_city_state_county' => esc_html__('Search State, City or Area', 'essential-real-estate'),
		                            ),
		                            'default' => 'prop_address'
	                            ),
                                array(
                                    'id' => 'section_search_form_price_field_option',
                                    'title' => esc_html__('Price Field', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'ere_property_price_dropdown_search_field',
                                            'type' => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Property Price Dropdown Value', 'essential-real-estate'),
                                        ),
                                        array(
                                            'id' => 'enable_price_number_short_scale',
                                            'title' => esc_html__('Enable Price Number in Short Scale on Search Field', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '0',
                                        ),

	                                    array(
		                                    'id' => 'property_price_dropdown_min',
		                                    'type' => 'text',
		                                    'title' => esc_html__('Minimum Prices List', 'essential-real-estate'),
		                                    'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
		                                    'default' => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
	                                    ),
	                                    array(
		                                    'id' => 'property_price_dropdown_max',
		                                    'type' => 'text',
		                                    'title' => esc_html__('Maximum Prices List', 'essential-real-estate'),
		                                    'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
		                                    'default' => '200,400,600,800,1000,1200,1400,1600,1800,2000',
	                                    ),

                                        array(
                                            'id' => 'property_price_dropdown_search_field',
                                            'title' => esc_html__('Price Field', 'essential-real-estate'),
                                            'type' => 'panel',
                                            'sort' => false,
                                            'fields' => array(
                                                array(
                                                    'type' => 'row',
                                                    'col' => '12',
                                                    'fields' => array(
                                                        array(
                                                            'id' => 'property_price_dropdown_property_status',
                                                            'title' => esc_html__('Property Status', 'essential-real-estate'),
                                                            'type' => 'select',
                                                            'data' => 'taxonomy',
                                                            'data_args' => array('taxonomy' => 'property-status', 'hide_empty' => 0, 'orderby' => 'ASC'),
                                                        ),
                                                        array(
                                                            'id' => 'property_price_dropdown_min',
                                                            'title' => esc_html__('Minimum Price', 'essential-real-estate'),
                                                            'subtitle' => esc_html__('Allow only comma separated numbers. Ex: 0,100,300,500,700,900', 'essential-real-estate'),
                                                            'type' => 'text',
                                                        ),
                                                        array(
                                                            'id' => 'property_price_dropdown_max',
                                                            'title' => esc_html__('Maximum Price', 'essential-real-estate'),
                                                            'subtitle' => esc_html__('Allow only comma separated numbers. Ex: 200,400,600,800,1000,1200', 'essential-real-estate'),
                                                            'type' => 'text',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                        ),
                                        array(
                                            'id' => 'ere_property_price_slider_search_field',
                                            'type' => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Property Price Slider Value', 'essential-real-estate'),
                                        ),
	                                    array(
		                                    'id' => 'property_price_slider_min',
		                                    'type' => 'text',
		                                    'title' => esc_html__('Minimum Price', 'essential-real-estate'),
		                                    'default' => '200',
	                                    ),
	                                    array(
		                                    'id' => 'property_price_slider_max',
		                                    'type' => 'text',
		                                    'title' => esc_html__('Maximum Price', 'essential-real-estate'),
		                                    'default' => '2500000',
	                                    ),
                                        array(
                                            'id' => 'property_price_slider_search_field',
                                            'title' => esc_html__('Price Field', 'essential-real-estate'),
                                            'type' => 'panel',
                                            'sort' => false,
                                            'fields' => array(
                                                array(
                                                    'type' => 'row',
                                                    'col' => '12',
                                                    'fields' => array(
                                                        array(
                                                            'id' => 'property_price_slider_property_status',
                                                            'title' => esc_html__('Property Status', 'essential-real-estate'),
                                                            'type' => 'select',
                                                            'data' => 'taxonomy',
                                                            'data_args' => array('taxonomy' => 'property-status', 'hide_empty' => 0, 'orderby' => 'ASC'),
                                                        ),
                                                        array(
                                                            'id' => 'property_price_slider_min',
                                                            'title' => esc_html__('Minimum Price', 'essential-real-estate'),
                                                            'subtitle' => esc_html__('Enter Minimum Price. Ex: 200', 'essential-real-estate'),
                                                            'type' => 'text',
                                                        ),
                                                        array(
                                                            'id' => 'property_price_slider_max',
                                                            'title' => esc_html__('Maximum Price', 'essential-real-estate'),
                                                            'subtitle' => esc_html__('Enter Maximum Price. Ex: 200000', 'essential-real-estate'),
                                                            'type' => 'text',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                        )
                                    )
                                ),
                                array(
                                    'id' => 'section_search_form_size_field_option',
                                    'title' => esc_html__('Size Field', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'ere_property_size_dropdown_search_field',
                                            'type' => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Property Size Dropdown Value', 'essential-real-estate'),
                                        ),
                                        array(
                                            'id' => 'property_size_dropdown_min',
                                            'type' => 'text',
                                            'title' => esc_html__('Minimum Size List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
                                        ),
                                        array(
                                            'id' => 'property_size_dropdown_max',
                                            'type' => 'text',
                                            'title' => esc_html__('Maximum Size List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '200,400,600,800,1000,1200,1400,1600,1800,2000',
                                        ),
                                        array(
                                            'id' => 'ere_property_size_slider_search_field',
                                            'type' => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Property Size Slider Value', 'essential-real-estate'),
                                        ),
                                        array(
                                            'id' => 'property_size_slider_min',
                                            'type' => 'text',
                                            'title' => esc_html__('Minimum Property Size', 'essential-real-estate'),
                                            'default' => '10',
                                        ),
                                        array(
                                            'id' => 'property_size_slider_max',
                                            'type' => 'text',
                                            'title' => esc_html__('Maximum Property Size', 'essential-real-estate'),
                                            'default' => '1000',
                                        ),
                                    )
                                ),
                                array(
                                    'id' => 'section_search_form_land_field_option',
                                    'title' => esc_html__('Land Area Field', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'ere_property_land_dropdown_search_field',
                                            'type' => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Property Land Area Dropdown Value', 'essential-real-estate'),
                                        ),
                                        array(
                                            'id' => 'property_land_dropdown_min',
                                            'type' => 'text',
                                            'title' => esc_html__('Minimum Land Area Size List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '0,100,300,500,700,900,1100,1300,1500,1700,1900',
                                        ),
                                        array(
                                            'id' => 'property_land_dropdown_max',
                                            'type' => 'text',
                                            'title' => esc_html__('Maximum Land Area Size List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '200,400,600,800,1000,1200,1400,1600,1800,2000',
                                        ),
                                        array(
                                            'id' => 'ere_property_land_slider_search_field',
                                            'type' => 'info',
                                            'style' => 'info',
                                            'title' => esc_html__('Property Land Area Slider Value', 'essential-real-estate'),
                                        ),
                                        array(
                                            'id' => 'property_land_slider_min',
                                            'type' => 'text',
                                            'title' => esc_html__('Minimum Land Area Size', 'essential-real-estate'),
                                            'default' => '10',
                                        ),
                                        array(
                                            'id' => 'property_land_slider_max',
                                            'type' => 'text',
                                            'title' => esc_html__('Maximum Land Area Size', 'essential-real-estate'),
                                            'default' => '1000',
                                        ),
                                    )
                                ),
                                array(
                                    'id' => 'section_search_form_other_field_option',
                                    'title' => esc_html__('Other Fields', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
	                                    array(
		                                    'id' => 'rooms_list',
		                                    'type' => 'text',
		                                    'title' => esc_html__('Rooms List', 'essential-real-estate'),
		                                    'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
		                                    'default' => '1,2,3,4,5,6,7,8,9,10'
	                                    ),
                                        array(
                                            'id' => 'bedrooms_list',
                                            'type' => 'text',
                                            'title' => esc_html__('Bedrooms List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '1,2,3,4,5,6,7,8,9,10'
                                        ),
                                        array(
                                            'id' => 'bathrooms_list',
                                            'type' => 'text',
                                            'title' => esc_html__('Bathrooms List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '1,2,3,4,5,6,7,8,9,10'
                                        ),
                                        array(
                                            'id' => 'garage_list',
                                            'type' => 'text',
                                            'title' => esc_html__('Garage List', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Allow only comma separated numbers', 'essential-real-estate'),
                                            'default' => '1,2,3,4,5,6,7,8,9,10'
                                        ),
                                    )
                                ),

                            )
                        ),
                        /* Search Page*/
                        array(
                            'id' => 'section_search_page_option',
                            'title' => esc_html__('Advanced Search Page Options', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'enable_advanced_search_form',
                                    'title' => esc_html__('Enable Search Form', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Enabled', 'essential-real-estate'),
                                        '0' => esc_html__('Disabled', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'advanced_search_price_field_layout',
                                    'title' => esc_html__('Property Price Field Layout', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Dropdown', 'essential-real-estate'),
                                        '1' => esc_html__('Slider', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_advanced_search_form', '=', '1'),
                                ),
                                array(
                                    'id' => 'advanced_search_size_field_layout',
                                    'title' => esc_html__('Property Size Field Layout', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Dropdown', 'essential-real-estate'),
                                        '1' => esc_html__('Slider', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_advanced_search_form', '=', '1'),
                                ),
                                array(
                                    'id' => 'advanced_search_land_field_layout',
                                    'title' => esc_html__('Property Land Area Field Layout', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Dropdown', 'essential-real-estate'),
                                        '1' => esc_html__('Slider', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_advanced_search_form', '=', '1'),
                                ),
                                array(
                                    'id' => 'enable_saved_search',
                                    'title' => esc_html__('Enable Saved Search', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Enabled', 'essential-real-estate'),
                                        '0' => esc_html__('Disabled', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'ere_search_property_layout',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Layout Search Result', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'search_property_layout_style',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                    'options' => array(
                                        'property-grid' => esc_html__('Grid', 'essential-real-estate'),
                                        'property-list' => esc_html__('List', 'essential-real-estate'),
                                    ),
                                    'default' => 'property-grid',
                                ),
                                array(
                                    'id' => 'search_property_items_amount',
                                    'type' => 'text',
                                    'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enter number for items amount property show in search page', 'essential-real-estate'),
                                    'default' => '12'
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => esc_html__('Image Size', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 330x180 (Not Include Unit, Space))', 'essential-real-estate'),
                                    'id' => 'search_property_image_size',
                                    'default' => '330x180',
                                ),
                                array(
                                    'id' => 'search_property_columns',
                                    'type' => 'select',
                                    'title' => esc_html__('Columns', 'essential-real-estate'),
                                    'options' => array(
                                        '2' => esc_html__('2', 'essential-real-estate'),
                                        '3' => esc_html__('3', 'essential-real-estate'),
                                        '4' => esc_html__('4', 'essential-real-estate'),
                                    ),
                                    'default' => '3',
                                    'required' => array('search_property_layout_style', 'in', array('property-grid')),
                                ),

                                array(
                                    'id' => 'search_property_columns_gap',
                                    'type' => 'select',
                                    'title' => esc_html__('Columns Gap', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Select columns gap between properties for page search', 'essential-real-estate'),
                                    'options' => array(
                                        'col-gap-0' => esc_html__('0px', 'essential-real-estate'),
                                        'col-gap-10' => esc_html__('10px', 'essential-real-estate'),
                                        'col-gap-20' => esc_html__('20px', 'essential-real-estate'),
                                        'col-gap-30' => esc_html__('30px', 'essential-real-estate'),
                                    ),
                                    'default' => 'col-gap-0',
                                    'required' => array('search_property_layout_style', 'in', array('property-grid')),
                                ),


                                /*RESPONSIVE*/
                                array(
                                    'id' => 'search_property_items_md',
                                    'type' => 'select',
                                    'title' => esc_html__('Items Desktop Small', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Browser Width < 1199px', 'essential-real-estate'),
                                    'options' => array(
                                        '2' => esc_html__('2', 'essential-real-estate'),
                                        '3' => esc_html__('3', 'essential-real-estate'),
                                        '4' => esc_html__('4', 'essential-real-estate'),
                                    ),
                                    'default' => '3',
                                    'required' => array('search_property_layout_style', 'in', array('property-grid')),
                                ),
                                array(
                                    'id' => 'search_property_items_sm',
                                    'type' => 'select',
                                    'title' => esc_html__('Items Tablet', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Browser Width < 992px', 'essential-real-estate'),
                                    'options' => array(
                                        '2' => esc_html__('2', 'essential-real-estate'),
                                        '3' => esc_html__('3', 'essential-real-estate'),
                                        '4' => esc_html__('4', 'essential-real-estate'),
                                    ),
                                    'default' => '2',
                                    'required' => array('search_property_layout_style', 'in', array('property-grid')),
                                ),
                                array(
                                    'id' => 'search_property_items_xs',
                                    'type' => 'select',
                                    'title' => esc_html__('Items Tablet Small', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Browser Width < 768px', 'essential-real-estate'),
                                    'options' => array(
                                        '1' => esc_html__('1', 'essential-real-estate'),
                                        '2' => esc_html__('2', 'essential-real-estate'),
                                        '3' => esc_html__('3', 'essential-real-estate'),
                                        '4' => esc_html__('4', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                    'required' => array('search_property_layout_style', 'in', array('property-grid')),
                                ),
                                array(
                                    'id' => 'search_property_items_mb',
                                    'type' => 'select',
                                    'title' => esc_html__('Items Mobile', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Browser Width < 480px', 'essential-real-estate'),
                                    'options' => array(
                                        '1' => esc_html__('1', 'essential-real-estate'),
                                        '2' => esc_html__('2', 'essential-real-estate'),
                                        '3' => esc_html__('3', 'essential-real-estate'),
                                        '4' => esc_html__('4', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                    'required' => array('search_property_layout_style', 'in', array('property-grid')),
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_option_search_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function payment_option()
        {
            return apply_filters('ere_register_option_payment', array(
                'id' => 'ere_payment_option',
                'title' => esc_html__('Payment & Submission Type', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-cart',
                'fields' => array_merge(
                    apply_filters('ere_register_option_payment_top', array()),
                    apply_filters('ere_register_option_payment_main', array(
                        array(
                            'id' => 'paid_submission_type',
                            'type' => 'select',
                            'title' => esc_html__('Paid Submission Type', 'essential-real-estate'),
                            'subtitle' => '',
                            'options' => array(
                                'no' => esc_html__('Free Submit', 'essential-real-estate'),
                                'per_listing' => esc_html__('Pay Per Listing', 'essential-real-estate'),
                                'per_package' => esc_html__('Pay Per Package', 'essential-real-estate')
                            ),
                            'default' => 'no',
                        ),
                        array(
                            'id' => 'price_per_listing',
                            'type' => 'text',
                            'required' => array('paid_submission_type', '=', 'per_listing'),
                            'title' => esc_html__('Price Per Listing Submission', 'essential-real-estate'),
                            'subtitle' => esc_html__('0 as Free Submit', 'essential-real-estate'),
                            'default' => '0',
                        ),
                        array(
                            'id' => 'price_featured_listing',
                            'type' => 'text',
                            'required' => array('paid_submission_type', '=', 'per_listing'),
                            'title' => esc_html__('Price To Make Listing Featured', 'essential-real-estate'),
                            'subtitle' => esc_html__('0 as Free', 'essential-real-estate'),
                            'default' => '0',
                        ),

                        array(
                            'id' => 'per_listing_expire_days',
                            'title' => esc_html__('Expire Days', 'essential-real-estate'),
                            'subtitle' => esc_html__('Enable set single listing expire days', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '0',
                            'required' => array('paid_submission_type', '=', 'per_listing'),
                        ),
                        array(
                            'id' => 'number_expire_days',
                            'type' => 'text',
                            'title' => esc_html__('Number of Expire Days', 'essential-real-estate'),
                            'default' => '30',
                            'required' => array(
                                array('per_listing_expire_days', '=', '1'),
                                array('paid_submission_type', '=', 'per_listing')
                            ),
                        ),
                        array(
                            'id' => 'payment_terms_condition',
                            'title' => esc_html__('Terms & Conditions', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select terms & conditions page', 'essential-real-estate'),
                            'type' => 'select',
                            'data' => 'page',
                            'data_args' => array(
                                'numberposts' => -1,
                            )
                        ),
                        array(
                            'id' => 'currency_code',
                            'type' => 'text',
                            'required' => array('paid_submission_type', '!=', 'no'),
                            'title' => esc_html__('Currency Code', 'essential-real-estate'),
                            'subtitle' => esc_html__('Provide the currency code that you want to use. Ex. USD', 'essential-real-estate'),
                            'default' => 'USD',
                        ),
                        array(
                            'id' => 'ere_paypal',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Paypal Setting', 'essential-real-estate'),
                            'required' => array('paid_submission_type', '!=', 'no'),
                        ),
                        array(
                            'id' => 'enable_paypal',
                            'title' => esc_html__('Enable Paypal', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Enabled', 'essential-real-estate'),
                                '0' => esc_html__('Disabled', 'essential-real-estate'),
                            ),
                            'default' => '0',
                            'required' => array('paid_submission_type', '!=', 'no'),
                        ),
                        array(
                            'id' => 'paypal_api',
                            'type' => 'select',
                            'required' => array(
                                array('enable_paypal', '=', '1'),
                                array('paid_submission_type', '!=', 'no')
                            ),
                            'title' => esc_html__('Paypal Api', 'essential-real-estate'),
                            'subtitle' => esc_html__('Sandbox = test API. LIVE = real payments API', 'essential-real-estate'),
                            'desc' => esc_html__('Update PayPal settings according to API type selection', 'essential-real-estate'),
                            'options' => array(
                                'sandbox' => esc_html__('Sandbox', 'essential-real-estate'),
                                'live' => esc_html__('Live', 'essential-real-estate')
                            ),
                            'default' => 'sandbox',
                        ),
                        array(
                            'id' => 'paypal_client_id',
                            'type' => 'text',
                            'required' => array(
                                array('enable_paypal', '=', '1'),
                                array('paid_submission_type', '!=', 'no')
                            ),
                            'title' => esc_html__('Paypal Client ID', 'essential-real-estate'),
                            'subtitle' => '',
                            'default' => '',
                        ),
                        array(
                            'id' => 'paypal_client_secret_key',
                            'type' => 'text',
                            'required' => array(
                                array('enable_paypal', '=', '1'),
                                array('paid_submission_type', '!=', 'no')
                            ),
                            'title' => esc_html__('Paypal Client Secret Key', 'essential-real-estate'),
                            'subtitle' => '',
                            'default' => '',
                        ),

                        array(
                            'id' => 'ere_stripe',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Stripe Setting', 'essential-real-estate'),
                            'required' => array('paid_submission_type', '!=', 'no'),
                        ),
                        array(
                            'id' => 'enable_stripe',
                            'title' => esc_html__('Enable Stripe', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Enabled', 'essential-real-estate'),
                                '0' => esc_html__('Disabled', 'essential-real-estate'),
                            ),
                            'default' => '0',
                            'required' => array('paid_submission_type', '!=', 'no'),
                        ),
                        array(
                            'id' => 'stripe_secret_key',
                            'type' => 'text',
                            'required' => array(
                                array('enable_stripe', '=', '1'),
                                array('paid_submission_type', '!=', 'no')
                            ),
                            'title' => esc_html__('Stripe Secret Key', 'essential-real-estate'),
                            'subtitle' => esc_html__('Info is taken from your account at https://dashboard.stripe.com/login', 'essential-real-estate'),
                            'default' => '',
                        ),
                        array(
                            'id' => 'stripe_publishable_key',
                            'type' => 'text',
                            'required' => array(
                                array('enable_stripe', '=', '1'),
                                array('paid_submission_type', '!=', 'no')
                            ),
                            'title' => esc_html__('Stripe Publishable Key', 'essential-real-estate'),
                            'subtitle' => esc_html__('Info is taken from your account at https://dashboard.stripe.com/login', 'essential-real-estate'),
                            'default' => '',
                        ),
                        array(
                            'id' => 'ere_wire_transfer',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Wire Transfer Setting', 'essential-real-estate'),
                            'required' => array('paid_submission_type', '!=', 'no'),
                        ),
                        array(
                            'id' => 'enable_wire_transfer',
                            'title' => esc_html__('Enable Wire Transfer', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Enabled', 'essential-real-estate'),
                                '0' => esc_html__('Disabled', 'essential-real-estate'),
                            ),
                            'default' => '0',
                            'required' => array('paid_submission_type', '!=', 'no'),
                        ),
                        array(
                            'id' => 'wire_transfer_info',
                            'type' => 'editor',
                            'title' => esc_html__('Wire Transfer Information', 'essential-real-estate'),
                            'required' => array('enable_wire_transfer', '=', '1'),
                        )
                    )),
                    apply_filters('ere_register_option_payment_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function payment_complete_option()
        {
            return apply_filters('ere_register_option_payment_complete', array(
                'id' => 'ere_payment_complete_option',
                'title' => esc_html__('Payment Complete', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-feedback',
                'fields' => array_merge(
                    apply_filters('ere_register_option_payment_complete_top', array()),
                    apply_filters('ere_register_option_payment_complete_main', array(
                        array(
                            'id' => 'ere_thankyou',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Thank-you note after payment via Paypal or Stripe', 'essential-real-estate'),
                        ),
                        array(
                            'id' => 'thankyou_title',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'essential-real-estate'),
                            'default' => esc_html__('Thank you for your purchase', 'essential-real-estate'),
                        ),
                        array(
                            'id' => 'thankyou_content',
                            'title' => esc_html__('Thank-you Content', 'essential-real-estate'),
                            'type' => 'editor',
                            'default' => '',
                        ),
                        array(
                            'id' => 'ere_thankyou_wire_transfer',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Thank-you note after payment via Wire Transfer', 'essential-real-estate'),
                        ),
                        array(
                            'id' => 'thankyou_title_wire_transfer',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'essential-real-estate'),
                            'default' => esc_html__('Thank you for your purchase', 'essential-real-estate'),
                        ),
                        array(
                            'id' => 'thankyou_content_wire_transfer',
                            'title' => esc_html__('Thank-you Content', 'essential-real-estate'),
                            'type' => 'editor',
                            'default' => esc_html__('Make your payment directly into our bank account. Please use your Order ID as payment reference', 'essential-real-estate'),
                        ),
                    )),
                    apply_filters('ere_register_option_payment_complete_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function invoices_option()
        {
            return apply_filters('ere_register_option_invoices', array(
                'id' => 'ere_invoices_option',
                'title' => esc_html__('Invoices', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-clipboard',
                'fields' => array_merge(
                    apply_filters('ere_register_option_invoices_top', array()),
                    apply_filters('ere_register_option_invoices_main', array(
                        array(
                            'id' => 'company_name',
                            'type' => 'text',
                            'title' => esc_html__('Company Name', 'essential-real-estate'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'company_address',
                            'type' => 'textarea',
                            'title' => esc_html__('Company Address', 'essential-real-estate'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'company_phone',
                            'type' => 'text',
                            'title' => esc_html__('Company Phone', 'essential-real-estate'),
                            'default' => ''
                        )
                    )),
                    apply_filters('ere_register_option_invoices_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function favorite_option()
        {
            return apply_filters('ere_register_option_favorite', array(
                'id' => 'ere_favorite_option',
                'title' => esc_html__('Favorite', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-heart',
                'fields' => array_merge(
                    apply_filters('ere_register_option_favorite_top', array()),
                    apply_filters('ere_register_option_favorite_main', array(
                        array(
                            'id' => 'enable_favorite_property',
                            'title' => esc_html__('Enable Favorite Properties', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                    )),
                    apply_filters('ere_register_option_favorite_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function social_share_option()
        {
            return apply_filters('ere_register_option_social_share', array(
                'id' => 'ere_social_share_option',
                'title' => esc_html__('Social Share', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-share',
                'fields' => array_merge(
                    apply_filters('ere_register_option_social_share_top', array()),
                    apply_filters('ere_register_option_social_share_main', array(
                        array(
                            'id' => 'enable_social_share',
                            'title' => esc_html__('Enable Social Share', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'title' => esc_html__('Social Share', 'essential-real-estate'),
                            'id' => 'social_sharing',
                            'type' => 'checkbox_list',
                            'value_inline' => false,
                            'subtitle' => esc_html__('Show Social Share in single property', 'essential-real-estate'),

                            //Must provide key => value pairs for multi checkbox options
                            'options' => array(
                                'facebook' => esc_html__('Facebook', 'essential-real-estate'),
                                'twitter' => esc_html__('Twitter', 'essential-real-estate'),
                                'linkedin' => esc_html__('Linkedin', 'essential-real-estate'),
                                'tumblr' => esc_html__('Tumblr', 'essential-real-estate'),
                                'pinterest' => esc_html__('Pinterest', 'essential-real-estate'),
                                'whatsup' => esc_html__('WhatsUp', 'essential-real-estate')
                            ),

                            //See how default has changed? you also don't need to specify opts that are 0.
                            'default' => array(
                                'facebook' => '1',
                                'twitter' => '1',
                                'linkedin' => '1',
                                'tumblr' => '1',
                                'pinterest' => '1'
                            )
                        )
                    )),
                    apply_filters('ere_register_option_social_share_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function print_option()
        {
            return apply_filters('ere_register_option_print', array(
                'id' => 'ere_print_option',
                'title' => esc_html__('Print', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-media-document',
                'fields' => array_merge(
                    apply_filters('ere_register_option_print_top', array()),
                    apply_filters('ere_register_option_print_main', array(
                        array(
                            'id' => 'enable_print_property',
                            'title' => esc_html__('Enable Print Property', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'enable_print_invoice',
                            'title' => esc_html__('Enable Print Invoice', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'print_logo',
                            'type' => 'image',
                            'url' => true,
                            'title' => esc_html__('Print Logo', 'essential-real-estate'),
                            'subtitle' => esc_html__('Upload logo for Print pages', 'essential-real-estate'),
                            'default' => ''
                        ),
                        array(
                            'type' => 'text',
                            'title' => esc_html__('Print Logo Size', 'essential-real-estate'),
                            'subtitle' => esc_html__('Enter print logo size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 100x100, 200x100, 200x200 (Not Include Unit, Space))', 'essential-real-estate'),
                            'id' => 'print_logo_size',
                            'default' => '200x100',
                            'required' => array('print_logo[id]', '!=', '')
                        ),
                    )),
                    apply_filters('ere_register_option_print_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function nearby_places_option()
        {
            return apply_filters('ere_register_option_nearby_places', array(
                'id' => 'ere_nearby_places_option',
                'title' => esc_html__('Nearby Places', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-location-alt',
                'fields' => array_merge(
                    apply_filters('ere_register_option_nearby_places_top', array()),
                    apply_filters('ere_register_option_nearby_places_main', array(
                        array(
                            'id' => 'enable_nearby_places',
                            'title' => esc_html__('Enable Nearby Places', 'essential-real-estate'),
                            'subtitle' => esc_html__('Enable Nearby Places on single property page?', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'nearby_places_rank_by',
                            'title' => esc_html__('Rank by', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select options', 'essential-real-estate'),
                            'type' => 'select',
                            'options' => array(
                                "default" => esc_html__('Prominence', 'essential-real-estate'),
                                "distance" => esc_html__('Distance', 'essential-real-estate'),
                            ),
                            'required' => array('enable_nearby_places', '=', '1')
                        ),
                        array(
                            'id' => 'nearby_places_radius',
                            'title' => esc_html__('Radius', 'essential-real-estate'),
                            'subtitle' => esc_html__('Radius', 'essential-real-estate'),
                            'desc' => esc_html__('Enter radius (meter)', 'essential-real-estate'),
                            'type' => 'text',
                            'default' => '5000',
                            'required' => array(
                                array('nearby_places_rank_by', '=', 'default'),
                                array('enable_nearby_places', '=', '1')
                            )
                        ),
                        array(
                            'id' => 'set_map_height',
                            'type' => 'text',
                            'title' => esc_html__('Set Map Height', 'essential-real-estate'),
                            'default' => '475',
                            'required' => array('enable_nearby_places', '=', '1')
                        ),
                        array(
                            'id' => 'nearby_places_distance_in',
                            'title' => esc_html__('Nearby places distance in', 'essential-real-estate'),
                            'subtitle' => esc_html__('Select options', 'essential-real-estate'),
                            'type' => 'select',
                            'options' => array(
                                "m" => esc_html__('Meter', 'essential-real-estate'),
                                "km" => esc_html__('Km', 'essential-real-estate'),
                                "mi" => esc_html__('Mile', 'essential-real-estate'),
                            ),
                            'required' => array('enable_nearby_places', '=', '1')
                        ),
                        array(
                            'id' => 'nearby_places_field',
                            'title' => esc_html__('Nearby Places Field', 'essential-real-estate'),
                            'type' => 'panel',
                            'sort' => false,
                            'fields' => array(
                                array(
                                    'type' => 'row',
                                    'col' => '12',
                                    'fields' => array(
                                        array(
                                            'id' => 'nearby_places_select_field_type',
                                            'title' => esc_html__('Type Place', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Select options', 'essential-real-estate'),
                                            'type' => 'select',
                                            'options' => array(
                                                "accounting" => esc_html__('Accounting', 'essential-real-estate'),
                                                "airport" => esc_html__('Airport', 'essential-real-estate'),
                                                "amusement_park" => esc_html__('Amusement Park', 'essential-real-estate'),
                                                "aquarium" => esc_html__('Aquarium', 'essential-real-estate'),
                                                "atm" => esc_html__('Atm', 'essential-real-estate'),
                                                "bakery" => esc_html__('Bakery', 'essential-real-estate'),
                                                "bank" => esc_html__('Bank', 'essential-real-estate'),
                                                "bar" => esc_html__('Bar', 'essential-real-estate'),
                                                "beauty_salon" => esc_html__('Beauty Salon', 'essential-real-estate'),
                                                "bicycle_store" => esc_html__('Bicycle Store', 'essential-real-estate'),
                                                "book_store" => esc_html__('Book Store', 'essential-real-estate'),
                                                "bowling_alley" => esc_html__('Bowling Alley', 'essential-real-estate'),
                                                "bus_station" => esc_html__('Bus Station', 'essential-real-estate'),
                                                "cafe" => esc_html__('Cafe', 'essential-real-estate'),
                                                "campground" => esc_html__('Campground', 'essential-real-estate'),
                                                "car_rental" => esc_html__('Car Rental', 'essential-real-estate'),
                                                "car_repair" => esc_html__('Car Repair', 'essential-real-estate'),
                                                "car_wash" => esc_html__('Car Wash', 'essential-real-estate'),
                                                "casino" => esc_html__('Casino', 'essential-real-estate'),
                                                "cemetery" => esc_html__('Cemetery', 'essential-real-estate'),
                                                "church" => esc_html__('Church', 'essential-real-estate'),
                                                "city_hall" => esc_html__('City Center', 'essential-real-estate'),
                                                "clothing_store" => esc_html__('Clothing Store', 'essential-real-estate'),
                                                "convenience_store" => esc_html__('Convenience Store', 'essential-real-estate'),
                                                "courthouse" => esc_html__('Courthouse', 'essential-real-estate'),
                                                "dentist" => esc_html__('Dentist', 'essential-real-estate'),
                                                "department_store" => esc_html__('Department Store', 'essential-real-estate'),
                                                "doctor" => esc_html__('Doctor', 'essential-real-estate'),
                                                "electrician" => esc_html__('Electrician', 'essential-real-estate'),
                                                "electronics_store" => esc_html__('Electronics Store', 'essential-real-estate'),
                                                "embassy" => esc_html__('Embassy', 'essential-real-estate'),
                                                "establishment" => esc_html__('Establishment', 'essential-real-estate'),
                                                "finance" => esc_html__('Finance', 'essential-real-estate'),
                                                "fire_station" => esc_html__('Fire Station', 'essential-real-estate'),
                                                "florist" => esc_html__('Florist', 'essential-real-estate'),
                                                "food" => esc_html__('Food', 'essential-real-estate'),
                                                "gas_station" => esc_html__('Gas Station', 'essential-real-estate'),
                                                "grocery_or_supermarket" => esc_html__('Grocery', 'essential-real-estate'),
                                                "gym" => esc_html__('Gym', 'essential-real-estate'),
                                                "hair_care" => esc_html__('Hair Care', 'essential-real-estate'),
                                                "hardware_store" => esc_html__('Hardware Store', 'essential-real-estate'),
                                                "health" => esc_html__('Health', 'essential-real-estate'),
                                                "home_goods_store" => esc_html__('Home Goods Store', 'essential-real-estate'),
                                                "hospital" => esc_html__('Hospital', 'essential-real-estate'),
                                                "jewelry_store" => esc_html__('Jewelry Store', 'essential-real-estate'),
                                                "laundry" => esc_html__('Laundry', 'essential-real-estate'),
                                                "lawyer" => esc_html__('Lawyer', 'essential-real-estate'),
                                                "library" => esc_html__('Library', 'essential-real-estate'),
                                                "lodging" => esc_html__('Lodging', 'essential-real-estate'),
                                                "movie_theater" => esc_html__('Movie Theater', 'essential-real-estate'),
                                                "moving_company" => esc_html__('Moving Company', 'essential-real-estate'),
                                                "night_club" => esc_html__('Night Club', 'essential-real-estate'),
                                                "park" => esc_html__('Park', 'essential-real-estate'),
                                                "pharmacy" => esc_html__('Pharmacy', 'essential-real-estate'),
                                                "place_of_worship" => esc_html__('Place Of Worship', 'essential-real-estate'),
                                                "plumber" => esc_html__('Plumber', 'essential-real-estate'),
                                                "police" => esc_html__('Police', 'essential-real-estate'),
                                                "post_office" => esc_html__('Post Office', 'essential-real-estate'),
                                                "restaurant" => esc_html__('Restaurant', 'essential-real-estate'),
                                                "school" => esc_html__('School', 'essential-real-estate'),
                                                "shopping_mall" => esc_html__('Shopping Mall', 'essential-real-estate'),
                                                "spa" => esc_html__('Spa', 'essential-real-estate'),
                                                "stadium" => esc_html__('Stadium', 'essential-real-estate'),
                                                "storage" => esc_html__('Storage', 'essential-real-estate'),
                                                "store" => esc_html__('Store', 'essential-real-estate'),
                                                "subway_station" => esc_html__('Subway Station', 'essential-real-estate'),
                                                "synagogue" => esc_html__('Synagogue', 'essential-real-estate'),
                                                "taxi_stand" => esc_html__('Taxi Stand', 'essential-real-estate'),
                                                "train_station" => esc_html__('Train Station', 'essential-real-estate'),
                                                "travel_agency" => esc_html__('Travel Agency', 'essential-real-estate'),
                                                "university" => esc_html__('University', 'essential-real-estate'),
                                                "veterinary_care" => esc_html__('Veterinary Care', 'essential-real-estate'),
                                                "zoo" => esc_html__('Zoo', 'essential-real-estate'),
                                            ),
                                            'default' => 'school',
                                        ),
                                        array(
                                            'id' => 'nearby_places_field_label',
                                            'title' => esc_html__('Label Place', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Enter label place', 'essential-real-estate'),
                                            'type' => 'text',
                                            'default' => 'School',
                                            'panel_title' => true,
                                        ),
                                        array(
                                            'id' => 'nearby_places_field_icon',
                                            'title' => esc_html__('Image Icon Place', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Image field default options', 'essential-real-estate'),
                                            'type' => 'image',
                                            'images_select_text' => esc_html__('Select Nearbey places Images', 'essential-real-estate'),
                                            'default' => ERE_PLUGIN_URL . 'public/assets/images/school-icon.png',
                                        ),
                                    ),
                                ),
                            ),
                            'required' => array('enable_nearby_places', '=', '1')
                        ),
                    )),
                    apply_filters('ere_register_option_nearby_places_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function walk_score_option()
        {
            return apply_filters('ere_register_option_walk_score', array(
                'id' => 'ere_walk_score_option',
                'title' => esc_html__('Walk Score', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-location',
                'fields' => array_merge(
                    apply_filters('ere_register_option_walk_score_top', array()),
                    apply_filters('ere_register_option_walk_score_main', array(
                        array(
                            'id' => 'enable_walk_score',
                            'title' => esc_html__('Enable Walk Score', 'essential-real-estate'),
                            'subtitle' => esc_html__('Enable Walk Score on single property page?', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '0',
                        ),
                        array(
                            'id' => 'walk_score_api_key',
                            'type' => 'text',
                            'required' => array('enable_walk_score', '=', '1'),
                            'title' => esc_html__('Walk Score API Key', 'essential-real-estate'),
                            'subtitle' => '',
                            'default' => '',
                        ),
                    )),
                    apply_filters('ere_register_option_walk_score_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function google_map_directions_option()
        {
            return apply_filters('ere_register_option_google_map_directions', array(
                'id' => 'ere_google_map_directions_option',
                'title' => esc_html__('Map Directions', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-redo',
                'fields' => array_merge(
                    apply_filters('ere_register_option_google_map_directions_top', array()),
                    apply_filters('ere_register_option_google_map_directions_main', array(
                        array(
                            'id' => 'enable_map_directions',
                            'title' => esc_html__('Enable Google Map Directions', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'map_directions_distance_units',
                            'type' => 'select',
                            'title' => esc_html__('Distance Units', 'essential-real-estate'),
                            'subtitle' => '',
                            'options' => array(
                                'metre' => esc_html__('Metre', 'essential-real-estate'),
                                'kilometre' => esc_html__('Kilometre', 'essential-real-estate'),
                                'mile' => esc_html__('Mile', 'essential-real-estate')
                            ),
                            'default' => 'no',
                        ),
                    )),
                    apply_filters('ere_register_option_google_map_directions_bottom', array())
                )
            ));
        }

        private function comments_reviews_option()
        {
            return apply_filters('ere_register_option_comments_reviews', array(
                'id' => 'ere_comments_reviews_option',
                'title' => esc_html__('Comments & Reviews', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-admin-comments',
                'fields' => array_merge(
                    apply_filters('ere_register_option_comments_reviews_top', array()),
                    apply_filters('ere_register_option_comments_reviews_main', array(
                        array(
                            'id' => 'section_comments_reviews_property',
                            'title' => esc_html__('Property', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'enable_comments_reviews_property',
                                    'title' => esc_html__('Enable Comments & Reviews For Property', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Hide', 'essential-real-estate'),
                                        '1' => esc_html__('Comments Only', 'essential-real-estate'),
                                        '2' => esc_html__('Ratings & Reviews', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'review_property_approved_by_admin',
                                    'title' => esc_html__('Ratings & Reviews Approved by Admin?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_comments_reviews_property', '=', array('2'))
                                ),
                            )
                        ),
                        array(
                            'id' => 'section_comments_reviews_agent',
                            'title' => esc_html__('Agent', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'enable_comments_reviews_agent',
                                    'title' => esc_html__('Enable Comments & Reviews For Agent', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Hide', 'essential-real-estate'),
                                        '1' => esc_html__('Comments Only', 'essential-real-estate'),
                                        '2' => esc_html__('Ratings & Reviews', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => 'review_agent_approved_by_admin',
                                    'title' => esc_html__('Ratings & Reviews Approved by Admin?', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_comments_reviews_agent', '=', array('2'))
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_option_comments_reviews_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function compare_option()
        {
            return apply_filters('ere_register_option_compare', array(
                'id' => 'ere_compare_option',
                'title' => esc_html__('Compare', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-controls-repeat',
                'fields' => array_merge(
                    apply_filters('ere_register_option_compare_top', array()),
                    apply_filters('ere_register_option_compare_main', array(
                        array(
                            'id' => 'enable_compare_properties',
                            'title' => esc_html__('Enable Compare Properties', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'hide_compare_fields',
                            'title' => esc_html__('Hide Compare Fields', 'essential-real-estate'),
                            'subtitle' => esc_html__('Choose which fields you want to hide when compare properties?', 'essential-real-estate'),
                            'type' => 'checkbox_list',
                            'options' => array(
                                'property_type' => esc_html__('Type', 'essential-real-estate'),
                                'property_status' => esc_html__('Status', 'essential-real-estate'),
                                'property_label' => esc_html__('Label', 'essential-real-estate'),
                                'property_price' => esc_html__('Price', 'essential-real-estate'),
                                'property_rooms' => esc_html__('Rooms', 'essential-real-estate'),
                                'property_bedrooms' => esc_html__('Bedrooms', 'essential-real-estate'),
                                'property_bathrooms' => esc_html__('Bathrooms', 'essential-real-estate'),
                                'property_size' => esc_html__('Size', 'essential-real-estate'),
                                'property_land' => esc_html__('Land Area', 'essential-real-estate'),
                                'property_garage' => esc_html__('Garages', 'essential-real-estate'),
                                'property_garage_size' => esc_html__('Garage Size', 'essential-real-estate'),
                                'property_year' => esc_html__('Year Built', 'essential-real-estate'),
                            ),
                            'value_inline' => false,
                            'default' => array()
                        ),
                    )),
                    apply_filters('ere_register_option_compare_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function google_map_option()
        {
            $allowed_html = array(
                'i' => array(
                    'class' => array()
                ),
                'span' => array(
                    'class' => array()
                ),
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array()
                )
            );
            return apply_filters('ere_register_option_google_map', array(
                'id' => 'ere_google_map_option',
                'title' => esc_html__('Google Map', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-admin-site',
                'fields' => array_merge(
                    apply_filters('ere_register_option_google_map_top', array()),
                    apply_filters('ere_register_option_google_map_main', array(
                        array(
                            'id' => 'googlemap_ssl',
                            'title' => esc_html__('Google Maps SSL', 'essential-real-estate'),
                            'subtitle' => esc_html__('Use google maps with ssl', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '0',
                        ),
                        array(
                            'id' => 'googlemap_api_key',
                            'type' => 'text',
                            'title' => esc_html__('Google Maps API KEY', 'essential-real-estate'),
                            'desc' => wp_kses(__('We strongly encourage you to get an APIs Console key and post the code in Theme Options. You can get it from <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key">here</a>', 'essential-real-estate'), $allowed_html),
                            'subtitle' => esc_html__('Enter your google maps api key', 'essential-real-estate'),
                            'default' => 'AIzaSyBqmFdSPp4-iY_BG14j_eUeLwOn9Oj4a4Q'
                        ),
                        array(
                            'id' => 'googlemap_zoom_level',
                            'type' => 'slider',
                            'title' => esc_html__('Default Map Zoom', 'essential-real-estate'),
                            'js_options' => array(
                                'step' => 1,
                                'min' => 1,
                                'max' => 20
                            ),
                            'default' => '12'
                        ),
                        array(
                            'id' => 'googlemap_pin_cluster',
                            'title' => esc_html__('Pin Cluster', 'essential-real-estate'),
                            'subtitle' => esc_html__('Use pin cluster on google map', 'essential-real-estate'),
                            'type' => 'button_set',
                            'options' => array(
                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                '0' => esc_html__('No', 'essential-real-estate'),
                            ),
                            'default' => '1',
                        ),
                        array(
                            'id' => 'googlemap_style',
                            'type' => 'ace_editor',
                            'title' => esc_html__('Style for Google Map', 'essential-real-estate'),
                            'subtitle' => sprintf(__('Use %s https://snazzymaps.com/ %s to create styles', 'essential-real-estate'),
                                '<a href="https://snazzymaps.com/" target="_blank">',
                                '</a>'
                            ),
                            'default' => ''
                        ),
                        array(
                            'id' => 'marker_icon',
                            'type' => 'image',
                            'url' => true,
                            'title' => esc_html__('Map Marker Icon', 'essential-real-estate'),
                            'default' => ERE_PLUGIN_URL . 'public/assets/images/map-marker-icon.png'
                        ),
                        array(
                            'id' => 'cluster_icon',
                            'type' => 'image',
                            'url' => true,
                            'title' => esc_html__('Map Cluster Icon', 'essential-real-estate'),
                            'default' => ERE_PLUGIN_URL . 'public/assets/images/map-cluster-icon.png'
                        ),
	                    array(
		                    'id' => 'googlemap_coordinate_default',
		                    'type' => 'text',
		                    'title' => esc_html__('Default Coordinate', 'essential-real-estate'),
							'desc' => esc_html__('Example: 40.735601,-74.165918', 'essential-real-estate'),
		                    'subtitle' => esc_html__('Enter your default coordinate when add new property', 'essential-real-estate'),
		                    'default' => ''
	                    ),
                    )),
                    apply_filters('ere_register_option_google_map_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         * https://perishablepress.com/integrating-google-no-captcha-recaptcha-wordpress-forms/
         */
        private function captcha_option()
        {
            return apply_filters('ere_register_option_captcha', array(
                'id' => 'ere_captcha_option',
                'title' => esc_html__('Google Captcha', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-lock',
                'fields' => array_merge(
                    apply_filters('ere_register_option_captcha_top', array()),
                    apply_filters('ere_register_option_captcha_main', array(
                        array(
                            'id' => 'enable_captcha',
                            'title' => esc_html__('Enable Google Captcha', 'essential-real-estate'),
                            'subtitle' => sprintf(__('Enable Google Captcha to submit forms. To get reCAPTCHA site key and secret key for your website by %s signing up here %s', 'essential-real-estate'),
                                '<a href="https://www.google.com/recaptcha/admin" target="_blank">',
                                '</a>'),
                            'type' => 'checkbox_list',
                            'options' => array(
                                'login' => esc_html__('Login', 'essential-real-estate'),
                                'register' => esc_html__('Register', 'essential-real-estate'),
                                'reset_password' => esc_html__('Reset Password', 'essential-real-estate'),
                                'contact_agent' => esc_html__('Contact Agent', 'essential-real-estate'),
                                'contact_agency' => esc_html__('Contact Agency', 'essential-real-estate'),
                            ),
                            'value_inline' => false,
                            'default' => array()
                        ),
                        array(
                            'id' => 'captcha_site_key',
                            'type' => 'text',
                            'title' => esc_html__('Site Key', 'essential-real-estate'),
                            'subtitle' => '',
                            'default' => '',
                        ),
                        array(
                            'id' => 'captcha_secret_key',
                            'type' => 'text',
                            'title' => esc_html__('Secret Key', 'essential-real-estate'),
                            'subtitle' => '',
                            'default' => '',
                        ),
                    )),
                    apply_filters('ere_register_option_captcha_bottom', array())
                )
            ));
        }

        /**
         * Property page option
         * @return mixed
         */
        private function property_page_option()
        {
            return apply_filters('ere_register_option_property_page', array(
                'id' => 'ere_property_page_option',
                'title' => esc_html__('Property Page', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-welcome-widgets-menus',
                'fields' => array_merge(
                    apply_filters('ere_register_option_property_page_top', array()),
                    apply_filters('ere_register_option_property_page_main', array(
                        apply_filters('ere_register_option_property_page_main_archive', array(
                            'id' => 'ere_property_archive',
                            'title' => esc_html__('Archive Property', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'enable_archive_search_form',
                                    'title' => esc_html__('Enable Search Form', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Enabled', 'essential-real-estate'),
                                        '0' => esc_html__('Disabled', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => 'hide_archive_search_fields',
                                    'type' => 'checkbox_list',
                                    'title' => esc_html__('Hide Advanced Search Fields', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Choose which fields you want to hide on advanced search page?', 'essential-real-estate'),
									'options' => ere_get_search_form_fields_config(),
                                    'value_inline' => false,
                                    'default' => array(
                                        'property_country', 'property_state', 'property_neighborhood', 'property_label', 'property_rooms'
                                    ),
                                    'required' => array('enable_archive_search_form', '=', array('1'))
                                ),
                                array(
                                    'id' => 'archive_search_price_field_layout',
                                    'title' => esc_html__('Property Price Field Layout', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Dropdown', 'essential-real-estate'),
                                        '1' => esc_html__('Slider', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_archive_search_form', '=', array('1'))
                                ),
                                array(
                                    'id' => 'archive_search_size_field_layout',
                                    'title' => esc_html__('Property Size Field Layout', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Dropdown', 'essential-real-estate'),
                                        '1' => esc_html__('Slider', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_archive_search_form', '=', array('1'))
                                ),
                                array(
                                    'id' => 'archive_search_land_field_layout',
                                    'title' => esc_html__('Property Land Area Field Layout', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '0' => esc_html__('Dropdown', 'essential-real-estate'),
                                        '1' => esc_html__('Slider', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                    'required' => array('enable_archive_search_form', '=', array('1'))
                                ),
                                array(
                                    'id' => 'section_archive_page_option',
                                    'title' => esc_html__('Page Options', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'archive_property_layout_style',
                                            'type' => 'button_set',
                                            'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                            'default' => 'property-grid',
                                            'options' => array(
                                                'property-grid' => esc_html__('Grid', 'essential-real-estate'),
                                                'property-list' => esc_html__('List', 'essential-real-estate')
                                            )
                                        ),
                                        array(
                                            'id' => 'archive_property_items_amount',
                                            'type' => 'text',
                                            'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                            'default' => 15,
                                            'pattern' => '[0-9]*',
                                        ),
                                        array(
                                            'type' => 'text',
                                            'title' => esc_html__('Image Size', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 330x180 (Not Include Unit, Space))', 'essential-real-estate'),
                                            'id' => 'archive_property_image_size',
                                            'default' => '330x180',
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Columns', 'essential-real-estate'),
                                            'id' => 'archive_property_columns',
                                            'options' => array(
                                                '2' => '2',
                                                '3' => '3',
                                                '4' => '4',
                                                '5' => '5',
                                                '6' => '6'
                                            ),
                                            'default' => '3',
                                            'required' => array('archive_property_layout_style', '=', array('property-grid'))
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Columns Gap', 'essential-real-estate'),
                                            'id' => 'archive_property_columns_gap',
                                            'options' => array(
                                                'col-gap-0' => '0px',
                                                'col-gap-10' => '10px',
                                                'col-gap-20' => '20px',
                                                'col-gap-30' => '30px',
                                            ),
                                            'default' => 'col-gap-30',
                                            'required' => array('archive_property_layout_style', '=', array('property-grid'))
                                        ),

                                        /* Responsive */
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Items Desktop Small', 'essential-real-estate'),
                                            'id' => 'archive_property_items_md',
                                            'subtitle' => esc_html__('Browser Width < 1199', 'essential-real-estate'),
                                            'options' => array(
                                                '2' => '2',
                                                '3' => '3',
                                                '4' => '4',
                                                '5' => '5',
                                                '6' => '6',
                                            ),
                                            'default' => '3',
                                            'required' => array('archive_property_layout_style', 'in', array('property-grid'))
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Items Tablet', 'essential-real-estate'),
                                            'id' => 'archive_property_items_sm',
                                            'subtitle' => esc_html__('Browser Width < 992', 'essential-real-estate'),
                                            'options' => array(
                                                '2' => '2',
                                                '3' => '3',
                                                '4' => '4',
                                                '5' => '5',
                                                '6' => '6',
                                            ),
                                            'default' => '2',
                                            'required' => array('archive_property_layout_style', 'in', array('property-grid'))
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Items Tablet Small', 'essential-real-estate'),
                                            'id' => 'archive_property_items_xs',
                                            'subtitle' => esc_html__('Browser Width < 768', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => '1',
                                                '2' => '2',
                                                '3' => '3',
                                                '4' => '4',
                                                '5' => '5',
                                                '6' => '6',
                                            ),
                                            'default' => '1',
                                            'required' => array('archive_property_layout_style', 'in', array('property-grid'))
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Items Mobile', 'essential-real-estate'),
                                            'id' => 'archive_property_items_mb',
                                            'subtitle' => esc_html__('Browser Width < 480', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => '1',
                                                '2' => '2',
                                                '3' => '3',
                                                '4' => '4',
                                                '5' => '5',
                                                '6' => '6',
                                            ),
                                            'default' => '1',
                                            'required' => array('archive_property_layout_style', 'in', array('property-grid'))
                                        )
                                    )
                                ),
                            )
                        )),
                        apply_filters('ere_register_option_property_page_main_single', array(
                            'id' => 'ere_property_single',
                            'title' => esc_html__('Single Property', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'hide_contact_information_if_not_login',
                                    'title' => esc_html__('Hide Contact Information if user not login', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '0',
                                ),
                                array(
                                    'id' => 'hide_empty_features',
                                    'title' => esc_html__('Hide the empty features on the single property page', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'enable_create_date',
                                    'title' => esc_html__('Show Create Date', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                                array(
                                    'id' => 'enable_views_count',
                                    'title' => esc_html__('Show Views Count', 'essential-real-estate'),
                                    'type' => 'button_set',
                                    'options' => array(
                                        '1' => esc_html__('Yes', 'essential-real-estate'),
                                        '0' => esc_html__('No', 'essential-real-estate'),
                                    ),
                                    'default' => '1',
                                ),
                            )
                        )),
                    )),
                    apply_filters('ere_register_option_property_page_bottom', array())
                )
            ));
        }

        /**
         * Agent page option
         * @return mixed
         */
        private function agent_page_option()
        {
            return apply_filters('ere_register_option_agent_page', array(
                'id' => 'ere_agent_page_option',
                'title' => esc_html__('Agent Page', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-businessman',
                'fields' => array_merge(
                    apply_filters('ere_register_option_agent_page_top', array()),
                    apply_filters('ere_register_option_agent_page_main', array(
                        apply_filters('ere_register_option_agent_page_main_archive', array(
                            'id' => 'ere_archive_agent',
                            'title' => esc_html__('Archive Agent', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'type' => 'selectize',
                                    'title' => esc_html__('Agency', 'essential-real-estate'),
                                    'id' => 'agent_agency',
                                    'data' => 'taxonomy',
                                    'data_args' => array('taxonomy' => 'agency', 'args' => array('hide_empty' => 0)),
                                    'multiple' => true,
                                    'subtitle' => esc_html__('Enter agency by names to narrow output', 'essential-real-estate')
                                ),
                                array(
                                    'id' => 'archive_agent_layout_style',
                                    'type' => 'button_set',
                                    'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                    'default' => 'agent-grid',
                                    'options' => array(
                                        'agent-grid' => esc_html__('Grid', 'essential-real-estate'),
                                        'agent-list' => esc_html__('List', 'essential-real-estate')
                                    )
                                ),
                                array(
                                    'id' => 'archive_agent_item_amount',
                                    'type' => 'text',
                                    'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                    'default' => 12,
                                    'pattern' => '[0-9]*',
                                ),
                                array(
                                    'type' => 'text',
                                    'title' => esc_html__('Image Size', 'essential-real-estate'),
                                    'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space))', 'essential-real-estate'),
                                    'id' => 'archive_agent_image_size',
                                    'default' => '270x340',
                                ),
                                array(
                                    'id' => 'archive_agent_columns',
                                    'title' => esc_html__('Columns', 'essential-real-estate'),
                                    'type' => 'group',
                                    'toggle_default' => false,
                                    'required' => array('archive_agent_layout_style', '=', array('agent-grid')),
                                    'fields' => array(
                                        array(
                                            'id' => 'archive_agent_column_lg',
                                            'type' => 'select',
                                            'title' => esc_html__('Column Desktop', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Browser Width >= 1199px', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => esc_html__('1', 'essential-real-estate'),
                                                '2' => esc_html__('2', 'essential-real-estate'),
                                                '3' => esc_html__('3', 'essential-real-estate'),
                                                '4' => esc_html__('4', 'essential-real-estate'),
                                                '5' => esc_html__('5', 'essential-real-estate'),
                                                '6' => esc_html__('6', 'essential-real-estate'),
                                            ),
                                            'default' => '4',
                                        ),
                                        array(
                                            'id' => 'archive_agent_column_md',
                                            'type' => 'select',
                                            'title' => esc_html__('Column Desktop Small', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Browser Width < 1199px', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => esc_html__('1', 'essential-real-estate'),
                                                '2' => esc_html__('2', 'essential-real-estate'),
                                                '3' => esc_html__('3', 'essential-real-estate'),
                                                '4' => esc_html__('4', 'essential-real-estate'),
                                                '5' => esc_html__('5', 'essential-real-estate'),
                                                '6' => esc_html__('6', 'essential-real-estate'),
                                            ),
                                            'default' => '3',
                                        ),
                                        array(
                                            'id' => 'archive_agent_column_sm',
                                            'type' => 'select',
                                            'title' => esc_html__('Column Tablet', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Browser Width < 992px', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => esc_html__('1', 'essential-real-estate'),
                                                '2' => esc_html__('2', 'essential-real-estate'),
                                                '3' => esc_html__('3', 'essential-real-estate'),
                                                '4' => esc_html__('4', 'essential-real-estate'),
                                                '5' => esc_html__('5', 'essential-real-estate'),
                                                '6' => esc_html__('6', 'essential-real-estate'),
                                            ),
                                            'default' => '2',
                                        ),
                                        array(
                                            'id' => 'archive_agent_column_xs',
                                            'type' => 'select',
                                            'title' => esc_html__('Column Tablet Small', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Browser Width < 768px', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => esc_html__('1', 'essential-real-estate'),
                                                '2' => esc_html__('2', 'essential-real-estate'),
                                                '3' => esc_html__('3', 'essential-real-estate'),
                                                '4' => esc_html__('4', 'essential-real-estate'),
                                                '5' => esc_html__('5', 'essential-real-estate'),
                                                '6' => esc_html__('6', 'essential-real-estate'),
                                            ),
                                            'default' => '2',
                                        ),
                                        array(
                                            'id' => 'archive_agent_column_mb',
                                            'type' => 'select',
                                            'title' => esc_html__('Column Mobile', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Browser Width < 480px', 'essential-real-estate'),
                                            'options' => array(
                                                '1' => esc_html__('1', 'essential-real-estate'),
                                                '2' => esc_html__('2', 'essential-real-estate'),
                                                '3' => esc_html__('3', 'essential-real-estate'),
                                                '4' => esc_html__('4', 'essential-real-estate'),
                                                '5' => esc_html__('5', 'essential-real-estate'),
                                                '6' => esc_html__('6', 'essential-real-estate'),
                                            ),
                                            'default' => '1',
                                        )
                                    ),
                                ),
                            )
                        )),
                        apply_filters('ere_register_option_agent_page_main_single', array(
                            'id' => 'ere_single_agent',
                            'title' => esc_html__('Single Agent', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'ere_property_of_agent',
                                    'title' => esc_html__('Properties of Agent', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'enable_property_of_agent',
                                            'title' => esc_html__('Show Properties of Agent', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '1',
                                        ),
                                        array(
                                            'id' => 'property_of_agent_layout_style',
                                            'type' => 'button_set',
                                            'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                            'default' => 'property-grid',
                                            'options' => array(
                                                'property-grid' => esc_html__('Grid', 'essential-real-estate'),
                                                'property-list' => esc_html__('List', 'essential-real-estate')
                                            ),
                                            'required' => array('enable_property_of_agent', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'property_of_agent_items_amount',
                                            'type' => 'text',
                                            'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                            'default' => 6,
                                            'pattern' => '[0-9]*',
                                            'required' => array('enable_property_of_agent', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'property_of_agent_image_size',
                                            'type' => 'text',
                                            'title' => esc_html__('Image Size', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space))', 'essential-real-estate'),
                                            'default' => '330x180',
                                            'required' => array(
                                                array('enable_property_of_agent', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Columns Gap', 'essential-real-estate'),
                                            'id' => 'property_of_agent_columns_gap',
                                            'default' => 'col-gap-30',
                                            'options' => array(
                                                'col-gap-0' => '0px',
                                                'col-gap-10' => '10px',
                                                'col-gap-20' => '20px',
                                                'col-gap-30' => '30px',
                                            ),
                                        ),
                                        array(
                                            'id' => 'property_of_agent_show_paging',
                                            'title' => esc_html__('Show Paging', 'essential-real-estate'),
                                            'type' => 'checkbox_list',
                                            'options' => array(
                                                'show_paging_property_of_agent' => esc_html__('Yes', 'essential-real-estate')
                                            ),
                                            'value_inline' => false,
                                            'default' => array(),
                                            'required' => array('enable_property_of_agent', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'property_of_agent_columns',
                                            'title' => esc_html__('Columns', 'essential-real-estate'),
                                            'type' => 'group',
                                            'toggle_default' => false,
                                            'required' => array(
                                                array('property_of_agent_layout_style', '=', array('property-grid')),
                                                array('enable_property_of_agent', '=', array('1'))
                                            ),
                                            'fields' => array(
                                                array(
                                                    'id' => 'property_of_agent_column_lg',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '3',
                                                ),
                                                array(
                                                    'id' => 'property_of_agent_column_md',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '3',
                                                ),
                                                array(
                                                    'id' => 'property_of_agent_column_sm',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 992px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'property_of_agent_column_xs',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 768px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'property_of_agent_column_mb',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Mobile', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 480px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '1',
                                                )
                                            ),
                                        ),
                                    )
                                ),
                                array(
                                    'id' => 'ere_other_agent',
                                    'title' => esc_html__('Other Agents', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'enable_other_agent',
                                            'title' => esc_html__('Show Other Agents', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '1',
                                        ),
                                        array(
                                            'id' => 'other_agent_layout_style',
                                            'type' => 'button_set',
                                            'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                            'default' => 'agent-slider',
                                            'options' => array(
                                                'agent-slider' => esc_html__('Carousel', 'essential-real-estate'),
                                                'agent-grid' => esc_html__('Grid', 'essential-real-estate'),
                                                'agent-list' => esc_html__('List', 'essential-real-estate')
                                            ),
                                            'required' => array('enable_other_agent', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'other_agents_item_amount',
                                            'type' => 'text',
                                            'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                            'default' => 12,
                                            'required' => array('enable_other_agent', '=', array('1'))
                                        ),
                                        array(
                                            'type' => 'text',
                                            'title' => esc_html__('Image Size', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space))', 'essential-real-estate'),
                                            'id' => 'other_agent_image_size',
                                            'default' => '270x340',
                                            'required' => array(
                                                array('enable_other_agent', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'id' => 'other_agent_show_paging',
                                            'title' => esc_html__('Show Paging', 'essential-real-estate'),
                                            'type' => 'checkbox_list',
                                            'options' => array(
                                                'show_paging_other_agent' => esc_html__('Yes', 'essential-real-estate')
                                            ),
                                            'value_inline' => false,
                                            'default' => array(),
                                            'required' => array(
                                                array('other_agent_layout_style', 'in', array('agent-grid', 'agent-list')),
                                                array('enable_other_agent', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'id' => 'other_agent_columns',
                                            'type' => 'group',
                                            'title' => esc_html__('Columns', 'essential-real-estate'),
                                            'toggle_default' => false,
                                            'required' => array(
                                                array('other_agent_layout_style', 'in', array('agent-grid', 'agent-slider')),
                                                array('enable_other_agent', '=', array('1'))
                                            ),
                                            'fields' => array(
                                                array(
                                                    'id' => 'other_agent_column_lg',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '4',
                                                ),
                                                array(
                                                    'id' => 'other_agent_column_md',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '3',
                                                ),
                                                array(
                                                    'id' => 'other_agent_column_sm',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 992px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'other_agent_column_xs',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 768px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'other_agent_column_mb',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Mobile', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 480px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '1',
                                                )
                                            ),
                                        )
                                    )
                                )
                            )
                        ))
                    )),
                    apply_filters('ere_register_option_agent_page_bottom', array())
                )
            ));
        }

        /**
         * Agency page option
         * @return mixed
         */
        private function agency_page_option()
        {
            return apply_filters('ere_register_option_agency_page', array(
                'id' => 'ere_agency_page_option',
                'title' => esc_html__('Agency Page', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-groups',
                'fields' => array_merge(
                    apply_filters('ere_register_option_agency_page_top', array()),
                    apply_filters('ere_register_option_agency_page_main', array(
                        array(
                            'id' => 'ere_single_agency',
                            'title' => esc_html__('Single Agency', 'essential-real-estate'),
                            'type' => 'group',
                            'fields' => array(
                                array(
                                    'id' => 'ere_property_of_agency',
                                    'title' => esc_html__('Properties of Agency', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'enable_property_of_agency',
                                            'title' => esc_html__('Show Properties of Agency', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '1',
                                        ),
                                        array(
                                            'id' => 'property_of_agency_layout_style',
                                            'type' => 'button_set',
                                            'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                            'default' => 'property-grid',
                                            'options' => array(
                                                'property-grid' => esc_html__('Grid', 'essential-real-estate'),
                                                'property-list' => esc_html__('List', 'essential-real-estate')
                                            ),
                                            'required' => array('enable_property_of_agency', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'property_of_agency_show_paging',
                                            'title' => esc_html__('Show Paging', 'essential-real-estate'),
                                            'type' => 'checkbox_list',
                                            'options' => array(
                                                'show_paging_property_of_agency' => esc_html__('Yes', 'essential-real-estate')
                                            ),
                                            'value_inline' => false,
                                            'default' => array(),
                                            'required' => array('enable_property_of_agency', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'property_of_agency_items_amount',
                                            'type' => 'text',
                                            'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                            'default' => 6,
                                            'pattern' => '[0-9]*',
                                            'required' => array(
                                                array('enable_property_of_agency', '=', array('1')),
                                                array('property_of_agency_show_paging', 'contain', 'show_paging_property_of_agency')
                                            )
                                        ),
                                        array(
                                            'id' => 'property_of_agency_image_size',
                                            'type' => 'text',
                                            'title' => esc_html__('Image Size', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space))', 'essential-real-estate'),
                                            'default' => '330x180',
                                            'required' => array(
                                                array('enable_property_of_agency', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'type' => 'select',
                                            'title' => esc_html__('Columns Gap', 'essential-real-estate'),
                                            'id' => 'property_of_agency_columns_gap',
                                            'default' => 'col-gap-30',
                                            'options' => array(
                                                'col-gap-0' => '0px',
                                                'col-gap-10' => '10px',
                                                'col-gap-20' => '20px',
                                                'col-gap-30' => '30px',
                                            ),
                                            'required' => array(
                                                array('property_of_agency_layout_style', 'in', array('property-grid')),
                                                array('enable_property_of_agency', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'id' => 'property_of_agency_columns',
                                            'title' => esc_html__('Columns', 'essential-real-estate'),
                                            'type' => 'group',
                                            'toggle_default' => false,
                                            'required' => array(
                                                array('property_of_agency_layout_style', 'in', array('property-grid')),
                                                array('enable_property_of_agency', '=', '1')
                                            ),
                                            'fields' => array(
                                                array(
                                                    'id' => 'property_of_agency_column_lg',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '3',
                                                ),
                                                array(
                                                    'id' => 'property_of_agency_column_md',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '3',
                                                ),
                                                array(
                                                    'id' => 'property_of_agency_column_sm',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 992px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'property_of_agency_column_xs',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 768px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'property_of_agency_column_mb',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Mobile', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 480px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '1',
                                                )
                                            ),
                                        ),
                                    )
                                ),
                                array(
                                    'id' => 'ere_agent_of_agency',
                                    'title' => esc_html__('Agents of Agency', 'essential-real-estate'),
                                    'type' => 'group',
                                    'fields' => array(
                                        array(
                                            'id' => 'enable_agents_of_agency',
                                            'title' => esc_html__('Show Agents of Agency', 'essential-real-estate'),
                                            'type' => 'button_set',
                                            'options' => array(
                                                '1' => esc_html__('Yes', 'essential-real-estate'),
                                                '0' => esc_html__('No', 'essential-real-estate'),
                                            ),
                                            'default' => '1',
                                        ),
                                        array(
                                            'id' => 'agents_of_agency_layout_style',
                                            'type' => 'button_set',
                                            'title' => esc_html__('Layout Style', 'essential-real-estate'),
                                            'default' => 'agent-slider',
                                            'options' => array(
                                                'agent-slider' => esc_html__('Carousel', 'essential-real-estate'),
                                                'agent-grid' => esc_html__('Grid', 'essential-real-estate'),
                                                'agent-list' => esc_html__('List', 'essential-real-estate')
                                            ),
                                            'required' => array('enable_agents_of_agency', '=', array('1'))
                                        ),
                                        array(
                                            'id' => 'agents_of_agency_show_paging',
                                            'title' => esc_html__('Show Paging', 'essential-real-estate'),
                                            'type' => 'checkbox_list',
                                            'options' => array(
                                                'show_paging_agents_of_agency' => esc_html__('Yes', 'essential-real-estate')
                                            ),
                                            'value_inline' => false,
                                            'default' => array(),
                                            'required' => array(
                                                array('agents_of_agency_layout_style', '!=', array('agent-slider')),
                                                array('enable_agents_of_agency', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'id' => 'agents_of_agency_item_amount',
                                            'type' => 'text',
                                            'title' => esc_html__('Items Amount', 'essential-real-estate'),
                                            'default' => 12,
                                            'required' => array(
                                                array('enable_agents_of_agency', '=', array('1')),
                                                array('agents_of_agency_show_paging', 'contain', 'show_paging_agents_of_agency'),
                                            )
                                        ),
                                        array(
                                            'type' => 'text',
                                            'title' => esc_html__('Image Size', 'essential-real-estate'),
                                            'subtitle' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space))', 'essential-real-estate'),
                                            'id' => 'agents_of_agency_image_size',
                                            'default' => '270x340',
                                            'required' => array(
                                                array('enable_agents_of_agency', '=', array('1'))
                                            )
                                        ),
                                        array(
                                            'id' => 'agents_of_agency_columns',
                                            'type' => 'group',
                                            'title' => esc_html__('Columns', 'essential-real-estate'),
                                            'toggle_default' => false,
                                            'required' => array(
                                                array('agents_of_agency_layout_style', 'in', array('agent-grid', 'agent-slider')),
                                                array('enable_agents_of_agency', '=', array('1'))
                                            ),
                                            'fields' => array(
                                                array(
                                                    'id' => 'agents_of_agency_column_lg',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width >= 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '4',
                                                ),
                                                array(
                                                    'id' => 'agents_of_agency_column_md',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Desktop Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 1199px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '3',
                                                ),
                                                array(
                                                    'id' => 'agents_of_agency_column_sm',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 992px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'agents_of_agency_column_xs',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Tablet Small', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 768px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '2',
                                                ),
                                                array(
                                                    'id' => 'agents_of_agency_column_mb',
                                                    'type' => 'select',
                                                    'title' => esc_html__('Column Mobile', 'essential-real-estate'),
                                                    'subtitle' => esc_html__('Browser Width < 480px', 'essential-real-estate'),
                                                    'options' => array(
                                                        '1' => esc_html__('1', 'essential-real-estate'),
                                                        '2' => esc_html__('2', 'essential-real-estate'),
                                                        '3' => esc_html__('3', 'essential-real-estate'),
                                                        '4' => esc_html__('4', 'essential-real-estate'),
                                                        '5' => esc_html__('5', 'essential-real-estate'),
                                                        '6' => esc_html__('6', 'essential-real-estate'),
                                                    ),
                                                    'default' => '1',
                                                )
                                            ),
                                        )
                                    )
                                )
                            )
                        )
                    )),
                    apply_filters('ere_register_option_agency_page_bottom', array())
                )
            ));
        }

        /**
         * @return mixed|void
         */
        private function email_management_option()
        {
            return apply_filters('ere_register_option_email_management', array(
                'id' => 'ere_email_management_option',
                'title' => esc_html__('Email Management', 'essential-real-estate'),
                'icon' => 'dashicons dashicons-email-alt',
                'fields' => array_merge(
                    apply_filters('ere_register_option_email_management_top', array()),
                    apply_filters('ere_register_option_email_management_main', array(
                        array(
                            'id' => 'email-new-user',
                            'title' => esc_html__('New Registered User', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_register_user',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_register_user_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_register_user',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your username and password on %website_url', 'essential-real-estate'),
	                                'required' => array('mail_register_user_enable','=','1')
                                ),
                                array(
                                    'id' => 'mail_register_user',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'required' => array('mail_register_user_enable','=','1'),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'default' => esc_html__('Hi there,
Welcome to %website_url! You can login now using the below credentials:
Username:%user_login_register
Password: %user_pass_register
If you have any problems, please contact us.
Thank you!', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_register_user',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_register_user_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_register_user',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('New User Registration', 'essential-real-estate'),
                                    'required' => array('admin_mail_register_user_enable','=','1')
                                ),
                                array(
                                    'id' => 'admin_mail_register_user',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'required' => array('admin_mail_register_user_enable','=','1'),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'default' => esc_html__('New user registration on %website_url.
Username: %user_login_register,
E-mail: %user_email_register', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-approved-agent',
                            'title' => esc_html__('Approved Agent', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_approved_agent',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_approved_agent_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_approved_agent',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your agent account approved', 'essential-real-estate'),
                                    'required' => array('mail_approved_agent_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_approved_agent',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_approved_agent_enable','=','1'),
                                    'default' => esc_html__("Hi there,
Your agent account on %website_url has been approved.
Agent Name:%agent_name
Agent Url: %agent_url", 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_approved_agent',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_approved_agent_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_approved_agent',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Somebody register as agent', 'essential-real-estate'),
                                    'required' => array('admin_mail_approved_agent_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_approved_agent',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_approved_agent_enable','=','1'),
                                    'default' => esc_html__('We received a request register as agent on  %website_url !
Please follow the instructions below to approve agent as soon as possible.
Agent Name:%agent_name
Agent Url: %agent_url', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-activated-package',
                            'title' => esc_html__('Activated Package', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_activated_package',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_activated_package_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_activated_package',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your purchase was activated', 'essential-real-estate'),
                                    'required' => array('mail_activated_package_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_activated_package',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_activated_package_enable','=','1'),
                                    'default' => esc_html__("Hi there,
Welcome to %website_url and thank you for purchasing a plan with us. We are excited you have chosen %website_name . %website_name is a great place to advertise and search properties.
You plan on  %website_url activated! You can now list your properties according to you plan.", 'essential-real-estate'),
                                )
                            )
                        ),

                        array(
                            'id' => 'email-activated-listing',
                            'title' => esc_html__('Activated Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_activated_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_activated_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_activated_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your purchase was activated', 'essential-real-estate'),
                                    'required' => array('mail_activated_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_activated_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_activated_listing_enable','=','1'),
                                    'default' => esc_html__('Hi there,Your purchase on %website_url is activated! You should go and check it out.', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-approved-listing',
                            'title' => esc_html__('Approved Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_approved_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_approved_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_approved_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your listing approved', 'essential-real-estate'),
                                    'required' => array('mail_approved_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_approved_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_approved_listing_enable','=','1'),
                                    'default' => esc_html__("Hi there,
Your listing on %website_url has been approved.

Listing Title:%listing_title
Listing Url: %listing_url", 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-expired-listing',
                            'title' => esc_html__('Expired Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_expired_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_expired_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_expired_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your listing expired', 'essential-real-estate'),
	                                'required' => array('mail_expired_listing_enable','=','1')
                                ),
                                array(
                                    'id' => 'mail_expired_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'required' => array('mail_expired_listing_enable','=','1'),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'default' => esc_html__("Hi,
Your listing on %website_url has been expired.

Listing Title:%listing_title
Listing Url: %listing_url", 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-new-wire-transfer',
                            'title' => esc_html__('New Wire Transfer', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_new_wire_transfer',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_new_wire_transfer_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_new_wire_transfer',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('You ordered a new Wire Transfer', 'essential-real-estate'),
                                    'required' => array('mail_new_wire_transfer_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_new_wire_transfer',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_new_wire_transfer_enable','=','1'),
                                    'default' => esc_html__('We received your Wire Transfer payment request on  %website_url !
Please follow the instructions below in order to start submitting properties as soon as possible.
The invoice number is: %invoice_no, Amount: %total_price.', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_new_wire_transfer',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_new_wire_transfer_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_new_wire_transfer',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Somebody ordered a new Wire Transfer', 'essential-real-estate'),
                                    'required' => array('admin_mail_new_wire_transfer_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_new_wire_transfer',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_new_wire_transfer_enable','=','1'),
                                    'default' => esc_html__('We received your Wire Transfer payment request on  %website_url !
Please follow the instructions below in order to start submitting properties as soon as possible.
The invoice number is: %invoice_no, Amount: %total_price.', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-paid-perlisting',
                            'title' => esc_html__('Paid Submission Per Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_paid_submission_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_paid_submission_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_paid_submission_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your new listing on %website_url', 'essential-real-estate'),
                                    'required' => array('mail_paid_submission_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_paid_submission_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_paid_submission_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have submitted new listing on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_paid_submission_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_paid_submission_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_paid_submission_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('New paid submission on %website_url', 'essential-real-estate'),
                                    'required' => array('admin_mail_paid_submission_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_paid_submission_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_paid_submission_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have a new paid submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-featured-perlisting',
                            'title' => esc_html__('Featured Submission Per Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_featured_submission_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_featured_submission_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_featured_submission_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('New featured upgrade on %website_url', 'essential-real-estate'),
                                    'required' => array('mail_featured_submission_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_featured_submission_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_featured_submission_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have a new featured submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_featured_submission_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_featured_submission_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_featured_submission_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('New featured submission on %website_url', 'essential-real-estate'),
                                    'required' => array('admin_mail_featured_submission_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_featured_submission_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_featured_submission_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have a new featured submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id
The invoice number is: %invoice_no', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-new-submission-listing',
                            'title' => esc_html__('New Submission Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_new_submission_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_new_submission_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_new_submission_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your new listing on %website_url', 'essential-real-estate'),
                                    'required' => array('mail_new_submission_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_new_submission_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_new_submission_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have submitted new listing on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_new_submission_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_new_submission_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_new_submission_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('New submission on %website_url', 'essential-real-estate'),
                                    'required' => array('admin_mail_new_submission_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_new_submission_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_new_submission_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have a new submission on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-new-modification-listing',
                            'title' => esc_html__('New Modification Listing', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_user_mail_new_modification_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_new_modification_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_new_modification_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your new modification listing on %website_url', 'essential-real-estate'),
                                    'required' => array('mail_new_modification_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_new_modification_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_new_modification_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have edited listing on  %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'essential-real-estate'),
                                ),
                                array(
                                    'id' => 'ere_admin_mail_new_modification_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_new_modification_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_new_modification_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('New modification on %website_url', 'essential-real-estate'),
                                    'required' => array('admin_mail_new_modification_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_new_modification_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_new_modification_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have a new modification on %website_url!
Listing Title: %listing_title
Listing ID:  %listing_id', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-expired-listing',
                            'title' => esc_html__('Resend For Approval', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_admin_mail_relist_listing',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('Admin Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'admin_mail_relist_listing_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_admin_mail_relist_listing',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Expired Listing sent for approval on %website_url', 'essential-real-estate'),
                                    'required' => array('admin_mail_relist_listing_enable','=','1'),
                                ),
                                array(
                                    'id' => 'admin_mail_relist_listing',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('admin_mail_relist_listing_enable','=','1'),
                                    'default' => esc_html__('Hi,
        A user has relist a new property on %submission_url! You should go and check it out.
        This is the property title: %submission_title.', 'essential-real-estate'),
                                )
                            )
                        ),
                        array(
                            'id' => 'email-matching-saved-search',
                            'title' => esc_html__('Matching Submission With Saved Searches', 'essential-real-estate'),
                            'type' => 'group',
                            'toggle_default' => false,
                            'fields' => array(
                                array(
                                    'id' => 'ere_matching_saved_search',
                                    'type' => 'info',
                                    'style' => 'info',
                                    'title' => esc_html__('User Email', 'essential-real-estate'),
                                ),
	                            array(
		                            'id' => 'mail_matching_saved_search_enable',
		                            'title' => esc_html__('Enable Send Mail?', 'essential-real-estate'),
		                            'type' => 'button_set',
		                            'options' => array(
			                            '1' => esc_html__('Yes', 'essential-real-estate'),
			                            '0' => esc_html__('No', 'essential-real-estate'),
		                            ),
		                            'default' => '1',
	                            ),
                                array(
                                    'id' => 'subject_mail_matching_saved_search',
                                    'type' => 'text',
                                    'title' => esc_html__('Subject', 'essential-real-estate'),
                                    'default' => esc_html__('Your new listing matching with your saved searches on %website_url', 'essential-real-estate'),
                                    'required' => array('mail_matching_saved_search_enable','=','1'),
                                ),
                                array(
                                    'id' => 'mail_matching_saved_search',
                                    'type' => 'editor',
                                    'args' => array(
                                        'media_buttons' => true,
                                        'quicktags' => true,
                                    ),
                                    'title' => esc_html__('Content', 'essential-real-estate'),
                                    'required' => array('mail_matching_saved_search_enable','=','1'),
                                    'default' => esc_html__('Hi,
You have new listings on %website_url matching with your saved searches:
%listings
If you do not wish to be notified anymore please login your dashboard and delete the saved search
Thank you!', 'essential-real-estate'),
                                ),
                            )
                        ),
                    )),
                    apply_filters('ere_register_option_email_management_bottom', array())
                )
            ));
        }

        public function add_meta_property_status_order_number($term_id, $tt_id ) {
            if (!isset($_POST['property_status_order_number'])) {
                add_term_meta($term_id,'property_status_order_number',1);
            }
        }

	    public function gsf_save_metabox_meta_field_keys($meta_field_keys, $post_id, $current_post_type) {
		    if ($current_post_type === 'property') {
			    $meta_field_keys['real_estate_additional_feature_title'] = array(
				    'type' => 'text',
				    'empty_value' => ''
			    );
			    $meta_field_keys['real_estate_additional_feature_value'] = array(
				    'type' => 'text',
				    'empty_value' => ''
			    );
		    }
		    return $meta_field_keys;
	    }

	    public function get_property_required_fields() {
        	$required_fields = array(
		        'property_title' => esc_html__('Title', 'essential-real-estate'),
		        'property_type' => esc_html__('Type', 'essential-real-estate'),
		        'property_status' => esc_html__('Status', 'essential-real-estate'),
		        'property_label' => esc_html__('Label', 'essential-real-estate'),
		        'property_price' => esc_html__('Price', 'essential-real-estate'),
		        'property_price_prefix' => esc_html__('Before Price Label', 'essential-real-estate'),
		        'property_price_postfix' => esc_html__('After Price Label', 'essential-real-estate'),
		        'property_rooms' => esc_html__('Rooms', 'essential-real-estate'),
		        'property_bedrooms' => esc_html__('Bedrooms', 'essential-real-estate'),
		        'property_bathrooms' => esc_html__('Bathrooms', 'essential-real-estate'),
		        'property_size' => esc_html__('Size', 'essential-real-estate'),
		        'property_land' => esc_html__('Land Area', 'essential-real-estate'),
		        'property_garage' => esc_html__('Garages', 'essential-real-estate'),
		        'property_year' => esc_html__('Year Built', 'essential-real-estate'),
		        'property_map_address' => esc_html__('Address', 'essential-real-estate'),
		        'country' => esc_html__('Country', 'essential-real-estate'),
		        'state' => esc_html__('Province / State', 'essential-real-estate'),
		        'city' => esc_html__('City / Town', 'essential-real-estate'),
		        'neighborhood' => esc_html__('Neighborhood', 'essential-real-estate'),
		        'postal_code' => esc_html__('Postal code', 'essential-real-estate'),
	        );
		    $meta_prefix = ERE_METABOX_PREFIX;
		    $additional_fields = ere_get_option('additional_fields');
		    if ($additional_fields && is_array($additional_fields)) {
			    foreach ($additional_fields as $k => $field) {
				    $id = isset($field['id']) && !empty($field['id'])
					    ? $field['id']
					    : sanitize_title($field['label']);

				    if (in_array($id,array('property_price_short','property_price_prefix','property_price_postfix','property_price_on_call','property_size','property_land','property_rooms','property_bedrooms','property_bathrooms','property_garage','property_garage_size','property_year','property_identity','additional_features','property_featured','property_address','property_zip','property_location','floors_enable','floors','property_images','property_attachments','property_video_url','property_video_image','property_virtual_tour_type','property_virtual_tour','property_image_360','agent_display_option','property_agent','property_other_contact_name','property_other_contact_mail','property_other_contact_phone','property_other_contact_description','private_note'))) {
					    $id = 'additional_detail__' . $id;
				    }
				    $id = $meta_prefix .$id;

				    $required_fields[$id] = $field['label'];

			    }
		    }

        	return apply_filters('ere_get_property_required_fields',$required_fields);

	    }

    }
}
