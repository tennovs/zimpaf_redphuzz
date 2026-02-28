<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="pieregister-admin">
	<div class="hideBorder bulkemail_tabs">
		<div class="settings">

    		<h2 class="headingwidth"><?php _e("Bulk Email",'pie-register') ?></h2>
			
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
			
        	
			<?php if ( is_plugin_active('pie-register-bulkemail/pie-register-bulkemail.php') ) { ?>
				<div class="invite-tabs clearfix">
				<ul>
					<li <?php if(!isset($_GET['piereg_bulkemail_drafts']) && !isset($_GET['piereg_bulkemail_sent_items'])){ echo 'class="invite-active"'; } ?>><a href="admin.php?page=pie-bulkemail&piereg_bulkemail"><?php _e("Create Email","pie-register")?></a></li>
					<li <?php if(isset($_GET['piereg_bulkemail_drafts'])){ echo 'class="invite-active"'; } ?>><a href="admin.php?page=pie-bulkemail&piereg_bulkemail_drafts"><?php _e("Drafts","pie-register")?></a></li>
					<li <?php if(isset($_GET['piereg_bulkemail_sent_items'])){ echo 'class="invite-active"'; } ?>><a href="admin.php?page=pie-bulkemail&piereg_bulkemail_sent_items"><?php _e("Sent Items","pie-register")?></a></li>
				</ul>
				</div>
			<?php } ?>
				
			<?php do_action("piereg_show_bulk_email_settings"); ?>
			
		</div>
	</div>
</div>