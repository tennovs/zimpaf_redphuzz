<?php
    if (is_user_logged_in()) {
        if (!current_user_can('read_udraw_templates')) {
            exit;
        }
    } else {
        exit;
    }    
    
    $udraw = new uDraw();
?>

<div class="wrap" id="manage-designs-page">
    <?php if (!$udraw->is_udraw_valid()) { ?>
        <div class="error settings-error" role="alert" style="width: 98%; border-left: 4px solid #FF0000; background: #FFD5D5; height: 34px; font-size: larger; padding-top: 15px;"><strong>Please Note:</strong> You have reached the maximum allowed templates. <a href="admin.php?page=edit_udraw_settings&tab=activation">Upgrade now</a> to full version!</div>
    <?php } ?>
    <h1>
        <i class="fa fa-picture-o" style="padding-right: 5px;"></i><?php _e('View Templates', 'udraw') ?>
        <?php if ($udraw->is_udraw_valid()) { ?> 
            <a href="admin.php?page=udraw_add_template" class="add-new-h2 button-primary"><?php _e('Add New', 'udraw') ?></a>
        <?php } ?>
    </h1>                

    <?php
    global $wpdb;
    $uDrawSettings = new uDrawSettings();
    $_udraw_settings = $uDrawSettings->get_settings();
    
    
    $templatesCategoryDB = $_udraw_settings['udraw_db_udraw_templates_category'];
    $templatesCategoryResults = $wpdb->get_results("SELECT * FROM $templatesCategoryDB");
    $myListTable = new uDraw_Templates_Table();
	if (isset($_GET['udraw'])) {
		if ( ($_GET['udraw'] == 'delete') && (isset($_GET['id'])) ) {			
			$table_name = $_udraw_settings['udraw_db_udraw_templates'];     
			$wpdb->query("DELETE FROM $table_name WHERE id = '". $_GET['id'] . "'");
			?>
            <div id="message" class="updated below-h2"><p>Template Deleted</p></div>
			<?php
		}
		if ($_GET['udraw'] == 'add') {
			?>
			<div id="message" class="updated below-h2"><p>Template Added</p></div>
			<?php
		}
        if ($_GET['udraw'] == 'duplicate') {
            if (uDraw::is_udraw_okay() == false) {
                if (count($udraw->get_udraw_templates()) >= 2) {
                    
                } else {
                    $response = $udraw->duplicate_udraw_template($_GET['id']);
                }
            } else {
                $response = $udraw->duplicate_udraw_template($_GET['id']);
            }
            ?>
            <div id="message" class="updated below-h2"><p>Template Duplicated</p></div>
            <?php
        }
        if (uDraw::is_udraw_okay() == false) { ?> <script>setTimeout(function () {location.href="?page=udraw"} , 500);</script> <?php }
	}
    
    $myListTable->prepare_items();
    $myListTable->views();
    ?>
    
	<div style="padding-top:10px;">
		<form method="get">
			<input type="hidden" name="page" value="udraw">			
			<?php            
			$myListTable->search_box('search', 'search_id');     
            ?>
            <!-- Category filter container; Displays lists of categories -->
            <?php if (current_user_can('edit_udraw_templates')) { ?>
            <br/>
            <br/>
            <div id="template-category-table-container" style="border-top: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; padding: 10px;">
                <div id="manage-add-remove-containers-buttons">
                    <a href="#" class="button button-default" onclick="javascript: openContainer('add');">Add category</a>
                    <a href="#" class="button button-default" onclick="javascript: openContainer('remove');">Remove category</a>
                    <a href="#" class="button button-default" onclick="javascript: openContainer('edit');">Edit category</a>
                </div>
                <div id="add-templates-category-div" style="display: none;">
                    <div style="padding: 5px 0px 5px 0px; font-size: 16px;">
                    Add Category
                    </div>
                    Add 
                    <input type='text' id="input-template-category" style="width: 200px; padding: 5px;" placeholder="Category Name">
                    as a
                    <select id="select-category-type">
                        <option value="main">Main category</option>
                        <option value="subcategory">Subcategory</option>
                    </select>
                    <div id="subcategory-select-div" style="display: none;">
                        to
                        <select id="select-subcategory" style="width: 200px;">
                            <?php 
                            $myListTable->buildCategorySelect("0", "");
                            ?>
                        </select>
                    </div>
                    <a href="#" id="add-template-category-btn" class="button button-default" onclick="javascript: addCategory();" style="margin-top: 5px;">Add</a>
                    <a href="#" class="button button-default" onclick="javascript: closeContainer();" style="margin-top: 5px;">Cancel</a>
                    <span id="successfully-added-category" style="display: none; "><i class="fa fa-check-circle" style="color: green; transform: scale(1.5); padding: 0px 2px 0px 2px;"></i>&nbsp;Successfully added!</span>
                    <span id="unsuccessfully-added-category" style="display: none; font-size: 10px;"><i class="fa fa-times-circle" style="color: red; transform: scale(2.2); padding: 0px 4px 0px 4px;"></i>&nbsp;Please make sure the name does not already exist.</span>
                    <br>
                    <span>Special characters will automatically be removed.</span>
                </div>
                <div id="remove-templates-category-div" style="display: none;">
                    <div style="padding: 5px 0px 5px 0px; font-size: 16px;">
                    Remove Category
                    <span style="font-size: 11px; color: red">( Please note that removing a category will also remove any subcategory it may have. )</span>
                        <div>
                            <select id="select-category-remove" style="width: 200px;">
                                <?php 
                                $myListTable->buildCategorySelect("0", "");
                                ?>
                            </select>
                            <a href="#" id="remove-template-category-btn" class="button button-default" onclick="javascript: removeCategory();">Remove</a>
                            <a href="#" class="button button-default" onclick="javascript: closeContainer();">Cancel</a>
                        </div>
                    </div>
                </div>
                <div id="edit-category-div" style="display: none;">
                    <span>Edit </span>
                    <select id="edit-templates-category" style="width: 250px;">
                        <option value="" disabled selected></option>
                        <?php
                        //Append options to remove-select
                            $myListTable->buildCategorySelect('0', '');
                        ?>
                    </select>
                    <div id="edit-category-details-div" style="display: none;">
                        <span>with name </span>
                        <input type="text" id="edit-category-name" value="" placeholder="Enter name" />
                        <span> as a </span>
                        <select id="edit-category-type">
                            <option value="0">Main Category</option>
                            <option value="1">Sub Category</option>
                        </select>
                        <div id="edit-sub-category-div" style="display: none;">
                            <span>to</span>
                            <select id="edit-sub-category-parent" style="width: 200px;">
                                <option value="" disabled selected></option>
                                <?php
                                $myListTable->buildCategorySelect('0', '');
                            ?>
                            </select>
                        </div>
                    </div>
                    <a href="#" id="edit-category-btn" class="button" onclick="javascript: updateCategory();">Update Category</a>
                    <a href="#" class="button button-default" onclick="javascript: closeContainer();">Cancel</a>
                    <div id="edit-category-success-div" style="display: none;"><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i><span id="remove-category-success-span">Successfully updated!</span></div>
                    <div id="edit-category-error-div" style="display: none;"><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i><span id="remove-category-error-span">An error had occurred.</span></div>
                    <div>
                    </div>
                </div>
            </div>
            
            <?php
            } 
			$myListTable->display();
			?>
		</form>
	</div>
