<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * ARPricelitelite Template Installer Data
 * 
 * @plugin ARPricelite
 * @since 1.0
 * 
 */
global $wpdb, $arplite_mainoptionsarr, $arplite_coloptionsarr, $arpricelite_form;

/**
 * ARPricelite Template 1
 * 
 * @since 1.0
 */
$values['name'] = 'ARPricelite Template 1';
$values['is_template'] = 1;
$values['ID'] = 1;
$values['template_name'] = 1;
$values['status'] = 'published';
$values['is_animated'] = 0;

$arp_pt_gen_options = array();

$arp_pt_template_settings = array();

$arp_pt_font_settings = array();

$arp_pt_general_settings = array();

$arp_header_font_settings = array();

$arp_price_font_settings = array();

$arp_price_text_font_settings = array();

$arp_content_font_settings = array();

$arp_button_font_settings = array();

$arp_pt_column_settings = array();

$arp_pt_button_settings = array();

$arp_pt_template_settings['template'] = 'arplitetemplate_1';
$arp_pt_template_settings['skin'] = 'multicolor';
$arp_pt_template_settings['template_type'] = 'normal';
$arp_pt_template_settings['features'] = array('column_description' => 'disable', 'custom_ribbon' => 'disable', 'button_position' => 'default', 'caption_style' => 'default', 'amount_style' => 'default', 'list_alignment' => 'default', 'ribbon_type' => 'default', 'column_description_style' => 'default', 'caption_title' => 'default', 'header_shortcode_type' => 'normal', 'header_shortcode_position' => 'default', 'tooltip_position' => 'top-left', 'tooltip_style' => 'default', 'second_btn' => false, 'is_animated' => 0);

$arp_pt_general_settings['column_order'] = '["main_column_0","main_column_1","main_column_2","main_column_3"]';
$arp_pt_general_settings['reference_template'] = 'arplitetemplate_1';
$arp_pt_general_settings['user_edited_columns'] = '';




$arp_pt_button_settings['button_shadow_color'] = '#ffffff';
$arp_pt_button_settings['button_radius'] = 0;

$arp_pt_column_settings['column_space'] = 0;
$arp_pt_column_settings['min_row_height'] = 40;
$arp_pt_column_settings['column_highlight_on_hover'] = 'hover_effect';
$arp_pt_column_settings['is_responsive'] = 1;
$arp_pt_column_settings['full_column_clickable'] = 0;
$arp_pt_column_settings['enable_hover_effect'] = 1;
$arp_pt_column_settings['hide_footer_global'] = 0;
$arp_pt_column_settings['hide_header_global'] = 0;
$arp_pt_column_settings['hide_price_global'] = 0;
$arp_pt_column_settings['hide_feature_global'] = 0;
$arp_pt_column_settings['hide_description_global'] = 0;
$arp_pt_column_settings['hide_header_shortcode_global'] = 0;
$arp_pt_column_settings['all_column_width'] = 200;
$arp_pt_column_settings['hide_caption_colunmn'] = 0;
$arp_pt_column_settings['column_opacity'] = $arplite_mainoptionsarr['general_options']['column_opacity'][0];
$arp_pt_column_settings['column_border_radius_top_left'] = 0;
$arp_pt_column_settings['column_border_radius_top_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['column_wrapper_width_txtbox'] = 800;

$arp_pt_column_settings['global_button_border_width'] = 0;
$arp_pt_column_settings['global_button_border_type'] = 'solid';
$arp_pt_column_settings['global_button_border_color'] = '#c9c9c9ff';
$arp_pt_column_settings['global_button_border_radius_top_left'] = 4;
$arp_pt_column_settings['global_button_border_radius_top_right'] = 4;
$arp_pt_column_settings['global_button_border_radius_bottom_left'] = 4;
$arp_pt_column_settings['global_button_border_radius_bottom_right'] = 4;
$arp_pt_column_settings['arp_global_button_type'] = 'shadow';

$arp_pt_column_settings['arp_row_border_size'] = '0';
$arp_pt_column_settings['arp_row_border_type'] = 'solid';
$arp_pt_column_settings['arp_row_border_color'] = '#c9c9c9ff';

$arp_pt_column_settings['arp_caption_row_border_size'] = '0';
$arp_pt_column_settings['arp_caption_row_border_style'] = 'solid';
$arp_pt_column_settings['arp_caption_row_border_color'] = '#c9c9c9ff';

$arp_pt_column_settings['arp_column_border_size'] = '1';
$arp_pt_column_settings['arp_column_border_type'] = 'solid';
$arp_pt_column_settings['arp_column_border_color'] = '#cececeff';

$arp_pt_column_settings['arp_column_border_left'] = 0;
$arp_pt_column_settings['arp_column_border_right'] = 0;
$arp_pt_column_settings['arp_column_border_top'] = 1;
$arp_pt_column_settings['arp_column_border_bottom'] = 1;

//caption border
$arp_pt_column_settings['arp_caption_border_size'] = '1';
$arp_pt_column_settings['arp_caption_border_style'] = 'solid';
$arp_pt_column_settings['arp_caption_border_color'] = '#cececeff';

$arp_pt_column_settings['arp_caption_border_left'] = 1;
$arp_pt_column_settings['arp_caption_border_right'] = 0;
$arp_pt_column_settings['arp_caption_border_top'] = 1;
$arp_pt_column_settings['arp_caption_border_bottom'] = 1;
$arp_pt_column_settings['display_col_mobile'] = 1;
$arp_pt_column_settings['display_col_tablet'] = 3;

$arp_pt_column_settings['column_box_shadow_effect'] = 'shadow_style_none';
$arp_pt_column_settings['hide_blank_rows'] = 'no';


$arp_pt_column_settings['header_font_family_global'] = 'Open Sans';
$arp_pt_column_settings['header_font_size_global'] = 28;
$arp_pt_column_settings['arp_header_text_alignment'] = 'center';
$arp_pt_column_settings['arp_header_text_bold_global'] = '';
$arp_pt_column_settings['arp_header_text_italic_global'] = '';
$arp_pt_column_settings['arp_header_text_decoration_global'] = '';
$arp_pt_column_settings['price_font_family_global'] = 'Open Sans';
$arp_pt_column_settings['price_font_size_global'] = 18;
$arp_pt_column_settings['arp_price_text_alignment'] = 'center';
$arp_pt_column_settings['arp_price_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_price_text_italic_global'] = '';
$arp_pt_column_settings['arp_price_text_decoration_global'] = '';
$arp_pt_column_settings['body_font_family_global'] = 'Arial';
$arp_pt_column_settings['body_font_size_global'] = 16;
$arp_pt_column_settings['arp_body_text_alignment'] = 'center';
$arp_pt_column_settings['arp_body_text_bold_global'] = '';
$arp_pt_column_settings['arp_body_text_italic_global'] = '';
$arp_pt_column_settings['arp_body_text_decoration_global'] = '';
$arp_pt_column_settings['footer_font_family_global'] = 'Open Sans Bold';
$arp_pt_column_settings['footer_font_size_global'] = 12;
$arp_pt_column_settings['arp_footer_text_alignment'] = 'center';
$arp_pt_column_settings['arp_footer_text_bold_global'] = '';
$arp_pt_column_settings['arp_footer_text_italic_global'] = '';
$arp_pt_column_settings['arp_footer_text_decoration_global'] = '';
$arp_pt_column_settings['button_font_family_global'] = 'Open Sans Bold';
$arp_pt_column_settings['button_font_size_global'] = 17;
$arp_pt_column_settings['arp_button_text_alignment'] = 'center';
$arp_pt_column_settings['arp_button_text_bold_global'] = '';
$arp_pt_column_settings['arp_button_text_italic_global'] = '';
$arp_pt_column_settings['arp_button_text_decoration_global'] = '';
$arp_pt_column_settings['description_font_family_global'] = '';
$arp_pt_column_settings['description_font_size_global'] = '';
$arp_pt_column_settings['arp_description_text_alignment'] = '';
$arp_pt_column_settings['arp_description_text_bold_global'] = '';
$arp_pt_column_settings['arp_description_text_italic_global'] = '';
$arp_pt_column_settings['arp_description_text_decoration_global'] = '';

$arp_pt_font_settings['header_fonts'] = $arp_header_font_settings;
$arp_pt_font_settings['price_fonts'] = $arp_price_font_settings;
$arp_pt_font_settings['price_text_fonts'] = $arp_price_text_font_settings;
$arp_pt_font_settings['content_fonts'] = $arp_content_font_settings;
$arp_pt_font_settings['button_fonts'] = $arp_button_font_settings;

$arp_pt_gen_options = array('template_setting' => $arp_pt_template_settings, 'font_settings' => $arp_pt_font_settings, 'column_settings' => $arp_pt_column_settings, 'general_settings' => $arp_pt_general_settings, 'button_settings' => $arp_pt_button_settings);

$arp_pt_custom_skin_array = array();

$arp_pt_custom_skin_array['arp_header_bg_custom_color'] = "#85d538ff";
$arp_pt_custom_skin_array['arp_column_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = "#70b828ff";
$arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = "#ffffffff";
$arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = "#e9e9e9ff";
$arp_pt_custom_skin_array['arp_footer_content_bg_color'] = "#e3e3e3ff";
$arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#85d538ff";
$arp_pt_custom_skin_array['arp_column_bg_hover_color'] = null;
$arp_pt_custom_skin_array['arp_button_bg_hover_color'] = '#5d9527ff';
$arp_pt_custom_skin_array['arp_header_bg_hover_color'] = '#85d538ff';
$arp_pt_custom_skin_array['arp_price_bg_hover_color'] = '#70b828ff';
$arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = '#ffffffff';
$arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = '#e9e9e9ff';
$arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = '#e3e3e3ff';
$arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffffff';
$arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#ffffffff';
$arp_pt_custom_skin_array['arp_price_font_custom_color'] = '#ffffffff';
$arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = '#ffffffff';
$arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_desc_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#364762ff';
$arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#364762ff';
$arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#364762ff';
$arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#364762ff';
$arp_pt_custom_skin_array['arp_footer_font_custom_color'] = '#364762ff';
$arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = '#364762ff';
$arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#ffffffff';
$arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#ffffffff';

$arp_pt_gen_options['custom_skin_colors'] = $arp_pt_custom_skin_array;

$values['options'] = maybe_serialize($arp_pt_gen_options);

$table_id = $arpricelite_form->create($values);

$pt_columns = array();

$column = array();

$column['column_0']['package_title'] = '';
$column['column_0']['column_description'] = '';
$column['column_0']['custom_ribbon_txt'] = '';
$column['column_0']['column_width'] = '';
$column['column_0']['column_align'] = 'left';

$column['column_0']['header_font_align'] = 'left';
$column['column_0']['header_background_color'] = '#ffffff';
$column['column_0']['header_hover_background_color'] = '#ffffff';
$column['column_0']['header_font_family'] = 'Open Sans';
$column['column_0']['header_font_size'] = 26;
$column['column_0']['header_font_color'] = '#333333';
$column['column_0']['header_hover_font_color'] = '#333333';
$column['column_0']['header_style_bold'] = '';
$column['column_0']['header_style_italic'] = '';
$column['column_0']['header_style_decoration'] = '';


$column['column_0']['price_background_color'] = '';
$column['column_0']['price_hover_background_color'] = '';
$column['column_0']['price_font_family'] = 'Open Sans';
$column['column_0']['price_font_size'] = 18;

$column['column_0']['price_font_color'] = '#ffffff';
$column['column_0']['price_hover_font_color'] = '#ffffff';
$column['column_0']['price_label_style_bold'] = 'bold';
$column['column_0']['price_label_style_italic'] = '';
$column['column_0']['price_label_style_decoration'] = '';


/* Price Text Font Settings */
$column['column_0']['price_text_font_family'] = 'Open Sans';
$column['column_0']['price_text_font_size'] = 18;

$column['column_0']['price_text_font_color'] = '#ffffff';
$column['column_0']['price_text_hover_font_color'] = '#ffffff';
$column['column_0']['price_text_style_bold'] = '';
$column['column_0']['price_text_style_italic'] = '';
$column['column_0']['price_text_style_decoration'] = '';
/* Price Text Font Settings */

/* Column Description Font Settings */
$column['column_0']['column_description_font_family'] = '';
$column['column_0']['column_description_font_size'] = '';

$column['column_0']['column_description_font_color'] = '';
$column['column_0']['column_description_hover_font_color'] = '';
$column['column_0']['column_description_style_bold'] = '';
$column['column_0']['column_description_style_italic'] = '';
$column['column_0']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_0']['content_font_family'] = 'Arial';
$column['column_0']['content_font_size'] = 16;

$column['column_0']['content_font_color'] = '#364762';
$column['column_0']['content_even_font_color'] = '#364762';
$column['column_0']['content_hover_font_color'] = '#364762';
$column['column_0']['content_even_hover_font_color'] = '#364762';
$column['column_0']['content_odd_color'] = '#f6f4f5';
$column['column_0']['content_odd_hover_color'] = '#f6f4f5';
$column['column_0']['content_even_color'] = '#f1f1f1';
$column['column_0']['content_even_hover_color'] = '#f1f1f1';

$column['column_0']['body_li_style_bold'] = '';
$column['column_0']['body_li_style_italic'] = '';
$column['column_0']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Button Font Settings */
$column['column_0']['button_background_color'] = '';
$column['column_0']['button_hover_background_color'] = '';
$column['column_0']['button_font_family'] = 'Open Sans Bold';
$column['column_0']['button_font_size'] = 17;

$column['column_0']['button_font_color'] = '#ffffff';
$column['column_0']['button_hover_font_color'] = '#ffffff';
$column['column_0']['button_style_bold'] = '';
$column['column_0']['button_style_italic'] = '';
$column['column_0']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_0']['is_caption'] = 1;
$column['column_0']['column_highlight'] = '';
$column['column_0']['html_content'] = "Hosting Plans";
$column['column_0']['body_text_alignment'] = 'left';
$column['column_0']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_0']['row_description'] = 'Data Storage';
$column['column_0']['rows']['row_0']['row_label'] = '';
$column['column_0']['rows']['row_0']['row_tooltip'] = '';
$column['column_0']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_1']['row_description'] = 'MySQL Databases';
$column['column_0']['rows']['row_1']['row_tooltip'] = '';
$column['column_0']['rows']['row_1']['row_label'] = '';
$column['column_0']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_2']['row_description'] = 'Daily Backup';
$column['column_0']['rows']['row_2']['row_tooltip'] = '';
$column['column_0']['rows']['row_2']['row_label'] = '';
$column['column_0']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_3']['row_description'] = 'Free Domains';
$column['column_0']['rows']['row_3']['row_tooltip'] = '';
$column['column_0']['rows']['row_3']['row_label'] = '';
$column['column_0']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_4']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_4']['row_description'] = 'Online Support';
$column['column_0']['rows']['row_4']['row_tooltip'] = '';
$column['column_0']['rows']['row_4']['row_label'] = '';
$column['column_0']['rows']['row_4']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_4']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_4']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_4']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_4']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_4']['row_caption_style_decoration'] = '';
$column['column_0']['button_size'] = '';
$column['column_0']['button_height'] = '';
$column['column_0']['button_type'] = '';
$column['column_0']['button_text'] = '';
$column['column_0']['button_url'] = '';
$column['column_0']['is_new_window'] = 0;

$column['column_0']['footer_content'] = '';
$column['column_0']['footer_content_position'] = 0;
$column['column_0']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_0']['footer_level_options_font_size'] = 12;
$column['column_0']['footer_level_options_font_color'] = '#364762';
$column['column_0']['footer_level_options_hover_font_color'] = '#364762';
$column['column_0']['footer_level_options_font_style_bold'] = '';
$column['column_0']['footer_level_options_font_style_italic'] = '';
$column['column_0']['footer_level_options_font_style_decoration'] = '';
$column['column_0']['footer_background_color'] = '#e3e3e3';
$column['column_0']['footer_hover_background_color'] = '#e3e3e3';
$column['column_0']['footer_text_align'] = 'center';

$column['column_1']['package_title'] = 'Bronze';
$column['column_1']['column_description'] = '';
$column['column_1']['custom_ribbon_txt'] = '';
$column['column_1']['column_width'] = '';
$column['column_1']['column_align'] = 'left';

/* Header Font Settings */
$column['column_1']['header_background_color'] = '#6dae2e';
$column['column_1']['header_hover_background_color'] = '#6dae2e';
$column['column_1']['header_font_family'] = 'Open Sans';
$column['column_1']['header_font_size'] = 28;

$column['column_1']['header_font_color'] = '#ffffff';
$column['column_1']['header_hover_font_color'] = '#ffffff';
$column['column_1']['header_style_bold'] = '';
$column['column_1']['header_style_italic'] = '';
$column['column_1']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_1']['price_background_color'] = '#528a1b';
$column['column_1']['price_hover_background_color'] = '#528a1b';
$column['column_1']['price_font_family'] = 'Open Sans';
$column['column_1']['price_font_size'] = 18;
$column['column_1']['price_font_color'] = '#ffffff';
$column['column_1']['price_hover_font_color'] = '#ffffff';
$column['column_1']['price_label_style_bold'] = 'bold';
$column['column_1']['price_label_style_italic'] = '';
$column['column_1']['price_label_style_decoration'] = '';


/* Price Text Font Settings */
$column['column_1']['price_text_font_family'] = 'Open Sans';
$column['column_1']['price_text_font_size'] = 18;

$column['column_1']['price_text_font_color'] = '#ffffff';
$column['column_1']['price_text_hover_font_color'] = '#ffffff';
$column['column_1']['price_text_style_bold'] = 'bold';
$column['column_1']['price_text_style_italic'] = '';
$column['column_1']['price_text_style_decoration'] = '';
/* Price Text Font Settings */

/* Column Description Font Settings */
$column['column_1']['column_description_font_family'] = '';
$column['column_1']['column_description_font_size'] = '';

$column['column_1']['column_description_font_color'] = '';
$column['column_1']['column_description_hover_font_color'] = '';
$column['column_1']['column_description_style_bold'] = '';
$column['column_1']['column_description_style_italic'] = '';
$column['column_1']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_1']['content_font_family'] = 'Arial';
$column['column_1']['content_font_size'] = 16;
$column['column_1']['content_font_color'] = '#364762';
$column['column_1']['content_even_font_color'] = '#364762';
$column['column_1']['content_hover_font_color'] = '#364762';
$column['column_1']['content_even_hover_font_color'] = '#364762';
$column['column_1']['content_odd_color'] = '#ffffff';
$column['column_1']['content_odd_hover_color'] = '#ffffff';
$column['column_1']['content_even_color'] = '#f1f1f1';
$column['column_1']['content_even_hover_color'] = '#f1f1f1';
$column['column_1']['body_li_style_bold'] = '';
$column['column_1']['body_li_style_italic'] = '';
$column['column_1']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Button Font Settings */
$column['column_1']['button_background_color'] = '#6dae2e';
$column['column_1']['button_hover_background_color'] = '#4c7a20';
$column['column_1']['button_font_family'] = 'Open Sans Bold';
$column['column_1']['button_font_size'] = 17;
$column['column_1']['button_font_color'] = '#ffffff';
$column['column_1']['button_hover_font_color'] = '#ffffff';
$column['column_1']['button_style_bold'] = '';
$column['column_1']['button_style_italic'] = '';
$column['column_1']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_1']['is_caption'] = 0;
$column['column_1']['column_highlight'] = '';
$column['column_1']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>20</span><span class='arp_price_duration'> / month</span>";
$column['column_1']['price_label'] = '';
$column['column_1']['arp_header_shortcode'] = '';
$column['column_1']['body_text_alignment'] = 'center';
$column['column_1']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_0']['row_description'] = '20GB';
$column['column_1']['rows']['row_0']['row_label'] = '';
$column['column_1']['rows']['row_0']['row_tooltip'] = '';
$column['column_1']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_1']['row_description'] = '15 Databases';
$column['column_1']['rows']['row_1']['row_label'] = '';
$column['column_1']['rows']['row_1']['row_tooltip'] = '';
$column['column_1']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_2']['row_description'] = 'No';
$column['column_1']['rows']['row_2']['row_label'] = '';
$column['column_1']['rows']['row_2']['row_tooltip'] = '';
$column['column_1']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_3']['row_description'] = '2 Domains';
$column['column_1']['rows']['row_3']['row_label'] = '';
$column['column_1']['rows']['row_3']['row_tooltip'] = '';
$column['column_1']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_4']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_4']['row_description'] = 'No';
$column['column_1']['rows']['row_4']['row_label'] = '';
$column['column_1']['rows']['row_4']['row_tooltip'] = '';
$column['column_1']['rows']['row_4']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_4']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_4']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_4']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_4']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_4']['row_caption_style_decoration'] = '';
$column['column_1']['button_size'] = '140';
$column['column_1']['button_height'] = '45';
$column['column_1']['button_type'] = 'button';
$column['column_1']['button_text'] = 'Purchase';
$column['column_1']['button_url'] = '#';
$column['column_1']['button_s_size'] = '';
$column['column_1']['button_s_type'] = '';
$column['column_1']['button_s_text'] = '';
$column['column_1']['button_s_url'] = '';
$column['column_1']['s_is_new_window'] = '';
$column['column_1']['is_new_window'] = 0;

$column['column_1']['footer_content'] = '';
$column['column_1']['footer_content_position'] = 0;
$column['column_1']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_1']['footer_level_options_font_size'] = 12;
$column['column_1']['footer_level_options_font_color'] = '#364762';
$column['column_1']['footer_level_options_hover_font_color'] = '#364762';
$column['column_1']['footer_level_options_font_style_bold'] = '';
$column['column_1']['footer_level_options_font_style_italic'] = '';
$column['column_1']['footer_level_options_font_style_decoration'] = '';
$column['column_1']['footer_background_color'] = '#e3e3e3';
$column['column_1']['footer_hover_background_color'] = '#e3e3e3';

$column['column_1']['is_post_variables'] = 0;
$column['column_1']['post_variables_content'] = '';

$column['column_2']['package_title'] = 'Silver';
$column['column_2']['column_description'] = '';
$column['column_2']['custom_ribbon_txt'] = '';
$column['column_2']['column_width'] = '';
$column['column_2']['column_align'] = 'left';

/* Header Font Settings */
$column['column_2']['header_background_color'] = '#fbb400';
$column['column_2']['header_hover_background_color'] = '#fbb400';
$column['column_2']['header_font_family'] = 'Open Sans';
$column['column_2']['header_font_size'] = 28;
$column['column_2']['header_font_color'] = '#ffffff';
$column['column_2']['header_hover_font_color'] = '#ffffff';
$column['column_2']['header_style_bold'] = '';
$column['column_2']['header_style_italic'] = '';
$column['column_2']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_2']['price_background_color'] = '#c28a01';
$column['column_2']['price_hover_background_color'] = '#c28a01';
$column['column_2']['price_font_family'] = 'Open Sans';
$column['column_2']['price_font_size'] = 18;
$column['column_2']['price_font_color'] = '#ffffff';
$column['column_2']['price_hover_font_color'] = '#ffffff';
$column['column_2']['price_label_style_bold'] = 'bold';
$column['column_2']['price_label_style_italic'] = '';
$column['column_2']['price_label_style_decoration'] = '';


/* Price Text Font Settings */
$column['column_2']['price_text_font_family'] = 'Open Sans';
$column['column_2']['price_text_font_size'] = 18;
$column['column_2']['price_text_font_color'] = '#ffffff';
$column['column_2']['price_text_hover_font_color'] = '#ffffff';
$column['column_2']['price_text_style_bold'] = 'bold';
$column['column_2']['price_text_style_italic'] = '';
$column['column_2']['price_text_style_decoration'] = '';
/* Price Text Font Settings */

/* Column Description Font Settings */
$column['column_2']['column_description_font_family'] = '';
$column['column_2']['column_description_font_size'] = '';
$column['column_2']['column_description_font_color'] = '';
$column['column_2']['column_description_hover_font_color'] = '';
$column['column_2']['column_description_style_bold'] = '';
$column['column_2']['column_description_style_italic'] = '';
$column['column_2']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_2']['content_font_family'] = 'Arial';
$column['column_2']['content_font_size'] = 16;
$column['column_2']['content_font_color'] = '#364762';
$column['column_2']['content_even_font_color'] = '#364762';
$column['column_2']['content_hover_font_color'] = '#364762';
$column['column_2']['content_even_hover_font_color'] = '#364762';
$column['column_2']['content_odd_color'] = '#ffffff';
$column['column_2']['content_odd_hover_color'] = '#ffffff';
$column['column_2']['content_even_color'] = '#f9f9f9';
$column['column_2']['content_even_hover_color'] = '#f9f9f9';
$column['column_2']['body_li_style_bold'] = '';
$column['column_2']['body_li_style_italic'] = '';
$column['column_2']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Button Font Settings */
$column['column_2']['button_background_color'] = '#fbb400';
$column['column_2']['button_hover_background_color'] = '#b07e00';
$column['column_2']['button_font_family'] = 'Open Sans Bold';
$column['column_2']['button_font_size'] = 17;
$column['column_2']['button_font_color'] = '#ffffff';
$column['column_2']['button_hover_font_color'] = '#ffffff';
$column['column_2']['button_style_bold'] = '';
$column['column_2']['button_style_italic'] = '';
$column['column_2']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_2']['is_caption'] = 0;
$column['column_2']['column_highlight'] = '';
$column['column_2']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>50</span><span class='arp_price_duration'> / month</span>";
$column['column_2']['price_label'] = "";
$column['column_2']['arp_header_shortcode'] = '';
$column['column_2']['body_text_alignment'] = 'center';
$column['column_2']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_0']['row_description'] = '80GB';
$column['column_2']['rows']['row_0']['row_tooltip'] = '';
$column['column_2']['rows']['row_0']['row_label'] = '';
$column['column_2']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_1']['row_description'] = '100 Databases';
$column['column_2']['rows']['row_1']['row_tooltip'] = '';
$column['column_2']['rows']['row_1']['row_label'] = '';
$column['column_2']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_2']['row_description'] = 'No';
$column['column_2']['rows']['row_2']['row_tooltip'] = '';
$column['column_2']['rows']['row_2']['row_label'] = '';
$column['column_2']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_3']['row_description'] = '5 Domains';
$column['column_2']['rows']['row_3']['row_tooltip'] = '';
$column['column_2']['rows']['row_3']['row_label'] = '';
$column['column_2']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_4']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_4']['row_description'] = 'No';
$column['column_2']['rows']['row_4']['row_tooltip'] = '';
$column['column_2']['rows']['row_4']['row_label'] = '';
$column['column_2']['rows']['row_4']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_4']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_4']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_4']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_4']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_4']['row_caption_style_decoration'] = '';
$column['column_2']['button_size'] = '140';
$column['column_2']['button_height'] = '45';
$column['column_2']['button_type'] = 'button';
$column['column_2']['button_text'] = 'Purchase';
$column['column_2']['button_url'] = '#';
$column['column_2']['button_s_size'] = '';
$column['column_2']['button_s_type'] = '';
$column['column_2']['button_s_text'] = '';
$column['column_2']['button_s_url'] = '';
$column['column_2']['s_is_new_window'] = '';
$column['column_2']['is_new_window'] = 0;

