<div class="nirweb_ticket_base">
<?php
include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET . 'func_status_and_priority.php';
include_once NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET . 'func_department.php';
include_once NIRWEB_SUPPORT_INC_USER_FUNCTIONS_TICKET . 'func_faq.php';
$faq = nirweb_ticket_get_all_faq_user();
 ?>
<div class="top_box_send_ticket">
        <div class="head_send_ticket_wpyar">
             <h4 class="wpyar-ticket"><?php echo __('New Ticket', 'nirweb-support') ?></h4>
    <?php
        if(is_plugin_active('wpyar-dashboard_user/wpyar-dashboard_user.php' )){
             $wpyarud = get_option('wpyarud_prefix_my_options');
              $page_name =$wpyarud['page_wpyarud_plugin'];  
            $page =get_bloginfo('url' ).'/'.$page_name.'?page=wpyar-ticket';
        }else{
            if( wpyar_ticket['select_page_ticket']){
               $page = esc_url( get_page_link( wpyar_ticket['select_page_ticket'] ) );
            }else{
               $page = get_permalink( get_option('woocommerce_myaccount_page_id') ).'wpyar-ticket/';
            }
        }
    ?>
  <a  href=" <?php echo $page; ?> "  class="btn btn_back_wpyt"><?php echo __('Back To List Tickets', 'nirweb-support')  ?></a>
    </div>
    <div class="content_ticket_send" >
        <?php echo wpautop(wpyar_ticket['text_top_send_mail']) ?>
    </div>
        <div class="list_of_faq_wpyar">
            <ul>
                <?php    foreach($faq as $key=>$value):   ?>
                     <li>
                    <p  class="li_list_of_faq_wpyar">
                                            <i></i>
                    <span class="number_faq_wpyar"><?php echo $key+1 ?>.</span>
                    <span class="title_faq_wpyar"><?php echo $value->question ?></span>
                    </p>
                    <div class="content_faq_wpyar"><?php echo $value->answer ?> </div>
                </li>           
                <?php endforeach; ?>
            </ul>
        </div>
    <div class="not_found_answer" <?php if(!$faq){ echo "style='display:none !important'";} ?> >
        <span><?php echo __('I didn\'t find the answer to my question', 'nirweb-support')  ?></span>
    </div>
