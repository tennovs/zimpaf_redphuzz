<?php

/**
 * Plugin : ARPricelite
 * Description : Ultimate WordPress Pricing Table Plugin.
 * @Package : ARPRicelite
 */
class arplite_default_settings {

    function __construct() {
        add_action('wp_ajax_arpricelite_default_template_skins', array($this, 'arpricelite_get_template_skins'));
        add_filter('arpricelite_default_template_skins_filter', array($this, 'arp_change_default_template_skins'), 10, 2);
    }

    function arp_footer_section_template_types() {

        $arplite_footer_sec_temp_types = apply_filters('arplite_footer_sec_temp_types', array(
            'type_1' => array(),
            'type_2' => array('arplitetemplate_1', 'arplitetemplate_2', 'arplitetemplate_7', 'arplitetemplate_8', 'arplitetemplate_11', 'arplitetemplate_26'),
            'type_3' => array(),
        ));

        return $arplite_footer_sec_temp_types;
    }

    function arp_color_skin_template_types() {

        $arp_color_skin_template_types = apply_filters('arplite_color_skin_temp_types', array(
            'type_1' => array('arplitetemplate_1'),
            'type_2' => array('arplitetemplate_7', 'arplitetemplate_11'),
            'type_3' => array(),
            'type_4' => array(),
            'type_5' => array('arplitetemplate_2', 'arplitetemplate_8', 'arplitetemplate_26'),
        ));

        return $arp_color_skin_template_types;
    }

    function arplite_exclude_caption_column_for_color_skin() {
        $arplite_exclude_caption_column_for_color_skin = apply_filters('arplite_exclude_caption_column_for_color_skin', array(
            'arplitetemplate_7' => false,
            'arplitetemplate_2' => false,
            'arplitetemplate_1' => false,
            'arplitetemplate_8' => false,
            'arplitetemplate_11' => false,
            'arplitetemplate_26' => false,
        ));

        return $arplite_exclude_caption_column_for_color_skin;
    }

