<?php

if ( ! defined( 'ABSPATH' ) || !function_exists('current_user_can') || !current_user_can('arplite_view_pricingtables') || !isset($_GET['arplite_page_nonce']) || ( isset( $_GET['arplite_page_nonce']) && !wp_verify_nonce( $_GET['arplite_page_nonce'], 'arplite_page_nonce' ) ) ){
    exit;
}


global $arplite_pricingtable, $arpricelite_default_settings, $arpricelite_analytics, $arpricelite_fonts, $arpricelite_version, $arprice_font_awesome_icons, $arpricelite_img_css_version, $arplite_subscription_time, $arpricelite_form, $wpdb;

$editable_templates = "SELECT t.*, COUNT(a.pricing_table_id) as views FROM ".$wpdb->prefix."arplite_arprice t LEFT JOIN ".$wpdb->prefix."arplite_arprice_analytics a ON t.ID = a.pricing_table_id WHERE t.is_template = %d GROUP BY t.ID ORDER BY t.ID DESC";
$arp_my_templates = $wpdb->get_results($wpdb->prepare($editable_templates, 0));

if( ! extension_loaded('dom') || !extension_loaded('gd') ){
    echo "<div class='notice notice-error'>";
        echo "<p>";
            echo esc_html__('ARPricelite requires to have the following PHP modules/extensions to work properly. Kindly contact your hosting provider to install these modules/extensions.','arprice-responsive-pricing-table');
            echo "<ul class='arplite_required_modules'>";
                echo "<li>PHP-XML/DOM</li>";
                echo "<li>GD Library</li>";
            echo "</ul>";
        echo "</p>";
    echo "</div>";
    return;
}

?>
<div id="arp_loader_div" class="arp_loader" style="display: none;">
    <div class="arp_loader_img"></div>
</div>
<input type="hidden" name="arp_version" id="arp_version" value="<?php echo $arpricelite_version; ?>" />
<input type="hidden" name="arp_request_version" id="arp_request_version" value="<?php echo get_bloginfo('version'); ?>" />
<?php
    $now = time(); /* or your date as well */
    $your_date = get_option('arplite_display_popup_date');
    $datediff = $now - $your_date;
    $days = floor($datediff / (60 * 60 * 24));
?>
<input type="hidden" id="popup_display_difference" name="popup_display_difference" value="<?php echo $arplite_subscription_time; ?>" />
<input type="hidden" id="popup_current_time_diff" name="popup_current_time_diff" value="<?php echo $days; ?>" />
<input type="hidden" id="is_display_popup" name="is_display_popup" value="<?php echo get_option('arplite_popup_display'); ?>" />
<input type="hidden" id="is_already_subscribed" name="is_already_subscribed" value="<?php echo get_option('arplite_already_subscribe'); ?>" />
<input type="hidden" id="popup_displayed_last_date" name="popup_displayed_last_date" value="<?php echo get_option('arplite_display_popup_date'); ?>" />
<input type="hidden" id="arplite_current_date" name="arplite_current_date" value="<?php echo time(); ?>" />