</div>
<!-- Start Form-->
<form class="form__global__ticket_new shadow__wpys" id="send_ticket_form" method="post"
      enctype="multipart/form-data"  <?php if($faq){ echo "style='display:none'";} ?> >
    <!-- subject row-->
    <div class="ibenic_upload_message"></div>
     <div class="row_nirweb_ticket_send">
         <div class="w-50">
            <label for="nirweb_ticket_frm_subject_send_ticket_user"><?php echo __('Enter subject please *', 'nirweb-support')  ?></label>
            <input type="text" id="nirweb_ticket_frm_subject_send_ticket_user" name="nirweb_ticket_frm_subject_send_ticket_user">
        </div>
    <!-- DepartMent-->
        <div class="w-50">
             <div class="department_form_user_send">
            <label for="nirweb_ticket_frm_department_send_ticket_user"><?php echo __('Select department please *', 'nirweb-support')  ?></label>
            <div class="select_custom_wpyar">
            <div class="custom_input_wpyar_send_ticket" id="nirweb_ticket_frm_department_send_ticket_user" data-id="-1" data-user="0" >
            <?php echo __('Select department', 'nirweb-support')  ?>
            </div>
                 <i class="fal fa-angle-down"></i>
                 <ul>
                        <?php
                $departments = nirweb_ticket_ticket_get_list_department();
                foreach ($departments as $department): ?>
                    <li data-user="<?php echo $department->support_id ?>" 
                     data-id="<?php echo $department->department_id ?>"><?php echo $department->name ?></li>
                <?php endforeach; ?>
                 </ul>
            </div>
        </div>
        </div>
    </div>
    <!--  priority And Product -->
    <div class="row_nirweb_ticket_send">
        <div class="w-50">
             <label for="nirweb_ticket_frm_priority_send_ticket_user"><?php echo __('Priority *', 'nirweb-support')?></label>
            <div class="select_custom_wpyar">
            <div class="custom_input_wpyar_send_ticket" id="nirweb_ticket_frm_priority_send_ticket_user" data-id="-1" >
                <?php echo __('Select priority', 'nirweb-support')?>
            </div>
                 <i class="fal fa-angle-down"></i>
                 <ul>
                 <li data-id="1"> <?php echo __('low', 'nirweb-support')?></li>
                <li data-id="2"> <?php echo __('normal', 'nirweb-support')?></li>
                <li data-id="3"> <?php echo __('necessary', 'nirweb-support')?></li>
                 </ul>
            </div>
        </div>
            <div class="w-50">
            <?php if(is_plugin_active('woocommerce/woocommerce.php')){ ?>
        <label for="product_user_wpyar_tixket">
        <?php echo __('Product', 'nirweb-support')?>
            <?php if( @wpyar_ticket['require_procut_user_wpyar']=='1'){echo ' * '; }  ?>
            </label>
              <div class="select_custom_wpyar">
            <div class="custom_input_wpyar_send_ticket" id="product_user_wpyar_tixket" data-id="-1" >
            <?php echo __('Select Product', 'nirweb-support')?>    
            </div>
                 <i class="fal fa-angle-down"></i>
                 <ul>
               <?php  $customer_orders = get_posts( array(
                    'numberposts' => -1,
                    'meta_key'    => '_customer_user',
                    'meta_value'  => get_current_user_id(),
                    'post_type'   => wc_get_order_types(),
                    'post_status' => array_keys( wc_get_order_statuses() ),
                ) );
                if ($customer_orders){
                $product_ids = array();
                foreach ($customer_orders as $customer_order) {
                    $order = wc_get_order($customer_order->ID);
                    $items = $order->get_items();
                    foreach ($items as $item) {
                        $product_id = $item->get_product_id();
                        $product_name = $item->get_name();
                        ?>
                        <li data-id="<?php echo $product_id ?>">
                        <?php echo $product_name ?>
                        </li>
                        <?php }  
                    }
                } ?>
                 </ul>
            </div>
     <?php } else if (is_plugin_active('easy-digital-downloads/easy-digital-downloads.php')){ ?>
        <label>        <?php echo __('Product', 'nirweb-support')?></label>
        <div class="select_custom_wpyar">
        <div class="custom_input_wpyar_send_ticket" id="product_user_wpyar_tixket" data-id="-1" >
        <?php echo __('Select Product', 'nirweb-support')?>    
            </div>
              <i class="fal fa-angle-down"></i>
            <ul>';
    <?php $rep = edd_get_users_purchased_products( $user = get_current_user_id(), $status = 'complete' );
            foreach ($rep as $row):
            echo'<li data-id="'.$row->ID.'">'.$row->post_title.'</option>';
                endforeach;
                echo'</ul></div>';
        } ?>
            </div>
    </div>
     <!--content message row-->
    <div class="row_nirweb_ticket_send">
        <div class="w-100">
                <label><?php echo __('Enter Message', 'nirweb-support'); ?></label>
        <textarea id="nirweb_ticket_frm_content_send_ticket_user" name="nirweb_ticket_frm_content_send_ticket_user" placeholder="<?php echo __('Enter Message please', 'nirweb-support'); ?>"></textarea>   
        </div>
    </div>
    <!------ Upload File ----->
    <div class="row_nirweb_ticket_send wpyar_upfile_base">
        <div class="upfile_wpyartick">
        <label for="main_image" class="label_main_image">
             <span class="remove_file_by_user"><i class="fal fa-times-circle"></i></span>  
            <i class="fal fa-arrow-up upicon" style="font-size: 30px;margin-bottom: 10px;"></i>
            <span class="text_label_main_image"> <?php echo __('Attachment File', 'nirweb-support') ?></span>
         </label>
        <input type="file" name="main_image" id="main_image" accept="<?php echo wpyar_ticket['mojaz_file_upload_user_wpyar'] ?>">
            </div>
            </div>
   <!-- buttons row-->
    <div class="send_reset_form">
        <div class="base_loarder">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
                <p><?php echo __('Send Request ...', 'nirweb-support') ?></p>
         </div>
            <span class="btn btn-warning text-white rest_form__wpys" name="nirweb_ticket_frm_user_restart_ticket">  
                 <?php echo __('Reset Form', 'nirweb-support') ?>
            </span>
    <button data-fileurl="" type="submit" class="btn btn-primary text-white" name="nirweb_ticket_frm_user_send_ticket" id="nirweb_ticket_frm_user_send_ticket">
    <?php echo __('Send Ticket', 'nirweb-support') ?>
    </button>
    <p class="stasus_send_wpyt"></p>
    </div>
