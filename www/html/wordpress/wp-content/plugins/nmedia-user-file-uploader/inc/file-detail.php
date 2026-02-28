<?php
/**
 * Single file template function
 **/
 if( ! defined("ABSPATH") ) die("Not Allowed");
 
function wpfm_get_file_detail( $file ) {
    
    $file_title = esc_attr($file->title);
    
    $allowed_html = [
        'a'      => [
            'href'  => [],
            'title' => [],
        ],
        'br'     => [],
        'em'     => [],
        'strong' => [],
    ];
    
    $file_content = wp_kses( $file->description, $allowed_html );
    
    
    $html = '';
    $html .= '<div class="wpfm-wrapper ffmwp-modal-ffmwp-container">';
    $html .= '<div id="file_detail_box_'.$file->id.'">';
        $html .= '<div class="close-modal-btn close-file_detail_box_'.$file->id.'"> ';
            $html .= '<img class="close-btn" src="'.WPFM_URL.'/images/closebt.svg">';
        $html .= '</div>';
        
        $html .= '<div class="ffmwp-modal-ffmwp-content">';
            $html .= '<div class="ffmwp-admin-wrapper">';
            $html .= '<div class="container-fluid">';
            $html .= '<div class="row">';
            $html .= '<div class="col-sm-3">';
                $html .= '<div class="thumbnail ffmwp-modal-card">'.$file->thumb_image.'</div>';
                
                $html   .= $file->download_button;
                
                
                if( $file->is_updateable && class_exists('WPFM_FileRevision') ) {
                    
                    $html .= $file->update_button;
                }
                
                 if( $file->is_deletable ) {
                    
                    $html .= $file->delete_button;
                }
                
                // if( $file->is_share_enable ) {
                //     $html   .= $file->share_button;
                // }
                
                $html .= '<div class="table-responsive">';
                    $html .= '<table class="table table-bordered table-striped">';
                        $html .= '<tbody>';
                            $html .= '<tr>';
                                $html .= '<td><b>'.__( 'File Title', 'wpfm').'</b></td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td>'.$file_title.'</td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td><b>'.__( 'File Name', 'wpfm').'</b></td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td><input type="text" class="wpfm_filename" name="wpfm_filename" value="'.$file->name.'">';
                                $html .= '<button data-fileid="'.esc_attr($file->id).'" title="'.__('Rename File').'" class="btn btn-primary btn-sm wpfm-wrap pull-right rename-edit-btn"><span class="dashicons dashicons-yes-alt"></span></button>';
                                $html .= '</td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td><b>'.__( "File Size", "wpfm" ).'</b></td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td>'.$file->size.'</td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td><b>'.__( "File ID", "wpfm" ).'</b></td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td>'.$file->id.'</td>';
                            $html .= '</tr>';
                            
                            if( wpfm_is_keep_log_file_name() && class_exists('WPFM_FileRevision') ) {
                                $html .= $file->exist_filenames;
                            }
                            
                            $html .= '<tr>';
                                $html .= '<td><b>'.__('Total Downloads', 'wpfm').'</b></td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td>'.$file->total_downloads.'</td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td><b>'.__( "Created Date", "wpfm" ).'</b></td>';
                            $html .= '</tr>';
                            $html .= '<tr>';
                                $html .= '<td>'.$file->created_on.'</td>';
                            $html .= '</tr>';
                        $html .= '</tbody>';
                    $html .= '</table>';
                $html .= '</div>';
                
                
               $html .= '<button class="btn-block close-file_detail_box_'.$file->id.' btn btn-primary pull-right" data-close_frizi="modal">'.__("Close", "wpfm").'</button>';
                
            $html .= '</div>';
            $html .= '<div class="col-sm-9">';
                if( wpfm_is_user_to_edit_file() ) {
                    
                    $html .= '<div class="row">';
                        $html .= '<div class="col-sm-9">';
                            $html .= '<h2 class="file-title">'. sprintf(__("%s", "wpfm"), $file_title ) .'</h2>';
                            $html .= '<p>'. sprintf(__("%s", "wpfm"), $file_content) .'</p>';
                        $html .= '</div>';
                    
                        $html .= '<div class="col-sm-3">';
                            $html .= '<button title="'.__('Edit Title','wpfm').'" class="btn btn-primary btn-sm wpfm-wrap pull-right file-edit-btn"><span class="dashicons dashicons-edit"></span></button>';
                            $html .= '<span class="clearfix"></span>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="row">';
                        $html .= '<div class="col-sm-12">';
                            $html .= '<div class="ffmwp-modal-form title_dec_adit_wrapper">';
                                $html .= '<input type="hidden" name="file_id" value="'.esc_attr($file->id).'">';
                                $html .= '<input type="hidden" name="action" value="wpfm_edit_file_title_desc">';
                                $html .= '<div class="card-header">';
                                    $html .= '<h3 class="card-title">'.__( "File Data", "wpfm" ).'</h3>';
                                $html .= '</div>';
                                $html .= '<div class="card-body">';
                                    $html .= '<h4>'.__("Title", "wpfm").'</h4>';
                                    $html .= '<input class="form-control file-title" value="'.$file_title.'" type="text" data-id="'.$file->id.'">';
                                    $html .= '<h4>'.__("Description", "wpfm").'</h4>';
                                    $html .= '<textarea class="form-control file-description">'.$file_content.'</textarea>';
                                   
                                    
                                    $html .= '<h4 style="margin-top:16px">'.__("Change File Directory", "wpfm").'</h4>';
                                    $html .= '<select class="change-dir-name form control" style ="width:100%">';
                                    // $all_dir = wpfm_get_all_dir_name();
                                    // var_dump(wpfm_get_all_dir_name());
                                    // foreach(wpfm_get_all_dir_name() as $fid => $fname){
                                        
                                    //     $html .= '<option value="'.$fid.'">'.$fname.'</option>';
                                    // }
                                    
                                    $html .='</select>';
                                    
                                    $html .= '<div>';
                                        $html .= '<button class="btn btn-success file-title-dec-save-btn" data-dismiss="modal">'.__( "Save Changes", "wpfm" ).'</button><button class="btn btn-info file-title-dec-cancel-adit-btn">'.__( "Cancel", "wpfm").'</button>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';  // col-sm-12
                    $html .= '</div>';  // row

                } else {
                    $html .= '<div class="row">';
                        $html .= '<div class="col-sm-12">';
                            $html .= '<h4 class="file-title">'. __( $file_title, "wpfm") .'</h4>';
                            $html .= '<p>'. __( $file->description, "wpfm") .'</p>';
                        $html .= '</div>';
                    $html .= '</div>';
                }
                
                if ( !empty($file->file_meta_info) ) {
                    $html .= '<div class="row">';
                        $html .= '<div class="col-sm-12">';
                            $html .= '<div class="meta-inforation ffmwp-modal-form">';
                                $html .= '<div class="meta-info">';
                                    $html .= '<div class="form-header">';
                                        $html .= '<h3 class="form-title">'.__( "File Meta", "wpfm" ).'</h3>';
                                    $html .= '</div>';
                                    $html .= '<div class="form-body">';
                                        $html .= '<div>';
                                            
                                            $html .= $file->file_meta_info;
                                        $html .= '</div>';
                                        
                                        if( wpfm_is_user_to_edit_file() ) {
                                            $html .= '<a title="'.__('Edit Meta','wpfm').'" class="edit-meta-btn pull-right btn btn-primary"><span class="dashicons dashicons-edit"></span></a>';
                                        }
                                    $html .= '</div>';
                                $html .= '</div>';
                                $html .= '<div class="border border-dark meta-edit-from">';
                                    $html .= '<h4 class="card-header modal-title">'.__('Edit Meta', 'wpfm').'</h4>';
                                        $html .= '<form class="form save-meta-frm" data-file_id="'.esc_attr($file->id).'">';
                                            $html .= $file->file_meta_html; 
                                        $html .= '<button class="btn btn-success save-file-meta-btn">'.__('Save Meta','wpfm').'</button>';
        		                        $html .= '<button class="btn btn-info go-to-meta-info-btn">'.__("Cancel", "wpfm").'</button>';
                                        $html .= '</form>';
                                    $html .= '<span class="clearfix"></span>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<br>';
                }
                
                // Check against admin option _send_file
                if( wpfm_is_user_allow_to_send_file() ) {
                    $req = 'required';
                    if(is_admin()){
                        $req = '';
                    }
                    $html .= '<div class="ffmwp-modal-form ffmwp-send-file-form-border">';
                        $html .= '<div class="form-header">';
                            $html .= '<h1 class="form-title">'.__('Send File','wpfm').'</h1>';
                        $html .= '</div>';
                        $html .= '<div class="form-body">';
                            $html .= '<form class="form-horizontal wpfm-send-file-in-email">';
                                $html .= '<input type="hidden" name="action" value="wpfm_send_file_in_email">';
                                $html .= '<input type="hidden" name="file_id" value="'.esc_attr($file->id).'">';
                                $html .= '<div class="form-group">';
                                    $html .= '<label class="col-sm-2 control-label" for="emailaddress">'.__('Email','wpfm').'</label>';
                                    $html .= '<div class="col-sm-12"><input '.$req.' type="email" class="form-control col-sm-12" name="emailaddress" id="emailaddress"></div>';
                                $html .= '</div>';
                                // $html .= '<div class="form-group">';
                                //     $html .= '<label class="col-sm-2 control-label" for="subject">'.__('Subject','wpfm').'</label>';
                                //     $html .= '<div class="col-sm-10"><input '.$req.' type="text" class="form-control" name="subject" id="subject"></div>';
                                // $html .= '</div>';
                                $html .= '<div class="form-group">';
                                    $html .= '<label class="col-sm-2 control-label" for="message">'.__('Message (optional)','wpfm').'</label>';
                                    $html .= '<div class="col-sm-12"><textarea id="message" name="message" class="form-control"></textarea></div>';
                                $html .= '</div>';
                                $html .= '<div class="form-group"></div>';
                                $html .= '<div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary ">'.__('Send','wpfm').'</button></div>';
                                $html .= '<span class="wpfm-sending-file" style="display:none">'.__('Sending file ...','wpfm').'</span>';
                            $html .= '</form>';
                        $html .= '</div>';
                    $html .= '</div>';
                
                }
            $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>'; // end col-12
        $html .= '</div>'; // row   
        $html .= '</div>'; // container-fluid
        $html .= '</div>'; // container-fluid
    $html .= '</div>';
    $html .= '</div>';
    
    // wpfm_pa($file);

    return apply_filters('wpfm_file_detail_template', $html, $file);
}