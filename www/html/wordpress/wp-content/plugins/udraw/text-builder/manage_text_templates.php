<?php
if (!is_user_logged_in() || !current_user_can('read_udraw_templates')) {
    exit;
}

global $wpdb, $charset_collate;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(UDRAW_PLUGIN_DIR . '/classes/tables/uDrawTextTemplatesTable.class.php');
$text_templates_table = new uDraw_Text_Templates();

// uDraw Text Templates Table
$sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name TEXT COLLATE utf8_general_ci NOT NULL,
        json LONGTEXT COLLATE utf8_general_ci NOT NULL,
        preview TEXT COLLATE utf8_general_ci NULL,
        tags TEXT COLLATE utf8_general_ci NULL,
        create_user TEXT COLLATE utf8_general_ci NOT NULL,
        create_date DATETIME COLLATE utf8_general_ci NOT NULL,
        modify_date DATETIME COLLATE utf8_general_ci NULL,
        category LONGTEXT COLLATE utf8_general_ci NULL,
        public_key VARCHAR(64) COLLATE utf8_general_ci NULL,
        PRIMARY KEY  (ID)";
$sql = "CREATE TABLE " . $wpdb->prefix . "udraw_text_templates ($sql) $charset_collate;";
dbDelta($sql);

// uDraw Text Templates Category Table                
$sql = "ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        category_name LONGTEXT COLLATE utf8_general_ci NOT NULL,
        parent_id BIGINT(20) NULL,
        PRIMARY KEY  (ID)";                
$sql = "CREATE TABLE " . $wpdb->prefix . "udraw_text_templates_category ($sql) $charset_collate;";                
dbDelta($sql);

if (isset($_REQUEST['udraw_action']) && strlen($_REQUEST['udraw_action']) > 0) {
    $action = $_REQUEST['udraw_action'];
    ?>
        <div id="message" class="updated below-h2"><p><?php _e("Template $action." , 'udraw'); ?></p></div>
    <?php
}
?>
<h1>Manage Text Templates</h1>
<a href="admin.php?page=udraw_edit_text_template" class="button button-primary">Add New Text Template</a>
<div style="margin-top: 10px;">
    <?php if (current_user_can('edit_udraw_templates')) { ?>
    <div id="manage-category-btns">
        <a href='#' id='add-category-btn' class='button' style='margin-top: 5px;'>Add Category</a>
        <a href='#' id='edit-category-btn' class='button' style='margin-top: 5px;'>Edit Category</a>
        <a href='#' id='remove-category-btn' class='button' style='margin-top: 5px;'>Remove Category</a>
    </div>
    <?php } ?>

    <div id='add-category-text-container' style='display: none;'>
        <span id="text">Add</span>
        <div id="edit-category-container" style="display: none;">
            <select id="select-original-category" style="width: 150px;">
                <option value="" disabled selected>Select One</option>
                <?php
                    $text_templates_table->buildCategorySelectOptions('0');
                ?>  
            </select>
            with name 
        </div>
        <input type='text' id='add-category-text' placeholder="Category Name"/>
        as a 
        <select id="select-new-category-type">
            <option value="0">Main Category</option>
            <!--<option value="1">Sub Category</option>-->
        </select>
        <div id="subcategory-container" style="display: none;">
            to
            <select id="select-parent-category" style="width: 150px;">
                <option value="" disabled selected>Select One</option>
                <?php
                    $text_templates_table->buildCategorySelectOptions('0');
                ?>  
            </select>
        </div>
        <a href='#' id='add-category' class='button button-default' onclick='javascript: addCategory();' style="display: none;">Add</a>
        <a href='#' id='update-category' class='button button-default' onclick='javascript: updateCategory();' style="display: none;">Edit</a>
        <a href="#" class="button button-default category-cancel">Cancel</a>
        <br/>
        Please note that any invalid characters will automatically be removed.
    </div>
    <span id='add-category-error' style='display:none;'><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i>An error had occured.  Please ensure that this name is not already in use.</span>
    <span id='add-category-success' style='display:none;'><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>Successfully added!.</span>
    <span id='update-category-success' style='display:none;'><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>Successfully updated!.</span>
    
    <div id='remove-category-container' style='display: none;'>
        <select id="select-remove-category" style="width: 200px;">
            <option value="" disabled selected></option>
            <?php 
            $text_templates_table->buildCategorySelectOptions('0');
            ?>
        </select>
        <a href='#' id='remove-category-select-btn' class='button button-default'>Remove</a>
        <a href="#" class="button button-default category-cancel">Cancel</a>
    </div>
    <span id='remove-category-error' style='display: none;'><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i>An error had occured.</span>
    <span id='remove-category-success' style='display: none;'><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>Successfully removed!</span>
