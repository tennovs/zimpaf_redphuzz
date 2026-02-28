var wpfmFiles = [];
var wpfm_file_error_type = '';
                

jQuery(function($) {
  // console.log(FileAPI);
	    
	if( FileAPI != undefined ) {
	 
    	FileAPI.event.on(filechooser, 'change', function (evt){
         
          var files = FileAPI.getFiles(evt); // Retrieve file list
          
          
            var minWidth = parseInt(wpfm_file_vars.image_min_width);
            var minHeight = parseInt(wpfm_file_vars.image_min_height);
            var maxWidth = parseInt(wpfm_file_vars.image_max_width);
            var maxHeight = parseInt(wpfm_file_vars.image_max_height);
          
          FileAPI.filterFiles(files, function (file, info/**Object*/){
            
            if( ! wpfm_file_type_allowed( file ) ) {
                wpfm_file_error_type = 'file_type'
                return false;
            }  
            
            var size_in_bytes = parseInt(wpfm_file_vars.max_file_size) * 1024 * 1024;
            
            if( file.size <= size_in_bytes){
                
                if( /^image/.test(file.type) ){
                    if(wpfm_file_vars.image_sizing !== ''){
                        if( ! (info.width >= minWidth && info.height >= minHeight) || !(info.width <= maxWidth && info.height <= maxHeight) ){
                            var msg_local = wpfm_file_vars.messages.file_dimension_error +
                            ' Min Height: '+minHeight+' Min Width: '+minWidth+ ' Max Height: '+maxHeight+' Max Width: '+maxWidth;
                            WPFM.alert(msg_local, 'error');
                            return false;
                        }else{
                            return true;
                        }
                    }else{
                        return true;
                    }
                }
                
            }else{
            
                var msg_local = wpfm_file_vars.messages.file_size_error + wpfm_file_vars.max_file_size+'mb';
                
                WPFM.alert(msg_local, 'error');
                return false;
            }
            
            return true;
            
          }, function (files/**Array*/, rejected/**Array*/){
            
            
            if( files.length && files.length >= wpfm_file_vars.total_file_allow){
                alert('your total files limit is '+wpfm_file_vars.total_file_allow);
                return false;
            }
            
            if( files.length && files.length > wpfm_file_vars.max_files){
                alert(wpfm_file_vars.max_files_message);
                return false;
            }
            
            // Checking errors
            if( ! wpfm_show_error() ) {
                return false;
            }
                
              wpfm_selected_images_preview(files);
              
              if(wpfm_file_vars.amazon_enabled == 'yes'){
                  wpfm_upload_files_amazon(files)
              }else{
                  wpfm_upload_files(files);

              }
            
          });
        });
        
        // setting up uploader
        // console.log(wpfm_file_vars.file_drag_drop);
        var dnd_el = document.getElementById('upload_files_btn_area');
        FileAPI.event.dnd(dnd_el, function (over){
            
            dnd_el.style.backgroundColor = over ? '#f60': '';
        }, function (files){
            
            if( files.length && files.length > wpfm_file_vars.max_files ){
                alert(wpfm_file_vars.max_files_message);
                return false;
                
            }else{
                wpfm_selected_images_preview(files);
                wpfm_upload_files(files);
            }
        });
    
        
	}

});

// Get image transfomr settings
function wpfm_get_image_transorm_settings() {
    
    var wpfmImagesTransformed;
    
    if(wpfm_file_vars.image_resize){
        var resize_params = wpfm_file_vars.image_resize.split(',');
        wpfmImagesTransformed = {
            maxWidth: resize_params[0],
            maxHeight: resize_params[1]
        }
    } else {
        wpfmImagesTransformed = undefined;        
    }
}


// Checking file types
function wpfm_file_type_allowed( file ) {
    
    var file_type   = file.name.split('.').pop();
    file_type       = file_type.toLowerCase();
    var type_valid = false;
    if( jQuery.inArray(file_type, wpfm_vars.file_types) !== -1 ) 
        type_valid = true;
        
    return type_valid;
}

