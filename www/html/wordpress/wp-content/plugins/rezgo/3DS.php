<?php
    require('rezgo/include/page_header.php');
    
    echo '<!DOCTYPE html><body>';

    $stripped_request = array();
    foreach ($_REQUEST as $key => $value) {
        // only 3DS vars
        if (strpos($key, '||3DS')){
            $replace = array('||3DS');
            $key = str_replace($replace, '', $key);
            $stripped_request[$key] = $value;
        }
    }
    // remove extra WP request vars
    foreach ($stripped_request as $key => $value) {
        if ($key == 'pagename' || $key == 'mode'){
            unset($stripped_request[$key]);
        }
    }

    // $code = json_encode($_REQUEST);

    $stripped_code = json_encode($stripped_request);
    
    echo "<script>parent.parent.sca_callback('".$stripped_code."');</script>";
    
    echo '</body></html>';