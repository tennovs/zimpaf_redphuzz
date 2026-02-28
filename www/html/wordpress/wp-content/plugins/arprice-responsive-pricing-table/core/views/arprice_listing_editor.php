<?php
if ( ! defined( 'ABSPATH' ) || !function_exists('current_user_can') || !current_user_can('arplite_view_pricingtables') || !isset($_GET['arplite_page_nonce']) || ( isset( $_GET['arplite_page_nonce']) && !wp_verify_nonce( $_GET['arplite_page_nonce'], 'arplite_page_nonce' ) )  ){
    exit;
}

global $arplite_pricingtable, $arpricelite_default_settings, $arpricelite_analytics, $arpricelite_fonts, $arpricelite_version, $arprice_font_awesome_icons, $arpricelite_img_css_version, $arplite_subscription_time,$arplite_tempbuttonsarr;

?>

<div style="display:none;">
</div>

<?php
/* ARPrice Font Awesome Icons */
require_once(ARPLITE_PRICINGTABLE_VIEWS_DIR . '/arprice_font_awesome_array.php');
$arprice_font_awesome_icons = arprice_font_awesome_font_array();

/* ARPrice Font Awesome Icons */

global $wpdb, $arpricelite_form, $arpricelite_fonts;
$arpaction = isset($_GET['arp_action']) ? esc_html( $_GET['arp_action'] ) : 'blank';
$arpreference = isset($_GET['ref']) ? esc_html( $_GET['ref'] ) : '';
$id = isset($_GET['eid']) ? intval( $_GET['eid'] ) : '';
$table_id = $id;

if (is_ssl()) {
    $googlefontpreviewurl = "https://www.google.com/fonts/specimen/";
} else {
    $googlefontpreviewurl = "http://www.google.com/fonts/specimen/";
}

$has_caption = 0;
$table_cols = -1;
$arp_template_type = '';
if ($arpaction == 'blank' && isset($_GET['arpaction']) && $_GET['arpaction'] == "") {
    $table_cols = -1;
} else if ($arpaction == 'create_new') {
    $table_name = isset( $_REQUEST['new_table_name'] ) ? sanitize_text_field( $_REQUEST['new_table_name'] ) : '';
    $table_cols = isset( $_REQUEST['no_of_cols'] ) ? intval( $_REQUEST['no_of_cols'] ) : -1;
    $table_rows = isset( $_REQUEST['no_of_rows'] ) ? intval( $_REQUEST['no_of_rows'] ) : 1;
    $has_caption = isset( $_REQUEST['has_caption'] ) ? intval( $_REQUEST['has_caption'] ) : 0;
    $arp_template_type = isset( $_REQUEST['template_type'] ) ? sanitize_text_field( $_REQUEST['template_type'] ) : 'normal';
    if ($table_cols == "") {
        $table_cols = 0;
    }
    if ($has_caption == "") {
        $has_caption = 0;
    }
}

if (isset($arpaction) and ( $arpaction == 'edit' or $arpaction == 'new') and isset($table_id) && $table_id) {
    $arpaction = 'edit';
    $id = $table_id;
} else if (isset($arpaction) and $arpaction == 'new') {
    $arpaction = 'new';
}
?>

<div class="main_box" >
    <form name="price_table" id="price_table_form" method="post" onsubmit="return check_package_validation();">
        <input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo admin_url('admin-ajax.php'); ?>"  />
        <input type="hidden" name="url" id="listing_url" value="admin.php?page=arpricelite" />
        <input type="hidden" name="template_type_old" id="template_type_old" value="<?php echo $id; ?>" />
        <input type="hidden" value="<?php echo $id; ?>" id="template_type_new" name="template_type_new">
        <input type="hidden" name="pricing_table_img_url" id="pricing_table_img_url" value="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>" />
        <input type="hidden" name="pricing_table_main_dir" id="pricing_table_main_dir" value="<?php echo ARPLITE_PRICINGTABLE_DIR; ?>"  />
        <input type="hidden" name="pricing_table_main_url" id="pricing_table_main_url" value="<?php echo ARPLITE_PRICINGTABLE_URL; ?>" />
        <input type="hidden" name="pricing_table_upload_dir" id="pricing_table_upload_dir" value="<?php echo ARPLITE_PRICINGTABLE_UPLOAD_DIR; ?>" />
        <input type="hidden" name="pricing_table_upload_url" id="pricing_table_upload_url" value="<?php echo ARPLITE_PRICINGTABLE_UPLOAD_URL; ?>" />
        <input type="hidden" name="pricing_table_admin" id="pricing_table_admin" value="<?php echo is_admin(); ?>" />
        <input type="hidden" name="arp_wp_version" id="arp_wp_version" value="<?php echo $GLOBALS['wp_version']; ?>" />
        <input type="hidden" name="arp_responsive_mobile_width" id="arp_responsive_mobile_width" value="<?php echo get_option('arplite_mobile_responsive_size'); ?>" />
        <input type="hidden" name="arp_responsive_tablet_width" id="arp_responsive_tablet_width" value="<?php echo get_option('arplite_tablet_responsive_size'); ?>" />
        <input type="hidden" name="arp_responsive_desktop_width" id="arp_responsive_desktop_width" value="<?php echo get_option('arplite_desktop_responsive_size'); ?>" />
        <input type="hidden" name="arp_version" id="arp_version" value="<?php global $arpricelite_version; echo $arpricelite_version; ?>" />
        <input type="hidden" name="arp_request_version" id="arp_request_version" value="<?php echo get_bloginfo('version'); ?>" />

        <?php $arplite_nonce_field = wp_create_nonce('arplite_wp_nonce'); ?>
        <input type="hidden" name="_wpnonce_arplite" value="<?php echo $arplite_nonce_field; ?>" />
        <?php

        $total_packages = 0;

        if ($arpaction == 'edit' or $arpaction == 'new') {
            global $wpdb, $arplite_mainoptionsarr;

            $sql = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = %d", $id));
            $table_name = $sql[0]->table_name;
            $is_template = $sql[0]->is_template;
            $status = $sql[0]->status;
            $template_name = $sql[0]->template_name;
            
            $table_gen_opt = maybe_unserialize($sql[0]->general_options);
            $arp_template = $table_gen_opt['template_setting']['template'];
            $arp_template_skin = $table_gen_opt['template_setting']['skin'];
            $arp_template_type = $table_gen_opt['template_setting']['template_type'];

            $sqls = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arplite_arprice_options WHERE table_id = %d", $id));
            $table_opt = $sqls[0]->table_options;
            $uns_table_opt = maybe_unserialize($table_opt);
            $total_packages = count($uns_table_opt['columns']);
            $caption_column = isset($uns_table_opt['columns']['column_0']['is_caption']) ? $uns_table_opt['columns']['column_0']['is_caption'] : '';
            $reference_template = $table_gen_opt['general_settings']['reference_template'];
            $template_feature = $arplite_mainoptionsarr['general_options']['template_options']['features'][$reference_template];

            if (is_array($template_feature) && in_array('column_description', $template_feature)) {
                $has_column_desc = 1;
                $col_desc_pos = array_search('column_description', $template_feature);
            } else {
                $has_column_desc = 0;
            }
            ?>
            <input type="hidden" name="is_template" id="is_template" value="<?php echo $is_template; ?>"/>
            <input type="hidden" name="pt_action" id="pt_action" value="<?php echo esc_html( $_GET['arp_action'] ); ?>" />
            <input type="hidden" name="added_package" id="total_packages" value="<?php echo $total_packages; ?>" />
            <input type="hidden" name="table_id" id="table_id" value="<?php echo $id; ?>" />
            <input type="hidden" name="arp_template_type" id="arp_template_type" value="<?php echo $arp_template_type; ?>" />
            <input type="hidden" name="has_caption_column" id="has_caption_column" value="<?php echo $caption_column; ?>"  />
            <input type="hidden" name="template_feature" id="arp_template_feature" value='<?php echo stripslashes(json_encode($template_feature)); ?>' />
            <?php $column_order = str_replace('"', '\'', $table_gen_opt['general_settings']['column_order']); ?>
            <input type="hidden" name="pricing_table_column_order" id="pricing_table_column_order" value="<?php echo $column_order; ?>" />
            <input type="hidden" name="arp_reference_template" id="arp_reference_template" value="<?php echo $reference_template; ?>" />
            <?php $user_edited_columns = ( $table_gen_opt['general_settings']['user_edited_columns'] == '' ) ? '' : stripslashes(json_encode($table_gen_opt['general_settings']['user_edited_columns'])); ?>
            <input type="hidden" name="arp_user_edited_columns" id="arp_user_edited_columns" value='<?php echo $user_edited_columns; ?>' />
            <?php
        } else {
            global $wpdb, $arplite_mainoptionsarr;
            $template_feature = $arplite_mainoptionsarr['general_options']['template_options']['features']['arplitetemplate_1'];
            ?>
            <input type="hidden" name="is_template" id="is_template" value="0" />
            <input type="hidden" name="pt_action" id="pt_action" value="new" />
            <input type="hidden" name="added_package" id="total_packages" value="<?php echo ($table_cols + $has_caption); ?>" />
            <input type="hidden" name="pt_coloumn_order" id="pt_coloumn_order" value="" />
            <input type="hidden" name="table_id" id="table_id" value="" />
            <input type="hidden" name="arp_template_type" id="arp_template_type" value="<?php echo $arp_template_type; ?>" />
            <input type="hidden" name="has_caption_column" id="has_caption_column" value="<?php echo $has_caption; ?>"  />
            <input type="hidden" name="template_feature" id="arp_template_feature" value='<?php echo stripslashes(json_encode($template_feature)); ?>' />
            <input type="hidden" name="pricing_table_column_order" id="pricing_table_column_order" value="" />
            <input type="hidden" name="arp_reference_template" id="arp_reference_template" value="" />
            <input type="hidden" name="arp_user_edited_columns" id="arp_user_edited_columns" value="" />
            <?php
        }
        global $arplite_mainoptionsarr, $arpricelite_form, $wp_version;
        $pricingtable_menu_belt_style = '';
        if ($arpaction == 'edit') {
            $pricingtable_menu_belt_style = 'display:block;';
        }
        ?>
        <div class="pricingtablename">


            <div class="empty">	</div>

            <div class="success_message" id="success_message"> 
                <div class="message_descripiton"><?php esc_html_e('Pricing table saved successfully.', 'arprice-responsive-pricing-table'); ?></div>		
            </div>

            <div class="editor_error_message" id="editor_error_messag">
                <div class="message_descripiton"></div>
            </div>

            <div class="repute_pricing_table_content">
                <?php
                global $wpdb;

                $animated_template = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE is_animated = 1 ORDER BY ID ASC");
                ?>
                <div class="arprice_editor" id="arprice_editor" style="">


                    <div class="main_package_part">

                        <div id="main_package_div">

                            <div class="main_package" id="main_package">
                                <div class="ex" style="">
                                    <ul id="packages">
                                        <?php
                                        if ($arpaction == 'create_new') {
                                            global $arpricelite_form;
                                            $columns = ($has_caption != "") ? ($table_cols + 1) : $table_cols;
                                            $arpricelite_form->arp_pricing_table_new_form($columns, $table_rows, $has_caption, $arp_template);
                                        } else if ($arpaction == 'edit' || $arpaction == 'new') {
                                            require_once ARPLITE_PRICINGTABLE_DIR . '/core/classes/class.arprice_preview_editor.php';
                                            global $arpricelite_form, $wpdb;
                                            echo arp_get_pricing_table_string_editor($id, $table_name, 2);
                                        }
                                        ?>
                                    </ul>
                                    <div style="height:auto;width:10px;float:left;"></div>



                                    <div id="addnewpackage_loader"> </div>
                                    <?php
                                    if ($total_packages > 3) {
                                        $disable_actual_btn = 'display:none;';
                                        $enable_loacked_btn = 'display:block;';
                                    } else {
                                        $disable_actual_btn = 'display:block;';
                                        $enable_loacked_btn = 'display:none;';
                                    }
                                    ?>
                                    <div class="add_new_package arplite_unlocked enabled" align="center" id="addnewpackage" style="<?php echo $disable_actual_btn; ?>">
                                        <label class="add_new_package_label"><?php esc_html_e('Add Column', 'arprice-responsive-pricing-table'); ?></label>
                                        <div class="add_new_package_icon">
                                            <span class="fa-stack fa-5x">
                                                <i class="far fa-circle fa-stack-2x"></i>
                                                <i class="fas fa-plus fa-stack-1x"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="add_new_package arplite_locked enabled" align="center" id="addnewpackage" style="<?php echo $enable_loacked_btn; ?>">
                                        <label class="add_new_package_label"><?php esc_html_e('Add Column', 'arprice-responsive-pricing-table'); ?></label>
                                        <div class="add_new_package_icon">
                                            <span class="fa-stack fa-5x">
                                                <i class="far fa-circle fa-stack-2x"></i>
                                                <i class="fas fa-lock fa-stack-1x"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div style="height:10px;"></div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="empty">	</div>


            <input type="hidden" name="arp_is_generate_html_canvas" id="arp_is_generate_html_canvas" value="no" />
        </div>
    </form>

    <div style="clear:both;"></div>

    <div class="arp_loader" id="arp_loader_div">
        <div class="arp_loader_img"></div>
    </div>

</div>

<div id="testingpre"></div>

<form  name="arp_export" method="post" action="" id="arp_editor_export">
    <?php $arplite_nonce = wp_create_nonce('arplite_wp_nonce'); ?>
    <input type="hidden" name="_wpnonce_arplite" value="<?php echo esc_html( $arplite_nonce ); ?>">
    <input type="hidden" name="arplite_export_tables" id="arplite_export_tables" value="arplite_export_tables">
    <input type="hidden" name="table_to_export[]" id="table_to_export" value="<?php echo $id; ?>">
</form>

<div style="clear:both;"></div>

<div id="arp_fileupload_iframe" class="arp_modal_box" style="display:none; height:430px; width:800px;">
    <div class="modal_top_belt">
        <span class="modal_title"><?php esc_html_e('Choose File', 'arprice-responsive-pricing-table'); ?></span>
        <span class="modal_close_btn b-close"></span>
    </div>
    <div id="arp_iframeContent">
    </div>
</div>

<?php /* ARPrice Modal Windows */ ?>

<!-- Pricing Table Preview -->
<input type="hidden" id="arpcol_insert" />
<input type="hidden" id="arpcol_to_insert_object" />
<div class="arp_model_box" id="arp_pricing_table_preview" style="display:none;background:white;">
    <div class="arp_model_preview_belt">
        <div class="device_icon active" id="computer_icon"></div>
        <div class="device_icon" id="tablet_icon"></div>
        <div class="device_icon" id="mobile_icon"></div>
        <div class="preview_close" id="prev_close_icon">
            <span class="modal_close_btn b-close"></span>
        </div>
    </div>
    <div class="preview_model" style="float:left;width:100%;height:90%;">

    </div>
</div>
<!-- Pricing Table Preview -->

