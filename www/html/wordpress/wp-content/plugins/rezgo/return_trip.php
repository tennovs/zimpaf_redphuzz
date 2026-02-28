<?php 
	// This is for return trips
	require('rezgo/include/page_header.php');
	// start a new instance of RezgoSite
	$site = $_REQUEST['sec'] ? new RezgoSite('secure') : new RezgoSite();

	if (isset($_REQUEST['parent_url'])) {
		$site->base = '/' . $site->requestStr('parent_url');
	}
	
	// Page title
	$site->setPageTitle($_REQUEST['title'] ? $_REQUEST['title'] : 'Return');
?>

<?php echo $site->getTemplate('frame_header');?>

<?php echo $site->getTemplate('return_trip');?>

<?php echo $site->getTemplate('frame_footer');?>