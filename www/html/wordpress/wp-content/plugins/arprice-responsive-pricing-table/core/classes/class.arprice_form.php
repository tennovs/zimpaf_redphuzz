<?php

class arpricelite_form {

    function __construct() {

        add_action('init', array($this, 'parse_standalone_request'), 1);
        add_shortcode('arplite_header_image', array($this, 'arplite_header_image_shortcode'));
        add_action('wp_ajax_arplite_updatetabledata', array($this, 'arp_save_pricing_table'));
        add_filter('widget_text', array($this, 'arplite_widget_text_filter'), 9);
        add_action('wp_ajax_arplite_save_template_image', array($this, 'arp_save_template_image'));
        add_action('wp_ajax_update_arplite_tour_guide_value', array($this, 'update_arp_tour_guide_value'));
        add_action('wp_ajax_arplite_save_pricing_table', array($this, 'arp_save_pricing_table'));
        add_action('wp_ajax_update_subscribe_date', array($this, 'arp_update_subscribe_date'));
        add_action('wp_ajax_arplite_remove_preview_opt', array( $this, 'arplite_remove_preivew_opts'));
    }


    function arplite_header_image_shortcode($atts) {
        global $arplite_is_lightbox;
        $image_url = isset($atts['id']) ? $atts['id'] : '';
        $open_in_lightbox = ( isset($atts['open_in_lightbox']) and $atts['open_in_lightbox'] == 1 ) ? '1' : '';
        $https = is_ssl() ? 's' : '';

        $height = ( isset($atts['height']) and $atts['height'] != '' ) ? $atts['height'] : 'auto';
        $width = ( isset($atts['width']) and $atts['width'] != '' ) ? $atts['width'] : 'auto';
        if(strpos($height, 'px')===true){
            $height = str_replace("px", "", $height);
        }
        if(strpos($width, 'px')===true){
            $width = str_replace("px", "", $width);
        }
        $style_width = 'auto';
        $style_height = 'auto';
        $arpifr_width = 'width: auto;';
        $arpifr_height = 'height: auto;';
        if($width != 'auto' && $width!=''){
            $style_width = "width:".$width."px;";
            $width = " width='".$width."'";
        }else {
            $width = "";
            $style_width = "";
        }
        if($height != 'auto' && $height!=''){
            $style_height = "height:".$height."px;";
            $height = " height='".$height."'";
        }else{
            $height = "";    
            $style_height = "";
        }
        $style = "";
        if ($open_in_lightbox == 1) {
            $arplite_is_lightbox = 1;
            return "<div class='arp_header_image arp_header_image_lightbox' data-bpopup=\"<iframe class='arp_video_ifr' src='".esc_url($image_url)."' style='border:0px;margin:0px;".$arpifr_width.$arpifr_height."'></iframe>\"> 
                    <img " . $width . $height . " src='" . esc_url($image_url) . "' class='alignnone arp_video_current_img' style='".$style_width.$style_height."'/> 
                    </div>";
        } else {
            return '<div class="arp_header_image"' . ( $style != '' ? ' style="' . $style . '"' : '' ) . '><img ' . $width . $height . ' src="' . esc_url($image_url) . '" class="alignnone" style="'.$style_width.$style_height.'" /></div>';
        }
    }

    function arp_save_pricing_table() {
        global $wpdb, $arpricelite_version, $arplite_pricingtable, $arpricelite_img_css_version;

        $is_preview = false;

        if( isset( $_POST['action'] ) && 'arplite_updatetabledata' == $_POST['action']  ){
            $is_preview = true;
        }

        $_POST = json_decode(stripslashes_deep($_POST['filtered_data']), true);

        if( $is_preview ){
            $_POST['pt_action'] = esc_html('preview');
        }

        /* MODIFY PRICING TABLE BEFORE SAVING */
        $_POST = apply_filters('arplite_change_values_before_update_pricing_table', $_POST);

        $select_templates = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "arplite_arprice WHERE is_template = 0");

        $pt_action = sanitize_text_field( $_POST['pt_action'] );


        if ($select_templates > 3 && $pt_action == 'new') {
            echo 'notice~|~';
            die();
        }
        $check_caps = $arplite_pricingtable->arplite_check_user_cap('arplite_add_udpate_pricingtables',true);

        if( $check_caps != 'success' ){
            $check_caps_msg = json_decode($check_caps,true);
            if( !empty( $check_caps_msg[1] ) && 'security_error' == $check_caps_msg[1] ){
                echo 'reauth';
            } else {
                echo 'error~|~'.$check_caps_msg[0];
            }
            die;
        }


        if ($pt_action == "edit") {
            $table_id = isset( $_POST['table_id'] ) ? intval( $_POST['table_id'] ) : '';
        }

        if ($pt_action == "new") {
            $is_template = 0;
        } else if( 'edit' == $pt_action ) {
            $get_is_template = $wpdb->get_results("SELECT is_template FROM {$wpdb->prefix}arplite_arprice WHERE ID = {$table_id}");

            $is_template = $get_is_template[0]->is_template;
        }

        do_action('arplite_before_update_pricing_table', $_POST);

        $main_table_title = isset( $_POST['pricing_table_main'] ) ? stripslashes_deep( sanitize_text_field( $_POST['pricing_table_main'] ) ) : '';

        $is_tbl_preview = ( isset($_POST['is_tbl_preview'] ) && intval( $_POST['is_tbl_preview'] ) == 1 ) ? 1 : 0;

        $dt = current_time('mysql');

        $total = isset( $_POST['added_package'] ) ? intval( $_POST['added_package'] ) : 0;

        if ($main_table_title == "" && !$is_tbl_preview) {
            return;
        }

        $all_columns_data = json_decode( $_POST['arp_table_data'], true );

        $template = isset($_POST['arp_template']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_template'] ) ) : '';
        $template_name = isset($_POST['arp_template_name']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_template_name'] ) ) : '';

        $template_skin = isset($_POST['arp_template_skin_editor']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_template_skin_editor'] ) ) : '';
        $template_type = isset($_POST['arp_template_type']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_template_type'] ) ) : '';

        $template_feature = json_decode(stripslashes_deep($_POST['template_feature']), true);

        $template_setting = array('template' => $template, 'skin' => $template_skin, 'template_type' => $template_type, 'features' => $template_feature);

        $column_order = stripslashes_deep( sanitize_text_field( $_POST['pricing_table_column_order'] ) );

        $column_ord = str_replace('\'', '"', $column_order);
        $col_ord_arr = json_decode($column_ord, true);
        if ($_POST['has_caption_column'] == 1 and ! in_array('main_column_0', $col_ord_arr)){
            array_unshift($col_ord_arr, 'main_column_0');
        }
        $new_id = array();

        $new_col_order = array();
        
        if (is_array($col_ord_arr) and count($col_ord_arr) > 0) {
            foreach ($col_ord_arr as $key => $value){
                $col_ord_id = str_replace( 'main_column_', '', $value );
                if( $col_ord_id != '' ){
                    $new_id[$key] = $col_ord_id;
                    $new_col_order[] = 'main_column_' . $col_ord_id;
                }
            }
        }

        $total = count($new_id);

        if( $total > 0 ){
            $total = max($new_id);
        }

        if( $total == 0 && count($new_id) == 1 ){
            $total = 1;
        }



        $column_order = json_encode($new_col_order);

        $reference_template = isset($_POST['arp_reference_template']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_reference_template'] ) ) : '';

        $user_edited_columns = isset( $_POST['arp_user_edited_columns'] ) ? json_decode(stripslashes_deep($_POST['arp_user_edited_columns']), true) : array();

        $general_settings = array('column_order' => $column_order, 'reference_template' => $reference_template, 'user_edited_columns' => $user_edited_columns);

        $is_column_space = isset($_POST['space_between_column']) ? intval( $_POST['space_between_column'] ) : '';
        $column_space = isset($_POST['column_space']) ? intval( $_POST['column_space'] ) : '';
        $min_row_height = isset($_POST['min_row_height']) ? intval($_POST['min_row_height']) : '';
        $hover_highlight = isset($_POST['column_high_on_hover']) ? intval( $_POST['column_high_on_hover'] ) : '';
        $is_responsive = isset($_POST['is_responsive']) ? intval( $_POST['is_responsive'] ) : '';
        $all_column_width = isset($_POST['all_column_width']) ? intval( $_POST['all_column_width'] ) : '';

        $arp_row_border_size = isset($_POST['arp_row_border_size']) ? intval( $_POST['arp_row_border_size'] ) : '';
        $arp_row_border_type = isset($_POST['arp_row_border_type']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_row_border_type'] ) ) : '';
        $arp_row_border_color = isset($_POST['arp_row_border_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_row_border_color'] ) ) : '';

        $arp_caption_row_border_size = isset($_POST['arp_caption_row_border_size']) ? intval( $_POST['arp_caption_row_border_size'] ) : '';
        $arp_caption_row_border_style = isset($_POST['arp_caption_row_border_style']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_caption_row_border_style'] ) ) : '';
        $arp_caption_row_border_color = isset($_POST['arp_caption_row_border_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_caption_row_border_color'] ) ) : '';

        $arp_column_border_size = isset($_POST['arp_column_border_size']) ? intval( $_POST['arp_column_border_size'] ) : '';
        $arp_column_border_type = isset($_POST['arp_column_border_type']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_border_type'] ) ) : '';
        $arp_column_border_color = isset($_POST['arp_column_border_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_border_color'] ) ) : '';
        $arp_column_border_all = isset($_POST['arp_column_border_all']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_border_all'] ) ) : '';
        $arp_column_border_left = isset($_POST['arp_column_border_left']) ? intval( $_POST['arp_column_border_left'] ) : '';
        $arp_column_border_right = isset($_POST['arp_column_border_right']) ? intval( $_POST['arp_column_border_right'] ) : '';
        $arp_column_border_top = isset($_POST['arp_column_border_top']) ? intval( $_POST['arp_column_border_top'] ) : '';
        $arp_column_border_bottom = isset($_POST['arp_column_border_bottom']) ? intval( $_POST['arp_column_border_bottom'] ) : '';

        $arp_caption_border_color = '';
        $arp_caption_border_style = '';
        $arp_caption_border_size = '';

        $arp_caption_border_left = '';
        $arp_caption_border_right = '';
        $arp_caption_border_top = '';
        $arp_caption_border_bottom = '';

        $arp_caption_border_all = isset($_POST['arp_caption_border_all']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_caption_border_all'] ) ) : '';

        if( isset( $_POST['has_caption_column'] ) && $_POST['has_caption_column'] == 1 ){
            $caption_column_data = $all_columns_data['column_0']['column_section'];
            $caption_color_data = $all_columns_data['column_0']['color_section'];
           
            $arp_caption_border_size = isset( $caption_column_data['caption_border_size'] ) ? intval( $caption_column_data['caption_border_size'] ) : '';
            $arp_caption_border_style = isset( $caption_column_data['caption_border_style'] ) ? stripslashes_deep( sanitize_text_field( $caption_column_data['caption_border_style'] ) ) : '';
            $arp_caption_border_left = isset($caption_column_data['caption_border_left']) ? intval( $caption_column_data['caption_border_left'] ) : '';
            $arp_caption_border_right = isset($caption_column_data['caption_border_right']) ? intval( $caption_column_data['caption_border_right'] ) : '';
            $arp_caption_border_top = isset($caption_column_data['caption_border_top']) ? intval( $caption_column_data['caption_border_top'] ) : '';
            $arp_caption_border_bottom = isset($caption_column_data['caption_border_bottom']) ? intval( $caption_column_data['caption_border_bottom'] ) : '';
            $arp_caption_border_color = isset( $caption_color_data['caption_border_color'] ) ? stripslashes_deep( sanitize_text_field( $caption_color_data['caption_border_color'] ) ) : '';
            $arp_caption_row_border_color = isset( $caption_color_data['caption_row_border_color'] ) ? stripslashes_deep( sanitize_text_field( $caption_color_data['caption_row_border_color'] ) ) : '';
        }

        $hide_caption_column = isset($_POST['hide_caption_column']) ? intval( $_POST['hide_caption_column'] ) : '';
        $hide_footer_global = isset($_POST['hide_footer_global']) ? intval( $_POST['hide_footer_global'] ) : '';
        $hide_header_global = isset($_POST['hide_header_global']) ? intval( $_POST['hide_header_global'] ) : '';
        $hide_price_global = isset($_POST['hide_price_global']) ? intval( $_POST['hide_price_global'] ) : '';
        $hide_feature_global = isset($_POST['hide_feature_global']) ? intval( $_POST['hide_feature_global'] ) : '';
        $hide_description_global = isset($_POST['hide_description_global']) ? intval( $_POST['hide_description_global'] ) : '';
        $hide_header_shortcode_global = isset($_POST['hide_header_shortcode_global']) ? intval( $_POST['hide_header_shortcode_global'] ) : '';

        $column_wrapper_width_txtbox = isset($_POST['column_wrapper_width_txtbox']) ? intval( $_POST['column_wrapper_width_txtbox'] ) : '';
        $column_wrapper_width_style = isset($_POST['column_wrapper_width_style']) ? stripslashes_deep( sanitize_text_field( $_POST['column_wrapper_width_style'] ) ) : '';

        $column_box_shadow_effect = isset($_POST['column_box_shadow_effect']) ? stripslashes_deep( sanitize_text_field( $_POST['column_box_shadow_effect'] ) ) : '';

        $column_border_radius_top_left = ( isset($_POST['column_border_radius_top_left']) and ! empty($_POST['column_border_radius_top_left']) ) ? intval( $_POST['column_border_radius_top_left'] ) : 0;
        $column_border_radius_top_right = ( isset($_POST['column_border_radius_top_right']) and ! empty($_POST['column_border_radius_top_right']) ) ? intval( $_POST['column_border_radius_top_right'] ) : 0;
        $column_border_radius_bottom_right = ( isset($_POST['column_border_radius_bottom_right']) and ! empty($_POST['column_border_radius_bottom_right']) ) ? intval( $_POST['column_border_radius_bottom_right'] ) : 0;
        $column_border_radius_bottom_left = ( isset($_POST['column_border_radius_bottom_left']) and ! empty($_POST['column_border_radius_bottom_left']) ) ? intval( $_POST['column_border_radius_bottom_left'] ) : 0;
        $column_hide_blank_rows = isset($_POST['hide_blank_rows']) ? sanitize_text_field( $_POST['hide_blank_rows'] ) : '';

        $global_button_border_width = isset($_POST['arp_global_button_border_width']) ? intval( $_POST['arp_global_button_border_width'] ) : '';
        $global_button_border_type = isset($_POST['arp_global_button_border_style']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_global_button_border_style'] ) ) : '';
        $global_button_border_color = isset($_POST['arp_global_button_border_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_global_button_border_color'] ) ) : '';
        $global_button_border_radius_top_left = isset($_POST['global_button_border_radius_top_left']) ? intval( $_POST['global_button_border_radius_top_left'] ) : '';
        $global_button_border_radius_top_right = isset($_POST['global_button_border_radius_top_right']) ? intval( $_POST['global_button_border_radius_top_right'] ) : '';
        $global_button_border_radius_bottom_left = isset($_POST['global_button_border_radius_bottom_left']) ? intval( $_POST['global_button_border_radius_bottom_left'] ) : '';
        $global_button_border_radius_bottom_right = isset($_POST['global_button_border_radius_bottom_right']) ? intval( $_POST['global_button_border_radius_bottom_right'] ) : '';
        $arp_global_button_border_type = isset($_POST['arp_global_button_type']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_global_button_type'] ) ) : '';

        $arp_common_font_family_global = isset($_POST['arp_common_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_common_font_family_global'] ) ) : '';
        $header_font_family_global = isset($_POST['header_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['header_font_family_global'] ) ) : '';
        $header_font_size_global = isset($_POST['header_font_size_global']) ? intval( $_POST['header_font_size_global'] ) : '';
        $arp_header_text_alignment = isset($_POST['arp_header_text_alignment']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_header_text_alignment'] ) ) : '';

        $header_style_bold_global = isset($_POST['header_style_bold_global']) ? stripslashes_deep( sanitize_text_field( $_POST['header_style_bold_global'] ) ) : '';
        $header_style_italic_global = isset($_POST['header_style_italic_global']) ? stripslashes_deep( sanitize_text_field( $_POST['header_style_italic_global'] ) ) : '';
        $header_style_decoration_global = isset($_POST['header_style_decoration_global']) ? stripslashes_deep( sanitize_text_field( $_POST['header_style_decoration_global'] ) ) : '';

        $price_font_family_global = isset($_POST['price_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['price_font_family_global'] ) ) : '';
        $price_font_size_global = isset($_POST['price_font_size_global']) ? intval( $_POST['price_font_size_global'] ) : '';
        $arp_price_text_alignment = isset($_POST['arp_price_text_alignment']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_price_text_alignment'] ) ) : '';

        $price_style_bold_global = isset($_POST['price_style_bold_global']) ? stripslashes_deep( sanitize_text_field( $_POST['price_style_bold_global'] ) ) : '';
        $price_style_italic_global = isset($_POST['price_style_italic_global']) ? stripslashes_deep( sanitize_text_field( $_POST['price_style_italic_global'] ) ) : '';
        $price_style_decoration_global = isset($_POST['price_style_decoration_global']) ? stripslashes_deep( sanitize_text_field( $_POST['price_style_decoration_global'] ) ) : '';

        $body_font_family_global = isset($_POST['body_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['body_font_family_global'] ) ) : '';
        $body_font_size_global = isset($_POST['body_font_size_global']) ? intval( $_POST['body_font_size_global'] ) : '';
        $arp_body_text_alignment = isset($_POST['arp_body_text_alignment']) ? sanitize_text_field( $_POST['arp_body_text_alignment'] ): '';

        $body_style_bold_global = isset($_POST['body_style_bold_global']) ? stripslashes_deep( sanitize_text_field( $_POST['body_style_bold_global'] ) ) : '';
        $body_style_italic_global = isset($_POST['body_style_italic_global']) ? stripslashes_deep( sanitize_text_field( $_POST['body_style_italic_global'] ) ) : '';
        $body_style_decoration_global = isset($_POST['body_style_decoration_global']) ? stripslashes_deep( sanitize_text_field( $_POST['body_style_decoration_global'] ) ) : '';

        $footer_font_family_global = isset($_POST['footer_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['footer_font_family_global'] ) ) : '';
        $footer_font_size_global = isset($_POST['footer_font_size_global']) ? intval( $_POST['footer_font_size_global'] ) : '';
        $arp_footer_text_alignment = isset($_POST['arp_footer_text_alignment']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_footer_text_alignment'] ) ) : '';

        $footer_style_bold_global = isset($_POST['footer_style_bold_global']) ? stripslashes_deep( sanitize_text_field( $_POST['footer_style_bold_global'] ) ) : '';
        $footer_style_italic_global = isset($_POST['footer_style_italic_global']) ? stripslashes_deep( sanitize_text_field( $_POST['footer_style_italic_global'] ) ) : '';
        $footer_style_decoration_global = isset($_POST['footer_style_decoration_global']) ? stripslashes_deep( sanitize_text_field( $_POST['footer_style_decoration_global'] ) ) : '';

        $button_font_family_global = isset($_POST['button_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['button_font_family_global'] ) ) : '';
        $button_font_size_global = isset($_POST['button_font_size_global']) ? intval( $_POST['button_font_size_global'] ) : '';
        $arp_button_text_alignment = isset($_POST['arp_button_text_alignment']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_button_text_alignment'] ) ) : '';

        $button_style_bold_global = isset($_POST['button_style_bold_global']) ? stripslashes_deep( sanitize_text_field( $_POST['button_style_bold_global'] ) ) : '';
        $button_style_italic_global = isset($_POST['button_style_italic_global']) ? stripslashes_deep( sanitize_text_field( $_POST['button_style_italic_global'] ) ) : '';
        $button_style_decoration_global = isset($_POST['button_style_decoration_global']) ? stripslashes_deep( sanitize_text_field( $_POST['button_style_decoration_global'] ) ) : '';

        $description_font_family_global = isset($_POST['description_font_family_global']) ? stripslashes_deep( sanitize_text_field( $_POST['description_font_family_global'] ) ) : '';
        $description_font_size_global = isset($_POST['description_font_size_global']) ? intval( $_POST['description_font_size_global'] ) : '';
        $arp_description_text_alignment = isset($_POST['arp_description_text_alignment']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_description_text_alignment'] ) ) : '';

        $description_style_bold_global = isset($_POST['description_style_bold_global']) ? stripslashes_deep( sanitize_text_field( $_POST['description_style_bold_global'] ) ) : '';
        $description_style_italic_global = isset($_POST['description_style_italic_global']) ? stripslashes_deep( sanitize_text_field( $_POST['description_style_italic_global'] ) ) : '';
        $description_style_decoration_global = isset($_POST['description_style_decoration_global']) ? stripslashes_deep( sanitize_text_field( $_POST['description_style_decoration_global'] ) ) : '';

        $column_setting = array(
            'space_between_column' => $is_column_space,
            'column_space' => $column_space,
            'min_row_height' => $min_row_height,
            'column_highlight_on_hover' => $hover_highlight,
            'is_responsive' => $is_responsive,
            'hide_caption_column' => $hide_caption_column,
            'hide_footer_global' => $hide_footer_global,
            'hide_header_global' => $hide_header_global,
            'hide_header_shortcode_global' => $hide_header_shortcode_global,
            'hide_price_global' => $hide_price_global,
            'hide_feature_global' => $hide_feature_global,
            'hide_description_global' => $hide_description_global,
            'all_column_width' => $all_column_width,
            'column_wrapper_width_txtbox' => $column_wrapper_width_txtbox,
            'column_wrapper_width_style' => $column_wrapper_width_style,
            'column_border_radius_top_left' => $column_border_radius_top_left,
            'column_border_radius_top_right' => $column_border_radius_top_right,
            'column_border_radius_bottom_right' => $column_border_radius_bottom_right,
            'column_border_radius_bottom_left' => $column_border_radius_bottom_left,
            'column_box_shadow_effect' => $column_box_shadow_effect,
            'column_hide_blank_rows' => $column_hide_blank_rows,
            'global_button_border_width' => $global_button_border_width,
            'global_button_border_type' => $global_button_border_type,
            'global_button_border_color' => $global_button_border_color,
            'global_button_border_radius_top_left' => $global_button_border_radius_top_left,
            'global_button_border_radius_top_right' => $global_button_border_radius_top_right,
            'global_button_border_radius_bottom_left' => $global_button_border_radius_bottom_left,
            'global_button_border_radius_bottom_right' => $global_button_border_radius_bottom_right,
            'arp_global_button_type' => $arp_global_button_border_type,
            'arp_row_border_size' => $arp_row_border_size,
            'arp_row_border_type' => $arp_row_border_type,
            'arp_row_border_color' => $arp_row_border_color,
            'arp_caption_border_style' => $arp_caption_border_style,
            'arp_caption_border_size' => $arp_caption_border_size,
            'arp_column_border_size' => $arp_column_border_size,
            'arp_column_border_type' => $arp_column_border_type,
            'arp_column_border_color' => $arp_column_border_color,
            'arp_caption_border_color' => $arp_caption_border_color,
            'arp_column_border_left' => $arp_column_border_left,
            'arp_column_border_right' => $arp_column_border_right,
            'arp_column_border_top' => $arp_column_border_top,
            'arp_column_border_bottom' => $arp_column_border_bottom,
            'arp_column_border_all' => $arp_column_border_all,
            'arp_caption_border_left' => $arp_caption_border_left,
            'arp_caption_border_right' => $arp_caption_border_right,
            'arp_caption_border_top' => $arp_caption_border_top,
            'arp_caption_border_bottom' => $arp_caption_border_bottom,
            'arp_caption_border_all' => $arp_caption_border_all,
            'arp_caption_row_border_size' => $arp_caption_row_border_size,
            'arp_caption_row_border_style' => $arp_caption_row_border_style, 'arp_caption_row_border_color' => $arp_caption_row_border_color,
            'arp_common_font_family_global' => $arp_common_font_family_global,
            'header_font_family_global' => $header_font_family_global,
            'header_font_size_global' => $header_font_size_global,
            'arp_header_text_alignment' => $arp_header_text_alignment,
            'arp_header_text_bold_global' => $header_style_bold_global,
            'arp_header_text_italic_global' => $header_style_italic_global,
            'arp_header_text_decoration_global' => $header_style_decoration_global,
            'price_font_family_global' => $price_font_family_global,
            'price_font_size_global' => $price_font_size_global,
            'arp_price_text_alignment' => $arp_price_text_alignment,
            'arp_price_text_bold_global' => $price_style_bold_global,
            'arp_price_text_italic_global' => $price_style_italic_global,
            'arp_price_text_decoration_global' => $price_style_decoration_global,
            'body_font_family_global' => $body_font_family_global,
            'body_font_size_global' => $body_font_size_global,
            'arp_body_text_alignment' => $arp_body_text_alignment,
            'arp_body_text_bold_global' => $body_style_bold_global,
            'arp_body_text_italic_global' => $body_style_italic_global,
            'arp_body_text_decoration_global' => $body_style_decoration_global,
            'footer_font_family_global' => $footer_font_family_global,
            'footer_font_size_global' => $footer_font_size_global,
            'arp_footer_text_alignment' => $arp_footer_text_alignment,
            'arp_footer_text_bold_global' => $footer_style_bold_global,
            'arp_footer_text_italic_global' => $footer_style_italic_global,
            'arp_footer_text_decoration_global' => $footer_style_decoration_global,
            'button_font_family_global' => $button_font_family_global,
            'button_font_size_global' => $button_font_size_global,
            'arp_button_text_alignment' => $arp_button_text_alignment,
            'arp_button_text_bold_global' => $button_style_bold_global,
            'arp_button_text_italic_global' => $button_style_italic_global,
            'arp_button_text_decoration_global' => $button_style_decoration_global,
            'description_font_family_global' => $description_font_family_global,
            'description_font_size_global' => $description_font_size_global,
            'arp_description_text_alignment' => $arp_description_text_alignment,
            'arp_description_text_bold_global' => $description_style_bold_global,
            'arp_description_text_italic_global' => $description_style_italic_global,
            'arp_description_text_decoration_global' => $description_style_decoration_global,
        );

        $arp_column_bg_custom_color = isset($_POST['arp_column_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_background_color'] ) ) : '';

        $arp_column_desc_bg_custom_color = isset($_POST['arp_column_desc_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_desc_background_color'] ) ) : '';

        $arp_column_desc_hover_bg_custom_color = isset($_POST['arp_column_desc_hover_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_desc_hover_background_color'] ) ) : '';

        $arp_header_bg_custom_color = isset($_POST['arp_header_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_header_background_color'] ) ) : '';

        $arp_pricing_bg_custom_color = isset($_POST['arp_pricing_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_pricing_background_color'] ) ) : '';

        $arp_template_odd_row_hover_bg_color = isset($_POST['arp_body_odd_row_hover_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_odd_row_hover_background_color'] ) ) : '';

        $arp_template_odd_row_bg_color = isset($_POST['arp_body_odd_row_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_odd_row_background_color'] ) ) : '';

        $arp_body_even_row_hover_bg_custom_color = isset($_POST['arp_body_even_row_hover_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_even_row_hover_background_color'] ) ) : '';

        $arp_body_even_row_bg_custom_color = isset($_POST['arp_body_even_row_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_even_row_background_color'] ) ) : '';

        $arp_footer_content_bg_color = isset($_POST['arp_footer_content_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_footer_content_background_color'] ) ) : '';

        $arp_footer_content_hover_bg_color = isset($_POST['arp_footer_content_hover_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_footer_content_hover_background_color'] ) ) : '';

        $arp_button_bg_custom_color = isset($_POST['arp_button_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_button_background_color'] ) ) : '';

        $arp_column_bg_hover_color = isset($_POST['arp_column_bg_hover_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_column_bg_hover_color'] ) ) : '';

        $arp_button_bg_hover_color = isset($_POST['arp_button_bg_hover_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_button_bg_hover_color'] ) ) : '';

        $arp_header_bg_hover_color = isset($_POST['arp_header_bg_hover_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_header_bg_hover_color'] ) ) : '';

        $arp_price_bg_hover_color = isset($_POST['arp_price_bg_hover_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_price_bg_hover_color'] ) ) : '';

        $arp_header_font_custom_color = isset($_POST['arp_header_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_header_font_custom_color_input'] ) ) : '';

        $arp_header_font_custom_hover_color_input = isset($_POST['arp_header_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_header_font_custom_hover_color_input'] ) ) : '';

        $arp_price_font_custom_color = isset($_POST['arp_price_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_price_font_custom_color_input'] ) ) : '';

        $arp_price_font_custom_hover_color_input = isset($_POST['arp_price_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_price_font_custom_hover_color_input'] ) ) : '';

        $arp_price_duration_font_custom_color = isset($_POST['arp_price_duration_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_price_duration_font_custom_color_input'] ) ) : '';

        $arp_price_duration_font_custom_hover_color_input = isset($_POST['arp_price_duration_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_price_duration_font_custom_hover_color_input'] ) ) : '';

