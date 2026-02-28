<?php
 include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_FAQ.php';
 if(isset($_POST['submit_new_faq']))
{
    nirweb_ticket_save_new_faq();
}
$FAQS=nirweb_ticket_get_all_faq();
?>

<h1 class="title_page_wpyt"><?php echo __('FAQ', 'nirweb-support') ?></h1>
<div class="wapper flex">
    <div class="right_FAQ" >
<form action="" id="form_Add_faq"  method="post">
        <div class="question__faq flex flexd-cul" >
            <label class="w-100"><b><?php echo __('Question', 'nirweb-support') ?></b></label>
            <input  name="nirweb_ticket_frm_subject_faq_ticket" id="nirweb_ticket_frm_subject_faq_ticket" class="wpyt_input" placeholder="<?php echo __('Enter Question', 'nirweb-support') ?>">
        </div>


    <div class="answer__faq flex flexd-cul" >
            <label class="w-100"><b><?php echo __('Answer', 'nirweb-support') ?></b></label>

        <?php
        $content = '';
        $editor_id = 'nirweb_ticket_frm_faq_ticket';
        wp_editor($content, $editor_id);
        ?>

        </div>

        <button  name="submit_new_faq" id="submit_new_faq" class="button button-primary">
        <?php echo __('Add Question', 'nirweb-support') ?>
        </button>

</form>

    </div>

    <div class="left_FAQ">
    <ul class="list__question_faq">
<?php if(count($FAQS)>=1)
    foreach ($FAQS as $faq):
     echo '
    <li class="flex w-100">
    <span class="dashicons dashicons-trash remove_faq danger" data-id="'.$faq->id.'"></span>
    <div class="li_list_question  ">
                <div class="question_wpy_faq flex">
                    <span class="soal_name_wpyt">'.$faq->question.'</span>
                    <span class="arrow_wpyt cret flex aline-c"></span>
                </div>
                <div class="answer_wpys_faq" >
                    <p>'.$faq->answer.'
                    </p>
                </div>

            </div>
            </li>
       ';
    endforeach;
else
    echo  __('not found', 'nirweb-support');
?>

</ul>
    </div>


</div>
