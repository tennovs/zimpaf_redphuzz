<?php
/**
 * 
 * Legacy support
 * WPFM_File_Legacy Class
 * handle all file related functions
 */

class WPFM_File_Legacy extends WPFM_File {
    
    // public $id;
    function __construct($id) {
        
        // parent::__construct();
        // self::$id = self::$id();
        // var_dump(self::$id);
       /**
        * Nothing to do so far
        **/
    }
    
    
    function name() {

        $filename = '';
        $args = array(
        'post_type' => 'attachment',
        'numberposts' => null,
        'post_status' => null,
        'post_parent' => self::$file_id,
        );
        $attachments = get_posts($args);
        
        if ($attachments) {
            foreach($attachments as $attachment){
                $file_path = $this->get_meta('_wp_attached_file');
                $file_path = get_attached_file($attachment->ID, false);
                // var_dump($attachment->ID);
                // var_dump($file_path);
                // $file_type = wp_check_filetype(basename( $file_path[0] ), null );
                $file_type = wp_check_filetype(basename( $file_path ), null );
                $filename = basename ( get_attached_file( $attachment->ID ) );
            
            }
        }

        return $filename;
    }

    function title() {

        $title = get_the_title(self::$file_id);
        return $title;
    }
    
    function is_dir() {
        $file_name = $this->name();

        //var_dump($file_name);
        // $is_dir = $file_name == '' ? true : false;
        $is_dir = empty($file_name) ? true : false;
        // var_dump($is_dir);
        
        return $is_dir;
    }
    
    function description() {
        $content_post = get_post(self::$file_id);
        $description = $content_post->post_content;
        // $description = get_the_content();
        return $description;
    }

    
    function size() {
        
        $file_size = null;
        $file_dir_path = $this->path();
        
        if( file_exists($file_dir_path) ) {
            $file_size = size_format( filesize( $file_dir_path ));
        }
        
        return $file_size;
    }
    
    function file_parent() {
        
        $parent = wp_get_post_parent_id(self::$file_id);
        return $parent;
    }
    
    function path() {
        
        $file_owner = $this->owner_id();
        $file_name = $this->name();
        
        $file_dir_path = wpfm_get_author_file_dir_path($file_owner) . $file_name;
        return $file_dir_path;
    }
    
    function url() {
        
        $file_name  = $this->name();
        $file_owner = $this->owner_id();
       
        $file_url   = wpfm_get_file_dir_url($file_owner, false, self::$file_id) . $file_name;
        
        return $file_url;
    }
    
    function thumb_url() {
        
        $file_name  = $this->name();
        $file_owner = $this->owner_id();
        $file_thumb = wpfm_get_file_dir_url($file_owner, true, self::$file_id) . $file_name;
        return $file_thumb;
    }

    function created_on() {

        $date_created = get_the_date(wpfm_get_date_format(), self::$file_id);
        return $date_created;
    }
}