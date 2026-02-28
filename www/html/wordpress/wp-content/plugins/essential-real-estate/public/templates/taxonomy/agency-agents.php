<?php
/**
 * @var $agency_term_slug
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$enable_agents_of_agency = ere_get_option( 'enable_agents_of_agency', '1' );
if(isset( $enable_agents_of_agency ) && $enable_agents_of_agency): ?>
    <?php
    $agents_of_agency_layout_style = ere_get_option('agents_of_agency_layout_style', 'agent-slider');
    $agents_of_agency_item_amount = ere_get_option('agents_of_agency_item_amount', 12);
    $agents_of_agency_image_size = ere_get_option('agents_of_agency_image_size', '270x340');
    $agents_of_agency_show_paging = ere_get_option('agents_of_agency_show_paging', array());

    $agents_of_agency_column_lg = ere_get_option('agents_of_agency_column_lg', '4');
    $agents_of_agency_column_md = ere_get_option('agents_of_agency_column_md', '3');
    $agents_of_agency_column_sm = ere_get_option('agents_of_agency_column_sm', '2');
    $agents_of_agency_column_xs = ere_get_option('agents_of_agency_column_xs', '2');
    $agents_of_agency_column_mb = ere_get_option('agents_of_agency_column_mb', '1');

    if (!is_array($agents_of_agency_show_paging)) {
        $agents_of_agency_show_paging = array();
    }
    if (in_array("show_paging_other_agent", $agents_of_agency_show_paging)) {
        $agent_show_paging = 'true';
    } else {
        $agent_show_paging = '';
        $agents_of_agency_item_amount = -1;
    }

    if ($agents_of_agency_layout_style == 'agent-slider') {
        $agent_show_paging = '';
    }
    ?>
    <div id="agency-agent" class="agency-agent">
        <div class="ere-heading">
            <h2><?php esc_html_e( 'Our Agents', 'essential-real-estate'); ?></h2>
            <p><?php esc_html_e( 'We Have Professional Agents', 'essential-real-estate'); ?></p>
        </div>
        <?php
        $agent_shortcode = '[ere_agent agency="' . $agency_term_slug . '" layout_style="' . $agents_of_agency_layout_style . '"
                        item_amount="' . $agents_of_agency_item_amount . '" items="' . $agents_of_agency_column_lg . '"
                        items_md="' . $agents_of_agency_column_md . '" items_sm="' . $agents_of_agency_column_sm . '"
                        items_xs="' . $agents_of_agency_column_xs . '" items_mb="' . $agents_of_agency_column_mb . '"
                        image_size="' . $agents_of_agency_image_size . '" show_paging="' . $agent_show_paging . '"]';
        echo do_shortcode($agent_shortcode);
        ?>
    </div>
<?php endif; ?>