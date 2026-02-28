<?php
/**
 * WPFM_File Class
 * handle all file related functions
 */

class WPFM_File {
    
    // class properties
    protected static $file_id;
    
    // legacy file
    private $legacy;
    
    function __construct( $file_id ) {
        
        self::$file_id = $file_id;
        $this->legacy = new WPFM_File_Legacy($file_id);
        
        // Setting default context to upload
        $this->context = 'upload';
        
        //Now we are creating properties agains each methods in our Alpha class.
        $methods = get_class_methods( $this );
        $excluded_methods = array('__construct',
                                    'file_instance',
                                    'set_meta',
                                    'get_meta',
                                    'is_dir',
                                    'delete_file',
                                    'add_file_hash',
                                    'file_hash_matched',
                                    'set_context',
                                    'rename_file',
                                    );
                                    
		foreach ( $methods as $method ) {
			if ( ! in_array($method, $excluded_methods) ) {
				$this->$method = $this->$method();
			}
		}
		
    }
    
    // function get_price

    public static function file_instance($file_id){
        
        $inst = new WPFM_File($file_id);
        return $inst;
    }
        
    // Set file meta
    function set_meta( $key, $value ) {
        
        update_post_meta( self::$file_id, $key, $value );
    }
    
    // Get file meta
    function get_meta( $key ) {
        
        $meta_value = get_post_meta( self::$file_id, $key, true );
        return $meta_value;
    }
    
    // Check if file is dir
    function is_dir() {

        $is_dir = ($this->node_type() == 'file' ) ? false : true;
        return apply_filters('wpfm_is_dir', $is_dir, $this);
    }
    
    
    function id() {
        
        return self::$file_id;
    }
    /**
     * Functions over files
     */
    function name() {
        
        //@Fayaz: replace with wpfm_get_attachment_file_name()
        // New version: first check in meta
        $filename = $this->get_meta('wpfm_file_name');
        if( ! empty($filename) ) 
            return $filename;
        
        $filename = $this->legacy->name();
        
        // $this->node_type = ( empty($filename) ? 'dir' : 'file');
        return $filename;
    }

    function title() {

        $title = null;
        
        if( ! $title = $this -> get_meta('wpfm_title') ) {
            
            $title = $this->legacy->title();
        }
        // var_dump($title.'==>'.$this->node_type);
        
        return apply_filters( 'wpfm_title', $title, $this);
    }
    
    function description() {

        $description = null;
        
        if( ! $description = $this -> get_meta('wpfm_description') ) {
            
            $description = $this->legacy->description();
        }
        
        return apply_filters( 'wpfm_description', $description, $this);
    }
    
    
    function price() {
        
        if( $this->node_type() != 'file' ) return '';
        
        // Checking Indivisual File Price
        $file_price = get_post_meta( $this->id(), 'edd_file_price', true);
        $price = '';
        if ( !empty($file_price) ) {
            $price = $file_price;
        }
        return $price;
    }
    
    function price_html() {
        $price = '';
            $price = $this->price;
            if ( function_exists('wc_price') ) {
                
                $price = wc_price($price);
            }
        
        return $price;
    }
    // get file size
    function size() {

        $file_size = null;
        
        if( ! $file_size = $this -> get_meta('wpfm_file_size') ) {
            
            $file_size = $this->legacy->size();
        }
        
        return $file_size;
    }

    function file_parent() {

        $parent = null;
        
        if( ! $parent = $this -> get_meta('wpfm_file_parent') ) {
            
            $parent = $this->legacy->file_parent();
        }
        
        return $parent;
    }

    // get file path
    function path() {

        $file_dir_path = null;
        //First check in meta (new vesion)
        // var_dump($this->id, $this -> get_meta('wpfm_dir_path'));
        
        if( ! $file_dir_path = $this -> get_meta('wpfm_dir_path') ) {
            $file_dir_path = $this->legacy->path();
        }
        
        if( ! is_file($file_dir_path) ) {
            $file_dir_path = null;
        }
        
        
        return apply_filters('wpfm_file_path', $file_dir_path, $this);
    }
    
