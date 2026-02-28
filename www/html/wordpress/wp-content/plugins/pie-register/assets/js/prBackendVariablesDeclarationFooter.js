jQuery(document).ready(function() {
    // from ver 3.6.15 due to conflict with the WP post editor
    // Conflict with WP Customer Area
    /* jQuery(document).tooltip({
        track: true,
        items: ':not(#content_ifr)'
    }); */
    jQuery(".tabsSetting .tabLayer1 > li").hover(
        function() {
            jQuery('ul.tabLayer2').each(function() {
                jQuery(this).css('display', 'none');
            })
            jQuery(this).children('ul.tabLayer2').css('display', 'block');
        },
        function() {
            jQuery('ul.tabLayer2').each(function() {
                jQuery(this).css('display', '');
            });
        }
    );
    jQuery(".piereg_restriction_type").on("click", function() {
        if (jQuery(".piereg_input #redirect").is(":checked")) {
            jQuery(".pieregister_block_content_area").hide();
            jQuery("#pieregister_restriction_url_area").show();
        } else if (jQuery(".piereg_input #block_content").is(":checked")) {
            jQuery("#pieregister_restriction_url_area").hide();
            jQuery(".pieregister_block_content_area").show();
        }
    });

    
    /**
     * Individual Visibility Restrictions
    */
     jQuery(".piereg_restriction_type").on("click",function()
     {
         checkRestrictionFields();
     });
     jQuery("#piereg_redirect_url, #piereg_redirect_page, #piereg_block_content").on("blur",function()
     {
         checkRestrictionFields();
     });
    jQuery("form#post").submit(function() {
        if (jQuery('#piereg_post_visibility').length > 1) {
            if (jQuery("#piereg_post_visibility").val().trim() != "default") {
                //	Set Defaule Color
                jQuery(".pieregister_restriction_type_area").removeAttr("style");

                var piereg_validate_fields = false;
                //	Validate Restriction Type	
                var restriction_type = false;
                var restriction_value = 0;
                jQuery(".piereg_restriction_type").each(function(i, obj) {
                    var check_local = jQuery(obj).is(":checked");
                    if (check_local) {
                        restriction_type = check_local;
                        restriction_value = jQuery(obj).val();
                    }
                });
                if (!restriction_type) {
                    jQuery(".pieregister_restriction_type_area").css({
                        "color": "rgb(250, 0, 0)"
                    });
                    piereg_validate_fields = true;
                }

                //	Validate Redirect
                var piereg_redirect_url = "";
                var piereg_redirect_page = "";
                if (restriction_value == 0) {
                    piereg_redirect_url = jQuery("#piereg_redirect_url").val();
                    piereg_redirect_page = jQuery("#piereg_redirect_page").val();
                }
                if (piereg_redirect_url != "" || piereg_redirect_page != -1) {

                } else {
                    jQuery(".pieregister_restriction_url_area").css({
                        "color": "rgb(250, 0, 0)"
                    });
                    piereg_validate_fields = true;
                }

                //	Validate Block Content	
                if (jQuery("#piereg_block_content").val().trim() == "" && jQuery(".piereg_input #block_content").is(":checked")) {
                    jQuery(".pieregister_block_content_area").css({
                        "color": "rgb(250, 0, 0)"
                    });
                    piereg_validate_fields = true;
                }

                //	Show Validation Message	
                if (piereg_validate_fields) {

                    var piereg_container = jQuery('html,body'),
                        piereg_scrollTo = jQuery('.pie_register-admin-meta');

                    piereg_container.animate({
                        scrollTop: piereg_scrollTo.offset().top - piereg_container.offset().top + piereg_container.scrollTop()
                    });
                    alert(pie_pr_backend_dec_vars.inValidFields);
                    return false;
                }
            }
        }
	});
	
    var $toplevel_page_pie_register, $toplevel_page_pie_register;
    $toplevel_page_pie_register = document.getElementById('toplevel_page_pie-register');
    $toplevel_page_pie_register = document.getElementsByClassName('toplevel_page_pie-register');
    if ($toplevel_page_pie_register != null && $toplevel_page_pie_register.length != 0 && pie_pr_backend_dec_vars.isPRFormEditor) {
        jQuery("#toplevel_page_pie-register, .toplevel_page_pie-register").removeClass("wp-not-current-submenu").addClass("wp-has-current-submenu wp-menu-open");
        jQuery("#toplevel_page_pie-register li.wp-first-item, #toplevel_page_pie-register a.wp-first-item").addClass("current");
        window.dispatchEvent(new Event('resize'));
    }

    jQuery("#payment_gateway_tabs").tabs();
    jQuery("#notifications_tabs, #blacklisted_tabs, #bulkemails_tabs").tabs();

});