</div>

<script type="text/javascript">
    var categoryArray = <?php echo json_encode($templatesCategoryResults) ?>;
    jQuery(document).ready(function($) {
        $('.template-tags').tagsInput({
            width: 'auto',
            height: '80px'
        });
        $('#select-category-type').select2({ }).on("change", function () {
            if ($(this).val() == 'main') {
                $('#subcategory-select-div').css('display', 'none');
            } else if ($(this).val() == 'subcategory') {
                $('#subcategory-select-div').css('display', 'inline-block');
            }
        });
        
        $('#select-subcategory').select2();
        $('#select-category-remove').select2();
        
        $('.add-template-category').select2({ }).on("change", function(){
            var id = jQuery(this).attr('id').replace('select-category-for-', '');
            addCategoryTo(id, jQuery(this).val());
        });
        
        $('#edit-templates-category').select2({ placeholder: 'Select one' }).on('change', function(e){
            if (e.target.value != '') {
                jQuery('#edit-category-details-div').show();
                for (var i = 0; i < categoryArray.length; i++) {
                    if (categoryArray[i].ID == e.target.value) {
                        jQuery('#edit-category-name').val(categoryArray[i].category_name);
                        if (categoryArray[i].parent_id === '0') {
                            jQuery('#edit-category-type').val('0').trigger('change');
                            jQuery('#edit-sub-category-div').hide();
                        } else {
                            jQuery('#edit-sub-category-parent option').prop('disabled', false);
                            jQuery('#edit-category-type').val('1').trigger('change');
                        }
                    }
                }
            } else {
                jQuery('#edit-category-details-div').hide();
                jQuery('#edit-category-name').val('');
            }
        });
        $('#edit-category-type').select2({ placeholder: "Select one"}).on('change', function (e){
            $('#edit-sub-category-parent').select2({ placeholder: "Select one"});
            if (e.target.value == '1') {
                jQuery('#edit-sub-category-div').show();
                jQuery('#edit-sub-category-parent option').prop('disabled', false);
                jQuery('#edit-sub-category-div').show();
                for (var i = 0; i < categoryArray.length; i++) {
                    if (categoryArray[i].ID == jQuery('#edit-templates-category').val()) {
                        jQuery('#edit-sub-category-parent').val(categoryArray[i].parent_id).trigger('change');
                    }
                }
                var listOfCategories = new Array();
                listOfCategories.push(jQuery('#edit-templates-category').val());
                for (var j = 0; j < listOfCategories.length; j++) {
                    for (var k = 0; k < categoryArray.length; k++) {
                        if (categoryArray[k].parent_id == listOfCategories[j]) {
                            listOfCategories.push(categoryArray[k].ID);
                        }
                    }
                }
                for (var m = 0; m < listOfCategories.length; m++) {
                    jQuery('#edit-sub-category-parent option[value="'+listOfCategories[m]+'"]').prop('disabled', true);
                }
            } else {
                jQuery('#edit-sub-category-div').hide();
            }
        });
        $('#edit-sub-category-parent').select2({ placeholder: "Select one"});
    });

    function renameTemplate(id) {
        jQuery('#udraw_rename_template_' + id).show();
        jQuery('#udraw_item_name_' + id).hide();
    }
    function execRenameTemplate(id) {
        var templateName = jQuery('#udraw_rename_template_input_' + id).val();
        if (templateName.length > 0) {

            jQuery.ajax({
                method: 'POST',
                dataType: "json",
                url: ajaxurl,
                data: {
                    'action': 'udraw_update_template_name',
                    'template_id': id,                    
                    'template_name': Base64.encode(templateName)
                },
                success: function (response) {
                    jQuery('#udraw_item_name_' + id).show();
                    jQuery('#udraw_rename_template_' + id).hide();
                    jQuery('#udraw_item_name_' + id).text(templateName);
                }

            });

        } else {
            jQuery('#udraw_item_name_' + id).show();
            jQuery('#udraw_rename_template_' + id).hide();
        }


    }
                           
    function updateTags(id) {
        jQuery('#tags-'+ id +'-display').hide();
        jQuery('#tags-'+ id +'-update').show();
    }
    
    function saveTags(id) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: ajaxurl,
            data: {
                'template_id': id,
                'action': 'udraw_update_template_tags',
                'tags': jQuery('#tags-'+ id +'-input').val()
            },
            success: function(response) {
                var tags = new Array();
                if (response != null) {
                    tags = response.split(",");
                }
                var _html = "";
                for (var x=0; x<tags.length; x++) {                    
                    _html += tags[x];
                    if (x != tags.length-1) {
                        _html += " | ";
                    }                          
                }
                jQuery('#tags-'+ id +'-span').empty();
                jQuery('#tags-'+ id +'-span').html(_html);
                jQuery('#tags-'+ id +'-update').hide();        
                jQuery('#tags-'+ id +'-display').show();
            }
        });        
       
    }
    
    function addCategory() {
        jQuery('#successfully-added-category').hide();
        jQuery('#unsuccessfully-added-category').hide();
        //check for non letters, numbers, hyphens and underscores, or is string is empty
        if (jQuery('#input-template-category').val().length > 0) {
            var category_name = jQuery('#input-template-category').val().replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\=|\+|\\|\||\]|\}|\[|\{|\?|\/|\.|\>|\,|\<|\;|\:|\"/gi, '');
            jQuery('#input-template-category').val(category_name);
            var sub = "";
            if (jQuery('#select-category-type').val() == 'subcategory') {
                sub = jQuery('#select-subcategory').val();
            }
            jQuery.ajax({
                method: 'POST',
                dataType: "json",
                url: ajaxurl,
                data: {
                    'action': 'udraw_add_template_category',
                    'category_name': jQuery('#input-template-category').val(),
                    'type' : jQuery('#select-category-type').val(),
                    'sub_category_id': sub
                },
                success: function(response) {
                    jQuery('#input-template-category').val('');
                    location.reload();
                    jQuery('#successfully-added-category').show();
                },
                error: function (error) {
                    jQuery('#unsuccessfully-added-category').show();
                    jQuery('#input-template-category').val('');
                }
            });
        } else {
            //Otherwise empty the text input and show error message
            jQuery('#input-template-category').val('');
            jQuery('#unsuccessfully-added-category').show();
        }
       
    }
    
    function removeCategory(){
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: ajaxurl,
            data: {
                'action': 'udraw_remove_template_category',
                'category_id': jQuery('#select-category-remove').val()
            },
            success: function(response) {
                location.reload();
            },
            error: function (error) {
                window.alert("Oops! Something went wrong.");
            }
        });
    }
    
    function addCategoryTo(id, category) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: ajaxurl,
            data: {
                'action': 'udraw_apply_template_category',
                'category_id': category,
                'template_id': id
            },
            success: function(response) {
                location.reload();
            },
            error: function (error) {
                window.alert("Oops! Something went wrong.");
            }
        });
    }
    function removeCategoryFrom(id, category) {
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: ajaxurl,
            data: {
                'action': 'udraw_detach_template_category',
                'category_id': category,
                'template_id': id
            },
            success: function(response) {
                location.reload();
            },
            error: function (error) {
                window.alert("Oops! Something went wrong.");
            }
        });
    }
    function getFilter() {
        var category = jQuery('#filter-category-select').val();
        if (category != 'none') {
            document.location.href='?page=<?php echo $_REQUEST["page"]?>&filter='+category;
        } else {
            document.location.href='?page=<?php echo $_REQUEST["page"]?>';
        }
    }
    
    function closeContainer() {
        jQuery('#manage-add-remove-containers-buttons').show();
        jQuery('#add-templates-category-div, #remove-templates-category-div, #edit-category-div').hide();
    }
    
    function openContainer(container) {
        if (container == 'add') {
            jQuery('#add-templates-category-div').show();
        } else if (container == 'remove') {
            jQuery('#remove-templates-category-div').show();
        } else if (container == 'edit') {
            jQuery('#edit-category-div').show();
        }
        jQuery('#manage-add-remove-containers-buttons').hide();
    }
         
    function updateCategory (){
        jQuery('#edit-category-error-div').hide();
        if (jQuery('#edit-templates-category').val() == null) {
            jQuery('#edit-category-error-div').show();
            return;
        }
        var category_id = jQuery('#edit-templates-category').val();
        if (jQuery('#edit-category-name').length == 0) {
            jQuery('#edit-category-error-div').show();
            return;
        }
        var category_name = jQuery('#edit-category-name').val().replace(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\=|\+|\\|\||\]|\}|\[|\{|\?|\/|\.|\>|\,|\<|\;|\:|\"/gi, '');
        jQuery('#edit-category-name').val(category_name);
        var parent_id = '0';
        if (jQuery('#edit-category-type').val() == '1') {
            if (jQuery('#edit-sub-category-parent').val() == null) {
                jQuery('#edit-category-error-div').show();
                return;
            }
            parent_id = jQuery('#edit-sub-category-parent').val();
        }
        jQuery.ajax({
            method: 'POST',
            dataType: "json",
            url: ajaxurl,
            data: {
                'action': 'udraw_update_template_category',
                'category_id': category_id,
                'parent_id' : parent_id,
                'category_name' : category_name
            },
            success: function(response) {
                location.reload();
            },
            error: function (error) {
                jQuery('#edit-category-error-div').show();
            }
        });
    }
    
</script>

<style>
    td.preview {
        width: 100%;
    }
</style>
    