function wpfm_show_error() {
    
    if( wpfm_file_error_type === '' ) return true;
    
    switch( wpfm_file_error_type ) {
        
        case 'file_type':
            WPFM.alert(wpfm_vars.messages.file_type_error, 'error');
            wpfm_file_error_type = '';
            break;
    }
    
    return false;
}

/** 
 * Display Images Preview
 */
 function wpfm_selected_images_preview(files){
  FileAPI.each(files, function (file){
      
    //   console.log(file);
      
      jQuery('#wpfm_file_loading_wrapper').css({
        "margin-top" : "10px",
        "margin-bottom" : "10px"
      })
      var class_name = file.name.replace(/[^a-zA-Z0-9]/g, "");
      //div with 3 column to hold image
      var new_row = jQuery('<div/>', { 
        class: 'row '+class_name
      }).appendTo('#wpfm_file_loading_wrapper');
      
    if( ! /^image/.test(file.type) ){
        
        var thumb_holder = jQuery('<div/>', { 
                class: 'col-sm-3 text-center'
            }).appendTo(new_row).append(file.name);
            
        //progressbar holder
        var fileprogress_holder = jQuery('<div/>', {
           class: 'progress',
        }).appendTo(thumb_holder)
        .append('<div class="progress-bar"></div>');
        
    }else{
        
        FileAPI.Image(file).preview(wpfm_file_vars.image_size).get(function (err, img){
        
            var thumb_holder = jQuery('<div/>', { 
                class: 'col-sm-3 text-center'
            }).appendTo(new_row).append(img);
            
            //progressbar holder
            var fileprogress_holder = jQuery('<div/>', {
               class: 'progress',
            }).appendTo(thumb_holder)
            .append('<div class="progress-bar"></div>');
            
        });
    
    }
    // console.log(thumb_holder);
   
    // jQuery('.upload_choosed_files').show();
    jQuery('.wpfm-new-select-wrapper').hide();
    jQuery('#wpfm-create-dir-option-btn').hide();
    jQuery('#wpfm-save-file-btn').show();
  
    wpfmFiles.push(file);
  });
}

/**
 * upload all files to local server
 * 
 * @since 10.5
 */
function wpfm_upload_files(uploadFiles) {

   jQuery.blockUI({ message:  '',});
 
    var wpfm_ajax_nonce = jQuery('#wpfm_ajax_nonce').val();
    // Uploading Files
      FileAPI.upload({
        url: `${wpfm_file_vars.ajaxurl}?wpfm_ajax_nonce=${wpfm_ajax_nonce}&action=wpfm_upload_file`,
        files: {file: uploadFiles},
        fileprogress: function (evt, file){ 
            var class_name = file.name.replace(/[^a-zA-Z0-9]/g, "");
            jQuery('.'+class_name+' .progress').show();
            var percent = parseInt((evt.loaded / evt.total * 100));
            jQuery('.'+class_name+' .progress .progress-bar').css('width', percent+'%');
            jQuery('.'+class_name+' .progress .progress-bar').text(percent+'%');

        },
        complete: function (err, xhr){
            if(err == false){
                
                var response = jQuery.parseJSON(xhr.response);
                if( response.status === 'error' ) {
                 alert( response.message );
                 window.location.reload();

                }

                jQuery('.file_upload_button').hide();
                jQuery('.new_file_upload').show();
                jQuery('#wpfm-save-file-btn').show();
                jQuery('#wpfm-files-wrapper').hide();


                
            } else {

                WPFM.alert(wpfm_vars.messages.file_upload_error);
            }               
        },
        
        imageTransform: wpfm_get_image_transorm_settings(),
        
        filecomplete: function (err/**String*/, xhr/**Object*/, file/**Object/, options/**Object*/){
            if( !err ){
              // File successfully uploaded
                //console.log(file);
                var class_name = file.name.replace(/[^a-zA-Z0-9]/g, "");
                jQuery('.'+class_name+' .progress .progress-bar').text(wpfm_vars.messages.file_upload_completed);
                
              
              var result = JSON.parse(xhr.responseText);
        
              jQuery.unblockUI();
    
              
              render_html_file_data(xhr.uid, class_name, result.file_name, '', result.file_groups);
              
              jQuery.event.trigger({
    				type: "wpfm_file_upload_completed",
    		    	file_id: xhr.uid,
    		    	response: result,
    		    	time: new Date()
    			});
              
            }
          }
      });

}


