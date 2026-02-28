(function ($) {
    'use strict';
    $(document).ready(function () {
        if (typeof ere_profile_vars !== "undefined") {

            var ajax_url = ere_profile_vars.ajax_url;
            var ajax_upload_url = ere_profile_vars.ajax_upload_url;
            var file_type_title = ere_profile_vars.file_type_title;
            var ere_site_url = ere_profile_vars.ere_site_url;
            var confirm_become_agent_msg=ere_profile_vars.confirm_become_agent_msg;
            var confirm_leave_agent_msg=ere_profile_vars.confirm_leave_agent_msg;

            $('.ere-update-profile').validate({
                ignore: ":hidden", // any children of hidden desc are ignored
                errorElement: "span", // wrap error elements in span not label
                rules: {
                    user_firstname: {
                        required: true
                    },
                    user_lastname: {
                        required: true
                    },
                    user_email: {
                        required: true
                    },
                    user_mobile_number: {
                        required: true
                    }
                },
                messages: {
                    user_firstname: "",
                    user_lastname: "",
                    user_email: "",
                    user_mobile_number: ""
                }
            });

            $("#ere_update_profile").on('click', function () {
                var $this = $(this);
                var $form = $this.parents('form');
                var $alert_title=$this.text();
                if ($form.valid()) {
                    $.ajax({
                        type: 'POST',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_update_profile_ajax',
                            'user_firstname': $("#user_firstname").val(),
                            'user_lastname': $("#user_lastname").val(),
                            'user_des': $("#user_des").val(),
                            'user_position': $("#user_position").val(),
                            'user_email': $("#user_email").val(),
                            'user_mobile_number': $("#user_mobile_number").val(),
                            'user_fax_number': $("#user_fax_number").val(),
                            'user_company': $("#user_company").val(),
                            'user_licenses': $("#user_licenses").val(),
                            'user_office_number': $("#user_office_number").val(),
                            'user_office_address': $("#user_office_address").val(),
                            'user_facebook_url': $("#user_facebook_url").val(),
                            'user_twitter_url': $("#user_twitter_url").val(),
                            'user_linkedin_url': $("#user_linkedin_url").val(),
                            'user_pinterest_url': $("#user_pinterest_url").val(),
                            'user_instagram_url': $("#user_instagram_url").val(),
                            'user_skype': $("#user_skype").val(),
                            'user_youtube_url': $("#user_youtube_url").val(),
                            'user_vimeo_url': $("#user_vimeo_url").val(),
                            'user_website_url': $("#user_website_url").val(),
                            'profile_pic': $("#profile-pic-id").val(),
                            'ere_security_update_profile': $('#ere_security_update_profile').val()
                        },
                        beforeSend: function () {
                            ERE.show_loading();
                        },
                        success: function (response) {
                            ERE.close_loading(0);
                            if (response.success) {
                                ERE.popup_alert('fa fa-check-square-o', $alert_title, response.message);
                            } else {
                                ERE.popup_alert('fa fa-exclamation-triangle', $alert_title, response.message);
                            }
                        },
                        error: function () {
                            ERE.close_loading(0);
                        }
                    });
                }
            });
            /*-------------------------------------------------------------------
             *  Change Password
             * ------------------------------------------------------------------*/
            $('.ere-change-password').validate({
                errorElement: "span", // wrap error elements in span not label
                rules: {
                    oldpass: {
                        required: true
                    },
                    newpass: {
                        required: true,
                        minlength: 4
                    },
                    confirmpass: {
                        required: true
                    }
                },
                messages: {
                    oldpass: "",
                    newpass: "",
                    confirmpass: ""
                }
            });

            $("#ere_change_pass").on('click', function () {
                var securitypassword, oldpass, newpass, confirmpass;

                var $this = $(this);
                var $form = $this.parents('form');
                var $alert_title=$this.text();
                oldpass = $("#oldpass").val();
                newpass = $("#newpass").val();
                confirmpass = $("#confirmpass").val();
                securitypassword = $("#ere_security_change_password").val();
                if ($form.valid()) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: ajax_url,
                        data: {
                            'action': 'ere_change_password_ajax',
                            'oldpass': oldpass,
                            'newpass': newpass,
                            'confirmpass': confirmpass,
                            'ere_security_change_password': securitypassword
                        },
                        beforeSend: function () {
                            ERE.show_loading();
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.href = ere_site_url;
                            } else {
                                ERE.close_loading(0);
                                ERE.popup_alert('fa fa-exclamation-triangle', $alert_title, response.message);
                            }
                        },
                        error: function () {
                            ERE.close_loading(0);
                        }
                    });
                }
            });

            $('#ere_user_as_agent').on('click', function () {
                var $this = $(this);
                var $alert_title=$this.text();
                ERE.confirm_dialog($alert_title, confirm_become_agent_msg, function () {
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_register_user_as_agent_ajax',
                            'ere_security_become_agent': $('#ere_security_become_agent').val()
                        },
                        beforeSend: function () {
                            ERE.show_loading();
                        },
                        success: function (response) {
                            if (response.success) {
                                ERE.close_loading(0);
                                ERE.popup_alert('fa fa-check-square-o',$alert_title,response.message );
                                setTimeout(function(){
                                    window.location.reload();
                                }, 3000);
                            }
                            else
                            {
                                ERE.close_loading(0);
                                ERE.popup_alert('fa fa-exclamation-triangle',$alert_title,response.message );
                            }
                        },
                        error: function () {
                            ERE.close_loading(0);
                        }
                    });
                });
            });

            $('#ere_leave_agent').on('click', function () {
                var $this = $(this);
                var $alert_title=$this.text();
                ERE.confirm_dialog($alert_title, confirm_leave_agent_msg, function () {
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_leave_agent_ajax',
                            'ere_security_leave_agent': $('#ere_security_leave_agent').val()
                        },
                        beforeSend: function () {
                            ERE.show_loading();
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.reload();
                            }
                            else
                            {
                                ERE.show_loading();
                                ERE.popup_alert('fa fa-exclamation-triangle',$alert_title,response.message );
                            }
                        },
                        error: function () {
                            ERE.show_loading();
                        }
                    });
                });
            });
            /*-------------------------------------------------------------------
             *  initialize uploader
             * ------------------------------------------------------------------*/
            var uploader = new plupload.Uploader({
                browse_button: 'ere_select_profile_image',
                file_data_name: 'ere_upload_file',
                container: 'ere_profile_plupload_container',
                multi_selection: false,
                url: ajax_upload_url,
                filters: {
                    mime_types: [
                        {title: file_type_title, extensions: "jpg,jpeg,gif,png"}
                    ],
                    max_file_size: '2000kb',
                    prevent_duplicates: true
                }
            });
            uploader.init();


            /* Run after adding file */
            uploader.bind('FilesAdded', function (up, files) {
                var html = '';
                var profileThumb = "";
                plupload.each(files, function (file) {
                    profileThumb += '<div id="holder-' + file.id + '" class="profile-thumb"></div>';
                });
                document.getElementById('user-profile-img').innerHTML = profileThumb;
                up.refresh();
                uploader.start();
            });


            /* Run during upload */
            uploader.bind('UploadProgress', function (up, file) {
                document.getElementById("holder-" + file.id).innerHTML = '<span><i class="fa fa-spinner fa-spin"></i></span>';
            });


            /* In case of error */
            uploader.bind('Error', function (up, err) {
                document.getElementById('errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
            });

            /* If files are uploaded successfully */
            uploader.bind('FileUploaded', function (up, file, ajax_response) {
                var response = $.parseJSON(ajax_response.response);

                if (response.success) {

                    var profileThumbHTML = '<img src="' + response.url + '"/>' +
                        '<input type="hidden" class="profile-pic-id" id="profile-pic-id" name="profile-pic-id" value="' + response.attachment_id + '"/>';

                    document.getElementById("holder-" + file.id).innerHTML = profileThumbHTML;
                }
            });

            $('#remove-profile-image').on('click', function (event) {
                event.preventDefault();
                document.getElementById('user-profile-img').innerHTML = '<div class="profile-thumb"></div>';
            });
        }
    });
})(jQuery);