</form>
<?php $accsses_file = str_replace('.','',trim( wpyar_ticket['mojaz_file_upload_user_wpyar'])); 
  $accsses_file = explode(",",trim($accsses_file));?>
 <!-- End form-->
  <script>
 jQuery('body').on('change','#main_image',function () {
     console.log(this.value.match(/\.(.+)$/)[1]);
  var ext = this.value.match(/\.(.+)$/)[1];
  switch (ext) {
      <?php foreach ($accsses_file as $file):?>
      case "<?php echo $file ?>":
           break;
          <?php endforeach; ?>
          
      default:
      Swal.fire( wpyarticket.nvalid_file, "", "error");
           this.value = '';
  }
 
});
//--------------------  Request Send ticket
jQuery('body').on('click','#nirweb_ticket_frm_user_send_ticket',function(e){
e.preventDefault();
jQuery('.base_loarder').css('display','flex');
    var subject = jQuery('#nirweb_ticket_frm_subject_send_ticket_user').val()
    var department = jQuery('#nirweb_ticket_frm_department_send_ticket_user').attr('data-id');
    var dep_name = jQuery('#nirweb_ticket_frm_department_send_ticket_user').text();
    var resived_id = jQuery('#nirweb_ticket_frm_department_send_ticket_user ').attr('data-user');
    var content = jQuery('#nirweb_ticket_frm_content_send_ticket_user').val();
    var priority = jQuery('#nirweb_ticket_frm_priority_send_ticket_user').attr('data-id');
    var priority_name = jQuery('#nirweb_ticket_frm_priority_send_ticket_user').text();
    var product = jQuery('#product_user_wpyar_tixket').attr('data-id');
    var formData = new FormData();
     formData.append('updoc', jQuery('input[type=file]')[0].files[0]);
     formData.append('subject',subject),
      formData.append('department',department),
     formData.append('dep_name',dep_name),
     formData.append('resived_id',resived_id),
     formData.append('content',content),
     formData.append('priority',priority),
     formData.append('priority_name',priority_name),
     formData.append('product',product);
  var image_select = jQuery('#main_image').val();
    if (image_select) {
              var   size_file = jQuery('input[type=file]')[0].files[0]['size'];
             var ac_size = <?php echo wpyar_ticket['size_of_file_wpyartik']; ?>000000;
             if(size_file >= ac_size){
                jQuery('.base_loarder').css('display','none');
                jQuery('.text_upload').css('display','none')
                    Swal.fire(wpyarticket.max_size_file, "", "error");
                         return false  
             }
    }
     <?php if( @wpyar_ticket['require_procut_user_wpyar']=='1'){
         $shart = 'subject && department !=-1 && priority !=-1  && product !=-1  && content';
     }else{
         $shart= 'subject && department !=-1 && priority !=-1  && content';
     } ?>
    if (<?php echo $shart; ?>)
    {
     formData.append('action', "user_send_tiket");
        jQuery.ajax({
            url: wpyarticket.ajax_url,
            type: "POST",
            data:formData,cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                if(response =='error_valid_type'){
                    Swal.fire( wpyarticket.nvalid_file, "", "error");
                    jQuery('.base_loarder').css('display','none');
                    jQuery('.text_label_main_image').html('<?php echo __('Attachment File', 'nirweb-support') ?>');
                    return false; 
                }
                jQuery('.base_loarder').css('display','none');
                Swal.fire("<?php echo __('Send Ticket Success', 'nirweb-support') ?>", "", "success");
                jQuery('#send_ticket_form').trigger('reset');
                jQuery('.wpyar_upfile_base').html(response);
                jQuery('.remove_file_by_user').hide();
                jQuery('.text_label_main_image').html('<?php echo __('Attachment File', 'nirweb-support') ?>');
                jQuery('#attach_url_file').attr('data-id','');
                return false;
            },
        })
    } else{
        jQuery('.base_loarder').css('display','none');
        Swal.fire( wpyarticket.nes_field, "", "error");
        return false;
    }
})
</script>
</div>
