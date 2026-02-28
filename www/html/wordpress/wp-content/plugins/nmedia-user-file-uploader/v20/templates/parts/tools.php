<?php
/**
 * FrontEnd FileManager WP Tools Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
?>
<div class="wpfm-bc">
	<ol class="breadcrumb" id="wpfm-bc" style="margin: 0 0 25px 0 !important;">
		<li data-node_id="0" data-title="Home" class="ffmwp-left-bc"><?php _e( "Home", "wpfm"); ?></li>
	</ol>
</div>
<div class="ffmwp-pull-right">
	<div class="ffmwp-tools-form-inline" style="margin: 10px 0px 10px 10px;">
		<label><?php _e( "Sorted by", "wpfm"); ?></label>
		<select class="ffmwp-tools-form-control" id="wpfm_sorted_by">
			<option value="title"><?php _e( "Name","wpfm"); ?></option>
			<option value="file_type"><?php _e( "Type", "wpfm"); ?></option>
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