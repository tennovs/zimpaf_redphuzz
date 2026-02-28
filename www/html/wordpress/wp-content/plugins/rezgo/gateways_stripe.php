<?php
    
    // this script handles generating payment intent IDs based on the stripe secret key
    
    require('rezgo/include/page_header.php');
    
    $site = new RezgoSite();
    $company = $site->getCompanyDetails();

    $stripe_amount = round($_REQUEST['amount'], 2) * 100;

    if ($_REQUEST['rezgoAction'] == 'stripe_create') {
    
        $res = $site->getPublicPayment($_REQUEST['amount'], ['stripe_action' => 'create']);
    
        echo json_encode($res);
    }
    
    // update if gift card is applied/removed
    if ($_REQUEST['rezgoAction'] == 'stripe_update_total') {
    
        $payment_id = $_REQUEST['payment_id'] ?? '';
    
        $res = $site->getPublicPayment($_REQUEST['amount'], ['stripe_action' => 'update', 'payment_id' => $payment_id]);
        
        echo json_encode($res);
    }
    