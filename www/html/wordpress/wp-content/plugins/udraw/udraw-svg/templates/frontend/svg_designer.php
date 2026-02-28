<?php
global $wpdb, $post, $woocommerce;
$uDraw_SVG = new uDraw_SVG();
$uDrawSettings = new uDrawSettings();
$_udraw_settings = $uDrawSettings->get_settings();
$svg_settings_class = new uDraw_SVG_settings();
$svg_settings = $svg_settings_class->get_settings();

$selected_background_colour = (metadata_exists('post', $post->ID, '_udraw_SVG_selected_background_colour')) ? 
        get_post_meta($post->ID, '_udraw_SVG_selected_background_colour', true) : '';

$background_image_isset = (metadata_exists('post', $post->ID, '_udraw_SVG_use_background_image')) ? 
        get_post_meta($post->ID, '_udraw_SVG_use_background_image', true) : false;
$selected_background_image_id = (metadata_exists('post', $post->ID, '_udraw_SVG_selected_background_image')) ? 
        get_post_meta($post->ID, '_udraw_SVG_selected_background_image', true) : false;
$selected_background_image_url = ($background_image_isset) ? get_post($selected_background_image_id)->guid : '';

$editing_tips_colour = (metadata_exists('post', $post->ID, '_udraw_SVG_editing_tips_colour')) ? 
        get_post_meta($post->ID, '_udraw_SVG_editing_tips_colour', true) : '#000';

$allow_custom_objects = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_custom_objects')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_custom_objects', true) : false;
$allow_background_colour = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_background_colour')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_background_colour', true) : false;
$allow_rotate_template = (metadata_exists('post', $post->ID, '_udraw_SVG_allow_rotate_template')) ? 
        get_post_meta($post->ID, '_udraw_SVG_allow_rotate_template', true) : false;

$is_update = false;
$udraw_access_key = '';
if (isset($_REQUEST['udraw_access_key'])) {
    $udraw_access_key = $_REQUEST['udraw_access_key'];
    $saved_designs_table = $_udraw_settings['udraw_db_udraw_customer_designs'];
    $results = $wpdb->get_row("SELECT * FROM $saved_designs_table where access_key='$udraw_access_key'", ARRAY_A);
    $design = addslashes(base64_decode(file_get_contents(UDRAW_STORAGE_DIR . $results['design_data'])));
}

