<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$warning 	= apply_filters('piereg_reset_password_warning',__("Enter the new password below.",'pie-register')); # newlyAddedHookFilter
$success	= "";
$errors 	= new WP_Error();

/*
 * Sanitizing post data
 */
$this->pie_post_array	= $this->piereg_sanitize_post_data_escape( ( (isset($_POST) && !empty($_POST))?$_POST : array() ) );		

if ( ( isset($_POST['piereg_get_password_nonce']) && wp_verify_nonce($_POST['piereg_get_password_nonce'], 'piereg_wp_get_password_nonce') ) &&  isset($this->pie_post_array['pass1']) && $this->pie_post_array['pass1'] != $this->pie_post_array['pass2'] ) {	
	$errors->add( 'password_reset_mismatch', 
				apply_filters("piereg_reset_password_error",__('The passwords do not match.', 'pie-register')) # newlyAddedHookFilter
			);
	
}

if( isset($_POST['piereg_get_password_nonce']) && wp_verify_nonce($_POST['piereg_get_password_nonce'], 'piereg_wp_get_password_nonce') )
{
	do_action( 'validate_password_reset', $errors, $user );
	if ( ( ! $errors->get_error_code() ) && isset( $this->pie_post_array['pass1'] ) && !empty( $this->pie_post_array['pass1'] ) ) {
		reset_password($user, $this->pie_post_array['pass1']);		
		$success = apply_filters('piereg_reset_password_success',__( 'The password has been reset.','pie-register' )); # newlyAddedHookFilter	
	}

}
?>

<div id="piereg_login">
	<?php if ($success != "") { ?>
    	<p class="piereg_message"> <?php echo esc_html($success)?> </p>
    <?php } else if (isset($errors->errors['password_reset_mismatch'][0]) && !empty($errors->errors['password_reset_mismatch'][0])  ) {  ?>
    	<p class="piereg_login_error">
    		<?php  print_r($errors->errors['password_reset_mismatch'][0]); ?>
    	</p>
    <?php } else { ?>
    	<p class="piereg_warning"> <?php echo esc_html($warning)?> </p>
    <?php } 
    $this_user = sanitize_user($_GET['login']);
    $the_key  = urlencode(sanitize_text_field($_GET['key']));
    $the_user = urlencode( $this_user ); 
    ?>
    <form name="resetpassform" id="piereg_resetpassform" action="<?php echo esc_url( site_url( 'wp-login.php?action=resetpass&key=' . $the_key . '&login=' . $the_user , 'login_post' ) ); ?>" method="post" autocomplete="off">
        <input type="hidden" id="user_login" value="<?php echo esc_attr( $this_user ); ?>" autocomplete="off" />    
        <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg_wp_get_password_nonce','piereg_get_password_nonce'); ?>
        <p>
            <label for="pass1"><?php _e('New password', 'pie-register'); ?></label>
            <br />
            <input type="password" name="pass1" id="pass1" class="input input_fields validate[required]" size="20" value="" autocomplete="off">
        </p>
        <p>
            <label for="pass2"><?php _e('Confirm new password', 'pie-register'); ?></label>
            <br />
            <input type="password" name="pass2" id="pass2" class="input input_fields validate[required,equals[pass1]]" size="20" value="" autocomplete="off">
        </p>
        <p class="submit">
        	<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Reset Password">
        </p>
    <?php  
		$form_links  	 = '<p id="nav"> <a href="'.wp_login_url().'">Log in</a> | <a href="'.site_url("/wp-login.php?action=register").'">Register</a> </p>';
		$form_links 	.= '<p id="backtoblog"><a title="Are you lost?" href="'.bloginfo("url").'">← Back to Pie Register</a></p>';  		
		$form_links      = apply_filters( 'pie_getpassword_form_links', $form_links );		
		echo wp_kses_post($form_links);
		?>
    </form>
</div>
