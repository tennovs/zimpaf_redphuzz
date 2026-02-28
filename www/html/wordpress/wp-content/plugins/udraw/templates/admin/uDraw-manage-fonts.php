<script>
    var lastUploadedFont = new Array();
</script>
<?php
    if (is_user_logged_in()) {
        if (!current_user_can('read_udraw_fonts')) {
            exit;
        }
    } else {
        exit;
    }

    $udrawSettings = new uDrawSettings();
    $uDrawUpload = new uDrawUpload();
    $_udraw_settings = $udrawSettings->get_settings();

//File upload stuff
    if (!empty($_FILES['files'])) {
        $uploaded_files = $uDrawUpload->handle_upload($_FILES['files'], UDRAW_FONTS_DIR, UDRAW_FONTS_URL, array( 'woff' => 'application/font-woff', 'ttf' => 'application/octet-stream' ));
        
        if (is_array($uploaded_files)) {
            for ($x = 0; $x < count($uploaded_files); $x++) {
                if ( !key_exists('error', $uploaded_files[$x]) ) {
                    echo '<div class="updated" style="padding: 10px; margin-left: 0px;"><span id="upload-success-span"><i class="fa fa-check-circle" style="color: green; transform: scale(1.5,1.5); margin-right: 5px;"></i>'. basename($uploaded_files[$x]['file']) .' was uploaded successfully!</span></div>';
                    echo '<script>var font = "'. basename($uploaded_files[$x]["file"]) .'"; lastUploadedFont.push(font.replace(".woff" , "").toLowerCase());</script>';
                } else {
                    echo '<div class="error" style="padding: 10px; margin-left: 0px;"><span id="upload-error-span"><i class="fa fa-times-circle" style="color: red; transform: scale(1.5,1.5); margin-right: 5px;"></i>An error occurred. Please ensure that the file type is .woff or .ttf.</span></div>';
                }
            }
        }        
    }
?>
<style>
    #udraw-current-font-list td {
        padding-bottom: 10px;
    }    
</style>
<?php if (current_user_can('edit_udraw_fonts')) { ?>
<div id="udraw-bootstrap"></div>
<div class="wrap" id="udraw-manage-fonts">
    <div class="row">
        <h3 id="font-upload-message"><?php _e('Upload Font(s)', 'udraw') ?> - <strong style="color:#F00;" ><?php _e('Supported File Types: .woff, .ttf', 'udraw') ?></strong></h3>
        
        <form action="" method="post" enctype="multipart/form-data">
            <a href="#" id="upload-font-files" class="button button-primary" onclick="javascript: jQuery('#files').trigger('click');"><span><?php _e('Select files...', 'udraw') ?></span></a>
            <input type="file" name="files[]" id="files" multiple accept=".woff,.ttf" style="display: none;">
            <input type="submit" id="submit-files" name="submit" value="Submit" style="display: none;">
        </form>
        
    </div>
    <div style="padding-top:10px;">
        <hr />
        <table id="udraw-current-font-list" style="width:100%">
            
        </table>
    </div>
</div>
<?php } ?>

<script>
    jQuery(document).ready(function($) {
        _load_udraw_fonts();
        $('#files').on('change', function(){
           $('#submit-files').trigger('click');
       });
    });
    
    <?php if (current_user_can('delete_udraw_fonts')) { ?>
        function removeUDrawFont(name, type) {
            jQuery.getJSON(ajaxurl + '?action=udraw_designer_remove_font&font_name=' + name + '&font_type=' + type,
                function (data) {
                    location.reload(true);
                }
            );

        }
    <?php } ?>
    
    function _load_udraw_fonts() 
    {
        jQuery.getJSON(ajaxurl + '?action=udraw_designer_local_fonts_list&localFontPath=<?php echo wp_make_link_relative(UDRAW_FONTS_URL) ?>',
            function (data) {
                var _fontList = "";
                for (var x = 0; x < data.length; x++) {
                    var lastUploaded = false;
                    if (lastUploadedFont != '') {
                        if (lastUploadedFont.indexOf(data[x].name.toLowerCase()) != -1) {
                        lastUploaded = true;
                        }
                    }
                    _fontList += "<tr";
                    if (lastUploaded) {
                        _fontList += " style='background: #ABFFAB;'";
                    }
                    if (data[x].fontType === 'truetype'){fontType = '.ttf'} 
                    else {fontType = '.woff'}
                    _fontList += "><td style=\"border-bottom: 1px solid black;padding-bottom: 5px;\">";
                    _fontList += "<span>";
                    _fontList += data[x].name[0].toUpperCase() + data[x].name.slice(1).toLowerCase();
                    _fontList += "</span>";
                    _fontList += "</td><td style=\"border-bottom: 1px solid black;\"><span style=\"font-family: '"+ data[x].name +"';font-size: 1.6em;\">the quick brown fox jumps over the lazy dog</span></td><td><?php if (current_user_can('delete_udraw_fonts')) { ?><a href=\"#\" onclick=\"removeUDrawFont('"+ data[x].name + "','" + fontType + "');\" class=\"button\" style=\"background: #CC2E2E;border-color: #A20000; color: #FFFFFF; margin-top: 3px;\">Delete</a><?php } ?>&nbsp;</td></tr>";
                }
                jQuery('#udraw-current-font-list').empty();
                jQuery('#udraw-current-font-list').append(_fontList);
            }
        );        
    }
</script>