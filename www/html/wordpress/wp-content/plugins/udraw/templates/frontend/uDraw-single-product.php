<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	do_action('woocommerce_before_main_content');


?>

<div class="udraw-product-wrapper">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php
            woocommerce_get_template_part( 'content', 'single-product' );
        ?>

	<?php endwhile; // end of the loop. ?>

</div>

<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
?>

<script>
	jQuery(document).ready(function($) {
		setTimeout(function() {
			$('#primary, #main').css("width", "100%");
		}, 200);
	});
</script>

<?php get_footer('shop'); ?>