<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="compare-listings" class="compare-listing">
	<div class="compare-listing-header">
		<h4 class="title"> <?php esc_html_e( 'Compare', 'essential-real-estate' ); ?></h4>
	</div>
	<?php do_action('ere_show_compare');  ?>
</div>