/** generating html block for file input (title, desc) **/
function render_html_file_data(fileid, class_name, file_name, amazon_data, file_groups){
    
    //creating HTML of added file
   //div with 9 column to hold file data input title, desc
   
   
   var file_id = 'wpfm-file-'+fileid;
   var filedata_holder = jQuery('<div/>')
              .addClass('col-sm-9 text-left wpfm-new-uploaded-files')
              .attr('id',file_id)
              .appendTo('.'+class_name);
   //title input holder
   var filetitle_holder = jQuery('<div/>', {
       class: 'form-group',
   }).appendTo(filedata_holder)
   .append('<input type="text" name="uploaded_files['+fileid+'][title]" class="form-control" placeholder="'+wpfm_vars.messages.text_title+'" value="'+file_name+'">');
   
   //hidden input filename
   var filetitle_holder = jQuery('<div/>', {
       class: 'form-group',
   }).appendTo(filedata_holder)
   .append('<input type="hidden" name="uploaded_files['+fileid+'][filename]" class="form-control" value="'+file_name+'">');
   
   //desc input holder
   var filedesc_holder = jQuery('<div/>', {
       class: 'form-group',
   }).appendTo(filedata_holder)
   .append('<textarea placeholder="'+wpfm_vars.messages.text_description+'" class="form-control" name="uploaded_files['+fileid+'][file_details]"></textarea>');
   
    // File groups if enabled
    if(wpfm_file_vars.file_group_add == 'yes' && file_groups !== undefined && file_groups.length > 0 ) {
       // var groups = jQuery('#shortcode_groups').val();
       // console.log(groups);
       var file_groups_html = '';
       file_groups_html += '<label>Group</label>';
       file_groups_html += '<select  multiple="multiple" class="form-control file-group" name="uploaded_files['+fileid+'][file_group][]">';
       file_groups_html += '<option value="0">'+wpfm_file_vars.messages.select_group+'</option>';
        jQuery.each(file_groups, function(i, group) {
          file_groups_html += '<option value="'+group.term_id+'" >'+group.name+'</option>';
          
       });
       file_groups_html += '</select>';
       file_groups_html += '<hr>';
       var filegroup_holder = jQuery('<div/>', {
           class: 'form-group',
       }).appendTo(filedata_holder)
       .append(file_groups_html);
    }
    
    if( amazon_data !== ''){
        
    var filetitle_holder = jQuery('<div/>', {
           class: 'form-group',
       }).appendTo(filedata_holder)
       .append('<input type="hidden" name="uploaded_files['+fileid+'][amazon][bucket]" class="form-control" value=\''+amazon_data.Bucket+'\'>')
       .append('<input type="hidden" name="uploaded_files['+fileid+'][amazon][key]" class="form-control" value=\''+amazon_data.Key+'\'>')
       .append('<input type="hidden" name="uploaded_files['+fileid+'][amazon][location]" class="form-control" value=\''+amazon_data.Location+'\'>');
    }
    
    jQuery.event.trigger({type: "filemanager_file_added",
                                fileid: fileid,
                                file_name: file_name,
                                amazon_data: amazon_data,
                                time: new Date()
                                });

    jQuery('select.file-group').select2({
            placeholder: 'Select Group',
        });
    
}