$column['column_2']['footer_content'] = '';
$column['column_2']['footer_content_position'] = 0;
$column['column_2']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_2']['footer_level_options_font_size'] = 12;
$column['column_2']['footer_level_options_font_color'] = '#364762';
$column['column_2']['footer_level_options_hover_font_color'] = '#364762';
$column['column_2']['footer_level_options_font_style_bold'] = '';
$column['column_2']['footer_level_options_font_style_italic'] = '';
$column['column_2']['footer_level_options_font_style_decoration'] = '';
$column['column_2']['footer_background_color'] = '#e3e3e3';
$column['column_2']['footer_hover_background_color'] = '#e3e3e3';
$column['column_2']['is_post_variables'] = 0;
$column['column_2']['post_variables_content'] = '';

$column['column_3']['package_title'] = 'Gold';
$column['column_3']['column_description'] = '';
$column['column_3']['custom_ribbon_txt'] = '';
$column['column_3']['column_width'] = '';
$column['column_3']['column_align'] = 'left';

/* Header Font Settings */
$column['column_3']['header_background_color'] = '#ea6d00';
$column['column_3']['header_hover_background_color'] = '#ea6d00';
$column['column_3']['header_font_family'] = 'Open Sans';
$column['column_3']['header_font_size'] = 28;
$column['column_3']['header_font_color'] = '#ffffff';
$column['column_3']['header_hover_font_color'] = '#ffffff';
$column['column_3']['header_style_bold'] = '';
$column['column_3']['header_style_italic'] = '';
$column['column_3']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_3']['price_background_color'] = '#b44404';
$column['column_3']['price_hover_background_color'] = '#b44404';
$column['column_3']['price_font_family'] = 'Open Sans';
$column['column_3']['price_font_size'] = 18;
$column['column_3']['price_font_color'] = '#ffffff';
$column['column_3']['price_hover_font_color'] = '#ffffff';
$column['column_3']['price_label_style_bold'] = 'bold';
$column['column_3']['price_label_style_italic'] = '';
$column['column_3']['price_label_style_decoration'] = '';


/* Price Text Font Settings */
$column['column_3']['price_text_font_family'] = 'Open Sans';
$column['column_3']['price_text_font_size'] = 18;
$column['column_3']['price_text_font_color'] = '#ffffff';
$column['column_3']['price_text_hover_font_color'] = '#ffffff';
$column['column_3']['price_text_style_bold'] = 'bold';
$column['column_3']['price_text_style_italic'] = '';
$column['column_3']['price_text_style_decoration'] = '';
/* Price Text Font Settings */

/* Column Description Font Settings */
$column['column_3']['column_description_font_family'] = '';
$column['column_3']['column_description_font_size'] = '';
$column['column_3']['column_description_font_color'] = '';
$column['column_3']['column_description_hover_font_color'] = '';
$column['column_3']['column_description_style_bold'] = '';
$column['column_3']['column_description_style_italic'] = '';
$column['column_3']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_3']['content_font_family'] = 'Arial';
$column['column_3']['content_font_size'] = 16;
$column['column_3']['content_font_color'] = '#364762';
$column['column_3']['content_even_font_color'] = '#364762';
$column['column_3']['content_hover_font_color'] = '#364762';
$column['column_3']['content_even_hover_font_color'] = '#364762';
$column['column_3']['content_odd_color'] = '#ffffff';
$column['column_3']['content_odd_hover_color'] = '#ffffff';
$column['column_3']['content_even_color'] = '#f1f1f1';
$column['column_3']['content_even_hover_color'] = '#f1f1f1';
$column['column_3']['body_li_style_bold'] = '';
$column['column_3']['body_li_style_italic'] = '';
$column['column_3']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Button Font Settings */
$column['column_3']['button_background_color'] = '#ea6d00';
$column['column_3']['button_hover_background_color'] = '#a44c00';
$column['column_3']['button_font_family'] = 'Open Sans Bold';
$column['column_3']['button_font_size'] = 17;
$column['column_3']['button_font_color'] = '#ffffff';
$column['column_3']['button_hover_font_color'] = '#ffffff';
$column['column_3']['button_style_bold'] = '';
$column['column_3']['button_style_italic'] = '';
$column['column_3']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_3']['is_caption'] = 0;
$column['column_3']['column_highlight'] = '';
$column['column_3']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>60</span><span class='arp_price_duration'> / month</span>";
$column['column_3']['price_label'] = '';
$column['column_3']['arp_header_shortcode'] = '';
$column['column_3']['body_text_alignment'] = 'center';
$column['column_3']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_0']['row_description'] = '150GB';
$column['column_3']['rows']['row_0']['row_tooltip'] = '';
$column['column_3']['rows']['row_0']['row_label'] = '';
$column['column_3']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_1']['row_description'] = '150 Databases';
$column['column_3']['rows']['row_1']['row_tooltip'] = '';
$column['column_3']['rows']['row_1']['row_label'] = '';
$column['column_3']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_2']['row_description'] = 'Yes';
$column['column_3']['rows']['row_2']['row_tooltip'] = '';
$column['column_3']['rows']['row_2']['row_label'] = '';
$column['column_3']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_3']['row_description'] = '10 Domains';
$column['column_3']['rows']['row_3']['row_tooltip'] = '';
$column['column_3']['rows']['row_3']['row_label'] = '';
$column['column_3']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_4']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_4']['row_description'] = 'Yes';
$column['column_3']['rows']['row_4']['row_tooltip'] = '';
$column['column_3']['rows']['row_4']['row_label'] = '';
$column['column_3']['rows']['row_4']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_4']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_4']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_4']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_4']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_4']['row_caption_style_decoration'] = '';
$column['column_3']['button_size'] = '140';
$column['column_3']['button_height'] = '45';
$column['column_3']['button_type'] = 'button';
$column['column_3']['button_text'] = 'Purchase';
$column['column_3']['button_url'] = '#';
$column['column_3']['button_s_size'] = '';
$column['column_3']['button_s_type'] = '';
$column['column_3']['button_s_text'] = '';
$column['column_3']['button_s_url'] = '';
$column['column_3']['is_new_window'] = 0;
$column['column_3']['s_is_new_window'] = '';


$column['column_3']['footer_content'] = '';
$column['column_3']['footer_content_position'] = 0;
$column['column_3']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_3']['footer_level_options_font_size'] = 12;
$column['column_3']['footer_level_options_font_color'] = '#364762';
$column['column_3']['footer_level_options_hover_font_color'] = '#364762';
$column['column_3']['footer_level_options_font_style_bold'] = '';
$column['column_3']['footer_level_options_font_style_italic'] = '';
$column['column_3']['footer_level_options_font_style_decoration'] = '';
$column['column_3']['footer_background_color'] = '#e3e3e3';
$column['column_3']['footer_hover_background_color'] = '#e3e3e3';
$column['column_3']['is_post_variables'] = 0;
$column['column_3']['post_variables_content'] = '';


$pt_columns = array('columns' => $column);

$opts = maybe_serialize($pt_columns);

$arpricelite_form->option_create($table_id, $opts);

unset($values);


/**
 * ARPricelite Template 8
 * 
 * @since 1.0
 */
$values['name'] = 'ARPricelite Template 8';
$values['is_template'] = 1;
$values['template_name'] = 8;
$values['ID'] = 8;
$values['status'] = 'published';
$values['is_animated'] = 0;

$arp_pt_gen_options = array();

$arp_pt_template_settings = array();

$arp_pt_font_settings = array();

$arp_pt_general_settings = array();

$arp_header_font_settings = array();
$arp_price_font_settings = array();
$arp_content_font_settings = array();
$arp_button_font_settings = array();

$arp_pt_column_settings = array();

$arp_pt_button_settings = array();

$arp_pt_template_settings['template'] = 'arplitetemplate_8';
$arp_pt_template_settings['skin'] = 'multicolor';
$arp_pt_template_settings['template_type'] = 'normal';
$arp_pt_template_settings['features'] = array('column_description' => 'disable', 'custom_ribbon' => 'disable', 'button_position' => 'position_2', 'caption_style' => 'default', 'amount_style' => 'style_2', 'list_alignment' => 'center', 'ribbon_type' => 'default', 'column_description_style' => 'default', 'caption_title' => 'default', 'header_shortcode_type' => 'rounded_corner', 'header_shortcode_position' => 'position_1', 'tooltip_position' => 'top', 'tooltip_style' => 'style_2', 'second_btn' => false, 'is_animated' => 0);

$arp_pt_general_settings['column_order'] = '["main_column_0","main_column_1","main_column_2","main_column_3"]';
$arp_pt_general_settings['reference_template'] = 'arplitetemplate_8';
$arp_pt_general_settings['user_edited_columns'] = '';



$arp_pt_button_settings['button_shadow_color'] = '#ffffff';
$arp_pt_button_settings['button_radius'] = 0;

