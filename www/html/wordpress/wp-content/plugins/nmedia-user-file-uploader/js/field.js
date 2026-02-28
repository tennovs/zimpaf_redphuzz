"use strict"
jQuery( function($){
    
    $(".save-meta-btn").click(function(event){
        event.preventDefault();
        $(".save-meta-btn").siblings('.loading-area').show();
        var data = $('.wpfm-fields-append :input').serialize();
        var wpfm_ajax_nonce = $('#wpfm_ajax_nonce').val();
        data = data+`&wpfm_ajax_nonce=${wpfm_ajax_nonce}&action=wpfm_save_meta`;
        $.post(ajaxurl, data, function(resp){
            
            if( resp.success ) {
                $(".save-meta-btn").siblings('.loading-area').hide();
                swal('Done', resp.data, 'success');
            }else{
                swal('Error', resp.data, 'error');
            }
        }).fail(function() {
            alert( "error" );
        });
    });

    var field_no = $('#field_index').val();
   // append new fields after click the fields button on model
    var md_field_click = 0;
    $(document).on('click', '.wpfm-fields', function(event) {
        event.preventDefault();

        var field_type = $(this).data('field-id');
        var fields = $('.wpfm-fields-append');
        var field_clone = $(".wpfm-field-"+ field_type+":last").clone();
        
        field_clone.find('.wpfm-meta-field').each(function(i, meta_field){
            
            var meta_type = $(meta_field).attr('data-metatype');
            var field_name = 'wpfm['+field_no+']['+field_type+']['+meta_type+']';
            if( meta_type === 'options' ) {
                field_name += '[0]';
            }
        
            $(meta_field).attr('name', field_name);
        });

        // getting model button no
        field_clone.find('.model_btn').each(function(i, meta_field){
            $(meta_field).attr('data-target','#model_no_'+field_no);
        });

        // getting model no to match model button no for popup
        field_clone.find('.model_index').each(function(i, meta_field){
            $(meta_field).attr('id','model_no_'+field_no);
        });        

        field_clone.find('.main-div').end().appendTo(fields);
        var new_ind = field_no;
        var opt_no1 = field_clone.find('#meta-opt-index').val();

        // add options controls
        field_clone.find('.controls').attr('data-opt-ex', field_no);
        // var add_opt_selector = field_clone.find('.wpfm-meta-field-opt');  
        // wpfm_add_option_set_index(add_opt_selector, new_ind, field_type , md_field_click );
        
        // add wpfm icons controls
        /*var add_icon_selector = field_clone.find('.icon_handle');
        wpfm_add_icons(add_icon_selector, field_no, field_type);*/
        
        // all append fields slide down and up function
        wpfm_slidtoggle(field_clone);
        
        // all append fields remove function
        wpfm_remove_fields();

        // render title
        wpfm_title_render();

        // title text input data render after field name
        $(".wpfm-slider .title").on('keyup', function() {
            var $this = $(this);
            var $this_text = $this.closest('.wpfm-slider');
            field_clone.find('.title_render').html('('+$(this).val()+')');
        });
        
        // all append fields are sortable
        $( ".main-div" ).sortable();
        $( ".main-div" ).disableSelection();

        field_no++; 

    }); 

    $( ".main-div" ).sortable();
    // $( ".wpfm-slider-inner" ).hide();

    $(document).on('click', '.wpfm-copy-field', function() {
        var copy_data = $(this).closest(".wpfm-slider.wpfm-arrange-id").clone(true);
        
        $( copy_data ).insertAfter( $(this).closest(".wpfm-slider.wpfm-arrange-id") );
    });
    // wpfm remove fields
    $(".wpfm-remove-field").click(function(e){
        e.preventDefault();
        var $this_fields = $(this);
        e.preventDefault();
        $this_fields.parent().parent().remove();
        swal({
             title: "Are you sure?",
             type: "warning",
             showCancelButton: true,
             confirmButtonColor: "#DD6B55 ",
             cancelButtonColor: "#DD6B55",
             confirmButtonText: "Yes",
             cancelButtonText: "No",
             closeOnConfirm: true
         }, function (isConfirm) {
            if (!isConfirm) return;
              $this_fields.parent().parent().remove();
         });
     });
    
    // title text input data render after field name
    function wpfm_title_render(){
        $('[data-meta-id="title"] input[type="text"]').on('keyup', function() {
            var $this = $(this);
            var $this_text = $this.closest('.wpfm-slider');
            $this_text.find('.title_render').html('('+$(this).val()+')');
        });
    }
    wpfm_title_render();
    
    
    // slideToggle function
    function wpfm_slidtoggle(field_clone){
        $(field_clone).on('click', '.wpfm-clone-field', function() {
            var field_slider = field_clone.closest('.wpfm-slider');
            field_slider.find('.wpfm_check').next('.next-slide').next(".wpfm_slide_toggle").slideToggle("slow");
        });
    }
    
    $(document).on('click', '.wpfm-clone-field', function() {
          var field_slider = $(this).closest('.wpfm-slider');
          field_slider.find('.wpfm_check').next('.next-slide').next(".wpfm_slide_toggle").slideToggle("slow");
      });
      
    // wpfm fields remove function
    function wpfm_remove_fields() {
        $(".wpfm-remove-field").click(function(e){
            
            e.preventDefault();
            var $this_fields = $(this);
            e.preventDefault();
            swal({
                 title: "Are you sure?",
                 type: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#DD6B55 ",
                 cancelButtonColor: "#DD6B55",
                 confirmButtonText: "Yes",
                 cancelButtonText: "No",
                 closeOnConfirm: true
             }
             , function (isConfirm) {
                if (!isConfirm) return;
                  $this_fields.parent().parent().remove();
             }
             );
         });
    }
    
    function  wpfm_add_option_set_index(add_opt_selector, opt_field_no, field_type , opt_no ){
        var field_name = 'wpfm['+opt_field_no+']['+field_type+']['+$(add_opt_selector).attr('data-metatype')+']['+opt_no+']';
        $(add_opt_selector).attr('name', field_name);
    }

    // add multiple options function
   $(document).on('click', '.btn-done', function(e){
       e.preventDefault();
       var button = $('.btn-done');
       var clone_close = $(this).closest('.wpfm-slider');
       var field_type12 = clone_close.find('.wpfm_copy_field').attr('data-copy-field_id');
       var selected_field_type = clone_close.find('.wpfm-pd').data('table-id');
       var controlForm = $(this).closest('.controls');
       var final_ind = controlForm.data('opt-ex');

       var field_type = $('#meta-field-type').val();
       var field_index_pt = $('#meta-field-index').val();
       //var controlForm = $(this).find('.controls');
       var  currentEntry = $('.entry:last').clone();

       var opt_no2 = parseInt(clone_close.find('#meta-opt-index').val());
       clone_close.find('#meta-opt-index').val( opt_no2 + 1 );
       // console.log(opt_no2);
       var add_opt_selector = currentEntry.find('.wpfm-meta-field-opt');  
       var newEntry = currentEntry.find('.hrlo').end().appendTo(controlForm);
       wpfm_add_option_set_index(add_opt_selector, final_ind, field_type12 , opt_no2, selected_field_type );
       
      newEntry.find('input').val('');
      controlForm.find('.entry:not(:last) .btn-done')
          .removeClass('btn-done').addClass('btn-remove')
          .removeClass('btn-success').addClass('btn-danger')
          .html('<i class="fa fa-minus" aria-hidden="true"></i>');
   }).on('click', '.btn-remove', function(e)
       {
       $(this).parents('.entry:first').remove();
       e.preventDefault();
       return false;
   });


   function  wpfm_add_option_set_index(add_opt_selector, opt_field_no, field_type , opt_no , selected_field_type){
       var field_name = 'wpfm['+opt_field_no+']['+selected_field_type+']['+$(add_opt_selector).attr('data-metatype')+']['+opt_no+']';
       $(add_opt_selector).attr('name', field_name);
   }

    // first time all specific role select hide()
    $('table [data-meta-id="specific_roles"]').hide();
    // then check all permission if specific role select then show this selctbox
    $( "table [data-metatype='permission']" ).each(function() {
        var permission = $(this).val();
        if (permission == 'specific_role') {

            $(this).closest("[data-meta-id='permission']").siblings("[data-meta-id='specific_roles']").show();
        };
        
    });
    // add select2 on privacy check 
    $('select.privacy_check').select2({
        placeholder: 'Select Group',
    });

    $(document).on('click', '[data-meta-id="permission"] select', function(event) {
       event.preventDefault();
       var $this = $(this);
       var $this_privacy = $this.closest('.wpfm-slider');
       var role_value = $(this).val();
       $this_privacy.find('[data-meta-id="permission"] select').each(function(i, meta_field){
               // console.log($(this).val());
               if (role_value == 'specific_role') {
                  $this_privacy.find('[data-meta-id="specific_roles"]').show();
               }else if(role_value == 'everyone' || role_value == 'member'){
                  $this_privacy.find('[data-meta-id="specific_roles"]').hide();
               }
       });
    });
    
    /* ================== auto generate data name field ============= */
    $("#wpfm-fields-wrapper").on('keyup', 'input[data-metatype="title"]', function(){
    
    	var dataname =$(this).closest('table').find('input[data-metatype="data_name"]');
    	var field_id = $(this).val().replace(/[^A-Z0-9]/ig, "_");
    	field_id = field_id.toLowerCase();
   		$(dataname).val( field_id );
    });
});