    // get file url
    function url() {

        $file_url = null;
        
        if( ! $file_url = $this -> get_meta('wpfm_file_url') ) {
            
            $file_url = $this->legacy->url();
        }
        
        return apply_filters( 'wpfm_file_url', $file_url, $this);
    }
    
    // get file thumb
    function thumb_url(){


        $file_thumb_url = null;
        
        if ( $this->is_dir() ) {
            
            $thumb_url = WPFM_URL."/images/folder_icon.png";

        }elseif ( $this->location() == 'amazon' ) {

            $h = 50;
            $w = 50;
            
            if(! wpfm_is_image($this->name)){
                $thumb_url = wpfm_file_icon($this->name);
            }else{
                if( ! $thumb_url = $this -> get_meta('wpfm_file_thumb_url') ) {
                    $thumb_url = $this->legacy->thumb_url();
                }
            }
        }elseif ( ! wpfm_is_image($this->name) ) {

            $thumb_url = wpfm_file_icon($this->name);
            
        }elseif ( wpfm_is_image($this->name) ) {
            
            if( ! $thumb_url = $this -> get_meta('wpfm_file_thumb_url') ) {
                $thumb_url = $this->legacy->thumb_url();
            }
        }
        
        
        return apply_filters( 'wpfm_file_thumb_url', $thumb_url, $this);
    }
    
    
    // get thumb image for file
    function thumb_image() {
        
        if( is_admin() ) {
            $h = $w = 50;
        }else {
            $h = wpfm_get_option('_thumb_size') != "" ? wpfm_get_option('_thumb_size') : 100;
            $w = wpfm_get_option('_thumb_size') != "" ? wpfm_get_option('_thumb_size') : 100;
        }
        
        $thumb_size = apply_filters('wpfm_thumb_size', array('w'=>$w,'h'=>$h), $this);
        
        $thumbnail = '<img height="'.esc_attr($thumb_size['h']).'" width="'.esc_attr($thumb_size['w']).'" src="'.esc_url($this->thumb_url).'" class="img-thumbnail wpfm-img" style="width: auto;">';
        
        return apply_filters('wpfm_thumb_image', $thumbnail, $this->thumb_url, $this);
    }
    
    // get file location 
    function location() {
        
        if( $this->is_dir() ) return null;
        
        $file_location = $this -> get_meta('wpfm_file_location');
    
        return apply_filters('wpfm_file_location', $file_location, $this);
    }
    
    // get file owner/author
    function owner_id() {
        
        $file_owner = get_post_field('post_author', self::$file_id);
        return $file_owner;
    }

    // file created date
    function created_on() {

        $created_on = null;
        
        if( ! $created_on = $this -> get_meta('wpfm_created_on') ) {
            
            $created_on = $this->legacy->created_on();
        }
        
        return apply_filters( 'wpfm_created_on', $created_on, $this);        
    }

    function amazon_data() {

        $amazon_data    = $this->get_meta('wpfm_amazon_data');
        return $amazon_data;
    }
    
    // Important: Generating download URL for file
    function download_url() {
        
        $secure_url = '';
        
        // check it's being called from downloader
        $download_id = get_query_var('download_id');
        $download_id = empty($download_id) ? false : $download_id;
        
        $param_download = array(
                            'do'            => 'wpfm_download',
                            'file_id'       => $this->id,
                            'download_id'   => $download_id);
                            
        $naked_url = add_query_arg($param_download, site_url());
        $secure_url = wp_nonce_url($naked_url, 'securing_file_download', 'nm_file_nonce');
        
        return apply_filters('wpfm_download_url', $secure_url, $this);
        
        if( $this->location() == 'amazon' && wpfm_is_amazon_addon_enable() ) {
            
            $amazon_key     = wpfm_get_option('_amazon_apikey');
            $amazon_secret  = wpfm_get_option('_amazon_apisecret');
            $expires        = wpfm_get_option('_amazon_expires');
            
            $expires = $expires == '' ? 1 : $expires;
            
            $amazon_data = $this->get_meta('wpfm_amazon_data');
            
            $bucket_file    = $amazon_data['bucket'];
            $bucket_key     = $amazon_data['key'];
            
            $secure_url     = 'AMAZON_URL';
            /*$secure_url = WPFM_AMAZON() -> download_s3_file($bucket_file, $bucket_key, $expires);
            if( is_wp_error( $secure_url ) )
                $secure_url = null;*/
            
        } else {
            
            
            
        }
        
        
    }
    
