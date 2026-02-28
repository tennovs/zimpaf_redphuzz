<?php 
	// This script handles the setting of templates in plugin settings
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	if($_POST['event']) {
		if ($_POST['event']==='rzg_use_cus_tmp') rezgo_use_cus_tmp();
		if ($_POST['event']==='rzg_use_def_tmp') rezgo_use_def_tmp();
		if ($_POST['event']==='rzg_set_tmp') rezgo_set_tmp();
		if ($_POST['event']==='rzg_get_tmp') rezgo_get_tmp();
	}
?>