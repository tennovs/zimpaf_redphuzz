<?php
include NIRWEB_SUPPORT_INC_ADMIN_FUNCTIONS_TICKET.'func_upload_file.php';
$files=get_list_user_files();
?>
<div class="base_load_file">

<h2><?php echo __('List of uploaded files', 'nirweb-support'); ?></h2>

<form method="post" id="list_all_user_files" name="list_all_user_files[]">

<table class="wpyt_table">
    <thead>
    <tr>
        <th style="width: 45px"></th>
         <th style="width: 80px"><?php echo __('Image File', 'nirweb-support'); ?></th>
        <th><?php echo __('Link File', 'nirweb-support'); ?></th>
    </tr>
    </thead>
    <tbody>

    <?php
    
    foreach ($files[0] as $row):
 
     ?>
    <tr>
        <th><input type="checkbox" id="frm_check_items" name="frm_check_items[]" 
        value="<?php echo $row->id ?>" data-file="<?php echo $row->file_id ?>"></th>
        <th><img src="<?php echo $row->url_file ?>" width="50" height="50"></th>
        <th><a href="<?php echo $row->url_file ?>" target="_blank"><?php echo __('Show Image', 'nirweb-support'); ?></a></th>
    </tr>
<?php endforeach ?>
 
    </tbody>
    <tfoot>
    <tr>
    <th></th>
         <th><?php echo __('Image File', 'nirweb-support'); ?></th>
        <th><?php echo __('Link File', 'nirweb-support'); ?></th>
    </tr>
    </tfoot>
</table>
<div class="remove_wpyt font-base" >
    <button type="submit" class="danger" id="frm_btn_delete_files_users">
    <?php echo __('Delete', 'nirweb-support'); ?>
    </button>
</div>
</form>


<div class="nirweb_ticket_pagination">

<?php echo $files[1] ?>

</div>

</div>