function addForm() {
    var form_id = jQuery("#pie_forms").val();
    if (form_id == null || form_id == "") {
        alert(pie_pr_backend_dec_vars.plsSelectForm);
        return;
    }
    window.send_to_editor(form_id);
}
// RegFormEdit
var hintNum = 0;
jQuery(document).ready(function(e) {

    var displayhints = pie_pr_backend_dec_vars.display_hints;

    if (displayhints == "1") {
        if (sessionStorage.getItem("hint") != "abc") {
            jQuery("#hint_" + hintNum).delay(500).fadeIn();
        }
        jQuery(".thanks").click(function() {
            jQuery(this).parents(".fields_hint").delay(100).fadeOut();
            hintNum++;
            jQuery("#hint_" + hintNum).delay(500).fadeIn();
            sessionStorage.setItem("hint", "abc");
        });
    } else {
        jQuery(".fields_hint").remove();
    }
});
var defaultMeta = Array();
defaultMeta = pie_pr_backend_dec_vars.defaultMeta;

var $fillvalNum = 0;
$fillvalNum = pie_pr_backend_dec_vars.fillvalNum;
if ($fillvalNum > 0) {
    for (i = 0; i < $fillvalNum; i++) {
        fillValues(pie_pr_backend_dec_vars.fillvalValue[i], pie_pr_backend_dec_vars.fillvalKey[i]);
    }
    no = pie_pr_backend_dec_vars.fillvalNo;
}
//////////////////////////////////////////////
jQuery(document).ready(function() {
    var $num, $option;

    if (jQuery(".strength_meter").val() == "1") {
        jQuery(".strength_labels_div").fadeIn();
    } else {
        jQuery(".strength_labels_div").fadeOut();
    }

    jQuery(".strength_meter").on("change", function() {
        if (jQuery(this).val() == "1") {
            jQuery(".strength_labels_div").fadeIn();
        } else {
            jQuery(".strength_labels_div").fadeOut();
        }
    });

    $num = $option = "";

    jQuery(".piereg_registration_form_fields .selected_field").on("change", function() {
        jQuery("#" + jQuery(this).attr("data-selected_field")).val(jQuery(this).val());
    });

    /* Allow Only come */
    jQuery("input[type=text].conditional_value_input").keypress(function(e) {
        if (jQuery(this).val().indexOf(",") >= 0) {
            if (e.which == 44 || e.keyCode == 44) {
                return false;
            }
        }
    });

    jQuery("select.piereg_field_as").on("change", function() {
        /*
        	1	=	Dropdown
        	0	=	Radio
        */
        if (jQuery(this).val() == 1) {
            jQuery(".piereg_pricing_radio").hide();
            jQuery(".piereg_pricing_select").show();
        } else {
            jQuery(".piereg_pricing_select").hide();
            jQuery(".piereg_pricing_radio").show();
        }
    });

});
// End RegFormEdit

