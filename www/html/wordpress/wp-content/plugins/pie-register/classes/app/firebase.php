<?php
 
/**
 * @author PIE REGISTER
 * @link pieregister.com
 */
if ( ! defined( 'ABSPATH' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;   
}

/*
    * Get Pie Register Dir Name
*/

$piereg_dir_path = dirname(__FILE__);
//define('PIEREG_DIR_NAME',$piereg_dir_path);

/*
    * Include PR Files
*/

class Pie_App_Firebase {
 
    // sending push message to single user by firebase reg id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
 
    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
         
        require_once(PIEREG_DIR_NAME.'/classes/app/config.php');
 
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
 
        $headers = array(
            'Content-Type' =>  'application/json',
            'Authorization' => 'key=' . PIE_APP_FIREBASE_API_KEY,
        );
        
 
        // Execute post
        $result = wp_remote_post($url, array(
            'method' => 'POST',
            'headers' => $headers,
            'body' => json_encode($fields))
        );

        if ($result === FALSE) {
            die('Curl failed');
        }
 
        
 
        return $result;
    }
}
?>