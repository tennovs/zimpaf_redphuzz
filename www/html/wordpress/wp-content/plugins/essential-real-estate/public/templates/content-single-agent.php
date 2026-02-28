<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 17/01/2017
 * Time: 10:41 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'single-agent');
global $post;
?>
<div id="agent-<?php the_ID(); ?>" <?php post_class('ere-agent-single-wrap ere-agent-single'); ?>>
	<?php
	/**
	 * ere_single_agent_before_summary hook.
	 */
	do_action( 'ere_single_agent_before_summary' );
	?>
	<?php
	/**
	 * ere_single_agent_summary hook.
	 *
	 * @hooked single_agent_info - 5
	 * @hooked comments_template - 10
	 * @hooked single_agent_reviews - 10
	 * @hooked single_agent_property - 20
	 * @hooked single_agent_other - 30
	 */
	do_action( 'ere_single_agent_summary' ); ?>
	<?php
	/**
	 * ere_single_agent_after_summary hook.
	 */
	do_action( 'ere_single_agent_after_summary' );
	?>
</div>