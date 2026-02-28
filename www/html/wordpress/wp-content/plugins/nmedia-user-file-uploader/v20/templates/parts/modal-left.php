<?php
/**
 * FrontEnd FileManager WP Util Modal left content
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
?>
<script type="text/html" id="tmpl-ffmwp-modal-left">
<div class="col-md-3 ffmwp-modal-left-wrapper">
    <div class="ffmwp-modal-card">
       <!-- <img src="{{data.thumb_url}}" alt="Avatar" style="width:100%"> -->
       {{{data.thumb_image}}}
    </div>
    <div class="ffmwp-left-modal-icons"> 
        <a href="{{{data.download_url}}}" data-id="{{data.id}}" class="ffmwp-file-icons-content ffmwp-download"><span class="dashicons dashicons-download"></span></a>
        <a href="#" class="ffmwp-file-icons-content ffmwp-trash ffmwp-delete-file" data-id="{{data.id}}"><span class="dashicons dashicons-trash"></span></a>
        <# if(ffmwp_vars.is_revision_addon){ #>
            {{{data.update_button}}}
        <# } #>
    </div>
    <div class="table-responsive">
    <table class="ffmwp-left-modal-table">
        <tbody>
            <tr>
                <td><b>{{ffmwp_vars.labels.file_title}}</b></td>
            </tr>
            <tr>
                <td>{{data.title}}</td>
            </tr>
            <tr>
                <td><b>{{ffmwp_vars.labels.file_name}}</b></td>
            </tr>
            <tr>
                <td>
                    <input type="text" class="wpfm_filename" name="wpfm_filename" value="{{data.name}}"/>
                    <button data-fileid="{{data.id}}" title="Rename File" class="wpfm-wrap ffmwp-rename-edit-btn pull-right"><span class="dashicons dashicons-yes-alt"></span></button>
                </td>
            </tr>
            <tr>
                <td><b>{{ffmwp_vars.labels.file_size}}</b></td>
            </tr>
            <tr>
                <td>{{data.size}}</td>
            </tr>
            <tr>
                <td><b>{{ffmwp_vars.labels.file_id}}</b></td>
            </tr>    
            <tr>
                <td>{{data.id}}</td>
            </tr>
            <tr>
                <td><b>{{ffmwp_vars.labels.total_downloads}}</b></td>
            </tr>
            <tr>
                <td>{{data.total_downloads}}</td>            
            </tr>
            <tr>
                <td><b>{{ffmwp_vars.labels.uploaded_on}}</b></td>
            </tr>
            <tr>
                <td>{{data.created_on}}</td>
            </tr>
            <tr>
                <td><b>{{ffmwp_vars.labels.file_source}}</b></td>
            </tr>
            <tr>
                <td>{{data.location == 'amazon' ? ffmwp_vars.labels.file_source_aws : ffmwp_vars.labels.file_source_local }}</td>
            </tr>
        </tbody>
    </table>
    <input data-ffmwp_modal-action="cancel" type="submit" value="Close" />            
    </div>
</div>
</script>