$SVG_product = get_post_meta($post->ID, '_udraw_SVG_product', true);
if ($SVG_product) {
    $template_id = get_post_meta($post->ID, '_udraw_SVG_template_id', true);
    $table_name = $wpdb->prefix.'udraw_svg_templates';
    $design_file = '';
    if (strlen($template_id) > 0) {
        $template = $wpdb->get_row("SELECT * FROM $table_name WHERE ID=$template_id", ARRAY_A);
        $design_file = $template['design_path'];
    }
    
    
    $cart_item_key = '';
    if (isset($_REQUEST['cart_item_key'])) {
        $cart_item_key = $_REQUEST['cart_item_key'];
        $cart = $woocommerce->cart->get_cart();
        $cart_item = $cart[$cart_item_key];
        if (isset($cart_item['udraw_SVG_data'])) {
            $udraw_SVG_data = $cart_item['udraw_SVG_data'];
            $design_file = $udraw_SVG_data['udraw_SVG_design_data'];
            $replace = wp_make_link_relative(UDRAW_STORAGE_URL);
            if (strpos($design_file, UDRAW_STORAGE_URL) !== false) {
                $replace = UDRAW_STORAGE_URL;
            }
            $design_file = str_replace($replace, UDRAW_STORAGE_URL, $design_file);
            $is_update = true;
            $design_preview = $udraw_SVG_data['udraw_SVG_design_preview'];
        }
    }
    
    //Set up some paths
    if (is_user_logged_in()) {
        $_asset_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/assets/';
        $_output_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/output/';
        $_export_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/export/';

        $_asset_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/assets/';
        $_output_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/output/';
        $_export_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/export/';
    } else {
        $session_id = $uDraw_SVG->get_session_id();
        if (strlen($cart_item_key) > 0) {
            if (isset($udraw_SVG_data['session_id']) && strlen($udraw_SVG_data['session_id']) > 0) {
                $session_id = $udraw_SVG_data['session_id'];
            }
        }
        $_asset_path = UDRAW_STORAGE_DIR . '_' . $session_id . '_' . '/assets/';
        $_output_path = UDRAW_STORAGE_DIR . '_' . $session_id . '_' . '/output/';
        $_export_path = UDRAW_STORAGE_DIR . '_' . $session_id . '_' . '/export/';

        $_asset_path_url = UDRAW_STORAGE_URL . '_' . $session_id . '_' . '/assets/';
        $_output_path_url = UDRAW_STORAGE_URL . '_' . $session_id . '_' . '/output/';
        $_export_path_url = UDRAW_STORAGE_URL . '_' . $session_id . '_' . '/export/';
    }
    if (!file_exists($_asset_path)) { wp_mkdir_p($_asset_path); }
    if (!file_exists($_output_path)) { wp_mkdir_p($_output_path); }
    if (!file_exists($_export_path)) { wp_mkdir_p($_export_path); }
    
    $uDraw = new uDraw();
    $uDraw_SVG = new uDraw_SVG();
    $template_count = $uDraw_SVG->get_udraw_SVG_template_count();
    if ($template_count <= 2 || $uDraw->is_udraw_okay()) {
        $included = include_once(UDRAW_PLUGIN_DIR . '/designer/designer-header-init.php');
    ?>
    <div class="designer_wrapper">
        <?php $uDraw_SVG->include_svg_designer(false, $allow_custom_objects, $allow_background_colour, $allow_rotate_template); ?>
    </div>

    <?php if ($svg_settings['udraw_SVGDesigner_display_proof']) { ?>
        <div class="udraw_product_proofing">
            <div>
                <a href="#" class="btn btn-danger proofing_return_to_design">
                    <i class="fas fa-chevron-left" style="margin-right: 5px;"></i>
                    <span class="desktop_only" data-i18n="[html]return_to_design"></span>
                </a>
                <a href="#" data-udrawSVG="confirm_add_to_cart" class="btn btn-success">
                    <span class="desktop_only" data-i18n="[html]add_to_cart"></span>
                    <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                </a>
            </div>
            <ul class="proofing_list"></ul>
        </div>
    <?php } ?>

    <form method="POST" action="" name="udraw_save_later_form" id="udraw_save_later">
        <input type="hidden" name="udraw_save_product_data" value="" />
        <input type="hidden" name="udraw_save_product_preview" value="" />
        <input type="hidden" name="udraw_save_post_id" value="<?php echo $post->ID ?>" />
        <input type="hidden" name="udraw_save_access_key" value="<?php echo $udraw_access_key; ?>" />
        <input type="hidden" name="udraw_is_saving_for_later" value="1" />
        <input type="hidden" name="udraw_price_matrix_selected_by_user" value="" />
        <input type="hidden" name="udraw_selected_variations" value="" />
        <?php wp_nonce_field('save_udraw_customer_design'); ?>
    </form>
<?php 
    } else {
        ?>
        <script>
            jQuery(document).ready(function($){
                $('[data-udrawsvg="design_now"]').on('click', function(){
                    window.alert('<?php _e('An error had occured with the designer. Please contact support.', 'udraw_svg'); ?>');
                });
            });
        </script>
        <?php
    }
}