// Check Restriction fields on Page/Post - admin meta box.
function checkRestrictionFields()
{
    if ( jQuery(".piereg_input #redirect").is(":checked") )
    {
        piereg_redirect_url = jQuery("#piereg_redirect_url").val();
        piereg_redirect_page = jQuery("#piereg_redirect_page").val();

        if ( ( piereg_redirect_url == '' ) && ( piereg_redirect_page == '-1' ) )
        {
            jQuery(".piereg_restriction_msg").fadeIn(1000);
            jQuery('.piereg_restriction_msg').text('Please Provide Either Redirect Page or Redirect URL.');
        }
        else
        {
            jQuery(".piereg_restriction_msg").fadeOut(1000);
        }
    }
    else if ( jQuery(".piereg_input #block_content").is(":checked") )
    {
        if ( jQuery("#piereg_block_content").val().trim() == "" )
        {
            jQuery(".piereg_restriction_msg").fadeIn(1000);
            jQuery('.piereg_restriction_msg').text('Please Provide Block Content.');
        }
        else
        {
            jQuery(".piereg_restriction_msg").fadeOut(1000);
        }
    }
}

// Import Export page
jQuery(document).ready(function(e) {
    piereg_endingDate = new Date();

    jQuery(".pieregister-admin .selectall").change(function() {
        if (jQuery(this).is(":checked")) {
            jQuery(".meta_key").each(function(){
               jQuery(this).prop("checked", true) ;
            });
        } else {
            jQuery(".meta_key").each(function(){
                jQuery(this).prop("checked", false) ;
             });
        }
    });
    jQuery(".pieregister-admin .meta_key").change(function() {
        if (jQuery('.meta_key:checked').length == jQuery('.meta_key').length) {
            jQuery(".selectall").attr("checked", "checked");
        } else {
            jQuery(".selectall").removeAttr("checked");
        }
    });
    dateFormat = 'yy-mm-dd';
    from = jQuery( ".pieregister-admin #date_start" ).datepicker({
        maxDate: piereg_endingDate,
        changeMonth: true,
    }).on( "change", function() {
        to.datepicker( "option", "minDate", jQuery(this).datepicker('getDate') );
    });

    to   = jQuery( ".pieregister-admin #date_end" ).datepicker({
        maxDate: piereg_endingDate,
        changeMonth: true,
    }).on( "change", function() {
        from.datepicker( "option", "maxDate", jQuery(this).datepicker('getDate') );
    });
    jQuery(".pieregister-admin #start_icon").on("click", function() {
        jQuery("#date_start").datepicker("show");
    });
    jQuery(".pieregister-admin #end_icon").on("click", function() {
        jQuery("#date_end").datepicker("show");
    });
    jQuery(".pieregister-admin #export").on("submit", function() {
        if (jQuery('.meta_key:checked').length < 1) {
            alert("Please select at least one field to export.");
            return false;
        }
    });
});
//End Import Export Page
//Invitation Page
function get_selected_box_ids() {
    var checked_id = "";
    jQuery(".invitaion_fields_class").each(function(i, obj) {
        if ((jQuery(obj).prop('checked')) == true) {
            checked_id = checked_id + jQuery(obj).attr("value") + ",";
        }

    });
    if (checked_id.trim() != "" && jQuery("#invitaion_code_bulk_option").val().trim() != "" && jQuery("#invitaion_code_bulk_option").val() != "0") {
        var status_str = jQuery("#invitaion_code_bulk_option").val();

        if (status_str == "unactive") {
            status_str = "deactivate";
        } else if (status_str == "active") {
            status_str = "activate";
        }

        if (confirm("Are you sure you want to " + status_str + " selected invitation code(s).?") == true) {
            checked_id = checked_id.slice(0, -1);
            jQuery("#select_invitaion_code_bulk_option").val(checked_id);
            return true;
        } else {
            return false;
        }
    } else {
        jQuery("#invitaion_code_error").css("display", "block");
        return false;
    }
}

function select_all_invitaion_checkbox() {
    var status = document.getElementById("select_all_invitaion_checkbox").value;
    if (status.trim() == "true") {
        jQuery(".select_all_invitaion_checkbox").val("false");
        jQuery(".invitaion_fields_class").attr("checked", false);
        jQuery(".select_all_invitaion_checkbox").attr("checked", false);
    } else {
        jQuery(".select_all_invitaion_checkbox").val("true");
        jQuery(".invitaion_fields_class").attr("checked", true);
        jQuery(".select_all_invitaion_checkbox").attr("checked", true);
    }
}