</div>

<?php
    //$text_templates_table = new uDraw_Text_Templates();
    $text_templates_table->prepare_items();
?>

<form method="POST" action="?page=<?php echo $_REQUEST['page']; ?>">
    <?php $text_templates_table->display(); ?>
</form>

<style>
    td.preview {
        float: none;
    }
    a.save_tags {
        margin-top:10px; 
        float:right;
    }
    div.display_tags,
    div.update_tags,
    td.column-tags a.update_tags {
        display: none;
    }
    div.display_tags.active,
    div.update_tags.active,
    td.column-tags a.update_tags.active {
        display: block;
    }
</style>
<script>
    function save_tags(template_id) {
        let tags = jQuery(`input.template-tags[data-template_id="${template_id}"]`).val();
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                'template_id': template_id,
                'action': 'udraw_text_templates_update_tags',
                'tags': tags
            },
            success: function(response) {
                let tags = new Array();
                if (response !== null) {
                    tags = response.split(',');
                }
                let _html = tags.join(' | ');
                
                jQuery(`div.display_tags[data-template_id="${template_id}"]`).addClass('active');
                jQuery(`a.update_tags[data-template_id="${template_id}"]`).addClass('active');
                jQuery(`span.tags_span[data-template_id="${template_id}"]`).html(_html);
                jQuery(`div.update_tags[data-template_id="${template_id}"]`).removeClass('active');
            }
        });        

    }

    function addCategory() {
        jQuery('#add-category-error').hide();
        var categoryName = jQuery('#add-category-text').val().replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\=|\+|\\|\||\]|\}|\[|\{|\?|\/|\.|\>|\,|\<|\;|\:|\"/gi, '');
        var parent_id = 0;
        if (jQuery('#select-new-category-type').val() == 1) {
            parent_id = jQuery('#select-parent-category').val();
        }
        if (categoryName.length > 0 && parent_id != null) {
            jQuery.ajax({
                method: 'GET',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    'action' : 'udraw_add_text_templates_category',
                    'category_name' : categoryName,
                    'parent_id' : parent_id
                },
                success: function (response) {
                    jQuery('#add-category-success').css('display, inline-block');
                    location.reload(true);
                },
                error: function (error) {
                    jQuery('#add-category-error').css('display', 'inline-block');
                }
            });
        } else {
            jQuery('#add-category-error').css('display', 'inline-block');
        }
    }

    function removeCategory(category) {
        jQuery('#remove-category-error').hide();
        jQuery.ajax({
            method: 'GET',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'udraw_remove_text_templates_category',
                'category': category
            },
            success: function (response) {
                jQuery('#remove-category-success').css('display', 'inline-block');
                location.reload(true);
            },
            error: function (error) {
                jQuery('#remove-category-error').css('display', 'inline-block');
            }
        });
    }

    function assignCategory (publickey, category, caller) {
        jQuery.ajax({
            method: 'GET',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'udraw_assign_text_templates',
                'public_key': publickey,
                'category' : category
            },
            success: function (response) {
                console.log(caller);
                caller.parent().css('background','#C8FFCA');
                setTimeout(function(){
                    caller.parent().css({
                        'background': 'rgba(255,255,255,0)',
                        'transition': 'all 1s'
                    });
                }, 1000)
            },
            error: function (error) {
                console.log(error);
                jQuery('#error-message').show();
            }
        });
    }

    function updateCategory () {
        jQuery('#add-category-error').hide();
        var category_id = jQuery('#select-original-category').val();
        var categoryName = jQuery('#add-category-text').val().replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\=|\+|\\|\||\]|\}|\[|\{|\?|\/|\.|\>|\,|\<|\;|\:|\"/gi, '');
        var parent_id = 0;
        if (jQuery('#select-new-category-type').val() == 1) {
            parent_id = jQuery('#select-parent-category').val();
        }
        if (categoryName.length > 0 && parent_id != null && category_id != null) {
            jQuery.ajax({
                method: 'GET',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    'action' : 'udraw_update_text_templates_category',
                    'category_name' : categoryName,
                    'parent_id' : parent_id,
                    'category_id' : category_id
                },
                success: function (response) {
                    if (response) {
                        jQuery('#update-category-success').css('display, inline-block');
                        location.reload(true);
                    } else {
                        jQuery('#add-category-error').css('display', 'inline-block');
                    }
                },
                error: function (error) {
                    
                }
            });
        } else {
            jQuery('#add-category-error').css('display', 'inline-block');
        }
    }
        
    jQuery(document).ready(function($){
        var categoryArray = <?php echo json_encode($text_templates_table->_text_templates_category_array) ?>;
        $('#add-category-btn').click(function(){
            $('#add-category-text-container span#text').text('Add');
            $('#add-category-text-container').css('display', 'inline-block');
            $('#manage-category-btns, #edit-category-container, #remove-category-error, #edit-category').hide();
            $('#add-category').show();
        });
        
        $('#edit-category-btn').click(function(){
            $('#add-category-text-container span#text').text('Edit');
            $('#add-category-text-container, #edit-category-container').css('display', 'inline-block');
            $('#manage-category-btns, #remove-category-error, #add-category').hide();
            $('#update-category').show();
        });

        $('.category-cancel').click(function(){
            $('#add-category-text-container, #remove-category-container, #edit-category-container').hide();
            $('#manage-category-btns').show();
            //Reset all options when clicking cancel button
            $('#add-category-text').val('');
            $('#select-new-category-type').val(0).trigger('change');
            $('#select-parent-category, #select-remove-category, #select-original-category').val('').trigger('change');
            for (var j = 0; j < categoryArray.length; j++) {
                $('#select-parent-category option[value='+parseInt(categoryArray[j].ID)+']').attr('disabled',false);
            }
            $('#select-parent-category').select2();
        });

        $('#remove-category-select-btn').click(function(){
            var category = $('#select-remove-category').val();
            removeCategory(category);
        });
        
        $('#remove-category-btn').click(function(){
            jQuery("#remove-category-container").show();
            jQuery("#manage-category-btns").hide();
        });
        $('#select-remove-category').select2({
            placeholder: "Select one"
        });
        
        $('.select-text-template-category').select2({
            placeholder: "Select one"
        }).on('change', function (){
            $(this).parent().css('background','#FBC0C0');
            var id = $(this).attr('id').replace('select-text-template-category-id-', '');
            var category = $(this).val();
            if (category == 'none') {
                category = '';
            }
            assignCategory(id, category, $(this));
        });
        
        $('#select-new-category-type').select2().on('change', function(){
            if($(this).val() == 0) {
                $('#subcategory-container').hide();
            } else {
                $('#subcategory-container').css('display', 'inline-block');
            }
        });
        
        $('#select-parent-category').select2({
            placeholder: 'Select One'
        });
        
        $('#select-original-category').select2({
            placeholder: 'Select One'
        }).on('change', function(){
            for (var i = 0; i < categoryArray.length; i++) {
                if (parseInt(categoryArray[i].ID) == $(this).val()) {
                    $('#add-category-text').val(categoryArray[i].category_name.replace(/\\/g, ''));
                    for (var j = 0; j < categoryArray.length; j++) {
                        $('#select-parent-category option[value='+parseInt(categoryArray[j].ID)+']').attr('disabled',false);
                    }
                    $('#select-parent-category').select2();
                    var childrenArray = new Array();
                    childrenArray.push(categoryArray[i].ID);
                    get_children_array(categoryArray[i].ID, childrenArray);
                    for (var j = 0; j < childrenArray.length; j++) {
                        $('#select-parent-category option[value='+parseInt(childrenArray[j])+']').attr('disabled','disabled');
                    }
                    $('#select-parent-category').select2();
                    if (categoryArray[i].parent_id == '0') {
                        $('#select-new-category-type').val(0).trigger('change');
                    } else {
                        $('#select-new-category-type').val(1).trigger('change');
                        $('#select-parent-category').val(parseInt(categoryArray[i].parent_id)).trigger('change');
                    }
                }
            }
        });

        $('[data-action="delete"]').on('click', function(){
            let key = $(this).attr('data-key');
            let template_id = $(this).attr('data-template_id');
            if (window.confirm('Delete template #' + template_id + '?')) {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    data: {
                        action: 'udraw_text_templates_delete',
                        key: key,
                        template_id: template_id
                    },
                    success: function (result) {
                        window.location.href = window.location.origin + window.location.pathname + '?page=<?php echo $_REQUEST['page'] ?>&udraw_action=deleted';
                    },
                    error: function (error) {
                        console.error(error);
                        window.alert('Error deleting template. Please try again or contact support.');
                    }
                });
            }
        });
        
        $('input.template-tags').tagsInput({
            width: 'auto',
            height: '80px'
        });

        $('a.update_tags').on('click', function(){
            let template_id = $(this).attr('data-template_id');
            $(this).removeClass('active');
            $(`div.display_tags[data-template_id="${template_id}"]`).removeClass('active');
            $(`div.update_tags[data-template_id="${template_id}"]`).addClass('active');
        });
        
        $('a.save_tags').on('click', function(){
            let template_id = $(this).attr('data-template_id');
            save_tags(template_id);
        });
    });
</script>