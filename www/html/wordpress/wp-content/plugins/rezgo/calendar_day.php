<?php
	// WP AJAX NONCE SECURITY
	check_ajax_referer('rezgo-nonce','security');

	// This page is the calendar display, it is fetched via AJAX to display the calendar
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	if (isset($_REQUEST['parent_url'])) {
		$site->base = $site->requestStr('parent_url'); // no leading slash
	}
?>

<?php echo $site->getTemplate('calendar_day'); ?>