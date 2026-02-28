<?php

if ( ! defined( 'ABSPATH' ) ){
    exit;
}

global $arpricelite_form, $arplite_mainoptionsarr, $arpricelite_default_settings;
$arp_is_rtl = is_rtl();
$template_section_array = $arpricelite_default_settings->arp_column_section_background_color();
$tablestring .= "<div class='column_level_settings' id='column_level_settings_new' data-column='main_" . $j . "'>";
$tablestring .= "<div class='btn-main'>";

/*column levele option container*/
$tablestring .= "<div class='column_level_button_wrapper'>";

$tablestring .= "<div class='arp_btn' id='column_level_options__button_1' data-level='column_level_options' style='display:none;' title='" . esc_html__('Column Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Column Settings', 'arprice-responsive-pricing-table') . "'></div>";

$tablestring .= "<div class='arp_btn' id='column_level_options__button_2' data-level='column_level_options' style='display:none;' title='" . esc_html__('Background and Font Color', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Background and Font Color', 'arprice-responsive-pricing-table') . "' ></div>";

$tablestring .= "<div class='arp_btn arp_btn_icon_wrapper pro_only' id='arp_hide_column' col-id=" . $col_no[1] . " data-level='column_level_options' style='display:none;' title='" . esc_html__('Hide/Show Column', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Hide/Show Column', 'arprice-responsive-pricing-table') . "' ><input type='hidden' name='column_hide_".$col_no[1]."' id='column_hide_input_".$col_no[1]."' value='0'> </div>";

$tablestring .= "<div class='arp_btn action_btn' col-id=" . $col_no[1] . " data-level='column_level_options' id='duplicate_column' style='display:none;' title='" . esc_html__('Duplicate Column', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Duplicate Column', 'arprice-responsive-pricing-table') . "'></div>";

$tablestring .= "<div class='arp_btn action_btn' col-id=" . $col_no[1] . " data-level='column_level_options' id='delete_column' style='display:none;' title='" . esc_html__('Delete Column', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Delete Column', 'arprice-responsive-pricing-table') . "'>";


$tablestring .= "<div class='delete_column_container' id='delete_column_container_" . $col_no[1] . "'>";
$tablestring .= "<div class='delete_column_arrow'></div>";
$tablestring .= "<div class='delete_column_title'>";
$tablestring .= esc_html__('Are you sure want to delete this column?', 'arprice-responsive-pricing-table');
$tablestring .= "</div>";
$tablestring .= "<div class='delete_column_buttons'>";
$tablestring .= "<button id='Model_Delete_Column_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_insert_btn delete_column'>" . esc_html__('Ok', 'arprice-responsive-pricing-table') . "</button>";
$tablestring .= "<button id='Model_Delete_Column_cancel_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_cancel_btn'>" . esc_html__('Cancel', 'arprice-responsive-pricing-table') . "</button>";
$tablestring .= "</div>";
$tablestring .= "</div>";
$tablestring .= "</div>";

$tablestring .= "</div>";
/*column level option container over*/

$tablestring .= "<div class='body_level_button_wrapper'>";
$tablestring .= "<div class='arp_btn column_add_new_row_action_btn' id='add_new_row' data-id='" . $col_no[1] . "' title='" . esc_html__('Add New Row', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add New Row', 'arprice-responsive-pricing-table') . "' data-level='body_level_options' style='display:none;'></div>";
$tablestring .= "</div>";

/*header level options container*/
$tablestring .= "<div class='header_level_button_wrapper'>";

$tablestring .= "<div class='arp_btn' id='header_level_options__button_1' data-level='header_level_options' title='" . esc_html__('Header Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Header Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
/*$tablestring .= "<div class='arp_btn' id='header_level_options__button_2' data-level='header_level_options' title='" . esc_html__('Column Description Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Column Description Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";*/
$tablestring .= "<div class='arp_btn' id='header_level_options__button_2' data-level='header_level_options' title='" . esc_html__('Media Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Media Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

$tablestring .= "</div>";
/*header level options container over*/

$tablestring .= "<div class='pricing_level_button_wrapper'>";
$tablestring .= "<div class='arp_btn' id='pricing_level_options__button_1' data-level='pricing_level_options' title='" . esc_html__('Price Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Price Settings', 'arprice-responsive-pricing-table') . "'  style='display:none;'></div>";
$tablestring .= "</div>";


$tablestring .= "<div class='column_description_level_button'>";
$tablestring .= "<div class='arp_btn' id='column_description_level__button_1' data-level='column_description_level' title='" . esc_html__('Column Description Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Column Description Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
$tablestring .= "<div class='arp_btn' id='pricing_level_options__button_3' data-level='pricing_level_options' title='" . esc_html__('Column Description Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Column Description Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
$tablestring .= "</div>";



/*body raw level option container*/
$tablestring .= "<div class='body_li_level_button_wrapper'>";

$tablestring .= "<div class='arp_btn' id='body_li_level_options__button_1' data-level='body_li_level_options' title='" . esc_html__('Description Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Description Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

$tablestring .= "<div class='arp_btn pro_only' id='body_li_level_options__button_2' data-level='body_li_level_options' title='" . esc_html__('Tooltip Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Tooltip Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

$tablestring .= "<div class='arp_btn pro_only' id='body_li_level_options__button_3' data-level='body_li_level_options' title='" . esc_html__('CSS Properties', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('CSS Properties', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

$tablestring .= "<div class='arp_btn' id='body_li_level_options__button_33' data-level='body_li_level_options' title='" . esc_html__('Label Description Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Label Description Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

$tablestring .= "<div class='arp_btn action_btn' id='copy_row' alt='' col-id='" . $col_no[1] . "' title='" . esc_html__('Duplicate Row', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Duplicate Row', 'arprice-responsive-pricing-table') . "' data-level='body_li_level_options' style='display:none;'></div>";
$tablestring .= "<div class='arp_btn action_btn' id='remove_row' row-id='' col-id='" . $col_no[1] . "' title='" . esc_html__('Delete Row', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Delete Row', 'arprice-responsive-pricing-table') . "' data-level='body_li_level_options' style='display:none;'>";

$tablestring .= "<div class='delete_row_container' id='delete_row_container_" . $col_no[1] . "'>";
$tablestring .= "<div class='delete_row_arrow'></div>";
$tablestring .= "<div class='delete_row_title'>";
$tablestring .= esc_html__('Are you sure want to delete this row?', 'arprice-responsive-pricing-table');
$tablestring .= "</div>";
$tablestring .= "<div class='delete_row_buttons'>";
$tablestring .= "<button id='Model_Delete_Row_Button_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_insert_btn delete_row' row-id=''>" . esc_html__('Ok', 'arprice-responsive-pricing-table') . "</button>";
$tablestring .= "<button id='Model_Delete_Row_Button_cancel_". $col_no[1] ."' col-id='" . $col_no[1] . "' type='button' class='ribbon_cancel_btn' row-id=''>" . esc_html__('Cancel', 'arprice-responsive-pricing-table') . "</button>";
$tablestring .= "</div>";
$tablestring .= "</div>";
$tablestring .= "</div>";

$tablestring .= "</div>";
/*body raw level option container over*/


//footer dbl click options

// Button Options
/*button level optoin container*/
$tablestring .= "<div class='footer_level_button_wrapper'>";

$tablestring .= "<div class='arp_btn' id='footer_level_options__button_1' data-level='footer_level_options' title='" . esc_html__('Footer General Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Footer General Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
$tablestring .= "<div class='arp_btn' id='footer_level_options__button_2' data-level='footer_level_options' title='" . esc_html__('Button General Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Button General Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
$tablestring .= "<div class='arp_btn' id='footer_level_options__button_3' data-level='footer_level_options' title='" . esc_html__('Button Image Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Button Image Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";
$tablestring .= "<div class='arp_btn' id='footer_level_options__button_4' data-level='footer_level_options' title='" . esc_html__('Button Link/Script Settings', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Button Link Settings', 'arprice-responsive-pricing-table') . "' style='display:none;'></div>";

$tablestring .= "</div>";
/*button level option container over*/

$tablestring .= "</div>";

$tablestring .= "<div class='column_level_options'>";

$tablestring .= "<div class='column_option_div' level-id='footer_level_options__button_1'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='column_level_options__button_1'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='column_level_options__button_2' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='header_level_options__button_1' >";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='header_level_options__button_2' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='pricing_level_options__button_1' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='pricing_level_options__button_2' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='pricing_level_options__button_3' style='display:none;'>";
$tablestring .= "</div>";

// BODY LEVEL OPTIONS
$tablestring .= "<input type='hidden' id='total_rows_" . $col_no[1] . "' value='" . esc_html( count($columns['rows']) ) . "' name='total_rows_" . $col_no[1] . "' />";

$tablestring .= "<div class='column_option_div' level-id='body_level_options__button_1' style='display:none;'>";
$tablestring .= '</div>';

// BODY LEVEL OPTIONS 2
$tablestring .= "<div class='column_option_div' level-id='body_level_options__button_2' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='column_description_level__button_1' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='body_level_options__button_3' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='button_options__button_4' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='footer_level_options__button_2' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='footer_level_options__button_3' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='footer_level_options__button_4' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div width_362' level-id='body_li_level_options__button_1' style='display:none;'>";
$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='body_li_level_options__button_2' style='display:none;'>";

$tablestring .= "</div>";

$tablestring .= "<div class='column_option_div' level-id='body_li_level_options__button_3' style='display:none;'>";

$tablestring .= "</div>";

$tablestring .= "</div>";
$tablestring .= "</div>";

?>