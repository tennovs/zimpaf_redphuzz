/* global ffmwp_vars mixitup NM_INIT_POPUP jQuery FFMWP_Util */

var FFMWP = {

  init: function() {

    this.current_files = ffmwp_vars.template_data.user_files;
    this.current_directory = 0;
    this.BC = [];
    
    // after files rendered
    jQuery(document).on('ffmwp_after_files_rendered', function(e){
      // Modal init
      var plugin_modal_id = 'ffmwp_modal';
	    NM_INIT_POPUP(plugin_modal_id);
	    
	   // Attaching file events
	   FFMWP.init_single_file_events();
	  });
	  
	  // on directory open
	  jQuery(document).on('ffmwp_on_dir_open', function(e){
	    
	    FFMWP.current_directory = e.dir.id;
	   // console.log('dir open',e);
	    FFMWP.add_to_bc(e.dir, e.context);
	  });
	  
	  // after directory created
	  jQuery(document).on('wpfm_after_dir_created', function(e){
	    
	   // FFMWP.current_directory = e.dir.id;
	   // console.log('dir open',e);
	   // FFMWP.add_to_bc(e.dir, e.context);
	  });
	  
    this.handle_new_dir_button_toggle();
    // this.handle_sorted_by_event();
    // this.handle_radio_event();
    this.handle_breadcrumb_topbar_events();
    this.handle_create_new_directy();
    this.handle_save_files();
    
    if( ffmwp_vars.files_area_display === '1' ){
      this.mixer = mixitup(`.ffmwp_files_${ffmwp_vars.files_view}`);
      FFMWP_Util.render_files(ffmwp_vars.template_data.user_files);
    }
    
  },
  
  init_single_file_events: function() {
    
    // console.log(this.mixer);
    this.handle_add_mixitup();
    this.handle_search_file_keyup_event();
    this.handle_sorted_by_event();
    this.handle_radio_event();
    this.handle_form_file_name_rename();
    this.file_edit_handle();
    this.file_cancel_handle();
    this.handle_form_file_title_desc_events();
    this.handle_form_send_file_via_email();
    this.handle_form_file_meta_update();
    this.handle_directory_open();
    this.handle_file_delete_event();
    this.handle_file_meta_wrapper_toggle_envent();
    
    // file drag/drop
    ffmwp_vars.dragdrop_allow && FFMWP_DD.init();
  },
  
  handle_add_mixitup: function() {

    // var mix = jQuery('.ffmwp_files_grid').mixItUp();
    this.mixer.destroy();
    this.mixer = mixitup(`.ffmwp_files_${ffmwp_vars.files_view}`,{
      animation: {
        queueLimit:5,
      }
    });
    console.log('mixed');
  },

  handle_new_dir_button_toggle: function() {

    jQuery(document).on('click', '.ffmwp-click-to-reveal, .ffmwp-uploadarea-cancel-btn ', function(e) {
      e.preventDefault();
      jQuery(`.ffmwp-click-to-reveal-block`).toggle();
    });

  },

  file_edit_handle: function() {

    jQuery(document).on('click', '.ffmwp-edit-file-title-desc', function(e) {
      e.preventDefault();
      // console.log(e);
      var fileid = jQuery(this).attr('data-fileid');
      jQuery(`#ffmwp-title-dec-dir-wrapper-${fileid}`).toggle();
    });
  },
  
  file_cancel_handle: function() {

    jQuery(document).on('click', '.wpfm-button.cancel', function(e) {
      e.preventDefault();
      // console.log(e);
      jQuery(this).closest('.ffmwp-title-dec-dir-wrapper').toggle();
    });
  },

  handle_form_file_title_desc_events: function() {

    // file-title-desc
    jQuery(document).on('submit', '.ffmwp-file-title-desc-form', function(e) {
      e.preventDefault();
      var data = jQuery(this).serialize();
      // console.log(data);

      jQuery.post(ffmwp_vars.ajaxurl, data, function(resp) {
        
        if (resp.success) {
          
          var {message, file} = resp.data;
				  
					FFMWP.alert(message, 'success');
					
				// 	var updated_files = [file, ...FFMWP.current_files];
          // console.log(updated_files);
					//Refresh current directory with fresh files
				// 	FFMWP.reload_current_dir(updated_files);
        }
        else {
          swal('error', resp.data, "error");
          location.reload();
        }

      }).fail(function() {
        swal('error', "File not update", "error");
      });

    });

  },
  
  handle_form_file_meta_update: function() {

    // file-email-msg
    jQuery(document).on('submit', '.ffmwp-update-file-meta', function(e) {
      e.preventDefault();

      var wpfm_ajax_nonce = jQuery('#wpfm_ajax_nonce').val();

      var data = jQuery(this).serialize();
      data = data+`&wpfm_ajax_nonce=${wpfm_ajax_nonce}`;
      // console.log(data);

      jQuery.post(ffmwp_vars.ajaxurl, data, function(resp) {

        var {data, success} = resp;
        var alert_type = success ? 'success' : 'error';
        FFMWP.alert(data, alert_type);
        
      }, 'json');

    });
  },

  handle_form_send_file_via_email: function() {

    // file-email-msg
    jQuery(document).on('submit', '.ffmwp-send-file-in-email', function(e) {
      e.preventDefault();

      jQuery('.ffmwp-sending-file').show();

      var wpfm_ajax_nonce = jQuery('#wpfm_ajax_nonce').val();

      var data = jQuery(this).serialize();
      data = data+`&wpfm_ajax_nonce=${wpfm_ajax_nonce}`;
      // console.log(data);

      jQuery.post(ffmwp_vars.ajaxurl, data, function(resp) {

        if (resp.success) {
          swal('success', resp.data, "success");
          // WPFM.alert(resp.data, 'success');	
        }
        else {
          swal('error', resp.data, "error");
          // WPFM.alert(resp.data, 'error');
        }

        jQuery('.ffmwp-sending-file').toggle();

      }, 'json');

    });
  },
  
  handle_form_file_name_rename: function(){

  	jQuery(document).on('click', '.ffmwp-rename-edit-btn', function(e) {
       e.preventDefault();
      
  		const filename = jQuery(this).closest('td').find('input.wpfm_filename').val();
  		const file_id = jQuery(this).attr('data-fileid');
  		const url = ffmwp_vars.rest_api_url + '/file-rename';
  		const data = { fileid: file_id, filename: filename };
  		   jQuery.post(url, data, function(resp) {
			      if (resp.error) {
              swal("Error!", "Name not changes!", "error");
              // location.reload();
            }
            else {
              swal("Good job!", "You have renamed the title!", "success");
              // location.reload();
            }
	    	 });
  		});
  	
  },
  
  handle_search_file_keyup_event: function(){
    
      jQuery(document).on('keyup', '#search_files', function(event) {
  		// Delay function invoked to make sure user stopped typing
  
  		var inputText;
  		var $matching = jQuery();
  		inputText = jQuery("#search_files").val().toLowerCase();
    
  		// Check to see if input field is empty
  		if (inputText.length > 0) {
  			jQuery('.mix').each(function() {
  				// add item to be filtered out if input text matches items inside the title   
  				if (jQuery(this).children('.file_title, .ffmwp_desc_search').text().toLowerCase().match(inputText)) {
            // console.log(this);
  					$matching = $matching.add(this);
  				}
  				else {
  					// removes any previously matched item
  					$matching = $matching.not(this);
  				}
  			});
  			
      		FFMWP.mixer.filter($matching);
  		}
  		else {
  		  FFMWP.mixer.filter('all');
  		}
  	});
    
  },
  
  handle_sorted_by_event: function(){
    
      jQuery(document).on('change', '#wpfm_sorted_by', function(event) {
    		var orderby = jQuery(this).val();
    		var order = jQuery('input[name="wpfm_sortorder"]:checked').val();
    		FFMWP.mixer.sort(`${orderby}:${order}`);
    	});
  },
  
  handle_radio_event: function(){
    
      jQuery(document).on('change', 'input[name="wpfm_sortorder"]', function(event) {
    		var order = jQuery(this).val();
    		var orderby = jQuery("#wpfm_sorted_by").val();
    		console.log(order, orderby);
    		FFMWP.mixer.sort(`${orderby}:${order}`);
    	});
  },
  
  handle_breadcrumb_topbar_events: function() {
    
    jQuery(document).on('click', '.wpfm-bc-item', function(e){
      e.preventDefault();
        var dir_id = jQuery(this).data('node_id');
		    var dir_title = jQuery(this).data('title');
		    var dir_node = FFMWP.get_node_by_id(dir_id);
	      FFMWP.open_directory(dir_node, 'bc-click'); 
    })
    
  },
  
  handle_create_new_directy: function(){
    
      jQuery('#ffmwp-create-dir-form').on('submit', function(e){
        
        e.preventDefault();
        var data = jQuery(this).serialize();
        var wp_nonce_value = jQuery('#wpfm_ajax_nonce').val();
        
        data += `&wpfm_ajax_nonce=${wp_nonce_value}&parent_id=${FFMWP.current_directory}`;
        
        jQuery.post(ffmwp_vars.ajaxurl, data, function(resp) {

				if(resp.success) {
				  
				  var {message, user_files, dir_id, new_dir} = resp.data;
				  
					FFMWP.alert(message, 'success');
					
					var updated_files = [new_dir, ...FFMWP.current_files];
          // console.log(updated_files);
					//Refresh current directory with fresh files
					FFMWP.reload_current_dir(updated_files);
					
					jQuery.event.trigger({
						type: "wpfm_after_dir_created",
						dir_id: dir_id,
						time: new Date()
					});
					
				// 	FFMWP_Util.render_files(user_files);
					
				}else{
				  
					FFMWP.alert(resp.data, 'error');
				// 	window.location.reload(false);
				}

			}).fail(function() {
				FFMWP.alert(ffmwp_vars.messages.http_server_error, 'error');
			}, 'json');
        
      })
  },
  
  handle_save_files: function() {
    
      jQuery(document).on('click', '#ffmwp_save_files_btn', function(e){
        e.preventDefault();
        FFMWP_File.upload_files();
        
      });
  },
  
  handle_directory_open: function(){
    
      jQuery(document).off('click', '.ffmwp-eye.wpfm-dir');
      // console.log('init dir open');
      
      var current_files = this.current_files;
      // console.log(current_files);
      
		  jQuery(document).on('click', '.ffmwp-eye.wpfm-dir', function(e){
		    e.preventDefault();
		    // e.stopPropagation();
		    
		    var dir_id = parseInt(jQuery(this).attr('data-node_id'));
		    
		    var current_dir = current_files.filter(function(f) {
		      return dir_id == f.id;
		    });
		    
		    if( current_dir.length > 0 ) {
		      current_dir = current_dir[0];
		      FFMWP.open_directory(current_dir);
		    }
		    
		  });
		},
		
	open_directory: function(dir_node, context='dir-click'){
		    FFMWP.current_files = dir_node.children === undefined ? ffmwp_vars.template_data.user_files : dir_node.children;
        FFMWP_Util.render_files(FFMWP.current_files);
	      jQuery.event.trigger({
  				type: "ffmwp_on_dir_open",
  				time: new Date(),
  				dir: dir_node,
  				context:context,
  			});  
		},
		
	add_to_bc: function(node, context) {

      switch (context) {
        case 'bc-click':
          let index = this.BC.findIndex(bc => bc.id === node.id);
          this.BC = this.BC.splice(0, index+1);
          break;
        case 'dir-click':
          if (this.BC.length == 0) {
    				this.BC.push(ffmwp_vars.default_bc);
    			}
    		// 	console.log(node);
    
    			if (node.id !== undefined) {
    				var new_node = { id: node.id, title: node.title };
    				this.BC.push(new_node);
    			}
    			break;
    		case 'home-click':
    		  this.BC = [];
    		  this.BC.push(ffmwp_vars.default_bc);
    		  break;
      }

			this.render_bc();
		},
		
	render_bc: function() {

			if (this.BC == []) return;

			var wpfm_bc_dom = jQuery('#wpfm-bc');
			wpfm_bc_dom.html('');
      // console.log(this.BC);
			jQuery.each(this.BC, function(i, bc) {

				var BCItem = jQuery('<li/>')
					.html(bc.title)
					.attr('data-node_id', bc.id)
					.attr('data-title', bc.title)
					.addClass('wpfm-bc-item')
					.addClass('ffmwp-left-bc')
					.appendTo(wpfm_bc_dom);
			});
		},
		
	get_node_by_id: function(node_id, children){
		  
		  var file_found = Array();

			if (node_id == 0) return file_found;

			var searchable_files = children === undefined ? ffmwp_vars.template_data.user_files : children;

			jQuery.each(searchable_files, function(i, file) {

				if (file.id === node_id) {
					file_found = file;
					return false;
				}else {
					if (file.node_type === 'dir' && file.children.length > 0 && file_found.length == 0) {
						file_found = FFMWP.get_node_by_id(node_id, file.children);
					}
				}
			});
			
			return file_found;
		},
		
	handle_file_delete_event: function(){
	  
      jQuery(document).on('click', '.ffmwp-delete-file', function(e) {
        
      e.preventDefault();
      
      var file_id = jQuery(this).data('id');
      
      
      swal({
      	title: ffmwp_vars.labels.file_delete,
      	icon: "warning",
      	showCancelButton: true,
      	buttons: true,
      	buttons: [ffmwp_vars.labels.text_cancel, ffmwp_vars.labels.text_yes],
      	dangerMode: true
      }).then(function(willDelete) {
      	if (willDelete) {
      
      		swal(ffmwp_vars.labels.file_deleting, {
      
      			className: "red-bg",
      			buttons: false,
      
      		});
      
      		FFMWP.delete_file(file_id);
      
      	}
      	else {
      		jQuery('html').css('overflow', 'visible')
      		jQuery('body').css('overflow', 'visible')
      	}
      });
      
      });
	},
	
	delete_file: function(file_id){
	  
	  //first hide modal
		var modal_id = `ffmwp-files-popup-${file_id}`;

		jQuery(modal_id).hide();
		
		var data = {
			'action': 'wpfm_delete_file',
			'file_id': file_id,
			"wpfm_ajax_nonce" :jQuery('#wpfm_ajax_nonce').val()
		}

		jQuery.post(ffmwp_vars.ajaxurl, data, function(resp) {

			if( resp.success ) {
			  
			  var {message} = resp.data;
			  
				FFMWP.alert(message, 'success');
				
				jQuery.event.trigger({
					type: "wpfm_after_item_deleted",
					file_id: file_id,
					time: new Date()
				});
				
				var updated_files = FFMWP.current_files.filter( f=> f.id !== parseInt(file_id) );
        // refreshing file rendering
        // FFMWP.reload_current_dir(updated_files);
				
				FFMWP_Util.render_files(updated_files);
				
			}else{
				FFMWP.alert(resp.data, 'error');
			}
		},'json');
			
	},
	
	reload_current_dir: function(user_files){
	  
	  // now reloading fresh files after creating the dir
		ffmwp_vars.template_data.user_files = user_files;
		
	  var dir_node = FFMWP.get_node_by_id(FFMWP.current_directory);
		FFMWP.open_directory(dir_node); 
	},
	
	alert: function(message, type) {

		type = undefined ? 'success' : type
		return swal(message, "", type);
	},
	
	handle_file_meta_wrapper_toggle_envent: function(){
	
    jQuery(document).on('click', '.ffmwp-edit-file-meta-wrapper', function(e) {
      e.preventDefault();
      
      jQuery('.ffmwp-update-file-meta').toggle();
      jQuery('.ffmwp-file-meta-info').toggle();

    });    
    
    jQuery(document).on('click', '.ffmwp-field-form-cancel-btn', function(e) {
      e.preventDefault();
      
      jQuery('.ffmwp-update-file-meta').toggle();
      jQuery('.ffmwp-file-meta-info').toggle();

    });
	  
	}
		
}

FFMWP.init();
jQuery(function($) {
  
});