function show_field(crnt, val) {
    jQuery("#" + crnt.id).css("display", "none");
    jQuery("#" + val).css("display", "inline-block"); //AQ
    jQuery("#" + val).focus();
}

function hide_datefield(crnt, val) {
    jQuery("#" + crnt.id).css("display", "none");
    jQuery("#" + val).css("display", "inline-block");
} //AQ
function hide_field(crnt, val) {
    jQuery("#" + crnt.id).css("display", "none");
    jQuery("#" + val).css("display", "inline-block"); //AQ
    current = jQuery("#" + crnt.id).val();
    value = jQuery("#" + val).html();
    jQuery("#" + val).html("Please Wait...");
    id = jQuery("#" + crnt.id).attr("data-id-invitationcode");
    type = jQuery("#" + crnt.id).attr("data-type-invitationcode");

    var inv_code_data = {
        method: "post",
        action: 'pireg_update_invitation_code',
        data: ({
            "value": jQuery("#" + crnt.id).val(),
            "id": id,
            "type": type
        })
    };
    piereg.post(ajaxurl, inv_code_data, function(response) {
        if (response.trim() == "done") {
            jQuery("#" + val).html(jQuery("#" + crnt.id).val());
        } else if (response.trim() == "duplicate") {
            if (current != value) {
                alert("This code (" + current + ") already exist");
            }
            jQuery("#" + val).html(value);
            jQuery("#" + crnt.id).val(value);
        } else if(response.trim() == "invalid usage"){
            alert("Usage can not be less than the number of times the code is used.");
            jQuery("#" + val).html(value);
            jQuery("#" + crnt.id).val(value);
        }  else {
            jQuery("#" + val).html(value);
            jQuery("#" + crnt.id).val(value);
        }
    });
}

function confirmDelInviteCode(id, code) {
    var conf = window.confirm("Are you sure you want to delete this (" + code + ") code?");
    if (conf) {
        document.getElementById("invi_del_id").value = id;
        document.getElementById("del_form").submit();
    }
}

function changeStatusCode(id, code, status) {
    var conf = window.confirm("Are you sure you want to " + status + " this (" + code + ") code ?");
    if (conf) {
        document.getElementById("status_id").value = id;
        document.getElementById("status_form").submit();
    }
}
// email count
function email_popup(id){
    jQuery("#dialog-message_"+id).dialog({
        dialogClass: "email-dialog",
        closeText  : ''
    });
}