    function shared_info() {
        
        // getting all shared user ids if found
        return $this->get_meta('shared_with');
    }
    
    
    function total_downloads() {
        
        $total_downloads = $this->get_meta('wpfm_total_downloads');
        
        if( ! $total_downloads ) {
            
            $total_downloads = 0;
        }
        
        return apply_filters('wpfm_total_downloads', $total_downloads, $this);
    }
    
    function node_type() {

        $node_type = null;
        
        if( ! $node_type = $this->get_meta('wpfm_node_type') ) {
            
            
            $filename = $this->name();
            $node_type = ( empty($filename) ? 'dir' : 'file');

        }
        
        return apply_filters('wpfm_node_type', $node_type, $this);
    }

    function file_meta() {
        
        $file_meta = array();
        $wpfm_meta_fields_array = wpfm_get_fields_meta();
        // wpfm_pa($wpfm_meta_fields_array);
        if( $wpfm_meta_fields_array ) {
            
            foreach ($wpfm_meta_fields_array as $key => $meta_fields) {
                foreach ($meta_fields as $key => $value) {
    
                    $file_meta[$value['data_name']] = get_post_meta( self::$file_id, $value['data_name'], true );
                }
            }
        }
        return $file_meta;
    }

    function file_meta_html() {

        $fields = new WPFM_Fields( $this->id, $this->file_meta );
        $fields_html = $fields -> generate_fields();

        return $fields_html;
    }
    
    function file_meta_info() {

        $fields = new WPFM_Fields( $this->id, $this->file_meta );
        $fields_html = $fields -> generate_saved_meta();

        return $fields_html;
    }

    function file_groups() {

        $terms = wp_get_post_terms( $this->id, 'file_groups', array("fields" => "slugs"));

        return $terms;
    }
    
    // get child/sub files for dir
    function children() {
        
        if( ! $this->is_dir() ) return null;
        
        $children = array();
        $wpfm_args = array(
                'orderby'       => wpfm_get_sort_by(),
                'order'         => wpfm_get_sort_order(),
                'post_type'     => 'wpfm-files',
                'post_status'   => 'publish',
                'nopaging'      => true,
                'post_parent'   => self::$file_id,
        );
        $child_files = get_posts($wpfm_args);
        
        foreach($child_files as $child){   
            
            $children[] = new WPFM_File($child->ID);
        }
        
        return apply_filters('wpfm_children', $children, $this);
        
    }


    /**
    **      HTML rendering property/methods
    ** ========================================
    */

