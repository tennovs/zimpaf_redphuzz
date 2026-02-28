<?php 
	// This script handles the booking requests made via ajax by book.php
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite('secure');

	// save form data
	if ($_POST['rezgoAction'] == 'giftCardPayment'){
		session_start();
		$data = explode('&', urldecode($_POST['formData']));
		for($i=0; $i < count($data); $i++){
			$key_value = explode('=', $data [$i]);
			$gc_array[$key_value [0]] = $key_value [1];
		}
		foreach ($gc_array as $k => $v){
            $_SESSION['gift-card'][$k] = $v;
        }
	}

	// return total amount due in correct currency format
	if ($_POST['rezgoAction'] == 'formatCurrency'){
		$company = $site->getCompanyDetails();
		$amount = $_POST['amount'];
		$result = $site->formatCurrency($amount, $company);
		
		echo $result;
	}

	$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
	$recaptcha_secret = REZGO_CAPTCHA_PRIV_KEY;
	$recaptcha_response = $_POST['recaptcha_response'];
	$recaptcha_threshold = 0.75;

	// Verify captcha on 2nd Step
	if ( $_POST['rezgoAction'] == 'addGiftCard' &&
		 $_SERVER['REQUEST_METHOD'] === 'POST' &&
		 isset($recaptcha_response)
		) {

		// Make and decode POST request:
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
		$recaptcha = json_decode($recaptcha);

		// Take action based on the score returned, or if a payment ID was sent
        // this is needed so the SCA validation can re-submit this request
		if ($recaptcha->score >= $recaptcha_threshold || $_POST['payment_id']) {

			if ($_POST['rezgoAction'] == 'addGiftCard') {

				// if honeypot field is filled
				if ( $_POST['rezgo_confirm_id'] ) {
					// This is a bot. Simulate purchase
					
					// randomly pick from array
					$random_card = FAKE_GIFT_CARDS[array_rand(FAKE_GIFT_CARDS)];

					$result->response = 1;
					$result->card = $random_card;
					$json = json_encode((array)$result); 
					echo '|||' . $json;
					return;
				} 
				else {
					//proceed as usual
					$result = $site->sendGiftOrder($_POST);
				}

				if ($result->status == 'Card created') {
					session_start();
					$result->card = $site->encode($result->card);

					$result->response = 1;
				}
				else {
                    
                    if($result->sca_required) {
    
                        $result->response = 8;
                        $result->message = '3DS verification is needed to continue';
                        $result->url = (string) $result->sca_url;
                        $result->post = (string) $result->sca_post;
                        $result->pass = (string) $result->sca_pass;
                        
                    } else {

                        // this booking failed, send a status code back to the requesting page
                        if($result->message == 'Availability Error' || $result->mesage == 'Fatal Error') {
                            $result->response = 2;
                        } elseif($result->message == 'Payment Declined' || $result->message == 'Invalid Card Checksum' || $result->message == 'Invalid Card Expiry') {
                            $result->response = 3;
                        } elseif($result->message == 'Account Error') {
                            // hard system error, no commit requests are allowed if there is no valid payment method
                            $result->response = 5;
                        } else {
                            $result->response = 4;
                        }
    
                    }
					               
				}

				$json = json_encode((array)$result); 
				echo '|||' . $json;
			}
		}
		else {
			// fail recaptcha 
			$result->response = 6;
			$json = json_encode((array)$result); 
			echo '|||' . $json;
		}
	}
	else if (!REZGO_CAPTCHA_PRIV_KEY) {

		if ($_POST['rezgoAction'] == 'addGiftCard') {

			// if honeypot field is filled
			if ( $_POST['rezgo_confirm_id'] ) {
				// This is a bot. Simulate purchase

				// randomly pick from array
				$random_card = FAKE_GIFT_CARDS[array_rand(FAKE_GIFT_CARDS)];

				$result->response = 1;
				$result->card = $random_card;

				$json = json_encode((array)$result); 
				echo '|||' . $json;
				return;
			} 
			else {
				//proceed as usual
				$result = $site->sendGiftOrder($_POST);
			}

			if ($result->status == 'Card created') {
				session_start();
				$result->card = $site->encode($result->card);

				$result->response = 1;
			}
			else {

				if($result->sca_required) {
    
					$result->response = 8;
					$result->message = '3DS verification is needed to continue';
					$result->url = (string) $result->sca_url;
					$result->post = (string) $result->sca_post;
					$result->pass = (string) $result->sca_pass;
                        
                } else {
                        
					// this booking failed, send a status code back to the requesting page
					if($result->message == 'Availability Error' || $result->mesage == 'Fatal Error') {
					    $result->response = 2;
					} elseif($result->message == 'Payment Declined' || $result->message == 'Invalid Card Checksum' || $result->message == 'Invalid Card Expiry') {
					    $result->response = 3;
					} elseif($result->message == 'Account Error') {
					    // hard system error, no commit requests are allowed if there is no valid payment method
					    $result->response = 5;
					} else {
					    $result->response = 4;
					}
    
                }
			}

			$json = json_encode((array)$result); 
			echo '|||' . $json;
		}
	}

	if ($_POST['rezgoAction'] == 'getGiftCard') {
		$result = $site->getGiftCard($_POST['gcNum']);

		if (array_key_exists('card', $result)) {
			$result->card->status = 1;

			$result->card->number = $site->cardFormat($result->card->number);
		} 
		else {
			$result->card->status = 0;
		}

		echo '|||' . json_encode($result->card);
	}
?>