$arp_pt_column_settings['column_space'] = 0;
$arp_pt_column_settings['min_row_height'] = 60;
$arp_pt_column_settings['column_highlight_on_hover'] = 'shadow_effect';
$arp_pt_column_settings['is_responsive'] = 1;
$arp_pt_column_settings['full_column_clickable'] = 0;
$arp_pt_column_settings['enable_hover_effect'] = 1;
$arp_pt_column_settings['hide_footer_global'] = 0;
$arp_pt_column_settings['hide_header_global'] = 0;
$arp_pt_column_settings['hide_price_global'] = 0;
$arp_pt_column_settings['hide_feature_global'] = 0;
$arp_pt_column_settings['hide_description_global'] = 0;
$arp_pt_column_settings['hide_header_shortcode_global'] = 0;
$arp_pt_column_settings['all_column_width'] = 250;
$arp_pt_column_settings['column_opacity'] = $arplite_mainoptionsarr['general_options']['column_opacity'][0];
$arp_pt_column_settings['column_border_radius_top_left'] = 0;
$arp_pt_column_settings['column_border_radius_top_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['column_wrapper_width_txtbox'] = 1000;

$arp_pt_column_settings['global_button_border_width'] = 0;
$arp_pt_column_settings['global_button_border_type'] = 'solid';
$arp_pt_column_settings['global_button_border_color'] = '#c9c9c9ff';
$arp_pt_column_settings['global_button_border_radius_top_left'] = 20;
$arp_pt_column_settings['global_button_border_radius_top_right'] = 20;
$arp_pt_column_settings['global_button_border_radius_bottom_left'] = 20;
$arp_pt_column_settings['global_button_border_radius_bottom_right'] = 20;
$arp_pt_column_settings['arp_global_button_type'] = 'shadow';

$arp_pt_column_settings['arp_row_border_size'] = '1';
$arp_pt_column_settings['arp_row_border_type'] = 'solid';
$arp_pt_column_settings['arp_row_border_color'] = '#d4d4d4ff';

$arp_pt_column_settings['arp_column_border_size'] = '1';
$arp_pt_column_settings['arp_column_border_type'] = 'solid';
$arp_pt_column_settings['arp_column_border_color'] = '#dfdbdcff';


$arp_pt_column_settings['arp_column_border_left'] = 1;
$arp_pt_column_settings['arp_column_border_right'] = 1;
$arp_pt_column_settings['arp_column_border_top'] = 1;
$arp_pt_column_settings['arp_column_border_bottom'] = 1;

$arp_pt_column_settings['display_col_mobile'] = 1;
$arp_pt_column_settings['display_col_tablet'] = 3;

$arp_pt_column_settings['column_box_shadow_effect'] = 'shadow_style_none';
$arp_pt_column_settings['hide_blank_rows'] = 'no';
$arp_pt_column_settings['header_font_family_global'] = 'Open Sans Semibold';
$arp_pt_column_settings['header_font_size_global'] = 22;
$arp_pt_column_settings['arp_header_text_alignment'] = 'center';
$arp_pt_column_settings['arp_header_text_bold_global'] = '';
$arp_pt_column_settings['arp_header_text_italic_global'] = '';
$arp_pt_column_settings['arp_header_text_decoration_global'] = '';
$arp_pt_column_settings['price_font_family_global'] = 'Arial';
$arp_pt_column_settings['price_font_size_global'] = 40;
$arp_pt_column_settings['arp_price_text_alignment'] = 'center';
$arp_pt_column_settings['arp_price_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_price_text_italic_global'] = '';
$arp_pt_column_settings['arp_price_text_decoration_global'] = '';
$arp_pt_column_settings['body_font_family_global'] = 'Open Sans';
$arp_pt_column_settings['body_font_size_global'] = 14;
$arp_pt_column_settings['arp_body_text_alignment'] = 'center';
$arp_pt_column_settings['arp_body_text_bold_global'] = '';
$arp_pt_column_settings['arp_body_text_italic_global'] = '';
$arp_pt_column_settings['arp_body_text_decoration_global'] = '';
$arp_pt_column_settings['footer_font_family_global'] = '';
$arp_pt_column_settings['footer_font_size_global'] = '';
$arp_pt_column_settings['arp_footer_text_alignment'] = '';
$arp_pt_column_settings['arp_footer_text_bold_global'] = '';
$arp_pt_column_settings['arp_footer_text_italic_global'] = '';
$arp_pt_column_settings['arp_footer_text_decoration_global'] = '';
$arp_pt_column_settings['button_font_family_global'] = 'Open Sans Bold';
$arp_pt_column_settings['button_font_size_global'] = 18;
$arp_pt_column_settings['arp_button_text_alignment'] = 'center';
$arp_pt_column_settings['arp_button_text_bold_global'] = '';
$arp_pt_column_settings['arp_button_text_italic_global'] = '';
$arp_pt_column_settings['arp_button_text_decoration_global'] = '';
$arp_pt_column_settings['description_font_family_global'] = '';
$arp_pt_column_settings['description_font_size_global'] = '';
$arp_pt_column_settings['arp_description_text_alignment'] = '';
$arp_pt_column_settings['arp_description_text_bold_global'] = '';
$arp_pt_column_settings['arp_description_text_italic_global'] = '';
$arp_pt_column_settings['arp_description_text_decoration_global'] = '';

$arp_pt_font_settings['header_fonts'] = $arp_header_font_settings;
$arp_pt_font_settings['price_fonts'] = $arp_price_font_settings;
$arp_pt_font_settings['price_text_fonts'] = $arp_price_text_font_settings;
$arp_pt_font_settings['content_fonts'] = $arp_content_font_settings;
$arp_pt_font_settings['button_fonts'] = $arp_button_font_settings;

$arp_pt_gen_options = array('template_setting' => $arp_pt_template_settings, 'font_settings' => $arp_pt_font_settings, 'column_settings' => $arp_pt_column_settings, 'general_settings' => $arp_pt_general_settings, 'button_settings' => $arp_pt_button_settings);

$arp_pt_custom_skin_array = array();

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

$arp_pt_gen_options['custom_skin_colors'] = $arp_pt_custom_skin_array;

$values['options'] = maybe_serialize($arp_pt_gen_options);


$table_id = $arpricelite_form->create($values);

$pt_columns = array();

$column = array();


$column['column_0']['shortcode_background_color'] = '#ffffff';
$column['column_0']['shortcode_font_color'] = '#ffffff';
$column['column_0']['shortcode_hover_background_color'] = '#ffffff';
$column['column_0']['shortcode_hover_font_color'] = '#ffffff';
$column['column_0']['arp_shortcode_customization_style'] = 'rounded';
$column['column_0']['arp_shortcode_customization_size'] = 'small';

$column['column_0']['package_title'] = 'Basic Pro';
$column['column_0']['column_description'] = '';
$column['column_0']['custom_ribbon_txt'] = '';
$column['column_0']['column_width'] = '';
$column['column_0']['is_caption'] = 0;
$column['column_0']['column_highlight'] = '';
/* Header Font Settings */
$column['column_0']['header_background_color'] = '#e92a4b';
$column['column_0']['header_hover_background_color'] = '#e92a4b';
$column['column_0']['header_font_family'] = 'Open Sans Semibold';
$column['column_0']['header_font_size'] = 22;
$column['column_0']['header_font_color'] = "#ffffff";
$column['column_0']['header_hover_font_color'] = "#ffffff";
$column['column_0']['header_style_bold'] = '';
$column['column_0']['header_style_italic'] = '';
$column['column_0']['header_style_decoration'] = '';
/* Header Font Settings */
$column['column_0']['price_background_color'] = '';
$column['column_0']['price_hover_background_color'] = '';
$column['column_0']['price_font_family'] = "Arial";
$column['column_0']['price_font_size'] = 40;
$column['column_0']['price_font_color'] = "#ffffff";
$column['column_0']['price_hover_font_color'] = "#ffffff";
$column['column_0']['price_label_style_bold'] = 'bold';
$column['column_0']['price_label_style_italic'] = '';
$column['column_0']['price_label_style_decoration'] = '';

$column['column_0']['price_text_font_family'] = 'Arial';
$column['column_0']['price_text_font_size'] = 13;
$column['column_0']['price_text_font_color'] = "#ffffff";
$column['column_0']['price_text_hover_font_color'] = "#ffffff";
$column['column_0']['price_text_style_bold'] = '';
$column['column_0']['price_text_style_italic'] = '';
$column['column_0']['price_text_style_decoration'] = '';


/* Column Description Font Settings */
$column['column_0']['column_description_font_family'] = 'Arial';
$column['column_0']['column_description_font_size'] = 13;
$column['column_0']['column_description_font_color'] = '#7c7c7c';
$column['column_0']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_0']['column_description_style_bold'] = '';
$column['column_0']['column_description_style_italic'] = '';
$column['column_0']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_0']['content_font_family'] = "Open Sans Bold";
$column['column_0']['content_font_size'] = 15;
$column['column_0']['content_font_color'] = "#333333";
$column['column_0']['content_even_font_color'] = "#333333";
$column['column_0']['content_hover_font_color'] = "#333333";
$column['column_0']['content_even_hover_font_color'] = "#333333";
$column['column_0']['body_li_style_bold'] = '';
$column['column_0']['body_li_style_italic'] = '';
$column['column_0']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Content Label Font Settings */
$column['column_0']['content_label_font_family'] = 'Open Sans';
$column['column_0']['content_label_font_size'] = 14;
$column['column_0']['content_label_font_color'] = '#000000';
$column['column_0']['content_label_hover_font_color'] = '#000000';
$column['column_0']['content_odd_color'] = '#ffffff';
$column['column_0']['content_odd_hover_color'] = '#ffffff';
$column['column_0']['content_even_color'] = '#f7f8fa';
$column['column_0']['content_even_hover_color'] = '#ffffff';
$column['column_0']['body_label_style_bold'] = '';
$column['column_0']['body_label_style_italic'] = '';
$column['column_0']['body_label_style_decoration'] = '';
/* Content Label Font Settings */

/* Button Font Settings */
$column['column_0']['button_background_color'] = '#ffffff';
$column['column_0']['button_hover_background_color'] = '#ffffff';
$column['column_0']['button_font_family'] = "Open Sans Bold";
$column['column_0']['button_font_size'] = 18;
$column['column_0']['button_font_color'] = "#323232";
$column['column_0']['button_hover_font_color'] = "#323232";
$column['column_0']['button_style_bold'] = '';
$column['column_0']['button_style_italic'] = '';
$column['column_0']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_0']['price_text'] = "<span class='arp_price_duration' style='font-size:13px;font-weight:normal;'> PER MONTH </span><span class='arp_price_value'><span class='arp_currency'>$</span>10</span>";
$column['column_0']['price_label'] = "";
$column['column_0']['arp_header_shortcode'] = '<i class="fas fa-bicycle arpsize-ico-48"></i>';
$column['column_0']['body_text_alignment'] = 'center';
$column['column_0']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_0']['row_description'] = 'Data Usage <br/><b>10 GB</b>';
$column['column_0']['rows']['row_0']['row_label'] = '';
$column['column_0']['rows']['row_0']['row_tooltip'] = '';
$column['column_0']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_decoration'] = '';

$column['column_0']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_1']['row_description'] = 'MySQL Databases <br/> <b>5</b>';
$column['column_0']['rows']['row_1']['row_label'] = '';
$column['column_0']['rows']['row_1']['row_tooltip'] = '';
$column['column_0']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_2']['row_description'] = 'Email Accounts <br/> <b>5</b>';
$column['column_0']['rows']['row_2']['row_label'] = '';
$column['column_0']['rows']['row_2']['row_tooltip'] = '';
$column['column_0']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_3']['row_description'] = 'Free Domain <br/> <b>5</b>';
$column['column_0']['rows']['row_3']['row_label'] = '';
$column['column_0']['rows']['row_3']['row_tooltip'] = '';
$column['column_0']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_decoration'] = '';

$column['column_0']['button_size'] = '134';
$column['column_0']['button_height'] = '36';
$column['column_0']['button_type'] = 'Button';
$column['column_0']['button_text'] = 'Submit';
$column['column_0']['button_url'] = '#';
$column['column_0']['button_s_size'] = '';
$column['column_0']['button_s_type'] = '';
$column['column_0']['button_s_text'] = '';
$column['column_0']['button_s_url'] = '';
$column['column_0']['s_is_new_window'] = '';
$column['column_0']['is_new_window'] = 0;

$column['column_0']['footer_content'] = '';
$column['column_0']['footer_content_position'] = 0;
$column['column_0']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_0']['footer_level_options_font_size'] = 12;
$column['column_0']['footer_level_options_font_color'] = '#333333';
$column['column_0']['footer_level_options_hover_font_color'] = '#333333';
$column['column_0']['footer_level_options_font_style_bold'] = '';
$column['column_0']['footer_level_options_font_style_italic'] = '';
$column['column_0']['footer_level_options_font_style_decoration'] = '';

$column['column_0']['is_post_variables'] = 0;
$column['column_0']['post_variables_content'] = '';

$column['column_1']['shortcode_background_color'] = '#ffffff';
$column['column_1']['shortcode_font_color'] = '#ffffff';
$column['column_1']['shortcode_hover_background_color'] = '#ffffff';
$column['column_1']['shortcode_hover_font_color'] = '#ffffff';
$column['column_1']['arp_shortcode_customization_style'] = 'rounded';
$column['column_1']['arp_shortcode_customization_size'] = 'small';
$column['column_1']['package_title'] = 'Standard Pro';
$column['column_1']['column_description'] = '';
$column['column_1']['custom_ribbon_txt'] = '';
$column['column_1']['column_width'] = '';
$column['column_1']['is_caption'] = 0;
$column['column_1']['column_highlight'] = '';
/* Header Font Settings */
$column['column_1']['header_background_color'] = '#21c77b';
$column['column_1']['header_hover_background_color'] = '#21c77b';
$column['column_1']['header_font_family'] = 'Open Sans Semibold';
$column['column_1']['header_font_size'] = 22;
$column['column_1']['header_font_color'] = "#ffffff";
$column['column_1']['header_hover_font_color'] = "#ffffff";
$column['column_1']['header_style_bold'] = '';
$column['column_1']['header_style_italic'] = '';
$column['column_1']['header_style_decoration'] = '';
/* Header Font Settings */
$column['column_1']['price_background_color'] = '';
$column['column_1']['price_hover_background_color'] = '';
$column['column_1']['price_font_family'] = "Arial";
$column['column_1']['price_font_size'] = 40;
$column['column_1']['price_font_color'] = "#ffffff";
$column['column_1']['price_hover_font_color'] = "#ffffff";
$column['column_1']['price_label_style_bold'] = 'bold';
$column['column_1']['price_label_style_italic'] = '';
$column['column_1']['price_label_style_decoration'] = '';

$column['column_1']['price_text_font_family'] = 'Arial';
$column['column_1']['price_text_font_size'] = 13;
$column['column_1']['price_text_font_color'] = "#ffffff";
$column['column_1']['price_text_hover_font_color'] = "#ffffff";
$column['column_1']['price_text_style_bold'] = '';
$column['column_1']['price_text_style_italic'] = '';
$column['column_1']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_1']['column_description_font_family'] = 'Arial';
$column['column_1']['column_description_font_size'] = 13;
$column['column_1']['column_description_font_color'] = '#7c7c7c';
$column['column_1']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_1']['column_description_style_bold'] = '';
$column['column_1']['column_description_style_italic'] = '';
$column['column_1']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_1']['content_font_family'] = "Open Sans Bold";
$column['column_1']['content_font_size'] = 15;
$column['column_1']['content_font_color'] = "#333333";
$column['column_1']['content_even_font_color'] = "#333333";
$column['column_1']['content_hover_font_color'] = "#333333";
$column['column_1']['content_even_hover_font_color'] = "#333333";
$column['column_1']['content_odd_color'] = '#ffffff';
$column['column_1']['content_odd_hover_color'] = '#ffffff';
$column['column_1']['content_even_color'] = '#f7f8fa';
$column['column_1']['content_even_hover_color'] = '#ffffff';
$column['column_1']['body_li_style_bold'] = '';
$column['column_1']['body_li_style_italic'] = '';
$column['column_1']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Content Label Font Settings */
$column['column_1']['content_label_font_family'] = 'Open Sans';
$column['column_1']['content_label_font_size'] = 14;
$column['column_1']['content_label_font_color'] = '#000000';
$column['column_1']['content_label_hover_font_color'] = '#000000';
$column['column_1']['body_label_style_bold'] = '';
$column['column_1']['body_label_style_italic'] = '';
$column['column_1']['body_label_style_decoration'] = '';
/* Content Label Font Settings */
/* Button Font Settings */
$column['column_1']['button_background_color'] = '#ffffff';
$column['column_1']['button_hover_background_color'] = '#ffffff';
$column['column_1']['button_font_family'] = "Open Sans Bold";
$column['column_1']['button_font_size'] = 18;
$column['column_1']['button_font_color'] = "#323232";
$column['column_1']['button_hover_font_color'] = "#323232";
$column['column_1']['button_style_bold'] = '';
$column['column_1']['button_style_italic'] = '';
$column['column_1']['button_style_decoration'] = '';
/* Button Font Settings */


$column['column_1']['price_text'] = "<span class='arp_price_duration' style='font-size:13px;font-weight:normal;'>PER MONTH </span><span class='arp_price_value'><span class='arp_currency'>$</span>20</span>";
$column['column_1']['price_label'] = "";
$column['column_1']['arp_header_shortcode'] = '<i class="fas fa-motorcycle arpsize-ico-48"></i>';
$column['column_1']['body_text_alignment'] = 'center';
$column['column_1']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_0']['row_description'] = 'Data Storage <br/> <b>25 GB</b>';
$column['column_1']['rows']['row_0']['row_label'] = '';
$column['column_1']['rows']['row_0']['row_tooltip'] = '';
$column['column_1']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_1']['row_description'] = 'MySQL Databases <br/> <b>10</b>';
$column['column_1']['rows']['row_1']['row_label'] = '';
$column['column_1']['rows']['row_1']['row_tooltip'] = '';
$column['column_1']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_2']['row_description'] = 'Email Accounts <br/> <b>10</b>';
$column['column_1']['rows']['row_2']['row_label'] = '';
$column['column_1']['rows']['row_2']['row_tooltip'] = '';
$column['column_1']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_3']['row_description'] = 'Free Domain <br/> <b>10</b>';
$column['column_1']['rows']['row_3']['row_label'] = '';
$column['column_1']['rows']['row_3']['row_tooltip'] = '';
$column['column_1']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_1']['button_size'] = '134';
$column['column_1']['button_height'] = '36';
$column['column_1']['button_type'] = 'Button';
$column['column_1']['button_text'] = 'Submit';
$column['column_1']['button_url'] = '#';
$column['column_1']['button_s_size'] = '';
$column['column_1']['button_s_type'] = '';
$column['column_1']['button_s_text'] = '';
$column['column_1']['button_s_url'] = '';
$column['column_1']['s_is_new_window'] = '';
$column['column_1']['is_new_window'] = 0;

$column['column_1']['footer_content'] = '';
$column['column_1']['footer_content_position'] = 0;
$column['column_1']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_1']['footer_level_options_font_size'] = 12;
$column['column_1']['footer_level_options_font_color'] = '#333333';
$column['column_1']['footer_level_options_hover_font_color'] = '#333333';
$column['column_1']['footer_level_options_font_style_bold'] = '';
$column['column_1']['footer_level_options_font_style_italic'] = '';
$column['column_1']['footer_level_options_font_style_decoration'] = '';
$column['column_1']['is_post_variables'] = 0;
$column['column_1']['post_variables_content'] = '';

$column['column_2']['shortcode_background_color'] = '#ffffff';
$column['column_2']['shortcode_font_color'] = '#ffffff';
$column['column_2']['shortcode_hover_background_color'] = '#ffffff';
$column['column_2']['shortcode_hover_font_color'] = '#ffffff';
$column['column_2']['arp_shortcode_customization_style'] = 'rounded';
$column['column_2']['arp_shortcode_customization_size'] = 'small';
$column['column_2']['package_title'] = 'Advanced Pro';
$column['column_2']['column_description'] = '';
$column['column_2']['custom_ribbon_txt'] = '';
$column['column_2']['column_width'] = '';
$column['column_2']['is_caption'] = 0;
$column['column_2']['column_highlight'] = 1;
/* Header Font Settings */
$column['column_2']['header_background_color'] = '#ffc000';
$column['column_2']['header_hover_background_color'] = '#ffc000';
$column['column_2']['header_font_family'] = 'Open Sans Semibold';
$column['column_2']['header_font_size'] = 22;
$column['column_2']['header_font_color'] = "#ffffff";
$column['column_2']['header_hover_font_color'] = "#ffffff";
$column['column_2']['header_style_bold'] = '';
$column['column_2']['header_style_italic'] = '';
$column['column_2']['header_style_decoration'] = '';
/* Header Font Settings */
$column['column_2']['price_background_color'] = '';
$column['column_2']['price_hover_background_color'] = '';
$column['column_2']['price_font_family'] = "Arial";
$column['column_2']['price_font_size'] = 40;
$column['column_2']['price_font_color'] = "#ffffff";
$column['column_2']['price_hover_font_color'] = "#ffffff";
$column['column_2']['price_label_style_bold'] = 'bold';
$column['column_2']['price_label_style_italic'] = '';
$column['column_2']['price_label_style_decoration'] = '';

$column['column_2']['price_text_font_family'] = 'Arial';
$column['column_2']['price_text_font_size'] = 13;
$column['column_2']['price_text_font_color'] = "#ffffff";
$column['column_2']['price_text_hover_font_color'] = "#ffffff";
$column['column_2']['price_text_style_bold'] = '';
$column['column_2']['price_text_style_italic'] = '';
$column['column_2']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_2']['column_description_font_family'] = 'Arial';
$column['column_2']['column_description_font_size'] = 13;
$column['column_2']['column_description_font_color'] = '#7c7c7c';
$column['column_2']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_2']['column_description_style_bold'] = '';
$column['column_2']['column_description_style_italic'] = '';
$column['column_2']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_2']['content_font_family'] = "Open Sans Bold";
$column['column_2']['content_font_size'] = 15;
$column['column_2']['content_font_color'] = "#333333";
$column['column_2']['content_even_font_color'] = "#333333";
$column['column_2']['content_hover_font_color'] = "#333333";
$column['column_2']['content_even_hover_font_color'] = "#333333";
$column['column_2']['content_odd_color'] = '#ffffff';
$column['column_2']['content_odd_hover_color'] = '#ffffff';
$column['column_2']['content_even_color'] = '#f7f8fa';
$column['column_2']['content_even_hover_color'] = '#ffffff';
$column['column_2']['body_li_style_bold'] = '';
$column['column_2']['body_li_style_italic'] = '';
$column['column_2']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Content Label Font Settings */
$column['column_2']['content_label_font_family'] = 'Open Sans';
$column['column_2']['content_label_font_size'] = 14;
$column['column_2']['content_label_font_color'] = '#000000';
$column['column_2']['content_label_hover_font_color'] = '#000000';
$column['column_2']['body_label_style_bold'] = '';
$column['column_2']['body_label_style_italic'] = '';
$column['column_2']['body_label_style_decoration'] = '';
/* Content Label Font Settings */
/* Button Font Settings */
$column['column_2']['button_background_color'] = '#ffffff';
$column['column_2']['button_hover_background_color'] = '#ffffff';
$column['column_2']['button_font_family'] = "Open Sans Bold";
$column['column_2']['button_font_size'] = 18;
$column['column_2']['button_font_color'] = "#323232";
$column['column_2']['button_hover_font_color'] = "#323232";
$column['column_2']['button_style_bold'] = '';
$column['column_2']['button_style_italic'] = '';
$column['column_2']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_2']['price_text'] = "<span class='arp_price_duration' style='font-size:13px;font-weight:normal;'> PER MONTH </span><span class='arp_price_value'><span class='arp_currency'>$</span>30</span>";
$column['column_2']['price_label'] = "";
$column['column_2']['arp_header_shortcode'] = '<i class="fas fa-car arpsize-ico-48"></i>';
$column['column_2']['body_text_alignment'] = 'center';
$column['column_2']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_0']['row_description'] = 'Data Storage <br/> <b>50 GB</b>';
$column['column_2']['rows']['row_0']['row_label'] = '';
$column['column_2']['rows']['row_0']['row_tooltip'] = '';
$column['column_2']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_1']['row_description'] = 'MySQL Databases <br/> <b>30</b>';
$column['column_2']['rows']['row_1']['row_label'] = '';
$column['column_2']['rows']['row_1']['row_tooltip'] = '';
$column['column_2']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_2']['row_description'] = 'Email Accounts <br/> <b>20</b>';
$column['column_2']['rows']['row_2']['row_label'] = '';
$column['column_2']['rows']['row_2']['row_tooltip'] = '';
$column['column_2']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_3']['row_description'] = 'Free Domain <br/> <b>30</b>';
$column['column_2']['rows']['row_3']['row_label'] = '';
$column['column_2']['rows']['row_3']['row_tooltip'] = '';
$column['column_2']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_2']['button_size'] = '134';
$column['column_2']['button_height'] = '36';
$column['column_2']['button_type'] = 'Button';
$column['column_2']['button_text'] = 'Submit';
$column['column_2']['button_url'] = '#';
$column['column_2']['button_s_size'] = '';
$column['column_2']['button_s_type'] = '';
$column['column_2']['button_s_text'] = '';
$column['column_2']['button_s_url'] = '';
$column['column_2']['s_is_new_window'] = '';
$column['column_2']['is_new_window'] = 0;

$column['column_2']['footer_content'] = '';
$column['column_2']['footer_content_position'] = 0;
$column['column_2']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_2']['footer_level_options_font_size'] = 12;
$column['column_2']['footer_level_options_font_color'] = '#333333';
$column['column_2']['footer_level_options_hover_font_color'] = '#333333';
$column['column_2']['footer_level_options_font_style_bold'] = '';
$column['column_2']['footer_level_options_font_style_italic'] = '';
$column['column_2']['footer_level_options_font_style_decoration'] = '';
$column['column_2']['is_post_variables'] = 0;
$column['column_2']['post_variables_content'] = '';

$column['column_3']['shortcode_background_color'] = '#ffffff';
$column['column_3']['shortcode_font_color'] = '#ffffff';
$column['column_3']['shortcode_hover_background_color'] = '#ffffff';
$column['column_3']['shortcode_hover_font_color'] = '#ffffff';
$column['column_3']['arp_shortcode_customization_style'] = 'rounded';
$column['column_3']['arp_shortcode_customization_size'] = 'small';
$column['column_3']['package_title'] = 'Ultimate Pro';
$column['column_3']['column_description'] = '';
$column['column_3']['custom_ribbon_txt'] = '';
$column['column_3']['column_width'] = '';
$column['column_3']['column_align'] = 'left';
$column['column_3']['is_caption'] = 0;
$column['column_3']['column_highlight'] = '';
/* Header Font Settings */
$column['column_3']['header_background_color'] = '#52c4ff';
$column['column_3']['header_hover_background_color'] = '#52c4ff';
$column['column_3']['header_font_family'] = 'Open Sans Semibold';
$column['column_3']['header_font_size'] = 22;
$column['column_3']['header_font_color'] = "#ffffff";
$column['column_3']['header_hover_font_color'] = "#ffffff";
$column['column_3']['header_style_bold'] = '';
$column['column_3']['header_style_italic'] = '';
$column['column_3']['header_style_decoration'] = '';
/* Header Font Settings */
$column['column_3']['price_background_color'] = '#761db5';
$column['column_3']['price_hover_background_color'] = '#761db5';
$column['column_3']['price_font_family'] = "Arial";
$column['column_3']['price_font_size'] = 40;
$column['column_3']['price_font_color'] = "#ffffff";
$column['column_3']['price_hover_font_color'] = "#ffffff";
$column['column_3']['price_label_style_bold'] = 'bold';
$column['column_3']['price_label_style_italic'] = '';
$column['column_3']['price_label_style_decoration'] = '';

$column['column_3']['price_text_font_family'] = 'Arial';
$column['column_3']['price_text_font_size'] = 13;
$column['column_3']['price_text_font_color'] = "#ffffff";
$column['column_3']['price_text_hover_font_color'] = "#ffffff";
$column['column_3']['price_text_style_bold'] = '';
$column['column_3']['price_text_style_italic'] = '';
$column['column_3']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_3']['column_description_font_family'] = 'Arial';
$column['column_3']['column_description_font_size'] = 13;
$column['column_3']['column_description_font_color'] = '#7c7c7c';
$column['column_3']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_3']['column_description_style_bold'] = '';
$column['column_3']['column_description_style_italic'] = '';
$column['column_3']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_3']['content_font_family'] = "Open Sans Bold";
$column['column_3']['content_font_size'] = 15;
$column['column_3']['content_font_color'] = "#333333";
$column['column_3']['content_even_font_color'] = "#333333";
$column['column_3']['content_hover_font_color'] = "#333333";
$column['column_3']['content_even_hover_font_color'] = "#333333";
$column['column_3']['content_odd_color'] = '#ffffff';
$column['column_3']['content_odd_hover_color'] = '#ffffff';
$column['column_3']['content_even_color'] = '#f7f8fa';
$column['column_3']['content_even_hover_color'] = '#ffffff';
$column['column_3']['body_li_style_bold'] = '';
$column['column_3']['body_li_style_italic'] = '';
$column['column_3']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Content Label Font Settings */
$column['column_3']['content_label_font_family'] = 'Open Sans';
$column['column_3']['content_label_font_size'] = 14;
$column['column_3']['content_label_font_color'] = '#000000';
$column['column_3']['content_label_hover_font_color'] = '#000000';
$column['column_3']['body_label_style_bold'] = '';
$column['column_3']['body_label_style_italic'] = '';
$column['column_3']['body_label_style_decoration'] = '';
/* Content Label Font Settings */

/* Button Font Settings */
$column['column_3']['button_background_color'] = '#ffffff';
$column['column_3']['button_hover_background_color'] = '#ffffff';
$column['column_3']['button_font_family'] = "Open Sans Bold";
$column['column_3']['button_font_size'] = 18;
$column['column_3']['button_font_color'] = "#323232";
$column['column_3']['button_hover_font_color'] = "#323232";
$column['column_3']['button_style_bold'] = '';
$column['column_3']['button_style_italic'] = '';
$column['column_3']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_3']['price_text'] = "<span class='arp_price_duration' style='font-size:13px;font-weight:normal;'> PER MONTH </span><span class='arp_price_value'><span class='arp_currency'>$</span>40</span>";
$column['column_3']['price_label'] = "";
$column['column_3']['arp_header_shortcode'] = '<i class="fas fa-subway arpsize-ico-48"></i>';
$column['column_3']['body_text_alignment'] = 'center';
$column['column_3']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_0']['row_description'] = 'Data Usage <br/> <b>Unlimited</b>';
$column['column_3']['rows']['row_0']['row_label'] = '';
$column['column_3']['rows']['row_0']['row_tooltip'] = '';
$column['column_3']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_1']['row_description'] = 'MySQL Databases <br/><b>Unlimited Database</b>';
$column['column_3']['rows']['row_1']['row_label'] = '';
$column['column_3']['rows']['row_1']['row_tooltip'] = '';
$column['column_3']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_2']['row_description'] = 'Email Accounts <br/> <b>30</b>';
$column['column_3']['rows']['row_2']['row_label'] = '';
$column['column_3']['rows']['row_2']['row_tooltip'] = '';
$column['column_3']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_3']['row_description'] = 'Free Domain <br/> <b>30</b>';
$column['column_3']['rows']['row_3']['row_label'] = '';
$column['column_3']['rows']['row_3']['row_tooltip'] = '';
$column['column_3']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_3']['button_size'] = '134';
$column['column_3']['button_height'] = '36';
$column['column_3']['button_type'] = 'Button';
$column['column_3']['button_text'] = 'Submit';
$column['column_3']['button_url'] = '#';
$column['column_3']['button_s_size'] = '';
$column['column_3']['button_s_type'] = '';
$column['column_3']['button_s_text'] = '';
$column['column_3']['button_s_url'] = '';
$column['column_3']['s_is_new_window'] = '';
$column['column_3']['s_is_new_window'] = '';
$column['column_3']['is_new_window'] = 0;

$column['column_3']['footer_content'] = '';
$column['column_3']['footer_content_position'] = 0;
$column['column_3']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_3']['footer_level_options_font_size'] = 12;
$column['column_3']['footer_level_options_font_color'] = '#333333';
$column['column_3']['footer_level_options_hover_font_color'] = '#333333';
$column['column_3']['footer_level_options_font_style_bold'] = '';
$column['column_3']['footer_level_options_font_style_italic'] = '';
$column['column_3']['footer_level_options_font_style_decoration'] = '';
$column['column_3']['is_post_variables'] = 0;
$column['column_3']['post_variables_content'] = '';

$pt_columns = array('columns' => $column);
$opts = maybe_serialize($pt_columns);

$arpricelite_form->option_create($table_id, $opts);

unset($values);

/**
 * ARPricelite Template 11
 * 
 * @since 1.0
 */
$values['name'] = 'ARPricelite Template 11';
$values['is_template'] = 1;
$values['ID'] = 11;
$values['template_name'] = 11;
$values['status'] = 'published';
$values['is_animated'] = 0;

$arp_pt_gen_options = array();

$arp_pt_template_settings = array();

$arp_pt_font_settings = array();

$arp_pt_general_settings = array();

$arp_header_font_settings = array();
$arp_price_font_settings = array();
$arp_content_font_settings = array();
$arp_button_font_settings = array();

$arp_pt_column_settings = array();





$arp_pt_button_settings = array();

$arp_pt_template_settings['template'] = 'arplitetemplate_11';
$arp_pt_template_settings['skin'] = 'yellow';
$arp_pt_template_settings['template_type'] = 'normal';
$arp_pt_template_settings['features'] = array('column_description' => 'enable', 'custom_ribbon' => 'disable', 'button_position' => 'position_1', 'caption_style' => 'none', 'amount_style' => 'default', 'list_alignment' => 'default', 'ribbon_type' => 'default', 'column_description_style' => 'style_4', 'caption_title' => 'default', 'header_shortcode_type' => 'normal', 'header_shortcode_position' => 'default', 'tooltip_position' => 'top-left', 'tooltip_style' => 'default', 'second_btn' => false, 'is_animated' => 0);



$arp_pt_general_settings['column_order'] = '["main_column_0","main_column_1","main_column_2","main_column_3"]';
$arp_pt_general_settings['reference_template'] = 'arplitetemplate_11';
$arp_pt_general_settings['user_edited_columns'] = '';



$arp_pt_button_settings['button_shadow_color'] = '#ffffff';
$arp_pt_button_settings['button_radius'] = 0;

$arp_pt_column_settings['column_space'] = 0;
$arp_pt_column_settings['min_row_height'] = 45;
$arp_pt_column_settings['column_highlight_on_hover'] = 'shadow_effect';
$arp_pt_column_settings['is_responsive'] = 1;
$arp_pt_column_settings['full_column_clickable'] = 0;
$arp_pt_column_settings['enable_hover_effect'] = 1;
$arp_pt_column_settings['hide_footer_global'] = 0;
$arp_pt_column_settings['hide_header_global'] = 0;
$arp_pt_column_settings['hide_price_global'] = 0;
$arp_pt_column_settings['hide_feature_global'] = 0;
$arp_pt_column_settings['hide_description_global'] = 0;
$arp_pt_column_settings['hide_header_shortcode_global'] = 0;
$arp_pt_column_settings['all_column_width'] = 250;
$arp_pt_column_settings['column_opacity'] = $arplite_mainoptionsarr['general_options']['column_opacity'][0];
$arp_pt_column_settings['column_border_radius_top_left'] = 0;
$arp_pt_column_settings['column_border_radius_top_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['column_wrapper_width_txtbox'] = 1000;

$arp_pt_column_settings['global_button_border_width'] = 0;
$arp_pt_column_settings['global_button_border_type'] = 'solid';
$arp_pt_column_settings['global_button_border_color'] = '#c9c9c9ff';
$arp_pt_column_settings['global_button_border_radius_top_left'] = 0;
$arp_pt_column_settings['global_button_border_radius_top_right'] = 0;
$arp_pt_column_settings['global_button_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['global_button_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['arp_global_button_type'] = 'shadow';

$arp_pt_column_settings['arp_row_border_size'] = '0';
$arp_pt_column_settings['arp_row_border_type'] = 'solid';
$arp_pt_column_settings['arp_row_border_color'] = '#c9c9c9ff';

$arp_pt_column_settings['arp_column_border_size'] = '1';
$arp_pt_column_settings['arp_column_border_type'] = 'solid';
$arp_pt_column_settings['arp_column_border_color'] = '#525252ff';

$arp_pt_column_settings['arp_column_border_left'] = 0;
$arp_pt_column_settings['arp_column_border_right'] = 1;
$arp_pt_column_settings['arp_column_border_top'] = 0;
$arp_pt_column_settings['arp_column_border_bottom'] = 0;

$arp_pt_column_settings['display_col_mobile'] = 1;
$arp_pt_column_settings['display_col_tablet'] = 3;


$arp_pt_column_settings['column_box_shadow_effect'] = 'shadow_style_none';
$arp_pt_column_settings['hide_blank_rows'] = 'no';

$arp_pt_column_settings['header_font_family_global'] = 'Roboto Condensed';
$arp_pt_column_settings['header_font_size_global'] = 28;
$arp_pt_column_settings['arp_header_text_alignment'] = 'center';
$arp_pt_column_settings['arp_header_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_header_text_italic_global'] = '';
$arp_pt_column_settings['arp_header_text_decoration_global'] = '';
$arp_pt_column_settings['price_font_family_global'] = 'Roboto Condensed';
$arp_pt_column_settings['price_font_size_global'] = 48;
$arp_pt_column_settings['arp_price_text_alignment'] = 'center';
$arp_pt_column_settings['arp_price_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_price_text_italic_global'] = '';
$arp_pt_column_settings['arp_price_text_decoration_global'] = '';
$arp_pt_column_settings['body_font_family_global'] = 'Roboto Condensed';
$arp_pt_column_settings['body_font_size_global'] = 18;
$arp_pt_column_settings['arp_body_text_alignment'] = 'left';
$arp_pt_column_settings['arp_body_text_bold_global'] = '';
$arp_pt_column_settings['arp_body_text_italic_global'] = '';
$arp_pt_column_settings['arp_body_text_decoration_global'] = '';
$arp_pt_column_settings['footer_font_family_global'] = '';
$arp_pt_column_settings['footer_font_size_global'] = '';
$arp_pt_column_settings['arp_footer_text_alignment'] = '';
$arp_pt_column_settings['arp_footer_text_bold_global'] = '';
$arp_pt_column_settings['arp_footer_text_italic_global'] = '';
$arp_pt_column_settings['arp_footer_text_decoration_global'] = '';
$arp_pt_column_settings['button_font_family_global'] = 'Roboto Condensed';
$arp_pt_column_settings['button_font_size_global'] = 20;
$arp_pt_column_settings['arp_button_text_alignment'] = 'center';
$arp_pt_column_settings['arp_button_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_button_text_italic_global'] = '';
$arp_pt_column_settings['arp_button_text_decoration_global'] = '';
$arp_pt_column_settings['description_font_family_global'] = 'Open Sans';
$arp_pt_column_settings['description_font_size_global'] = 14;
$arp_pt_column_settings['arp_description_text_alignment'] = 'center';
$arp_pt_column_settings['arp_description_text_bold_global'] = '';
$arp_pt_column_settings['arp_description_text_italic_global'] = '';
$arp_pt_column_settings['arp_description_text_decoration_global'] = '';




$arp_pt_font_settings['header_fonts'] = $arp_header_font_settings;
$arp_pt_font_settings['price_fonts'] = $arp_price_font_settings;
$arp_pt_font_settings['price_text_fonts'] = $arp_price_text_font_settings;
$arp_pt_font_settings['content_fonts'] = $arp_content_font_settings;
$arp_pt_font_settings['button_fonts'] = $arp_button_font_settings;

$arp_pt_gen_options = array('template_setting' => $arp_pt_template_settings, 'font_settings' => $arp_pt_font_settings, 'column_settings' => $arp_pt_column_settings, 'general_settings' => $arp_pt_general_settings, 'button_settings' => $arp_pt_button_settings);

$arp_pt_custom_skin_array = array();

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

$arp_pt_gen_options['custom_skin_colors'] = $arp_pt_custom_skin_array;

$values['options'] = maybe_serialize($arp_pt_gen_options);


$table_id = $arpricelite_form->create($values);

$pt_columns = array();

$column = array();

$column['column_0']['package_title'] = 'Basic';
$column['column_0']['column_description'] = '"Aliquam euisod erat libero condimentum nisl hendrerit."';
$column['column_0']['custom_ribbon_txt'] = '';
$column['column_0']['column_width'] = '';
$column['column_0']['is_caption'] = 0;
$column['column_0']['column_highlight'] = '';

$column['column_0']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>10</span><span class='arp_price_duration' style='font-size:18px;font-weight:normal;'> PER MONTH </span>";
$column['column_0']['price_label'] = "";
$column['column_0']['arp_header_shortcode'] = '';
/* Header Font Settings */
$column['column_0']['header_background_color'] = '#414045';
$column['column_0']['header_hover_background_color'] = '#51545d';
$column['column_0']['header_font_family'] = 'Roboto Condensed';
$column['column_0']['header_font_size'] = 28;
$column['column_0']['header_font_color'] = "#ffffff";
$column['column_0']['header_hover_font_color'] = "#ffffff";
$column['column_0']['header_style_bold'] = 'bold';
$column['column_0']['header_style_italic'] = '';
$column['column_0']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_0']['price_font_family'] = "Roboto Condensed";
$column['column_0']['price_font_size'] = 48;
$column['column_0']['price_font_color'] = "#ffffff";
$column['column_0']['price_hover_font_color'] = "#ffffff";
$column['column_0']['price_label_style_bold'] = 'bold';
$column['column_0']['price_label_style_italic'] = '';
$column['column_0']['price_label_style_decoration'] = '';

$column['column_0']['price_text_font_family'] = 'Roboto Condensed';
$column['column_0']['price_text_font_size'] = 18;
$column['column_0']['price_text_font_color'] = "#ffffff";
$column['column_0']['price_text_hover_font_color'] = "#ffffff";
$column['column_0']['price_text_style_bold'] = '';
$column['column_0']['price_text_style_italic'] = '';
$column['column_0']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_0']['column_description_font_family'] = 'Open Sans';
$column['column_0']['column_description_font_size'] = 14;
$column['column_0']['column_description_font_color'] = '#ffffff';
$column['column_0']['column_description_hover_font_color'] = '#ffffff';
$column['column_0']['column_desc_background_color'] = '#37363b';
$column['column_0']['column_desc_hover_background_color'] = '#46474c';
$column['column_0']['column_description_style_bold'] = '';
$column['column_0']['column_description_style_italic'] = '';
$column['column_0']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_0']['content_font_family'] = "Roboto Condensed";
$column['column_0']['content_font_size'] = 18;
$column['column_0']['content_font_color'] = "#ffffff";
$column['column_0']['content_even_font_color'] = "#ffffff";
$column['column_0']['content_hover_font_color'] = "#ffffff";
$column['column_0']['content_even_hover_font_color'] = "#ffffff";
$column['column_0']['content_odd_color'] = '#313035';
$column['column_0']['content_odd_hover_color'] = '#3e4044';
$column['column_0']['content_even_color'] = '#37363b';
$column['column_0']['content_even_hover_color'] = '#46474c';
$column['column_0']['body_li_style_bold'] = '';
$column['column_0']['body_li_style_italic'] = '';
$column['column_0']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Button Font Settings */
$column['column_0']['button_background_color'] = '#efa738';
$column['column_0']['button_hover_background_color'] = '#09b1f8';
$column['column_0']['button_font_family'] = "Roboto Condensed";
$column['column_0']['button_font_size'] = 20;
$column['column_0']['button_font_color'] = "#ffffff";
$column['column_0']['button_hover_font_color'] = "#ffffff";
$column['column_0']['button_style_bold'] = 'bold';
$column['column_0']['button_style_italic'] = '';
$column['column_0']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_0']['body_text_alignment'] = 'left';
$column['column_0']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> sit dolor lobortis';
$column['column_0']['rows']['row_0']['row_label'] = '';
$column['column_0']['rows']['row_0']['row_tooltip'] = '';
$column['column_0']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> Falli libris has id fa';
$column['column_0']['rows']['row_1']['row_label'] = '';
$column['column_0']['rows']['row_1']['row_tooltip'] = '';
$column['column_0']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> pertinax vel eum';
$column['column_0']['rows']['row_2']['row_label'] = '';
$column['column_0']['rows']['row_2']['row_tooltip'] = '';
$column['column_0']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> taleni nolui gniferu';
$column['column_0']['rows']['row_3']['row_label'] = '';
$column['column_0']['rows']['row_3']['row_tooltip'] = '';
$column['column_0']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_0']['button_size'] = '158';
$column['column_0']['button_height'] = '45';
$column['column_0']['button_type'] = 'Button';
$column['column_0']['button_text'] = 'Purchase';
$column['column_0']['button_url'] = '#';
$column['column_0']['button_s_size'] = '';
$column['column_0']['button_s_type'] = '';
$column['column_0']['button_s_text'] = '';
$column['column_0']['button_s_url'] = '';
$column['column_0']['s_is_new_window'] = '';
$column['column_0']['is_new_window'] = 0;

$column['column_0']['footer_content'] = '';
$column['column_0']['footer_content_position'] = 0;
$column['column_0']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_0']['footer_level_options_font_size'] = 12;
$column['column_0']['footer_level_options_font_color'] = '#ffffff';
$column['column_0']['footer_level_options_hover_font_color'] = '#ffffff';
$column['column_0']['footer_level_options_font_style_bold'] = '';
$column['column_0']['footer_level_options_font_style_italic'] = '';
$column['column_0']['footer_level_options_font_style_decoration'] = '';

$column['column_0']['is_post_variables'] = 0;
$column['column_0']['post_variables_content'] = '';


$column['column_1']['package_title'] = 'Personal';
$column['column_1']['column_description'] = '"Aliquam euisod erat libero condimentum nisl hendrerit."';
$column['column_1']['custom_ribbon_txt'] = '';
$column['column_1']['column_width'] = '';
$column['column_1']['is_caption'] = 0;
$column['column_1']['column_highlight'] = '';
/* Header Font Settings */
$column['column_1']['header_background_color'] = '#414045';
$column['column_1']['header_hover_background_color'] = '#51545d';
$column['column_1']['header_font_family'] = 'Roboto Condensed';
$column['column_1']['header_font_size'] = 28;
$column['column_1']['header_font_color'] = "#ffffff";
$column['column_1']['header_hover_font_color'] = "#ffffff";
$column['column_1']['header_style_bold'] = 'bold';
$column['column_1']['header_style_italic'] = '';
$column['column_1']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_1']['price_font_family'] = "Roboto Condensed";
$column['column_1']['price_font_size'] = 48;
$column['column_1']['price_font_color'] = "#ffffff";
$column['column_1']['price_hover_font_color'] = "#ffffff";
$column['column_1']['price_label_style_bold'] = 'bold';
$column['column_1']['price_label_style_italic'] = '';
$column['column_1']['price_label_style_decoration'] = '';

$column['column_1']['price_text_font_family'] = 'Roboto Condensed';
$column['column_1']['price_text_font_size'] = 18;
$column['column_1']['price_text_font_color'] = "#ffffff";
$column['column_1']['price_text_hover_font_color'] = "#ffffff";
$column['column_1']['price_text_style_bold'] = '';
$column['column_1']['price_text_style_italic'] = '';
$column['column_1']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_1']['column_description_font_family'] = 'Open Sans';
$column['column_1']['column_description_font_size'] = 14;
$column['column_1']['column_description_font_color'] = '#ffffff';
$column['column_1']['column_description_hover_font_color'] = '#ffffff';
$column['column_1']['column_desc_background_color'] = '#37363b';
$column['column_1']['column_desc_hover_background_color'] = '#46474c';
$column['column_1']['column_description_style_bold'] = '';
$column['column_1']['column_description_style_italic'] = '';
$column['column_1']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_1']['content_font_family'] = "Roboto Condensed";
$column['column_1']['content_font_size'] = 18;
$column['column_1']['content_font_color'] = "#ffffff";
$column['column_1']['content_even_font_color'] = "#ffffff";
$column['column_1']['content_hover_font_color'] = "#ffffff";
$column['column_1']['content_even_hover_font_color'] = "#ffffff";
$column['column_1']['content_odd_color'] = '#313035';
$column['column_1']['content_odd_hover_color'] = '#3e4044';
$column['column_1']['content_even_color'] = '#37363b';
$column['column_1']['content_even_hover_color'] = '#46474c';
$column['column_1']['body_li_style_bold'] = '';
$column['column_1']['body_li_style_italic'] = '';
$column['column_1']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Button Font Settings */
$column['column_1']['button_background_color'] = '#efa738';
$column['column_1']['button_hover_background_color'] = '#09b1f8';
$column['column_1']['button_font_family'] = "Roboto Condensed";
$column['column_1']['button_font_size'] = 20;
$column['column_1']['button_font_color'] = "#ffffff";
$column['column_1']['button_hover_font_color'] = "#ffffff";
$column['column_1']['button_style_bold'] = 'bold';
$column['column_1']['button_style_italic'] = '';
$column['column_1']['button_style_decoration'] = '';
/* Button Font Settings */


$column['column_1']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>20</span><span class='arp_price_duration' style='font-size:18px;font-weight:normal;'> PER MONTH </span>";
$column['column_1']['price_label'] = "";
$column['column_1']['arp_header_shortcode'] = '';
$column['column_1']['body_text_alignment'] = 'left';
$column['column_1']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> sit dolor logortis';
$column['column_1']['rows']['row_0']['row_label'] = '';
$column['column_1']['rows']['row_0']['row_tooltip'] = '';
$column['column_1']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> Falli libris has id fa';
$column['column_1']['rows']['row_1']['row_label'] = '';
$column['column_1']['rows']['row_1']['row_tooltip'] = '';
$column['column_1']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_decoration'] = '';

$column['column_1']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> pertinax vel eum';
$column['column_1']['rows']['row_2']['row_label'] = '';
$column['column_1']['rows']['row_2']['row_tooltip'] = '';
$column['column_1']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> taleni nolui gniferu';
$column['column_1']['rows']['row_3']['row_label'] = '';
$column['column_1']['rows']['row_3']['row_tooltip'] = '';
$column['column_1']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_1']['button_size'] = '158';
$column['column_1']['button_height'] = '45';
$column['column_1']['button_type'] = 'Button';
$column['column_1']['button_text'] = 'Purchase';
$column['column_1']['button_url'] = '#';
$column['column_1']['button_s_size'] = '';
$column['column_1']['button_s_type'] = '';
$column['column_1']['button_s_text'] = '';
$column['column_1']['button_s_url'] = '';
$column['column_1']['s_is_new_window'] = '';
$column['column_1']['is_new_window'] = 0;

$column['column_1']['footer_content'] = '';
$column['column_1']['footer_content_position'] = 0;
$column['column_1']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_1']['footer_level_options_font_size'] = 12;
$column['column_1']['footer_level_options_font_color'] = '#ffffff';
$column['column_1']['footer_level_options_hover_font_color'] = '#ffffff';
$column['column_1']['footer_level_options_font_style_bold'] = '';
$column['column_1']['footer_level_options_font_style_italic'] = '';
$column['column_1']['footer_level_options_font_style_decoration'] = '';
$column['column_1']['is_post_variables'] = 0;
$column['column_1']['post_variables_content'] = '';

$column['column_2']['package_title'] = 'Business';
$column['column_2']['column_description'] = '"Aliquam euisod erat libero condimentum nisl hendrerit."';
$column['column_2']['custom_ribbon_txt'] = '';
$column['column_2']['column_width'] = '';
$column['column_2']['is_caption'] = 0;
$column['column_2']['column_highlight'] = 1;
/* Header Font Settings */
$column['column_2']['header_background_color'] = '#414045';
$column['column_2']['header_hover_background_color'] = '#51545d';
$column['column_2']['header_font_family'] = 'Roboto Condensed';
$column['column_2']['header_font_size'] = 28;
$column['column_2']['header_font_color'] = "#ffffff";
$column['column_2']['header_hover_font_color'] = "#ffffff";
$column['column_2']['header_style_bold'] = 'bold';
$column['column_2']['header_style_italic'] = '';
$column['column_2']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_2']['price_font_family'] = "Roboto Condensed";
$column['column_2']['price_font_size'] = 48;
$column['column_2']['price_font_color'] = "#ffffff";
$column['column_2']['price_hover_font_color'] = "#ffffff";
$column['column_2']['price_label_style_bold'] = 'bold';
$column['column_2']['price_label_style_italic'] = '';
$column['column_2']['price_label_style_decoration'] = '';

$column['column_2']['price_text_font_family'] = 'Roboto Condensed';
$column['column_2']['price_text_font_size'] = 18;
$column['column_2']['price_text_font_color'] = "#ffffff";
$column['column_2']['price_text_hover_font_color'] = "#ffffff";
$column['column_2']['price_text_style_bold'] = '';
$column['column_2']['price_text_style_italic'] = '';
$column['column_2']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_2']['column_description_font_family'] = 'Open Sans';
$column['column_2']['column_description_font_size'] = 14;
$column['column_2']['column_description_font_color'] = '#ffffff';
$column['column_2']['column_description_hover_font_color'] = '#ffffff';
$column['column_2']['column_desc_background_color'] = '#37363b';
$column['column_2']['column_desc_hover_background_color'] = '#46474c';
$column['column_2']['column_description_style_bold'] = '';
$column['column_2']['column_description_style_italic'] = '';
$column['column_2']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_2']['content_font_family'] = "Roboto Condensed";
$column['column_2']['content_font_size'] = 18;
$column['column_2']['content_font_color'] = "#ffffff";
$column['column_2']['content_even_font_color'] = "#ffffff";
$column['column_2']['content_hover_font_color'] = "#ffffff";
$column['column_2']['content_even_hover_font_color'] = "#ffffff";
$column['column_2']['content_odd_color'] = '#313035';
$column['column_2']['content_odd_hover_color'] = '#3e4044';
$column['column_2']['content_even_color'] = '#37363b';
$column['column_2']['content_even_hover_color'] = '#46474c';
$column['column_2']['body_li_style_bold'] = '';
$column['column_2']['body_li_style_italic'] = '';
$column['column_2']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Button Font Settings */
$column['column_2']['button_background_color'] = '#efa738';
$column['column_2']['button_hover_background_color'] = '#09b1f8';
$column['column_2']['button_font_family'] = "Roboto Condensed";
$column['column_2']['button_font_size'] = 20;
$column['column_2']['button_font_color'] = "#ffffff";
$column['column_2']['button_hover_font_color'] = "#ffffff";
$column['column_2']['button_style_bold'] = 'bold';
$column['column_2']['button_style_italic'] = '';
$column['column_2']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_2']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>50</span><span class='arp_price_duration' style='font-size:18px;'> PER MONTH </span>";
$column['column_2']['price_label'] = "";
$column['column_2']['arp_header_shortcode'] = '';
$column['column_2']['body_text_alignment'] = 'left';
$column['column_2']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> sit dolor logortis';
$column['column_2']['rows']['row_0']['row_label'] = '';
$column['column_2']['rows']['row_0']['row_tooltip'] = '';
$column['column_2']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> Falli libris has id fa';
$column['column_2']['rows']['row_1']['row_label'] = '';
$column['column_2']['rows']['row_1']['row_tooltip'] = '';
$column['column_2']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> pertinax vel eum';
$column['column_2']['rows']['row_2']['row_label'] = '';
$column['column_2']['rows']['row_2']['row_tooltip'] = '';
$column['column_2']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> taleni nolui gniferu';
$column['column_2']['rows']['row_3']['row_label'] = '';
$column['column_2']['rows']['row_3']['row_tooltip'] = '';
$column['column_2']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_2']['button_size'] = '158';
$column['column_2']['button_height'] = '45';
$column['column_2']['button_type'] = 'Button';
$column['column_2']['button_text'] = 'Purchase';
$column['column_2']['button_url'] = '#';
$column['column_2']['button_s_size'] = '';
$column['column_2']['button_s_type'] = '';
$column['column_2']['button_s_text'] = '';
$column['column_2']['button_s_url'] = '';
$column['column_2']['s_is_new_window'] = '';
$column['column_2']['is_new_window'] = 0;

$column['column_2']['footer_content'] = '';
$column['column_2']['footer_content_position'] = 0;
$column['column_2']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_2']['footer_level_options_font_size'] = 12;
$column['column_2']['footer_level_options_font_color'] = '#ffffff';
$column['column_2']['footer_level_options_hover_font_color'] = '#ffffff';
$column['column_2']['footer_level_options_font_style_bold'] = '';
$column['column_2']['footer_level_options_font_style_italic'] = '';
$column['column_2']['footer_level_options_font_style_decoration'] = '';
$column['column_2']['is_post_variables'] = 0;
$column['column_2']['post_variables_content'] = '';

$column['column_3']['package_title'] = 'Enterprise';
$column['column_3']['column_description'] = '"Aliquam euisod erat libero condimentum nisl hendrerit."';
$column['column_3']['custom_ribbon_txt'] = '';
$column['column_3']['column_width'] = '';
$column['column_3']['is_caption'] = 0;
$column['column_3']['column_highlight'] = '';
/* Header Font Settings */
$column['column_3']['header_background_color'] = '#414045';
$column['column_3']['header_hover_background_color'] = '#51545d';
$column['column_3']['header_font_family'] = 'Roboto Condensed';
$column['column_3']['header_font_size'] = 28;
$column['column_3']['header_font_color'] = "#ffffff";
$column['column_3']['header_hover_font_color'] = "#ffffff";
$column['column_3']['header_style_bold'] = 'bold';
$column['column_3']['header_style_italic'] = '';
$column['column_3']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_3']['price_font_family'] = "Roboto Condensed";
$column['column_3']['price_font_size'] = 48;
$column['column_3']['price_font_color'] = "#ffffff";
$column['column_3']['price_hover_font_color'] = "#ffffff";
$column['column_3']['price_label_style_bold'] = 'bold';
$column['column_3']['price_label_style_italic'] = '';
$column['column_3']['price_label_style_decoration'] = '';

$column['column_3']['price_text_font_family'] = 'Roboto Condensed';
$column['column_3']['price_text_font_size'] = 18;
$column['column_3']['price_text_font_color'] = "#ffffff";
$column['column_3']['price_text_hover_font_color'] = "#ffffff";
$column['column_3']['price_text_style_bold'] = '';
$column['column_3']['price_text_style_italic'] = '';
$column['column_3']['price_text_style_decoration'] = '';


/* Column Description Font Settings */
$column['column_3']['column_description_font_family'] = 'Open Sans';
$column['column_3']['column_description_font_size'] = 14;
$column['column_3']['column_description_font_color'] = '#ffffff';
$column['column_3']['column_description_hover_font_color'] = '#ffffff';
$column['column_3']['column_desc_background_color'] = '#37363b';
$column['column_3']['column_desc_hover_background_color'] = '#46474c';
$column['column_3']['column_description_style_bold'] = '';
$column['column_3']['column_description_style_italic'] = '';
$column['column_3']['column_description_style_decoration'] = '';
/* Column Description Font Settings */
/* Content Font Settings */
$column['column_3']['content_font_family'] = "Roboto Condensed";
$column['column_3']['content_font_size'] = 18;
$column['column_3']['content_font_color'] = "#ffffff";
$column['column_3']['content_even_font_color'] = "#ffffff";
$column['column_3']['content_hover_font_color'] = "#ffffff";
$column['column_3']['content_even_hover_font_color'] = "#ffffff";
$column['column_3']['content_odd_color'] = '#313035';
$column['column_3']['content_odd_hover_color'] = '#3e4044';
$column['column_3']['content_even_color'] = '#37363b';
$column['column_3']['content_even_hover_color'] = '#46474c';
$column['column_3']['body_li_style_bold'] = '';
$column['column_3']['body_li_style_italic'] = '';
$column['column_3']['body_li_style_decoration'] = '';
/* Content Font Settings */
/* Button Font Settings */
$column['column_3']['button_background_color'] = '#efa738';
$column['column_3']['button_hover_background_color'] = '#09b1f8';
$column['column_3']['button_font_family'] = "Roboto Condensed";
$column['column_3']['button_font_size'] = 20;
$column['column_3']['button_font_color'] = "#ffffff";
$column['column_3']['button_hover_font_color'] = "#ffffff";
$column['column_3']['button_style_bold'] = 'bold';
$column['column_3']['button_style_italic'] = '';
$column['column_3']['button_style_decoration'] = '';
/* Button Font Settings */


$column['column_3']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>69</span><span class='arp_price_duration' style='font-size:18px;font-weight:normal;'> PER MONTH </span>";
$column['column_3']['price_label'] = "";
$column['column_3']['arp_header_shortcode'] = '';
$column['column_3']['body_text_alignment'] = 'left';
$column['column_3']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> sit dolor logortis';
$column['column_3']['rows']['row_0']['row_label'] = '';
$column['column_3']['rows']['row_0']['row_tooltip'] = '';
$column['column_3']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> Falli libris has id fa';
$column['column_3']['rows']['row_1']['row_label'] = '';
$column['column_3']['rows']['row_1']['row_tooltip'] = '';
$column['column_3']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_decoration'] = '';

$column['column_3']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> pertinax vel eum';
$column['column_3']['rows']['row_2']['row_label'] = '';
$column['column_3']['rows']['row_2']['row_tooltip'] = '';
$column['column_3']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> taleni nolui gniferu';
$column['column_3']['rows']['row_3']['row_label'] = '';
$column['column_3']['rows']['row_3']['row_tooltip'] = '';
$column['column_3']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_3']['button_size'] = '158';
$column['column_3']['button_height'] = '45';
$column['column_3']['button_type'] = 'Button';
$column['column_3']['button_text'] = 'Purchase';
$column['column_3']['button_url'] = '#';
$column['column_3']['button_s_size'] = '';
$column['column_3']['button_s_type'] = '';
$column['column_3']['button_s_text'] = '';
$column['column_3']['button_s_url'] = '';
$column['column_3']['s_is_new_window'] = '';
$column['column_3']['is_new_window'] = 0;

$column['column_3']['footer_content'] = '';
$column['column_3']['footer_content_position'] = 0;
$column['column_3']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_3']['footer_level_options_font_size'] = 12;
$column['column_3']['footer_level_options_font_color'] = '#ffffff';
$column['column_3']['footer_level_options_hover_font_color'] = '#ffffff';
$column['column_3']['footer_level_options_font_style_bold'] = '';
$column['column_3']['footer_level_options_font_style_italic'] = '';
$column['column_3']['footer_level_options_font_style_decoration'] = '';
$column['column_3']['is_post_variables'] = 0;
$column['column_3']['post_variables_content'] = '';

$pt_columns = array('columns' => $column);
$opts = maybe_serialize($pt_columns);

$arpricelite_form->option_create($table_id, $opts);

unset($values);

/**
 * ARPricelite Template 23
 *
 * @since 2.0
 */
$values['name'] = 'ARPricelite Template 23';
$values['is_template'] = 1;
$values['ID'] = 23;
$values['status'] = 'published';
$values['template_name'] = 23;
$values['is_animated'] = 0;

$arp_pt_gen_options = array();

$arp_pt_template_settings = array();

$arp_pt_font_settings = array();

$arp_pt_general_settings = array();

$arp_header_font_settings = array();
$arp_price_font_settings = array();
$arp_content_font_settings = array();
$arp_button_font_settings = array();

$arp_pt_column_settings = array();



$arp_pt_button_settings = array();

$arp_pt_template_settings['template'] = 'arplitetemplate_26';
$arp_pt_template_settings['skin'] = 'blue';
$arp_pt_template_settings['template_type'] = 'normal';
$arp_pt_template_settings['features'] = array('column_description' => 'disable', 'custom_ribbon' => 'disable', 'button_position' => 'default', 'caption_style' => 'default', 'amount_style' => 'default', 'list_alignment' => 'default', 'ribbon_type' => 'default', 'column_description_style' => 'default', 'caption_title' => 'default', 'header_shortcode_type' => 'rounded_border', 'header_shortcode_position' => 'default', 'tooltip_position' => 'top', 'tooltip_style' => 'style_2', 'second_btn' => false, 'is_animated' => 0);

$arp_pt_general_settings['column_order'] = '["main_column_0","main_column_1","main_column_2"]';
$arp_pt_general_settings['reference_template'] = 'arplitetemplate_26';
$arp_pt_general_settings['user_edited_columns'] = '';
/* Toggle Content Changes */
$arp_pt_general_settings['enable_toggle_price'] = '0';
$arp_pt_general_settings['toggle_copy_data_to_other_step'] = '0';
$arp_pt_general_settings['arp_step_main'] = '2';
$arp_pt_general_settings['togglestep_yearly'] = 'Yearly';
$arp_pt_general_settings['togglestep_monthly'] = 'Monthly';
$arp_pt_general_settings['togglestep_quarterly'] = 'Quarterly';
$arp_pt_general_settings['setas_default_toggle'] = '0';
$arp_pt_general_settings['arp_toggle_main'] = '0';
$arp_pt_general_settings['toggle_active_color'] = '#404040';
$arp_pt_general_settings['toggle_inactive_color'] = '#ffffff';
$arp_pt_general_settings['toggle_active_text_color'] = '#ffffff';
$arp_pt_general_settings['toggle_button_font_color'] = '#000000';
$arp_pt_general_settings['toggle_title_font_color'] = '#000000';
$arp_pt_general_settings['toggle_label_content'] = 'Please Select Your Plan';
$arp_pt_general_settings['arp_label_position_main'] = '1';
$arp_pt_general_settings['toggle_main_color'] = '#E8E9EB';
$arp_pt_general_settings['toggle_title_font_family'] = 'Ubuntu';
$arp_pt_general_settings['toggle_title_font_size'] = 16;
$arp_pt_general_settings['toggle_title_font_style_bold'] = '';
$arp_pt_general_settings['toggle_title_font_style_italic'] = '';
$arp_pt_general_settings['toggle_title_font_style_decoration'] = '';
$arp_pt_general_settings['toggle_button_font_family'] = 'Ubuntu';
$arp_pt_general_settings['toggle_button_font_size'] = 16;
$arp_pt_general_settings['toggle_button_font_style_bold'] = '';
$arp_pt_general_settings['toggle_button_font_style_italic'] = '';
$arp_pt_general_settings['toggle_button_font_style_decoration'] = '';
/* Toggle Content Changes */

$arp_pt_button_settings['button_shadow_color'] = '#ffffff';
$arp_pt_button_settings['button_radius'] = 0;

$arp_pt_column_settings['column_space'] = 10;
$arp_pt_column_settings['min_row_height'] = 52;
$arp_pt_column_settings['column_highlight_on_hover'] = 'no_effect';
$arp_pt_column_settings['is_responsive'] = 1;
$arp_pt_column_settings['full_column_clickable'] = 0;
$arp_pt_column_settings['enable_hover_effect'] = 1;
$arp_pt_column_settings['hide_footer_global'] = 0;
$arp_pt_column_settings['hide_header_global'] = 0;
$arp_pt_column_settings['hide_price_global'] = 0;
$arp_pt_column_settings['hide_feature_global'] = 0;
$arp_pt_column_settings['hide_description_global'] = 0;
$arp_pt_column_settings['hide_header_shortcode_global'] = 0;
$arp_pt_column_settings['all_column_width'] = 280;
$arp_pt_column_settings['column_opacity'] = $arplite_mainoptionsarr['general_options']['column_opacity'][0];
$arp_pt_column_settings['column_border_radius_top_left'] = 15;
$arp_pt_column_settings['column_border_radius_top_right'] = 15;
$arp_pt_column_settings['column_border_radius_bottom_right'] = 15;
$arp_pt_column_settings['column_border_radius_bottom_left'] = 15;
$arp_pt_column_settings['column_wrapper_width_txtbox'] = 870;

$arp_pt_column_settings['global_button_border_width'] = 0;
$arp_pt_column_settings['global_button_border_type'] = 'solid';
$arp_pt_column_settings['global_button_border_color'] = '#c9c9c9ff';
$arp_pt_column_settings['global_button_border_radius_top_left'] = 0;
$arp_pt_column_settings['global_button_border_radius_top_right'] = 0;
$arp_pt_column_settings['global_button_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['global_button_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['arp_global_button_type'] = 'none';

$arp_pt_column_settings['arp_row_border_size'] = '0';
$arp_pt_column_settings['arp_row_border_type'] = 'solid';
$arp_pt_column_settings['arp_row_border_color'] = '#c9c9c9ff';

$arp_pt_column_settings['arp_column_border_size'] = '0';
$arp_pt_column_settings['arp_column_border_type'] = 'solid';
$arp_pt_column_settings['arp_column_border_color'] = '#e3e3e3ff';

$arp_pt_column_settings['arp_column_border_left'] = 1;
$arp_pt_column_settings['arp_column_border_right'] = 1;
$arp_pt_column_settings['arp_column_border_top'] = 1;
$arp_pt_column_settings['arp_column_border_bottom'] = 1;

$arp_pt_column_settings['display_col_mobile'] = 1;
$arp_pt_column_settings['display_col_tablet'] = 3;

$arp_pt_column_settings['column_box_shadow_effect'] = 'shadow_style_none';
$arp_pt_column_settings['hide_blank_rows'] = 'no';
$arp_pt_column_settings['header_font_family_global'] = 'Open Sans';
$arp_pt_column_settings['header_font_size_global'] = 30;
$arp_pt_column_settings['arp_header_text_alignment'] = 'center';
$arp_pt_column_settings['arp_header_text_bold_global'] = '';
$arp_pt_column_settings['arp_header_text_italic_global'] = '';
$arp_pt_column_settings['arp_header_text_decoration_global'] = '';
$arp_pt_column_settings['price_font_family_global'] = '';
$arp_pt_column_settings['price_font_size_global'] = '';
$arp_pt_column_settings['arp_price_text_alignment'] = '';
$arp_pt_column_settings['arp_price_text_bold_global'] = '';
$arp_pt_column_settings['arp_price_text_italic_global'] = '';
$arp_pt_column_settings['arp_price_text_decoration_global'] = '';
$arp_pt_column_settings['body_font_family_global'] = 'Open Sans';
$arp_pt_column_settings['body_font_size_global'] = 18;
$arp_pt_column_settings['arp_body_text_alignment'] = 'center';
$arp_pt_column_settings['arp_body_text_bold_global'] = '';
$arp_pt_column_settings['arp_body_text_italic_global'] = '';
$arp_pt_column_settings['arp_body_text_decoration_global'] = '';
$arp_pt_column_settings['footer_font_family_global'] = '';
$arp_pt_column_settings['footer_font_size_global'] = '';
$arp_pt_column_settings['arp_footer_text_alignment'] = '';
$arp_pt_column_settings['arp_footer_text_bold_global'] = '';
$arp_pt_column_settings['arp_footer_text_italic_global'] = '';
$arp_pt_column_settings['arp_footer_text_decoration_global'] = '';
$arp_pt_column_settings['button_font_family_global'] = 'Roboto';
$arp_pt_column_settings['button_font_size_global'] = 20;
$arp_pt_column_settings['arp_button_text_alignment'] = 'center';
$arp_pt_column_settings['arp_button_text_bold_global'] = '';
$arp_pt_column_settings['arp_button_text_italic_global'] = '';
$arp_pt_column_settings['arp_button_text_decoration_global'] = '';
$arp_pt_column_settings['description_font_family_global'] = '';
$arp_pt_column_settings['description_font_size_global'] = '';
$arp_pt_column_settings['arp_description_text_alignment'] = '';
$arp_pt_column_settings['arp_description_text_bold_global'] = '';
$arp_pt_column_settings['arp_description_text_italic_global'] = '';
$arp_pt_column_settings['arp_description_text_decoration_global'] = '';



$arp_pt_font_settings['header_fonts'] = $arp_header_font_settings;
$arp_pt_font_settings['price_fonts'] = $arp_price_font_settings;
$arp_pt_font_settings['price_text_fonts'] = $arp_price_text_font_settings;
$arp_pt_font_settings['content_fonts'] = $arp_content_font_settings;
$arp_pt_font_settings['button_fonts'] = $arp_button_font_settings;

$arp_pt_gen_options = array('template_setting' => $arp_pt_template_settings, 'font_settings' => $arp_pt_font_settings, 'column_settings' => $arp_pt_column_settings, 'general_settings' => $arp_pt_general_settings, 'button_settings' => $arp_pt_button_settings);

$arp_pt_custom_skin_array = array();

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

$arp_pt_gen_options['custom_skin_colors'] = $arp_pt_custom_skin_array;

$values['options'] = maybe_serialize($arp_pt_gen_options);

$table_id = $arpricelite_form->create($values);

$pt_columns = array();

$column = array();

$column['column_0']['shortcode_background_color'] = '#2fb8ff';
$column['column_0']['shortcode_font_color'] = '#ffffff';
$column['column_0']['shortcode_hover_background_color'] = '#2fb8ff';
$column['column_0']['shortcode_hover_font_color'] = '#2fb8ff';
$column['column_0']['arp_shortcode_customization_style'] = 'rounded';
$column['column_0']['arp_shortcode_customization_size'] = 'large';
$column['column_0']['package_title'] = 'John Smith <span style="text-transform:uppercase;font-size:18px;display:block;">London</span>';
$column['column_0']['column_description'] = '';
$column['column_0']['custom_ribbon_txt'] = '';
$column['column_0']['column_width'] = '';
$column['column_0']['is_caption'] = 0;
$column['column_0']['column_highlight'] = '';
$column['column_0']['column_background_color'] = "#2B2E37";
$column['column_0']['column_hover_background_color'] = "#2B2E37";

/* Header Font Settings */
$column['column_0']['header_background_color'] = '#2FB8FF';
$column['column_0']['header_hover_background_color'] = '#08090B';
$column['column_0']['header_font_family'] = 'Open Sans';
$column['column_0']['header_font_size'] = 30;
$column['column_0']['header_font_color'] = "#ffffff";
$column['column_0']['header_hover_font_color'] = "#ffffff";
$column['column_0']['header_style_bold'] = '';
$column['column_0']['header_style_italic'] = '';
$column['column_0']['header_style_decoration'] = '';
/* Header Font Settings */

$column['column_0']['price_font_family'] = "Lato";
$column['column_0']['price_font_size'] = 40;
$column['column_0']['price_font_color'] = "#ffffff";
$column['column_0']['price_hover_font_color'] = "#000000";
$column['column_0']['price_label_style_bold'] = 'bold';
$column['column_0']['price_label_style_italic'] = '';
$column['column_0']['price_label_style_decoration'] = '';

$column['column_0']['price_text_font_family'] = 'Lato';
$column['column_0']['price_text_font_size'] = 18;
$column['column_0']['price_text_font_color'] = "#ffffff";
$column['column_0']['price_text_hover_font_color'] = "#000000";
$column['column_0']['price_text_style_bold'] = 'bold';
$column['column_0']['price_text_style_italic'] = '';
$column['column_0']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_0']['column_description_font_family'] = 'Arial';
$column['column_0']['column_description_font_size'] = 13;
$column['column_0']['column_description_font_color'] = '#7c7c7c';
$column['column_0']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_0']['column_description_style_bold'] = '';
$column['column_0']['column_description_style_italic'] = '';
$column['column_0']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_0']['content_font_family'] = "Open Sans";
$column['column_0']['content_font_size'] = 18;
$column['column_0']['content_font_color'] = "#ffffff";
$column['column_0']['content_even_font_color'] = "#ffffff";
$column['column_0']['content_hover_font_color'] = "#2FB8FF";
$column['column_0']['content_even_hover_font_color'] = "#2FB8FF";
$column['column_0']['body_li_style_bold'] = '';
$column['column_0']['body_li_style_italic'] = '';
$column['column_0']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Content Label Font Settings */
$column['column_0']['content_label_font_family'] = 'Arial';
$column['column_0']['content_label_font_size'] = 15;
$column['column_0']['content_label_font_color'] = '#2a2e31';
$column['column_0']['content_label_hover_font_color'] = '#2a2e31';
$column['column_0']['body_label_style_bold'] = 'bold';
$column['column_0']['body_label_style_italic'] = '';
$column['column_0']['body_label_style_decoration'] = '';
/* Content Label Font Settings */

/* Button Font Settings */
$column['column_0']['button_background_color'] = '#2fb8ff';
$column['column_0']['button_hover_background_color'] = '#272727';
$column['column_0']['button_font_family'] = "Roboto";
$column['column_0']['button_font_size'] = 20;
$column['column_0']['button_font_color'] = "#ffffff";
$column['column_0']['button_hover_font_color'] = "#ffffff";
$column['column_0']['button_style_bold'] = '';
$column['column_0']['button_style_italic'] = '';
$column['column_0']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_0']['price_text'] = "";
$column['column_0']['price_label'] = "";
$column['column_0']['arp_header_shortcode'] = "<div style='background:url(" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/arp_26_img_1.png) no-repeat 0 0;background-size:cover;width:100%;height:100%;'></div>";
$column['column_0']['body_text_alignment'] = 'center';
$column['column_0']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_0']['row_description'] = 'I am a Model in London <br/> from 5 Years';
$column['column_0']['rows']['row_0']['row_label'] = '';
$column['column_0']['rows']['row_0']['row_tooltip'] = '';
$column['column_0']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_1']['row_description'] = '<br/>';
$column['column_0']['rows']['row_1']['row_label'] = '';
$column['column_0']['rows']['row_1']['row_tooltip'] = '';
$column['column_0']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_2']['row_description'] = 'Follow me on <br/> Instagram & Twitter';
$column['column_0']['rows']['row_2']['row_label'] = '';
$column['column_0']['rows']['row_2']['row_tooltip'] = '';
$column['column_0']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_0']['button_size'] = 'Medium';
$column['column_0']['button_height'] = 'Medium';
$column['column_0']['button_type'] = 'Button';
$column['column_0']['button_text'] = 'Contact Me';
$column['column_0']['button_url'] = '#';
$column['column_0']['button_s_size'] = '';
$column['column_0']['button_s_type'] = '';
$column['column_0']['button_s_text'] = '';
$column['column_0']['button_s_url'] = '';
$column['column_0']['s_is_new_window'] = '';
$column['column_0']['is_new_window'] = 0;

$column['column_0']['footer_content'] = '';
$column['column_0']['footer_content_position'] = 0;
$column['column_0']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_0']['footer_level_options_font_size'] = 12;
$column['column_0']['footer_level_options_font_color'] = '#ffffff';
$column['column_0']['footer_level_options_hover_font_color'] = '#000000';
$column['column_0']['footer_level_options_font_style_bold'] = '';
$column['column_0']['footer_level_options_font_style_italic'] = '';
$column['column_0']['footer_level_options_font_style_decoration'] = '';

$column['column_0']['is_post_variables'] = 0;
$column['column_0']['post_variables_content'] = '';


$column['column_1']['shortcode_background_color'] = '#2fb8ff';
$column['column_1']['shortcode_font_color'] = '#ffffff';
$column['column_1']['shortcode_hover_background_color'] = '#2fb8ff';
$column['column_1']['shortcode_hover_font_color'] = '#2fb8ff';
$column['column_1']['arp_shortcode_customization_style'] = 'rounded';
$column['column_1']['arp_shortcode_customization_size'] = 'large';
$column['column_1']['package_title'] = 'Jaceb Haden  <span style="text-transform:uppercase;font-size:18px;display:block;">London</span>';
$column['column_1']['column_description'] = '';
$column['column_1']['custom_ribbon_txt'] = '';
$column['column_1']['column_width'] = '';
$column['column_1']['is_caption'] = 0;
$column['column_1']['column_highlight'] = 1;
$column['column_1']['column_background_color'] = "#2B2E37";
$column['column_1']['column_hover_background_color'] = "#2B2E37";

/* Header Font Settings */
$column['column_1']['header_font_family'] = 'Open Sans';
$column['column_1']['header_font_size'] = 30;
$column['column_1']['header_font_color'] = "#ffffff";
$column['column_1']['header_hover_font_color'] = "#ffffff";
$column['column_1']['header_style_bold'] = '';
$column['column_1']['header_style_italic'] = '';
$column['column_1']['header_style_decoration'] = '';
$column['column_1']['header_background_color'] = '#2FB8FF';
$column['column_1']['header_hover_background_color'] = '#08090B';
/* Header Font Settings */

$column['column_1']['price_font_family'] = "Lato";
$column['column_1']['price_font_size'] = 40;
$column['column_1']['price_font_color'] = "#ffffff";
$column['column_1']['price_hover_font_color'] = "#000000";
$column['column_1']['price_label_style_bold'] = 'bold';
$column['column_1']['price_label_style_italic'] = '';
$column['column_1']['price_label_style_decoration'] = '';

$column['column_1']['price_text_font_family'] = 'Lato';
$column['column_1']['price_text_font_size'] = 18;
$column['column_1']['price_text_font_color'] = "#ffffff";
$column['column_1']['price_text_hover_font_color'] = "#000000";
$column['column_1']['price_text_style_bold'] = 'bold';
$column['column_1']['price_text_style_italic'] = '';
$column['column_1']['price_text_style_decoration'] = '';

/* Column Description Font Settings */
$column['column_1']['column_description_font_family'] = 'Arial';
$column['column_1']['column_description_font_size'] = 13;
$column['column_1']['column_description_font_color'] = '#7c7c7c';
$column['column_1']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_1']['column_description_style_bold'] = '';
$column['column_1']['column_description_style_italic'] = '';
$column['column_1']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_1']['content_font_family'] = "Open Sans";
$column['column_1']['content_font_size'] = 18;
$column['column_1']['content_font_color'] = "#ffffff";
$column['column_1']['content_even_font_color'] = "#ffffff";
$column['column_1']['content_hover_font_color'] = "#2fb8ff";
$column['column_1']['content_even_hover_font_color'] = "#2fb8ff";
$column['column_1']['body_li_style_bold'] = '';
$column['column_1']['body_li_style_italic'] = '';
$column['column_1']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Content Label Font Settings */
$column['column_1']['content_label_font_family'] = 'Arial';
$column['column_1']['content_label_font_size'] = 15;
$column['column_1']['content_label_font_color'] = '#2a2e31';
$column['column_1']['content_label_hover_font_color'] = '#2a2e31';
$column['column_1']['body_label_style_bold'] = 'bold';
$column['column_1']['body_label_style_italic'] = '';
$column['column_1']['body_label_style_decoration'] = '';
/* Content Label Font Settings */

/* Button Font Settings */
$column['column_1']['button_background_color'] = '#2fb8ff';
$column['column_1']['button_hover_background_color'] = '#272727';
$column['column_1']['button_font_family'] = "Roboto";
$column['column_1']['button_font_size'] = 20;
$column['column_1']['button_font_color'] = "#ffffff";
$column['column_1']['button_hover_font_color'] = "#ffffff";
$column['column_1']['button_style_bold'] = '';
$column['column_1']['button_style_italic'] = '';
$column['column_1']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_1']['price_text'] = "";
$column['column_1']['price_label'] = "";
$column['column_1']['arp_header_shortcode'] = "<div style='background:url(" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/arp_26_img_2.png) no-repeat 0 0;background-size:cover;width:100%;height:100%;'></div>";
$column['column_1']['body_text_alignment'] = 'center';
$column['column_1']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_0']['row_description'] = 'I am a Designer in London <br/> from 9 Years';
$column['column_1']['rows']['row_0']['row_label'] = '';
$column['column_1']['rows']['row_0']['row_tooltip'] = '';
$column['column_1']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_1']['row_description'] = '<br/>';
$column['column_1']['rows']['row_1']['row_label'] = '';
$column['column_1']['rows']['row_1']['row_tooltip'] = '';
$column['column_1']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_2']['row_description'] = 'Follow me on <br/> Instagram & Twitter';
$column['column_1']['rows']['row_2']['row_label'] = '';
$column['column_1']['rows']['row_2']['row_tooltip'] = '';
$column['column_1']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_1']['button_size'] = 'Medium';
$column['column_1']['button_height'] = 'Medium';
$column['column_1']['button_type'] = 'Button';
$column['column_1']['button_text'] = 'Contact Me';
$column['column_1']['button_url'] = '#';
$column['column_1']['is_new_window'] = 0;


$column['column_1']['footer_content'] = '';
$column['column_1']['footer_content_position'] = 0;
$column['column_1']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_1']['footer_level_options_font_size'] = 12;
$column['column_1']['footer_level_options_font_color'] = '#ffffff';
$column['column_1']['footer_level_options_hover_font_color'] = '#000000';
$column['column_1']['footer_level_options_font_style_bold'] = '';
$column['column_1']['footer_level_options_font_style_italic'] = '';
$column['column_1']['footer_level_options_font_style_decoration'] = '';
$column['column_1']['is_post_variables'] = 0;
$column['column_1']['post_variables_content'] = '';

$column['column_2']['shortcode_background_color'] = '#2fb8ff';
$column['column_2']['shortcode_font_color'] = '#ffffff';
$column['column_2']['shortcode_hover_background_color'] = '#2fb8ff';
$column['column_2']['shortcode_hover_font_color'] = '#2fb8ff';
$column['column_2']['arp_shortcode_customization_style'] = 'rounded';
$column['column_2']['arp_shortcode_customization_size'] = 'large';
$column['column_2']['package_title'] = 'Jason Carter <span style="text-transform:uppercase;font-size:18px;display:block;">London</span>';
$column['column_2']['column_description'] = '';
$column['column_2']['custom_ribbon_txt'] = '';
$column['column_2']['column_width'] = '';
$column['column_2']['is_caption'] = 0;
$column['column_2']['column_highlight'] = '';
$column['column_2']['column_background_color'] = "#2B2E37";
$column['column_2']['column_hover_background_color'] = "#2B2E37";

/* Header Font Settings */
$column['column_2']['header_font_family'] = 'Open Sans';
$column['column_2']['header_font_size'] = 30;
$column['column_2']['header_font_color'] = "#ffffff";
$column['column_2']['header_hover_font_color'] = "#ffffff";
$column['column_2']['header_style_bold'] = '';
$column['column_2']['header_style_italic'] = '';
$column['column_2']['header_style_decoration'] = '';
$column['column_2']['header_background_color'] = '#2FB8FF';
$column['column_2']['header_hover_background_color'] = '#08090B';
/* Header Font Settings */


$column['column_2']['price_font_family'] = "Lato";
$column['column_2']['price_font_size'] = 40;
$column['column_2']['price_font_color'] = "#ffffff";
$column['column_2']['price_hover_font_color'] = "#000000";
$column['column_2']['price_label_style_bold'] = 'bold';
$column['column_2']['price_label_style_italic'] = '';
$column['column_2']['price_label_style_decoration'] = '';

$column['column_2']['price_text_font_family'] = 'Lato';
$column['column_2']['price_text_font_size'] = 18;
$column['column_2']['price_text_font_color'] = "#ffffff";
$column['column_2']['price_text_hover_font_color'] = "#000000";
$column['column_2']['price_text_style_bold'] = 'bold';
$column['column_2']['price_text_style_italic'] = '';
$column['column_2']['price_text_style_decoration'] = '';


/* Column Description Font Settings */
$column['column_2']['column_description_font_family'] = 'Arial';
$column['column_2']['column_description_font_size'] = 18;
$column['column_2']['column_description_font_color'] = '#7c7c7c';
$column['column_2']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_2']['column_description_style_bold'] = '';
$column['column_2']['column_description_style_italic'] = '';
$column['column_2']['column_description_style_decoration'] = '';
/* Column Description Font Settings */

/* Content Font Settings */
$column['column_2']['content_font_family'] = "Open Sans";
$column['column_2']['content_font_size'] = 18;
$column['column_2']['content_font_color'] = "#ffffff";
$column['column_2']['content_even_font_color'] = "#ffffff";
$column['column_2']['content_hover_font_color'] = "#2fb8ff";
$column['column_2']['content_even_hover_font_color'] = "#2fb8ff";
$column['column_2']['body_li_style_bold'] = '';
$column['column_2']['body_li_style_italic'] = '';
$column['column_2']['body_li_style_decoration'] = '';
/* Content Font Settings */

/* Content Label Font Settings */
$column['column_2']['content_label_font_family'] = 'Arial';
$column['column_2']['content_label_font_size'] = 15;
$column['column_2']['content_label_font_color'] = '#2a2e31';
$column['column_2']['content_label_hover_font_color'] = '#2a2e31';
$column['column_2']['body_label_style_bold'] = 'bold';
$column['column_2']['body_label_style_italic'] = '';
$column['column_2']['body_label_style_decoration'] = '';
/* Content Label Font Settings */

/* Button Font Settings */
$column['column_2']['button_background_color'] = '#2fb8ff';
$column['column_2']['button_hover_background_color'] = '#272727';
$column['column_2']['button_font_family'] = "Roboto";
$column['column_2']['button_font_size'] = 22;
$column['column_2']['button_font_color'] = "#ffffff";
$column['column_2']['button_hover_font_color'] = "#ffffff";
$column['column_2']['button_style_bold'] = '';
$column['column_2']['button_style_italic'] = '';
$column['column_2']['button_style_decoration'] = '';
/* Button Font Settings */

$column['column_2']['price_text'] = "";
$column['column_2']['price_label'] = "";
$column['column_2']['arp_header_shortcode'] = "<div style='background:url(" . ARPLITE_PRICINGTABLE_IMAGES_URL . "/arp_26_img_3.png) no-repeat 0 0;background-size:cover;width:100%;height:100%;'></div>";
$column['column_2']['body_text_alignment'] = 'center';
$column['column_2']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_0']['row_description'] = 'I am a makup artist in London <br/> from 15 Years';
$column['column_2']['rows']['row_0']['row_label'] = '';
$column['column_2']['rows']['row_0']['row_tooltip'] = '';
$column['column_2']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_1']['row_description'] = '<br/>';
$column['column_2']['rows']['row_1']['row_label'] = '';
$column['column_2']['rows']['row_1']['row_tooltip'] = '';
$column['column_2']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_2']['row_description'] = 'Follow me on <br/> Instagram & Twitter';
$column['column_2']['rows']['row_2']['row_label'] = '';
$column['column_2']['rows']['row_2']['row_tooltip'] = '';
$column['column_2']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_2']['button_size'] = 'Medium';
$column['column_2']['button_height'] = 'Medium';
$column['column_2']['button_type'] = 'Button';
$column['column_2']['button_text'] = 'Contact Me';
$column['column_2']['button_url'] = '#';
$column['column_2']['button_s_size'] = '';
$column['column_2']['button_s_type'] = '';
$column['column_2']['button_s_text'] = '';
$column['column_2']['button_s_url'] = '';
$column['column_2']['s_is_new_window'] = '';
$column['column_2']['is_new_window'] = 0;

$column['column_2']['footer_content'] = '';
$column['column_2']['footer_content_position'] = 0;
$column['column_2']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_2']['footer_level_options_font_size'] = 12;
$column['column_2']['footer_level_options_font_color'] = '#ffffff';
$column['column_2']['footer_level_options_hover_font_color'] = '#000000';
$column['column_2']['footer_level_options_font_style_bold'] = '';
$column['column_2']['footer_level_options_font_style_italic'] = '';
$column['column_2']['footer_level_options_font_style_decoration'] = '';
$column['column_2']['is_post_variables'] = 0;
$column['column_2']['post_variables_content'] = '';

$pt_columns = array('columns' => $column);
$opts = maybe_serialize($pt_columns);

$arpricelite_form->option_create($table_id, $opts);

unset($values);



/**
 * ARPricelite Template 2
 *
 * @since 2.0
 */
/*if( isset($arp_update_table) && $arp_update_table == 1 ){
	$values['ID'] = 2;
}*/
$values['name'] = 'ARPricelite Template 2';
$values['is_template'] = 1;
$values['ID'] = 2;
$values['status'] = 'published';
$values['template_name'] = 2;
$values['is_animated'] = 0;

$arp_pt_gen_options = array();
$arp_pt_template_settings = array();
$arp_pt_font_settings = array();
$arp_pt_general_settings = array();
$arp_header_font_settings = array();
$arp_price_font_settings = array();
$arp_content_font_settings = array();
$arp_button_font_settings = array();

$arp_pt_column_settings = array();

$arp_pt_column_animation = array();

$arp_pt_tooltip_settings = array();

$arp_pt_button_settings = array();

$arp_pt_template_settings['template'] = 'arplitetemplate_2';
$arp_pt_template_settings['skin'] = 'lightviolet';
$arp_pt_template_settings['template_type'] = 'normal';
$arp_pt_template_settings['features'] = array('column_description' => 'disable', 'custom_ribbon' => 'disable', 'button_position' => 'default', 'caption_style' => 'default', 'amount_style' => 'default', 'list_alignment' => 'default', 'ribbon_type' => 'default', 'column_description_style' => 'default', 'caption_title' => 'default', 'header_shortcode_type' => 'rounded_border', 'header_shortcode_position' => 'default', 'tooltip_position' => 'top', 'tooltip_style' => 'style_2', 'second_btn' => false, 'is_animated' => 0);

$arp_pt_general_settings['column_order'] = '["main_column_0","main_column_1","main_column_2","main_column_3"]';
$arp_pt_general_settings['reference_template'] = 'arplitetemplate_2';
$arp_pt_general_settings['user_edited_columns'] = '';

$arp_pt_general_settings['enable_toggle_price'] = '0';
$arp_pt_general_settings['toggle_copy_data_to_other_step'] = '0';
$arp_pt_general_settings['arp_step_main'] = '2';
$arp_pt_general_settings['togglestep_yearly'] = 'Yearly';
$arp_pt_general_settings['togglestep_monthly'] = 'Monthly';
$arp_pt_general_settings['togglestep_quarterly'] = 'Quarterly';
$arp_pt_general_settings['togglestep_weekly'] = 'Weekly';
$arp_pt_general_settings['togglestep_daily'] = 'Daily';
$arp_pt_general_settings['setas_default_toggle'] = '0';
$arp_pt_general_settings['arp_toggle_main'] = '0';
$arp_pt_general_settings['toggle_active_color'] = '#404040';
$arp_pt_general_settings['toggle_inactive_color'] = '#ffffff';
$arp_pt_general_settings['toggle_active_text_color'] = '#ffffff';
$arp_pt_general_settings['toggle_button_font_color'] = '#000000';
$arp_pt_general_settings['toggle_title_font_color'] = '#000000';
$arp_pt_general_settings['toggle_label_content'] = 'Please Select Your Plan';
$arp_pt_general_settings['arp_label_position_main'] = '1';
$arp_pt_general_settings['toggle_main_color'] = '#E8E9EB';
$arp_pt_general_settings['toggle_title_font_family'] = 'Ubuntu';
$arp_pt_general_settings['toggle_title_font_size'] = 16;
$arp_pt_general_settings['toggle_title_font_style_bold'] = '';
$arp_pt_general_settings['toggle_title_font_style_italic'] = '';
$arp_pt_general_settings['toggle_title_font_style_decoration'] = '';
$arp_pt_general_settings['toggle_button_font_family'] = 'Ubuntu';
$arp_pt_general_settings['toggle_button_font_size'] = 16;
$arp_pt_general_settings['toggle_button_font_style_bold'] = '';
$arp_pt_general_settings['toggle_button_font_style_italic'] = '';
$arp_pt_general_settings['toggle_button_font_style_decoration'] = '';


$arp_pt_button_settings['button_shadow_color'] = '#ffffff';
$arp_pt_button_settings['button_radius'] = 0;

$arp_pt_column_settings['column_space'] = 10;
$arp_pt_column_settings['min_row_height'] = 37;
$arp_pt_column_settings['column_highlight_on_hover'] = 'shadow_effect';
$arp_pt_column_settings['is_responsive'] = 1;
$arp_pt_column_settings['full_column_clickable'] = 0;
$arp_pt_column_settings['enable_hover_effect'] = 1;
$arp_pt_column_settings['hide_footer_global'] = 0;
$arp_pt_column_settings['hide_header_global'] = 0;
$arp_pt_column_settings['hide_price_global'] = 0;
$arp_pt_column_settings['hide_feature_global'] = 0;
$arp_pt_column_settings['hide_description_global'] = 0;
$arp_pt_column_settings['hide_header_shortcode_global'] = 0;
$arp_pt_column_settings['all_column_width'] = 240;
$arp_pt_column_settings['column_opacity'] = $arplite_mainoptionsarr['general_options']['column_opacity'][0];
$arp_pt_column_settings['column_border_radius_top_left'] = 7;
$arp_pt_column_settings['column_border_radius_top_right'] = 7;
$arp_pt_column_settings['column_border_radius_bottom_right'] = 7;
$arp_pt_column_settings['column_border_radius_bottom_left'] = 7;
$arp_pt_column_settings['column_wrapper_width_txtbox'] = 1000;

$arp_pt_column_settings['global_button_border_width'] = 0;
$arp_pt_column_settings['global_button_border_type'] = 'solid';
$arp_pt_column_settings['global_button_border_color'] = '#c9c9c9ff';
$arp_pt_column_settings['global_button_border_radius_top_left'] = 3;
$arp_pt_column_settings['global_button_border_radius_top_right'] = 3;
$arp_pt_column_settings['global_button_border_radius_bottom_left'] = 3;
$arp_pt_column_settings['global_button_border_radius_bottom_right'] = 3;
$arp_pt_column_settings['arp_global_button_type'] = 'shadow';

$arp_pt_column_settings['arp_row_border_size'] = '0';
$arp_pt_column_settings['arp_row_border_type'] = 'solid';
$arp_pt_column_settings['arp_row_border_color'] = '#c9c9c9ff';

$arp_pt_column_settings['arp_column_border_size'] = '0';
$arp_pt_column_settings['arp_column_border_type'] = 'solid';
$arp_pt_column_settings['arp_column_border_color'] = '#e3e3e3ff';

$arp_pt_column_settings['arp_column_border_left'] = 1;
$arp_pt_column_settings['arp_column_border_right'] = 1;
$arp_pt_column_settings['arp_column_border_top'] = 1;
$arp_pt_column_settings['arp_column_border_bottom'] = 1;

$arp_pt_column_settings['display_col_mobile'] = 1;
$arp_pt_column_settings['display_col_tablet'] = 3;
$arp_pt_column_settings['header_font_family_global'] = 'Lato';
$arp_pt_column_settings['header_font_size_global'] = 26;
$arp_pt_column_settings['arp_header_text_alignment'] = 'center';
$arp_pt_column_settings['arp_header_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_header_text_italic_global'] = '';
$arp_pt_column_settings['arp_header_text_decoration_global'] = '';
$arp_pt_column_settings['price_font_family_global'] = 'Lato';
$arp_pt_column_settings['price_font_size_global'] = 40;
$arp_pt_column_settings['arp_price_text_alignment'] = 'center';
$arp_pt_column_settings['arp_price_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_price_text_italic_global'] = '';
$arp_pt_column_settings['arp_price_text_decoration_global'] = '';
$arp_pt_column_settings['body_font_family_global'] = 'Lato';
$arp_pt_column_settings['body_font_size_global'] = 18;
$arp_pt_column_settings['arp_body_text_alignment'] = 'center';
$arp_pt_column_settings['arp_body_text_bold_global'] = '';
$arp_pt_column_settings['arp_body_text_italic_global'] = '';
$arp_pt_column_settings['arp_body_text_decoration_global'] = '';
$arp_pt_column_settings['footer_font_family_global'] = 'Open Sans Bold';
$arp_pt_column_settings['footer_font_size_global'] = 12;
$arp_pt_column_settings['arp_footer_text_alignment'] = 'center';
$arp_pt_column_settings['arp_footer_text_bold_global'] = '';
$arp_pt_column_settings['arp_footer_text_italic_global'] = '';
$arp_pt_column_settings['arp_footer_text_decoration_global'] = '';
$arp_pt_column_settings['button_font_family_global'] = 'Lato';
$arp_pt_column_settings['button_font_size_global'] = 22;
$arp_pt_column_settings['arp_button_text_alignment'] = 'center';
$arp_pt_column_settings['arp_button_text_bold_global'] = '';
$arp_pt_column_settings['arp_button_text_italic_global'] = '';
$arp_pt_column_settings['arp_button_text_decoration_global'] = '';
$arp_pt_column_settings['description_font_family_global'] = '';
$arp_pt_column_settings['description_font_size_global'] = '';
$arp_pt_column_settings['arp_description_text_alignment'] = 'center';
$arp_pt_column_settings['arp_description_text_bold_global'] = '';
$arp_pt_column_settings['arp_description_text_italic_global'] = '';
$arp_pt_column_settings['arp_description_text_decoration_global'] = '';
$arp_pt_column_settings['column_box_shadow_effect'] = 'shadow_style_none';
$arp_pt_column_settings['hide_blank_rows'] = 'no';
$arp_pt_column_animation['is_animation'] = 'no';
$arp_pt_column_animation['visible_column'] = 2;
$arp_pt_column_animation['scrolling_columns'] = 2;
$arp_pt_column_animation['navigation'] = 1;
$arp_pt_column_animation['autoplay'] = 1;
$arp_pt_column_animation['sliding_effect'] = 'slide';
$arp_pt_column_animation['transition_speed'] = 750;
$arp_pt_column_animation['navigation_style'] = $arplite_mainoptionsarr['general_options']['column_animation']['navigation_style'][1];
$arp_pt_column_animation['pagination'] = $arplite_mainoptionsarr['general_options']['column_animation']['pagination'];
$arp_pt_column_animation['pagination_style'] = $arplite_mainoptionsarr['general_options']['column_animation']['pagination_style'][1];
$arp_pt_column_animation['pagination_position'] = $arplite_mainoptionsarr['general_options']['column_animation']['pagination_position'][0];
$arp_pt_column_animation['easing_effect'] = $arplite_mainoptionsarr['general_options']['column_animation']['easing_effect'][0];
$arp_pt_column_animation['infinite'] = 0;
$arp_pt_column_animation['navi_nav_btn'] = 'navigation';
$arp_pt_column_animation['pagi_nav_btn'] = 'pagination_bottom';
$arp_pt_column_animation['sticky_caption'] = 0;

$arp_pt_tooltip_settings['background_color'] = '#2579eb';
$arp_pt_tooltip_settings['text_color'] = '#FFFFFF';
$arp_pt_tooltip_settings['animation'] = $arplite_mainoptionsarr['general_options']['tooltipsetting']['animation'][0];
$arp_pt_tooltip_settings['position'] = $arplite_mainoptionsarr['general_options']['tooltipsetting']['position'][0];
$arp_pt_tooltip_settings['style'] = $arplite_mainoptionsarr['general_options']['tooltipsetting']['style'][0];
$arp_pt_tooltip_settings['tooltip_width'] = '';
$arp_pt_tooltip_settings['tooltip_font_family'] = 'Open Sans Bold';
$arp_pt_tooltip_settings['tooltip_font_size'] = 18;
$arp_pt_tooltip_settings['tooltip_font_style'] = 'normal';
$arp_pt_tooltip_settings['tooltip_trigger_type'] = 'hover';
$arp_pt_tooltip_settings['tooltip_display_style'] = 'default';
$arp_pt_tooltip_settings['tooltip_informative_icon'] = '<i class="fas fa-info-circle arpsize-ico-14"></i>';
$arp_pt_font_settings['header_fonts'] = $arp_header_font_settings;
$arp_pt_font_settings['price_fonts'] = $arp_price_font_settings;
$arp_pt_font_settings['price_text_fonts'] = $arp_price_text_font_settings;
$arp_pt_font_settings['content_fonts'] = $arp_content_font_settings;
$arp_pt_font_settings['button_fonts'] = $arp_button_font_settings;

$arp_pt_gen_options = array('template_setting' => $arp_pt_template_settings, 'font_settings' => $arp_pt_font_settings, 'column_settings' => $arp_pt_column_settings, 'column_animation' => $arp_pt_column_animation, 'tooltip_settings' => $arp_pt_tooltip_settings, 'general_settings' => $arp_pt_general_settings, 'button_settings' => $arp_pt_button_settings);

$arp_pt_custom_skin_array = array();

$arp_pt_custom_skin_array['arp_header_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_column_bg_custom_color'] = "#6c62d3";
$arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_footer_content_bg_color'] = null;
$arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#ffffff";
$arp_pt_custom_skin_array['arp_column_bg_hover_color'] = "#F6F6F6";
$arp_pt_custom_skin_array['arp_button_bg_hover_color'] = "#6c62d3";
$arp_pt_custom_skin_array['arp_header_bg_hover_color'] = null;
$arp_pt_custom_skin_array['arp_price_bg_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = null;
$arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#6c62d3';
$arp_pt_custom_skin_array['arp_price_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = '#000000';
$arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = '#000000';
$arp_pt_custom_skin_array['arp_desc_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#000000';
$arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#000000';
$arp_pt_custom_skin_array['arp_footer_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = '#000000';
$arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#313234';
$arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_shortocode_background'] = '#ffffff';
$arp_pt_custom_skin_array['arp_shortocode_font_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_shortcode_bg_hover_color'] = '#6c62d3';
$arp_pt_custom_skin_array['arp_shortcode_font_hover_color'] = '#6c62d3';
$arp_pt_gen_options['custom_skin_colors'] = $arp_pt_custom_skin_array;


$values['options'] = maybe_serialize($arp_pt_gen_options);

$table_id = $arpricelite_form->create($values);

$pt_columns = array();

$column = array();


$column['column_0']['package_title'] = 'BASIC';
$column['column_0']['column_description'] = '';
$column['column_0']['custom_ribbon_txt'] = '';
$column['column_0']['column_width'] = '';
$column['column_0']['is_caption'] = 0;
$column['column_0']['column_highlight'] = '';
$column['column_0']['column_background_color'] = "#6c62d3";
$column['column_0']['column_hover_background_color'] = "#f6f6f6";


$column['column_0']['header_font_family'] = 'Lato';
$column['column_0']['header_font_size'] = 26;
$column['column_0']['header_font_color'] = "#ffffff";
$column['column_0']['header_hover_font_color'] = "#6c62d3";
$column['column_0']['header_style_bold'] = 'bold';
$column['column_0']['header_style_italic'] = '';
$column['column_0']['header_style_decoration'] = '';



$column['column_0']['price_font_family'] = "Lato";
$column['column_0']['price_font_size'] = 40;
$column['column_0']['price_font_color'] = "#ffffff";
$column['column_0']['price_hover_font_color'] = "#000000";
$column['column_0']['price_label_style_bold'] = 'bold';
$column['column_0']['price_label_style_italic'] = '';
$column['column_0']['price_label_style_decoration'] = '';

$column['column_0']['price_text_font_family'] = 'Lato';
$column['column_0']['price_text_font_size'] = 18;
$column['column_0']['price_text_font_color'] = "#ffffff";
$column['column_0']['price_text_hover_font_color'] = "#000000";
$column['column_0']['price_text_style_bold'] = 'bold';
$column['column_0']['price_text_style_italic'] = '';
$column['column_0']['price_text_style_decoration'] = '';



$column['column_0']['column_description_font_family'] = 'Arial';
$column['column_0']['column_description_font_size'] = 13;
$column['column_0']['column_description_font_color'] = '#7c7c7c';
$column['column_0']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_0']['column_description_style_bold'] = '';
$column['column_0']['column_description_style_italic'] = '';
$column['column_0']['column_description_style_decoration'] = '';



$column['column_0']['content_font_family'] = "Lato";
$column['column_0']['content_font_size'] = 18;
$column['column_0']['content_font_color'] = "#ffffff";
$column['column_0']['content_even_font_color'] = "#ffffff";
$column['column_0']['content_hover_font_color'] = "#000000";
$column['column_0']['content_even_hover_font_color'] = "#000000";
$column['column_0']['body_li_style_bold'] = '';
$column['column_0']['body_li_style_italic'] = '';
$column['column_0']['body_li_style_decoration'] = '';



$column['column_0']['content_label_font_family'] = 'Arial';
$column['column_0']['content_label_font_size'] = 15;
$column['column_0']['content_label_font_color'] = '#2a2e31';
$column['column_0']['content_label_hover_font_color'] = '#2a2e31';
$column['column_0']['body_label_style_bold'] = 'bold';
$column['column_0']['body_label_style_italic'] = '';
$column['column_0']['body_label_style_decoration'] = '';



$column['column_0']['button_background_color'] = '#ffffff';
$column['column_0']['button_hover_background_color'] = '#6c62d3';
$column['column_0']['button_font_family'] = "Lato";
$column['column_0']['button_font_size'] = 22;
$column['column_0']['button_font_color'] = "#313234";
$column['column_0']['button_hover_font_color'] = "#ffffff";
$column['column_0']['button_style_bold'] = '';
$column['column_0']['button_style_italic'] = '';
$column['column_0']['button_style_decoration'] = '';


$column['column_0']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>19.00</span><span class='arp_price_duration' style='font-size:18px;'> monthly </span>";
$column['column_0']['price_label'] = "";
$column['column_0']['arp_header_shortcode'] = "<i class='fas fa-file-alt arpsize-ico-48'></i>";
$column['column_0']['body_text_alignment'] = 'center';
$column['column_0']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_0']['row_description'] = '10 GB Disk Space';
$column['column_0']['rows']['row_0']['row_label'] = '';
$column['column_0']['rows']['row_0']['row_tooltip'] = '';
$column['column_0']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_1']['row_description'] = '5 Databases';
$column['column_0']['rows']['row_1']['row_label'] = '';
$column['column_0']['rows']['row_1']['row_tooltip'] = '';
$column['column_0']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_2']['row_description'] = '5 Domains';
$column['column_0']['rows']['row_2']['row_label'] = '';
$column['column_0']['rows']['row_2']['row_tooltip'] = '';
$column['column_0']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_0']['rows']['row_3']['row_description'] = 'No online Support';
$column['column_0']['rows']['row_3']['row_label'] = '';
$column['column_0']['rows']['row_3']['row_tooltip'] = '';
$column['column_0']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_0']['button_size'] = '158';
$column['column_0']['button_height'] = '48';
$column['column_0']['button_type'] = 'Button';
$column['column_0']['button_text'] = 'Buy Now';
$column['column_0']['button_url'] = '#';
$column['column_0']['button_s_size'] = '';
$column['column_0']['button_s_type'] = '';
$column['column_0']['button_s_text'] = '';
$column['column_0']['button_s_url'] = '';
$column['column_0']['s_is_new_window'] = '';
$column['column_0']['is_new_window'] = 0;

$column['column_0']['footer_content'] = '';
$column['column_0']['footer_content_position'] = 0;
$column['column_0']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_0']['footer_level_options_font_size'] = 12;
$column['column_0']['footer_level_options_font_color'] = '#ffffff';
$column['column_0']['footer_level_options_hover_font_color'] = '#000000';
$column['column_0']['footer_level_options_font_style_bold'] = '';
$column['column_0']['footer_level_options_font_style_italic'] = '';
$column['column_0']['footer_level_options_font_style_decoration'] = '';

$column['column_0']['is_post_variables'] = 0;
$column['column_0']['post_variables_content'] = '';

$column['column_0']['shortcode_background_color'] = '#ffffff';
$column['column_0']['shortcode_font_color'] = '#ffffff';
$column['column_0']['shortcode_hover_background_color'] = '#6c62d3';
$column['column_0']['shortcode_hover_font_color'] = '#6c62d3';
$column['column_0']['arp_shortcode_customization_style'] = 'rounded';
$column['column_0']['arp_shortcode_customization_size'] = 'medium';

$column['column_1']['shortcode_background_color'] = '#ffffff';
$column['column_1']['shortcode_font_color'] = '#ffffff';
$column['column_1']['shortcode_hover_background_color'] = '#6c62d3';
$column['column_1']['shortcode_hover_font_color'] = '#6c62d3';
$column['column_1']['arp_shortcode_customization_style'] = 'rounded';
$column['column_1']['arp_shortcode_customization_size'] = 'medium';
$column['column_1']['package_title'] = 'PERSONAL';
$column['column_1']['column_description'] = '';
$column['column_1']['custom_ribbon_txt'] = '';
$column['column_1']['column_width'] = '';
$column['column_1']['is_caption'] = 0;
$column['column_1']['column_highlight'] = '';
$column['column_1']['column_background_color'] = "#6c62d3";
$column['column_1']['column_hover_background_color'] = "#f6f6f6";


$column['column_1']['header_font_family'] = 'Lato';
$column['column_1']['header_font_size'] = 26;
$column['column_1']['header_font_color'] = "#ffffff";
$column['column_1']['header_hover_font_color'] = "#6c62d3";
$column['column_1']['header_style_bold'] = 'bold';
$column['column_1']['header_style_italic'] = '';
$column['column_1']['header_style_decoration'] = '';



$column['column_1']['price_font_family'] = "Lato";
$column['column_1']['price_font_size'] = 40;
$column['column_1']['price_font_color'] = "#ffffff";
$column['column_1']['price_hover_font_color'] = "#000000";
$column['column_1']['price_label_style_bold'] = 'bold';
$column['column_1']['price_label_style_italic'] = '';
$column['column_1']['price_label_style_decoration'] = '';

$column['column_1']['price_text_font_family'] = 'Lato';
$column['column_1']['price_text_font_size'] = 18;
$column['column_1']['price_text_font_color'] = "#ffffff";
$column['column_1']['price_text_hover_font_color'] = "#000000";
$column['column_1']['price_text_style_bold'] = 'bold';
$column['column_1']['price_text_style_italic'] = '';
$column['column_1']['price_text_style_decoration'] = '';



$column['column_1']['column_description_font_family'] = 'Arial';
$column['column_1']['column_description_font_size'] = 13;
$column['column_1']['column_description_font_color'] = '#7c7c7c';
$column['column_1']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_1']['column_description_style_bold'] = '';
$column['column_1']['column_description_style_italic'] = '';
$column['column_1']['column_description_style_decoration'] = '';



$column['column_1']['content_font_family'] = "Lato";
$column['column_1']['content_font_size'] = 18;
$column['column_1']['content_font_color'] = "#ffffff";
$column['column_1']['content_even_font_color'] = "#ffffff";
$column['column_1']['content_hover_font_color'] = "#000000";
$column['column_1']['content_even_hover_font_color'] = "#000000";
$column['column_1']['body_li_style_bold'] = '';
$column['column_1']['body_li_style_italic'] = '';
$column['column_1']['body_li_style_decoration'] = '';



$column['column_1']['content_label_font_family'] = 'Arial';
$column['column_1']['content_label_font_size'] = 15;
$column['column_1']['content_label_font_color'] = '#2a2e31';
$column['column_1']['content_label_hover_font_color'] = '#2a2e31';
$column['column_1']['body_label_style_bold'] = 'bold';
$column['column_1']['body_label_style_italic'] = '';
$column['column_1']['body_label_style_decoration'] = '';



$column['column_1']['button_background_color'] = '#ffffff';
$column['column_1']['button_hover_background_color'] = '#6c62d3';
$column['column_1']['button_font_family'] = "Lato";
$column['column_1']['button_font_size'] = 22;
$column['column_1']['button_font_color'] = "#313234";
$column['column_1']['button_hover_font_color'] = "#ffffff";
$column['column_1']['button_style_bold'] = '';
$column['column_1']['button_style_italic'] = '';
$column['column_1']['button_style_decoration'] = '';


$column['column_1']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>29.00</span><span class='arp_price_duration' style='font-size:18px;'> monthly </span>";
$column['column_1']['price_label'] = "";
$column['column_1']['arp_header_shortcode'] = "<i class='fas fa-user arpsize-ico-48'></i>";
$column['column_1']['body_text_alignment'] = 'center';
$column['column_1']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_0']['row_description'] = '50 GB Disk Space';
$column['column_1']['rows']['row_0']['row_label'] = '';
$column['column_1']['rows']['row_0']['row_tooltip'] = '';
$column['column_1']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_1']['row_description'] = '25 Databases';
$column['column_1']['rows']['row_1']['row_label'] = '';
$column['column_1']['rows']['row_1']['row_tooltip'] = '';
$column['column_1']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_2']['row_description'] = '25 Domains';
$column['column_1']['rows']['row_2']['row_label'] = '';
$column['column_1']['rows']['row_2']['row_tooltip'] = '';
$column['column_1']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_1']['rows']['row_3']['row_description'] = 'No online Support';
$column['column_1']['rows']['row_3']['row_label'] = '';
$column['column_1']['rows']['row_3']['row_tooltip'] = '';
$column['column_1']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_1']['button_size'] = '158';
$column['column_1']['button_height'] = '48';
$column['column_1']['button_type'] = 'Button';
$column['column_1']['button_text'] = 'Buy Now';
$column['column_1']['button_url'] = '#';
$column['column_1']['button_s_size'] = '';
$column['column_1']['button_s_type'] = '';
$column['column_1']['button_s_text'] = '';
$column['column_1']['button_s_url'] = '';
$column['column_1']['s_is_new_window'] = '';
$column['column_1']['is_new_window'] = 0;


$column['column_1']['footer_content'] = '';
$column['column_1']['footer_content_position'] = 0;
$column['column_1']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_1']['footer_level_options_font_size'] = 12;
$column['column_1']['footer_level_options_font_color'] = '#ffffff';
$column['column_1']['footer_level_options_hover_font_color'] = '#000000';
$column['column_1']['footer_level_options_font_style_bold'] = '';
$column['column_1']['footer_level_options_font_style_italic'] = '';
$column['column_1']['footer_level_options_font_style_decoration'] = '';
$column['column_1']['is_post_variables'] = 0;
$column['column_1']['post_variables_content'] = '';

$column['column_2']['shortcode_background_color'] = '#ffffff';
$column['column_2']['shortcode_font_color'] = '#ffffff';
$column['column_2']['shortcode_hover_background_color'] = '#6c62d3';
$column['column_2']['shortcode_hover_font_color'] = '#6c62d3';
$column['column_2']['arp_shortcode_customization_style'] = 'rounded';
$column['column_2']['arp_shortcode_customization_size'] = 'medium';
$column['column_2']['package_title'] = 'STANDARD';
$column['column_2']['column_description'] = '';
$column['column_2']['custom_ribbon_txt'] = '';
$column['column_2']['column_width'] = '';
$column['column_2']['is_caption'] = 0;
$column['column_2']['column_highlight'] = 1;
$column['column_2']['column_background_color'] = "#6c62d3";
$column['column_2']['column_hover_background_color'] = "#f6f6f6";


$column['column_2']['header_font_family'] = 'Lato';
$column['column_2']['header_font_size'] = 26;
$column['column_2']['header_font_color'] = "#ffffff";
$column['column_2']['header_hover_font_color'] = "#6c62d3";
$column['column_2']['header_style_bold'] = 'bold';
$column['column_2']['header_style_italic'] = '';
$column['column_2']['header_style_decoration'] = '';



$column['column_2']['price_font_family'] = "Lato";
$column['column_2']['price_font_size'] = 40;
$column['column_2']['price_font_color'] = "#ffffff";
$column['column_2']['price_hover_font_color'] = "#000000";
$column['column_2']['price_label_style_bold'] = 'bold';
$column['column_2']['price_label_style_italic'] = '';
$column['column_2']['price_label_style_decoration'] = '';

$column['column_2']['price_text_font_family'] = 'Lato';
$column['column_2']['price_text_font_size'] = 18;
$column['column_2']['price_text_font_color'] = "#ffffff";
$column['column_2']['price_text_hover_font_color'] = "#000000";
$column['column_2']['price_text_style_bold'] = 'bold';
$column['column_2']['price_text_style_italic'] = '';
$column['column_2']['price_text_style_decoration'] = '';



$column['column_2']['column_description_font_family'] = 'Arial';
$column['column_2']['column_description_font_size'] = 13;
$column['column_2']['column_description_font_color'] = '#7c7c7c';
$column['column_2']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_2']['column_description_style_bold'] = '';
$column['column_2']['column_description_style_italic'] = '';
$column['column_2']['column_description_style_decoration'] = '';



$column['column_2']['content_font_family'] = "Lato";
$column['column_2']['content_font_size'] = 18;
$column['column_2']['content_font_color'] = "#ffffff";
$column['column_2']['content_even_font_color'] = "#ffffff";
$column['column_2']['content_hover_font_color'] = "#000000";
$column['column_2']['content_even_hover_font_color'] = "#000000";
$column['column_2']['body_li_style_bold'] = '';
$column['column_2']['body_li_style_italic'] = '';
$column['column_2']['body_li_style_decoration'] = '';



$column['column_2']['content_label_font_family'] = 'Arial';
$column['column_2']['content_label_font_size'] = 15;
$column['column_2']['content_label_font_color'] = '#2a2e31';
$column['column_2']['content_label_hover_font_color'] = '#2a2e31';
$column['column_2']['body_label_style_bold'] = 'bold';
$column['column_2']['body_label_style_italic'] = '';
$column['column_2']['body_label_style_decoration'] = '';



$column['column_2']['button_background_color'] = '#ffffff';
$column['column_2']['button_hover_background_color'] = '#6c62d3';
$column['column_2']['button_font_family'] = "Lato";
$column['column_2']['button_font_size'] = 22;
$column['column_2']['button_font_color'] = "#313234";
$column['column_2']['button_hover_font_color'] = "#ffffff";
$column['column_2']['button_style_bold'] = '';
$column['column_2']['button_style_italic'] = '';
$column['column_2']['button_style_decoration'] = '';


$column['column_2']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>39.00</span><span class='arp_price_duration' style='font-size:18px;'> monthly </span>";
$column['column_2']['price_label'] = "";
$column['column_2']['arp_header_shortcode'] = "<i class='fas fa-check arpsize-ico-48'></i>";
$column['column_2']['body_text_alignment'] = 'center';
$column['column_2']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_0']['row_description'] = '80 GB Disk Space';
$column['column_2']['rows']['row_0']['row_label'] = '';
$column['column_2']['rows']['row_0']['row_tooltip'] = '';
$column['column_2']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_1']['row_description'] = '30 Databases';
$column['column_2']['rows']['row_1']['row_label'] = '';
$column['column_2']['rows']['row_1']['row_tooltip'] = '';
$column['column_2']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_2']['row_description'] = '30 Domains';
$column['column_2']['rows']['row_2']['row_label'] = '';
$column['column_2']['rows']['row_2']['row_tooltip'] = '';
$column['column_2']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_2']['rows']['row_3']['row_description'] = 'Online Support';
$column['column_2']['rows']['row_3']['row_label'] = '';
$column['column_2']['rows']['row_3']['row_tooltip'] = '';
$column['column_2']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_2']['button_size'] = '158';
$column['column_2']['button_height'] = '48';
$column['column_2']['button_type'] = 'Button';
$column['column_2']['button_text'] = 'Buy Now';
$column['column_2']['button_url'] = '#';
$column['column_2']['button_s_size'] = '';
$column['column_2']['button_s_type'] = '';
$column['column_2']['button_s_text'] = '';
$column['column_2']['button_s_url'] = '';
$column['column_2']['s_is_new_window'] = '';
$column['column_2']['is_new_window'] = 0;


$column['column_2']['footer_content'] = '';
$column['column_2']['footer_content_position'] = 0;
$column['column_2']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_2']['footer_level_options_font_size'] = 12;
$column['column_2']['footer_level_options_font_color'] = '#ffffff';
$column['column_2']['footer_level_options_hover_font_color'] = '#000000';
$column['column_2']['footer_level_options_font_style_bold'] = '';
$column['column_2']['footer_level_options_font_style_italic'] = '';
$column['column_2']['footer_level_options_font_style_decoration'] = '';
$column['column_2']['is_post_variables'] = 0;
$column['column_2']['post_variables_content'] = '';


$column['column_3']['shortcode_background_color'] = '#ffffff';
$column['column_3']['shortcode_font_color'] = '#ffffff';
$column['column_3']['shortcode_hover_background_color'] = '#6c62d3';
$column['column_3']['shortcode_hover_font_color'] = '#6c62d3';
$column['column_3']['arp_shortcode_customization_style'] = 'rounded';
$column['column_3']['arp_shortcode_customization_size'] = 'medium';
$column['column_3']['package_title'] = 'ULTIMATE PRO';
$column['column_3']['column_description'] = '';
$column['column_3']['custom_ribbon_txt'] = '';
$column['column_3']['column_width'] = '';
$column['column_3']['column_align'] = 'left';
$column['column_3']['is_caption'] = 0;
$column['column_3']['column_highlight'] = '';
$column['column_3']['column_background_color'] = "#6c62d3";
$column['column_3']['column_hover_background_color'] = "#f6f6f6";


$column['column_3']['header_font_family'] = 'Lato';
$column['column_3']['header_font_size'] = 26;
$column['column_3']['header_font_color'] = "#ffffff";
$column['column_3']['header_hover_font_color'] = "#6c62d3";
$column['column_3']['header_style_bold'] = 'bold';
$column['column_3']['header_style_italic'] = '';
$column['column_3']['header_style_decoration'] = '';



$column['column_3']['price_font_family'] = "Lato";
$column['column_3']['price_font_size'] = 40;
$column['column_3']['price_font_color'] = "#ffffff";
$column['column_3']['price_hover_font_color'] = "#000000";
$column['column_3']['price_label_style_bold'] = 'bold';
$column['column_3']['price_label_style_italic'] = '';
$column['column_3']['price_label_style_decoration'] = '';

$column['column_3']['price_text_font_family'] = 'Lato';
$column['column_3']['price_text_font_size'] = 18;
$column['column_3']['price_text_font_color'] = "#ffffff";
$column['column_3']['price_text_hover_font_color'] = "#000000";
$column['column_3']['price_text_style_bold'] = 'bold';
$column['column_3']['price_text_style_italic'] = '';
$column['column_3']['price_text_style_decoration'] = '';



$column['column_3']['column_description_font_family'] = 'Arial';
$column['column_3']['column_description_font_size'] = 13;
$column['column_3']['column_description_font_color'] = '#7c7c7c';
$column['column_3']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_3']['column_description_style_bold'] = '';
$column['column_3']['column_description_style_italic'] = '';
$column['column_3']['column_description_style_decoration'] = '';



$column['column_3']['content_font_family'] = "Lato";
$column['column_3']['content_font_size'] = 18;
$column['column_3']['content_font_color'] = "#ffffff";
$column['column_3']['content_even_font_color'] = "#ffffff";
$column['column_3']['content_hover_font_color'] = "#000000";
$column['column_3']['content_even_hover_font_color'] = "#000000";
$column['column_3']['body_li_style_bold'] = '';
$column['column_3']['body_li_style_italic'] = '';
$column['column_3']['body_li_style_decoration'] = '';



$column['column_3']['content_label_font_family'] = 'Arial';
$column['column_3']['content_label_font_size'] = 15;
$column['column_3']['content_label_font_color'] = '#2a2e31';
$column['column_3']['content_label_hover_font_color'] = '#2a2e31';
$column['column_3']['body_label_style_bold'] = 'bold';
$column['column_3']['body_label_style_italic'] = '';
$column['column_3']['body_label_style_decoration'] = '';



$column['column_3']['button_background_color'] = '#ffffff';
$column['column_3']['button_hover_background_color'] = '#6c62d3';
$column['column_3']['button_font_family'] = "Lato";
$column['column_3']['button_font_size'] = 22;
$column['column_3']['button_font_color'] = "#313234";
$column['column_3']['button_hover_font_color'] = "#ffffff";
$column['column_3']['button_style_bold'] = '';
$column['column_3']['button_style_italic'] = '';
$column['column_3']['button_style_decoration'] = '';


$column['column_3']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>99.00</span><span class='arp_price_duration' style='font-size:18px;'> monthly </span>";
$column['column_3']['price_label'] = "";
$column['column_3']['arp_header_shortcode'] = "<i class='fas fa-building arpsize-ico-48'></i>";
$column['column_3']['body_text_alignment'] = 'center';
$column['column_3']['rows']['row_0']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_0']['row_description'] = 'Unlimited Disk Space';
$column['column_3']['rows']['row_0']['row_label'] = '';
$column['column_3']['rows']['row_0']['row_tooltip'] = '';
$column['column_3']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_1']['row_description'] = 'Unlimited Database';
$column['column_3']['rows']['row_1']['row_label'] = '';
$column['column_3']['rows']['row_1']['row_tooltip'] = '';
$column['column_3']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_2']['row_description'] = '30 Domains';
$column['column_3']['rows']['row_2']['row_label'] = '';
$column['column_3']['rows']['row_2']['row_tooltip'] = '';
$column['column_3']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_des_txt_align'] = 'center';
$column['column_3']['rows']['row_3']['row_description'] = 'Online Support';
$column['column_3']['rows']['row_3']['row_label'] = '';
$column['column_3']['rows']['row_3']['row_tooltip'] = '';
$column['column_3']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_3']['button_size'] = '158';
$column['column_3']['button_height'] = '48';
$column['column_3']['button_type'] = 'Button';
$column['column_3']['button_text'] = 'Buy Now';
$column['column_3']['button_url'] = '#';
$column['column_3']['button_s_size'] = '';
$column['column_3']['button_s_type'] = '';
$column['column_3']['button_s_text'] = '';
$column['column_3']['button_s_url'] = '';
$column['column_3']['s_is_new_window'] = '';
$column['column_3']['s_is_new_window'] = '';
$column['column_3']['is_new_window'] = 0;


$column['column_3']['footer_content'] = '';
$column['column_3']['footer_content_position'] = 0;
$column['column_3']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_3']['footer_level_options_font_size'] = 12;
$column['column_3']['footer_level_options_font_color'] = '#ffffff';
$column['column_3']['footer_level_options_hover_font_color'] = '#000000';
$column['column_3']['footer_level_options_font_style_bold'] = '';
$column['column_3']['footer_level_options_font_style_italic'] = '';
$column['column_3']['footer_level_options_font_style_decoration'] = '';
$column['column_3']['is_post_variables'] = 0;
$column['column_3']['post_variables_content'] = '';


$pt_columns = array('columns' => $column);
$opts = maybe_serialize($pt_columns);

$arpricelite_form->option_create($table_id, $opts);

unset($values);

/**
 * ARPrice Template 7
 * 
 * @since 1.0
 */
/*if( isset($arp_update_table) && $arp_update_table == 1 ){
	$values['ID'] = 7;
}*/
$values['name'] = 'ARPricelite Template 7';
$values['is_template'] = 1;
$values['template_name'] = 7;
$values['ID'] = 7;
$values['status'] = 'published';
$values['is_animated'] = 0;

$arp_pt_gen_options = array();

$arp_pt_template_settings = array();

$arp_pt_font_settings = array();

$arp_pt_general_settings = array();

$arp_header_font_settings = array();
$arp_price_font_settings = array();
$arp_content_font_settings = array();
$arp_button_font_settings = array();

$arp_pt_column_settings = array();

$arp_pt_column_animation = array();

$arp_pt_tooltip_settings = array();

$arp_pt_button_settings = array();

$arp_pt_template_settings['template'] = 'arplitetemplate_7';
$arp_pt_template_settings['skin'] = 'blue';
$arp_pt_template_settings['template_type'] = 'normal';
$arp_pt_template_settings['features'] = array('column_description' => 'enable', 'custom_ribbon' => 'disable', 'button_position' => 'position_1', 'caption_style' => 'none', 'amount_style' => 'default', 'list_alignment' => 'default', 'ribbon_type' => 'default', 'column_description_style' => 'style_3', 'caption_title' => 'default', 'header_shortcode_type' => 'normal', 'header_shortcode_position' => 'position_1', 'tooltip_position' => 'top-left', 'tooltip_style' => 'style_1', 'second_btn' => false, 'is_animated' => 0);



$arp_pt_general_settings['column_order'] = '["main_column_0","main_column_1","main_column_2","main_column_3"]';
$arp_pt_general_settings['reference_template'] = 'arplitetemplate_7';
$arp_pt_general_settings['user_edited_columns'] = '';


$arp_pt_general_settings['enable_toggle_price'] = '0';
$arp_pt_general_settings['toggle_copy_data_to_other_step'] = '0';
$arp_pt_general_settings['arp_step_main'] = '2';
$arp_pt_general_settings['togglestep_yearly'] = 'Yearly';
$arp_pt_general_settings['togglestep_monthly'] = 'Monthly';
$arp_pt_general_settings['togglestep_quarterly'] = 'Quarterly';
$arp_pt_general_settings['togglestep_weekly'] = 'Weekly';
$arp_pt_general_settings['togglestep_daily'] = 'Daily';
$arp_pt_general_settings['setas_default_toggle'] = '0';
$arp_pt_general_settings['arp_toggle_main'] = '0';
$arp_pt_general_settings['toggle_active_color'] = '#404040';
$arp_pt_general_settings['toggle_inactive_color'] = '#ffffff';
$arp_pt_general_settings['toggle_active_text_color'] = '#ffffff';
$arp_pt_general_settings['toggle_button_font_color'] = '#000000';
$arp_pt_general_settings['toggle_title_font_color'] = '#000000';
$arp_pt_general_settings['toggle_label_content'] = 'Please Select Your Plan';
$arp_pt_general_settings['arp_label_position_main'] = '1';
$arp_pt_general_settings['toggle_main_color'] = '#E8E9EB';
$arp_pt_general_settings['toggle_title_font_family'] = 'Ubuntu';
$arp_pt_general_settings['toggle_title_font_size'] = 16;
$arp_pt_general_settings['toggle_title_font_style_bold'] = '';
$arp_pt_general_settings['toggle_title_font_style_italic'] = '';
$arp_pt_general_settings['toggle_title_font_style_decoration'] = '';
$arp_pt_general_settings['toggle_button_font_family'] = 'Ubuntu';
$arp_pt_general_settings['toggle_button_font_size'] = 16;
$arp_pt_general_settings['toggle_button_font_style_bold'] = '';
$arp_pt_general_settings['toggle_button_font_style_italic'] = '';
$arp_pt_general_settings['toggle_button_font_style_decoration'] = '';



$arp_pt_button_settings['button_shadow_color'] = '#ffffff';
$arp_pt_button_settings['button_radius'] = 0;

$arp_pt_column_settings['column_space'] = 10;
$arp_pt_column_settings['min_row_height'] = 35;
$arp_pt_column_settings['column_highlight_on_hover'] = 'shadow_effect';
$arp_pt_column_settings['is_responsive'] = 1;
$arp_pt_column_settings['full_column_clickable'] = 0;
$arp_pt_column_settings['enable_hover_effect'] = 1;
$arp_pt_column_settings['hide_footer_global'] = 0;
$arp_pt_column_settings['hide_header_global'] = 0;
$arp_pt_column_settings['hide_price_global'] = 0;
$arp_pt_column_settings['hide_feature_global'] = 0;
$arp_pt_column_settings['hide_description_global'] = 0;
$arp_pt_column_settings['hide_header_shortcode_global'] = 0;

$arp_pt_column_settings['all_column_width'] = 240;


$arp_pt_column_settings['column_opacity'] = $arplite_mainoptionsarr['general_options']['column_opacity'][0];
$arp_pt_column_settings['column_border_radius_top_left'] = 0;
$arp_pt_column_settings['column_border_radius_top_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['column_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['column_wrapper_width_txtbox'] = 1000;

$arp_pt_column_settings['global_button_border_width'] = 0;
$arp_pt_column_settings['global_button_border_type'] = 'solid';
$arp_pt_column_settings['global_button_border_color'] = '#c9c9c9ff';
$arp_pt_column_settings['global_button_border_radius_top_left'] = 0;
$arp_pt_column_settings['global_button_border_radius_top_right'] = 0;
$arp_pt_column_settings['global_button_border_radius_bottom_left'] = 0;
$arp_pt_column_settings['global_button_border_radius_bottom_right'] = 0;
$arp_pt_column_settings['arp_global_button_type'] = 'shadow';

$arp_pt_column_settings['arp_row_border_size'] = '1';
$arp_pt_column_settings['arp_row_border_type'] = 'solid';
$arp_pt_column_settings['arp_row_border_color'] = '#e1e1e1ff';

$arp_pt_column_settings['arp_column_border_size'] = '2';
$arp_pt_column_settings['arp_column_border_type'] = 'solid';
$arp_pt_column_settings['arp_column_border_color'] = '#dfdbdcff';

$arp_pt_column_settings['arp_column_border_left'] = 1;
$arp_pt_column_settings['arp_column_border_right'] = 1;
$arp_pt_column_settings['arp_column_border_top'] = 1;
$arp_pt_column_settings['arp_column_border_bottom'] = 1;

$arp_pt_column_settings['display_col_mobile'] = 1;
$arp_pt_column_settings['display_col_tablet'] = 3;


$arp_pt_column_settings['column_box_shadow_effect'] = 'shadow_style_none';
$arp_pt_column_settings['hide_blank_rows'] = 'no';
$arp_pt_column_settings['header_font_family_global'] = 'Open Sans Bold';
$arp_pt_column_settings['header_font_size_global'] = 19;
$arp_pt_column_settings['arp_header_text_alignment'] = 'center';
$arp_pt_column_settings['arp_header_text_bold_global'] = 'bold';
$arp_pt_column_settings['arp_header_text_italic_global'] = '';
$arp_pt_column_settings['arp_header_text_decoration_global'] = '';
$arp_pt_column_settings['price_font_family_global'] = 'Anton';
$arp_pt_column_settings['price_font_size_global'] = 46;
$arp_pt_column_settings['arp_price_text_alignment'] = 'center';
$arp_pt_column_settings['arp_price_text_bold_global'] = '';
$arp_pt_column_settings['arp_price_text_italic_global'] = '';
$arp_pt_column_settings['arp_price_text_decoration_global'] = '';
$arp_pt_column_settings['body_font_family_global'] = 'Arial';
$arp_pt_column_settings['body_font_size_global'] = 14;
$arp_pt_column_settings['arp_body_text_alignment'] = 'left';
$arp_pt_column_settings['arp_body_text_bold_global'] = '';
$arp_pt_column_settings['arp_body_text_italic_global'] = '';
$arp_pt_column_settings['arp_body_text_decoration_global'] = '';
$arp_pt_column_settings['footer_font_family_global'] = '';
$arp_pt_column_settings['footer_font_size_global'] = '';
$arp_pt_column_settings['arp_footer_text_alignment'] = '';
$arp_pt_column_settings['arp_footer_text_bold_global'] = '';
$arp_pt_column_settings['arp_footer_text_italic_global'] = '';
$arp_pt_column_settings['arp_footer_text_decoration_global'] = '';
$arp_pt_column_settings['button_font_family_global'] = 'Arial';
$arp_pt_column_settings['button_font_size_global'] = 18;
$arp_pt_column_settings['arp_button_text_alignment'] = 'center';
$arp_pt_column_settings['arp_button_text_bold_global'] = '';
$arp_pt_column_settings['arp_button_text_italic_global'] = '';
$arp_pt_column_settings['arp_button_text_decoration_global'] = '';
$arp_pt_column_settings['description_font_family_global'] = 'Arial';
$arp_pt_column_settings['description_font_size_global'] = 14;
$arp_pt_column_settings['arp_description_text_alignment'] = 'center';
$arp_pt_column_settings['arp_description_text_bold_global'] = '';
$arp_pt_column_settings['arp_description_text_italic_global'] = '';
$arp_pt_column_settings['arp_description_text_decoration_global'] = '';
$arp_pt_column_animation['is_animation'] = 'no';
$arp_pt_column_animation['visible_column'] = 2;
$arp_pt_column_animation['scrolling_columns'] = 2;
$arp_pt_column_animation['navigation'] = 1;
$arp_pt_column_animation['autoplay'] = 1;
$arp_pt_column_animation['sliding_effect'] = 'slide';
$arp_pt_column_animation['transition_speed'] = 750;
$arp_pt_column_animation['navigation_style'] = $arplite_mainoptionsarr['general_options']['column_animation']['navigation_style'][0];
$arp_pt_column_animation['pagination'] = $arplite_mainoptionsarr['general_options']['column_animation']['pagination'];
$arp_pt_column_animation['pagination_style'] = $arplite_mainoptionsarr['general_options']['column_animation']['pagination_style'][0];
$arp_pt_column_animation['pagination_position'] = $arplite_mainoptionsarr['general_options']['column_animation']['pagination_position'][0];
$arp_pt_column_animation['easing_effect'] = $arplite_mainoptionsarr['general_options']['column_animation']['easing_effect'][0];
$arp_pt_column_animation['infinite'] = $arplite_mainoptionsarr['general_options']['column_animation']['infinite'];

$arp_pt_column_animation['navi_nav_btn'] = 'navigation';
$arp_pt_column_animation['pagi_nav_btn'] = 'pagination_bottom';
$arp_pt_column_animation['sticky_caption'] = 0;

$arp_pt_tooltip_settings['background_color'] = '#77b900';
$arp_pt_tooltip_settings['text_color'] = '#FFFFFF';
$arp_pt_tooltip_settings['animation'] = $arplite_mainoptionsarr['general_options']['tooltipsetting']['animation'][0];
$arp_pt_tooltip_settings['position'] = $arplite_mainoptionsarr['general_options']['tooltipsetting']['position'][2];
$arp_pt_tooltip_settings['style'] = $arplite_mainoptionsarr['general_options']['tooltipsetting']['style'][0];
$arp_pt_tooltip_settings['tooltip_width'] = '';
$arp_pt_tooltip_settings['tooltip_font_family'] = 'Arial';
$arp_pt_tooltip_settings['tooltip_font_size'] = 14;
$arp_pt_tooltip_settings['tooltip_font_style'] = 'normal';
$arp_pt_tooltip_settings['tooltip_trigger_type'] = 'hover';
$arp_pt_tooltip_settings['tooltip_display_style'] = 'default';
$arp_pt_tooltip_settings['tooltip_informative_icon'] = '<i class="fas fa-info-circle arpsize-ico-14"></i>';

$arp_pt_font_settings['header_fonts'] = $arp_header_font_settings;
$arp_pt_font_settings['price_fonts'] = $arp_price_font_settings;
$arp_pt_font_settings['price_text_fonts'] = $arp_price_text_font_settings;
$arp_pt_font_settings['content_fonts'] = $arp_content_font_settings;
$arp_pt_font_settings['button_fonts'] = $arp_button_font_settings;

$arp_pt_gen_options = array('template_setting' => $arp_pt_template_settings, 'font_settings' => $arp_pt_font_settings, 'column_settings' => $arp_pt_column_settings, 'column_animation' => $arp_pt_column_animation, 'tooltip_settings' => $arp_pt_tooltip_settings, 'general_settings' => $arp_pt_general_settings, 'button_settings' => $arp_pt_button_settings);

$arp_pt_custom_skin_array = array();

$arp_pt_custom_skin_array['arp_header_bg_custom_color'] = "#000000";
$arp_pt_custom_skin_array['arp_column_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_column_desc_bg_custom_color'] = "#ffffff";
$arp_pt_custom_skin_array['arp_pricing_bg_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_odd_row_bg_custom_color'] = "#ffffff";
$arp_pt_custom_skin_array['arp_body_even_row_bg_custom_color'] = "#f2f2f2";
$arp_pt_custom_skin_array['arp_footer_content_bg_color'] = null;
$arp_pt_custom_skin_array['arp_button_bg_custom_color'] = "#3473dc";
$arp_pt_custom_skin_array['arp_column_bg_hover_color'] = null;
$arp_pt_custom_skin_array['arp_button_bg_hover_color'] = "#3e3e3c";
$arp_pt_custom_skin_array['arp_header_bg_hover_color'] = '#000000';
$arp_pt_custom_skin_array['arp_price_bg_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_odd_row_hover_bg_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_body_even_row_hover_bg_custom_color'] = '#f2f2f2';
$arp_pt_custom_skin_array['arp_footer_content_hover_bg_color'] = null;
$arp_pt_custom_skin_array['arp_column_desc_hover_bg_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_header_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_header_font_custom_hover_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_price_font_custom_color'] = '#3e3e3c';
$arp_pt_custom_skin_array['arp_price_font_custom_hover_color'] = '#3e3e3c';
$arp_pt_custom_skin_array['arp_price_duration_font_custom_color'] = '#898989';
$arp_pt_custom_skin_array['arp_price_duration_font_custom_hover_color'] = '#898989';
$arp_pt_custom_skin_array['arp_desc_font_custom_color'] = '#7c7c7c';
$arp_pt_custom_skin_array['arp_desc_font_custom_hover_color'] = '#7c7c7c';
$arp_pt_custom_skin_array['arp_body_label_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_body_label_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_body_font_custom_color'] = '#333333';
$arp_pt_custom_skin_array['arp_body_even_font_custom_color'] = '#333333';
$arp_pt_custom_skin_array['arp_body_font_custom_hover_color'] = '#333333';
$arp_pt_custom_skin_array['arp_body_even_font_custom_hover_color'] = '#333333';
$arp_pt_custom_skin_array['arp_footer_font_custom_color'] = null;
$arp_pt_custom_skin_array['arp_footer_font_custom_hover_color'] = null;
$arp_pt_custom_skin_array['arp_button_font_custom_color'] = '#ffffff';
$arp_pt_custom_skin_array['arp_button_font_custom_hover_color'] = '#ffffff';

$arp_pt_gen_options['custom_skin_colors'] = $arp_pt_custom_skin_array;

$values['options'] = maybe_serialize($arp_pt_gen_options);


$table_id = $arpricelite_form->create($values);

$pt_columns = array();

$column = array();

$column['column_0']['package_title'] = 'Basic';
$column['column_0']['column_description'] = 'Aliquam euismod erat libero, eu condimentum nisl hendrerit vel.';
$column['column_0']['custom_ribbon_txt'] = '';
$column['column_0']['column_width'] = '';
$column['column_0']['is_caption'] = 0;
$column['column_0']['column_highlight'] = '';

$column['column_0']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>11</span><span class='arp_price_duration' style='font-size:16px;'> / month </span>";
$column['column_0']['price_label'] = "";


$column['column_0']['header_background_color'] = '#000000';
$column['column_0']['header_hover_background_color'] = '#000000';
$column['column_0']['header_font_family'] = 'Open Sans Bold';
$column['column_0']['header_font_size'] = 19;
$column['column_0']['header_font_color'] = "#ffffff";
$column['column_0']['header_hover_font_color'] = "#ffffff";

$column['column_0']['header_style_bold'] = 'bold';
$column['column_0']['header_style_italic'] = '';
$column['column_0']['header_style_decoration'] = '';


$column['column_0']['price_font_family'] = "Anton";
$column['column_0']['price_font_size'] = 46;
$column['column_0']['price_font_color'] = "#3E3E3C";
$column['column_0']['price_hover_font_color'] = "#3E3E3C";

$column['column_0']['price_label_style_bold'] = '';
$column['column_0']['price_label_style_italic'] = '';
$column['column_0']['price_label_style_decoration'] = '';

$column['column_0']['price_text_font_family'] = 'Arial';
$column['column_0']['price_text_font_size'] = 16;
$column['column_0']['price_text_font_color'] = "#898989";
$column['column_0']['price_text_hover_font_color'] = "#898989";
$column['column_0']['price_text_style_bold'] = 'bold';
$column['column_0']['price_text_style_italic'] = 'italic';
$column['column_0']['price_text_style_decoration'] = '';


$column['column_0']['column_description_font_family'] = 'Arial';
$column['column_0']['column_description_font_size'] = 14;

$column['column_0']['column_description_font_color'] = '#7c7c7c';
$column['column_0']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_0']['column_desc_background_color'] = '#ffffff';
$column['column_0']['column_desc_hover_background_color'] = '#ffffff';
$column['column_0']['column_description_style_bold'] = '';
$column['column_0']['column_description_style_italic'] = '';
$column['column_0']['column_description_style_decoration'] = '';


$column['column_0']['content_font_family'] = "Arial";
$column['column_0']['content_font_size'] = 14;
$column['column_0']['content_font_color'] = "#333333";
$column['column_0']['content_even_font_color'] = "#333333";
$column['column_0']['content_hover_font_color'] = "#333333";
$column['column_0']['content_even_hover_font_color'] = "#333333";
$column['column_0']['content_odd_color'] = '#ffffff';
$column['column_0']['content_odd_hover_color'] = '#ffffff';
$column['column_0']['content_even_color'] = '#f2f2f2';
$column['column_0']['content_even_hover_color'] = '#f2f2f2';
$column['column_0']['body_li_style_bold'] = '';
$column['column_0']['body_li_style_italic'] = '';
$column['column_0']['body_li_style_decoration'] = '';


$column['column_0']['button_background_color'] = '#3473dc';
$column['column_0']['button_hover_background_color'] = '#3e3e3c';
$column['column_0']['button_font_family'] = "Arial";
$column['column_0']['button_font_size'] = 18;
$column['column_0']['button_font_color'] = "#ffffff";
$column['column_0']['button_hover_font_color'] = "#ffffff";
$column['column_0']['button_style_bold'] = '';
$column['column_0']['button_style_italic'] = '';
$column['column_0']['button_style_decoration'] = '';


$column['column_0']['arp_header_shortcode'] = '[arplite_header_image id="'.ARPLITE_PRICINGTABLE_IMAGES_URL.'/arptemplate_7_1.jpg" height="240" width="235" open_in_lightbox="0"]';
$column['column_0']['body_text_alignment'] = 'left';
$column['column_0']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> 10 GB Disk Space';
$column['column_0']['rows']['row_0']['row_label'] = '';
$column['column_0']['rows']['row_0']['row_tooltip'] = '';
$column['column_0']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_1']['row_description'] = '  <i class="fas fa-shopping-cart arpsize-ico-14"></i> 5 Databases';
$column['column_0']['rows']['row_1']['row_label'] = '';
$column['column_0']['rows']['row_1']['row_tooltip'] = '';
$column['column_0']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> 5 Email Accounts';
$column['column_0']['rows']['row_2']['row_label'] = '';
$column['column_0']['rows']['row_2']['row_tooltip'] = '';
$column['column_0']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_0']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> 5 Free Domain';
$column['column_0']['rows']['row_3']['row_label'] = '';
$column['column_0']['rows']['row_3']['row_tooltip'] = '';
$column['column_0']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_0']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_0']['button_size'] = '146';
$column['column_0']['button_height'] = '45';
$column['column_0']['button_type'] = 'Button';
$column['column_0']['button_text'] = 'Sign Up';
$column['column_0']['button_url'] = '#';
$column['column_0']['button_s_size'] = '';
$column['column_0']['button_s_type'] = '';
$column['column_0']['button_s_text'] = '';
$column['column_0']['button_s_url'] = '';
$column['column_0']['s_is_new_window'] = '';
$column['column_0']['is_new_window'] = 0;


$column['column_0']['footer_content'] = '';
$column['column_0']['footer_content_position'] = 0;
$column['column_0']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_0']['footer_level_options_font_size'] = 12;
$column['column_0']['footer_level_options_font_color'] = '#333333';
$column['column_0']['footer_level_options_hover_font_color'] = '#333333';
$column['column_0']['footer_level_options_font_style_bold'] = '';
$column['column_0']['footer_level_options_font_style_italic'] = '';
$column['column_0']['footer_level_options_font_style_decoration'] = '';

$column['column_0']['is_post_variables'] = 0;
$column['column_0']['post_variables_content'] = '';

$column['column_1']['package_title'] = 'Team';
$column['column_1']['column_description'] = 'Aliquam euismod erat libero, eu condimentum nisl hendrerit vel.';
$column['column_1']['custom_ribbon_txt'] = '';
$column['column_1']['column_width'] = '';
$column['column_1']['is_caption'] = 0;
$column['column_1']['column_highlight'] = 1;

$column['column_1']['header_background_color'] = '#000000';
$column['column_1']['header_hover_background_color'] = '#000000';
$column['column_1']['header_font_family'] = 'Open Sans Bold';
$column['column_1']['header_font_size'] = 19;
$column['column_1']['header_font_color'] = "#ffffff";
$column['column_1']['header_hover_font_color'] = "#ffffff";

$column['column_1']['header_style_bold'] = 'bold';
$column['column_1']['header_style_italic'] = '';
$column['column_1']['header_style_decoration'] = '';


$column['column_1']['price_font_family'] = "Anton";
$column['column_1']['price_font_size'] = 46;
$column['column_1']['price_font_color'] = "#3E3E3C";
$column['column_1']['price_hover_font_color'] = "#3E3E3C";

$column['column_1']['price_label_style_bold'] = '';
$column['column_1']['price_label_style_italic'] = '';
$column['column_1']['price_label_style_decoration'] = '';

$column['column_1']['price_text_font_family'] = 'Arial';
$column['column_1']['price_text_font_size'] = 16;
$column['column_1']['price_text_font_color'] = "#898989";
$column['column_1']['price_text_hover_font_color'] = "#898989";
$column['column_1']['price_text_style_bold'] = 'bold';
$column['column_1']['price_text_style_italic'] = 'italic';
$column['column_1']['price_text_style_decoration'] = '';


$column['column_1']['column_description_font_family'] = 'Arial';
$column['column_1']['column_description_font_size'] = 14;

$column['column_1']['column_description_font_color'] = '#7c7c7c';
$column['column_1']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_1']['column_desc_background_color'] = '#ffffff';
$column['column_1']['column_desc_hover_background_color'] = '#ffffff';
$column['column_1']['column_description_style_bold'] = '';
$column['column_1']['column_description_style_italic'] = '';
$column['column_1']['column_description_style_decoration'] = '';


$column['column_1']['content_font_family'] = "Arial";
$column['column_1']['content_font_size'] = 14;
$column['column_1']['content_font_color'] = "#333333";
$column['column_1']['content_even_font_color'] = "#333333";
$column['column_1']['content_hover_font_color'] = "#333333";
$column['column_1']['content_even_hover_font_color'] = "#333333";
$column['column_1']['content_odd_color'] = '#ffffff';
$column['column_1']['content_odd_hover_color'] = '#ffffff';
$column['column_1']['content_even_color'] = '#f2f2f2';
$column['column_1']['content_even_hover_color'] = '#f2f2f2';
$column['column_1']['body_li_style_bold'] = '';
$column['column_1']['body_li_style_italic'] = '';
$column['column_1']['body_li_style_decoration'] = '';


$column['column_1']['button_background_color'] = '#3473dc';
$column['column_1']['button_hover_background_color'] = '#3e3e3c';
$column['column_1']['button_font_family'] = "Arial";
$column['column_1']['button_font_size'] = 18;
$column['column_1']['button_font_color'] = "#ffffff";
$column['column_1']['button_hover_font_color'] = "#ffffff";
$column['column_1']['button_style_bold'] = '';
$column['column_1']['button_style_italic'] = '';
$column['column_1']['button_style_decoration'] = '';



$column['column_1']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>22</span><span class='arp_price_duration' style='font-size:16px;'> / month </span>";
$column['column_1']['price_label'] = "";
$column['column_1']['arp_header_shortcode'] = '[arplite_header_image id="'.ARPLITE_PRICINGTABLE_IMAGES_URL.'/arptemplate_7_2.jpg" height="240" width="235" open_in_lightbox="0"]';
$column['column_1']['body_text_alignment'] = 'left';
$column['column_1']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> 20 GB Disk Space';
$column['column_1']['rows']['row_0']['row_label'] = '';
$column['column_1']['rows']['row_0']['row_tooltip'] = '';
$column['column_1']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_0']['row_caption_style_decoration'] = '';

$column['column_1']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> 10 Databases';
$column['column_1']['rows']['row_1']['row_label'] = '';
$column['column_1']['rows']['row_1']['row_tooltip'] = '';

$column['column_1']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> 10 Email Accounts';
$column['column_1']['rows']['row_2']['row_label'] = '';
$column['column_1']['rows']['row_2']['row_tooltip'] = '';
$column['column_1']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_1']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> 10 Free Domain';
$column['column_1']['rows']['row_3']['row_label'] = '';
$column['column_1']['rows']['row_3']['row_tooltip'] = '';
$column['column_1']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_1']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_1']['button_size'] = '146';
$column['column_1']['button_height'] = '45';
$column['column_1']['button_type'] = 'Button';
$column['column_1']['button_text'] = 'Sign Up';
$column['column_1']['button_url'] = '#';
$column['column_1']['button_s_size'] = '';
$column['column_1']['button_s_type'] = '';
$column['column_1']['button_s_text'] = '';
$column['column_1']['button_s_url'] = '';
$column['column_1']['s_is_new_window'] = '';
$column['column_1']['is_new_window'] = 0;



$column['column_1']['footer_content'] = '';
$column['column_1']['footer_content_position'] = 0;
$column['column_1']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_1']['footer_level_options_font_size'] = 12;
$column['column_1']['footer_level_options_font_color'] = '#333333';
$column['column_1']['footer_level_options_hover_font_color'] = '#333333';
$column['column_1']['footer_level_options_font_style_bold'] = '';
$column['column_1']['footer_level_options_font_style_italic'] = '';
$column['column_1']['footer_level_options_font_style_decoration'] = '';
$column['column_1']['is_post_variables'] = 0;
$column['column_1']['post_variables_content'] = '';

$column['column_2']['package_title'] = 'Premium';
$column['column_2']['column_description'] = 'Aliquam euismod erat libero, eu condimentum nisl hendrerit vel.';
$column['column_2']['custom_ribbon_txt'] = '';
$column['column_2']['column_width'] = '';
$column['column_2']['is_caption'] = 0;

$column['column_2']['column_highlight'] = 0;

$column['column_2']['header_background_color'] = '#000000';
$column['column_2']['header_hover_background_color'] = '#000000';
$column['column_2']['header_font_family'] = 'Open Sans Bold';
$column['column_2']['header_font_size'] = 19;
$column['column_2']['header_font_color'] = "#ffffff";
$column['column_2']['header_hover_font_color'] = "#ffffff";

$column['column_2']['header_style_bold'] = 'bold';
$column['column_2']['header_style_italic'] = '';
$column['column_2']['header_style_decoration'] = '';


$column['column_2']['price_font_family'] = "Anton";
$column['column_2']['price_font_size'] = 46;
$column['column_2']['price_font_color'] = "#3E3E3C";
$column['column_2']['price_hover_font_color'] = "#3E3E3C";

$column['column_2']['price_label_style_bold'] = '';
$column['column_2']['price_label_style_italic'] = '';
$column['column_2']['price_label_style_decoration'] = '';

$column['column_2']['price_text_font_family'] = 'Arial';
$column['column_2']['price_text_font_size'] = 16;
$column['column_2']['price_text_font_color'] = "#898989";
$column['column_2']['price_text_hover_font_color'] = "#898989";
$column['column_2']['price_text_style_bold'] = 'bold';
$column['column_2']['price_text_style_italic'] = 'italic';
$column['column_2']['price_text_style_decoration'] = '';


$column['column_2']['column_description_font_family'] = 'Arial';
$column['column_2']['column_description_font_size'] = 14;

$column['column_2']['column_description_font_color'] = '#7c7c7c';
$column['column_2']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_2']['column_desc_background_color'] = '#ffffff';
$column['column_2']['column_desc_hover_background_color'] = '#ffffff';
$column['column_2']['column_description_style_bold'] = '';
$column['column_2']['column_description_style_italic'] = '';
$column['column_2']['column_description_style_decoration'] = '';


$column['column_2']['content_font_family'] = "Arial";
$column['column_2']['content_font_size'] = 14;
$column['column_2']['content_font_color'] = "#333333";
$column['column_2']['content_even_font_color'] = "#333333";
$column['column_2']['content_hover_font_color'] = "#333333";
$column['column_2']['content_even_hover_font_color'] = "#333333";
$column['column_2']['content_odd_color'] = '#ffffff';
$column['column_2']['content_odd_hover_color'] = '#ffffff';
$column['column_2']['content_even_color'] = '#f2f2f2';
$column['column_2']['content_even_hover_color'] = '#f2f2f2';
$column['column_2']['body_li_style_bold'] = '';
$column['column_2']['body_li_style_italic'] = '';
$column['column_2']['body_li_style_decoration'] = '';


$column['column_2']['button_background_color'] = '#3473dc';
$column['column_2']['button_hover_background_color'] = '#3e3e3c';
$column['column_2']['button_font_family'] = "Arial";
$column['column_2']['button_font_size'] = 18;
$column['column_2']['button_font_color'] = "#ffffff";
$column['column_2']['button_hover_font_color'] = "#ffffff";
$column['column_2']['button_style_bold'] = '';
$column['column_2']['button_style_italic'] = '';
$column['column_2']['button_style_decoration'] = '';



$column['column_2']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>38</span><span class='arp_price_duration' style='font-size:16px;'> / month </span>";
$column['column_2']['price_label'] = "";
$column['column_2']['arp_header_shortcode'] = '[arplite_header_image id="'.ARPLITE_PRICINGTABLE_IMAGES_URL.'/arptemplate_7_3.jpg" height="240" width="235" open_in_lightbox="0"]';
$column['column_2']['body_text_alignment'] = 'left';
$column['column_2']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> 25 GB Disk Space';
$column['column_2']['rows']['row_0']['row_label'] = '';
$column['column_2']['rows']['row_0']['row_tooltip'] = '';
$column['column_2']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_0']['row_caption_style_decoration'] = '';

$column['column_2']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> 10 Databases';
$column['column_2']['rows']['row_1']['row_label'] = '';
$column['column_2']['rows']['row_1']['row_tooltip'] = '';
$column['column_2']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> 10 Email Accounts';
$column['column_2']['rows']['row_2']['row_label'] = '';
$column['column_2']['rows']['row_2']['row_tooltip'] = '';
$column['column_2']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_2']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> 10 Domains';
$column['column_2']['rows']['row_3']['row_label'] = '';
$column['column_2']['rows']['row_3']['row_tooltip'] = '';
$column['column_2']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_2']['rows']['row_3']['row_caption_style_decoration'] = '';
$column['column_2']['button_size'] = '146';
$column['column_2']['button_height'] = '45';
$column['column_2']['button_type'] = 'Button';
$column['column_2']['button_text'] = 'Sign Up';
$column['column_2']['button_url'] = '#';
$column['column_2']['button_s_size'] = '';
$column['column_2']['button_s_type'] = '';
$column['column_2']['button_s_text'] = '';
$column['column_2']['button_s_url'] = '';
$column['column_2']['is_new_window'] = 0;
$column['column_2']['s_is_new_window'] = '';



$column['column_2']['footer_content'] = '';
$column['column_2']['footer_content_position'] = 0;
$column['column_2']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_2']['footer_level_options_font_size'] = 12;
$column['column_2']['footer_level_options_font_color'] = '#333333';
$column['column_2']['footer_level_options_hover_font_color'] = '#333333';
$column['column_2']['footer_level_options_font_style_bold'] = '';
$column['column_2']['footer_level_options_font_style_italic'] = '';
$column['column_2']['footer_level_options_font_style_decoration'] = '';
$column['column_2']['is_post_variables'] = 0;
$column['column_2']['post_variables_content'] = '';

$column['column_3']['package_title'] = 'Corporate';
$column['column_3']['column_description'] = 'Aliquam euismod erat libero, eu condimentum nisl hendrerit vel.';
$column['column_3']['custom_ribbon_txt'] = '';
$column['column_3']['column_width'] = '';
$column['column_3']['is_caption'] = 0;
$column['column_3']['column_highlight'] = '';

$column['column_3']['header_background_color'] = '#000000';
$column['column_3']['header_hover_background_color'] = '#000000';
$column['column_3']['header_font_family'] = 'Open Sans Bold';
$column['column_3']['header_font_size'] = 19;
$column['column_3']['header_font_color'] = "#ffffff";
$column['column_3']['header_hover_font_color'] = "#ffffff";

$column['column_3']['header_style_bold'] = 'bold';
$column['column_3']['header_style_italic'] = '';
$column['column_3']['header_style_decoration'] = '';


$column['column_3']['price_font_family'] = "Anton";
$column['column_3']['price_font_size'] = 46;
$column['column_3']['price_font_color'] = "#3E3E3C";
$column['column_3']['price_hover_font_color'] = "#3E3E3C";

$column['column_3']['price_label_style_bold'] = '';
$column['column_3']['price_label_style_italic'] = '';
$column['column_3']['price_label_style_decoration'] = '';

$column['column_3']['price_text_font_family'] = 'Arial';
$column['column_3']['price_text_font_size'] = 16;
$column['column_3']['price_text_font_color'] = "#898989";
$column['column_3']['price_text_hover_font_color'] = "#898989";

$column['column_3']['price_text_style_bold'] = 'bold';
$column['column_3']['price_text_style_italic'] = 'italic';
$column['column_3']['price_text_style_decoration'] = '';


$column['column_3']['column_description_font_family'] = 'Arial';
$column['column_3']['column_description_font_size'] = 14;

$column['column_3']['column_description_font_color'] = '#7c7c7c';
$column['column_3']['column_description_hover_font_color'] = '#7c7c7c';
$column['column_3']['column_desc_background_color'] = '#ffffff';
$column['column_3']['column_desc_hover_background_color'] = '#ffffff';
$column['column_3']['column_description_style_bold'] = '';
$column['column_3']['column_description_style_italic'] = '';
$column['column_3']['column_description_style_decoration'] = '';


$column['column_3']['content_font_family'] = "Arial";
$column['column_3']['content_font_size'] = 14;
$column['column_3']['content_font_color'] = "#333333";
$column['column_3']['content_even_font_color'] = "#333333";
$column['column_3']['content_hover_font_color'] = "#333333";
$column['column_3']['content_even_hover_font_color'] = "#333333";
$column['column_3']['content_odd_color'] = '#ffffff';
$column['column_3']['content_odd_hover_color'] = '#ffffff';
$column['column_3']['content_even_color'] = '#f2f2f2';
$column['column_3']['content_even_hover_color'] = '#f2f2f2';
$column['column_3']['body_li_style_bold'] = '';
$column['column_3']['body_li_style_italic'] = '';
$column['column_3']['body_li_style_decoration'] = '';


$column['column_3']['button_background_color'] = '#3473dc';
$column['column_3']['button_hover_background_color'] = '#3e3e3c';
$column['column_3']['button_font_family'] = "Arial";
$column['column_3']['button_font_size'] = 18;
$column['column_3']['button_font_color'] = "#ffffff";
$column['column_3']['button_hover_font_color'] = "#ffffff";
$column['column_3']['button_style_bold'] = '';
$column['column_3']['button_style_italic'] = '';
$column['column_3']['button_style_decoration'] = '';



$column['column_3']['price_text'] = "<span class='arp_price_value'><span class='arp_currency'>$</span>49</span><span class='arp_price_duration' style='font-size:16px;'> / month </span>";
$column['column_3']['price_label'] = "";
$column['column_3']['arp_header_shortcode'] = '[arplite_header_image id="'.ARPLITE_PRICINGTABLE_IMAGES_URL.'/arptemplate_7_4.jpg" height="240" width="235" open_in_lightbox="0"]';
$column['column_3']['body_text_alignment'] = 'left';
$column['column_3']['rows']['row_0']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_0']['row_description'] = '<i class="far fa-clock arpsize-ico-14"></i> Unlimited Disk Space';
$column['column_3']['rows']['row_0']['row_label'] = '';
$column['column_3']['rows']['row_0']['row_tooltip'] = '';
$column['column_3']['rows']['row_0']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_0']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_1']['row_description'] = '<i class="fas fa-shopping-cart arpsize-ico-14"></i> Unlimited Databases';
$column['column_3']['rows']['row_1']['row_label'] = '';
$column['column_3']['rows']['row_1']['row_tooltip'] = '';
$column['column_3']['rows']['row_1']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_1']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_2']['row_description'] = '<i class="fas fa-star arpsize-ico-14"></i> 30 Email accounts';
$column['column_3']['rows']['row_2']['row_label'] = '';
$column['column_3']['rows']['row_2']['row_tooltip'] = '';
$column['column_3']['rows']['row_2']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_2']['row_caption_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_des_txt_align'] = 'left';
$column['column_3']['rows']['row_3']['row_description'] = '<i class="fas fa-heart arpsize-ico-14"></i> 30 Free Domains';
$column['column_3']['rows']['row_3']['row_label'] = '';
$column['column_3']['rows']['row_3']['row_tooltip'] = '';
$column['column_3']['rows']['row_3']['row_des_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_des_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_des_style_decoration'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_bold'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_italic'] = '';
$column['column_3']['rows']['row_3']['row_caption_style_decoration'] = '';

$column['column_3']['button_size'] = '146';
$column['column_3']['button_height'] = '45';
$column['column_3']['button_type'] = 'Button';
$column['column_3']['button_text'] = 'Sign Up';
$column['column_3']['button_url'] = '#';
$column['column_3']['button_s_size'] = '';
$column['column_3']['button_s_type'] = '';
$column['column_3']['button_s_text'] = '';
$column['column_3']['button_s_url'] = '';
$column['column_3']['s_is_new_window'] = '';
$column['column_3']['is_new_window'] = 0;



$column['column_3']['footer_content'] = '';
$column['column_3']['footer_content_position'] = 0;
$column['column_3']['footer_level_options_font_family'] = 'Open Sans Bold';
$column['column_3']['footer_level_options_font_size'] = 12;
$column['column_3']['footer_level_options_font_color'] = '#333333';
$column['column_3']['footer_level_options_hover_font_color'] = '#333333';
$column['column_3']['footer_level_options_font_style_bold'] = '';
$column['column_3']['footer_level_options_font_style_italic'] = '';
$column['column_3']['footer_level_options_font_style_decoration'] = '';
$column['column_3']['is_post_variables'] = 0;
$column['column_3']['post_variables_content'] = '';

$pt_columns = array('columns' => $column);
$opts = maybe_serialize($pt_columns);

$arpricelite_form->option_create($table_id, $opts);

unset($values);
?>