    function delete_button() {
        
        $current_user = wpfm_get_current_user();
        
        $link_html = '';
        
        // Only allow file owner to delete it
        if( $current_user && $this->owner_id == $current_user->ID ) {
            $link_html .= '<button class="btn view-icon btn-danger del-file-btn" data-id="'.esc_attr($this->id).'">';
            $link_html .= '<span class="dashicons dashicons-trash"></span></button>';
        }
        
        return apply_filters('wpfm_file_delete_button', $link_html, $this);
    }
    
    
    function owner_image(){
        
        $user = get_userdata( $this->owner_id );
        
        // sometimes user deleted but file remains
        if( !$user ) return '';
        
        $html = '';
        $html.= '<span class="wpfm-avatar">'.get_avatar($this->owner_id, 100).'</span>';
        $html.= '<span class="wpfm-avatar-title">'.$user->user_login.'</span>';
      
        return $html;
        
    }
    
    
    function title_date(){
        
        $title = $this->title;
        
        $html = $title;
        $html.= '<span class="wpfm-date">'.nm_plugin_time_difference($this->created_on).'</span>';
      
        return $html;
        
    }
    
    
    /** File Revision Addon */
    function update_button() {
        
        if( ! $this->is_updateable() ) return '';
       
        $param_update = array('file_id' => $this->id);
       
        $url = get_permalink();
                            
        $naked_url = add_query_arg($param_update, $url);
        
        $current_user = wpfm_get_current_user();
        
        $link_html = '';
      
        // Only allow file owner to update it
        if( $current_user && $this->owner_id == $current_user->ID ) {
            
            $link_html = '<a class="ffmwp-file-icons-content ffmwp-revision"';
            $link_html .= ' data-id="'.esc_attr($this->id).'"';
            $link_html .= ' title="'.__('Update File','wpfm').'"';
            $link_html .= ' href="'.esc_url($naked_url).'">';
            $link_html .= '<span class="dashicons dashicons-backup"></span>';
    
            $link_html .= '</a>';
            
        }
        
        return apply_filters('wpfm_file_update_button', $link_html, $this);
    }
    
    function exist_filenames(){
        
        $files_names = $this->get_meta('previous_file_name');
        $current_user = wpfm_get_current_user();
      
        $link_html = '';
        $link_html .= '<tr>';
        $link_html .= '<td><b>'.__('Revisions File').'</b></td>';
        $link_html .= '</tr>';
        $link_html .= '<tr>';
        $link_html .= '<td>';
        $link_html .= '<ol>';
        
        
        if(is_array($files_names)){
            
        
            foreach($files_names as $index => $name){
                
                $link_html .= '<li>';
                $link_html .= '<a class="" target="_blank" href="'.wpfm_get_file_dir_url($current_user->ID, false, $this->id).$name.'" >'.$name;
                $link_html .= '</a>';
                $link_html .= '</li>';
               
            }
            
            $link_html .= '</td>';
            $link_html .= '</tr>';
            $link_html .= '</ol>';
        }
        
        return apply_filters('wpfm_exist_filenames', $link_html, $this);
    }
    
    
    function download_button() {

        $is_enable_down_open =  wpfm_get_option('_down_open'); 
        $file_url = $this->download_url;
        $new_window = ''; 

        if ($is_enable_down_open == 'yes' && class_exists('WPFM_PRO') ) {
            $file_url = $this->url;
            $new_window = 'target=_blank'; 
        }              
        
        $link_html = '<a class="download-icon btn btn-dark" '.$new_window.'';
        $link_html .= ' data-id="'.esc_attr($this->id).'"';
        $link_html .= ' title="'.__('Download','wpfm').'"';
        $link_html .= ' href="'.esc_url($file_url).'">';
        $link_html .= '<span class="dashicons dashicons-download"></span>';
        // $link_html .= __("Download", 'wpfm');
        $link_html .= '</a>';
        
        return apply_filters('wpfm_download_button', $link_html, $this);
    }
    
    function download_button_v20() {

        $is_enable_down_open =  wpfm_get_option('_down_open'); 
        $file_url = $this->download_url;
        $new_window = ''; 

        if ($is_enable_down_open == 'yes' && class_exists('WPFM_PRO') ) {
            $file_url = $this->url;
            $new_window = 'target=_blank'; 
        }              
        
        $link_html = '<a class="ffmwp-file-icons-content ffmwp-download" '.$new_window.'';
        $link_html .= ' data-id="'.esc_attr($this->id).'"';
        $link_html .= ' title="'.__('Download','wpfm').'"';
        $link_html .= ' href="'.esc_url($file_url).'">';
        $link_html .= '<span class="dashicons dashicons-download"></span>';
        // $link_html .= __("Download", 'wpfm');
        $link_html .= '</a>';
        
        return apply_filters('wpfm_download_button', $link_html, $this);
    }
    
