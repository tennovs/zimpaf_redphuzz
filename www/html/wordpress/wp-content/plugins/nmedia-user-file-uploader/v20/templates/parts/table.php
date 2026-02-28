<?php
/**
 * FrontEnd FileManager WP Util Files Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
?>
<button id="ffmwp-table-view-dlt-btn"><?php _e('Delete', 'wpfm');?></button>

<div class="ffmwp_files_wrapper table" id="wpfm-files-wrapper">
  <div class="ffmwp_files_table"></div>
</div>

<script type="text/html" id="tmpl-ffmwp-files-table">
  <div class="ffmwp-table-view-wrapper">
    <table class="table ffmwp-table-view-responsive-xl">
      <thead>
		    <tr>
		      <th>&nbsp;</th>
		    	<th><?php _e('Thumbnail', 'wpfm');?></th>
		    	<th><?php _e('Title', 'wpfm');?></th>
			    <th><?php _e('Size', 'wpfm');?></th>	
			    <th><?php _e('Action', 'wpfm');?></th>
		    </tr>
		  </thead>
		  <tbody>
	      <# _.forEach( data, function ( file ) {
	    	var download_class = file.location == 'amazon' ? 'wpfm-amazon-download ffmwp-download' : 'ffmwp-download';
	      #>
		    <tr class="ffmwp-table-view-row {{file.title}} parent-0 mix {{file.id}}"
	    		id="node-{{file.id}}"
		        data-file_type="{{file.node_type}}"
		        data-pid="0"
		        data-title="{{file.title}}"
		        data-node_id="{{file.id}}"
		        style="display: table-row !important;">
		    	<td>
		    		<input type="checkbox" value="{{file.id}}" class="ffmwp-table-view-checked">
		    	</td>
		    	<td class="ffmwp-table-view-files">
		      		<img src="{{file.thumb_url}}" class="ffmwp-table-view-thumb-url">
		    	</td>
		    	<td class="file_title">{{file.title}}
		    	
		    	<# 
			    //search only
			    #>
			    <span class="ffmwp_desc_search">{{file.description}}</span>
		    	</td>
			    <td>{{file.size}}</td>
	    		<td>
			      <div class="ffmwp-table-icons-box">
	                <# if(file.node_type == 'dir'){ #>
	            	    {{{file.share_button_v20}}}
	                  <a href="#" class="ffmwp-file-icons-content ffmwp-eye wpfm-dir" data-node_id="{{file.id}}" data-title="{{file.title}}"><span class="dashicons dashicons-visibility"></span></a>
	            	    <a href="#" class="ffmwp-file-icons-content ffmwp-trash ffmwp-delete-file" data-id="{{file.id}}"><span class="dashicons dashicons-trash"></span></a>
	                <# }else{ #>
	            	    {{{file.share_button_v20}}}
	            	    <a href="#" data-ffmwp_modal-target="ffmwp-files-popup-{{file.id}}" class="ffmwp-file-icons-content ffmwp-eye"><span class="dashicons dashicons-visibility"></span></a>
	            	    <a href="#" class="ffmwp-file-icons-content ffmwp-trash ffmwp-delete-file" data-id="{{file.id}}"><span class="dashicons dashicons-trash"></span></a>
	            	    <a data-id="{{file.id}}" href="{{{file.download_url}}}" class="ffmwp-file-icons-content {{download_class}}"><span class="dashicons dashicons-download"></span></a>
	            	   <# } #>
	               </div>
		    	</td>
		    </tr>
           <# }) #>
		  </tbody>
		</table>
	</div>
</script>