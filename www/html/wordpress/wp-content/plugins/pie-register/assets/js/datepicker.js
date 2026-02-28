var piereg = jQuery.noConflict();

piereg(document).ready(function(e) {

    /*piereg('.date_start').on("focus", function() {
        if (!piereg("#ui-datepicker-div").closest('.pieregWrapper').length) {
            piereg("#ui-datepicker-div").wrap("<div class='pieregWrapper pieregister-admin'></div>");
        }
    });*/

    /*piereg(".pieregformWrapper form").validationEngine();
    piereg("#lostpasswordform").validationEngine();
    piereg("#resetpassform").validationEngine();	
    piereg("#loginform").validationEngine();*/

    /*piereg('.date_start').datepicker({
        dateFormat : 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		yearRange: piereg_startingDate+":"+piereg_endingDate
	});*/

    // used count to make unique ids for datepicker.
    var count = 0;

    piereg('.date_start').each(function(index, element) {

        count++;

        var selectedForm = piereg(this).closest('form');

        var id = piereg(this).attr("id");
        var new_id = id + "_" + count; // Date field Id updated to avoid same id conflict in 2 forms. 
        selectedForm.find("#" + id).attr("id", new_id);

        piereg_endingDate = new Date();

        var year  = ( piereg_endingDate.getFullYear() ) + 10;
        piereg_endingDate = year;

        //Setting date Format
        var formatid = id + "_format";
        var format = selectedForm.find("#" + formatid).val();
       
        read = selectedForm.find("#" + new_id).attr("readonly");   // Field Visibility Plugin
        if(read != "readonly"){
        
            selectedForm.find("#" + new_id).datepicker({
                dateFormat: format,
                changeMonth: true,
                changeYear: true,
                yearRange: piereg_startingDate + ":" + piereg_endingDate,
                showAnim: "fadeIn"
            });

            //First day of a week
            var formatid = id + "_firstday";
            var format = selectedForm.find("#" + formatid).val();
            selectedForm.find("#" + new_id).datepicker({
                firstDay: format,
                changeMonth: true,
                changeYear: true,
                yearRange: piereg_startingDate + ":" + piereg_endingDate,
                showAnim: "fadeIn"
            });

            //Min date		
            var formatid = id + "_startdate";
            var format = selectedForm.find("#" + formatid).val();
            selectedForm.find("#" + new_id).datepicker({
                minDate: format,
                changeMonth: true,
                changeYear: true,
                yearRange: piereg_startingDate + ":" + piereg_endingDate,
                showAnim: "fadeIn"
            });
        }
        piereg("#ui-datepicker-div").hide();

        if (!piereg("#ui-datepicker-div").closest('.pieregWrapper').length) {
            piereg("#ui-datepicker-div").wrap("<div class='pieregWrapper pieregister-admin'></div>");
        }
        piereg(".calendar_icon").on("click", function() {
            var selectedForm = piereg(this).closest('form');
            var id = piereg(this).attr("id");
            id = id.replace("_icon", "");
            read = selectedForm.find("#" + new_id).attr("readonly");   // Field Visibility Plugin
        
            if(read != "readonly"){
                selectedForm.find("#" + new_id).datepicker("show");
            }
        });
    });

    /* piereg js 01012015 */
    piereg(".piereg-select-all-text-onclick").on("click", function() {
        piereg(this).select();
    });
    piereg(".piereg-select-all-text-onclick").on("keyup", function() {
        piereg(this).select();
    });
    piereg(".piereg-select-all-text-onclick").on("focus", function() {
        piereg(this).select();
    });

    /*hide required field*/
    piereg("#pie_register input.piereg_input_field_required").removeAttr("required");
    piereg("#pie_register input.piereg_input_field_required").attr("class", "input_fields piereg_input_field_required ");
    piereg("#pie_register input.piereg_input_field_required").parent().parent().hide();

    if (window.location.hash == "#_=_") {
        history.pushState(null, null, window.location.href.replace(/#_=_/, ''));
        //window.location.href = window.location.href.replace(/#_=_/, '');
    }

});

// Declare jQuery Object to $.
$ = jQuery;