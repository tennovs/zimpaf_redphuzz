<?php
/**
 * FrontEnd FileManager WP Util Files Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
?>
<div class="ffmwp_files_wrapper grid" id="wpfm-files-wrapper">
  <div class="row ffmwp_files_grid"></div>
</div>

<script type="text/html" id="tmpl-ffmwp-files-grid">
<# _.forEach( data, function ( file ) { 
  // console.log(file.thumb_url);
  
  var download_class = file.location == 'amazon' ? 'wpfm-amazon-download ffmwp-download' : 'ffmwp-download';
  #>
  <div class="col-xs-12 wpfm_file_box file-margin-bottom {{file.title}} col-sm-2 parent-0 mix wpfm-file ui-droppable node-{{file.id}}" 
        id="node-{{file.id}}"
        data-file_type="{{file.node_type}}"
        data-pid="0"
        data-title="{{file.title}}"
        data-node_id="{{file.id}}">
    <div class="wpfm_user_file ui-draggable ui-draggable-handle" id="{{file.id}}">
      <div class="icon-box file-box">
        <img height="100" width="100" class="img-thumbnail wpfm-img" src="{{file.thumb_url}}" style="width: auto;">
      </div>
      <div class="ffmwp-file-icons file-action">
        <# if(file.node_type == 'dir'){ #>
    	    {{{file.share_button_v20}}}
          <a href="#" class="ffmwp-file-icons-content ffmwp-eye wpfm-dir" data-node_id="{{file.id}}" data-title="{{file.title}}"><span class="dashicons dashicons-visibility"></span></a>
    	    <a href="#" class="ffmwp-file-icons-content ffmwp-trash ffmwp-delete-file" data-id="{{file.id}}"><span class="dashicons dashicons-trash"></span></a>
        <# }else{ #>
        
    	    {{{file.share_button_v20}}}
    	    
    	    {{{file.view_button}}}
    	    
    	    <a href="#" class="ffmwp-file-icons-content ffmwp-trash ffmwp-delete-file" data-id="{{file.id}}"><span class="dashicons dashicons-trash"></span></a>
    	    
    	    {{{file.download_button_v20}}}
    	    
    	   <# } #>
      </div>  
    </div>
    <span class="file_title ffmwp-content-title">{{file.title}}</span>
    
    <# 
    //search only
    #>
    <span class="ffmwp_desc_search">{{file.description}}</span>
  </div>
<# }) #>
</script>