    function arp_column_section_background_color() {
        $arp_col_sec_bg_color = apply_filters('arp_col_sec_bg_col', array(
            'arplitetemplate_2' => array(
                'blue' => array(
                    'arp_column_background' => array(
                        '#02a3ff',
                        '#02a3ff',
                        '#02a3ff',
                        '#02a3ff',
                        '#02a3ff',
                    ),
                    'arp_button_background' => array(
                        '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#02a3ff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                            '#02a3ff',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#02a3ff',
                        ),
                    ),
                ),
                'lightviolet' => array(
                    'arp_column_background' => array(
                        '#6c62d3',
                        '#6c62d3',
                        '#6c62d3',
                        '#6c62d3',
                        '#6c62d3',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#6c62d3',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                            '#6c62d3',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#6c62d3',
                        ),
                    ),
                ),
                'yellow' => array(
                    'arp_column_background' => array(
                        '#ffba00',
                        '#ffba00',
                        '#ffba00',
                        '#ffba00',
                        '#ffba00',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffba00',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                            '#ffba00',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#ffba00',
                        ),
                    ),
                ),
                'limegreen' => array(
                    'arp_column_background' => array(
                        '#6ed563',
                        '#6ed563',
                        '#6ed563',
                        '#6ed563',
                        '#6ed563',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#6ed563',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                            '#6ed563',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#6ed563',
                        ),
                    ),
                ),
                'orange' => array(
                    'arp_column_background' => array(
                        '#ff9525',
                        '#ff9525',
                        '#ff9525',
                        '#ff9525',
                        '#ff9525',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ff9525',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                            '#ff9525',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#ff9525',
                        ),
                    ),
                ),
                'softblue' => array(
                    'arp_column_background' => array(
                        '#4476d9',
                        '#4476d9',
                        '#4476d9',
                        '#4476d9',
                        '#4476d9',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#4476d9',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                            '#4476d9',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#4476d9',
                        ),
                    ),
                ),
                'limecyan' => array(
                    'arp_column_background' => array(
                        '#37ba5a',
                        '#37ba5a',
                        '#37ba5a',
                        '#37ba5a',
                        '#37ba5a',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#37ba5a',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                            '#37ba5a',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#37ba5a',
                        ),
                    ),
                ),
                'brightred' => array(
                    'arp_column_background' => array(
                        '#f34044',
                        '#f34044',
                        '#f34044',
                        '#f34044',
                        '#f34044',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#f34044',
                            '#f34044',
                            '#f34044',
                            '#f34044',
                            '#f34044',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#f34044',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#f34044',
                            '#f34044',
                            '#f34044',
                            '#f34044',
                            '#f34044',
                            '#f34044',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#f340arp_column_background44',
                        ),
                    ),
                ),
                'red' => array(
                    'arp_column_background' => array(
                        '#de1a4c',
                        '#de1a4c',
                        '#de1a4c',
                        '#de1a4c',
                        '#de1a4c',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#de1a4c',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                            '#de1a4c',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#de1a4c',
                        ),
                    ),
                ),
                'pink' => array(
                    'arp_column_background' => array(
                        '#de199a',
                        '#de199a',
                        '#de199a',
                        '#de199a',
                        '#de199a',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#de199a',
                            '#de199a',
                            '#de199a',
                            '#de199a',
                            '#de199a',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#de199a',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#de199a',
                            '#de199a',
                            '#de199a',
                            '#de199a',
                            '#de199a',
                            '#de199a',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#de199a',
                        ),
                    ),
                ),
                'lightblue' => array(
                    'arp_column_background' => array(
                        '#1a5fde',
                        '#1a5fde',
                        '#1a5fde',
                        '#1a5fde',
                        '#1a5fde',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#1a5fde',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                            '#1a5fde',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#1a5fde',
                        ),
                    ),
                ),
                'darkpink' => array(
                    'arp_column_background' => array(
                        '#a51143',
                        '#a51143',
                        '#a51143',
                        '#a51143',
                        '#a51143',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#a51143',
                            '#a51143',
                            '#a51143',
                            '#a51143',
                            '#a51143',
                            '#a51143',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#a51143',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#a51143',
                            '#a51143',
                            '#a51143',
                            '#a51143',
                            '#a51143',
                            '#a51143',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#a51143',
                        ),
                    ),
                ),
                'darkcyan' => array(
                    'arp_column_background' => array(
                        '#11a599',
                        '#11a599',
                        '#11a599',
                        '#11a599',
                        '#11a599',
                    ),
                    'arp_button_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#313234',
                    ),
                    'arp_footer_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_shortcode_background' => array(
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                        '#ffffff',
                    ),
                    'arp_shortcode_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_hover_color' => array(
                        'column_bg_color' => array(
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                            '#F6F6F6',
                        ),
                        'button_bg_color' => array(
                            '#11a599',
                            '#11a599',
                            '#11a599',
                            '#11a599',
                            '#11a599',
                            '#11a599',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#11a599',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_footer_hover_font_color' => array(
                            '',
                            '#000000',
                        ),
                        'arp_shortcode_hover_background' => array(
                            '#11a599',
                            '#11a599',
                            '#11a599',
                            '#11a599',
                            '#11a599',
                            '#11a599',
                        ),
                        'arp_shortcode_hover_font_color' => array(
                            '',
                            '#11a599',
                        ),
                    ),
                ),
                'custom_skin' => array(
                    'arp_column_background' => '',
                    'arp_button_background' => '',
                    'arp_header_font_color' => '',
                    'arp_price_value_color' => '',
                    'arp_button_font_color' => '',
                    'arp_footer_font_color' => '',
                    'arp_body_font_color' => '',
                    'arp_body_even_font_color' => '',
                    'arp_shortcode_background' => '',
                    'arp_shortcode_font_color' => '',
                    'arp_hover_color' => array(
                        'column_bg_color' => '',
                        'button_bg_color' => '',
                        'arp_button_hover_font_color' => '',
                        'arp_header_hover_font_color' => '',
                        'arp_price_value_hover_color' => '',
                        'arp_price_duration_hover_color' => '',
                        'arp_body_font_hover_color' => '',
                        'arp_body_even_font_hover_color' => '',
                        'arp_footer_hover_font_color' => '',
                        'arp_shortcode_hover_background' => '',
                        'arp_shortcode_hover_font_color' => '',
                    ),
                ),
            ),
            'arplitetemplate_7' => array(
                'blue' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#3473dc',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'black' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#3e3e3c',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'cyan' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#1eae8b',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'lightblue' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#1bace1',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'red' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#f33c3e',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'yellow' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#ffa800',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'olive' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#8fb021',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'darkpurple' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#5b48a2',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'darkred' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#79302a',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'pink' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#ed1374',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'brown' => array(
                    'arp_header_background' => array(
                        '#000000',
                    ),
                    'arp_button_background' => array(
                        '#b11d00',
                    ),
                    'arp_desc_background' => array(
                        '#ffffff',
                    ),
                    'arp_body_odd_row_background_color' => array(
                        '#ffffff',
                    ),
                    'arp_body_even_row_background_color' => array(
                        '#f2f2f2',
                    ),
                    'arp_header_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_price_value_color' => array(
                        '',
                        '#3e3e3c',
                    ),
                    'arp_price_duration_color' => array(
                        '',
                        '#898989',
                    ),
                    'arp_desc_font_color' => array(
                        '',
                        '#7c7c7c',
                    ),
                    'arp_button_font_color' => array(
                        '',
                        '#ffffff',
                    ),
                    'arp_body_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_body_even_font_color' => array(
                        '',
                        '#333333',
                    ),
                    'arp_hover_color' => array(
                        'button_bg_color' => array(
                            '#3E3E3C',
                        ),
                        'header_bg_color' => array(
                            '#000000',
                        ),
                        'arp_body_odd_row_hover_background_color' => array(
                            '#ffffff',
                        ),
                        'arp_body_even_row_hover_background_color' => array(
                            '#f2f2f2',
                        ),
                        'arp_desc_hover_background' => array(
                            '#ffffff',
                        ),
                        'arp_button_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_header_hover_font_color' => array(
                            '',
                            '#ffffff',
                        ),
                        'arp_price_value_hover_color' => array(
                            '',
                            '#3e3e3c',
                        ),
                        'arp_price_duration_hover_color' => array(
                            '',
                            '#898989',
                        ),
                        'arp_desc_hover_font_color' => array(
                            '',
                            '#7c7c7c',
                        ),
                        'arp_body_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                        'arp_body_even_font_hover_color' => array(
                            '',
                            '#333333',
                        ),
                    ),
                ),
                'custom_skin' => array(
                    'arp_header_background' => '',
                    'arp_button_background' => '',
                    'arp_desc_background' => '',
                    'arp_body_odd_row_background_color' => '',
                    'arp_body_even_row_background_color' => '',
                    'arp_header_font_color' => '',
                    'arp_price_value_color' => '',
                    'arp_price_duration_color' => '',
                    'arp_desc_font_color' => '',
                    'arp_button_font_color' => '',
                    'arp_body_font_color' => '',
                    'arp_body_even_font_color' => '',
                    'arp_hover_color' => array(
                        'button_bg_color' => '',
                        'header_bg_color' => '',
                        'arp_body_odd_row_hover_background_color' => '',
                        'arp_body_even_row_hover_background_color' => '',
                        'arp_desc_hover_background' => '',
                        'arp_button_hover_font_color' => '',
                        'arp_header_hover_font_color' => '',
                        'arp_price_value_hover_color' => '',
                        'arp_price_duration_hover_color' => '',
                        'arp_desc_hover_font_color' => '',
                        'arp_body_font_hover_color' => '',
                        'arp_body_even_font_hover_color' => '',
                    ),
                ),
            ),
            'arplitetemplate_1' => array(
                'multicolor' => array(
                    'arp_header_background' => array('#ffffff', '#6dae2e', '#fbb400', '#ea6d00', '#c32929', '#e52937'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#528a1b', '#c28a01', '#b44404', '#a50b0b', '#bc0210'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#6dae2e', '#fbb400', '#ea6d00', '#c32929', '#e52937'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#FFFFFF'),
                    'arp_body_even_row_background_color' => array('#F1F1F1', "#E9E9E9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#F6F4F5'),
                    'arp_body_caption_even_row_bg_color' => array('#F1F1F1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#4C7A20', '#B07E00', '#A44C00', '#8C1E1E', '#A01D27'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#6dae2e', '#fbb400', '#ea6d00', '#c32929', '#e52937'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#528a1b', '#c28a01', '#b44404', '#a50b0b', '#bc0210'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#FFFFFF'),
                        'arp_body_even_row_hover_background_color' => array('#F1F1F1', "#E9E9E9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'green' => array(
                    'arp_header_background' => array('#ffffffff', '#85d538ff', '#7cc635ff', '#6dae2eff', '#619c26ff', '#4e8619ff'),
                    'arp_header_font_color' => array('', '#ffffffff'),
                    'arp_price_background' => array('', '#70b828ff', '#62a323ff', '#528a1bff', '#497e16ff', '#3d6c0eff'),
                    'arp_price_value_color' => array('', '#ffffffff'),
                    'arp_button_background' => array('', '#85d538ff', '#7cc635ff', '#6dae2eff', '#619c26ff', '#4e8619ff'),
                    'arp_button_font_color' => array('', '#ffffffff'),
                    'arp_footer_background' => array('#e3e3e3ff', '#e3e3e3ff', '#e3e3e3ff', '#e3e3e3ff', '#e3e3e3ff', '#e3e3e3ff'),
                    'arp_footer_font_color' => array('', '#364762ff'),
                    'arp_body_odd_row_background_color' => array('#ffffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1ff', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762ff'),
                    'arp_body_even_font_color' => array('', '#364762ff'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5ff'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1ff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#5D9527ff', '#6BAB2Eff', '#4C7A20ff', '#446D1Bff', '#375E12ff'),
                        'arp_button_hover_font_color' => array('', '#ffffffff'),
                        'header_bg_color' => array('', '#85d538FFff', '#7cc635ff', '#6dae2eff', '#619c26ff', '#4e8619ff'),
                        'arp_header_hover_font_color' => array('', '#ffffffff'),
                        'price_bg_color' => array('', '#70b828ff', '#62a323ff', '#528a1bff', '#497e16ff', '#3d6c0eff'),
                        'arp_price_value_hover_color' => array('', '#ffffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1ff', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762ff'),
                        'arp_body_even_font_hover_color' => array('', '#364762ff'),
                        'footer_bg_color' => array('#e3e3e3ff'),
                        'arp_footer_hover_font_color' => array('', '#364762ff'),
                    ),
                ),
                'yellow' => array(
                    'arp_header_background' => array('#ffffff', '#fbce59', '#ffc327', '#fbb400', '#e39002', '#cb8202'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#edbc3c', '#ecb014', '#dea001', '#c98204', '#b87502'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#fbce59', '#ffc327', '#fbb400', '#e39002', '#cb8202'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#B0903E', '#B3891B', '#D49800', '#9F6501', '#8F5C01'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#fbce59', '#ffc327', '#fbb400', '#e39002', '#cb8202'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#edbc3c', '#ecb014', '#dea001', '#c98204', '#b87502'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'darkorange' => array(
                    'arp_header_background' => array('#ffffff', '#ff902e', '#fa7701', '#e75c01', '#cd4a02', '#bd3c03'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#e17616', '#df610c', '#cb5404', '#b64509', '#a03201'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#ff902e', '#fa7701', '#e75c01', '#cd4a02', '#bd3c03'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#BE6B22', '#B35501', '#A24001', '#B64202', '#842A02'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#ff902e', '#fa7701', '#e75c01', '#cd4a02', '#bd3c03'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#e17616', '#df610c', '#cb5404', '#b64509', '#a03201'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'darkred' => array(
                    'arp_header_background' => array('#ffffff', '#e42c2c', '#c32929', '#a41a1a', '#900808', '#760000'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#c41818', '#a50b0b', '#89090a', '#780202', '#5b0000'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#e42c2c', '#c32929', '#a41a1a', '#900808', '#760000'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#A01F1F', '#891d1d', '#731212', '#650606', '#560000'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#e42c2c', '#c32929', '#a41a1a', '#900808', '#760000'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#c41818', '#a50b0b', '#89090a', '#780202', '#5b0000'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'red' => array(
                    'arp_header_background' => array('#ffffff', '#fa303e', '#e52937', '#cb2330', '#aa1823', '#8a0b14'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#e01d2d', '#bc0210', '#a8000d', '#870813', '#6e0107'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#fa303e', '#e52937', '#cb2330', '#aa1823', '#8a0b14'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#AF222B', '#A01D27', '#8E1922', '#771119', '#61080E'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#fa303e', '#e52937', '#cb2330', '#aa1823', '#8a0b14'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#e01d2d', '#bc0210', '#a8000d', '#870813', '#6e0107'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'violet' => array(
                    'arp_header_background' => array('#ffffff', '#922cbc', '#713887', '#5a2e6d', '#451e55', '#2e0e3d'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#6b1b8c', '#4d1465', '#400e53', '#340f42', '#20052d'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#922cbc', '#713887', '#5a2e6d', '#451e55', '#2e0e3d'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#661F84', '#4F275F', '#3F204C', '#30153C', '#200A2B'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#922cbc', '#713887', '#5a2e6d', '#451e55', '#2e0e3d'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#6b1b8c', '#4d1465', '#400e53', '#340f42', '#20052d'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'pink' => array(
                    'arp_header_background' => array('#ffffff', '#ff5792', '#ff287d', '#eb005c', '#cf0251', '#b90149'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#eb3b7b', '#db1864', '#c9004e', '#b00146', '#99013c'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#ff5792', '#ff287d', '#eb005c', '#cf0251', '#b90149'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#b33d66', '#b31c58', '#a50040', '#910139', '#820133'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('#ffffff', '#ff5792', '#ff287d', '#eb005c', '#cf0251', '#b90149'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#eb3b7b', '#db1864', '#c9004e', '#b00146', '#99013c'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'blue' => array(
                    'arp_header_background' => array('#ffffff', '#2eb5ed', '#29a1d3', '#248fbb', '#1878a2', '#0b5b7c'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#0b92ca', '#0981b3', '#06719d', '#046085', '#014c6b'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#2eb5ed', '#29a1d3', '#248fbb', '#1878a2', '#0b5b7c'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#207fa6', '#1d7194', '#196483', '#115471', '#084057'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#2eb5ed', '#29a1d3', '#248fbb', '#1878a2', '#0b5b7c'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#0b92ca', '#0981b3', '#06719d', '#046085', '#014c6b'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'darkblue' => array(
                    'arp_header_background' => array('#ffffff', '#444db2', '#2f3687', '#23286c', '#141950', '#090d3c'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#303996', '#23264f', '#191c3f', '#080c29', '#030627'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#444db2', '#2f3687', '#23286c', '#141950', '#090d3c'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#30367d', '#21265f', '#191c4c', '#0e1238', '#06092a'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#444db2', '#2f3687', '#23286c', '#141950', '#090d3c'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#303996', '#23264f', '#191c3f', '#080c29', '#030627'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'lightgreen' => array(
                    'arp_header_background' => array('#ffffff', '#23cd53', '#1dbb4c', '#1ba341', '#0f8c31', '#027822'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#0bb53b', '#0c9d34', '#0a892e', '#087826', '#026a1f'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#23cd53', '#1dbb4c', '#1ba341', '#0f8c31', '#027822'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#19903a', '#148335', '#13722e', '#0b6222', '#015418'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#23cd53', '#1dbb4c', '#1ba341', '#0f8c31', '#027822'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#0bb53b', '#0c9d34', '#0a892e', '#087826', '#026a1f'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'darkestblue' => array(
                    'arp_header_background' => array('#ffffff', '#42607a', '#395266', '#2f4251', '#223544', '#152837'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#384e63', '#2f4251', '#223544', '#162938', '#0b1d2b'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#42607a', '#395266', '#2f4251', '#223544', '#152837'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#E9E9E9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#2e4355', '#283947', '#212e39', '#182530', '#0f1c27'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#42607a', '#395266', '#2f4251', '#223544', '#152837'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#384e63', '#2f4251', '#223544', '#162938', '#0b1d2b'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#E9E9E9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'cyan' => array(
                    'arp_header_background' => array('#ffffff', '#0cb691', '#009e7b', '#0b866a', '#0a725b', '#065f4b'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#0b8d71', '#027057', '#096651', '#075643', '#024939'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#0cb691', '#009e7b', '#0b866a', '#0a725b', '#065f4b'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', '#F1F1F1', "#e9e9e9", '#F1F1F1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#087f66', '#006f56', '#085e4a', '#075040', '#044335'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#0cb691', '#009e7b', '#0b866a', '#0a725b', '#065f4b'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#0b8d71', '#027057', '#096651', '#075643', '#024939'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1'),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'black' => array(
                    'arp_header_background' => array('#ffffff', '#828282', '#6e6e6e', '#5c5c5c', '#4a4a4a', '#383838'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_background' => array('', '#707070', '#5b5b5b', '#4c4c4c', '#3c3c3c', '#2d2d2d'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_button_background' => array('', '#828282', '#6e6e6e', '#5c5c5c', '#4a4a4a', '#383838'),
                    'arp_button_font_color' => array('', '#ffffff'),
                    'arp_footer_background' => array('#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3', '#e3e3e3'),
                    'arp_footer_font_color' => array('', '#364762'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f1f1f1', "#e9e9e9"),
                    'arp_body_font_color' => array('', '#364762'),
                    'arp_body_even_font_color' => array('', '#364762'),
                    'arp_body_caption_odd_row_bg_color' => array('#f6f4f5'),
                    'arp_body_caption_even_row_bg_color' => array('#f1f1f1'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('', '#5b5b5b', '#4d4d4d', '#404040', '#343434', '#272727'),
                        'arp_button_hover_font_color' => array('', '#ffffff'),
                        'header_bg_color' => array('', '#828282', '#6e6e6e', '#5c5c5c', '#4a4a4a', '#383838'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'price_bg_color' => array('', '#707070', '#5b5b5b', '#4c4c4c', '#3c3c3c', '#2d2d2d'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#f1f1f1', "#e9e9e9"),
                        'arp_body_font_hover_color' => array('', '#364762'),
                        'arp_body_even_font_hover_color' => array('', '#364762'),
                        'footer_bg_color' => array('#e3e3e3'),
                        'arp_footer_hover_font_color' => array('', '#364762'),
                    ),
                ),
                'custom_skin' => array(
                    'arp_header_background' => '',
                    'arp_header_font_color' => '',
                    'arp_price_background' => '',
                    'arp_price_value_color' => '',
                    'arp_button_background' => '',
                    'arp_button_font_color' => '',
                    'arp_footer_background' => '',
                    'arp_footer_font_color' => '',
                    'arp_body_odd_row_background_color' => '',
                    'arp_body_even_row_background_color' => '',
                    'arp_body_font_color' => '',
                    'arp_body_even_font_color' => '',
                    'arp_body_caption_odd_row_bg_color' => '',
                    'arp_body_caption_even_row_bg_color' => '',
                    'arp_hover_color' => array(
                        'button_bg_color' => '',
                        'arp_button_hover_font_color' => '',
                        'header_bg_color' => '',
                        'arp_header_hover_font_color' => '',
                        'price_bg_color' => '',
                        'arp_price_value_hover_color' => '',
                        'arp_body_odd_row_hover_background_color' => '',
                        'arp_body_even_row_hover_background_color' => '',
                        'arp_body_font_hover_color' => '',
                        'arp_body_even_font_hover_color' => '',
                        'footer_bg_color' => '',
                        'arp_footer_hover_font_color' => '',
                    ),
                ),
            ),
            'arplitetemplate_8' => array(
                'multicolor' => array(
                    'arp_header_background' => array('#e92a4b', '#21c77b', '#ffc000', '#52c4ff', '#528fff'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#e92a4b', '#21c77b', '#ffc000', '#52c4ff', '#528fff'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'purple' => array(
                    'arp_header_background' => array('#A461D4', '#A461D4', '#A461D4', '#A461D4', '#A461D4'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#A461D4', '#A461D4', '#A461D4', '#A461D4', '#A461D4'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'skyblue' => array(
                    'arp_header_background' => array('#3AAFE2', '#3AAFE2', '#3AAFE2', '#3AAFE2', '#3AAFE2'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#3AAFE2', '#3AAFE2', '#3AAFE2', '#3AAFE2', '#3AAFE2'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'red' => array(
                    'arp_header_background' => array('#EE4546', '#EE4546', '#EE4546', '#EE4546', '#EE4546'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#EE4546', '#EE4546', '#EE4546', '#EE4546', '#EE4546'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'green' => array(
                    'arp_header_background' => array('#6CB03B', '#6CB03B', '#6CB03B', '#6CB03B', '#6CB03B'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#6CB03B', '#6CB03B', '#6CB03B', '#6CB03B', '#6CB03B'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'blue' => array(
                    'arp_header_background' => array('#4448A9', '#4448A9', '#4448A9', '#4448A9', '#4448A9'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#4448A9', '#4448A9', '#4448A9', '#4448A9', '#4448A9'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'orange' => array(
                    'arp_header_background' => array('#FF5830', '#FF5830', '#FF5830', '#FF5830', '#FF5830'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#ffffff'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#FF5830', '#FF5830', '#FF5830', '#FF5830', '#FF5830'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'darkcyan' => array(
                    'arp_header_background' => array('#41C0A1', '#41C0A1', '#41C0A1', '#41C0A1', '#41C0A1'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#41C0A1', '#41C0A1', '#41C0A1', '#41C0A1', '#41C0A1'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'yellow' => array(
                    'arp_header_background' => array('#FFBF3B', '#FFBF3B', '#FFBF3B', '#FFBF3B', '#FFBF3B'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#FFBF3B', '#FFBF3B', '#FFBF3B', '#FFBF3B', '#FFBF3B'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'pink' => array(
                    'arp_header_background' => array('#E9338C', '#E9338C', '#E9338C', '#E9338C', '#E9338C'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#E9338C', '#E9338C', '#E9338C', '#E9338C', '#E9338C'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'teal' => array(
                    'arp_header_background' => array('#1EC9D1', '#1EC9D1', '#1EC9D1', '#1EC9D1', '#1EC9D1'),
                    'arp_button_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_odd_row_background_color' => array('#ffffff'),
                    'arp_body_even_row_background_color' => array('#f7f8fa'),
                    'arp_header_font_color' => array('', '#ffffff'),
                    'arp_price_value_color' => array('', '#ffffff'),
                    'arp_price_duration_color' => array('', '#ffffff'),
                    'arp_button_font_color' => array('', '#323232'),
                    'arp_body_font_color' => array('', '#333333'),
                    'arp_body_even_font_color' => array('', '#333333'),
                    'arp_body_label_font_color' => array('', '#000000'),
                    'arp_shortcode_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'header_bg_color' => array('#1EC9D1', '#1EC9D1', '#1EC9D1', '#1EC9D1', '#1EC9D1'),
                        'arp_body_odd_row_hover_background_color' => array('#ffffff'),
                        'arp_body_even_row_hover_background_color' => array('#ffffff'),
                        'arp_button_hover_font_color' => array('', '#323232'),
                        'arp_header_hover_font_color' => array('', '#ffffff'),
                        'arp_price_value_hover_color' => array('', '#ffffff'),
                        'arp_price_duration_hover_color' => array('', '#ffffff'),
                        'arp_body_font_hover_color' => array('', '#333333'),
                        'arp_body_even_font_hover_color' => array('', '#333333'),
                        'arp_body_label_font_hover_color' => array('', '#000000'),
                        'arp_shortcode_hover_background' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'custom_skin' => array(
                    'arp_header_background' => '',
                    'arp_button_background' => '',
                    'arp_body_odd_row_background_color' => '',
                    'arp_body_even_row_background_color' => '',
                    'arp_header_font_color' => '',
                    'arp_price_value_color' => '',
                    'arp_price_duration_color' => '',
                    'arp_button_font_color' => '',
                    'arp_body_font_color' => '',
                    'arp_body_even_font_color' => '',
                    'arp_body_label_font_color' => '',
                    'arp_shortcode_background' => '',
                    'arp_shortcode_font_color' => '',
                    'arp_hover_color' => array(
                        'button_bg_color' => '',
                        'header_bg_color' => '',
                        'arp_body_odd_row_hover_background_color' => '',
                        'arp_body_even_row_hover_background_color' => '',
                        'arp_button_hover_font_color' => '',
                        'arp_header_hover_font_color' => '',
                        'arp_price_value_hover_color' => '',
                        'arp_price_duration_hover_color' => '',
                        'arp_body_font_hover_color' => '',
                        'arp_body_even_font_hover_color' => '',
                        'arp_body_label_font_hover_color' => '',
                        'arp_shortcode_hover_background' => '',
                        'arp_shortcode_hover_font_color' => '',
                    ),
                ),
            ),
            'arplitetemplate_11' => array(
                'yellow' => array(
                    'arp_button_background' => array('#efa738', '#efa738', '#efa738', '#efa738', '#efa738'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('', '#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#09B1F8', '#09B1F8', '#09B1F8', '#09B1F8', '#09B1F8'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'limegreen' => array(
                    'arp_button_background' => array('#43b34d', '#43b34d', '#43b34d', '#43b34d', '#43b34d'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#09B1F8', '#09B1F8', '#09B1F8', '#09B1F8', '#09B1F8'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'red' => array(
                    'arp_button_background' => array('#ff3241', '#ff3241', '#ff3241', '#ff3241', '#ff3241'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#FEA203', '#FEA203', '#FEA203', '#FEA203', '#FEA203'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'blue' => array(
                    'arp_button_background' => array('#09b1f8', '#09b1f8', '#09b1f8', '#09b1f8', '#09b1f8'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#43B34D', '#43B34D', '#43B34D', '#43B34D', '#43B34D'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'pink' => array(
                    'arp_button_background' => array('#e3328c', '#e3328c', '#e3328c', '#e3328c', '#e3328c'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#21B1B2', '#21B1B2', '#21B1B2', '#21B1B2', '#21B1B2'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'cyan' => array(
                    'arp_button_background' => array('#11b0b6', '#11b0b6', '#11b0b6', '#11b0b6', '#11b0b6'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#F49600', '#F49600', '#F49600', '#F49600', '#F49600'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'lightpink' => array(
                    'arp_button_background' => array('#f15f74', '#f15f74', '#f15f74', '#f15f74', '#f15f74'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#949494', '#949494', '#949494', '#949494', '#949494'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'violet' => array(
                    'arp_button_background' => array('#8f4aff', '#8f4aff', '#8f4aff', '#8f4aff', '#8f4aff'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#CD448A', '#CD448A', '#CD448A', '#CD448A', '#CD448A'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'gray' => array(
                    'arp_button_background' => array('#949494', '#949494', '#949494', '#949494', '#949494'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#F15F74', '#F15F74', '#F15F74', '#F15F74', '#F15F74'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'green' => array(
                    'arp_button_background' => array('#78c335', '#78c335', '#78c335', '#78c335', '#78c335'),
                    'arp_header_background' => array('#414045', '#414045', '#414045', '#414045', '#414045'),
                    'arp_desc_background' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_body_odd_row_background_color' => array('#313035', '#313035', '#313035', '#313035', '#313035'),
                    'arp_body_even_row_background_color' => array('#37363b', '#37363b', '#37363b', '#37363b', '#37363b'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_value_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_price_duration_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_desc_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_hover_color' => array(
                        'button_bg_color' => array('#FFB43D', '#FFB43D', '#FFB43D', '#FFB43D', '#FFB43D'),
                        'header_bg_color' => array('#51545D', '#51545D', '#51545D', '#51545D', '#51545D'),
                        'price_bg_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_body_odd_row_hover_background_color' => array('#3E4044', '#3E4044', '#3E4044', '#3E4044', '#3E4044'),
                        'arp_body_even_row_hover_background_color' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_desc_hover_background' => array('#46474C', '#46474C', '#46474C', '#46474C', '#46474C'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_value_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_price_duration_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_even_font_hover_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                        'arp_desc_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    ),
                ),
                'custom_skin' => array(
                    'arp_button_background' => '',
                    'arp_header_background' => '',
                    'arp_desc_background' => '',
                    'arp_body_odd_row_background_color' => '',
                    'arp_body_even_row_background_color' => '',
                    'arp_header_font_color' => '',
                    'arp_price_value_color' => '',
                    'arp_price_duration_color' => '',
                    'arp_desc_font_color' => '',
                    'arp_button_font_color' => '',
                    'arp_body_font_color' => '',
                    'arp_body_even_font_color' => '',
                    'arp_hover_color' => array(
                        'button_bg_color' => '',
                        'header_bg_color' => '',
                        'price_bg_color' => '',
                        'arp_body_odd_row_hover_background_color' => '',
                        'arp_body_even_row_hover_background_color' => '',
                        'arp_desc_hover_background' => '',
                        'arp_button_hover_font_color' => '',
                        'arp_header_hover_font_color' => '',
                        'arp_price_value_hover_color' => '',
                        'arp_price_duration_hover_color' => '',
                        'arp_body_font_hover_color' => '',
                        'arp_body_even_font_hover_color' => '',
                        'arp_desc_hover_font_color' => '',
                    ),
                ),
            ),
            'arplitetemplate_26' => array(
                'blue' => array(
                    'arp_header_background' => array('#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#2fb8ff', '#2fb8ff', '#2fb8ff'),
                        'arp_body_even_font_hover_color' => array('#2fb8ff', '#2fb8ff', '#2fb8ff'),
                        'arp_shortcode_hover_background' => array('#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff', '#2fb8ff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'red' => array(
                    'arp_header_background' => array('#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ff2d46', '#ff2d46', '#ff2d46'),
                        'arp_body_even_font_hover_color' => array('#ff2d46', '#ff2d46', '#ff2d46'),
                        'arp_shortcode_hover_background' => array('#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46', '#ff2d46'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'lightblue' => array(
                    'arp_header_background' => array('#4196ff', '#4196ff', '#4196ff', '#4196ff', '#4196ff'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#4196ff', '#4196ff', '#4196ff', '#4196ff', '#4196ff'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#4196ff', '#4196ff', '#4196ff', '#4196ff', '#4196ff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#4196ff', '#4196ff', '#4196ff'),
                        'arp_body_even_font_hover_color' => array('#4196ff', '#4196ff', '#4196ff'),
                        'arp_shortcode_hover_background' => array('#4196ff', '#4196ff', '#4196ff', '#4196ff', '#4196ff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'cyan' => array(
                    'arp_header_background' => array('#00d29d', '#00d29d', '#00d29d', '#00d29d', '#00d29d'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#00d29d', '#00d29d', '#00d29d', '#00d29d', '#00d29d'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#00d29d', '#00d29d', '#00d29d', '#00d29d', '#00d29d', '#00d29d'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#00d29d', '#00d29d', '#00d29d'),
                        'arp_body_even_font_hover_color' => array('#00d29d', '#00d29d', '#00d29d'),
                        'arp_shortcode_hover_background' => array('#00d29d', '#00d29d', '#00d29d', '#00d29d', '#00d29d'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'yellow' => array(
                    'arp_header_background' => array('#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#f1bc16', '#f1bc16', '#f1bc16'),
                        'arp_body_even_font_hover_color' => array('#f1bc16', '#f1bc16', '#f1bc16'),
                        'arp_shortcode_hover_background' => array('#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16', '#f1bc16'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'pink' => array(
                    'arp_header_background' => array('#ff2476', '#ff2476', '#ff2476', '#ff2476', '#ff2476'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#ff2476', '#ff2476', '#ff2476', '#ff2476', '#ff2476'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#ff2476', '#ff2476', '#ff2476', '#ff2476', '#ff2476'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#ff2476', '#ff2476', '#ff2476'),
                        'arp_body_even_font_hover_color' => array('#ff2476', '#ff2476', '#ff2476'),
                        'arp_shortcode_hover_background' => array('#ff2476', '#ff2476', '#ff2476', '#ff2476', '#ff2476'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'lightviolet' => array(
                    'arp_header_background' => array('#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#6b68ff', '#6b68ff', '#6b68ff'),
                        'arp_body_even_font_hover_color' => array('#6b68ff', '#6b68ff', '#6b68ff'),
                        'arp_shortcode_hover_background' => array('#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff', '#6b68ff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'gray' => array(
                    'arp_header_background' => array('#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#b7bdcb', '#b7bdcb', '#b7bdcb'),
                        'arp_body_even_font_hover_color' => array('#b7bdcb', '#b7bdcb', '#b7bdcb'),
                        'arp_shortcode_hover_background' => array('#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb', '#b7bdcb'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'orange' => array(
                    'arp_header_background' => array('#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#fd9a25', '#fd9a25', '#fd9a25'),
                        'arp_body_even_font_hover_color' => array('#fd9a25', '#fd9a25', '#fd9a25'),
                        'arp_shortcode_hover_background' => array('#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25', '#fd9a25'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'darkblue' => array(
                    'arp_header_background' => array('#337cff', '#337cff', '#337cff', '#337cff', '#337cff'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#337cff', '#337cff', '#337cff', '#337cff', '#337cff'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#337cff', '#337cff', '#337cff', '#337cff', '#337cff'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#337cff', '#337cff', '#337cff'),
                        'arp_body_even_font_hover_color' => array('#337cff', '#337cff', '#337cff'),
                        'arp_shortcode_hover_background' => array('#337cff', '#337cff', '#337cff', '#337cff', '#337cff'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'turquoise' => array(
                    'arp_header_background' => array('#00dbef', '#00dbef', '#00dbef', '#00dbef', '#00dbef'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#00dbef', '#00dbef', '#00dbef', '#00dbef', '#00dbef'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#00dbef', '#00dbef', '#00dbef', '#00dbef', '#00dbef'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#00dbef', '#00dbef', '#00dbef'),
                        'arp_body_even_font_hover_color' => array('#00dbef', '#00dbef', '#00dbef'),
                        'arp_shortcode_hover_background' => array('#00dbef', '#00dbef', '#00dbef', '#00dbef', '#00dbef'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'grayishyellow' => array(
                    'arp_header_background' => array('#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#cfc5a1', '#cfc5a1', '#cfc5a1'),
                        'arp_body_even_font_hover_color' => array('#cfc5a1', '#cfc5a1', '#cfc5a1'),
                        'arp_shortcode_hover_background' => array('#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1', '#cfc5a1'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'green' => array(
                    'arp_header_background' => array('#16d784', '#16d784', '#16d784', '#16d784', '#16d784'),
                    'arp_column_background' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                    'arp_button_background' => array('#16d784', '#16d784', '#16d784', '#16d784', '#16d784'),
                    'arp_header_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_button_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_body_even_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                    'arp_shortcode_background' => array('#16d784', '#16d784', '#16d784', '#16d784', '#16d784'),
                    'arp_shortcode_font_color' => array('', '#ffffff'),
                    'arp_hover_color' => array(
                        'header_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'column_bg_color' => array('#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37', '#2B2E37'),
                        'button_bg_color' => array('#08090B', '#08090B', '#08090B', '#08090B', '#08090B'),
                        'arp_button_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_header_hover_font_color' => array('#ffffff', '#ffffff', '#ffffff'),
                        'arp_body_font_hover_color' => array('#16d784', '#16d784', '#16d784'),
                        'arp_body_even_font_hover_color' => array('#16d784', '#16d784', '#16d784'),
                        'arp_shortcode_hover_background' => array('#16d784', '#16d784', '#16d784', '#16d784', '#16d784'),
                        'arp_shortcode_hover_font_color' => array('', '#ffffff'),
                    ),
                ),
                'custom_skin' => array(
                    'arp_header_background' => '',
                    'arp_column_background' => '',
                    'arp_button_background' => '',
                    'arp_header_font_color' => '',
                    'arp_button_font_color' => '',
                    'arp_body_font_color' => '',
                    'arp_shortcode_background' => '',
                    'arp_shortcode_font_color' => '',
                    'arp_body_even_font_color' => '',
                    'arp_hover_color' => array(
                        'header_bg_color' => '',
                        'column_bg_color' => '',
                        'button_bg_color' => '',
                        'arp_button_hover_font_color' => '',
                        'arp_header_hover_font_color' => '',
                        'arp_body_font_hover_color' => '',
                        'arp_body_even_font_hover_color' => '',
                        'arp_shortcode_hover_background' => '',
                        'arp_shortcode_hover_font_color' => '',
                    ),
                ),
            ),
        ));

        return $arp_col_sec_bg_color;
    }

    function arp_template_bg_section_classes() {

        $arp_template_bg_sec_classes = apply_filters('arplite_tmp_bg_sec_classes', array(
            'arplitetemplate_7' => array(
                'caption_column' => array(),
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'desc_selection' => 'column_description,arppricetablecolumnprice',
                    'body_section' => array(
                        'odd_row' => 'arp_odd_row',
                        'even_row' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_2' => array(
                'caption_column' => array(),
                'other_column' => array(
                    'column_section' => 'arp_column_content_wrapper',
                ),
            ),
            'arplitetemplate_1' => array(
                'caption_column' => array(
                    'header_section' => 'arpcaptiontitle',
                    'footer_section' => 'arpcolumnfooter',
                    'body_section' => array(
                        'odd_row' => 'arp_odd_row',
                        'even_row' => 'arp_even_row',
                    ),
                ),
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'pricing_section' => 'arppricetablecolumnprice',
                    'button_section' => 'bestPlanButton',
                    'footer_section' => 'arpcolumnfooter',
                    'body_section' => array(
                        'odd_row' => 'arp_odd_row',
                        'even_row' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_8' => array(
                'caption_column' => array(
                    'footer_section' => 'arpcolumnfooter',
                    'body_section' => array(
                        'odd_row' => 'arp_odd_row',
                        'even_row' => 'arp_even_row',
                    ),
                ),
                'other_column' => array(
                    'header_section' => 'arpcolumnheader',
                    'button_section' => 'bestPlanButton',
                    'body_section' => array(
                        'odd_row' => 'arp_odd_row',
                        'even_row' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_11' => array(
                'caption_columns' => array(),
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'desc_selection' => 'arppricetablecolumnprice',
                    'button_section' => 'bestPlanButton',
                    'body_section' => array(
                        'odd_row' => 'arp_odd_row',
                        'even_row' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_26' => array(
                'caption_column' => array(),
                'other_column' => array(
                    'header_section' => 'bestPlanTitle',
                    'column_section' => 'arp_column_content_wrapper',
                    'button_section' => 'bestPlanButton',
                ),
            ),
        ));

        return $arp_template_bg_sec_classes;
    }

    function arp_border_color() {

        $arp_border_color = apply_filters('arplite_border_colors', array(
            'arplitetemplate_1' => array(
                'caption_column' => array(
                    '.arp_column_content_wrapper' => array(
                        'border_color' => '#e3e3e3',
                        'border_type' => 'solid',
                        'border_size' => '1px',
                        'border_position' => 'right',
                    ),
                ),
            ),
        ));
        return array();
    }

    function arp_template_sections_array() {

        $arptemplatesectionsarray = apply_filters('arplitetemplate_available_sections_array', array(
            'arplitetemplate_7' => array(
                'arp_header_background_color_div' => array('.arppricetablecolumntitle'),
                'arp_column_desc_background_color_data_div' => array('column_description,.arppricetablecolumnprice'),
                'arp_body_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_button_background_color_div' => array('bestPlanButton'),
                'arp_btn_hover_color_div' => array('arp_btn_hover_color'),
                'arp_header_hover_bg_color' => array('.arppricetablecolumntitle'),
                'arp_body_hover_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_column_desc_hover_background_color_data' => array('desc_hover_color'),
                'arp_header_font_color' => array('bestPlanTitle'),
                'arp_header_hover_font_color' => array('bestPlanTitle'),
                'arp_price_font_color' => array('arp_price_wrapper'),
                'arp_price_hover_font_color' => array('arppricetablecolumnprice'),
                'arp_price_duration_font_color' => array('arp_price_duration'),
                'arp_price_duration_hover_font_color' => array('arp_price_duration'),
                'arp_desc_font_color' => array('column_description'),
                'arp_desc_hover_font_color' => array('column_description'),
                'arp_body_font_color' => array('arp_odd_row'),
                'arp_body_even_font_color' => array('arp_even_row'),
                'arp_body_hover_font_color' => array('arpbodyoptionrow'),
                'arp_button_font_color' => array('bestPlanButton'),
                'arp_button_hover_font_color' => array('bestPlanButton'),
                'arp_pricing_background_hover_color_div' => array('arp_pricing_background_hover_color_div'),
                'arp_pricing_background_color_div' => array('arp_price_wrapper'),
            ),
            'arplitetemplate_2' => array(
                'arp_footer_background_color_div' => array('arpcolumnfooter'),
                'arp_footer_hover_background_color' => array('footer_hover_color'),
                'arp_column_background_color_data_div' => array('arp_column_content_wrapper'),
                'arp_button_background_color_div' => array('bestPlanButton'),
                'arp_column_hover_color_div' => array('arp_column_hover_color'),
                'arp_btn_hover_color_div' => array('arp_btn_hover_color'),
                'arp_header_font_color' => array('bestPlanTitle'),
                'arp_header_hover_font_color' => array('bestPlanTitle'),
                'arp_price_font_color' => array('arp_price_wrapper'),
                'arp_price_hover_font_color' => array('arppricetablecolumnprice'),
                'arp_price_duration_font_color' => array('arp_price_duration'),
                'arp_price_duration_hover_font_color' => array('arp_price_duration'),
                'arp_body_font_color' => array('arp_odd_row'),
                'arp_body_even_font_color' => array('arp_even_row'),
                'arp_body_hover_font_color' => array('arpbodyoptionrow'),
                'arp_footer_font_color' => array('arp_footer_content'),
                'arp_footer_hover_font_color' => array('arp_footer_content'),
                'arp_button_font_color' => array('bestPlanButton'),
                'arp_button_hover_font_color' => array('bestPlanButton'),
                'arp_header_background_color_div' => array('.arppricetablecolumntitle'),
                'arp_header_hover_bg_color' => array('arp_header_hover_background_color'),
                'arp_shortcode_hover_bg_color' => array('arp_shortcode_hover_background_color'),
                'arp_body_hover_background_color' => array('arp_body_hover_background_color'),
                'arp_body_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_pricing_background_hover_color_div' => array('arp_pricing_background_hover_color_div'),
                'arp_pricing_background_color_div' => array('arp_price_wrapper'),
                'arp_shortcode_background_color_div' => array('arp_shortcode_background_color_div'),
                'arp_shortcode_background_color' => array('.rounded_corder'),
                'arp_shortcode_font_color' => array('.rounded_corder'),
            ),
            'arplitetemplate_1' => array(
                'arp_header_background_color_div' => array('.arppricetablecolumntitle'),
                'arp_pricing_background_color_div' => array('arppricetablecolumnprice'),
                'arp_body_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_footer_background_color_div' => array('arpcolumnfooter'),
                'arp_button_background_color_div' => array('bestPlanButton'),
                'arp_price_hover_color' => array('arp_price_hover_color'),
                'arp_btn_hover_color_div' => array('arp_btn_hover_color'),
                'arp_body_hover_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_header_hover_bg_color' => array('.arppricetablecolumntitle'),
                'arp_footer_hover_background_color' => array('footer_hover_color'),
                'arp_header_font_color' => array('bestPlanTitle'),
                'arp_header_hover_font_color' => array('bestPlanTitle'),
                'arp_price_font_color' => array('arp_price_wrapper'),
                'arp_price_hover_font_color' => array('arppricetablecolumnprice'),
                'arp_body_font_color' => array('arp_odd_row'),
                'arp_body_even_font_color' => array('arp_even_row'),
                'arp_body_hover_font_color' => array('arpbodyoptionrow'),
                'arp_footer_font_color' => array('arp_footer_content'),
                'arp_footer_hover_font_color' => array('arp_footer_content'),
                'arp_button_font_color' => array('bestPlanButton'),
                'arp_button_hover_font_color' => array('bestPlanButton'),
                'arp_pricing_background_hover_color_div' => array('arp_pricing_background_hover_color_div'),
            ),
            'arplitetemplate_8' => array(
                'arp_header_background_color_div' => array('.arpcolumnheader'),
                'arp_body_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_button_background_color_div' => array('bestPlanButton'),
                'arp_header_hover_bg_color' => array('.arppricetablecolumntitle'),
                'arp_body_hover_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_btn_hover_color_div' => array('arp_btn_hover_color'),
                'arp_header_font_color' => array('bestPlanTitle'),
                'arp_header_hover_font_color' => array('bestPlanTitle'),
                'arp_price_font_color' => array('arp_price_wrapper'),
                'arp_price_hover_font_color' => array('arppricetablecolumnprice'),
                'arp_price_duration_font_color' => array('arp_price_duration'),
                'arp_price_duration_hover_font_color' => array('arp_price_duration'),
                'arp_body_font_color' => array('arp_odd_row'),
                'arp_body_even_font_color' => array('arp_even_row'),
                'arp_body_hover_font_color' => array('arpbodyoptionrow'),
                'arp_button_font_color' => array('bestPlanButton'),
                'arp_button_hover_font_color' => array('bestPlanButton'),
                'arp_pricing_background_color_div' => array('arp_price_wrapper'),
                'arp_pricing_background_hover_color_div' => array('arp_pricing_background_hover_color_div'),
                'arp_shortcode_background_color_div' => array('arp_shortcode_background_color_div'),
                'arp_shortcode_background_color' => array('.rounded_corder'),
                'arp_shortcode_font_color' => array('.rounded_corder'),
                'arp_shortcode_hover_bg_color' => array('arp_shortcode_hover_background_color'),
            ),
            'arplitetemplate_11' => array(
                'arp_header_background_color_div' => array('.arppricetablecolumntitle'),
                'arp_column_desc_background_color_data_div' => array('arppricetablecolumnprice'),
                'arp_body_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_button_background_color_div' => array('bestPlanButton'),
                'arp_btn_hover_color_div' => array('arp_btn_hover_color'),
                'arp_column_desc_hover_background_color_data' => array('desc_hover_color'),
                'arp_header_hover_bg_color' => array('.arppricetablecolumntitle'),
                'arp_body_hover_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_header_font_color' => array('bestPlanTitle'),
                'arp_header_hover_font_color' => array('bestPlanTitle'),
                'arp_price_font_color' => array('arp_price_wrapper'),
                'arp_price_hover_font_color' => array('arppricetablecolumnprice'),
                'arp_price_duration_font_color' => array('arp_price_duration'),
                'arp_price_duration_hover_font_color' => array('arp_price_duration'),
                'arp_body_font_color' => array('arp_odd_row'),
                'arp_body_even_font_color' => array('arp_even_row'),
                'arp_body_hover_font_color' => array('arpbodyoptionrow'),
                'arp_button_font_color' => array('bestPlanButton'),
                'arp_button_hover_font_color' => array('bestPlanButton'),
                'arp_desc_font_color' => array('column_description'),
                'arp_desc_hover_font_color' => array('column_description'),
                'arp_pricing_background_hover_color_div' => array('arp_pricing_background_hover_color_div'),
                'arp_pricing_background_color_div' => array('arp_price_wrapper'),
            ),
            'arplitetemplate_26' => array(
                'arp_header_background_color_div' => array('.arppricetablecolumntitle'),
                'arp_column_background_color_data_div' => array('arp_column_content_wrapper'),
                'arp_button_background_color_div' => array('bestPlanButton'),
                'arp_column_hover_color_div' => array('arp_column_hover_color'),
                'arp_btn_hover_color_div' => array('arp_btn_hover_color'),
                'arp_header_font_color' => array('bestPlanTitle'),
                'arp_header_hover_font_color' => array('bestPlanTitle'),
                'arp_body_hover_font_color' => array('arpbodyoptionrow'),
                'arp_footer_font_color' => array('arp_footer_content'),
                'arp_footer_hover_font_color' => array('arp_footer_content'),
                'arp_button_font_color' => array('bestPlanButton'),
                'arp_button_hover_font_color' => array('bestPlanButton'),
                'arp_header_background_color_div' => array('.arppricetablecolumntitle'),
                'arp_header_hover_bg_color' => array('arp_header_hover_background_color'),
                'arp_body_hover_background_color' => array('arp_body_hover_background_color'),
                'arp_body_background_color' => array(
                    'odd_row' => 'arp_odd_row',
                    'even_row' => 'arp_even_row',
                ),
                'arp_body_font_color' => array('arp_odd_row'),
                'arp_body_even_font_color' => array('arp_even_row'),
                'arp_shortcode_background_color_div' => array('arp_shortcode_background_color_div'),
                'arp_shortcode_background_color' => array('.rounded_corder'),
                'arp_shortcode_font_color' => array('.rounded_corder'),
                'arp_shortcode_hover_bg_color' => array('arp_shortcode_hover_background_color'),
            ),
        ));

        return $arptemplatesectionsarray;
    }

    function arplite_template_custom_skin_array() {
        $arplite_template_custom_skin_array = apply_filters('arplite_template_custom_skin_array', array(
            'arplitetemplate_7' => array(
                'header_font_color' => array(
                    'css' => array(
                        '.bestPlanTitle_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'header_background_color' => array(
                    'css' => array(
                        '.arppricetablecolumntitle_^_1' => array(
                            'background' => '{arp_rgb_color___0.7}',
                        ),
                    ),
                ),
                'column_description_font_color' => array(
                    'css' => array(
                        '.column_description_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'column_desc_background_color' => array(
                    'css' => array(
                        '.column_description_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                        '.arppricetablecolumnprice_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'price_font_color' => array(
                    'css' => array(
                        '.arp_price_wrapper_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_text_font_color' => array(
                    'css' => array(
                        '.arp_price_duration_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_font_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_background_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_odd_color' => array(
                    'css' => array(
                        'ul.arppricingtablebodyoptions[ARP_SPACE]li:even_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_color' => array(
                    'css' => array(
                        'ul.arppricingtablebodyoptions[ARP_SPACE]li:odd_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
            ),
            'arplitetemplate_2' => array(
                'column_background_color' => array(
                    'css' => array(
                        '.arp_column_content_wrapper_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'header_font_color' => array(
                    'css' => array(
                        '.bestPlanTitle_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_font_color' => array(
                    'css' => array(
                        '.arp_price_wrapper_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_text_font_color' => array(
                    'css' => array(
                        '.arp_price_duration_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'footer_level_options_font_color' => array(
                    'css' => array(
                        '.arp_footer_content_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_font_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_background_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'shortcode_background_color' => array(
                    'css' => array(
                        '.rounded_corder_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'shortcode_font_color' => array(
                    'css' => array(
                        '.rounded_corder_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
            ),
            'arplitetemplate_1' => array(
                'header_font_color' => array(
                    'css' => array(
                        '#column_header_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                        '.bestPlanTitle_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'header_background_color' => array(
                    'css' => array(
                        '#column_header_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                        '.arppricetablecolumntitle_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_font_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_background_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'price_background_color' => array(
                    'css' => array(
                        '.arppricetablecolumnprice_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'price_font_color' => array(
                    'css' => array(
                        '.arp_price_wrapper_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'footer_background_color' => array(
                    'css' => array(
                        '.arpcolumnfooter_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'footer_level_options_font_color' => array(
                    'css' => array(
                        '.arp_footer_content_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_odd_color' => array(
                    'css' => array(
                        'ul.arppricingtablebodyoptions[ARP_SPACE]li:even_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_color' => array(
                    'css' => array(
                        'ul.arppricingtablebodyoptions[ARP_SPACE]li:odd_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
            ),
            'arplitetemplate_8' => array(
                'header_font_color' => array(
                    'css' => array(
                        '.bestPlanTitle_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'header_background_color' => array(
                    'css' => array(
                        '.arpcolumnheader_^_1' => array(
                            'background-color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_font_color' => array(
                    'css' => array(
                        '.arp_price_wrapper_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_text_font_color' => array(
                    'css' => array(
                        '.arp_price_duration_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_odd_color' => array(
                    'css' => array(
                        'ul.arppricingtablebodyoptions[ARP_SPACE]li:even_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_color' => array(
                    'css' => array(
                        'ul.arppricingtablebodyoptions[ARP_SPACE]li:odd_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_background_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'button_font_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'shortcode_background_color' => array(
                    'css' => array(
                        '.rounded_corder_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'shortcode_font_color' => array(
                    'css' => array(
                        '.rounded_corder_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
            ),
            'arplitetemplate_11' => array(
                'header_background_color' => array(
                    'css' => array(
                        '.arppricetablecolumntitle_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'header_font_color' => array(
                    'css' => array(
                        '.bestPlanTitle_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_font_color' => array(
                    'css' => array(
                        '.arp_price_wrapper_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'price_text_font_color' => array(
                    'css' => array(
                        '.arp_price_duration_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'column_desc_background_color' => array(
                    'css' => array(
                        '.arppricetablecolumnprice_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'column_description_font_color' => array(
                    'css' => array(
                        '.column_description_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_background_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'button_font_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_odd_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li:even_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li:odd_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'content_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
            ),
            'arplitetemplate_26' => array(
                'header_background_color' => array(
                    'css' => array(
                        '.arppricetablecolumntitle_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                        '.rounded_corder_^_1' => array(
                            'border' => '5px solid {arp_color}',
                        ),
                    ),
                ),
                'column_background_color' => array(
                    'css' => array(
                        '.arp_column_content_wrapper_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'header_font_color' => array(
                    'css' => array(
                        '.bestPlanTitle_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'content_even_font_color' => array(
                    'css' => array(
                        'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_font_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
                'button_background_color' => array(
                    'css' => array(
                        '.bestPlanButton_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'shortcode_background_color' => array(
                    'css' => array(
                        '.rounded_corder_^_1' => array(
                            'background' => '{arp_color}',
                        ),
                    ),
                ),
                'shortcode_font_color' => array(
                    'css' => array(
                        '.rounded_corder_^_1' => array(
                            'color' => '{arp_color}',
                        ),
                    ),
                ),
            ),
        ));

        return $arplite_template_custom_skin_array;
    }

    function arp_template_hover_class_array() {
        $arplitetemplatehoverclassarray = apply_filters('arplitetemplatehoverclassarray', array(
            'arplitetemplate_7' => array(
                'arp_common_hover_css' => array(
                    '.arppricetablecolumntitle_^_1' => array(
                        'background' => '{arp_header_background_custom_hover_input_rgba^_^(0.7)}',
                    ),
                    '.arppricetablecolumntitle[ARP_SPACE].bestPlanTitle_^_1' => array(
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.column_description_^_1' => array(
                        'background' => '{arp_desc_hover_background_color}',
                        'color' => '{arp_description_hover_font_color}',
                    ),
                    '.arppricetablecolumnprice_^_1' => array(
                        'background' => '{arp_desc_hover_background_color}',
                    ),
                    '.arp_price_wrapper_^_1' => array(
                        'color' => '{arp_price_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                        'background-color' => '{arp_odd_row_hover_background_color}',
                        'color' => '{arp_content_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                        'background-color' => '{arp_even_row_hover_background_color}',
                        'color' => '{arp_content_even_hover_font_color}',
                    ),
                    '.bestPlanButton_^_1' => array(
                        'background' => '{arp_button_background_color}',
                    ),
                    '.bestPlanButton_text_^_1' => array(
                        'color' => '{arp_button_hover_font_color}',
                    ),
                ),
            ),
            'arplitetemplate_2' => array(
                'arp_common_hover_css' => array(
                    '.bestPlanTitle_^_1' => array(
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.rounded_corder_^_1' => array(
                        'background' => '{arp_shortcode_background_color}',
                        'border-color' => '{arp_shortcode_border_color}',
                        'color' => '{arp_shortcode_font_color}'
                    ),
                    '.arp_price_wrapper_^_1' => array(
                        'color' => '{arp_price_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                        'color' => '{arp_content_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                        'color' => '{arp_content_even_hover_font_color}',
                    ),
                    '.bestPlanButton_^_1' => array(
                        'background' => '{arp_button_background_color}',
                    ),
                    '.bestPlanButton_text_^_1' => array(
                        'color' => '{arp_button_hover_font_color}',
                    ),
                    '.arp_footer_content_^_1' => array(
                        'color' => '{arp_footer_font_hover_color}',
                    ),
                    '.arp_footer_content_text_^_1' => array(
                        'color' => '{arp_footer_font_hover_color}',
                    ),
                    '.arp_column_content_wrapper_^_1' => array(
                        'background' => '{arp_column_hover_background_color}',
                        'box-shadow' => '0[ARP_SPACE]0[ARP_SPACE]0[ARP_SPACE]2px[ARP_SPACE]{arp_column_background_color}',
                    ),
                ),
            ),
            'arplitetemplate_1' => array(
                'arp_common_hover_css' => array(
                    '.arppricetablecolumntitle_^_1' => array(
                        'background' => '{arp_header_bg_custom_hover_color}',
                    ),
                    '.bestPlanTitle_^_1' => array(
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.arppricetablecolumnprice_^_1' => array(
                        'background' => '{arp_price_hover_background_color}',
                    ),
                    '.arp_price_wrapper_^_1' => array(
                        'color' => '{arp_price_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                        'background' => '{arp_odd_row_hover_background_color}',
                        'color' => '{arp_content_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                        'background' => '{arp_even_row_hover_background_color}',
                        'color' => '{arp_content_even_hover_font_color}',
                    ),
                    '.arpcolumnfooter_^_1' => array(
                        'background' => '{arp_footer_bg_custom_hover_color}',
                    ),
                    '.arpcolumnfooter[ARP_SPACE].arp_footer_content_text_^_1' => array(
                        'color' => '{arp_footer_font_hover_color}',
                    ),
                    '.bestPlanButton_^_1' => array(
                        'background' => '{arp_button_background_color}',
                    ),
                    '.bestPlanButton_text_^_1' => array(
                        'color' => '{arp_button_hover_font_color}',
                    ),
                ),
            ),
            'arplitetemplate_8' => array(
                'arp_common_hover_css' => array(
                    '.arpcolumnheader_^_1' => array(
                        'background' => '{arp_header_bg_custom_hover_color}',
                    ),
                    '.arppricetablecolumntitle[ARP_SPACE].bestPlanTitle_^_1' => array(
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.arp_price_wrapper_^_1' => array(
                        'color' => '{arp_price_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                        'background' => '{arp_odd_row_hover_background_color}',
                        'color' => '{arp_content_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                        'background' => '{arp_even_row_hover_background_color}',
                        'color' => '{arp_content_even_hover_font_color}',
                    ),
                    '.bestPlanButton_^_1' => array(
                        'background' => '{arp_button_background_color}',
                    ),
                    '.bestPlanButton_text_^_1' => array(
                        'color' => '{arp_button_hover_font_color}',
                    ),
                    '.rounded_corder_^_1' => array(
                        'background' => '{arp_shortcode_background_color}',
                        'border-color' => '{arp_shortcode_border_color}',
                    ),
                ),
            ),
            'arplitetemplate_11' => array(
                'arp_common_hover_css' => array(
                    '.arppricetablecolumntitle_^_1' => array(
                        'background' => '{arp_header_bg_custom_hover_color}',
                    ),
                    '.arppricetablecolumntitle[ARP_SPACE].bestPlanTitle_^_1' => array(
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.bestPlanButton_^_1' => array(
                        'background' => '{arp_button_background_color}',
                    ),
                    '.column_description_^_1' => array(
                        'color' => '{arp_description_hover_font_color}',
                    ),
                    '.arppricetablecolumnprice_^_1' => array(
                        'background' => '{arp_desc_hover_background_color}',
                    ),
                    '.arp_price_wrapper_^_1' => array(
                        'color' => '{arp_price_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_odd_row_^_1' => array(
                        'background' => '{arp_odd_row_hover_background_color}',
                        'color' => '{arp_content_hover_font_color}',
                    ),
                    'ul.arp_opt_options[ARP_SPACE]li.arp_even_row_^_1' => array(
                        'background' => '{arp_even_row_hover_background_color}',
                        'color' => '{arp_content_even_hover_font_color}',
                    ),
                    '.bestPlanButton_text_^_1' => array(
                        'color' => '{arp_button_hover_font_color}',
                    ),
                ),
            ),
            'arplitetemplate_26' => array(
                'arp_common_hover_css' => array(
                    '.arppricetablecolumntitle_^_1' => array(
                        'background' => '{arp_header_bg_custom_hover_color}',
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.arppricetablecolumntitle[ARP_SPACE].bestPlanTitle_^_1' => array(
                        'color' => '{arp_header_hover_font_color}',
                    ),
                    '.rounded_corder_^_1' => array(
                        'background' => '{arp_shortcode_background_color}',
                        'border-color' => '{arp_shortcode_border_color}',
                    ),
                    'ul.arp_opt_options li.arp_odd_row_^_1' => array(
                        'color' => '{arp_content_hover_font_color}',
                    ),
                    'ul.arp_opt_options li.arp_even_row_^_1' => array(
                        'color' => '{arp_content_even_hover_font_color}',
                    ),
                    '.bestPlanButton_^_1' => array(
                        'background' => '{arp_button_background_color}',
                        'color' => '{arp_button_hover_font_color}',
                    ),
                    '.bestPlanButton_text_^_1' => array(
                        'color' => '{arp_button_hover_font_color}',
                    ),
                    '.arp_column_content_wrapper_^_1' => array(
                        'background' => '{arp_column_hover_background_color}',
                    ),
                ),
            ),
        ));

        return $arplitetemplatehoverclassarray;
    }

    function arplite_default_gradient_templates() {
        $arplite_default_gradient_templates = apply_filters('arplite_default_gradient_templates', array(
            'default_only' => array(),
            'all_skins' => array(),
        ));
        return $arplite_default_gradient_templates;
    }

    function arplite_default_gradient_templates_colors() {
        $arp_default_gradient_template_colors = apply_filters('arplite_default_gradient_colors', array());
        return $arp_default_gradient_template_colors;
    }

    function arp_default_rgba_color_array() {
        $arp_rgba_color_codes = apply_filters('arplite_default_rgba_colors', array(
            'arplitetemplate_7' => array(
                'header_background_color' => array(
                    '.arppricetablecolumntitle' => '{arp_header_background_color}___0.7',
                ),
            ),
        ));
        return $arp_rgba_color_codes;
    }

    function arplite_default_skin_luminosity() {
        $arplite_default_skin_luminosity = apply_filters('arplite_default_skin_luminosity', array(
            'arplitetemplate_7' => array(),
            'arplitetemplate_2' => array(),
            'arplitetemplate_1' => array(),
            'arplitetemplate_8' => array(),
            'arplitetemplate_11' => array(),
            'arplitetemplate_26' => array(),
        ));

        return $arplite_default_skin_luminosity;
    }

    function arplite_depended_section_color_codes() {
        $arplite_depended_section_color_code = apply_filters('arplite_depended_section_color_code', array(
            'arplitetemplate_7' => array(),
            'arplitetemplate_2' => array(),
            'arplitetemplate_1' => array(),
            'arplitetemplate_8' => array(),
            'arplitetemplate_11' => array(),
            'arplitetemplate_26' => array(),
        ));

        return $arplite_depended_section_color_code;
    }

    function arp_custom_skin_selection_section_color() {
        $arplite_custom_skin_selection_color = apply_filters('arplite_custom_skin_selection_color', array(
            'arplitetemplate_7' => array('arp_header_background_color_input', 'arp_header_background_color~|~arp_header_background_color_input'),
            'arplitetemplate_2' => array('arp_column_background_color_input', 'arp_column_background_color_data~|~arp_column_background_color_input'),
            'arplitetemplate_1' => array('arp_header_background_color_input', 'arp_header_background_color~|~arp_header_background_color_input'),
            'arplitetemplate_8' => array('arp_header_background_color_input', 'arp_header_background_color~|~arp_header_background_color_input'),
            'arplitetemplate_11' => array('arp_header_background_color_input', 'arp_header_background_color~|~arp_header_background_color_input'),
            'arplitetemplate_26' => array('arp_header_background_color_input', 'arp_header_background_color~|~arp_header_background_color_input'),
        ));

        return $arplite_custom_skin_selection_color;
    }

    function arplite_custom_css_selected_bg_color() {
        $arplite_custom_css_selected_bg_color = apply_filters('arplite_custom_css_selected_bg_color', array(
            'arplitetemplate_7' => 'arp_header_bg_custom_color',
            'arplitetemplate_2' => 'arp_column_bg_custom_color',
            'arplitetemplate_1' => 'arp_header_bg_custom_color',
            'arplitetemplate_8' => 'arp_header_bg_custom_color',
            'arplitetemplate_11' => 'arp_header_bg_custom_color',
            'arplitetemplate_26' => 'arp_header_bg_custom_color',
        ));

        return $arplite_custom_css_selected_bg_color;
    }

    function arp_background_image_section_array() {

        $arplite_global_bg_image_section = apply_filters('arplite_global_bg_image_section', array(
            'arplitetemplate_7' => array(),
            'arplitetemplate_2' => array(),
            'arplitetemplate_1' => array(),
            'arplitetemplate_8' => array('arpcolumnheader'),
            'arplitetemplate_11' => array(),
            'arplitetemplate_26' => array(),
        ));

        return $arplite_global_bg_image_section;
    }

    function arprice_default_template_skins($post = array()) {
        $arprice_default_template_skins = apply_filters('arpricelite_default_template_skins_filter', array(
            'arplitetemplate_7' => array(
                'skin' => array('blue', 'black', 'cyan', 'lightblue', 'red', 'yellow', 'olive', 'darkpurple', 'darkred', 'pink', 'brown'),
                'color' => array('3473DC', '3E3E3C', '1EAE8B', '1BACE1', 'F33C3E', 'FFA800', '8FB021', '5B48A2', '79302A', 'ED1374', 'B11D00'),
            ),
            'arplitetemplate_2' => array(
                'skin' => array('blue', 'lightviolet', 'yellow', 'limegreen', 'orange', 'softblue', 'limecyan', 'brightred', 'red', 'pink', 'lightblue', 'darkpink', 'darkcyan'),
                'color' => array('02a3ff', '6c62d3', 'ffba00', '6ed563', 'ff9525', '4476d9', '37ba5a', 'f34044', 'de1a4c', 'de199a', '1a5fde', 'a51143', '11a599'),
            ),
            'arplitetemplate_1' => array(
                'skin' => array('green', 'yellow', 'darkorange', 'darkred', 'red', 'violet', 'pink', 'blue', 'darkblue', 'lightgreen', 'darkestblue', 'cyan', 'black', 'multicolor'),
                'color' => array('6dae2e', 'fbb400', 'e75c01', 'c32929', 'e52937', '713887', 'EB005C', '29A1D3', '2F3687', '1BA341', '2F4251', '009E7B', '5C5C5C', 'Multicolor'),
            ),
            'arplitetemplate_8' => array(
                'skin' => array('purple', 'skyblue', 'red', 'green', 'blue', 'orange', 'darkcyan', 'yellow', 'pink', 'teal', 'multicolor'),
                'color' => array('AB6ED7', '44B7E4', 'F15859', '7FB948', '595EB7', 'FF6E3D', '54CAB0', 'FFC74B', 'EC3E9A', '25D0D7', 'Multicolor'),
            ),
            'arplitetemplate_11' => array(
                'skin' => array('yellow', 'limegreen', 'red', 'blue', 'pink', 'cyan', 'lightpink', 'violet', 'gray', 'green'),
                'color' => array('EFA738', '43B34D', 'FF3241', '09B1F8', 'E3328C', '11B0B6', 'F15F74', '8F4AFF', '949494', '78C335'),
            ),
            'arplitetemplate_26' => array(
                'skin' => array('blue', 'red', 'lightblue', 'cyan', 'yellow', 'pink', 'lightviolet', 'gray', 'orange', 'darkblue', 'turquoise', 'grayishyellow', 'green'),
                'color' => array('2fb8ff', 'ff2d46', '4196ff', '00d29d', 'f1bc16', 'ff2476', '6b68ff', 'b7bdcb', 'fd9a25', '337cff', '00dbef', 'cfc5a1', '16d784'),
            ),
                ), $post);

        return $arprice_default_template_skins;
    }

    function arpricelite_get_template_skins() {

        $template_id = isset( $_POST['table_id'] ) ? intval( $_POST['table_id'] ) : '';
        $reference_id = isset( $_POST['reference_template'] ) ? sanitize_text_field( $_POST['reference_template'] ) : '';
        $default_template_skin_code = $this->arprice_default_template_skins($_POST);
        $skins = $default_template_skin_code[$reference_id]['skin'];
        $colors = $default_template_skin_code[$reference_id]['color'];
        echo wp_json_encode($default_template_skin_code[$reference_id]);
        die();
    }

    function arp_change_default_template_skins($default_array, $post,$general_options = array()) {
        global $wpdb;

        $action = isset($post['action']) ? $post['action'] : '';
        $tableid = isset($post['table_id']) ? $post['table_id'] : '';
        $reference = isset($post['reference_template']) ? $post['reference_template'] : '';

        if ($tableid == "") {
            return $default_array;
        }

        $count_general_options = count($general_options);
        
        if ($action == 'arprice_default_template_skins') {
            $query = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = " . $tableid);
        } 
        if( $count_general_options <= 0 ){
            $results = maybe_unserialize($query[0]->general_options);
        } else {
            $results = $general_options;
        }

        $custom_skin_colors = $results['custom_skin_colors'];

        $arp_column_custom_bg_color = $custom_skin_colors['arp_column_bg_custom_color'];
        $arp_column_bg_hover_color = $custom_skin_colors['arp_column_bg_hover_color'];
        $arp_column_desc_custom_color = $custom_skin_colors['arp_column_desc_bg_custom_color'];
        $arp_header_custom_bg_color = $custom_skin_colors['arp_header_bg_custom_color'];
        $arp_pricing_bg_custom_color = $custom_skin_colors['arp_pricing_bg_custom_color'];
        $arp_body_odd_row_bg_color = $custom_skin_colors['arp_body_odd_row_bg_custom_color'];
        $arp_body_even_row_bg_color = $custom_skin_colors['arp_body_even_row_bg_custom_color'];
        $arp_analytics_bgcolor = (isset($custom_skin_colors['arp_analytics_bgcolor'])) ? $custom_skin_colors['arp_analytics_bgcolor']:'#39434D';
        $arp_analytics_forgcolor =(isset($custom_skin_colors['arp_analytics_forgcolor']))? $custom_skin_colors['arp_analytics_forgcolor']:'#F5F5F5';
        
        $arp_footer_bg_custom_color = $custom_skin_colors['arp_footer_content_bg_color'];
        $arp_button_bg_custom_color = $custom_skin_colors['arp_button_bg_custom_color'];
        $arp_button_bg_hover_color = $custom_skin_colors['arp_button_bg_hover_color'];

        $arp_section_background = $this->arp_custom_skin_selection_section_color();
        $main_color = '';

        switch ($arp_section_background[$reference][0]) {
            case 'arp_header_background_color_input':
                $main_color = $arp_header_custom_bg_color;
                break;
            case 'arp_column_background_color_input':
                $main_color = $arp_column_custom_bg_color;
                break;
            case 'arp_pricing_background_color_input':
                $main_color = $arp_pricing_bg_custom_color;
                break;
            case 'arp_button_background_color_input':
                $main_color = $arp_button_bg_custom_color;
                break;
            default:
                $main_color = $arp_header_custom_bg_color;
                break;
        }

        $count = count($default_array[$reference]['color']);

        $default_array[$reference]['color'][$count] = str_replace('#', '', $main_color);
        $default_array[$reference]['skin'][$count] = 'db_custom_skin';

        return $default_array;
    }

    function arplite_css_pseudo_elements_array() {
        $arplite_css_pseudo_elements = apply_filters('arplite_css_pseudo_elements', array('::after', ':after', '::before', ':before')
        );

        return $arplite_css_pseudo_elements;
    }

    function arprice_responsive_width_array() {

        $arp_responsive_width_array = apply_filters('arplite_responsive_widths', array(
            'arplitetemplate_7' => array(
                'with_space' => array('23%'),
                'no_space' => array('25%'),
            ),
            'arplitetemplate_2' => array(
                'with_space' => array('23%'),
                'no_space' => array('25%'),
            ),
            'arplitetemplate_1' => array(
                'with_space' => array('18%'),
                'no_space' => array('20%'),
            ),
            'arplitetemplate_8' => array(
                'with_space' => array('23%'),
                'no_space' => array('25%'),
            ),
            'arplitetemplate_11' => array(
                'with_space' => array('23%'),
                'no_space' => array('25%'),
            ),
            'arplitetemplate_26' => array(
                'with_space' => array('23%'),
                'no_space' => array('25%'),
            ),
        ));

        return $arp_responsive_width_array;
    }

    function arpricelite_allow_border_radius() {

        $arpricelite_allow_border_radius = apply_filters('arpricelite_allow_border_radius', array(
            'arplitetemplate_7' => true,
            'arplitetemplate_2' => true,
            'arplitetemplate_1' => true,
            'arplitetemplate_8' => true,
            'arplitetemplate_11' => true,
            'arplitetemplate_26' => true,
        ));

        return $arpricelite_allow_border_radius;
    }

    function arp_border_bottom() {

        $arp_border_bottom_hide_footer = apply_filters('arplite_hide_footer_border_bottom', array(
            'arplitetemplate_1' => array(
                'caption_column' => array(
                    'ul.arppricingtablebodyoptions' => '1px solid #cecece',
                ),
                'other_column' => array(
                    'ul.arppricingtablebodyoptions' => '1px solid #cecece',
                ),
            ),
        ));

        return $arp_border_bottom_hide_footer;
    }

    function arprice_column_wrapper_height() {
        $arprice_col_wrapper_height = apply_filters('arpricelite_set_column_wrapper_height', array(
            'arplitetemplate_7' => 40,
            'arplitetemplate_2' => 40,
            'arplitetemplate_1' => 40,
            'arplitetemplate_8' => 20,
            'arplitetemplate_11' => 20,
            'arplitetemplate_26' => 10,
        ));
        return $arprice_col_wrapper_height;
    }

    function arpricelite_column_wrapper_default_height(){
        $arpricelite_column_wrapper_default_height = array(
            'arplitetemplate_1' => 40,
            'arplitetemplate_2' => 40,
            'arplitetemplate_7' => 40,
            'arplitetemplate_8' => 40,
            'arplitetemplate_11' => 40,
            'arplitetemplate_26' => 40,
        );
        return apply_filters('arpricelite_column_default_wrapper_height',$arpricelite_column_wrapper_default_height);
    }

    function arp_section_text_alignment() {
        $arplite_section_text_alignment = apply_filters('arplite_section_text_alignment', array(
            'arplitetemplate_7' => array(
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'pricing_section' => 'arppricetablecolumnprice',
                    'column_description_section' => 'column_description',
                    'body_section' => 'arppricingtablebodyoptions li',
                ),
            ),
            'arplitetemplate_2' => array(
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'pricing_section' => 'arppricetablecolumnprice',
                    'footer_section' => 'arpcolumnfooter',
                    'body_section' => 'arppricingtablebodyoptions li',
                ),
            ),
            'arplitetemplate_1' => array(
                'caption_column' => array(
                    'header_section' => 'arpcaptiontitle',
                    'footer_section' => 'arpcolumnfooter',
                ),
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'pricing_section' => 'arppricetablecolumnprice',
                    'body_section' => 'arppricingtablebodyoptions li',
                    'footer_section' => 'arpcolumnfooter',
                ),
            ),
            'arplitetemplate_8' => array(
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'pricing_section' => 'arppricetablecolumnprice',
                    'body_section' => 'arppricingtablebodyoptions li',
                ),
            ),
            'arplitetemplate_11' => array(
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'pricing_section' => 'arppricetablecolumnprice',
                    'column_description_section' => 'column_description',
                    'body_section' => 'arppricingtablebodyoptions li',
                ),
            ),
            'arplitetemplate_26' => array(
                'other_column' => array(
                    'header_section' => 'arppricetablecolumntitle',
                    'footer_section' => 'arpcolumnfooter',
                    'body_section' => 'arppricingtablebodyoptions li',
                ),
            ),
        ));
        return $arplite_section_text_alignment;
    }

    function arprice_hide_section_array() {
        $arprice_hide_section_array = apply_filters('arprice_hide_section_array', array(
            'arplitetemplate_7' => array(
                'arp_header' => array('.arppricetablecolumntitle'),
                'arp_price' => array('.arp_price_wrapper'),
                'arp_feature' => array('.arppricingtablebodycontent'),
                'arp_footer' => array('.arpcolumnfooter'),
                'arp_description' => array('.column_description'),
                'arp_header_shortcode' => array('.arp_header_shortcode'),
            ),
            'arplitetemplate_2' => array(
                'arp_header' => array('.arppricetablecolumntitle'),
                'arp_header_shortcode' => array('.arp_rounded_shortcode_wrapper'),
                'arp_price' => array('.arppricetablecolumnprice'),
                'arp_feature' => array('.arppricingtablebodycontent'),
                'arp_footer' => array('.arpcolumnfooter'),
            ),
            'arplitetemplate_1' => array(
                'arp_header' => array('.arppricetablecolumntitle', '.arpcaptiontitle'),
                'arp_price' => array('.arppricetablecolumnprice'),
                'arp_feature' => array('.arppricingtablebodycontent'),
                'arp_footer' => array('.arpcolumnfooter'),
            ),
            'arplitetemplate_8' => array(
                'arp_header' => array('.arppricetablecolumntitle'),
                'arp_header_shortcode' => array('.arp_header_shortcode'),
                'arp_price' => array('.arppricetablecolumnprice'),
                'arp_feature' => array('.arppricingtablebodycontent'),
                'arp_footer' => array('.arpcolumnfooter'),
            ),
            'arplitetemplate_11' => array(
                'arp_header' => array('.arppricetablecolumntitle'),
                'arp_price' => array('.arp_price_wrapper'),
                'arp_feature' => array('.arppricingtablebodycontent'),
                'arp_footer' => array('.arpcolumnfooter'),
                'arp_description' => array('.column_description'),
            ),
            'arplitetemplate_26' => array(
                'arp_header' => array('.arpcolumnheader'),
                'arp_feature' => array('.arppricingtablebodycontent'),
                'arp_footer' => array('.arpcolumnfooter'),
            ),
                )
        );

        return $arprice_hide_section_array;
    }

    function arprice_min_height_with_section_hide() {
        $arprice_min_height_with_section_hide = apply_filters('arprice_min_height_with_section_hide', array(
            'arplitetemplate_7' => array(
                'arp_header' => '',
                'arp_header_shortcode' => '',
                'arp_price' => '.arppricetablecolumnprice',
                'arp_feature' => '',
                'arp_description' => '',
                'arp_footer' => '.arppricetablecolumnprice',
            ),
            'arplitetemplate_2' => array(
                'arp_header' => '',
                'arp_header_shortcode' => '.arpcolumnheader',
                'arp_price' => '.arpcolumnheader',
                'arp_feature' => '',
                'arp_description' => '',
                'arp_footer' => '',
            ),
            'arplitetemplate_1' => array(
                'arp_header' => '.arpcolumnheader',
                'arp_header_shortcode' => '',
                'arp_price' => '.arpcolumnheader',
                'arp_feature' => '',
                'arp_description' => '',
                'arp_footer' => '',
            ),
            'arplitetemplate_11' => array(
                'arp_header' => '.arpcolumnheader',
                'arp_header_shortcode' => '',
                'arp_price' => array('.arpcolumnheader', '.arppricetablecolumnprice'),
                'arp_feature' => '',
                'arp_description' => array('.arpcolumnheader', '.arppricetablecolumnprice'),
                'arp_footer' => array('.arpcolumnheader', '.arppricetablecolumnprice'),
            ),
        ));
        return $arprice_min_height_with_section_hide;
    }

    function arp_column_border_array() {
        $arp_column_border_array = apply_filters('arp_column_border_array', array(
            'arplitetemplate_7' => array(
                'top' => '.arp_column_content_wrapper',
                'bottom' => '.arp_column_content_wrapper',
                'left' => '.arp_column_content_wrapper',
                'right' => '.arp_column_content_wrapper',
            ),
            'arplitetemplate_2' => array(
                'top' => '.arp_column_content_wrapper',
                'bottom' => '.arp_column_content_wrapper',
                'left' => '.arp_column_content_wrapper',
                'right' => '.arp_column_content_wrapper',
            ),
            'arplitetemplate_1' => array(
                'all' => '.arp_column_content_wrapper',
                'top' => '.arp_column_content_wrapper',
                'bottom' => '.arp_column_content_wrapper',
                'left' => '.arp_column_content_wrapper',
                'right' => '.arp_column_content_wrapper',
                'caption_border_all' => array(
                    'left' => '.arp_column_content_wrapper',
                    'right' => '.arp_column_content_wrapper',
                    'top' => '.arp_column_content_wrapper',
                    'bottom' => '.arp_column_content_wrapper',
                ),
            ),
            'arplitetemplate_8' => array(
                'all' => '.arp_column_content_wrapper',
                'top' => '.arp_column_content_wrapper',
                'bottom' => '.arp_column_content_wrapper',
                'left' => '.arp_column_content_wrapper',
                'right' => '.arp_column_content_wrapper',
            ),
            'arplitetemplate_11' => array(
                'all' => '.arp_column_content_wrapper',
                'top' => '.arp_column_content_wrapper',
                'bottom' => '.arp_column_content_wrapper',
                'left' => '.arp_column_content_wrapper',
                'right' => '.arp_column_content_wrapper',
            ),
            'arplitetemplate_26' => array(
                'all' => '.arp_column_content_wrapper',
                'top' => '.arp_column_content_wrapper',
                'bottom' => '.arp_column_content_wrapper',
                'left' => '.arp_column_content_wrapper',
                'right' => '.arp_column_content_wrapper',
            ),
        ));
        return $arp_column_border_array;
    }

    function arp_font_settings() {
        $arp_font_settings = apply_filters('arp_font_settings', array(
            'arplitetemplate_7' => array('arp_header_font', 'arp_price_font', 'arp_body_font', 'arp_button_font', 'arp_desc_font'),
            'arplitetemplate_2' => array('arp_header_font', 'arp_price_font', 'arp_body_font', 'arp_footer_font', 'arp_button_font'),
            'arplitetemplate_1' => array('arp_header_font', 'arp_price_font', 'arp_body_font', 'arp_footer_font', 'arp_button_font'),
            'arplitetemplate_8' => array('arp_header_font', 'arp_price_font', 'arp_body_font', 'arp_button_font'),
            'arplitetemplate_11' => array('arp_header_font', 'arp_price_font', 'arp_body_font', 'arp_button_font', 'arp_desc_font'),
            'arplitetemplate_26' => array('arp_header_font', 'arp_body_font', 'arp_button_font'),
                )
        );

        return $arp_font_settings;
    }

    function arp_row_level_border() {

        $arp_row_level_border = apply_filters('arp_row_level_border', array(
            'arplitetemplate_6' => array(
                array('.arpcolumnheader', 'border-bottom'),
            ),
        ));

        return $arp_row_level_border;
    }

    function arp_row_level_border_remove_from_last_child() {
        $arp_row_level_border_remove_from_last_child = apply_filters('arp_row_level_border_remove_from_last_child', array('arplitetemplate_2','arplitetemplate_8', 'arplitetemplate_11'));

        return $arp_row_level_border_remove_from_last_child;
    }

    function arp_exclude_caption_column_for_color_skin() {
        $arp_exclude_caption_column_for_color_skin = apply_filters('arp_exclude_caption_column_for_color_skin', array(
            'arplitetemplate_1' => false,
            'arplitetemplate_2' => false,
            'arplitetemplate_7' => false,
            'arplitetemplate_8' => false,
            'arplitetemplate_11' => false,
            'arplitetemplate_26' => false,
        ));

        return $arp_exclude_caption_column_for_color_skin;
    }

    function arp_select_previous_skin_for_multicolor() {

        $arp_select_previous_skin_for_multicolor = apply_filters('arp_select_previous_skin_for_multicolor', array(
            'arplitetemplate_1' => 'green',
            'arplitetemplate_8' => 'red',
        ));

        return $arp_select_previous_skin_for_multicolor;
    }

    function arp_navigation_section_array() {
        $arp_navigation_section_array = apply_filters('arp_navigation_section_array', array(
            'arplitetemplate_1' => array(
                'header_level' => array(
                    'caption_column' => '.arpcaptiontitle',
                    'other_columns' => '.arppricetablecolumntitle',
                ),
                'price_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arppricetablecolumnprice',
                ),
                'footer_level' => array(
                    'caption_column' => '.arpcolumnfooter',
                    'other_columns' => '.arpcolumnfooter',
                ),
                'button_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpcolumnfooter',
                ),
                'row_level' => array(
                    'caption_column' => '.arpbodyoptionrow',
                    'other_columns' => '.arpbodyoptionrow',
                ),
            ),
            'arplitetemplate_8' => array(
                'header_level' => array(
                    'caption_columns' => '',
                    'other_columns' => '.arp_header_selection_new',
                ),
                'price_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arppricetablecolumnprice',
                ),
                'row_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpbodyoptionrow',
                ),
                'footer_level' => array(
                    'caption_column' => '',
                    'other_columns' => '',
                ),
                'button_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpcolumnfooter',
                ),
            ),
            'arplitetemplate_11' => array(
                'header_level' => array(
                    'caption_columns' => '',
                    'other_columns' => '.arppricetablecolumntitle',
                ),
                'price_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arp_price_wrapper',
                ),
                'description_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.column_description',
                ),
                'row_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpbodyoptionrow',
                ),
                'footer_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpcolumnfooter',
                ),
                'button_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpcolumnfooter',
                ),
            ),
            'arplitetemplate_26' => array(
                'header_level' => array(
                    'caption_columns' => '',
                    'other_columns' => '.arp_header_selection_new',
                ),
                'price_level' => array(
                    'caption_column' => '',
                    'other_columns' => '',
                ),
                'row_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpbodyoptionrow',
                ),
                'footer_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpcolumnfooter',
                ),
                'button_level' => array(
                    'caption_column' => '',
                    'other_columns' => '.arpcolumnfooter',
                ),
            ),
        ));
        return $arp_navigation_section_array;
    }

    function arp_button_type() {
        $arp_button_type = apply_filters('arp_button_type', array(
            'shadow' => array(
                'name' => esc_html__('Shadow', 'arprice-responsive-pricing-table'),
                'class' => 'arp_shadow_button',
            ),
            'flat' => array(
                'name' => esc_html__('Flat', 'arprice-responsive-pricing-table'),
                'class' => 'arp_flat_button',
            ),
            'classic' => array(
                'name' => esc_html__('Classic', 'arprice-responsive-pricing-table'),
                'class' => 'arp_classic_button',
            ),
            'border' => array(
                'name' => esc_html__('Border', 'arprice-responsive-pricing-table'),
                'class' => 'arp_border_button',
            ),
            'reverse_border' => array(
                'name' => esc_html__('Reverse Border', 'arprice-responsive-pricing-table'),
                'class' => 'arp_reverse_border_button',
            ),
            'modern' => array(
                'name' => esc_html__('Modern', 'arprice-responsive-pricing-table'),
                'class' => 'arp_modern_button',
            ),
        ));

        return $arp_button_type;
    }

    function arp_shortcode_custom_type() {
        $arp_shortcode_custom_type = apply_filters('arp_shortcode_custom_type', array(
            'rounded' => array(
                'name' => esc_html__('Circle (Bordered)', 'arprice-responsive-pricing-table'),
                'class' => 'arp_rounded_shortcode',
                'type' => 'bordered',
            ),
            'rounded_solid' => array(
                'name' => esc_html__('Circle (Solid)', 'arprice-responsive-pricing-table'),
                'class' => 'arp_rounded_shortcode_solid',
                'type' => 'solid',
            ),
            'square' => array(
                'name' => esc_html__('Square (Bordered)', 'arprice-responsive-pricing-table'),
                'class' => 'arp_square_shortcode',
                'type' => 'bordered',
            ),
            'square_solid' => array(
                'name' => esc_html__('Square (Solid)', 'arprice-responsive-pricing-table'),
                'class' => 'arp_square_shortcode_solid',
                'type' => 'solid',
            ),
            'semiround' => array(
                'name' => esc_html__('Rounded Square (Bordered)', 'arprice-responsive-pricing-table'),
                'class' => 'arp_semiround_shortcode',
                'type' => 'bordered',
            ),
            'semiround_solid' => array(
                'name' => esc_html__('Rounded Square (Solid)', 'arprice-responsive-pricing-table'),
                'class' => 'arp_semiround_shortcode_solid',
                'type' => 'solid',
            ),
            'none' => array(
                'name' => esc_html__('None', 'arprice-responsive-pricing-table'),
                'class' => 'arp_none_shortcode',
                'type' => 'none',
            ),
        ));

        return $arp_shortcode_custom_type;
    }

    function arp_custom_css_inner_sections() {
        $arp_custom_css_inner_sections = apply_filters('arplite_custom_css_inner_sections', array(
            'arplitetemplate_1' => array(),
            'arplitetemplate_2' => array(
                'header_background' => false,
                'pricing_background' => false,
                'body_background' => false,
                'footer_background' => false,
            ),
            'arplitetemplate_7' => array(
                'pricing_background' => false,
            ),
            'arplitetemplate_8' => array(
                'pricing_background' => false,
            ),
            'arplitetemplate_11' => array(
                'pricing_background' => false,
            ),
            'arplitetemplate_26' => array(
                'body_background' => false,
            ),
        ));
        return $arp_custom_css_inner_sections;
    }

    function arp_template_bg_section_inputs() {

        $arp_tmp_bg_sec_inputs = apply_filters('arp_tmp_bg_sec_inputs', array(
            'arplitetemplate_7' => array(
                'caption_column' => array(),
                'other_column' => array(
                    'header_background_color' => 'arppricetablecolumntitle',
                    'button_background_color' => 'bestPlanButton',
                    'column_desc_background_color' => 'column_description,arppricetablecolumnprice',
                    'body_section' => array(
                        'content_odd_color' => 'arp_odd_row',
                        'content_even_color' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_2' => array(
                'caption_column' => array(),
                'other_column' => array(
                    'column_background_color' => 'arp_column_content_wrapper',
                    'button_background_color' => 'bestPlanButton',
                ),
            ),
            'arplitetemplate_1' => array(
                'caption_column' => array(
                    'header_background_color' => 'arpcaptiontitle',
                    'footer_background_color' => 'arpcolumnfooter',
                    'body_section' => array(
                        'content_odd_color' => 'arp_odd_row',
                        'content_even_color' => 'arp_even_row',
                    ),
                ),
                'other_column' => array(
                    'header_background_color' => 'arppricetablecolumntitle',
                    'price_background_color' => 'arppricetablecolumnprice',
                    'button_background_color' => 'bestPlanButton',
                    'footer_background_color' => 'arpcolumnfooter',
                    'body_section' => array(
                        'content_odd_color' => 'arp_odd_row',
                        'content_even_color' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_8' => array(
                'caption_column' => array(
                    'footer_background_color' => 'arpcolumnfooter',
                    'body_section' => array(
                        'content_odd_color' => 'arp_odd_row',
                        'content_even_color' => 'arp_even_row',
                    ),
                ),
                'other_column' => array(
                    'header_background_color' => 'arpcolumnheader',
                    'button_background_color' => 'bestPlanButton',
                    'body_section' => array(
                        'content_odd_color' => 'arp_odd_row',
                        'content_even_color' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_11' => array(
                'caption_columns' => array(),
                'other_column' => array(
                    'header_background_color' => 'arppricetablecolumntitle',
                    'column_desc_background_color' => 'arppricetablecolumnprice',
                    'button_background_color' => 'bestPlanButton',
                    'body_section' => array(
                        'content_odd_color' => 'arp_odd_row',
                        'content_even_color' => 'arp_even_row',
                    ),
                ),
            ),
            'arplitetemplate_26' => array(
                'caption_column' => array(),
                'other_column' => array(
                    'header_background_color' => 'arppricetablecolumntitle',
                    'column_background_color' => 'arp_column_content_wrapper',
                    'button_background_color' => 'bestPlanButton',
                ),
            ),
        ));
        return $arp_tmp_bg_sec_inputs;
    }

    function arp_button_size_new() {
        $arp_button_size_new = apply_filters('arplite_button_size_new', array(
            'Small' => 'arp_small_btn',
            'Medium' => 'arp_medium_btn',
            'Large' => 'arp_large_btn',
        ));

        return $arp_button_size_new;
    }

    function arp_column_bg_image_colors() {
        $arp_column_bg_image_colors = apply_filters('arplite_column_bg_image_colors', array(
            'arplitetemplate_1' => array('.bestPlanButton'),
            'arplitetemplate_2' => array('.rounded_corder', '.bestPlanButton'),
            'arplitetemplate_7' => array('.arppricetablecolumntitle', '.column_description', '.arppricetablecolumnprice', '.arp_even_row', '.arp_odd_row', '.bestPlanButton'),
            'arplitetemplate_8' => array('.rounded_corder', '.bestPlanButton'),
            'arplitetemplate_11' => array('.bestPlanButton'),
            'arplitetemplate_26' => array('.rounded_corder', '.bestPlanButton'),
        ));
        return $arp_column_bg_image_colors;
    }

    function arpricelite_default_highlighted_column_height_with_hover_effect(){
        $templates_array_for_highlighted_columns = array(
            'arplitetemplate_1' => 20,
            'arplitetemplate_2' => 20,
            'arplitetemplate_7' => 20,
            'arplitetemplate_8' => 22,
            'arplitetemplate_11' => 20,
            'arplitetemplate_26' => 20,
        );
        $arpricelite_defualt_height_hover = apply_filters('arpricelite_defualt_height_hover',$templates_array_for_highlighted_columns);
        return $arpricelite_defualt_height_hover;
    }

}
