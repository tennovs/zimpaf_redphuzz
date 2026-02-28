<?php
/**
 * FrontEnd FileManager WP Index Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; }

/**
 * Nonce for all operations
**/
wp_nonce_field('wpfm_securing_ajax','wpfm_ajax_nonce');
?>

<div class="ffmwp-admin-wrapper">
    
    <?php    
        
        
        if( wpfm_is_upload_form_visible( $context ) ) {
            ffmwp_load_template("parts/upload-area.php", ['wpfm_bp_group_id'=>$wpfm_bp_group_id, 'shortcode_groups' => $shortcode_groups,]);
            ffmwp_load_template("parts/file-form.php", ['wpfm_bp_group_id'=>$wpfm_bp_group_id, 'shortcode_groups' => $shortcode_groups,]);
            ffmwp_load_template("parts/file-meta.php");
        }
        
        if (wpfm_is_files_area_visible($context)) {
            
            ffmwp_load_template("parts/top-bar.php"); 
            
            ffmwp_load_template("parts/tools.php");
        
            ffmwp_load_template("parts/{$files_view}.php");
        
            ffmwp_load_template("parts/modal.php");
        
            ffmwp_load_template("parts/modal-left.php");
        
            ffmwp_load_template("parts/modal-right.php");
        }
        
    ?>
    
</div>