<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php 
	$coming_soon_aweber = false;
	?>
<div class="pieregister-admin">
<div id="exportusers_tabs" class="hideBorder">
	<div class="settings">
    	<h2 class="headingwidth"><?php _e("Marketing",'pie-register') ?></h2>
        <?php 
			if(isset($_POST['notice']) && $_POST['notice'] ){
				echo '<div id="message" class="updated fade msg_belowheading"><p>' . esc_html($_POST['notice']) . '</p></div>';
			}elseif(isset($_POST['error']) && $_POST['error'] ){
				echo '<div id="error" class="error fade msg_belowheading"><p>' . esc_html($_POST['error']) . '</p></div>';
			}
			if(isset($_POST['warning']) && $_POST['warning'] ){
				echo '<div id="warning" class="warning fade msg_belowheading"><p>' . esc_html($_POST['warning']) . '</p></div>';
			}
		?>
				<?php do_action("piereg_email_services"); ?>
                <?php 
				if( $coming_soon_aweber ) { ?>
                    <div id="piereg_aweber"><div class="right_section image_wrapper"><img class="comming_soon_img" alt="Aweber Coming Soon" src="<?php echo esc_url(PIEREG_PLUGIN_URL.'assets/images/Coming-soon_aweber.png'); ?>" /></div></div>    
                <?php } ?>
	</div>
</div>
</div>