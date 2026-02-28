<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb, $arpricelite_version, $arplite_pricingtable;

$checkupdate = "";
$checkupdate = get_option('arpricelite_version');

if (version_compare($checkupdate, '1.2', '<')) {

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem();
    global $wp_filesystem;

    // update all existing templates
    include_once(ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_default_templates_update_1.2.php');



    // Create temporary table before changing any values
    $charset_collate = '';
    if ($wpdb->has_cap('collation')) {

        if (!empty($wpdb->charset))
            $charset_collate .= "DEFAULT CHARACTER SET $wpdb->charset";

        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }

    $temp_table1 = $wpdb->prefix . 'arplite_arprice_temp_latest';

    $sql_table = "CREATE TABLE IF NOT EXISTS `{$temp_table1}`(			
                 ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                 table_name VARCHAR(255) NOT NULL, 
                 template_name int(11) NOT NULL,
                 general_options LONGTEXT NOT NULL,
				 table_options LONGTEXT NOT NULL, 
                 is_template int(1) NOT NULL,
                 is_animated int(1) NOT NULL,
                 status VARCHAR(255) NOT NULL, 
                 create_date DATETIME NOT NULL, 
                 arp_last_updated_date DATETIME NOT NULL,
                 ref_table_name VARCHAR(255) NOT NULL
            ){$charset_collate}";

    $wpdb->query($sql_table);

    $arplite_arprice = $wpdb->prefix . 'arplite_arprice';
    $arplite_arprice_options = $wpdb->prefix . 'arplite_arprice_options';
    $arplite_analytics = $wpdb->prefix . 'arplite_arprice_analytics';

    $templates = $wpdb->get_results("SELECT * FROM `" . $arplite_arprice . "` where is_template = 0 ORDER BY ID ASC");
    global $arpricelite_form, $arpricelite_img_css_version;

    foreach ($templates as $template) {
        $original_general_options = '';
        $original_table_options = '';
        $result = $template;
        $imported_id = $result->ID;
        $reference_id = $result->template_name;
        $general_options = maybe_unserialize($result->general_options);
        $reference_template = $general_options['general_settings']['reference_template'];
        $current_color_skin = $general_options['template_setting']['skin'];

        $get_temp_options = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d", $imported_id));
        if (!empty($get_temp_options)) {
            $result_temp = $get_temp_options[0];
            $original_table_options = $result_temp->table_options;
        }
        $original_general_options = $result->general_options;
        //ins into temp table
        $query_temp_ins = '';

        $query_temp_ins = $wpdb->prepare("INSERT INTO $temp_table1 ( table_name, template_name, general_options, table_options, is_template, is_animated, status, create_date, arp_last_updated_date,ref_table_name ) VALUES ( %s,%d,%s,%s,%d,%d,%s,%s,%s,%d )", sanitize_text_field($result->table_name), 0, $original_general_options, $original_table_options, 0, $result->is_animated, sanitize_text_field($result->status), $result->create_date, $result->arp_last_updated_date, $result->ID);

        $wpdb->query($query_temp_ins);

        if ($reference_template == "arplitetemplate_1") {
            $general_options['column_settings']['global_button_border_width'] = 0;
            $general_options['column_settings']['global_button_border_type'] = 'solid';
            $general_options['column_settings']['global_button_border_color'] = '#c9c9c9';
            $general_options['column_settings']['global_button_border_radius_top_left'] = 4;
            $general_options['column_settings']['global_button_border_radius_top_right'] = 4;
            $general_options['column_settings']['global_button_border_radius_bottom_left'] = 4;
            $general_options['column_settings']['global_button_border_radius_bottom_right'] = 4;
            $general_options['column_settings']['arp_global_button_type'] = 'shadow';

            $general_options['column_settings']['arp_row_border_size'] = '0';
            $general_options['column_settings']['arp_row_border_type'] = 'solid';
            $general_options['column_settings']['arp_row_border_color'] = '#c9c9c9';

            $general_options['column_settings']['arp_caption_row_border_size'] = '0';
            $general_options['column_settings']['arp_caption_row_border_style'] = 'solid';
            $general_options['column_settings']['arp_caption_row_border_color'] = '#c9c9c9';

            $general_options['column_settings']['arp_column_border_size'] = '1';
            $general_options['column_settings']['arp_column_border_type'] = 'solid';
            $general_options['column_settings']['arp_column_border_color'] = '#cecece';
            $general_options['column_settings']['arp_column_border_left'] = 0;
            $general_options['column_settings']['arp_column_border_right'] = 0;
            $general_options['column_settings']['arp_column_border_top'] = 1;
            $general_options['column_settings']['arp_column_border_bottom'] = 1;

            $general_options['column_settings']['arp_caption_border_size'] = '1';
            $general_options['column_settings']['arp_caption_border_style'] = 'solid';
            $general_options['column_settings']['arp_caption_border_color'] = '#cecece';
            $general_options['column_settings']['arp_caption_border_left'] = 1;
            $general_options['column_settings']['arp_caption_border_right'] = 0;
            $general_options['column_settings']['arp_caption_border_top'] = 1;
            $general_options['column_settings']['arp_caption_border_bottom'] = 1;

            $general_options['column_settings']['hide_header_global'] = 0;
            $general_options['column_settings']['hide_price_global'] = 0;
            $general_options['column_settings']['hide_feature_global'] = 0;
            $general_options['column_settings']['hide_description_global'] = 0;
            $general_options['column_settings']['hide_header_shortcode_global'] = 0;
        } else if ($reference_template == "arplitetemplate_8") {
            $general_options['column_settings']['global_button_border_width'] = 0;
            $general_options['column_settings']['global_button_border_type'] = 'solid';
            $general_options['column_settings']['global_button_border_color'] = '#c9c9c9';
            $general_options['column_settings']['global_button_border_radius_top_left'] = 20;
            $general_options['column_settings']['global_button_border_radius_top_right'] = 20;
            $general_options['column_settings']['global_button_border_radius_bottom_left'] = 20;
            $general_options['column_settings']['global_button_border_radius_bottom_right'] = 20;
            $general_options['column_settings']['arp_global_button_type'] = 'shadow';

            $general_options['column_settings']['arp_row_border_size'] = '1';
            $general_options['column_settings']['arp_row_border_type'] = 'solid';
            $general_options['column_settings']['arp_row_border_color'] = '#d4d4d4';

            $general_options['column_settings']['arp_caption_row_border_size'] = '1';
            $general_options['column_settings']['arp_caption_row_border_style'] = 'solid';
            $general_options['column_settings']['arp_caption_row_border_color'] = '#d4d4d4';

            $general_options['column_settings']['arp_column_border_size'] = '1';
            $general_options['column_settings']['arp_column_border_type'] = 'solid';
            $general_options['column_settings']['arp_column_border_color'] = '#dfdbdc';
            $general_options['column_settings']['arp_column_border_left'] = 1;
            $general_options['column_settings']['arp_column_border_right'] = 1;
            $general_options['column_settings']['arp_column_border_top'] = 1;
            $general_options['column_settings']['arp_column_border_bottom'] = 1;

            $general_options['column_settings']['arp_caption_border_size'] = '0';
            $general_options['column_settings']['arp_caption_border_style'] = 'solid';
            $general_options['column_settings']['arp_caption_border_color'] = '#cecece';
            $general_options['column_settings']['arp_caption_border_left'] = 0;
            $general_options['column_settings']['arp_caption_border_right'] = 0;
            $general_options['column_settings']['arp_caption_border_top'] = 0;
            $general_options['column_settings']['arp_caption_border_bottom'] = 0;

            $general_options['column_settings']['hide_header_global'] = 0;
            $general_options['column_settings']['hide_price_global'] = 0;
            $general_options['column_settings']['hide_feature_global'] = 0;
            $general_options['column_settings']['hide_description_global'] = 0;
            $general_options['column_settings']['hide_header_shortcode_global'] = 0;
        } else if ($reference_template == "arplitetemplate_11") {
            $general_options['column_settings']['global_button_border_width'] = 0;
            $general_options['column_settings']['global_button_border_type'] = 'solid';
            $general_options['column_settings']['global_button_border_color'] = '#c9c9c9';
            $general_options['column_settings']['global_button_border_radius_top_left'] = 0;
            $general_options['column_settings']['global_button_border_radius_top_right'] = 0;
            $general_options['column_settings']['global_button_border_radius_bottom_left'] = 0;
            $general_options['column_settings']['global_button_border_radius_bottom_right'] = 0;
            $general_options['column_settings']['arp_global_button_type'] = 'shadow';

            $general_options['column_settings']['arp_row_border_size'] = '0';
            $general_options['column_settings']['arp_row_border_type'] = 'solid';
            $general_options['column_settings']['arp_row_border_color'] = '#c9c9c9';

            $general_options['column_settings']['arp_caption_row_border_size'] = '0';
            $general_options['column_settings']['arp_caption_row_border_style'] = 'solid';
            $general_options['column_settings']['arp_caption_row_border_color'] = '#c9c9c9';

            $general_options['column_settings']['arp_column_border_size'] = '1';
            $general_options['column_settings']['arp_column_border_type'] = 'solid';
            $general_options['column_settings']['arp_column_border_color'] = '#525252';
            $general_options['column_settings']['arp_column_border_left'] = 0;
            $general_options['column_settings']['arp_column_border_right'] = 1;
            $general_options['column_settings']['arp_column_border_top'] = 0;
            $general_options['column_settings']['arp_column_border_bottom'] = 0;

            $general_options['column_settings']['arp_caption_border_size'] = '0';
            $general_options['column_settings']['arp_caption_border_style'] = 'solid';
            $general_options['column_settings']['arp_caption_border_color'] = '#cecece';
            $general_options['column_settings']['arp_caption_border_left'] = 0;
            $general_options['column_settings']['arp_caption_border_right'] = 0;
            $general_options['column_settings']['arp_caption_border_top'] = 0;
            $general_options['column_settings']['arp_caption_border_bottom'] = 0;

            $general_options['column_settings']['hide_header_global'] = 0;
            $general_options['column_settings']['hide_price_global'] = 0;
            $general_options['column_settings']['hide_feature_global'] = 0;
            $general_options['column_settings']['hide_description_global'] = 0;
            $general_options['column_settings']['hide_header_shortcode_global'] = 0;
        }

        $get_temp_options = $wpdb->get_results($wpdb->prepare("SELECT * FROM $arplite_arprice_options WHERE table_id = %d", $imported_id));
        if (!empty($get_temp_options)) {
            $result = $get_temp_options[0];
            $column_options = maybe_unserialize($result->table_options);

            foreach ($column_options['columns'] as $key => $value) {
                if ($column_options['columns'][$key]['is_caption'] == 1) {
                    
                } else {
                    $mycurrentcolumn = $column_options['columns'][$key];
                    break;
                }
            }
        }

        $general_options['column_settings']['header_font_family_global'] = isset($mycurrentcolumn['header_font_family']) ? $mycurrentcolumn['header_font_family'] : 'Open Sans';
        $general_options['column_settings']['header_font_size_global'] = isset($mycurrentcolumn['header_font_size']) ? $mycurrentcolumn['header_font_size'] : '28';
        $general_options['column_settings']['arp_header_text_alignment'] = 'center';
        $general_options['column_settings']['arp_header_text_bold_global'] = isset($mycurrentcolumn['header_style_bold']) ? $mycurrentcolumn['header_style_bold'] : '';
        $general_options['column_settings']['arp_header_text_italic_global'] = isset($mycurrentcolumn['header_style_italic']) ? $mycurrentcolumn['header_style_italic'] : '';
        $general_options['column_settings']['arp_header_text_decoration_global'] = isset($mycurrentcolumn['header_style_decoration']) ? $mycurrentcolumn['header_style_decoration'] : '';

        $general_options['column_settings']['price_font_family_global'] = isset($mycurrentcolumn['price_font_family']) ? $mycurrentcolumn['price_font_family'] : 'Open Sans';
        $general_options['column_settings']['price_font_size_global'] = isset($mycurrentcolumn['price_font_size']) ? $mycurrentcolumn['price_font_size'] : '18';
        $general_options['column_settings']['arp_price_text_alignment'] = 'center';
        $general_options['column_settings']['arp_price_text_bold_global'] = isset($mycurrentcolumn['price_label_style_bold']) ? $mycurrentcolumn['price_label_style_bold'] : 'bold';
        $general_options['column_settings']['arp_price_text_italic_global'] = isset($mycurrentcolumn['price_label_style_italic']) ? $mycurrentcolumn['price_label_style_italic'] : '';
        $general_options['column_settings']['arp_price_text_decoration_global'] = isset($mycurrentcolumn['price_label_style_decoration']) ? $mycurrentcolumn['price_label_style_decoration'] : '';

        $general_options['column_settings']['body_font_family_global'] = isset($mycurrentcolumn['content_font_family']) ? $mycurrentcolumn['content_font_family'] : 'Arial';
        $general_options['column_settings']['body_font_size_global'] = isset($mycurrentcolumn['content_font_size']) ? $mycurrentcolumn['content_font_size'] : 'Arial';
        $general_options['column_settings']['arp_body_text_alignment'] = isset($mycurrentcolumn['body_text_alignment']) ? $mycurrentcolumn['body_text_alignment'] : 'center';
        $general_options['column_settings']['arp_body_text_bold_global'] = '';
        $general_options['column_settings']['arp_body_text_italic_global'] = '';
        $general_options['column_settings']['arp_body_text_decoration_global'] = '';

        $general_options['column_settings']['footer_font_family_global'] = isset($mycurrentcolumn['footer_level_options_font_family']) ? $mycurrentcolumn['footer_level_options_font_family'] : 'Open Sans Bold';
        $general_options['column_settings']['footer_font_size_global'] = isset($mycurrentcolumn['footer_level_options_font_size']) ? $mycurrentcolumn['footer_level_options_font_size'] : '12';
        $general_options['column_settings']['arp_footer_text_alignment'] = 'center';
        $general_options['column_settings']['arp_footer_text_bold_global'] = isset($mycurrentcolumn['footer_level_options_font_style_bold']) ? $mycurrentcolumn['footer_level_options_font_style_bold'] : '';
        $general_options['column_settings']['arp_footer_text_italic_global'] = isset($mycurrentcolumn['footer_level_options_font_style_italic']) ? $mycurrentcolumn['footer_level_options_font_style_italic'] : '';
        $general_options['column_settings']['arp_footer_text_decoration_global'] = isset($mycurrentcolumn['footer_level_options_font_style_decoration']) ? $mycurrentcolumn['footer_level_options_font_style_decoration'] : '';


        $general_options['column_settings']['button_font_family_global'] = isset($mycurrentcolumn['button_font_family']) ? $mycurrentcolumn['button_font_family'] : 'Open Sans Bold';
        $general_options['column_settings']['button_font_size_global'] = isset($mycurrentcolumn['button_font_size']) ? $mycurrentcolumn['button_font_size'] : '17';
        $general_options['column_settings']['arp_button_text_bold_global'] = isset($mycurrentcolumn['button_style_bold']) ? $mycurrentcolumn['button_style_bold'] : '';
        $general_options['column_settings']['arp_button_text_italic_global'] = isset($mycurrentcolumn['button_style_italic']) ? $mycurrentcolumn['button_style_italic'] : '';
        $general_options['column_settings']['arp_button_text_decoration_global'] = isset($mycurrentcolumn['button_style_decoration']) ? $mycurrentcolumn['button_style_decoration'] : '';

        $general_options['column_settings']['description_font_family_global'] = isset($mycurrentcolumn['column_description_font_family']) ? $mycurrentcolumn['column_description_font_family'] : '';
        $general_options['column_settings']['description_font_size_global'] = isset($mycurrentcolumn['column_description_font_size']) ? $mycurrentcolumn['column_description_font_size'] : '';
        $general_options['column_settings']['arp_description_text_alignment'] = 'center';
        $general_options['column_settings']['arp_description_text_bold_global'] = isset($mycurrentcolumn['column_description_style_bold']) ? $mycurrentcolumn['column_description_style_bold'] : '';
        $general_options['column_settings']['arp_description_text_italic_global'] = isset($mycurrentcolumn['column_description_style_italic']) ? $mycurrentcolumn['column_description_style_italic'] : '';
        $general_options['column_settings']['arp_description_text_decoration_global'] = isset($mycurrentcolumn['column_description_style_decoration']) ? $mycurrentcolumn['column_description_style_decoration'] : '';


        $general_options = maybe_serialize($general_options);

        $qry = $wpdb->prepare("UPDATE " . $wpdb->prefix . "arplite_arprice SET `general_options` = %s WHERE `ID` = %d", $general_options, $imported_id);
        $wpdb->query($qry);


        $column_options = array();
        $get_temp_options = $wpdb->get_results($wpdb->prepare("SELECT * FROM $arplite_arprice_options WHERE table_id = %d", $imported_id));

        if (!empty($get_temp_options)) {
            $result = $get_temp_options[0];
            $column_options = maybe_unserialize($result->table_options);

            foreach ($column_options['columns'] as $key => $value) {


                // merge pricing section
                $arp_price_label_css = '';
                if (isset($column_options['columns'][$key]['price_text_font_size'])) {
                    $arp_price_label_css .= 'font-size: ' . $column_options['columns'][$key]['price_text_font_size'] . 'px;';
                }
                if ($column_options['columns'][$key]['price_text_style_bold'] == 'bold') {
                    $arp_price_label_css .='font-weight:bold;';
                } else {
                    $arp_price_label_css .='font-weight:normal;';
                }
                if ($column_options['columns'][$key]['price_text_style_italic'] == 'italic') {
                    $arp_price_label_css .='font-style:italic;';
                }
                if ($column_options['columns'][$key]['price_text_style_decoration'] == 'underline') {
                    $arp_price_label_css .='text-decoration:underline;';
                }
                if ($column_options['columns'][$key]['price_text_style_decoration'] == 'line-through') {
                    $arp_price_label_css .='text-decoration:line-through;';
                }

                if ($reference_template == "arplitetemplate_11") {
                    $column_options['columns'][$key]['price_text'] = "<span class='arp_price_value'>" . $column_options['columns'][$key]['price_text'] . "</span><span class='arp_price_duration' style='" . $arp_price_label_css . "'>" . $column_options['columns'][$key]['price_label'] . "</span>";

                    $column_options['columns'][$key]['price_label'] = '';
                    $column_options['columns'][$key]['price_text_input_two_step_label'] = '';
                    $column_options['columns'][$key]['price_text_input_three_step_label'] = '';
                }

                if ($reference_template == "arplitetemplate_8") {
                    $column_options['columns'][$key]['price_text'] = "<span class='arp_price_duration' style='" . $arp_price_label_css . "'>" . $column_options['columns'][$key]['price_label'] . "</span><span class='arp_price_value'>" . $column_options['columns'][$key]['price_text'] . "</span>";

                    $column_options['columns'][$key]['price_label'] = '';
                    $column_options['columns'][$key]['price_text_input_two_step_label'] = '';
                    $column_options['columns'][$key]['price_text_input_three_step_label'] = '';
                }

                //migrate shapes
                if ($reference_template == "arplitetemplate_8") {

                    if ($reference_template == "arplitetemplate_8") {
                        $column_options['columns'][$key]['arp_shortcode_customization_size'] = 'small';
                        $column_options['columns'][$key]['arp_shortcode_customization_style'] = 'rounded';
                        $column_options['columns'][$key]['shortcode_background_color'] = '#ffffff';
                        $column_options['columns'][$key]['shortcode_font_color'] = '#ffffff';
                        $column_options['columns'][$key]['shortcode_hover_background_color'] = '#ffffff';
                        $column_options['columns'][$key]['shortcode_hover_font_color'] = '#ffffff';
                    }
                } else {
                    $column_options['columns'][$key]['arp_shortcode_customization_size'] = 'medium';
                    $column_options['columns'][$key]['arp_shortcode_customization_style'] = 'none';
                    $column_options['columns'][$key]['shortcode_background_color'] = '';
                    $column_options['columns'][$key]['shortcode_font_color'] = '';
                    $column_options['columns'][$key]['shortcode_hover_background_color'] = '';
                    $column_options['columns'][$key]['shortcode_hover_font_color'] = '';
                }

                if ($reference_template == "arplitetemplate_8") {
                    $column_options['columns'][$key]['column_hover_background_color'] = '';
                    $column_options['columns'][$key]['header_hover_background_color'] = $column_options['columns'][$key]['header_background_color'];
                    $column_options['columns'][$key]['header_hover_font_color'] = $column_options['columns'][$key]['header_font_color'];
                    $column_options['columns'][$key]['price_hover_background_color'] = $column_options['columns'][$key]['price_background_color'];
                    $column_options['columns'][$key]['price_hover_font_color'] = $column_options['columns'][$key]['price_font_color'];
                    $column_options['columns'][$key]['content_even_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_hover_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_even_hover_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_odd_hover_color'] = '#ffffff';
                    $column_options['columns'][$key]['content_even_hover_color'] = '#ffffff';
                    $column_options['columns'][$key]['button_hover_background_color'] = $column_options['columns'][$key]['button_background_color'];
                    $column_options['columns'][$key]['button_hover_font_color'] = $column_options['columns'][$key]['button_font_color'];
                    $column_options['columns'][$key]['column_description_hover_font_color'] = '';
                    $column_options['columns'][$key]['column_desc_hover_background_color'] = '';
                    $column_options['columns'][$key]['footer_hover_background_color'] = '';
                    $column_options['columns'][$key]['footer_level_options_hover_font_color'] = '';
                    $column_options['columns'][$key]['shortcode_background_color'] = '#ffffff';
                    $column_options['columns'][$key]['shortcode_font_color'] = '#ffffff';
                    $column_options['columns'][$key]['shortcode_hover_background_color'] = '#ffffff';
                    $column_options['columns'][$key]['shortcode_hover_font_color'] = '#ffffff';

                    //merge rows
                    foreach ($value['rows'] as $key1 => $value1) {
                        $column_options['columns'][$key]['rows'][$key1]['row_description'] = $column_options['columns'][$key]['rows'][$key1]['row_label'] . '<br><b>' . $column_options['columns'][$key]['rows'][$key1]['row_description'] . '</b>';
                    }


                    if ($column_options['columns'][$key]['button_size'] == 'Small') {
                        $column_options['columns'][$key]['button_size'] = '110';
                        $column_options['columns'][$key]['button_height'] = '30';
                    }
                    if ($column_options['columns'][$key]['button_size'] == 'Medium') {
                        $column_options['columns'][$key]['button_size'] = '134';
                        $column_options['columns'][$key]['button_height'] = '36';
                    }
                    if ($column_options['columns'][$key]['button_size'] == 'Large') {
                        $column_options['columns'][$key]['button_size'] = '122';
                        $column_options['columns'][$key]['button_height'] = '51';
                    }
                }
                if ($reference_template == "arplitetemplate_1") {
                    $column_options['columns'][$key]['column_hover_background_color'] = '';
                    $column_options['columns'][$key]['header_hover_background_color'] = $column_options['columns'][$key]['header_background_color'];
                    $column_options['columns'][$key]['header_hover_font_color'] = $column_options['columns'][$key]['header_font_color'];
                    $column_options['columns'][$key]['price_hover_background_color'] = $column_options['columns'][$key]['price_background_color'];
                    $column_options['columns'][$key]['price_hover_font_color'] = $column_options['columns'][$key]['price_font_color'];
                    $column_options['columns'][$key]['content_even_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_hover_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_even_hover_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_odd_hover_color'] = $column_options['columns'][$key]['content_odd_color'];
                    $column_options['columns'][$key]['content_even_hover_color'] = $column_options['columns'][$key]['content_even_color'];
                    $column_options['columns'][$key]['button_hover_background_color'] = $arpricelite_form->arp_generate_color_tone($column_options['columns'][$key]['button_background_color'], -30);
                    $column_options['columns'][$key]['button_hover_font_color'] = $arpricelite_form->arp_generate_color_tone($column_options['columns'][$key]['button_font_color'], -30);
                    $column_options['columns'][$key]['column_description_hover_font_color'] = '';
                    $column_options['columns'][$key]['column_desc_hover_background_color'] = '';
                    $column_options['columns'][$key]['footer_hover_background_color'] = $column_options['columns'][$key]['footer_background_color'];
                    $column_options['columns'][$key]['footer_level_options_hover_font_color'] = $column_options['columns'][$key]['footer_level_options_font_color'];


                    if ($column_options['columns'][$key]['button_size'] == 'Small') {
                        $column_options['columns'][$key]['button_size'] = '122';
                        $column_options['columns'][$key]['button_height'] = '30';
                    }
                    if ($column_options['columns'][$key]['button_size'] == 'Medium') {
                        $column_options['columns'][$key]['button_size'] = '140';
                        $column_options['columns'][$key]['button_height'] = '45';
                    }
                    if ($column_options['columns'][$key]['button_size'] == 'Large') {
                        $column_options['columns'][$key]['button_size'] = '158';
                        $column_options['columns'][$key]['button_height'] = '54';
                    }
                }
                if ($reference_template == "arplitetemplate_11") {
                    $column_options['columns'][$key]['column_hover_background_color'] = '';
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['header_hover_background_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 25);
                    } else {
                        $column_options['columns'][$key]['header_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['header_bg_color'][0];
                    }
                    $column_options['columns'][$key]['header_hover_font_color'] = $column_options['columns'][$key]['header_font_color'];
                    $column_options['columns'][$key]['price_hover_background_color'] = '';
                    $column_options['columns'][$key]['price_hover_font_color'] = $column_options['columns'][$key]['price_font_color'];
                    $column_options['columns'][$key]['content_even_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_hover_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    $column_options['columns'][$key]['content_even_hover_font_color'] = $column_options['columns'][$key]['content_font_color'];
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['content_odd_hover_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 5);
                    } else {
                        $column_options['columns'][$key]['content_odd_hover_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_body_odd_row_hover_background_color'][0];
                    }
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['content_even_hover_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 15);
                    } else {
                        $column_options['columns'][$key]['content_even_hover_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_body_even_row_hover_background_color'][0];
                    }
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['button_hover_background_color'] = $general_options['custom_skin_colors']["arp_button_bg_hover_color"];
                    } else {
                        $column_options['columns'][$key]['button_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['button_bg_color'][0];
                    }

                    $column_options['columns'][$key]['button_hover_font_color'] = $column_options['columns'][$key]['button_font_color'];

                    $column_options['columns'][$key]['column_description_hover_font_color'] = $column_options['columns'][$key]['column_description_font_color'];
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['column_desc_hover_background_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 25);
                    } else {
                        $column_options['columns'][$key]['column_desc_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_desc_hover_background'][0];
                    }
                    $column_options['columns'][$key]['footer_hover_background_color'] = '';
                    $column_options['columns'][$key]['footer_level_options_hover_font_color'] = '';

                    if ($column_options['columns'][$key]['button_size'] == 'Small') {
                        $column_options['columns'][$key]['button_size'] = '122';
                        $column_options['columns'][$key]['button_height'] = '33';
                    }
                    if ($column_options['columns'][$key]['button_size'] == 'Medium') {
                        $column_options['columns'][$key]['button_size'] = '158';
                        $column_options['columns'][$key]['button_height'] = '45';
                    }
                    if ($column_options['columns'][$key]['button_size'] == 'Large') {
                        $column_options['columns'][$key]['button_size'] = '146';
                        $column_options['columns'][$key]['button_height'] = '54';
                    }
                }
            }

            $column_options = maybe_serialize($column_options);

            $qry_opt = $wpdb->prepare("UPDATE " . $wpdb->prefix . "arplite_arprice_options SET `table_options` = %s WHERE `ID` = %d", $column_options, $imported_id);
            $wpdb->query($qry_opt);
        }




        //migrate existing css with reference table's css
        $reference_id_array = array();
        $original_ref_template = $reference_template;
        $reference_id_array = explode("arplitetemplate_", $original_ref_template);
        $reference_id = $reference_id_array[1];

        $css_directory = ARPLITE_PRICINGTABLE_DIR . '/css/templates';
        $file = $css_directory . '/arplitetemplate_' . $reference_id . '_v' . $arpricelite_img_css_version . '.css';

        $file_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $reference_id . '_v' . $arpricelite_img_css_version . '.css';

        $new_file = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/arplitetemplate_' . $imported_id . '.css';

        $css_content_data = wp_remote_get( $file_url, array(
            'sslverify' => false
        ) );
        $css = $css_content_data['body'];

        $css_url =- 
        $css_content = preg_replace('/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $imported_id, $css);
        $css_content = str_replace('../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_content);

        $wp_filesystem->put_contents($new_file, $css_content, 0777);
    }
}

if (version_compare($checkupdate, '1.2.2', '<')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem();
    global $wp_filesystem, $wpdb;

    $arplite_arprice = $wpdb->prefix . 'arplite_arprice';

    $templates = $wpdb->get_results("SELECT * FROM `" . $arplite_arprice . "` where is_template = 0 ORDER BY ID ASC");
    global $arpricelite_form, $arpricelite_img_css_version;

    foreach ($templates as $template) {
        $original_general_options = '';
        $original_table_options = '';
        $result = $template;
        $imported_id = $result->ID;
        $reference_id = $result->template_name;
        $general_options = maybe_unserialize($result->general_options);
        $reference_template = $general_options['general_settings']['reference_template'];

        //migrate existing css with reference table's css
        $reference_id_array = array();
        $original_ref_template = $reference_template;
        $reference_id_array = explode("arplitetemplate_", $original_ref_template);
        $reference_id = $reference_id_array[1];

        if ($reference_id == 26) {
            $reference_id = 23;
        }

        $css_directory = ARPLITE_PRICINGTABLE_DIR . '/css/templates';
        $file = $css_directory . '/arplitetemplate_' . $reference_id . '_v' . $arpricelite_img_css_version . '.css';

        $file_url = ARPLITE_PRICINTABLE_URL . '/css/templates/arplitetemplate_' . $reference_id . '_v' . $arpricelite_img_css_version . '.css';

        $new_file = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/arplitetemplate_' . $imported_id . '.css';

        $css_content_data = wp_remote_get( $file_url, array(
            'sslverify' => false
        ) );
        $css = $css_content_data['css'];
        $css_content = preg_replace('/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $imported_id, $css);
        $css_content = str_replace('../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_content);

        $wp_filesystem->put_contents($new_file, $css_content, 0777);
    }
}

if (version_compare($checkupdate, '1.3', '<')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem();
    global $wp_filesystem, $wpdb;

    $arplite_arprice = $wpdb->prefix . 'arplite_arprice';

    $templates = $wpdb->get_results("SELECT * FROM `" . $arplite_arprice . "` where is_template = 0 ORDER BY ID ASC");
    global $arpricelite_form, $arpricelite_img_css_version;

    foreach ($templates as $template) {
        $original_general_options = '';
        $original_table_options = '';
        $result = $template;
        $imported_id = $result->ID;
        $reference_id = $result->template_name;
        $general_options = maybe_unserialize($result->general_options);
        $reference_template = $general_options['general_settings']['reference_template'];

        //migrate existing css with reference table's css
        $reference_id_array = array();
        $original_ref_template = $reference_template;
        $reference_id_array = explode("arplitetemplate_", $original_ref_template);
        $reference_id = $reference_id_array[1];

        if ($reference_id == 26) {
            $reference_id = 23;
        }

        $css_directory = ARPLITE_PRICINGTABLE_DIR . '/css/templates';
        $file = $css_directory . '/arplitetemplate_' . $reference_id . '_v' . $arpricelite_img_css_version . '.css';

        $file_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/arplitetemplate_' . $reference_id . '_v' . $arpricelite_img_css_version . '.css';

        $new_file = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/arplitetemplate_' . $imported_id . '.css';

        $css_content_data = wp_remote_get( $file_url, array(
            'sslverify' => false
        ) );
        $css = $css_content_data['body'];

        $css_content = preg_replace('/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $imported_id, $css);
        $css_content = str_replace('../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_content);

        $wp_filesystem->put_contents($new_file, $css_content, 0777);
    }
}

if (version_compare($checkupdate, '1.5', '<')) {
    global $wpdb, $arpricelite_form, $arpricelite_default_settings;
    $table = $wpdb->prefix . 'arplite_arprice';
    $templates = $wpdb->get_results("SELECT `ID`,`general_options` FROM `{$table}`");
    if (!empty($templates)) {
        foreach ($templates as $key => $template) {
            $table_id = $template->ID;
            $general_options = maybe_unserialize($template->general_options);
            $current_color_skin = $general_options['template_setting']['skin'];
            $reference_template = $general_options['general_settings']['reference_template'];
            $arp_pt_custom_skin_array = array();
            switch ($reference_template) {
                case 'arplitetemplate_1':
                    $arp_pt_custom_skin_array['arp_header_bg_custom_color'] = "#85d538";
                    $arp_pt_custom_skin_array['arp_column_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = "#70b828";
                    $arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = "#ffffff";
                    $arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = "#e9e9e9";
                    $arp_pt_custom_skin_array['arp_footer_content_bg_color'] = "#e3e3e3";
                    $arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#85d538";
                    $arp_pt_custom_skin_array['arp_column_bg_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_bg_hover_color'] = '#5d9527';
                    $arp_pt_custom_skin_array['arp_header_bg_hover_color'] = '#85d538';
                    $arp_pt_custom_skin_array['arp_price_bg_hover_color'] = '#70b828';
                    $arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = '#e9e9e9';
                    $arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = '#e3e3e3';
                    $arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_desc_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#364762';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#364762';
                    $arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#364762';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#364762';
                    $arp_pt_custom_skin_array['arp_footer_font_custom_color'] = '#364762';
                    $arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = '#364762';
                    $arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#ffffff';

                    $general_options['custom_skin_colors'] = $arp_pt_custom_skin_array;
                    break;
                case 'arplitetemplate_8':
                    $arp_pt_custom_skin_array['arp_header_bg_custom_color'] = "#ee4546";
                    $arp_pt_custom_skin_array['arp_column_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = "#ffffff";
                    $arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = "#f7f8fa";
                    $arp_pt_custom_skin_array['arp_footer_content_bg_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#ffffff";
                    $arp_pt_custom_skin_array['arp_column_bg_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_bg_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_header_bg_hover_color'] = '#ee4546';
                    $arp_pt_custom_skin_array['arp_price_bg_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = null;
                    $arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_desc_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = '#000000';
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = '#000000';
                    $arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#333333';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#333333';
                    $arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#333333';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#333333';
                    $arp_pt_custom_skin_array['arp_footer_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#323232';
                    $arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#323232';
                    $arp_pt_custom_skin_array['arp_shortocode_background'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_shortocode_font_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_shortcode_bg_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_shortcode_font_hover_color'] = '#ffffff';
                    $general_options['custom_skin_colors'] = $arp_pt_custom_skin_array;
                    break;
                case 'arplitetemplate_11':
                    $arp_pt_custom_skin_array['arp_header_bg_custom_color'] = "#414045";
                    $arp_pt_custom_skin_array['arp_column_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = "#37363b";
                    $arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = "#313035";
                    $arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = "#37363b";
                    $arp_pt_custom_skin_array['arp_footer_content_bg_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#efa738";
                    $arp_pt_custom_skin_array['arp_column_bg_hover_color'] = "#46474c";
                    $arp_pt_custom_skin_array['arp_button_bg_hover_color'] = "#09B1F8";
                    $arp_pt_custom_skin_array['arp_header_bg_hover_color'] = '#51545D';
                    $arp_pt_custom_skin_array['arp_price_bg_hover_color'] = '#46474C';
                    $arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = '#3E4044';
                    $arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = '#46474C';
                    $arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = null;
                    $arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = '#46474C';
                    $arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_desc_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_footer_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#ffffff';

                    $general_options['custom_skin_colors'] = $arp_pt_custom_skin_array;
                    break;
                case 'arplitetemplate_26':
                    $arp_pt_custom_skin_array['arp_header_bg_custom_color'] = "#2EB7FD";
                    $arp_pt_custom_skin_array['arp_column_bg_custom_color'] = "#2B2E37";
                    $arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_footer_content_bg_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#2FB8FF";
                    $arp_pt_custom_skin_array['arp_column_bg_hover_color'] = "#2B2E37";
                    $arp_pt_custom_skin_array['arp_button_bg_hover_color'] = "#08090B";
                    $arp_pt_custom_skin_array['arp_header_bg_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_price_bg_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = null;
                    $arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_price_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_desc_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#2B2E37';
                    $arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#2B2E37';
                    $arp_pt_custom_skin_array['arp_footer_font_custom_color'] = null;
                    $arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = null;
                    $arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_shortocode_background'] = '#2fb8ff';
                    $arp_pt_custom_skin_array['arp_shortocode_font_color'] = '#ffffff';
                    $arp_pt_custom_skin_array['arp_shortcode_bg_hover_color'] = '#2fb8ff';
                    $arp_pt_custom_skin_array['arp_shortcode_font_hover_color'] = '#2fb8ff';

                    $general_options['custom_skin_colors'] = $arp_pt_custom_skin_array;
                    break;
                default:
                    break;
            }
            $general_options = maybe_serialize($general_options);
            $wpdb->update($table, array('general_options' => $general_options), array('ID' => $table_id));

            $get_temp_options = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d", $table_id));
            $result = $get_temp_options[0];
            $column_options = maybe_unserialize($result->table_options);
            $section_bg_color = $arpricelite_default_settings->arp_column_section_background_color();
            foreach ($column_options['columns'] as $key => $value) {

                //migrate colors
                if ($reference_template == "arplitetemplate_1") {
                    $column_options['columns'][$key]['column_hover_background_color'] = '';
                    $column_options['columns'][$key]['header_hover_background_color'] = $column_options['columns'][$key]['header_background_color'];

                    $column_options['columns'][$key]['price_hover_background_color'] = $column_options['columns'][$key]['price_background_color'];

                    $column_options['columns'][$key]['content_odd_hover_color'] = $column_options['columns'][$key]['content_odd_color'];
                    $column_options['columns'][$key]['content_even_hover_color'] = $column_options['columns'][$key]['content_even_color'];
                    $column_options['columns'][$key]['button_hover_background_color'] = $arpricelite_form->arp_generate_color_tone($column_options['columns'][$key]['button_background_color'], -30);

                    $column_options['columns'][$key]['column_desc_hover_background_color'] = '';
                    
                    $column_options['columns'][$key]['footer_hover_background_color'] ='#e3e3e3';
                    $column_options['columns'][$key]['footer_background_color']='#e3e3e3';
                }

                if ($reference_template == "arplitetemplate_8") {
                    $column_options['columns'][$key]['column_hover_background_color'] = '';
                    $column_options['columns'][$key]['header_hover_background_color'] = $column_options['columns'][$key]['header_background_color'];

                    $column_options['columns'][$key]['price_hover_background_color'] = $column_options['columns'][$key]['price_background_color'];

                    $column_options['columns'][$key]['content_odd_hover_color'] = '#ffffff';
                    $column_options['columns'][$key]['content_even_hover_color'] = '#ffffff';
                    $column_options['columns'][$key]['button_hover_background_color'] = $column_options['columns'][$key]['button_background_color'];

                    $column_options['columns'][$key]['column_desc_hover_background_color'] = '';
                    $column_options['columns'][$key]['footer_hover_background_color'] = '';

                    $column_options['columns'][$key]['shortcode_background_color'] = '#ffffff';

                    $column_options['columns'][$key]['shortcode_hover_background_color'] = '#ffffff';
                }

                if ($reference_template == "arplitetemplate_11") {
                    $column_options['columns'][$key]['column_hover_background_color'] = '';
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['header_hover_background_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 25);
                    } else {
                        $column_options['columns'][$key]['header_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['header_bg_color'][0];
                    }

                    $column_options['columns'][$key]['price_hover_background_color'] = '';



                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['content_odd_hover_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 5);
                    } else {
                        $column_options['columns'][$key]['content_odd_hover_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_body_odd_row_hover_background_color'][0];
                    }
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['content_even_hover_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 15);
                    } else {
                        $column_options['columns'][$key]['content_even_hover_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_body_even_row_hover_background_color'][0];
                    }
                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['button_hover_background_color'] = $general_options['custom_skin_colors']["arp_button_bg_hover_color"];
                    } else {
                        $column_options['columns'][$key]['button_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['button_bg_color'][0];
                    }


                    if ($current_color_skin == 'custom_skin') {
                        $column_options['columns'][$key]['column_desc_hover_background_color'] = $arpricelite_form->arp_generate_color_tone($general_options['custom_skin_colors']["arp_column_bg_hover_color"], 15);
                    } else {
                        $column_options['columns'][$key]['column_desc_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_desc_hover_background'][0];
                    }
                    $column_options['columns'][$key]['footer_hover_background_color'] = '';
                }

                if ($reference_template == "arplitetemplate_26") {
                    $column_options['columns'][$key]['column_hover_background_color'] =  $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['column_bg_color'][0];
                    $column_options['columns'][$key]['column_background_color'] = '#2B2E37';
                    
                    $column_options['columns'][$key]['header_hover_background_color'] = '#08090B';

                    
                    $column_options['columns'][$key]['shortcode_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['arp_shortcode_hover_background'][0];

                    $column_options['columns'][$key]['price_hover_background_color'] = '';
                    $column_options['columns'][$key]['content_odd_hover_color'] = '';
                    $column_options['columns'][$key]['content_even_hover_color'] = '';
                    $column_options['columns'][$key]['button_hover_background_color'] = $section_bg_color[$reference_template][$current_color_skin]['arp_hover_color']['button_bg_color'][0];

                    $column_options['columns'][$key]['column_desc_hover_background_color'] = '';

                    $column_options['columns'][$key]['footer_hover_background_color'] = '';
                }
            }

            $column_options = maybe_serialize($column_options);

            $qry_opt = $wpdb->prepare("UPDATE " . $wpdb->prefix . "arplite_arprice_options SET `table_options` = %s WHERE `ID` = %d", $column_options, $table_id);
            $wpdb->query($qry_opt);
        }
    }
}

if(version_compare($checkupdate, '1.6', '<')){
    global $arplite_pricingtable;
    $args = array(
        'role' => 'administrator',
        'fields' => 'id'
    );
    $users = get_users($args);
    if (count($users) > 0) {
        foreach ($users as $key => $user_id) {
            $arproles = $arplite_pricingtable->arp_capabilities();
            $userObj = new WP_User($user_id);
            
            foreach ($arproles as $arprole => $arproledescription){
                
                $userObj->add_cap($arprole);
            }

            unset($arproles);
            unset($arprole);
            unset($arproledescription);
        }
    }
}

if( version_compare($checkupdate, '2.0', '<') ){
    require ARPLITE_PRICINGTABLE_VIEWS_DIR.'/upgrade_latest_data_v2.0.php';
}

if( version_compare($checkupdate, '2.3', '<') ){
    update_option('arplite_db_version_before_2.3',$checkupdate);

    /* Fetch All template id with reference template 7 */
    $arplite_arprice = $wpdb->prefix . 'arplite_arprice';
    $arplite_arprice_options = $wpdb->prefix . 'arplite_arprice_options';

    $table_ids = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM `" . $arplite_arprice . "` WHERE `general_options` REGEXP %s", '.*"reference_template";s:[0-9]+:"arplitetemplate_7".*' ) );

    foreach( $table_ids as $key => $value ){
        $table_id = $value->ID;
        $table_opts = $wpdb->get_row( $wpdb->prepare( "SELECT table_options FROM `" . $arplite_arprice_options . "` WHERE `ID` = %d", $table_id ) );

        $column_opts = maybe_unserialize( $table_opts->table_options );

        $columns = $column_opts['columns'];
        $new_col_opts = array();
        $new_col_opts['columns'] = array();
        $new_col_opts['columns'] = $columns;
        foreach( $columns as $k => $col ){
            $new_col_opts['columns'][$k]['arp_header_shortcode'] = isset( $col['arp_header_shortcode'] ) ? str_replace('[arp_header_image','[arplite_header_image', $col['arp_header_shortcode']) : '';
        }

        $new_opts = maybe_serialize( $new_col_opts );

        $wpdb->update(
            $arplite_arprice_options,
            array(
                'table_options' => $new_opts
            ),
            array(
                'table_id' => $table_id
            )
        );
    }

}

if( version_compare($checkupdate, '2.6', '<')){
    require ARPLITE_PRICINGTABLE_VIEWS_DIR.'/upgrade_latest_data_v2.6.php';
}

if( version_compare($checkupdate, '3.3', '<')) {
    global $arpricelite_import_export,$arplite_pricingtable,$arpricelite_img_css_version;

    $arp_all_templates = $wpdb->get_results($wpdb->prepare("SELECT ID FROM `".$wpdb->prefix."arplite_arprice` WHERE `is_template` = %d",1));

    foreach( $arp_all_templates as $key => $template ){
        $table_id = $template->ID;
        $wpdb->delete(
            $wpdb->prefix.'arplite_arprice',
            array( 'ID' => $table_id ),
            array( '%d' )
        );

        $wpdb->delete(
            $wpdb->prefix.'arplite_arprice_options',
            array('table_id' => $table_id ),
            array('%d')
        );
    }

    include(ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_default_templates.php');

    $all_created_tables = $wpdb->get_results( $wpdb->prepare("SELECT * FROM `".$wpdb->prefix."arplite_arprice` WHERE `is_template` = %d AND `status` = %s",0,'published'));

    foreach( $all_created_tables as $k => $table ){
        $table_id = $table->ID;

        $general_options_updated = array();
        $general_options = maybe_unserialize($table->general_options);
        $general_options_updated = $general_options;
        $reference_template = $general_options['general_settings']['reference_template'];

        $ref_id = str_replace('arplitetemplate_', '', $reference_template);

        if( $ref_id >= 20 ){
            $ref_id = $ref_id - 3;
            $reference_template = 'arplitetemplate_'.$ref_id;
        }
        
        $final_updated_opts = maybe_serialize($general_options_updated);

        $wpdb->update(
            $wpdb->prefix.'arplite_arprice',
            array( 'general_options' => $final_updated_opts ),
            array( 'ID' => $table_id ),
            array( '%s' ),
            array( '%d' )
        );

        $tableopts = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."arplite_arprice_options` WHERE table_id = %d",$table_id));

        $table_opt_id = $tableopts->ID;
        $table_opts = maybe_unserialize($tableopts->table_options);

        $column_opts = $table_opts['columns'];
        $new_column_opts = array();

        foreach( $column_opts as $c => $columns ){
            if( isset( $columns['html_content'] ) ){
                $columns['html_content'] = $arpricelite_import_export->update_fa_font_class($columns['html_content']);    
            }
            if( isset( $columns['package_title'] ) ){
                $columns['package_title'] = $arpricelite_import_export->update_fa_font_class($columns['package_title']);
            }
            if( isset( $columns['arp_header_shortcode'] ) ){
                $columns['arp_header_shortcode'] = $arpricelite_import_export->update_fa_font_class($columns['arp_header_shortcode']);
            }
            if( isset( $columns['price_text'] ) ){
                $columns['price_text'] = $arpricelite_import_export->update_fa_font_class($columns['price_text']);
            }
            if( isset( $columns['column_description'] ) ){
                $columns['column_description'] = $arpricelite_import_export->update_fa_font_class($columns['column_description']);
            }
            if( isset( $columns['button_text'] ) ){
                $columns['button_text'] = $arpricelite_import_export->update_fa_font_class($columns['button_text']);
            }
            if( isset( $columns['footer_content'] ) ){
                $columns['footer_content'] = $arpricelite_import_export->update_fa_font_class($columns['footer_content']);
            }

            $column_opts[$c] = $columns;

            if( is_array( $columns['rows']) && count($columns['rows']) > 0 ){
                foreach( $columns['rows'] as $r => $row ){
                    if( isset( $row['row_description'] ) ){
                        $row['row_description'] = $arpricelite_import_export->update_fa_font_class($row['row_description']);
                    }
                    $column_opts[$c]['rows'][$r]['row_description'] = $row['row_description'];
                }
            }
        }

        $new_column_opts['columns'] = $column_opts;

        $final_updated_cols = maybe_serialize($new_column_opts);

        $wpdb->update(
            $wpdb->prefix.'arplite_arprice_options',
            array( 'table_options' => $final_updated_cols ),
            array( 'table_id' => $table_id, 'ID' => $table_opt_id ),
            array( '%s' ),
            array( '%d','%d')
        );

        
        WP_Filesystem();

        global $wp_filesystem;

        $css_file_name = 'arplitetemplate_'.$table_id.'.css';
        
        $ref_css_file_name = ARPLITE_PRICINGTABLE_DIR . '/css/templates/' . $reference_template . '_v' . $arpricelite_img_css_version . '.css';
        
        $ref_css_file_url = ARPLITE_PRICINGTABLE_URL . '/css/templates/' . $reference_template . '_v' . $arpricelite_img_css_version . '.css';
        $css_content_data = wp_remote_get( $ref_css_file_url, array(
                'sslverify' => false
            ) );
        $css_file_content = $css_content_data['body'];

        $css_new = preg_replace('/arplitetemplate_([\d]+)/', 'arplitetemplate_' . $table_id, $css_file_content);

        $css_new = str_replace('../../images', ARPLITE_PRICINGTABLE_IMAGES_URL, $css_new);

        $path = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/css/';

        $file_name = 'arplitetemplate_' . $table_id . '.css';

        $wp_filesystem->put_contents($path . $file_name, $css_new, 0777);
    }
}

if( version_compare( $checkupdate, '3.6', '<' )){
    global $arpricelite_import_export,$arplite_pricingtable,$arpricelite_img_css_version;

    $arp_all_templates = $wpdb->get_results($wpdb->prepare("SELECT ID FROM `".$wpdb->prefix."arplite_arprice` WHERE `is_template` = %d",1));

    foreach( $arp_all_templates as $key => $template ){
        $table_id = $template->ID;
        $wpdb->delete(
            $wpdb->prefix.'arplite_arprice',
            array( 'ID' => $table_id ),
            array( '%d' )
        );

        $wpdb->delete(
            $wpdb->prefix.'arplite_arprice_options',
            array('table_id' => $table_id ),
            array('%d')
        );
    }

    include(ARPLITE_PRICINGTABLE_CLASSES_DIR . '/class.arprice_default_templates.php');
}

$arplite_newdb_version = sanitize_text_field('3.6');

update_option('arpricelite_version',$arplite_newdb_version);

update_option('arpricelite_updated_date_' . $arplite_newdb_version, current_time( 'mysql' ) );