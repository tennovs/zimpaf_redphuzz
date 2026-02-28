<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Widget_Search_Form')) {

    class ERE_Widget_Search_Form extends ERE_Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_cssclass = 'ere_widget ere_widget_search_form';
            $this->widget_description = esc_html__("Display the search form.", 'essential-real-estate');
            $this->widget_id = 'ere_widget_search_form';
            $this->widget_name = esc_html__('ERE Search Form', 'essential-real-estate');
            $this->settings = array(
                'title' => array(
                    'type' => 'text',
                    'std' => esc_html__('Search Form', 'essential-real-estate'),
                    'label' => esc_html__('Title:', 'essential-real-estate')
                ),
                'layout'  => array(
                    'type'    => 'select',
                    'std'     => 'tab',
                    'label'   => esc_html__( 'Source', 'essential-real-estate' ),
                    'options' => array(
                        'tab' => esc_html__('Status As Tab','essential-real-estate'),
                        'dropdown' => esc_html__('Status As Dropdown','essential-real-estate')
                    )
                ),
                'status_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Status', 'essential-real-estate')
                ),
                'type_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Type', 'essential-real-estate')
                ),
                'title_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Property Title', 'essential-real-estate')
                ),
                'address_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Address', 'essential-real-estate')
                ),
                'country_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Country', 'essential-real-estate')
                ),
                'state_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Province / State', 'essential-real-estate')
                ),
                'city_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('City / Town', 'essential-real-estate')
                ),
                'neighborhood_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Neighborhood', 'essential-real-estate')
                ),
                'rooms_enable' => array(
	                'type' => 'checkbox',
	                'std' => false,
	                'label' => esc_html__('Rooms', 'essential-real-estate')
                ),

                'bedrooms_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Bedrooms', 'essential-real-estate')
                ),
                'bathrooms_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Bathrooms', 'essential-real-estate')
                ),
                'price_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Price', 'essential-real-estate')
                ),
                'price_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Price?', 'essential-real-estate')
                ),
                'area_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Size', 'essential-real-estate')
                ),
                'area_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Size?', 'essential-real-estate')
                ),
                'land_area_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Land Area', 'essential-real-estate')
                ),
                'land_area_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Land Area?', 'essential-real-estate')
                ),
                'label_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Label', 'essential-real-estate')
                ),
                'garage_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Number Garage', 'essential-real-estate')
                ),
                'property_identity_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Property ID', 'essential-real-estate')
                ),
            );

            parent::__construct();
        }
        /**
         * Output widget
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
            $this->widget_start($args, $instance);

            echo ere_get_template_html('widgets/search-form/search-form.php', array('args' => $args, 'instance' => $instance));

            $this->widget_end($args);
        }
    }
}