<input type="hidden" name="arp_restrict_dashboard" id="arp_restrict_dashboard" value="<?php echo get_option('arplite_is_dashboard_visited'); ?>" />
<input type="hidden" name="arp_tour_guide_value" id="arp_tour_guide_value" value="<?php echo get_option('arpricelite_tour_guide_value'); ?>" />
<input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo admin_url('admin-ajax.php'); ?>" />
<input type="hidden" name="arp_admin_url" id="arp_admin_url" value="<?php echo admin_url('admin.php?page=arpricelite'); ?>">
<input type="hidden" name="arplite_display_sale_popup" id="arplite_display_sale_popup" value="<?php echo get_option('arplite_display_bf_sale_popup'); ?>" />
<?php $arplite_nonce_field = wp_create_nonce('arplite_wp_nonce'); ?>
<input type="hidden" name="_wpnonce_arplite" value="<?php echo $arplite_nonce_field; ?>" />
<div class="arprice_container">
    <div class="dashboard_error_message" id="dashboard_error_message">
        <div class="message_descripiton"></div>
    </div>
    <div class="arprice_template_listing_top_belt">
        <div class="arprice_template_listing_logo"></div>
        <ul class="arprice_template_listing_tab_wrapper">
            <?php

                $active_tab_class = 'arp_active';
                $active_template_tab = '';
                if( isset($arp_my_templates) && is_array($arp_my_templates) && count($arp_my_templates) > 0 ){
                    $active_tab_class = '';
                    $active_template_tab = 'arp_active';
            ?>
                <li class="arprice_template_listing_tab arp_active" id="arprice_templates"><?php esc_html_e('MY PRICING TABLES','arprice-responsive-pricing-table'); ?></li>
            <?php
            
                }
            ?>
            <li class="arprice_template_listing_tab <?php echo $active_tab_class; ?>" id="arp_create_new_template"><?php esc_html_e('CREATE NEW','arprice-responsive-pricing-table') ?></li>
        </ul>
        <?php if( isset($arp_my_templates) && is_array($arp_my_templates) && count($arp_my_templates) > 0 ){ ?>
        <button type="button" name="create_new_table" class="arp_create_new_pricing_table_btn arp_active" id="create_new_table"><?php esc_html_e('Add New','arprice-responsive-pricing-table'); ?></button>
        <?php } ?>
        <button type="button" name="arp_go_create_new_table" class="arp_go_create_new_pricing_table_arrow" id="arp_go_create_new_table"></button>
    </div>
    <div class="arprice_template_listing_container">
        <div class="arprice_template_listing_tab_container <?php echo $active_template_tab; ?>" id="arprice_templates">
            <?php
                foreach( $arp_my_templates as $key => $template ){

                    $template_view = 0;

                    $template_opt = maybe_unserialize($template->general_options);
                    $template_name = $template_opt['template_setting']['template'];
                    $reference_template = $template_opt['general_settings']['reference_template'];
                    $table_name = $template->table_name;
                    $arp_template_id = $template->ID;
                    $total_visit = $template->views;
                    $last_update_date = $template->arp_last_updated_date;
                    $thumb_img_dir = ARPLITE_PRICINGTABLE_UPLOAD_DIR . '/template_images/arplitetemplate_' . $arp_template_id . '_large.png';
                    $thumb_img_path = ARPLITE_PRICINGTABLE_UPLOAD_URL . '/template_images/arplitetemplate_' . $arp_template_id . '_large.png';
                    $img_thumb_content = '<div class="arprice_template_thumb_box" style="background: #ffffff url('.$thumb_img_path.') no-repeat center;">
                            </div>';

                    if(!file_exists($thumb_img_dir)){
                        $img_thumb_content = '<div class="no_image_div"><span class="no_image_text">No Image</span></div>';    
                    }

                    if($total_visit>0){
                        $template_view = 1;
                    }

                    $date_format = get_option('date_format');
                    if ($last_update_date == "0000-00-00 00:00:00"){
                        $last_update_date = $template->create_date;
                    }
                    $date_to_display = date($date_format, strtotime($last_update_date));
                    
            ?>
                    <div class="arprice_editable_template_container" id="arp_template_<?php echo $arp_template_id; ?>">

                        <div class="arprice_editable_template_div">
                            <?php echo $img_thumb_content; ?>
                            <div class="arprice_template_options_container">    
                                
                                    <div class="arprice_template_options arprice_template_preview template_action_btn" onClick='arp_price_preview_home("<?php echo $arpricelite_form->get_direct_link($template->ID, true) ?>");' title="<?php esc_html_e('Preview', 'arprice-responsive-pricing-table'); ?>" ></div>
                                    <div class="arprice_template_options arprice_template_edit template_action_btn" title="<?php esc_html_e('Select Table', 'arprice-responsive-pricing-table'); ?>" onclick="window.location.href = '<?php echo admin_url('admin.php?page=arpricelite&arp_action=edit&eid=' . $template->ID) ?>'"></div>
                                    <div class="arprice_template_options arprice_template_clone template_action_btn" id="clone_template" data-url="<?php echo admin_url('admin.php?page=arpricelite&arp_action=new&eid=' . $template->ID); ?>" title="<?php esc_html_e('Clone Table', 'arprice-responsive-pricing-table'); ?>"></div>
                                    <div id="delete_template" class="arprice_template_options arprice_template_delete template_action_btn" data-template-id="<?php echo $template->ID; ?>" title="<?php esc_html_e('Delete Table', 'arprice-responsive-pricing-table'); ?>"></div>    

                            </div>
                            <hr class="arprice_template_seperator">
                            <div class="arprice_template_description_container">
                                <div class="arprice_template_description_row">
                                    <div class="arprice_template_description_content arp_font_medium"><?php esc_html_e('Title', 'arprice-responsive-pricing-table'); ?></div>
                                    <div class="arprice_template_description_content arprice_template_listing_table_name" title="<?php echo $table_name; ?>" style="line-height: normal;padding:10px 0;"><?php echo $table_name; ?></div>
                                </div>
                                <div class="arprice_template_description_row">
                                    <div class="arprice_template_description_content arp_font_medium"><?php esc_html_e('Last Modified', 'arprice-responsive-pricing-table'); ?></div>
                                    <div class="arprice_template_description_content"><?php echo $date_to_display; ?></div>
                                </div>
                                <div class="arprice_template_description_row">
                                    <div class="arprice_template_description_content arp_font_medium"><?php esc_html_e('Statistics', 'arprice-responsive-pricing-table'); ?></div>
                                    <div class="arprice_template_description_content">
                                        <span class="float_left"><?php echo $total_visit; ?> <?php esc_html_e('(Visits)', 'arprice-responsive-pricing-table'); ?></span>
                                        <span class="float_right arprice_statistics_ico" id="arprice_get_analytics" data-template-id="<?php echo $arp_template_id; ?>" data-template-views="<?php echo $template_view; ?>" title="<?php esc_html_e('Analytics', 'arprice-responsive-pricing-table'); ?>"></span>
                                    </div>
                                </div>
                                <div class="arprice_template_description_row">
                                    <div class="arprice_template_description_content arp_font_medium"><?php esc_html_e('Shortcode','arprice-responsive-pricing-table'); ?></div>
                                    <div class="arprice_template_description_content" id="arprice_template_shortcode" data-copy-title="<?php esc_html_e('Click to Copy','arprice-responsive-pricing-table') ?>" data-copied-title="<?php esc_html_e('Copied to Clipboard','arprice-responsive-pricing-table'); ?>"><?php echo "[ARPLite id=".$arp_template_id."]" ?></div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
            <?php
                }
            ?>


        </div>
        <div class="arprice_template_listing_tab_container <?php echo $active_tab_class; ?>" id="arp_create_new_template">
            <h2 class="arprice_create_new_template_title"><?php esc_html_e('Create New Table','arprice-responsive-pricing-table') ?></h2>
            <div class="arprice_add_new_pricing_table_wrapper">
                <div class="arprice_new_template_box arp_create_new">
                    <span class="arprice_box_background"></span>
                    <span class="arprice_new_template_box_title"><?php esc_html_e('Select Pricing Table','arprice-responsive-pricing-table'); ?></span>
                    <span class="arprice_new_template_box_subtitle"><?php esc_html_e('Choose your design','arprice-responsive-pricing-table'); ?></span>
                    <button class="arprice_box_button" id="arprice_create_new_template_button" type="button"><?php esc_html_e('Select Template','arprice-responsive-pricing-table'); ?></button>
                </div>
                <div class="arprice_new_template_box arp_download_sample">
                    <span class="arprice_box_background"></span>
                    <span class="arprice_new_template_box_title"><?php esc_html_e('Install Free Samples','arprice-responsive-pricing-table'); ?></span>
                    <span class="arprice_new_template_box_subtitle"><?php esc_html_e('Ready made pricing templates','arprice-responsive-pricing-table'); ?></span>
                    <button class="arprice_box_button" id="arprice_download_sample_button" type="button"><?php esc_html_e('Browse Samples','arprice-responsive-pricing-table'); ?></button>
                    <span class="arpricelite_pro_version_notice"><i class="fas fa-lock"></i>&nbsp;<?php esc_html_e('Premium Version Only','arprice-responsive-pricing-table'); ?>&nbsp;<i class="fas fa-lock"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="arprice_select_template_container">
        <h2 class="arprice_select_template_title"><?php esc_html_e('Select Template','arprice-responsive-pricing-table'); ?></h2>
        <?php
        $default_templates = "SELECT * FROM " . $wpdb->prefix . "arplite_arprice WHERE is_template = '%d' AND status = '%s' ORDER BY is_template DESC, is_animated ASC, ID ASC";
        $default_templates = $wpdb->get_results($wpdb->prepare($default_templates, 1, 'published'));
        $template_orders = $arplite_pricingtable->arp_template_order();
        $pro_templates = $arplite_pricingtable->arp_template_pro_images();
        $template_new_orders = array();
        $total_default = count($default_templates);
        $total_ordered = count($template_orders);
        $x = 0;
        
        foreach ($template_orders as $key => $value) {
            foreach ($default_templates as $key1 => $template) {
                $template_opt = maybe_unserialize($template->general_options);
                $reference_template = $template_opt['general_settings']['reference_template'];
                if ($key == $reference_template) {
                    $template_new_orders[$x] = $default_templates[$key1];
                }
            }
            $x++;
        }
        
        ?>
        <div class="arprice_select_template_list_container arp_default_template_list">
            <?php
                foreach( $template_new_orders as $k => $template ){
                    $template_img = 'arp_template_'.$template->template_name.'.png';
                    $template_img_url = ARPLITE_PRICINGTABLE_IMAGES_URL.'/template_images/'.$template_img;
                    $template_img_hover = 'arp_template_'.$template->template_name.'_hover.png';
                    $template_img_url_hover = ARPLITE_PRICINGTABLE_IMAGES_URL.'/template_images/'.$template_img_hover;
                    $tour_guide_tpl_id = "id='arp_template_".$template->template_name."'";
                    if($tour_guide_tpl_id=='arp_template_8'){
                        $tour_guide_tpl_id = 'id="arp_template_8"';
                    }

            ?>
            <div class="arprice_select_template_container_item" <?php echo $tour_guide_tpl_id; ?>>
                <div class="arprice_select_template_inner_container">
                    <div class="arprice_select_template_bg_img" style="background:url(<?php echo $template_img_url; ?>) no-repeat top left;" arp_template="<?php echo $template_img_url; ?>" arp_template_hover="<?php echo $template_img_url_hover; ?>"></div>
                    <div class="arprice_select_template_action_div">
                        <div class="arprice_select_template_action_btn arprice_preview_template" id="arprice_preview_template" title="<?php esc_html_e('Preview', 'arprice-responsive-pricing-table'); ?>" onClick='arp_price_preview_home("<?php echo $arpricelite_form->get_direct_link($template->ID, true) ?>");'></div>
                        <div class="arprice_select_template_action_btn arpice_clone_template" id="clone_template" title="<?php esc_html_e('Select', 'arprice-responsive-pricing-table'); ?>" data-url="<?php echo admin_url('admin.php?page=arpricelite&arp_action=new&eid=' . $template->ID); ?>"></div>
                    </div>
                </div>
            </div>
            <?php
                }

                foreach ($pro_templates as $key => $value) {
                    $template_id = str_replace('arptemplate_', '', $value);

                    if( $template_id >= 20 ){
                        $template_id = $template_id - 3;
                    }
                    $template_img = 'arp_template_'.$template_id.'.png';
                    $template_img_url = ARPLITE_PRICINGTABLE_IMAGES_URL.'/template_images/'.$template_img;
                    $template_img_hover = 'arp_template_'.$template_id.'_hover.png';
                    $template_img_url_hover = ARPLITE_PRICINGTABLE_IMAGES_URL.'/template_images/'.$template_img_hover;
            ?>
                <div class="arprice_select_template_container_item" <?php echo $tour_guide_tpl_id; ?>>
                    <div class="arpricelite_pro_belt"></div>
                    <div class="arprice_select_template_inner_container">
                        <div class="arprice_select_template_bg_img" style="background:url(<?php echo $template_img_url; ?>) no-repeat top left;" arp_template="<?php echo $template_img_url; ?>" arp_template_hover="<?php echo $template_img_url_hover; ?>"></div>
                        <div class="arprice_select_template_action_div">
                            <div class="arprice_select_template_action_btn arprice_preview_template" id="arprice_preview_template" title="<?php esc_html_e('Preview', 'arprice-responsive-pricing-table'); ?>" data-img-url="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL . '/' . $value . '_v' . $arpricelite_img_css_version . '_preview.png'; ?>" data-id="<?php echo $value; ?>" onClick='arp_price_preview_home(this);'></div>
                            <div class="arprice_select_template_action_btn arpice_clone_template pro_only" id="clone_template" title="<?php esc_html_e('Select', 'arprice-responsive-pricing-table'); ?>" ></div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
    </div>

    <div class="arprice_download_sample_container">
        <div class="error_message arp_sample_download_error" id="arp_download_error_message"> 
            <?php esc_html_e('Something went wrong, while downloading sample template. Please try again', 'arprice-responsive-pricing-table'); ?>
        </div>
        <h2 class="arprice_select_template_title arprice_download_sample_title"><?php esc_html_e('Install Free Samples','arprice-responsive-pricing-table'); ?></h2>
        <div class="arp_sample_page_loader">
          <span class="arp_sample_page_loader_1">
            <span class="left"><span class="anim"></span></span>
            <span class="right"><span class="anim"></span></span>
          </span>
        </div>
        <span class="arp_download_samples_note"><?php esc_html_e('There are no samples available.', 'arprice-responsive-pricing-table'); ?></span>

        <div class="arprice_select_template_list_container arprice_download_sample_list_container">
        </div>
        <div class="arp_load_more_samples_container">
            <button type="button" class="arp_load_more_samples_btn" id="arp_load_more_samples_btn"><?php esc_html_e('Browse More Samples','arprice-responsive-pricing-table'); ?></button>
        </div>
        <input type="hidden" name="is_last_arp_sample_page" id="is_last_arp_sample_page" value="0" />
        <input type="hidden" name="is_sample_page_loaded" id="is_sample_page_loaded" value="0" />
    </div>
</div>

<!-- Template Preview Model -->
<div class="arp_admin_modal_overlay">
    <div class="arp_admin_modal arp_desktop_view" id="arp_pricing_table_preview" style="">
        <div class="arp_model_preview_belt">
            <div class="device_icon active" id="computer_icon"></div>
            
            <div class="device_icon" id="tablet_icon"></div>
            
            <div class="device_icon" id="mobile_icon"></div>
            
            <div class="preview_close" id="prev_close_icon">
                <span class="arp_modal_close_btn b-close"></span>
            </div>
        </div>
        <div class="preview_model" style="float:left;width:100%;height:90%;">
        </div>
    </div>
</div>
<!-- Template Preview Model -->

<!-- Tour Guide Model -->
<div class="arp_admin_modal_overlay" id="arp_tour_guide_model">
    <div class="arp_model_delete_box">
        <div class="modal_top_belt">
            <span class="modal_title"><?php esc_html_e('ARPrice Guided Tour', 'arprice-responsive-pricing-table'); ?></span>
            <span id="nav_style_close" class="arp_modal_close_btn b-close"></span>
        </div>
        <div class="arp_modal_delete_content">
            <div class="arp_delete_modal_msg"><?php esc_html_e('Please take a quick tour of basic functionalities.', 'arprice-responsive-pricing-table'); ?></div>
            <div class="arp_delete_modal_btn">
                
                <button id="arp_tour_guide_start_yes" class="arp_tour_guide_start_model ribbon_insert_btn" type="button"><?php esc_html_e('Start Tour', 'arprice-responsive-pricing-table'); ?></button>
                <button id="arp_tour_guide_start_no" class="arp_tour_guide_start_model ribbon_insert_btn" type="button" style="background:#373a3f;"><?php esc_html_e('Skip Tour', 'arprice-responsive-pricing-table'); ?></button>

            </div>
        </div>
    </div>
</div>
<!-- Tour Guide Model -->

<!-- Remove template -->
<div class="arp_admin_modal_overlay">
    <div class="arp_model_delete_box" id="arp_remove_template">
        <input type="hidden" id="delete_table_id" value="" />
        <div class="modal_top_belt">
            <span class="modal_title"><?php esc_html_e('Delete Table', 'arprice-responsive-pricing-table'); ?></span>
            <span id="nav_style_close" class="arp_modal_close_btn b-close"></span>
        </div>
        <div class="arp_modal_delete_content">
            <div class="arp_delete_modal_msg"><?php esc_html_e('Are you sure you want to delete this table?', 'arprice-responsive-pricing-table'); ?></div>
            <div class="arp_delete_modal_btn">
                <button id="Model_Delete_Template"  type="button" class="ribbon_insert_btn delete_template"><?php esc_html_e('OK', 'arprice-responsive-pricing-table'); ?></button>
                <button id="Model_Delete_Template"  class="ribbon_cancel_btn" type="button"><?php esc_html_e('Cancel', 'arprice-responsive-pricing-table'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Remove template -->

<!-- Restrict sample download -->
<div class="arp_admin_modal_overlay">
    <div class="arp_model_delete_box" id="arp_restrict_sample_download">
        <div class="modal_top_belt">
            <span class="modal_title"><?php esc_html_e('Install Failed', 'arprice-responsive-pricing-table'); ?></span>
            <span id="nav_style_close" class="arp_modal_close_btn b-close"></span>
        </div>
        <div class="arp_modal_delete_content">
            <div class="arp_delete_modal_msg arp_sample_download_msg"></div>
            <div class="arp_delete_modal_btn arp_sample_download_btn">
                <button id="Model_Sample_Template_Btn" type="button" class="ribbon_insert_btn"><?php esc_html_e('OK', 'arprice-responsive-pricing-table'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Restrict sample download -->


<div class="arprice_information_block">
    <a href="https://www.arpriceplugin.com/documentation/getting-started/" target="_blank" class="arprice_info_icon arprice_doc_icon arp_guid_btn" title="<?php esc_html_e('Documentation','arprice-responsive-pricing-table'); ?>"></a>
    <div class="arprice_info_icon arprice_guide_icon arp_guid_btn" id="arp_tour_guide_start" title="<?php esc_html_e('Tour Guide','arprice-responsive-pricing-table'); ?>"></div>
    <br><br>
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

<div class="arp_model_box" id="arp_pricing_table_pro_preview" style="display:none;background:white;">
    <div class="arp_model_preview_belt">
        <div class="arp_pro_model_notice">
            <?php esc_html_e('This template is available in premium version only', 'arprice-responsive-pricing-table'); ?>
        </div>
        <div class="preview_close" id="prev_close_icon">
            <span class="modal_close_btn b-close"></span>
        </div>
    </div>
    <div class="preview_model" style="float:left;width:100%;height:90%;">

    </div>
</div>

<div class="arp_upgrade_modal" id="arplite_save_table_notice_editor" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('You can create maximum 4 tables in free version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
    </div>
</div>

<div class="arp_subscription_model" id="arplite_subscription_model" style="display:none;">
    <div class="arp_subscription_model_close_btn"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL . '/icons/close_button.png' ?>" height="15" width="15" /></div>
    <form name="arplite_subscription" method="get" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="page" value="arpricelite" />
        <div class="arp_subscription_header_wrapper">
            <div class="arp_subscription_header">
                <div class="arp_subscription_model_title"> <?php esc_html_e('Subscribe with Us', 'arprice-responsive-pricing-table'); ?> </div>
                <div class="arp_subscription_model_subtitle">Get interesting offers and update notifications straight into your email Inbox. Only few mails a year.</div>
                <div class="arp_subscription_form">
                    <input type="text" name="subscription_email" id="subscription_email" placeholder="Enter Your Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Your Email'" class="arp_subscription_field" />
                    <div class="arperrmessage subscribeerror" id="subscription_email_error" style="display:none;"><?php esc_html_e('This field cannot be blank.', 'arprice-responsive-pricing-table'); ?></div>
                </div>
            </div>
        </div>
        <div class="arp_subscription_submit_button_wrapper">
            <button type="button" name="arp_subscribe" class="arp_subscribe_button" id="subscribe-arprice" value="subscribe"><?php esc_html_e('Send it now', 'arprice-responsive-pricing-table'); ?></button>
            <span id="subscribe_loader" style="display:none;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL . '/ajax_loader_add_new_column.gif'; ?>" height="15" /></span>
            <span class="arplite_subscription_note"><?php esc_html_e('We respect your privacy. We will NEVER share your detail anywhere.', 'arprice-responsive-pricing-table') ?></span>
        </div>
    </form>
</div>

<div class="arp_black_friday_sale_popup_wrapper">
    <div class="arp_black_friday_sale_popup_container">
        <span class="arp_black_friday_sale_close_btn" id="arp_black_friday_sale_close_btn">
            <i class="fa fa-times fa-lg"></i>
        </span>
        <span class="arp_bf_sale_title">UPGRADE TO PREMIUM VERSION</span>
        <span class="arp_bf_sale_text">
            BLACK FRIDAY SALE
        </span>
        <span class="arp_bf_discount_price">FLAT 50% OFF PREMIUM</span>
        <span class="arp_bf_limited_text_wrapper">LIMITED TIME OFFER</span>
        <a class="arf_bf_popup_btn" href="https://1.envato.market/9o9x4" target='_blank'>UPGRADE TO PREMIUM</a>
    </div>
</div>