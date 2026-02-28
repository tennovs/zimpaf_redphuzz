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
wp_print_styles( ERE_PLUGIN_PREFIX . 'single-agent');
?>
<div class="ere-author-wrap ere-agent-single-wrap">
    <div class="ere-author ere-agent-single">
        <?php
        /**
         * ere_single_agent_before_summary hook.
         */
        do_action('ere_author_before_summary');
        ?>
        <?php
        /**
         * ere_author_summary hook.
         *
         * @hooked author_info - 5
         * @hooked author_property - 10
         */
        do_action('ere_author_summary'); ?>
        <?php
        /**
         * ere_author_after_summary hook.
         */
        do_action('ere_author_after_summary');
        ?>
    </div>
</div>
<?php
/**
 * ere_after_main_content hook.
 *
 * @hooked ere_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'ere_after_main_content' );
/**
 * ere_sidebar_agent hook.
 *
 * @hooked ere_sidebar_agent - 10
 */
do_action('ere_sidebar_agent');
get_footer('ere');