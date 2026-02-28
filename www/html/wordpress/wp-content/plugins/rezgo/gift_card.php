<?php 
	// This is the gift card page
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite('secure');

	if (isset($_REQUEST['parent_url'])) {
			$site->base = '/' . $site->requestStr('parent_url');
	}
?>

<?php echo $site->getTemplate('frame_header');

	if ($_REQUEST['step'] == '1') {
		echo $site->getTemplate('gift_card');
	} else if ($_REQUEST['step'] == '2') {
		echo $site->getTemplate('gift_card_payment');
	}

echo $site->getTemplate('frame_footer'); ?>	