<!-- Ribbon Modal -->
<?php global $arplite_mainoptionsarr; ?>
<div class="arp_model_box" id="arp_ribbon_modal_window" style="top:50px;">
    <form name="arp_ribbon_settings" onsubmit="return add_column_ribbon();" id="arp_ribbon_settings">
        <input type="hidden" value="" id="arp_ribbon_to_insert_column" />
        <input type="hidden" value="" id="arp_ribbon_bg_color" />
        <input type="hidden" value="" id="arp_ribbon_textcolor" />
        <div class="modal_top_belt">
            <span class="modal_title"><?php esc_html_e('Select Ribbon', 'arprice-responsive-pricing-table'); ?></span>
            <span class="modal_close_btn b-close"></span>
        </div>
        <div class="arp_ribbon_modal_content" style="height:525px;">
            <div class="arp_ribbon_text_title single" style="padding:5px 5px 5px 38px;height:auto;">
                <div class="arp_select_ribbon_dropdown_menu" id="arp_select_ribbon_dropdown_menu">
                    <span class="arp_ribbon_text_title single"><?php esc_html_e('Ribbon Style', 'arprice-responsive-pricing-table'); ?></span>
                    <input type="hidden" id="arp_ribbon_style" />
                    <dl id="arp_ribbon_style" class="arp_selectbox" data-id="arp_ribbon_style" data-name="arp_ribbon_style" style="width:75% !important;margin-top:15px;float:left;">
                        <dt>
                        <span><?php esc_html_e('Select Ribbon', 'arprice-responsive-pricing-table'); ?></span>
                        <input type="text" value="<?php echo 'Select Ribbon'; ?>" style="display:none;" class="arp_autocomplete" />
                        <i class='fas fa-caret-down fa-md'></i>
                        </dt>
                        <dd>
                            <ul class="arp_ribbon_style" data-id="arp_ribbon_style">
                                <ol class="arp_selectbox_group_label"><?php esc_html_e('Preset Ribbons', 'arprice-responsive-pricing-table'); ?></ol>
                                <?php
                                foreach ($arplite_mainoptionsarr['general_options']['template_options']['arp_ribbons'] as $value => $label) {
                                    if ($value == 'arp_ribbon_6') {
                                        ?>
                                        <ol class="arp_selectbox_group_label"><?php esc_html_e('Custom Ribbon', 'arprice-responsive-pricing-table'); ?></ol>
                                        <li class="arp_selectbox_option arp_ribbon_icons" id="arp_ribbon_icons" data-ribbon="<?php echo $value; ?>" data-label="<?php echo esc_html($label); ?>" data-value="<?php echo esc_html($value); ?>"><?php echo $label; ?></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li class="arp_selectbox_option arp_ribbon_icons" id="arp_ribbon_icons" data-ribbon="<?php echo $value; ?>" data-label="<?php echo esc_html($label); ?>" data-value="<?php echo esc_html($value); ?>"><?php echo $label; ?></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </dd>
                    </dl>

                    <span class="arp_ribbon_text_title single"><?php esc_html_e('Ribbon Position', 'arprice-responsive-pricing-table'); ?></span>
                    <dl style="width:75% !important;float:left;" data-id="arp_ribbon_position" data-name="arp_ribbon_position" id="select_arp_ribbon_position" class="arp_selectbox">
                        <dt><span style="float: left; max-width: 100px;"><?php esc_html_e('Right', 'arprice-responsive-pricing-table'); ?></span><input type="text" value="Right" class="arp_autocomplete" style="display: none;" id='arp_ribbon_position'><i class="fas fa-caret-down fa-md"></i></dt>
                        <dd>
                            <ul style="margin-top: 18px; display: none;" data-id="arp_ribbon_position">
                                <li data-label="<?php esc_html_e('Right', 'arprice-responsive-pricing-table'); ?>" data-value="right"><?php esc_html_e('Right', 'arprice-responsive-pricing-table'); ?></li>
                                <li data-label="<?php esc_html_e('Left', 'arprice-responsive-pricing-table'); ?>" data-value="left"><?php esc_html_e('Left', 'arprice-responsive-pricing-table'); ?></li>
                            </ul>
                        </dd>
                    </dl>
                </div>

                <div class="arp_selected_ribbon_preview" id="arp_selected_ribbon_preview">
                    <style id="preview_arp_ribbon_1">
                        .arp_ribbon_style_preview_container .arp_ribbon_content.arp_ribbon_1:before,
                        .arp_ribbon_style_preview_container .arp_ribbon_content.arp_ribbon_1:after{
                            border-top-color:#0c0b0b;
                        }

                        .arp_ribbon_style_preview_container .arp_ribbon_content.arp_ribbon_1{
                            background:#0c0b0b;
                            background-color:#0c0b0b;
                            background-image:-moz-linear-gradient(0deg,#0c0b0b,#514e4e,#0c0b0b);
                            background-image:-webkit-gradient(linear, 0 0, 0 0, color-stop(0%,#0c0b0b), color-stop(50%,#514e4e), color-stop(100%,#0c0b0b));
                            background-image:-webkit-linear-gradient(left,#0c0b0b 0%, #514e4e 51%, #0c0b0b 100%);
                            background-image:-o-linear-gradient(left,#0c0b0b 0%, #514e4e 51%, #0c0b0b 100%);
                            background-image:linear-gradient(90deg,#0c0b0b,#514e4e, #0c0b0b);
                            background-image:-ms-linear-gradient(left,#0c0b0b,#514e4e, #0c0b0b);
                            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#514e4e', endColorstr='#0c0b0b', GradientType=1);
                            -ms-filter: "progid:DXImageTransform.Microsoft.gradient (startColorstr="#514e4e", endColorstr="#0c0b0b", GradientType=1)";
                            background-repeat:repeat-x;
                            border-top:1px solid #1a1818;
                            box-shadow:13px 1px 2px rgba(0,0,0,0.6);
                            -webkit-box-shadow:13px 1px 2px rgba(0,0,0,0.6);
                            -moz-box-shadow:13px 1px 2px rgba(0,0,0,0.6);
                            -o-box-shadow:13px 1px 2px rgba(0,0,0,0.6);
                            color:#ffffff;
                            text-shadow:0 0 1px rgba(0,0,0,0.4);
                        }
                    </style>
                    <div id="arp_ribbon_style_preview" class="arp_ribbon_style_preview_container">
                        <div class="arp_ribbon_container arp_ribbon_right arp_ribbon_1">
                            <div class="arp_ribbon_content arp_ribbon_right arp_ribbon_1">
                                <span>20% off</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="arp_ribbon_text_content" id="arp_ribbon_text"  style="margin-top:0px;">
                <div class="arp_ribbon_text_title single"><?php esc_html_e('Ribbon Text', 'arprice-responsive-pricing-table'); ?></div>
                <div class="arp_ribbon_text_input single">
                    <input type="text" id="arp_ribbon_content" data-column-step="first" value="20% Off" class="arp_modal_txtbox ribbon_content_txt" />
                </div>
            </div>

            <div class="arp_ribbon_text_content single" id="arp_ribbon_background_color_title" style="margin-top:20px;">
                <span style="font-family:Open Sans Bold;font-size:14px;"><?php esc_html_e('Set Colors', 'arprice-responsive-pricing-table'); ?></span>
            </div>

            <div class="arp_ribbon_text_content multiple" id="arp_ribbon_background_color" style="width:25%;padding-right:0px;">
                <div class="arp_ribbon_text_input multiple" style="width:95%;">
                    <div class="arp_ribbon_bgcolor_wrapper" id="arp_ribbon_bgcolor_wrapper">
                        <input type="text" id="arp_ribbon_bgcolor" name="arp_ribbon_bgcolor" value="#514e4e" />
                        <div class="arp_ribbon_bgcolor_picker"><i class="fas fa-eye-dropper fa-lg"></i></div>
                    </div>
                </div>
                <div class="arp_ribbon_text_title single" style="font-family:Ubuntu;line-height:normal;width:90%;text-align:center;"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
            </div>

            <div class="arp_ribbon_text_content multiple" id="arp_ribbon_text_color" style="width:22%;padding-left:10px;padding-right:6px;">
                <div class="arp_ribbon_text_input multiple" style="width:95%;">
                    <div class="arp_ribbon_txtcolor_wrapper" id="arp_ribbon_txtcolor_wrapper">
                        <input type="text" id="arp_ribbon_txtcolor" name="arp_ribbon_textcolor" value="#ffffffff" />
                        <div class="arp_ribbon_textcolor_picker"><i class="fas fa-eye-dropper fa-lg"></i></div>
                    </div>
                </div>
                <div class="arp_ribbon_text_title single" style="font-family:Ubuntu;line-height:normal;width:90%;text-align:center;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>
            </div>

            <div class="arp_ribbon_text_content single" id="arp_ribbon_custom_image" style="display: none;margin-top:0px;">
                <div class="arp_ribbon_text_title single"><?php esc_html_e('Custom Ribbon', 'arprice-responsive-pricing-table'); ?></div>
                <div class="arp_ribbon_text_input multiple" style="position: relative; top: 0px;margin-top:0px;">
                    <div class="arp_ribbon_txtcolor_wrapper">
                        <input type="text" id="arp_ribbon_content_custom" value="" class="arp_modal_txtbox custom_ribbon_img" style="width:50% !important;" />
                        <button data-column="" class="add_arp_ribbon_object" tyle="button" name="add_arp_ribbon_object" id="add_arp_ribbon_object" data-insert='arp_ribbon_image_object' data-id="arp_ribbon_image_url"><?php esc_html_e('Add Ribbon', 'arprice-responsive-pricing-table'); ?></button>
                    </div>
                </div>
            </div>

            <div style="float:left;width:100%;display:none;" id="ribbon_custom_position" >
                <div class="arp_ribbon_text_content">
                    <div class="arp_ribbon_text_title"><?php esc_html_e('Custom Position:', 'arprice-responsive-pricing-table'); ?></div>
                </div>
                <div class="arp_ribbon_text_content multiple" style="box-sizing:border-box;width:22%;margin-top:16px;">
                    <div class="arp_ribbon_text_input single" style="position:relative;top:-5px;line-height:35px;">
                        <input type="text" name="arp_ribbon_custom_position_rl" id="arp_ribbon_custom_position_rl_modal" class="arp_modal_txtbox" value="0" style="width:60px;margin-right:5px;" /><?php esc_html_e('Px', 'arprice-responsive-pricing-table'); ?>
                    </div>
                    <div class="arp_ribbon_text_title single" style="font-family:ubuntu;line-height:normal;"><?php esc_html_e('Left / Right', 'arprice-responsive-pricing-table'); ?></div>
                </div>
                <div class="arp_ribbon_text_content multiple" style="box-sizing:border-box;width:22%;margin-top:16px;">
                    <div class="arp_ribbon_text_input single" style="position:relative;top:-5px;line-height:35px;">
                        <input type="text" name="arp_ribbon_custom_position_top" id="arp_ribbon_custom_position_top_modal" class="arp_modal_txtbox" value="0" style="width:60px;margin-right:5px;" /><?php esc_html_e('Px', 'arprice-responsive-pricing-table'); ?>
                    </div>
                    <div class="arp_ribbon_text_title single" style="font-family:ubuntu;line-height:normal;">
                        <?php esc_html_e('Top', 'arprice-responsive-pricing-table'); ?>
                    </div>
                </div>
            </div>

            <div class="arp_ribbon_btn_content">
                <div class="arp_ribbon_btn">
                    <button type="submit" name="add_ribbon_insert" id="add_ribbon_insert" class="ribbon_insert_btn">
                        <?php esc_html_e('Add Ribbon', 'arprice-responsive-pricing-table') ?>
                    </button>
                </div>
                <div class="arp_ribbon_btn">
                    <button type="button" name="add_ribbon_cancel" id="add_ribbon_cancel" class="ribbon_cancel_btn">
                        <?php esc_html_e('Cancel', 'arprice-responsive-pricing-table'); ?>
                    </button>
                </div>

            </div>
        </div>

        <div class="arp_ribbon_colorpicker_wrapper" id="arp_ribbon_colorpicker_wrapper" data-insert="arp_rbn_textcolor">
            <div class="arp_ribbon_colorpicker" id="arp_ribbon_colorpicker">
                <div class="ribbon_modal_top_belt">
                    <span class="modal_title"><?php esc_html_e('Choose Color', 'arprice-responsive-pricing-table'); ?></span>
                    <span class="ribbon_modal_close_btn"><i class="fas fa-times"></i></span>
                </div>
                <div class="arp_ribbon_colorpicker_tabs">
                    <div class="arp_basic_color_tab" id="arp_basic_color_tab">
                        <?php
                        global $arplite_mainoptionsarr;

                        $basic_colors = $arplite_mainoptionsarr['general_options']['arp_basic_colors'];
                        ?>
                        <ul class="arp_basic_colors">
                            <?php
                            foreach ($basic_colors as $key => $colors) {
                                ?>

                                <li class="basic_color_box basic_color_<?php echo $key; ?>" title="<?php echo $colors; ?>" data-color="<?php echo $colors; ?>" >&nbsp;</li>
                                <?php
                            }
                            ?>
                        </ul>
                        <div class="arp_ribbon_colorpicker_okbtn">
                            <button type="button" id="arp_close_colorpicker" class='col_opt_btn' style="float:right;margin-right:10px;position:relative;top:-10px !important;"><?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?></button>
                        </div>
                    </div>
                    <div class="arp_advanced_color_tab" id="arp_advanced_color_tab" data-insert="">
                        <div class="arp_advanced_color_picker arplite_jscolor" id='arp_advanced_color_picker' data-elm='arp_ribbon_txtcolor' data-color="#ffffff" data-jscolor="{hash:true,onInput:'arp_update_color(this,arp_advanced_color_picker)',valueElement:'#arp_ribbon_txtcolor'}">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Ribbon Modal -->
<?php
global $arplite_coloptionsarr;

$header_options = isset($arplite_coloptionsarr['header_options']) ? $arplite_coloptionsarr['header_options'] : array();
?>

<input type="hidden" name="shortcode_to_insert" id="shortcode_to_insert" value="" />
<div class="arp_admin_modal_overlay"></div>
<div class="arp_modal_box arp_offset_container" id="new_template_modal">
    <div class="modal_top_belt">
        <span class="modal_title"><?php esc_html_e('Add Shortcode', 'arprice-responsive-pricing-table'); ?></span>
        <span class="arp_modal_close_btn b-close"></span>
    </div>
    
    <form name="add_header_shortcode_form" id="add_header_shortcode_form" method="POST" onsubmit="return add_headershortcodeform();">
        <input type="hidden" name="arpaction" id="arpaction" value="create_new" />
        <input type="hidden" name="page" value="arp_add_pricing_table" />
        <input type="hidden" name="arp_shortcode_types_hidden" id="arp_shortcode_types_hidden" value='<?php echo wp_json_encode($header_options['html_shortcode_options']); ?>' />
        <input type="hidden" name="arp_shortcode_type_value" id="arp_shortcode_type_value" value="" />
        <input type="hidden" name="arpcol_insert_header" id="arpcol_insert_header" value="" />
        <div class="arp_modal_content shortcode_modal_content">
            <div class="modal_content_inner">

                <div class="modal_content_row">
                    <div class="modal_content_cell" style="width:70%;">
                        <div class="modal_content_label"><?php esc_html_e('Create Shortcode', 'arprice-responsive-pricing-table'); ?></div>
                        <div class="modal_content_input" id="arp_shortcode_type_dd">
                        </div>
                    </div>
                    <div class="modal_content_cell">
                    </div>
                </div>

                <!-- Header Shortcode Image -->

                <div id="arp_image_shortcode_div" class="arp_shortcode_div" style="display:none;margin-top: 20px;">
                    <?php
                    if ($header_options['image_shortcode_options']) {
                        foreach ($header_options['image_shortcode_options'] as $field_id => $field_title) {
                            ?>
                            <div class="modal_content_row">
                                <div class="modal_content_cell">
                                    <label class="modal_content_label" for="arp_image_<?php echo $field_id; ?>"><?php echo $field_title; ?></label>
                                    <?php
                                    if ($field_id == 'url') {
                                        ?>
                                        <div class="modal_content_input">
                                            <input type="text" name="arp_image_<?php echo $field_id; ?>" id="arp_image_text_<?php echo $field_id; ?>" class="arp_modal_txtbox img" />
                                            <button data-insert="image" data-id="arp_image_url" type="button" id="arp_image_btn_<?php echo $field_id; ?>" class="arp_modal_add_file_btn"><?php esc_html_e('Add File', 'arprice-responsive-pricing-table'); ?></button>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="modal_content_input">
                                            <input type="text" name="arp_image_<?php echo $field_id; ?>" id="arp_image_<?php echo $field_id; ?>" class="arp_modal_txtbox" />
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <div class="modal_content_row">
                        <div class="modal_content_cell shortcode_modal_content_cell">
                            <div class="modal_content_input modal_single shortcode_chk_div">
                                <span class='arp_price_checkbox_wrapper'>
                                    <input type="checkbox" name="arp_image_open_lightbox" id="arp_image_open_lightbox" class="arp_checkbox light_bg modal_single" value="1" />
                                    <span></span>
                                </span>
                            </div>
                            <label for="arp_image_open_lightbox"  class="modal_content_label modal_single shortcode_box_label"><?php esc_html_e('Open in Lightbox', 'arprice-responsive-pricing-table'); ?></label>
                        </div>
                    </div>


                </div>

                <!-- Header Shortcode Image -->
            </div>
        </div>
    </form>

    <div class="arp_shortcode_modal_footer_container">
        <div id="arp_image_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: block;">
            <input type="submit" class="arp_modal_insert_shortcode_btn" name="arp_image_btn" onclick="jQuery('#add_header_shortcode_form').submit()" id="arp_image_btn" value="<?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?>">    
        </div>
        
        <div id="arp_youtube_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" class="arp_modal_insert_shortcode_btn" name="arp_youtube_btn" id="arp_youtube_btn" onclick="jQuery('#add_header_shortcode_form').submit()"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_vimeo_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_vimeo_btn" id="arp_vimeo_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_video_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_video_btn" id="arp_video_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_dailymotion_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_dailymotion_btn" id="arp_dailymotion_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_metacafe_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_metacafe_btn" id="arp_metacafe_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_audio_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_audio_btn" id="arp_audio_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_soundcloud_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_soundcloud_btn" id="arp_soundcloud_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_mixcloud_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_mixcloud_btn" id="arp_mixcloud_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_beatport_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_beatport_btn" id="arp_beatport_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_googlemap_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_googlemap_btn" id="arp_googlemap_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

        <div id="arp_embed_btn_div" class="arp_shortcode_modal_footer_container_div" style="display: none;">
            <button type="submit" onclick="jQuery('#add_header_shortcode_form').submit()" class="arp_modal_insert_shortcode_btn" name="arp_embed_btn" id="arp_embed_btn"><?php esc_html_e('Insert Shortcode', 'arprice-responsive-pricing-table') ?></button>
        </div>

    </div>
</div>


<!-- Remove column -->
<div class="arp_model_delete_box" id="arp_remove_column_last" style="display:none;background:white;">
    <div class="modal_top_belt">
        <span class="modal_title"><?php esc_html_e('Delete Column', 'arprice-responsive-pricing-table'); ?></span>
        <span id="nav_style_close" class="modal_close_btn b-close"></span>
    </div>
    <div class="arp_modal_delete_content">
        <div class="arp_delete_modal_msg"><?php esc_html_e("You can not delete all columns", 'arprice-responsive-pricing-table'); ?></div>
        <div class="arp_delete_modal_btn">
            <button id="Model_Delete_Column_last"  class="ribbon_insert_btn Model_Delete_Column_last_btn" type="button"><?php esc_html_e("Okay", 'arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<!-- Remove column -->



<!-- Tour Guide Model -->
<div class="arp_model_delete_box" id="arp_tour_guide_model" style="display:none;background:white;">
    <div class="modal_top_belt">
        <span class="modal_title"><?php esc_html_e('ARPrice Guided Tour', 'arprice-responsive-pricing-table'); ?></span>
        <span id="nav_style_close" class="arp_tour_guide_start_model modal_close_btn b-close"></span>
    </div>

    <div class="arp_modal_delete_content">
        <div class="arp_delete_modal_msg"><?php esc_html_e('Please take a quick tour of basic functionalities.', 'arprice-responsive-pricing-table'); ?></div>

        <div class="arp_delete_modal_btn">
            <button id="arp_tour_guide_start_yes" class="arp_tour_guide_start_model ribbon_insert_btn b-close" type="button"><?php esc_html_e('Start Tour', 'arprice-responsive-pricing-table'); ?></button>
            <button id="arp_tour_guide_start_no" class="arp_tour_guide_start_model ribbon_insert_btn b-close" type="button" style="background:#373a3f;"><?php esc_html_e('Skip Tour', 'arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<!-- Tour Guide Model -->

<!-- ARPrice Font Icons Model -->
<input type="hidden" name="fa_to_insertcol" id="fa_to_insertcol" value="" />
<input type="hidden" name="fa_to_insertrow" id="fa_to_insertrow" value="" />
<input type="hidden" name="fa_to_inserttooltip" id="fa_to_inserttooltip" value="" />
<input type="hidden" name="fa_to_insertlabel" id="fa_to_insertlabel" value="" />
<input type="hidden" name="fontselected_1" id="fontselected_1" value="" />
<input type="hidden" name="fontselected_2" id="fontselected_2" value="" />
<input type="hidden" name="add_to_sec_btn" id="add_to_sec_btn" value="" />
<input type="hidden" name="arp_fa_text" id="arp_fa_text" value="" />
<input type="hidden" name="arpcol_to_insert_font" id="arpcol_to_insert_font" value="" />
<input type="hidden" name="arpcol_insert_font" id="arpcol_insert_font" value="" />
<div class="arp_font_icons" id="arp_font_icons" style="display:none;">

    <?php
    $fonticon = '';
    $fonticon .= "<div class='arp_font_awesome_arrow'></div>";
    $fonticon .= "<div class='font_awesome_icon_list'>";
    $fonticon .= "<div class='arp_icon_search'><input class='arp_icon_search_input' id='arp_icon_search_input' name='arp_icon_search_input' placeholder='search' /></div>";
    foreach ($arprice_font_awesome_icons as $name => $icon) {

        if ($name == 'font_awesome') {
            $fonticon .= '<div class="arp_icon_text_title" id="arp_font_awaesome_icon">Font Awesome</span></div><div class="clear"></div>';
            $is_enable_font_awesome = get_option('enable_font_loading_icon');
            if( is_array($is_enable_font_awesome) && in_array('enable_fontawesome_icon', $is_enable_font_awesome)){
                foreach ($icon as $icon_name => $icon_class) {
                
                    $ico_cls = ( isset($icon_class['code']) && $icon_class['code'] != '' ) ? $icon_class['code'] : '';
                    $ico_style = ( isset($icon_class['style']) && $icon_class['style'] != '' ) ? $icon_class['style'] : '';
                    $fonticon .= "<div class='arp_fainsideimge' data-icon='fontawesome' id='" . $ico_cls . "' title='" . $icon_name . "'>";
                    $fonticon .= "<i class='".$ico_style." ". $ico_cls . "'></i>";

                    $fonticon .= "</div>";
                }    
            }else{
                $fonticon .= "<span class='font_icons_notice'>" . esc_html__('Please enable Font Awesome icon.', 'arprice-responsive-pricing-table') . "</span>";
            }
            
        }
        if ($name == 'material_design') {
            $fonticon .= '<div class="clear"></div><div class="arp_icon_text_title" id="arp_font_material_icon">Material Design Icons</div><div class="clear"></div>';

            $fonticon .= "<span class='font_icons_notice'>" . esc_html__('Please upgrade to premium version to use this icons', 'arprice-responsive-pricing-table') . "</span>";
        }
        if ($name == 'typicons') {
            $fonticon .= '<div class="clear"></div><div class="arp_icon_text_title" id="arp_font_typicons_icon">Typicons</div><div class="clear"></div>';

            $fonticon .= "<span class='font_icons_notice'>" . esc_html__('Please upgrade to premium version to use this icons', 'arprice-responsive-pricing-table') . "</span>";
        }
        if ($name == 'ionicons') {
            $fonticon .= '<div class="clear"></div><div class="arp_icon_text_title" id="arp_font_ionicons_icon">Ionicons</div><div class="clear"></div>';

            $fonticon .= "<span class='font_icons_notice'>" . esc_html__('Please upgrade to premium version to use this icons', 'arprice-responsive-pricing-table') . "</span>";
        }
    }
    $fonticon .= "</div>";
    $fonticon .= "<div class='arp_fontawesome_preview_div' style='display:none;'>";
    
    $fonticon .= "</div>";
    echo $fonticon;
    ?>
</div>
<!-- ARPrice Font Icons Model -->
<?php /* ARPrice Modal Windows */ ?>



<!-- ARPrice Pro Version Notice -->
<div class="arp_upgrade_modal" id="arplite_addnew_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('You can create maximum 4 columns in free version', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
    </div>
</div>
<div class="arp_upgrade_modal" id="arplite_custom_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('To unlock this Feature, Buy Premium Version for $27.00 Only.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
    </div>
</div>
<div class="arp_upgrade_modal" id="arplite_custom_css_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('To unlock this Feature, Buy Premium Version for $27.00 Only.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
    </div>
</div>
<div class="arp_upgrade_modal" id="arplite_ribbon_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('To unlock this Feature, Buy Premium Version for $27.00 Only.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
    </div>
</div>
<div class="arp_upgrade_modal" id="arplite_save_table_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('To unlock this Feature, Buy Premium Version for $27.00 Only.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
    </div>
</div>

<?php
    
    $sql = $wpdb->get_row($wpdb->prepare("SELECT general_options FROM " . $wpdb->prefix . "arplite_arprice WHERE ID = %d AND status = %s ", $id, 'published'));
    $table_id = isset($sql->ID) ? $sql->ID : '';
    $general_option = maybe_unserialize($sql->general_options);
    $arp_label_btn_style = "float:left;";
    $general_settings = $general_option['general_settings'];
    $ref_template = $general_settings['reference_template'];
    $column_settings = $general_option['column_settings'];
?>
<!-- arp_row_description_skeleton -->
<div class="arp_row_description_skeleton" style="display: none;">
    <div class="arp_row_wrapper" id="arp_{row_id}">
        <div class="col_opt_row arp_{row_id} arp_hide_on_caption width_342" id="arp_li_content_type{row_no}" style="display:none;">

            <div class="col_opt_input_div width_342 col_opt_input_div_bottom_margin">

                <span class="arp_price_radio_wrapper_standard arp_radio_dark_bg">

                    <input type="radio" class="arp_checkbox dark_bg arp_content_type_options arp_content_type_html" value="0" id="row_content_type0_{col_no}_{row_no}" name="row_{col_no}_content_type_{row_no}" data-column="main_{col_id}" />
                    
                    <span></span>
                    
                    <label id="row_content_html_{col_no}_{row_no}" for="row_content_type0_{col_no}_{row_no}"><?php esc_html_e("HTML/Text", 'arprice-responsive-pricing-table') ?></label>

                </span>

                <span class="arp_price_radio_wrapper_standard arp_radio_dark_bg">

                    <input type="radio" class="arp_checkbox dark_bg arp_content_type_options arp_content_type_btn" value="1" id="row_content_type1_{col_no}_{row_no}" name="row_{col_no}_content_type_{row_no}" data-column="main_{col_id}" />

                    <span></span>

                    <label id="row_content_html_{col_no}_{row_no}" for="row_content_type1_{col_no}_{row_no}"><?php esc_html_e("Button", 'arprice-responsive-pricing-table') ?></label>

                </span>

                <span class='pro_version_info row_level_pro_notice'>(Pro Version)</span>
            </div>
        </div>
    </div>

    <div class="col_opt_row arp_{row_id} width_342" id="description{row_no}">
        <div class="col_opt_title_div"><?php esc_html_e('Description','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div width_342">
            <div class="option_tab" id="description_yearly_tab">
                <textarea name="row_{col_no}_description_{row_no}" id="arp_li_description" data-column-step="first" data-column="main_{col_id}" class="col_opt_textarea row_description_first"></textarea>
            </div>
        </div>
    </div>

    <div class="col_opt_row arp_{row_id} width_342" id="body_li_add_shortcode{row_no}">
        <div class="col_opt_btn_div">
            <button type='button' class='col_opt_btn_icon arp_add_row_object arptooltipster align_left' name='{col_no}_add_body_li_object_{row_no}' id='arp_add_row_object' data-insert='arp_{row_id} textarea#arp_li_description' data-column='main_{col_id}' title='<?php esc_html_e('Add Media', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Add Media', 'arprice-responsive-pricing-table'); ?>'>
            </button>
            <label style='float:left;width:10px;'>&nbsp;</label>
            <button type='button' class='col_opt_btn_icon arp_add_row_shortcode arptooltipster align_left' name='{col_no}_add_description_shortcode_btn_{row_no}' id='arp_add_row_shortcode' data-id='{col_no}' column-id='{col_no}' data-row-id="{row_id}" title='<?php esc_html_e('Add Font Icon', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Add Font Icon', 'arprice-responsive-pricing-table'); ?>'>
            </button>

            <div class='arp_font_awesome_model_box_container'></div>

            <div class='arp_add_image_container'>
                <div class='arp_add_image_arrow'></div>
                <div class='arp_add_img_content'>
                    <div class='arp_add_img_row'>
                        <div class='arp_add_img_label'>
                            <?php esc_html_e('Image URL', 'arprice-responsive-pricing-table'); ?>
                            <span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>
                        </div>
                        <div class='arp_add_img_option'>
                            <input type='text' value='' class='arp_modal_txtbox img' id='arp_header_image_url_body_li' name='arp_header_image_url' />
                            <button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'><?php esc_html_e('Add File', 'arprice-responsive-pricing-table') ?></button>
                        </div>
                    </div>
                    <div class='arp_add_img_row'>
                        <div class='arp_add_img_label'>
                            <?php esc_html_e('Dimension ( height X width )', 'arprice-responsive-pricing-table'); ?>
                        </div>
                        <div class='arp_add_img_option'>
                            <input type='text' class='arp_modal_txtbox' id='arp_header_image_height_body_li' name='arp_header_image_height' /><label class='arp_add_img_note'>(px)</label>
                            <label>x</label>
                            <input type='text' class='arp_modal_txtbox' id='arp_header_image_width_body_li' name='arp_header_image_width' /><label class='arp_add_img_note'>(px)</label>
                        </div>
                    </div>
                    <div class='arp_add_img_row' style='margin-top:10px;'>
                        <div class='arp_add_img_label'>
                            <button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">
                                <?php esc_html_e('Add', 'arprice-responsive-pricing-table'); ?>
                            </button>
                            <button type="button" style="display:none;margin-right:10px;" onclick="arp_remove_object();" class="arp_modal_insert_shortcode_btn" name="arp_remove_img_btn" id="arp_remove_img_btn">
                                <?php esc_html_e('Remove', 'arprice-responsive-pricing-table'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col_opt_row arp_ok_div arp_{row_id} width_342" id="body_li_level_other_arp_ok_div__button_1{row_no}">
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-up arp_navigation_arrow' id='row_up_arrow' data-column='{col_no}' data-row-id='arp_{row_id}' data-button-id='body_li_level_options__button_1'></i>&nbsp;
                <i class='fas fa-arrow-down arp_navigation_arrow' id='row_down_arrow' data-column='{col_no}' data-row-id='arp_{row_id}' data-button-id='body_li_level_options__button_1'></i>&nbsp;
                <i class='fas fa-arrow-left arp_navigation_arrow' id='row_left_arrow' data-column='{col_no}' data-row-id='arp_{row_id}' data-button-id='body_li_level_options__button_1'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='row_right_arrow' data-column='{col_no}' data-row-id='arp_{row_id}' data-button-id='body_li_level_options__button_1'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' >
                <?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?>
            </button>
        </div>
    </div>
</div>

<div class="arp_button_setting_skeleton" style="display:none;">
    <div class="col_opt_row width_342" id="button_text">
        <div class="col_opt_title_div width_342"><?php esc_html_e('Button Content','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div width_342">
            <div class="option_tab" id="button_yearly_tab">
                <textarea name="btn_content_{col_no}" id="btn_content" data-column="main_{col_id}" data-column-step="first" class="col_opt_textarea btn_content_first"></textarea>
            </div>
        </div>
    </div>

    <div class="col_opt_row width_342" id="add_icon">
        <div class="col_opt_btn_div">
            <button type="button" onclick="add_arp_button_shortcode(this, false);" class="col_opt_btn_icon align_left arptooltipster" name="add_button_shortcode_{col_no}" id="add_button_shortcode" title="<?php esc_html_e('Add Font Icon', 'arprice-responsive-pricing-table') ?>" data-title="<?php esc_html_e('Add Font Icon', 'arprice-responsive-pricing-table') ?>" ></button>
            <div class='arp_font_awesome_model_box_container'></div>
        </div>
    </div>
    <div class='col_opt_row width_342' id='button_size' style='display:none;'>
        
        <div class="col_opt_title_div two_column" style='width:200px;height:60px;'><?php esc_html_e('Button Width','arprice-responsive-pricing-table'); ?></div>
        
        <div class="col_opt_input_div two_column">
            <div class="arp_button_slider" data-column="{col_no}"></div>
            <input type="hidden" id="button_size_input" name="button_size_{col_no}" data-column="main_{col_id}" />
        </div>

        <div class="col_opt_input_div two_column" style="float:right;">
            <div class="arp_slider_float_left">80px</div><div class="arp_slider_float_right">200px</div>
        </div>

        <div class="col_opt_title_div two_column" style='width:200px;'><?php esc_html_e('Button Height','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div two_column" style="float:right;">
            <div class="arp_button_height_slider" data-column="{col_no}"></div>
            <input type="hidden" id="button_height_input" name="button_height_{col_no}" data-column="main_{col_id}" />
        </div>
        <div class="col_opt_input_div two_column" style="float:right;">
            <div class="arp_slider_float_left">30px</div><div class="arp_slider_float_right">60px</div>
        </div>
    </div>

    <div class="col_opt_row width_342 arp_ok_div" id="button_options_other_arp_ok_div__button_1">
        <div class="col_opt_btn_div">
            <div class="col_opt_navigation_div">
                <i class="fas fa-arrow-left arp_navigation_arrow" id="button_left_arrow" data-column="{col_no}" data-button-id="footer_level_options__button_2"></i>&nbsp;
                <i class="fas fa-arrow-right arp_navigation_arrow" id="button_right_arrow" data-column="{col_no}" data-button-id="footer_level_options__button_2"></i>
            </div>
            <button type="button" id="arp_ok_btn" class="col_opt_btn arp_ok_btn">
                <?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?>
            </button>
        </div>
    </div>
</div>

<div class="arp_button_image_skeleton" style="display:none;">
    <div class="col_opt_row" id="button_image">
        <div class="col_opt_title_div"><?php esc_html_e('Button Image URL', 'arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            <input type="text" id="btn_img_url" class="col_opt_input arpbtn_img_url" name="btn_img_url_{col_no}" />

            <button onclick='add_arp_button_scode(this, false);' type='button' class='col_opt_btn_icon align_left arptooltipster' name="add_button_scode_{col_no}" id="add_button_scode" title="<?php esc_html_e('Add Button Image', 'arprice-responsive-pricing-table') ?>" data-title="<?php esc_html_e('Add Button Image', 'arprice-responsive-pricing-table') ?>" ></button>

            <div class="arp_google_font_preview_note" id="arp_remove_btn_image_link" style="display:none;">
                <a onClick="remove_arp_button_scode(this, false)" name="remove_button_scode_{col_no}" class="arp_google_font_preview_link" style="cursor:pointer;"><?php esc_html_e('Remove Image','arprice-responsive-pricing-table'); ?></a>
            </div>

            <div class="arp_add_image_container add_btn_image_container">
                <div class="arp_add_image_arrow"></div>
                <div class="arp_add_img_content">
                    <div class="arp_add_img_row">
                        <div class="arp_add_img_label"><?php esc_html_e('Image URL','arprice-responsive-pricing-table'); ?><span class='arp_model_close_btn' id='add_btn_image_container'><i class='fas fa-times'></i></span></div>
                        <div class="arp_add_img_option">
                            <input type="text" class="arp_modal_txtbox img" id="arp_btn_image_url" name="rpt_btn_image_url" />
                            <button id="arp_add_btn_image_link" data-column-id="main_{col_id}" data-insert="btn_image" data-id="arp_btn_image_url" type="button" class="arp_modal_add_file_btn"><?php esc_html_e('Add File','arprice-responsive-pricing-table'); ?></button>
                        </div>
                    </div>
                    <div class="arp_add_img_row" style='margin-top:10px;'>
                        <div class="arp_add_img_label">
                            <button type="button" onclick="add_arp_btn_shortcode(0);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">
                                <?php esc_html_e('Add', 'arprice-responsive-pricing-table'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" class="arpbtn_img_height" id="arpbtn_img_height" name="button_img_height_{col_no}" />
        <input type="hidden" class="arpbtn_img_width" id="arpbtn_img_width" name="button_img_width_{col_no}" />
    </div>
    <div class="col_opt_row arp_ok_div" id="button_options_other_arp_ok_div__button_2">
        <div class="col_opt_btn_div">
            <div class="col_opt_navigation_div">
                <i class='fas fa-arrow-left arp_navigation_arrow' id='button_left_arrow' data-column='{col_no}' data-button-id='footer_level_options__button_3'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='button_right_arrow' data-column='{col_no}' data-button-id='footer_level_options__button_3'></i>
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' ><?php esc_html_e('OK','arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<div class="arp_button_link_skeleton" style="display:none;">
    <div class="col_opt_row" id="redirect_link">
        <div class="col_opt_title_div"><?php esc_html_e('Button Link', 'arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            <textarea class="col_opt_textarea button_url_textarea" data-column-step="first" data-column="main_{col_id}" id="btn_link" name="btn_link_{col_no}"></textarea>
        </div>
    </div>

    <div class="col_opt_row arplite_restricted_view" id="external_btn">
        <div class="col_opt_title_div"><?php esc_html_e('Embed Script (e.g. PayPal Code)','arprice-responsive-pricing-table'); ?>&nbsp;<span class='pro_version_info'>(Pro Version)</span></div>
        <div class="col_opt_input_div">
            <textarea class='col_opt_textarea' data-column-step="first" data-column="main_{col_id}" readonly="readonly" name='paypal_code_{col_no}' id='arp_paypal_code'></textarea>
        </div>
    </div>

    <div class="col_opt_row" id="hide_default_btn">
        <div class="col_opt_title_div two_column more_size"><?php esc_html_e('Hide default button', 'arprice-responsive-pricing-table'); ?>&nbsp;<span class='pro_version_info'>(Pro Version)</span></div>
        <div class="col_opt_input_div two_column small_size">
            <div class="arp_checkbox_div">
                <span class="arp_price_checkbox_wrapper">
                    <input type="checkbox" class="arp_checkbox dark_bg arplite_restricted_view" id="arp_hide_default_btn" data-column="main_{col_id}" value="1" name="arp_hide_default_btn_{col_no}" />
                    <span></span>
                </span>
                <label class="arp_checkbox_label" for="arp_hide_default_btn"><?php esc_html_e('Yes','arprice-responsive-pricing-table'); ?></label>
            </div>
        </div>
    </div>

    <div class="col_opt_row" id="open_in_new_window">
        <div class="col_opt_title_div two_column more_size"><?php esc_html_e('Open in New Tab?', 'arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div two_column small_size">
            <div class="arp_checkbox_div">
                <span class="arp_price_checkbox_wrapper">
                    <input type="checkbox" class="arp_checkbox dark_bg" id="new_window" value="1" data-column="main_{col_id}" name="new_window_{col_no}" />
                    <span></span>
                </span>
                <label class="arp_checkbox_label" for="new_window"><?php esc_html_e('Yes','arprice-responsive-pricing-table'); ?></label>
            </div>
        </div>
    </div>

    <div class="col_opt_row" id="open_in_new_window_actual">
        <div class="col_opt_title_div two_column more_size"><?php esc_html_e('Open in New Window?', 'arprice-responsive-pricing-table'); ?>&nbsp;<span class='pro_version_info'>(Pro Version)</span></div>
        <div class="col_opt_input_div two_column small_size">
            <div class="arp_checkbox_div">
                <span class="arp_price_checkbox_wrapper">
                    <input type="checkbox" class="arp_checkbox dark_bg arplite_restricted_view" id="new_window_actual" data-column="main_{col_id}" value="1" name="new_window_actual_{col_no}" />
                    <span></span>
                </span>
                <label class="arp_checkbox_label" for="new_window_actual"><?php esc_html_e('Yes','arprice-responsive-pricing-table'); ?></label>
            </div>
        </div>
    </div>

    <div class="col_opt_row" id="nofollow_link_option">
        <div class="col_opt_title_div two_column more_size"><?php esc_html_e('Add Nofollow Link?', 'arprice-responsive-pricing-table'); ?>&nbsp;<span class='pro_version_info'>(Pro Version)</span></div>
        <div class="col_opt_input_div two_column small_size">
            <div class="arp_checkbox_div">
                <span class="arp_price_checkbox_wrapper">
                    <input type="checkbox" class="arp_checkbox dark_bg arplite_restricted_view" id="nofollow_link" value="1" data-column="main_{col_id}" name="nofollow_link_{col_no}" />
                    <span></span>
                </span>
                <label class="arp_checkbox_label" for="nofollow_link"><?php esc_html_e('Yes','arprice-responsive-pricing-table'); ?></label>
            </div>
        </div>
    </div>

    <div class="col_opt_row arp_ok_div" id="button_options_other_arp_ok_div__button_4">
        <div class="col_opt_btn_div">
            <div class="col_opt_navigation_div">
                <i class='fas fa-arrow-left arp_navigation_arrow' id='button_left_arrow' data-column='{col_no}' data-button-id='footer_level_options__button_4'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='button_right_arrow' data-column='{col_no}' data-button-id='footer_level_options__button_4'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn'><?php esc_html_e('Ok','arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<div class="arp_footer_content_skeleton" style="display:none;">
    <div class="col_opt_row" id="footer_text">
        <div class="col_opt_title_div two_column"><?php esc_html_e('Footer Content', 'arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            <div class="option_tab" id="footer_yearly_tab">
                <textarea name="footer_content_{col_no}" id="footer_content" data-column-step="first" data-column="main_{col_id}" class="col_opt_textarea footer_content_first"></textarea>
            </div>
        </div>
    </div>

    <div class="col_opt_row arp_hide_on_caption" id="above_below_button">
        <div class="col_opt_title_div two_column"><?php esc_html_e('Position','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div col_opt_input_div_bottom_margin">
            <?php
                foreach ($arplite_mainoptionsarr['general_options']['footer_content_position'] as $key => $above_below_array) {
                    echo '<span class="arp_price_radio_wrapper_standard arp_radio_dark_bg">';
                        echo '<input type="radio" class="arp_checkbox dark_bg" value="'.$key.'" id="footer_content_position_'.$key.'_{col_no}" name="footer_content_position_{col_no}" data-column="main_{col_id}" />';
                        echo '<span></span>';
                        echo '<label id="footer_content_position_'.$key.'_{col_no}" for="footer_content_position_'.$key.'_{col_no}">'.$above_below_array.'</label>';
                    echo '</span>';
                }
            ?>
        </div>
    </div>
    
    <div class="col_opt_row arp_show_on_caption" id="footer_text_alignment" style="display:none;">
        <div class="col_opt_title_div"><?php esc_html_e('Text Alignment','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            
            <div class="arp_alignment_btn align_left_btn" data-align="left" id="align_left_btn" data-id="{col_no}" data-level="footer_section">
                <i class='fas fa-align-left fa-flip-vertical'></i>
            </div>

            <div class="arp_alignment_btn align_center_btn" data-align="center" id="align_center_btn" data-id="{col_no}" data-level="footer_section">
                <i class='fas fa-align-center fa-flip-vertical'></i>
            </div>

            <div class="arp_alignment_btn align_right_btn" data-align="right" id="align_right_btn" data-id="{col_no}" data-level="footer_section">
                <i class='fas fa-align-right fa-flip-vertical'></i>
            </div>
            <input type="hidden" id="arp_footer_text_alignment" name="arp_footer_text_alignment_{col_no}" />
        </div>
    </div>
    <div class="col_opt_row arp_show_on_caption" id="footer_level_options_font_family" style="display:none;">
        <div class="col_opt_title_div"><?php esc_html_e('Font Family','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            <input type="hidden" id="footer_level_options_font_family" name="footer_level_options_font_family_{col_no}" data-column="main_{col_id}" />
            <dl class='arp_selectbox column_level_dd' data-name='footer_level_options_font_family_{col_no}' data-id='footer_level_options_font_family_{col_no}'>
                <dt>
                    <span></span>
                    <input type='text' style='display:none;' value="" class='arp_autocomplete' />
                    <i class='fas fa-caret-down fa-lg'></i>
                </dt>
                <dd>
                    <ul data-id='footer_level_options_font_family' data-column='{col_id}'></ul>
                </dd>
            </dl>
            
            <div class='arp_google_font_preview_note'><a target='_blank'  class='arp_google_font_preview_link' id='arp_footer_level_options_font_family_preview' href=<?php echo $googlefontpreviewurl ?>><?php esc_html_e('Font Preview', 'arprice-responsive-pricing-table'); ?></a></div>
        </div>
    </div>
    <div class='col_opt_row arp_show_on_caption' id='footer_level_options_font_size' style="display:none;">
        <div class='btn_type_size'>
            <div class='col_opt_title_div two_column'><?php esc_html_e('Font Size', 'arprice-responsive-pricing-table'); ?></div>
            <div class='col_opt_input_div two_column'>
                <input type='hidden' id='footer_level_options_font_size' data-column='main_{col_id}' name='footer_level_options_font_size_{col_no}' />
                <dl class='arp_selectbox column_level_size_dd' data-name='footer_level_options_font_size_{col_no}' data-id='footer_level_options_font_size_{col_no}' style='width:115px;max-width:115px;'>
                    <dt>
                        <span></span>
                        <input type='text' style='display:none;' class='arp_autocomplete' />
                        <i class='fas fa-caret-down fa-lg'></i>
                    </dt>
                    <dd>
                        <?php
                            $size_arr = array();
                            echo "<ul data-id='footer_level_options_font_size' data-column='{col_id}'>";
                                for ($s = 8; $s <= 20; $s++){
                                    $size_arr[] = $s;
                                }
                                for ($st = 22; $st <= 70; $st+=2){
                                    $size_arr[] = $st;
                                }
                                foreach ($size_arr as $size) {
                                    echo "<li data-value='" . $size . "' data-label='" . $size . "'>" . $size . "</li>";
                                }
                            echo "</ul>";
                        ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class='col_opt_row arp_show_on_caption' id='footer_level_options_font_style' style="display:none;">
        <div class='col_opt_title_div two_column'><?php esc_html_e('Font Style', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div' data-level='footer_level_options_font_style' level-id='footer_level_options_font_style'>
            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Bold', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Bold', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_bold' data-id='{col_no}'>
                <i class='fas fa-bold'></i>
            </div>

            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Italic', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Italic', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_italic' data-id='{col_no}'>
                <i class='fas fa-italic'></i>
            </div>

            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Underline', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Underline', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_underline' data-id='{col_noe}'>
                <i class='fas fa-underline'></i>
            </div>

            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Line-through', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Line-through', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_strike' data-id='{col_no}'>
                <i class='fas fa-strikethrough'></i>
            </div>

            <input type='hidden' id='footer_level_options_font_style_bold' name='footer_level_options_font_style_bold_{col_no}' />
            <input type='hidden' id='footer_level_options_font_style_italic' name='footer_level_options_font_style_italic_{col_no}' />
            <input type='hidden' id='footer_level_options_font_style_decoration' name='footer_level_options_font_style_decoration_{col_no}' />
        </div>
    </div>
    <div class='col_opt_row arp_ok_div' id='footer_level_options_arp_ok_div__button_1'>
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-left arp_navigation_arrow' id='footer_left_arrow' data-column='{col_no}' data-button-id='footer_level_options__button_1'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='footer_right_arrow' data-column='{col_no}' data-button-id='footer_level_options__button_1'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn'><?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<div class="arp_pricing_content_skeleton" style="display:none;">
    <div class="col_opt_row" id="price_text">
        <div class="col_opt_title_div"><?php esc_html_e('Price Text','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div width_342">
            <div class="option_tab" id="price_yearly_tab">
                <textarea name="price_text_{col_no}" id="price_text_input" data-column-step="first" data-column="main_{col_id}" class="col_opt_textarea price_text_first_step col_opt_textarea_big"></textarea>
            </div>
            
            <?php
                if (isset($column_settings['toggle_column_animation']) && $column_settings['toggle_column_animation'] == 1) {
                    $arp_style = 'display: block;';
                } else {
                    $arp_style = 'display: none;';
                }
            ?>
            <div class="arp_toogle_price_note" id="arp_toogle_price_note" style="<?php echo $arp_style; ?>"><?php echo sprintf( esc_html__('Use class %s for price animation. It will only work with numbers.', 'arprice-responsive-pricing-table'), '<b>.arp_price_amount</b>'); ?></div>
            <div class="col_opt_button">
                <?php
                if( 'arplitetemplate_25' != $ref_template){

                    if (isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1']) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1']) && in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1'])) {
                        echo "<button type='button' class='col_opt_btn_icon add_arp_object arptooltipster align_left' name='add_header_object_{col_no}' id='add_arp_object' data-insert='price_text_input' data-column='main_{col_id}' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'></button>";

                        echo "<label style='{$arp_label_btn_style} width:10px;'>&nbsp;</label>";
                        $arp_pricing_font_awesome_icon = "";
                    }

                    if ( isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1']) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1']) && in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1'])){

                        echo "<button type='button' class='col_opt_btn_icon add_header_fontawesome arptooltipster align_left' name='add_header_fontawesome_{col_no}' id='add_header_fontawesome' data-insert='price_text_input' data-column='main_{col_id}' title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' ></button>";

                        echo "<div class='arp_font_awesome_model_box_container'></div>";
                    }
                }
                ?>

                <div class='arp_add_image_container'>
                    <div class='arp_add_image_arrow'></div>
                    <div class='arp_add_img_content'>

                        <div class='arp_add_img_row'>
                            <div class='arp_add_img_label'>
                                <?php esc_html_e('Image URL', 'arprice-responsive-pricing-table'); ?>
                                <span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>
                            </div>
                            <div class='arp_add_img_option'>
                                <input type='text' value='' class='arp_modal_txtbox img' id='arp_header_image_url_price_text' name='arp_header_image_url' />
                                <button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'>
                                    <?php esc_html_e('Add File', 'arprice-responsive-pricing-table'); ?>
                                </button>
                            </div>
                        </div>

                        <div class='arp_add_img_row'>
                            <div class='arp_add_img_label'>
                                <?php esc_html_e('Dimension ( height X width )', 'arprice-responsive-pricing-table'); ?>
                            </div>
                            <div class='arp_add_img_option'>
                                <input type='text' class='arp_modal_txtbox' id='arp_header_image_height_price_text' name='arp_header_image_height' /><label class='arp_add_img_note'>(px)</label>
                                <label>x</label>
                                <input type='text' class='arp_modal_txtbox' id='arp_header_image_width_price_text' name='arp_header_image_width' /><label class='arp_add_img_note'>(px)</label>
                            </div>
                        </div>

                        <div class='arp_add_img_row' style='margin-top:10px;'>
                            <div class='arp_add_img_label'>
                                <button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">
                                    <?php esc_html_e('Add', 'arprice-responsive-pricing-table'); ?>
                                </button>
                                <button type="button" style="display:none;margin-right:10px;" onclick="arp_remove_object();" class="arp_modal_insert_shortcode_btn" name="arp_remove_img_btn" id="arp_remove_img_btn">
                                    <?php esc_html__('Remove', 'arprice-responsive-pricing-table'); ?>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        if (isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1']) && in_array('arp_shortcode_customization_style_div', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1'])) {

            $arprice_customization_style = $arpricelite_default_settings->arp_shortcode_custom_type();

            if ($reference_template == 'arplitetemplate_26') {
                unset($arprice_customization_style['none']);
            }

            echo "<div class='col_opt_row width_342' id='arp_shortcode_customization_style_div'>";
                
                echo "<div class='col_opt_title_div' style='width : 20%;margin-top:6px;'>" . esc_html__('Style', 'arprice-responsive-pricing-table') . "</div>";
                
                
                echo "<div class='col_opt_input_div' style='width : 58%;'>";

                    echo "<input type='hidden' id='arp_shortcode_customization_style' name='arp_shortcode_customization_style_{col_no}' data-column='main_{col_id}' />";
                    echo "<dl class='arp_selectbox column_level_size_dd' data-name='arp_shortcode_customization_style_{col_no}' data-id='arp_shortcode_customization_style_{col_no}' style='width:190px;'>";
                        echo "<dt style='width : 180px;'><span></span><input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";
                        echo "<dd style='width : 195px;'>";
                            echo "<ul data-id='arp_shortcode_customization_style' data-column='{col_id}'>";
                            foreach ($arprice_customization_style as $key => $style) {
                                echo "<li data-value='" . $key . "' data-label='" . $style['name'] . "'>" . $style['name'] . "</li>";
                            }
                            echo "</ul>";
                        echo "</dd>";
                    echo "</dl>";
                echo "</div>";
            echo "</div>";
        }

        if (isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1']) && in_array('arp_shortcode_customization_size_div', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['pricing_level_options']['other_columns_buttons']['pricing_level_options__button_1'])) {
            echo "<div class='col_opt_row width_342' id='arp_shortcode_customization_size_div'>";

                echo "<div class='col_opt_title_div' style='width : 40%;margin-top:6px;'>" . esc_html__('Size', 'arprice-responsive-pricing-table') . "</div>";

                echo "<div class='col_opt_input_div' style='width : 43%;'>";

                    echo "<input type='hidden' id='arp_shortcode_customization_size' name='arp_shortcode_customization_size_{col_no}' data-column='main_{col_id}' />";
                    echo "<dl class='arp_selectbox column_level_size_dd' data-name='arp_shortcode_customization_size_{col_no}' data-id='arp_shortcode_customization_size_{col_no}' style='width:190px;'>";
                        echo "<dt style='width : 130px;'><span></span><input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";
                        echo "<dd style='width : 146px;'>";
                            echo "<ul data-id='arp_shortcode_customization_size' data-column='{col_id}'>";
                                $arprice_customization_size = isset($arp_coloptionsarr['column_button_options']['button_size']) ? $arp_coloptionsarr['column_button_options']['button_size'] : '';
                                foreach ($arprice_customization_size as $key => $style) {
                                    echo "<li data-value='" . $key . "' data-label='" . $style . "'>" . $style . "</li>";
                                }
                            echo "</ul>";
                        echo "</dd>";
                    echo "</dl>";
                echo "</div>";
            echo "</div>";
        }
    ?>
    <div class='col_opt_row arp_ok_div width_342' id='pricing_level_other_arp_ok_div__button_1' style='display:none;'>
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-left arp_navigation_arrow' id='price_left_arrow' data-column='{col_no}' data-button-id='pricing_level_options__button_1'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='price_right_arrow' data-column='{col_no}' data-button-id='pricing_level_options__button_1'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' >
                <?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?>
            </button>
        </div>
    </div>
</div>

<div class="arp_header_section_skeleton" style="display:none;">
    <div class="col_opt_row" id="column_title">
        <div class="col_opt_title_div"><?php esc_html_e('Column Title', 'arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            <div class="option_tab" id="header_{wrapper_key}_yearly_tab">
                <textarea name="{col_title_name}_{col_no}" id="{column_title_id}" data-column-step="first" data-column="main_{col_id}" class="col_opt_textarea {column_title_input_cls}_first"></textarea>
            </div>
        </div>
        <div class="col_opt_button arp_show_on_caption">
            <?php
                if (isset( $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['caption_column_buttons']['header_level_options__button_1'] ) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['caption_column_buttons']['header_level_options__button_1'])) {
                    if (in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['caption_column_buttons']['header_level_options__button_1'])) {
                        echo "<button type='button' class='col_opt_btn_icon add_arp_object arptooltipster align_left' name='add_header_object_{col_no}' id='add_arp_object' data-insert='column_caption_title_input' data-column='main_{col_id}' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'>";
                        echo "</button>";
                        echo "<label style='{$arp_label_btn_style} width:10px;'>&nbsp;</label>";

                        echo "<div class='arp_add_image_container'>";
                        echo "<div class='arp_add_image_arrow'></div>";
                        echo "<div class='arp_add_img_content'>";

                        echo "<div class='arp_add_img_row'>";
                        echo "<div class='arp_add_img_label'>";
                        echo esc_html__('Image URL', 'arprice-responsive-pricing-table');
                        echo "<span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>";
                        echo "</div>";
                        echo "<div class='arp_add_img_option'>";
                        echo "<input type='text' value='' class='arp_modal_txtbox img' id='arp_header_image_url_header_sec' name='arp_header_image_url' />";
                        echo "<button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'>";
                        echo esc_html__('Add File', 'arprice-responsive-pricing-table');
                        echo "</button>";
                        echo "</div>";
                        echo "</div>";

                        echo "<div class='arp_add_img_row'>";
                        echo "<div class='arp_add_img_label'>";
                        echo esc_html__('Dimension ( height X width )', 'arprice-responsive-pricing-table');
                        echo "</div>";
                        echo "<div class='arp_add_img_option'>";
                        echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_height_header_section' name='arp_header_image_height' /><label class='arp_add_img_note'>(px)</label>";
                        echo "<label>x</label>";
                        echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_width_header_section' name='arp_header_image_width' /><label class='arp_add_img_note'>(px)</label>";
                        echo "</div>";
                        echo "</div>";

                        echo "<div class='arp_add_img_row' style='margin-top:10px;'>";
                        echo "<div class='arp_add_img_label'>";
                        echo '<button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">';
                        echo esc_html__('Add', 'arprice-responsive-pricing-table');
                        echo '</button>';
                        echo '<button type="button" style="display:none;margin-right:10px;" onclick="arp_remove_object();" class="arp_modal_insert_shortcode_btn" name="arp_remove_img_btn" id="arp_remove_img_btn">';
                        echo esc_html__('Remove', 'arprice-responsive-pricing-table');
                        echo '</button>';
                        echo "</div>";
                        echo "</div>";

                        echo "</div>";
                        echo "</div>";
                    }
                }
                if (isset( $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['caption_column_buttons']['header_level_options__button_1'] ) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['caption_column_buttons']['header_level_options__button_1'])) {
                    if (in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['caption_column_buttons']['header_level_options__button_1'])) {

                        echo "<button type='button' class='col_opt_btn_icon add_header_fontawesome arptooltipster align_left' name='add_header_fontawesome_{col_no}' id='add_header_fontawesome' data-insert='column_caption_title_input' data-column='main_{col_id}' title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' >";
                        echo "</button>";
                    }
                }

                
                echo "<div class='arp_font_awesome_model_box_container'></div>";
            ?>
        </div>
        <div class="col_opt_button arp_hide_on_caption">
            <?php
                if (isset( $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_1'] ) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_1'])) {
                    if (in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_1'])) {
                        echo "<button type='button' class='col_opt_btn_icon add_arp_object arptooltipster align_left' name='add_header_object_{col_no}' id='add_arp_object' data-insert='column_title_input' data-column='main_{col_id}' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'>";
                        echo "</button>";
                        echo "<label style='{$arp_label_btn_style} width:10px;'>&nbsp;</label>";

                        echo "<div class='arp_add_image_container'>";
                        echo "<div class='arp_add_image_arrow'></div>";
                        echo "<div class='arp_add_img_content'>";

                        echo "<div class='arp_add_img_row'>";
                        echo "<div class='arp_add_img_label'>";
                        echo esc_html__('Image URL', 'arprice-responsive-pricing-table');
                        echo "<span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>";
                        echo "</div>";
                        echo "<div class='arp_add_img_option'>";
                        echo "<input type='text' value='' class='arp_modal_txtbox img' id='arp_header_image_url_hide_caption' name='arp_header_image_url' />";
                        echo "<button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'>";
                        echo esc_html__('Add File', 'arprice-responsive-pricing-table');
                        echo "</button>";
                        echo "</div>";
                        echo "</div>";

                        echo "<div class='arp_add_img_row'>";
                        echo "<div class='arp_add_img_label'>";
                        echo esc_html__('Dimension ( height X width )', 'arprice-responsive-pricing-table');
                        echo "</div>";
                        echo "<div class='arp_add_img_option'>";
                        echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_height_header_sction_nocaption' name='arp_header_image_height' /><label class='arp_add_img_note'>(px)</label>";
                        echo "<label>x</label>";
                        echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_width_header_section_nocaption' name='arp_header_image_width' /><label class='arp_add_img_note'>(px)</label>";
                        echo "</div>";
                        echo "</div>";

                        echo "<div class='arp_add_img_row' style='margin-top:10px;'>";
                        echo "<div class='arp_add_img_label'>";
                        echo '<button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">';
                        echo esc_html__('Add', 'arprice-responsive-pricing-table');
                        echo '</button>';
                        echo '<button type="button" style="display:none;margin-right:10px;" onclick="arp_remove_object();" class="arp_modal_insert_shortcode_btn" name="arp_remove_img_btn" id="arp_remove_img_btn">';
                        echo esc_html__('Remove', 'arprice-responsive-pricing-table');
                        echo '</button>';
                        echo "</div>";
                        echo "</div>";

                        echo "</div>";
                        echo "</div>";
                    }
                }
                if (isset( $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_1'] ) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_1'])) {
                    if (in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_1'])) {

                        echo "<button type='button' class='col_opt_btn_icon add_header_fontawesome arptooltipster align_left' name='add_header_fontawesome_{col_no}' id='add_header_fontawesome' data-insert='column_title_input' data-column='main_{col_id}' title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' >";
                        echo "</button>";
                    }
                }
                echo "<div class='arp_font_awesome_model_box_container'></div>";
            ?>
        </div>
    </div>

    <div class="col_opt_row arp_show_on_caption" id="header_text_alignment" style="display:none;">
        <div class="col_opt_title_div"><?php esc_html_e('Text Alignment','arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_input_div">
            
            <div class="arp_alignment_btn align_left_btn" data-align="left" id="align_left_btn" data-id="{col_no}" data-level="header_section">
                <i class='fas fa-align-left fa-flip-vertical'></i>
            </div>

            <div class="arp_alignment_btn align_center_btn" data-align="center" id="align_center_btn" data-id="{col_no}" data-level="header_section">
                <i class='fas fa-align-center fa-flip-vertical'></i>
            </div>

            <div class="arp_alignment_btn align_right_btn" data-align="right" id="align_right_btn" data-id="{col_no}" data-level="header_section">
                <i class='fas fa-align-right fa-flip-vertical'></i>
            </div>
            <input type="hidden" id="arp_header_text_alignment" name="arp_header_text_alignment_{col_no}" />
        </div>
    </div>

    <div class='col_opt_row arp_show_on_caption' id='header_caption_font_family'>
        <div class='col_opt_title_div'><?php esc_html_e('Font Family', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div'>
            <input type='hidden' id='header_font_family' name='header_font_family_{col_no}' data-column='main_{col_id}' />
            <dl class='arp_selectbox column_level_dd' data-name='header_font_family_{col_no}' data-id='header_font_family_{col_no}'>
                <dt>
                    <span></span>
                    <input type='text' style='display:none;' class='arp_autocomplete' />
                    <i class='fas fa-caret-down fa-lg'></i>
                </dt>
                <dd>
                    <ul data-id='header_font_family' data-column='{col_id}'></ul>
                </dd>
            </dl>

            <div class='arp_google_font_preview_note'><a target='_blank'  class='arp_google_font_preview_link' id='arp_caption_header_font_family_preview' href=<?php echo $googlefontpreviewurl ?>><?php esc_html_e('Font Preview', 'arprice-responsive-pricing-table'); ?></a></div>
        </div>
    </div>

    <div class='col_opt_row arp_show_on_caption' id='header_caption_font_size'>
        <div class='btn_type_size'>
            <div class='col_opt_title_div two_column'><?php esc_html_e('Font Size', 'arprice-responsive-pricing-table'); ?></div>
            <div class='col_opt_input_div two_column'>
                <input type='hidden' id='header_font_size' name='header_font_size_{col_no}' data-column='main_{col_id}' />
                <dl class='arp_selectbox column_level_size_dd' data-name='header_font_size_{col_no}' data-id='header_font_size_{col_no}' style='width:115px;max-width:115px;'>
                    <dt>
                        <span></span>
                        <input type='text' style='display:none;' class='arp_autocomplete' />
                        <i class='fas fa-caret-down fa-lg'></i>
                    </dt>
                    <dd>
                        <?php
                            $size_arr = array();
                        ?>
                        <ul data-id='header_font_size' data-column='{col_id}'>
                            <?php
                                for ($s = 8; $s <= 20; $s++){
                                    $size_arr[] = $s;
                                }
                                for ($st = 22; $st <= 70; $st+=2){
                                    $size_arr[] = $st;
                                }
                                foreach ($size_arr as $size) {
                                    echo "<li data-value='" . $size . "' data-label='" . $size . "'>" . $size . "</li>";
                                }
                            ?>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class='col_opt_row arp_show_on_caption' id='header_caption_font_color'>
        <div class='col_opt_title_div two_column'><?php esc_html_e('Font Style', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div' data-level='header_level_options' level-id='header_button1' >
            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Bold', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Bold', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_bold' data-id='{col_no}'>
                <i class='fas fa-bold'></i>
            </div>

            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Italic', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Italic', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_italic' data-id='{col_no}'>
                <i class='fas fa-italic'></i>
            </div>

            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Underline', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Underline', 'arprice-responsive-pricing-table'); ?>' data-column='main_{col_id}' id='arp_style_underline' data-id='{col_no}'>
                <i class='fas fa-underline'></i>
            </div>

            <div class='arp_style_btn arptooltipster' title='<?php esc_html_e('Line-through', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Line-through', 'arprice-responsive-pricing-table'); ?>' data-align='right' data-column='main_{col_id}' id='arp_style_strike' data-id='{col_no}'>
                <i class='fas fa-strikethrough'></i>
            </div>

            <input type='hidden' id='header_style_bold' name='header_style_bold_{col_no}' />
            <input type='hidden' id='header_style_italic' name='header_style_italic_{col_no}' />
            <input type='hidden' id='header_style_decoration' name='header_style_decoration_{col_no}'/>
        </div>
    </div>

    <div class='col_opt_row arp_ok_div' id='header_level_caption_arp_ok_div__button_1' >
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-left arp_navigation_arrow' id='header_left_arrow' data-button-id='header_level_options__button_1' data-column='{col_no}'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='header_right_arrow' data-button-id='header_level_options__button_1' data-column='{col_no}'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn'>
                <?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?>
            </button>
        </div>
    </div>
</div>

<div class="arp_header_object_skeleton" style="display: none;">
    <?php $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'] = isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2']) ? $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'] : "";
    
    if (is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {
        
        if (in_array('additional_shortcode', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {

            if (in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2']) || in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {
                $header_shortcode_txtarea_cls = 'editable_shortcode';
            } else {
                $header_shortcode_txtarea_cls = '';
            }

            echo "<div class='col_opt_row width_342' id='additional_shortcode'>";

            echo "<div class='col_opt_title_div'>" . esc_html__('Additional Shortcode', 'arprice-responsive-pricing-table') . "</div>";

            echo "<div class='col_opt_input_div width_342'>";
            
                echo "<div class='option_tab' id='header_shortcode_yearly_tab'>";
                    echo "<textarea name='additional_shortcode_{col_no}' id='additional_shortcode_input' data-column-step='first' data-column='main_{col_id}' class='col_opt_textarea header_shortcode_yearly {$header_shortcode_txtarea_cls}'></textarea>";
                echo "</div>";            

            echo "</div>";

            $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'] = isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2']) ? $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'] : array();

            if (in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2']) || in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {

                echo "<div class='col_opt_button width_342'>";

                if ($ref_template == 'arplitetemplate_5' || $ref_template == 'arplitetemplate_7') {
                    echo "<button type='button' class='col_opt_btn_icon align_left arptooltipster add_header_shortcode' onclick='add_header_shortcode_fn(this);' name='add_header_shortcode_btn_{col_no}' id='add_header_shortcode' data-insert='additional_shortcode_input' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'>";

                    echo "</button>";
                    echo "<label style='{$arp_label_btn_style} width:10px;'>&nbsp;</label>";
                }

                if (in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {
                    echo "<button type='button' class='col_opt_btn_icon add_arp_object arptooltipster align_left add_header_shortcode' name='add_header_object_{col_no}' id='add_header_shortcode' data-insert='additional_shortcode_input' data-column='main_{col_id}' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'>";
                    echo "</button>";
                    echo "<label style='{$arp_label_btn_style} width:10px;'>&nbsp;</label>";
                }

                if (in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {
                    echo "<button type='button' class='col_opt_btn_icon add_header_fontawesome arptooltipster align_left add_header_shortcode' name='add_header_fontawesome_{col_no}' id='add_header_fontawesome' data-insert='additional_shortcode_input' data-column='main_{col_id}' title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' >";
                    echo "</button>";

                    echo "<div class='arp_font_awesome_model_box_container'></div>";
                }

                echo "<div class='arp_add_image_container'>";
                echo "<div class='arp_add_image_arrow'></div>";
                echo "<div class='arp_add_img_content'>";

                echo "<div class='arp_add_img_row'>";
                echo "<div class='arp_add_img_label'>";
                echo esc_html__('Image URL', 'arprice-responsive-pricing-table');
                echo "<span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>";
                echo "</div>";
                echo "<div class='arp_add_img_option'>";
                echo "<input type='text' value='' class='arp_modal_txtbox img' id='arp_header_image_url_additional_sc' name='arp_header_image_url' />";
                echo "<button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'>";
                echo esc_html__('Add File', 'arprice-responsive-pricing-table');
                echo "</button>";
                echo "</div>";
                echo "</div>";

                echo "<div class='arp_add_img_row'>";
                echo "<div class='arp_add_img_label'>";
                echo esc_html__('Dimension ( height X width )', 'arprice-responsive-pricing-table');
                echo "</div>";
                echo "<div class='arp_add_img_option'>";
                echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_height_additional_sc' name='arp_header_image_height' /><label class='arp_add_img_note'>(px)</label>";
                echo "<label>x</label>";
                echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_width_additional_sc' name='arp_header_image_width' /><label class='arp_add_img_note'>(px)</label>";
                echo "</div>";
                echo "</div>";

                echo "<div class='arp_add_img_row' style='margin-top:10px;'>";
                echo "<div class='arp_add_img_label'>";
                echo '<button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">';
                echo esc_html__('Add', 'arprice-responsive-pricing-table');
                echo '</button>';
                echo '<button type="button" style="display:none;margin-right:10px;" onclick="arp_remove_object();" class="arp_modal_insert_shortcode_btn" name="arp_remove_img_btn" id="arp_remove_img_btn">';
                echo esc_html__('Remove', 'arprice-responsive-pricing-table');
                echo '</button>';
                echo "</div>";
                echo "</div>";

                echo "</div>";
                echo "</div>";

                echo "</div>";
            } else {
                echo "<div class='col_opt_button'>";
                echo "<button type='button' class='col_opt_btn_icon align_left arptooltipster add_header_shortcode' onclick='add_header_shortcode_fn(this);' name='add_header_shortcode_btn_{col_no}' id='add_header_shortcode' data-insert='additional_shortcode_input' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'>";
                echo "<img src='" . PRICINGTABLE_IMAGES_URL . "/icons/audio-icon.png' />";
                echo "</button>";
                echo "</div>";
            }
            echo "</div>";
        }

        if (in_array('arp_shortcode_customization_style_div', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {
            $arprice_customization_style = $arpricelite_default_settings->arp_shortcode_custom_type();
            if ($reference_template == 'arplitetemplate_26') {
                unset($arprice_customization_style['none']);
            }
            echo "<div class='col_opt_row width_342' id='arp_shortcode_customization_style_div'>";
            echo "<div class='col_opt_title_div' style='width : 20%;margin-top:6px;'>" . esc_html__('Style', 'arprice-responsive-pricing-table') . "</div>";
            echo "<div class='col_opt_input_div' style='width : 64%;'>";

            echo "<input type='hidden' id='arp_shortcode_customization_style' name='arp_shortcode_customization_style_{col_no}' data-column='main_{col_id}' />";
            echo "<dl class='arp_selectbox column_level_size_dd' data-name='arp_shortcode_customization_style_{col_no}' data-id='arp_shortcode_customization_style_{col_no}' style='width:190px;'>";
            echo "<dt style='width : 197px;'><span></span><input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";
            echo "<dd style='width : 213px;'>";
            echo "<ul data-id='arp_shortcode_customization_style' data-column='{col_id}'>";

            foreach ($arprice_customization_style as $key => $style) {
                $restricted_style = '';
                $pro_notice = '';
                if( in_array($key, array( 'square','square_solid','semiround', 'semiround_solid','none' ) ) ) {
                    $restricted_style = 'arplite_restricted_view';
                    $pro_notice = ' <span class="pro_version_info">(Pro Version)</span>';
                }
                echo "<li class='arp_shortcode_nowrap {$restricted_style}' data-value='" . $key . "' data-label='" . $style['name'] . "'>" . $style['name'] . $pro_notice . "</li>";
            }
            echo "</ul>";
            echo "</dd>";
            echo "</dl>";
            echo "</div>";
            echo "</div>";
        }

        if (in_array('arp_shortcode_customization_size_div', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['header_level_options']['other_columns_buttons']['header_level_options__button_2'])) {
            echo "<div class='col_opt_row width_342' id='arp_shortcode_customization_size_div'>";


            echo "<div class='col_opt_title_div' style='width : 40%;margin-top:6px;'>" . esc_html__('Size', 'arprice-responsive-pricing-table') . "</div>";
            
            echo "<div class='col_opt_input_div' style='width : 43%;'>";

            echo "<input type='hidden' id='arp_shortcode_customization_size' name='arp_shortcode_customization_size_{col_no}' data-column='main_{col_id}' />";
            echo "<dl class='arp_selectbox column_level_size_dd' data-name='arp_shortcode_customization_size_{col_no}' data-id='arp_shortcode_customization_size_{col_no}' style='width:190px;'>";
            echo "<dt style='width : 130px;'><span></span><input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i></dt>";
            echo "<dd style='width : 146px;'>";
            echo "<ul data-id='arp_shortcode_customization_size' data-column='{col_id}'>";
            $arprice_customization_size = isset($arplite_coloptionsarr['column_button_options']['button_size']) ? $arplite_coloptionsarr['column_button_options']['button_size'] : '';
            foreach ($arprice_customization_size as $key => $style) {
                echo "<li data-value='" . $key . "' data-label='" . $style . "'>" . $style . "</li>";
            }
            echo "</ul>";
            echo "</dd>";
            echo "</dl>";
            echo "</div>";
            echo "</div>";
        }
    }
    ?>
    <div class='col_opt_row arp_ok_div width_342' id='header_level_other_arp_ok_div__button_2' style='display:none;'>
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-left arp_navigation_arrow' id='header_left_arrow' data-column='{col_no}' data-button-id='header_level_options__button_2'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='header_right_arrow' data-column='{col_no}' data-button-id='header_level_options__button_2'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' ><?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<div class="arp_col_description_skeleton" style="display:none;">
    <?php
        if (isset($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['column_description_level']['other_columns_buttons']['column_description_level__button_1']) && is_array($arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['column_description_level']['other_columns_buttons']['column_description_level__button_1'])) {
            if (in_array('column_description', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['column_description_level']['other_columns_buttons']['column_description_level__button_1'])) {
                echo "<div class='col_opt_row width_342' id='column_description'>";
                    echo "<div class='col_opt_title_div'>" . esc_html__('Column Description', 'arprice-responsive-pricing-table') . "</div>";
                    echo "<div class='col_opt_input_div width_342'>";
                        echo "<div class='option_tab' id='column_description_yearly_tab'>";
                            echo "<textarea name='arp_column_description_{col_no}' id='arp_column_description' data-column-step='first' data-column='main_{col_id}' class='col_opt_textarea arp_column_description_first'></textarea>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='col_opt_button'>";
                        if (in_array('arp_object', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['column_description_level']['other_columns_buttons']['column_description_level__button_1'])) {
                            echo "<button type='button' class='col_opt_btn_icon add_arp_object arptooltipster align_left' name='add_header_object_{col_no}' id='add_arp_object' data-insert='arp_column_description' data-column='main_{col_id}' title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Media', 'arprice-responsive-pricing-table') . "'></button>";
                            echo "<label style='{$arp_label_btn_style} width:10px;'>&nbsp;</label>";
                        }

                    if (in_array('arp_fontawesome', $arplite_tempbuttonsarr['template_button_options']['features'][$ref_template]['column_description_level']['other_columns_buttons']['column_description_level__button_1'])) {
                        echo "<button type='button' class='col_opt_btn_icon add_header_fontawesome arptooltipster align_left' name='add_header_fontawesome_{col_no}' id='add_header_fontawesome' data-insert='arp_column_description' data-column='main_{col_id}' title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' data-title='" . esc_html__('Add Font Icon', 'arprice-responsive-pricing-table') . "' ></button>";

                        echo "<div class='arp_font_awesome_model_box_container'></div>";
                    }

                    echo "<div class='arp_add_image_container'>";
                        echo "<div class='arp_add_image_arrow'></div>";
                        echo "<div class='arp_add_img_content'>";

                            echo "<div class='arp_add_img_row'>";
                                
                                echo "<div class='arp_add_img_label'>";
                                    echo esc_html__('Image URL', 'arprice-responsive-pricing-table');
                                    echo "<span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>";
                                echo "</div>";

                                echo "<div class='arp_add_img_option'>";
                                    echo "<input type='text' value='' class='arp_modal_txtbox img' id='arp_header_image_url_col_description' name='arp_header_image_url' />";
                                        echo "<button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'>";
                                            echo esc_html__('Add File', 'arprice-responsive-pricing-table');
                                        echo "</button>";
                                    echo "</div>";
                                echo "</div>";

                                echo "<div class='arp_add_img_row'>";
                                    echo "<div class='arp_add_img_label'>";
                                        echo esc_html__('Dimension ( height X width )', 'arprice-responsive-pricing-table');
                                    echo "</div>";
                                    echo "<div class='arp_add_img_option'>";
                                        echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_height_col_description' name='arp_header_image_height' /><label class='arp_add_img_note'>(px)</label>";
                                        echo "<label>x</label>";
                                        echo "<input type='text' class='arp_modal_txtbox' id='arp_header_image_width_col_description' name='arp_header_image_width' /><label class='arp_add_img_note'>(px)</label>";
                                    echo "</div>";
                                echo "</div>";

                                echo "<div class='arp_add_img_row' style='margin-top:10px;'>";
                                    echo "<div class='arp_add_img_label'>";
                                        echo '<button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">';
                                            echo esc_html__('Add', 'arprice-responsive-pricing-table');
                                        echo '</button>';
                                        echo '<button type="button" style="display:none;margin-right:10px;" onclick="arp_remove_object();" class="arp_modal_insert_shortcode_btn" name="arp_remove_img_btn" id="arp_remove_img_btn">';
                                            echo esc_html__('Remove', 'arprice-responsive-pricing-table');
                                        echo '</button>';
                                    echo "</div>";
                                echo "</div>";

                            echo "</div>";
                        echo "</div>";

                    echo "</div>";
                echo "</div>";
            }
        }

        echo "<div class='col_opt_row arp_ok_div width_342' id='column_description_level_other_arp_ok_div__button_1' style='display:none;'>";
            echo "<div class='col_opt_btn_div'>";
                echo "<div class='col_opt_navigation_div'>";
                    echo "<i class='fas fa-arrow-left arp_navigation_arrow' id='description_left_arrow' data-column='{col_no}' data-button-id='column_description_level__button_1'></i>&nbsp;";
                    echo "<i class='fas fa-arrow-right arp_navigation_arrow' id='description_right_arrow' data-column='{col_no}' data-button-id='column_description_level__button_1'></i>&nbsp;";
                echo "</div>";
                echo "<button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' >";
                    echo esc_html__('Ok', 'arprice-responsive-pricing-table');
                echo "</button>";
            echo "</div>";
        echo "</div>";
    ?>
</div>

<div class="arp_colorpicker_skeleton" style="display: none;">
    <?php
        if( $caption_column == 1 ){
            ?>
            <div class='col_opt_row arp_show_on_caption' id='arp_custom_color_tab_column'>
                <div class="col_opt_title_div" style="padding:5px 5px 10px !important;"><?php esc_html_e('Column Color Settings (Normal State)', 'arprice-responsive-pricing-table') ?></div>
            </div>
            <div class="col_opt_row arp_show_on_caption arp_caption_color" id="arp_normal_custom_color_tab_column" style="padding-bottom: 0 !important;margin: 0px 0px 0px 10px;border-bottom: none; ">
                <div class="arp_color_wrapper_container arp_no_bottom_border">
                    <div class="col_opt_title_div two_column"></div>
                    <div class="col_opt_title_div first_picker two_column" data-id="background_color"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
                    <div class="col_opt_title_div second_picker two_column" data-id="font_color" style="padding-left:0px !important; margin-left: -3px;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>

                    <div class="col_opt_row sub_row" id="arp_header_color_div" style="display:none">
                        <div class="col_opt_title_div two_column"><?php esc_html_e('Header', 'arprice-responsive-pricing-table'); ?></div>
                        <div class="col_opt_input_div two_column first_picker header_background_color_div" id="header_background_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="header_background_color_main_{col_id}_wrapper" data-id="header_background_color_main_{col_id}" data-color=" " data-column-id="header_background_color_main_{col_id}" data-jscolor="{hash:true,onInput:'arp_update_color(this,header_background_color_main_{col_id}_wrapper)',valueElement:'#header_background_color_main_{col_id}'}" jscolor-hash="true" jscolor-onInput="arp_update_color(this,header_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#header_background_color_main_{col_id}" >
                        </div>
                        <input type="hidden" id="header_background_color_main_{col_id}" name="header_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                        </div>
                        <div class="col_opt_input_div two_column second_picker header_font_color_div" id="header_font_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="header_font_color_main_{col_id}_wrapper" data-id="header_font_color_main_{col_id}" data-color=" " data-column-id="header_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,header_font_color_main_{col_id}_wrapper)",valueElement:"#header_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-onInput="arp_update_color(this,header_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#header_font_color_main_{col_id}">
                        </div>
                        <input type="hidden" id="header_font_color_main_{col_id}" name="header_font_color_{col_no}" value=" " class=" "  />
                        </div>
                    </div>

                    <div class="col_opt_row sub_row" id="arp_footer_color_div" style="display:none">
                        <div class="col_opt_title_div two_column"><?php esc_html_e('Footer', 'arprice-responsive-pricing-table'); ?></div>
                        <div class="col_opt_input_div two_column first_picker footer_background_color_div" id="footer_background_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="footer_background_color_main_{col_id}_wrapper" data-id="footer_background_color_main_{col_id}" data-color=" " data-column-id="footer_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,footer_background_color_main_{col_id}_wrapper)",valueElement:"#footer_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,footer_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#footer_background_color_main_{col_id}" >
                        </div>
                        <input type="hidden" id="footer_background_color_main_{col_id}" name="footer_bg_color_{col_no}" value=" " class=" "  />
                        </div>
                        <div class="col_opt_input_div two_column second_picker footer_font_color_div" id="footer_font_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="footer_level_options_font_color_main_{col_id}_wrapper" data-id="footer_level_options_font_color_main_{col_id}" data-color=" " data-column-id="footer_level_options_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,footer_level_options_font_color_main_{col_id}_wrapper)",valueElement:"#footer_level_options_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,footer_level_options_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#footer_level_options_font_color_main_{col_id}" >
                        </div>
                        <input type="hidden" id="footer_level_options_font_color_main_{col_id}" name="footer_level_options_font_color_{col_no}" value=" " class=" "  />
                        </div>
                    </div>

                    <div class="col_opt_row arp_show_on_caption" id="arp_body_background_color_div">
                        <div class="col_opt_title_div" style="padding-left: 2px !important;"><?php esc_html_e("Body Row Colors", 'arprice-responsive-pricing-table'); ?></div>
                        <div class="col_opt_title_div two_column"></div>
                        <div class="col_opt_title_div first_picker two_column" data-id="background_color" style="margin-left: -9px;"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
                        <div class="col_opt_title_div second_picker two_column" data-id="font_color" style="padding-left:0px !important; margin-left: -4px;text-align: center;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>
                    </div>

                    <div class="col_opt_row sub_row" id="arp_odd_color_div" style="display:none">
                        <div class="col_opt_title_div two_column"><?php esc_html_e('Odd', 'arprice-responsive-pricing-table'); ?></div>
                        <div class="col_opt_input_div two_column first_picker odd_background_color_div" id="odd_background_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_odd_color_main_{col_id}_wrapper" data-id="content_odd_color_main_{col_id}" data-color=" " data-column-id="content_odd_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_odd_color_main_{col_id}_wrapper)",valueElement:"#content_odd_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_odd_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_odd_color_main_{col_id}" >
                        </div>
                        <input type="hidden" id="content_odd_color_main_{col_id}" name="content_odd_color_{col_no}" value=" " class=" "  />
                        </div>
                        <div class="col_opt_input_div two_column second_picker odd_font_color_div" id="odd_font_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_font_color_main_{col_id}_wrapper" data-id="content_font_color_main_{col_id}" data-color=" " data-column-id="content_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_font_color_main_{col_id}_wrapper)",valueElement:"#content_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_font_color_main_{col_id}" >
                        </div>
                            <input type="hidden" id="content_font_color_main_{col_id}" name="content_font_color_{col_no}" value=" " class=" "  />
                        </div>
                    </div>

                    <div class="col_opt_row sub_row" id="arp_even_color_div" style="display:none">
                        <div class="col_opt_title_div two_column"><?php esc_html_e('Even', 'arprice-responsive-pricing-table'); ?></div>
                        <div class="col_opt_input_div two_column first_picker even_background_color_div" id="even_background_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_even_color_main_{col_id}_wrapper" data-id="content_even_color_main_{col_id}" data-color=" " data-column-id="content_even_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_even_color_main_{col_id}_wrapper)",valueElement:"#content_even_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_even_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_even_color_main_{col_id}" >
                        </div>
                        <input type="hidden" id="content_even_color_main_{col_id}" name="content_even_color_{col_no}" value=" " class=" "  />                            
                        </div>
                        <div class="col_opt_input_div two_column second_picker even_font_color_div" id="even_font_color_div">
                            <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_even_font_color_main_{col_id}_wrapper" data-id="content_even_font_color_main_{col_id}" data-color=" " data-column-id="content_even_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_even_font_color_main_{col_id}_wrapper)",valueElement:"#content_even_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_even_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_even_font_color_main_{col_id}" >
                        </div>
                        <input type="hidden" id="content_even_font_color_main_{col_id}" name="content_even_font_color_{col_no}" value=" " class=" " />                            
                        </div>
                    </div>
               </div>

            </div>
            <div class="col_opt_row arp_show_on_caption arp_caption_color" id="arp_border_color_div" style="padding-top:0 !important; padding-bottom: 0 !important;border: 1px solid #d9e3ed;margin: 0px 0px 0px 10px;border-top: none;border-bottom: none;">
                <div class="arp_color_wrapper_container arp_no_top_border arp_no_bottom_border">
                    <div class="col_opt_title_div" style="padding-left: 15px !important;width:100%;box-sizing:border-box !important;"><?php esc_html_e("Border Colors", 'arprice-responsive-pricing-table'); ?></div>
                    <div class="col_opt_title_div two_column"></div>
                    <div class="col_opt_title_div first_picker two_column" data-id="background_color" style="text-align:center;"><?php esc_html_e('Column', 'arprice-responsive-pricing-table'); ?></div>
                    <div class="col_opt_title_div second_picker two_column" data-id="font_color" style="text-align:center;margin-left: -8px !important;"><?php esc_html_e('Row', 'arprice-responsive-pricing-table'); ?></div>
               </div>
            </div>
            <div class="col_opt_row arp_show_on_caption arp_caption_color sub_row" id='arp_border_color_div_sub' style="display:none; padding-top:0 !important; border-top: none !important; margin-bottom: 10px;border: 1px solid #d9e3ed;margin: 0px 0px 17px 10px;">
                <div class="arp_color_wrapper_container arp_no_top_border">
                    <div class="col_opt_title_div two_column"></div>
                    <div class="col_opt_input_div two_column first_picker column_border_color_div" id="column_border_color_div" style="margin-left: 6px;">
                        <div class='color_picker color_picker_round arplite_jscolor opt_bg_box_alignment' data-jscolor='{hash:true,onInput:"arp_update_color(this,arp_caption_border_color_div)",valueElement:"#arp_caption_border_color"}' data-id='arp_caption_border_color' jscolor-hash='true' jscolor-oninput='arp_update_color(this,arp_caption_border_color_div)' jscolor-valueelement="#arp_caption_border_color" data-column="main_{col_id}" data-column-id='arp_caption_border_color' id='arp_caption_border_color_div'></div>
                        <input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' name='arp_caption_border_color' id='arp_caption_border_color' />
                    </div>
                    <div class="col_opt_input_div two_column second_picker border_caption_color row_border_color_div" id="row_border_color_div">
                        <div class='color_picker color_picker_round arplite_jscolor' data-jscolor='{hash:true,onInput:"arp_update_color(this,arp_caption_row_border_color_div)",valueElement:"#arp_caption_row_border_color"}'  data-column="main_{col_id}" data-id='arp_caption_row_border_color' data-column-id='arp_caption_row_border_color' jscolor-hash='true' jscolor-oninput='arp_update_color(this,arp_caption_row_border_color_div)' jscolor-valueelement="#arp_caption_row_border_color" id='arp_caption_row_border_color_div'></div>

                        <input type='hidden' class='general_color_box general_color_box_font_color general_color_box_background_color' name='arp_caption_row_border_color' id='arp_caption_row_border_color'  />
                    </div>
                </div>
            </div>
            <?php 
        }
    ?>

    <div class="col_opt_row row_dark arp_hide_on_caption" id="arp_custom_color_tab_column" style="padding: 7px 6px 0px 7px !important;">
        <div class='col_opt_title_div' style='padding:10px 5px 10px !important'><?php esc_html_e('Column Color Settings', 'arprice-responsive-pricing-table'); ?></div>
        <div class="col_opt_title_div two_column arp_color_tab_column selected" data-id="arp_normal" style="margin-left: 3px;">Normal</div>
        <div class="col_opt_title_div two_column arp_color_tab_column" data-id="arp_hover">Hover</div>
    </div>

    <div class="col_opt_row arp_hide_on_caption arp_column_colors" id="arp_normal_custom_color_tab_column" style="padding-top:0 !important;margin: 0px 0px 17px 10px;width: 286px;border-top: none;">

        <div class="arp_color_wrapper_container arp_no_top_border">
            <div class="col_opt_title_div two_column"></div>
            <div class="col_opt_title_div first_picker two_column" data-id="background_color"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
            <div class="col_opt_title_div second_picker two_column" data-id="font_color" style="padding-left:2px !important;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_column_color_div" style="display:none;">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Column', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker column_background_color_div" id="column_background_color_div">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="column_background_color_main_{col_id}_wrapper" data-id="column_background_color_main_{col_id}" data-color=" " data-column-id="column_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,column_background_color_main_{col_id}_wrapper)",valueElement:"#column_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,column_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#column_background_color_main_{col_id}" >
                    </div>

                    <input type="hidden" id="column_background_color_main_{col_id}" name="column_background_color_{col_no}" value=" " class=" general_color_box_background_color background_color_{col_no}"  />

                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_header_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Header', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker header_background_color_div" id="header_background_color_div_other_col">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="header_background_color_main_{col_id}_wrapper" data-id="header_background_color_main_{col_id}" data-color=" " data-column-id="header_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,header_background_color_main_{col_id}_wrapper)",valueElement:"#header_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,header_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#header_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="header_background_color_main_{col_id}" name="header_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker header_font_color_div" id="header_font_color_div">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="header_font_color_main_{col_id}_wrapper" data-id="header_font_color_main_{col_id}" data-color=" " data-column-id="header_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,header_font_color_main_{col_id}_wrapper)",valueElement:"#header_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,header_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#header_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="header_font_color_main_{col_id}" name="header_font_color_{col_no}" value=" " class=" "  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_shortcode_div" style="display:none">
                <div class="col_opt_title_div two_column" style="line-height:1.5"><?php esc_html_e('Shortcode Section', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker arp_shortcode_background" id="arp_shortcode_background" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="shortcode_background_color_main_{col_id}_wrapper" data-id="shortcode_background_color_main_{col_id}" data-color=" " data-column-id="shortcode_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,shortcode_background_color_main_{col_id}_wrapper)",valueElement:"#shortcode_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,shortcode_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#shortcode_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="shortcode_background_color_main_{col_id}" name="shortcode_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker arp_shortcode_font_color" id="arp_shortcode_font_color" style="display:none;">

                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="shortcode_font_color_main_{col_id}_wrapper" data-id="shortcode_font_color_main_{col_id}" data-color=" " data-column-id="shortcode_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,shortcode_font_color_main_{col_id}_wrapper)",valueElement:"#shortcode_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,shortcode_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#shortcode_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="shortcode_font_color_main_{col_id}" name="shortcode_font_color_{col_no}" value=" " class=" "  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_desc_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Description', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker desc_background_color_div" id="desc_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="column_desc_background_color_main_{col_id}_wrapper" data-id="column_desc_background_color_main_{col_id}" data-color=" " data-column-id="column_desc_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,column_desc_background_color_main_{col_id}_wrapper)",valueElement:"#column_desc_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,column_desc_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#column_desc_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="column_desc_background_color_main_{col_id}" name="column_desc_background_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker desc_font_color_div" id="desc_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="column_description_font_color_main_{col_id}_wrapper" data-id="column_description_font_color_main_{col_id}" data-color=" " data-column-id="column_description_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,column_description_font_color_main_{col_id}_wrapper)",valueElement:"#column_description_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,column_description_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#column_description_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="column_description_font_color_main_{col_id}" name="column_description_font_color_{col_no}" value=" " class=" " />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_price_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Pricing', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker price_background_color_div" id="price_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="price_background_color_main_{col_id}_wrapper" data-id="price_background_color_main_{col_id}" data-color=" " data-column-id="price_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,price_background_color_main_{col_id}_wrapper)",valueElement:"#price_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,price_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#price_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="price_background_color_main_{col_id}" name="price_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker price_font_color_div" id="price_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="price_font_color_main_{col_id}_wrapper" data-id="price_font_color_main_{col_id}" data-color=" " data-column-id="price_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,price_font_color_main_{col_id}_wrapper)",valueElement:"#price_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,price_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#price_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="price_font_color_main_{col_id}" name="price_font_color_{col_no}" value=" " class=" "  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_footer_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Footer', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker footer_background_color_div" id="footer_background_color_div" style="display:none;">
                <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="footer_background_color_main_{col_id}_wrapper" data-id="footer_background_color_main_{col_id}" data-color=" " data-column-id="footer_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,footer_background_color_main_{col_id}_wrapper)",valueElement:"#footer_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,footer_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#footer_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="footer_background_color_main_{col_id}" name="footer_bg_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker footer_font_color_div" id="footer_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="footer_level_options_font_color_main_{col_id}_wrapper" data-id="footer_level_options_font_color_main_{col_id}" data-color=" " data-column-id="footer_level_options_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,footer_level_options_font_color_main_{col_id}_wrapper)",valueElement:"#footer_level_options_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,footer_level_options_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#footer_level_options_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="footer_level_options_font_color_main_{col_id}" name="footer_level_options_font_color_{col_no}" value=" " class=" "  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_button_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Button', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker button_background_color_div" id="button_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="button_background_color_main_{col_id}_wrapper" data-id="button_background_color_main_{col_id}" data-color=" " data-column-id="button_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,button_background_color_main_{col_id}_wrapper)",valueElement:"#button_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,button_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#button_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="button_background_color_main_{col_id}" name="button_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker button_font_color_div" id="button_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="button_font_color_main_{col_id}_wrapper" data-id="button_font_color_main_{col_id}" data-color=" " data-column-id="button_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,button_font_color_main_{col_id}_wrapper)",valueElement:"#button_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,button_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#button_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="button_font_color_main_{col_id}" name="button_font_color_{col_no}" value=" " class=" "  />
                    <div class="col_opt_input_div" id="button_font_notice_div" style="display:none;">(For <br> Button <br>Hover)</div>
                </div>
            </div>

            <div class="col_opt_row arp_hide_on_caption" id="arp_body_background_color_div">
                <div class="col_opt_title_div"><?php esc_html_e("Body Row Colors", 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_title_div two_column"></div>
                <div class="col_opt_title_div first_picker two_column" data-id="background_color"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_title_div second_picker two_column" data-id="font_color" style="padding-left:2px !important;margin-right: -15px;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_odd_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Odd', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker odd_background_color_div" id="odd_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_odd_color_main_{col_id}_wrapper" data-id="content_odd_color_main_{col_id}" data-color=" " data-column-id="content_odd_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_odd_color_main_{col_id}_wrapper)",valueElement:"#content_odd_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_odd_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_odd_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_odd_color_main_{col_id}" name="content_odd_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker odd_font_color_div" id="odd_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_font_color_main_{col_id}_wrapper" data-id="content_font_color_main_{col_id}" data-color=" " data-column-id="content_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_font_color_main_{col_id}_wrapper)",valueElement:"#content_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_font_color_main_{col_id}" name="content_font_color_{col_no}" value=" " class=" " />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_even_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Even', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker even_background_color_div" id="even_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_even_color_main_{col_id}_wrapper" data-id="content_even_color_main_{col_id}" data-color=" " data-column-id="content_even_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_even_color_main_{col_id}_wrapper)",valueElement:"#content_even_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_even_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_even_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_even_color_main_{col_id}" name="content_even_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker even_font_color_div" id="even_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_even_font_color_main_{col_id}_wrapper" data-id="content_even_font_color_main_{col_id}" data-color=" " data-column-id="content_even_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_even_font_color_main_{col_id}_wrapper)",valueElement:"#content_even_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_even_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_even_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_even_font_color_main_{col_id}" name="content_even_font_color_{col_no}" value=" " class=" "  />
                </div>
            </div>
        </div>

    </div>

    <div class="col_opt_row arp_hide_on_caption arp_column_colors" id="arp_hover_background_color_column" style="padding-top: 0px !important;margin: 0px 0px 17px 10px;border: 1px solid #d9e3ed !important;border-top: none !important;">
        <div class="arp_color_wrapper_container arp_no_top_border">
            <div class="col_opt_title_div two_column"></div>
            <div class="col_opt_title_div two_column" data-id="background_color"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
            <div class="col_opt_title_div two_column" data-id="font_color" style="padding-left:7px !important;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_column_hover_color_div_column" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Column', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker column_hover_background_color_div" id="column_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="column_hover_background_color_main_{col_id}_wrapper" data-id="column_hover_background_color_main_{col_id}" data-color=" " data-column-id="column_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,column_hover_background_color_main_{col_id}_wrapper)",valueElement:"#column_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,column_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#column_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="column_hover_background_color_main_{col_id}" name="column_hover_background_color_{col_no}" value=" " class=" general_color_box_background_color background_color_{col_id}"  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_header_hover_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Header', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker header_hover_background_color_div" id="header_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="header_hover_background_color_main_{col_id}_wrapper" data-id="header_hover_background_color_main_{col_id}" data-color=" " data-column-id="header_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,header_hover_background_color_main_{col_id}_wrapper)",valueElement:"#header_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,header_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#header_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="header_hover_background_color_main_{col_id}" name="header_hover_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker header_hover_font_color_div" id="header_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="header_hover_font_color_main_{col_id}_wrapper" data-id="header_hover_font_color_main_{col_id}" data-color=" " data-column-id="header_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,header_hover_font_color_main_{col_id}_wrapper)",valueElement:"#header_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,header_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#header_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="header_hover_font_color_main_{col_id}" name="header_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_shortcode_hover_div" style="display:none">
                <div class="col_opt_title_div two_column"  style="line-height:1.5"><?php esc_html_e('Shortcode Section', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker arp_shortcode_hover_background" id="arp_shortcode_hover_background" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="shortcode_hover_background_color_main_{col_id}_wrapper" data-id="shortcode_hover_background_color_main_{col_id}" data-color=" " data-column-id="shortcode_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,shortcode_hover_background_color_main_{col_id}_wrapper)",valueElement:"#shortcode_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,shortcode_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#shortcode_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="shortcode_hover_background_color_main_{col_id}" name="shortcode_hover_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker arp_shortcode_hover_font_color" id="arp_shortcode_hover_font_color" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="shortcode_hover_font_color_main_{col_id}_wrapper" data-id="shortcode_hover_font_color_main_{col_id}" data-color=" " data-column-id="shortcode_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,shortcode_hover_font_color_main_{col_id}_wrapper)",valueElement:"#shortcode_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,shortcode_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#shortcode_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="shortcode_hover_font_color_main_{col_id}" name="shortcode_hover_font_color_{col_no}" value=" " class=" "  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_desc_hover_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Description', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker desc_hover_background_color_div" id="desc_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="column_desc_hover_background_color_main_{col_id}_wrapper" data-id="column_desc_hover_background_color_main_{col_id}" data-color=" " data-column-id="column_desc_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,column_desc_hover_background_color_main_{col_id}_wrapper)",valueElement:"#column_desc_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,column_desc_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#column_desc_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="column_desc_hover_background_color_main_{col_id}" name="column_desc_hover_background_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker desc_hover_font_color_div" id="desc_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="column_description_hover_font_color_main_{col_id}_wrapper" data-id="column_description_hover_font_color_main_{col_id}" data-color=" " data-column-id="column_description_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,column_description_hover_font_color_main_{col_id}_wrapper)",valueElement:"#column_description_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,column_description_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#column_description_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="column_description_hover_font_color_main_{col_id}" name="column_description_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_price_hover_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Pricing', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker price_hover_background_color_div" id="price_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="price_hover_background_color_main_{col_id}_wrapper" data-id="price_hover_background_color_main_{col_id}" data-color=" " data-column-id="price_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,price_hover_background_color_main_{col_id}_wrapper)",valueElement:"#price_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,price_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#price_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="price_hover_background_color_main_{col_id}" name="price_hover_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker price_hover_font_color_div" id="price_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="price_hover_font_color_main_{col_id}_wrapper" data-id="price_hover_font_color_main_{col_id}" data-color=" " data-column-id="price_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,price_hover_font_color_main_{col_id}_wrapper)",valueElement:"#price_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,price_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#price_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="price_hover_font_color_main_{col_id}" name="price_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_footer_hover_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Footer', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker footer_hover_background_color_div" id="footer_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="footer_hover_background_color_main_{col_id}_wrapper" data-id="footer_hover_background_color_main_{col_id}" data-color=" " data-column-id="footer_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,footer_hover_background_color_main_{col_id}_wrapper)",valueElement:"#footer_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,footer_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#footer_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="footer_hover_background_color_main_{col_id}" name="footer_hover_bg_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker footer_hover_font_color_div" id="footer_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="footer_hover_font_color_main_{col_id}_wrapper" data-id="footer_hover_font_color_main_{col_id}" data-color=" " data-column-id="footer_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,footer_hover_font_color_main_{col_id}_wrapper)",valueElement:"#footer_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,footer_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#footer_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="footer_hover_font_color_main_{col_id}" name="footer_level_options_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_hover_button_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Button', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker button_hover_background_color_div" id="button_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="button_hover_background_color_main_{col_id}_wrapper" data-id="button_hover_background_color_main_{col_id}" data-color=" " data-column-id="button_hover_background_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,button_hover_background_color_main_{col_id}_wrapper)",valueElement:"#button_hover_background_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,button_hover_background_color_main_{col_id}_wrapper)" jscolor-valueelement="#button_hover_background_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="button_hover_background_color_main_{col_id}" name="button_hover_background_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
                <div class="col_opt_input_div two_column second_picker button_hover_font_color_div" id="button_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="button_hover_font_color_main_{col_id}_wrapper" data-id="button_hover_font_color_main_{col_id}" data-color=" " data-column-id="button_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,button_hover_font_color_main_{col_id}_wrapper)",valueElement:"#button_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,button_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#button_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="button_hover_font_color_main_{col_id}" name="button_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>

            <div class="col_opt_row arp_hide_on_caption" id="arp_body_hover_background_color_div">
                <div class="col_opt_title_div"><?php esc_html_e("Body Row Colors", 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_title_div two_column"></div>
                <div class="col_opt_title_div two_column" data-id="background_color"><?php esc_html_e('Background', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_title_div two_column" data-id="font_color" style="padding-left: 0px !important;margin-right: -13px;"><?php esc_html_e('Text Color', 'arprice-responsive-pricing-table'); ?></div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_odd_hover_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Odd', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker odd_hover_background_color_div" id="odd_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_odd_hover_color_main_{col_id}_wrapper" data-id="content_odd_hover_color_main_{col_id}" data-color=" " data-column-id="content_odd_hover_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_odd_hover_color_main_{col_id}_wrapper)",valueElement:"#content_odd_hover_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_odd_hover_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_odd_hover_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_odd_hover_color_main_{col_id}" name="content_odd_hover_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker odd_hover_font_color_div" id="odd_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_hover_font_color_main_{col_id}_wrapper" data-id="content_hover_font_color_main_{col_id}" data-color=" " data-column-id="content_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_hover_font_color_main_{col_id}_wrapper)",valueElement:"#content_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_hover_font_color_main_{col_id}" name="content_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>

            <div class="col_opt_row sub_row arp_hide_on_caption" id="arp_even_hover_color_div" style="display:none">
                <div class="col_opt_title_div two_column"><?php esc_html_e('Even', 'arprice-responsive-pricing-table'); ?></div>
                <div class="col_opt_input_div two_column first_picker even_hover_background_color_div" id="even_hover_background_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_even_hover_color_main_{col_id}_wrapper" data-id="content_even_hover_color_main_{col_id}" data-color=" " data-column-id="content_even_hover_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_even_hover_color_main_{col_id}_wrapper)",valueElement:"#content_even_hover_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_even_hover_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_even_hover_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_even_hover_color_main_{col_id}" name="content_even_hover_color_{col_no}" value=" " class=" "  />
                </div>
                <div class="col_opt_input_div two_column second_picker even_font_color_div" id="even_hover_font_color_div" style="display:none;">
                    <div class="arplite_jscolor arp_custom_css_colorpicker arp_general_color_box" data-column="main_{col_id}" id="content_even_hover_font_color_main_{col_id}_wrapper" data-id="content_even_hover_font_color_main_{col_id}" data-color=" " data-column-id="content_even_hover_font_color_main_{col_id}" data-jscolor='{hash:true,onInput:"arp_update_color(this,content_even_hover_font_color_main_{col_id}_wrapper)",valueElement:"#content_even_hover_font_color_main_{col_id}"}' jscolor-hash="true" jscolor-oninput="arp_update_color(this,content_even_hover_font_color_main_{col_id}_wrapper)" jscolor-valueelement="#content_even_hover_font_color_main_{col_id}" >
                    </div>
                    <input type="hidden" id="content_even_hover_font_color_main_{col_id}" name="content_even_hover_font_color_{col_no}" value=" " class=" general_color_box_background_color"  />
                </div>
            </div>
        </div>

    </div>

    <div class='col_opt_row arp_ok_div' id='column_level_other_arp_ok_div__button_2'>
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-left arp_navigation_arrow' id='column_left_arrow' data-column='{col_no}' data-button-id='column_level_options__button_2'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='column_right_arrow' data-column='{col_no}' data-button-id='column_level_options__button_2'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' ><?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<div class="arp_column_opt_skeleton" style="display:none;">
    <div class='col_opt_row arp_show_on_caption' id='column_width' style='display:none;'>
        <div class='col_opt_title_div two_column'><?php esc_html_e('width (optional)', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column'>
            <div class='col_opt_input'>
                <input type='text' name='column_width_{col_no}' id='column_width_input' data-column='main_{col_id}' class='col_opt_input' />
                <span><?php esc_html_e('Px', 'arprice-responsive-pricing-table'); ?></span>
            </div>
        </div>
    </div>

    <div class='col_opt_row arp_show_on_caption' id='caption_border' style='display:none;'>
        <div class='col_opt_title_div'><?php esc_html_e('Column Borders', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_title_div two_column'><?php esc_html_e('Border Size', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column'>
            <div>
                <input type='hidden' name='arp_caption_border_size' id='arp_caption_border_size' data-column="main_{col_id}" />
                <dl id='arp_caption_border_size' class='arp_selectbox' data-id='arp_caption_border_size' data-name='arp_caption_border_size' style='margin-top: 15px; width: 101px !important;'>
                    <dt>
                        <span></span>
                        <input type='text' style='display:none;' class='arp_autocomplete' />
                        <i class='fas fa-caret-down fa-lg'></i>
                    </dt>
                    <dd>
                        <ul class='arp_caption_border_size' data-id='arp_caption_border_size' style='width: 117px;'>
                            <?php
                            for ($i = 0; $i <= 10; $i++) {
                                echo"<li style='margin:0' class='arp_selectbox_option' data-value='" . $i . "' data-label='" . $i . "'>" . $i . "</li>";
                            }
                            ?>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>

        <div class='col_opt_title_div two_column'><?php esc_html_e('Border Style', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column'>
            <div>
                <input type='hidden' name='arp_caption_border_style' id='arp_caption_border_style' data-column="main_{col_id}" />
                <dl id='arp_caption_border_style' class='arp_selectbox' data-id='arp_caption_border_style' data-name='arp_caption_border_style' style='margin-top: 15px; width: 101px !important;'>
                    <dt>
                        <span></span>
                        <input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i>
                    </dt>
                    <dd>
                        <ul class='arp_caption_border_style' data-id='arp_caption_border_style' style='width: 117px;'>
                            <li style='margin:0' class='arp_selectbox_option' data-value='solid' data-label='Solid'>Solid</li>
                            <li style='margin:0' class='arp_selectbox_option' data-value='dotted' data-label='Dotted'>Dotted</li>
                            <li style='margin:0' class='arp_selectbox_option' data-value='dashed' data-label='Dashed'>Dashed</li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>

        <div class='col_opt_title_div two_column'><?php esc_html_e('Borders', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column' style='width: 80px;'>

            <span class='arp_price_checkbox_wrapper' style='margin: 10px 5px 5px 5px;'>
                <input type='checkbox' name='arp_caption_border_left' id='arp_caption_border_left' data-column="main_{col_id}"  class='arp_checkbox light_bg' value='1' />
                <span></span>
            </span>
            <label class='arp_checkbox_label' style='margin:10px 5px 5px 5px;' for='arp_caption_border_left'><?php esc_html_e('Left', 'arprice-responsive-pricing-table'); ?></label>
            
            <div style='width:100%;height:1px;float:left;'></div>

            <span class='arp_price_checkbox_wrapper' style='margin: 10px 5px 5px 5px;'>
                <input type='checkbox' name='arp_caption_border_right' id='arp_caption_border_right' data-column="main_{col_id}"  class='arp_checkbox light_bg' value='1' />
                <span></span>
            </span>
            <label class='arp_checkbox_label' style='margin:10px 3px 5px 5px;' for='arp_caption_border_right'><?php esc_html_e('Right', 'arprice-responsive-pricing-table'); ?></label>
            <div style='width:100%;height:1px;float:left;'></div>

            <span class='arp_price_checkbox_wrapper' style='margin: 10px 5px 5px 5px;'>
                <input type='checkbox' name='arp_caption_border_top' id='arp_caption_border_top' data-column="main_{col_id}"  class='arp_checkbox light_bg' value='1' />
                <span></span>
            </span>
            <label class='arp_checkbox_label' style='margin:10px 5px 5px 5px;' for='arp_caption_border_top'><?php esc_html_e('Top', 'arprice-responsive-pricing-table'); ?></label>
            <div style='width:100%;height:1px;float:left;'></div>
            
            <span class='arp_price_checkbox_wrapper' style='margin: 10px 5px 5px 5px;'>
                <input type='checkbox' name='arp_caption_border_bottom' id='arp_caption_border_bottom' data-column="main_{col_id}"  class='arp_checkbox light_bg' value='1' />
                <span></span>
            </span>
            <label class='arp_checkbox_label' style='margin:10px 1px 1px 5px;' for='arp_caption_border_bottom'><?php esc_html_e('Bottom', 'arprice-responsive-pricing-table'); ?></label>
            <div style='width:100%;height:1px;float:left;'></div>

        </div>
    </div>

    <div class='col_opt_row arp_hide_on_caption' id='column_other_background_image'>
        <div class='col_opt_title_div two_column'><?php esc_html_e('Background Image', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column'>
            <button type='button' class='col_opt_btn_icon add_arp_object arptooltipster arplite_restricted_view' name='arp_column_background_image_{col_no}' id='arp_column_background_image' data-insert='arp_column_background_image_input' data-column='main_{col_id}' title='<?php esc_html_e('Add Column Background Image', 'arprice-responsive-pricing-table'); ?>' data-title='<?php esc_html_e('Add Column Background Image', 'arprice-responsive-pricing-table'); ?>'></button>
            <input type='hidden' name='arp_column_background_image_{col_no}'  data-column="main_{col_id}" id='arp_column_background_image_input' />
            <input type='hidden' name='arp_column_background_image_height_{col_no}' data-column="main_{col_id}" id='arp_column_background_image_height_input' />
            <input type='hidden' name='arp_column_background_image_width_{col_no}' data-column="main_{col_id}" id='arp_column_background_image_width_input' />
            <div class='arp_add_image_container arp_background'>
                <div class='arp_add_image_arrow' style='margin-left:78px;'></div>
                <div class='arp_add_img_content'>
                    <div class='arp_add_img_row'>
                        <div class='arp_add_img_label'>
                            <?php esc_html_e('Image URL', 'arprice-responsive-pricing-table'); ?>
                            <span class='arp_model_close_btn' id='arp_add_image_container'><i class='fas fa-times'></i></span>
                        </div>
                        <div class='arp_add_img_option'>
                            <input type='text' class='arp_modal_txtbox img' id='arp_header_image_url_col_bgimage' name='arp_header_image_url' />
                            <button data-insert='header_object' data-id='arp_header_image_url' type='button' class='arp_header_object'>
                                <?php esc_html_e('Add File', 'arprice-responsive-pricing-table'); ?>
                            </button>
                        </div>
                    </div>

                    <div class='arp_add_img_row'>
                        <div class='arp_add_img_option arp_image_scale arp_price_radio_wrapper_standard'>
                            <input type='radio' class='arp_column_background_scaling_radio' id='do_not_scale_image' name='column_background_scaling_{col_no}' value='do_not_scale_image' data-column='main_column_{col_no}' />
                            <span></span>
                            <label data-for='do_not_scale_image' class='arp_add_img_note arp_back_scale'><?php esc_html_e('Do not scale image', 'arprice-responsive-pricing-table'); ?></label>
                        </div>
                        
                        <div class='arp_add_img_option arp_image_scale arp_price_radio_wrapper_standard'>
                            <input type='radio' class='arp_column_background_scaling_radio' id='fit_to_container' name='column_background_scaling_{col_no}' value='fit_to_container' data-column='main_column_{col_no}' />
                            <span></span>
                            <label data-for='fit_to_container' class='arp_add_img_note arp_back_scale'><?php esc_html_e('Fit to container', 'arprice-responsive-pricing-table'); ?></label>
                        </div>
                    </div>

                    <div class='arp_add_img_row' id='arp_background_position' >
                        
                        <div class='arp_add_img_label'><?php esc_html_e('Background Position', 'arprice-responsive-pricing-table'); ?></div>
                        
                        <div class='arp_add_img_option'>
                            <input type='text' class='arp_modal_txtbox' id='column_background_min_positon' name='column_background_min_positon_{col_no}' data-column='main_column_{col_no}' />
                            <label class='arp_add_img_note'>(%)</label>
                            <label></label>
                            <input type='text' class='arp_modal_txtbox' id='column_background_max_positon' name='column_background_max_positon_{col_no}' data-column='main_column_{col_no}' />
                            <label class='arp_add_img_note'>(%)</label>
                        </div>

                        <div class='arp_add_img_option'>
                            <label class='arp_add_img_note arp_sub_title' style='width: 33%;'>x-axis</label>
                            <label class='arp_add_img_note arp_sub_title' style='width: 35%;'>y-axis</label>
                        </div>

                        <div class='arp_add_img_label arp_sub'>
                            <?php esc_html_e('(Minimum value can be 0 and maximum value can be 100.)', 'arprice-responsive-pricing-table'); ?>
                        </div>
                    </div>

                    <div class='arp_add_img_row' style='margin-top:10px;margin-bottom:10px;'>
                        <div class='arp_add_img_label'>
                            <button type="button" onclick="arp_add_object(this);" class="arp_modal_insert_shortcode_btn" name="rpt_image_btn" id="rpt_image_btn">
                                <?php esc_html_e('Add', 'arprice-responsive-pricing-table'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='arp_google_font_preview_note' id='arp_remove_column_image_link' style="display: none;">
            <a href='javascript:arp_remove_object("main_column_{col_no}","arp_column_background_image_input")'  class='arp_google_font_preview_link' id='remove_column_image_link'>
                <?php esc_html_e('Remove Image', 'arprice-responsive-pricing-table'); ?>
            </a>
        </div>
    </div>

    <div class='col_opt_row arp_hide_on_caption' id='column_highlight'>
        <div class='col_opt_title_div two_column'><?php esc_html_e('Highlight Column', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column'>
            <div class='arp_checkbox_div'>
                <span class='arp_price_checkbox_wrapper'>
                    <input type='checkbox' class='arp_checkbox dark_bg' value='1' id='column_highlight_input' name='column_highlight_{col_no}' data-column='main_{col_id}' />
                    <span></span>
                </span>
                <label class='arp_checkbox_label' for='column_highlight_input'><?php esc_html_e('Yes', 'arprice-responsive-pricing-table'); ?></label>
            </div>
        </div>
    </div>

    <div class='col_opt_row arp_hide_on_caption' id='select_ribbon'>
        <div class='col_opt_title_div two_column'><?php esc_html_e('Ribbon', 'arprice-responsive-pricing-table'); ?></div>
        <div class='col_opt_input_div two_column'>

            <button type='button' class='col_opt_btn' onclick='arp_select_ribbon(this)' name='ribbon_select_{col_id}' id='ribbon_select' data-column='main_{col_id}'><?php esc_html_e('Select Ribbon', 'arprice-responsive-pricing-table'); ?></button>
        
            <input type='hidden' id='arp_ribbon_style_main' name='arp_ribbon_style_{col_no}' />
    
            <input type='hidden' id='arp_ribbon_bgcol_main' name='arp_ribbon_bgcol_{col_no}' />
    
            <input type='hidden' id='arp_ribbon_textcol_main' name='arp_ribbon_textcol_{col_no}' />
    
            <input type='hidden' id='arp_ribbon_position_main' name='arp_ribbon_position_{col_no}' />
    
            <input type='hidden' id='arp_ribbon_content_main' name='arp_ribbon_content_{col_no}' />

            <input type='hidden' id='arp_ribbon_content_main_second' name='arp_ribbon_content_second_{col_no}' />

            <input type='hidden' id='arp_ribbon_content_main_third' name='arp_ribbon_content_third_{col_no}' />

            <input type='hidden' id='arp_ribbon_content_main_fourth' name='arp_ribbon_content_fourth_{col_no}' />

            <input type='hidden' id='arp_ribbon_content_main_fifth' name='arp_ribbon_content_fifth_{col_no}' />

            <input type='hidden' id='arp_custom_ribbon_url' name='arp_custom_ribbon_url_{col_no}' />

            <input type='hidden' id='arp_custom_ribbon_url_second' name='arp_custom_ribbon_url_second_{col_no}' />

            <input type='hidden' id='arp_custom_ribbon_url_third' name='arp_custom_ribbon_url_third_{col_no}' />

            <input type='hidden' id='arp_custom_ribbon_url_fourth' name='arp_custom_ribbon_url_fourth_{col_no}' />

            <input type='hidden' id='arp_custom_ribbon_url_fifth' name='arp_custom_ribbon_url_fifth_{col_no}' />

            <input type='hidden' id='arp_ribbon_custom_position_rl' name='arp_ribbon_custom_position_rl_{col_no}' />

            <input type='hidden' id='arp_ribbon_custom_position_top' name='arp_ribbon_custom_position_top_{col_no}' />
        </div>

        <div class='arp_google_font_preview_note' id='arp_remove_ribbon_container_{col_no}' style="display:none;">
            <a class='arp_google_font_preview_link' data-column='main_column_{col_no}' id='arp_ribbon_remove' style='text-decoration:none;cursor:pointer;'><?php esc_html_e('Remove Ribbon', 'arprice-responsive-pricing-table'); ?></a>
        </div>
    </div>

    <div class='col_opt_row arp_hide_on_caption arplite_restricted_view' id='post_variables_content'>
        <div class='col_opt_title_div'><?php esc_html_e('Pass variables in URL', 'arprice-responsive-pricing-table'); ?>&nbsp;<span class='pro_version_info'>(Pro Version)</span></div>

        <div class='col_opt_input_div'>
            <div class="option_tab" id="post_variable_first_tab">
                <textarea id="post_variable_content" data-column-step="first" data-column="main_{col_id}" readonly="readonly" class="col_opt_textarea post_variable_{tab_name}"></textarea>
            </div>
            <span class='arp_note' id='post_variable_content_ex'>e.g. plan_id={col_no};type=arprice;</span>
            <span class='arp_note' id='post_variable_content_ex'><?php esc_html_e('Add your variables with seperated by ; (semicolon). These variables will pass by GET method to specified URL upon button click.', 'arprice-responsive-pricing-table'); ?></span>
        </div>
    </div>

    <div class='col_opt_row arp_ok_div' id='column_level_caption_arp_ok_div__button_1' >
        <div class='col_opt_btn_div'>
            <div class='col_opt_navigation_div'>
                <i class='fas fa-arrow-left arp_navigation_arrow' id='column_left_arrow' data-button-id='column_level_options__button_1' data-column='{col_no}'></i>&nbsp;
                <i class='fas fa-arrow-right arp_navigation_arrow' id='column_right_arrow' data-button-id='column_level_options__button_1' data-column='{col_no}'></i>&nbsp;
            </div>
            <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn'><?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?></button>
        </div>
    </div>
</div>

<?php
     if( $caption_column == 1 ){
?>
        <div class="arp_column_body_opt_skeleton" style="display:none;">

            <div class='col_opt_row' id='text_alignment'>
                <div class='col_opt_title_div'><?php esc_html_e('Text Alignment', 'arprice-responsive-pricing-table'); ?></div>
                <div class='col_opt_input_div'>
                    <div class='alignment_btn align_left_btn' data-align='left' id='align_left_btn' data-id='{col_no}'>
                        <i class='fas fa-align-left fa-flip-vertical'></i>
                    </div>

                    <div class='alignment_btn align_center_btn' data-align='center' id='align_center_btn' data-id='{col_no}'>
                        <i class='fas fa-align-center fa-flip-vertical'></i>
                    </div>

                    <div class='alignment_btn align_right_btn ' data-align='right' id='align_right_btn' data-id='{col_no}'>
                        <i class='fas fa-align-right fa-flip-vertical'></i>
                    </div>

                    <input type='hidden' id='body_text_alignment' name='body_text_alignment_{col_no}'>

                </div>
            </div>

            <div class='col_opt_row' id='body_li_caption_font_family'>
                <div class='col_opt_title_div'><?php esc_html_e('Font Family', 'arprice-responsive-pricing-table'); ?></div>
                <div class='col_opt_input_div'>
                    <input type='hidden' id='content_font_family' name='content_font_family_{col_no}' data-column='main_{col_id}' />
                    <dl class='arp_selectbox column_level_dd' data-name='content_font_family_{col_no}' data-id='content_font_family_{col_no}'>
                        <dt>
                            <span></span>
                            <input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i>
                        </dt>
                        <dd>
                            <ul data-id='content_font_family' data-column='{col_id}'></ul>
                        </dd>
                    </dl>
                    <div class='arp_google_font_preview_note'><a target='_blank'  class='arp_google_font_preview_link' id='arp_content_font_family_preview' href='<?php echo $googlefontpreviewurl ?>'><?php esc_html_e('Font Preview', 'arprice-responsive-pricing-table'); ?></a></div>
                </div>
            </div>


            <div class='col_opt_row' id='body_li_caption_font_size'>
                <div class='btn_type_size'>
                    <div class='col_opt_title_div two_column'><?php esc_html_e('Font Size', 'arprice-responsive-pricing-table'); ?></div>
                    <div class='col_opt_input_div two_column'>
                        <input type='hidden' id='content_font_size' name='content_font_size_{col_no}' data-column='main_{col_id}' />
                        <dl class='arp_selectbox column_level_size_dd' data-name='content_font_size_{col_no}' data-id='content_font_size_{col_no}' style='width:115px;max-width:115px;'>
                            <dt>
                                <span></span>
                                <input type='text' style='display:none;' class='arp_autocomplete' /><i class='fas fa-caret-down fa-lg'></i>
                            </dt>
                            <dd>
                                <?php
                                    $size_arr = array();
                                    echo "<ul data-id='content_font_size' data-column='{col_id}'>";
                                    for ($s = 8; $s <= 20; $s++){
                                        $size_arr[] = $s;
                                    }
                                    for ($st = 22; $st <= 70; $st+=2){
                                        $size_arr[] = $st;
                                    }
                                    foreach ($size_arr as $size) {
                                        echo "<li data-value='" . $size . "' data-label='" . $size . "'>" . $size . "</li>";
                                    }
                                    echo "</ul>";
                                ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class='col_opt_row arp_ok_div' id='body_level_caption_arp_ok_div__button_1' >
                <div class='col_opt_btn_div'>
                    <button type='button' id='arp_ok_btn' class='col_opt_btn arp_ok_btn' ><?php esc_html_e('Ok', 'arprice-responsive-pricing-table'); ?> </button>
                </div>
            </div>
        </div>
<?php
     }
?>