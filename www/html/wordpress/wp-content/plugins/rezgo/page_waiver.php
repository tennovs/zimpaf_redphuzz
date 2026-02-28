<?php 
	// This is the waiver page
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();
	//$site->sendTo('/' . $site->requestStr('parent_url'));
	$domain = "https://".$site->getDomain();
	$site->sendTo($domain.'.rezgo.com/waiver/');
?>

<?php echo $site->getTemplate('frame_header'); ?>

<?php echo $site->getTemplate('waiver'); ?>

<?php echo $site->getTemplate('frame_footer'); ?>