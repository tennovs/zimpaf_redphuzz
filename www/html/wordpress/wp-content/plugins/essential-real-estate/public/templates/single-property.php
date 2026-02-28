<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header('ere');
/**
 * ere_before_main_content hook.
 *
 * @hooked ere_output_content_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action( 'ere_before_main_content' );
do_action('ere_single_property_before_main_content');
if (have_posts()):
    while (have_posts()): the_post(); ?>
        <?php ere_get_template_part('content', 'single-property'); ?>
    <?php endwhile;
endif;
do_action('ere_single_property_after_main_content');
/**
 * ere_after_main_content hook.
 *
 * @hooked ere_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'ere_after_main_content' );
/**
 * ere_sidebar_property hook.
 *
 * @hooked ere_sidebar_property - 10
 */

do_action('ere_sidebar_property');
get_footer('ere');