if (strlen($udraw_access_key) === 0 && strlen($cart_item_key) === 0) {
    if (substr($design_file, strlen($design_file) - 4) === 'json' && strrpos($design_file, '_templates_') !== false) {
        //Copy all the svg files inside the json file, and rename them
        $replace = wp_make_link_relative(UDRAW_STORAGE_URL);
        if (strpos($design_file, UDRAW_STORAGE_URL) !== false) {
            $replace = UDRAW_STORAGE_URL;
        }
        $json_file_dir = str_replace($replace, UDRAW_STORAGE_DIR, $design_file);
        $contents = json_decode(file_get_contents($json_file_dir));
        
        $SVG_templates_handler = new SVG_templates_handler();
        $rand_id = $SVG_templates_handler->make_uniqid_folder_id($_output_path);
        $_dir = $_output_path . $rand_id;
        $_url = $_output_path_url . $rand_id;
        wp_mkdir_p($_dir);
        
        for ($i = 0; $i < count($contents->pages); $i++) {
            $_svg = $contents->pages[$i]->design_file;
            $_preview = $contents->pages[$i]->preview_url;
            $_replace = wp_make_link_relative(UDRAW_STORAGE_URL);
            if (strpos($_svg, UDRAW_STORAGE_URL) !== false) {
                $_replace = UDRAW_STORAGE_URL;
            }
            $_svg_dir = str_replace($_replace, UDRAW_STORAGE_DIR, $_svg);
            $svg_contents = file_get_contents($_svg_dir);
            $new_file = $rand_id . '_page_' . $i;
            file_put_contents($_dir . '/' . $new_file . '.svg', $svg_contents);
            //Preview image
            $_preview_dir = str_replace($_replace, UDRAW_STORAGE_DIR, $_preview);
            $preview_contents = file_get_contents($_preview_dir);
            file_put_contents($_dir . '/'. $new_file . '.png', $preview_contents);
            
            $contents->pages[$i]->design_file = $_url . '/' . $new_file . '.svg';
            $contents->pages[$i]->preview_url = $_url . '/' . $new_file . '.png';
        }
        $contents->session_id = $rand_id;
        file_put_contents($_dir . '/'. $rand_id . '.json', json_encode($contents));
        
        $design_file = $_url . '/'. $rand_id . '.json';
    }
}

