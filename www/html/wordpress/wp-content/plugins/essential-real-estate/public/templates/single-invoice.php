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
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'ere-invoice', ERE_PLUGIN_URL . 'public/assets/js/invoice/ere-invoice' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'single-invoice');

do_action('ere_single_invoice_before_main_content');
if (have_posts()):
    while (have_posts()): the_post(); ?>
        <?php ere_get_template_part('content', 'single-invoice'); ?>
    <?php endwhile;
endif;
do_action('ere_single_invoice_after_main_content');
/**
 * ere_after_main_content hook.
 *
 * @hooked ere_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */

do_action( 'ere_after_main_content' );
/**
 * ere_sidebar_invoice hook.
 *
 * @hooked ere_sidebar_invoice - 10
 */
do_action('ere_sidebar_invoice');
get_footer('ere');