    function view_button() {
        
        $html = '';
        if ( $this->node_type == 'file' ) {
        
            $html .= '<a class="ffmwp-file-icons-content ffmwp-eye" title="'.esc_attr($this->title).'" href="#" data-ffmwp_modal-target="ffmwp-files-popup-'.esc_attr($this->id).'">';
            $html .= '<span class="dashicons dashicons-visibility"></span>';
            $html .= '</a>';
            
            
        } elseif( $this->node_type == 'dir' ) {
            
            $html .= '<span class="view-icon-dir btn wpfm-dir" data-node_id="'.esc_attr($this->id).'" data-title="'.esc_attr($this->title).'"><span class="dashicons dashicons-visibility"></span></span>';
            // $html .= '<a class="view-ico b wpfm-dir" href="#"><span class="dashicons dashicons-visibility"></span></a>';
        }
        
        return apply_filters('wpfm_view_btn', $html, $this);
    }
    
    // File sharing via user specific addon
    function share_button() {
        
        if( ! wpfm_is_addon_installed('user-specific') 
        || ! WPFM_US() -> is_user_allow_to_share() ) return '';
        
        $link_html = '<a class="btn view-icon share-file-btn wpfm-us-modal" data-toggle="modal"';
        $link_html .= ' data-post_id="'.esc_attr($this->id).'"';
        $link_html .= ' href="#"><span class="dashicons dashicons-share-alt2">';
        // $link_html .= __("Share", 'wpfm');
        $link_html .= '</a>';
        
        return apply_filters('wpfm_share_file', $link_html, $this);
    }
    
    // Version 20: UTIL: File sharing via user specific addon
    function share_button_v20() {
        
        if( ! wpfm_is_addon_installed('user-specific') 
        || ! WPFM_US() -> is_user_allow_to_share() ) return '';
        
        $link_html = '<a data-ffmwp_modal-target="ffmwp-us-popup" class="ffmwp-file-icons-content ffmwp-share-file"';
        $link_html .= ' data-post_id="'.esc_attr($this->id).'"';
        $link_html .= ' href="#"><span class="dashicons dashicons-share-alt2">';
        $link_html .= '</a>';
        
        return apply_filters('wpfm_share_file', $link_html, $this);
    }

    // check if file is sharable - checked js
    function is_share_enable() {
        
        $sharable = true;
        
        if( ! wpfm_is_addon_installed('user-specific') 
            || ! WPFM_US() -> is_user_allow_to_share() ) {
            $sharable = false;
        }
        
        // if view from download then disable sharing
        if( $this->view_context() == 'download' ) {
            $sharable = false;
        }
        
        // if view is shared
        if( defined('WPFM_REQUEST_TYPE') && WPFM_REQUEST_TYPE == 'wpfm_shared' ) {
            
            $sharable = false;
        }
        
        return $sharable;
    }
    
    // can user delete it
    function is_deletable() {
        
        $deletable = true;
        $is_allow_delete_file =  wpfm_get_option('_diss_allow_file'); 
        
        // if view from download then disable delete
        if( $this->view_context() == 'download' ) {
            $deletable = false;
        }
        
        // if view is shared
        if( defined('WPFM_REQUEST_TYPE') && WPFM_REQUEST_TYPE == 'wpfm_shared' ) {
            
            $deletable = false;
        }
        
         if( $is_allow_delete_file == 'yes' ) {
                
            $deletable = false;
        }
        
        return $deletable;
    }
    
     // can user update it
    function is_updateable() {
        
        $updateable = true;
        
        // if view from download then disable update
        if( $this->view_context() == 'download' ) {
            $updateable = false;
        }
        
        // if view is shared
        if( defined('WPFM_REQUEST_TYPE') && WPFM_REQUEST_TYPE == 'wpfm_shared' ) {
            
            $updateable = false;
        }

        
        return $updateable;
    }
    
    
    
