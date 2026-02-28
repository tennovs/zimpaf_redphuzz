<?php 
	// This page is the calendar display, it is fetched via AJAX to display the calendar

	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	echo $site->getTemplate('frame_header');

	echo $site->getTemplate('tour_calendar');

	echo $site->getTemplate('frame_footer');
?>

<?php // echo $site->getTemplate('calendar'); ?>  