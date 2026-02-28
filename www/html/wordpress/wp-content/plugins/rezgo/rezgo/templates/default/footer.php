<?php
	// this is your footer template, you can either grab the Rezgo footer from XML or create your own here
	$site = new RezgoSite(
		sanitize_text_field($_REQUEST['sec'])
	);
	
	echo $site->getAnalytics();

	if (!$site->config('REZGO_HIDE_HEADERS')) {
		echo $site->getFooter();
	}
?>