<?php 
/*
 * this file rendering admin options for the plugin
* options are defined in inc/arrays.php
*/
$wpfm_options = wpfm_array_get_admin_settigns();

// wpfm_pa($wpfm_options);

// for ajax calls
wp_nonce_field('wpfm_securing_ajax','wpfm_ajax_nonce');

?>

<style>
	
	.wpfm-help{
		font-size: 11px;
	}
	.the-button{
		float: right;
		margin-top: 10px;
		}
	.nm-saving-settings{
	float: left;
	margin-right: 5px;
	display: block;
	margin-top: 5px;
}
</style>

<h2>
	<?php printf(__("%s", 'wpfm'), 'WP Frontend File Manager');?>
</h2>
<div id="filemanager-tabs" class="wrap">
	<ul class='etabs'>
		<?php 
		foreach($wpfm_options as $id => $option){?>

		<li class='tab'>
			<a href="#<?php echo $id?>">
			<?php echo $option['name']?>
			</a>
		</li>

		<?php }?>
	</ul>

	<form id="nm-admin-form-<?php echo $id?>" class="wpfm-settings-form-filemanager">
	<input type="hidden" name="action" value="wpfm_save_settings" />
	
	<?php foreach($wpfm_options as $id => $options){ ?>

	<div id="<?php echo $id?>">
		<table class="form-table">
		<?php foreach($options['meat'] as $key => $legend ){  ?>
				<tr class="wpfm-legend"> 
				<th class="wpfm-legend-th">
				<table>
				<tr>
					<th>
						<?php echo $key; ?>
					</th>
				</tr>
    			<?php
    			foreach($legend as $data){
					$label 		 	= (isset($data['label']) ? $data['label'] : '');
					$help 			= (isset($data['help']) ? $data['help'] : '');
					$type 			= (isset($data['type']) ? $data['type'] : '');
					
					if($type == 'file'){
	
						echo '<tr><td>';
						$file = WPFM_PATH .'/templates/admin/'.$data['id'];
						if(file_exists($file))
							include $file;
						else
							echo 'file not exists '.$file;
						
						echo '</td></tr>';
					}else {
					?>
					<tr valign="top">
						<th scope="row" width="25%"><?php printf(__('%s', 'wpfm'), $label);?>
							
						</th>
						<td width="30%">
							<?php wpfm_render_settings_input($data); ?>
						</td>
						<td width="40%">
							<span class="wpfm-help"><?php echo $help?></span>
						</td>
					</tr>
				
					<?php
					} 
			 	} ?>
    			</table>
			 	</th> 
			 	</tr> 
			 	
			
    <?php  } ?>
			
		</table>
	</div>
	
	<?php } ?>
	<p class="the-button"><button class="button button-primary"><?php _e('Save settings', 'wpfm')?></button><span class="nm-saving-settings"></span></p>
	<span class="pull-right loading-image"><strong><img src="<?php echo admin_url("images/spinner.gif"); ?>"></strong></span>
	</form>
	
	<div id="migrate-result"></div>
</div>