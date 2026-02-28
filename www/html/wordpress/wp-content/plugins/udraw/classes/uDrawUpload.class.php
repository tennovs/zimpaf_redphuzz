<?php

if (!class_exists('uDrawUpload')) {
    
    class uDrawUpload {
        
        public $mimes = array();
        public $upload_dir;
        public $upload_url;
        public $uploaded_files = array();
        
        function __construct() {}
        
        public function init() {
            add_filter('wp_check_filetype_and_ext', array(&$this, 'wp_check_filetype_and_ext'), 10, 4);
        }
        
        /**
         * Summary of wp_check_filetype_and_ext ( slightly modifed from the original wordpress core function.
         * @param mixed $file 
         * @param mixed $filename 
         * @param mixed $mimes 
         * @return mixed
         */
        function wp_check_filetype_and_ext($default, $file, $filename, $mimes = null ) {
            $proper_filename = false;
            
            // Do basic extension validation and MIME mapping
            $wp_filetype = wp_check_filetype( $filename, $mimes );
            $ext = $wp_filetype['ext'];
            $type = $wp_filetype['type'];

            // We can't do any further validation without a file to work with
            if ( ! file_exists( $file ) ) {
                return compact( 'ext', 'type', 'proper_filename' );
            }
            
            // Since SVG is an image, but getimagesize() doesn't support SVG, we use this as a work around to validate the svg file.
            if ($type == 'image/svg+xml' || $type == 'application/font-woff' || $type == 'application/postscript') {
                if (function_exists('finfo_open')) {
                    // Use finfo_file if available to validate non-image files.
                    $finfo = finfo_open( FILEINFO_MIME_TYPE );                    
                    $real_mime = finfo_file( $finfo, $file );
                    finfo_close( $finfo );
                    
                    if ($type == 'application/font-woff' || $type == 'application/postscript') {
                        // Allow application/octet-stream
                        if ( ($real_mime !== 'application/octet-stream' && $real_mime !== 'application/pdf') && ($real_mime !== $type) ) { 
                            $type = $ext = false;
                        }
                    } else {
                        // If the extension does not match the file's real type, return false.
                        if ( $real_mime !== $type ) {
                            $type = $ext = false;
                        }
                    }
                    
                    return compact( 'ext', 'type', 'proper_filename' );
                }
            }

            return $default;
        }
        
        public function handle_upload($files, $upload_dir = UDRAW_TEMP_UPLOAD_DIR, $upload_url = UDRAW_TEMP_UPLOAD_URL, $mimes = null, $filename = '') {
            if (empty($files) || is_null($files)) { return false; }
            
            $this->upload_dir = $upload_dir;
            $this->upload_url = $upload_url;
            
            // Create default accepted mimes if not passed to function.
            if (empty($mimes) || is_null($mimes)) {
                $this->mimes['jpg|jpeg|jpe'] = 'image/jpeg';
                $this->mimes['png'] = 'image/png';
                $this->mimes['svg'] = 'image/svg+xml';
                $this->mimes['pdf'] = 'application/pdf';
                $this->mimes['psd'] = 'application/octet-stream';
                $this->mimes['gif'] = 'image/gif';
                $this->mimes['woff'] = 'application/font-woff';
                $this->mimes['ttf'] = 'application/octet-stream';
            } else {
                $this->mimes = $mimes;
            }
            
            if ( ! function_exists( 'wp_handle_upload' ) ) { require_once( ABSPATH . 'wp-admin/includes/file.php' ); }
            
            if (gettype($files['name']) === 'array') {
                foreach ($files['name'] as $key => $value) {
                    if ($files['name'][$key]) {
                        $file = array(
                            'name'     => $files['name'][$key],
                            'type'     => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error'    => $files['error'][$key],
                            'size'     => $files['size'][$key],
                            'original_name' => $files['name'][$key]
                        );
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        if (strlen($filename) > 0) {
                            $_key = '_' . $key;
                            if (count($files['name']) === 1) {
                                $_key = '';
                            }
                            $file['name'] = sprintf('%s%s.%s', $filename, $_key, $ext);
                        }
                        $filesArray[$key] = $file;
                    }                                
                }
            } else if (gettype($files['name']) === 'string') {
                $file = array(
                    'name'     => $files['name'],
                    'type'     => $files['type'],
                    'tmp_name' => $files['tmp_name'],
                    'error'    => $files['error'],
                    'size'     => $files['size'],
                    'original_name' => $files['name']
                );
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (strlen($filename) > 0) {
                    $file['name'] = sprintf('%s.%s', $filename, $ext);
                }
                $filesArray[0] = $file;
            }

            if ($filesArray) {
                foreach ($filesArray as $key => $value) {
                    $file = $filesArray[$key];
                    $upload_overrides = array( 'test_form' => false, 'mimes' => $this->mimes);
                    add_filter( 'upload_dir', array(&$this,'custom_upload_dir') );
                    add_filter( 'upload_mimes', array(&$this,'custom_mimes') );
                    $uploaded = wp_handle_upload($file, $upload_overrides);
                    $uploaded['original_name'] = $file['original_name'];
                    array_push($this->uploaded_files, $uploaded);
                    remove_filter( 'upload_dir', array(&$this,'custom_upload_dir') );
                    remove_filter( 'upload_mimes', array(&$this,'custom_mimes') );
                }
            }
            
            return $this->uploaded_files;
        }
        
        public function custom_upload_dir( $dir ) {
            return array(
                'path'   => $this->upload_dir,
                'url'    => $this->upload_url,
                'subdir' => '/'
            ) + $dir;
        }
        
        public function custom_mimes( $mimes ) {  
            return $this->mimes;
        }
    }
}
?>