<?php
$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();
$uDraw_SVG = new uDraw_SVG();

$valid_extensions = ".jpg,.jpeg,.png,.gif,.pdf";
if (isset($_udraw_settings['goprint2_file_upload_types']) && is_array($_udraw_settings['goprint2_file_upload_types'])) {
    $valid_extensions = "";
    foreach($_udraw_settings['goprint2_file_upload_types'] as $key => $value) {
        $valid_extensions_array = explode("|",$key);
        foreach ($valid_extensions_array as $ext) {
            $valid_extensions .= ".". $ext .",";
        }
    }
}
$valid_extensions = rtrim($valid_extensions, ",");

$template_id = get_post_meta($post->ID, '_udraw_SVG_template_id', true);
$has_template = strlen($template_id) > 0;
$allow_upload = get_post_meta($post->ID, '_udraw_SVG_allow_upload_artwork', true);
$upload_pages = json_decode(get_post_meta($post->ID, '_udraw_SVG_upload_artwork_pages', true));
$allow_upload_single_doc = get_post_meta($post->ID, '_udraw_SVG_allow_upload_artwork_single_document', true);

$allow_download_template = get_post_meta($post->ID, '_udraw_SVG_allow_download_template', true);

$session_id = $uDraw_SVG->get_session_id();

$cart_item_key = (isset($_REQUEST['cart_item_key'])) ? $_REQUEST['cart_item_key'] : '';
?>
<div class="udrawSVG_button_container">
<?php
if ($has_template) {
?>
    <button type="button" name="SVGDesigner_design_now" data-udrawSVG="design_now" class="button alt">
        <span class="loading hidden"><?php _e('Design is loading. Please wait...', 'udraw_svg'); ?></span>
        <i class="fa fa-spinner fa-pulse hidden"></i>
        <span class="design_now_span"><?php _e('Design Now', 'udraw_svg'); ?></span>
    </button>
<?php
}
if ($has_template && ($allow_upload || $allow_upload_single_doc)) {
?>
<?php
}
if ($allow_upload || $allow_upload_single_doc) {
?>
    <a href="#TB_inline?&width=850&height=700&inlineId=udrawSVG_upload_artwork_list_container" 
       data-udrawSVG="upload_artwork" class="button alt thickbox">
        <?php _e('Upload Artwork', 'udraw_svg'); ?>
    </a>
<?php
}
if ($has_template && $allow_download_template) {
?>
<?php
}
if ($allow_download_template) {
?>
    <button type="button" data-udrawSVG="download_template" class="button alt">
        <span><?php _e('Download Template for offline design','udraw_svg'); ?></span>
    </button>
    <br />
    <input type="file" name="files[]" data-udrawSVG="upload_template" class="hidden" accept="<?php echo $valid_extensions ?>" />
    <button type="button" data-udrawSVG="upload_template_button" class="button alt">
        <span><?php _e('Upload Finished Design','udraw_svg'); ?></span>
    </button>
<?php
}

?>
</div>
<style>
    div.product div.quantity {
        display: inline-block;
        vertical-align: top;
    }
    form.cart div.udrawSVG_button_container {
        display: inline-block;
        text-align: center;
        vertical-align: top;
    }
    form.cart div.udrawSVG_button_container button {
        float: none;
    }
    form.cart div.udrawSVG_button_container br {
        clear: both;
    }