    // Getting context 1. upload, 2. download
    function view_context() {
        
        $download_id = get_query_var('download_id');
        if( $download_id ) {
            $this->context = 'download';
        }
        
        return $this->context;
    }

    // This should be at last of all function.
    function file_detail_html() {
        
        return wpfm_get_file_detail( $this );
    }
    
    // Delete current file
    function delete_file($child_id=null) {
        
        $response = false;
        
        
        if( $this->node_type() == 'dir' ) {
            
            // Get children
            foreach($this->children as $child) {
                
                $child->delete_file($child->id);
                // var_dump('childid', ;$child->id);
            }
            
            if($child_id) wp_delete_post($child_id, true);
            
            $response = true;
        
        } elseif( $this->location() == 'local') {
                
            $attached_files = get_attached_media( '', $this->id );
            foreach ($attached_files as $attachment) {
                wp_delete_attachment( $attachment->ID, true );
            }
            
            $file_path = $this->path;
            
            $file_owner = get_post_field('post_author', $this->id);
            $file_name = wpfm_get_attachment_file_name($this->id);
            $file_dir_path = wpfm_get_author_file_dir_path($file_owner);
    
            if (file_exists($file_path)) {
				if (unlink($file_path)) {
				    $response = true;
				    $filename_new = preg_replace_callback('/\.\w+$/', function($m){
            		   return strtolower($m[0]);
            		}, $file_name);
					$file_path_thumb = $file_dir_path . 'thumbs/' . $filename_new;
					if (file_exists($file_path_thumb)) {
						unlink($file_path_thumb);
					}
				}
			}
        }elseif( $this->location() == 'amazon' && wpfm_is_amazon_addon_enable() ) {
            
            $amazon_key     = wpfm_get_option('_amazon_apikey');
            $amazon_secret  = wpfm_get_option('_amazon_apisecret');
            $expires        = wpfm_get_option('_amazon_expires');
            
            $amazon_data = $this->get_meta('wpfm_amazon_data');
            
            $bucket_file    = $amazon_data['bucket'];
            $bucket_key     = $amazon_data['key'];
            
            $result = WPFM_AMAZON() -> delete_file($bucket_file, $bucket_key);
            
            $response = true;
        }
        
        return $response;
    }
    
    
    function set_context( $context ) {
        
        $this->context = $context;
    }
    
    // Adding hashes in file meta for Email downloads
    function add_file_hash() {
        
        $file_hash  	= wp_generate_password(36);
        $file_hash  	= sanitize_key($file_hash);
        
        $prev_hash = get_post_meta($this->id, 'file_hashes', true);
        
        $file_hashes = array();
        if( !$prev_hash ) {
            
            $file_hashes = array($file_hash);
            update_post_meta($this->id, 'file_hashes', $file_hashes);
        } else {
            
            $file_hashes = $prev_hash;
            $file_hashes[] = $file_hash;
            update_post_meta($this->id, 'file_hashes', $file_hashes);
        }
        
        return $file_hash;
    }
    
    // Searching if file hash matached
    function file_hash_matched( $hash_founding ) {
        
        $hash_found = false;
        $file_hashes = get_post_meta($this->id, 'file_hashes', true);
        
        
        if( ! $file_hashes ) {
            
            $hash_found = false;
        } elseif( in_array($hash_founding, $file_hashes) ) {
            
            $hash_found = true;
        }
        
        return $hash_found;
    }
    
    function rename_file($new_filename){
        
        $file_dir_path = wpfm_files_setup_get_directory($this->owner_id);
        $file_new = $file_dir_path.$new_filename;
        $file_url = wpfm_get_file_dir_url($this->owner_id);
        $file_new_url = $file_url.$new_filename;
        $this->set_meta('wpfm_file_name', $new_filename);
        $this->set_meta('wpfm_dir_path', $file_new);
        $this->set_meta('wpfm_file_url', $file_new_url);
        
    }
}