?>
<script>
    var cart_item_key = '<?php echo $cart_item_key ?>';
        
    function __load_settings() {
        RacadSVGDesigner.settings.handler_file = '<?php echo admin_url('admin-ajax.php'); ?>';
        RacadSVGDesigner.settings.local_font_path = '<?php echo wp_make_link_relative(UDRAW_FONTS_URL); ?>';
        RacadSVGDesigner.settings.upload_path =  '<?php echo wp_make_link_relative($_asset_path_url); ?>';
        RacadSVGDesigner.settings.output_path = '<?php echo wp_make_link_relative($_output_path_url); ?>';
        RacadSVGDesigner.settings.export_path = '<?php echo wp_make_link_relative($_export_path_url); ?>';
        RacadSVGDesigner.settings.image_placeholder_src = '<?php echo wp_make_link_relative(UDRAW_SVG_URL . '/SVGDesigner/images/add_photo_placeholder.png'); ?>';
        RacadSVGDesigner.settings.facebook_client_id = '';
        RacadSVGDesigner.settings.locale = '<?php echo get_locale(); ?>';
        RacadSVGDesigner.settings.locales_path = '<?php echo wp_make_link_relative(UDRAW_SVG_LOCALE_URL); ?>';
        RacadSVGDesigner.settings.design_file = '<?php echo $design_file; ?>';
        
        <?php if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
            RacadSVGDesigner.settings.facebook_client_id = '<?php echo $_udraw_settings['designer_facebook_app_id'] ?>';
        <?php } ?>
        <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
            RacadSVGDesigner.settings.instagram_client_id = '<?php echo $_udraw_settings['designer_instagram_client_id']?>';
        <?php } ?>
        <?php if ($_udraw_settings['designer_enable_google_functions']) { ?>
            RacadSVGDesigner.settings.google_api_key = '<?php echo $_udraw_settings['designer_google_api_key'] ?>';
            RacadSVGDesigner.settings.google_client_id = '<?php echo $_udraw_settings['designer_google_client_id'] ?>';
            RacadSVGDesigner.settings.google_photos_src = '<?php echo UDRAW_SVG_IMAGE_URL ?>Google_Photos_icon.svg',
        <?php } ?>
        <?php if(isset($session_id) && strlen($session_id) > 0) { ?>
            RacadSVGDesigner.settings.session_id = '<?php echo $session_id ?>';
        <?php } ?>
        <?php if ($svg_settings['udraw_SVGDesigner_enable_dpi']) { ?>
            RacadSVGDesigner.settings.minimum_dpi_requirement = parseInt('<?php echo $svg_settings['udraw_SVGDesigner_minimum_dpi'] ?>');
        <?php } ?>
        <?php if ($svg_settings['udraw_SVGDesigner_enable_stock_images']) { ?>
            RacadSVGDesigner.settings.load_private_images = true;
            RacadSVGDesigner.settings.private_image_library_path = '<?php echo UDRAW_CLIPART_URL ?>';
        <?php } ?>
        <?php if (isset($display_layers) && $display_layers) { ?>
            RacadSVGDesigner.settings.display_layers = !parseInt('<?php echo $display_layers ?>');
        <?php } ?>
        <?php if ($svg_settings['udraw_SVGDesigner_display_image_name']) { ?>
            RacadSVGDesigner.settings.display_image_name = true;
        <?php } ?>
        <?php if ($svg_settings['udraw_SVGDesigner_embed_images']) { ?>
            RacadSVGDesigner.settings.embed_images = true;
        <?php } ?>
        <?php if ($svg_settings['udraw_SVGDesigner_skin'] === 'default') { ?>
            RacadSVGDesigner.settings.use_default_cropper = false;
            RacadSVGDesigner.settings.images_in_placeholder_draggable = false;
            RacadSVGDesigner.settings.prevent_editing_when_dragging = true;
        <?php } ?>
        
        RacadSVGDesigner.handler_actions.save = 'udraw_SVGDesigner_save_svg';
        RacadSVGDesigner.handler_actions.load = 'udraw_SVGDesigner_read_svg';
        RacadSVGDesigner.handler_actions.upload_image = 'udraw_SVGDesigner_upload_image';
        RacadSVGDesigner.handler_actions.uploaded_images = 'udraw_SVGDesigner_uploaded_images';
        RacadSVGDesigner.handler_actions.download_image = 'udraw_SVGDesigner_download_image';
        RacadSVGDesigner.handler_actions.export_image = 'udraw_SVGDesigner_export_image';
        RacadSVGDesigner.handler_actions.local_fonts = 'udraw_SVGDesigner_local_fonts';
        RacadSVGDesigner.handler_actions.authenticate_instagram = 'udraw_SVGDesigner_authenticate_instagram';
        RacadSVGDesigner.handler_actions.retrieve_instagram = 'udraw_SVGDesigner_retrieve_instagram';
        RacadSVGDesigner.handler_actions.check_templates = 'udraw_SVGDesigner_get_templates_count';
        RacadSVGDesigner.handler_actions.check_license = 'udraw_SVGDesigner_check_license_key';
        RacadSVGDesigner.handler_actions.save_page = 'udraw_SVGDesigner_save_page';
        RacadSVGDesigner.handler_actions.create_page = 'udraw_SVGDesigner_create_page';
        RacadSVGDesigner.handler_actions.get_private_images_library = 'udraw_retrieve_clipart';
        RacadSVGDesigner.handler_actions.convert_url_to_base64 = 'udraw_convert_url_to_base64';
        RacadSVGDesigner.handler_actions.upload_pdf_template = 'udraw_svg_upload_pdf_template';
    }
    
    jQuery(document).ready(function($){
        window.use_edit_text_modal = !parseInt('<?php echo $svg_settings['udraw_SVGDesigner_tab_text_editor'] ?>');
        <?php if ($is_update) { ?>
            $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_designer_loaded', function(){
                $('input[name="udraw_SVG_product"]').val(true);
                $('input[name="udraw_SVG_product_data"]').val('<?php echo $design_file ?>');
                $('input[name="udraw_SVG_product_preview"]').val(RacadSVGDesigner.settings.output_path + '<?php echo $design_preview ?>');
                $('[name="udraw_svg_product_cart_item_key"]').val('<?php echo $cart_item_key ?>');
                $('input[name="udraw_SVG_session_id"]').val('<?php echo $session_id ?>');
            });
        <?php } ?>
        //Add some padding/margin to top of modals
        if ($('#wpadminbar').is(':visible')) {
            $(window).on('resize',function(){
                var height = $('#wpadminbar').height();
                $('div.udraw_modal div.modal-content').css('margin-top', height);
            }).trigger('resize');
        }
        $('[data-udrawSVG="design_now"]').on('click', function(){
            //Scroll to top of page, add designer_open class to body to keep scroll hidden
            //$('body').addClass('designer_open');
            $('div.designer_wrapper').addClass('active');
            $('html,body').animate({
                scrollTop: $('[data-udrawsvg="SVGDesigner"]').offset().top
            });
        });
        $('[data-udrawSVG="back_to_options"]').on('click', function(){
            $('div.designer_wrapper').removeClass('active');
            $('body').removeClass('designer_open');
        });
        $('[data-udrawSVG="add_to_cart"]').on('click', function(){
            <?php if ($svg_settings['udraw_SVGDesigner_display_proof']) { ?>
                RacadSVGDesigner.Zoom.previous_zoom = RacadSVGDesigner.Zoom.current_zoom;
                //Display proofing div instead of confirmation modal
                build_proofing_images();
                $('div.designer_wrapper').removeClass('active');
                $('body').removeClass('designer_open');
                $('div.udraw_product_proofing').addClass('active');
            <?php } else { ?>
                $('[data-udrawSVG="confirm_add_to_cart_modal"]').modal('show');
            <?php } ?>
        });
        <?php if ($svg_settings['udraw_SVGDesigner_display_proof']) { ?>
            $('a.proofing_return_to_design').on("click", function(){
                $('div.designer_wrapper').addClass('active');
                $('body').addClass('designer_open');
                $('div.udraw_product_proofing').removeClass('active');
                
                //Hide progress modal and display canvas
                var previous_zoom = RacadSVGDesigner.Zoom.previous_zoom || 1;
                RacadSVGDesigner.Zoom.zoom_canvas(previous_zoom, function(){
                    RacadSVGDesigner.Zoom.previous_zoom = undefined;
                    $('#svg_canvas').show();
                    $('[data-udrawsvg="progress_modal"]').modal('hide');
                });
            });
        <?php } ?>
        $('[data-udrawSVG="confirm_add_to_cart"]').on('click', function(){
            RacadSVGDesigner.Save.file(function(response){
                $('[name="udraw_SVG_product"]').val(true);
                $('input[name="udraw_SVG_session_id"]').val(RacadSVGDesigner.settings.session_id);
                var design_file = RacadSVGDesigner.settings.design_file;
                if (design_file.substring(design_file.length - 4) === 'json') {
                    $('[name="udraw_SVG_product_data"]').val(RacadSVGDesigner.settings.design_file);
                    $('[name="udraw_SVG_product_preview"]').val(RacadSVGDesigner.Pages.list[0].preview_url);
                } else {
                    $('[name="udraw_SVG_product_data"]').val(response.output_path + response.document_name);
                    $('[name="udraw_SVG_product_preview"]').val(RacadSVGDesigner.settings.output_path + response.preview_image);
                }
                if (typeof RacadSVGDesigner.before_add_to_cart === 'function') {
                    RacadSVGDesigner.before_add_to_cart(function(){
                        $('[name="add-to-cart"], button.single_add_to_cart_button').trigger('click');
                    });
                } else {
                    $('[name="add-to-cart"], button.single_add_to_cart_button').trigger('click');
                }
            });
        });
        //Load saved design
        <?php if (strlen($udraw_access_key) > 0) { ?>
            $('[data-udrawSVG="SVGDesigner"]').on('udraw_svg_designer_loaded', function(){
                var design_string = '<?php echo $design; ?>';
                RacadSVGDesigner.Load.file(design_string, function(){
                    RacadSVGDesigner.Zoom.scaleToFit();
                });
            });
        <?php } ?>
        //Save design
        $('[data-udrawSVG="save_design"]').on('click', function() {
            RacadSVGDesigner.Save.file(function (response) {
                $('input[name="udraw_save_product_data"]').val(response.output_path + response.document_name);
                $('input[name="udraw_save_product_preview"]').val(response.preview_image);
                if (typeof selectedByUser !== 'undefined') {
                    var pm_options = {
                        options: selectedByUser,
                        quantity: $('input[name="udraw_price_matrix_qty"]').val(),
                        dimensions: {
                            width: $('[name="txtWidth"]').val(),
                            height: $('[name="txtHeight"]').val()
                        }
                    }
                    $('input[name="udraw_price_matrix_selected_by_user"]').val(JSON.stringify(pm_options));
                } else if ($('table.variations').length > 0) {
                    var variation_options = new Array();
                    $('select.variation_select').each(function(){
                        var object = {
                            name: $(this).attr('name'),
                            value: $(this).val()
                        }
                        variation_options.push(object);
                    });
                    $('input[name="udraw_selected_variations"]').val(JSON.stringify(variation_options));
                }
                $('#udraw_save_later').submit();
            });
        });
        
        function build_proofing_images () {
            //Save current page
            var current_page = RacadSVGDesigner.settings.current_page;
            RacadSVGDesigner.Pages.process_data(current_page, function (svg, current_index) {
                RacadSVGDesigner.svg_to_png(svg, function (preview_data) {
                    var proofing_list = $('div.udraw_product_proofing ul.proofing_list');
                    proofing_list.empty();
                    var pages = RacadSVGDesigner.Pages.list;
                    for (let i = 0; i < pages.length; i++) {
                        var page = pages[i];
                        var preview_url = '';
                        var page_name = page.label ? page.label : '';
                        if (i === current_index) {
                            preview_url = preview_data;
                        } else {
                            for (var prop in page) {
                                if (prop.indexOf('preview') !== -1) {
                                    preview_url = page[prop];
                                    break;
                                }
                            }
                        }

                        var _image = $('<img />').attr('src', preview_url);
                        var _label = $('<h6></h6>').text(page_name).addClass('text-center');
                        var _li = $('<li></li>').append(_image, _label);
                        proofing_list.append(_li);
                    }
                });
            });
        }
        <?php echo $svg_settings['udraw_svg_js_hook']; ?>
    });
