<?php

if (php_sapi_name() === 'cli' || !isset($_SERVER['REQUEST_URI'])) {
    return;
}
// inject our function overrides
error_log("AUTH PREPEND EXECUTED");

// if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'bwapp'){
if (str_contains($_SERVER['REQUEST_URI'], 'bwapp')){
	error_log("BWAPP AUTH IS BYPASSED");
	if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
	if(!isset($_SESSION['login'])) {
		// session_start();
		session_regenerate_id(true);
		$token = sha1(uniqid(mt_rand(0,100000)));

		$_SESSION["login"] = "admin";
		$_SESSION["admin"] = "1";
		$_SESSION["token"] = $token;
		$_SESSION["amount"] = 1000;

		if(!isset($_COOKIE['security_level'])) {
			$_COOKIE['security_level'] = "0";
			setcookie("security_level", "0", time()+60*60*24*365, "/", "", false, false);
		}
	}
// }else if(!isset($_SESSION['user']) && $_SERVER['HTTP_HOST'] === 'xvwa') {
}else if(!isset($_SESSION['user']) && str_contains($_SERVER['REQUEST_URI'], 'bwapp')) {
	$_SESSION['user'] = "admin";
}

