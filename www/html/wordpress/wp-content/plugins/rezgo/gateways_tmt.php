<?php
    
    // this script handles generating payment keys from the public API path for TMT
    
    require('rezgo/include/page_header.php');
    $site = new RezgoSite();
    $company = $site->getCompanyDetails();
    
    $res = $site->getPublicPayment($_REQUEST['amount']);
    
    echo json_encode($res);
    