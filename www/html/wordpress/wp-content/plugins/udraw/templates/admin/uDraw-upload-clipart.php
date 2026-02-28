<div id="deleted-message" class="updated" style="display: none; margin-left: 0px; padding: 10px;"><span><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>Clipart Deleted</span></div>
<div id='error-message' class="error" style='display: none; margin-left: 0px; padding: 10px;'><span><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i>An error had occured.</span></div>
<?php

global $wpdb;
$uDrawClipartTable = new uDraw_Clipart_Table();
$uDrawUpload = new uDrawUpload();
$uDrawSettings = new uDrawSettings();
$_udraw_settings = $uDrawSettings->get_settings();
$clipartDB = $_udraw_settings['udraw_db_udraw_clipart'];

//Upload stuff
if (!empty($_FILES['files'])) {
    
    $uploaded_files = $uDrawUpload->handle_upload($_FILES['files'], UDRAW_CLIPART_DIR, UDRAW_CLIPART_URL);
    
    if (is_array($uploaded_files)) {
        for ($x = 0; $x < count($uploaded_files); $x++) {
            if ( !key_exists('error', $uploaded_files[$x]) ) {
                echo '<div class="updated" style="padding: 10px; margin-left: 0px;"><span id="upload-success-span"><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>' . basename($uploaded_files[$x]['file']) . ' Uploaded successfully!</span></div>';
                $uDrawSettings = new uDrawSettings();
                $_udraw_settings = $uDrawSettings->get_settings();
                $clipartDB = $_udraw_settings['udraw_db_udraw_clipart'];
                $dt = new DateTime();
                $wpdb->insert($clipartDB, array(
                    'user_uploaded' => wp_get_current_user()->user_login,
                    'image_name' => str_replace('/' , '', str_replace(UDRAW_CLIPART_DIR, '', $uploaded_files[$x]['file'])),
                    'date' => $dt->format('Y-m-d H:i:s'),
                    'access_key' => uniqid()
                ));
            } else {
                echo '<div class="error" style="padding: 10px; margin-left: 0px;"><span id="upload-error-span"><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i>'. $uploaded_files[$x]['error'] .'</span></div>';
            }
        }
    }    
}
    
$uDrawClipartTable->prepare_items();
?>

<h1><?php _e('Private Image Library', 'udraw'); ?></h1>

<?php
if (is_user_logged_in()) {
    if (current_user_can('edit_udraw_clipart_upload')) {
        ?>
    <form action="" method="post" enctype="multipart/form-data">
        <a href="#" id="upload-clipart-files" class="button button-primary" onclick="javascript: jQuery('#files').trigger('click');"><i class="fa fa-upload"></i> Upload Clipart</a>
        <input type="file" name="files[]" id="files" multiple accept="image/gif,image/png,image/bmp,image/jpeg,image/svg+xml" style="display: none;">
        <input type="submit" id="submit-files" name="submit" value="Submit" style="display: none;">
    </form>
    <p><strong>Valid file types</strong>: gif, png, bmp, jpg, svg</p>
    <p>To enable Private Image Library on uDraw Designer go to <strong>W2P: uDraw > Settings > Designer UI > Enable Local Clipart.</strong></p>
<?php
    }
} else {
    exit;
}
?>

<div>
    <?php if (current_user_can('edit_udraw_clipart_upload')) { ?>
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
                    $uDrawClipartTable->buildCategorySelectOptions('0');
                ?>  
            </select>
            with name 
        </div>
        <input type='text' id='add-category-text' placeholder="Category Name"/>
        as a 
        <select id="select-new-category-type">
            <option value="0">Main Category</option>
            <option value="1">Sub Category</option>
        </select>
        <div id="subcategory-container" style="display: none;">
            to
            <select id="select-parent-category" style="width: 150px;">
                <option value="" disabled selected>Select One</option>
                <?php
                    $uDrawClipartTable->buildCategorySelectOptions('0');
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
            $uDrawClipartTable->buildCategorySelectOptions('0');
            ?>
        </select>
        <a href='#' id='remove-category-select-btn' class='button button-default'>Remove</a>
        <a href="#" class="button button-default category-cancel">Cancel</a>
    </div>
    <span id='remove-category-error' style='display: none;'><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i>An error had occured.</span>
    <span id='remove-category-success' style='display: none;'><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>Successfully removed!</span>
    
<div>
    <form method="get">
		<input type="hidden" name="page" value="upload_private_image_collection">
		<?php            
		$uDrawClipartTable->search_box('search', 'search_id');
        $uDrawClipartTable->display();
        ?>
    </form>
</div>
</div>

<script type="text/javascript">
    var categoryArray = <?php echo json_encode($uDrawClipartTable->_clipart_category_array) ?>;
    jQuery(document).ready(function($) {
        $('#files').on('change', function(){
           $('#submit-files').trigger('click');
       });
        
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
        
        $('.clipart-tags').tagsInput({
            width: 'auto',
            height: '80px'
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
        
        $('.select-clipart-category').select2({
            placeholder: "Select one"
        }).on('change', function (){
            $(this).parent().css('background','#FBC0C0');
            var id = $(this).attr('id').replace('select-clipart-category-id-', '');
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
        
        $('#filter-category-button').click(function(){
            if ($('#filter-category-select').val() != ' ') {
                location.search = "page=upload_private_image_collection&filter=" + $('#filter-category-select').val();
            } else {
                location.search = "page=upload_private_image_collection";
            }
        });
    });
    
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
                'action': 'udraw_update_clipart_tags',
                'clipart_id': id,
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
                    'action' : 'udraw_add_clipart_category',
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
                'action': 'udraw_remove_clipart_category',
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
    
    function removeClipart (accessKey) {
        jQuery.ajax({
            method: 'GET',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'udraw_remove_clipart',
                'access_key': accessKey
            },
            success: function (response) {
                jQuery("#deleted-message").show();
                window.location.reload(true);
            },
            error: function (error) {
                console.log(error);
                jQuery('#error-message').show();
            }
        });
    }
    
    function assignCategory (accesskey, category, caller) {
        jQuery.ajax({
            method: 'GET',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'udraw_assign_clipart',
                'access_key': accesskey,
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
                    'action' : 'udraw_update_clipart_category',
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
    
    function get_children_array(parent_id, array) {
        for (var i = 0; i < categoryArray.length; i++) {
            if (categoryArray[i].parent_id == parent_id) {
                array.push(categoryArray[i].ID);
                get_children_array(categoryArray[i].ID, array);
            }
        }
    }
</script>
    
<style>
    td.preview {
        width: 100%;
    }
</style>