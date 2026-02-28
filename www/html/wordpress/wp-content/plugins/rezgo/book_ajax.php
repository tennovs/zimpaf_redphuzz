<?php 
	// This script handles the booking requests made via ajax by book.php
	
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite('secure');

	if ($_POST['rezgoAction'] == 'get_paypal_token') {
		
		// send a partial commit (a=get_paypal_token) to get a paypal token for the modal window
		// include the return url (this url), so the paypal API can use it in the modal window
		if($_POST['mode'] == 'mobile') {
			$result = $site->sendBooking(null, 'a=get_paypal_token&paypal_return_url=https://'.$_SERVER['HTTP_HOST'].REZGO_DIR.'/paypal');
		} else {
			$result = $site->sendBookingOrder(null, '<additional>get_paypal_token</additional><paypal_return_url>https://'.$_SERVER['HTTP_HOST'].REZGO_DIR.'/paypal</paypal_return_url>');
		}
		
		$response = ($site->exists($result->paypal_token)) ? $result->paypal_token : 0;

	} elseif($_POST['rezgoAction'] == 'reset_cart_status') {

		unset($_SESSION['cart_status']);

	} elseif($_POST['rezgoAction'] == 'update_promo') {

		unset($_SESSION['promo']);
		$result = $site->updatePromo($_POST['promo']);

	} elseif($_POST['rezgoAction'] == 'update_lead_passenger') {

		$result = $site->saveLeadPassenger();

	} elseif($_POST['rezgoAction'] == 'add_item') {

		$result = $site->addCart();
		$response = json_encode($result);

	} elseif($_POST['rezgoAction'] == 'edit_pax') {

		$result = $site->editPax();
		$response = json_encode($result);

	} elseif($_POST['rezgoAction'] == 'remove_item') {

		$index = $_POST['index'];
		$item_id = $_POST['item_id'];
		$date = $_POST['date'];

		if (!empty( $item_id && $date )){ 
			$site->removeCart($index, $item_id, $date);
		}

	} elseif($_POST['rezgoAction'] == 'book_step_one') {

		$result = $site->updateCart();
		
	} elseif($_POST['rezgoAction'] == 'update_debug') {

		$result = $site->updateDebug();

	} elseif($_POST['rezgoAction'] == 'commit_debug') {

		$result = $site->commitDebug();

	} elseif($_POST['rezgoAction'] == 'book') {
    
        $result = $site->sendBookingOrder();
        
        if ( $result->status == 1 ) {

			// start a session so we can save the analytics code
			session_start();	
   
			$response = [
			    'status' => 1,
                'message' => 'Booking Complete',
			    'txid' => $site->encode($result->trans_num)
            ];

			// Set a session variable for the analytics to carry to the receipt's first view
			$_SESSION['REZGO_CONVERSION_ANALYTICS'] = $result->analytics_convert;

			// Add a blank script tag so that this session is detected on the receipt
			$_SESSION['REZGO_CONVERSION_ANALYTICS'] .= '<script></script>';
			
		} else {
        
            if($result->sca_required) {

                $response = [
                    'status' => 8,
                    'message' => '3DS verification is needed to continue',
                    'url' => (string) $result->sca_url,
                    'post' => (string) $result->sca_post,
                    'pass' => (string) $result->sca_pass
                ];
            
            } else {
    
                // this booking failed, send a status code back to the requesting page
                if($result->message == 'Availability Error') { //  || $result->message == 'Fatal Error'
                    $response = [
                        'status' => 2,
                        'message' => $result->message
                    ];
                } elseif($result->message == 'Payment Declined' || $result->message == 'Invalid Card Checksum' || $result->message == 'Invalid Card Expiry') {
                    $response = [
                        'status' => 3,
                        'message' => $result->message
                    ];
                } elseif($result->message == 'Account Error') {
                    // hard system error, no commit requests are allowed if there is no valid payment method
                    $response = [
                        'status' => 5,
                        'message' => $result->message
                    ];
                } elseif($result->message == 'Fatal Error' && $result->error == 'Expected total did not match actual total.') {
                    $response = [
                        'status' => 6,
                        'message' => $result->message
                    ];
                } else {
                    $response = [
                        'status' => 4
                    ];
                }
    
            }
            
		}

		$response = json_encode($response, JSON_PRETTY_PRINT);
    }

	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		// ajax response if we requested this page correctly
		echo $response;		
	} else {
		// if, for some reason, the ajax form submit failed, then we want to handle the user anyway
		die ('Something went wrong during booking. Your booking may have still been completed.');
	}
?>