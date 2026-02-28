<?php
/**
 * FrontEnd FileManager WP Util Modal right content
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
?>

<script type="text/html" id="tmpl-ffmwp-modal-right">
<div class="col-md-9 ffmwp-modal-right-wrapper">
    <div class="row">
        <h1 class="file-title ffmwp-model-file-title col-sm-11">{{data.title}}</h1>
        <span class="dashicons dashicons-edit-large fa-3 ffmwp-edit-file-title-desc" data-fileid="{{data.id}}" aria-hidden="true"></span>
    </div>
        <p>{{data.description}}</p>
    <hr style="margin:20px 0 !important;">    
    <div class="ffmwp-title-dec-dir-wrapper" id="ffmwp-title-dec-dir-wrapper-{{data.id}}">
    <form class="ffmwp-modal-form ffmwp-file-title-desc-form">
        <input type="hidden" name="file_id" value="{{data.id}}"/>
        <input type="hidden" name="action" value="wpfm_edit_file_title_desc"/>
        <ul>
            <li>
            <label for="title-{{data.id}}" class="wpfm-label title"><?php _e('File Title', 'wpfm');?></label>
            <input type="text" id="title-{{data.id}}" name="file_title" data-id="{{data.id}}" value="{{data.title}}" class="wpfm-input title ffmwp-modal-field-style ffmwp-modal-field-full" required/>
            </li>
            <li>
            <label for="desc-{{data.id}}" class="wpfm-label desc"><?php _e('File Description', 'wpfm');?></label>
            <textarea name="file_content" class="wpfm-intput desc ffmwp-modal-field-style" id="desc-{{data.id}}">{{data.description}}</textarea>
            </li>
            <li>
            <input type="submit" value="<?php _e('Save Changes', 'wpfm');?>" class="ffmwp-save-title-desc-btn" />
            <button class="wpfm-button cancel"><?php _e('Cancel', 'wpfm');?></button>
            </li>
        </ul>
    </form>
    </div>
    
    <# 
    if( ffmwp_vars.file_meta.length ) {
    #>
    
    <div class="ffmwp-file-meta-wrapper">
        <div class="row">
            <h2 class="col-sm-11">{{ffmwp_vars.labels.file_meta_heading}}</h2>
            <span class="dashicons dashicons-edit fa-3 ffmwp-edit-file-meta-wrapper" aria-hidden="true"></span>
        </div>
        
        <div class="ffmwp-file-meta-info">
            {{{data.file_meta_info}}}
        </div>
        
        <form class="ffmwp-update-file-meta ffmwp-modal-form">
                <input type="hidden" name="action" value="wpfm_file_meta_update"/>
                {{{data.file_meta_html}}}
                
                <input type="submit" class="ffmwp-send-email-btn" value="{{ffmwp_vars.labels.button_meta_save}}" />
                <button class="ffmwp-field-form-cancel-btn">Cancel</button>
        </form>
    </div>
    
    <#
    }
    #>
    
    <# 
    if( ffmwp_vars.template_data.enable_email_share ) {
    #>
    
    <div class="ffmwp-email-msg-wrapper">
    <form class="ffmwp-send-file-in-email ffmwp-modal-form">
            <input type="hidden" name="file_id" value="{{data.id}}"/>
            <input type="hidden" name="action" value="wpfm_send_file_in_email"/>
            <h1>Send File</h1>
        <ul>
            <li>
            <label>Email</label>
            <input type="email" name="emailaddress" class="ffmwp-modal-field-style ffmwp-modal-field-full" required/>
            </li>
            <li> 
            <label>Message (optional)</label>
            <textarea name="message" class="ffmwp-modal-field-style"></textarea>
            </li>
            <li>
            <input type="submit" class="ffmwp-send-email-btn" value="Send" />
            <span class="ffmwp-sending-file" style="display:none">Sending file ...</span>
            </li>
        </ul>
    </form>
    </div>
    
    <#
    }
    #>
    
</div>

</script>