<?php
/**
 * FrontEnd FileManager WP TopBar Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
$count = wpfm_get_wp_files_count(get_current_user_id());
?>

<div class="ffmwp-top-menu">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9">
				<?php 
					$wpfm_menu = wpfm_get_top_menu();
				
					foreach ($wpfm_menu as $menu) { 
						?>
						<div class="wpfm-nav-item ffmwp-topbar-files-title"><a class="nav-link" href="<?php echo esc_url($menu['link']);?>">
							<span class="ffmwp-top-bar-file-icon dashicons <?php echo esc_attr($menu['icon']);?>"></span><?php echo $menu['label']; ?></a>
						</div>
					<?php }
				?>			
			</div>
			<div class="col-md-3">
				<form role="search">
					<input type="text" placeholder="<?php _e('Search Files', 'wpfm');?>" name="srch-term" id="search_files"/>
				</form>	
			</div>
		</div>
	</div>
</div>