"use strict"
var FFMWP_DD = {
    
    init: function() {
        
        jQuery('.wpfm_file_box > .wpfm_user_file').draggable({

    		revert: function() {
    
    			jQuery(this).find('.file-action').css('display', 'inline-block');
    			jQuery(this).find('.wpfm_user_file').css('border', '1px solid #ddd');
    			jQuery(this).find('.wpfm-img').css('width', '64%');
    
    			return true;
    		},
    		cursor: 'move',
    		// refreshPositions: true
    	});
    
    	jQuery('.wpfm_file_box').on("drag", function(event, ui) {
    
            
    		jQuery(this).find('.file-action').css('display', 'none');
    		jQuery(this).find('.wpfm_user_file').css('border', 'none');
    		jQuery(this).find('.wpfm-img').css('width', '40%');
    
    	});
    
    	jQuery('*[data-file_type="dir"]').droppable({
    
    		hoverClass: 'wpfm-active-droppable-box',
    
    		accept: '.wpfm_file_box > .wpfm_user_file',
    		drop: function(event, ui) {
    
                console.log('ui', ui);
    			var dir_id = jQuery(this).data("node_id");
    			var file_id = ui.draggable.attr('id');
    			jQuery("#wpfm-files-wrapper").find("[data-node_id='" + file_id + "']").css('display', 'none');
    
    			var data = {
    				action: 'nm_uploadfile_move_file',
    				file_id: file_id,
    				parent_id: dir_id,
    				"wpfm_ajax_nonce": jQuery('#wpfm_ajax_nonce').val()
    			}
    
    			jQuery.post(ffmwp_vars.ajaxurl, data, function(resp) {
    
    				if (resp.success) {
    				    
    				    var {message, updated_dir} = resp.data;
    				    // removing the file
    				    var updated_files = FFMWP.current_files.filter( f => f.id !== parseInt(file_id) );
                        // removing the dir
    				    updated_files = updated_files.filter( f => f.id !== parseInt(dir_id) );
    				    // adding new dir with new file moved
    				    updated_files = [updated_dir, ...updated_files];
                        //Refresh current directory with fresh files
					    FFMWP.reload_current_dir(updated_files);
    					FFMWP.alert(message, 'success');
					    
    				}
    				else {
    				    var {message, user_files} = resp.data;
    					FFMWP.alert(message, 'error');
    					window.location.reload();
    				}
    
    			}).fail(function() {
    
    				alert("error");
    			}, 'json');
    
    		}
    	});

    	jQuery('*[data-file_type="file"]').droppable({
    
    
    		hoverClass: 'wpfm-file-droppable-box',
    
    		accept: '*[data-file_type="file"]',
    
    	});
    }
}