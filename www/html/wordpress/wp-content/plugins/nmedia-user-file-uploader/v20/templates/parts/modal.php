<?php
/**
 * FrontEnd FileManager WP Util Modal Template
 */

/*
**========== Block Direct Access ===========
*/
if( ! defined('ABSPATH' ) ){ exit; } 
?>
<div id="ffmwp-model-wrapper"></div>

<script type="text/html" id="tmpl-ffmwp-model">
<# _.forEach( data, function ( file ) { #>
<div class="ffmwp-modal-ffmwp-container">
    <div class="ffmwp_modal" data-ffmwp_modal-id="ffmwp-files-popup-{{file.id}}" role="dialog">
        <div class="ffmwp-modal-ffmwp-content">
            <div class="ffmwp-admin-wrapper"> 
              <span class="dashicons dashicons-dismiss circle-dismiss" data-ffmwp_modal-action="cancel" aria-hidden="true"></span>
                <div class="container-fluid">
                    <div class="row">
                    
                        {{{FFMWP_Util.render_template_part('ffmwp-modal-left', file)}}}
                        
                        {{{FFMWP_Util.render_template_part('ffmwp-modal-right', file)}}}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<# }) #>
</script>