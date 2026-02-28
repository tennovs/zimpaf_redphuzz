<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'single-property', ERE_PLUGIN_URL . 'public/assets/js/property/ere-single-property' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'single-property');
?>
<div id="property-<?php the_ID(); ?>" <?php post_class('ere-property-wrap single-property-area content-single-property'); ?>>
	<?php
	/**
	 * ere_single_property_before_summary hook.
	 */
	do_action( 'ere_single_property_before_summary' );
	?>
	<?php
	/**
	* ere_single_property_summary hook.
	*
	* @hooked single_property_header - 5
	* @hooked single_property_gallery - 10
	* @hooked single_property_description - 15
	* @hooked single_property_location - 20
	* @hooked single_property_features - 25
	* @hooked single_property_floors - 30
	* @hooked single_property_attachments - 35
	* @hooked single_property_map_directions - 40
	* @hooked single_property_nearby_places - 45
	* @hooked single_property_walk_score - 50
	* @hooked single_property_contact_agent - 55
	* @hooked single_property_footer - 90
	* @hooked comments_template - 95
	* @hooked single_property_rating - 95
	*/
	do_action( 'ere_single_property_summary' ); ?>
	<?php
	/**
	 * ere_single_property_after_summary hook.
	 *
	 * * @hooked comments_template - 90
	 */
	do_action( 'ere_single_property_after_summary' );
	?>
</div>