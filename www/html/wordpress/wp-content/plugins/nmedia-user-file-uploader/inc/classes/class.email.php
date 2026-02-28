<?php
/**
 * Email Manager Class
 **/

if( ! defined("ABSPATH" ) )
        die("Not Allewed");
        
class WPFM_Email {
    
    var $user;
    var $context;
    var $file_objects;
    
    function __construct( $file_objects, $context='file-saved' ) {
        
        $this -> user           = wpfm_get_current_user();
        $this -> context 		= $context;
        $this -> file_objects	= $file_objects;
        
        $this -> to				= '';
        $this -> subject		= '';
        $this -> message		= '';
        
    }
    
    function send() {
        
        if( $this->to() == '' ) return '';
        
        if( ! wp_mail( $this->to(), $this->subject(), $this->get_message_with_template(), $this->get_mail_header()) ) {
            
            // echo 'Error while sending email';
        }
    }

	function to() {
		
		return apply_filters('wpfm_email_to', $this->to, $this);
	}
    
    function subject() {
        
        $subject = '';
        
        switch( $this->context ) {
        	
        	case 'file-saved':
        		
        		$subject = $this->get_subject_with_var($this->subject);
        		break;
        		
        	case 'send-file':
        		$subject = $this->get_subject_with_var($this->subject);
        		break;
        		
        }
        
        
        return apply_filters( "wpfm_email_subject", sprintf(__("%s", "wpfm"), $subject, $this) );
    }
    
    function get_subject_with_var($subject){
    
    	$email_vars = $this->get_email_vars();
		foreach( $email_vars as $var => $value ) {
		    
		    $subject = str_replace( $var, $value, $subject);
		    
		}
		
		
		return $subject;
    }
    
    function message() {
        
        $email_message = '';
        
        switch( $this->context ) {
        	
        	case 'file-saved':
        		$email_message = $this->message;
        		break;
        		
        	case 'send-file':
        		if( $this->message != '' ) {
        			
        			$email_message = $this->message;
        			$email_message .= "<br><hr>";
        			$email_message .= wpfm_get_option('_email_message_sendfile');
        			$email_message = nl2br($email_message);
        		}
        		
        		break;
        		
        }

       
        return apply_filters( 'wpfm_email_message', $email_message, $this->user);  
    }
    
    /**
	 * this function returns the proper header
	 * with wp site title and admin email
	 */
	
	function get_mail_header(){
	 	
	 	$site_title = get_bloginfo('name');
	 	
	 	$from_email	 = wpfm_get_option('_from_email');
		$from_email	 = empty($from_email) ? get_bloginfo('admin_email') : $from_email;
		
		$headers[] = "From: {$site_title} <{$from_email}>";
		$headers[] = "Content-Type: text/html";
		$headers[] = "MIME-Version: 1.0\r\n";
		
		return apply_filters('wpfm_email_header', $headers);
	}
	 
	function get_message_with_template( ){
		
		ob_start();
		
		$message = $this->message();
		
							
		$email_template = "email/template.email.php";
		wpfm_load_templates( $email_template);
		
		$email_string = ob_get_clean();
		
		
		// File Download Links
		$file_download_links = $this->get_file_download_url();
		
		
		// Adding message to template
		$email_string = str_replace("%WPFM_EMAIL_CONTENT%", $message, $email_string);
		$email_string = str_replace("%FILES_DOWNLOAD_URLS%", $file_download_links, $email_string);
		
		$email_vars = $this->get_email_vars();
		foreach( $email_vars as $var => $value ) {
		    
		    $email_string = str_replace( $var, $value, $email_string);
		    
		}
		
		
		return apply_filters('wpfm_email_html', $email_string, $message);
	}
	 
	// This will return email vars
	function get_email_vars() {
	     
	     $email_vars = array(
	     					"%USER_EMAIL%"  => $this->user->user_email,
	                        "%USER_NAME%"   => $this->user->user_login,
	                        "%FIRST_NAME%"  => $this->user->first_name,
	                        "%LAST_NAME%"   => $this->user->last_name,
	                        "%SITE_URL%"    => get_bloginfo('url'),
	                        "%SITE_NAME%"   => get_bloginfo('name'),
	                        "%ADMIN_EMAIL%" => get_bloginfo('admin_email'),
	                        );
	                        
	     return apply_filters('wpfm_email_vars', $email_vars, $this);
	}
	
	function get_file_download_url() {
		
		$download_urls = '<p><dt>';
		if( is_array($this->file_objects) ) {
			
			foreach($this->file_objects as $file) {
				
				$file_obj = $file['file_obj'];
				
				$download_urls .= '<dl><a href="'.esc_url($file_obj->download_url).'">'.$file_obj->title.'</a></dl>';
			}
		} else {
			
			$file = new WPFM_File($this->file_objects);
				
			$download_urls .= '<a href="'.esc_url($file->download_url).'">'.$file->title.'</a><br>';
		}
		
		$download_urls .= '</dt></p>';
		
		// var_dump($download_urls); exit;
		
		return apply_filters('wpfm_file_url_in_email', $download_urls, $this);
	}
}