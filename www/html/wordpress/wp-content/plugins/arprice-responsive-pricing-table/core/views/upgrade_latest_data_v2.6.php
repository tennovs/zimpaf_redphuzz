<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb,$arpricelite_import_export,$arplite_pricingtable,$arpricelite_img_css_version;

update_option('arplite_db_version_before_2.6',$checkupdate);

@ini_set('max_execution_time', 0);

/* REMOVING BACKUP OF TABLES */

$wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix . "arplite_arprice_backup_v2.0`" );
$wpdb->query( "DROP TABLE IF EXISTS `".$wpdb->prefix . "arplite_arprice_options_backup_v2.0`" );

$wp_upload_dir = wp_upload_dir();
$backup_dir = $wp_upload_dir['basedir'].'/arprice-responsive-pricing-table_backup_v2';
if( is_dir($backup_dir) ){
	arplite_rmdir( $backup_dir );
}

/* CREATING BACKUP OF TABLES */

$arplite_price_backup_tbl = $wpdb->prefix.'arplite_arprice_backup_v2.6';
$arplite_price_options_backup_tbl = $wpdb->prefix.'arplite_arprice_options_backup_v2.6';

$wpdb->query("CREATE TABLE `".$arplite_price_backup_tbl."` LIKE `".$wpdb->prefix."arplite_arprice`");
$wpdb->query("INSERT `".$arplite_price_backup_tbl."` SELECT * FROM `".$wpdb->prefix."arplite_arprice`");

$wpdb->query("CREATE TABLE `".$arplite_price_options_backup_tbl."` LIKE `".$wpdb->prefix."arplite_arprice_options`");
$wpdb->query("INSERT `".$arplite_price_options_backup_tbl."` SELECT * FROM `".$wpdb->prefix."arplite_arprice_options`");

$wp_upload_dir = wp_upload_dir();
$source_dir = $wp_upload_dir['basedir'].'/arprice-responsive-pricing-table';
$destination_dir = $wp_upload_dir['basedir'].'/arprice-responsive-pricing-table_backup_v26';

$arplite_pricingtable->arplite_copy_folder($source_dir,$destination_dir,0755);

update_option( 'arp_2_6_update_date', date( 'Y-m-d H:i:s' ) );
$timestamp = strtotime('+1 month');
wp_schedule_single_event( $timestamp, 'arplite_remove_backup_data' );

/* Removing Preview Table Data */
$wpdb->query("DELETE FROM " . $wpdb->options . " WHERE option_name LIKE '%arp_previewtabledata_%'");

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
        
        $columns['package_title'] = $arpricelite_import_export->update_fa_font_class($columns['package_title']);
        $columns['arp_header_shortcode'] = $arpricelite_import_export->update_fa_font_class($columns['arp_header_shortcode']);
        $columns['price_text'] = $arpricelite_import_export->update_fa_font_class($columns['price_text']);
        $columns['column_description'] = $arpricelite_import_export->update_fa_font_class($columns['column_description']);
        $columns['button_text'] = $arpricelite_import_export->update_fa_font_class($columns['button_text']);

        $column_opts[$c] = $columns;

        if( is_array( $columns['rows']) && count($columns['rows']) > 0 ){
            foreach( $columns['rows'] as $r => $row ){
                $row['row_description'] = $arpricelite_import_export->update_fa_font_class($row['row_description']);
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
    
    global $arplite_images_css_previous_version;
    if( $arplite_images_css_previous_version == '' ){
        $arplite_images_css_previous_version = '1.0';
    }
    $source_template_dir = ARPLITE_PRICINGTABLE_DIR.'/css/templates';

    $template_dir = opendir($source_template_dir);

    while(($file = readdir($template_dir)) != false ){
        if( $file != '' && file_exists($source_template_dir.'/'.$file)  ){
            $pattern = '/arplitetemplate_(\d+)_v'.$arplite_images_css_previous_version.'.css/';
            if( preg_match($pattern,$file) ){
                unlink($source_template_dir.'/'.$file);
            }
        }
    }
    $enable_fonts = array('enable_fontawesome_icon');
    update_option('enable_font_loading_icon',$enable_fonts);

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

    $nextEvent = strtotime('+1 week');
    wp_schedule_single_event( $nextEvent, 'arplite_display_ratenow_popup' );

}