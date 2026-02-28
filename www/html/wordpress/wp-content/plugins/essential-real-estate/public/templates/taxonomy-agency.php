<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 07/02/2017
 * Time: 2:37 CH
 */
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
do_action( 'ere_taxonomy_agency_before_main_content' );
?>
<div class="ere-agency-single-wrap">
    <div class="agency-single">
        <?php
        /**
         * ere_taxonomy_agency_before_summary hook.
         */
        do_action( 'ere_taxonomy_agency_before_summary' );
        ?>
        <?php
        /**
         * ere_taxonomy_agency_summary hook.
         *
         * @hooked taxonomy_agency_detail - 10
         */
        do_action( 'ere_taxonomy_agency_summary' ); ?>
        <?php
        /**
         * ere_taxonomy_agency_after_summary hook.
         */
        do_action( 'ere_taxonomy_agency_after_summary' );
        ?>

    </div>
</div>
<?php
do_action( 'ere_taxonomy_agency_after_main_content' );
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