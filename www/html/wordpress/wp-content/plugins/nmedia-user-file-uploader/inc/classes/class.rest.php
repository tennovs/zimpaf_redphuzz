<?php
/**
 * WPFM REST API
 * 
 **/
 
class WPFM_REST {
    
    function __construct(){
         
         add_action('rest_api_init', array($this, 'rest_api'));
     }
     
     function rest_api() {
         
        // handle add new question
        register_rest_route( 'wpfm/v1', '/file-rename', array(
 		    'methods' => 'POST',
 		    'callback' => array($this, 'rename_file'),
 		    'permission_callback' => '__return_true'
 		) );
     }
     
     // Rename the file
     function rename_file($request) {
         
         $params = $request->get_params();
         $fileobj = new WPFM_File($params['fileid']);
         $file_dir_path = wpfm_files_setup_get_directory($fileobj->owner_id);
         $file_new = $file_dir_path.$params['filename'];
         $resp = rename($fileobj->path, $file_new);
         
         if( $resp )
            $fileobj->rename_file($params['filename']);
         
         wp_send_json($params);
     }
}

new WPFM_REST;