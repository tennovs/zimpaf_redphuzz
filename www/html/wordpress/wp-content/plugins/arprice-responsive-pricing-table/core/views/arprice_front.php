<?php

if (!function_exists('arplite_get_pricing_table_string')) {

    function arplite_get_pricing_table_string($table_id, $pricetable_name = "", $is_tbl_preview = 0) {
        $font_awesome_match = array();
        $arp_inc_effect_css = array();

        wp_enqueue_script('arplite_front_js');
        wp_enqueue_style('arplite_front_css');
        $is_enable_font_awesome = get_option('enable_font_loading_icon');
        
        if( is_array($is_enable_font_awesome) && in_array('enable_fontawesome_icon', $is_enable_font_awesome)){
            wp_enqueue_style('arplite_fontawesome_css');    
        }
        
        global $arplite_is_lightbox;
        global $wpdb, $arpricelite_form, $arplite_mainoptionsarr, $arplite_pricingtable, $arplite_templateresponsivearr, $arp_template_column_radius, $arpricelite_version, $arpricelite_default_settings, $arpricelite_img_css_version,$arpricelite_fonts;
        $arp_responsive_arr = $arpricelite_default_settings->arprice_responsive_width_array();
        $arplite_mainoptionsarr = $arplite_pricingtable->arp_mainoptions();
        $arplite_templateresponsivearr = $arplite_pricingtable->arp_template_responsive_type_array();
        $arplite_col_wrapper_highlighted_height = $arpricelite_default_settings->arpricelite_default_highlighted_column_height_with_hover_effect();
        $arplite_col_wrapper_default_height = $arpricelite_default_settings->arpricelite_column_wrapper_default_height();
        $arp_col_wrapper_height = $arpricelite_default_settings->arprice_column_wrapper_height();

        $id = $table_id;
        $name = $pricetable_name;

        if ($is_tbl_preview && $is_tbl_preview == 1) {
            if (isset($_REQUEST['optid']) && $_REQUEST['optid'] != '') {
                $post_values = get_option($_REQUEST['optid']);
                
                if( false == $post_values || empty( $post_values ) ){
                    return false;
                }

                $filtered_data = json_decode( $post_values, true );

                $general_option = maybe_unserialize( $filtered_data['table_opt']['general_options'] );
                $opts = maybe_unserialize( $filtered_data['table_col_opt'] );
                $id = $arp_template_name = $table_id = intval( $_REQUEST['tbl'] );
                $is_animated = $filtered_data['table_opt']['is_animated'];
                $is_template = $filtered_data['table_opt']['is_template'];
            }
        } else {
            $sql = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = %d AND status = %s ", $id, 'published'));
            $table_id = $sql->ID;
            $sql_opt = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d ", $table_id));
            $is_template = $sql->is_template;
            $is_animated = $sql->is_animated;
            $opts = maybe_unserialize($sql_opt[0]->table_options);
            $general_option = maybe_unserialize($sql->general_options);
            $arp_template_name = $sql->template_name;
        }

        $table_cols = array();
        $table_cols = $table_cols_new = $opts['columns'];

        if (isset($is_template) && !empty($is_template) && $is_template == 1) {
            wp_register_style('arplitetemplate_' . $table_id . '_v' . $arpricelite_img_css_version . '_css', ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $table_id . '_v' . $arpricelite_img_css_version . '.css', array(), null);
            wp_enqueue_style('arplitetemplate_' . $table_id . '_v' . $arpricelite_img_css_version . '_css');
            $arplite_pricingtable->arplite_front_inline_css_callback( $table_id, 0, true );
        } else {
            wp_register_style('arplitetemplate_' . $table_id . '_css', ARPLITE_PRICINGTABLE_UPLOAD_URL . '/css/arplitetemplate_' . $table_id . '.css', array(), null);
            
            wp_enqueue_style('arplitetemplate_' . $table_id . '_css');
            $arplite_pricingtable->arplite_front_inline_css_callback( $table_id, 0, true );
        }

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $maxrowcount = 0;
        if (is_array($table_cols)) {
            foreach ($table_cols as $countcol) {
                if ($countcol['rows'] && count($countcol['rows']) > $maxrowcount)
                    $maxrowcount = count($countcol['rows']);
            }
            $maxrowcount--;
        }

        $arp_tablet_view_width = $arplite_mainoptionsarr['general_options']['template_options']['arp_tablet_view_width'];

        $opts['columns'] = $table_cols;

        $column_settings = $general_option['column_settings'];

        $hover_type = $column_settings['column_highlight_on_hover'];
        $arp_global_button_type = isset($column_settings['arp_global_button_type']) ? $column_settings['arp_global_button_type'] : 'flat';
        $arp_global_button_class_array = $arpricelite_default_settings->arp_button_type();
        $arp_global_button_class_array[$arp_global_button_type]['class'] = isset($arp_global_button_class_array[$arp_global_button_type]['class']) ? $arp_global_button_class_array[$arp_global_button_type]['class'] : '';
        if (isset($column_settings['disable_button_hover_effect']) && $column_settings['disable_button_hover_effect'] == 1) {
            $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'] . ' arp_button_hover_disable';
        } else {
            $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'];
        }

        $template_settings = $general_option['template_setting'];

        $general_settings = $general_option['general_settings'];

        $template_type = $template_settings['template_type'];

        $template = $template_settings['template'];

        $template_id = $template_settings['template'];

        $ref_template = $general_settings['reference_template'];

        $is_responsive = $general_option['column_settings']['is_responsive'];

        $template_feature = $arplite_mainoptionsarr['general_options']['template_options']['features'][$ref_template];

        $hide_blank_row = isset($general_option['column_settings']['column_hide_blank_rows']) ? $general_option['column_settings']['column_hide_blank_rows'] : '';

        if ($is_tbl_preview == 1 || ( isset($_REQUEST['home_view']) && $_REQUEST['home_view'] == 1 )) {
            if ($is_template == 1) {
                do_action('arplite_enqueue_preview_style', $arp_template_name, $arp_template_name, 0, $is_template);
            } else {
                do_action('arplite_enqueue_preview_style', $id, $id, 0, $is_template);
            }
        }

        $tablestring = "";

        $title_cls = "";

	$animation_margin = '';

        /* /pre render action */
        do_action('arplite_predisplay_pt_action', $table_id);
        do_action('arplite_predisplay_pt_action' . $table_id, $table_id);

        $tablestring = apply_filters('arplite_predisplay_pricingtable_filter', $tablestring, $table_id);

                $tablestring .= "<div class='arplite_responsive_array_front' style='display:none;'>" . wp_json_encode($arplite_templateresponsivearr) . "</div>";



        if ($column_settings['column_wrapper_width_txtbox'] != '') {
            $container_width = $column_settings['column_wrapper_width_txtbox'] . 'px;';
        } else {
            $container_width = $arplite_mainoptionsarr['general_options']['wrapper_width'] . 'px;';
        }

        $caption_row_border = isset($column_settings['arp_caption_row_border_size']) ? $column_settings['arp_caption_row_border_size'] : '';
        $row_border = isset($column_settings['arp_row_border_size']) ? $column_settings['arp_row_border_size'] : '';
        $tablestring .= "<div class='arplite_template_main_container' id='arplite_template_main_container' style='width:$container_width;text-align:center;' data-hide-blank-rows='{$hide_blank_row}' data-is-tempalte='{$is_template}' data-mobile-width='" . get_option('arplite_mobile_responsive_size') . "' data-is-responsive='{$general_option['column_settings']['is_responsive']}' data-is-animated='{$is_animated}' data-arp-template='arplitetemplate_{$table_id}' data-template-type='{$template_type}' data-table-preview='{$is_tbl_preview}' data-reference-template='{$ref_template}' data-hover-type='{$hover_type}' data-column-mobile='1' data-column-tablet='3' data-column-desktop='' data-all-column-width='{$column_settings['all_column_width']}' data-tablet-width='" . get_option('arplite_tablet_responsive_size') . "' data-space-columns='{$column_settings['column_space']}' data-responsive-width-arr='" . wp_json_encode($arp_responsive_arr[$ref_template]) . "' data-column-wrapper-width-arr='" . wp_json_encode($arp_col_wrapper_height[$ref_template]) . "' data-column-wrapper-highlighted-height='".wp_json_encode($arplite_col_wrapper_highlighted_height[$ref_template])."' data-column-wrapper-default-height='".wp_json_encode($arplite_col_wrapper_default_height[$ref_template])."' data-caption-row-border='" . $caption_row_border . "' data-row-border='" . $row_border . "'>";

        $template_main_css = "";
        if( isset($is_tbl_preview) && ($is_tbl_preview == 1 || $is_tbl_preview == 2) ){
            $template_main_css = "padding-bottom:40px;";
        }

        $tablestring .= "<div class='ArpTemplate_main arplite_front_main_container' id='ArpTemplate_main' style='" . $animation_margin . $template_main_css . "'>";
        $tablestring .= "<input type='hidden' id='ajaxurl' name='ajaxurl' value='" . esc_url( admin_url('admin-ajax.php') ) . "' />";
        $column_ord = str_replace('\'', '"', $general_settings['column_order']);

        $col_ord_arr = json_decode($column_ord, true);

        if ($is_template == 1) {
            $template_name = $arp_template_name;
        } else {
            $template_name = $table_id;
        }
        
                $arp_default_character_arr = get_option('arplite_css_character_set');
        $arp_subset = (isset($arp_default_character_arr) and ! empty($arp_default_character_arr)) ? "&subset=" . implode(',', $arp_default_character_arr) : '';

        if (is_ssl())
            $googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
        else
            $googlefontbaseurl = "http://fonts.googleapis.com/css?family=";

        $default_fonts = $arpricelite_fonts->get_default_fonts();

        array_push($default_fonts, 'inherit');

        $including_google_fonts = array();
        $general_column_settings = isset($general_option['column_settings']) ? $general_option['column_settings'] : array();

        if (!in_array($general_column_settings['price_font_family_global'], $default_fonts) && $general_column_settings['price_font_family_global'] != '') {
            if (!in_array($general_column_settings['price_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['price_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['description_font_family_global'], $default_fonts) && $general_column_settings['description_font_family_global'] != '') {
            if (!in_array($general_column_settings['description_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['description_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['header_font_family_global'], $default_fonts) && $general_column_settings['header_font_family_global'] != '') {
            if (!in_array($general_column_settings['header_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['header_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['body_font_family_global'], $default_fonts) && $general_column_settings['body_font_family_global'] != '') {
            if (!in_array($general_column_settings['body_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['body_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['footer_font_family_global'], $default_fonts) && $general_column_settings['footer_font_family_global'] != '') {
            if (!in_array($general_column_settings['footer_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['footer_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['button_font_family_global'], $default_fonts) && $general_column_settings['button_font_family_global'] != '') {
            if (!in_array($general_column_settings['button_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['button_font_family_global'];
            }
        }

        foreach ($opts['columns'] as $j => $columns) {

            if (isset($columns['is_caption']) && $columns['is_caption'] == 1){

                $arp_caption_header_font_family = isset($columns['header_font_family'])?$columns['header_font_family']:'';
                if (isset($arp_caption_header_font_family) && !in_array($arp_caption_header_font_family, $default_fonts) && $arp_caption_header_font_family != '') {
                    if (!in_array($arp_caption_header_font_family, $including_google_fonts)) {
                        $including_google_fonts[] = $arp_caption_header_font_family;
                    }
                }

                $arp_caption_content_font_family = isset($columns['content_font_family'])?$columns['content_font_family']:'';
                if (isset($arp_caption_content_font_family) && !in_array($arp_caption_content_font_family, $default_fonts) && $arp_caption_content_font_family != '') {
                    if (!in_array($arp_caption_content_font_family, $including_google_fonts)) {
                        $including_google_fonts[] = $arp_caption_content_font_family;
                    }
                }

                $arp_caption_footer_level_options_font_family = isset($columns['footer_level_options_font_family'])?$columns['footer_level_options_font_family']:'';
                if (isset($arp_caption_footer_level_options_font_family) && !in_array($arp_caption_footer_level_options_font_family, $default_fonts) && $arp_caption_footer_level_options_font_family != '') {
                    if (!in_array($arp_caption_footer_level_options_font_family, $including_google_fonts)) {
                        $including_google_fonts[] = $arp_caption_footer_level_options_font_family;
                    }
                }

            }

        }


        /* Toggle Content Style End */
        if (isset($including_google_fonts) and is_array($including_google_fonts) and ! empty($including_google_fonts)) {
            foreach ($including_google_fonts as $google_fonts) {
                    wp_enqueue_style( 'arplite_google_font_css-'. str_replace(' ','+',$google_fonts), $googlefontbaseurl. urlencode(trim($google_fonts) ). $arp_subset );
            }
        }

        $template_id = $template_settings['template'];
        $color_scheme = 'arp' . $template_settings['skin'];

        $hover_class = $hover_type;

        if (!in_array($hover_class, array('hover_effect', 'shadow_effect'))) {
            $hover_class .= ' arp_hover_animated_effect';
            $arp_inc_effect_css[] = 1;
        }

        $animation_class = 'no_animation';
        $global_column_width = "";

        $col_array = array();
        foreach ($opts['columns'] as $j => $columns) {
            if (isset($columns['is_caption']) && $columns['is_caption'] == 1)
                $col_array[] = 1;
            else
                $col_array[] = 0;
        }
        $tablestring .= "<div class='ArpPriceTable arp_outer_wrapper_all_columns arplite_price_table_" . $template_name . " arplitetemplate_" . $template_name . " arp_price_ref_table_" . $ref_template . " " . $color_scheme . " '>";

        $tablestring .= "<div class='arp_inner_wrapper_all_columns'  id='ArpPricingTableColumns'>";
        $caption_array = array();
        $x = 0;
        if ($opts['columns'] and count($opts['columns']) > 0) {

            $header_img = array();
            foreach ($opts['columns'] as $j => $columns) {
                if (isset($columns['arp_header_shortcode']) && $columns['arp_header_shortcode'] != '')
                    $header_img[] = 1;
                else
                    $header_img[] = 0;
            }

            foreach ($opts['columns'] as $j => $columns) {

                if ($columns['column_width'] != '' && $columns['column_width'] > 0) {
                    $inline_column_width[] = 1;
                } else {
                    $inline_column_width[] = 0;
                }
            }


            $margin_top_all_div = "";
            if (isset($column_animation['is_animation']) and $column_animation['is_animation'] == 'yes') {
                $margin_top_all_div = 'padding-top:22px;';
            }

            $tablestring .= "<div class='arp_allcolumnsdiv' style='" . $margin_top_all_div . "'>";
            $style_ = 0;
            foreach ($opts['columns'] as $j => $columns) {

                if ($columns['is_caption'] == 1 and $template_feature['caption_style'] == 'default') {
                    $inlinecolumnwidth = "";
                    if ($columns["column_width"] != "")
                        $inlinecolumnwidth = 'width:' . $columns["column_width"] . 'px';
                    $column_highlight = $opts['columns'][$j]['column_highlight'];
                    if ($column_highlight && $column_highlight == 1)
                        $highlighted_column = 'column_highlight';

                    if ($columns['column_width'] != '') {
                        $has_custom_column_width = 'data-has_custom_column_width="true"';
                        $has_custom_width = '';
                    } else {
                        $has_custom_column_width = 'data-has_custom_column_width="false"';
                        $has_custom_width = '';
                    }

                    if (isset($column_settings['space_between_column']) && $column_settings['space_between_column'] == 'yes' and $column_settings['column_space'] > 0) {
                        $has_column_space = 'data-has_column_space="' . $column_settings['column_space'] . '" data-is_column_space="true"';
                    } else {
                        $has_column_space = 'data-has_column_space="false" data-is_column_space="false"';
                    }

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

                    $column_settings['hide_caption_column'] = isset($column_settings['hide_caption_column']) ? $column_settings['hide_caption_column'] : "";

                    if (!empty($general_option['column_settings']['column_box_shadow_effect']) && $general_option['column_settings']['column_border_radius_top_left'] == 0 && $general_option['column_settings']['column_border_radius_top_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_left'] == 0) {
                        if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_1')
                            $shadow_default_class = 'shadow_style_1';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_2')
                            $shadow_default_class = 'shadow_style_2';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_3')
                            $shadow_default_class = 'shadow_style_3';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_4')
                            $shadow_default_class = 'shadow_style_4';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_5')
                            $shadow_default_class = 'shadow_style_5';
                        else
                            $shadow_default_class = 'shadow_none';
                    } else {
                        $shadow_default_class = '';
                    }
                    if( $column_settings['hide_caption_column'] ){
                        $hide_caption_column = ' arp_hidden_captioncolumn ';
                    } else {
                        $hide_caption_column = '  ';
                    }
                    $tablestring .= "<div id='main_" . $j . "' data-width='".$inlinecolumnwidth."' " . $has_custom_column_width . " " . $has_column_space . " data-order='main_column_" . $style_ . "' class='$shadow_default_class ArpPricingTableColumnWrapper ".$hide_caption_column." " . $has_custom_width . " style_" . $j . " maincaptioncolumn  " . $animation_class . " arp_style_$style_' style='";

                    if ($column_settings['hide_caption_column'] == 1) {
                        $tablestring .= "display:none;";
                    }
                    if ($columns['column_width'] != '' && $columns['column_width'] > 0) {
                        $tablestring .= $inlinecolumnwidth;
                    } else {
                        if ($is_responsive != 1) {
                            $tablestring .= $global_column_width;
                        }
                    } $tablestring .= "'";
                    $tablestring.= " >";
                    $new_clickable_class = "";

                    $tablestring .= "<div class='arpplan " . $new_clickable_class . " ";
                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "maincaptioncolumn ";
                    } else {
                        $tablestring .= $j . " ";
                    } if ($x % 2 == 0) {
                        $tablestring .= " arpdark-bg ArpPriceTablecolumndarkbg";
                    }


                    $tablestring .= "' style='";
                    if ($column_settings['hide_caption_column'] == 1) {
                        $tablestring .= "display:none;";
                    }
                    $tablestring .= "' >";

                    $tablestring .= "<div class='planContainer'>";


                    $tablestring .= "<div class='arp_column_content_wrapper'>";

                    $tablestring .= "<div class='arpcolumnheader " . (isset($header_cls) ? $header_cls : "") . "'>";

                    if ($columns['is_caption'] == 1) {
                        if ($template_feature['caption_title'] == 'default') {
                            if ($template == 'arplitetemplate_1' && in_array(1, $header_img))
                                $header_cls = 'has_header_code';
                            else
                                $header_cls = '';

                            $tablestring .= "<div class='arpcaptiontitle " . $header_cls . "'>";
                                $tablestring .= "<div class='html_content_first'>" . do_shortcode(stripslashes_deep($columns['html_content'])) . "</div>";
                            $tablestring .= "</div>";
                        }
                        else if ($template_feature['caption_title'] == 'style_1') {
                            $tablestring .= "<div class='arpcaptiontitle'>
                                            	
                                                <div class='arpcaptiontitle_style_1'>" . do_shortcode(stripslashes_deep($columns['html_content'])) . "</div>
                                            </div>";
                        }
                    } else {
                        $tablestring .= "<div class='arppricetablecolumntitle'>
											<div class='bestPlanTitle'>" . do_shortcode(stripslashes_deep($columns['package_title'])) . "</div>
										</div>
										<div class='arppricetablecolumnprice " . esc_attr( $template_feature['amount_style'] ) . "'>" . do_shortcode(stripslashes_deep($columns['html_content'])) . "</div>";
                    }

                    $tablestring .= "</div>
                        <div class='arpbody-content arppricingtablebodycontent'>
                            <ul class='arp_opt_options arppricingtablebodyoptions' id='column_" . $x . "' style='text-align:" . $columns['body_text_alignment'] . "' >";

                    $r = 0;

                    $row_order = isset($opts['columns'][$j]['row_order']) ? $opts['columns'][$j]['row_order'] : array();
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


                    for ($ri = 0; $ri <= $maxrowcount; $ri++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $ri]) ? $opts['columns'][$j]['rows']['row_' . $ri] : array();



                        if ($columns['is_caption'] == 1) {
                            if (($ri + 1) % 2 == 0) {
                                $cls = 'rowlightcolorstyle';
                            } else {
                                $cls = '';
                            }
                        } else {
                            if ($x % 2 == 0) {
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

                        $isBlank = '';
                        if( isset($rows['row_description']) && '' == $rows['row_description'] ){
                            $isBlank = 'blank';
                        }

                        $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                        $tablestring .= "<li class='" . $cls . " " . $li_class . "' id='arp_" . $j . '_row_' . $ri . "' style='text-align:";
                        $tablestring .= "' ><div data-isBlank='".$isBlank."' class='row_description_first_step arp_row_description_text'";
                        $tablestring .= "' >" . (( isset($rows['row_description']) && $rows['row_description'] != '' ) ? stripslashes_deep($rows['row_description']) : '') . "</div>";
                        $tablestring .= "</li>";
                        if( isset( $rows['row_description'] ) && '' != $rows['row_description'] ){
                            array_push($font_awesome_match, $rows['row_description']);
                        }

                        if( isset( $rows['row_label'] ) ){
                            array_push($font_awesome_match, $rows['row_label']);
                        }
                    }

                    $tablestring .= "</ul>
                        </div>";
                    if( $columns['is_caption'] ){
                        $caption_array = arplite_get_caption_feature_part( $columns, $template_name, $maxrowcount, $column_settings );
                    }

                    if ($template_feature['button_position'] == 'default') {

                        $tablestring .= "<div class='arpcolumnfooter $footer_hover_class arp_" . strtolower($columns['button_size']) . "_btn'>";
                        $footer_content_below_btn = "";
                        if ($columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1)
                            $footer_content_above_btn = "display:block;";
                        else
                            $footer_content_above_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</div>";
                        }
                        if ($columns['button_text'] == '' && empty($columns['btn_img'])) {
                            $hide_default_btn_true = "";
                            $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . "'>&nbsp;</div>";
                        } else {
                            $paypal_btn = 0;
                            $hide_default_btn_true = "";

                            $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . "' style='text-align:center;'>";

                            $tablestring .= "<button type='button'  class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' ";
                            if ($columns['btn_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important; '";
                            }
                            $tablestring .= " onclick='arplite_redirect(\"" . $columns['button_url'] . "\", \"";
                            if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1) {
                                $tablestring .= "1";
                            } else {
                                $tablestring .= "0";
                            } $tablestring .= "\",\"" . $paypal_btn . "\",this,\"" . $table_id . "\",\"main_" . $j . "\");'>";
                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step bestPlanButton_text '>";
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
                    $tablestring .= "</div>";
                    $x++;
                    $style_++;
                } else if ($columns['is_caption'] == 1 and $template_feature['caption_style'] == 'style_1') {
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
                if( isset($columns['html_content']) && '' != $columns['html_content'] ){
                    array_push($font_awesome_match, $columns['html_content']);
                }
                if( isset($columns['footer_content']) && '' != $columns['footer_content'] ){
                    array_push($font_awesome_match, $columns['footer_content']);
                }
            }

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
                            $new_arr['columns'][$new_col_id] = $columns;
                        }
                    }
                }
            } else {
                $new_arr = $opts;
            }

            foreach ($new_arr['columns'] as $j => $columns) {

                $shortcode_class = '';
                $shortcode_class_array = $arpricelite_default_settings->arp_shortcode_custom_type();
                if (isset($columns['arp_shortcode_customization_style']) && '' != $columns['arp_shortcode_customization_style']) {
                    $shortcode_class = $columns['arp_shortcode_customization_size'] . ' ' . $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'];
                }
                $col_num = str_replace('column_', '', $j);
                if ($columns['is_caption'] == 0) {
                    $inlinecolumnwidth = "";
                    if ($columns["column_width"] != "")
                        $inlinecolumnwidth = 'width:' . $columns["column_width"] . 'px';
                    $column_highlight = $opts['columns'][$j]['column_highlight'];
                    if ($column_highlight && $column_highlight == 1)
                        $highlighted_column = 'column_highlight ';
                    else
                        $highlighted_column = '';

                    if ($columns['column_width'] != '') {
                        $has_custom_column_width = 'data-has_custom_column_width="true"';
                        $has_custom_width = '';
                    } else {
                        $has_custom_column_width = 'data-has_custom_column_width="false"';
                        $has_custom_width = '';
                    }

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

                    if (isset($column_settings['space_between_column']) && $column_settings['space_between_column'] == 'yes' and $column_settings['column_space'] > 0) {
                        $has_column_space = 'data-has_column_space="' . $column_settings['column_space'] . '" data-is_column_space="true"';
                    } else {
                        $has_column_space = 'data-has_column_space="false" data-is_column_space="false"';
                    }

                    if (!empty($general_option['column_settings']['column_box_shadow_effect']) && $general_option['column_settings']['column_border_radius_top_left'] == 0 && $general_option['column_settings']['column_border_radius_top_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_left'] == 0) {
                        if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_1')
                            $shadow_default_class = 'shadow_style_1';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_2')
                            $shadow_default_class = 'shadow_style_2';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_3')
                            $shadow_default_class = 'shadow_style_3';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_4')
                            $shadow_default_class = 'shadow_style_4';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_5')
                            $shadow_default_class = 'shadow_style_5';
                        else
                            $shadow_default_class = 'shadow_none';
                    } else {
                        $shadow_default_class = '';
                    }

                    $tablestring .= "<div id='main_" . $j . "' " . $has_custom_column_width . " " . $has_column_space . " data-order='main_column_" . $style_ . "'   class='$shadow_default_class  " . $highlighted_column . " ArpPricingTableColumnWrapper style_" . $j . " " . $hover_class . " " . $animation_class . " " . $has_custom_width . " arp_style_$style_ '  style='";
                    if ($c == 0) {
                        $tablestring .= "border-left:1px solid #DADADA;";
                    } if ($columns['column_width'] != '' && $columns['column_width'] > 0) {
                        $tablestring .= $inlinecolumnwidth;
                    } else {
                        if ($is_responsive != 1) {
                            $tablestring .= $global_column_width;
                        }
                    } $tablestring .= "'";
                    $tablestring .= " data-column-footer-position='{$columns['footer_content_position']}'";
                    $tablestring.= ">";


                    $new_clickable_class = "";

                    $tablestring .= "<div class='arpplan " . $new_clickable_class . " ";
                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "maincaptioncolumn";
                    } else {
                        $tablestring .= "column_" . $c;
                    } if ($x % 2 == 0) {
                        $tablestring .= " arpdark-bg ArpPriceTablecolumndarkbg";
                    } $tablestring .= "'>";

                    $columns['ribbon_setting']['arp_ribbon'] = isset($columns['ribbon_setting']['arp_ribbon']) ? $columns['ribbon_setting']['arp_ribbon'] : "";
                    $tablestring .= "<div class='planContainer " . $columns['ribbon_setting']['arp_ribbon'] . " '>";

                    if (isset($columns['arp_header_shortcode']) && $columns['arp_header_shortcode'] != '')
                        $header_cls = 'has_arp_shortcode';
                    else
                        $header_cls = '';


                    $columns_custom_ribbon_position = '';
                    if ($columns['ribbon_setting'] and $columns['ribbon_setting']['arp_ribbon'] != '' and $columns['ribbon_setting']['arp_ribbon_content'] != '') {
                        $basic_col = $arplite_mainoptionsarr['general_options']['arp_basic_colors'];
                        $ribbon_bg_col = $columns['ribbon_setting']['arp_ribbon_bgcol'];
                        $base_color = $ribbon_bg_col;
                        $base_color_key = array_search($base_color, $basic_col);
                        $gradient_color = $arplite_mainoptionsarr['general_options']['arp_basic_colors_gradient'][$base_color_key];
                        $ribbon_border_color = $arplite_mainoptionsarr['general_options']['arp_ribbon_border_color'][$base_color_key];
                        $tablestring .= "<div id='arp_ribbon_container' class='arp_ribbon_container arp_ribbon_" . strtolower($columns['ribbon_setting']['arp_ribbon_position']) . " " . $columns['ribbon_setting']['arp_ribbon'] . " ' style='" . $columns_custom_ribbon_position . "' >";

                        $tablestring .= "<div class='arp_ribbon_content arp_ribbon_" . strtolower($columns['ribbon_setting']['arp_ribbon_position']) . "'>";
                        $tablestring .= esc_html($columns['ribbon_setting']['arp_ribbon_content']);
                        $tablestring .= "</div>";

                        $tablestring .= "</div>";
                    }


                    $tablestring .= "<div class='arp_column_content_wrapper'>";

                    $tablestring .= "<div class='arpcolumnheader " . $header_cls . "'>";

                    if ($template_feature['header_shortcode_position'] == 'position_1') {

                        $tablestring .= "<div class='arp_header_shortcode'>";

                        if ($template_feature['header_shortcode_type'] == 'normal'){
                            $tablestring .= do_shortcode($columns['arp_header_shortcode']);
                        } else if ($template_feature['header_shortcode_type'] == 'rounded_corner') {
                            $tablestring .= "<div class='arp_rounded_shortcode_wrapper '>";
                            $tablestring .= "<div class='rounded_corner_wrapper $shortcode_class'>";
                            $tablestring .= "<div class='rounded_corder $shortcode_class'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                            $tablestring .= "</div>";
                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                    }

                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "<div class='arpcaptiontitle'>" . do_shortcode(stripslashes_deep($columns['html_content'])) . "</div>";
                    } else {

                        $tablestring .= "<div class='arppricetablecolumntitle'>";

                        $tablestring .= "<div class='bestPlanTitle " . $title_cls . " package_title_first '>" . do_shortcode(stripslashes_deep($columns['package_title'])) . "</div>";

                        if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_1') {
                            $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step '>" . stripslashes_deep($columns['column_description']) . "</div>";
                        }

                        $tablestring .= "</div>";

                        if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_3') {
                            $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step '>" . stripslashes_deep($columns['column_description']) . "</div>";
                        }

                        if ($template_feature['button_position'] == 'position_2') {
                            $columns['paypal_code'] = isset($columns['paypal_code']) ? $columns['paypal_code'] : "";
                            $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";

                            if ($columns['paypal_code'] != '') {
                                $columns['paypal_code'] = do_shortcode($columns['paypal_code']);
                                $paypal_btn = 1;
                            } else {
                                $paypal_btn = 0;
                            }

                            $tablestring .= "<div class='arpcolumnfooter $footer_hover_class'>";
                            $hide_default_btn_true = "";
                            $footer_content_below_btn = "";
                            if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1 and $template_feature['has_footer_content'] == 1)
                                $footer_content_above_btn = "display:block;";
                            else
                                $footer_content_above_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";

                                $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "<span class='footer_content_second_step arp_footer_content_text ' style='display:none'>";
                                $tablestring .= stripslashes_deep($columns['footer_content_second']);
                                $tablestring .= "</span>";

                                $tablestring .= "<span class='footer_content_third_step arp_footer_content_text ' style='display:none'>";
                                $tablestring .= stripslashes_deep($columns['footer_content_third']);
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }

                            $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . "' style='text-align:center;'>";
                            $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' ";
                            if ($columns['btn_img'] != "") {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                            }
                            $tablestring .= " onclick='arplite_redirect(\"" . $columns['button_url'] . "\", \"";
                            if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1) {
                                $tablestring .= "1";
                            } else {
                                $tablestring .= "0";
                            } $tablestring .= "\",\"" . $paypal_btn . "\",this,\"" . $table_id . "\",\"main_" . $j . "\"); '>";
                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step bestPlanButton_text '>";
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

                                $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
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
                                $tablestring .= "<div class='arp_rounded_shortcode_wrapper'>";
                                $tablestring .= "<div class='rounded_corner_wrapper $shortcode_class'>";
                                $tablestring .= "<div class='rounded_corder $shortcode_class'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            }
                            
                        }
                        if ($template_feature['amount_style'] != 'style_3') {
                            $tablestring .= "<div class='arppricetablecolumnprice " . $template_feature['amount_style'] . "'>";

                            if ($template_feature['amount_style'] == 'default') {
                                $tablestring .= "<div class='arp_price_wrapper'>";

                                $tablestring .= $columns['price_text'];

                                $tablestring .= "</div>";

                                $tablestring .= isset($columns['html_content']) ? $columns['html_content'] : "";
                            } else if ($template_feature['amount_style'] == 'style_1') {
                                $tablestring .= "<div class='arp_pricename'>";
                                $tablestring .= "<div class='arp_price_wrapper'>";
                                $tablestring .= "<span class=\"arp_price_value\">";
                                $tablestring .= $columns['price_text'];

                                $tablestring .= "</span>";

                                $tablestring .= "<span class=\"arp_price_duration\">";
                                $tablestring .= $columns['price_label'];

                                $tablestring .= "</span>";
                                $tablestring .= "</div>";
                                $tablestring .= "</div>";
                            } else if ($template_feature['amount_style'] == 'style_2') {

                                $tablestring .= "<div class='arp_price_wrapper'>";
                                $tablestring .= "<span class=\"arp_price_duration\">";
                                $tablestring .= $columns['price_label'];

                                $tablestring .= "</span>";

                                $tablestring .= "<span class=\"arp_price_value\">";
                                $tablestring .= $columns['price_text'];

                                $tablestring .= "</span>";
                                $tablestring .= "</div>";
                                $tablestring .= do_shortcode(isset($columns['html_content']) ? $columns['html_content'] : "" );
                            }

                            if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_2') {
                                $tablestring .= "<div class='custom_ribbon_wrapper'>";
                                $tablestring .= "<div class='column_description column_description_first_step '>" . stripslashes_deep($columns['column_description']) . "</div>";
                                $tablestring .= "<div class='column_description column_description_second_step ' style='display:none;'>" . stripslashes_deep($columns['column_description_second']) . "</div>";
                                $tablestring .= "<div class='column_description column_description_third_step ' style='display:none;'>" . stripslashes_deep($columns['column_description_third']) . "</div>";
                                $tablestring .= "</div>";
                            }

                            if ($template_feature['column_description'] == 'enable' && $template_feature['column_description_style'] == 'style_4') {
                                $first_desc_blank = $second_desc_blank = $third_desc_blank = '';
                                $first_desc_blank = empty($columns['column_description']) ? ' desc_content_blank' : '';
                                $second_desc_blank = empty($columns['column_description_second']) ? ' desc_content_blank' : '';
                                $third_desc_blank = empty($columns['column_description_third']) ? ' desc_content_blank' : '';

                                $tablestring .= "<div class='column_description column_description_first_step  " . $first_desc_blank . "'>" . stripslashes_deep($columns['column_description']) . "</div>";
                            }


                            if ($template_feature['button_position'] == 'position_1') {
                                $columns['paypal_code'] = isset($columns['paypal_code']) ? $columns['paypal_code'] : "";
                                $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";
                                $paypal_btn = 0;

                                $tablestring .= "<div class='arpcolumnfooter $footer_hover_class'>";
                                $hide_default_btn_true = "";
                                $footer_content_above_btn = "";
                                if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                                    $footer_content_above_btn = "display:block;";
                                else
                                    $footer_content_above_btn = "display:none;";
                                if ($template_feature['has_footer_content'] == 1) {
                                    $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";

                                    $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                                    $tablestring .= $columns['footer_content'];
                                    $tablestring .= "</span>";

                                    $tablestring .= "</div>";
                                }
                                $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . "' style='text-align:center;'>";

                                $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' ";
                                if ($columns['btn_img'] != "") {
                                    $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                                }
                                $tablestring .= " onclick='arplite_redirect(\"" . $columns['button_url'] . "\", \"";
                                if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1) {
                                    $tablestring .= "1";
                                } else {
                                    $tablestring .= "0";
                                } $tablestring .= "\",\"" . $paypal_btn . "\",this,\"" . $table_id . "\",\"main_" . $j . "\"); '>";
                                if ($columns['btn_img'] == "") {
                                    $tablestring .= "<span class='btn_content_first_step bestPlanButton_text '>";
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

                                    $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                                    $tablestring .= $columns['footer_content'];
                                    $tablestring .= "</span>";

                                    $tablestring .= "</div>";
                                }
                                $tablestring.= "</div>";
                            }

                            $tablestring .= "</div>";
                        }
                    }
                    if ($template_feature['header_shortcode_position'] == 'position_2') {
                        $tablestring .= "<div class='arp_header_shortcode'>";

                        if ($template_feature['header_shortcode_type'] == 'normal')
                            $tablestring .= do_shortcode($columns['arp_header_shortcode']);
                        else if ($template_feature['header_shortcode_type'] == 'rounded_corner') {
                            $tablestring .= "<div class='arp_rounded_shortcode_wrapper'>";
                            $tablestring .= "<div class='rounded_corner_wrapper $shortcode_class'>";
                            $tablestring .= "<div class='rounded_corder $shortcode_class'>" . do_shortcode($columns['arp_header_shortcode']) . "</div>";
                            $tablestring .= "</div>";
                            $tablestring .= "</div>";
                        }
                        $tablestring .= "</div>";
                    }

                    $tablestring .= "</div>";



                    $tablestring .= "<div class='arpbody-content arppricingtablebodycontent'>";
                    if ($template_feature['button_position'] == 'position_3') {
                        $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step '>" . stripslashes_deep($columns['column_description']) . "</div>";
                        $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";

                        $tablestring .= "<div class='arpcolumnfooter arp_" . strtolower($columns['button_size']) . "_btn $footer_hover_class' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons'>";
                        $paypal_btn = 0;
                        $hide_default_btn_true = "";
                        $footer_content_above_btn = "";
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                            $footer_content_above_btn = "display:block;";
                        else
                            $footer_content_above_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";

                            $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }
                        
                        $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . "' data-column='main_" . $j . "' style='text-align:center;'>";
                        $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_". $col_num ."'  data-is-post-variables='{$columns['is_post_variables']}' data-post-variables='" . stripslashes($columns['post_variables_content']) . "' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                        if ($columns['btn_img'] != "" && $columns['hide_default_btn'] != 1) {
                            $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                        }
                        if ($columns['hide_default_btn'] == 1) {
                            $tablestring .= "style='display:none;'";
                        }
                        $tablestring .= "onclick='arplite_redirect(\"" . $columns['button_url'] . "\", \"";
                        if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1) {
                            $tablestring .= "1";
                        } else {
                            $tablestring .= "0";
                        } $tablestring .="\",\"" . $paypal_btn . "\",this,\"" . $table_id . "\",\"main_" . $j . "\");'>";
                        if ($columns['btn_img'] == "") {
                            $tablestring .= "<span class='btn_content_first_step bestPlanButton_text '>";
                            $tablestring .= stripslashes_deep($columns['button_text']);
                            $tablestring .= "</span>";
                        } $tablestring .= "</button>";
                        $tablestring .= do_shortcode($columns['paypal_code']);
                        $tablestring .= "</div>";
                        $tablestring .= "</div>";
                        $footer_content_below_btn = "";
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                            $footer_content_below_btn = "display:block;";
                        else
                            $footer_content_below_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";

                            $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }
                        $tablestring .= "</div>";
                    }

                    $tablestring .= "<ul class='arp_opt_options arppricingtablebodyoptions' id='" . $x . "' style='text-align:" . $columns['body_text_alignment'] . "'>";

                    $r = 0;

                    $row_order = isset($opts['columns'][$j]['row_order']) ? $opts['columns'][$j]['row_order'] : array();
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

                    for ($ri = 0; $ri <= $maxrowcount; $ri++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $ri]) ? $opts['columns'][$j]['rows']['row_' . $ri] : array();

                        if ($columns['is_caption'] == 1) {
                            if (($ri + 1) % 2 == 0) {
                                $cls = 'rowlightcolorstyle';
                            } else {
                                $cls = '';
                            }
                        } else {
                            if ($x % 2 == 0) {
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

                        $columns['column_title'] = isset($columns['column_title']) ? $columns['column_title'] : "";
                        $columns['html_content'] = isset($columns['html_content']) ? $columns['html_content'] : "";

                        $isBlank = '';
                        if( isset($rows['row_description']) && '' == $rows['row_description'] ){
                            $isBlank = 'blank';
                        }
                        
                        if ($template_feature['caption_style'] == 'style_1' and $template_feature['list_alignment'] != 'default') {
                            $tablestring .= "<li class='" . $cls;

                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= " " . $li_class . "' id='arp_" . $j . '_row_' . $ri . "'>";
                            $tablestring .= "<span class='caption_li'>";
                            $tablestring .= "<div class='row_label_first_step arp_caption_li_text '>" . (( isset($rows['row_label']) && $rows['row_label'] != '' ) ? stripslashes_deep($rows['row_label']) : '') . "</div>";
                            $tablestring .= "</span>";

                            $tablestring .= "<span class='caption_detail'>";
                            $tablestring .= "<div class='row_description_first_step arp_caption_detail_text  ";

                            $tablestring .= "' data-tipso=''>" . (( isset($rows['row_description']) && $rows['row_description'] != '' ) ? stripslashes_deep($rows['row_description']) : '');

                            $tablestring .= "</div>";

                            $tablestring .= "</span>
                            						</li>";
                        } else if ($template_feature['caption_style'] == 'style_2') {

                            $tablestring .= "<li class='" . $cls;
                            if ($rows['row_tooltip'] != "") {
                                $tablestring .= " arp_tooltip_li";
                            }
                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= " " . $li_class . "' id='arp_" . $j . '_row_' . $ri . "'";

                            $tablestring .= ">";
                            $tablestring .= "<span class='caption_detail'>";

                            /* / first step description */
                            $tablestring .= "<div data-isBlank='".$isBlank."' class='row_description_first_step arp_caption_detail_text ' data-tipso=''>" . (( isset($rows['row_description']) && $rows['row_description'] != '' ) ? stripslashes_deep($rows['row_description']) : '');


                            $tablestring .= "</div>";

                            $tablestring .= "</span>";

                            $tablestring .= "<span class='caption_li'>";
                            $tablestring .= "<div class='row_label_first_step arp_caption_li_text '>" . (( isset($rows['row_label']) && $rows['row_label'] != '' ) ? stripslashes_deep($rows['row_label']) : '') . "</div>";
                            $tablestring .= "</span>";
                            $tablestring .= "</li>";
                        } else if ($template_feature['list_alignment'] != 'default') {
                            $tablestring .= "<li class='" . $cls;

                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= " " . $li_class . "' id='arp_" . $j . '_row_' . $ri . "' style='text-align:" . $template_feature['list_alignment'] . "' >";


                            /* / first step description */
                            $tablestring .= "<div data-isBlank='".$isBlank."' class='row_description_first_step arp_row_description_text  ";
                            if ($rows['row_tooltip'] != "" and ( isset($rows['row_description']) && $rows['row_description'] != '' )) {
                                $tablestring .= " arp_tooltip";
                            }
                            $tablestring .= "' data-tipso='";
                            $tablestring .= "'>" . (( isset($rows['row_description']) && $rows['row_description'] != '' ) ? stripslashes_deep($rows['row_description']) : '');

                            $tablestring .= "</div>";

                            $tablestring .= "</li>";
                        } else {
                            $tablestring .= "<li class='" . $cls;

                            $li_class = $ref_template . '_' . $j . '_row_' . $ri;
                            $tablestring .= " " . $li_class . "' id='arp_" . $j . '_row_' . $ri . "' style='text-align:";
                            $tablestring .= "' >";
                            $desc_key = "row_description";
                            $cap_des_key = "description";
                            
                            /* / first step description   */
                            $tablestring .= "<div data-isBlank='".$isBlank."' class='row_description_first_step arp_row_description_text ";

                            $tablestring .= "' data-tipso='";
                            $tablestring .= "'>";
                            if(!empty($caption_array)){
                                
                                if(isset($caption_array[$template_name][$ri][$cap_des_key])) {

                                    $tablestring .= '<p class="arprice_caption_hidden">'.$caption_array[$template_name][$ri][$cap_des_key].'</p>';
                                }
                            }
                            $tablestring .= (( isset($rows['row_description']) && $rows['row_description'] != '' ) ? stripslashes_deep($rows['row_description']) : '');
                            $tablestring .= "</div>";

                            $tablestring .= "
                                           </li>";
                        }
                        $last_li_cls = $cls;
                        if( isset( $rows['row_label'] ) && !empty( $rows['row_label']) ){
                            array_push($font_awesome_match, $rows['row_label']);
                        }
                        if( isset( $rows['row_description'] ) && !empty( $rows['row_description'] ) ){
                            array_push($font_awesome_match, $rows['row_description']);
                        }
                    }
                    if ($template_feature['button_position'] != 'default') {
                        $tablestring .= "<li class='arp_last_list_item " . $last_li_cls . "'></li>";
                    }
                    $tablestring .= "</ul>";
                    $tablestring .= "</div>";

                    if ($template_feature['amount_style'] === 'style_3') {
                        $tablestring .= "<div class='arppricetablecolumnprice " . $template_feature['amount_style'] . "' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='pricing_level_options' data-type='other_columns_buttons' >";
                        $tablestring .= "<div class='arp_price_wrapper'>";
                        $tablestring .= "<span class=\"arp_price_duration\">";
                        $tablestring .= $columns['price_label'];

                        $tablestring .= "</span>";

                        $tablestring .= "<span class=\"arp_price_value\">";
                        $tablestring .= $columns['price_text'];

                        $tablestring .= "</span>";

                        $tablestring .= "</div>";
                        $tablestring .= do_shortcode($columns['html_content']);


                        if ($template_feature['button_position'] == 'position_4') {
                            $tablestring .= "<div class='arpcolumnfooter arp_" . strtolower($columns['button_size']) . "_btn $footer_hover_class' id='arpcolumnfooter' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons'>";
                            $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";
                            $hide_default_btn_true = "";
                            $footer_content_above_btn = "";
                            if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                                $footer_content_above_btn = "display:block;";
                            else
                                $footer_content_above_btn = "display:none;";
                            if ($template_feature['has_footer_content'] == 1) {
                                $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";

                                $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }
                            $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . "' data-column='main_" . $j . "' style='text-align:center;'>";
                            $paypal_btn = 0;
                            $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn' id='bestPlanButton_". $col_num ."' data-column='main_" . $j . "' data-template_id='" . $ref_template . "' data-level='button_options' data-type='other_columns_buttons' ";
                            if ($columns['btn_img'] != "" && $columns['hide_default_btn'] != 1) {
                                $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                            }
                            if ($columns['hide_default_btn'] == 1) {
                                $tablestring .= "style='display:none;'";
                            }
                            $tablestring .= "onclick='arplite_redirect(\"" . $columns['button_url'] . "\", \"";
                            if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1) {
                                $tablestring .= "1";
                            } else {
                                $tablestring .= "0";
                            } $tablestring .="\",\"" . $paypal_btn . "\",this,\"" . $table_id . "\",\"main_" . $j . "\");'>";
                            if ($columns['btn_img'] == "") {
                                $tablestring .= "<span class='btn_content_first_step bestPlanButton_text '>";
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
                                $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_below_btn}'>";

                                $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                                $tablestring .= $columns['footer_content'];
                                $tablestring .= "</span>";

                                $tablestring .= "</div>";
                            }
                            $tablestring .= "</div>";
                        }

                        $tablestring .= "</div>";
                    }

                    if ($template_feature['button_position'] == 'default') {

                        $tablestring .= "<div class='arpcolumnfooter arp_" . strtolower($columns['button_size']) . "_btn $footer_hover_class'>";

                        if ($template_feature['second_btn'] == true && $columns['button_s_text'] != '') {
                            $has_s_btn = 'has_second_btn';
                        } else {
                            $has_s_btn = 'no_second_btn';
                        }
                        $hide_default_btn_true = "";
                        $footer_content_above_btn = "";
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 1)
                            $footer_content_above_btn = "display:block;";
                        else
                            $footer_content_above_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_before_content' style='{$footer_content_above_btn}'>";

                            $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }
                        $tablestring .= "<div class='arppricetablebutton " . $hide_default_btn_true . " ' style='text-align:center;'>";
                        $paypal_btn = 0;
                        $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";
                        $columns['hide_default_btn'] = isset($columns['hide_default_btn']) ? $columns['hide_default_btn'] : '';
                        $tablestring .= "<button type='button' class='bestPlanButton $arp_global_button_class arp_" . strtolower($columns['button_size']) . "_btn " . $has_s_btn . "' ";
                        if ($columns['btn_img'] != "" && $columns['hide_default_btn'] != 1) {
                            $tablestring .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important;'";
                        }
                        $tablestring .= "onclick='arplite_redirect(\"" . $columns['button_url'] . "\", \"";
                        if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1) {
                            $tablestring .= "1";
                        } else {
                            $tablestring .= "0";
                        } $tablestring .="\",\"" . $paypal_btn . "\",this,\"" . $table_id . "\",\"main_" . $j . "\");'>";
                        if ($columns['btn_img'] == "") {
                            $tablestring .= "<span class='btn_content_first_step bestPlanButton_text '>";
                            $tablestring .= stripslashes_deep($columns['button_text']);
                            $tablestring .= "</span>";
                        } $tablestring .="</button>";

                        $tablestring .= "</div>";
                        $footer_content_below_btn = '';
                        if ($columns['footer_content'] != '' and $columns['footer_content_position'] == 0)
                            $footer_content_below_btn = "display:block;";
                        else
                            $footer_content_below_btn = "display:none;";
                        if ($template_feature['has_footer_content'] == 1) {
                            $tablestring .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";

                            $tablestring .= "<span class='footer_content_first_step arp_footer_content_text '>";
                            $tablestring .= $columns['footer_content'];
                            $tablestring .= "</span>";

                            $tablestring .= "</div>";
                        }


                        $tablestring .= "</div>";
                        if ($template_feature['column_description'] == 'enable' and $template_feature['column_description_style'] == 'after_button') {
                            $tablestring .= "<div class='column_description " . $title_cls . " column_description_first_step '>" . stripslashes_deep($columns['column_description']) . "</div>";
                        }
                    }

                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";

                    $c++;
                    if ($x % 5 == 0) {
                        $c = 1;
                    }
                    $x++;
                    $style_++;
                }
                if( isset($columns['ribbon_setting']['arp_ribbon_content']) && '' != $columns['ribbon_setting']['arp_ribbon_content'] ){
                    array_push($font_awesome_match, $columns['ribbon_setting']['arp_ribbon_content']);
                }

                if( isset($columns['html_content']) && '' != $columns['html_content'] ){
                    array_push($font_awesome_match, $columns['html_content']);
                }

                if( isset( $columns['package_title'] ) && '' != $columns['package_title'] ){
                    array_push($font_awesome_match, $columns['package_title']);
                }

                if( isset( $columns['column_description'] ) && '' != $columns['column_description'] ){
                    array_push($font_awesome_match, $columns['column_description']);
                }

                if( isset( $columns['arp_header_shortcode'] ) && '' != $columns['arp_header_shortcode'] ){
                    array_push($font_awesome_match, $columns['arp_header_shortcode']);
                }

                if( isset( $columns['column_description'] ) && '' != $columns['column_description'] ){
                    array_push($font_awesome_match, $columns['footer_content']);
                }

                if( isset( $columns['button_text'] ) && '' != $columns['button_text'] ){
                    array_push($font_awesome_match, $columns['button_text']);
                }

                if( isset( $columns['price_text'] ) && '' != $columns['price_text'] ){
                    array_push($font_awesome_match, $columns['price_text']);
                }

                if( isset( $columns['price_label'] ) && '' != $columns['price_label'] ){
                    array_push($font_awesome_match, $columns['price_label']);
                }
            }

            $tablestring .= "</div>";
            if( $arplite_is_lightbox ){
                $tablestring .= "<div class='arp_front_modal_overlay' style='text-align:center;'><div class='arp_video_content'></div></div>";
            }
        } else {
            $tablestring .= esc_html__('Please select valid table', 'arprice-responsive-pricing-table');
        }

        $tablestring .= "</div>";
        $tablestring .= "</div>";
        $tablestring .= "</div>";
        $tablestring .= "</div>";


        $tablestring = apply_filters('arplite_postdisplay_pricingtable_filter', $tablestring, $table_id);

        /* /post render action */
        do_action('arplite_postdisplay_pt_action', $table_id);
        do_action('arplite_postdisplay_pt_action' . $table_id, $table_id);

        global $arplite_has_tooltip;
        $arplite_has_tooltip = 0;
        if ($arplite_has_tooltip == 1)
            $arp_inc_effect_css[] = 1;

        /* Pattern to check if font awesome is in pricing table or not. */

        $fa_pattern = '/class\=(\'|")(fa|far|fab|fas)\s(.*?)(\'|")/i'; 
        
        /* Remove Empty array elements of content which may have font awesome class. */
        $filtered_font_awesome_match = array_values(array_filter($font_awesome_match));

        if (preg_grep($fa_pattern, $filtered_font_awesome_match)) {
            global $arplite_has_fontawesome;
            $arplite_has_fontawesome = 1;
        }

        $arplite_effect_css = 0;

        /* / changes for replace \n for remove p tag   08jan2015 */
        $tablestring .= "<div style='clear:both;'></div>";
        $tablestring = preg_replace("~\r?~", "", $tablestring);
        $tablestring = preg_replace("~\r\n?~", "", $tablestring);
        $tablestring = preg_replace("/\n\n+/", "", $tablestring);
        $tablestring = preg_replace("|\n|", "", $tablestring);
        $tablestring = preg_replace("~\n~", "", $tablestring);

        $tablestring = $arplite_pricingtable->arprice_font_icon_size_parser($tablestring);

        $tablestring = $arplite_pricingtable->arp_remove_style_tag($tablestring);

        return $tablestring; /* / return table string */
    }

}

function arplite_get_caption_feature_part( $columns, $template_name, $maxrowcount, $column_settings ){
    
    $row_order = isset($columns['row_order']) ? $columns['row_order'] : array();

    $caption_array = array();
    
    for ($ri = 0; $ri <= $maxrowcount; $ri++) {
        $rows = isset($columns['rows']['row_' . $ri]) ? $columns['rows']['row_' . $ri] : array();
        $desc_key = "row_description";
        $cap_des_key = "description";
        if( 1 != $column_settings['hide_caption_column'] ){
            $caption_array[$template_name][$ri][$cap_des_key] = (( isset($rows[$desc_key]) && $rows[$desc_key] != '' ) ? stripslashes_deep($rows[$desc_key]) : '');        
        }
    }

    return $caption_array;
}

?>