<?php
/**
 * Single file template function
 **/
 if( ! defined("ABSPATH") ) die("Not Allowed");
function wpfm_get_file_detail( $file ) {
    
    $html = '';
    $html .= '<div class="frizi-modal frizi-animate" id="file_detail_box_'.$file->id().'">';
        $html .= '<div class="modal-dialog frizi-full">';
            $html .= '<div class="modal-content">';
                $html .= '<div class="modal-header">';
                    $html .= '<button class="close" data-close_frizi="modal">×</button>';
                    $html .= '<h4 class="modal-title">'.__( $file->title(), "wpfm").'</h4>';
                $html .= '</div>';
                $html .= '<div class="modal-body row">';
                    $html .= '<div class="col-sm-3">';
                        $html .= '<div class="thumbnail">'.$file->thumb_image().'</div>';
                        // $html .= '<a class="btn btn-success btn-block download-file-btn" data-id="'.$file->id.'" href="'.esc_attr( $file->download_url).'">Download</a>';
                        $html   .= $file->download_button();
                        $html   .= $file->share_button();
                        
                        $html .= '<div class="table-responsive">';
                            $html .= '<table class="table table-bordered table-striped">';
                                $html .= '<tbody>';
                                    $html .= '<tr>';
                                        $html .= '<td><b>'.__( 'File Title', 'wpfm').'</b></td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td>'.$file->title().'</td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td><b>'.__( "File Size", "wpfm" ).'</b></td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td>'.$file->size().'</td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td><b>'.__( "File ID", "wpfm" ).'</b></td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td>'.$file->id().'</td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td><b>'.__('Total Downloads', 'wpfm').'</b></td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td>'.$file->total_downloads().'</td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td><b>'.__( "Created Date", "wpfm" ).'</b></td>';
                                    $html .= '</tr>';
                                    $html .= '<tr>';
                                        $html .= '<td>'.$file->created_on().'</td>';
                                    $html .= '</tr>';
                                $html .= '</tbody>';
                            $html .= '</table>';
                        $html .= '</div>';
                        
                        
                        $html .= $file->delete_button();
                        
                        
                    $html .= '</div>';
                    $html .= '<div class="col-sm-9">';
                        $html .= '<div class="col-sm-9">';
                            $html .= '<h4 class="file-title">'. __( $file->title(), "wpfm") .'</h4>';
                            $html .= '<p>'. __( $file->description(), "wpfm") .'</p>';
                        $html .= '</div>';
                        $html .= '<div class="col-sm-3">';
                            $html .= '<button class="btn btn-info btn-sm wpfm-wrap pull-right file-edit-btn" id="file-edit-btn">'.__("Edit Title and Description", "wpfm").'</button>';
                            $html .= '<span class="clearfix"></span>';
                        $html .= '</div>';
                        $html .= '<div class="col-sm-12">';
                        $html .= '<div class="panel panel-primary title_dec_adit_wrapper">';
                            $html .= '<div class="panel-heading">';
                                $html .= '<h3 class="panel-title">'.__( "File Data", "wpfm" ).'</h3>';
                            $html .= '</div>';
                            $html .= '<div class="panel-body">';
                                $html .= '<h4>Title</h4>';
                                $html .= '<input class="form-control file-title" value="'.esc_attr($file->title).'" type="text" data-id="'.$file->id.'">';
                                $html .= '<h4>'.__("Description", "wpfm").'</h4>';
                                $html .= '<textarea class="form-control file-description">'.esc_textarea($file->description).'</textarea>';
                                $html .= '<button class="btn btn-success file-title-dec-save-btn" data-dismiss="modal">'.__( "Save Changes", "wpfm" ).'</button><button class="btn btn-info file-title-dec-cancel-adit-btn">'.__( "Cancel", "wpfm").'</button>';
                            $html .= '</div>';
                        $html .= '</div>';
                        if ( !empty($file->file_meta_info) ) {
                            $html .= '<div class="panel panel-primary">';
                                $html .= '<div class="panel-heading">';
                                    $html .= '<h3 class="panel-title">'.__( "File Meta", "wpfm" ).'</h3>';
                                $html .= '</div>';
                                $html .= '<div class="panel-body">';
                                    $html .= '<div>';
                                        
                                        $html .= $file->file_meta_info;
                                    $html .= '</div>';
                                    $html .= '<button class="pull-right btn btn-info" data-modal="frizi-modal" data-target="#file_meta_'.$file->id.'">Edit</button>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<hr>';
                        }
                        // Check against admin option _send_file
                        
                        if( wpfm_is_user_allow_to_send_file() ) {
                              $req = 'required';
                                if(is_admin()){
                                    $req = '';
                                }
                            $html .= '<div class="panel panel-primary">';
                                $html .= '<div class="panel-heading">';
                                    $html .= '<h3 class="panel-title">'.__('Send File','wpfm').'</h3>';
                                $html .= '</div>';
                                $html .= '<div class="panel-body">';
                                    $html .= '<form class="form-horizontal wpfm-send-file-in-email">';
                                        $html .= '<input type="hidden" name="action" value="wpfm_send_file_in_email">';
                                        $html .= '<input type="hidden" name="_download_url" value="'.esc_url($file->download_url).'">';
                                        $html .= '<div class="form-group">';
                                            $html .= '<label class="col-sm-2 control-label" for="emailaddress">'.__('Email','wpfm').'</label>';
                                            $html .= '<div class="col-sm-10"><input '.$req.' type="email" class="form-control" name="emailaddress" id="emailaddress"></div>';
                                        $html .= '</div>';
                                        $html .= '<div class="form-group">';
                                            $html .= '<label class="col-sm-2 control-label" for="subject">'.__('Subject','wpfm').'</label>';
                                            $html .= '<div class="col-sm-10"><input '.$req.' type="text" class="form-control" name="subject" id="subject"></div>';
                                        $html .= '</div>';
                                        $html .= '<div class="form-group">';
                                            $html .= '<label class="col-sm-2 control-label" for="message">'.__('Message (optional)','wpfm').'</label>';
                                            $html .= '<div class="col-sm-10"><textarea id="message" name="message" class="form-control"></textarea></div>';
                                        $html .= '</div>';
                                        $html .= '<div class="form-group"></div>';
                                        $html .= '<div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary ">'.__('Send','wpfm').'</button></div>';
                                        $html .= '<span class="wpfm-sending-file" style="display:none">'.__('Sending file ...','wpfm').'</span>';
                                    $html .= '</form>';
                                $html .= '</div>';
                            $html .= '</div>';
                        
                        }
                        $html .= '<button class="btn btn-primary pull-right" data-close_frizi="modal">'.__("Close", "wpfm").'</button>';
                    $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>'; // end col-12
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
                      
    // file meta save area/modal
    $html .= '<div id="file_meta_'.$file->id.'" class="frizi-modal child-frizi" role="dialog">';
        $html .= '<div class="modal-dialog ">';

            $html .= '<div class="modal-content">';
                $html .= '<div class="modal-header">';
                $html .= '<button type="button" class="close" data-close_frizi="modal">&times;</button>';
                $html .= '<h4 class="modal-title">Edit '. $file->name .' Meta</h4>';
                $html .= '</div>';
                $html .= '<div class="modal-body">';
                    $html .= '<form class="form save-meta-frm" data-file_id="'.$file->id.'">';
                        $html .= $file->file_meta_html; 
                    $html .= '</form>';
                $html .= '<button class="btn btn-primary pull-right" data-close_frizi="modal">'.__("Close", "wpfm").'</button>';
                $html .= '<span class="clearfix"></span>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>  ';
    return apply_filters('wpfm_file_detail_template', $html, $file);
}