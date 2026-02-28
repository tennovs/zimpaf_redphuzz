<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Vc_map')) {
    /**
     * Class ERE_Vc_map
     */
    class ERE_Vc_map
    {

        /**
         * Register vc_map if visual composer activated
         */
        public function register_vc_map()
        {
            if (!function_exists('vc_map')) return;
            vc_map(array(
                'name' => esc_html__('Property', 'essential-real-estate'),
                'base' => 'ere_property',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Layout Style', 'essential-real-estate'),
                        'param_name' => 'layout_style',
                        'admin_label' => true,
                        'value' => array(
                            esc_html__('Grid', 'essential-real-estate') => 'property-grid',
                            esc_html__('List', 'essential-real-estate') => 'property-list',
                            esc_html__('Zig Zac', 'essential-real-estate') => 'property-zigzac',
                            esc_html__('Carousel', 'essential-real-estate') => 'property-carousel',
                        ),
                        'std' => 'property-grid',
                        'description' => esc_html__('Select Layout Style.', 'essential-real-estate')
                    ),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-type', 'property_type', esc_html__('Type', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-status', 'property_status', esc_html__('Status', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-feature', 'property_feature', esc_html__('Feature', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-city', 'property_city', esc_html__('City / Town', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-state', 'property_state', esc_html__('Province / State', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-neighborhood', 'property_neighborhood', esc_html__('Neighborhood', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-label', 'property_label', esc_html__('Label', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Property Featured', 'essential-real-estate'),
                        'param_name' => 'property_featured',
                        'std' => 'false',
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '6',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Columns', 'essential-real-estate'),
                        'param_name' => 'columns',
                        'value' => array(
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6'
                        ),
                        'std' => '3',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Desktop Small', 'essential-real-estate'),
                        'param_name' => 'items_md',
                        'description' => esc_html__('Browser Width < 1199', 'essential-real-estate'),
                        'value' => array(
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '3',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Tablet', 'essential-real-estate'),
                        'param_name' => 'items_sm',
                        'description' => esc_html__('Browser Width < 992', 'essential-real-estate'),
                        'value' => array(
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '2',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Tablet Small', 'essential-real-estate'),
                        'param_name' => 'items_xs',
                        'description' => esc_html__('Browser Width < 768', 'essential-real-estate'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '1',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Mobile', 'essential-real-estate'),
                        'param_name' => 'items_mb',
                        'description' => esc_html__('Browser Width < 480', 'essential-real-estate'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '1',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 , Zic Zac: 290x270 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size',
                        'std' => '330x180',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Columns Gap', 'essential-real-estate'),
                        'param_name' => 'columns_gap',
                        'value' => array(
                            '0px' => 'col-gap-0',
                            '10px' => 'col-gap-10',
                            '20px' => 'col-gap-20',
                            '30px' => 'col-gap-30',
                        ),
                        'std' => 'col-gap-30',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('View All Link', 'essential-real-estate'),
                        'param_name' => 'view_all_link',
                        'value' => '',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Paging', 'essential-real-estate'),
                        'param_name' => 'show_paging',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value_not_equal_to' => 'property-carousel'),
                    ),
                    array(
                        'param_name' => 'include_heading',
                        'type' => 'checkbox',
                        'heading' => esc_html__('Include Heading', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'essential-real-estate'),
                        'param_name' => 'heading_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Sub Title', 'essential-real-estate'),
                        'param_name' => 'heading_sub_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Pagination Control', 'essential-real-estate'),
                        'param_name' => 'dots',
                        'dependency' => array('element' => 'layout_style', 'value' => 'property-carousel'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Navigation Control', 'essential-real-estate'),
                        'param_name' => 'nav',
                        'dependency' => array('element' => 'layout_style', 'value' => 'property-carousel'),
                        'std' => 'true',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Heading Contain Navigation Bar', 'essential-real-estate'),
                        'param_name' => 'move_nav',
                        'dependency' => array('element' => 'nav', 'value' => 'true'),
                        'std' => 'false',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Navigation Position', 'essential-real-estate'),
                        'param_name' => 'nav_position',
                        'value' => array(
                            esc_html__('Middle Center', 'essential-real-estate') => '',
                            esc_html__('Top Right', 'essential-real-estate') => 'top-right',
                            esc_html__('Bottom Center', 'essential-real-estate') => 'bottom-center',
                        ),
                        'std' => '',
                        'dependency' => array('element' => 'move_nav', 'value_not_equal_to' => 'true'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Auto play', 'essential-real-estate'),
                        'param_name' => 'autoplay',
                        'dependency' => array('element' => 'layout_style', 'value' => 'property-carousel'),
                        'std' => 'true',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Autoplay Timeout', 'essential-real-estate'),
                        'param_name' => 'autoplaytimeout',
                        'description' => esc_html__('Autoplay interval timeout.', 'essential-real-estate'),
                        'value' => '',
                        'std' => 1000,
                        'dependency' => array('element' => 'autoplay', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'paged',
                        'value' => '1',
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'author_id',
                        'value' => '',
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'agent_id',
                        'value' => '',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Carousel with Left Navigation', 'essential-real-estate'),
                'base' => 'ere_property_carousel',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '6'
                    ),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-type', 'property_type', esc_html__('Type', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-status', 'property_status', esc_html__('Status', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-feature', 'property_feature', esc_html__('Feature', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-city', 'property_city', esc_html__('City / Town', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-state', 'property_state', esc_html__('Province / State', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-neighborhood', 'property_neighborhood', esc_html__('Neighborhood', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-label', 'property_label', esc_html__('Label', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Property Featured', 'essential-real-estate'),
                        'param_name' => 'property_featured',
                        'std' => 'false',
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size',
                        'std' => '330x180'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Columns Gap', 'essential-real-estate'),
                        'param_name' => 'columns_gap',
                        'value' => array(
                            '0px' => 'col-gap-0',
                            '10px' => 'col-gap-10',
                            '20px' => 'col-gap-20',
                            '30px' => 'col-gap-30',
                        ),
                        'std' => 'col-gap-0',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Color Scheme', 'essential-real-estate'),
                        'param_name' => 'color_scheme',
                        'value' => array(
                            esc_html__('Dark', 'essential-real-estate') => 'color-dark',
                            esc_html__('Light', 'essential-real-estate') => 'color-light'
                        ),
                        'std' => 'color-dark',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'param_name' => 'include_heading',
                        'type' => 'checkbox',
                        'heading' => esc_html__('Include Heading', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'essential-real-estate'),
                        'param_name' => 'heading_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Sub Title', 'essential-real-estate'),
                        'param_name' => 'heading_sub_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Slider', 'essential-real-estate'),
                'base' => 'ere_property_slider',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Layout Style', 'essential-real-estate'),
                        'param_name' => 'layout_style',
                        'admin_label' => true,
                        'value' => array(
                            esc_html__('Navigation Middle', 'essential-real-estate') => 'navigation-middle',
                            esc_html__('Pagination as Image', 'essential-real-estate') => 'pagination-image'
                        ),
                        'std' => 'navigation-middle'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '6'
                    ),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-type', 'property_type', esc_html__('Type', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-status', 'property_status', esc_html__('Status', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-feature', 'property_feature', esc_html__('Feature', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-city', 'property_city', esc_html__('City / Town', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-state', 'property_state', esc_html__('Province / State', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-neighborhood', 'property_neighborhood', esc_html__('Neighborhood', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-label', 'property_label', esc_html__('Label', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Property Featured', 'essential-real-estate'),
                        'param_name' => 'property_featured',
                        'std' => 'false',
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 1200x600 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size',
                        'std' => '1200x600'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Gallery', 'essential-real-estate'),
                'base' => 'ere_property_gallery',
                'class' => '',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Display Carousel?', 'essential-real-estate'),
                        'param_name' => 'is_carousel',
                        'admin_label' => true,
                        'std' => false,
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Color Scheme', 'essential-real-estate'),
                        'param_name' => 'color_scheme',
                        'value' => array(
                            esc_html__('Dark', 'essential-real-estate') => 'color-dark',
                            esc_html__('Light', 'essential-real-estate') => 'color-light'
                        ),
                        'std' => 'color-dark',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'param_name' => 'category_filter',
                        'type' => 'checkbox',
                        'heading' => esc_html__('Category Filter', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Filter Style', 'essential-real-estate'),
                        'param_name' => 'filter_style',
                        'value' => array(
                            esc_html__('Isotope', 'essential-real-estate') => 'filter-isotope',
                            esc_html__('Ajax', 'essential-real-estate') => 'filter-ajax'
                        ),
                        'description' => esc_html__('Not applicable for carousel', 'essential-real-estate'),
                        'std' => 'filter-isotope',
                        'dependency' => array('element' => 'category_filter', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'param_name' => 'include_heading',
                        'type' => 'checkbox',
                        'heading' => esc_html__('Include Heading', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'category_filter', 'value' => 'true')
                    ),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-type', 'property_types', esc_html__('Type', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-status', 'property_status', esc_html__('Status', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-feature', 'property_feature', esc_html__('Feature', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-city', 'property_city', esc_html__('City / Town', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-state', 'property_state', esc_html__('Province / State', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-neighborhood', 'property_neighborhood', esc_html__('Neighborhood', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-label', 'property_label', esc_html__('Label', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Property Featured', 'essential-real-estate'),
                        'param_name' => 'property_featured',
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'essential-real-estate'),
                        'param_name' => 'heading_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Sub Title', 'essential-real-estate'),
                        'param_name' => 'heading_sub_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '6',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 290x270 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size',
                        'std' => '290x270'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Columns', 'essential-real-estate'),
                        'param_name' => 'columns',
                        'value' => array(
                            '2' => '2',
                            '3' => '3',
                            '4' => '4'
                        ),
                        'std' => '4',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Columns Gap', 'essential-real-estate'),
                        'param_name' => 'columns_gap',
                        'value' => array(
                            '0px' => 'col-gap-0',
                            '10px' => 'col-gap-10',
                            '20px' => 'col-gap-20',
                            '30px' => 'col-gap-30',
                        ),
                        'std' => 'col-gap-0',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Pagination Control', 'essential-real-estate'),
                        'param_name' => 'dots',
                        'dependency' => array('element' => 'is_carousel', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Navigation Control', 'essential-real-estate'),
                        'param_name' => 'nav',
                        'dependency' => array('element' => 'is_carousel', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Auto play', 'essential-real-estate'),
                        'param_name' => 'autoplay',
                        'dependency' => array('element' => 'is_carousel', 'value' => 'true'),
                        'std' => 'true',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Autoplay Timeout', 'essential-real-estate'),
                        'param_name' => 'autoplaytimeout',
                        'description' => esc_html__('Autoplay interval timeout.', 'essential-real-estate'),
                        'value' => '',
                        'std' => 1000,
                        'dependency' => array('element' => 'autoplay', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'property_type',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                ),
            ));
            vc_map(array(
                'name' => esc_html__('Property Featured', 'essential-real-estate'),
                'base' => 'ere_property_featured',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Layout Style', 'essential-real-estate'),
                        'param_name' => 'layout_style',
                        'value' => array(
                            esc_html__('List Two Columns', 'essential-real-estate') => 'property-list-two-columns',
                            esc_html__('Cities Filter', 'essential-real-estate') => 'property-cities-filter',
                            esc_html__('Single Carousel', 'essential-real-estate') => 'property-single-carousel',
                            esc_html__('Sync Carousel', 'essential-real-estate') => 'property-sync-carousel',
                        ),
                        'std' => 'property-list-two-columns',
                        'admin_label' => true,
                        'description' => esc_html__('Select Layout Style.', 'essential-real-estate')
                    ),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-type', 'property_type', esc_html__('Type', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-status', 'property_status', esc_html__('Status', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-feature', 'property_feature', esc_html__('Feature', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-city', 'property_cities', esc_html__('City / Town', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-state', 'property_state', esc_html__('Province / State', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-neighborhood', 'property_neighborhood', esc_html__('Neighborhood', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array_merge($this->vc_map_add_narrow_taxonomy('property-label', 'property_label', esc_html__('Label', 'essential-real-estate')), array(
                        'group' => esc_html__('Filter Property', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    )),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Color Scheme', 'essential-real-estate'),
                        'param_name' => 'color_scheme',
                        'value' => array(
                            esc_html__('Dark', 'essential-real-estate') => 'color-dark',
                            esc_html__('Light', 'essential-real-estate') => 'color-light'
                        ),
                        'std' => 'color-dark',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '6',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 240x180 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size1',
                        'std' => '240x180',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-list-two-columns'))
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 835x320 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size2',
                        'std' => '835x320',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-cities-filter'))
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 570x320 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size3',
                        'std' => '570x320',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-single-carousel'))
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 945x605 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size4',
                        'std' => '945x605',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('property-sync-carousel'))
                    ),
                    array(
                        'param_name' => 'include_heading',
                        'type' => 'checkbox',
                        'heading' => esc_html__('Include Heading', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'essential-real-estate'),
                        'param_name' => 'heading_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Sub Title', 'essential-real-estate'),
                        'param_name' => 'heading_sub_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Text Align', 'essential-real-estate'),
                        'param_name' => 'heading_text_align',
                        'description' => esc_html__('Select heading alignment.', 'essential-real-estate'),
                        'value' => array(
                            esc_html__('Left', 'essential-real-estate') => 'text-left',
                            esc_html__('Center', 'essential-real-estate') => 'text-center',
                            esc_html__('Right', 'essential-real-estate') => 'text-right',
                        ),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'property_city',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Type', 'essential-real-estate'),
                'base' => 'ere_property_type',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array_merge($this->vc_map_add_narrow_property_type(), array(
                        'admin_label' => true
                    )),
                    array(
                        'type' => 'attach_image',
                        'heading' => esc_html__('Upload Type Image', 'essential-real-estate'),
                        'param_name' => 'type_image',
                        'value' => '',
                        'description' => esc_html__('Upload the custom image.', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'param_name' => 'image_size',
                        'value' => 'full',
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 200x100 (Not Include Unit, Space)).', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Map', 'essential-real-estate'),
                'base' => 'ere_property_map',
                'icon' => 'fa fa-map-marker',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Map Style', 'essential-real-estate'),
                        'param_name' => 'map_style',
                        'value' => array(
                            esc_html__('Normal', 'essential-real-estate') => 'normal',
                            esc_html__('Single Property', 'essential-real-estate') => 'property'
                        ),
                        'std' => 'property',
                        'admin_label' => true
                    ),
                    array(
                        'type' => 'attach_image',
                        'heading' => esc_html__('Marker Icon', 'essential-real-estate'),
                        'param_name' => 'icon',
                        'value' => '',
                        'description' => esc_html__('Choose an image from media library.', 'essential-real-estate'),
                    ),
                    array(
                        'heading' => esc_html__('Property ID', 'essential-real-estate'),
                        'type' => 'textfield',
                        'param_name' => 'property_id',
                        'value' => '',
                        'dependency' => array('element' => 'map_style', 'value' => 'property')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Latitude ', 'essential-real-estate'),
                        'param_name' => 'lat',
                        'value' => '',
                        'dependency' => array('element' => 'map_style', 'value' => 'normal')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Longitude ', 'essential-real-estate'),
                        'param_name' => 'lng',
                        'value' => '',
                        'dependency' => array('element' => 'map_style', 'value' => 'normal')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Map height (px or %)', 'essential-real-estate'),
                        'param_name' => 'map_height',
                        'edit_field_class' => 'vc_col-sm-6',
                        'std' => '500px',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));

            vc_map(array(
                'name' => esc_html__('Property Search', 'essential-real-estate'),
                'base' => 'ere_property_search',
                'icon' => 'fa fa-search',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array_merge(
                	array(
		                array(
			                'type' => 'dropdown',
			                'heading' => esc_html__('Search Form Style', 'essential-real-estate'),
			                'param_name' => 'search_styles',
			                'value' => array(
				                esc_html__('Form Default ', 'essential-real-estate') => 'style-default',
				                esc_html__('Form Default Small ', 'essential-real-estate') => 'style-default-small',
				                esc_html__('Mini Inline', 'essential-real-estate') => 'style-mini-line',
				                esc_html__('Form Absolute Map ', 'essential-real-estate') => 'style-absolute',
				                esc_html__('Map Vertical', 'essential-real-estate') => 'style-vertical',
			                ),
		                ),
		                array(
			                'type' => 'checkbox',
			                'heading' => esc_html__('Show status tab', 'essential-real-estate'),
			                'description' => __('Select property status field like tab.', 'essential-real-estate'),
			                'param_name' => 'show_status_tab',
			                'value' => array(esc_html__('Yes', 'essential-real-estate') => 'true'),
			                'std' => 'true',
			                'dependency' => array('element' => 'search_styles', 'value' => array('style-default', 'style-default-small', 'style-absolute', 'style-vertical'))
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'status_enable',
			                'value' => array(esc_html__('Status', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'type_enable',
			                'value' => array(esc_html__('Type', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'keyword_enable',
			                'value' => array(esc_html__('Keyword', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'title_enable',
			                'value' => array(esc_html__('Title', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'address_enable',
			                'value' => array(esc_html__('Address', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'country_enable',
			                'value' => array(esc_html__('Country', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'state_enable',
			                'value' => array(esc_html__('Province / State', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'city_enable',
			                'value' => array(esc_html__('City / Town', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'neighborhood_enable',
			                'value' => array(esc_html__('Neighborhood', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'rooms_enable',
			                'value' => array(esc_html__('Rooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'bedrooms_enable',
			                'value' => array(esc_html__('Bedrooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'bathrooms_enable',
			                'value' => array(esc_html__('Bathrooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'price_enable',
			                'value' => array(esc_html__('Price', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'price_is_slider',
			                'value' => array(esc_html__('Show Slider for Price?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'price_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'area_enable',
			                'value' => array(esc_html__('Size', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'area_is_slider',
			                'value' => array(esc_html__('Show Slider for Size?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'area_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'land_area_enable',
			                'value' => array(esc_html__('Land Area', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'land_area_is_slider',
			                'value' => array(esc_html__('Show Slider for Land Area?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'land_area_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'label_enable',
			                'value' => array(esc_html__('Label', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'garage_enable',
			                'value' => array(esc_html__('Number Garage', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'property_identity_enable',
			                'value' => array(esc_html__('Property ID', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
	                ),
	                $this->vc_map_add_additions_search_form_fields(),
	                array(
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'other_features_enable',
			                'value' => array(esc_html__('Other Features', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'heading' => esc_html__('Map Search  Enable', 'essential-real-estate'),
			                'param_name' => 'map_search_enable',
			                'description' => __('Show map and search properties with form and show result by map', 'essential-real-estate'),
			                'value' => array(esc_html__('Yes', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'search_styles', 'value' => array('style-mini-line', 'style-default', 'style-default-small'))
		                ),
		                array(
			                'type' => 'dropdown',
			                'heading' => esc_html__('Color Scheme', 'essential-real-estate'),
			                'param_name' => 'color_scheme',
			                'value' => array(
				                esc_html__('Dark', 'essential-real-estate') => 'color-dark',
				                esc_html__('Light', 'essential-real-estate') => 'color-light'
			                ),
			                'std' => 'color-light',
			                'edit_field_class' => 'vc_col-sm-6 vc_column'
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => __('Extra class name', 'essential-real-estate'),
			                'param_name' => 'el_class',
			                'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
		                ),
	                )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Search Map', 'essential-real-estate'),
                'base' => 'ere_property_search_map',
                'icon' => 'fa fa-search',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array_merge(
	                array(
		                array(
			                'type' => 'checkbox',
			                'heading' => esc_html__('Show status tab', 'essential-real-estate'),
			                'description' => __('Select property status field like tab.', 'essential-real-estate'),
			                'param_name' => 'show_status_tab',
			                'value' => array(esc_html__('Yes', 'essential-real-estate') => 'true'),
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'status_enable',
			                'value' => array(esc_html__('Status', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'type_enable',
			                'value' => array(esc_html__('Type', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'keyword_enable',
			                'value' => array(esc_html__('Keyword', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'title_enable',
			                'value' => array(esc_html__('Title', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'address_enable',
			                'value' => array(esc_html__('Address', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'country_enable',
			                'value' => array(esc_html__('Country', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'state_enable',
			                'value' => array(esc_html__('Province / State', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'city_enable',
			                'value' => array(esc_html__('City / Town', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'neighborhood_enable',
			                'value' => array(esc_html__('Neighborhood', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'rooms_enable',
			                'value' => array(esc_html__('Rooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'bedrooms_enable',
			                'value' => array(esc_html__('Bedrooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'bathrooms_enable',
			                'value' => array(esc_html__('Bathrooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'price_enable',
			                'value' => array(esc_html__('Price', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'price_is_slider',
			                'value' => array(esc_html__('Show Slider for Price?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'price_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'area_enable',
			                'value' => array(esc_html__('Size', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'area_is_slider',
			                'value' => array(esc_html__('Show Slider for Size?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'area_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'land_area_enable',
			                'value' => array(esc_html__('Land Area', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'land_area_is_slider',
			                'value' => array(esc_html__('Show Slider for Land Area?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'land_area_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'label_enable',
			                'value' => array(esc_html__('Label', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'garage_enable',
			                'value' => array(esc_html__('Number Garage', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'property_identity_enable',
			                'value' => array(esc_html__('Property ID', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
	                ),
	                $this->vc_map_add_additions_search_form_fields(),
	                array(
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'other_features_enable',
			                'value' => array(esc_html__('Other Features', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'show_advanced_search_btn',
			                'value' => array(esc_html__('Show Advanced Search Button', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => esc_html__('Items Amount', 'essential-real-estate'),
			                'param_name' => 'item_amount',
			                'std' => '18',
			                'edit_field_class' => 'vc_col-sm-6 vc_column'
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => esc_html__('Marker Property Image Size', 'essential-real-estate'),
			                'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 100x100 (Not Include Unit, Space)).', 'essential-real-estate'),
			                'param_name' => 'marker_image_size',
			                'std' => '100x100',
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => __('Extra class name', 'essential-real-estate'),
			                'param_name' => 'el_class',
			                'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
		                ),
	                )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Property Advanced Search', 'essential-real-estate'),
                'base' => 'ere_property_advanced_search',
                'icon' => 'fa fa-search',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array_merge(
	                array(
		                array(
			                'type' => 'dropdown',
			                'heading' => esc_html__('Layout Style', 'essential-real-estate'),
			                'param_name' => 'layout',
			                'value' => array(
				                esc_html__('Status As Tab', 'essential-real-estate') => 'tab',
				                esc_html__('Status As Dropdown', 'essential-real-estate') => 'dropdown',
			                ),
		                ),
		                array(
			                'type' => 'dropdown',
			                'heading' => esc_html__('Column', 'essential-real-estate'),
			                'param_name' => 'column',
			                'value' => array(
				                esc_html__('1', 'essential-real-estate') => '1',
				                esc_html__('2', 'essential-real-estate') => '2',
				                esc_html__('3', 'essential-real-estate') => '3',
				                esc_html__('4', 'essential-real-estate') => '4'
			                ),
			                'std' => '3',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'status_enable',
			                'value' => array(esc_html__('Status', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'type_enable',
			                'value' => array(esc_html__('Type', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'keyword_enable',
			                'value' => array(esc_html__('Keyword', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'title_enable',
			                'value' => array(esc_html__('Title', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'address_enable',
			                'value' => array(esc_html__('Address', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'country_enable',
			                'value' => array(esc_html__('Country', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'state_enable',
			                'value' => array(esc_html__('Province / State', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'city_enable',
			                'value' => array(esc_html__('City / Town', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'neighborhood_enable',
			                'value' => array(esc_html__('Neighborhood', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'rooms_enable',
			                'value' => array(esc_html__('Rooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'bedrooms_enable',
			                'value' => array(esc_html__('Bedrooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'bathrooms_enable',
			                'value' => array(esc_html__('Bathrooms', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'price_enable',
			                'value' => array(esc_html__('Price', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'std' => 'true',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'price_is_slider',
			                'value' => array(esc_html__('Show Slider for Price?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'price_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'area_enable',
			                'value' => array(esc_html__('Size', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'area_is_slider',
			                'value' => array(esc_html__('Show Slider for Size?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'area_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'land_area_enable',
			                'value' => array(esc_html__('Land Area', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'land_area_is_slider',
			                'value' => array(esc_html__('Show Slider for Land Area?', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
			                'dependency' => array('element' => 'land_area_enable', 'value' => 'true')
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'label_enable',
			                'value' => array(esc_html__('Label', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'garage_enable',
			                'value' => array(esc_html__('Number Garage', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'property_identity_enable',
			                'value' => array(esc_html__('Property ID', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),

	                ),
	                $this->vc_map_add_additions_search_form_fields(),
	                array(
		                array(
			                'type' => 'checkbox',
			                'param_name' => 'other_features_enable',
			                'value' => array(esc_html__('Other Features', 'essential-real-estate') => 'true'),
			                'edit_field_class' => 'vc_col-sm-6 vc_column',
		                ),
		                array(
			                'type' => 'dropdown',
			                'heading' => esc_html__('Color Scheme', 'essential-real-estate'),
			                'param_name' => 'color_scheme',
			                'value' => array(
				                esc_html__('Dark', 'essential-real-estate') => 'color-dark',
				                esc_html__('Light', 'essential-real-estate') => 'color-light'
			                ),
			                'std' => 'color-light',
		                ),
		                array(
			                'type' => 'textfield',
			                'heading' => __('Extra class name', 'essential-real-estate'),
			                'param_name' => 'el_class',
			                'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
		                ),
	                )
                )

            ));

            vc_map(array(
                'name' => esc_html__('Property Mini Search', 'essential-real-estate'),
                'base' => 'ere_property_mini_search',
                'icon' => 'fa fa-search',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'checkbox',
                        'description' => __('Check to show status search field.', 'essential-real-estate'),
                        'param_name' => 'status_enable',
                        'value' => array(esc_html__('Status', 'essential-real-estate') => 'true'),
                        'std' => 'true',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    ),
                )
            ));
            vc_map(array(
                'name' => esc_html__('Agent', 'essential-real-estate'),
                'base' => 'ere_agent',
                'icon' => 'fa fa-user-plus',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    $this->vc_map_add_narrow_taxonomy('agency', 'agency', esc_html__('Agency', 'essential-real-estate')),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Layout Style', 'essential-real-estate'),
                        'param_name' => 'layout_style',
                        'value' => array(
                            esc_html__('Carousel', 'essential-real-estate') => 'agent-slider',
                            esc_html__('Grid', 'essential-real-estate') => 'agent-grid',
                            esc_html__('List', 'essential-real-estate') => 'agent-list',
                        ),
                        'std' => 'agent-slider',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '12',
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Columns', 'essential-real-estate'),
                        'param_name' => 'items',
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6'
                        ),
                        'std' => '4',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'dependency' => array('element' => 'layout_style', 'value' => array('agent-grid', 'agent-slider'))
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Image Size', 'essential-real-estate'),
                        'description' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space)).', 'essential-real-estate'),
                        'param_name' => 'image_size',
                        'std' => '270x340',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Paging', 'essential-real-estate'),
                        'param_name' => 'show_paging',
                        'dependency' => array('element' => 'layout_style', 'value' => array('agent-grid', 'agent-list')),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show pagination control', 'essential-real-estate'),
                        'param_name' => 'dots',
                        'dependency' => array('element' => 'layout_style', 'value' => 'agent-slider'),
                        'edit_field_class' => 'vc_col-sm-4 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show navigation control', 'essential-real-estate'),
                        'param_name' => 'nav',
                        'dependency' => array('element' => 'layout_style', 'value' => 'agent-slider'),
                        'std' => 'true',
                        'edit_field_class' => 'vc_col-sm-4 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Navigation Position', 'essential-real-estate'),
                        'param_name' => 'nav_position',
                        'value' => array(
                            esc_html__('Center', 'essential-real-estate') => 'center',
                            esc_html__('Top Right', 'essential-real-estate') => 'top-right',
                        ),
                        'std' => 'center',
                        'dependency' => array('element' => 'nav', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-4 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Auto play', 'essential-real-estate'),
                        'param_name' => 'autoplay',
                        'dependency' => array('element' => 'layout_style', 'value' => 'agent-slider'),
                        'std' => 'true',
                        'edit_field_class' => 'vc_col-sm-4 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Autoplay Timeout', 'essential-real-estate'),
                        'param_name' => 'autoplaytimeout',
                        'description' => esc_html__('Autoplay interval timeout.', 'essential-real-estate'),
                        'value' => '',
                        'std' => 1000,
                        'dependency' => array('element' => 'autoplay', 'value' => 'true'),
                        'edit_field_class' => 'vc_col-sm-4 vc_column',
                        'group' => esc_html__('Carousel Options', 'essential-real-estate')
                    ),
	                array(
		                'type' => 'checkbox',
		                'heading' => esc_html__('Loop', 'essential-real-estate'),
		                'param_name' => 'loop',
		                'dependency' => array('element' => 'layout_style', 'value' => 'agent-slider'),
		                'std' => '',
		                'edit_field_class' => 'vc_col-sm-4 vc_column',
		                'group' => esc_html__('Carousel Options', 'essential-real-estate')
	                ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Desktop Small', 'essential-real-estate'),
                        'param_name' => 'items_md',
                        'description' => esc_html__('Browser Width < 1199', 'essential-real-estate'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '3',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'dependency' => array(
                            'element' => 'layout_style',
                            'value' => array('agent-grid', 'agent-slider'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Tablet', 'essential-real-estate'),
                        'param_name' => 'items_sm',
                        'description' => esc_html__('Browser Width < 992', 'essential-real-estate'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '2',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'dependency' => array(
                            'element' => 'layout_style',
                            'value' => array('agent-grid', 'agent-slider'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Tablet Small', 'essential-real-estate'),
                        'param_name' => 'items_xs',
                        'description' => esc_html__('Browser Width < 768', 'essential-real-estate'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '2',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'dependency' => array(
                            'element' => 'layout_style',
                            'value' => array('agent-grid', 'agent-slider'),
                        ),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Items Mobile', 'essential-real-estate'),
                        'param_name' => 'items_mb',
                        'description' => esc_html__('Browser Width < 480', 'essential-real-estate'),
                        'value' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ),
                        'std' => '1',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                        'group' => esc_html__('Responsive', 'essential-real-estate'),
                        'dependency' => array(
                            'element' => 'layout_style',
                            'value' => array('agent-grid', 'agent-slider'),
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'paged',
                        'value' => '1',
                    ),
                    array(
                        'type' => 'hidden',
                        'param_name' => 'post_not_in',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Agency', 'essential-real-estate'),
                'base' => 'ere_agency',
                'icon' => 'fa fa-group',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items Amount', 'essential-real-estate'),
                        'param_name' => 'item_amount',
                        'std' => '6'
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Show Paging', 'essential-real-estate'),
                        'param_name' => 'show_paging',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'param_name' => 'include_heading',
                        'type' => 'checkbox',
                        'heading' => esc_html__('Include Heading', 'essential-real-estate'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'essential-real-estate'),
                        'param_name' => 'heading_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Sub Title', 'essential-real-estate'),
                        'param_name' => 'heading_sub_title',
                        'value' => '',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Text Align', 'essential-real-estate'),
                        'param_name' => 'heading_text_align',
                        'description' => esc_html__('Select heading alignment.', 'essential-real-estate'),
                        'value' => array(
                            esc_html__('Left', 'essential-real-estate') => 'text-left',
                            esc_html__('Center', 'essential-real-estate') => 'text-center',
                            esc_html__('Right', 'essential-real-estate') => 'text-right',
                        ),
                        'std' => 'text-left',
                        'dependency' => array('element' => 'include_heading', 'value' => 'true'),
                        'group' => esc_html__('Heading Options', 'essential-real-estate')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Extra class name', 'essential-real-estate'),
                        'param_name' => 'el_class',
                        'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
                    )
                )
            ));
            vc_map(array(
                'name' => esc_html__('Login', 'essential-real-estate'),
                'base' => 'ere_login',
                'icon' => 'fa fa-user',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Redirect Page', 'essential-real-estate'),
                        'param_name' => 'redirect',
                        'description' => esc_html__('After Login Redirect Page.', 'essential-real-estate'),
                        'value' => array(
                            esc_html__('My Profile', 'essential-real-estate') => 'my_profile',
                            esc_html__('Current Page', 'essential-real-estate') => 'current_page',
                        ),
                    ),
                )
            ));
            vc_map(array(
                'name' => esc_html__('Register', 'essential-real-estate'),
                'base' => 'ere_register',
                'icon' => 'fa fa-user-plus',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('Profile', 'essential-real-estate'),
                'base' => 'ere_profile',
                'icon' => 'fa fa-user',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('Reset Password', 'essential-real-estate'),
                'base' => 'ere_reset_password',
                'icon' => 'fa fa-refresh',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('My Invoice', 'essential-real-estate'),
                'base' => 'ere_my_invoices',
                'icon' => 'fa fa-list',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('Package', 'essential-real-estate'),
                'base' => 'ere_package',
                'icon' => 'fa fa-list-alt',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('My Properties', 'essential-real-estate'),
                'base' => 'ere_my_properties',
                'icon' => 'fa fa-th',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('Submit Property', 'essential-real-estate'),
                'base' => 'ere_submit_property',
                'icon' => 'fa fa-newspaper-o',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('My Favorites', 'essential-real-estate'),
                'base' => 'ere_my_favorites',
                'icon' => 'fa fa-star',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('Advanced Search Page', 'essential-real-estate'),
                'base' => 'ere_advanced_search',
                'icon' => 'fa fa-search-plus',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('Compare', 'essential-real-estate'),
                'base' => 'ere_compare',
                'icon' => 'fa fa-balance-scale',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
            vc_map(array(
                'name' => esc_html__('My Saved Searches', 'essential-real-estate'),
                'base' => 'ere_my_save_search',
                'icon' => 'fa fa-save',
                'category' => esc_html__('ERE Shortcode', 'essential-real-estate')
            ));
        }

        /**
         * List type of property
         * @return array
         */
        private function vc_map_add_narrow_property_type()
        {
            $type = array();
            $types = get_categories(array('taxonomy' => 'property-type', 'hide_empty' => 0, 'orderby' => 'ASC'));
            if (is_array($types)) {
                foreach ($types as $st) {
                    $type[$st->name] = $st->slug;
                }
            }
            return array(
                'type' => 'dropdown',
                'heading' => esc_html__('Narrow Type', 'essential-real-estate'),
                'param_name' => 'property_type',
                'admin_label' => true,
                'value' => $type,
                'std' => '',
                'description' => esc_html__('Enter type by names to narrow output.', 'essential-real-estate')
            );
        }

        /**
         * List taxonomy as selectize
         * @param $taxonomy
         * @param $param_name
         * @param $heading
         *
         * @return array
         */
        private function vc_map_add_narrow_taxonomy($taxonomy, $param_name, $heading)
        {
            $taxonomies = array();
            $taxonomy_arr = get_categories(array('taxonomy' => $taxonomy, 'hide_empty' => 0, 'orderby' => 'ASC'));
            if (is_array($taxonomy_arr)) {
                foreach ($taxonomy_arr as $tx) {
                    $taxonomies[$tx->name] = $tx->slug;
                }
            }
            return array(
                'type' => 'ere_selectize',
                'heading' => sprintf(esc_html__('Narrow %s', 'essential-real-estate'),esc_html($heading)),
                'param_name' => $param_name,
                'value' => $taxonomies,
                'multiple' => true,
                'std' => '',
                'description' => sprintf(esc_html__('Enter %s by names to narrow output.', 'essential-real-estate'), esc_html($heading))
            );
        }

        private function vc_map_add_additions_search_form_fields(){
	        $config = array();
	        $additional_fields = ere_get_search_additional_fields();
	        foreach ($additional_fields as $k => $v) {
		        $config[] = array(
			        'type' => 'checkbox',
			        'param_name' => "{$k}_enable",
			        'value' => array($v => 'true'),
			        'edit_field_class' => 'vc_col-sm-6 vc_column',
			        'std' => '',
		        );
	        }
	        return $config;

        }
    }
}