</script>
<style>
    form.cart button[name="add-to-cart"] {
        display: none;
    }
    div.designer_wrapper {
        position: absolute;
        width: 95vw;
        height: 95vh;
        top: -9999px;
        left: -9999px;
        z-index: -1;
        background: #fff;
        visibility: hidden;
    }
    <?php if ($background_image_isset) { ?>
        [data-udrawsvg="SVGDesigner"] {
            background-image: url(<?php echo $selected_background_image_url ?>);
            background-size: cover;
            background-position-x: center;
        }
    <?php } else { 
        if (strlen($selected_background_colour) > 0 ) { ?>
        [data-udrawsvg="SVGDesigner"] {
            background: <?php echo $selected_background_colour ?>;
        }
        <?php 
        }
    } ?>
    [data-udrawSVG="SVGDesigner"] div.main_body > div.editing_tips_div {
        color: <?php echo $editing_tips_colour; ?>
    }
    div.designer_wrapper.active{
        z-index: 1050;
        top: 5%;
        left: 2.5%;
        visibility: initial;
    }
    
    div.udraw_product_proofing {
        display: none;
        position: absolute;
        background: white;
        z-index: 1;
        width: 100%;
        min-height: 100%;
    }
    div.udraw_product_proofing.active {
        display: block;
    }
    div.udraw_product_proofing ul.proofing_list {
        list-style-type: none;
        margin-left: 0;
    }
    div.udraw_product_proofing ul.proofing_list li {
        display: inline-block;
        width: 45%;
        vertical-align: top;
    }
    <?php if (!$svg_settings['udraw_SVGDesigner_display_rulers']) { ?>
        [data-udrawSVG="SVGDesigner"] div.canvas_container canvas.ruler {
            display: none;
        }
        [data-udrawSVG="SVGDesigner"] div.canvas_container table td {
            text-align: center;
        }
    <?php } ?>
    <?php echo $svg_settings['udraw_svg_css_hook']; ?>
</style>