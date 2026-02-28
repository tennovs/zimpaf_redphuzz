/**
 * File Upload Handling
 **/
 
/* global jQuery ffmwp_file_vars ffmwp_vars wpfm_amz_vars FFMWP ffmwp_choosefile FileAPI */

var FFMWP_File = {
    
    init: function() {
        
        this.error_type = '';
        this.FILE_COMPLETED = false;
        
        this.files = [];
        this.connect_dom();
        
        // handle file remove (before upload)
        this.handle_file_remove();
        
        // handle file data after uploading complete of each file
        this.handle_file_data_form_submit();
        
        
        // Local events
        
        // on directory open
        jQuery(document).on('ffmwp_after_file_preview', function(e){
    	    
            jQuery('.ffmwp-select2').select2();
            
            // when file preview show save file button
            jQuery('.ffmwp_save_file_button_wrapper').show();
        });
        
        // on all files uploaded
        jQuery(document).on('ffmwp_after_all_files_uploaded', function(e) {
            FFMWP_File.FILE_COMPLETED = true;
            // Now submit form data
            jQuery(`#ffmwp-form-data`).submit();
        });
        
    },
    
    connect_dom: function(){
        
        FileAPI.event.on(ffmwp_choosefile, 'change', function (evt){
        
            var files = FileAPI.getFiles(evt); // Retrieve file list
            
            var minWidth = parseInt(ffmwp_file_vars.image_min_width);
            var minHeight = parseInt(ffmwp_file_vars.image_min_height);
            var maxWidth = parseInt(ffmwp_file_vars.image_max_width);
            var maxHeight = parseInt(ffmwp_file_vars.image_max_height);
            
            FileAPI.filterFiles(files, function (file, info/**Object*/){
            
                if( ! FFMWP_File.allowed_file_types( file ) ) {
                    FFMWP_File.error_type = 'file_type'
                    return false;
                } 
                
                var size_in_bytes = parseInt(ffmwp_file_vars.max_file_size) * 1024 * 1024;
                
                if( file.size <= size_in_bytes){
                    
                    if( /^image/.test(file.type) ){
                        if(ffmwp_file_vars.image_sizing !== ''){
                            if( ! (info.width >= minWidth && info.height >= minHeight) || !(info.width <= maxWidth && info.height <= maxHeight) ){
                                var msg_local = ffmwp_file_vars.labels.file_dimension_error +
                                ' Min Height: '+minHeight+' Min Width: '+minWidth+ ' Max Height: '+maxHeight+' Max Width: '+maxWidth;
                                FFMWP.alert(msg_local, 'error');
                                return false;
                            }else{
                                return true;
                            }
                        }else{
                            return true;
                        }
                    }
                    
                }else{
                
                    var msg_local = ffmwp_file_vars.labels.file_size_error + ffmwp_file_vars.max_file_size+'mb';
                    
                    FFMWP.alert(msg_local, 'error');
                    return false;
                }
                
                return true;
                
            }, function (files/**Array*/, rejected/**Array*/){
            
                if( files.length && files.length > ffmwp_file_vars.total_file_allow){
                    alert(ffmwp_file_vars.labels.file_max_user);
                    return false;
                }
                
                // console.log(FFMWP_File.files);
                if( files.length && files.length > ffmwp_file_vars.max_files 
                    || (FFMWP_File.files.length+files.length) > ffmwp_file_vars.max_files){
                    alert(ffmwp_file_vars.max_files_message);
                    return false;
                }
                
                // Checking errors
                if( ! FFMWP_File.wpfm_show_error() ) {
                    return false;
                }
                    
                FFMWP_File.file_preview(files);
                // reset for new upload
                FFMWP_File.FILE_COMPLETED = false;
            
            });
        });
    },
    
    allowed_file_types: function( file ) {
    
        var file_type   = file.name.split('.').pop();
        file_type       = file_type.toLowerCase();
        var type_valid = false;
        if( jQuery.inArray(file_type, ffmwp_file_vars.file_types) !== -1 ) 
            type_valid = true;
            
        return type_valid;
    },
    
    wpfm_show_error: function() {
    
        console.log(this.error_type);
        if( this.error_type === '' ) return true;
        
        switch( this.error_type ) {
            
            case 'file_type':
                FFMWP.alert(ffmwp_file_vars.labels.file_type_error, 'error');
                this.error_type = '';
                break;
        }
        
        return false;
    },
    
    file_preview: function( files ) {
        // console.log(files);
        
        FileAPI.each(files, function (file){
            
            file._id = FFMWP_File.get_file_id(file);
            FFMWP_File.files.push(file);
        
            var is_image = (/^image/.test(file.type)) && file.type !== 'image/tiff' ? true : false;
            // console.log(file, is_image);
            if( is_image ) {
                FileAPI.Image(file).preview(ffmwp_file_vars.image_size).get(function (err, img){
                    
                    // console.log(img);
                    var preview_thumb = img.toDataURL() || '';
                    var data = {file: file, 
                                preview_thumb: preview_thumb, 
                                is_image: is_image,
                                file_id: FFMWP_File.get_file_id(file),
                                };
                    const preview_tmpl = FFMWP_Util.render_template_part('ffmwp-preview-files', data);
                    jQuery('#ffmwp_files_preview_wrapper').append(preview_tmpl);
                    jQuery.event.trigger({
						type: "ffmwp_after_file_preview",
						file: file,
						time: new Date()
					});
                });
            } else {
                var data = {file: file, preview_thumb: 
                            ffmwp_file_vars.image_preview_ph,
                            is_image: is_image,
                            file_id: FFMWP_File.get_file_id(file),
                            };
                const preview_tmpl = FFMWP_Util.render_template_part('ffmwp-preview-files', data);
                jQuery('#ffmwp_files_preview_wrapper').append(preview_tmpl);
                jQuery.event.trigger({
						type: "ffmwp_after_file_preview",
						file: file,
						time: new Date()
					});
            }
        });
    },
    
    upload_files: function() {
        
        // file file uploaded operation is already completed, than only submit form data
        if(this.FILE_COMPLETED) {
            jQuery(`#ffmwp-form-data`).submit();
            return;
        }
        
        
        jQuery.blockUI({ message:  ffmwp_file_vars.labels.file_uploading});
        
        if( ffmwp_file_vars.amazon_enabled === 'yes' ) {
            this.upload_via_amazon();
        } else {
            this.upload_via_fileapi();
        }
        
    },
    
    upload_via_fileapi: function() {
        
        var wpfm_ajax_nonce = jQuery('#wpfm_ajax_nonce').val();
        
        FileAPI.upload({
            url: `${ffmwp_file_vars.ajaxurl}?wpfm_ajax_nonce=${wpfm_ajax_nonce}&action=wpfm_upload_file`,
            files: {file: FFMWP_File.files},
            fileprogress: function (evt, file){
                // console.log(file);
                var file_id = FFMWP_File.get_file_id(file);
                jQuery(`.${file_id}.progress`).show();
                var percent = parseInt((evt.loaded / evt.total * 100));
                jQuery(`.${file_id}.progress`).css('width', percent+'%');
                jQuery(`.${file_id}.progress`).text(percent+'%');
    
            },
            filecomplete: function (err/**String*/, xhr/**Object*/, file/**Object/, options/**Object*/){
                if( !err ){
                  // File successfully uploaded
                    //console.log(file);
                    var file_id = FFMWP_File.get_file_id(file);
                    jQuery(`.${file_id}.progress`).text(ffmwp_file_vars.labels.file_upload_completed);
                    
                    // parsing the result
                    var result = JSON.parse(xhr.responseText);
                    var file_name = result.file_name;
                    
                    // setting filename to hidden input in form
                    jQuery(`#file_name-${file_id}`).val(file_name);
                    
                }
            },
            complete: function (err, xhr){
                
                jQuery.unblockUI();
                
                // console.log(xhr);
                if(err == false){
                    var response = jQuery.parseJSON(xhr.response);
                    if( response.status === 'error' ) {
                        FFMWP.alert(response.message, 'error');
                        window.location.reload();
                    }
                    
                    // Regitering event when all files uploaded
                    jQuery.event.trigger({
						type: "ffmwp_after_all_files_uploaded",
						location: 'local',
						time: new Date()
					});
                    
                    
                }
            },
            
        });
    },
    
    upload_via_amazon: function() {
        
        jQuery.blockUI({ message:  '',});
        
        //adding support to upload files directly to Amazon
        AWS.config.update({accessKeyId: wpfm_amz_vars.amazon_key, 
                            secretAccessKey: wpfm_amz_vars.amazon_secret,
                            region: wpfm_amz_vars.amazon_region
        });
        
        var AWS_BUCKET = new AWS.S3({params: {Bucket: wpfm_amz_vars.amazon_bucket}});
        
        var Uploaded_Files = 0;
                    
        jQuery(FFMWP_File.files).each(function(index, file){
           
           // Getting Keys
           var fileKey = '';
           if( FFMWP.current_directory !== 0 ) {
               var dir_node = FFMWP.get_node_by_id(FFMWP.current_directory);
               fileKey += dir_node.title;
           }
           
           fileKey += file.name;
           
            if(wpfm_amz_vars.user_name !== ''){
                fileKey = wpfm_amz_vars.user_name+'/'+fileKey;
            }
            
            var acl_permission = wpfm_amz_vars.amazon_acl == 'yes' ? 'public-read' : 'private';
            
            
            var params = {  Key: fileKey, 
                            ContentType: file.type, 
                            Body: file, 
                            ACL: acl_permission,
                            Metadata: {
                            'user_name': wpfm_amz_vars.user_name,
                          },
            };
            
            AWS_BUCKET
            .upload(params)
            .on('httpUploadProgress', function(evt) {
                
                var file_id = FFMWP_File.get_file_id(file);
                jQuery(`.${file_id}.progress`).show();
                var percent = parseInt((evt.loaded / evt.total * 100));
                jQuery(`.${file_id}.progress`).css('width', percent+'%');
                jQuery(`.${file_id}.progress`).text(percent+'%');
                
                //console.log("Uploaded :: " + parseInt((evt.loaded * 100) / evt.total)+'%');
            })
            
            .send(function(err, amazon_data) {
                
                Uploaded_Files++
                
                jQuery.unblockUI();
                
                var file_id = FFMWP_File.get_file_id(file);
                jQuery(`.${file_id}.progress`).text(ffmwp_file_vars.labels.file_upload_completed);
                
                if( ! err ) {
                    
                    // setting filename to hidden input in form
                    jQuery(`#file_name-${file_id}`).val(file.name);
                    
                    jQuery(`#aws-data-${file_id}`).html('')
                    .append('<input type="hidden" name="uploaded_files['+file_id+'][amazon][bucket]" class="form-control" value=\''+amazon_data.Bucket+'\'>')
                    .append('<input type="hidden" name="uploaded_files['+file_id+'][amazon][key]" class="form-control" value=\''+amazon_data.Key+'\'>')
                    .append('<input type="hidden" name="uploaded_files['+file_id+'][amazon][location]" class="form-control" value=\''+amazon_data.Location+'\'>');
      
                    
                    if( Uploaded_Files == FFMWP_File.files.length ){
                        // Regitering event when all files uploaded
                        jQuery.event.trigger({
    						type: "ffmwp_after_all_files_uploaded",
    						location: 'amazon',
    						time: new Date()
    					});
                    }
                    
                } else {
                    FFMWP.alert(ffmwp_file_vars.labels.file_upload_error, 'error');
                    // window.location.reload();
                }
                
            });
            
        });
    },
    
    handle_file_remove: function() {
        
        jQuery(document).on('click', '.ffmwp-file-remove', function(e){
           
           e.preventDefault();
           var file_id = jQuery(this).data('node_id');
           
           FFMWP_File.remove_preview_file(file_id);
           
        });
    },
    
    // remove single or all preview files.
    remove_preview_file: function(file_id = 'ALL'){
        
        if( file_id == 'ALL' ){
            FFMWP_File.files = [];
            jQuery(`.file-preview-wrapper`).remove();
        }else{
            // filtered items
            FFMWP_File.files = FFMWP_File.files.filter(f => f._id !== file_id);
            // console.log(FFMWP_File.files);
            jQuery(`.file-preview-wrapper.${file_id}`).remove();
        }
       
        // if not files found hide the save file button
        if( FFMWP_File.files.length === 0 ){
           jQuery('.ffmwp_save_file_button_wrapper').hide();
        }
    },
    
    handle_file_data_form_submit: function(){
        
        jQuery(document).on('submit', '#ffmwp-form-data', function(e){
            jQuery.blockUI({ message:  ffmwp_file_vars.labels.file_data_saving});
            
            e.preventDefault();
            var data = jQuery(this).serialize();
            data += `&parent_id=${FFMWP.current_directory}`;
            // console.log(data); return;
            
            jQuery.post(ffmwp_vars.ajaxurl, data, function(resp){
               jQuery.unblockUI();
               
               // extracting file objects
               var new_files = resp.new_files.map(f => f.file_obj);
               
               var updated_files = [...new_files, ...FFMWP.current_files];
               // refreshing file rendering
               FFMWP.reload_current_dir(updated_files);
               
               FFMWP_File.remove_preview_file('ALL');
            //   var alter_type = resp.status == 'success' ? true : false;
               FFMWP.alert(resp.message, resp.status);
            //   window.location.reload();
            },'json');
        })
        
    },
    
    get_file_id: function(file){
        var filename = file.name.replace(/[^a-zA-Z0-9]/g, "");
        return `${filename}-${file.lastModified}`;
    }
}

FFMWP_File.init();