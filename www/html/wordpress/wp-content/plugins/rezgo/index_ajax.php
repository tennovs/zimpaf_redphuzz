<?php
	// WP AJAX NONCE SECURITY 
	check_ajax_referer('rezgo-nonce','security');

	// any new page must start with the page_header, it will include the correct files
	// so that the rezgo parser classes and functions will be available to your templates
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	if (isset($_REQUEST['parent_url'])) {
			$site->base = $site->requestStr('parent_url'); // no leading slash
	}

	// save the current search to a cookie so we can return to it
	if ($_REQUEST['search'] != 'restore') {
		$site->saveSearch();
	}

	// some code to handle the pagination
	if (!$_REQUEST['pg']) {
		$_REQUEST['pg'] = 1;
	}

	$start = (intval($_REQUEST['pg']) - 1) * REZGO_RESULTS_PER_PAGE;

	// we only want 11 responses, starting at our page number times item number
	$site->setTourLimit(REZGO_RESULTS_PER_PAGE + 1, $start);

	echo $site->getTemplate('index_ajax');
?>