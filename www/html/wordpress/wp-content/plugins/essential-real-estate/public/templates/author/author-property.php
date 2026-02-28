<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wp_query;
$current_author = $wp_query->get_queried_object();
$author_id=$current_author->ID;
$agent_id = 0;
$ere_property = new ERE_Property();
$total_property = $ere_property->get_total_properties_by_user($agent_id, $author_id);

$property_agent_shortcode = '[ere_property layout_style = "property-list"
    item_amount = "10"
    show_paging = "true"
    author_id = "' . $author_id . '"
    agent_id = "' . $agent_id . '"]';
?>
<?php if ($total_property > 0): ?>
	<div class="agent-properties">
		<div class="agent-properties-inner">
			<div class="ere-heading">
				<h2><?php esc_html_e('My properties', 'essential-real-estate'); ?><sub>(<?php echo ere_get_format_number($total_property); ?>)</sub></h2>
			</div>
			<?php echo do_shortcode($property_agent_shortcode); ?>
		</div>
	</div>
<?php endif; ?>