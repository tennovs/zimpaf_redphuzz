<?php
	if ( ! defined( 'ABSPATH' ) || !function_exists('current_user_can') || !current_user_can('arplite_import_export_pricingtables') || !isset($_GET['arplite_import_export_nonce']) || isset( $_GET['arplite_import_export_nonce'] ) && !wp_verify_nonce( $_GET['arplite_import_export_nonce'], 'arplite_import_export_nonce' ) ){
	    exit;
	}
?>
<div id="arp_loader_div" class="arp_loader" style="display: none;">
    <div class="arp_loader_img"></div>
</div>
<?php
global $arpricelite_version, $arplite_pricingtable;
global $wpdb, $arpricelite_import_export;

if (isset($_FILES["arp_pt_import_file"])) {
    global $wpdb, $WP_Filesystem;

    $file = $_FILES['arp_pt_import_file'];

    $arplite_fileobj = new ARPLiteFilecontroller( $file, false );

    if( false == $arplite_fileobj ){
        echo "Error: " . $arplite_fileobj->error_msg . "<br/>";
    } else {
        $wp_upload_dir = wp_upload_dir();
        $upload_dir = $wp_upload_dir['basedir'] . '/arprice-responsive-pricing-table/import/';

        $output_dir = $upload_dir;
        $output_url = $wp_upload_dir['baseurl'] . '/arprice-responsive-pricing-table/import/';

        if (!is_dir($output_dir)){
            wp_mkdir_p($output_dir);
        }

        $arplite_fileobj->check_caps = true;
        $arplite_fileobj->capabilities = array( 'arplite_import_export_pricingtables' );

        $arplite_fileobj->check_nonce = true;
        $arplite_fileobj->nonce_data = isset( $_POST['_wpnonce_arplite'] ) ? $_POST['_wpnonce_arplite'] : '';
        $arplite_fileobj->nonce_action = 'arplite_wp_nonce';

        $arplite_fileobj->check_only_image = false;

        $arplite_fileobj->check_specific_ext = true;
        $arplite_fileobj->allowed_ext = array( 'txt' );

        $destination = $output_dir . $file['name'];

        $arplite_fileobj->arplite_process_upload( $destination );

        if( false == $arplite_fileobj ){
            echo "<input type='hidden' id='arp_import_file_error' value='" . $arplite_fileobj->error_msg . "' />";
        } else {
            $explodezipfilename = explode(".", $file["name"]);
            $zipfilename = sanitize_text_field( $explodezipfilename[0] );
            echo "<input type='hidden' id='arp_perform_import_file_check' value='".$zipfilename."' />";
        }
    }
}
?>