</style>
<?php
if ($allow_upload || $allow_upload_single_doc) {
    add_thickbox();
    ?>
    <div id="udrawSVG_upload_artwork_list_container" style="display: none;">
        <table id="udrawSVG_upload_artwork_list_table">
            <thead>
                <tr>
                    <?php if ($allow_upload ) { ?>
                        <th><?php _e('Page Name', 'udraw_svg'); ?></th>
                    <?php } ?>
                    <th></th><th><?php _e('File Uploaded', 'udraw_svg'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($allow_upload ) {
                    for ($i = 0; $i < count($upload_pages); $i++) {
                        ?>
                        <tr>
                            <td><label><?php echo $upload_pages[$i]->label ?></label></td>
                            <td><input type="file" id="<?php echo str_replace(' ', '_', $upload_pages[$i]->label); ?>" name="files[]" class="upload_artwork_page hidden" accept="<?php echo $valid_extensions ?>">
                            <button type="button" id="<?php echo str_replace(' ', '_', $upload_pages[$i]->label); ?>_button" class="upload_artwork_page_btn btn btn-secondary">
                                <?php _e('Select File', 'udraw_svg'); ?>
                            </button>
                            </td>
                            <td><span class="upload_artwork_page_span" id="<?php echo str_replace(' ', '_', $upload_pages[$i]->label); ?>_span"></span></td>
                        </tr>
                        <?php 
                    }
                } else {
                    ?>
                        <td><input type="file" id="file_upload" name="files[]" class="upload_artwork_page hidden" accept="<?php echo $valid_extensions ?>">
                            <button type="button" id="file_upload_button" class="upload_artwork_page_btn btn btn-secondary">
                                <?php _e('Select File', 'udraw_svg'); ?>
                            </button>
                        </td>
                        <td><span id="file_upload_span" class="upload_artwork_page_span"></span></td>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><a href="#" class="button alt" data-udrawSVG="upload_artwork_submit"><?php _e('Add to Cart', 'udraw_svg'); ?></a></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>
        var uploaded_files = new Object();
        jQuery(document).ready(function($){
            if ('<?php echo $cart_item_key ?>'.length > 0) {
                <?php
                global $woocommerce;
                foreach ($woocommerce->cart->get_cart() as $key => $values) {
                    if ($key == $cart_item_key) {
                        ?>
                            var uploaded_files = <?php echo json_encode($values['udraw_SVG_data']['udraw_SVG_uploaded_artwork']); ?>;
                        <?php
                    }                    
                }
                ?>
                $('[name="udraw_SVG_uploaded_artwork"]').val(JSON.stringify(uploaded_files));
                for (var prop in uploaded_files) {
                    var page_name = prop;
                    $(`#udrawSVG_upload_artwork_list_table tr span#${page_name}_span`).text(uploaded_files[prop].original_name);
                }
            }
            $('button.upload_artwork_page_btn').on('click', function(){
                var page_name = $(this).attr('id').replace('_button', '');
                $(`#${page_name}`).trigger('click');
            });
            $('[data-udrawSVG="upload_artwork_submit"]').on('click', function(){
                var count = 0;
                for (var prop in uploaded_files) {
                    count++;
                }
                if (count !== $('input.upload_artwork_page').length) {
                    alert('<?php _e('Insufficient files were uploaded.', 'udraw_svg'); ?>');
                    return false;
                }
                
                $('[name="udraw_SVG_uploaded_artwork"]').val(JSON.stringify(uploaded_files));
                $('[name="udraw_SVG_product"]').val(true);
                
                if (typeof priceMatrixObj === 'object') {
                    $('form.cart').submit();
                } else {
                    $('[name="add-to-cart"]').trigger('click');
                }
            });
            $('input.upload_artwork_page').each(function(){
                var filename = $(this).attr('id');
                var session_id = '<?php echo $session_id ?>';
                if (typeof RacadSVGDesigner === 'object' && RacadSVGDesigner.settings.session_id !== 0) {
                    session_id = RacadSVGDesigner.settings.session_id;
                }
                $(this).fileupload({
                    url:  '<?php echo admin_url( 'admin-ajax.php' ) . '?action=udraw_price_matrix_upload&session=' ?>' + session_id + '&filename=' + encodeURI(filename),
                    dataType: 'json',
                    done: function (e, data) {
                        for (var x = 0; x < data.result.length; x++) {
                            if (typeof data.result[x].error == 'string') {
                                var _valid_extension_names = '';
                                <?php
                                if (isset($valid_extensions)) {
                                    if (strlen($valid_extensions) > 0) {
                                        echo '_valid_extension_names="'. $valid_extensions .'";';
                                    }
                                }
                                ?>
                                var _errorMessage = 'Upload Failed, Invalid File Type.';
                                if (_valid_extension_names.length > 0) {
                                    var _valid_extensions_arr = _valid_extension_names.split(',');
                                    _errorMessage = 'Upload Failed, Invalid File Type.\n\nAllowed File Type(s) Are: ';
                                    for (var z = 0; z < _valid_extensions_arr.length; z++) {
                                        if (z === _valid_extensions_arr.length-1) {
                                            _errorMessage += _valid_extensions_arr[z];
                                        } else {
                                            _errorMessage += _valid_extensions_arr[z] + ', ';
                                        }                                
                                    }
                                }
                                alert(_errorMessage);
                                break;
                            }
                            var _item = {
                                name: data.result[x].name,
                                url: data.result[x].url,
                                original_name: data.result[x].original_name
                            };
                            uploaded_files[filename] = _item;
                            //Display the uploaded file's name
                            $(`#${filename}_span`).text(_item.original_name);
                        }
                    }
                });
            });
        });
    </script>
    <?php
}

if ($allow_download_template) {
    ?>
    <script>
        jQuery(document).ready(function($){
            RacadSVGDesigner.settings.handler_file = '<?php echo admin_url('admin-ajax.php'); ?>';

            function _disable_design_button () {
                $('[data-udrawSVG="design_now"]').addClass('disabled').prop('disabled', true);
                $('[data-udrawSVG="design_now"] i').removeClass('hidden');
                $('[data-udrawSVG="design_now"] span.loading').removeClass('hidden');
                $('[data-udrawSVG="design_now"] span.design_now_span').addClass('hidden');
            }
            
            function _enable_design_button () {
                $('[data-udrawSVG="design_now"]').removeClass('disabled').prop('disabled', false);
                $('[data-udrawSVG="design_now"] i').addClass('hidden');
                $('[data-udrawSVG="design_now"] span.loading').addClass('hidden');
                $('[data-udrawSVG="design_now"] span.design_now_span').removeClass('hidden');
            }
            
            $('[data-udrawSVG="download_template"]').on('click', function(e){
                
            });
            $('[data-udrawSVG="upload_template_button"]').on('click', function(e){
                $('[data-udrawSVG="upload_template"]').trigger('click'); 
            });
            $('[data-udrawSVG="upload_template"]').fileupload({
                url: RacadSVGDesigner.settings.handler_file,
                autoUpload: true,
                sequentialUploads: true,
                chooseText: 'Upload',
                formData: {
                    assetPath: RacadSVGDesigner.settings.upload_path,
                    action: RacadSVGDesigner.handler_actions.upload_pdf_template
                },
                submit: function (e, data) {
                    _disable_design_button();
                },
                done: function (e, data) {
                    var design_file = JSON.parse(data.result).design_file;
                    RacadSVGDesigner.settings.design_file = design_file;
                    RacadSVGDesigner.Load.json_file(RacadSVGDesigner.settings.design_file, function(){
                        $('[data-udrawSVG="design_now"] span.design_now_span').html('Finalize Design');
                        _enable_design_button();
                    });
                }
            });
        });
    </script>
    <?php
}