jQuery(document).ready(function() {
    jQuery("#invitation_code_per_page_items").change(function() {
        jQuery("#form_invitation_code_per_page_items").submit();
    });
});
//End Invitation Page
//User Role Page
function confirmDelUserRole(id, role, key) {
    var conf = window.confirm("Are you sure you want to delete this (" + role + ") role?");
    if (conf) {
        document.getElementById("role_del_id").value   = id;
        document.getElementById("role_del_key").value  = key;
        document.getElementById("del_role_form").submit();
    }
}
function get_selected_roles_ids() {
    var checked_id = "";
    jQuery(".role_fields_class").each(function(i, obj) {
        if ((jQuery(obj).prop('checked')) == true) {
            checked_id = checked_id + jQuery(obj).attr("value") + ",";
        }

    });
    if (checked_id.trim() != "" && jQuery("#custom_role_bulk_option").val().trim() != "" && jQuery("#custom_role_bulk_option").val() != "0") {
        var status_str = jQuery("#custom_role_bulk_option").val();

        if (confirm("Are you sure you want to " + status_str + " selected user role(s)?") == true) {
            checked_id = checked_id.slice(0, -1);
            jQuery("#select_custom_role_bulk_option").val(checked_id);
            return true;
        } else {
            return false;
        }
    } else {
        jQuery("#custom_role_error").css("display", "block");
        return false;
    }
}
function select_all_roles_checkbox() {
    var status = document.getElementById("select_all_roles_checkbox").value;
    if (status.trim() == "true") {
        jQuery(".select_all_roles_checkbox").val("false");
        jQuery(".role_fields_class").attr("checked", false);
        jQuery(".select_all_roles_checkbox").attr("checked", false);
    } else {
        jQuery(".select_all_roles_checkbox").val("true");
        jQuery(".role_fields_class").attr("checked", true);
        jQuery(".select_all_roles_checkbox").attr("checked", true);
    }
}
//End User Role page
//Notification Page
function changediv() {
    var value = jQuery('#user_email_type').val();
    jQuery(".hide-div").hide();
    jQuery("." + value).show();

    if ((jQuery("." + value).css('display') == 'list-item') || (jQuery("." + value).css('display') == 'block')) {
        jQuery(".btnvisibile").show();
    }
}
jQuery(document).ready(function() {
    jQuery('.notification-item .notification-item-toggler').click(function() {
        jQuery(this).siblings('.content').slideToggle();
        jQuery(this).toggleClass('active');
        jQuery(this).parents('.notification-item').siblings().find('.content').slideUp();
        jQuery(this).parents('.notification-item').siblings().find('.notification-item-toggler').removeClass('active');
    })
});
if (jQuery('.ckeditor').length) {
    jQuery('.ckeditor').each(function() {
        // From PR ver 3.7.0.5 - ckeditor version updated
        // var $this = this;
        // CKEDITOR.replace(jQuery($this).attr("id"), {
        //     removeButtons: 'About'
        // });
    });
}
jQuery(document).ready(function() {
    jQuery(".piereg_replacement_keys").change(function() {
        //get the wp_editor
        var $current_ckeditor_id = jQuery(this).closest(".fields").find("textarea.wp-editor-area").prop("id");
        tinymce.get($current_ckeditor_id ).execCommand('mceInsertContent', false, jQuery(this).val().trim());
        jQuery(this).val('select');
    });
});
//End Notification page
//Payment gateway
function numbersonly(myfield, e, dec) {
    var key;
    var keychar;

    if (window.event) {
        key = window.event.keyCode;
    } else if (e) {
        key = e.which;
    } else {
        return true;
    }
    keychar = String.fromCharCode(key);

    // control keys
    if ((key == null) || (key == 0) || (key == 8) ||
        (key == 9) || (key == 13) || (key == 27)) {
        return true;
        // numbers
    } else if ((("0123456789").indexOf(keychar) > -1)) {
        return true;

        /* decimal point jump
        else if (dec && (keychar == "."))
		{
		myfield.form.elements[dec].focus();
		return false;
		} */
    } else {
        return false;
    }
}

jQuery(document).ready(function() {
    jQuery(".piereg-payment-log-table").on("click", "tbody tr", function(e) {
        jQuery("." + jQuery(this).attr("data-piereg-id")).fadeToggle(1000);
    });
});
//End payment gateway
//RegForm
function confrm_box(msg, url) {
    if (confirm(msg) == true) {
        window.location.href = url;
    }
}

function previrw_form(msg, url) {
    if (confirm(msg) == true) {
        window.open(url, "_blank", "toolbar=no,scrollbars=yes,menubar=no,resizable=no,location=no,width=" + screen.width + ",height=" + screen.height + "");
    }
}
//End egForm
//Setting All users
jQuery(document).ready(function() {
    jQuery("#after_login").change(function() {

        if (jQuery(this).val() == "url") {
            jQuery(this).parent().next(".fields").show();
        } else {
            jQuery(this).parent().next(".fields").hide();
        }
    })
    jQuery("#alternate_logout").change(function() {
        if (jQuery(this).val() == "url") {
            jQuery(this).parent().next(".fields").show();
        } else {
            jQuery(this).parent().next(".fields").hide();
        }
    })
})

