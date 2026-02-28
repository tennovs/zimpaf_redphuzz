<?php
$site = new RezgoSite(sanitize_text_field($_REQUEST['sec']));

if (!$site->config('REZGO_HIDE_HEADERS')) {
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('Content-Type: text/html; charset=utf-8');

	echo $site->getHeader();
}
?>

<meta charset="utf-8">
