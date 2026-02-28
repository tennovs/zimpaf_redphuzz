<?php
if (!function_exists('arp_get_pricing_table_string_editor')) {

    function arp_get_pricing_table_string_editor($table_id, $pricetable_name = "", $is_tbl_preview = 0, $general_option = '', $opts = '', $is_clone = '') {

        global $wpdb, $arpricelite_form, $arpricelite_fonts, $arpricelite_version, $arprice_font_awesome_icons, $arplite_pricingtable, $arpricelite_default_settings, $arpricelite_img_css_version;
        $template_section_array = $arpricelite_default_settings->arp_column_section_background_color();

        $id = $table_id;
        $name = $pricetable_name;

        if (is_ssl()) {
            $googlefontpreviewurl = "https://www.google.com/fonts/specimen/";
        } else {
            $googlefontpreviewurl = "http://www.google.com/fonts/specimen/";
        }

        global $arplite_tempbuttonsarr, $arplite_mainoptionsarr, $arpricelite_form, $arpricelite_fonts, $arpricelite_default_settings;

        $tablestring = "";
        $title_cls = "";
        $header_cls = "";

        $default_fonts_string = '';
        $google_fonts_string = '';
	
    	$default_fonts = $arpricelite_fonts->get_default_fonts();
    	$google_fonts = $arpricelite_fonts->google_fonts_list();
        
    	foreach ($default_fonts as $font) {
    	    $default_fonts_string .= "<li class='arp_selectbox_option' data-font-type='normal' data-value='" . esc_html( $font ) . "' data-label='" . esc_html( $font ) . "'>" . esc_html( $font ) . "</li>";
    	}
                
    	foreach ($google_fonts as $font) {
    	    $google_fonts_string .= "<li class='arp_selectbox_option' data-font-type='google' data-value='" . esc_html( $font ) . "' data-label='" . esc_html( $font ) . "'>" . esc_html( $font ) . "</li>";
    	}

        if ($is_tbl_preview && $is_tbl_preview == 1) {
            if (isset($_REQUEST['optid']) && $_REQUEST['optid'] != '') {
                $post_values = get_option($_REQUEST['optid']);
                $filtered_data = json_decode( $post_values, true );

                $arp_template_name = $filtered_data['table_opt']['table_name'];
                $general_option = maybe_unserialize( $filtered_data['table_opt']['general_options'] );
                $opts = maybe_unserialize( $filtered_data['table_col_opt'] );
                $id = $table_id = intval( $_REQUEST['tbl'] );
                $is_animated = $filtered_data['table_opt']['is_animated'];
                $is_template = $filtered_data['table_opt']['is_template'];
            }
        } else if ($is_tbl_preview && $is_tbl_preview == 3) {
            $opts = maybe_unserialize($opts);
            $general_option = maybe_unserialize($general_option);
        } else {
            $sql = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = %d AND status = %s ", $id, 'published'));
            $table_id = $sql->ID;
            $sql_opt = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d ", $table_id));
            $is_animated = $sql->is_animated;
            $opts = maybe_unserialize($sql_opt[0]->table_options);
            $general_option = maybe_unserialize($sql->general_options);
            $is_template = $sql->is_template;
            apply_filters('arplite_append_googlemap_js', $table_id);
        }

        $general_option_new = $arplite_pricingtable->arplite_reset_colorpicker( $general_option );

        $general_option = $general_option_new;

        $table_cols = array();
        $table_cols = $table_cols_new = $arplite_pricingtable->arplite_reset_colorpicker( $opts['columns'] );

        $arp_table_data = array();

        foreach( $table_cols as $col_key => $tab_col ){
            if( !isset($arp_table_data[$col_key]) ){
                $arp_table_data[$col_key] = array();
            }
            
            $rows = isset($tab_col['rows']) ? $tab_col['rows'] : array();

            if( !isset( $arp_table_data[$col_key]['rows'] ) ){
                $arp_table_data[$col_key]['rows'] = array();
            }

            foreach( $rows as $rkey => $rval ){
                $g = 0;
                
                $irKey = ( $g == 0 ) ? 'description' : 'description_'. $tab_name[2];
                $rvalKey = ( $g == 0 ) ? 'row_description' : 'row_description_'.$tab_name[2];
                
                $arp_table_data[$col_key]['rows'][$rkey]['description'] = isset( $rval['row_description'] ) ? $rval['row_description'] : '';
            }

            

            $arp_table_data[$col_key]['button_content']['btn_content'] = isset( $tab_col['button_text'] ) ? $tab_col['button_text'] : ''; 
            $arp_table_data[$col_key]['button_content']['btn_url'] = isset( $tab_col['button_url'] ) ? $tab_col['button_url'] : '';
            $arp_table_data[$col_key]['footer_content']['footer_content'] = isset( $tab_col['footer_content'] ) ? $tab_col['footer_content'] : '';


            $arp_table_data[$col_key]['button_content']['size'] = isset( $tab_col['button_size'] ) ? $tab_col['button_size'] : '';
            $arp_table_data[$col_key]['button_content']['height'] = isset( $tab_col['button_height'] ) ? $tab_col['button_height'] : '';
            $arp_table_data[$col_key]['button_content']['image'] = isset( $tab_col['btn_img'] ) ? $tab_col['btn_img'] : '';
            $arp_table_data[$col_key]['button_content']['image_height'] = isset( $tab_col['btn_img_height'] ) ? $tab_col['btn_img_height'] : '';
            $arp_table_data[$col_key]['button_content']['image_width'] = isset( $tab_col['btn_img_width'] ) ? $tab_col['btn_img_width'] : '';

            $arp_table_data[$col_key]['button_content']['is_new_window'] = isset( $tab_col['is_new_window'] ) ? $tab_col['is_new_window'] : 0;

            $arp_table_data[$col_key]['button_content']['min_height'] = isset( $tab_col['button_min_height'] ) ? $tab_col['button_min_height'] : '';

            if( isset( $tab_col['is_caption'] ) && $tab_col['is_caption'] == 1 ){
                $arp_table_data[$col_key]['body_section']['alignment'] = isset( $tab_col['body_text_alignment'] ) ? $tab_col['body_text_alignment'] : '';
                $arp_table_data[$col_key]['body_section']['font_family'] = isset( $tab_col['content_font_family'] ) ? $tab_col['content_font_family'] : '';
                $arp_table_data[$col_key]['body_section']['font_size'] = isset( $tab_col['content_font_size'] ) ? $tab_col['content_font_size'] : '';
            }

            
            $arp_table_data[$col_key]['footer_content']['position'] = isset( $tab_col['footer_content_position'] ) ? $tab_col['footer_content_position'] : '';
            $arp_table_data[$col_key]['footer_content']['min_height'] = isset( $tab_col['footer_min_height'] ) ? $tab_col['footer_min_height'] : '';

            if( isset( $tab_col['is_caption'] ) && $tab_col['is_caption'] == 1 ){

                $footer_text_alignment_global = isset($general_option['column_settings']['arp_footer_text_alignment']) ? $general_option['column_settings']['arp_footer_text_alignment'] : 'center';

                $footer_text_alignment = isset( $tab_col['footer_text_align'] ) ? $tab_col['footer_text_align'] : $footer_text_alignment_global;

                $arp_table_data[$col_key]['footer_content']['alignment'] = $footer_text_alignment;
                $arp_table_data[$col_key]['footer_content']['font_family'] = isset( $tab_col['footer_level_options_font_family'] ) ? $tab_col['footer_level_options_font_family'] : '';
                $arp_table_data[$col_key]['footer_content']['font_size'] = isset( $tab_col['footer_level_options_font_size'] ) ? $tab_col['footer_level_options_font_size'] : '';
                $arp_table_data[$col_key]['footer_content']['font_bold'] = isset( $tab_col['footer_level_options_font_style_bold'] ) ? $tab_col['footer_level_options_font_style_bold'] : '';
                $arp_table_data[$col_key]['footer_content']['font_italic'] = isset( $tab_col['footer_level_options_font_style_italic'] ) ? $tab_col['footer_level_options_font_style_italic'] : '';
                $arp_table_data[$col_key]['footer_content']['font_decoration'] = isset( $tab_col['footer_level_options_font_style_decoration'] ) ? $tab_col['footer_level_options_font_style_decoration'] :'';
            }

            $arp_table_data[$col_key]['pricing_content']['price_text'] = isset( $tab_col['price_text'] ) ? $tab_col['price_text'] : '';

                    
            $arp_table_data[$col_key]['pricing_content']['shortcode_style'] = isset( $tab_col['arp_shortcode_customization_style'] ) ? $tab_col['arp_shortcode_customization_style'] : '';
            $arp_table_data[$col_key]['pricing_content']['shortcode_size'] = isset( $tab_col['arp_shortcode_customization_size'] ) ? $tab_col['arp_shortcode_customization_size'] : '';

            $arp_table_data[$col_key]['pricing_content']['font_family'] = isset( $tab_col['price_font_family'] ) ? $tab_col['price_font_family'] : '';

            
            if( isset( $tab_col['is_caption'] ) && $tab_col['is_caption'] == 1 ){
                $hvkey = 'html_content';
            } else {
                $hvkey = 'package_title';
            }

            $arp_table_data[$col_key]['header_content']['header_title'] = isset( $tab_col[$hvkey] ) ? $tab_col[$hvkey] : '';


            $arp_table_data[$col_key]['header_content']['alignment'] = isset( $tab_col['header_font_align']) ? $tab_col['header_font_align'] : 'center';
            $arp_table_data[$col_key]['header_content']['font_family'] = isset( $tab_col['header_font_family'] ) ? $tab_col['header_font_family'] : '';
            $arp_table_data[$col_key]['header_content']['font_size'] = isset( $tab_col['header_font_size'] ) ? $tab_col['header_font_size'] : '';
            $arp_table_data[$col_key]['header_content']['font_bold'] = isset( $tab_col['header_style_bold'] ) ? $tab_col['header_style_bold'] : '';
            $arp_table_data[$col_key]['header_content']['font_italic'] = isset( $tab_col['header_style_italic'] ) ? $tab_col['header_style_italic'] : '';
            $arp_table_data[$col_key]['header_content']['font_decoration'] = isset( $tab_col['header_style_decoration'] ) ? $tab_col['header_style_decoration'] : '';
            $arp_table_data[$col_key]['header_content']['shortcode_min_height'] = isset( $tab_col['shortcode_min_height']) ? $tab_col['shortcode_min_height'] : '';

            
            $arp_table_data[$col_key]['header_content']['min_height'] = isset( $tab_col['min_height'] ) ? $tab_col['min_height'] : '';

            $arp_table_data[$col_key]['header_content']['header_shortcode'] = isset( $tab_col['arp_header_shortcode'] ) ? $tab_col['arp_header_shortcode'] : '';

        

            $arp_table_data[$col_key]['header_content']['shortcode_style'] = isset( $tab_col['arp_shortcode_customization_style'] ) ? $tab_col['arp_shortcode_customization_style'] : '';
            $arp_table_data[$col_key]['header_content']['shortcode_size'] = isset( $tab_col['arp_shortcode_customization_size'] ) ? $tab_col['arp_shortcode_customization_size'] : '';

            $arp_table_data[$col_key]['column_description']['description'] = isset( $tab_col['column_description'] ) ? $tab_col['column_description'] : '';

            $arp_table_data[$col_key]['column_description']['min_height'] = isset( $tab_col['col_desc_min_height'] ) ? $tab_col['col_desc_min_height'] : '';

            $arp_table_data[$col_key]['color_section']['column_bg_color'] = isset( $tab_col['column_background_color'] ) ? $tab_col['column_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['column_hover_bg_color'] = isset( $tab_col['column_hover_background_color'] ) ? $tab_col['column_hover_background_color'] : '';

            $arp_table_data[$col_key]['color_section']['header_bg_color'] = isset( $tab_col['header_background_color'] ) ? $tab_col['header_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['header_hover_bg_color'] = isset( $tab_col['header_hover_background_color'] ) ? $tab_col['header_hover_background_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['header_font_color'] = isset( $tab_col['header_font_color'] ) ? $tab_col['header_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['header_hover_font_color'] = isset( $tab_col['header_hover_font_color'] ) ? $tab_col['header_hover_font_color'] : '';

            $arp_table_data[$col_key]['color_section']['price_bg_color'] = isset( $tab_col['price_background_color'] ) ? $tab_col['price_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['price_hover_bg_color'] = isset( $tab_col['price_hover_background_color'] ) ? $tab_col['price_hover_background_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['price_font_color'] = isset( $tab_col['price_font_color'] ) ? $tab_col['price_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['price_hover_font_color'] = isset( $tab_col['price_hover_font_color'] ) ? $tab_col['price_hover_font_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['price_text_font_color'] = isset( $tab_col['price_text_font_color'] ) ? $tab_col['price_text_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['price_text_hover_font_color'] = isset( $tab_col['price_text_hover_font_color'] ) ? $tab_col['price_text_hover_font_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['content_font_color'] = isset( $tab_col['content_font_color'] ) ? $tab_col['content_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['content_even_font_color'] = isset( $tab_col['content_even_font_color'] ) ? $tab_col['content_even_font_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['content_hover_font_color'] = isset( $tab_col['content_hover_font_color'] ) ? $tab_col['content_hover_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['content_even_hover_font_color'] = isset( $tab_col['content_even_hover_font_color'] ) ? $tab_col['content_even_hover_font_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['content_odd_color'] = isset( $tab_col['content_odd_color'] ) ? $tab_col['content_odd_color'] : '';
            $arp_table_data[$col_key]['color_section']['content_odd_hover_color'] = isset( $tab_col['content_odd_hover_color'] ) ? $tab_col['content_odd_hover_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['content_even_color'] = isset( $tab_col['content_even_color'] ) ? $tab_col['content_even_color'] : '';
            $arp_table_data[$col_key]['color_section']['content_even_hover_color'] = isset( $tab_col['content_even_hover_color'] ) ? $tab_col['content_even_hover_color'] : '';

            $arp_table_data[$col_key]['color_section']['button_bg_color'] = isset( $tab_col['button_background_color'] ) ? $tab_col['button_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['button_hover_bg_color'] = isset( $tab_col['button_hover_background_color'] ) ? $tab_col['button_hover_background_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['button_font_color'] = isset( $tab_col['button_font_color'] ) ? $tab_col['button_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['button_hover_font_color'] = isset( $tab_col['button_hover_font_color'] ) ? $tab_col['button_hover_font_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['footer_font_color'] = isset( $tab_col['footer_level_options_font_color'] ) ? $tab_col['footer_level_options_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['footer_hover_font_color'] = isset( $tab_col['footer_level_options_hover_font_color'] ) ? $tab_col['footer_level_options_hover_font_color'] : '';
            
            $arp_table_data[$col_key]['color_section']['footer_background_color'] = isset( $tab_col['footer_background_color'] ) ? $tab_col['footer_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['footer_hover_background_color'] = isset( $tab_col['footer_hover_background_color'] ) ? $tab_col['footer_hover_background_color'] : '';

            $arp_table_data[$col_key]['color_section']['caption_border_color'] = isset( $general_option['column_settings']['arp_caption_border_color'] ) ? $general_option['column_settings']['arp_caption_border_color'] : '';
            $arp_table_data[$col_key]['color_section']['caption_row_border_color'] = isset( $general_option['column_settings']['arp_caption_row_border_color'] ) ? $general_option['column_settings']['arp_caption_row_border_color'] : '';

            $arp_table_data[$col_key]['color_section']['shortcode_bg_color'] = isset( $tab_col['shortcode_background_color'] ) ? $tab_col['shortcode_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['shortcode_hover_bg_color'] = isset( $tab_col['shortcode_hover_background_color'] ) ? $tab_col['shortcode_hover_background_color'] : '';

            $arp_table_data[$col_key]['color_section']['shortcode_font_color'] = isset( $tab_col['shortcode_font_color'] ) ? $tab_col['shortcode_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['shortcode_hover_font_color'] = isset( $tab_col['shortcode_hover_font_color'] ) ? $tab_col['shortcode_hover_font_color'] : '';

            $arp_table_data[$col_key]['color_section']['column_description_font_color'] = isset( $tab_col['column_description_font_color'] ) ? $tab_col['column_description_font_color'] : '';
            $arp_table_data[$col_key]['color_section']['column_description_hover_font_color'] = isset( $tab_col['column_description_hover_font_color'] ) ? $tab_col['column_description_hover_font_color'] : '';

            $arp_table_data[$col_key]['color_section']['column_desc_bg_color'] = isset( $tab_col['column_desc_background_color'] ) ? $tab_col['column_desc_background_color'] : '';
            $arp_table_data[$col_key]['color_section']['column_desc_hover_bg_color'] = isset( $tab_col['column_desc_hover_background_color'] ) ? $tab_col['column_desc_hover_background_color'] : '';


            $arp_table_data[$col_key]['column_section']['column_width'] = isset( $tab_col['column_width'] ) ? $tab_col['column_width'] : '';
            $arp_table_data[$col_key]['column_section']['caption_border_size'] = isset( $general_option['column_settings']['arp_caption_border_size'] ) ? $general_option['column_settings']['arp_caption_border_size'] : '';
            $arp_table_data[$col_key]['column_section']['caption_border_style'] = isset( $general_option['column_settings']['arp_caption_border_style']) ? $general_option['column_settings']['arp_caption_border_style'] : '';
            $arp_table_data[$col_key]['column_section']['caption_border_left'] = isset( $general_option['column_settings']['arp_caption_border_left'] ) ? $general_option['column_settings']['arp_caption_border_left'] : '';
            $arp_table_data[$col_key]['column_section']['caption_border_right'] = isset( $general_option['column_settings']['arp_caption_border_right'] ) ? $general_option['column_settings']['arp_caption_border_right'] : '';
            $arp_table_data[$col_key]['column_section']['caption_border_top'] = isset( $general_option['column_settings']['arp_caption_border_top'] ) ? $general_option['column_settings']['arp_caption_border_top'] : '';
            $arp_table_data[$col_key]['column_section']['caption_border_bottom'] = isset( $general_option['column_settings']['arp_caption_border_bottom'] ) ? $general_option['column_settings']['arp_caption_border_bottom'] : '';

            $arp_table_data[$col_key]['column_section']['column_background_image'] = isset( $tab_col['column_background_image'] ) ? $tab_col['column_background_image'] : '';
            $arp_table_data[$col_key]['column_section']['column_background_scaling'] = isset( $tab_col['column_background_scaling'] ) ? $tab_col['column_background_scaling'] : '';
            $arp_table_data[$col_key]['column_section']['column_background_image_height'] = isset( $tab_col['column_background_image_height'] ) ? $tab_col['column_background_image_height'] : '';
            $arp_table_data[$col_key]['column_section']['column_background_image_width'] = isset( $tab_col['column_background_image_width'] ) ? $tab_col['column_background_image_width'] : '';
            $arp_table_data[$col_key]['column_section']['column_background_min_positon'] = isset( $tab_col['column_background_min_positon'] ) ? $tab_col['column_background_min_positon'] : '50';
            $arp_table_data[$col_key]['column_section']['column_background_max_positon'] = isset( $tab_col['column_background_max_positon'] ) ? $tab_col['column_background_max_positon'] : '50';

            $arp_table_data[$col_key]['column_section']['column_highlight'] = isset( $tab_col['column_highlight'] ) ? $tab_col['column_highlight'] : '';

            $arp_table_data[$col_key]['column_section']['arp_ribbon'] = isset( $tab_col['ribbon_setting']['arp_ribbon'] ) ? $tab_col['ribbon_setting']['arp_ribbon'] : '';
            $arp_table_data[$col_key]['column_section']['arp_ribbon_bgcol'] = isset( $tab_col['ribbon_setting']['arp_ribbon_bgcol'] ) ? $tab_col['ribbon_setting']['arp_ribbon_bgcol'] : '';
            $arp_table_data[$col_key]['column_section']['arp_ribbon_txtcol'] = isset( $tab_col['ribbon_setting']['arp_ribbon_txtcol'] ) ? $tab_col['ribbon_setting']['arp_ribbon_txtcol'] : '';
            $arp_table_data[$col_key]['column_section']['arp_ribbon_position'] = isset( $tab_col['ribbon_setting']['arp_ribbon_position'] ) ? $tab_col['ribbon_setting']['arp_ribbon_position'] : '';

            $arp_table_data[$col_key]['column_section']['arp_ribbon_custom_position_rl'] = isset( $tab_col['ribbon_setting']['arp_ribbon_custom_position_rl'] ) ? $tab_col['ribbon_setting']['arp_ribbon_custom_position_rl'] : '';
            $arp_table_data[$col_key]['column_section']['arp_ribbon_custom_position_top'] = isset( $tab_col['ribbon_setting']['arp_ribbon_custom_position_top'] ) ? $tab_col['ribbon_setting']['arp_ribbon_custom_position_top'] : '';

           $arp_table_data[$col_key]['column_section']['arp_ribbon_content'] = isset( $tab_col['ribbon_setting']['arp_ribbon_content'] ) ? $tab_col['ribbon_setting']['arp_ribbon_content'] : '';
            $arp_table_data[$col_key]['column_section']['arp_custom_ribbon'] = isset( $tab_col['ribbon_setting']['arp_custom_ribbon'] ) ? $tab_col['ribbon_setting']['arp_custom_ribbon'] : ''; 

            $arp_table_data[$col_key]['column_section']['post_variables_content'] = isset( $tab_col['post_variables_content'] ) ? $tab_col['post_variables_content'] : '';


        }


        echo '<input type="hidden" id="arp_table_data" name="arp_table_data" value="'. htmlspecialchars( json_encode( $arp_table_data ) ). '" />';

        $maxrowcount = 0;
        if (is_array($table_cols)) {
            foreach ($table_cols as $countcol) {
                if ($countcol['rows'] && count($countcol['rows']) > $maxrowcount)
                    $maxrowcount = count($countcol['rows']);
            }
            $maxrowcount--;
        }

        $opts['columns'] = $table_cols;

        $total_columns = count($table_cols);

        $column_settings = $arplite_pricingtable->arplite_reset_colorpicker( $general_option['column_settings'] );

        $hover_type = $column_settings['column_highlight_on_hover'];

        $template_settings = $general_option['template_setting'];

        $general_settings = $general_option['general_settings'];

        $template_type = $template_settings['template_type'];

        $template = $template_settings['template'];

        $ref_template = $general_settings['reference_template'];

        $template_id = $template_settings['template'];

        $arp_template_skin = $template_settings['skin'];

        $is_responsive = $general_option['column_settings']['is_responsive'];

        $reference_template = $general_settings['reference_template'];

        $arp_global_button_type = isset($column_settings['arp_global_button_type']) ? $column_settings['arp_global_button_type'] : 'shadow';

        $arp_global_button_class_array = $arpricelite_default_settings->arp_button_type();
            
        $arp_global_button_class = '';
        if ($arp_global_button_type !== 'none') {
            if (isset($column_settings['disable_button_hover_effect']) && $column_settings['disable_button_hover_effect'] == 1) {
                $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'] . ' arp_button_hover_disable arp_editor';
            } else {
                $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'] . ' arp_editor';
            }
        }

        $arp_template_custom_color = isset($template_settings['custom_color_code']) ? $template_settings['custom_color_code'] : '';
        $shadow_style = '';
        if ($column_settings['column_border_radius_top_left'] == 0 && $column_settings['column_border_radius_top_right'] == 0 && $column_settings['column_border_radius_bottom_right'] == 0 && $column_settings['column_border_radius_bottom_left'] == 0) {
            $shadow_style = $column_settings['column_box_shadow_effect'];
        }


        $caption_col = array();

        if (is_array($opts['columns'])) {
            foreach ($opts['columns'] as $key => $val) {
                if ($val['is_caption'] == 1) {
                    $caption_col[] = 1;
                } else {
                    $caption_col[] = 0;
                }
            }
        }

        $tablestring .= "<div class='pricingtable_menu_belt' style='display:block;'>";

            $tablestring .= "<div class='pricingtable_menu_inner'>";
            
                $tablestring .= "<div class='pricing_table_editor_logo'></div>";
                
                $tablestring .= "<div class='pricing_table_main'>";

                    $tablestring .= "<div class='pt_table_main_cnt'>";

                        $tablestring .= "<div class='header_table_name enable' data-image='" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/icons/edit-icon_hover.png' id='main_pricing_table_name'>";

                            $tablestring .= "<input type='text' name='pricing_table_main' id='pricing_table_main' class='arp_pricing_table_name' value='" . esc_html($name) . "'>";
                    
                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                $tablestring .= "</div>";

                $tablestring .= "<div class='pricing_table_btns'>";

                    $display = ( empty($id) or $is_clone == 1 ) ? 'display:none;' : '';

                    $shortcode_display = ($_GET['arp_action'] == 'edit') ? '' : "display:none;";
                
                    $tablestring .= "<div title='".esc_html__('Shortcode','arprice-responsive-pricing-table')."' id='arp_shortcode' class='arp_shortcode_main arp_shortcode' style='" . $display . $shortcode_display. "' >";

                        $tablestring .= '<div class="arp_shortcode_icon" id="arprice_shortcode_icon"></div>';

                    $tablestring .= "</div>";

                    $tablestring .= '<div class="arprice_editor_shortcode_list_content">';
                                    
                        $tablestring .= '<ul id="arp_editor_saved_form_shortcodes" class="arp_editor_form_shortcode_list" >';

                            $tablestring .= '<li class="arprice_editor_shortcode_header">'.esc_html__("Shortcode", 'arprice-responsive-pricing-table').'</li>';
                            
                            $tablestring .= '<li class="arprice_editor_shortcode">';
                            
                                $tablestring .= '<span class="arprice_shortcode_label">'.esc_html__("Embed Inline Pricing Table", 'arprice-responsive-pricing-table').'</span>';

                                $tablestring .= '<span id="arp_shortcode_value" class="arprice_shortcode_content">[ARPLite id='.$id.']</span>';
                            
                            $tablestring .= '</li>';
                            
                        $tablestring .= '</ul>';

                    $tablestring .= '</div>';

                    $tablestring .= "<div class='btn_field' style='float:right;height:100%;'>";

                        $tablestring .= "<div class='arp_editor_top_belt_btn enable arp_save_btn' id='save_btn'></div>";

                        $tablestring .= "<div class='arp_editor_top_belt_btn arp_preview_btn' data-src='" . $arpricelite_form->get_direct_link() . "' id='preview_btn' onClick='arp_preview_new(\"" . $arpricelite_form->get_direct_link() . "\");' ></div>";

                        $export_option_style =(isset($_REQUEST['arp_action']) && $_REQUEST['arp_action'] =='edit' ) ? 'display:inline-block;' : 'display:none;';
                
                        $tablestring .= '<div class="arp_editor_top_belt_btn arp_export_btn" id="export_table_options" style="'.$export_option_style.'"></div>';

                        $tablestring .= "<div class='arp_editor_top_belt_btn arp_cancel_btn' id='template_close_btn' onClick='javascript:location.href=\"admin.php?page=arpricelite\"'></div>";

                        $arp_template = isset($arp_template) ? $arp_template : '';
                        $arp_template_skin = ($arp_template_skin) ? $arp_template_skin : '';
                        $arplitetemplate_1 = ($id) ? 'arplitetemplate_' . $id : '';

                        $tablestring .= "<input type='hidden' name='arp_template' id='arp_template_id_main_belt' value='" . esc_html( $arplitetemplate_1) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_template_old' id='arp_template_old' value='" . esc_html( $arp_template ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_template_skin_editor' class='arp_template_skin' id='arp_template_skin' value='" . esc_html( $arp_template_skin ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_custom_color_code' id='arp_custom_color_code' value='" . esc_html( $arp_template_custom_color ) . "' />";

                        $arp_template_is_custom_color = isset($arp_template_is_custom_color) ? $arp_template_is_custom_color : '';
                        
                        $tablestring .= "<input type='hidden' name='is_custom_color' id='is_custom_color' value='" . esc_html( $arp_template_is_custom_color) . "' />";

                        $arp_template_column_bg_color = isset($general_option['custom_skin_colors']['arp_column_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_column_bg_custom_color'] : '';
                        $arp_template_column_desc_bg_color = isset($general_option['custom_skin_colors']['arp_column_desc_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_column_desc_bg_custom_color'] : '';
                        $arp_template_header_bg_color = isset($general_option['custom_skin_colors']['arp_header_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_header_bg_custom_color'] : '';
                        $arp_template_pricing_bg_color = isset($general_option['custom_skin_colors']['arp_pricing_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_pricing_bg_custom_color'] : '';
                        $arp_template_odd_row_bg_color = isset($general_option['custom_skin_colors']['arp_body_odd_row_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_body_odd_row_bg_custom_color'] : '';
                        $arp_template_even_row_bg_color = isset($general_option['custom_skin_colors']['arp_body_even_row_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_body_even_row_bg_custom_color'] : '';
                        $arp_template_footer_content_bg_color = isset($general_option['custom_skin_colors']['arp_footer_content_bg_color']) ? $general_option['custom_skin_colors']['arp_footer_content_bg_color'] : '';
                        $arp_template_button_bg_color = isset($general_option['custom_skin_colors']['arp_button_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_button_bg_custom_color'] : '';
                        $arp_column_bg_hover_color = isset($general_option['custom_skin_colors']['arp_column_bg_hover_color']) ? $general_option['custom_skin_colors']['arp_column_bg_hover_color'] : '';
                        $arp_button_bg_hover_color = isset($general_option['custom_skin_colors']['arp_button_bg_hover_color']) ? $general_option['custom_skin_colors']['arp_button_bg_hover_color'] : '';
                        $arp_header_bg_hover_color = isset($general_option['custom_skin_colors']['arp_header_bg_hover_color']) ? $general_option['custom_skin_colors']['arp_header_bg_hover_color'] : '';
                        $arp_price_bg_hover_color = isset($general_option['custom_skin_colors']['arp_price_bg_hover_color']) ? $general_option['custom_skin_colors']['arp_price_bg_hover_color'] : '';
                        $arp_template_odd_row_hover_bg_color = isset($general_option['custom_skin_colors']['arp_body_odd_row_hover_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_body_odd_row_hover_bg_custom_color'] : '';
                        $arp_template_even_row_hover_bg_color = isset($general_option['custom_skin_colors']['arp_body_even_row_hover_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_body_even_row_hover_bg_custom_color'] : '';
                        $arp_footer_hover_background_color = isset($general_option['custom_skin_colors']['arp_footer_content_hover_bg_color']) ? $general_option['custom_skin_colors']['arp_footer_content_hover_bg_color'] : '';
                        $arp_template_column_desc_hover_bg_color = isset($general_option['custom_skin_colors']['arp_column_desc_hover_bg_custom_color']) ? $general_option['custom_skin_colors']['arp_column_desc_hover_bg_custom_color'] : '';
                        $arp_header_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_header_font_custom_color']) ? $general_option['custom_skin_colors']['arp_header_font_custom_color'] : '';
                        $arp_header_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_header_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_header_font_custom_hover_color'] : "";
                        $arp_price_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_price_font_custom_color']) ? $general_option['custom_skin_colors']['arp_price_font_custom_color'] : '';
                        $arp_price_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_price_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_price_font_custom_hover_color'] : '';
                        $arp_price_duration_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_price_duration_font_custom_color']) ? $general_option['custom_skin_colors']['arp_price_duration_font_custom_color'] : '';
                        $arp_price_duration_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_price_duration_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_price_duration_font_custom_hover_color'] : '';
                        $arp_desc_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_desc_font_custom_color']) ? $general_option['custom_skin_colors']['arp_desc_font_custom_color'] : '';
                        $arp_desc_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_desc_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_desc_font_custom_hover_color'] : '';
                        $arp_body_label_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_body_label_font_custom_color']) ? $general_option['custom_skin_colors']['arp_body_label_font_custom_color'] : '';
                        $arp_body_label_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_body_label_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_body_label_font_custom_hover_color'] : '';
                        $arp_body_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_body_font_custom_color']) ? $general_option['custom_skin_colors']['arp_body_font_custom_color'] : '';
                        $arp_body_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_body_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_body_font_custom_hover_color'] : '';
                        $arp_body_even_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_body_even_font_custom_color']) ? $general_option['custom_skin_colors']['arp_body_even_font_custom_color'] : '';
                        $arp_body_even_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_body_even_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_body_even_font_custom_hover_color'] : "";

                        $arp_footer_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_footer_font_custom_color']) ? $general_option['custom_skin_colors']['arp_footer_font_custom_color'] : '';
                        $arp_footer_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_footer_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_footer_font_custom_hover_color'] : "";
                        $arp_button_font_custom_color_input = isset($general_option['custom_skin_colors']['arp_button_font_custom_color']) ? $general_option['custom_skin_colors']['arp_button_font_custom_color'] : '';
                        $arp_button_font_custom_hover_color_input = isset($general_option['custom_skin_colors']['arp_button_font_custom_hover_color']) ? $general_option['custom_skin_colors']['arp_button_font_custom_hover_color'] : "";
                
                        $arp_shortocode_background = isset( $general_option['custom_skin_colors']['arp_shortocode_background'] ) ? $general_option['custom_skin_colors']['arp_shortocode_background'] : '';
                        $arp_shortocode_font_color = isset( $general_option['custom_skin_colors']['arp_shortocode_font_color'] ) ? $general_option['custom_skin_colors']['arp_shortocode_font_color'] : '';
                        $arp_shortcode_bg_hover_color = isset( $general_option['custom_skin_colors']['arp_shortcode_bg_hover_color'] ) ? $general_option['custom_skin_colors']['arp_shortcode_bg_hover_color'] : '';
                        $arp_shortcode_font_hover_color = isset( $general_option['custom_skin_colors']['arp_shortcode_font_hover_color'] ) ? $general_option['custom_skin_colors']['arp_shortcode_font_hover_color'] : '';

                        $tablestring .= "<input type='hidden' name='arp_column_background_color' id='arp_column_background_color_input' value='" . esc_html( $arp_template_column_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_column_desc_background_color' id='arp_column_desc_background_color_input' value='" . esc_html( $arp_template_column_desc_bg_color) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_header_background_color' id='arp_header_background_color_input' value='" . esc_html( $arp_template_header_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_pricing_background_color' id='arp_pricing_background_color_input' value='" . esc_html( $arp_template_pricing_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_odd_row_background_color' id='arp_body_odd_row_background_color' value='" . esc_html( $arp_template_odd_row_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_even_row_background_color' id='arp_body_even_row_background_color' value='" . esc_html( $arp_template_even_row_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_footer_content_background_color' id='arp_footer_content_background_color' value='" . esc_html( $arp_template_footer_content_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_button_background_color' id='arp_button_background_color_input' value='" . esc_html( $arp_template_button_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_column_bg_hover_color' class='arp_column_bg_hover_color' id='arp_column_bg_hover_color' value='" . esc_html( $arp_column_bg_hover_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_header_bg_hover_color' class='arp_header_bg_hover_color' id='arp_header_bg_hover_color' value='" . esc_html( $arp_header_bg_hover_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_button_bg_hover_color' class='arp_button_bg_hover_color' id='arp_button_bg_hover_color' value='" . esc_html( $arp_button_bg_hover_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_price_bg_hover_color' class='arp_price_bg_hover_color' id='arp_price_bg_hover_color' value='" . esc_html( $arp_price_bg_hover_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_odd_row_hover_background_color' id='arp_body_odd_row_hover_background_color' class='arp_body_odd_row_hover_background_color' value='" . esc_html( $arp_template_odd_row_hover_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_even_row_hover_background_color' id='arp_body_even_row_hover_background_color' class='arp_body_even_row_hover_background_color' value='" . esc_html( $arp_template_even_row_hover_bg_color ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_footer_content_hover_background_color' id='arp_footer_hover_bg_color' class='arp_footer_hover_background_color' value='" . esc_html( $arp_footer_hover_background_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_column_desc_hover_background_color' class='arp_column_desc_hover_background_color_input' id='arp_column_desc_hover_background_color_input' value='" . esc_html( $arp_template_column_desc_hover_bg_color ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_header_font_custom_color_input' class='arp_header_font_custom_color_input' id='arp_header_font_custom_color_input' value='" . esc_html( $arp_header_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_header_font_custom_hover_color_input' class='arp_header_font_custom_hover_color_input' id='arp_header_font_custom_hover_color_input' value='" . esc_html( $arp_header_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_price_font_custom_color_input' class='arp_price_font_custom_color_input' id='arp_price_font_custom_color_input' value='" . esc_html( $arp_price_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_price_font_custom_hover_color_input' class='arp_price_font_custom_hover_color_input' id='arp_price_font_custom_hover_color_input' value='" . esc_html( $arp_price_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_price_duration_font_custom_color_input' class='arp_price_duration_font_custom_color_input' id='arp_price_duration_font_custom_color_input' value='" . esc_html( $arp_price_duration_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_price_duration_font_custom_hover_color_input' class='arp_price_duration_font_custom_hover_color_input' id='arp_price_duration_font_custom_hover_color_input' value='" . esc_html( $arp_price_duration_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_desc_font_custom_color_input' class='arp_desc_font_custom_color_input' id='arp_desc_font_custom_color_input' value='" . esc_html( $arp_desc_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_desc_font_custom_hover_color_input' class='arp_desc_font_custom_hover_color_input' id='arp_desc_font_custom_hover_color_input' value='" . esc_html( $arp_desc_font_custom_hover_color_input ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_body_label_font_custom_color_input' class='arp_body_label_font_custom_color_input' id='arp_body_label_font_custom_color_input' value='" . esc_html( $arp_body_label_font_custom_color_input ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_body_label_font_custom_hover_color_input' class='arp_body_label_font_custom_hover_color_input' id='arp_body_label_font_custom_hover_color_input' value='" . esc_html( $arp_body_label_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_font_custom_color_input' class='arp_body_font_custom_color_input' id='arp_body_font_custom_color_input' value='" . esc_html( $arp_body_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_font_custom_hover_color_input' class='arp_body_font_custom_hover_color_input' id='arp_body_font_custom_hover_color_input' value='" . esc_html( $arp_body_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_body_even_font_custom_color_input' class='arp_body_even_font_custom_color_input' id='arp_body_even_font_custom_color_input' value='" . esc_html( $arp_body_even_font_custom_color_input ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_body_even_font_custom_hover_color_input' class='arp_body_even_font_custom_hover_color_input' id='arp_body_even_font_custom_hover_color_input' value='" . esc_html( $arp_body_even_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_footer_font_custom_color_input' class='arp_footer_font_custom_color_input' id='arp_footer_font_custom_color_input' value='" . esc_html( $arp_footer_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_footer_font_custom_hover_color_input' class='arp_footer_font_custom_hover_color_input' id='arp_footer_font_custom_hover_color_input' value='" . esc_html( $arp_footer_font_custom_hover_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_button_font_custom_color_input' class='arp_button_font_custom_color_input' id='arp_button_font_custom_color_input' value='" . esc_html( $arp_button_font_custom_color_input ) . "' />";

                        $tablestring .= "<input type='hidden' name='arp_button_font_custom_hover_color_input' class='arp_button_font_custom_hover_color_input' id='arp_button_font_custom_hover_color_input' value='" . esc_html( $arp_button_font_custom_hover_color_input ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_shortocode_background_color' id='arp_shortocode_background_color_input' value='" . esc_html( $arp_shortocode_background ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_shortocode_font_custom_color_input' class='arp_shortocode_font_custom_color_input' id='arp_shortocode_font_custom_color_input' value='" . esc_html( $arp_shortocode_font_color ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_shortcode_font_custom_hover_color_input' class='arp_shortcode_font_custom_hover_color_input' id='arp_shortcode_font_custom_hover_color_input' value='" . esc_html( $arp_shortcode_font_hover_color ) . "' />";
                        $tablestring .= "<input type='hidden' name='arp_shortcode_bg_hover_color' class='arp_shortcode_bg_hover_color' id='arp_shortcode_bg_hover_color' value='" . esc_html( $arp_shortcode_bg_hover_color ) . "' />";

                    $tablestring .= "</div>";

                $tablestring .= "</div>";

            $tablestring .= "</div>";

            /**
              * New Belt Design
              * 
              * @since ARPricelite 1.0
            */
            
            $tablestring .= "<div class='arprice_options_menu_belt'>";

                $tablestring .= "<div class='arprice_top_belt_menu_option' id='column_options'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='column_options_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Column Options', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= "<div class='arprice_top_belt_menu_option' id='column_effects'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='column_effects_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Effects', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= "<div class='arprice_top_belt_menu_option' id='all_font_options'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='font_options_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Fonts', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= "<div class='arprice_top_belt_menu_option arp_manage_color_options' id='all_color_options'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='color_options_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Colors', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= "<div class='arprice_top_belt_menu_option' id='tootip_options'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='tooltip_options_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Tooltip', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= "<div class='arprice_top_belt_menu_option' id='custom_css_options'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='custom_css_options_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Custom CSS', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= "<div class='arprice_top_belt_menu_option' id='toggle_content_options'>";
                    $tablestring .= "<div class='arprice_top_belt_inner_container'>";
                        $tablestring .= "<div class='toggle_content_options_img'></div>";
                        $tablestring .= "<label class='arprice_top_belt_label'>" . esc_html__('Toggle Price', 'arprice-responsive-pricing-table') . "</label>";
                    $tablestring .= "</div>";
                $tablestring .= "</div>";

                $tablestring .= '<div class="arprice_top_belt_menu_option arplite_restricted_view" id="migrate_template">';
                    $tablestring .= '<div class="arprice_top_belt_inner_container">';
                        $tablestring .= '<div class="migrate_content_options_img"></div>';
                        $tablestring .= '<label class="arprice_top_belt_label">'.esc_html__('Import Data', 'arprice-responsive-pricing-table').'</label>';
                        $tablestring .= '<span class="arp_new_feature_label">'. esc_html__('New', 'arprice-responsive-pricing-table') . '</span>';
                    $tablestring .= "</div>";
                $tablestring .= "</div>";


                $tablestring .= "<div class='arprice_top_belt_menu_right'>";

                    $tablestring .= "<div class='arprice_top_right_belt_inner container_width'>";
                        if ($column_settings['column_wrapper_width_txtbox'] != '') {
                            $wrapper_width_value = $column_settings['column_wrapper_width_txtbox'];
                        } else {
                            $wrapper_width_value = $arplite_mainoptionsarr['general_options']['wrapper_width'];
                        }
                        $tablestring .= "<label for='column_wrapper_width_txtbox'>" . esc_html__('Width', 'arprice-responsive-pricing-table') . "</label>&nbsp;&nbsp;";
                        $tablestring .= "<div class='arprice_container_width_wrapper'>";
                            $tablestring .= "<input type='text' id='column_wrapper_width_txtbox' value='".esc_html( $wrapper_width_value ) ."' class='arp_tab_txt' name='column_wrapper_width_txtbox'>";
                            $tablestring .= "<span>px</span>";
                        $tablestring .= "</div>";
                    $tablestring .= "</div>";

                $tablestring .= "</div>";

            $tablestring .= "</div>";

            /**
             * ARPricelite Column Options Menu New Design.
             * 
             * @since 1.0
             */
            /* Start */

            $tablestring .= "<div class='general_options_bar arp_hidden'>";

                $tablestring .= "<div class='general_options_bar_content'>";

                    $tablestring .= "<div class='arprice_toggle_menu_options'></div>";

                    /* Column Options Start */

                    $tablestring .= "<div class='general_column_options_tab enable global_opts' id='column_options'>";

                        $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__('Column Options', 'arprice-responsive-pricing-table') . "</div>";

                        $tablestring .= "<div class='column_option_dropdown' id='column_option_dropdown'>";
                
                            $tablestring .= "<div class='column_content_light_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Column Width', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $column_width_readonly = '';

                                    $tablestring .= "<span class='arp_col_px'>px</span>";

                                    $tablestring .= "<input type='text' " . $column_width_readonly . " name='all_column_width' class='arp_tab_txt' value='" . esc_html( $column_settings['all_column_width'] ) . "' id='all_column_width' />";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_content_light_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Space between Columns', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $tablestring .= "<span class='arp_col_px'>px</span>";

                                    $tablestring .= "<input type='text' name='column_space' class='arp_tab_txt' value='" . esc_html( $column_settings['column_space'] ) . "' id='column_space' />";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_content_light_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label two_cols column_opt_label_height' >" . esc_html__('Minimum Row Height', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $tablestring .= "<span class='arp_col_px'>px</span>";

                                    $tablestring .= "<input type='text' name='min_row_height' class='arp_tab_txt' value='".(!empty($column_settings['min_row_height']) ? $column_settings['min_row_height'] : '' )."' id='min_row_height' />";

                                $tablestring .= "</div>";
                                
                            $tablestring .= "</div>";

                            $allow_border_radius = $arpricelite_default_settings->arpricelite_allow_border_radius();
                
                            if ($allow_border_radius[$reference_template]) {

                                $tablestring .= "<div class='column_content_dark_row column_opt_row'>";

                                    $tablestring .= "<div class='column_opt_label two_cols' style='line-height:70px'>" . esc_html__('Column Radius (px)', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols align_right column_chk_box_alignment'>";


                                        if ($column_settings['column_box_shadow_effect'] == 'shadow_style_none' || $column_settings['column_box_shadow_effect'] == '') {
                                            $arp_tab_column_radius_txt_disabled = '';
                                        } else {
                                            $arp_tab_column_radius_txt_disabled = 'readonly="readonly"';
                                        }

                                        if ($column_settings['column_border_radius_top_left'] != '' || $column_settings['column_border_radius_top_left'] == 0) {
                                            $column_border_radius_top_left = $column_settings['column_border_radius_top_left'];
                                        } else {
                                            $column_border_radius_top_left = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['top_left'];
                                        }

                                        if ($column_settings['column_border_radius_top_right'] != '' || $column_settings['column_border_radius_top_right'] == 0) {
                                            $column_border_radius_top_right = $column_settings['column_border_radius_top_right'];
                                        } else {
                                            $column_border_radius_top_right = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['top_right'];
                                        }

                                        if ($column_settings['column_border_radius_bottom_right'] != '' || $column_settings['column_border_radius_bottom_right'] == 0) {
                                            $column_border_radius_bottom_right = $column_settings['column_border_radius_bottom_right'];
                                        } else {
                                            $column_border_radius_bottom_right = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['bottom_right'];
                                        }

                                        if ($column_settings['column_border_radius_bottom_left'] != '' || $column_settings['column_border_radius_bottom_left'] == 0) {
                                            $column_border_radius_bottom_left = $column_settings['column_border_radius_bottom_left'];
                                        } else {
                                            $column_border_radius_bottom_left = $arplite_mainoptionsarr['general_options']['default_column_radius_value'][$reference_template]['bottom_left'];
                                        }

                                        $tablestring .= "<div class='arp_column_radius_main'>";

                                            $tablestring .= "<div>";

                                                $tablestring .= "<span>".esc_html__('Left','arprice-responsive-pricing-table')."</span>";
                                            
                                                $tablestring .= "<input type='text' id='column_border_radius_top_left' value='".esc_html( $column_border_radius_top_left )."' class='arp_tab_txt arp_tab_column_radius_txt' name='column_border_radius_top_left' onBlur=\"arp_update_column_border_radius(this.value,jQuery('#column_border_radius_top_right').val(),jQuery('#column_border_radius_bottom_right').val(), jQuery('#column_border_radius_bottom_left').val(),$is_animated)\" } $arp_tab_column_radius_txt_disabled />";
                                            
                                            $tablestring .= "</div>";

                                            $tablestring .= "<div>";

                                                $tablestring .= "<span>".esc_html__('Right','arprice-responsive-pricing-table')."</span>";
                                            
                                                $tablestring .= "<input type='text' id='column_border_radius_top_right' value='".esc_html( $column_border_radius_top_right )."' class='arp_tab_txt arp_tab_column_radius_txt' name='column_border_radius_top_right' onBlur=\"arp_update_column_border_radius(jQuery('#column_border_radius_top_left').val(),this.value,jQuery('#column_border_radius_bottom_right').val(), jQuery('#column_border_radius_bottom_left').val(),$is_animated)\" $arp_tab_column_radius_txt_disabled />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div>";

                                                $tablestring .= "<span>".esc_html__('Left','arprice-responsive-pricing-table')."</span>";
                                            
                                                $tablestring .= "<input type='text' id='column_border_radius_bottom_left' value='".esc_html( $column_border_radius_bottom_left )."' class='arp_tab_txt arp_tab_column_radius_txt' name='column_border_radius_bottom_left' onBlur=\"arp_update_column_border_radius(jQuery('#column_border_radius_top_left').val(), jQuery('#column_border_radius_top_right').val(), jQuery('#column_border_radius_bottom_right').val(), this.value, $is_animated)\" $arp_tab_column_radius_txt_disabled />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div>";

                                                $tablestring .= "<span>".esc_html__('Right','arprice-responsive-pricing-table')."</span>";

                                                $tablestring .= "<input type='text' id='column_border_radius_bottom_right' value='".esc_html( $column_border_radius_bottom_right )."' class='arp_tab_txt arp_tab_column_radius_txt' name='column_border_radius_bottom_right' onBlur=\"arp_update_column_border_radius(jQuery('#column_border_radius_top_left').val(), jQuery('#column_border_radius_top_right').val(), this.value, jQuery('#column_border_radius_bottom_left').val(),$is_animated)\" $arp_tab_column_radius_txt_disabled />";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_column_radius_main'>";

                                            $tablestring .= "<div class='arp_column_radius_bottom'>";
                                        
                                                $tablestring .= "<span>".esc_html__('Top','arprice-responsive-pricing-table')."</span>";
                                        
                                            $tablestring .= "</div>";
                                        
                                            $tablestring .= "<div class='arp_column_radius_bottom'>";
                                        
                                                $tablestring .= "<span>".esc_html__('Bottom','arprice-responsive-pricing-table')."</span>";
                                        
                                            $tablestring .= "</div>";
                                        
                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";
                            }

                     
                            $tablestring .= "<div class='column_content_dark_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Enable Responsive column', 'arprice-responsive-pricing-table')."</div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right column_opt_opts_alignment'>";

                                    $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                        $tablestring .= "<input type='checkbox' name='is_responsive' id='is_responsive' class='arp_checkbox light_bg' value='1' " . checked($column_settings['is_responsive'], 1, false) . " />";

                                        $tablestring .= "<span></span>";

                                    $tablestring .= "</span>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Mobile', 'arprice-responsive-pricing-table') . "&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";

                                        $tablestring .= "<input type='hidden' name='arp_display_columns_mobile' id='arp_display_columns_mobile' value='1' />";

                                        $tablestring .= "<dl id='arp_display_columns_mobile' class='arp_selectbox arplite_restricted_view' data-id='arp_display_columns_mobile' data-name='arp_display_columns_mobile' style='margin-top:18px;'>";

                                            $tablestring .= "<dt><span>1</span><input type='text' style='display:none;' value='1' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                            
                                            $tablestring .= "<dd>";
                                            
                                                $tablestring .= "<ul class='arp_display_columns_mobile' data-id='arp_display_columns_mobile'>";
                                                    
                                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html__('All', 'arprice-responsive-pricing-table') . "' data-label='" . esc_html__('All', 'arprice-responsive-pricing-table') . "'>" . esc_html__('All', 'arprice-responsive-pricing-table') . "</li>";
                                                    for ($i = 1; $i <= $total_columns; $i++) {
                                                        $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . $i . "'>" . $i . "</li>";
                                                    }
                                                $tablestring .= "</ul>";
                                            
                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";
                    
                                $tablestring .= "<div class='column_opt_opts'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label two_cols'>" . esc_html__('Tablet', 'arprice-responsive-pricing-table') . "&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";

                                        $tablestring .= "<input type='hidden' name='arp_display_columns_tablet' id='arp_display_columns_tablet' value='3' />";

                                        $tablestring .= "<dl id='arp_display_columns_tablet' class='arp_selectbox arplite_restricted_view' data-id='arp_display_columns_tablet' data-name='arp_display_columns_tablet' style='margin-top:18px;'>";

                                            $tablestring .= "<dt><span>3</span><input type='text' style='display:none;' value='3' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                            
                                            $tablestring .= "<dd>";
                                                
                                                $tablestring .= "<ul class='arp_display_columns_tablet' data-id='arp_display_columns_tablet'>";
                                                    
                                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html__('All', 'arprice-responsive-pricing-table') . "' data-label='" . esc_html__('All', 'arprice-responsive-pricing-table') . "'>" . esc_html__('All', 'arprice-responsive-pricing-table') . "</li>";
                                                    
                                                    for ($i = 1; $i <= $total_columns; $i++) {
                                                        $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $i ) . "'>" . esc_html( $i ) . "</li>";
                                                    }

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";
                                        
                                        $tablestring .= "</dl>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            if (in_array(1, $caption_col)){
                                $style = 'display:block;';
                            } else {
                                $style = 'display:none;';
                            }

                            $tablestring .= "<div class='column_content_light_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Full Column Clickable', 'arprice-responsive-pricing-table') . "<br><span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $column_settings['full_column_clickable'] = isset($column_settings['full_column_clickable']) ? $column_settings['full_column_clickable'] : "";

                                    $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                        $tablestring .= "<input type='checkbox' name='full_column_clickable' id='full_column_clickable' class='arp_checkbox light_bg arplite_restricted_view' value='1' " . checked($column_settings['full_column_clickable'], 1, false) . " />";

                                        $tablestring .="<span></span>";

                                    $tablestring .="</span>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";
                
                            if (in_array(1, $caption_col)){
                                $style = 'display:block;';
                            } else {
                                $style = 'display:none;';
                            }

                            $tablestring .= "<div class='column_content_light_row column_opt_row' id='column_content_hide_caption_column' style='" . $style . "'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Hide Caption Column', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $column_settings['hide_caption_column'] = isset($column_settings['hide_caption_column']) ? $column_settings['hide_caption_column'] : "";

                                    $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                        $tablestring .= "<input type='checkbox' name='hide_caption_column' id='hide_caption_column' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_caption_column'], 1, false) . " />";

                                        $tablestring .="<span></span>";

                                    $tablestring .="</span>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_content_light_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Hide blank rows from bottom', 'arprice-responsive-pricing-table') . "</div>";

                                $column_settings['column_hide_blank_rows'] = isset($column_settings['column_hide_blank_rows'])?$column_settings['column_hide_blank_rows']:'';
                            
                                $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";
                                
                                        $tablestring .= "<input type='checkbox' name='hide_blank_rows' id='hide_blank_rows' value='yes' " . checked($column_settings['column_hide_blank_rows'], 'yes', false) . " class='arp_checkbox light_bg' />";

                                        $tablestring .="<span></span>";

                                    $tablestring .="</span>";

                                $tablestring .= "</div>";
                            
                                $tablestring .= "<div class='column_opt_label_help'>(" . esc_html__('Only bottom rows will hide and shown in preview and front end.', 'arprice-responsive-pricing-table') . ")</div>";

                            $tablestring .= "</div>";

                            if (in_array(1, $caption_col)){
                                $style = 'display:block;';
                            } else {
                                $style = 'display:none;';
                            }

                            $hide_section_array = $arpricelite_default_settings->arprice_hide_section_array();
                        
                            $hide_section_array = $hide_section_array[$ref_template];

                            $tablestring .= "<div class='column_content_light_row column_opt_row' id='arp_hide_show_section'>";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Hide Column Sections', 'arprice-responsive-pricing-table') . "</div>";

                                if (array_key_exists('arp_header', $hide_section_array)){
                                    $style = 'display:block;';
                                } else {
                                    $style = 'display:none;';
                                }

                                $tablestring .= "<div class='column_opt_opts' style='" . $style . "'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Hide Header', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols sub_column_chk_box_alignment'>";

                                        $column_settings['hide_header_global'] = isset($column_settings['hide_header_global']) ? $column_settings['hide_header_global'] : "";

                                        $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                            $tablestring .= "<input type='checkbox' data-hide-section='arp_header' name='hide_header_global' id='hide_header_global' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_header_global'], 1, false) . " />";

                                            $tablestring .="<span></span>";

                                        $tablestring .="</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                if (array_key_exists('arp_header_shortcode', $hide_section_array)){
                                    $style = 'display:block;';
                                } else {
                                    $style = 'display:none;';
                                }

                                $tablestring .= "<div class='column_opt_opts' style='" . $style . "'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Hide Shortcode', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols sub_column_chk_box_alignment'>";

                                        $column_settings['hide_header_shortcode_global'] = isset($column_settings['hide_header_shortcode_global']) ? $column_settings['hide_header_shortcode_global'] : "";

                                        $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";
                                    
                                            $tablestring .= "<input type='checkbox' data-hide-section='arp_header_shortcode' name='hide_header_shortcode_global' id='hide_header_shortcode_global' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_header_shortcode_global'], 1, false) . " />";

                                            $tablestring .="<span></span>";

                                        $tablestring .="</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                if (array_key_exists('arp_price', $hide_section_array)){
                                    $style = 'display:block;';
                                } else {
                                    $style = 'display:none;';
                                }

                                $tablestring .= "<div class='column_opt_opts' style='" . $style . "'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Hide Price', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols sub_column_chk_box_alignment'>";

                                        $column_settings['hide_price_global'] = isset($column_settings['hide_price_global']) ? $column_settings['hide_price_global'] : "";

                                        $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";
                                    
                                            $tablestring .= "<input type='checkbox' data-hide-section='arp_price' name='hide_price_global' id='hide_price_global' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_price_global'], 1, false) . " />";

                                            $tablestring .="<span></span>";

                                        $tablestring .="</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                if (array_key_exists('arp_feature', $hide_section_array)){
                                    $style = 'display:block;';
                                } else {
                                    $style = 'display:none;';
                                }

                                $tablestring .= "<div class='column_opt_opts' style='" . $style . "'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Hide Body', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols sub_column_chk_box_alignment'>";

                                        $column_settings['hide_feature_global'] = isset($column_settings['hide_feature_global']) ? $column_settings['hide_feature_global'] : "";

                                        $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                            $tablestring .= "<input type='checkbox' data-hide-section='arp_feature' name='hide_feature_global' id='hide_feature_global' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_feature_global'], 1, false) . " />";

                                            $tablestring .="<span></span>";

                                        $tablestring .="</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                if (array_key_exists('arp_description', $hide_section_array)){
                                    $style = 'display:block;';
                                } else {
                                    $style = 'display:none;';
                                }

                                $tablestring .= "<div class='column_opt_opts' style='" . $style . "'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Hide Description', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols sub_column_chk_box_alignment'>";

                                        $column_settings['hide_description_global'] = isset($column_settings['hide_description_global']) ? $column_settings['hide_description_global'] : "";

                                        $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                            $tablestring .= "<input type='checkbox' data-hide-section='arp_description' name='hide_description_global' id='hide_description_global' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_description_global'], 1, false) . " />";

                                            $tablestring .="<span></span>";

                                        $tablestring .="</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                if (array_key_exists('arp_footer', $hide_section_array)){
                                    $style = 'display:block;';
                                } else {
                                    $style = 'display:none;';
                                }

                                $tablestring .= "<div class='column_opt_opts' style='" . $style . "'>";
                                
                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Hide Button', 'arprice-responsive-pricing-table') . "</div>";
                                
                                    $tablestring .= "<div class='column_opt_opts two_cols sub_column_chk_box_alignment'>";
                                    
                                        $column_settings['hide_footer_global'] = isset($column_settings['hide_footer_global']) ? $column_settings['hide_footer_global'] : "";

                                        $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                            $tablestring .= "<input type='checkbox' data-hide-section='arp_footer' name='hide_footer_global' id='hide_footer_global' class='arp_checkbox light_bg' value='1' " . checked($column_settings['hide_footer_global'], 1, false) . " />";

                                            $tablestring .="<span></span>";

                                        $tablestring .="</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_label_help'>(" . esc_html__('Effect will shown in preview and front end only.', 'arprice-responsive-pricing-table') . ")</div>";

                            $tablestring .= "</div>";
     
                            if (in_array(1, $caption_col)){
                                $cls = 'column_content_dark_row';
                            } else {
                                $cls = 'column_content_light_row';
                            }

                            $display = 'display:block';

                            $tablestring .= "<div class='" . $cls . " column_opt_row' id='column_content_opacity' style='" . $display . ";' > ";

                                $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Opacity', 'arprice-responsive-pricing-table') . "<br><span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";

                                    $tablestring .= "<input type='hidden' name='column_opacity' id='column_opacity' value='1' />";

                                    $tablestring .= "<dl class='arp_selectbox arplite_restricted_view' id='column_opacity_dd' data-name='column_opacity' data-id='column_opacity' style='margin-top:18px;'>";

                                        $tablestring .= "<dt><span>1</span><input type='text' style='display:none;' value='1' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                        
                                        $tablestring .= "<dd>";
                                        
                                            $tablestring .= "<ul class='arp_column_opacity' data-id='column_opacity'>";
                                            
                                                foreach ($arplite_mainoptionsarr['general_options']['column_opacity'] as $column_opacity) {
                                                
                                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html( $column_opacity ) . "' data-label='" . $column_opacity . "'>" . $column_opacity . "</li>";
                                                
                                                }
                                        
                                            $tablestring .= "</ul>";
                            
                                        $tablestring .= "</dd>";
                            
                                    $tablestring .= "</di>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_label_help' style='margin: -2px 0 0;'>(" . esc_html__('Opacity will be shown in preview and frontend only.', 'arprice-responsive-pricing-table') . ")</div>";

                            $tablestring .= "</div>";

                            if (in_array(1, $caption_col)){
                                $cls = 'column_content_light_row';
                            } else {
                                $cls = 'column_content_dark_row';
                            }

                            if ($template_settings['features']['is_animated'] == 0 && $ref_template != 'arplitetemplate_23' && $ref_template != 'arplitetemplate_21') {

                                $arp_selectbox_disabled = '';

                                $tablestring .= "<div id='column_box_shadow_effect' class='$cls column_opt_row  $arp_selectbox_disabled'>";

                                    $tablestring .= "<div class='column_opt_label  two_cols'>" . esc_html__('Column Shadow', 'arprice-responsive-pricing-table')."</div>";

    	                            $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";

                                        if ($column_settings['column_box_shadow_effect'] != '') {
                                            $column_box_shadow_effect = $column_settings['column_box_shadow_effect'];
        	                            } else {
            	                            $column_box_shadow_effect = esc_html__('None', 'arprice-responsive-pricing-table');
                                        }

                                        $tablestring .= "<input type='hidden' name='column_box_shadow_effect' class='arp_box_shadow_change' id='column_box_shadow_effect' value='" . esc_html( $column_box_shadow_effect ) . "' />";

                	                    if ($column_settings['column_box_shadow_effect'] == 'shadow_style_1') {
                	                        $shadow_span_text = 'Style 1';
                	                    } else if ($column_settings['column_box_shadow_effect'] == 'shadow_style_2') {
                	                        $shadow_span_text = 'Style 2';
                	                    } else if ($column_settings['column_box_shadow_effect'] == 'shadow_style_3') {
                	                        $shadow_span_text = 'Style 3';
                	                    } else if ($column_settings['column_box_shadow_effect'] == 'shadow_style_4') {
                	                        $shadow_span_text = 'Style 4';
                	                    } else if ($column_settings['column_box_shadow_effect'] == 'shadow_style_5') {
                	                        $shadow_span_text = 'Style 5';
                	                    } else {
                	                        $shadow_span_text = 'None';
                	                    }

                                        $tablestring .= '<dl name="column_box_shadow_effect" style="margin-top:18px;" id="column_box_shadow_effect" class="arp_selectbox">';

                                            $tablestring .= '<dt><span>' . $shadow_span_text . '</span><input type="text" class="arp_autocomplete" value="None" style="display:none;"><i class="fas fa-caret-down fa-md"></i></dt>';

                                            $tablestring .= '<dd>';

                                                $tablestring .= '<ul data-id="column_box_shadow_effect" class="column_box_shadow_effect" id="column_box_shadow_effect1">';

                                                    foreach ($arplite_mainoptionsarr['general_options']['column_box_shadow_effect'] as $key => $column_box_shadow_effect) {
                                                        $tablestring .= '<li data-label="' . $column_box_shadow_effect . '" data-value="' . esc_html( $key ) . '" class="arp_selectbox_option" style="margin:0">' . $column_box_shadow_effect . '</li>';
                                                    }

        	                                    $tablestring .= '</ul>';

                                            $tablestring .= '</dd>';

                                        $tablestring .= '</dl>';

        	                        $tablestring .= "</div>";

                                    $tablestring .= "<div class='column_opt_label_help' style='margin: -2px 0 0;'>(" . esc_html__('Column shadow will not apply with column border radius.', 'arprice-responsive-pricing-table') . ")</div>";

    	                        $tablestring .= '</div>';
    	                    }

                            // Column Shadow End \\

                            $tablestring .= "<div class='column_content_dark_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Column Borders', 'arprice-responsive-pricing-table') . "</div>";

    	                        $tablestring .= "<div class='column_opt_opts'>";

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Border Size', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";

                                        $column_settings['arp_column_border_size'] = isset($column_settings['arp_column_border_size']) ? $column_settings['arp_column_border_size'] : '';

        	                            $tablestring .= "<input type='hidden' name='arp_column_border_size' id='arp_column_border_size' value='" . esc_html( $column_settings['arp_column_border_size'] ) . "' />";

        	                            $tablestring .= "<dl id='arp_column_border_size' class='arp_selectbox' data-id='arp_column_border_size' data-name='arp_column_border_size' style='margin-top:18px;'>";

                	                        if ($column_settings['arp_column_border_size']) {
                	                            $selected_border_size = $column_settings['arp_column_border_size'];
                	                        } else {
                	                            $selected_border_size = "0";
                	                        }

        	                                $tablestring .= "<dt><span>" . $selected_border_size . "</span><input type='text' style='display:none;' value='" . esc_html( $selected_border_size ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
    	                                
                                            $tablestring .= "<dd>";
    	                                
                                                $tablestring .= "<ul class='arp_column_border_size' data-id='arp_column_border_size'>";
    	                                
                                                    for ($i = 0; $i <= 10; $i++) {
                                                        $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $i ) . "'>" . esc_html( $i ) . "</li>";
                                                    }
    	                                
                                                $tablestring .= "</ul>";
    	                                
                                            $tablestring .= "</dd>";

            	                        $tablestring .= "</dl>";

        	                        $tablestring .= "</div>";

        	                    $tablestring .= "</div>";
    	                
        	                    $tablestring .= "<div class='column_opt_opts'>";

        	                        $tablestring .= "<div class='column_opt_label column_opt_sub_label two_cols'>" . esc_html__('Border Type', 'arprice-responsive-pricing-table') . "</div>";

        	                        $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";
    	                        
        	                            $column_settings['arp_column_border_type'] = isset($column_settings['arp_column_border_type']) ? $column_settings['arp_column_border_type'] : '';
    	                            
                                        $tablestring .= "<input type='hidden' name='arp_column_border_type' id='arp_column_border_type' value='" . esc_html( $column_settings['arp_column_border_type'] ) . "' />";

        	                            $tablestring .= "<dl id='arp_column_border_type' class='arp_selectbox' data-id='arp_column_border_type' data-name='arp_column_border_type' style='margin-top:18px;'>";

                                            if ($column_settings['arp_column_border_type']) {
                                                $selected_border_type = $column_settings['arp_column_border_type'];
                                            } else {
                                                $selected_border_type = esc_html__('Choose Option', 'arprice-responsive-pricing-table');
                                            }

                                            $tablestring .= "<dt><span>" . $selected_border_type . "</span><input type='text' style='display:none;' value='" . esc_html( $selected_border_type ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";

                                            $tablestring .= "<dd>";
                
                                                $tablestring .= "<ul class='arp_column_border_type' data-id='arp_column_border_type'>";

                                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='solid' data-label='".esc_html__('Solid','arprice-responsive-pricing-table')."'>".esc_html__('Solid','arprice-responsive-pricing-table')."</li>";

                                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='dotted' data-label='".esc_html__('Dotted','arprice-responsive-pricing-table')."'>".esc_html__('Dotted','arprice-responsive-pricing-table')."</li>";

                                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='dashed' data-label='".esc_html__('Dashed','arprice-responsive-pricing-table')."'>".esc_html__('Dashed','arprice-responsive-pricing-table')."</li>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

    	                           $tablestring .= "</div>";

    	                        $tablestring .= "</div>";

                                $disable_other_class = '';

                                $column_settings['arp_column_border_all'] = isset($column_settings['arp_column_border_all']) ? $column_settings['arp_column_border_all'] : '';
        		            
            		            if ($column_settings['arp_column_border_all'] == 1) {
            		                $disable_other_border = "disabled='disabled'";
            		                $disable_other_class = 'arp_selectbox_disabled';
            		            } else {
            		                $disable_other_border = "";
            		            }


                                $tablestring .= "<div class='column_opt_label column_opt_sub_label two_cols'>" . esc_html__('Borders', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts two_cols align_right column_chk_box_alignment'>";
    		                
            		                $tablestring .= "<div class='arp_column_radius_main'>";

            		                    $tablestring .= "<div>";
            		                        $tablestring .= "<span>".esc_html__('Left','arprice-responsive-pricing-table')."</span>";
            		                        $column_settings['arp_column_border_left'] = isset($column_settings['arp_column_border_left']) ? $column_settings['arp_column_border_left'] : '';
                                            $tablestring .= "<span class='arp_price_checkbox_wrapper_standard'>";
                		                        $tablestring .= "<input type='checkbox' name='arp_column_border_left' id='arp_column_border_left' class='arp_checkbox light_bg $disable_other_class' value='1' " . checked($column_settings['arp_column_border_left'], 1, false) . " style='position:relative;' $disable_other_border />";
                                                $tablestring .= "<span></span>";
                                            $tablestring .= "</span>";
            		                    $tablestring .= "</div>";

            		                    $tablestring .= "<div>";
            		                        $tablestring .= "<span>".esc_html__( 'Right','arprice-responsive-pricing-table' )."</span>";
            		                        $column_settings['arp_column_border_right'] = isset($column_settings['arp_column_border_right']) ? $column_settings['arp_column_border_right'] : '';
                                            $tablestring .= "<span class='arp_price_checkbox_wrapper_standard'>";
                		                        $tablestring .= "<input type='checkbox' name='arp_column_border_right' id='arp_column_border_right' class='arp_checkbox light_bg $disable_other_class' value='1' " . checked($column_settings['arp_column_border_right'], 1, false) . " style='position:relative;' $disable_other_border />";
                                                $tablestring .= "<span></span>";
                                            $tablestring .= "</span>";
            		                    $tablestring .= "</div>";

            		                    $tablestring .= "<div>";
            		                        $tablestring .= "<span>".esc_html__('Top','arprice-responsive-pricing-table')."</span>";
            		                        $column_settings['arp_column_border_top'] = isset($column_settings['arp_column_border_top']) ? $column_settings['arp_column_border_top'] : '';
                                            $tablestring .= "<span class='arp_price_checkbox_wrapper_standard'>";
                		                        $tablestring .= "<input type='checkbox' name='arp_column_border_top' id='arp_column_border_top' class='arp_checkbox light_bg $disable_other_class' value='1' " . checked($column_settings['arp_column_border_top'], 1, false) . " style='position:relative;' $disable_other_border />";
                                                $tablestring .= "<span></span>";
                                            $tablestring .= "</span>";
            		                    $tablestring .= "</div>";

            		                    $tablestring .= "<div>";
            		                        $tablestring .= "<span>".esc_html__('Bottom','arprice-responsive-pricing-table')."</span>";
            		                        $column_settings['arp_column_border_bottom'] = isset($column_settings['arp_column_border_bottom']) ? $column_settings['arp_column_border_bottom'] : '';
                                            $tablestring .= "<span class='arp_price_checkbox_wrapper_standard'>";
                		                        $tablestring .= "<input type='checkbox' name='arp_column_border_bottom' id='arp_column_border_bottom' class='arp_checkbox light_bg $disable_other_class' value='1' " . checked($column_settings['arp_column_border_bottom'], 1, false) . " style='position:relative;' $disable_other_border />";
                                                $tablestring .= "<span></span>";
                                            $tablestring .= "</span>";
            		                    $tablestring .= "</div>";

            		                $tablestring .= "</div>";
        		            
                                $tablestring .= "</div>";
    		        
            		        $tablestring .= "</div>";

            		        $tablestring .= "<div class='column_content_dark_row column_opt_row'>";

            		            $tablestring .= "<div class='column_opt_label'>" . esc_html__('Row Borders', 'arprice-responsive-pricing-table') . "</div>";

            		            $tablestring .= "<div class='column_opt_opts'>";

            		                $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Border Size', 'arprice-responsive-pricing-table') . "</div>";

                		            $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";

                		                $column_settings['arp_row_border_size'] = isset($column_settings['arp_row_border_size']) ? $column_settings['arp_row_border_size'] : '';

                		                $tablestring .= "<input type='hidden' name='arp_row_border_size' id='arp_row_border_size' value='" . esc_html( $column_settings['arp_row_border_size'] ) . "' />";

                		                $tablestring .= "<dl id='arp_row_border_size' class='arp_selectbox' data-id='arp_row_border_size' data-name='arp_row_border_size' style='margin-top:18px;'>";

                		                    if ($column_settings['arp_row_border_size']) {
                		                        $selected_border_size = $column_settings['arp_row_border_size'];
                		                    } else {
                		                        $selected_border_size = "0";
                		                    }
        		                    
                                            $tablestring .= "<dt><span>" . $selected_border_size . "</span><input type='text' style='display:none;' value='" . esc_html( $selected_border_size ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
    		                    
                                            $tablestring .= "<dd>";
        		                
                                                $tablestring .= "<ul class='arp_row_border_size' data-id='arp_row_border_size'>";
        		                
                                                    for ($i = 0; $i <= 10; $i++) {
        		                
                                                        $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $i ) . "'>" . esc_html( $i ) . "</li>";
        		                
                                                    }
        		                
                                                $tablestring .= "</ul>";
        		                
                                            $tablestring .= "</dd>";
        		                
                                        $tablestring .= "</dl>";

                		            $tablestring .= "</div>";

                		        $tablestring .= "</div>";
    	        
                		        $tablestring .= "<div class='column_opt_opts'>";

                		            $tablestring .= "<div class='column_opt_label column_opt_sub_label two_cols'>" . esc_html__('Border Type', 'arprice-responsive-pricing-table')."</div>";

                		            $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment'>";
    		                
                		                $column_settings['arp_row_border_type'] = isset($column_settings['arp_row_border_type']) ? $column_settings['arp_row_border_type'] : '';

                		                $tablestring .= "<input type='hidden' name='arp_row_border_type' id='arp_row_border_type' value='" . esc_html( $column_settings['arp_row_border_type'] ) . "' />";

                		                $tablestring .= "<dl id='arp_row_border_type' class='arp_selectbox' data-id='arp_row_border_type' data-name='arp_row_border_type' style='margin-top:18px;'>";

                		                    if ($column_settings['arp_row_border_type']) {
                		                        $selected_border_type = $column_settings['arp_row_border_type'];
                		                    } else {
                		                        $selected_border_type = esc_html__('Choose Option', 'arprice-responsive-pricing-table');
                		                    }
        		                    
                                            $tablestring .= "<dt><span>" . esc_html( $selected_border_type ) . "</span><input type='text' style='display:none;' value='" . esc_html( $selected_border_type ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";

                                            $tablestring .= "<dd>";

                		                        $tablestring .= "<ul class='arp_row_border_type' data-id='arp_row_border_type'>";
                		                            $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='solid' data-label='".esc_html__('Solid','arprice-responsive-pricing-table')."'>".esc_html__('Solid','arprice-responsive-pricing-table')."</li>";
                		                            $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='dotted' data-label='".esc_html__('Dotted','arprice-responsive-pricing-table')."'>".esc_html__('Dotted','arprice-responsive-pricing-table')."</li>";
                		                            $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='dashed' data-label='".esc_html__('Dashed','arprice-responsive-pricing-table')."'>".esc_html__('Dashed','arprice-responsive-pricing-table')."</li>";
                		                        $tablestring .= "</ul>";

                		                    $tablestring .= "</dd>";
                		                $tablestring .= "</dl>";
                		            $tablestring .= "</div>";

                		        $tablestring .= "</div>";

                            $tablestring .= "</div>";

                		    $style = '';
                		    if ($reference_template == 'arplitetemplate_26') {
                		        $style = 'display:none';
                		    } else {
                		        $style = 'display:block';
                		    }

                            $tablestring .= "<div class='column_content_light_row column_opt_row arp_no_border' style='" . $style . ";margin-bottom:15px;'>";

                		        $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('Button Style Options', 'arprice-responsive-pricing-table') . "</div>";

            		            $arp_global_button_border_type = isset($column_settings['arp_global_button_type']) ? $column_settings['arp_global_button_type'] : 'shadow';
        		            
            		            if ($reference_template == 'arplitetemplate_5') {
            		                $button_button_type = 'display : none;';
            		            } else {
            		                $button_button_type = 'display : block;';
            		            }

            		            $button_type = $arpricelite_default_settings->arp_button_type();

        		                $tablestring .= "<div class='column_opt_opts' style='" . $button_button_type . "'>";
    		                    
                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols' >" . esc_html__('Button Type', 'arprice-responsive-pricing-table') . "</div>";
    		                    
                                    $tablestring .= "<div class='column_opt_opts two_cols column_option_dropdown_alignment' >";
    		                    
                                        $tablestring .= "<input type='hidden' name='arp_global_button_type' id='arp_global_button_border_type' value='" . esc_html( $arp_global_button_border_type ) . "' />";
    		                    
                                        $tablestring .= "<dl id='arp_global_button_border_type' class='arp_selectbox' data-id='arp_global_button_border_type' data-name='arp_global_button_border_type' style='margin-top:18px;'>";

        		                            $button_type[$arp_global_button_border_type]['name'] = isset($button_type[$arp_global_button_border_type]['name'])?$button_type[$arp_global_button_border_type]['name']:'';

        		                            $tablestring .= "<dt><span>" . $button_type[$arp_global_button_border_type]['name'] . "</span><input type='text' style='display:none;' value='" . esc_html( $button_type[$arp_global_button_border_type]['name'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";

                                            $tablestring .= "<dd>";
    		                    
                                                $tablestring .= "<ul class='arp_global_button_border_type' data-id='arp_global_button_border_type'>";
    		                    
                                                    foreach ($button_type as $i => $value) {
        		                                        if ($i == 'shadow') {
        		                                            $tablestring .= "<li style='margin:0px' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $value['name'] ) . "'>" . esc_html( $value['name'] ) . "</li>";
        		                                        } else {
        		                                            $tablestring .= "<li class='arplite_restricted_view' style='margin:0px' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $value['name'] ) . "'>" . esc_html( $value['name'] ) . " <span class='pro_version_info'>(Pro Version)</span></li>";
        		                                        }
        		                                    }
    		                    
                                                $tablestring .= "</ul>";
        		                    
                                            $tablestring .= "</dd>";
        		                    
                                        $tablestring .= "</dl>";
        		                    
                                    $tablestring .= "</div>";
        		                
                                $tablestring .= "</div>";

        		                $tablestring .= "<div class='column_opt_opts' style='display:none;'>";

        		                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Border Width', 'arprice-responsive-pricing-table') . "</div>";

        		                    $tablestring .= "<div class='column_opt_opts two_cols' style='display:none;'>";

            		                    if (isset($column_settings['global_button_border_width'])) {
            		                        $arp_global_button_border_width = $column_settings['global_button_border_width'];
            		                    } else {
            		                        $arp_global_button_border_width = 0;
            		                    }

            		                    $tablestring .= "<input type='hidden' name='arp_global_button_border_width' id='arp_global_button_border_width' value='" . esc_html( $arp_global_button_border_width ) . "' />";

            		                    $tablestring .= "<dl id='arp_global_button_border_width' class='arp_selectbox' data-id='arp_global_button_border_width' data-name='arp_global_button_border_width' style='width:141px;margin-top:18px;margin-right:15px;float:right;'>";

            		                        $tablestring .= "<dt><span>" . esc_html( $arp_global_button_border_width ) . "</span><input type='text' style='display:none;' value='" . esc_html( $arp_global_button_border_width ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
        		
                                            $tablestring .= "<dd>";
    		    
                                                $tablestring .= "<ul class='arp_global_button_border_width' data-id='arp_global_button_border_width'>";

            		                                for ($i = 0; $i <= 10; $i++) {
            		                                    $tablestring .= "<li style='margin:0' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $i ) . "'>" . esc_html( $i ) . "</li>";
            		                                }
        		                    
                                                $tablestring .= "</ul>";
    		                        
                                            $tablestring .= "</dd>";
        		                    
                                        $tablestring .= "</dl>";
        		                
                                    $tablestring .= "</div>";

                		        $tablestring .= "</div>";

                		        $border_style = array('solid', 'dotted', 'dashed');
    		        
                                $tablestring .= "<div class='column_opt_opts'>";

                		            if (isset($column_settings['global_button_border_type'])) {
                		                $arp_global_button_border_style = $column_settings['global_button_border_type'];
                		            } else {
                		                $arp_global_button_border_style = esc_html__('solid', 'arprice-responsive-pricing-table');
                		            }

                                    $tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols' style='display:none;'>" . esc_html__('Border Style', 'arprice-responsive-pricing-table') . "</div>";

                		            $tablestring .= "<div class='column_opt_opts two_cols' style='display:none;'>";

                		                $tablestring .= "<input type='hidden' name='arp_global_button_border_style' id='arp_global_button_border_style' value='" . esc_html( $arp_global_button_border_style ) . "' />";

                		                $tablestring .= "<dl id='arp_global_button_border_style' class='arp_selectbox' data-id='arp_global_button_border_style' data-name='arp_global_button_border_style' style='width:141px;margin-top:18px;margin-right:15px;float:right;'>";

                		                    $tablestring .= "<dt><span>" . esc_html( $arp_global_button_border_style ) . "</span><input type='text' style='display:none;' value='" . esc_html( $arp_global_button_border_style ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
    		                
                		                    $tablestring .= "<dd>";
    		                        
                		                        $tablestring .= "<ul class='arp_global_button_border_style' data-id='arp_global_button_border_style'>";

                		                            foreach ($border_style as $i) {
                		                                $tablestring .= "<li style='margin:0px' class='arp_selectbox_option' data-value='" . esc_html( $i ) . "' data-label='" . esc_html( $i ) . "'>" . esc_html( $i ) . "</li>";
                		                            }
    		                        
                		                        $tablestring .= "</ul>";
    		                    
                		                    $tablestring .= "</dd>";
    		            
                		                $tablestring .= "</dl>";

                		            $tablestring .= "</div>";

                		        $tablestring .= "</div>";

                		        if (isset($column_settings['global_button_border_color']) && $column_settings['global_button_border_color']) {
                		            $arp_global_button_border_color = $column_settings['global_button_border_color'];
                		        } else {
                		            $arp_global_button_border_color = '#c9c9c9ff';
                		        }

                        		$tablestring .= "<div class='column_opt_opts' style='height: 50px;display:none;'>";

                        			$tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Border Color', 'arprice-responsive-pricing-table'). "</div>";

                        			$tablestring .= "<div class='column_opt_opts two_cols' style='margin-top:10px;'>";

                        				$tablestring .= "<div class='color_picker color_picker_round jscolor' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_global_button_border_color)\",valueElement:\"#arp_global_button_border_color_hidden\"}' jscolor-hash='true' jscolor-onInput='arp_update_color(this,arp_global_button_border_color)' jscolor-valueelement='\"#arp_global_button_border_color_hidden\"' data-id='arp_global_button_border_color_hidden' data-column-id='arp_global_button_border_color' id='arp_global_button_border_color' style='background:" . $arp_global_button_border_color . ";margin-left:0px;' data-color='" . $arp_global_button_border_color . "' ></div>";

                        			$tablestring .= "</div>";

                        			$tablestring .= "<input type='hidden' id='arp_global_button_border_color_hidden' name='arp_global_button_border_color' value='" . esc_html( $arp_global_button_border_color ) . "' />";

                        		$tablestring .= "</div>";

                		        if ($reference_template === 'arplitetemplate_26') {
                		            $button_border_radius = "display:none;";
                		        } else {
                		            $button_border_radius = "display:block;";
                		        }

                        		$tablestring .= "<div class='column_opt_opts' style='{$button_border_radius}'>";

                        			$tablestring .= "<div class='column_opt_label column_opt_sub_label  two_cols'>" . esc_html__('Border Radius', 'arprice-responsive-pricing-table') ."</div>";

                			        if (isset($column_settings['global_button_border_radius_top_left']) && $column_settings['global_button_border_radius_top_left'] != '') {
                			            $global_button_border_radius_top_left = $column_settings['global_button_border_radius_top_left'];
                			        } else {
                			            $global_button_border_radius_top_left = 0;
                			        }

                			        if (isset($column_settings['global_button_border_radius_top_right']) && $column_settings['global_button_border_radius_top_right'] != '') {
                			            $global_button_border_radius_top_right = $column_settings['global_button_border_radius_top_right'];
                			        } else {
                			            $global_button_border_radius_top_right = 0;
                			        }
                			        if (isset($column_settings['global_button_border_radius_bottom_left']) && $column_settings['global_button_border_radius_bottom_left'] != '') {
                			            $global_button_border_radius_bottom_left = $column_settings['global_button_border_radius_bottom_left'];
                			        } else {
                			            $global_button_border_radius_bottom_left = 0;
                			        }
                			        if (isset($column_settings['global_button_border_radius_bottom_right']) && $column_settings['global_button_border_radius_bottom_right'] != '') {
                			            $global_button_border_radius_bottom_right = $column_settings['global_button_border_radius_bottom_right'];
                			        } else {
                			            $global_button_border_radius_bottom_right = 0;
                			        }

                			        $tablestring .= "<div class='column_opt_opts two_cols column_chk_box_alignment'>";

                				        $tablestring .= "<div class='arp_button_radius_main'>";

                            				$tablestring .= "<div>";

                            					$tablestring .= "<span>".esc_html__('Left','arprice-responsive-pricing-table')."</span>";
            					
                            					$tablestring .= "<input type='text' id='global_button_border_radius_top_left' value='".esc_html( $global_button_border_radius_top_left ) ."' class='arp_tab_txt arp_tab_column_radius_txt' name='global_button_border_radius_top_left' onBlur=\"arp_update_button_border_radius(this.value,jQuery('#global_button_border_radius_top_right').val(),jQuery('#global_button_border_radius_bottom_right').val(), jQuery('#global_button_border_radius_bottom_left').val())\" />";

                    						$tablestring .= "</div>";

                    				        $tablestring .= "<div>";

                    					        $tablestring .= "<span>".esc_html__('Right','arprice-responsive-pricing-table')."</span>";
        					    
                    					        $tablestring .= "<input type='text' id='global_button_border_radius_top_right' value='".esc_html( $global_button_border_radius_top_right ) ."' class='arp_tab_txt arp_tab_column_radius_txt' name='global_button_border_radius_top_right' onBlur=\"arp_update_button_border_radius(jQuery('#global_button_border_radius_top_left').val(),this.value,jQuery('#global_button_border_radius_bottom_right').val(), jQuery('#global_button_border_radius_bottom_left').val())\" />";

                    				        $tablestring .= "</div>";

                            				$tablestring .= "<div>";

                            					$tablestring .= "<span>".esc_html__('Left','arprice-responsive-pricing-table')."</span>";

                            					$tablestring .= "<input type='text' id='global_button_border_radius_bottom_left' value='".esc_html( $global_button_border_radius_bottom_left ) ."' class='arp_tab_txt arp_tab_column_radius_txt' name='global_button_border_radius_bottom_left' onBlur=\"arp_update_button_border_radius(jQuery('#global_button_border_radius_top_left').val(), jQuery('#global_button_border_radius_top_right').val(), jQuery('#global_button_border_radius_bottom_right').val(), this.value)\" />";

                            				$tablestring .= "</div>";

                    				        $tablestring .= "<div>";

                    					        $tablestring .= "<span>".esc_html__('Right','arprice-responsive-pricing-table')."</span>";
        	
                    					        $tablestring .= "<input type='text' id='global_button_border_radius_bottom_right' value='".esc_html( $global_button_border_radius_bottom_right)."' class='arp_tab_txt arp_tab_column_radius_txt' name='global_button_border_radius_bottom_right' onBlur=\"arp_update_button_border_radius(jQuery('#global_button_border_radius_top_left').val(), jQuery('#global_button_border_radius_top_right').val(), this.value, jQuery('#global_button_border_radius_bottom_left').val())\" />";

                    				        $tablestring .= "</div>";

                            			$tablestring .= "</div>";


                           				$tablestring .= "<div class='arp_button_radius_main'>";
                				
                            				$tablestring .= "<div class='arp_column_radius_bottom'>";

                            					$tablestring .= "<span>".esc_html__('Top','arprice-responsive-pricing-table')."</span>";
                				
                            				$tablestring .= "</div>";

                            				$tablestring .= "<div class='arp_column_radius_bottom'>";

                            					$tablestring .= "<span>".esc_html__('Bottom','arprice-responsive-pricing-table')."</span>";
                				
                            				$tablestring .= "</div>";
                			
                            			$tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";
                            
                            /* Button Customization */

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    /* Column Options End */

                    /* Column Effects Start */

                    $tablestring .= "<div class='general_animation_tab enable global_opts' id='column_effects' >";

                        $tablestring .= "<div class='animation_dropdown'>";

                            $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__("Effects", 'arprice-responsive-pricing-table') . "&nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                            $tablestring .= "<div class='column_option_animation_dropdown' id='column_option_animation_dropdown'>";

                                $tablestring .= "<img id='arplite_restricted_section' src='" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/effect.png' />";

                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    /* Column Effects End */

                    /* Column Tooltip Start */

                    $tablestring .= "<div class='general_tooltip_tab enable global_opts' id='tootip_options' >";

                        $tablestring .= "<div class='tooltip_dropdown'>";

                            $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__('Tooltip', 'arprice-responsive-pricing-table') . "&nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                            $tablestring .= "<div class='column_option_tooltip_dropdown' id='column_option_tooltip_dropdown'>";

                                $tablestring .= "<img id='arplite_restricted_section' src='" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/tooltip.png' />";

                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    /* Column Tooltip End */

                    /* Custom CSS Start */

                    $tablestring .= "<div class='general_custom_css_tab enable global_opts' id='custom_css_options' >";

                        $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__('Custom CSS', 'arprice-responsive-pricing-table') . "&nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                        $tablestring .= "<div class='custom_css_dropdown'>";

                            $tablestring .= "<div class='column_opt_label_div two_column'>";

                                $tablestring .= "<div class='column_opt_label_div'>" . esc_html__('Enter css class and style', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_content_light_row column_opt_row '>";

                                    $tablestring .= "<div class='arp_custom_css_wrapper'>";

                                        $tablestring .= "<textarea class='arp_custom_css'></textarea>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div style='width:100%; float:left; margin:8px 0 0 5px;font-size:13px;'><span style='font-weight:normal; margin-right:6px;'>(e.g.) .btn{color:#000000;}</span></div>";

                                    $tablestring .= "<button id='arp_custom_css_btn' style='float:left; margin:14px 0 5px 0;' class='col_opt_btn arplite_restricted_view' type='button'>" . esc_html__('Apply To Editor', 'arprice-responsive-pricing-table') . "</button>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_content_dark_row column_opt_row arp_no_border'>";

                                    $tablestring .= "<div class='column_opt_label two_cols'>" . esc_html__('CSS class info', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='column_opt_opts two_cols align_right'>";

                                    $tablestring .= "<span class='arp_switch_wrapper arp_align_right'>";

                                        $tablestring .= "<input type='checkbox' id='css_debug_mode' value='1' class='css_debug_mode arp_switch arplite_restricted_view' name='arp_css_debug_mode' />";

                                    $tablestring .= "<span></span>";
                                    
                                    $tablestring .= "</span>"; 

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='column_opt_label' style='box-sizing: border-box;float: left; width: 100%;white-space:pre-wrap;' >";

                                        $tablestring .= "<span class='column_opt_label_help' style='line-height:normal;margin:auto;'>" . esc_html__('When you turn ON CSS Class Info, You will get an extra button by clicking on each column. By clicking on that, you will get all css class information for that particular column.', 'arprice-responsive-pricing-table') . "</span>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    /* Custom CSS End */


                    /* Toggle Price Start */

                    $tablestring .= "<div class='general_toggle_options_tab enable global_opts' id='toggle_content_options' >";

                        $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__('Toggle Price', 'arprice-responsive-pricing-table') . "&nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                        $tablestring .= "<div class='toggle_options_dropdown' style='padding-left:0;'>";
                            
                            $tablestring .= "<img id='arplite_restricted_section' width='330' src='" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/toggle_price.png' />";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    /* Toggle Price End */

                    /* Font Start */

                    $tablestring .= "<div class='general_toggle_options_tab enable global_opts' id='all_font_options'>";
                        
                        $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__('Font Settings', 'arprice-responsive-pricing-table') . "</div>";
                        
                        $tablestring .= "<div class='font_settings_options_dropdown'>";

                            /*common font settings*/
                            
                            $arp_common_font_family_global = isset($general_option['column_settings']['arp_common_font_family_global']) ? $general_option['column_settings']['arp_common_font_family_global'] : 'Helvetica';

                            $tablestring .= "<div class='column_content_light_row column_opt_row'>";

                                $tablestring .= "<div class='column_opt_label arp_fix_height'><div class='arp_options_group_title' style='padding:0'>" . esc_html__('Set Default Font', 'arprice-responsive-pricing-table') . "</div></div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_family arp_common_font_family_opts'>";

                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";
                                        
                                        $tablestring .= "<input type='hidden' id='arp_common_font_family_global' name='arp_common_font_family_global' value='" . esc_html( $arp_common_font_family_global ) . "' />";

                                        $tablestring .= "<dl class='arp_selectbox arp_font_option_dd' id='arp_common_font_font_family_dd' data-name='arp_common_font_font_family_dd' data-id='arp_common_font_family_global' style='width:90% !important;'>";

                                            if ($arp_common_font_family_global){
                                                $arp_selectbox_placeholder = $arp_common_font_family_global;
                                            } else {
                                                $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');
                                            }
                                            if( $arp_selectbox_placeholder == 'inherit' ){
                                                $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                            }

                                            $tablestring .= "<dt><span>" . $arp_selectbox_placeholder . "</span><input type='text' style='display:none;' value='" . esc_html( $arp_common_font_family_global ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                            
                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='arp_common_font_family_global'>";

                                                    $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";
            
                                                    $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
            
                                                    $tablestring .= $default_fonts_string;

                                                    $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive' data-id='google_fonts_wrapper'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

                                        $common_font_google_note_style = 'display:block;';
                                        if( !in_array($arp_common_font_family_global, $google_fonts) ){
                                            $common_font_google_note_style = 'display:none;';
                                        }

                                        $tablestring .= "<div class='arp_google_font_preview_note' style='margin-right:42px;$common_font_google_note_style'><a target='_blank'  class='arp_google_font_preview_link' id='arp_common_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $arp_common_font_family_global . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            /*common font settings over*/

                            $arp_font_settings = $arpricelite_default_settings->arp_font_settings();

                            $arp_font_settings = $arp_font_settings[$reference_template];

                            if (in_array('arp_header_font', $arp_font_settings)) {
                                $arp_header_style = 'display:block;';
                            } else {
                                $arp_header_style = 'display:none;';
                            }

                            /* header font settings */
                            
                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='" . $arp_header_style . "border-bottom: none;'>";

                                $tablestring .= "<div class='column_opt_label arp_fix_height'><div class='arp_options_group_title' style='padding: 0px 0px 16px 0px;'>" . esc_html__('Customize Font Sectionwise', 'arprice-responsive-pricing-table') . "</div></div>";

                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' style='padding:0';>" . esc_html__('Header Fonts', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";

                                    $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";

                                        $tablestring .= "<input type='hidden' id='header_font_family_global' class='arp_custom_font_family_options' name='header_font_family_global' value='" .esc_html(  $general_option['column_settings']['header_font_family_global'] ) . "' />";

                                        $tablestring .= "<dl class='arp_selectbox' id='header_font_font_family_dd' data-name='header_font_font_family_dd' data-id='header_font_family_global' style=''>";

                                            if ($general_option['column_settings']['header_font_family_global']){
                                                $arp_selectbox_placeholder = $general_option['column_settings']['header_font_family_global'];
                                            } else {
                                                $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');
                                            }
                                            if( $arp_selectbox_placeholder == 'inherit' ){
                                                $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                            }

                                            $tablestring .= "<dt><span>" . $arp_selectbox_placeholder . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['header_font_family_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='header_font_family_global'>";

                                                    $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";

                                                    $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
            
                                                    $tablestring .= $default_fonts_string;

                                                    $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";
            
                                        $header_font_google_note_style = 'display:block;';

                                        if( !in_array($arp_selectbox_placeholder, $google_fonts) ){
                                            $header_font_google_note_style = 'display:none;';
                                        }
            
                                        $tablestring .= "<div class='arp_google_font_preview_note' style='".$header_font_google_note_style."'><a target='_blank'  class='arp_google_font_preview_link' id='header_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $general_option['column_settings']['header_font_family_global'] . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";
                                    
                                    $tablestring .= "</div>";
                                
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";

                                    $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";
                                    
                                    $tablestring .= "<div class='font_title_font_family_div'>";
                                        
                                        $tablestring .= "<input type='hidden' id='header_font_size_global'  name='header_font_size_global' value='" . esc_html( $general_option['column_settings']['header_font_size_global'] ) . "' />";
                                        
                                        $tablestring .= "<dl class='arp_selectbox header_font_size_global_dd' data-name='header_font_size_global' data-id='header_font_size_global' style='width : 80% !important;' >";

                                            $tablestring .= "<dt><span>" . $general_option['column_settings']['header_font_size_global'] . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['header_font_size_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                                
                                            $tablestring .= "<dd>";

                                                $size_arr = array();

                                                $tablestring .= "<ul data-id='header_font_size_global'>";

                                                    for ($s = 8; $s <= 20; $s++){
                                                        $size_arr[] = $s;
                                                    }
                                                    for ($st = 22; $st <= 70; $st+=2){
                                                        $size_arr[] = $st;
                                                    }

                                                    foreach ($size_arr as $size) {
                                                        $tablestring .= "<li data-value='" . esc_html( $size ) . "' data-label='" . esc_html( $size ) . "'>" . esc_html( $size ) . "</li>";
                                                    }

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_align'>";
                                    $header_text_align = isset($general_option['column_settings']['arp_header_text_alignment']) ? $general_option['column_settings']['arp_header_text_alignment'] : 'center';
                                    $tablestring .= $arpricelite_form->arp_create_alignment_div_new('header_text_alignment', $header_text_align, 'arp_header_text_alignment', '', 'header_section');
                                $tablestring .= "</div>";

                                if ($general_option['column_settings']['arp_header_text_bold_global'] == 'bold') {
                                    $header_title_style_bold_selected = 'selected';
                                } else {
                                    $header_title_style_bold_selected = '';
                                }

                                //check selected for italic
                                if ($general_option['column_settings']['arp_header_text_italic_global'] == 'italic') {
                                    $header_title_style_italic_selected = 'selected';
                                } else {
                                    $header_title_style_italic_selected = '';
                                }

                                //check selected for underline or line-through
                                if ($general_option['column_settings']['arp_header_text_decoration_global'] == 'underline') {
                                    $header_title_style_underline_selected = 'selected';
                                } else {
                                    $header_title_style_underline_selected = '';
                                }

                                if ($general_option['column_settings']['arp_header_text_decoration_global'] == 'line-through') {
                                    $header_title_style_linethrough_selected = 'selected';
                                } else {
                                    $header_title_style_linethrough_selected = '';
                                }

                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_header_text_style_global'>";

                                    $tablestring .= "<div class='font_title_font_family_div' data-level = 'header_level_options' level-id='header_button_global'>";

                                        $tablestring .= "<div class='arp_style_btn " . $header_title_style_bold_selected . " arptooltipster' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                        $tablestring .= "<i class='fas fa-bold'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn " . $header_title_style_italic_selected . " arptooltipster' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                        $tablestring .= "<i class='fas fa-italic'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn " . $header_title_style_underline_selected . " arptooltipster' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                        $tablestring .= "<i class='fas fa-underline'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn " . $header_title_style_linethrough_selected . " arptooltipster' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                        $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<input type='hidden' id='header_style_bold_global' name='header_style_bold_global' value='" . esc_html( $general_option['column_settings']['arp_header_text_bold_global'] ) . "' /> ";
                                        $tablestring .= "<input type='hidden' id='header_style_italic_global' name='header_style_italic_global' value='" . esc_html( $general_option['column_settings']['arp_header_text_italic_global'] ) . "' /> ";
                                        $tablestring .= "<input type='hidden' id='header_style_decoration_global' name='header_style_decoration_global' value='" . esc_html( $general_option['column_settings']['arp_header_text_decoration_global'] ) . "' /> ";
            
                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";
            
                            $tablestring .= "</div>";
                            
                            /* header font settings */

                            if (in_array('arp_desc_font', $arp_font_settings)) {
                                $arp_header_style = 'display:block;';
                            } else {
                                $arp_header_style = 'display:none;';
                            }

                            /* Desc font settings */
                            $tablestring .= "<div class='column_content_light_row arp_no_border column_opt_row' style='" . $arp_header_style . "'>";
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' style='padding:0';>" . esc_html__('Description Fonts', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='description_font_family_global' class='arp_custom_font_family_options' name='description_font_family_global' value='" . esc_html( $general_option['column_settings']['description_font_family_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox' id='description_font_font_family_dd' data-name='description_font_font_family_dd' data-id='description_font_family_global' style=''>";
                                if ($general_option['column_settings']['description_font_family_global'])
                                    $arp_selectbox_placeholder = $general_option['column_settings']['description_font_family_global'];
                                else
                                    $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');

                                if( $arp_selectbox_placeholder == 'inherit' ){
                                    $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                }

                                $tablestring .= "<dt><span>" . $arp_selectbox_placeholder . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['description_font_family_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";
                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='description_font_family_global'>";

                                $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";
                                
                                $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
                                
                                $tablestring .= $default_fonts_string;

                                $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";


                                $tablestring .= "</ul>";

                                $tablestring .= "</dd>";

                                $tablestring .= "</dl>";

                                $description_font_google_note_style = 'display:block;';
                                if( !in_array($arp_selectbox_placeholder, $google_fonts) ){
                                    $description_font_google_note_style = 'display:none;';
                                }

                                $tablestring .= "<div class='arp_google_font_preview_note' style='".$description_font_google_note_style."'><a target='_blank'  class='arp_google_font_preview_link' id='description_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $general_option['column_settings']['description_font_family_global'] . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='description_font_size_global'  name='description_font_size_global' value='" . esc_html( $general_option['column_settings']['description_font_size_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox description_font_size_global_dd' data-name='description_font_size_global' data-id='description_font_size_global' style='width : 80% !important;' >";
                                $tablestring .= "<dt><span>" . $general_option['column_settings']['description_font_size_global'] . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['description_font_size_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";

                                $size_arr = array();

                                $tablestring .= "<ul data-id='description_font_size_global'>";

                                for ($s = 8; $s <= 20; $s++)
                                    $size_arr[] = $s;
                                for ($st = 22; $st <= 70; $st+=2)
                                    $size_arr[] = $st;

                                foreach ($size_arr as $size) {
                                    $tablestring .= "<li data-value='" . esc_html( $size ) . "' data-label='" . esc_html( $size ) . "'>" . esc_html( $size ) . "</li>";
                                }
                                $tablestring .= "</ul>";
                                $tablestring .= "</dd>";
                                $tablestring .= "</dl>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_align'>";
                                $description_text_alignment = isset($general_option['column_settings']['arp_description_text_alignment']) ? $general_option['column_settings']['arp_description_text_alignment'] : 'center';
                                $tablestring .= $arpricelite_form->arp_create_alignment_div_new('description_text_alignment', $description_text_alignment, 'arp_description_text_alignment', '', 'column_description_section');
                                $tablestring .= "</div>";

                                if ($general_option['column_settings']['arp_description_text_bold_global'] == 'bold') {
                                    $description_title_style_bold_selected = 'selected';
                                } else {
                                    $description_title_style_bold_selected = '';
                                }

                                //check selected for italic
                                if ($general_option['column_settings']['arp_description_text_italic_global'] == 'italic') {
                                    $description_title_style_italic_selected = 'selected';
                                } else {
                                    $description_title_style_italic_selected = '';
                                }

                                //check selected for underline or line-through
                                if ($general_option['column_settings']['arp_description_text_decoration_global'] == 'underline') {
                                    $description_title_style_underline_selected = 'selected';
                                } else {
                                    $description_title_style_underline_selected = '';
                                }

                                if ($general_option['column_settings']['arp_description_text_decoration_global'] == 'line-through') {
                                    $description_title_style_linethrough_selected = 'selected';
                                } else {
                                    $description_title_style_linethrough_selected = '';
                                }
                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_description_text_style_global'>";
                                $tablestring .= "<div class='font_title_font_family_div' data-level = 'description_level_options' level-id='description_button_global'>";

                                $tablestring .= "<div class='arp_style_btn " . $description_title_style_bold_selected . " arptooltipster' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                $tablestring .= "<i class='fas fa-bold'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $description_title_style_italic_selected . " arptooltipster' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                $tablestring .= "<i class='fas fa-italic'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $description_title_style_underline_selected . " arptooltipster' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                $tablestring .= "<i class='fas fa-underline'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn " . $description_title_style_linethrough_selected . " arptooltipster' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                $tablestring .= "</div>";
                                $tablestring .= "<input type='hidden' id='description_style_bold_global' name='description_style_bold_global' value='" . esc_html( $general_option['column_settings']['arp_description_text_bold_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='description_style_italic_global' name='description_style_italic_global' value='" . esc_html( $general_option['column_settings']['arp_description_text_italic_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='description_style_decoration_global' name='description_style_decoration_global' value='" . esc_html( $general_option['column_settings']['arp_description_text_decoration_global'] ) . "' /> ";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            /* Desc font settings */


                            if (in_array('arp_price_font', $arp_font_settings)) {
                                $arp_header_style = 'display:block;';
                            } else {
                                $arp_header_style = 'display:none;';
                            }

                            /* price font settings */
                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='" . $arp_header_style . "border-bottom: none;'>";
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' style='padding:0';>" . esc_html__('Pricing Fonts', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='price_font_family_global' class='arp_custom_font_family_options' name='price_font_family_global' value='" . esc_html( $general_option['column_settings']['price_font_family_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox' id='price_font_font_family_dd' data-name='price_font_font_family_dd' data-id='price_font_family_global' style=''>";
                                if ($general_option['column_settings']['price_font_family_global'])
                                    $arp_selectbox_placeholder = esc_html( $general_option['column_settings']['price_font_family_global'] );
                                else
                                    $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');

                                if( $arp_selectbox_placeholder == 'inherit' ){
                                    $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                }

                                $tablestring .= "<dt><span>" . $arp_selectbox_placeholder . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['price_font_family_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";
                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='price_font_family_global'>";

                                $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";
                                
                                $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
                                
                                $tablestring .= $default_fonts_string;

                                $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                $tablestring .= "</ul>";

                                $tablestring .= "</dd>";

                                $tablestring .= "</dl>";

                                $price_font_google_note_style = 'display:block;';
                                if( !in_array($arp_selectbox_placeholder, $google_fonts) ){
                                    $price_font_google_note_style = 'display:none;';
                                }

                                $tablestring .= "<div class='arp_google_font_preview_note' style='".$price_font_google_note_style."'><a target='_blank'  class='arp_google_font_preview_link' id='price_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $general_option['column_settings']['price_font_family_global'] . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='price_font_size_global'  name='price_font_size_global' value='" . esc_html( $general_option['column_settings']['price_font_size_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox price_font_size_global_dd' data-name='price_font_size_global' data-id='price_font_size_global' style='width : 80% !important;' >";
                                $tablestring .= "<dt><span>" . esc_html( $general_option['column_settings']['price_font_size_global'] ) . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['price_font_size_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";

                                $size_arr = array();

                                $tablestring .= "<ul data-id='price_font_size_global'>";

                                for ($s = 8; $s <= 20; $s++)
                                    $size_arr[] = $s;
                                for ($st = 22; $st <= 70; $st+=2)
                                    $size_arr[] = $st;

                                foreach ($size_arr as $size) {
                                    $tablestring .= "<li data-value='" . esc_html( $size ) . "' data-label='" . esc_html( $size ) . "'>" . esc_html( $size ) . "</li>";
                                }
                                $tablestring .= "</ul>";
                                $tablestring .= "</dd>";
                                $tablestring .= "</dl>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_align'>";
                                $price_text_alignment = isset($general_option['column_settings']['arp_price_text_alignment']) ? $general_option['column_settings']['arp_price_text_alignment'] : 'center';
                                $tablestring .= $arpricelite_form->arp_create_alignment_div_new('price_text_alignment', $price_text_alignment, 'arp_price_text_alignment', '', 'pricing_section');
                                $tablestring .= "</div>";

                                if ($general_option['column_settings']['arp_price_text_bold_global'] == 'bold') {
                                    $price_title_style_bold_selected = 'selected';
                                } else {
                                    $price_title_style_bold_selected = '';
                                }

                                //check selected for italic
                                if ($general_option['column_settings']['arp_price_text_italic_global'] == 'italic') {
                                    $price_title_style_italic_selected = 'selected';
                                } else {
                                    $price_title_style_italic_selected = '';
                                }

                                //check selected for underline or line-through
                                if ($general_option['column_settings']['arp_price_text_decoration_global'] == 'underline') {
                                    $price_title_style_underline_selected = 'selected';
                                } else {
                                    $price_title_style_underline_selected = '';
                                }

                                if ($general_option['column_settings']['arp_price_text_decoration_global'] == 'line-through') {
                                    $price_title_style_linethrough_selected = 'selected';
                                } else {
                                    $price_title_style_linethrough_selected = '';
                                }
                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_price_text_style_global'>";

                                $tablestring .= "<div class='font_title_font_family_div' data-level = 'price_level_options' level-id='price_button_global'>";

                                $tablestring .= "<div class='arp_style_btn " . $price_title_style_bold_selected . " arptooltipster' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                $tablestring .= "<i class='fas fa-bold'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $price_title_style_italic_selected . " arptooltipster' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                $tablestring .= "<i class='fas fa-italic'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $price_title_style_underline_selected . " arptooltipster' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                $tablestring .= "<i class='fas fa-underline'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn " . $price_title_style_linethrough_selected . " arptooltipster' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                $tablestring .= "</div>";
                                $tablestring .= "<input type='hidden' id='price_style_bold_global' name='price_style_bold_global' value='" . esc_html( $general_option['column_settings']['arp_price_text_bold_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='price_style_italic_global' name='price_style_italic_global' value='" . esc_html( $general_option['column_settings']['arp_price_text_italic_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='price_style_decoration_global' name='price_style_decoration_global' value='" . esc_html( $general_option['column_settings']['arp_price_text_decoration_global'] ) . "' /> ";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            /* Price font settings */


                            if (in_array('arp_body_font', $arp_font_settings)) {
                                $arp_header_style = 'display:block;';
                            } else {
                                $arp_header_style = 'display:none;';
                            }

                            /* body font settings */
                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='" . $arp_header_style . "border-bottom: none;'>";
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' style='padding:0';>" . esc_html__('Body Fonts', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='body_font_family_global' class='arp_custom_font_family_options' name='body_font_family_global' value='" . esc_html( $general_option['column_settings']['body_font_family_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox' id='body_font_font_family_dd' data-name='body_font_font_family_dd' data-id='body_font_family_global' style=''>";
                                if ($general_option['column_settings']['body_font_family_global'])
                                    $arp_selectbox_placeholder = $general_option['column_settings']['body_font_family_global'];
                                else
                                    $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');

                                if( $arp_selectbox_placeholder == 'inherit' ){
                                    $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                }

                                $tablestring .= "<dt><span>" . esc_html( $arp_selectbox_placeholder ) . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['body_font_family_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";
                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='body_font_family_global'>";

                                $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";
                                
                                $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
                                
                                $tablestring .= $default_fonts_string;

                                $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";


                                $tablestring .= "</ul>";

                                $tablestring .= "</dd>";

                                $tablestring .= "</dl>";

                                $body_font_google_note_style = 'display:block;';
                                if( !in_array($arp_selectbox_placeholder, $google_fonts) ){
                                    $body_font_google_note_style = 'display:none;';
                                }

                                $tablestring .= "<div class='arp_google_font_preview_note' style='".$body_font_google_note_style."'><a target='_blank'  class='arp_google_font_preview_link' id='body_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $general_option['column_settings']['body_font_family_global'] . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='body_font_size_global'  name='body_font_size_global' value='" . esc_html( $general_option['column_settings']['body_font_size_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox body_font_size_global_dd' data-name='body_font_size_global' data-id='body_font_size_global' style='width : 80% !important;' >";
                                $tablestring .= "<dt><span>" . esc_html( $general_option['column_settings']['body_font_size_global'] ) . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['body_font_size_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";

                                $size_arr = array();

                                $tablestring .= "<ul data-id='body_font_size_global'>";

                                for ($s = 8; $s <= 20; $s++)
                                    $size_arr[] = $s;
                                for ($st = 22; $st <= 70; $st+=2)
                                    $size_arr[] = $st;

                                foreach ($size_arr as $size) {
                                    $tablestring .= "<li data-value='" . esc_html( $size ) . "' data-label='" . esc_html( $size ) . "'>" . esc_html( $size ) . "</li>";
                                }
                                $tablestring .= "</ul>";
                                $tablestring .= "</dd>";
                                $tablestring .= "</dl>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_align'>";
                                $body_text_alignment = isset($general_option['column_settings']['arp_body_text_alignment']) ? $general_option['column_settings']['arp_body_text_alignment'] : 'center';
                                $tablestring .= $arpricelite_form->arp_create_alignment_div_new('body_text_alignment', $body_text_alignment, 'arp_body_text_alignment', '', 'body_section');
                                $tablestring .= "</div>";

                                if ($general_option['column_settings']['arp_body_text_bold_global'] == 'bold') {
                                    $body_title_style_bold_selected = 'selected';
                                } else {
                                    $body_title_style_bold_selected = '';
                                }

                                //check selected for italic
                                if ($general_option['column_settings']['arp_body_text_italic_global'] == 'italic') {
                                    $body_title_style_italic_selected = 'selected';
                                } else {
                                    $body_title_style_italic_selected = '';
                                }

                                //check selected for underline or line-through
                                if ($general_option['column_settings']['arp_body_text_decoration_global'] == 'underline') {
                                    $body_title_style_underline_selected = 'selected';
                                } else {
                                    $body_title_style_underline_selected = '';
                                }

                                if ($general_option['column_settings']['arp_body_text_decoration_global'] == 'line-through') {
                                    $body_title_style_linethrough_selected = 'selected';
                                } else {
                                    $body_title_style_linethrough_selected = '';
                                }
                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_body_text_style_global'>";
                                $tablestring .= "<div class='font_title_font_family_div' data-level = 'body_level_options' level-id='body_button_global'>";

                                $tablestring .= "<div class='arp_style_btn " . $body_title_style_bold_selected . " arptooltipster' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                $tablestring .= "<i class='fas fa-bold'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $body_title_style_italic_selected . " arptooltipster' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                $tablestring .= "<i class='fas fa-italic'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $body_title_style_underline_selected . " arptooltipster' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                $tablestring .= "<i class='fas fa-underline'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn " . $body_title_style_linethrough_selected . " arptooltipster' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                $tablestring .= "</div>";
                                $tablestring .= "<input type='hidden' id='body_style_bold_global' name='body_style_bold_global' value='" . esc_html( $general_option['column_settings']['arp_body_text_bold_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='body_style_italic_global' name='body_style_italic_global' value='" . esc_html( $general_option['column_settings']['arp_body_text_italic_global'] ). "' /> ";
                                $tablestring .= "<input type='hidden' id='body_style_decoration_global' name='body_style_decoration_global' value='" . esc_html( $general_option['column_settings']['arp_body_text_decoration_global'] ) . "' /> ";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            /* body font settings */


                            if (in_array('arp_footer_font', $arp_font_settings)) {
                                $arp_header_style = 'display:block;';
                            } else {
                                $arp_header_style = 'display:none;';
                            }

                            /* footer font settings */
                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='" . $arp_header_style . "border-bottom: none;'>";
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height'>" . esc_html__('Footer Fonts', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='footer_font_family_global' class='arp_custom_font_family_options' name='footer_font_family_global' value='" . esc_html( $general_option['column_settings']['footer_font_family_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox' id='footer_font_font_family_dd' data-name='footer_font_font_family_dd' data-id='footer_font_family_global' style=''>";
                                if ($general_option['column_settings']['footer_font_family_global'])
                                    $arp_selectbox_placeholder = $general_option['column_settings']['footer_font_family_global'];
                                else
                                    $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');

                                if( $arp_selectbox_placeholder == 'inherit' ){
                                    $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                }

                                $tablestring .= "<dt><span>" . $arp_selectbox_placeholder . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['footer_font_family_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";
                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='footer_font_family_global'>";

                                $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";
                                
                                $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
                                
                                $tablestring .= $default_fonts_string;

                                $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                $tablestring .= "</ul>";

                                $tablestring .= "</dd>";

                                $tablestring .= "</dl>";

                                $footer_font_google_note_style = 'display:block;';
                                if( !in_array($arp_selectbox_placeholder, $google_fonts) ){
                                    $footer_font_google_note_style = 'display:none;';
                                }

                                $tablestring .= "<div class='arp_google_font_preview_note' style='".$footer_font_google_note_style."'><a target='_blank'  class='arp_google_font_preview_link' id='footer_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $general_option['column_settings']['footer_font_family_global'] . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='footer_font_size_global'  name='footer_font_size_global' value='" . esc_html( $general_option['column_settings']['footer_font_size_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox footer_font_size_global_dd' data-name='footer_font_size_global' data-id='footer_font_size_global' style='width : 80% !important;' >";
                                $tablestring .= "<dt><span>" . esc_html( $general_option['column_settings']['footer_font_size_global'] ) . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['footer_font_size_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";

                                $size_arr = array();

                                $tablestring .= "<ul data-id='footer_font_size_global'>";

                                for ($s = 8; $s <= 20; $s++)
                                    $size_arr[] = $s;
                                for ($st = 22; $st <= 70; $st+=2)
                                    $size_arr[] = $st;

                                foreach ($size_arr as $size) {
                                    $tablestring .= "<li data-value='" . esc_html( $size ) . "' data-label='" . esc_html( $size ) . "'>" . esc_html( $size ) . "</li>";
                                }
                                $tablestring .= "</ul>";
                                $tablestring .= "</dd>";
                                $tablestring .= "</dl>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_align'>";
                                $footer_text_alignment = isset($general_option['column_settings']['arp_footer_text_alignment']) ? $general_option['column_settings']['arp_footer_text_alignment'] : 'center';
                                $tablestring .= $arpricelite_form->arp_create_alignment_div_new('footer_text_alignment', $footer_text_alignment, 'arp_footer_text_alignment', '', 'footer_section');
                                $tablestring .= "</div>";

                                if ($general_option['column_settings']['arp_footer_text_bold_global'] == 'bold') {
                                    $footer_title_style_bold_selected = 'selected';
                                } else {
                                    $footer_title_style_bold_selected = '';
                                }

                                //check selected for italic
                                if ($general_option['column_settings']['arp_footer_text_italic_global'] == 'italic') {
                                    $footer_title_style_italic_selected = 'selected';
                                } else {
                                    $footer_title_style_italic_selected = '';
                                }

                                //check selected for underline or line-through
                                if ($general_option['column_settings']['arp_footer_text_decoration_global'] == 'underline') {
                                    $footer_title_style_underline_selected = 'selected';
                                } else {
                                    $footer_title_style_underline_selected = '';
                                }

                                if ($general_option['column_settings']['arp_footer_text_decoration_global'] == 'line-through') {
                                    $footer_title_style_linethrough_selected = 'selected';
                                } else {
                                    $footer_title_style_linethrough_selected = '';
                                }
                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_footer_text_style_global'>";
                                $tablestring .= "<div class='font_title_font_family_div' data-level = 'footer_level_options' level-id='footer_button_global'>";

                                $tablestring .= "<div class='arp_style_btn " . $footer_title_style_bold_selected . " arptooltipster' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                $tablestring .= "<i class='fas fa-bold'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $footer_title_style_italic_selected . " arptooltipster' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                $tablestring .= "<i class='fas fa-italic'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $footer_title_style_underline_selected . " arptooltipster' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                $tablestring .= "<i class='fas fa-underline'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn " . $footer_title_style_linethrough_selected . " arptooltipster' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                $tablestring .= "</div>";
                                $tablestring .= "<input type='hidden' id='footer_style_bold_global' name='footer_style_bold_global' value='" . esc_html( $general_option['column_settings']['arp_footer_text_bold_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='footer_style_italic_global' name='footer_style_italic_global' value='" . esc_html( $general_option['column_settings']['arp_footer_text_italic_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='footer_style_decoration_global' name='footer_style_decoration_global' value='" . esc_html( $general_option['column_settings']['arp_footer_text_decoration_global'] ) . "' /> ";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            /* footer font settings */


                            if (in_array('arp_button_font', $arp_font_settings)) {
                                $arp_header_style = 'display:block;';
                            } else {
                                $arp_header_style = 'display:none;';
                            }

                            /* button font settings */
                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='" . $arp_header_style . "border-bottom: none;'>";
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' style='padding:0';>" . esc_html__('Button Fonts', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='button_font_family_global' class='arp_custom_font_family_options' name='button_font_family_global' value='" . esc_html( $general_option['column_settings']['button_font_family_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox' id='button_font_font_family_dd' data-name='button_font_font_family_dd' data-id='button_font_family_global' style=''>";
                                if ($general_option['column_settings']['button_font_family_global'])
                                    $arp_selectbox_placeholder = $general_option['column_settings']['button_font_family_global'];
                                else
                                    $arp_selectbox_placeholder = esc_html__('Choose Option', 'arprice-responsive-pricing-table');

                                if( $arp_selectbox_placeholder == 'inherit' ){
                                    $arp_selectbox_placeholder = esc_html__( 'Inherit from Theme', 'arprice-responsive-pricing-table' );
                                }

                                $tablestring .= "<dt><span>" . $arp_selectbox_placeholder . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['button_font_family_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";

                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='button_font_family_global'>";

                                $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";

                                $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
                                
                                $tablestring .= $default_fonts_string;

                                $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                $tablestring .= "</ul>";

                                $tablestring .= "</dd>";

                                $tablestring .= "</dl>";

                                $button_font_google_note_style = 'display:block;';
                                if( !in_array($arp_selectbox_placeholder, $google_fonts) ){
                                    $button_font_google_note_style = 'display:none;';
                                }

                                $tablestring .= "<div class='arp_google_font_preview_note' style='".$button_font_google_note_style."'><a target='_blank'  class='arp_google_font_preview_link' id='button_font_family_global_font_family_preview' href='" . $googlefontpreviewurl . $general_option['column_settings']['button_font_family_global'] . "'>" . esc_html__('Font Preview', 'arprice-responsive-pricing-table') . "</a></div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";
                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";
                                $tablestring .= "<div class='font_title_font_family_div'>";
                                $tablestring .= "<input type='hidden' id='button_font_size_global'  name='button_font_size_global' value='" . esc_html( $general_option['column_settings']['button_font_size_global'] ) . "' />";
                                $tablestring .= "<dl class='arp_selectbox button_font_size_global_dd' data-name='button_font_size_global' data-id='button_font_size_global' style='width : 80% !important;' >";
                                $tablestring .= "<dt><span>" . $general_option['column_settings']['button_font_size_global'] . "</span><input type='text' style='display:none;' value='" . esc_html( $general_option['column_settings']['button_font_size_global'] ) . "' class='arp_autocomplete' /><i class='fas fa-caret-down fa-md'></i></dt>";
                                $tablestring .= "<dd>";

                                $size_arr = array();

                                $tablestring .= "<ul data-id='button_font_size_global'>";

                                for ($s = 8; $s <= 20; $s++)
                                    $size_arr[] = $s;
                                for ($st = 22; $st <= 70; $st+=2)
                                    $size_arr[] = $st;

                                foreach ($size_arr as $size) {
                                    $tablestring .= "<li data-value='" . esc_html( $size ) . "' data-label='" . esc_html( $size ) . "'>" . esc_html( $size ) . "</li>";
                                }
                                $tablestring .= "</ul>";
                                $tablestring .= "</dd>";
                                $tablestring .= "</dl>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                if ($general_option['column_settings']['arp_button_text_bold_global'] == 'bold') {
                                    $button_title_style_bold_selected = 'selected';
                                } else {
                                    $button_title_style_bold_selected = '';
                                }

                                //check selected for italic
                                if ($general_option['column_settings']['arp_button_text_italic_global'] == 'italic') {
                                    $button_title_style_italic_selected = 'selected';
                                } else {
                                    $button_title_style_italic_selected = '';
                                }

                                //check selected for underline or line-through
                                if ($general_option['column_settings']['arp_button_text_decoration_global'] == 'underline') {
                                    $button_title_style_underline_selected = 'selected';
                                } else {
                                    $button_title_style_underline_selected = '';
                                }

                                if ($general_option['column_settings']['arp_button_text_decoration_global'] == 'line-through') {
                                    $button_title_style_linethrough_selected = 'selected';
                                } else {
                                    $button_title_style_linethrough_selected = '';
                                }
                                $tablestring .= "<div class='column_opt_opts font_style_div' style='float: right;' id='arp_button_text_style_global'>";

                                $tablestring .= "<div class='font_title_font_family_div' data-level = 'button_level_options' level-id='button_level_global'>";

                                $tablestring .= "<div class='arp_style_btn " . $button_title_style_bold_selected . " arptooltipster' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                $tablestring .= "<i class='fas fa-bold'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $button_title_style_italic_selected . " arptooltipster' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                $tablestring .= "<i class='fas fa-italic'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div class='arp_style_btn " . $button_title_style_underline_selected . " arptooltipster' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                $tablestring .= "<i class='fas fa-underline'></i>";
                                $tablestring .= "</div>";

                                $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn " . $button_title_style_linethrough_selected . " arptooltipster' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                $tablestring .= "</div>";
                                $tablestring .= "<input type='hidden' id='button_style_bold_global' name='button_style_bold_global' value='" . esc_html( $general_option['column_settings']['arp_button_text_bold_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='button_style_italic_global' name='button_style_italic_global' value='" . esc_html( $general_option['column_settings']['arp_button_text_italic_global'] ) . "' /> ";
                                $tablestring .= "<input type='hidden' id='button_style_decoration_global' name='button_style_decoration_global' value='" . esc_html( $general_option['column_settings']['arp_button_text_decoration_global'] ) . "' /> ";

                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            /** button font settings */

                            /** tooltip font settings */
                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='border-bottom: none;'>";
                            
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height'>" . esc_html__('Tooltip Fonts', 'arprice-responsive-pricing-table') ." &nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                    
                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";
                                        
                                        $tablestring .= "<dl class='arp_selectbox arp_font_option_dd arplite_restricted_view' id='tooltip_font_family_dd' data-name='tooltip_font_family' data-id='tooltip_font_family'  style=''>";

                                            $tablestring .= "<dt><span style='float:left;'>". esc_html__('Helvetica', 'arprice-responsive-pricing-table') ."</span><input type='text' style='display:none;' value='' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul class='arp_tooltip_font_setting' data-id='tooltip_font_family'>";

                                                    $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";

                                                    $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                    $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive' data-id='google_fonts_wrapper'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";
                                        

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";

                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";

                                        $tablestring .= "<input type='hidden' id='tooltip_font_size' name='tooltip_font_size' value=''/>";

                                        $tablestring .= "<dl class='arp_selectbox arp_font_option_dd arplite_restricted_view' id='tooltip_font_size_dd' data-name='tooltip_font_size' data-id='tooltip_font_size'  style='width : 83% !important;'>";

                                            $tablestring .= "<dt><span style='float:left;'>18</span><input type='text' style='display:none;' value='' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul class='arp_tooltip_font_setting' data-id='tooltip_font_size'>";

                                                    $tablestring .= "<li class='arp_selectbox_option' data-value='' data-label=''></li>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_tooltip_text_style_global'>";

                                    $tablestring .= "<div class='font_title_font_family_div' data-level = 'tooltip_font_style' level-id='tooltip_font_style'>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";

                                            $tablestring .= "<i class='fas fa-bold'></i>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                            
                                            $tablestring .= "<i class='fas fa-italic'></i>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                            
                                            $tablestring .= "<i class='fas fa-underline'></i>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn arptooltipster arplite_restricted_view' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                            $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<input type='hidden' id='tooltip_font_style_bold' name='tooltip_font_style_bold' value='' />";
                            
                                        $tablestring .= "<input type='hidden' id='tooltip_font_style_italic' name='tooltip_font_style_italic' value='' />";
                            
                                        $tablestring .= "<input type='hidden' id='tooltip_font_style_decoration' name='tooltip_font_style_decoration' value='' /> ";

                                    $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            /** tooltip font settings */


                            /** toggle title fonts */

                            $tablestring .= "<div class='column_content_light_row column_opt_row' style='border-bottom: none;'>";

                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' >" . esc_html__('Toggle Title Fonts', 'arprice-responsive-pricing-table') . " &nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";
                                    
                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";

                                        $tablestring .= "<dl class='arp_selectbox arp_font_option_dd arplite_restricted_view' id='toggle_title_font_family_dd' data-name='toggle_title_font_family' data-id='toggle_title_font_family' style=''>";

                                            $tablestring .= "<dt><span>" . esc_html__('Helvetica', 'arprice-responsive-pricing-table') . "</span><input type='text' style='display:none;' value='' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul class='arp_toggletitle_font_setting' data-id='toggle_title_font_family'>";

                                                    $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";

                                                    $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                    $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive' data-id='google_fonts_wrapper'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";
                                        

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";
            
                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";

                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";

                                        $tablestring .= "<input type='hidden' id='toggle_title_font_size'  name='toggle_title_font_size' value='' />";

                                        $tablestring .= "<dl class='arp_selectbox toggle_title_font_size_dd arp_font_option_dd arplite_restricted_view' data-name='toggle_title_font_size' data-id='toggle_title_font_size' style='width : 83% !important;' >";

                                            $tablestring .= "<dt><span>18</span><input type='text' style='display:none;' value='' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $size_arr = array();

                                                $tablestring .= "<ul data-id='toggle_title_font_size'>";
            
                                                    $tablestring .= "<li data-value='' data-label=''></li>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='arp_toggle_title_text_style_global'>";

                                    $tablestring .= "<div class='font_title_font_family_div' data-level = 'toggle_title_font_style' level-id='toggle_button_font_style'>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                            $tablestring .= "<i class='fas fa-bold'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                            $tablestring .= "<i class='fas fa-italic'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                            $tablestring .= "<i class='fas fa-underline'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div style='' class='arp_style_btn arptooltipster arplite_restricted_view' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                            $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<input type='hidden' id='toggle_title_font_style_bold' name='toggle_title_font_style_bold' value='' /> ";
                                        $tablestring .= "<input type='hidden' id='toggle_title_font_style_italic' name='toggle_title_font_style_italic' value='' /> ";
                                        $tablestring .= "<input type='hidden' id='toggle_title_font_style_decoration' name='toggle_title_font_style_decoration' value='' /> ";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            /* toggle tab fonts */

                            $tablestring .= "<div class='column_content_light_row column_opt_row arp_no_border' style='border-bottom: none;'>";
            
                                $tablestring .= "<div class='column_opt_label two_cols column_opt_title_height' >" . esc_html__('Toggle Tab Fonts', 'arprice-responsive-pricing-table') . " &nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                $tablestring .= "<div class='column_opt_opts arp_font_family'>";

                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Family', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";

                                        $tablestring .= "<dl class='arp_selectbox arp_font_option_dd arplite_restricted_view' id='toggle_button_font_family_dd' data-name='toggle_button_font_family' data-id='toggle_button_font_family' style=''>";

                                            $tablestring .= "<dt><span>". esc_html__('Helvetica', 'arprice-responsive-pricing-table') ."</span><input type='text' style='display:none;' value='' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul class='arp_togglebutton_font_setting' data-id='toggle_button_font_family'>";

                                                    $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";

                                                    $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
            
                                                    $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive' data-id='google_fonts_wrapper'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";
                                        

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_settings_div'>";

                                    $tablestring .= "<div class='column_opt_label arp_fontsetting_label_height'>" . esc_html__('Font Size', 'arprice-responsive-pricing-table') . "</div>";

                                    $tablestring .= "<div class='font_title_font_family_div'>";
            
                                        $tablestring .= "<input type='hidden' id='toggle_button_font_size'  name='toggle_button_font_size' value='' />";

                                        $tablestring .= "<dl class='arp_selectbox toggle_button_font_size_dd arp_font_option_dd arplite_restricted_view' data-name='toggle_button_font_size' data-id='toggle_button_font_size' style='width : 83% !important;'>";

                                            $tablestring .= "<dt><span>18</span><input type='text' style='display:none;' value='' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";

                                            $tablestring .= "<dd>";

                                                $tablestring .= "<ul data-id='toggle_button_font_size'>";
        
                                                    $tablestring .= "<li data-value='" . $size . "' data-label='" . $size . "'>" . $size . "</li>";

                                                $tablestring .= "</ul>";

                                            $tablestring .= "</dd>";

                                        $tablestring .= "</dl>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='column_opt_opts font_style_div' style='' id='toggle_button_font_style'>";

                                    $tablestring .= "<div class='font_title_font_family_div' data-level = 'toggle_button_font_style' level-id='toggle_button_font_style'>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='left' title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Bold', 'arprice-responsive-pricing-table') . "' id='arp_style_bold'>";
                                            $tablestring .= "<i class='fas fa-bold'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='center' title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Italic', 'arprice-responsive-pricing-table') . "' id='arp_style_italic'>";
                                            $tablestring .= "<i class='fas fa-italic'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='arp_style_btn arptooltipster arplite_restricted_view' data-align='right' title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Underline', 'arprice-responsive-pricing-table') . "' id='arp_style_underline'>";
                                            $tablestring .= "<i class='fas fa-underline'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div style='margin-right:0 !important;' class='arp_style_btn arptooltipster arplite_restricted_view' data-align='right' title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Line-through', 'arprice-responsive-pricing-table') . "' id='arp_style_strike'>";
                                            $tablestring .= "<i class='fas fa-strikethrough'></i>";
                                        $tablestring .= "</div>";

                                        $tablestring .= "<input type='hidden' id='toggle_button_font_style_bold' name='toggle_button_font_style_bold' value=''/> ";
                                        $tablestring .= "<input type='hidden' id='toggle_button_font_style_italic' name='toggle_button_font_style_italic' value='' /> ";
                                        $tablestring .= "<input type='hidden' id='toggle_button_font_style_decoration' name='toggle_button_font_style_decoration' value='' /> ";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    /* Font End */

                    /* Color Start */

                    $tablestring .= "<div class='general_toggle_options_tab enable global_opts' id='all_color_options'>";

                        $tablestring .= "<div class='arprice_option_belt_title'>" . esc_html__('Color Settings', 'arprice-responsive-pricing-table') . "</div>";

                        $tablestring .= "<div class='font_settings_options_dropdown arp_offset_container'>";

                            $tablestring .= "<div class='column_opt_label arp_fix_height'>";

                                $tablestring .= "<div class='arp_options_group_title' style='padding:10px 0px 0px 15px;'>" . esc_html__('Default Table Color', 'arprice-responsive-pricing-table') . "</div>";

                            $tablestring .= "</div>"; 

                            $tablestring .= "<div class='column_content_light_row column_color_skin_row' style='border-bottom:1px solid #e1e9f2' >";

                                $tablestring .= "<div class='column_opt_label'>" . esc_html__('Select Default Color', 'arprice-responsive-pricing-table') . "</div>";

                                $tablestring .= "<div class='column_opt_opts arp_toggle_step_dropdwon' >";

                                    if ($reference_template == '') {
                                        $reference_template = 'arplitetemplate_1';
                                    }
                                    $arp_template_skin_selected_key = array_search($arp_template_skin, $arplite_mainoptionsarr['general_options']['template_options']['skins'][$reference_template]);

                                    $default_skins = $arpricelite_default_settings->arprice_default_template_skins();
                                    $postarr['action'] = "arprice_default_template_skins";
                                    $postarr['table_id'] = $table_id;
                                    $postarr['reference_template'] = $reference_template;

                                    $skins = $arpricelite_default_settings->arp_change_default_template_skins($default_skins, $postarr);

                                    $data_skin = json_encode($skins[$reference_template]['skin']);
                                    $data_array = json_encode($skins[$reference_template]['color']);

                                    $skins_reference_template_colors = isset($skins[$reference_template]['color']) ? $skins[$reference_template]['color'] : array();

                                    if ($arplite_mainoptionsarr['general_options']['template_options']['skins'][$reference_template][$arp_template_skin_selected_key] == 'multicolor' && $arplite_mainoptionsarr['general_options']['template_options']['skins'][$reference_template][$arp_template_skin_selected_key] == 'multicolor'){
                                        $cls = 'multi-color-small-icon';
                                    } else {
                                        $cls = '';
                                    }

                                    if (isset($arplite_mainoptionsarr['general_options']['template_options']['skins'][$reference_template][$arp_template_skin_selected_key]) && $arplite_mainoptionsarr['general_options']['template_options']['skins'][$reference_template][$arp_template_skin_selected_key] != 'multicolor') {
                                        $color = '#' . $arplite_mainoptionsarr['general_options']['template_options']['skin_color_code'][$reference_template][$arp_template_skin_selected_key];
                                    } else {
                                        $color = '';
                                    }

                                    if ($template_settings['skin'] == 'custom' || $template_settings['skin'] == 'custom_skin') {
                                        $custom_skin_key = $arpricelite_default_settings->arplite_custom_css_selected_bg_color();
                                        $custom_skin_key = $custom_skin_key[$reference_template];
                                        $color = $general_option['custom_skin_colors'][$custom_skin_key];
                                    }

                                    $tablestring .= '<input type="hidden" id="color_selection_options_main_hidden"  name="color_options_main" class="color_options_main" value="' . $skins_reference_template_colors[$arp_template_skin_selected_key] . '" /> ';

                                    $tablestring .= "<dl class='arp_selectbox arp_select_colorbox arp_toggle_option_dd' id='color_selection_options_main_hidden_dd' data-name='color_options_main' data-id='color_selection_options_main_hidden' style='width:92% !important;float:right;'>";

                                        $tablestring .= "<dt>";
                                        
                                            if( 'Multicolor' == $skins_reference_template_colors[$arp_template_skin_selected_key] ){
                                                $tablestring .= "<span class='arp_multicolor_dd_belt'>&nbsp;</span>";
                                            } else if( '' === trim($arp_template_skin_selected_key) ){
                                                $tablestring .= "<span class='arp_custom_skin_dd_belt'>".esc_html__('Custom Color','arprice-responsive-pricing-table')."</span>";
                                            } else {
                                                $tablestring .= "<span style='background:#{$skins_reference_template_colors[$arp_template_skin_selected_key]}'>&nbsp;</span>";
                                            }

                                        $tablestring .= "<i class='fas fa-caret-down fa-lg'></i></dt>";

                                        $tablestring .= "<dd>";

                                            $tablestring .= "<ul data-id='color_selection_options_main_hidden'>";

                                                $arp_cntr = 0;

                                                foreach ( $skins_reference_template_colors as $skins_reference_template_color ){

                                                    if( $skins_reference_template_color == 'Multicolor' ){
                                                    $tablestring .= "<li class='arp_selectbox_option arp_multicolor_dd_belt' data-value='{$skins_reference_template_color}' onClick='arp_select_template_skin(\"".$skins[$reference_template]['skin'][$arp_cntr]."\",\"".$skins_reference_template_color."\");' data-label='&nbsp;' >";
                                                    $tablestring .= " ";
                                                    $tablestring .= "</li>";
                                                    } else if( 'db_custom_skin' == $skins[$reference_template]['skin'][$arp_cntr] ){
                                                        $tablestring .= "<li class='arp_selectbox_option arp_custom_skin_dd_belt' data-value='{$skins_reference_template_color}'  onClick='arp_select_template_skin(\"".$skins[$reference_template]['skin'][$arp_cntr]."\",\"".$skins_reference_template_color."\");' data-label='".esc_html__('Custom Color','arprice-responsive-pricing-table')."'>".esc_html__('Custom Color','arprice-responsive-pricing-table')."</li>";
                                                    } else {
                                                        $tablestring .= "<li class='arp_selectbox_option' data-value='{$skins_reference_template_color}' onClick='arp_select_template_skin(\"".$skins[$reference_template]['skin'][$arp_cntr]."\",\"".$skins_reference_template_color."\");' data-label='&nbsp;' style='background:#".$skins_reference_template_color.";'>";
                                                            $tablestring .= " ";
                                                        $tablestring .= "</li>";
                                                    }
                                                        $arp_cntr++;
                                                }
                                        
                                            $tablestring .= "</ul>";

                                        $tablestring .= "</dd>";

                                    $tablestring .= "</dl>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_opt_label arp_fix_height'><div class='arp_options_group_title' style='padding: 10px 0px 0px 15px;'>" . esc_html__('Customize Color Options', 'arprice-responsive-pricing-table') . "</div></div>";

                            $tablestring .= "<div class='column_color_skin_row' style='padding-bottom:0 !important;padding-top:0 !important;'>";

                                $tablestring .= "<div class='column_opt_label'>";

                                    $tablestring .= esc_html__('Customize Colors','arprice-responsive-pricing-table');

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";
                                    
                            $tablestring .= "<div class='column_custom_background' table_id='".$id."'  id='arp_custom_color_scheme_popup'>";

                                $tablestring .= "<div class='col_opt_row' id='arp_custom_color_tab' style='padding:0 !important;'>";

                                    $tablestring .= "<div class='col_opt_title_div two_column arp_color_tab selected' data-id='arp_normal'>". esc_html__('Normal', 'arprice-responsive-pricing-table')."</div>";

                                    $tablestring .= "<div class='col_opt_title_div two_column arp_color_tab' data-id='arp_hover'>".esc_html__('Hover', 'arprice-responsive-pricing-table')."</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div class='col_opt_row' id='arp_normal_custom_color_tab' style='padding:0 !important;padding-top:7px !important;'>";

                                    $tablestring .= "<div class='col_opt_title_div three_column txt_align_center'></div>";

                                    $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='background_color' style='padding-top:5px !important;'>". esc_html__('Background', 'arprice-responsive-pricing-table')."</div>";

                                    $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='font_color' style='padding-top:5px !important;'>". esc_html__('Text Color', 'arprice-responsive-pricing-table')."</div>";

                                $tablestring .= "</div>";

                                $tablestring .= "<div id='arp_normal_background_color'>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_column_background_color_data_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Column', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column'>";

                                            $tablestring .= "<div data-color='".$arp_template_column_bg_color."' data-custom-input='arp_column_background_color_input' id='arp_column_background_color_data' data-column-id='' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_background_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_background_color_data)\",valueElement:\"#arp_column_background_color_data_hidden\"}'>";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_template_column_bg_color."' name='arp_column_bg_custom_color' id='arp_column_background_color_data_hidden' />";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_header_background_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Header', 'arprice-responsive-pricing-table') . "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_header_background_div_id' id='arp_header_background_div_id'>";

                                            $tablestring .= "<div data-color='".$arp_template_header_bg_color."' data-custom-input='arp_header_background_color_input' id='arp_header_background_color' data-column-id='' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_background_color jscolor arp_custom_css_colorpicker' data-id='arp_header_background_color_hidden' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_header_background_color)\",valueElement:\"#arp_header_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_template_header_bg_color."' name='arp_header_background_color' id='arp_header_background_color_hidden' >";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_header_font_color_div_id' id='arp_header_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_header_font_color' data-custom-input='arp_header_font_custom_color_input' data-color='".$arp_header_font_custom_color_input."' data-column-id='".$arp_header_font_custom_color_input."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_header_font_color)\",valueElement:\"#arp_header_font_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_header_font_custom_color_input."' name='arp_header_font_custom_color' id='arp_header_font_color_hidden' >";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_shortcode_background_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>" . esc_html__('Shortcode', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_shortcode_background_div_id' id='arp_shortcode_background_div_id'>";

                                            $tablestring .= "<div data-color='".$arp_shortocode_background."' data-custom-input='arp_shortocode_background_color_input' id='arp_shortcode_background_color' data-column-id='' data-column='' class='color_picker_font font_color_picker background_column_picker arp_shortcode_background_color jscolor arp_custom_css_colorpicker' data-id='arp_shortcode_background_color_hidden' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_shortcode_background_color)\",valueElement:\"#arp_shortcode_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_shortocode_background."' name='arp_shortcode_background_color' id='arp_shortcode_background_color_hidden' >";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_shortcode_font_color_div_id' id='arp_shortcode_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_shortcode_font_custom_color' data-custom-input='arp_shortocode_font_custom_color_input' data-color='".$arp_shortocode_font_color."' data-column-id='".$arp_shortocode_font_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_shortcode_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_shortcode_font_custom_color)\",valueElement:\"#arp_shortcode_font_color_hidden\"}' data-id='arp_shortcode_font_color_hidden'>";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_shortocode_font_color."' name='arp_shortcode_font_color' id='arp_shortcode_font_color_hidden' >";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";
                         
                                    $tablestring .= "<div class='col_opt_row' id='arp_column_desc_background_color_data_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>".esc_html__('Description', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_column_desc_background_color_div_id' id='arp_column_desc_background_color_div_id'>";

                                            $tablestring .= "<div data-color='".$arp_template_column_desc_bg_color."' data-custom-input='arp_column_desc_background_color_input' id='arp_column_desc_background_color_data' data-column-id='".$arp_template_column_desc_bg_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_background_color jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_desc_background_color_data)\",valueElement:\"#arp_column_desc_background_color_data_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_template_column_desc_bg_color."' name='arp_column_desc_bg_custom_color' id='arp_column_desc_background_color_data_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_desc_font_custom_color_div_id' id='arp_desc_font_custom_color_div_id'>";

                                            $tablestring .= "<div id='arp_desc_font_custom_color' data-color='".$arp_desc_font_custom_color_input."' data-custom-input='arp_desc_font_custom_color_input' data-column-id='".$arp_desc_font_custom_color_input."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_desc_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_desc_font_custom_color)\",valueElement:\"#arp_desc_font_custom_color_hidden\"}'>";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_desc_font_custom_color_input."' name='arp_desc_font_custom_color' id='arp_desc_font_custom_color_hidden' />";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";
                                                
                                    $tablestring .= "<div class='col_opt_row' id='arp_pricing_background_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Pricing', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_pricing_background_div_id' id='arp_pricing_background_div_id'>";

                                            $tablestring .= "<div data-color='".$arp_template_pricing_bg_color."' data-custom-input='arp_pricing_background_color_input' id='arp_pricing_background_color' data-column-id='".$arp_template_pricing_bg_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_background_color jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_pricing_background_color)\",valueElement:\"#arp_pricing_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_template_pricing_bg_color."' name='arp_pricing_background_color' id='arp_pricing_background_color_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_pricing_font_color_div_id' id='arp_pricing_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_price_font_custom_color' data-color='".$arp_price_font_custom_color_input."' data-custom-input='arp_price_font_custom_color_input' data-column-id='".$arp_price_font_custom_color_input."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_price_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_price_font_custom_color)\",valueElement:\"#arp_price_font_custom_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_price_font_custom_color_input."' name='arp_price_font_custom_color' id='arp_price_font_custom_color_hidden' >";
                                                    
                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_footer_background_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Footer', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_footer_background_div_id'>";

                                            $tablestring .= "<div data-color='". $arp_template_footer_content_bg_color ."' data-custom-input='arp_footer_content_background_color' id='arp_footer_background_color' data-column_id='' data-column='' class='color_picker_font font_color_picker background_column_picker arp_footer_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_footer_background_color)\",valueElement:\"#arp_footer_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color'   value='". $arp_template_footer_content_bg_color ."' name='arp_footer_background_color' id='arp_footer_background_color_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_footer_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_footer_font_custom_color' data-color='".$arp_footer_font_custom_color_input."' data-custom-input='arp_footer_font_custom_color_input' data-column-id='".$arp_footer_font_custom_color_input."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_footer_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_footer_font_custom_color)\",valueElement:\"#arp_footer_font_custom_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_footer_font_custom_color_input."' name='arp_footer_font_custom_color' id='arp_footer_font_custom_color_hidden' >";
                                                    
                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";
                                                
                                    $tablestring .= "<div class='col_opt_row' id='arp_button_background_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Button', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_button_background_div_id' id='arp_button_background_div_id'>";

                                            $tablestring .= "<div data-color='". $arp_template_button_bg_color ."' data-custom-input='arp_button_background_color_input' id='arp_button_background_color' data-column_id='". $arp_template_button_bg_color ."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_footer_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_button_background_color)\",valueElement:\"#arp_button_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_template_button_bg_color."' name='arp_button_background_color' id='arp_button_background_color_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column' id='arp_button_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_button_font_custom_color' data-color='".$arp_button_font_custom_color_input."' data-custom-input='arp_button_font_custom_color_input' data-column-id='".$arp_button_font_custom_color_input."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_button_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_button_font_custom_color)\",valueElement:\"#arp_button_font_custom_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_button_font_custom_color_input."' name='arp_button_font_custom_color' id='arp_button_font_custom_color_hidden'>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div' id='button_custom_font_notice' style='display:none;'>(For Button Hover)</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_body_background_color' style='display:none;'>";

                                        $tablestring .= "<div id='' class='col_opt_row' style='padding-left:0 !important;'>";

                                            $tablestring .= "<div class='col_opt_title_div col_opt_title_div_sub_head'>". esc_html__('Body Row Colors', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Odd', 'arprice-responsive-pricing-table')."</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_background_div_id' id='arp_body_background_div_id'>";

                                                $tablestring .= "<div data-color='".$arp_template_odd_row_bg_color."' data-custom-input='arp_body_odd_row_background_color' id='arp_body_odd_background' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_odd_background jscolor arp_custom_css_colorpicker' data-column_id='".$arp_template_odd_row_bg_color."' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_odd_background)\",valueElement:\"#arp_body_odd_background_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_template_odd_row_bg_color."' name='arp_body_odd_background_color' id='arp_body_odd_background_hidden' />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_font_color_id' id='arp_body_font_color_id'>";

                                                $tablestring .= "<div id='arp_body_font_custom_color' data-custom-input='arp_body_font_custom_color_input' data-color='".$arp_body_font_custom_color_input."' data-column-id='".$arp_body_font_custom_color_input."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_font_custom_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_font_custom_color)\",valueElement:\"#arp_body_font_custom_color_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color ' value='".$arp_body_font_custom_color_input."' name='arp_body_font_custom_color' id='arp_body_font_custom_color_hidden' >";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Even', 'arprice-responsive-pricing-table'). "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_background_div_id' id='arp_body_background_div_id'>";

                                                $tablestring .= "<div data-color='". $arp_template_even_row_bg_color . "' data-custom-input='arp_body_even_row_background_color' id='arp_body_even_background' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_even_background jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_even_background)\",valueElement:\"#arp_body_even_background_hidden\"}' >";

                                                $tablestring .= "</div>";
                                                            
                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='". $arp_template_even_row_bg_color . "' name='arp_body_even_background_color' id='arp_body_even_background_hidden' />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_font_color_id' id='arp_body_font_color_id'>";

                                                $tablestring .= "<div data-color='". $arp_body_even_font_custom_color_input . "' data-custom-input='arp_body_even_font_custom_color_input' id='arp_body_even_font_custom_color' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_even_font_custom_color jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_even_font_custom_color)\",valueElement:\"#arp_body_even_font_custom_color_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='". $arp_body_even_font_custom_color_input . "' name='arp_body_even_font_custom_color_color' id='arp_body_even_font_custom_color_hidden' />";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row' style='padding-left:0 !important;'>";

                                            $tablestring .= "<div class='col_opt_title_div col_opt_title_div_sub_head'>". esc_html__('Submit Loader Colors', 'arprice-responsive-pricing-table'). " &nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Background', 'arprice-responsive-pricing-table'). "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_analytics_bgcolor_id' id='arp_analytics_bgcolor_id'>";

                                                $tablestring .= "<div data-color='' id='arp_analytics_bgcolor' data-column='' class='color_picker_font font_color_picker background_column_picker arp_analytics_bgcolor arp_custom_css_colorpicker' data-column_id=''>";

                                                $tablestring .= "</div>";
                                                            
                                                /*$tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='' name='arp_analytics_bgcolor' id='arp_analytics_bgcolor_hidden' />";*/

                                            $tablestring .= "</div>";
                                                        
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view'>";
                                                        
                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Foreground', 'arprice-responsive-pricing-table'). "</div>";
                                                        
                                            $tablestring .= "<div class='col_opt_input_div three_column arp_analytics_forgcolor_id' id='arp_analytics_forgcolor_id'>";
                                                        
                                               $tablestring .= "<div data-color='' id='arp_analytics_forgcolor' data-column='' class='arplite_restricted_view color_picker_font font_color_picker background_column_picker arp_analytics_forgcolor arp_custom_css_colorpicker' data-column_id=''>";
                                                        
                                                $tablestring .= "</div>";
                                                        
                                                /*$tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='' name='arp_analytics_forgcolor' id='arp_analytics_forgcolor_hidden' />";*/
                                                    
                                            $tablestring .= "</div>";
                                                        
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row' style='padding-left:0 !important;'>";

                                            $tablestring .= "<div class='col_opt_title_div col_opt_title_div_sub_head'>" . esc_html('Border Colors','arprice-responsive-pricing-table') . "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row' style='padding:0 !important;'>";
                                        
                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center'></div>";
                                              
                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='background_color' style='padding-top:5px !important;'>". esc_html__('Column', 'arprice-responsive-pricing-table')."</div>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='font_color' style='padding-top:5px !important;'>". esc_html__('Row', 'arprice-responsive-pricing-table')."</div>";
                                                    
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'></div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";

                                                $column_settings['arp_column_border_color'] = isset($column_settings['arp_column_border_color']) ? $column_settings['arp_column_border_color'] : "#c9c9c9";
                                                        
                                                $tablestring .= "<div class='color_picker_font arp_custom_css_colorpicker color_picker_round jscolor' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_border_color)\",valueElement:\"#arp_column_border_color_hidden\"}' data-column-id='arp_column_border_color' data-id='arp_column_border_color_hidden' id='arp_column_border_color' style='background:" . $column_settings['arp_column_border_color'] . ";' data-color='" . $column_settings['arp_column_border_color'] . "' ></div>";

                                                $tablestring .= "<input type='hidden' id='arp_column_border_color_hidden' data-column-id='arp_column_border_color' data-id='arp_column_border_color' name='arp_column_border_color' value='" . $column_settings['arp_column_border_color'] . "' />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";

                                                $column_settings['arp_row_border_color'] = isset($column_settings['arp_row_border_color']) ? $column_settings['arp_row_border_color'] : '';


                                                $tablestring .= "<div class='color_picker_font arp_custom_css_colorpicker color_picker_round jscolor' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_row_border_color)\",valueElement:\"#arp_row_border_color_hidden\"}' data-id='arp_row_border_color_hidden' id='arp_row_border_color' style='background:" . $column_settings['arp_row_border_color'] . ";' data-color='" . $column_settings['arp_row_border_color'] . "' data-column-id='arp_row_border_color'></div>";
                                                        
                                                $tablestring .= "<input type='hidden' id='arp_row_border_color_hidden' data-column-id='arp_row_border_color' data-id='arp_row_border_color' name='arp_row_border_color' value='" . $column_settings['arp_row_border_color'] . "' />";
                                                    
                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";
                                            
                                $tablestring .= "</div>";

                                $tablestring .= "<div id='arp_hover_background_color' style='display:none;'>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_column_hover_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Column', 'arprice-responsive-pricing-table'). "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_column_background_div_id' id='arp_column_background_div_id'>";

                                            $tablestring .= "<div data-color='". $arp_column_bg_hover_color. "' data-custom-input='arp_column_bg_hover_color' id='arp_column_hover_background' data-column_id='". $arp_column_bg_hover_color. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_column_hover_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_hover_background)\",valueElement:\"#arp_column_hover_background_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='". $arp_column_bg_hover_color. "' name='arp_column_bg_hover_color' id='arp_column_hover_background_hidden' />";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_header_hover_bg_color' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>";

                                            $tablestring .= esc_html__('Header', 'arprice-responsive-pricing-table');

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_header_background_div_id'>";

                                            $tablestring .= "<div data-color='". $arp_header_bg_hover_color. "' data-custom-input='arp_header_bg_hover_color' id='arp_header_hover_background_color' data-column-id='". $arp_header_bg_hover_color. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_background_color jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_header_hover_background_color)\",valueElement:\"#arp_header_hover_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_header_bg_hover_color. "' name='arp_header_hover_background_color' id='arp_header_hover_background_color_hidden' >";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_header_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_header_font_custom_hover_color' data-color='". $arp_header_font_custom_hover_color_input. "' data-custom-input='arp_header_font_custom_hover_color_input' data-column-id='". $arp_header_font_custom_hover_color_input. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_header_font_custom_hover_color)\",valueElement:\"#arp_header_font_custom_hover_color_hidden\"}' >";
                                
                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_header_font_custom_hover_color_input. "' name='arp_header_font_custom_hover_color' id='arp_header_font_custom_hover_color_hidden' />";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_shortcode_hover_bg_color' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>";

                                            $tablestring .= esc_html__('Shortcode', 'arprice-responsive-pricing-table');

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_shortcode_background_div_id'>";

                                            $tablestring .= "<div data-color='". $arp_shortcode_bg_hover_color. "' data-custom-input='arp_shortcode_bg_hover_color' id='arp_shortcode_hover_background_color' data-column-id='' data-column='' class='color_picker_font font_color_picker background_column_picker arp_shortcode_background_color jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_shortcode_hover_background_color)\",valueElement:\"#arp_shortcode_hover_background_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_shortcode_bg_hover_color. "' name='arp_shortcode_hover_background_color' id='arp_shortcode_hover_background_color_hidden' >";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_shortcode_font_color_div_id'>";

                                            $tablestring .= "<div id='arp_shortcode_font_custom_hover_color' data-custom-input='arp_shortcode_font_custom_hover_color_input' data-color='". $arp_shortcode_font_hover_color . "' data-column-id='". $arp_shortcode_font_hover_color. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_shortcode_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_shortcode_font_custom_hover_color)\",valueElement:\"#arp_shortcode_font_custom_hover_color_hidden\"}' >";

                                            $tablestring .= "</div>";
                                                        
                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_shortcode_font_hover_color. "' name='arp_shortcode_font_custom_hover_color' id='arp_shortcode_font_custom_hover_color_hidden' />";
                                                    
                                        $tablestring .= "</div>";
                                                
                                    $tablestring .= "</div>";
                                                
                                    $tablestring .= "<div class='col_opt_row' id='arp_column_desc_hover_background_color_data' style='display:none;'>";
                                                    
                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Description', 'arprice-responsive-pricing-table'). "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_column_desc_background_color_div_id'>";
                                                    
                                            $tablestring .= "<div data-color='".$arp_template_column_desc_hover_bg_color."' id='arp_column_desc_hover_bg_custom_color' data-custom-input='arp_column_desc_hover_background_color_input' data-column-id='".$arp_template_column_desc_hover_bg_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_header_background_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_desc_hover_bg_custom_color)\",valueElement:\"#arp_column_desc_hover_bg_custom_color_hidden\"}' >";

                                            $tablestring .= "</div>";
                                            
                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='".$arp_template_column_desc_hover_bg_color."' name='arp_column_desc_hover_bg_custom_color' id='arp_column_desc_hover_bg_custom_color_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_desc_font_custom_color_div_id'>";

                                            $tablestring .= "<div id='arp_desc_font_custom_hover_color' data-color='". $arp_desc_font_custom_hover_color_input. "' data-custom-input='arp_desc_font_custom_hover_color_input' data-column-id='". $arp_desc_font_custom_hover_color_input. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_desc_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_desc_font_custom_hover_color)\",valueElement:\"#arp_desc_font_custom_hover_color_hidden\"}' >";
                                                    
                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_desc_font_custom_hover_color_input. "' name='arp_desc_font_custom_hover_color' id='arp_desc_font_custom_hover_color_hidden' />";
                                                
                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_pricing_background_hover_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Pricing', 'arprice-responsive-pricing-table'). "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_pricing_background_div_id'>";

                                            $tablestring .= "<div data-color='".$arp_price_bg_hover_color."' id='arp_column_price_hover_background' data-custom-input='arp_price_bg_hover_color' data-column_id='".$arp_price_bg_hover_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_column_price_hover_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_price_hover_background)\",valueElement:\"#arp_column_price_hover_background_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_price_bg_hover_color."' name='arp_column_price_hover_background' id='arp_column_price_hover_background_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_pricing_font_color_div_id'>";
                                            $tablestring .= "<div id='arp_price_font_custom_hover_color' data-custom-input='arp_price_font_custom_hover_color_input' data-color='". $arp_price_font_custom_hover_color_input. "' data-column-id='". $arp_price_font_custom_hover_color_input. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_price_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_price_font_custom_hover_color)\",valueElement:\"#arp_price_font_custom_hover_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_price_font_custom_hover_color_input. "' name='arp_price_font_custom_hover_color' id='arp_price_font_custom_hover_color_hidden' >";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_footer_hover_background_color' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Footer', 'arprice-responsive-pricing-table'). "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_footer_background_div_id'>";
                                            
                                            $tablestring .= "<div data-color='".$arp_footer_hover_background_color."' id='arp_footer_hover_background' data-custom-input='arp_footer_hover_bg_color' data-column_id='".$arp_footer_hover_background_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_footer_hover_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_footer_hover_background)\",valueElement:\"#arp_footer_hover_background_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_footer_hover_background_color."' name='arp_footer_hover_background' id='arp_footer_hover_background_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column arp_footer_font_color_div_id'>";
                                            
                                            $tablestring .= "<div id='arp_footer_font_custom_hover_color' data-custom-input='arp_footer_font_custom_hover_color_input' data-color='". $arp_footer_font_custom_hover_color_input. "' data-column-id='". $arp_footer_font_custom_hover_color_input. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_footer_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_footer_font_custom_hover_color)\",valueElement:\"#arp_footer_font_custom_hover_color_hidden\"}' >";

                                            $tablestring .= "</div>";
                                            
                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_footer_font_custom_hover_color_input. "' name='arp_footer_font_custom_hover_color' id='arp_footer_font_custom_hover_color_hidden' >";
                                        
                                        $tablestring .= "</div>";
                                    
                                    $tablestring .= "</div>";
                                                
                                    $tablestring .= "<div class='col_opt_row' id='arp_btn_hover_color_div' style='display:none;'>";

                                        $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Button', 'arprice-responsive-pricing-table'). "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column'>";

                                            $tablestring .= "<div data-color='".$arp_button_bg_hover_color."' id='arp_column_btn_hover_background' data-custom-input='arp_button_bg_hover_color' data-column_id='".$arp_button_bg_hover_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_column_btn_hover_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_column_btn_hover_background)\",valueElement:\"#arp_column_btn_hover_background_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_button_bg_hover_color."' name='arp_column_btn_bg_hover_color' id='arp_column_btn_hover_background_hidden' />";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_input_div three_column'>";

                                            $tablestring .= "<div id='arp_button_font_custom_hover_color' data-custom-input='arp_button_font_custom_hover_color_input' data-color='". $arp_button_font_custom_hover_color_input. "' data-column-id='". $arp_button_font_custom_hover_color_input. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_button_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_button_font_custom_hover_color)\",valueElement:\"#arp_button_font_custom_hover_color_hidden\"}' >";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_button_font_custom_hover_color_input. "' name='arp_button_font_custom_hover_color' id='arp_button_font_custom_hover_color_hidden' >";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                    $tablestring .= "<div class='col_opt_row' id='arp_body_hover_background_color' style='display:none;'>";

                                        $tablestring .= "<div id='' class='col_opt_row' style='padding-left:0 !important;'>";

                                            $tablestring .= "<div class='col_opt_title_div col_opt_title_div_sub_head'>". esc_html__('Body Row Colors', 'arprice-responsive-pricing-table'). "</div>";
                                           
                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Odd', 'arprice-responsive-pricing-table'). "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_background_div_id'>";

                                                $tablestring .= "<div data-color='".$arp_template_odd_row_hover_bg_color."' id='arp_body_hover_odd_background' data-custom-input='arp_body_odd_row_hover_background_color' data-column_id='".$arp_template_odd_row_hover_bg_color."' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_hover_odd_background jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_hover_odd_background)\",valueElement:\"#arp_body_hover_odd_background_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_template_odd_row_hover_bg_color."' name='arp_body_hover_odd_background_color' id='arp_body_hover_odd_background_hidden' />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_font_color_div_id'>";

                                                $tablestring .= "<div id='arp_body_font_custom_hover_color' data-custom-input='arp_body_font_custom_hover_color_input' data-color='". $arp_body_font_custom_hover_color_input. "' data-column-id='". $arp_body_font_custom_hover_color_input. "' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_font_custom_hover_color)\",valueElement:\"#arp_body_font_custom_hover_color_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' value='". $arp_body_font_custom_hover_color_input. "' name='arp_body_font_custom_hover_color' id='arp_body_font_custom_hover_color_hidden' >";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>". esc_html__('Even', 'arprice-responsive-pricing-table'). "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_background_div_id'>";

                                                $tablestring .= "<div data-color='".$arp_template_even_row_hover_bg_color."' id='arp_body_hover_even_background' data-custom-input='arp_body_even_row_hover_background_color' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_hover_even_background jscolor arp_custom_css_colorpicker' data-column_id='".$arp_template_even_row_hover_bg_color."' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_hover_even_background)\",valueElement:\"#arp_body_hover_even_background_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='".$arp_template_even_row_hover_bg_color."' name='arp_body_hover_even_background_color' id='arp_body_hover_even_background_hidden' />";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column arp_body_font_color_id' id='arp_body_font_color_id'>";

                                                $tablestring .= "<div data-color='". $arp_body_even_font_custom_hover_color_input. "' data-custom-input='arp_body_even_font_custom_hover_color_input' id='arp_body_even_font_custom_hover_color' data-column='' class='color_picker_font font_color_picker background_column_picker arp_body_even_font_custom_hover_color jscolor arp_custom_css_colorpicker' data-column_id='' data-jscolor='{hash:true,onInput:\"arp_update_color(this,arp_body_even_font_custom_hover_color)\",valueElement:\"#arp_body_even_font_custom_hover_color_hidden\"}' >";

                                                $tablestring .= "</div>";

                                                $tablestring .= "<input type='hidden' class='general_color_box general_color_box_font_color' value='". $arp_body_even_font_custom_hover_color_input. "' name='arp_body_even_font_custom_hover_color_hidden' id='arp_body_even_font_custom_hover_color_hidden' />";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_content_light_row column_opt_row arp_tooltip_color_section' style='border-bottom:none;'>";

                                $tablestring .= "<div class='column_opt_label arp_fix_height'>" . esc_html__('Toggle Button Colors', 'arprice-responsive-pricing-table') . " &nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_custom_background' style='padding-top:0px;'>";

                                $tablestring .= "<div class='arp_normal_background_color'>";

                                    $tablestring .= "<div class='col_opt_row'>";

                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view' style='padding:0 !important;'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center'></div>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='background_color' style='padding-top:5px !important;'>". esc_html__('Background', 'arprice-responsive-pricing-table')."</div>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='font_color' style='padding-top:5px !important;'>". esc_html__('Text Color', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "</div>";
                                        
                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view' style='border-bottom:none;padding:5px 20px;'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>" . esc_html__('Active Tab', 'arprice-responsive-pricing-table') . "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";
                                                
                                                $tablestring .= "<div class='color_picker_font font_color_picker background_column_picker arp_custom_css_colorpicker' data-id='toggle_active_color_hidden' id='toggle_active_color'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";
                                                
                                                $tablestring .= "<div class='color_picker_font font_color_picker arp_custom_css_colorpicker' data-id='toggle_active_text_color_hidden' id='toggle_active_text_color'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view' style='border-bottom:none;padding:5px 20px;'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title' >" . esc_html__('Inactive Tab', 'arprice-responsive-pricing-table') . "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";
                                                
                                                $tablestring .= "<div class='color_picker_font font_color_picker color_picker_round arp_custom_css_colorpicker' data-id='toggle_inactive_color_hidden' id='toggle_inactive_color'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";

                                                $tablestring .= "<div class='color_picker_font color_picker_round arp_custom_css_colorpicker' data-id='toggle_button_font_color_hidden' id='toggle_button_font_color'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row toggle_main_background_div arplite_restricted_view' style='border-bottom:none;padding:5px 20px;'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title arp_toggle_main_belt_label' style='display:none;'>" . esc_html__('Main Belt', 'arprice-responsive-pricing-table') . "</div>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title arp_toggle_title_font_label column_opt_label_height'>" . esc_html__('Title Font', 'arprice-responsive-pricing-table') . "</div>";

                                            $tablestring .= "<div class='col_opt_input_div toggle_belt_background_color_div three_column'>";
                                                
                                                $tablestring .= "<div class='color_picker_font color_picker_round arp_custom_css_colorpicker' data-id='toggle_main_color_hidden' id='toggle_main_color'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column align_right'>";
                                                
                                                $tablestring .= "<div class='color_picker_font arp_custom_css_colorpicker color_picker_round' data-id='toggle_title_font_color_hidden' id='toggle_title_font_color'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                            $tablestring .= "<div class='column_content_light_row column_opt_row arp_tooltip_row arp_tooltip_color_section' style='border-bottom:none;'>";

                                $tablestring .= "<div class='column_opt_label arp_fix_height'>" . esc_html__('Tooltip Color', 'arprice-responsive-pricing-table') . " &nbsp;&nbsp;&nbsp;<span class='pro_version_info'>(" . esc_html__('Pro Version', 'arprice-responsive-pricing-table') . ")</span></div>";

                            $tablestring .= "</div>"; 

                            $tablestring .= "<div class='column_custom_background' style='padding-top:0px !important;'>";

                                $tablestring .= "<div class='arp_normal_background_color'>";
                                    
                                    $tablestring .= "<div class='col_opt_row' style='padding-top:0px !important;'>";

                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view' style='padding:0 !important;'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center'></div>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='background_color' style='padding-top:5px !important;'>". esc_html__('Background', 'arprice-responsive-pricing-table')."</div>";

                                            $tablestring .= "<div class='col_opt_title_div three_column txt_align_center' data-id='font_color' style='padding-top:5px !important;'>". esc_html__('Text Color', 'arprice-responsive-pricing-table')."</div>";

                                        $tablestring .= "</div>";

                                        $tablestring .= "<div class='col_opt_row arplite_restricted_view' style='border-bottom: none;'>";

                                            $tablestring .= "<div class='col_opt_title_div three_column three_column_title'>&nbsp;</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";

                                                $tablestring .= "<div class='color_picker_font color_picker_round arp_custom_css_colorpicker' data-id='tooltip_bgcolor_hidden' id='tooltip_bgcolor_div'></div>";

                                             $tablestring .= "</div>";

                                            $tablestring .= "<div class='col_opt_input_div three_column'>";

                                                $tablestring .= "<div class='color_picker_font color_picker_round arp_custom_css_colorpicker' data-id='tooltip_txtcolor_hidden' id='tooltip_txtcolor_div'>";

                                                $tablestring .= "</div>";

                                            $tablestring .= "</div>";

                                        $tablestring .= "</div>";

                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";

                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";
                    /* Color End */

                $tablestring .= "</div>";

            $tablestring .= "</div>";

            /* End */

        $tablestring .= "</div>";

        global $arplite_mainoptionsarr;

        $template_feature = $arplite_mainoptionsarr['general_options']['template_options']['features'][$ref_template];

        if ($is_template == 1) {
            $template_name = $sql->template_name;
        } else {
            $template_name = $table_id;
        }

        $arguments = array(
            'sslverify' => false
        );

        $col_ord_arr = json_decode($general_settings['column_order']);


        if (isset($column_animation['is_animation']) and $column_animation['is_animation'] == 'yes' and $column_animation['is_pagination'] == 1 and ( $column_animation['pagination_position'] == 'Top' or $column_animation['pagination_position'] == 'Both' ))
            $tablestring .= "<div class='arp_pagination " . $column_animation['pagination_style'] . " arp_pagination_top' id='arp_slider_" . $id . "_pagination_top'></div>";

        $container_width = $wrapper_width_value . 'px;';
        $tablestring .= "<div class='ArpTemplate_main' id=\"ArpTemplate_main\" style='clear:both;width:$container_width'>";

        $tablestring .= "<div class='arp_width_guide_line'>";
        $tablestring .= "<div class='arp_width_guide_line_box' id='arp_width_guide_line_box'>";
        $tablestring .= $wrapper_width_value . "px";
        $tablestring .= "</div>";
        $tablestring .= "</div>";

        $tablestring .= "<div id='arp_inlinestyle'></div> ";

        $tablestring .= "<div class='arp_inlinescript'>";

        $global_column_width = "";

        if ($column_settings['all_column_width'] && $column_settings['all_column_width'] > 0) {
            $global_column_width = 'width:' . $column_settings['all_column_width'] . 'px;';
        }


        $tablestring .= "<input type='hidden' name='template' id='arp_template_id_hidden_inlinescript' value='" . esc_html( $template_settings['template'] ) . "' />";
        $tablestring .= "<input type='hidden' name='template_type' id='arp_template_type_editor' value='" . esc_html( $template_type ) . "' />";
        $tablestring .= "<input type='hidden' name='is_tbl_preview' id='is_tbl_preview' value='" . esc_html( $is_tbl_preview ) . "' /></div>";
        $tablestring .= "<input type='hidden' name='column_level_dynamic_array' id='column_level_dynamic_array' />";

        

        $tablestring .= "<input type='hidden' id='arp_template_name' name='arp_template_name' value='arplitetemplate_" . esc_html( $template_name ) . "' />";

        $template_id = $template_settings['template'];
        $color_scheme = 'arp' . $template_settings['skin'];
        if ($hover_type == 0 and $is_tbl_preview != 2) {
            $hover_class = 'hover_effect';
        } else if ($hover_type == 1 and $is_tbl_preview != 2) {
            $hover_class = 'shadow_effect';
        } else {
            $hover_class = 'no_effect';
        }

        $animation_class = 'no_animation';

        $slider_pagination_container = '';

        $tablestring .= "<div class='ArpPriceTable arp_admin_template_editor arplite_price_table_" . $template_name . " arplitetemplate_" . $template_name . " " . $color_scheme . " " . $slider_pagination_container . "'";


        if (isset($column_animation['is_animation']) and $column_animation['is_animation'] == 'yes' and $is_tbl_preview != 2 and $is_tbl_preview != 3) {
            $data_items = $column_animation['visible_column'] ? $column_animation['visible_column'] : 1;
            $scrolling_columns = $column_animation['scrolling_columns'] ? $column_animation['scrolling_columns'] : 1;
            $navigation = ( $column_animation['navigation'] == 1 ) ? 1 : 0;
            $autoplay = ( $column_animation['autoplay'] == 1 ) ? 1 : 0;
            $sliding_effect = $column_animation['sliding_effect'] ? $column_animation['sliding_effect'] : 'slide';
            $transition_speed = $column_animation['transition_speed'] ? $column_animation['transition_speed'] : '500';
            $hide_caption = $column_animation['hide_caption'] ? $column_animation['hide_caption'] : 0;
            $infinite = $column_animation['is_infinite'] ? $column_animation['is_infinite'] : 0;
            $easing_effect = $column_animation['easing_effect'] ? $column_animation['easing_effect'] : 'swing';

            $tablestring .= "data-animate='true' data-id='" . $table_id . "' data-items='" . $data_items . "' data-scroll='" . $scrolling_columns . "' data-autoplay='" . $autoplay . "' data-effect='" . $sliding_effect . "' data-speed='" . $transition_speed . "' data-caption='" . $hide_caption . "' data-infinite='" . $infinite . "' data-easing='" . $easing_effect . "'";
        }
        $tablestring .= ">";

        $navigation = "";
        $ref_template = $general_settings['reference_template'];

        $tablestring .= "<div id='ArpPricingTableColumns'";
        $tablestring .= ">";

        $x = 0;
        if ($opts['columns'] and count($opts['columns']) > 0) {

            $header_img = array();
            foreach ($opts['columns'] as $j => $columns) {
                if (isset($columns['arp_header_shortcode']) && $columns['arp_header_shortcode'] != '')
                    $header_img[] = 1;
                else
                    $header_img[] = 0;
            }
            $new_arr = array();
            if (is_array($col_ord_arr) && count($col_ord_arr) > 0) {
                foreach ($col_ord_arr as $key => $value) {
                    $new_value = str_replace('main_', '', $value);
                    $new_col_id = $new_value;
                    foreach ($opts['columns'] as $j => $columns) {
                        if ($new_col_id == $j) {
                            if ($columns['is_caption'] != 1) {
                                $new_arr['columns'][$new_col_id] = $columns;
                            }
                        }
                    }
                }
            } else {
                $new_arr = $opts;
            }


            foreach ($opts['columns'] as $j => $column) {
                if ($column['is_caption'] == 1) {
                    $caption_column[] = 'yes';
                } else {
                    $caption_column[] = 'no';
                }
            }
            if (in_array('yes', $caption_column)) {
                $has_caption = 1;
            } else {
                $has_caption = 0;
            }
            $column_count = 1;
            foreach ($opts['columns'] as $j => $columns) {
                $col_num = str_replace('column_', '', $j);
                if ($columns['is_caption'] == 1 and $template_feature['caption_style'] == 'default') {
                    $inlinecolumnwidth = "";
                    $columns["column_width"] = (int) $columns["column_width"];
                    if ($columns["column_width"] != "") {
                        $inlinecolumnwidth = 'width:' . $columns["column_width"] . 'px';
                    } else {
                        if ($column_settings['is_responsive'] != 1) {
                            $inlinecolumnwidth = $global_column_width;
                        }
                    }
                    $column_highlight = $opts['columns'][$j]['column_highlight'];
                    if ($column_highlight && $column_highlight == 1 and $is_table_preview != 2)
                        $highlighted_column = 'column_highlight';


                    $tablestring .= "<div class='ArpPricingTableColumnWrapper no_transition  maincaptioncolumn " . $animation_class . " style_" . $j . " $shadow_style' style='";
                    if ($column_settings['hide_caption_column'] && $column_settings['hide_caption_column'] == 1) {
                        $tablestring .= "display:none;";
                    }

                    $tablestring .= $inlinecolumnwidth . "' id='main_" . $j . "' data-col-id='main_" . $j . "'  is_caption='1' data-template_id='" . $ref_template . "' data-level='column_level_options' data-type='caption_column_buttons' >";

                    $tablestring .= '<input type="hidden" value="1" name="caption_column_0" id="caption_column">';



                    $tablestring .= "<div class='arpplan ";
                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "maincaptioncolumn";
                    } else {
                        $tablestring .= $j . " ";
                    } if ($x % 2 == 0) {
                        $tablestring .= " arpdark-bg ArpPriceTablecolumndarkbg";
                    } $tablestring .= "' style='";
                    $tablestring .= "' >";

                    $tablestring .= "<div class='planContainer'>";
                    $tablestring .= "<div class='arp_column_content_wrapper'>";

                    if (in_array(1, $header_img))
                        $header_cls = 'has_header_code';

                    $tablestring .= "<div class='arpcolumnheader " . $header_cls . "' data-column='main_" . $j . "' >";

                    if ($columns['is_caption'] == 1) {
                        if ($template_feature['caption_title'] == 'default') {
                            if ($template == 'arplitetemplate_1' && in_array(1, $header_img))
                                $header_cls = 'has_header_code';
                            else
                                $header_cls = '';

                            $tablestring .= "<div class='arpcaptiontitle " . $header_cls . "' id='column_header' data-column='main_column_0' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='caption_column_buttons'><div class='html_content_first toggle_step_first'>" . do_shortcode($columns['html_content']) . "</div></div>";
                        }
                        else if ($template_feature['caption_title'] == 'style_1') {
                            $tablestring .= "<div class='arpcaptiontitle' id='column_header' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='caption_column_buttons' data-column='main_column_0'>
                                                
                                                <div class='html_content_first toggle_step_first arpcaptiontitle_style_1'>" . do_shortcode($columns['html_content']) . "</div>
                                            </div>";
                        }
                    } else {
                        $tablestring .= "<div class='arppricetablecolumntitle' id='column_header' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='caption_column_buttons'>
                                            <div class='bestPlanTitle package_title_first toggle_step_first'>" . do_shortcode($columns['package_title']) . "</div>
                                        </div>
                                        <div class='arppricetablecolumnprice' data-column='main_" . $j . "'>" . do_shortcode($columns['html_content']) . "</div>";
                    }

                    $tablestring .= "</div>
                        <div class='arpbody-content arppricingtablebodycontent' id='arppricingtablebodycontent' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='body_level_options' data-type='caption_column_buttons'>
                            <ul class='arp_opt_options arppricingtablebodyoptions' id='column_column_" . $x . "' style='text-align:" . $columns['body_text_alignment'] . "'>";

                    $r = 0;

                    $row_order = isset($opts['columns'][$j]['row_order']) ? $opts['columns'][$j]['row_order'] : "";

                    if ($row_order && is_array($row_order)) {
                        $rows = array();
                        asort($row_order);
                        $ji = 0;
                        $maxorder = max($row_order) ? max($row_order) : 0;
                        foreach ($opts['columns'][$j]['rows'] as $rowno => $row) {
                            $row_order[$rowno] = isset($row_order[$rowno]) ? $row_order[$rowno] : ($maxorder + 1);
                        }
                        foreach ($row_order as $row_id => $order_id) {
                            if ($opts['columns'][$j]['rows'][$row_id]) {
                                $rows['row_' . $ji] = $opts['columns'][$j]['rows'][$row_id];
                                $ji++;
                            }
                        }
                        $opts['columns'][$j]['rows'] = $rows;
                    }
                    $column_count++;
                    $row_count = 0;
                    for ($ri = 0; $ri <= $maxrowcount; $ri++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $ri]) ? $opts['columns'][$j]['rows']['row_' . $ri] : array();

                        if ($columns['is_caption'] == 1) {
                            if (($ri + 1) % 2 == 0) {
                                $cls = 'rowlightcolorstyle';
                            } else {
                                $cls = '';
                            }
                        } else {
                            if ($column_count % 2 == 0) {
                                if (($ri + 1) % 2 == 0) {
                                    $cls = 'rowdarkcolorstyle';
                                } else {
                                    $cls = '';
                                }
                            } else {
                                if (($ri + 1) % 2 == 0) {
                                    $cls = 'rowlightcolorstyle';
                                } else {
                                    $cls = '';
                                }
                            }
                        }

                        if (($ri + 1 ) % 2 == 0) {
                            $cls .= " arp_even_row";
                        } else {
                            $cls .= " arp_odd_row";
                        }
                        if ($rows['row_description'] == '') {
                            $rows['row_description'] = '';
                        }

                        $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                        $tablestring .= "<li data-column='main_" . $j . "' class='arpbodyoptionrow " . $cls . " " . $li_class . " arp_" . $j . "_row_" . $row_count . "' id='arp_row_" . $ri . "' data-row-id='arp_row_".$ri."' style='text-align:";
                        $tablestring .= "' data-template_id='" . $ref_template . "' data-level='body_li_level_options' data-type='caption_column_buttons' ><span class='toggle_step_first' title='";
                        $tablestring .= "'>" . stripslashes_deep($rows['row_description']) . "</span></li>";
                        $row_count++;
                    }

                    $tablestring .= "</ul>
                        </div>";

                    //footer text class assign start.
                    $footer_hover_class = '';
                    if ($columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1) {

                        $footer_hover_class .= ' has_footer_content';
                        if ($columns['footer_content_position'] == 0) {
                            $footer_hover_class .= " footer_below_content";
                        } else {
                            $footer_hover_class .= " footer_above_content";
                        }
                    } else {
                        $footer_hover_class = "";
                    }
                    //footer text class assign end.

                    if ($template_feature['button_position'] == 'default') {
                        $tablestring .= "<div class='arpcolumnfooter " . $footer_hover_class . "' data-template_id='" . $ref_template . "' data-level='footer_level_options' data-type='caption_column_buttons' id='arpcolumnfooter' data-column='main_" . $j . "'>";

                        $footer_content_below_btn = "";
                        if ($columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1) {
                            $footer_content_above_btn = "display:block;";
                        } else {
                            $footer_content_above_btn = "display:none;";
                        }

                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_before_content arp_footer_caption_column' style='{$footer_content_above_btn}'>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</div>";
                        }

                        if ($columns['button_text'] != '' && $columns['btn_img'] != "") {
                            $tablestring .= "<div class='arppricetablebutton' data-column='main_" . $j . "' style='text-align:center;'>
                                            <button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_". $col_num ."' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                            if ($columns['btn_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important; '";
                            } $tablestring .= ">";
                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step toggle_step_first'>";
                                $tablestring .= stripslashes_deep($columns['button_text']);
                                $tablestring .= "</span>";
                            } $tablestring .= "</button>";
                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                    }
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";


                    $col_no = explode('_', $j);

                    $tablestring .= "<div class='column_level_settings' id='column_level_settings_new' data-column='main_column_0'>";
                    $tablestring .= "<div class='btn-main rpt'>";

                        $tablestring .= "<div class='column_level_button_wrapper'>";
                            $tablestring .= "<div class='arp_btn' id='column_level_options__button_1' data-level='column_level_options' style='display:none;' title='" . esc_html__('Column Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Column Settings', 'arprice-responsive-pricing-table') . "' ></div>";

                            $tablestring .= "<div class='arp_btn' id='column_level_options__button_2' data-level='column_level_options' style='display:none;' title='" . esc_html__('Background and Font Color', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Background and Font Color', 'arprice-responsive-pricing-table') . "' ></div>";

                            $tablestring .= "<div class='arp_btn action_btn' col-id=" . $col_no[1] . " data-level='column_level_options' id='delete_column' style='display:none;' title='" . esc_html__('Delete Column', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Delete Column', 'arprice-responsive-pricing-table') . "'>";
                            
                                /* Delete Model Window */

                                $tablestring .= "<div class='delete_column_container' id='delete_column_container_" . $col_no[1] . "'>";
                                $tablestring .= "<div class='delete_column_arrow'></div>";
                                $tablestring .= "<div class='delete_column_title'>";
                                $tablestring .= esc_html__('Are you sure want to delete this column?', 'arprice-responsive-pricing-table');
                                $tablestring .= "</div>";
                                $tablestring .= "<div class='delete_column_buttons'>";
                                $tablestring .= "<button id='Model_Delete_Column_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_insert_btn arp_delete_column_btn delete_column'>" . esc_html__('Ok', 'arprice-responsive-pricing-table') . "</button>";
                                $tablestring .= "<button id='Model_Delete_Column_cancel_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_cancel_btn arp_cancel_delete_column_btn'>" . esc_html__('Cancel', 'arprice-responsive-pricing-table') . "</button>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";

                                /* Delete Model Window */
                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                        $tablestring .= "<div class='header_level_button_wrapper'>";

                            $tablestring .= "<div class='arp_btn' id='header_level_options__button_1' data-level='header_level_options' title='" . esc_html__('Header Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Header Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

                        $tablestring .= "</div>";

                        //caption level footer setting menu start

                        $footer_btn_cls = 'arp_footer_top_position';
                        if( in_array( $reference_template, array('arplitetemplate_7','arplitetemplate_8','arplitetemplate_11') ) ){
                            $footer_btn_cls = '';
                        }

                        $tablestring .= "<div class='footer_level_button_wrapper'>";
                            $tablestring .= "<div class='arp_btn' id='footer_level_options__button_1' data-level='footer_level_options' title='" . esc_html__("Footer General Settings", 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__("Footer General Settings", 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
                        $tablestring .= "</div>";
                        //caption level footer setting menu end

                        $tablestring .= "<div class='body_level_button_wrapper'>";
                            
                            $tablestring .= "<div class='arp_btn' id='body_level_options__button_1' data-level='body_level_options' style='display:none;' title='" . esc_html__('Content Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Content Settings', 'arprice-responsive-pricing-table') . "'></div>";

                            $tablestring .= "<div class='arp_btn action_btn' id='add_new_row' data-level='body_level_options' title='" . esc_html__('Add New Row', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add New Row', 'arprice-responsive-pricing-table') . "' data-id='" . $col_no[1] . "' style='display:none;'></div>";
                            
                        $tablestring .= "</div>";

                        $tablestring .= "<div class='body_li_level_button_wrapper'>";

                            $tablestring .= "<div class='arp_btn' id='body_li_level_options__button_1' data-level='body_li_level_options' title='" . esc_html__('Description Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Description Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

                            $tablestring .= "<div class='arp_btn pro_only' id='body_li_level_options__button_2' data-level='body_li_level_options' title='" . esc_html__('Tooltip Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Tooltip Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

                            $tablestring .= "<div class='arp_btn pro_only' id='body_li_level_options__button_3' data-level='body_li_level_options' title='" . esc_html__('CSS Properties', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('CSS Properties', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

                            $tablestring .= "<div class='arp_btn action_btn' id='copy_row' alt='' data-level='body_li_level_options' title='" . esc_html__('Duplicate Row', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Duplicate Row', 'arprice-responsive-pricing-table') . "' col-id='" . $col_no[1] . "' style='display:none;'></div>";

                            $tablestring .= "<div class='arp_btn action_btn' id='remove_row' row-id='' data-level='body_li_level_options' title='" . esc_html__('Delete Row', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Delete Row', 'arprice-responsive-pricing-table') . "' col-id='" . $col_no[1] . "' style='display:none;'>";
                                $tablestring .= "<div class='delete_row_container' id='delete_row_container_" . $col_no[1] . "'>";
                                    $tablestring .= "<div class='delete_row_arrow'></div>";
                                    $tablestring .= "<div class='delete_row_title'>";
                                        $tablestring .= esc_html__('Are you sure want to delete this row?', 'arprice-responsive-pricing-table');
                                    $tablestring .= "</div>";
                                    $tablestring .= "<div class='delete_row_buttons'>";
                                        $tablestring .= "<button id='Model_Delete_Row_Button_" . $col_no[1] . "' col-id='" . $col_no[1] . "' type='button' class='ribbon_insert_btn delete_row' row-id=''>" . esc_html__('Ok', 'arprice-responsive-pricing-table') . "</button>";
                                        $tablestring .= "<button id='Model_Delete_Row_Button_cancel_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_cancel_btn' row-id=''>" . esc_html__('Cancel', 'arprice-responsive-pricing-table') . "</button>";
                                    $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            $tablestring .= "</div>";

                        $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_level_options'>";


                    $tablestring .= "<div class='column_option_div' level-id='column_level_options__button_1' style='display:none;'>";
                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_option_div' level-id='column_level_options__button_2' >";
                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_option_div ".$footer_btn_cls."' level-id='footer_level_options__button_1'>";
                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_option_div' level-id='header_level_options__button_1' style='display:none;'>";
                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_option_div' level-id='body_level_options__button_1' style='display:none;'>";
                    $tablestring .= "</div>";

                    $tablestring .= "<input type='hidden' id='total_rows_" . $col_no[1] . "' value='" . esc_html( count($columns['rows']) ) . "' name='total_rows_" . $col_no[1] . "' />";

                    $tablestring .= "<div class='column_option_div width_362' level-id='body_li_level_options__button_1' style='display:none;'>";
                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_option_div' level-id='body_li_level_options__button_2' style='display:none;'>";
                    $tablestring .= "</div>";

                    $tablestring .= "<div class='column_option_div' level-id='body_li_level_options__button_3' style='display:none;'>";
                    $tablestring .= "</div>";

                    $tablestring .= "</div>";

                    $tablestring .= "</div>";


                    $tablestring .= "</div>";

                    $x++;
                } //only for caption column
                else if ($columns['is_caption'] == 1 and $template_feature['caption_style'] == 'style_1') {
                    for ($i = 0; $i <= $maxrowcount; $i++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $i]) ? $opts['columns'][$j]['rows']['row_' . $i] : array();
                        $caption_li[$i] = stripslashes_deep($rows['row_description']);
                    }
                } else if ($columns['is_caption'] == 1 and $template_feature['caption_style'] == 'style_2') {
                    for ($i = 0; $i <= $maxrowcount; $i++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $i]) ? $opts['columns'][$j]['rows']['row_' . $i] : array();
                        $caption_li[$i] = stripslashes_deep($rows['row_description']);
                    }
                }
            }

            $tablestring .= "<div class='arp_allcolumnsdiv' id='arp_allcolumnsdiv' style='float:none'>";

            $c = $x;
            if ($c == 0) {
                $c = $x = 1;
            }
            $new_arr = array();
            if (is_array($col_ord_arr) && count($col_ord_arr) > 0) {
                foreach ($col_ord_arr as $key => $value) {
                    $new_value = str_replace('main_', '', $value);
                    $new_col_id = $new_value;
                    foreach ($opts['columns'] as $j => $columns) {
                        if ($new_col_id == $j) {
                            if ($columns['is_caption'] != 1) {
                                $new_arr['columns'][$new_col_id] = $columns;
                            }
                        }
                    }
                }
            } else {
                $new_arr = $opts;
            }

            $counter = 1;
            foreach ($new_arr['columns'] as $j => $columns) {
                $col_num = str_replace('column_', '', $j);
                if ($columns['is_caption'] == 0) {
                    $inlinecolumnwidth = "";
                    $columns["column_width"] = (int) $columns["column_width"];
                    if ($columns["column_width"] != "") {
                        $inlinecolumnwidth = 'width:' . $columns["column_width"] . 'px';
                    } else {
                        if ($column_settings['is_responsive'] != 1) {
                            $inlinecolumnwidth = $global_column_width;
                        }
                    }
                    $shortcode_class = '';
                    $shortcode_class_array = $arpricelite_default_settings->arp_shortcode_custom_type();
                    
                    if (isset($columns['arp_shortcode_customization_style']) && '' != $columns['arp_shortcode_customization_style']) {
                        $shortcode_class .= $columns['arp_shortcode_customization_size'] . ' ' . $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'];
                    }

                    $column_highlight = $opts['columns'][$j]['column_highlight'];
                    if ($column_highlight && $column_highlight == 1 and $is_tbl_preview != 2)
                        $highlighted_column = 'column_highlight ';
                    else
                        $highlighted_column = '';

                    $col_no = explode('_', $j);
                    $tablestring .= "<div class='" . $highlighted_column . " ArpPricingTableColumnWrapper no_transition style_" . $j . " " . $hover_class . " " . $animation_class . " $shadow_style' id='main_column_" . $col_no[1] . "' data-col-id='main_column_" . $col_no[1] . "'  style='";  if ($c == 0) {
                        $tablestring .= "border-left:1px solid #DADADA;";
                    }

                    $tablestring .= $inlinecolumnwidth . "' is_caption='0' data-order='" . $counter . "' data-template_id='" . $ref_template . "' data-level='column_level_options' data-type='other_columns_buttons' "
                            . "data-column-footer-position='{$columns['footer_content_position']}'"
                            . ">";


                    $tablestring .= "<div class='arpplan ";
                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "maincaptioncolumn";
                    } else {
                        $tablestring .= "column_" . $c;
                    } if ($x % 2 == 0) {
                        $tablestring .= " arpdark-bg ArpPriceTablecolumndarkbg";
                    } $tablestring .= "'>";

                    $columns['ribbon_setting']['arp_ribbon'] = isset($columns['ribbon_setting']['arp_ribbon']) ? $columns['ribbon_setting']['arp_ribbon'] : "";
                    $tablestring .= "<div class='planContainer " . $columns['ribbon_setting']['arp_ribbon'] . "'>";
                    $tablestring .= "<div class='arp_column_content_wrapper'>";
                    if ($columns['arp_header_shortcode'] != '')
                        $header_cls = 'has_arp_shortcode';
                    else
                        $header_cls = '';
                    $columns_custom_ribbon_position = '';
                    if (isset($columns['ribbon_setting']) && $columns['ribbon_setting'] and $columns['ribbon_setting']['arp_ribbon'] != '' and $columns['ribbon_setting']['arp_ribbon_content'] != '') {
                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_6') {
                            if ($columns['ribbon_setting']['arp_ribbon_position'] == 'left') {
                                $columns_custom_ribbon_position = "left:{$columns['ribbon_setting']['arp_ribbon_custom_position_rl']}px;top:{$columns['ribbon_setting']['arp_ribbon_custom_position_top']}px;";
                            } else {
                                $columns_custom_ribbon_position = "right:{$columns['ribbon_setting']['arp_ribbon_custom_position_rl']}px;top:{$columns['ribbon_setting']['arp_ribbon_custom_position_top']}px;";
                            }
                        }
                        $basic_col = $arplite_mainoptionsarr['general_options']['arp_basic_colors'];
                        $ribbon_bg_col = $columns['ribbon_setting']['arp_ribbon_bgcol'];
                        $base_color = $ribbon_bg_col;
                        $base_color_key = array_search($base_color, $basic_col);
                        $gradient_color = $arplite_mainoptionsarr['general_options']['arp_basic_colors_gradient'][$base_color_key];
                        $ribbon_border_color = $arplite_mainoptionsarr['general_options']['arp_ribbon_border_color'][$base_color_key];
                        $tablestring .= "<div id='arp_ribbon_container' class='arp_ribbon_container arp_ribbon_" . strtolower($columns['ribbon_setting']['arp_ribbon_position']) . " " . $columns['ribbon_setting']['arp_ribbon'] . " ' style='" . $columns_custom_ribbon_position . "' >";
                        
                        $tablestring .= "<div class='arp_ribbon_content arp_ribbon_" . strtolower($columns['ribbon_setting']['arp_ribbon_position']) . "'>";

                        $tablestring .= $columns['ribbon_setting']['arp_ribbon_content'];

                        $tablestring .= "</div>";

                        $tablestring .= "</div>";
                    }

                    $tablestring .= "<div class='arpcolumnheader " . $header_cls . "'>";
                    if ($template_feature['header_shortcode_position'] == 'default' && ( $ref_template == 'arplitetemplate_2' or $ref_template == 'arplitetemplate_5' or $ref_template == 'arplitetemplate_26' )) {
                        $tablestring .= "<div class='arp_header_selection_new' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='other_columns_buttons' data-column='main_" . $j . "'>";
                    }
                    if ( $template_feature['header_shortcode_position'] == 'position_1') {

                        if ($template_feature['header_shortcode_position'] == 'position_1' && ( $ref_template == 'arplitetemplate_8' )) {
                            $tablestring .= "<div class='arp_header_selection_new' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='other_columns_buttons'  data-column='main_" . $j . "'>";
                        }
                        $tablestring .= "<div class='arp_header_shortcode'>";
                        if ($template_feature['header_shortcode_type'] == 'normal') {
                            $tablestring .= $arpricelite_form->arp_get_video_image($columns['arp_header_shortcode']);
                        } else if ($template_feature['header_shortcode_type'] == 'rounded_corner') {
                            $tablestring .= "<div class='arp_rounded_shortcode_wrapper'>";
                            $tablestring .= "<div class='rounded_corner_wrapper $shortcode_class'>";
                            $tablestring .= "<div class='rounded_corder $shortcode_class'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                            $tablestring .= "</div>";
                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                    }

                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "<div class='arpcaptiontitle' id='column_header' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='other_columns_buttons'  data-column='main_" . $j . "'>" . do_shortcode($columns['html_content']) . "</div>";
                    } else {

                        $tablestring .= "<div class='arppricetablecolumntitle' id='column_header' data-template_id='" . $ref_template . "' data-level='header_level_options' data-type='other_columns_buttons' data-column='main_" . $j . "'>
                                <div class='bestPlanTitle " . $title_cls . " package_title_first toggle_step_first'>" . do_shortcode($columns['package_title']) . "</div>";


                        if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_1') {
                            $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step toggle_step_first' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='column_description_level'>" . stripslashes_deep($columns['column_description']) . "</div>";
                        }

                        if ($template_feature['header_shortcode_position'] == 'position_1' && ( $ref_template == 'arplitetemplate_8' )) {
                            $tablestring .= "</div>";
                        }
                        $tablestring .= "</div>";

                        if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_3') {
                            $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step toggle_step_first' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='column_description_level'>" . stripslashes_deep($columns['column_description']) . "</div>";
                        }

                        if ($template_feature['button_position'] == 'position_2') {

                            $tablestring .= "<div class='arpcolumnfooter' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='footer_level_options' data-type='other_columns_buttons'>";
                            
                            $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";

                            $footer_content_below_btn = "";
                            if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1 and $template_feature['has_footer_content'] == 1)
                                $footer_content_above_btn = "display:block;";
                            else
                                $footer_content_above_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";
                                $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }

                            if (isset($columns['button_background_color']) && $columns['button_background_color'] != '') {
                                $button_background_color = $columns['button_background_color'];
                            } else {
                                $button_background_color = '';
                            }

                            $tablestring .= "<div class='arppricetablebutton' data-column='main_" . $j . "' style='text-align:center;'>";
                            $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_". $col_num ."' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                            if ($columns['btn_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                            } else {
                                $tablestring .= "style='background:" . $button_background_color . "'";
                            }

                            $tablestring .= ">";

                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step toggle_step_first'>";
                                $tablestring .= stripslashes_deep($columns['button_text']);
                                $tablestring .= "</span>";
                            } $tablestring .= "</button>";

                            $tablestring .= "</div>";

                            $footer_content_below_btn = "";
                            if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                                $footer_content_below_btn = "display:block;";
                            else
                                $footer_content_below_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";
                                $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }

                            $tablestring .= "</div>";
                        }

                        if ($template_feature['header_shortcode_position'] == 'default') {
                            if ($template_feature['header_shortcode_type'] == 'normal') {
                                $tablestring .= "<div class='arp_header_shortcode'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                            } else if ($template_feature['header_shortcode_type'] == 'rounded_border') {
                                $tablestring .= "<div class='arp_rounded_shortcode_wrapper $shortcode_class'>";
                                $tablestring .= "<div class='rounded_corner_wrapper $shortcode_class'>";
                                $tablestring .= "<div class='rounded_corder $shortcode_class'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            }
                        }
                        if ($template_feature['header_shortcode_position'] == 'default') {
                            $tablestring .= "</div>";
                        }
                        if ($template_feature['amount_style'] == 'style_2')
                            $amount_style_cls = 'style_2';
                        $tablestring .= "<div class='arppricetablecolumnprice " . ( isset($amount_style_cls) ? $amount_style_cls : "" ) . "' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='pricing_level_options' data-type='other_columns_buttons' >";


                        if ($template_feature['amount_style'] == 'default') {
                            $tablestring .= "<div class='arp_price_wrapper'>";
                            if ($ref_template == 'arplitetemplate_1') {
                                $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                                $tablestring .= $columns['price_text'];
                                $tablestring .= '</span>';
                            } else {

                                $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                                $tablestring .= $columns['price_text'];
                                $tablestring .= '</span>';


                            }
                            $tablestring .= "</div>";

                            $tablestring .= isset($columns['html_content']) ? $columns['html_content'] : "";
                        } else if ($template_feature['amount_style'] == 'style_1') {
                            $tablestring .= "<div class='arp_pricename' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='pricing_level_options'>";
                            $tablestring .= "<div class='arp_price_wrapper'  data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='pricing_level_options' >";
                            $tablestring .= "<span class=\"arp_price_value\">";
                            $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                            $tablestring .= $columns['price_text'];
                            $tablestring .= '</span>';

                            $tablestring .= "</span>";
                            $tablestring .= "<span class=\"arp_price_duration\">";
                            $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                            $tablestring .= $columns['price_label'];
                            $tablestring .= '</span>';

                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                            $tablestring .= "</div>";
                            $columns['html_content'] = isset($columns['html_content']) ? $columns['html_content'] : "";
                            $tablestring .= do_shortcode($columns['html_content']);
                        } else if ($template_feature['amount_style'] == 'style_2') {
                            $tablestring .= "<div class='arp_price_wrapper'>";
                            if ($template == 'arplitetemplate_11') {
                                $tablestring .= "<div class='arp_pricename_selection_new' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='pricing_level_options' data-type='other_columns_buttons'>";
                            }
                            $tablestring .= "<span class=\"arp_price_duration\">";
                            $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                            $tablestring .= $columns['price_label'];
                            $tablestring .= '</span>';

                            $tablestring .= "</span>";
                            $tablestring .= "<span class=\"arp_price_value\">";
                            $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                            $tablestring .= $columns['price_text'];
                            $tablestring .= '</span>';

                            $tablestring .= "</span>";

                            if ($template == 'arplitetemplate_11') {
                                $tablestring .= "</div>";
                            }
                            $tablestring .= "</div>";
                            $columns['html_content'] = isset($columns['html_content']) ? $columns['html_content'] : "";
                            $tablestring .= do_shortcode($columns['html_content']);
                        }

                        if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_2') {
                            $tablestring .= "<div class='custom_ribbon_wrapper'>";
                            $tablestring .= "<div class='column_description column_description_first_step toggle_step_first' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='column_description_level'>" . stripslashes_deep($columns['column_description']) . "</div>";
                            $tablestring .= "</div>";
                        }

                        if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_4') {
                            $first_desc_blank = $second_desc_blank = $third_desc_blank = '';
                            $first_desc_blank = empty($columns['column_description']) ? ' desc_content_blank' : '';
                            $second_desc_blank = empty($columns['column_description_second']) ? ' desc_content_blank' : '';
                            $third_desc_blank = empty($columns['column_description_third']) ? ' desc_content_blank' : '';

                            $tablestring .= "<div class='column_description column_description_first_step toggle_step_first " . $first_desc_blank . "' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='column_description_level'>" . stripslashes_deep($columns['column_description']) . "</div>";
                        }

                        if ($template_feature['button_position'] == 'position_1') {

                            $tablestring .= "<div class='arpcolumnfooter' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='footer_level_options' data-type='other_columns_buttons'>";

                            $footer_content_above_btn = "";
                            if (isset($columns['footer_content']) && $columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                                $footer_content_above_btn = "display:block;";
                            else
                                $footer_content_above_btn = "display:none;";

                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";
                                $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                                $tablestring .= isset($columns['footer_content']) ? $columns['footer_content'] : '';
                                $tablestring .= "</span>";
                                $tablestring .= "</div>";
                            }

                            $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";
                            $tablestring .= "<div class='arppricetablebutton' data-column='main_" . $j . "' style='text-align:center;'>
                                                        <button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_" . $col_num . "' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                            if ($columns['btn_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                            }  $tablestring .= ">";
                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step toggle_step_first'>";
                                $tablestring .= stripslashes_deep($columns['button_text']);
                                $tablestring .= "</span>";
                            } $tablestring .= "</button>";

                            $tablestring .= "</div>";
                            $footer_content_below_btn = "";
                            if (isset($columns['footer_content']) && $columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                                $footer_content_below_btn = "display:block;";
                            else
                                $footer_content_below_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";
                                $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }
                            $tablestring .= "</div>";
                        }
                        $tablestring .= "</div>";
                    }
                    if ( $template_feature['header_shortcode_position'] == 'position_2') {

                        $tablestring .= "<div class='arp_header_shortcode'>";
                        if ($template_feature['header_shortcode_type'] == 'normal')
                            $tablestring .= do_shortcode($columns['arp_header_shortcode']);
                        else if ($template_feature['header_shortcode_type'] == 'rounded_border') {
                            $tablestring .= "<div class='arp_rounded_shortcode_wrapper'>";
                            $tablestring .= "<div class='rounded_corner_wrapper $shortcode_class'>";
                            $tablestring .= "<div class='rounded_corder $shortcode_class'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                            $tablestring .= "</div>";
                            $tablestring .= "</div>";
                        }
                        $tablestring .= "</div>";
                    }

                    $tablestring .= "</div>";


                    if ($template_feature['button_position'] == 'position_3') {
                        $tablestring .= "<div style='float:left;width:100%;'>";
                        $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step toggle_step_first' data-level='column_description_level' data-type='other_columns_buttons' data-template_id='" . $ref_template . "' data-column='main_" . $j . "'>" . stripslashes_deep($columns['column_description']) . "</div>";
                        $tablestring .= "<div class='arpcolumnfooter " . $footer_hover_class . "' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='footer_level_options' data-type='other_columns_buttons'>";
                        $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";

                        $footer_content_above_btn = "";
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                            $footer_content_above_btn = "display:block;";
                        else
                            $footer_content_above_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";
                            $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";
                            $tablestring .= "</div>";
                        }

                        $tablestring .= "<div class='arppricetablebutton' data-column='main_" . $j . "' style='text-align:center;'>
                                                        <button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_". $col_num."' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                        if ($columns['btn_img'] != "") {
                            $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                        } $tablestring .= ">";
                        if ($columns['btn_img'] == "") {
                            $tablestring .= "<span class='btn_content_first_step toggle_step_first'>";
                            $tablestring .= stripslashes_deep($columns['button_text']);
                            $tablestring .= "</span>";
                        } $tablestring .= "</button>";
                        $tablestring .= "</div>";

                        $footer_content_below_btn = "";
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                            $footer_content_below_btn = "display:block;";
                        else
                            $footer_content_below_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";
                            $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                        $tablestring .= "</div>";
                    }

                    $tablestring .= "<div class='arpbody-content arppricingtablebodycontent' id='arppricingtablebodycontent' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='body_level_options' data-type='other_columns_buttons'>";

                    $tablestring .= "<ul class='arp_opt_options arppricingtablebodyoptions' id='column_" . $j . "' style='text-align:" . $columns['body_text_alignment'] . ";'>";

                    $r = 0;

                    $row_order = isset($new_arr['columns'][$j]['row_order']) ? $new_arr['columns'][$j]['row_order'] : array();
                    if ($row_order && is_array($row_order)) {
                        $rows = array();
                        asort($row_order);
                        $ji = 0;
                        $maxorder = max($row_order) ? max($row_order) : 0;
                        foreach ($new_arr['columns'][$j]['rows'] as $rowno => $row) {
                            $row_order[$rowno] = isset($row_order[$rowno]) ? $row_order[$rowno] : ($maxorder + 1);
                        }

                        foreach ($row_order as $row_id => $order_id) {
                            if ($new_arr['columns'][$j]['rows'][$row_id]) {
                                $rows['row_' . $ji] = $new_arr['columns'][$j]['rows'][$row_id];
                                $ji++;
                            }
                        }

                        $new_arr['columns'][$j]['rows'] = $rows;
                    }
                    $column_count++;
                    $row_count = 0;
                    for ($ri = 0; $ri <= $maxrowcount; $ri++) {
                        $rows = isset($new_arr['columns'][$j]['rows']['row_' . $ri]) ? $new_arr['columns'][$j]['rows']['row_' . $ri] : array();

                        if ($columns['is_caption'] == 1) {
                            if (($ri + 1) % 2 == 0) {
                                $cls = 'rowlightcolorstyle';
                            } else {
                                $cls = '';
                            }
                        } else {

                            if ($column_count % 2 == 0) {
                                if (($ri + 1) % 2 == 0) {
                                    $cls = 'rowdarkcolorstyle';
                                } else {
                                    $cls = '';
                                }
                            } else {
                                if (($ri + 1) % 2 == 0) {
                                    $cls = 'rowlightcolorstyle';
                                } else {
                                    $cls = '';
                                }
                            }
                        }

                        if (($ri + 1 ) % 2 == 0) {
                            $cls .= " arp_even_row";
                        } else {
                            $cls .= " arp_odd_row";
                        }
                        if ($rows['row_description'] == '') {
                            $rows['row_description'] = '';
                        }
                        if ($template_feature['caption_style'] == 'style_1' and $template_feature['list_alignment'] != 'default') {
                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= "<li data-template_id='" . $ref_template . "' data-level='body_li_level_options' data-type='other_columns_buttons' data-column='main_" . $j . "' class='arpbodyoptionrow arp_" . $j . "_row_" . $row_count . " " . $cls;

                            $tablestring .= " " . $li_class . "' data-row-id='arp_row_" . $ri . "' id='arp_row_" . $ri . "'>";

                            $tablestring .= "<span class='caption_li'>";
                            $tablestring .= "<div class='row_label_first_step toggle_step_first'>" . stripslashes_deep($rows['row_label']) . "</div>";
                            $tablestring .= "</span>";
                            $tablestring .= "<span class='caption_detail' ";
                            $tablestring .= " title='";
                            $tablestring .= "'>";
                            $tablestring .= "<div class='row_description_first_step toggle_step_first'>" . stripslashes_deep($rows['row_description']) . "</div>";
                            $tablestring .= "</span>
                                            </li>";
                        } else if ($template_feature['caption_style'] == 'style_2') {
                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;

                            $tablestring .= "<li data-template_id='" . $ref_template . "' data-level='body_li_level_options' data-type='other_columns_buttons' data-column='main_" . $j . "' class='arpbodyoptionrow arp_" . $j . "_row_" . $row_count . " " . $cls;
                            $tablestring .= " " . $li_class . "' data-row-id='arp_row_" . $ri . "'  id='arp_row_" . $ri . "'";

                            $tablestring .= ">";
                            $tablestring .= "<span class='caption_detail' ";

                            $tablestring .= "title='";
                            if ($rows['row_tooltip'] != "") {
                                $tablestring .= esc_html($rows['row_tooltip']);
                            }
                            $tablestring .= "'>";
                            $tablestring .= "<div class='row_description_first_step toggle_step_first'>" . stripslashes_deep($rows['row_description']) . "</div>";
                            $tablestring .= "</span>";
                            $tablestring .= "<span class='caption_li'>";
                            $tablestring .= "<div class='row_label_first_step toggle_step_first'>" . stripslashes_deep($rows['row_label']) . "</div>";
                            $tablestring .= "</span>";
                            $tablestring .= "</li>";
                        } else if ($template_feature['list_alignment'] != 'default') {
                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= "<li data-template_id='" . $ref_template . "' data-level='body_li_level_options' data-type='other_columns_buttons' data-column='main_" . $j . "' class='arpbodyoptionrow arp_" . $j . "_row_" . $row_count . " " . $cls;
                            $tablestring .= " " . $li_class . "' data-row-id='arp_row_" . $ri . "'  id='arp_row_" . $ri . "' style='text-align:" . $template_feature['list_alignment'] . "' >";
                            $tablestring .= "<span class=''";
                            $tablestring .= " title='";
                            if ($rows['row_tooltip'] != "") {
                                $tablestring .= esc_html($rows['row_tooltip']);
                            }
                            $tablestring .= "'>";
                            $tablestring .= "<div class='row_description_first_step toggle_step_first'>" . stripslashes_deep($rows['row_description']) . "</div>";
                            $tablestring .= "</span>
                                           </li>";
                        } else {
                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= "<li data-template_id='" . $ref_template . "' data-level='body_li_level_options' data-type='other_columns_buttons' data-column='main_" . $j . "' class='arpbodyoptionrow arp_" . $j . "_row_" . $row_count . " " . $cls;
                            $tablestring .= " " . $li_class . "' data-row-id='arp_row_" . $ri . "'  id='arp_row_" . $ri . "' style='text-align:";  $tablestring .= "' >";
                            $tablestring .= "<span class='' ";
                            $tablestring .= " title='";
                            $tablestring .= "'>";
                            $tablestring .= "<div class='row_description_first_step toggle_step_first'>" . stripslashes_deep($rows['row_description']) . "</div>";
                            $tablestring .= "</span>
                                           </li>";
                        }
                        $row_count++;
                    }
                    $tablestring .= "</ul>";
                    $tablestring .= "</div>";


                    // TMP5


                    if ($template_feature['amount_style'] == 'style_3') {
                        $tablestring .= "<div class='arppricetablecolumnprice' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='pricing_level_options' data-type='other_columns_buttons' >";
                        $tablestring .= "<div class='arp_price_wrapper'>";

                        $tablestring .= "<span class=\"arp_price_duration\">";
                        $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                        $tablestring .= $columns['price_label'];
                        $tablestring .= '</span>';

                        $tablestring .= "</span>";
                        $tablestring .= "<span class=\"arp_price_value\">";
                        $tablestring .= "<span class='price_text_first_step toggle_step_first'>";
                        $tablestring .= $columns['price_text'];
                        $tablestring .= '</span>';

                        $tablestring .= "</span>";
                        $tablestring .= "</div>";
                        $columns['html_content'] = isset($columns['html_content']) ? $columns['html_content'] : "";
                        $tablestring .= do_shortcode($columns['html_content']);


                        if ($template_feature['button_position'] == 'position_4') {

                            $footer_hover_class = "";
                            if ($columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1) {
                                $footer_hover_class .= ' has_footer_content';
                                if ($columns['footer_content_position'] == 0) {
                                    $footer_hover_class .= " footer_below_content";
                                } else {
                                    $footer_hover_class .= " footer_above_content";
                                }
                            } else {
                                $footer_hover_class = "";
                            }

                            $tablestring .= "<div class='arpcolumnfooter " . $footer_hover_class . "' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='footer_level_options' data-type='other_columns_buttons'>";
                            $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";

                            $footer_content_above_btn = "";
                            if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                                $footer_content_above_btn = "display:block;";
                            else
                                $footer_content_above_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";
                                $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }

                            $tablestring .= "<div class='arppricetablebutton' data-column='main_" . $j . "' style='text-align:center;'>
                                                        <button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_".$col_num."' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                            if ($columns['btn_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                            } $tablestring .= ">";
                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step toggle_step_first'>";
                                $tablestring .= stripslashes_deep($columns['button_text']);
                                $tablestring .= "</span>";

                                
                            } $tablestring .= "</button>";

                            $tablestring .= "</div>";

                            $footer_content_below_btn = "";
                            if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                                $footer_content_below_btn = "display:block;";
                            else
                                $footer_content_below_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";
                                $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";
                                $tablestring .= "</div>";
                            }

                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                    }

                    if ($template_feature['button_position'] == 'default') {

                        $footer_hover_class = "";
                        if ($columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1) {
                            $footer_hover_class .= ' has_footer_content';
                            if ($columns['footer_content_position'] == 0) {
                                $footer_hover_class .= " footer_below_content";
                            } else {
                                $footer_hover_class .= " footer_above_content";
                            }
                        } else {
                            $footer_hover_class = "";
                        }

                        $tablestring .= "<div class='arpcolumnfooter " . $footer_hover_class . "' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='footer_level_options' data-type='other_columns_buttons'>";

                        if ($template_feature['second_btn'] == true && $columns['button_s_text'] != '') {
                            $has_s_btn = 'has_second_btn';
                        } else {
                            $has_s_btn = 'no_second_btn';
                        }

                        $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";


                        $footer_content_above_btn = "";
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                            $footer_content_above_btn = "display:block;";
                        else
                            $footer_content_above_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";
                            $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }

                        $tablestring .= "<div class='arppricetablebutton' data-column='main_" . $j . "' style='text-align:center;'>";
                        $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn " . $has_s_btn . "' id='bestPlanButton_".$col_num."' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' data-column='main_" . $j . "' ";
                        if ($columns['btn_img'] != "") {
                            $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                        } $tablestring .= ">";
                        if ($columns['btn_img'] == "") {
                            $tablestring .= "<span class='btn_content_first_step toggle_step_first'>";
                            $tablestring .= stripslashes_deep($columns['button_text']);
                            $tablestring .= "</span>";
                        } $tablestring .="</button>";

                        if ($template_feature['second_btn'] == true && $columns['button_s_text'] != '') {
                            if ($columns['button_text'] != '') {
                                $has_f_btn = 'has_first_btn';
                            } else {
                                $has_f_btn = 'no_first_btn';
                            }
                            $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_s_size']) . "_btn SecondBestPlanButton " . $has_f_btn . "' id='bestPlanButton_".$col_num."' data-template_id='" . $ref_template . "' data-level='second_button_options' data-type='other_columns_buttons' data-column='main_" . $j . "' ";
                            if ($columns['button_s_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['button_s_img'] . ") no-repeat !important;width:" . $columns['btn_s_img_width'] . "px;height:" . $columns['btn_s_img_height'] . "px;' ";
                            } $tablestring .= ">";
                            if ($columns['button_s_img'] == "") {
                                $tablestring .= stripslashes_deep($columns['button_s_text']);
                            } $tablestring .="</button>";
                        }
                        $tablestring .= "</div>";

                        $footer_content_below_btn = '';
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                            $footer_content_below_btn = "display:block;";
                        else
                            $footer_content_below_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";
                            $tablestring .= "<span class='footer_content_first_step toggle_step_first'>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                    }

                    if ($template_feature['column_description'] == 'enable' and $template_feature['column_description_style'] == 'after_button') {
                        $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step toggle_step_first' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-type='other_columns_buttons' data-level='column_description_level'>" . stripslashes_deep($columns['column_description']) . "</div>";
                    }

                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";


                    /* Dynamic Button Options */
                    $col_no = explode('_', $j);
                    include(ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_preview_editor_option.php');
                    $tablestring .= "</div>"; 

                    $c++;

                    if ($x % 5 == 0) {
                        $c = 1;
                    }
                    $x++;
                }
                $counter++;
            }

            $tablestring .= "</div>";
        } else {
            $tablestring .= esc_html__('Please select valid table', 'arprice-responsive-pricing-table');
        }



        $tablestring .= "<div id='arp_all_font_listing' style='display:none;'>";

        $tablestring .= "<li class='arp_selectbox_option' data-value='inherit' data-label='" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "'>" . esc_html__('Inherit from Theme', 'arprice-responsive-pricing-table' ) . "</li>";
        
        $tablestring .= "<ol class='arp_selectbox_group_label'>" . esc_html__('Default Fonts', 'arprice-responsive-pricing-table') . "</ol>";
        $tablestring .= $default_fonts_string;
        $tablestring .= "<ol class='arp_selectbox_group_label google_fonts_string_retrive'>" . esc_html__('Google Fonts', 'arprice-responsive-pricing-table') . "</ol>";
        
        $tablestring .= "</div>";


        $tablestring .= "</div>";



        $tablestring .= "</div>";
        $tablestring .= "</div>";
        if (isset($column_animation['is_animation']) and $column_animation['is_animation'] == 'yes' and $is_tbl_preview != 2 and $column_animation['is_pagination'] == 1 and ( $column_animation['pagination_position'] == 'Bottom' or $column_animation['pagination_position'] == 'Both' ))
            $tablestring .= "<div class='arp_pagination
 " . $column_animation['pagination_style'] . " arp_pagination
_bottom' id='arp_slider
_" . $id . "_pagination_bottom'></div>";

        $tablestring = $arplite_pricingtable->arprice_font_icon_size_parser($tablestring);

        $tablestring = $arplite_pricingtable->arp_remove_style_tag($tablestring);
        $tablestring .='<div class="google_fonts_string_block" style="display:none;">'.$google_fonts_string.'</div>';
        return $tablestring;
    }

}
?>