function validateSettings() {
    var block_wp_login = pie_pr_backend_dec_vars.block_wp_login;
    if (block_wp_login == 1 && document.getElementById("alternate_login").value == "-1") {
        alert("Please select an alternate login page.");
        return false;
    }

    if (block_wp_login == 1 && document.getElementById("alternate_register").value == "-1") {
        alert("Please select an alternate register page.");
        return false;
    }

    if (block_wp_login == 1 && document.getElementById("alternate_forgotpass").value == "-1") {
        alert("Please select an alternate forgot password page.");
        return false;
    }
}
//End Settings All users
//Setting RoleBased
jQuery(document).ready(function(e) {

    var length = jQuery('#piereg_user_role').children('option').length;
    if (length == 0) {
        jQuery('#piereg_user_role').prop('disabled', true);
    }

    jQuery("#invitation_code_per_page_items").change(function() {
        jQuery("#form_invitation_code_per_page_items").submit();
    });

    /*Color Change Disable record*/
    jQuery(".inactive").closest("tr").css({
        "background": "rgb(237, 234, 234)"
    });

    jQuery("#log_in_page").change(function() {

        if (jQuery(this).val() == "0") {
            jQuery(this).parent().next(".fields").show();
        } else {
            jQuery(this).parent().next(".fields").hide();
        }
    })
    jQuery("#log_out_page").change(function() {
        if (jQuery(this).val() == "0") {
            jQuery(this).parent().next(".fields").show();
        } else {
            jQuery(this).parent().next(".fields").hide();
        }
    })

});

function changeStatus(id, code, status) {
    var conf = window.confirm("Are you sure you want to " + status + " this record?");
    if (conf) {
        document.getElementById("redirect_settings_status_id").value = id;
        document.getElementById("redirect_settings_status_form").submit();
    }
}

function confirmDel(id, code) {
    var conf = window.confirm("Are you sure you want to delete this (" + code + ") record?");
    if (conf) {
        document.getElementById("redirect_settings_del_id").value = id;
        document.getElementById("redirect_settings_del_form").submit();
    }
}
//End Setting RoleBased
//Setting Security Basic
function validateSettingsSecurity() {
    return piereg_recaptcha_validate();
}

function piereg_recaptcha_validate() {

    var is_error = false;

    if (!jQuery("#captcha_in_login_value_0").is(":checked") && jQuery("#piereg_capthca_in_login").val() != 2) {
        if (jQuery("#piereg_reCAPTCHA_Public_Key").val() == "") {
            jQuery("#piereg_reCAPTCHA_Public_Key_error").show();
            jQuery("#piereg_reCAPTCHA_Public_Key").css({
                "border-color": "red"
            });
            jQuery("#piereg_reCAPTCHA_Public_Key").focus();
            is_error = true;
        } else if (jQuery("#piereg_reCAPTCHA_Private_Key").val() == "") {
            jQuery("#piereg_reCAPTCHA_Public_Key_error").show();
            jQuery("#piereg_reCAPTCHA_Private_Key").focus();
            is_error = true;
        }
    } else if (!jQuery("#captcha_in_forgot_value_0").is(":checked") && jQuery("#piereg_capthca_in_forgot_pass").val() != 2) {
        if (jQuery("#piereg_reCAPTCHA_Public_Key").val() == "") {
            jQuery("#piereg_reCAPTCHA_Public_Key_error").show();
            jQuery("#piereg_reCAPTCHA_Public_Key").focus();
            is_error = true;
        } else if (jQuery("#piereg_reCAPTCHA_Private_Key").val() == "") {
            jQuery("#piereg_reCAPTCHA_Public_Key_error").show();
            jQuery("#piereg_reCAPTCHA_Private_Key").css({
                "border-color": "red"
            });
            jQuery("#piereg_reCAPTCHA_Private_Key").focus();
            is_error = true;
        }
    } else {
        jQuery("#piereg_reCAPTCHA_Public_Key_error").hide();
        jQuery("#piereg_reCAPTCHA_Private_Key").css({
            "border-color": ""
        });
    }

    if (jQuery("#piereg_reCAPTCHA_Private_Key").val() != "" || jQuery("#piereg_reCAPTCHA_Public_Key").val()) {
        var patt1 = /[0-9a-zA-Z_-]{40}/;


        if (!jQuery("#piereg_reCAPTCHA_Public_Key").val().match(patt1)) {

            if (!jQuery("#tabs_5").is(":visible")) {
                jQuery("#ui-id-5").click();
            }

            jQuery("#piereg_reCAPTCHA_Public_Key").css({
                "color": "red"
            });
            jQuery("#piereg_reCAPTCHA_Public_Key").focus();
            jQuery("#piereg_reCAPTCHA_Public_Key_error").show();
            is_error = true;
        } else if (!jQuery("#piereg_reCAPTCHA_Private_Key").val().match(patt1)) {
            if (!jQuery("#tabs_5").is(":visible")) {
                jQuery("#ui-id-5").click();
            }
            jQuery("#piereg_reCAPTCHA_Private_Key").css({
                "color": "red"
            });
            jQuery("#piereg_reCAPTCHA_Private_Key").focus();
            jQuery("#piereg_reCAPTCHA_Public_Key_error").show();
            is_error = true;
        }
    }

    if (is_error) {
        return false;
    } else {
        return true;
    }
}