        $arp_desc_font_custom_color = isset($_POST['arp_desc_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_desc_font_custom_color_input'] ) ) : '';

        $arp_desc_font_custom_hover_color_input = isset($_POST['arp_desc_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_desc_font_custom_hover_color_input'] ) ) : '';

        $arp_body_label_font_custom_color = isset($_POST['arp_body_label_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_label_font_custom_color_input'] ) ) : '';

        $arp_body_label_font_custom_hover_color_input = isset($_POST['arp_body_label_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_label_font_custom_hover_color_input'] ) ) : '';

        $arp_body_font_custom_color = isset($_POST['arp_body_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_font_custom_color_input'] ) ) : '';
        $arp_body_even_font_custom_color = isset($_POST['arp_body_even_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_even_font_custom_color_input'] ) ) : '';

        $arp_body_font_custom_hover_color_input = isset($_POST['arp_body_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_font_custom_hover_color_input'] ) ) : '';
        $arp_body_even_font_custom_hover_color_input = isset($_POST['arp_body_even_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_body_even_font_custom_hover_color_input'] ) ) : '';

        $arp_footer_font_custom_color = isset($_POST['arp_footer_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_footer_font_custom_color_input'] ) ) : '';

        $arp_footer_font_custom_hover_color_input = isset($_POST['arp_footer_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_footer_font_custom_hover_color_input'] ) ) : '';

        $arp_button_font_custom_color = isset($_POST['arp_button_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_button_font_custom_color_input'] ) ) : '';

        $arp_button_font_custom_hover_color_input = isset($_POST['arp_button_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_button_font_custom_hover_color_input'] ) ) : '';

        $arp_shortocode_background = isset($_POST['arp_shortocode_background_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_shortocode_background_color'] ) ) : '';
        $arp_shortocode_font_color = isset($_POST['arp_shortocode_font_custom_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_shortocode_font_custom_color_input'] ) ) : '';
        $arp_shortcode_bg_hover_color = isset($_POST['arp_shortcode_bg_hover_color']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_shortcode_bg_hover_color'] ) ) : '';
        $arp_shortcode_font_hover_color = isset($_POST['arp_shortcode_font_custom_hover_color_input']) ? stripslashes_deep( sanitize_text_field( $_POST['arp_shortcode_font_custom_hover_color_input'] ) ) : '';

        $custom_skin_colors = array(
            "arp_header_bg_custom_color" => $arp_header_bg_custom_color,
            "arp_column_bg_custom_color" => $arp_column_bg_custom_color,
            "arp_column_desc_bg_custom_color" => $arp_column_desc_bg_custom_color,
            "arp_column_desc_hover_bg_custom_color" => $arp_column_desc_hover_bg_custom_color,
            "arp_pricing_bg_custom_color" => $arp_pricing_bg_custom_color,
            "arp_body_odd_row_bg_custom_color" => $arp_template_odd_row_bg_color,
            "arp_body_odd_row_hover_bg_custom_color" => $arp_template_odd_row_hover_bg_color,
            "arp_body_even_row_hover_bg_custom_color" => $arp_body_even_row_hover_bg_custom_color,
            "arp_body_even_row_bg_custom_color" => $arp_body_even_row_bg_custom_color,
            "arp_footer_content_hover_bg_color" => $arp_footer_content_hover_bg_color,
            "arp_footer_content_bg_color" => $arp_footer_content_bg_color,
            "arp_button_bg_custom_color" => $arp_button_bg_custom_color,
            "arp_column_bg_hover_color" => $arp_column_bg_hover_color,
            "arp_button_bg_hover_color" => $arp_button_bg_hover_color,
            "arp_header_bg_hover_color" => $arp_header_bg_hover_color,
            "arp_price_bg_hover_color" => $arp_price_bg_hover_color,
            "arp_header_font_custom_color" => $arp_header_font_custom_color,
            "arp_header_font_custom_hover_color" => $arp_header_font_custom_hover_color_input,
            "arp_price_font_custom_color" => $arp_price_font_custom_color,
            "arp_price_font_custom_hover_color" => $arp_price_font_custom_hover_color_input,
            "arp_desc_font_custom_color" => $arp_desc_font_custom_color,
            "arp_desc_font_custom_hover_color" => $arp_desc_font_custom_hover_color_input,
            "arp_body_label_font_custom_color" => $arp_body_label_font_custom_color,
            "arp_body_label_font_custom_hover_color" => $arp_body_label_font_custom_hover_color_input,
            "arp_body_font_custom_color" => $arp_body_font_custom_color,
            "arp_body_even_font_custom_color" => $arp_body_even_font_custom_color,
            "arp_body_font_custom_hover_color" => $arp_body_font_custom_hover_color_input,
            "arp_body_even_font_custom_hover_color" => $arp_body_even_font_custom_hover_color_input,
            "arp_footer_font_custom_color" => $arp_footer_font_custom_color,
            "arp_footer_font_custom_hover_color" => $arp_footer_font_custom_hover_color_input,
            "arp_button_font_custom_color" => $arp_button_font_custom_color,
            "arp_button_font_custom_hover_color" => $arp_button_font_custom_hover_color_input,
            'arp_shortocode_background' => $arp_shortocode_background,
            'arp_shortocode_font_color' => $arp_shortocode_font_color,
            'arp_shortcode_bg_hover_color' => $arp_shortcode_bg_hover_color,
            'arp_shortcode_font_hover_color' => $arp_shortcode_font_hover_color,
        );
        $tab_general_opt = array('template_setting' => $template_setting,
            'column_settings' => $column_setting,
            'general_settings' => $general_settings,
            'custom_skin_colors' => $custom_skin_colors
        );

        $general_opt = maybe_serialize($tab_general_opt);

        $row = array();
        $column_order = array();
        $preview_data = array();

        if ($total > 0) {

            if ($pt_action == "new") {
                if ($is_tbl_preview && $is_tbl_preview == 1) {
                    $temp_status = 'draft';

                    $id = $wpdb->query($wpdb->prepare('INSERT INTO ' . $wpdb->prefix . 'arplite_arprice (table_name,general_options,status,create_date,arp_last_updated_date) VALUES (%s,%s,%s,%s,%s)', sanitize_text_field($main_table_title), $general_opt, sanitize_text_field($temp_status), $dt, $dt));

                    $table_id = $wpdb->insert_id;
                } else {
                    $new_status = 'published';

                    $type_of_template = $template_feature['is_animated'];

                    $id = $wpdb->query($wpdb->prepare('INSERT INTO ' . $wpdb->prefix . 'arplite_arprice (table_name,general_options,is_animated,status,create_date,arp_last_updated_date) VALUES (%s,%s,%d,%s,%s,%s)',sanitize_text_field($main_table_title), $general_opt, $type_of_template,sanitize_text_field($new_status), $dt, $dt));
                    $table_id = $wpdb->insert_id;
                }
            } else if( $pt_action == 'preview') {
                $table_id = isset( $_POST['table_id'] ) ? intval( $_POST['table_id'] ) : 0;

                $template_data = $wpdb->get_row( $wpdb->prepare( "SELECT is_template FROM `". $wpdb->prefix . "arplite_arprice` WHERE ID = %d", $table_id ) );

                $is_template = isset( $_POST['is_template'] ) ? sanitize_text_field( $_POST['is_template'] ) : 0;
                if( isset( $template_data->is_template ) ){
                    $is_template = $template_data->is_template;
                }
                $preview_table['table_opt'] = array(
                    'table_name' => sanitize_text_field( $main_table_title ),
                    'general_options' => $general_opt,
                    'status' => sanitize_text_field( 'published' ),
                    'is_template' => $is_template,
                    'is_animated' => $template_feature['is_animated']
                );

            } else {
                $query_results = $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'arplite_arprice SET table_name = %s, general_options= %s,arp_last_updated_date=%s WHERE ID = %d', sanitize_text_field($main_table_title), $general_opt, $dt, $table_id));

                if (!isset($_POST['is_tbl_preview']))
                    $wpdb->update($wpdb->prefix . 'arplite_arprice', array('status' => sanitize_text_field('published'), 'arp_last_updated_date' => $dt), array('ID' => $table_id));
            }

            do_action('arplite_after_update_pricing_table', $table_id, $_POST);
            do_action('arplite_after_update_pricing_table' . $table_id, $table_id, $_POST);

            $table_id = apply_filters('arplite_change_values_after_update_pricing_table', $table_id, $_POST);
            $arplite_allowed_html = $arplite_pricingtable->arpricelite_allowed_html_tags();
            if (count($new_id) > 0) {
                for ($i = 0; $i <= $total; $i++) {
                    if (!in_array($i, $new_id)) {
                        continue;
                    }

                    $Title = 'column_' . $i;

                    $column_section = $all_columns_data[$Title]['column_section'];
                    $color_section = $all_columns_data[$Title]['color_section'];
                    $button_section = $all_columns_data[$Title]['button_content'];
                    $footer_content = $all_columns_data[$Title]['footer_content'];
                    $pricing_content = $all_columns_data[$Title]['pricing_content'];
                    $header_section = $all_columns_data[$Title]['header_content'];
                    $column_description = $all_columns_data[$Title]['column_description'];
                    $rows_data = $all_columns_data[$Title]['rows'];

                    $body_section = isset( $all_columns_data[$Title]['body_section'] ) ? $all_columns_data[$Title]['body_section'] : array();

                    $column_width = isset( $column_section['column_width'] ) ? intval($column_section['column_width']) : '';

                    $caption = isset($_POST['caption_column_' . $i]) ? intval( $_POST['caption_column_' . $i] ) : 0;

                    $column_hide = isset($_POST['column_hide_' . $i]) ? intval( $_POST['column_hide_' . $i] ) : 0;

                    $cstm_rbn_txt = isset( $column_section['arp_custom_ribbon'] ) ? wp_kses( $column_section['arp_custom_ribbon'], $arplite_allowed_html ) : '';
                    $column_highlight = isset( $column_section['column_highlight'] ) ? intval( $column_section['column_highlight'] ) : '';

                    $column_background_color = isset( $color_section['column_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['column_bg_color'] ) ) : '';
                    $column_hover_background_color = isset( $color_section['column_hover_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['column_hover_bg_color'] ) ) : '';

                    $column_background_image = isset( $column_section['column_background_image'] ) ? stripslashes_deep(  sanitize_text_field( $column_section['column_background_image'] ) ) : '';
                    $column_background_image_height = isset( $column_section['column_background_image_height'] ) ? intval( $column_section['column_background_image_height'] ) : '';
                    $column_background_image_width = isset( $column_section['column_background_image_width'] ) ? intval( $column_section['column_background_image_width'] ) : '';
                    $column_background_scaling = isset( $column_section['column_background_scaling'] ) ? stripslashes_deep( sanitize_text_field( $column_section['column_background_scaling'] ) ) : '';
                    $column_background_min_positon = isset( $column_section['column_background_min_positon'] ) ? intval( $column_section['column_background_min_positon'] ) : '';
                    $column_background_max_positon = isset( $column_section['column_background_max_positon'] ) ? intval( $column_section['column_background_max_positon'] ) : '';

                    $arp_shortcode_customization_size = isset( $header_section['shortcode_size'] ) ? stripslashes_deep( sanitize_text_field( $header_section['shortcode_size'] ) ) : '';
                    $arp_shortcode_customization_style = isset( $header_section['shortcode_style'] ) ? stripslashes_deep( sanitize_text_field( $header_section['shortcode_style'] ) ) : '';

                    $shortcode_background_color = isset( $color_section['shortcode_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['shortcode_bg_color'] ) ) : '';
                    $shortcode_font_color = isset( $color_section['shortcode_font_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['shortcode_font_color'] ) ) : '';

                    $shortcode_hover_background_color = isset( $color_section['shortcode_hover_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['shortcode_hover_bg_color'] ) ) : '';
                    $shortcode_hover_font_color = isset( $color_section['shortcode_hover_font_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['shortcode_hover_font_color'] ) ) : '';

                    $body_text_alignemnt = isset($body_section['alignment']) ? stripslashes_deep( sanitize_text_field( $body_section['alignment'] ) ) : '';//reputelog

                    $btn_size = isset( $button_section['size'] ) ? stripslashes_deep( sanitize_text_field( $button_section['size'] ) ) : '';
                    $btn_height = isset( $button_section['height'] ) ? stripslashes_deep( sanitize_text_field( $button_section['height'] ) ) : '';
                    //$btn_type = isset($_POST['button_type_' . $i]) ? $_POST['button_type_' . $i] : '';
                    $hide_default_btn = isset( $button_section['hide_default_btn'] ) ? intval( $button_section['hide_default_btn'] ) : '';
                    $btn_img = isset( $button_section['image'] ) ? sanitize_text_field( $button_section['image'] ) : '';
                    $btn_img_height = isset( $button_section['image_height'] ) ? stripslashes_deep( sanitize_text_field( $button_section['image_height'] ) ) : '';
                    $btn_img_width = isset( $button_section['image_width'] ) ? sanitize_text_field( $button_section['image_width'] ) : '';
                    $is_new_window = isset( $button_section['is_new_window'] ) ? intval( $button_section['is_new_window'] ) : '';
                    $is_new_window_actual = isset( $button_section['is_new_window_actual'] ) ? intval( $button_section['is_new_window_actual'] ) : '';
                    $is_nofollow_link = isset( $button_section['is_nofollow_link'] ) ? intval( $button_section['is_nofollow_link'] ) : '';

                    if ( isset($table_columsn[$Title]['row_order']) && ( !$table_columns[$Title]['row_order'] || !is_array($table_columns[$Title]['row_order']))) {
                        parse_str($_POST[$Title . '_row_order'], $col_row_order);
                        $row_order = isset($col_row_order) ? $col_row_order : '';
                    } else {
                        $row_order = isset($table_columns[$Title]['row_order']) ? $table_columns[$Title]['row_order'] : '';
                    }

                    $header_background_color = isset( $color_section['header_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['header_bg_color'] ) ) : '';
                    $header_hover_background_color = isset( $color_section['header_hover_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['header_hover_bg_color'] ) ) : '';

                    $header_font_color = isset($color_section['header_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['header_font_color'] ) ) : '';
                    $header_hover_font_color = isset($color_section['header_hover_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['header_hover_font_color'] ) ) : '';
                    

                    $header_font_family = isset($header_section['font_family']) ? stripslashes_deep( sanitize_text_field( $header_section['font_family'] ) ) : '';
                    $header_font_size = isset($header_section['font_size']) ? intval($header_section['font_size']) : '';
                    
                    $header_font_align = isset($header_section['alignment']) ? stripslashes_deep( sanitize_text_field( $header_section['alignment'] ) ) : '';

                    $header_font_style = isset($values['header_font_style_' . $i]) ? sanitize_text_field( $values['header_font_style_' . $i] ) : '';
                    $header_style_bold = isset( $header_section['font_bold'] ) ? sanitize_text_field( $header_section['font_bold'] ) : '';
                    $header_style_italic = isset( $header_section['font_italic'] ) ? sanitize_text_field( $header_section['font_italic'] ) : '';
                    $header_style_decoration = isset( $header_section['font_decoration'] ) ? sanitize_text_field( $header_section['font_decoration'] ) : '';

                    $header_background_image = isset($_POST['arp_header_background_image_' . $i]) ? stripslashes_deep($_POST['arp_header_background_image_' . $i]) : '';

                    $header_margin_top = isset( $header_section['margin_top'] ) ? stripslashes_deep( sanitize_text_field( $header_section['margin_top'] ) ) : '';

                    $header_min_height = isset( $header_section['min_height'] ) ? stripslashes_deep( sanitize_text_field( $header_section['min_height'] ) ) : '';

                    $hscode_min_height = isset( $header_section['shortcode_min_height'] ) ? stripslashes_deep( sanitize_text_field( $header_section['shortcode_min_height'] ) ) : '';
                    $price_min_height = isset( $pricing_content['min_height'] ) ? stripslashes_deep( sanitize_text_field( $pricing_content['min_height'] ) ) : '';

                    $col_desc_min_height = isset( $column_description['min_height'] ) ? stripslashes_deep( sanitize_text_field( $column_description['min_height'] ) ) : '';

                    $footer_min_height = isset( $footer_content['min_height'] ) ? stripslashes_deep( sanitize_text_field( $footer_content['min_height']) ) : '';

                    $button_min_height = isset( $button_section['min_height'] ) ? stripslashes_deep( sanitize_text_field( $button_section['min_height']) ) : '';

                    $price_background_color = isset( $color_section['price_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['price_bg_color'] ) ) : '';
                    $price_hover_background_color = isset( $color_section['price_hover_bg_color'] ) ? stripslashes_deep( sanitize_text_field( $color_section['price_hover_bg_color'] ) ) : '';

                    $price_font_color = isset($color_section['price_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['price_font_color'] ) ) : '';
                    $price_hover_font_color = isset($color_section['price_hover_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['price_hover_font_color'] ) ) : '';
                    
                    

                    $price_text_font_color = isset($color_section['price_text_font_color']) ? stripslashes_deep($color_section['price_text_font_color']) : '';
                    $price_text_hover_font_color = isset($color_section['price_text_hover_font_color']) ? stripslashes_deep($color_section['price_text_hover_font_color']) : '';

                    $content_font_color = isset($color_section['content_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_font_color'] ) ) : '';
                    $content_even_font_color = isset($color_section['content_even_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_even_font_color'] ) ) : '';
                    $content_hover_font_color = isset($color_section['content_hover_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_hover_font_color'] ) ) : '';
                    $content_even_hover_font_color = isset($color_section['content_even_hover_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_even_hover_font_color'] ) ) : '';

                    $content_odd_color = isset($color_section['content_odd_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_odd_color'] ) ) : '';
                    $content_odd_hover_color = isset($color_section['content_odd_hover_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_odd_hover_color'] ) ) : '';
                    $content_even_color = isset($color_section['content_even_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_even_color'] ) ) : '';
                    $content_even_hover_color = isset($color_section['content_even_hover_color']) ? stripslashes_deep( sanitize_text_field( $color_section['content_even_hover_color'] ) ) : '';

                    $content_font_family = isset( $body_section['font_family'] ) ? stripslashes_deep( sanitize_text_field( $body_section['font_family' ] ) ) :'';
                    $content_font_size = isset( $body_section['font_size'] ) ? intval( $body_section['font_size' ] ) :'';
                    $content_font_alignment = isset( $body_section['alignment'] ) ? stripslashes_deep( sanitize_text_field( $body_section['alignment'] ) ) : '';

                    
                    $button_background_color = isset($color_section['button_bg_color']) ? stripslashes_deep(sanitize_text_field( $color_section['button_bg_color'] ) ) : '';
                    $button_hover_background_color = isset($color_section['button_hover_bg_color']) ? stripslashes_deep(sanitize_text_field( $color_section['button_hover_bg_color'] ) ) : '';
                    $button_font_color = isset($color_section['button_font_color']) ? stripslashes_deep(sanitize_text_field( $color_section['button_font_color'] ) ) : '';
                    $button_hover_font_color = isset($color_section['button_hover_font_color']) ? stripslashes_deep(sanitize_text_field( $color_section['button_hover_font_color'] ) ) : '';
                
                    /* reputelog - start */
                    $button_font_family = isset($_POST['button_font_family_' . $i]) ? stripslashes_deep($_POST['button_font_family_' . $i]) : '';
                    $button_font_size = isset($_POST['button_font_size_' . $i]) ? $_POST['button_font_size_' . $i] : '';
                    $button_font_style = isset($_POST['button_font_style_' . $i]) ? stripslashes_deep($_POST['button_font_style_' . $i]) : '';

                    $button_style_bold = isset($_POST['button_style_bold_' . $i]) ? $_POST['button_style_bold_' . $i] : '';
                    $button_style_italic = isset($_POST['button_style_italic_' . $i]) ? $_POST['button_style_italic_' . $i] : '';
                    $button_style_decoration = isset($_POST['button_style_decoration_' . $i]) ? $_POST['button_style_decoration_' . $i] : '';
                    /* reputelog - end */

                    $column_description_font_color = isset($color_section['column_description_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['column_description_font_color'] ) ) : '';
                    $column_description_hover_font_color = isset($color_section['column_description_hover_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['column_description_hover_font_color'] ) ) : '';
                    $column_desc_background_color = isset($color_section['column_desc_bg_color']) ? stripslashes_deep( sanitize_text_field( $color_section['column_desc_bg_color'] ) ) : '';
                    $column_desc_hover_background_color = isset($color_section['column_desc_hover_bg_color']) ? stripslashes_deep( sanitize_text_field( $color_section['column_desc_hover_bg_color'] ) ) : '';

                    
                    $footer_background_color = isset($color_section['footer_background_color']) ? stripslashes_deep( sanitize_text_field( $color_section['footer_background_color'] ) ) : '';
                    $footer_hover_background_color = isset($color_section['footer_hover_background_color']) ? stripslashes_deep( sanitize_text_field( $color_section['footer_hover_background_color'] ) ) : '';
                    $footer_level_options_font_color = isset($color_section['footer_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['footer_font_color'] ) ) : '';
                    $footer_level_options_hover_font_color = isset($color_section['footer_hover_font_color']) ? stripslashes_deep( sanitize_text_field( $color_section['footer_hover_font_color'] ) ) : '';

                    $footer_content_position = isset($footer_content['position']) ? intval($footer_content['position']) : '';
                    $footer_text_align = isset($footer_content['alignment']) ? stripslashes_deep( sanitize_text_field( $footer_content['alignment'] ) ) : '';                    
                    $footer_level_options_font_family = isset($footer_content['font_family']) ? stripslashes_deep( sanitize_text_field( $footer_content['font_family'] ) ) : '';
                    $footer_level_options_font_size = isset($footer_content['font_size']) ? intval( $footer_content['font_size'] ) : '';
                    $footer_level_options_font_style_bold = isset($footer_content['font_bold']) ? stripslashes_deep( sanitize_text_field( $footer_content['font_bold'] ) ) : '';
                    $footer_level_options_font_style_italic = isset($footer_content['font_italic']) ? stripslashes_deep( sanitize_text_field( $footer_content['font_italic'] ) ) : '';
                    $footer_level_options_font_style_decoration = isset($footer_content['font_decoration']) ? stripslashes_deep( sanitize_text_field( $footer_content['font_decoration'] ) ) : '';

                    $total_rows = isset($_POST['total_rows_' . $i]) ? intval( $_POST['total_rows_' . $i] ) : '';

                    if( '' == $total_rows ){
                        $total_rows = count( $rows_data );
                    }

                    $row = array();
                    if( $total_rows > 0 ){
                        for( $j = 0; $j < $total_rows; $j++ ){
                            
                            $row_title = 'row_' . $j;
                            
                            $rowsOpts = $rows_data[$row_title];

                            $row_content_type = isset( $rowsOpts['content_type'] ) ? intval( $rowsOpts['content_type'] ) : '';

                            $row_custom_css = isset( $rowsOpts['custom_css'] ) ? sanitize_text_field( $rowsOpts['custom_css'] ) : '';

                            $row_hover_custom_css = isset( $rowsOpts['hover_custom_css'] ) ? sanitize_text_field( $rowsOpts['hover_custom_css'] ) : '';

                            $row_min_height = isset( $rowsOpts['min_height'] ) ? sanitize_text_field( $rowsOpts['min_height'] ) : '';

                            $row[$row_title] = array(
                                'row_content_type' => $row_content_type,
                                'row_custom_css' => $row_custom_css,
                                'row_hover_custom_css' => $row_hover_custom_css,
                                'row_min_height' => $row_min_height
                            );
                            
                            $row[$row_title]['row_description'] = isset($rowsOpts['description']) ? stripslashes_deep( wp_kses( $rowsOpts['description'], $arplite_allowed_html ) ) : '';
                            $row[$row_title]['row_tooltip'] = isset($rowsOpts['tooltip']) ? stripslashes_deep( wp_kses( $rowsOpts['tooltip'], $arplite_allowed_html ) ) : '';
                            $row[$row_title]['row_label'] = isset($values['row_' . $i . '_label_' . $j]) ? stripslashes_deep( wp_kses( $values['row_' . $i . '_label_' . $j], $arplite_allowed_html ) ) : '';
                        
                        }
                    }

                    $ribbon_settings = array(
                        'arp_ribbon' => isset( $column_section['arp_ribbon'] ) ? $column_section['arp_ribbon'] : '',
                        'arp_ribbon_bgcol' => isset( $column_section['arp_ribbon_bgcol'] ) ? $column_section['arp_ribbon_bgcol'] : '',
                        'arp_ribbon_txtcol' => isset( $column_section['arp_ribbon_txtcol'] ) ? $column_section['arp_ribbon_txtcol'] : '',
                        'arp_ribbon_position' => isset( $column_section['arp_ribbon_position'] ) ? $column_section['arp_ribbon_position'] : '',
                        'arp_ribbon_custom_position_rl' => isset( $column_section['arp_ribbon_custom_position_rl'] ) ? intval($column_section['arp_ribbon_custom_position_rl']) : '',
                        'arp_ribbon_custom_position_top' => isset( $column_section['arp_ribbon_custom_position_top'] ) ? intval($column_section['arp_ribbon_custom_position_top']) : '',
                    );

                    $ribbon_settings['arp_ribbon_content'] = isset($column_section['arp_ribbon_content']) ? stripslashes_deep( sanitize_text_field( $column_section['arp_ribbon_content'] ) ) : '';
                    $ribbon_settings['arp_custom_ribbon'] = isset($column_section['arp_custom_ribbon']) ? stripslashes_deep( sanitize_text_field( $column_section['arp_custom_ribbon'] ) ) : '';

                    $column[$Title] = array(
                        'column_width' => $column_width,
                        'is_caption' => $caption,
                        'custom_ribbon_txt' => $cstm_rbn_txt,
                        'column_highlight' => $column_highlight,
                        'column_hide' => $column_hide,
                        'column_background_color' => $column_background_color,
                        'column_hover_background_color' => $column_hover_background_color,
                        'column_background_image' => $column_background_image,
                        'column_background_image_height' => $column_background_image_height,
                        'column_background_image_width' => $column_background_image_width,
                        'column_background_scaling' => $column_background_scaling,
                        'column_background_min_positon' => $column_background_min_positon,
                        'column_background_max_positon' => $column_background_max_positon,
                        'arp_shortcode_customization_size' => $arp_shortcode_customization_size,
                        'arp_shortcode_customization_style' => $arp_shortcode_customization_style,
                        'shortcode_background_color' => $shortcode_background_color,
                        'shortcode_font_color' => $shortcode_font_color,
                        'shortcode_hover_background_color' => $shortcode_hover_background_color,
                        'shortcode_hover_font_color' => $shortcode_hover_font_color,
                        'gmap_marker' => isset($google_map_marker) ? $google_map_marker : '',
                        'body_text_alignment' => $body_text_alignemnt,
                        'rows' => $row,
                        'button_size' => $btn_size,
                        'button_height' => $btn_height,
                        //'button_type' => $btn_type,
                        'hide_default_btn' => $hide_default_btn,
                        'btn_img' => $btn_img,
                        'btn_img_height' => $btn_img_height,
                        'btn_img_width' => $btn_img_width,
                        'is_new_window' => $is_new_window,
                        'is_new_window_actual' => $is_new_window_actual,
                        'is_nofollow_link' => $is_nofollow_link,
                        'row_order' => $row_order,
                        'ribbon_setting' => $ribbon_settings,
                        'header_background_color' => $header_background_color,
                        'header_hover_background_color' => $header_hover_background_color,
                        'header_font_family' => $header_font_family,
                        'header_font_size' => $header_font_size,
                        'header_font_color' => $header_font_color,
                        'header_hover_font_color' => $header_hover_font_color,
                        'header_font_align' => $header_font_align,
                        'header_font_style' => $header_font_style,
                        'header_style_bold' => $header_style_bold,
                        'header_style_italic' => $header_style_italic,
                        'header_style_decoration' => $header_style_decoration,
                        'header_background_image' => $header_background_image,
                        'price_background_color' => $price_background_color,
                        'price_hover_background_color' => $price_hover_background_color,                        
                        'price_font_color' => $price_font_color,
                        'price_hover_font_color' => $price_hover_font_color,                        
                        'price_text_font_color' => $price_text_font_color,
                        'price_text_hover_font_color' => $price_text_hover_font_color,                        
                        'content_font_family' => $content_font_family,
                        'content_font_size' => $content_font_size,
                        'body_text_alignment' => $content_font_alignment,
                        'content_font_color' => $content_font_color,
                        'content_even_font_color' => $content_even_font_color,
                        'content_hover_font_color' => $content_hover_font_color,
                        'content_even_hover_font_color' => $content_even_hover_font_color,
                        'content_odd_color' => $content_odd_color,
                        'content_odd_hover_color' => $content_odd_hover_color,
                        'content_even_color' => $content_even_color,
                        'content_even_hover_color' => $content_even_hover_color,                        
                        'button_background_color' => $button_background_color,
                        'button_hover_background_color' => $button_hover_background_color,
                        'button_font_family' => $button_font_family,
                        'button_font_size' => $button_font_size,
                        'button_font_color' => $button_font_color,
                        'button_hover_font_color' => $button_hover_font_color,
                        'button_font_style' => $button_font_style,
                        'button_style_bold' => $button_style_bold,
                        'button_style_italic' => $button_style_italic,
                        'button_style_decoration' => $button_style_decoration,
                        'column_description_font_color' => $column_description_font_color,
                        'column_description_hover_font_color' => $column_description_hover_font_color,
                        'column_desc_background_color' => $column_desc_background_color,
                        'column_desc_hover_background_color' => $column_desc_hover_background_color,                        
                        'footer_content_position' => $footer_content_position,
                        'footer_text_align' => $footer_text_align,
                        'footer_level_options_font_family' => $footer_level_options_font_family,
                        'footer_background_color' => $footer_background_color,
                        'footer_hover_background_color' => $footer_hover_background_color,
                        'footer_level_options_font_size' => $footer_level_options_font_size,
                        'footer_level_options_font_color' => $footer_level_options_font_color,
                        'footer_level_options_hover_font_color' => $footer_level_options_hover_font_color,
                        'footer_level_options_font_style_bold' => $footer_level_options_font_style_bold,
                        'footer_level_options_font_style_italic' => $footer_level_options_font_style_italic,
                        'footer_level_options_font_style_decoration' => $footer_level_options_font_style_decoration,
                        'header_margin_top' => $header_margin_top,
                        'header_min_height' => $header_min_height,
                        'shortcode_min_height' => $hscode_min_height,
                        'price_min_height' => $price_min_height,
                        'col_desc_min_height' => $col_desc_min_height,
                        'footer_min_height' => $footer_min_height,
                        'button_min_height' => $button_min_height
                    );        
                
                    $column[$Title]['package_title'] = isset($header_section['header_title']) ? stripslashes_deep( wp_kses( $header_section['header_title'], $arplite_allowed_html ) ) : '';

                    $column[$Title]['column_description'] = isset($column_description['description']) ? stripslashes_deep( wp_kses( $column_description['description'], $arplite_allowed_html ) ) : '';
                      
                    $column[$Title]['post_variables_content'] = isset($column_section['post_variables_content']) ? stripslashes_deep( sanitize_text_field( $column_section['post_variables_content'] ) ) : '';

                    $column[$Title]['arp_header_shortcode'] = isset($header_section['header_shortcode']) ? stripslashes_deep( $header_section['header_shortcode'] ) : '';

                    if( $caption ){
                        $column[$Title]['html_content'] = isset($header_section['header_title']) ? stripslashes_deep( wp_kses( $header_section['header_title'], $arplite_allowed_html ) ) : '';
                    }
                      
                    $column[$Title]['price_text'] = isset($pricing_content['price_text']) ? stripslashes_deep( wp_kses( $pricing_content['price_text'], $arplite_allowed_html ) ) : '';
                      
                    $column[$Title]['button_text'] = isset($button_section['btn_content']) ? stripslashes_deep( wp_kses( $button_section['btn_content'], $arplite_allowed_html ) ) : '';
                      
                    $column[$Title]['paypal_code'] = isset($button_section['embed_script']) ? stripslashes_deep( wp_kses( $button_section['embed_script'], $arplite_allowed_html ) ) : '';
                      
                    $column[$Title]['button_url'] = isset($button_section['btn_url']) ? stripslashes_deep( esc_url_raw( $button_section['btn_url'] ) ) : '';
                      
                    $column[$Title]['footer_content'] = isset($footer_content['footer_content']) ? stripslashes_deep(wp_kses( $footer_content['footer_content'], $arplite_allowed_html ) ) : '';
            
            
                }
            }
        } else {
            return;
        }

        $tbl_opt['columns'] = $column;
        $tbl_opt['column_order'] = $column_order;
        $table_options = maybe_serialize($tbl_opt);
        
        if ($pt_action == "new") {
            $ins = $wpdb->query($wpdb->prepare('INSERT INTO ' . $wpdb->prefix . 'arplite_arprice_options (table_id,table_options) VALUES (%d,%s)', $table_id, $table_options));

            $css_file_name = $template_name . '.css';

            WP_Filesystem();

            global $wp_filesystem;
            $arguments = array(
                'sslverify' => false
            );
            if (file_exists(ARPLITE_PRICINGTABLE_DIR . '/css/templates/' . $template_name . '_v' . $arpricelite_img_css_version . '.css')) {
                $css_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/' . $template_name .'_v' . $arpricelite_img_css_version . '.css';
                $css_content = wp_remote_get( $css_url, $arguments );
                $css = $css_content['body'];
            } else {

                if (file_exists(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/' . $css_file_name)){
                    $css_url = ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/' . $css_file_name;
                    $css_content = wp_remote_get( $css_url, $arguments );
                    $css = $css_content['body'];
                } else {
                    $css_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/' . $reference_template . '_v' . $arpricelite_img_css_version . '.css';
                    $css_content = wp_remote_get( $css_url, $arguments );
                    $css = $css_content['body'];
                }
            }

            $css_new = preg_replace('/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $table_id, $css);

            $css_new = str_replace('../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_new);

            $path = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/';

            $file_name = 'arplitetemplate_' . $table_id . '.css';

            $wp_filesystem->put_contents($path . $file_name, $css_new, 0777);
        } else if( $pt_action == 'preview' ){
            $random = rand(11, 9999);
            if (get_option('arplite_previewtabledata_' . $random) != ''){
                $random = rand(11, 9999);
            }
            $preview_table['table_col_opt'] = $table_options;
            update_option('arplite_previewtabledata_' . $random, json_encode( $preview_table ) );
            echo 'arplite_previewtabledata_' . $random;
        } else {
            $ins = $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'arplite_arprice_options SET table_options = %s WHERE table_id = %d', $table_options, $table_id));
            $query = $wpdb->get_row($wpdb->prepare('SELECT is_template FROM ' . $wpdb->prefix . 'arplite_arprice WHERE ID = %d', $table_id));

            $is_template = $query->is_template;

            if ($is_template == 0 and ! file_exists(ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/arplitetemplate_' . $table_id . '.css')) {

                WP_Filesystem();

                global $wp_filesystem;

                $css_file_name = $template_name . '.css';

                $ref_id = str_replace('arplitetemplate_', '', $reference_template);
                if( $ref_id >= 20 ){
                    $ref_id = $ref_id - 3;
                    $reference_template = 'arplitetemplate_'.$ref_id;
                }
                $arguments = array(
                    'sslverify' => false
                );
                if (file_exists(ARPLITE_PRICINGTABLE_DIR . '/css/templates/' . $reference_template . '_v' . $arpricelite_img_css_version . '.css')) {
                    $css_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/' . $reference_template . '_v' . $arpricelite_img_css_version . '.css';
                    $css_content = wp_remote_get( $css_url, $arguments );
                    $css = $css_content['body'];

                } else {
                    if (file_exists(ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/' . $css_file_name)){
                        $css_url = ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/' . $css_file_name;
                        $css_content = wp_remote_get( $css_url, $arguments );
                        $css = $css_content['body'];
                    } else {
                        $css_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/' . $reference_template . '_v' . $arpricelite_img_css_version . '.css';
                        $css_content = wp_remote_get( $css_url, $arguments );
                        $css = $css_content['body'];
                    }
                }

                $css_new = preg_replace('/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $table_id, $css);

                $css_new = str_replace('../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_new);

                $path = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/';

                $file_name = 'arplitetemplate_' . $table_id . '.css';

                $wp_filesystem->put_contents($path . $file_name, $css_new, 0777);
            }
        }


        /* Query for delete preview data option start */
        $all_previewoption = get_option('arplite_previewoptions');
        $all_previewoption = maybe_unserialize($all_previewoption);
        if ($all_previewoption && count($all_previewoption) > 0) {
            $option_to_delete = array();
            $day_ago_time = strtotime("-2 days");
            $all_previewoption_db = $all_previewoption;
            foreach ($all_previewoption as $opt_name => $opt_date) {
                if (isset($opt_name) && $opt_name != '' && $opt_name != '0' && $opt_date <= $day_ago_time) {
                    $option_to_delete[] = $opt_name;
                    unset($all_previewoption_db[$opt_name]);
                }
            }
            if ($option_to_delete && count($option_to_delete) > 0) {
                update_option('arplite_previewoptions', sanitize_text_field($all_previewoption_db));  // Update Remaining options
                $option_to_delete_str = implode("','", $option_to_delete);
                $option_to_delete_str = "'" . $option_to_delete_str . "'";
                $wpdb->query("DELETE FROM " . $wpdb->options . " WHERE option_name IN (" . $option_to_delete_str . ")");
            }
        }
        /* Query for delete preview data option end */

        $get_counter = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "arplite_arprice WHERE is_template = 0");
        $already_displayed = get_option('arplite_display_popup_date');
        $popup = "";
        if ($get_counter == 1 && $already_displayed == '' && $pt_action == 'new') {
            $is_subscribed = get_option('arplite_already_subscribe');
            $display_popup = get_option('arplite_popup_display');
            if ($is_subscribed === 'no') {
                update_option('arplite_popup_display', sanitize_text_field('yes'));
            }
        }

        if( $pt_action != 'preview' ){
            echo $pt_action . '~|~' . $table_id . '~|~' . $is_template;
        }

        die();
    }

    function create($values = array()) {
        global $wpdb;

        $form_name = $values['name'];
        $dt = current_time('mysql');
        $status = $values['status'];
        $table_id = $values['ID'];
        $template = $values['is_template'];
        $template_name = $values['template_name'];
        $is_animated = $values['is_animated'];
        $options = $values['options'];

        $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "arplite_arprice (ID,table_name,template_name,general_options,is_template,is_animated,status,create_date,arp_last_updated_date) VALUES (%d,%s,%d,%s,%d,%d,%s,%s,%s) ", $table_id, sanitize_text_field($form_name), sanitize_text_field($template_name), $options, $template, $is_animated, sanitize_text_field($status), $dt, $dt));


        return $wpdb->insert_id;
    }

    function new_release_update($values = array()) {
        global $wpdb;

        $form_name = $values['name'];
        $dt = current_time('mysql');
        $status = $values['status'];
        $template = $values['is_template'];
        $template_name = $values['template_name'];
        $is_animated = $values['is_animated'];
        $options = $values['options'];

        
        $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "arplite_arprice set general_options = %s where template_name = %d ", $options, sanitize_text_field($template_name)));

        return $template_name;
    }

    function option_create($table_id, $opts) {
        global $wpdb;
        $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "arplite_arprice_options(ID,table_id,table_options) VALUES (%d,%d,%s)", $table_id, $table_id, $opts));
    }

    function new_release_option_update($table_id, $opts) {
        global $wpdb;
        
        $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "arplite_arprice_options set table_options = %s where table_id = %d ", $opts, $table_id));
    }

    function get_direct_link($tbl_id = '', $chk_preview = false) {

        if (!$chk_preview) {
            $target_url = esc_url(wp_nonce_url(get_home_url() . '/index.php','arplite_home_preview','_wpnonce').'&plugin=arpricelite&arpaction=preview&tbl=' . $tbl_id);
        } else {
            $target_url = esc_url(wp_nonce_url(get_home_url() . '/index.php','arplite_home_preview','_wpnonce').'&plugin=arpricelite&arpaction=preview&home_view=1&tbl=' . $tbl_id);
        }

        if (is_ssl()) {
            $target_url = str_replace('http://', 'https://', $target_url);
        }

        return $target_url;
    }

    function parse_standalone_request() {
        global $arpricelite_form;
        $plugin = isset($_REQUEST['plugin']) ? sanitize_text_field( $_REQUEST['plugin'] ) : '';

        $action = isset($_REQUEST['arpaction']) ? sanitize_text_field( $_REQUEST['arpaction'] ) : '';

        if (!empty($plugin) and $plugin == 'arpricelite' and ! empty($action) and $action == 'preview') {

            $table_id = isset($_REQUEST['tbl']) ? intval( $_REQUEST['tbl'] ) : '';
            $arpricelite_form->preview_table($table_id);
            exit;
        }
    }

    function preview_table($table_id) {

        header("Content-Type: text/html; charset=utf-8");

        header("Cache-Control: no-cache, must-revalidate, max-age=0");

        $is_tbl_preview = 1;

        require(ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_preview.php');
    }

    function edit_template() {
        global $wpdb;
        $arpaction_new = 'new';
        if (isset($_REQUEST['template_type']) && $_REQUEST['template_type'] == 'new') {
            
        } else if (isset($_REQUEST['template_type']) && $_REQUEST['template_type'] != '') {
            $template_id = sanitize_text_field( $_REQUEST['template_type'] );

            $tbl_res = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = %d", $template_id));

            $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d", $tbl_res->ID));

            $new_values = array();

            $new_values['table_name'] = isset($tbl_res->table_name) ? sanitize_text_field($tbl_res->table_name) : '';
            $new_values['general_options'] = isset($tbl_res->general_options) ? $tbl_res->general_options : '';
            $new_values['is_template'] = 0;
            $new_values['status'] = sanitize_text_field('draft');
            $new_current_date = current_time('mysql');
            $new_values['create_date'] = $new_current_date;
            $new_values['arp_last_updated_date'] = $new_current_date;

            $res = $wpdb->insert($wpdb->prefix . "arplite_arprice", $new_values);
            $table_id = $wpdb->insert_id;

            $new_values = array();
            $new_values['table_id'] = $table_id;
            $new_values['table_options'] = isset($results->table_options) ? $results->table_options : '';
            $res = $wpdb->insert($wpdb->prefix . "arplite_arprice_options", $new_values);

            $general_option = maybe_unserialize($tbl_res->general_options);

            $general_font_settings = isset($general_option['font_settings']) ? $general_option['font_settings'] : array();

            $general_column_settings = isset($general_option['font_settings']) ? $general_option['column_settings'] : array();

            $general_tooltip_settings = isset($general_option['tooltip_settings']) ? $general_option['tooltip_settings'] : array();

            $new_values = array();

            $arpaction_new = 'edit';
        }

        if (file_exists(ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_listing_editor.php')){
            include(ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_listing_editor.php');
        }
    }

    function arp_render_customcss($table_id, $general_option, $front_preview, $opts, $is_animated) {
        global $arplite_mainoptionsarr, $arpricelite_fonts, $arpricelite_form, $arpricelite_default_settings;

        $template_section_array = $arpricelite_default_settings->arp_column_section_background_color();

        $returnstring = "";

        $template_type = $general_option['template_setting']['template_type'];

        $general_column_settings = $general_option['column_settings'];

        $general_template_settings = $general_option['template_setting'];

        $template_color_skin = $general_template_settings['skin'];

        $general_settings = $general_option['general_settings'];

        $user_edited_columns = $general_settings['user_edited_columns'];

        $column_order = $general_settings['column_order'];

        $col_ord_arr = json_decode($column_order, true);

        $temp_cols = $opts['columns'];

        $new_cols = array();
        $new_cols['columns'] = array();
        if (is_array($col_ord_arr) && count($col_ord_arr) > 0) {
            foreach ($col_ord_arr as $key => $value) {
                $new_value = str_replace('main_', '', $value);
                $new_col_id = $new_value;
                foreach ($opts['columns'] as $j => $columns) {
                    if ($new_col_id == $j) {
                        $new_cols['columns'][$new_col_id] = $columns;
                    }
                }
            }
        } else {
            $new_cols = $opts;
        }

        $opts = $new_cols;

        $reference_template = $general_option['general_settings']['reference_template'];
        if (isset($general_template_settings['template_feature']) and ! empty($general_template_settings['template_feature'])) {
            $template_feature = maybe_unserialize($general_template_settings['template_feature']);
        } else {
            
            $template_feature = maybe_unserialize($general_template_settings['features']);
        }

        $new_values = array();

        $new_values['space_between_column'] = isset($general_column_settings['space_between_column']) ? 1 : 0;

        $new_values['column_space'] = $general_column_settings['column_space'];

        $new_values['min_row_height'] = isset( $general_column_settings['min_row_height'] ) ? $general_column_settings['min_row_height'] : '';

        $new_values['highlight_column'] = isset($general_column_settings['highlightcolumnonhover']) ? 1 : 0;

        if ($front_preview == 1 || $front_preview == 2) {
            $new_values['caption_style'] = $template_feature['caption_style'];
        } else {
            $new_values['caption_style'] = $general_template_settings['features']['caption_style'];
        }

        $new_values['column_wrapper_width_txtbox'] = isset( $general_column_settings['column_wrapper_width_txtbox'] ) ? $general_column_settings['column_wrapper_width_txtbox'] : '';

        $new_values['column_wrapper_width_style'] = isset($general_column_settings['column_wrapper_width_style']) ? $general_column_settings['column_wrapper_width_style'] : '';

        $new_values['column_border_radius_top_left'] = ( isset($general_column_settings['column_border_radius_top_left']) and ! empty($general_column_settings['column_border_radius_top_left']) ) ? $general_column_settings['column_border_radius_top_left'] : 0;
        $new_values['column_border_radius_top_right'] = ( isset($general_column_settings['column_border_radius_top_right']) and ! empty($general_column_settings['column_border_radius_top_right']) ) ? $general_column_settings['column_border_radius_top_right'] : 0;
        $new_values['column_border_radius_bottom_right'] = ( isset($general_column_settings['column_border_radius_bottom_right']) and ! empty($general_column_settings['column_border_radius_bottom_right']) ) ? $general_column_settings['column_border_radius_bottom_right'] : 0;
        $new_values['column_border_radius_bottom_left'] = ( isset($general_column_settings['column_border_radius_bottom_left']) and ! empty($general_column_settings['column_border_radius_bottom_left']) ) ? $general_column_settings['column_border_radius_bottom_left'] : 0;

        $is_responsive = $general_column_settings['is_responsive'];

        $is_columnhover_on = $general_column_settings['column_highlight_on_hover'];



        $arp_column_bg_hover_color = $general_option['custom_skin_colors']['arp_column_bg_hover_color'];

        $arp_button_bg_hover_color = $general_option['custom_skin_colors']['arp_button_bg_hover_color'];

        $arp_header_bg_hover_color = $general_option['custom_skin_colors']['arp_header_bg_hover_color'];

        $is_columnanimation_on = ( isset($general_column_animation['is_animation']) and $general_column_animation['is_animation'] == 'yes' ) ? 1 : 0;

        extract($new_values);

        $default_luminosity = $arpricelite_default_settings->arplite_default_skin_luminosity();

        $luminosity = ($default_luminosity[$reference_template]) ? $default_luminosity[$reference_template][0] : '';
        $template_inputs = $arpricelite_default_settings->arp_template_bg_section_inputs();
        $template_inputs_ = $template_inputs[$reference_template];

        if (is_array($opts['columns'])) {
            foreach ($opts['columns'] as $c => $columns) {

                $column_type = "";
                $col_arr_key = 0;
                if ($columns['is_caption'] == 1)
                    $column_type = "caption_column";
                else
                    $column_type = "other_column";
                $col = str_replace('column_', '', $c);
                if ($column_type == 'caption_column') {
                    $col_arr_key = 0;
                } else {
                    $col_arr_key = $col % 4;
                    $col_arr_key = ($col_arr_key > 0) ? $col_arr_key : 4;
                }

                $is_colum_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_column_bg_color = (is_array($template_inputs_['caption_column']) && array_key_exists('column_background_color', $template_inputs_['caption_column'])) ? true : false;
                } else {
                    $is_column_bg_color = (is_array($template_inputs_['other_column']) && array_key_exists('column_background_color', $template_inputs_['other_column'])) ? true : false;
                }

                if (isset($columns['column_background_color']) && $columns['column_background_color'] != '' && $is_column_bg_color) {


                    $gradient_arr = $arpricelite_default_settings->arplite_default_gradient_templates();
                    $gradient_col = $arpricelite_default_settings->arplite_default_gradient_templates_colors();
                    $gradient_default_skin = $gradient_arr['default_only'];
                    $gradient_all_skin = $gradient_arr['all_skins'];
                    $all_skin_template = 0;
                    $default_skin_template = 0;

                    if (in_array($reference_template, $gradient_all_skin)) {
                        $all_skin_template = 1;
                        $default_skin_template = 0;
                    } else if (in_array($reference_template, $gradient_default_skin)) {
                        $all_skin_template = 0;
                        $default_skin_template = 1;
                    }

                    $css_class = $arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['column_section'];

                    $explode_css_class = explode(",", $css_class);

                    if ($all_skin_template == 1 || $default_skin_template == 1) {

                        foreach ($explode_css_class as $css_class) {
                            $colors = $gradient_col[$reference_template]['arp_color_skin']['arp_css']['column_level_gradient'][$css_class][$template_color_skin];

                            if ($template_color_skin == 'custom_skin') {
                                foreach ($explode_css_class as $column_class) {

                                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c $column_class,";
                                    $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c $column_class{";

                                    if ($colors[$col_arr_key] == "") {
                                        $properties[] = "background";
                                        $values[] = $columns['column_background_color'];
                                        foreach ($properties as $arkey => $arvalue) {
                                            $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                        }
                                    } else {
                                        $properties = array();
                                        $values = array();

                                        $colors = explode('___', $colors[$col_arr_key]);
                                        $color1 = $colors[0];
                                        $color2 = $colors[1];
                                        $putcol = $colors[2];

                                        if ($color1 == '{arp_column_background_color}') {
                                            $color1 = str_replace('{arp_column_background_color}', $columns['column_background_color'], $color1);
                                        }

                                        preg_match('/\d{2,3}|(\.\d{2,3})/', $color2, $matches);


                                        if ($matches[0] != "") {
                                            $matches[0] = $matches[0];
                                            $color2 = $this->arp_generate_color_tone($color1, $matches[0]);
                                        } else {
                                            $color2 = $colors[1];
                                        }


                                        if ($putcol == 1) {
                                            $first_color = $color1;
                                            $base_color = $color1;
                                            $color1 = $color2;
                                        } else {
                                            $first_color = $color1;
                                            $color1 = $color1;
                                            $base_color = $color2;
                                        }

                                        $properties[] = "background";
                                        $values[] = $first_color;
                                        $properties[] = "background-color";
                                        $values[] = $first_color;
                                        $properties[] = "background-image";
                                        $values[] = "-moz-linear-gradient(top,$base_color,$color1)";
                                        $properties[] = "background-image";
                                        $values[] = "-webkit-gradient(linear,0 0, 100%, from(), to($base_color,$color1))";
                                        $properties[] = "background-image";
                                        $values[] = "-webkit-linear-gradient(top,$base_color,$color1)";
                                        $properties[] = "background-image";
                                        $values[] = "-o-linear-gradient(top,$base_color,$color1)";
                                        $properties[] = "background-image";
                                        $values[] = "linear-gradient(to bottom,$base_color,$color1)";
                                        $properties[] = "background-repeat";
                                        $values[] = "repeat-x";
                                        $properties[] = "filter";
                                        $values[] = "progid:DXImageTransform.Microsoft.gradient(startColorstr='$base_color', endColorstr='$color1', GradientType=0)";
                                        $properties[] = "-ms-filter";
                                        $values[] = "progid:DXImageTransform.Microsoft.gradient (startColorstr=$base_color, endColorstr=$color1, GradientType=0)";
                                        foreach ($properties as $arkey => $arvalue) {
                                            $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                        }
                                    }
                                    $returnstring .= "}";
                                }
                            } else {

                                $colors = $colors[$col_arr_key];
                                foreach ($explode_css_class as $column_class) {
                                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c $column_class,";
                                    $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c $column_class{";

                                    $colors_new = $gradient_col[$reference_template]['arp_color_skin']['arp_css']['column_level_gradient'][$css_class][$template_color_skin];
                                    $column_bg_color = $columns['column_background_color'];
                                    $default_gradient_colors = array();
                                    if (is_array($colors_new) && !empty($colors_new)) {
                                        foreach ($colors_new as $key => $tmpcol) {
                                            $default_gradient_colors[$key] = substr($tmpcol, 0, 7);
                                        }
                                    }

                                    if (( $colors == "")) {
                                        $properties[] = "background";
                                        $values[] = $columns['column_background_color'];
                                        foreach ($properties as $arkey => $arvalue) {
                                            $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                        }
                                    } else {
                                        $properties = array();
                                        $values = array();

                                        $colors = explode('___', $colors);
                                        $color1 = $colors[0];
                                        $color2 = $colors[1];
                                        $putcol = $colors[2];

                                        if ($putcol == 1) {
                                            $first_color = $color1;
                                            $base_color = $color1;
                                            $color1 = $color2;
                                        } else {
                                            $first_color = $color1;
                                            $color1 = $color1;
                                            $base_color = $color2;
                                        }

                                        $properties[] = "background";
                                        $values[] = $first_color;
                                        $properties[] = "background-color";
                                        $values[] = $first_color;
                                        $properties[] = "background-image";
                                        $values[] = "-moz-linear-gradient(top,$base_color,$color1)";
                                        $properties[] = "background-image";
                                        $values[] = "-webkit-gradient(linear,0 0, 100%, from(), to($base_color,$color1))";
                                        $properties[] = "background-image";
                                        $values[] = "-webkit-linear-gradient(top,$base_color,$color1)";
                                        $properties[] = "background-image";
                                        $values[] = "-o-linear-gradient(top,$base_color,$color1)";
                                        $properties[] = "background-image";
                                        $values[] = "linear-gradient(to bottom,$base_color,$color1)";
                                        $properties[] = "background-repeat";
                                        $values[] = "repeat-x";
                                        $properties[] = "filter";
                                        $values[] = "progid:DXImageTransform.Microsoft.gradient(startColorstr='$base_color', endColorstr='$color1', GradientType=0)";
                                        $properties[] = "-ms-filter";
                                        $values[] = "progid:DXImageTransform.Microsoft.gradient (startColorstr=$base_color, endColorstr=$color1, GradientType=0)";
                                        foreach ($properties as $arkey => $arvalue) {
                                            $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                        }
                                    }
                                    $returnstring .= "}";
                                }
                            }
                        }
                    } else {

                        foreach ($explode_css_class as $column_class) {
                            if (!empty($column_class)) {
                                $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c $column_class,";
                                $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c $column_class{";
                                $returnstring .= "background-color:{$columns['column_background_color']};";
                                $returnstring .= "}";
                            }
                        }
                    }
                }

                /* ==== Column Section Background ==== */

                /* ==== Column Desc Section Background ==== */
                $is_column_desc_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_column_desc_bg_color = ( is_array($template_inputs_['caption_column']) && array_key_exists('column_desc_background_color', $template_inputs_['caption_column'])) ? true : false;
                } else {
                    $is_column_desc_bg_color = ( is_array($template_inputs_['other_column']) && array_key_exists('column_desc_background_color', $template_inputs_['other_column'])) ? true : false;
                }

                if (isset($columns['column_desc_background_color']) && $columns['column_desc_background_color'] != '' && $is_column_desc_bg_color) {

                    $back_sect_class = explode(',', $arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['desc_selection']);

                    foreach( $back_sect_class as $value ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$value,";
                        $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$value{";

                        $returnstring .= "background-color:{$columns['column_desc_background_color']};";

                        $returnstring .= "}";
                    }
                }

                /* ==== Column Desc Section Background ==== */

                /* ==== Header Section Background ==== */
                $is_column_header_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_column_header_bg_color = (is_array($template_inputs_['caption_column']) && array_key_exists('header_background_color', $template_inputs_['caption_column'])) ? true : false;
                } else {
                    $is_column_header_bg_color = ( is_array($template_inputs_['other_column']) && array_key_exists('header_background_color', $template_inputs_['other_column'])) ? true : false;
                }

                if (isset($columns['header_background_color']) && $columns['header_background_color'] != '' && $is_column_header_bg_color) {

                    $explode_header_class_arr = explode(",", $arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['header_section']);

                    $gradient_arr = $arpricelite_default_settings->arplite_default_gradient_templates();
                    $gradient_col = $arpricelite_default_settings->arplite_default_gradient_templates_colors();
                    $gradient_default_skin = $gradient_arr['default_only'];
                    $gradient_all_skin = $gradient_arr['all_skins'];
                    $all_skin_template = 0;
                    $default_skin_template = 0;

                    if (in_array($reference_template, $gradient_all_skin)) {
                        $all_skin_template = 1;
                        $default_skin_template = 0;
                    } else if (in_array($reference_template, $gradient_default_skin)) {
                        $all_skin_template = 0;
                        $default_skin_template = 1;
                    }

                    foreach ($explode_header_class_arr as $explode_header_class) {

                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$explode_header_class ,";
                        $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$explode_header_class {";
                        $returnstring .= "background-color:{$columns['header_background_color']};";
                        $returnstring .= "}";
                    }
                }

                $is_column_price_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_column_price_bg_color = (is_array($template_inputs_['caption_column']) && array_key_exists('price_background_color', $template_inputs_['caption_column'])) ? true : false;
                } else {
                    $is_column_price_bg_color = (is_array($template_inputs_['other_column']) && array_key_exists('price_background_color', $template_inputs_['other_column'])) ? true : false;
                }

                if (isset($columns['price_background_color']) && $columns['price_background_color'] != '' && $is_column_price_bg_color) {
                    $gradient_arr = $arpricelite_default_settings->arplite_default_gradient_templates();
                    $gradient_col = $arpricelite_default_settings->arplite_default_gradient_templates_colors();
                    $gradient_default_skin = $gradient_arr['default_only'];
                    $gradient_all_skin = $gradient_arr['all_skins'];
                    $all_skin_template = 0;
                    $default_skin_template = 0;

                    if (in_array($reference_template, $gradient_all_skin)) {
                        $all_skin_template = 1;
                        $default_skin_template = 0;
                    } else if (in_array($reference_template, $gradient_default_skin)) {
                        $all_skin_template = 0;
                        $default_skin_template = 1;
                    }

                    $css_class = (isset($arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['pricing_section'])) ? $arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['pricing_section'] : '';

                    if ($all_skin_template == 1 || $default_skin_template == 1) {

                        $colors = $gradient_col[$reference_template]['arp_color_skin']['arp_css']['pricing_level_gradient']['.' . $css_class][$template_color_skin];

                        if ($template_color_skin == 'custom_skin') {

                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$css_class,";
                            $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$css_class{";

                            if ($colors[$col_arr_key] == "") {
                                $properties[] = "background";
                                $values[] = $columns['price_background_color'];
                                foreach ($properties as $arkey => $arvalue) {
                                    $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                }
                            } else {
                                $properties = array();
                                $values = array();

                                $colors = explode('___', $colors[$col_arr_key]);
                                $color1 = $colors[0];
                                $color2 = $colors[1];
                                $putcol = $colors[2];

                                if ($color1 == '{arp_pricing_background_color_input}') {
                                    $color1 = str_replace('{arp_pricing_background_color_input}', $columns['price_background_color'], $color1);
                                }

                                preg_match('/\d{2,3}|(\.\d{2,3})/', $color2, $matches);


                                if ($matches[0] != "") {
                                    $matches[0] = $matches[0];
                                    $color2 = $this->arp_generate_color_tone($color1, $matches[0]);
                                } else {
                                    $color2 = $colors[1];
                                }


                                if ($putcol == 1) {
                                    $first_color = $color1;
                                    $base_color = $color1;
                                    $color1 = $color2;
                                } else {
                                    $first_color = $color1;
                                    $color1 = $color1;
                                    $base_color = $color2;
                                }

                                $properties[] = "background";
                                $values[] = $first_color;
                                $properties[] = "background-color";
                                $values[] = $first_color;
                                $properties[] = "background-image";
                                $values[] = "-moz-linear-gradient(top,$base_color,$color1)";
                                $properties[] = "background-image";
                                $values[] = "-webkit-gradient(linear,0 0, 100%, from(), to($base_color,$color1))";
                                $properties[] = "background-image";
                                $values[] = "-webkit-linear-gradient(top,$base_color,$color1)";
                                $properties[] = "background-image";
                                $values[] = "-o-linear-gradient(top,$base_color,$color1)";
                                $properties[] = "background-image";
                                $values[] = "linear-gradient(to bottom,$base_color,$color1)";
                                $properties[] = "background-repeat";
                                $values[] = "repeat-x";
                                $properties[] = "filter";
                                $values[] = "progid:DXImageTransform.Microsoft.gradient(startColorstr='$base_color', endColorstr='$color1', GradientType=0)";
                                $properties[] = "-ms-filter";
                                $values[] = "progid:DXImageTransform.Microsoft.gradient (startColorstr=$base_color, endColorstr=$color1, GradientType=0)";

                                foreach ($properties as $arkey => $arvalue) {
                                    $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                }
                            }
                            $returnstring .= "}";
                        } else {

                            $colors = $colors[$col_arr_key];

                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$css_class,";
                            $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$css_class{";
                            if ($colors == "") {
                                $properties[] = "background";
                                $values[] = $columns['price_background_color'];
                                foreach ($properties as $arkey => $arvalue) {
                                    $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                }
                            } else {
                                $properties = array();
                                $values = array();
                                $colors = explode('___', $colors);
                                $color1 = $colors[0];
                                $color2 = $colors[1];
                                $putcol = $colors[2];

                                if ($putcol == 1) {
                                    $first_color = $color1;
                                    $base_color = $color1;
                                    $color1 = $color2;
                                } else {
                                    $first_color = $color1;
                                    $color1 = $color1;
                                    $base_color = $color2;
                                }

                                $properties[] = "background";
                                $values[] = $first_color;
                                $properties[] = "background-color";
                                $values[] = $first_color;
                                $properties[] = "background-image";
                                $values[] = "-moz-linear-gradient(top,$base_color,$color1)";
                                $properties[] = "background-image";
                                $values[] = "-webkit-gradient(linear,0 0, 100%, from(), to($base_color,$color1))";
                                $properties[] = "background-image";
                                $values[] = "-webkit-linear-gradient(top,$base_color,$color1)";
                                $properties[] = "background-image";
                                $values[] = "-o-linear-gradient(top,$base_color,$color1)";
                                $properties[] = "background-image";
                                $values[] = "linear-gradient(to bottom,$base_color,$color1)";
                                $properties[] = "background-repeat";
                                $values[] = "repeat-x";
                                $properties[] = "filter";
                                $values[] = "progid:DXImageTransform.Microsoft.gradient(startColorstr='$base_color', endColorstr='$color1', GradientType=0)";
                                $properties[] = "-ms-filter";
                                $values[] = "progid:DXImageTransform.Microsoft.gradient (startColorstr=$base_color, endColorstr=$color1, GradientType=0)";
                                foreach ($properties as $arkey => $arvalue) {
                                    $returnstring .= $arvalue . ':' . $values[$arkey] . ';';
                                }
                            }
                            $returnstring .= "}";
                        }
                    } else {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$css_class,";
                        $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .$css_class{";
                        $returnstring .= "background-color:{$columns['price_background_color']};";
                        $returnstring .= "}";
                    }
                }

                $is_button_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_button_bg_color = (is_array($template_inputs_['caption_column']) && array_key_exists('button_background_color', $template_inputs_['caption_column'])) ? true : false;
                } else {
                    $is_button_bg_color = (is_array($template_inputs_['other_column']) && array_key_exists('button_background_color', $template_inputs_['other_column'])) ? true : false;
                }
                if (isset($columns['button_background_color']) && $columns['button_background_color'] != '' && $is_button_bg_color) {
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['button_section']},";
                    $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['button_section']}{";
                    $returnstring .= "background-color:{$columns['button_background_color']};";
                    $returnstring .= "}";
                }

                $is_footer_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_footer_bg_color = (is_array($template_inputs_['caption_column']) && array_key_exists('footer_background_color', $template_inputs_['caption_column'])) ? true : false;
                } else {
                    $is_footer_bg_color = (is_array($template_inputs_['other_column']) && array_key_exists('footer_background_color', $template_inputs_['other_column'])) ? true : false;
                }

                if (isset($columns['footer_background_color']) && $columns['footer_background_color'] != '' && $is_footer_bg_color) {

                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['footer_section']},";
                    $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['footer_section']}{";
                    $returnstring .= "background:{$columns['footer_background_color']};";
                    $returnstring .= "}";
                }

                $is_content_odd_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_body_section = ( is_array($template_inputs_['caption_column']) && array_key_exists('body_section', $template_inputs_['caption_column']) ) ? true : false;
                    $is_content_odd_bg_color = ( $is_body_section && is_array($template_inputs_['caption_column']['body_section']) && array_key_exists('content_odd_color', $template_inputs_['caption_column']['body_section'])) ? true : false;
                   
                } else {
                    $is_body_section = is_array($template_inputs_['other_column']) && array_key_exists('body_section', $template_inputs_['other_column']) ? true : false;
                    $is_content_odd_bg_color = ($is_body_section && $template_inputs_['other_column']['body_section'] && array_key_exists('content_odd_color', $template_inputs_['other_column']['body_section'])) ? true : false;
                }

                if (isset($columns['content_odd_color']) && $columns['content_odd_color'] != '' && $is_content_odd_bg_color) {
                   
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['body_section']['odd_row']} ,";
                    $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['body_section']['odd_row']} {";
                    $returnstring .= "background:{$columns['content_odd_color']}";
                    $returnstring .= "}";
                }

                $is_content_even_bg_color = false;
                if ($column_type === 'caption_column') {
                    $is_body_section = (is_array($template_inputs_['caption_column']) && array_key_exists('body_section', $template_inputs_['caption_column'])) ? true : false;
                    $is_content_even_bg_color = ($is_body_section && is_array($template_inputs_['caption_column']['body_section']) && array_key_exists('content_even_color', $template_inputs_['caption_column']['body_section'])) ? true : false;
                } else {
                    $is_body_section = is_array($template_inputs_['other_column']) && array_key_exists('body_section', $template_inputs_['other_column']) ? true : false;
                    $is_content_even_bg_color = ($is_body_section && is_array($template_inputs_['other_column']['body_section']) && array_key_exists('content_even_color', $template_inputs_['other_column']['body_section'])) ? true : false;
                }

                if (isset($columns['content_even_color']) && $columns['content_even_color'] != '' && $is_content_even_bg_color) {
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['body_section']['even_row']} ,";
                    $returnstring .= " .arplitetemplate_$table_id #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_$c .{$arplite_mainoptionsarr['general_options']['template_bg_section_classes'][$reference_template][$column_type]['body_section']['even_row']} {";
                    $returnstring .= "background:{$columns['content_even_color']}";
                    $returnstring .= "}";
                }

                if ($columns['is_caption'] != 0) {
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arpcolumnheader .arpcaptiontitle,";
                    $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arpcolumnheader .arpcaptiontitle";
                    $returnstring .= " {";
                    $returnstring .= "font-family: '" . stripslashes($columns['header_font_family']) . "';font-size: " . $columns['header_font_size'] . "px; ";
                    if ($columns['header_style_bold'] != '')
                        $returnstring .= " font-weight: " . $columns['header_style_bold'] . ";";

                    if ($columns['header_style_italic'] != '')
                        $returnstring .= " font-style: " . $columns['header_style_italic'] . ";";

                    if ($columns['header_style_decoration'] != '')
                        $returnstring .= " text-decoration: " . $columns['header_style_decoration'] . ";";


                    $returnstring .= " color: " . $columns['header_font_color'] . "; }";
                } else {
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arpcolumnheader .bestPlanTitle,";
                    $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arpcolumnheader .bestPlanTitle{";
                    $returnstring .= " color: " . $columns['header_font_color'] . "; }";
                }


                if ($template_type == 'normal') {

                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper_text,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper_text .arp_price_value,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper_text .arp_price_duration,";
                    $returnstring .= "  .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper_text, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper_text .arp_price_value, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_wrapper_text .arp_price_duration{";

                    $returnstring .= "color:" . $columns['price_font_color'] . ";";
                    $returnstring .= "}";
                } else if ($template_type == 'advanced') {

                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_value,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_value_text,";
                    $returnstring .= "  .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_value, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_value_text{";

                    $returnstring .= "color:" . $columns['price_font_color'] . ";";
                    $returnstring .= "}";


                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_duration,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_duration_text,";
                    $returnstring .= "  .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_duration, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_price_duration_text{";

                    $returnstring .= "color:" . $columns['price_text_font_color'] . ";";
                    $returnstring .= "}";
                }

                if ($caption_style == 'style_1' || $caption_style == 'style_2') {
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li span.caption_detail,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li .arp_caption_detail_text,";
                    $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li span.caption_detail, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li .arp_caption_detail_text";

                    $returnstring .= "{";
                    $returnstring .= "color:" . $columns['content_font_color'] . ";";

                    $returnstring .= "}";
                }

                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li.arp_odd_row,";
                $returnstring .= ".arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li.arp_odd_row{";
                $returnstring .= "color:" . $columns['content_font_color'] . ";";
                $returnstring .= "}";
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li.arp_even_row,";
                $returnstring .= ".arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_opt_options li.arp_even_row{";
                $returnstring .= "color:" . $columns['content_even_font_color'] . ";";
                $returnstring .= "}";

                if (is_array($columns['rows'])) {
                    $row_count = 0;
                    foreach ($columns['rows'] as $i => $row_detail) {

                        if ($caption_style == 'style_1' || $caption_style == 'style_2') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,";

                            $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li";
                            $returnstring .= "{";

                            $returnstring .= "color:" . $columns['content_font_color'] . ";";

                            $returnstring .= "}";


                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " span.caption_detail,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " .arp_caption_detail_text,";
                            $returnstring .= ".arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " span.caption_detail, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " .arp_caption_detail_text";
                            $returnstring .= "{";

                            $returnstring .= "color:" . $columns['content_font_color'] . ";";
                            $returnstring .= "}";

                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,";
                            $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li";
                            $returnstring .= "{";

                            $returnstring .= "color:" . $columns['content_label_font_color'] . ";";

                            $returnstring .= "}";

                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " span.caption_li,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " .arp_caption_li_text,";
                            $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " span.caption_li, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count . " .arp_caption_li_text";
                            $returnstring .= "{";

                            $returnstring .= "color:" . $columns['content_label_font_color'] . ";";

                            $returnstring .= "}";

                        } else {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,";
                            $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li,.arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.no_transition).style_" . $c . " .arp_opt_options li";

                            $returnstring .= "{";
                            if ($columns['is_caption'] != 0) {
                                $returnstring .= "font-family:" . stripslashes_deep($columns['content_font_family']) . ";";
                                $returnstring .= "font-size:" . $columns['content_font_size'] . "px;";

                                if (isset( $row_detail['row_des_style_bold'] ) && $row_detail['row_des_style_bold'] != ''){
                                    $returnstring .= " font-weight: " . $row_detail['row_des_style_bold'] . ";";
                                }

                                if (isset( $row_detail['row_des_style_italic'] ) && $row_detail['row_des_style_italic'] != ''){
                                    $returnstring .= " font-style: " . $row_detail['row_des_style_italic'] . ";";
                                }

                                if ( isset( $row_detail['row_des_style_italic'] ) && $row_detail['row_des_style_decoration'] != ''){
                                    $returnstring .= " text-decoration: " . $row_detail['row_des_style_decoration'] . ";";
                                }
                            }


                            $returnstring .= "}";

                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count.",";
                            $returnstring .= ".arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition.style_" . $c . " .arp_opt_options li.arp_" . $c . "_row_" . $row_count;
                            $returnstring .= "{";
                            if ($columns['is_caption'] != 0) {
                                $returnstring .= "font-family:'" . stripslashes_deep($columns['content_font_family']) . "';";
                                $returnstring .= "font-size:" . $columns['content_font_size'] . "px;";

                                if (isset( $row_detail['row_des_style_bold'] ) && $row_detail['row_des_style_bold'] != ''){
                                    $returnstring .= " font-weight: " . $row_detail['row_des_style_bold'] . ";";
                                }

                                if ( isset( $row_detail['row_des_style_italic'] ) && $row_detail['row_des_style_italic'] != ''){
                                    $returnstring .= " font-style: " . $row_detail['row_des_style_italic'] . ";";
                                }

                                if ( isset( $row_detail['row_des_style_decoration'] ) && $row_detail['row_des_style_decoration'] != ''){
                                    $returnstring .= " text-decoration: " . $row_detail['row_des_style_decoration'] . ";";
                                }

                                $returnstring .= "color:" . $columns['content_font_color'] . ";";
                            }
                            $returnstring .= "}";
                        }
                        $row_count++;
                    }
                }

                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton:not(.SecondBestPlanButton),#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton:not(.SecondBestPlanButton) .bestPlanButton_text,";
                $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton:not(.SecondBestPlanButton), .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton:not(.SecondBestPlanButton) .bestPlanButton_text";

                $returnstring .= "{";

                $returnstring .= "color:" . $columns['button_font_color'] . ";";

                $returnstring .= "}";
                
                if (isset($columns['button_size']) && isset($columns['button_height'])) {
                    
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton,#ArpTemplate_main.arplite_front_main_container .arp_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton,";
                    $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton, .arp_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton";
                    $returnstring .= "{";
                    $returnstring .= "width:" . $columns['button_size'] . "px;";
                    $returnstring .= "height:" . $columns['button_height'] . "px;";
                    $returnstring .= "}";
                }



                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .column_description,";
                $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .column_description{";
                $returnstring .= "color:" . stripslashes_deep($columns['column_description_font_color']) . ";";
                $returnstring .= "}";

                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .caption_li,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_caption_li_text,";
                $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .caption_li, .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_caption_li_text{";

                if (isset($columns['body_label_style_bold']) && $columns['body_label_style_bold'] != '')
                    $returnstring .= " font-weight: " . $columns['body_label_style_bold'] . ";";

                if (isset($columns['body_label_style_italic']) && $columns['body_label_style_italic'] != '')
                    $returnstring .= " font-style: " . $columns['body_label_style_italic'] . ";";

                if (isset($columns['body_label_style_decoration']) && $columns['body_label_style_decoration'] != '')
                    $returnstring .= " text-decoration: " . $columns['body_label_style_decoration'] . ";";


                $returnstring .= "font-family:" . stripslashes_deep(isset($columns['content_label_font_family']) ? $columns['content_label_font_family'] : "") . ";";
                $returnstring .= "font-size:" . ( isset($columns['content_label_font_size']) ? $columns['content_label_font_size'] : "" ) . 'px;';
                $returnstring .= "color:" . ( isset($columns['content_label_font_color']) ? $columns['content_label_font_color'] : "" ) . ";";


                $returnstring .= "}";

                if ($columns['is_caption'] != 0) {

                    $returnstring .= '#ArpTemplate_main.arplite_front_main_container .arplitetemplate_' . $table_id . ' .style_column_0 .arp_footer_content,';
                    $returnstring .= '.arplitetemplate_' . $table_id . ' .style_column_0 .arp_footer_content {';
                    $returnstring .= 'margin: 5px;';
                    $returnstring .= 'color: ' . $columns['footer_level_options_font_color'] . ';';

                    $returnstring .= 'font-family: ' . $columns['footer_level_options_font_family'] . ';';
                    $returnstring .= 'font-size:' . $columns['footer_level_options_font_size'] . 'px;';
                    if ($columns['footer_level_options_font_style_bold'] == 'bold') {
                        $returnstring .= 'font-weight: bold;';
                    }
                    if ($columns['footer_level_options_font_style_italic'] == 'italic') {
                        $returnstring .= 'font-style: italic;';
                    }
                    if ($columns['footer_level_options_font_style_decoration'] == 'underline') {
                        $returnstring .= 'text-decoration: underline;';
                    } else if ($columns['footer_level_options_font_style_decoration'] == 'line-through') {
                        $returnstring .= 'text-decoration: line-through;';
                    }
                      $returnstring .= '}';
                }
              

                $arp_section_text_alignment = $arpricelite_default_settings->arp_section_text_alignment();
                $arp_section_text_alignment = isset($arp_section_text_alignment[$reference_template]) ? $arp_section_text_alignment[$reference_template] : array();                
                if ($columns['is_caption'] != 0) {
                    
                    $arp_section_text_alignment = $arp_section_text_alignment['caption_column'];
                    if (isset($columns['header_font_align']) && array_key_exists('header_section', $arp_section_text_alignment)) {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['header_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['header_section'] . "{";
                        $returnstring .="text-align:" . $columns['header_font_align'] . ";";
                        $returnstring .="}";
                    }      
                     
                    if (isset($columns['footer_text_align']) && array_key_exists('footer_section', $arp_section_text_alignment)) {
                       
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['footer_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['footer_section'] . "{";
                        $returnstring .="text-align:" . $columns['footer_text_align'] . ";";
                        $returnstring .="}";
                    }
                } else {
                    
                    $arp_section_text_alignment = isset($arp_section_text_alignment['other_column']) ? $arp_section_text_alignment['other_column'] : array();
                    
                    if (isset($general_column_settings['arp_header_text_alignment']) && array_key_exists('header_section', $arp_section_text_alignment)) {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_column_content_wrapper ." . $arp_section_text_alignment['header_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .arp_column_content_wrapper ." . $arp_section_text_alignment['header_section'] . "{";
                        
                        $returnstring .="text-align:" . $general_column_settings['arp_header_text_alignment'] . ";";
                        $returnstring .="}";
                        
                    }
                    if (isset($general_column_settings['arp_price_text_alignment']) && array_key_exists('pricing_section', $arp_section_text_alignment)) {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['pricing_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['pricing_section'] . "{";
                        $returnstring .="text-align:" . $general_column_settings['arp_price_text_alignment'] . ";";
                        $returnstring .="}";
                    }
                    if (isset($general_column_settings['arp_footer_text_alignment']) && array_key_exists('footer_section', $arp_section_text_alignment)) {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['footer_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['footer_section'] . "{";
                        $returnstring .="text-align:" . $general_column_settings['arp_footer_text_alignment'] . ";";
                        $returnstring .="}";
                    }
                    if (isset($general_column_settings['arp_body_text_alignment']) && array_key_exists('body_section', $arp_section_text_alignment)) {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['body_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['body_section'] . "{";
                        $returnstring .="text-align:" . $general_column_settings['arp_body_text_alignment'] . ";";
                        $returnstring .= "}";
                    }

                    if (isset($general_column_settings['arp_description_text_alignment']) && array_key_exists('column_description_section', $arp_section_text_alignment)) {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['column_description_section'] . ",";
                        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ." . $arp_section_text_alignment['column_description_section'] . "{";
                        $returnstring .="text-align:" . $general_column_settings['arp_description_text_alignment'] . ";";
                        $returnstring .="}";
                    }
                }
                if ($columns['is_caption'] == 0) {
                    $returnstring .= '#ArpTemplate_main.arplite_front_main_container .arplitetemplate_' . $table_id . ' .style_' . $c . ' .arp_footer_content,';
                    $returnstring .= '.arplitetemplate_' . $table_id . ' .style_' . $c . ' .arp_footer_content{';

                    $returnstring .= 'margin: 5px;';
                    $returnstring .= 'color: ' . $columns['footer_level_options_font_color'] . ';';

                    $returnstring .= '}';
                }

                if (isset($columns['arp_shortcode_customization_style']) && isset($columns['arp_shortcode_customization_size'])) {
                    $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .rounded_corder,";
                    $returnstring .= " .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .rounded_corder{";
                    $shortcode_array = $arpricelite_default_settings->arp_shortcode_custom_type();

                    $returnstring .="color : " . $columns['shortcode_font_color'] . "; ";
                    if( isset( $columns['price_background_color']) ){
                        $returnstring .="border-color : " . $columns['price_background_color'] . "; ";
                    }

                    if ( isset($shortcode_array[$columns['arp_shortcode_customization_style']]['type']) && $shortcode_array[$columns['arp_shortcode_customization_style']]['type'] == 'solid') {

                        $returnstring .="background-color : " . $columns['shortcode_background_color'] . "; ";
                    }
                    $returnstring .="border-color : " . $columns['shortcode_background_color'] . "; ";

                    $returnstring .="}";
                }



                $arp_button_type = $arpricelite_default_settings->arp_button_type();
                if ($general_column_settings['arp_global_button_type'] == 'shadow') {
                    $color = $arpricelite_form->hex2rgb($columns['button_hover_background_color']);
                    if( is_array( $color ) && count(  $color ) > 0 ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . ":not(.arp_admin_template_editor) #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton." . $arp_button_type[$general_column_settings['arp_global_button_type']]['class'] . ":hover,";
                        $returnstring .= " .arplite_price_table_" . $table_id . ":not(.arp_admin_template_editor) #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " .bestPlanButton." . $arp_button_type[$general_column_settings['arp_global_button_type']]['class'] . ":hover{";

                        $returnstring .= 'background-color:rgba(' . $color['red'] . ',' . $color['green'] . ',' . $color['blue'] . ',0.75) !important';
                        $returnstring .="}";
                    }
                }

                if( isset( $columns['header_min_height'] ) && '' != $columns['header_min_height'] ){
                    $header_min_height_data = explode( '||', $columns['header_min_height'] );
                    if( $header_min_height_data[0] > 0 && '' != $header_min_height_data[1] ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$header_min_height_data[1]."{";
                            $returnstring .= "height:" . $header_min_height_data[0] ."px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['header_margin_top'] ) && '' != $columns['header_margin_top'] ){
                    $header_margin_top_data = explode( '||', $columns['header_margin_top'] );
                    if( $header_margin_top_data[0] > 0 && '' != $header_margin_top_data[1] ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$header_margin_top_data[1]."{";
                            $returnstring .= "margin-top:" . $header_margin_top_data[0] . "px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['shortcode_min_height'] ) && '' != $columns['shortcode_min_height'] ) {
                    $shortcode_height_data = explode( '||', $columns['shortcode_min_height'] );
                    if( $shortcode_height_data[0] > 0 && '' != $shortcode_height_data[1] ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$shortcode_height_data[1]."{";
                            $returnstring .= "height:" . $shortcode_height_data[0] . "px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['price_min_height'] ) && '' != $columns['price_min_height'] ) {
                    $price_min_height_data = explode( '||', $columns['price_min_height'] );
                    if( $price_min_height_data[0] > 0 && '' !=  $price_min_height_data[1]){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$price_min_height_data[1]."{";
                            $returnstring .= "height:" . $price_min_height_data[0] . "px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['col_desc_min_height'] ) && '' != $columns['col_desc_min_height'] ){
                    $col_desc_height_data = explode( '||', $columns['col_desc_min_height'] );
                    if( $col_desc_height_data[0] > 0 && '' != $col_desc_height_data[1] ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$col_desc_height_data[1]."{";
                            $returnstring .= "height:" . $col_desc_height_data[0] . "px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['footer_min_height'] ) && '' != $columns['footer_min_height'] ) {
                    $footer_min_height_data = explode( '||', $columns['footer_min_height'] );
                    if( $footer_min_height_data[0] > 0 && '' != $footer_min_height_data[1] ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$footer_min_height_data[1]."{";
                            $returnstring .= "height:" . $footer_min_height_data[0] . "px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['button_min_height'] ) && '' != $columns['button_min_height'] ){
                    $button_min_height_data = explode( '||', $columns['button_min_height'] );
                    if( $button_min_height_data[0] > 0 && '' != $button_min_height_data[1] ){
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ".$button_min_height_data[1]."{";
                            $returnstring .= "height:" . $button_min_height_data[0] . "px;";
                        $returnstring .= "}";
                    }
                }

                if( isset( $columns['rows'] ) && count( $columns['rows'] ) > 0 ){
                    foreach( $columns['rows'] as $rk => $rv ){
                        if( isset( $rv['row_min_height'] ) && '' != $rv['row_min_height'] ){
                            $row_min_height_data = explode('||', $rv['row_min_height']);
                            if( $row_min_height_data[0] > 0 && '' != $row_min_height_data[1] ){
                                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arp_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.style_" . $c . " ul li#arp_".$c."_".$rk."{";
                                    $returnstring .= "height:" . $row_min_height_data[0] . "px;";
                                $returnstring .= "}";
                            }
                        }
                    }
                }
            }
        }

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .fa,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .fas,#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .far,";
        $returnstring .= ".arplitetemplate_" . $table_id . " .fa, .arplitetemplate_" . $table_id . " .fas, .arplitetemplate_" . $table_id . " .far{";
            $returnstring .= " font-family:'Font Awesome 5 Free' !important; ";
        $returnstring .= "}";

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .fab,";
        $returnstring .= ".arplitetemplate_" . $table_id . " .fab{";
            $returnstring .= " font-family:'Font Awesome 5 Brands' !important; ";
        $returnstring .= "}";


        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper,";
        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper{";
		      $returnstring .= "margin-right: " . ($column_space / 2) . "px;
				margin-left: " . ($column_space / 2) . "px;
			}";
        if ($column_space > 0) {
            $arp_border_array = $arpricelite_default_settings->arp_border_color();
            $arp_border_array = isset($arp_border_array[$reference_template]) ? $arp_border_array[$reference_template] : '';

            if (!empty($arp_border_array['caption_column'])) {
                foreach ($arp_border_array['caption_column'] as $class => $arr) {
                    $class_name = $class;
                    $border_size = $arr['border_size'];
                    $border_color = $arr['border_color'];
                    $border_type = $arr['border_type'];
                    $border_position = $arr['border_position'];
                    $brdposition = explode('|^|', $border_position);
                    if ($border_position == 'all') {
                        
                    } else {
                        foreach ($brdposition as $pstn) {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .maincaptioncolumn " . $class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . " .maincaptioncolumn " . $class . "{";
                            $returnstring .= "border-" . $pstn . ":" . $border_size . " " . $border_type . " " . $border_color . " !important;";
                            $returnstring .= "}";
                        }
                    }
                }
            }

            if (!empty($arp_border_array['other_column'])) {
                foreach ($arp_border_array['other_column'] as $class => $arr) {
                    $class_name = $class;
                    $border_size = $arr['border_size'];
                    $border_color = $arr['border_color'];
                    $border_type = $arr['border_type'];
                    $border_position = $arr['border_position'];
                    $brdposition = explode('|^|', $border_position);
                    if ($border_position == 'all') {
                        
                    } else {
                        foreach ($brdposition as $pstn) {

                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $class . "{";
                            $returnstring .= "border-" . $pstn . ":" . $border_size . " " . $border_type . " " . $border_color . " !important;";
                            $returnstring .= "}";
                        }
                    }
                }
            }
        }

        global $arplite_pricingtable, $arplite_templatehoverclassarr, $arpricelite_default_settings;
        $arplite_templatehoverclassarr = $arpricelite_default_settings->arp_template_hover_class_array();

        $exclude_caption = $arpricelite_default_settings->arplite_exclude_caption_column_for_color_skin();
        $is_exclude_caption = $exclude_caption[$reference_template];

        $caption_column_odd_color = !empty($opts['columns']['column_0']['content_odd_color']) ? $opts['columns']['column_0']['content_odd_color'] : '';
        $caption_column_even_color = !empty($opts['columns']['column_0']['content_even_color']) ? $opts['columns']['column_0']['content_even_color'] : '';

        $content_odd_color = isset($columns['content_odd_color'])?$columns['content_odd_color']:'';
        $content_even_color = isset($columns['content_even_color'])?$columns['content_even_color']:'';
        $skinarr = array();

        if (!empty($arplite_templatehoverclassarr[$reference_template])) {

            $common_skin = isset($arplite_templatehoverclassarr[$reference_template]['arp_common_hover_css']) ? $arplite_templatehoverclassarr[$reference_template]['arp_common_hover_css'] : '';
            $color_skins = isset($arplite_templatehoverclassarr[$reference_template]['arp_skin_hover_css']) ? $arplite_templatehoverclassarr[$reference_template]['arp_skin_hover_css'] : '';
            
            $columns = $opts['columns'];
            $element_hover = "";
            $parent_hover = "";
            $g = 1;
            $grc = 1;

            $cap_cols = array();
            $start = 0;


            foreach ($columns as $c => $column) {

                if ($column['is_caption'] == 1) {
                    $start++;
                    continue;
                }

                $col = str_replace('column_', '', $c);
                $col_arr_key = $col % 4;
                $col_arr_key = ($col_arr_key > 0) ? $col_arr_key : 4;


                $g = ($general_option['template_setting']['skin'] == 'custom_skin') ? 0 : 1;
                $caption_column_odd_color = isset($opts['columns']['column_0']['content_odd_color']) ? $opts['columns']['column_0']['content_odd_color'] : "";
                $caption_column_even_color = isset($opts['columns']['column_0']['content_even_color']) ? $opts['columns']['column_0']['content_even_color'] : "";

                $content_odd_color = isset($column['content_odd_color']) ? $column['content_odd_color'] : "";
                $content_even_color = isset($column['content_even_color']) ? $column['content_even_color'] : "";

                if (!empty($common_skin)) {
                    foreach ($common_skin as $class_key => $cskin) {

                        $str = '';
                        $class_key = explode('_^_', $class_key);
                        $class_name = $class_key[0];
                        $class_name = str_replace("[ARP_SPACE]", " ", $class_name);
                        $hover = $class_key[1];
                        if ($hover == 0) {
                            $element_hover = ":hover";
                            $parent_hover = "";
                        } else {
                            $element_hover = "";
                            $parent_hover = ":hover";
                        }

                        $str .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_$table_id .ArpPricingTableColumnWrapper.no_animation.arp_style_$start:not(.no_transition):not(.maincaptioncolumn)$parent_hover $class_name";
                        $str .= $element_hover;
                        $str .= ",#ArpTemplate_main.arplite_front_main_container .arplitetemplate_$table_id .ArpPricingTableColumnWrapper.no_animation.arp_style_$start:not(.no_transition):not(.maincaptioncolumn).column_highlight $class_name$element_hover";
                        $str .= ",";

                        $str .= ".arplitetemplate_$table_id .ArpPricingTableColumnWrapper.no_animation.arp_style_$start:not(.no_transition):not(.maincaptioncolumn)$parent_hover $class_name";
                        $str .= $element_hover;
                        $str .= ",.arplitetemplate_$table_id .ArpPricingTableColumnWrapper.no_animation.arp_style_$start:not(.no_transition):not(.maincaptioncolumn).column_highlight $class_name$element_hover";
                        $str .="{";

                        foreach ($cskin as $property => $values) {

                            $values = explode('<==>', $values);

                            $values_ = isset($values[0]) ? $values[0] : '';
                            $parameter = isset($values[1]) ? $values[1] : '';
                            $points = isset($values[2]) ? $values[2] : '';
                            if (preg_match('/____/', $values_)) {
                                $values_ = explode('____', $values_);
                            } else {
                                $value = $values_;
                            }

                            $value = ( is_array($values_) and count($values_) > 1 ) ? $values_[1] : $values_;

                            $arp_button_bg_hover_color = isset($column['button_hover_background_color']) ? $column['button_hover_background_color'] : $general_option['custom_skin_colors']['arp_button_bg_hover_color'];
                            $arp_button_hover_font_color = isset($column['button_hover_font_color']) ? $column['button_hover_font_color'] : '';

                            $arp_column_bg_hover_color = isset($column['column_hover_background_color']) ? $column['column_hover_background_color'] : $general_option['custom_skin_colors']['arp_column_bg_hover_color'];


                            if (isset($general_option['custom_skin_colors']['arp_footer_content_bg_color']) and ! empty($general_option['custom_skin_colors']['arp_footer_content_bg_color']) && $template_color_skin == 'custom_skin') {
                                $arp_footer_bg_hover_color = $general_option['custom_skin_colors']['arp_footer_content_bg_color'];
                            } else {
                                $arp_footer_bg_hover_color = isset( $column['footer_background_color'] ) ? $column['footer_background_color'] : '';
                            }


                            if (isset($general_option['custom_skin_colors']['arp_header_bg_custom_color']) and ! empty($general_option['custom_skin_colors']['arp_header_bg_custom_color']) && $template_color_skin == 'custom_skin') {
                                $arp_header_bg_hover_color = $general_option['custom_skin_colors']['arp_header_bg_custom_color'];
                            } else {
                                $arp_header_bg_hover_color = isset($column['header_hover_background_color']) ? $column['header_hover_background_color'] : $general_option['custom_skin_colors']['arp_header_bg_custom_color'];
                            }

                            $arp_header_bg_hover_custom_color = isset($column['header_hover_background_color']) ? $column['header_hover_background_color'] : $general_option['custom_skin_colors']['arp_header_bg_hover_color'];

                            $arp_header_hover_font_color = isset($column['header_hover_font_color']) ? $column['header_hover_font_color'] : '';
                            $arp_price_bg_hover_custom_color = isset($column['price_hover_background_color']) ? $column['price_hover_background_color'] : $general_option['custom_skin_colors']['arp_price_bg_hover_color'];

                            $arp_odd_row_hover_background_color = isset($column['content_odd_hover_color']) ? $column['content_odd_hover_color'] : $general_option['custom_skin_colors']['arp_body_odd_row_hover_bg_custom_color'];

                            $arp_even_row_hover_background_color = isset($column['content_even_hover_color']) ? $column['content_even_hover_color'] : $general_option['custom_skin_colors']['arp_body_even_row_hover_bg_custom_color'];

                            $arp_content_hover_font_color = isset($column['content_hover_font_color']) ? $column['content_hover_font_color'] : '';
                            $arp_content_even_hover_font_color = isset($column['content_even_hover_font_color']) ? $column['content_even_hover_font_color'] : '';
                            $arp_content_label_hover_font_color = isset($column['content_label_hover_font_color']) ? $column['content_label_hover_font_color'] : '';

                            $arp_footer_content_hover_bg_color = isset($column['footer_hover_background_color']) ? $column['footer_hover_background_color'] : $general_option['custom_skin_colors']['arp_footer_content_hover_bg_color'];
                            $arp_footer_hover_font_color = isset($column['footer_level_options_hover_font_color']) ? $column['footer_level_options_hover_font_color'] : '';

                            $arp_desc_hover_background_color = isset($column['column_desc_hover_background_color']) ? $column['column_desc_hover_background_color'] : $general_option['custom_skin_colors']['arp_column_desc_hover_bg_custom_color'];
                            $arp_desc_hover_font_color = isset($column['column_description_hover_font_color']) ? $column['column_description_hover_font_color'] : '';

                            $arp_price_backgroud_color = isset($column['price_background_color']) ? $column['price_background_color'] : '';
                            $arp_price_hover_font_color = isset($column['price_hover_font_color']) ? $column['price_hover_font_color'] : '';
                            $arp_price_label_hover_font_color = isset($column['price_text_hover_font_color']) ? $column['price_text_hover_font_color'] : '';

                            $arp_shortoce_hover_font_color = isset($column['shortcode_hover_font_color']) ? $column['shortcode_hover_font_color'] : '';
                            $arp_shortoce_hover_background_color = isset($column['shortcode_hover_background_color']) ? $column['shortcode_hover_background_color'] : '';
                            
                            $value = str_replace('{arp_even_row_hover_background_color}', $arp_even_row_hover_background_color, $value);
                            $value = str_replace('{arp_odd_row_hover_background_color}', $arp_odd_row_hover_background_color, $value);
                            $value = str_replace('{arp_price_hover_font_color}', $arp_price_hover_font_color, $value);
                            $value = str_replace('{arp_price_label_hover_font_color}', $arp_price_label_hover_font_color, $value);
                            $value = str_replace('{arp_button_background_color}', $arp_button_bg_hover_color, $value);
                            $value = str_replace('{arp_button_hover_font_color}', $arp_button_hover_font_color, $value);
                            $value = str_replace('{arp_column_hover_background_color}', $arp_column_bg_hover_color, $value);
                            $value = str_replace('{arp_footer_column_background_color}', $arp_column_bg_hover_color, $value);
                            $value = str_replace('{arp_header_background_color}', $arp_header_bg_hover_color, $value);
                            $value = str_replace('{arp_header_hover_font_color}', $arp_header_hover_font_color, $value);

                            $value = str_replace('{arp_content_hover_font_color}', $arp_content_hover_font_color, $value);

                            $value = str_replace('{arp_content_even_hover_font_color}', $arp_content_even_hover_font_color, $value);

                            $value = str_replace('{arp_footer_font_hover_color}', $arp_footer_hover_font_color, $value);
                            $value = str_replace('{arp_description_hover_font_color}', $arp_desc_hover_font_color, $value);
                            $value = str_replace('[ARP_SPACE]', ' ', $value);

                            $value = str_replace('{arp_header_bg_custom_hover_color}', $arp_header_bg_hover_custom_color, $value);
                            $column['column_background_color'] = isset($column['column_background_color'])?$column['column_background_color']:'';
                            $value = str_replace('{arp_column_background_color}', $column['column_background_color'], $value);
                              $value = str_replace('{arp_desc_hover_background_color}', $arp_desc_hover_background_color, $value);
                                $value = str_replace('{arp_footer_bg_custom_hover_color}', $arp_footer_content_hover_bg_color, $value);
                               $value = str_replace('{arp_price_hover_background_color}', $arp_price_bg_hover_custom_color, $value);
                            if ($class_name == '.rounded_corder') {
                                $shortcode_array = $arpricelite_default_settings->arp_shortcode_custom_type();
                                
                                if (isset($column['arp_shortcode_customization_style'])) {
                                    if ($shortcode_array[$column['arp_shortcode_customization_style']]['type'] == 'solid') {

                                        $value = str_replace('{arp_shortcode_background_color}', $arp_shortoce_hover_background_color, $value);
                                    } else {
                                        $value = str_replace('{arp_shortcode_background_color}', 'none', $value);
                                    }
                                }
                                $value = str_replace('{arp_shortcode_border_color}', $arp_shortoce_hover_background_color, $value);
                            }

                            $value = str_replace('{arp_shortcode_font_color}', $arp_shortoce_hover_font_color, $value);

                            if ($points > 0) {

                                if ($parameter == "n") {
                                    $points = "-" . $points;
                                } else {
                                    $points = $points;
                                }

                                $value = $this->arp_generate_color_tone($value, $points);
                            }
                            $str .= $property . ':' . $value . ' !important;';
                        }
                        $str .= "}";
                        $skinarr[] = $str;
                    }
                }

                if (!empty($color_skins)) {

                    $template_skin = $general_option['template_setting']['skin'];
                    $skinarrn = array();
                    foreach ($color_skins as $class_key => $skins) {

                        $str = '';
                        $point = 0;
                        $class_key = explode('_^_', $class_key);
                        $class_name = $class_key[0];
                        $hover = $class_key[1];

                        if ($hover == 0) {
                            $element_hover = ":hover";
                            $parent_hover = "";
                        } else {
                            $element_hover = "";
                            $parent_hover = ":hover";
                        }

                        foreach ($skins as $property => $skin) {

                            $str .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_$table_id .ArpPricingTableColumnWrapper.arp_style_$start.no_animation:not(.no_transition):not(.maincaptioncolumn)$parent_hover $class_name";
                            $str .= $element_hover;
                            $str .= ",#ArpTemplate_main.arplite_front_main_container .arplitetemplate_$table_id .ArpPricingTableColumnWrapper.arp_style_$start.no_animation:not(.no_transition):not(.maincaptioncolumn).column_highlight  $class_name,";

                            $str .= ".arplitetemplate_$table_id .ArpPricingTableColumnWrapper.arp_style_$start.no_animation:not(.no_transition):not(.maincaptioncolumn)$parent_hover $class_name";
                            $str .= $element_hover;
                            $str .= ",.arplitetemplate_$table_id .ArpPricingTableColumnWrapper.arp_style_$start.no_animation:not(.no_transition):not(.maincaptioncolumn).column_highlight  $class_name";
                            $str .="{";
                            $value = $skin[$template_skin];

                            if ($template_skin == 'custom_skin') {
                                $value = str_replace('{arp_column_background_color}', $general_option['custom_skin_colors']['arp_column_bg_hover_color'], $value);
                                $value = str_replace('{arp_footer_column_background_color}', $general_option['custom_skin_colors']['arp_column_bg_hover_color'], $value);
                                $value = str_replace('{arp_header_background_color}', $general_option['custom_skin_colors']['arp_header_bg_custom_color'], $value);
                                $value = str_replace('{arp_button_background_color}', $general_option['custom_skin_colors']['arp_button_bg_hover_color'], $value);
                            } else {
                                $value = str_replace('{arp_header_background_color}', $column['header_background_color'], $value);
                                $value = $value;
                            }

                            if (preg_match('/____/', $value)) {
                                $value__ = explode('____', $value);
                                if ($template_skin == 'custom_skin') {
                                    $value = $value__[1];
                                } else {
                                    $value = $value__[0];
                                }
                            } else {
                                $value = $value;
                            }

                            preg_match_all('/<==>/', $value, $matches);

                            if (!empty($matches[0])) {
                                $value_ = explode('<==>', $value);
                            } else {
                                $value_ = $value;
                            }

                            if (is_array($value_) and ! empty($value_)) {
                                $value = $value_[0];

                                $parameter = $value_[1];
                                $point = $value_[2];
                            } else {
                                $value = $value_;
                            }

                            if ($point > 0) {
                                if ($parameter == "n") {
                                    $points = "-" . $point;
                                } else {
                                    $points = $point;
                                }

                                $value = $this->arp_generate_color_tone($value, $points);
                            } else {
                                $value = $value;
                            }

                            $str .= $property . ":" . $value . " !important;";
                            $str .= "}";
                            $skinarrn[] = $str;
                        }
                        $returnstring .= $str;
                    }
                }
                $start++;
            }
        }

        if (is_array($skinarr) && !empty($skinarr)) {
            foreach ($skinarr as $css) {
                $returnstring .= $css;
            }
        }

        $min_row_height = isset( $general_column_settings['min_row_height'] ) ? $general_column_settings['min_row_height'] : 0;

        if( 0 < $min_row_height ){
            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper ul.arppricingtablebodyoptions li{";
                $returnstring .= 'min-height:' . $min_row_height . 'px;';
            $returnstring .= "}";
        }

        $tablet_responsive_size = get_option('arplite_tablet_responsive_size');
        $tablet_responsive_size += 1;

        $returnstring .= "@media ( min-width:" . $tablet_responsive_size . "px){";
        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper,";
        $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper{";
        $returnstring .= "width:" . $general_column_settings['all_column_width'] . "px;";
        $returnstring .= "}";
        $returnstring .= "}";

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper,";
        $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper{";
        $returnstring .= "width:" . $general_column_settings['all_column_width'] . "px;";
        $returnstring .= "}";

        $hide_section_min_height_array = $arpricelite_default_settings->arprice_min_height_with_section_hide();
        $hide_section_min_height_array = isset($hide_section_min_height_array[$reference_template]) ? $hide_section_min_height_array[$reference_template] : '';

        if (isset($hide_section_min_height_array)) {
            if (isset($general_column_settings['hide_header_global']) && $general_column_settings['hide_header_global'] == '1') {
                if (is_array($hide_section_min_height_array) && is_array($hide_section_min_height_array['arp_header'])) {
                    foreach ($hide_section_min_height_array['arp_header'] as $hide_class) {
                        if ($hide_class != '') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . "{";
                            $returnstring .= "min-height:0px !important;";
                            $returnstring .= "}";
                        }
                    }
                } else {
                    if (is_array($hide_section_min_height_array) && $hide_section_min_height_array['arp_header'] != '') {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_header'] . ",";
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_header'] . "{";
                        $returnstring .= "min-height:0px !important;";
                        $returnstring .= "}";
                    }
                }
            }
            if (isset($general_column_settings['hide_header_shortcode_global']) && $general_column_settings['hide_header_shortcode_global'] == '1') {

                if (isset($hide_section_min_height_array['arp_header_shortcode']) && is_array($hide_section_min_height_array) && is_array($hide_section_min_height_array['arp_header_shortcode'])) {
                    foreach ($hide_section_min_height_array['arp_header_shortcode'] as $hide_class) {
                        if ($hide_class != '') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . "{";
                            $returnstring .= "min-height:0px !important;";
                            $returnstring .= "}";
                        }
                    }
                } else {
                    if (isset($hide_section_min_height_array['arp_header_shortcode']) && is_array($hide_section_min_height_array) && $hide_section_min_height_array['arp_header_shortcode'] != '') {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_header_shortcode'] . ",";
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_header_shortcode'] . "{";
                        $returnstring .= "min-height:0px !important;";
                        $returnstring .= "}";
                    }
                }
            }
            if (isset($general_column_settings['hide_feature_global']) && $general_column_settings['hide_feature_global'] == '1') {
                if (isset($hide_section_min_height_array['arp_feature']) && is_array($hide_section_min_height_array['arp_feature'])) {
                    foreach ($hide_section_min_height_array['arp_feature'] as $hide_class) {
                        if ($hide_class != '') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . "{";
                            $returnstring .= "min-height:0px !important;";
                            $returnstring .= "}";
                        }
                    }
                } else {
                    if (isset($hide_section_min_height_array['arp_feature']) && $hide_section_min_height_array['arp_feature'] != '') {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_feature'] . ",";
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_feature'] . "{";
                        $returnstring .= "min-height:0px !important;";
                        $returnstring .= "}";
                    }
                }
            }
            if (isset($general_column_settings['hide_price_global']) && $general_column_settings['hide_price_global'] == '1') {
                if (isset($hide_section_min_height_array['arp_price']) && is_array($hide_section_min_height_array['arp_price'])) {
                    foreach ($hide_section_min_height_array['arp_price'] as $hide_class) {
                        if ($hide_class != '') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . "{";
                            $returnstring .= "min-height:0px !important;";
                            $returnstring .= "}";
                        }
                    }
                } else {
                    if (isset($hide_section_min_height_array['arp_price']) && $hide_section_min_height_array['arp_price'] != '') {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_price'] . ",";
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_price'] . "{";
                        $returnstring .= "min-height:0px !important;";
                        $returnstring .= "}";
                    }
                }
            }
            if (isset($general_column_settings['hide_description_global']) && $general_column_settings['hide_description_global'] == '1') {
                if (isset($hide_section_min_height_array['arp_description']) && is_array($hide_section_min_height_array['arp_description'])) {
                    foreach ($hide_section_min_height_array['arp_description'] as $hide_class) {
                        if ($hide_class != '') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . "{";
                            $returnstring .= "min-height:0px !important;";
                            $returnstring .= "}";
                        }
                    }
                } else {
                    if (isset($hide_section_min_height_array['arp_description']) && $hide_section_min_height_array['arp_description'] != '') {
                        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_description'] . ",";
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_description'] . "{";
                        $returnstring .= "min-height:0px !important;";
                        $returnstring .= "}";
                    }
                }
            }
            if (isset($general_column_settings['hide_footer_global']) && $general_column_settings['hide_footer_global'] == '1') {
                if (isset($hide_section_min_height_array['arp_footer']) && is_array($hide_section_min_height_array['arp_footer'])) {
                    foreach ($hide_section_min_height_array['arp_footer'] as $hide_class) {
                        if ($hide_class != '') {
                            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . ",";
                            $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_class . "{";
                            $returnstring .= "min-height:0px !important;";
                            $returnstring .= "}";
                        }
                    }
                } else {
                    if (isset($hide_section_min_height_array['arp_footer']) && $hide_section_min_height_array['arp_footer'] != '') {
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_footer'] . ",";
                        $returnstring .= ".arplitetemplate_" . $table_id . ":not(.arp_admin_template_editor) " . $hide_section_min_height_array['arp_footer'] . "{";
                        $returnstring .= "min-height:0px !important;";
                        $returnstring .= "}";
                    }
                }
            }
        }

        if (isset($arplite_mainoptionsarr['general_options']['template_options']['features'][$reference_template]['button_border_customization']) && $arplite_mainoptionsarr['general_options']['template_options']['features'][$reference_template]['button_border_customization'] == 1) {
            if (isset($general_column_settings['global_button_border_color']) && $general_column_settings['global_button_border_color'] != '') {
                $general_column_settings['global_button_border_color'] = $general_column_settings['global_button_border_color'];
            } else {
                $general_column_settings['global_button_border_color'] = '#c9c9c9ff';
            }

            if (isset($general_column_settings['global_button_border_width']) && $general_column_settings['global_button_border_width'] != '') {
                $general_column_settings['global_button_border_width'] = $general_column_settings['global_button_border_width'];
            } else {
                $general_column_settings['global_button_border_width'] = 0;
            }

            if (isset($general_column_settings['global_button_border_type']) && $general_column_settings['global_button_border_type'] != '') {
                $general_column_settings['global_button_border_type'] = $general_column_settings['global_button_border_type'];
            } else {
                $general_column_settings['global_button_border_type'] = 'solid';
            }

            if (isset($general_column_settings['global_button_border_radius_top_left']) && $general_column_settings['global_button_border_radius_top_left'] != '') {
                $general_column_settings['global_button_border_radius_top_left'] = $general_column_settings['global_button_border_radius_top_left'];
            } else {
                $general_column_settings['global_button_border_radius_top_left'] = 0;
            }

            if (isset($general_column_settings['global_button_border_radius_top_right']) && $general_column_settings['global_button_border_radius_top_right'] != '') {
                $general_column_settings['global_button_border_radius_top_right'] = $general_column_settings['global_button_border_radius_top_right'];
            } else {
                $general_column_settings['global_button_border_radius_top_right'] = 0;
            }
            if (isset($general_column_settings['global_button_border_radius_bottom_left']) && $general_column_settings['global_button_border_radius_bottom_left'] != '') {
                $general_column_settings['global_button_border_radius_bottom_left'] = $general_column_settings['global_button_border_radius_bottom_left'];
            } else {
                $general_column_settings['global_button_border_radius_bottom_left'] = 0;
            }

            if (isset($general_column_settings['global_button_border_radius_bottom_right']) && $general_column_settings['global_button_border_radius_bottom_right'] != '') {
                $general_column_settings['global_button_border_radius_bottom_right'] = $general_column_settings['global_button_border_radius_bottom_right'];
            } else {
                $general_column_settings['global_button_border_radius_bottom_right'] = '0';
            }


            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .bestPlanButton,";
            $returnstring .= ".arplitetemplate_" . $table_id . " .bestPlanButton{";
            $returnstring .= 'border : ' . $general_column_settings['global_button_border_width'] . 'px ' . $general_column_settings['global_button_border_type'] . ' ' . $general_column_settings['global_button_border_color'] . ';';
            $returnstring .= 'border-radius :' . $general_column_settings['global_button_border_radius_top_left'] . 'px ' . $general_column_settings['global_button_border_radius_top_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_left'] . 'px;';
            $returnstring .= '-webkit-border-radius :' . $general_column_settings['global_button_border_radius_top_left'] . 'px ' . $general_column_settings['global_button_border_radius_top_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_left'] . 'px;';
            $returnstring .= '-moz-border-radius :' . $general_column_settings['global_button_border_radius_top_left'] . 'px ' . $general_column_settings['global_button_border_radius_top_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_left'] . 'px;';
            $returnstring .= '-o-border-radius :' . $general_column_settings['global_button_border_radius_top_left'] . 'px ' . $general_column_settings['global_button_border_radius_top_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_right'] . 'px ' . $general_column_settings['global_button_border_radius_bottom_left'] . 'px;';
            $returnstring .= "}";
        }


        $tol_bottom_border_style = " border-bottom-style:";
        $tol_bottom_border_width = " border-bottom-width:";
        $tol_bottom_border_color = " border-bottom-color:";

        $general_column_settings['arp_row_border_type'] = isset($general_column_settings['arp_row_border_type']) ? $general_column_settings['arp_row_border_type'] : '';
        $general_column_settings['arp_row_border_size'] = isset($general_column_settings['arp_row_border_size']) ? $general_column_settings['arp_row_border_size'] : '';
        $general_column_settings['arp_row_border_color'] = isset($general_column_settings['arp_row_border_color']) ? $general_column_settings['arp_row_border_color'] : '';

        $general_column_settings['arp_caption_row_border_style'] = isset($general_column_settings['arp_caption_row_border_style']) ? $general_column_settings['arp_caption_row_border_style'] : '';
        $general_column_settings['arp_caption_row_border_size'] = isset($general_column_settings['arp_caption_row_border_size']) ? $general_column_settings['arp_caption_row_border_size'] : '';
        $general_column_settings['arp_caption_row_border_color'] = isset($general_column_settings['arp_caption_row_border_color']) ? $general_column_settings['arp_caption_row_border_color'] : '';

        if (isset($template_feature['button_position']) && $template_feature['button_position'] != 'default') {
            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_$table_id:not(.arp_admin_template_editor) .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li:not(:nth-last-child(-n+2)),#ArpTemplate_main.arplite_front_main_container .arplite_price_table_$table_id:not(.arp_admin_template_editor) .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li:last-child,#ArpTemplate_main.arplite_front_main_container .arplite_price_table_$table_id.arp_admin_template_editor .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li,";
            $returnstring .= " .arplite_price_table_$table_id:not(.arp_admin_template_editor) .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li:not(:nth-last-child(-n+2)),.arplite_price_table_$table_id:not(.arp_admin_template_editor) .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li:last-child,.arplite_price_table_$table_id.arp_admin_template_editor .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li";
        } else {
            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_$table_id .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li,";
            $returnstring .= " .arplite_price_table_$table_id .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .planContainer .arppricingtablebodycontent ul li";
        }
        $returnstring .= "{";
        $returnstring .= "$tol_bottom_border_style " . $general_column_settings['arp_row_border_type'] . ";";
        $returnstring .= "$tol_bottom_border_width " . $general_column_settings['arp_row_border_size'] . "px;";
        $returnstring .= "$tol_bottom_border_color " . $general_column_settings['arp_row_border_color'] . ";";
        $returnstring .= " }";

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_$table_id .ArpPricingTableColumnWrapper.maincaptioncolumn .planContainer .arppricingtablebodycontent ul li,";
        $returnstring .= " .arplite_price_table_$table_id .ArpPricingTableColumnWrapper.maincaptioncolumn .planContainer .arppricingtablebodycontent ul li";
        $returnstring .= "{";
        $returnstring .= "$tol_bottom_border_style " . $general_column_settings['arp_row_border_type'] . ";";
        $returnstring .= "$tol_bottom_border_width " . $general_column_settings['arp_row_border_size'] . "px;";
        $returnstring .= "$tol_bottom_border_color " . $general_column_settings['arp_caption_row_border_color'] . ";";
        $returnstring .= " }";


        $arp_row_level_border_remove_from_last_child = $arpricelite_default_settings->arp_row_level_border_remove_from_last_child();
        if (in_array($reference_template, $arp_row_level_border_remove_from_last_child)) {

            $returnstring .= "#ArpTemplate_main.arplite_front_main_container  .arplite_price_table_$table_id .ArpPricingTableColumnWrapper .planContainer .arppricingtablebodycontent ul li:last-child,";
            $returnstring .= " .arplite_price_table_$table_id .ArpPricingTableColumnWrapper .planContainer .arppricingtablebodycontent ul li:last-child";
            $returnstring .= "{border-bottom:none !important;}";
            if ($reference_template == 'arplitetemplate_8' || $reference_template == 'arplitetemplate_11') {

                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_$table_id:not(.arp_admin_template_editor) .ArpPricingTableColumnWrapper .planContainer .arppricingtablebodycontent ul li:nth-last-child(-n+2)";
                $returnstring .= " .arplite_price_table_$table_id:not(.arp_admin_template_editor) .ArpPricingTableColumnWrapper .planContainer .arppricingtablebodycontent ul li:nth-last-child(-n+2)";
                $returnstring .= "{border-bottom:none;}";
            }
        }

        $arp_border_css_class = $arpricelite_default_settings->arp_column_border_array();
        $arp_border_css_class = $arp_border_css_class[$reference_template];

        $border_size = isset($general_column_settings['arp_column_border_size']) ? $general_column_settings['arp_column_border_size'] : '0';
        $border_type = isset($general_column_settings['arp_column_border_type']) ? $general_column_settings['arp_column_border_type'] : 'solid';
        $all_size_border = isset($general_column_settings['arp_column_border_all']) ? $general_column_settings['arp_column_border_all'] : '';
        $left_size_border = isset($general_column_settings['arp_column_border_left']) ? $general_column_settings['arp_column_border_left'] : '';
        $right_size_border = isset($general_column_settings['arp_column_border_right']) ? $general_column_settings['arp_column_border_right'] : '';
        $top_size_border = isset($general_column_settings['arp_column_border_top']) ? $general_column_settings['arp_column_border_top'] : '';
        $bottom_size_border = isset($general_column_settings['arp_column_border_bottom']) ? $general_column_settings['arp_column_border_bottom'] : '';

        $border_color = isset($general_column_settings['arp_column_border_color']) ? $general_column_settings['arp_column_border_color'] : '#c9c9c9ff';



        $caption_border_color = isset($general_column_settings['arp_caption_border_color']) ? $general_column_settings['arp_caption_border_color'] : '#c9c9c9ff';
        $caption_border_size = isset($general_column_settings['arp_caption_border_size']) ? $general_column_settings['arp_caption_border_size'] : '0';
        $arp_caption_border_style = isset($general_column_settings['arp_caption_border_style']) ? $general_column_settings['arp_caption_border_style'] : 'solid';
        
        

        $caption_left_size_border = isset($general_column_settings['arp_caption_border_left']) ? $general_column_settings['arp_caption_border_left'] : '';
        $caption_right_size_border = isset($general_column_settings['arp_caption_border_right']) ? $general_column_settings['arp_caption_border_right'] : '';
        $caption_top_size_border = isset($general_column_settings['arp_caption_border_top']) ? $general_column_settings['arp_caption_border_top'] : '';
        $caption_bottom_size_border = isset($general_column_settings['arp_caption_border_bottom']) ? $general_column_settings['arp_caption_border_bottom'] : '';

        $caption_all_size_border = isset($general_column_settings['arp_caption_border_all']) ? $general_column_settings['arp_caption_border_all'] : '';

        if ($border_size != '0' && $all_size_border != '' && isset($arp_border_css_class['all'])) {
            $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['all'] . ",";
            $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['all'] . "{";
            $returnstring .= 'border :' . $border_size . 'px ' . $border_type . ' ' . $border_color . ';';
            $returnstring .= "}";
        } else {
            if ($border_size != '0' && $left_size_border != '' && $left_size_border != '0' && isset($arp_border_css_class['left'])) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['left'] . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['left'] . "{";
                $returnstring .= 'border-left :' . $border_size . 'px ' . $border_type . ' ' . $border_color . ';';
                $returnstring .= "}";
            }
            if ($border_size != '0' && $right_size_border != '' && $right_size_border != '0' && isset($arp_border_css_class['right'])) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['right'] . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['right'] . "{";
                $returnstring .= 'border-right :' . $border_size . 'px ' . $border_type . ' ' . $border_color . ';
            ';
                $returnstring .= "}";
            }
            if ($border_size != '0' && $top_size_border != '' && $top_size_border != '0' && isset($arp_border_css_class['top'])) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['top'] . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['top'] . "{";
                $returnstring .= 'border-top :' . $border_size . 'px ' . $border_type . ' ' . $border_color . ';
            ';
                $returnstring .= "}";
            }
            if ($border_size != '0' && $bottom_size_border != '' && $bottom_size_border != '0' && isset($arp_border_css_class['bottom'])) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['bottom'] . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) " . $arp_border_css_class['bottom'] . "{";
                $returnstring .= 'border-bottom :' . $border_size . 'px ' . $border_type . ' ' . $border_color . ';
            ';
                $returnstring .= "}";
            }
        }

        if ($caption_border_size != '0' && $caption_left_size_border != '' && $caption_left_size_border != '0' && isset($arp_border_css_class['left']) || $caption_border_size != '0' && $caption_all_size_border != '' && isset($arp_border_css_class['all'])) {

            $cap_border_left = explode(",", $arp_border_css_class['caption_border_all']['left']);
            foreach ($cap_border_left as $value_left) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_left . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_left . "{";
                $returnstring .= 'border-left :' . $caption_border_size . 'px ' . $arp_caption_border_style . ' ' . $caption_border_color . ';
            ';
                $returnstring .= "}";
            }
        }

        if ($caption_border_size != '0' && $caption_right_size_border != '' && $caption_right_size_border != '0' && isset($arp_border_css_class['right']) || $caption_border_size != '0' && $caption_all_size_border != '' && isset($arp_border_css_class['all'])) {

            $cap_border_right = explode(",", $arp_border_css_class['caption_border_all']['right']);
            foreach ($cap_border_right as $value_right) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_right . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_right . "{";
                $returnstring .= 'border-right :' . $caption_border_size . 'px ' . $arp_caption_border_style . ' ' . $caption_border_color . ';
            ';
                $returnstring .= "}";
            }
        }

        if ($caption_border_size != '0' && $caption_top_size_border != '' && $caption_top_size_border != '0' && isset($arp_border_css_class['top']) || $caption_border_size != '0' && $caption_all_size_border != '' && isset($arp_border_css_class['all'])) {

            $cap_border_top = explode(",", $arp_border_css_class['caption_border_all']['top']);
            foreach ($cap_border_top as $value_top) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_top . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_top . "{";
                $returnstring .= 'border-top :' . $caption_border_size . 'px ' . $arp_caption_border_style . ' ' . $caption_border_color . ';
            ';
                $returnstring .= "}";
            }
        }

        if ($caption_border_size != '0' && $caption_bottom_size_border != '' && $caption_bottom_size_border != '0' && isset($arp_border_css_class['bottom']) || $caption_border_size != '0' && $caption_all_size_border != '' && isset($arp_border_css_class['all'])) {

            $cap_border_bottom = explode(",", $arp_border_css_class['caption_border_all']['bottom']);
            foreach ($cap_border_bottom as $value_bottom) {
                $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_bottom . ",";
                $returnstring .= ".arplitetemplate_" . $table_id . " .ArpPricingTableColumnWrapper.maincaptioncolumn " . $value_bottom . "{";
                $returnstring .= 'border-bottom :' . $caption_border_size . 'px ' . $arp_caption_border_style . ' ' . $caption_border_color . ';
            ';
                $returnstring .= "}";
            }
        }

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .bestPlanTitle,";
        $returnstring .= " .arplitetemplate_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .bestPlanTitle";

        $returnstring .= " {font-family: '" . stripslashes($general_column_settings['header_font_family_global']) . "';font-size: " . $general_column_settings['header_font_size_global'] . "px; ";
        if ($general_column_settings['arp_header_text_bold_global'] != '') {
            $returnstring .= " font-weight: " . $general_column_settings['arp_header_text_bold_global'] . ";";
        }
        if ($general_column_settings['arp_header_text_italic_global'] != '') {
            $returnstring .= " font-style: " . $general_column_settings['arp_header_text_italic_global'] . ";";
        }
        if ($general_column_settings['arp_header_text_decoration_global'] != '') {
            $returnstring .= " text-decoration: " . $general_column_settings['arp_header_text_decoration_global'] . ";";
        }
        $returnstring .="}";

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .arp_price_wrapper,";
        $returnstring .= " .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .arp_price_wrapper{";


        $returnstring .= "font-family:'" . stripslashes_deep($general_column_settings['price_font_family_global']) . "';
		font-size:" . $general_column_settings['price_font_size_global'] . "px;";

        if (isset($general_column_settings['arp_price_text_bold_global']) && $general_column_settings['arp_price_text_bold_global'] != '') {
            $returnstring .= " font-weight: " . $general_column_settings['arp_price_text_bold_global'] . ";";
        }

        if (isset($general_column_settings['price_label_style_italic']) && $general_column_settings['price_label_style_italic'] != '') {
            $returnstring .= " font-style: " . $general_column_settings['price_label_style_italic'] . ";";
        }

        if (isset($general_column_settings['arp_price_text_decoration_global']) && $general_column_settings['arp_price_text_decoration_global'] != '') {
            $returnstring .= " text-decoration: " . $general_column_settings['arp_price_text_decoration_global'] . ";";
        }


        $returnstring .= "}";


        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn):not(.no_transition) .arp_opt_options li *,#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition:not(.maincaptioncolumn) .arp_opt_options li,";
        $returnstring .= " .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn):not(.no_transition) .arp_opt_options li *, .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper.no_transition:not(.maincaptioncolumn) .arp_opt_options li";

        $returnstring .= "{";
        $returnstring .= "font-family:'" . stripslashes_deep($general_column_settings['body_font_family_global']) . "';";
        $returnstring .= "font-size:" . $general_column_settings['body_font_size_global'] . "px;";

        if ($general_column_settings['arp_body_text_bold_global'] != ''){
            $returnstring .= " font-weight: " . $general_column_settings['arp_body_text_bold_global'] . ";";
        }

        if ($general_column_settings['arp_body_text_italic_global'] != ''){
            $returnstring .= " font-style: " . $general_column_settings['arp_body_text_italic_global'] . ";";
        }

        if ($general_column_settings['arp_body_text_decoration_global'] != ''){
            $returnstring .= " text-decoration: " . $general_column_settings['arp_body_text_decoration_global'] . " ;";
        }

        $returnstring .= "}";

        $returnstring .= '#ArpTemplate_main.arplite_front_main_container .arplite_price_table_' . $table_id . ' #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .arp_footer_content,';
        $returnstring .= '.arplite_price_table_' . $table_id . ' #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .arp_footer_content{';

        $returnstring .= 'font-family: ' . $general_column_settings['footer_font_family_global'] . ';';
        $returnstring .= 'font-size:' . $general_column_settings['footer_font_size_global'] . 'px;';
        if ($general_column_settings['arp_footer_text_bold_global'] == 'bold') {
            $returnstring .= 'font-weight: bold;';
        }
        if ($general_column_settings['arp_footer_text_italic_global'] == 'italic') {
            $returnstring .= 'font-style: italic;';
        }
        if ($general_column_settings['arp_footer_text_decoration_global'] == 'underline') {
            $returnstring .= 'text-decoration: underline;';
        } else if ($general_column_settings['arp_footer_text_decoration_global'] == 'line-through') {
            $returnstring .= 'text-decoration: line-through;';
        }

        $returnstring .= '}';

        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .bestPlanButton,#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .bestPlanButton .bestPlanButton_text,";
        $returnstring .= " .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .bestPlanButton, .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .bestPlanButton .bestPlanButton_text";

        $returnstring .= "{";

        $returnstring .= "font-family:'" . stripslashes_deep($general_column_settings['button_font_family_global']) . "';";
        $returnstring .= "font-size:" . $general_column_settings['button_font_size_global'] . "px;";

        if (isset($general_column_settings['arp_button_text_bold_global']) && $general_column_settings['arp_button_text_bold_global'] != ''){
            $returnstring .= " font-weight: " . $general_column_settings['arp_button_text_bold_global'] . ";";
        }

        if (isset($general_column_settings['arp_button_text_italic_global']) && $general_column_settings['arp_button_text_italic_global'] != ''){
            $returnstring .= " font-style: " . $general_column_settings['arp_button_text_italic_global'] . ";";
        }

        if (isset($general_column_settings['arp_button_text_decoration_global']) && $general_column_settings['arp_button_text_decoration_global'] != ''){
            $returnstring .= " text-decoration: " . $general_column_settings['arp_button_text_decoration_global'] . ";";
        }

        $returnstring .= "}";


        $returnstring .= "#ArpTemplate_main.arplite_front_main_container .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .column_description,";
        $returnstring .= " .arplite_price_table_" . $table_id . " #ArpPricingTableColumns .ArpPricingTableColumnWrapper:not(.maincaptioncolumn) .column_description{";


        if ($general_column_settings['arp_description_text_bold_global'] != ''){
            $returnstring .= " font-weight: " . $general_column_settings['arp_description_text_bold_global'] . ";";
        }

        if ($general_column_settings['arp_description_text_italic_global'] != ''){
            $returnstring .= " font-style: " . $general_column_settings['arp_description_text_italic_global'] . ";";
        }

        if ($general_column_settings['arp_description_text_decoration_global'] != ''){
            $returnstring .= " text-decoration: " . $general_column_settings['arp_description_text_decoration_global'] . ";";
        }


        $returnstring .= "font-family:" . stripslashes_deep($general_column_settings['description_font_family_global']) . ";";
        $returnstring .= "font-size:" . $general_column_settings['description_font_size_global'] . 'px;';


        $returnstring .= "}";




        return $returnstring;
    }

    function arp_get_video_image($add_shortcode) {
        $add_shortcode_text = str_replace('[', '', $add_shortcode);
        $add_shortcode_text = str_replace(']', '', $add_shortcode_text);

        $as_shortcode = shortcode_parse_atts($add_shortcode_text);

        return do_shortcode($add_shortcode);
    }

    function get_table_enqueue_data($tablearr = array()) {
        
        if (!$tablearr){
            return;
        }

        global $wpdb;

        $tableresutls = array();

        foreach ($tablearr as $table_id) {
            $tabledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = %d and is_template = 0", $table_id));
            $tableoption = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d", $table_id));

            if ($tabledata && $tableoption) {
                $general_options = maybe_unserialize($tabledata->general_options);
                $table_options = maybe_unserialize($tableoption->table_options);

                $googlemap = 0;
                if ($table_options['columns']) {
                    foreach ($table_options['columns'] as $columns) {
                        $html_content = isset($columns['arp_header_shortcode']) ? $columns['arp_header_shortcode'] : "";
                        if (preg_match('/arp_googlemap/', $html_content)){
                            $googlemap = 1;
                        }
                    }
                }

                $tableresutls[$tabledata->ID] = array(
                    'template' => $general_options['template_setting']['template'],
                    'skin' => $general_options['template_setting']['skin'],
                    'template_name' => $tabledata->template_name,
                    'is_template' => $tabledata->is_template,
                    'googlemap' => $googlemap,
                );
            }
        }

        return $tableresutls;
    }
    function arplite_widget_text_filter($content) {
        $regex = '/\[\s*ARPLite\s+.*\]/';
        return preg_replace_callback($regex, array($this, 'arplite_widget_text_filter_callback'), $content);
    }

    function arplite_widget_text_filter_callback($matches) {

        global $arpricelite_form;

        if ($matches[0]) {
            $parts = explode("id=", $matches[0]);
            $partsnew = explode(" ", $parts[1]);
            $tableid = $partsnew[0];
            $tableid = trim($tableid);

            if ($tableid) {
                $newvalues_enqueue = $arpricelite_form->get_table_enqueue_data(array($tableid));

                if (is_array($newvalues_enqueue) && count($newvalues_enqueue) > 0) {
                    $to_google_map = 0;
                    $templates = array();

                    foreach ($newvalues_enqueue as $newqnqueue) {
                        if ($newqnqueue['googlemap']){
                            $to_google_map = 1;
                        }

                        $templates[] = $newqnqueue['template'];
                    }

                    $templates = array_unique($templates);

                    if ($templates) {
                        wp_enqueue_script('arprice_js');

                        wp_enqueue_style('arprice_front_css');
                        wp_enqueue_style('arp_fontawesome_css');
                        wp_enqueue_style('arprice_font_css_front');

                        foreach ($templates as $template) {
                            foreach ($newvalues_enqueue as $template_id => $newqnqueue) {
                                if (isset($newqnqueue['is_template']) && !empty($newqnqueue['is_template'])) {
                                    wp_register_style('arplitetemplate_' . $newqnqueue['template_name'] . '_css', ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $newqnqueue['template_name'] . '.css', array(), null);
                                    wp_enqueue_style('arplitetemplate_' . $newqnqueue['template_name'] . '_css');
                                } else {

                                    wp_register_style('arplitetemplate_' . $template_id . '_css', ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/arplitetemplate_' . $template_id . '.css', array(), null);
                                    wp_enqueue_style('arplitetemplate_' . $template_id . '_css');
                                }
                            }
                        }
                    }
                }
            }
        }

        return do_shortcode($matches[0]);
    }

    function hex2rgb($colour) {

        if (isset($colour[0]) && $colour[0] == '#') {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6) {
            list( $r, $g, $b ) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        } elseif (strlen($colour) == 3) {
            list( $r, $g, $b ) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }

    function font_settings($selected_fonts = '') {

        global $arpricelite_fonts;

        $default_fonts = $arpricelite_fonts->get_default_fonts();

        $google_fonts = $arpricelite_fonts->google_fonts_list();

        $str = '';

        $str .= '<optgroup label="' . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . '">';

        foreach ($default_fonts as $font) {
            $str .= '<option style="font-family:' . $font . '" id="normal" ' . selected($font, $selected_fonts, false) . ' value="' . esc_html( $font ) . '">' . esc_html( $font ) . '</option>';
        }

        $str .= '</optgroup>';

        $str .= '<optgroup label="' . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . '">';

        foreach ($google_fonts as $font) {
            $str .= '<option style="font-family:' . $font . '" id="google" ' . selected($font, $selected_fonts, false) . ' value="' . esc_html( $font ) . '">' . esc_html( $font ) . '</div>';
        }

        $str.= '</optgroup>';

        return $str;
    }

    function font_size($selected_size = '') {
        $str = '';
        for ($s = 8; $s <= 20; $s++) {
            $size_arr[] = $s;
        }
        for ($st = 22; $st <= 70; $st+=2) {
            $size_arr[] = $st;
        }
        foreach ($size_arr as $size) {
            $str .= '<option ' . selected($size, $selected_size, false) . ' value="' . esc_html( $size ) . '">' . esc_html( $size ) . '</option>';
        }
        return $str;
    }

    function font_style($selected_style = '') {
        $str = '';
        $style_arr = array('normal', 'italic', 'bold');
        foreach ($style_arr as $style) {
            $str .= '<option ' . selected($style, $selected_style, false) . ' value="' . esc_html( $style ) . '">' . esc_html( $style ) . '</option>';
        }
        return $str;
    }

    function font_style_new() {
        $str = '';
        $style_arr = array('normal' => esc_html__('Normal', 'arprice-responsive-pricing-table'), 'italic' => esc_html__('Italic', 'arprice-responsive-pricing-table'), 'bold' => esc_html__('Bold', 'arprice-responsive-pricing-table'));
        foreach ($style_arr as $x => $style) {
            $str .= "<li data-value='" . esc_html( $x ) . "' data-label='" . $style . "'>" . $style . "</li>";
        }
        return $str;
    }

    function font_color_new($property_name = '', $data_column = '', $data_column_id = '', $id = '', $value = '', $main_class = '', $input_class = '') {
        $str = '';
        $pattern = "/(background|content_odd_color|content_even_color|content_odd_hover_color|content_even_hover_color)/";
        $restricted_class = '';
        preg_match($pattern, $id, $matches);
        if (is_array($matches) && !empty($matches)) {
            $restricted_class = 'arplite_restricted_view';
        } else {
            $restricted_class = '';
        }
        $restricted_class = '';
        $str .= '<div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box ' . $restricted_class . '" data-column="' . $data_column . '" id="' . $id . '_' . $data_column . '_wrapper" data-color="' . $value . '" data-jscolor="{hash:true,onInput:\'arp_update_color(this,' . $id . '_' . $data_column . '_wrapper)\',valueElement:\'#' . $id . '_' . $data_column . '\',required:false}" jscolor-required="false" jscolor-hash="true" jscolor-oninput="arp_update_color(this,' . $id . '_' . $data_column . '_wrapper)" jscolor-valueelement="' . $id . '_' . $data_column . '">';
        $str .= '</div>';
        $str .= '<input type="hidden" id="' . $id . '_' . $data_column . '" name="' . $property_name . '" value="' . esc_html( $value ) . '" class="  ' . $input_class . '"  />';

        return $str;
    }

    function font_color($property_name = '', $data_column = '', $data_column_id = '', $id = '', $value = '', $main_class = '', $input_class = '', $is_readonly = false) {
        $str = '';

        $readonly = $reaonly_cls = '';
        if ($is_readonly == true) {
            $readonly = "readonly='readonly'";
            $readonly_cls = 'arplite_restricted_view';
        } else {
            $readonly = "";
            $readonly_cls = "";
        }

        $str.='<div class="color_picker_font font_color_picker ' . $main_class . ' ' . $readonly_cls . ' " data-column="' . $data_column . '" id="' . $id . '_wrapper" data-color="' . $value . '">';
        if ($readonly_cls == "") {
            $str.='<input type="text" id="' . $id . '_' . $data_column . '" name="' . $property_name . '" value="' . esc_html( $value ) . '" class="general_color_box general_color_box_font_color jscolor ' . $input_class . ' ' . $readonly_cls . '" data-jscolor="{hash:true,onInput:\'arp_update_color(this,' . $id . '_' . $data_column . ')\',required:false}" jscolor-required="false" jscolor-hash="true" jscolor-onInput="arp_update_color(this,' . $id . '_' . $data_column . ')" ' . $readonly . ' />';
        } else if ($readonly_cls != "") {
            $str.='<input type="text" id="' . $id . '" name="' . $property_name . '" value="' . esc_html( $value ) . '" class="general_color_box general_color_box_font_color restricted_jscolor ' . $input_class . ' ' . $readonly_cls . '" ' . $readonly . ' />';
        }
        $str.='</div>';

        return $str;
    }

    function arp_save_template_image() {
        WP_Filesystem();
        global $wp_filesystem;

        $arp_image_data = isset($_POST['arp_image_data']) ? wp_kses_post( $_POST['arp_image_data'] ) : '';

        $template_id = isset($_POST['template_id']) ? intval( $_POST['template_id'] ) : '';



        if ($arp_image_data != '' && $template_id != '') {
            $arp_image_data = str_replace('data:image/png;base64,', '', $arp_image_data);
            $arp_image_data = str_replace(' ', '+', $arp_image_data);
            $data = base64_decode($arp_image_data);
            $file = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $template_id . '_full_legnth.png';
            $wp_filesystem->put_contents($file, $data, 0777);

            list($width, $height) = getimagesize($file);
            $newheight = 180;
            $newwidth = 400;

            $src_image = imagecreatefrompng($file);
            $tmp_image = imagecreatetruecolor($newwidth, $newheight);
            $bgColor = imagecolorallocate($tmp_image, 255, 255, 255);
            imagefill($tmp_image, 0, 0, $bgColor);
            imagecopyresampled($tmp_image, $src_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            $filename = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $template_id . '.png';
            imagepng($tmp_image, $filename);
            imagedestroy($tmp_image);

            $newheight_big = 238;
            $newwidth_big = 530;
            $tmp_image_big = imagecreatetruecolor($newwidth_big, $newheight_big);
            $bgColor_big = imagecolorallocate($tmp_image_big, 255, 255, 255);
            imagefill($tmp_image_big, 0, 0, $bgColor_big);
            imagecopyresampled($tmp_image_big, $src_image, 0, 0, 0, 0, $newwidth_big, $newheight_big, $width, $height);
            $filename_big = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $template_id . '_big.png';
            imagepng($tmp_image_big, $filename_big);
            imagedestroy($tmp_image_big);

            $newheight_large = 300;
            $newwidth_large = 668;
            $tmp_image_large = imagecreatetruecolor($newwidth_large, $newheight_large);
            $bgColor_large = imagecolorallocate($tmp_image_large, 255, 255, 255);
            imagefill($tmp_image_large, 0, 0, $bgColor_large);
            imagecopyresampled($tmp_image_large, $src_image, 0, 0, 0, 0, $newwidth_large, $newheight_large, $width, $height);
            $filename_large = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $template_id . '_large.png';
            imagepng($tmp_image_large, $filename_large);
            imagedestroy($tmp_image_large);

            unlink($file);
        }
        die();
    }

    function update_arp_tour_guide_value() {
        $return = '0';
        update_option('arpricelite_tour_guide_value', sanitize_text_field('no'));
        if ($_REQUEST['arp_tour_guide_value'] == 'arp_tour_guide_start_yes') {
            $return = '1';
        }

        echo $return;

        die();
    }

    function arp_generate_color_tone($hex, $steps) {

        $steps = max(-255, min(255, $steps));

        $hex = str_replace('#', '', $hex);
        if ($hex != '' && strlen($hex) < 6) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $color_parts = str_split($hex, 2);
        $return = '#';

        $acsteps = str_replace(array('+', '-'), array('', ''), $steps);

        if (strlen($acsteps) > 2)
            $lum = $steps / 1000;
        else
            $lum = $steps / 100;

        foreach ($color_parts as $color) {
            $color = hexdec($color);
            $color = round(max(0, min(255, $color + ($color * $lum))));
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
        }

        return $return;
    }

    function arp_create_alignment_div($id, $alignment, $name, $column, $level) {
        $tablestring = '';
        $tablestring .= "<div class='col_opt_row' id='" . $id . "'>";
        $tablestring .= "<div class='col_opt_title_div'>" . esc_html__('Text Alignment', 'arprice-responsive-pricing-table') . "</div>";
        $tablestring .= "<div class='col_opt_input_div'>";
        $left_selected = ($alignment == 'left') ? 'align_selected' : '';
        $center_selected = ($alignment == 'center') ? 'align_selected' : '';
        $right_selected = ($alignment == 'right') ? 'align_selected' : '';

        $tablestring .= "<div class='arp_alignment_btn align_left_btn " . $left_selected . "' data-align='left' id='align_left_btn' data-id='" . $column . "' data-level='" . $level . "'>";
        $tablestring .= "<i class='fas fa-align-left fa-flip-vertical'></i>";
        $tablestring .= "</div>";

        $tablestring .= "<div class='arp_alignment_btn align_center_btn " . $center_selected . "' data-align='center' id='align_center_btn' data-id='" . $column . "' data-level='" . $level . "'>";
        $tablestring .= "<i class='fas fa-align-center fa-flip-vertical'></i>";
        $tablestring .= "</div>";

        $tablestring .= "<div class='arp_alignment_btn align_right_btn " . $right_selected . "' data-align='right' id='align_right_btn' data-id='" . $column . "' data-level='" . $level . "'>";
        $tablestring .= "<i class='fas fa-align-right farpa-flip-vertical'></i>";
        $tablestring .= "</div>";

        $tablestring .= "<input type='hidden' id='$name' value='" . esc_html( $alignment ) . "' name='" . $name . "_" . $column . "'>";

        $tablestring .= "</div>";
        $tablestring .= "</div>";

        return $tablestring;
        die();
    }

    function arp_update_subscribe_date() {
        $time = time();
        update_option('arplite_popup_display', sanitize_text_field('no'));
        update_option('arplite_display_popup_date', $time);
        echo wp_json_encode(array('time' => $time, 'display' => 'yes'));
        die();
    }

    function arp_create_alignment_div_new($id, $alignment, $name, $column, $level) {
        $tablestring = '';

        $tablestring .= "<div class='col_opt_input_div' id='" . $id . "'>";
        $left_selected = ($alignment == 'left') ? 'align_selected' : '';
        $center_selected = ($alignment == 'center') ? 'align_selected' : '';
        $right_selected = ($alignment == 'right') ? 'align_selected' : '';

        $tablestring .= "<div class='arp_alignment_btn align_left_btn " . $left_selected . "' data-align='left' id='align_left_btn' data-id='" . $column . "' data-level='" . $level . "'>";
        $tablestring .= "<i class='fas fa-align-left fa-flip-vertical'></i>";
        $tablestring .= "</div>";

        $tablestring .= "<div class='arp_alignment_btn align_center_btn " . $center_selected . "' data-align='center' id='align_center_btn' data-id='" . $column . "' data-level='" . $level . "'>";
        $tablestring .= "<i class='fas fa-align-center fa-flip-vertical'></i>";
        $tablestring .= "</div>";

        $tablestring .= "<div class='arp_alignment_btn align_right_btn " . $right_selected . "' data-align='right' id='align_right_btn' data-id='" . $column . "' data-level='" . $level . "'>";
        $tablestring .= "<i class='fas fa-align-right fa-flip-vertical'></i>";
        $tablestring .= "</div>";

        $tablestring .= "<input type='hidden' id='$name' value='" . esc_html( $alignment ) . "' name='" . $name . "'>";

        $tablestring .= "</div>";

        return $tablestring;
        die();
    }

    function arplite_remove_preivew_opts(){
        global $arplite_pricingtable;
        $check_caps = $arplite_pricingtable->arplite_check_user_cap('arplite_add_udpate_pricingtables',true);

        if( $check_caps != 'success' ){
            $check_caps_msg = json_decode($check_caps,true);
            echo 'error~|~'.$check_caps_msg[0];
            die;
        }

        $opt_id = isset( $_POST['opt_id'] ) ? sanitize_text_field( $_POST['opt_id'] ) : '';
        
        if( $opt_id != '' ){
            delete_option($opt_id);
        }
        die;
    }

}
?>