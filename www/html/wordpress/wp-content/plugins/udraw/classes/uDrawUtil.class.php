<?php

if (!class_exists('uDrawUtil')) {
    
    class uDrawUtil extends uDrawAjaxBase {
        function __construct() {
        }
        
        function init_actions() {
            add_action( 'wp_ajax_udraw_get_customers', array(&$this, 'get_customers') );
        }
        
        public function get_customers ($page = 1, $per_page = 500) {
            if (isset($_REQUEST['page'])) {
                $page = $_REQUEST['page'];
            }
            if (isset($_REQUEST['per_page'])) {
                $per_page = $_REQUEST['per_page'];
            }
            $return_array = array();
            $get_next_page = true;
            $get_users_args = array(
                'role__in'  => [ 'customer', 'subscriber'],
                'number'    => $per_page,
                'paged'     => $page
            );
            $customers = get_users( $get_users_args );
            foreach($customers as $customer) {
                $return_obj = array(
                    'ID' => $customer->ID,
                    'display_name' => $customer->display_name,
                    'email' => $customer->user_email
                );
                array_push($return_array, $return_obj);  
            }
            if (count($return_array) < $per_page) {
                $get_next_page = false;
            }
            $return = array(
                'customers' => $return_array,
                'get_next_page' => $get_next_page
            );
            $this->sendResponse($return);
        }
        
        function get_web_contents($url, $postData="", $headers=array()) {
            $timeout = ini_get( 'max_execution_time' );
			$args = array(
				'body' => $postData,
                'timeout' => $timeout,
                'headers' => $headers
            );

            if ($postData !== "") {
                return wp_remote_retrieve_body( wp_remote_post( $url, $args ) );
            }
            return wp_remote_retrieve_body( wp_remote_get( $url , $args ) );
        }
        
        function is_dir_empty($dir) {
            if (!is_readable($dir)) return NULL; 
            $handle = opendir($dir);
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    return FALSE;
                }
            }
            return TRUE;
        }
        
        function download_file($url, $path) {
            //Remove any spaces that may be present
            $url = preg_replace('/\s/', '%20', $url);
            $timeout = ini_get( 'max_execution_time' );
            $args = array (
                'filename' => $path,
                'stream' => true,
                'timeout' => $timeout
            );

            $response = wp_remote_get( $url, $args );
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                return true;
            } else {
                return false;
            }
        }
        
        function startsWith($haystack, $needle) {
            // search backwards starting from haystack length characters from the end
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
        }
        
        function download_file_with_pointer($url, $fp) {
            if (function_exists('curl_version')) {
                $file = fopen('php://temp', 'w+');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FILE, $file);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSLVERSION, 6);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                
                if(curl_exec($ch) === false) {
                    error_log('Curl error: ' . curl_error($ch));
                }
                
                //curl_exec($ch);
                curl_close($ch);

                rewind($file);
            } else {
                $file = fopen ($url, "rb");
            }
            
            if ($file) {
                while(!feof($file)) {
                    fwrite($fp, fread($file, 1024 * 8 ), 1024 * 8 );
                }
                
                fclose($file);
            }

            return $fp;
        }
        
        function empty_target_folder ($folder_dir) {
            $files = glob($folder_dir.'/*'); // get all file names
            foreach($files as $file){ // iterate files
                if(is_file($file))
                error_log('FILE WAS DELETED - EMPTY TARGET FOLDER');
                error_log(print_r($file, true));
                    unlink($file); // delete file
            }
        }
        
        function create_zip_file ($data_array = '', $destination = '', $overwrite = false) {
            //$data_array will contain the array of objects containing the url and intented name of the file to be zipped
            if(file_exists($destination) && !$overwrite) { return false; }
            //Create array to hold pdf files in
            $file_array = array();
            for ($i = 0; $i < count($data_array); $i++) {
                //if current entry in data array has pdf entry and the pdf exists, push into file array
                $data = (object)$data_array[$i];
                if (isset($data->directory) && file_exists($data->directory)) {
                    array_push($file_array, $data);
                }
            }
            if(count($file_array) > 0) {
                //create the archive
                touch($destination);
                $zip = new ZipArchive();
                if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach($file_array as $file) {
                    $zip->addFile($file->directory,$file->name);
                }
                //close the zip -- done!
                $zip->close();
                
                //check to make sure the file exists
                return file_exists($destination);
            } else {
                return false;
            }
        }
        
        private function fsock_get_web_response($protocol, $host, $port, $request) {
            $response = "";
            
            // create and configure the client socket
            $fp = @fsockopen($protocol . $hostname, $port, $errno, $errstr, 30);
            if ($fp) {
                // send request headers
                fwrite($fp, "GET ". $request . " HTTP/1.1\r\n");
                fwrite($fp, "Host: " . $hostname . "\r\n");
                //fwrite($fp, $additional_headers); // Accept, User-Agent, Referer, etc.
                fwrite($fp, "Connection: close\r\n");
                
                // read response
                while (!feof($fp)) {
                    $response .= fgets($fp, 128);
                }
                
                // close the socket
                fclose($fp);
            }
            
            return $response;
        }
        
        private function fsock_post_web_response($protocol, $host, $port, $request, $data) {
            $response = "";
            $fp = @fsockopen($protocol . $host, $port, $errno, $errstr, 30);
            if ($fp) {
                $msg  = 'POST ' . $request .' HTTP/1.1' . "\r\n";
                $msg .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
                $msg .= 'Content-Length: ' . strlen($data) . "\r\n";
                $msg .= 'Host: ' . $host . "\r\n";
                $msg .= 'Connection: close' . "\r\n\r\n";
                $msg .= $data;
                if ( fwrite($fp, $msg) ) {
                    while ( !feof($fp) ) {
                        $response .= fgets($fp, 1024);
                    }
                }
                fclose($fp);

            } else {
                $response = false;
            }
            
            return $response;
        }
        
        private function fopen_post_request($url, $postData="") {
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postData
                )
            );
            $context  = stream_context_create($opts);
            $response = file_get_contents($url, false, $context);
            return $response;
        }
        
        private function curl_get_response($url, $postData="") {
            try {
                $ch = curl_init();
                if (FALSE === $ch)
                    throw new Exception('failed to initialize');
                
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_HEADER, 0);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                if (strlen($postData) > 0) {
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                }
                
                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                if (FALSE === $response || '' === $response)
                    throw new Exception(curl_error($ch), curl_errno($ch));
                
                curl_close($ch);
                return $response;
            }
            catch (Exception $e) {
                error_log(sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(), $e->getMessage()),
                    E_USER_ERROR);
                return false;
                
            }
        }
    }
   
}

?>