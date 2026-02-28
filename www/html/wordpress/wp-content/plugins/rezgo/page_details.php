<?php 
	// any new page must start with the page_header, it will include the correct files
	// so that the rezgo parser classes and functions will be available to your templates
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	if (isset($_REQUEST['parent_url'])) {
			$site->base = '/' . $site->requestStr('parent_url');
	}

	echo $site->getTemplate('frame_header');

	if (isset($_REQUEST['view']) && $_REQUEST['view']==='calendar') {
		echo $site->getTemplate('tour_calendar');
	} else {
		echo $site->getTemplate('tour_details');
	}
	
	echo $site->getTemplate('frame_footer');
	
?>