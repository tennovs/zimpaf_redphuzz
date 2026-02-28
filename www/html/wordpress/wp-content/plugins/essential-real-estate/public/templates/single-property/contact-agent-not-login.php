<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<p class="ere-account-sign-in"><?php esc_html_e('Please login or register to view contact information for this agent/owner', 'essential-real-estate'); ?>
	<button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
			data-target="#ere_signin_modal">
		<?php esc_html_e('Login', 'essential-real-estate'); ?>
	</button>
</p>
