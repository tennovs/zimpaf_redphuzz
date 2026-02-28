<div class="designer_wrapper">
    <?php 
        $uDrawSettings = new uDrawSettings();
        $_udraw_settings = $uDrawSettings->get_settings();
        $svg_settings_class = new uDraw_SVG_settings();
        $svg_settings = $svg_settings_class->get_settings();
    
        $uDraw_SVG = new uDraw_SVG();
        $uDraw_SVG->include_svg_designer(true);
    ?>
</div>

<script>
    function __load_settings() {
        RacadSVGDesigner.settings.handler_file = '<?php echo admin_url('admin-ajax.php'); ?>';
        RacadSVGDesigner.settings.local_font_path = '<?php echo wp_make_link_relative(UDRAW_FONTS_URL); ?>';
        RacadSVGDesigner.settings.image_placeholder_src = '<?php echo wp_make_link_relative(UDRAW_SVG_URL . '/SVGDesigner/images/add_photo_placeholder.png'); ?>';
        RacadSVGDesigner.settings.facebook_client_id = '';
        RacadSVGDesigner.settings.locale = '<?php echo get_locale(); ?>';
        RacadSVGDesigner.settings.locales_path = '<?php echo wp_make_link_relative(UDRAW_SVG_LOCALE_URL); ?>';
        
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
        <?php if ($svg_settings['udraw_SVGDesigner_enable_dpi']) { ?>
            RacadSVGDesigner.settings.minimum_dpi_requirement = parseInt('<?php echo $svg_settings['udraw_SVGDesigner_minimum_dpi'] ?>');
        <?php } ?>
        RacadSVGDesigner.settings.load_private_images = true;
        RacadSVGDesigner.settings.private_image_library_path = '<?php echo UDRAW_CLIPART_URL ?>';
        RacadSVGDesigner.settings.display_layers = true;
        <?php if ($svg_settings['udraw_SVGDesigner_embed_images']) { ?>
            RacadSVGDesigner.settings.embed_images = true;
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
        RacadSVGDesigner.handler_actions.get_private_images_library = 'udraw_retrieve_clipart';
        RacadSVGDesigner.handler_actions.convert_url_to_base64 = 'udraw_convert_url_to_base64';
    }
    jQuery(document).ready(function($){
        window.use_edit_text_modal = true;
        $('[data-udrawSVG="admin_save"]').on('click', function(){
            RacadSVGDesigner.Save.file(function(url){
                url.design_data = undefined;
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php') ?>',
                    type: 'POST',
                    contentType: "application/x-www-form-urlencoded",
                    dataType: "json",
                    data: {
                        action: 'udraw_svg_update_svg_url',
                        order_item_id: RacadSVGDesigner.settings.item_id,
                        order_id: RacadSVGDesigner.settings.order_id,
                        url: url
                    },
                    success: function(response){
                        if (response) {
                            //Rebuild PDF
                            $('.rebuild_svg_pdf[data-order_item_id="' + RacadSVGDesigner.settings.item_id, + '"]').trigger('click');
                        }
                    },
                    error: function (){

                    }
                });
            });
        });
    });
</script>
<style>
    .modal-open .modal {
        overflow: auto!important;
    }
    
    .designer_wrapper {
        width: 100%;
        height: 95%;
    }
    
    [data-udrawSVG="SVGDesigner"] {
        width: 100%;
    }
</style>