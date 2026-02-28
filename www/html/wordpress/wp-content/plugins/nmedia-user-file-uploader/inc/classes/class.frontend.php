<?php
/**
 * Class Frontend
 * 
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 

class FFMANAGER_FRONTEND{
    
    /**
     * the static object instace
     */
    private static $ins = null;
    
    public static function get_instance() {

        // create a new object if it doesn't exist.
        is_null(self::$ins) && self::$ins = new self;
        return self::$ins;
    }    
    
    function __construct() {
        
        add_shortcode('nm-wp-file-uploader', array($this,'ffmwp_render_frontend'));
        add_shortcode('ffmwp', array($this,'ffmwp_render_frontend'));
    }

    function ffmwp_render_frontend($atts) {
        
        $context = 'upload';
        
        $shortcode_groups = wpfm_extrac_group_from_shortcode($atts);
    	set_query_var('shortcode_groups', $shortcode_groups);
    	
    	$wpfm_bp_group_id = wpfm_extract_bp_group_from_shortcode($atts);
    	set_query_var('wpfm_bp_group_id', $wpfm_bp_group_id);
    	
		$allow_public = wpfm_is_guest_upload_allow( $atts );
	
    	if ( !is_user_logged_in() && !$allow_public) {
    
    		$public_message = wpfm_get_option('_public_message');
    		if($public_message != ''){
    			ob_start ();
    		
    			printf(__('%s', 'wpfm'), $public_message);
    			$output_string = ob_get_contents ();
    			ob_end_clean ();
    			return $output_string;
    		}else{
    			echo '<script type="text/javascript">
    			window.location = "'.wp_login_url( get_permalink() ).'"
    			</script>';
    			return ;
    		}
    	}
    	
    	//call scripts
        $this->ffmwp_functions_scripts($context);
        
    	$template_vars = apply_filters('ffmwp_template_vars', [ 'shortcode_groups' => $shortcode_groups, 
                                                                'wpfm_bp_group_id'=>$wpfm_bp_group_id,
                                                                'files_view'        => wpfm_get_view_type(),
                                                                'context'   => $context,
                                                                ]
                                    );
                                    
    	ob_start();
        ffmwp_load_template('index.php', $template_vars);
        return ob_get_clean();
    }
    
    
    
    function ffmwp_functions_scripts($context="upload"){
        
        // File Upload
        if( wpfm_is_upload_form_visible( $context ) ) {
            // Select2
            wp_enqueue_style( 'wpfm-select', WPFM_URL .'/css/select2.css');
            wp_enqueue_script( 'wpfm-select-js', WPFM_URL .'/js/select2.js', array('jquery'));
        
            wp_enqueue_script( 'wpfm-fileapi', WPFM_URL.'/js/fileapi/dist/FileAPI.min.js');
            wp_enqueue_script( 'wpfm-file', WPFM_URL.'/v20/js/fileupload.js', array('wpfm-fileapi'));
            wp_localize_script('wpfm-file', 'ffmwp_file_vars', $this->js_vars($context));
        }
        
    	wp_enqueue_style( 'ffmwp-css', WPFM_URL.'/v20/css/ffmwp.css' );
        // SweetAlert
        wp_enqueue_style( 'wpfm-swal', WPFM_URL .'/js/swal/sweetalert.css');
        wp_enqueue_script( 'wpfm-swal-js', WPFM_URL .'/js/swal/sweetalert.js', array('jquery'));
        wp_enqueue_script( 'wpfm-blcok-ui-js', WPFM_URL .'/js/block-ui.js', array('jquery','jquery-ui-core'));
        
        
        if (wpfm_is_files_area_visible()) {
            
        }
        
        wp_enqueue_script('ffmwp-ppom-modal-js', WPFM_URL.'/v20/js/ffmwp-ppom-modal.js',array('jquery'), WPFM_VERSION, true);
        wp_enqueue_style( 'ffmwp-ppom-modal-css', WPFM_URL.'/v20/css/ffmwp-ppom-modal.css' ); 
        
        // wp_enqueue_script( 'wpfm-mixitup-js', WPFM_URL .'/js/jquery.mixitup.min.js', array('jquery'));
        wp_enqueue_script( 'wpfm-mixitup-js', WPFM_URL .'/js/mixitup.v3.3.1.js', array('jquery'));
        
        // Drag/drop
        $allow_dragdrop = wpfm_get_option('_files_move');
        if( $allow_dragdrop == 'yes' ) {
            wp_enqueue_script('ffmwp-dragdrop', WPFM_URL.'/v20/js/dragdrop.js',array('jquery-ui-draggable', 'jquery-ui-droppable'), WPFM_VERSION, true );
        }
        
        wp_enqueue_style( 'ppom-grid-css', WPFM_URL.'/v20/css/ppom-grid.css' );
        wp_enqueue_script('ffmwp-util-js', WPFM_URL.'/v20/js/ffmwp-util.js',array('jquery','wp-util'), WPFM_VERSION, true );
        wp_enqueue_script('ffmwp-js', WPFM_URL.'/v20/js/ffmwp.js',array('ffmwp-ppom-modal-js','ffmwp-util-js','wpfm-mixitup-js'), WPFM_VERSION, true );
        
        // Dashicons frontend
        wp_enqueue_style( 'dashicons' );
        
        wp_localize_script('ffmwp-js', 'ffmwp_vars', $this->js_vars($context));
        
        // legacy
        do_action( 'wpfm_after_scripts_loaded' );
        
        do_action( 'ffmwp_after_scripts_loaded', $context );
        
    }
    
    function js_vars($context) {
        
        $allow_dragdrop = wpfm_get_option('_files_move');
        
        $ffmwp_user_files = [];
        if( $context == 'download' ) {
            $ffmwp_user_files = WPFM_DOWNLOAD()->get_files();
        } else {
            $ffmwp_user_files = wpfm_get_user_files();
        }
        
        
        $messages_js = array('files_loading'	=> __('Files are being loaded ...', 'wpfm'),
	 						'file_sharing'	=> __('Please wait ...', 'wpfm'),
	 						'file_uploading'=> __('File(s) are being saved', 'wpfm'),
	 						'file_data_saving'=> __('File(s) data is saving ...', 'wpfm'),
	 						'file_upload_completed' => __('File Upload Complete', 'wpfm'),
	 						'file_uploaded' => wpfm_get_message_file_saved(),
	 						'file_upload_error' => __('Sorry, but some error while uplaoding. Please try again', 'wpfm'),
	 						'file_dimension_error' => __("Image Dimensions are Not Allowed, Required: \n", 'wpfm'),
	 						'file_size_error' => __("File Size Not Allowed, Required: \n", 'wpfm'),
	 						'file_delete' => __('Are you sure?', 'wpfm'),
	 						'file_deleting' => __('Deleting file ...', 'wpfm'),
	 						'file_deleted' => __('File deleted', 'wpfm'),
	 						'file_updating' => __('Updating file ...', 'wpfm'),
	 						'directory_creating' => __('Creating directory ...', 'wpfm'),
	 						'select_group' => __('Select Group', 'wpfm'),
	 						'http_server_error' => __("Oops! Something wrong with server, please try again"),
	 						'file_type_error'	=> sprintf(__("Allowed Types: %s",'wpfm'), implode(',',wpfm_get_allowed_file_types())),
	 						'text_cancel'		=> __("Cancel", 'wpfm'),
	 						'text_yes'		=> __("Yes", 'wpfm'),
	 						'text_share'		=> __("Share", 'wpfm'),
	 						'text_description'	=> __("Description", 'wpfm'),
	 						'text_title'	=> __("Title", 'wpfm'),
	 						'wpfm_lib_msg' => __('Just a moment...', 'wpfm'),
	 						'button_meta_save' => __('Save Meta', 'wpfm'),
	 						'file_id'	=> __('File ID', 'wpfm'),
	 						'file_title' => __('File Title', 'wpfm'),
	 						'file_name' => __('File Name', 'wpfm'),
	 						'file_size' => __('File Size', 'wpfm'),
	 						'file_source' => __('File Source', 'wpfm'),
	 						'file_source_aws' => __('AWS/S3', 'wpfm'),
	 						'file_source_local' => __('Local Server', 'wpfm'),
	 						'total_downloads' => __('Total Downloads', 'wpfm'),
	 						'file_remove' => __('Remove File', 'wpfm'),
	 						'uploaded_on' => __('Upload on', 'wpfm'),
	 						'file_max_user'=> sprintf("Your file upload limit is %d", wpfm_files_allower_per_user()),
	 					);
        
        $vars = ['template_data'    =>
                     ['user_files'          => $ffmwp_user_files,
                      'enable_email_share'  => true],
                      'ajaxurl'             => admin_url( 'admin-ajax.php', (is_ssl() ? 'https' : 'http') ),
                      'rest_api_url'        => get_rest_url(null, 'wpfm/v1'),
                      'default_bc'          => ['id'=>0,'title'=>__('Home','wpfm'),
                      'file_delete'         => __('Are you sure?', 'wpfm'),
                    ],
                'plugin_url'          => WPFM_URL,
                'labels'                => $messages_js,
                'allow_group_frontend' 	=> wpfm_can_user_choose_group_fileupload(),
                'file_groups'           => wpfm_get_file_groups(),
                
                // checking if amazon enable
			    'amazon_enabled'	=> wpfm_is_amazon_addon_enable() ? 'yes' : 'no',
			    
			    // file meta
			    'file_meta'			=> wpfm_get_fields_meta_array(),
                
                // Image related
    			'image_sizing' 		=> (wpfm_get_option('_enable_image_sizing') == 'yes') ? true : false,
    			'image_min_width' 	=> (wpfm_get_option('_image_min_width') != '') ? wpfm_get_option('_image_min_width') : '320',
    			'image_min_height' 	=> (wpfm_get_option('_image_min_height') != '') ? wpfm_get_option('_image_min_height') : '240',
    			'image_max_width' 	=> (wpfm_get_option('_image_max_width') != '') ? wpfm_get_option('_image_max_width') : '3840',
    			'image_max_height' 	=> (wpfm_get_option('_image_max_height') != '') ? wpfm_get_option('_image_max_height') : '2160',
    			'image_resize' 		=> (wpfm_get_option('_resize_transform') != '') ? wpfm_get_option('_resize_transform') : false,
    			'image_size' 		=> (wpfm_get_option('_thumb_size') != '') ? wpfm_get_option('_thumb_size') : '150',
    			
    			// File releated
    			'max_file_size' 	=> wpfm_max_filesize_limit_by_role(),
    			'max_files' 		=> user_can_upload_file_one_atemp(),
    			'max_files_message' => sprintf(__("Max. %d file(s) can be uploaded", 'wpfm'), user_can_upload_file_one_atemp()),
    			'file_types' 		=> wpfm_get_allowed_file_types(),
    			'file_auto_upload' 	=> (wpfm_get_option('_file_auto_upload') == 'yes') ? true : false,
    			'file_allow_duplicate' => (wpfm_get_option('_file_allow_duplicate') == 'yes') ? true : false,
    			'file_drag_drop' 	=> wpfm_get_option('_file_allow_drag_n_drop'),
    			'total_file_allow'  => wpfm_files_allower_per_user(),
    			'image_preview_ph'  => WPFM_URL.'/images/file-icon.png',
    			'files_view'        => wpfm_get_view_type(),
    			'dragdrop_allow'    => $allow_dragdrop == 'yes' ? true : false,
    			'files_area_display'=> wpfm_is_files_area_visible($context),
    			'is_revision_addon' => class_exists('WPFM_FileRevision'),
                ];
                
        return apply_filters('ffmwp_js_vars', $vars);
    }
    
}

function FFWP_Frontend() {
    return FFMANAGER_FRONTEND::get_instance();
}