function piereg_deactivate_keys_Form(form) {
    if (confirm('Are you sure you want to deactivate the plugin license?')) {
        form.submit_btn.disabled = true;
        form.submit();
        return true;
    }
    return false;
}
jQuery(document).ready(function() {
    /* Validate recaptcha error */
    jQuery(".piereg-gs-menu-btn").on("click", function() {
        return piereg_recaptcha_validate();
    });

    /* Login Form Captcha */
    jQuery(".captcha_in_login_value").on("change", function() {
        captcha_show();
    });
    captcha_show();

    function captcha_show() {
        if (jQuery("#captcha_in_login_value_1").is(":checked")) {
            jQuery(".piereg_captcha_label_show").fadeIn(1000);
            jQuery(".piereg_captcha_type_show").fadeIn(1000);
            if (jQuery("#piereg_capthca_in_login").val() == 1 && !jQuery("#captcha_in_login_value_0").is(":checked")) {
                jQuery(".piereg_recapthca_skin_login").fadeIn(1000);
                jQuery("#note_quotation").fadeIn(1000);
            }
        } else if (jQuery("#captcha_in_login_value_0").is(":checked")) {
            jQuery(".piereg_captcha_label_show").fadeOut(1000);
            jQuery(".piereg_captcha_type_show").fadeOut(1000);
            jQuery(".piereg_recapthca_skin_login").fadeOut(1000);
            jQuery("#note_quotation").fadeOut(1000);
        }
    }

    /* Login Form Captcha Type */
    jQuery("#piereg_capthca_in_login").on("change", function() {
        if (jQuery(this).val() == 1 && !jQuery("#captcha_in_login_value_0").is(":checked")) {
            jQuery(".piereg_recapthca_skin_login").fadeIn(1000);
            jQuery("#note_quotation").fadeIn(1000);
        } else {
            jQuery(".piereg_recapthca_skin_login").fadeOut(1000);
            jQuery("#note_quotation").fadeOut(1000);
        }
    });

    /* Forgot Password Form Captcha */
    jQuery(".captcha_in_forgot_value").on("change", function() {
        captcha_forgot_show();
    });
    captcha_forgot_show();

    function captcha_forgot_show() {
        if (jQuery("#captcha_in_forgot_value_1").is(":checked")) {
            jQuery(".piereg_capthca_forgot_pass_label_show").fadeIn(1000);
            jQuery(".piereg_captcha_forgot_pass_type_show").fadeIn(1000);
            if (jQuery("#piereg_capthca_in_forgot_pass").val() == 1 && !jQuery("#captcha_in_forgot_value_0").is(":checked")) {
                jQuery(".piereg_recapthca_skin_forgot_pas").fadeIn(1000);
                jQuery("#for_note_quotation").fadeIn(1000);
            }
        } else if (jQuery("#captcha_in_forgot_value_0").is(":checked")) {
            jQuery(".piereg_capthca_forgot_pass_label_show").fadeOut(1000);
            jQuery(".piereg_captcha_forgot_pass_type_show").fadeOut(1000);
            jQuery(".piereg_recapthca_skin_forgot_pas").fadeOut(1000);
            jQuery("#for_note_quotation").fadeOut(1000);
        }
    }

    /* Forgot Password Form Captcha Type */
    jQuery("#piereg_capthca_in_forgot_pass").on("change", function() {
        if (jQuery(this).val() == 1 && !jQuery("#captcha_in_forgot_value_0").is(":checked")) {
            jQuery(".piereg_recapthca_skin_forgot_pas").fadeIn(1000);
            jQuery("#for_note_quotation").fadeIn(1000);
        } else {
            jQuery(".piereg_recapthca_skin_forgot_pas").fadeOut(1000);
            jQuery("#for_note_quotation").fadeOut(1000);
        }
    });
    // Selection of reCAPTCHA v2/v3
    jQuery( 'select.piereg_recaptcha_type' ).change( function() {
        var recaptcha_v2_site_key   = jQuery( '#piereg_reCAPTCHA_Public_Key' ).parents( 'div.fields' ).eq( 0 ),
            recaptcha_v2_secret_key = jQuery( '#piereg_reCAPTCHA_Private_Key' ).parents( 'div.fields' ).eq( 0 ),
            recaptcha_v3_site_key   = jQuery( '#piereg_reCAPTCHA_Public_Key_v3' ).parents( 'div.fields' ).eq( 0 ),
            recaptcha_v3_secret_key = jQuery( '#piereg_reCAPTCHA_Private_Key_v3' ).parents( 'div.fields' ).eq( 0 );
        
        if ( 'v2' === jQuery( this ).val() ) {
            recaptcha_v2_site_key.show();
            recaptcha_v2_secret_key.show();
            recaptcha_v3_site_key.hide();
            recaptcha_v3_secret_key.hide();
        } else {
            recaptcha_v2_site_key.hide();
            recaptcha_v2_secret_key.hide();
            recaptcha_v3_site_key.show();
            recaptcha_v3_secret_key.show();
        }
    }).change();

    /* Notice Slider */
    var current_notice  = jQuery('#wp-admin-bar-pie_register').find('.piereg-notice-num');
    if(current_notice.text() > 1){
        var $slickElement = jQuery('.piereg-notice-slider');

        $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
            //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
            var i = (currentSlide ? currentSlide : 0) + 1;
        
        });
        
        $slickElement.slick({
            slide: 'div',
            swipe: false,
            autoplay: false,
            dots: false,
            adaptiveHeight: true
        });
    }

    jQuery(document.body).on( 'click', '.notice-dismiss', function( event, el ) {
        var $notice         = jQuery(this).parent('.notice.is-dismissible');
        var dismiss_url     = $notice.attr('data-dismiss-url');
        
        current_notice.text(current_notice.text() - 1);
        var current_notice_  = jQuery('.wp-menu-name').find('.piereg-notice-num');
        current_notice_.text(current_notice_.text() - 1);
        var current_notice__  = jQuery('.wp-submenu').find('li:last-child').find('.piereg-notice-num');
        current_notice__.text(current_notice__.text() - 1);
         
        i = jQuery(".piereg-notice.slick-active").attr("data-slick-index");
        if($slickElement != undefined){
            // $slickElement.slick('slickRemove', i);
            $slickElement.not('.slick-initialized').slick();
        }
        var j = 0;
        jQuery(".piereg-notice.slick-slide").each(function(){
            jQuery(this).attr("data-slick-index",j);
            j++;
        });


        if(current_notice.text() == 1){
            jQuery('.piereg-notice-slider').find('.slick-prev.slick-arrow').hide();
            jQuery('.piereg-notice-slider').find('.slick-next.slick-arrow').hide();
        }

        if(current_notice.text() == 0){
            jQuery('.piereg-notice-slider').hide();
            current_notice.hide();
            current_notice_.hide();
            current_notice__.hide();
        }
        if(dismiss_url){
            $.get( dismiss_url );
        }
        
    });
});
//End Setting Security basic