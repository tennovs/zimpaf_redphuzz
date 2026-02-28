<?php include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_department.php';
    $users=nirweb_ticket_ticket_get_supporter_department();
    $departments=nirweb_ticket_ticket_get_list_department();
?>
 
<h1 class="title_page_wpyt"><?php echo __('Departments', 'nirweb-support') ?></h1>
<div class="wapper flex">
    <div class="right_FAQ" >
        <form method="post">
            <div class="question__faq flex flexd-cul" >
                <label class="w-100"><b><?php echo __('Department Name', 'nirweb-support') ?></b></label>
                <input id="nirweb_ticket_name_department" name="nirweb_ticket_name_department" class="wpyt_input"  placeholder="<?php echo __('Department Name', 'nirweb-support') ?>">
            </div>
            <div class="question__faq flex flexd-cul" >
                <label class="w-100"><b><?php echo __('Support', 'nirweb-support') ?></b></label>
                <?php
                echo '<select id="nirweb_ticket_support_department" name="nirweb_ticket_support_department">';
                echo '<option value="-1">'.__('Select Support User', 'nirweb-support').'</option>';
                foreach ($users as $user) { ?>
                    <option data-mail="<?php echo $user->user_email ?>"  value="<?php echo $user->ID ?>"><?php echo $user->display_name ?></option>
                <?php }
                echo '</select>';
                ?>
            </div>
            <button name="submit_new_department" id="submit_new_department" class="button button-primary"><?php echo __('Add Department', 'nirweb-support') ?></button>
        </form>
    </div>
    <div class="left_FAQ">
<table class="wp-list-table widefat striped">
                <thead>
            <tr>
                <th></th>
                <th><?php echo __('Department Name', 'nirweb-support') ?></th>
                <th><?php echo __('Support', 'nirweb-support') ?></th>
                <th><?php echo __('Edit', 'nirweb-support') ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                
                <th></th>
                <th><?php echo __('Department Name', 'nirweb-support') ?></th>
                <th><?php echo __('Support', 'nirweb-support') ?></th>
                <th><?php echo __('Edit', 'nirweb-support') ?></th>
            </tr>
            </tfoot>
            <tbody>
            <?php foreach ($departments as $department): ?>
            <tr style="border: solid 1px #ccc" class="row_dep">
            <th><input type="checkbox" id="frm_check_items" name="frm_check_items[]" value="<?php echo $department->department_id ?>"></th>
            <th  class="dep_name"  data-id="<?php echo $department->department_id ?>" ><?php echo $department->name ?></th>
            <th class="name_user"  data-user_id="<?php echo $department->support_id?>" ><?php echo $department->display_name ?></th>
            <th><a class="edit_ticket_wpys edit_dep_wpys">
            <span class="dashicons dashicons-edit"></span></a></a></th>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="remove_wpyt font-base" >
            <button class="button button-primary" id="frm_btn_delete_dep">
            <?php echo __('Delete', 'nirweb-support') ?>
            </button>
        </div>
    </div>
</div>
