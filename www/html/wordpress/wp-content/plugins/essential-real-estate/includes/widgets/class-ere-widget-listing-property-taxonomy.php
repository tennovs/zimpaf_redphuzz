<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Widget_Listing_Property_Taxonomy')) {

    class ERE_Widget_Listing_Property_Taxonomy extends ERE_Widget_Acf
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_cssclass = 'ere_widget ere_widget_listing_property_taxonomy';
            $this->widget_description = esc_html__("Display the listing property taxonomy.", 'essential-real-estate');
            $this->widget_id = 'ere_widget_listing_property_taxonomy';
            $this->widget_name = esc_html__('ERE Listing Property Taxonomy', 'essential-real-estate');
            $this->settings = array(
                'extra' => array(
                    array(
                        'name' => 'title',
                        'type' => 'text',
                        'std' => esc_html__('Property Cities', 'essential-real-estate'),
                        'title' => esc_html__('Title:', 'essential-real-estate')
                    ),
                    array(
                        'name' => 'taxonomy',
                        'type' => 'select',
                        'title' => esc_html__('Select Property Taxonomy:', 'essential-real-estate'),
                        'std' => 'type',
                        'options' => array(
                            'type' => esc_html__( 'Type', 'essential-real-estate' ),
                            'status' => esc_html__( 'Status', 'essential-real-estate' ),
                            'city' => esc_html__( 'City / Town', 'essential-real-estate' ),
                            'feature' => esc_html__( 'Features', 'essential-real-estate' ),
                            'neighborhood' => esc_html__( 'Neighborhood', 'essential-real-estate' ),
                            'state' => esc_html__( 'Province / State', 'essential-real-estate' ),
                            'label' => esc_html__( 'Label', 'essential-real-estate' )
                        )
                    ),
                    array(
                        'name' => 'types',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Types:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-type'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('type'))
                    ),
                    array(
                        'name' => 'status',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Status:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-status'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('status'))
                    ),
                    array(
                        'name' => 'cities',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Cities:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-city'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('city'))
                    ),
                    array(
                        'name' => 'features',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Features:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-features'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('features'))
                    ),
                    array(
                        'name' => 'neighborhoods',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Neighborhoods:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-neighborhood'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('neighborhood'))
                    ),
                    array(
                        'name' => 'states',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Province / State:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-state'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('state'))
                    ),
                    array(
                        'name' => 'labels',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Labels:', 'essential-real-estate'),
                        'options' => $this->get_all_taxonomies('property-label'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('label'))
                    ),
                    array(
                        'name' => 'columns',
                        'type' => 'select',
                        'title' => esc_html__('Columns:', 'essential-real-estate'),
                        'options' => array('1'=>'1', '2'=>'2'),
                        'std' => '1'
                    ),
                    array(
                        'name' => 'show_count',
                        'type' => 'checkbox',
                        'value-inline' => true,
                        'std' => '0',
                        'title' => esc_html__('Show Count Item?', 'essential-real-estate')
                    ),
	                array(
		                'name' => 'hide_empty',
		                'type' => 'checkbox',
		                'value-inline' => true,
		                'std' => '0',
		                'title' => esc_html__('Hide Empty Item?', 'essential-real-estate')
	                ),
                    array(
                        'name' => 'color_scheme',
                        'type' => 'select',
                        'title' => esc_html__('Color Scheme:', 'essential-real-estate'),
                        'options' => array(
                            'scheme-dark' => esc_html__( 'Color Light', 'essential-real-estate' ),
                            'scheme-light' => esc_html__( 'Color Dark', 'essential-real-estate' )
                        ),
                        'std' => 'scheme-dark'
                    )
                )
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
            $extra = array_key_exists('extra', $instance) ? $instance['extra'] : array();
            $title = array_key_exists('title', $extra) ? $extra['title'] : '';
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);
            echo wp_kses_post($args['before_widget']);
            if ( $title ) {
                echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
            }

            echo ere_get_template_html('widgets/listing-property-taxonomy/listing-property-taxonomy.php', array('extra' => $extra));

            echo wp_kses_post($args['after_widget']);
        }

        private function get_all_taxonomies($taxonomy_name)
        {
            $list_tax_item = array();
            $taxonomy_items = get_categories( array( 'taxonomy' => $taxonomy_name, 'hide_empty' => 0, 'orderby' => 'ASC' ) );
            foreach($taxonomy_items as $item){
                $list_tax_item[$item->slug] = $item->name;
            }
            return $list_tax_item;
        }
    }
}