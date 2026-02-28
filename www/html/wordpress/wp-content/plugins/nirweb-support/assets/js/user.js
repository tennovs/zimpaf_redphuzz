jQuery(document).ready(function () {
      //---------------- Ajax Filter Ticket

    jQuery('body').on('click', '.filter_ajax_wpytu', function () {
        let status_id = jQuery('#select_wpytu_status option:selected').val()
        let asns = jQuery('#selcet_filter_ans option:selected').val()
        jQuery('.lds-dual-ring').css('display', 'flex')
        jQuery.ajax({
            url: wpyarticket.ajax_url,
            type: "POST",
            data: {
                status_id,
                asns,
                action: "filter_ajax_ticket"
            },
            success: function (data) {
                 
                jQuery('.wpyt_table tbody').html(data);
                jQuery('.lds-dual-ring').css('display', 'none')

            },
        })
    })


 //---------------- Search In Table Ticket
       jQuery("#user_search_in_ticket_wpyar").on("keyup", function() {
        var value = jQuery(this).val();
                if(value){
                jQuery.ajax({
                    url: wpyarticket.ajax_url,
                    type: "post",
                    data: {
                        value: value,
                        action: "nirweb_ticket_user_search",
                                },
                    success: function (response) {
                       jQuery('.search_war_wpyar_ticket ul').slideDown(150).html(response)
                        return false;
                    },
            
                })
            }else{
                jQuery('.search_war_wpyar_ticket ul').hide()      
            }
        });

//---------------- Rest Form Send Ticket
jQuery('body').on('click','.rest_form__wpys',function(e){
    swal({
        title: wpyarticket.reset_form_title,
        text: wpyarticket.reset_form_subtitle,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal( wpyarticket.reset_form_success, "", "success");
                jQuery('#send_ticket_form').trigger('reset');
                jQuery('#attach_url_file').val('');
                return false;
            }
        });
})

 

//------------- Remove Upload By user

jQuery('body').on('click','.remove_file_by_user',function(e){
                 e.preventDefault();
                 e.stopPropagation();
jQuery('.wpyar_upfile_base').html(`<div class="upfile_wpyartick">
             
        <label for="main_image" class="label_main_image">
             <span class="remove_file_by_user"><i class="fal fa-times-circle"></i></span>  
            <i class="fal fa-arrow-up upicon" style="font-size: 30px;margin-bottom: 10px;"></i>
            <span class="text_label_main_image">${wpyarticket.attach_file}</span>
   
        </label>

        <input type="file" name="main_image" id="main_image" accept=".png,.jpg,.jpeg">
            
            </div>`)

})

//------------------- Send Ticket ----------------------

    //------------------- FAQ  --- Start
    
    jQuery('body').on('click','.li_list_of_faq_wpyar',function(e){
        jQuery(this).parent('li').toggleClass('open');
        jQuery(this).parent('li').find('.content_faq_wpyar').slideToggle(150);
    })
    
    jQuery('body').on('click','.not_found_answer span',function(e){
            jQuery('#send_ticket_form').slideDown(250);
            jQuery('.list_of_faq_wpyar').remove();
            jQuery('.not_found_answer').remove();
            
    })
    
    //------------------- FAQ  --- End
    
    //------------- Start Custom Select For send Ticket
    
    jQuery('.select_custom_wpyar').click(function(e){
                  e.stopPropagation();
        jQuery('.select_custom_wpyar').find('i').removeClass('top');
        jQuery('.select_custom_wpyar').find('ul').fadeOut();
                  e.stopPropagation();
        jQuery(this).children('i').toggleClass('top')
        jQuery(this).children('ul').fadeToggle();
          e.stopPropagation();
    })
    
    //------ Preview Image Attach file
    
    
    function readURL(input) {
 
        var formData = new FormData();
        formData.append('updoc', jQuery('input[type=file]')[0].files[0]);
        jQuery('.text_label_main_image').html(input.files[0]['name'])
        jQuery('.upicon').remove()
       
     }
  jQuery('body').on('change','#main_image',function(e){
        jQuery('.remove_file_by_user').fadeIn();
      readURL(this);
    });
    
    //-------------------- End Custom Select For send Ticket
    
    jQuery('.select_custom_wpyar ul li').click(function(e){
        var text_li = jQuery(this).text()
        var date_id_li = jQuery(this).attr('data-id')
        var data_user_li = jQuery(this).attr('data-user')
        var tar_get = jQuery(this).parents('.select_custom_wpyar').find('.custom_input_wpyar_send_ticket')
        jQuery(tar_get).text(text_li)
        jQuery(tar_get).attr('data-id',date_id_li)
        jQuery(tar_get).attr('data-user',data_user_li)
            
        jQuery(this).parents('.select_custom_wpyar').find('i').removeClass('top');
        jQuery(this).parents('ul').fadeOut();
                 e.preventDefault();
                 e.stopPropagation();
    })
    
    
    //------------- End Custom Select For send Ticket
    
    
//---------------- Remove Custom List After Click in body an Other Place    
    jQuery('body').click(function(e){
         jQuery('.select_custom_wpyar').children('ul').fadeOut();
         jQuery('.select_custom_wpyar').find('i').removeClass('top');
        
    })
    
//----------------- Filter List Ticket    
 jQuery('body').on('click','.col_box_status_ticket_wpyar',function(event){
    jQuery('.ajax_result').html('<h3 style="color:red;font-weight: 400;text-align: center;font-size: 18px;">'+wpyarticket.recv_info+'</h3>')

    var status = jQuery(this).attr('id');
    jQuery.ajax({
        url: wpyarticket.ajax_url,
        type: "post",
        data: {
            status,
            action: "filtter_ticket_status",
                    },
        success: function (response) {
           jQuery('.ajax_result').html(response)
            return false;
        },

    })
 })


    });/////------------------ End document ready
