<?php
/**
 * @var $total_post
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="ere-heading">
    <h2><?php esc_html_e('Agents', 'essential-real-estate') ?>
        <sub>(<?php echo ere_get_format_number($total_post); ?>)</sub></h2>
</div>