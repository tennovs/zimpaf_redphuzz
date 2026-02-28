<?php
/**
 * Template to render downloadable files
 * 
 * */
 
if( ! defined("ABSPATH") ) die("Not Allowed");

$pagination_limit = wpfm_get_option ( '_pagination_limit' );

?>
<div class="wpfm-wrapper">
	<div id="wpfm-main-wrapper">
		<div class="navbar navbar-default">
			<div class="container-fluid">
		        <form class="navbar-form navbar-right hidden-xs" role="search">
		        	<input type="text" class="form-control" placeholder="<?php _e('Search Files', 'wpfm');?>" name="srch-term" id="search_files">
		        </form>
				<ul class="nav navbar-nav navbar-left">
					<?php 
						$wpfm_menu = wpfm_get_download_top_menu();
						foreach ($wpfm_menu as $menu) { 
							
							?>
							<li class=""><a href="<?php echo esc_url($menu['link']);?>">
								<span class="glyphicon <?php echo esc_attr($menu['icon']);?>"></span> <?php echo $menu['label']; ?></a>
							</li>
							
						<?php }
					 ?>
				</ul>
			</div>
		</div>

		<div class="wpfm-body-area">
			<?php $is_disabled_breadcrumbs =  wpfm_get_option('_disable_breadcrumbs'); 
			if ($is_disabled_breadcrumbs != 'yes') { ?>
				
				<ol class="breadcrumb pull-left" id="wpfm-bc">
					<li id="home_bc"><?php _e( "Home", "wpfm"); ?></li>
				</ol>
			<?php } ?>

			<div class="toolbar pull-right">
				<div class="form-inline pull-right hidden-sm hidden-xs" style="margin-left:10px;" >
					
					<label><?php _e( "Sorted by", "wpfm"); ?></label>
					<select class="form-control" id="wpfm_sorted_by">
						<option value="title"><?php _e( "Name","wpfm"); ?></option>
						<option value="fiel_type"><?php _e( "Type", "wpfm"); ?></option>
					</select>
					<div class="radio">
						<label>
							<input type="radio" name="wpfm_sortorder" checked="" value="asc">
							<?php _e( "Ascending", "wpfm"); ?>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="wpfm_sortorder" value="desc">
							<?php _e( "Descending", "wpfm"); ?> &nbsp;
						</label>
					</div>
				</div>

			</div>
		</div>
		<div class="clearfix"></div>
			<!-- Breadcrumbs Area Ends -->


	    <!-- here rendered all euser files with jquery -->
	    <div id="wpfm-files-wrapper">
	    	
	    </div>
	</div>
</div>