<div class="arp_import_export_main" style="background-color: #fff;">

    <div class="arp_import_export_main_title"><?php esc_html_e('Import / Export Pricing Tables', 'arprice-responsive-pricing-table'); ?></div>
    <div class="clear" style="clear:both;"></div>
    <div class="success_message" id="import_success_message" style="">
        <?php esc_html_e('Table Imported Successfully', 'arprice-responsive-pricing-table'); ?>
    </div> 
    <div class="error_message arp_message_padding" id="import_validation_zip_error_message" style="display:none;">
        <?php esc_html_e('Please Select file exported from ARPrice Lite Plugin.', 'arprice-responsive-pricing-table'); ?>
    </div>
    <div class="error_message arp_message_padding" id="import_table_invalid_cap_error_message" style="display:none;">
        <?php esc_html_e('Sorry, you do not have permission to perform this action','arprice-responsive-pricing-table'); ?>
    </div>
    <div class="error_message arp_message_padding" id="import_table_invalid_nonce_error_message" style="display:none;">
        <?php esc_html_e('Sorry, your request cannot be processed due to security reason.','arprice-responsive-pricing-table'); ?>
    </div>
    <div class="error_message arp_message_padding" id="import_max_validation_zip_error_message" style="display:none;">
        <?php esc_html_e('You can create maximum 4 tables in free version.', 'arprice-responsive-pricing-table'); ?>
    </div>
    <div class="error_message arp_message_padding" id="import_invalid_zip_error_message" style="display:none;">
        <?php esc_html_e('Please Select Valid File.', 'arprice-responsive-pricing-table'); ?>
    </div>
    <div class="error_message arp_message_padding" id="import_blank_zip_error_message" style="display:none;">
        <?php esc_html_e('Please Select File.', 'arprice-responsive-pricing-table'); ?>
    </div>
    <div class="error_message arp_message_padding" id="export_blank_error_message" style="display:none;">
        <?php esc_html_e('Please Select Table.', 'arprice-responsive-pricing-table'); ?>
    </div>
    <div class="clear" style="clear:both;"></div>
    <div class="arp_import_export_main_inner">

        <div class="arp_export_section">

            <div class="arp_import_export_sub_title"><?php esc_html_e('Export Pricing Tables', 'arprice-responsive-pricing-table'); ?></div>

            <div class="import_export_list_main">
                <form  name="arplite_export" method="post" action="" id="arplite_export" onsubmit="return import_export_table();">
                    <div class="arp_import_export_frm_title"><?php esc_html_e('Please Select Table(s)', 'arprice-responsive-pricing-table'); ?></div>
                    <div class="arp_import_export_frm_select" id="export_table_lists">
                        <?php
                        global $wpdb;
                        $table = $wpdb->prefix . 'arplite_arprice';

                        $res_default_template = $wpdb->get_results("SELECT * FROM " . $table . " WHERE  status = 'published' AND is_template ='1' ");
                        $arplite_nonce = wp_create_nonce('arplite_wp_nonce'); ?>
                        <input type="hidden" name="_wpnonce_arplite" value="<?php echo esc_html( $arplite_nonce ); ?>">
                        <select multiple="multiple" name="table_to_export[]" id="table_to_export">
                            <?php
                            foreach ($res_default_template as $r) {
                                ?>
                                <option value="<?php echo esc_html( $r->ID ); ?>">Template ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $r->table_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo esc_html( $r->ID ); ?>]</option>
                                <?php
                            }

                            $res_new_template = $wpdb->get_results("SELECT * FROM " . $table . " WHERE  status = 'published' AND is_template ='0' ");

                            foreach ($res_new_template as $r) {
                                ?>
                                <option value="<?php echo esc_html( $r->ID ); ?>">Table ::&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $r->table_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $r->ID; ?>]</option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php ?>
                    </div>
                    <div class="clear" style="clear:both;"></div>
                    <div class="arp_import_export_frm_submit">
                        <button class="arp_import_export_btn" type="submit" name="arplite_export_tables"><img class="arp_import_export_btn_img"><span class="arp_import_export_btn_txt"><?php esc_html_e('Export', 'arprice-responsive-pricing-table'); ?></span></button> 
                    </div>
                </form>

            </div>
        </div> 


        <div class="arp_import_section">
            <div class="arp_import_export_sub_title"><?php esc_html_e('Import Pricing Tables', 'arprice-responsive-pricing-table'); ?></div>

            <div class="import_export_list_main">
                <form name="arp_import" id="arp_import" method="post" enctype="multipart/form-data" onsubmit="return check_valid_imported_file();" >
                    <?php $arplite_nonce = wp_create_nonce('arplite_wp_nonce'); ?>
                    <input type="hidden" name="_wpnonce_arplite" value="<?php echo esc_html( $arplite_nonce ); ?>">
                    <table align="left" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td colspan="3"><div class="arp_import_export_frm_title"><?php esc_html_e('Please Upload text file exported from ARPrice Lite plugin', 'arprice-responsive-pricing-table'); ?></div></td>
                        </tr>
                        <tr>
                            <td><div class="arp_import_export_select_title"><?php esc_html_e('Select File :', 'arprice-responsive-pricing-table'); ?></div></td>                                
                        </tr>

                        <tr>
                            <td>
                                <input type="file" style="opacity:0;width:0px !important;;height:0px !important;;padding:0px !important;" id="arp_pt_import_file" name="arp_pt_import_file"  />
                                <label for="arp_pt_import_file" class="arp_import_file_main">
                                    <div  class="text pd_input_control pd_input_small helpdesk_txt">
                                        <div class="arp_import_export_file_btn"><?php esc_html_e('Add File', 'arprice-responsive-pricing-table'); ?></div>
                                        <div id="arp_pt_import_file_name" class= "arp_import_file_name">
                                            <?php esc_html_e('No file Selected', 'arprice-responsive-pricing-table'); ?>
                                        </div>
                                    </div>
                                </label>    
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="arp_import_export_frm_submit">
                                    <button class="arp_import_export_btn" type="submit" name="imprort_file" id="import_file" style="margin-top: 20px;"><img class="arp_import_export_btn_img"><span class="arp_import_export_btn_txt"><?php esc_html_e('Import', 'arprice-responsive-pricing-table'); ?></span></button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

    </div>
</div>    


<div class="arp_upgrade_modal" id="arplite_custom_css_notice" style="display:none;">
    <div class="upgrade_modal_top_belt">
        <div class="logo" style="text-align:center;"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_update_logo.png" /></div>
        <div id="nav_style_close" class="close_button b-close"><img src="<?php echo ARPLITE_PRICINGTABLE_IMAGES_URL; ?>/arprice_upgrade_close_img.png" /></div>
    </div>
    <div class="upgrade_title"><?php esc_html_e('Upgrade To Premium Version.', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_message"><?php esc_html_e('You can create maximum 4 columns in free version', 'arprice-responsive-pricing-table'); ?></div>
    <div class="upgrade_modal_btn">
        <button id="pro_upgrade_button"  type="button" class="buy_now_button"><?php esc_html_e('Buy Now', 'arprice-responsive-pricing-table'); ?></button>
        <button id="pro_upgrade_cancel_button"  class="learn_more_button" type="button">Learn More</button>
        <input type="hidden" name="arp_version" id="arp_version" value="<?php echo esc_html( $arpricelite_version );?>" />
        <input type="hidden" name="arp_request_version" id="arp_request_version" value="<?php echo esc_html( get_bloginfo('version') ); ?>" />
    </div>
</div>