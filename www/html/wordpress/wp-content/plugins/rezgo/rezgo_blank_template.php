<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)	]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title(''); ?></title>
	<?php
	rezgo_plugin_scripts_and_styles();
	wp_print_scripts();
	wp_print_styles();
	?>
</head>
<body>
<?php while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
</body>
</html>