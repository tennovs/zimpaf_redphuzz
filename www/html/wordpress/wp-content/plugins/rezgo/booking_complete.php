<?php 
	// This is the booking receipt page
	require('rezgo/include/page_header.php');

	// start a new instance of RezgoSite
	$site = new RezgoSite();

	if (isset($_REQUEST['parent_url'])) {
		$site->base = '/' . $site->requestStr('parent_url');
	}

	// grab and decode the trans_num if it was set
	$trans_num = $site->decode(sanitize_text_field($_REQUEST['trans_num']));

	// send the user home if they shoulden't be here
	if (!$trans_num) {
		$site->sendTo($site->base."/booking-not-found");
	}

	// start a session so we can grab the analytics code
	session_start();

	// empty the cart
	$site->clearCart();

	$site->setMetaTags('<meta name="robots" content="noindex, nofollow">');
?>

<?php echo $site->getTemplate('frame_header'); ?>

<?php if (strlen($trans_num) == 16): ?>
	<?php echo $site->getTemplate('booking_order'); ?>
<?php else: ?>
	<?php echo $site->getTemplate('booking_complete'); ?>
<?php endif; ?>

<?php echo $site->getTemplate('frame_footer'); ?>