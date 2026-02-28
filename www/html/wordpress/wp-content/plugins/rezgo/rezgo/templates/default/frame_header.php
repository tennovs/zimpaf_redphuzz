<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Cache-control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="format-detection" content="telephone=no" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php 
		$http = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
		$host = $_SERVER['HTTP_HOST'];
		$path = ($_REQUEST['mode'] != 'index') ? str_replace('page_', '', $_REQUEST['mode']).'/' : '';
		$tags = ($_REQUEST['tags']) ? $_REQUEST['tags'].'/' : '';
		$slug = ($_REQUEST['wp_slug']) ? $_REQUEST['wp_slug'].'/' : '';
		
		// build canonical url
		$canonical = $http.$host.'/'.$slug;
		if ($path == 'details/') {
			$canonical .= $path.$_REQUEST['com'].'/'.$_REQUEST['seo_name'].'/'.$tags;
		} else {
			$canonical .= $path.$tags;
		}
  ?>
	<link rel="canonical" href="<?php echo $canonical; ?>" />
	<title><?php echo sanitize_text_field($_REQUEST['page_title']); ?></title>
	<style>body { overflow:hidden; }</style>
	

	<?php
	rezgo_plugin_scripts_and_styles();
	wp_print_scripts();
	wp_print_styles();
	?>

	<?php if ($site->exists($site->getStyles())) { ?>
		<style><?php echo $site->getStyles(); ?></style>
	<?php } ?>

	<base target="_<?php echo REZGO_FRAME_TARGET; ?>">
</head>

<body>
