<?php
    $uDraw = new uDraw();
    $uDraw_SVG = new uDraw_SVG();
    $_output_path = UDRAW_STORAGE_DIR . '/_templates_/output/';
    $_output_path_url = UDRAW_STORAGE_URL . '/_templates_/output/';
    
    $_export_path = UDRAW_STORAGE_DIR . '/_templates_/export/';
    $_export_path_url = UDRAW_STORAGE_URL . '/_templates_/export/';
    
    $_assets_path = UDRAW_STORAGE_DIR . '/_templates_/assets/';
    $_assets_path_url = UDRAW_STORAGE_URL . '/_templates_/assets/';
    
    if (!file_exists($_output_path)) { wp_mkdir_p($_output_path); }
    if (!file_exists($_export_path)) { wp_mkdir_p($_export_path); }
    if (!file_exists($_export_path)) { wp_mkdir_p($_assets_path); }
    
    $session_id = uniqid();
?>
<div class="modal fade udraw_modal" data-udrawsvg_template="add_template_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 data-udrawsvg_template="add_template_modal_header"></h3>
            </div>
            <div class="modal-body">
                <div>
                    <label>Template Name:</label>
                    <input type="text" value="" data-udrawsvg_template="svg_template_name" placeholder="Template Name" style="margin-left: 5px;"/>
                </div>
                <div class="upload_file_div">
                    <div>
                        <div style="display: table-cell;">
                            <button type="button" data-udrawsvg_template="blank"><?php _e('New Blank Template', 'udraw_svg'); ?></button>
                        </div>
                        <div style="display: table-cell;"></div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            <button type="button" data-udrawsvg_template="file_upload_trigger"></button>
                            <input type="file" data-udrawsvg_template="upload_svg" accept="image/svg+xml" class="hidden"/>
                        </div>
                        <div style="display: table-cell;">
                            <button type="button" data-i18n="[html]show_instructions" class="button-default toggle_instructions"></button>
                            <div class="instructions">
                                <p data-i18n="[html]uploaded_svg_requirements"></p>
                                <p data-i18n="[html]svg_object_restrictions"></p>
                                <p data-i18n="[html]flowtext_warning"></p>
                            </div>
                        </div>
                    </div>
                    <?php if ($uDraw->is_udraw_okay()) { ?>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">
                            <button type="button" data-udrawsvg_template="pdf_file_upload_trigger"><?php _e('Upload PDF', 'udraw_svg'); ?></button>
                            <input type="file" data-udrawsvg_template="upload_pdf" accept="application/pdf" class="hidden"/>
                        </div>
                        <div style="display: table-cell;"></div>
                    </div>
                    <?php } ?>
                </div>
                <div class="template_preview">
                    <div class="edit_template_div hidden" style="width: 100%;">
                        <button type="button" data-udrawsvg_template="edit_template"><?php _e('Edit Template', 'udraw_svg'); ?></button>
                    </div>
                    <div><img src="" data-udrawsvg_template="template_preview"></div>
                    <div class="template_summary hidden">
                        <table data-udrawsvg_template="summary_table">
                            <tbody>
                                <tr>
                                    <td><strong data-i18n="[html]image_placeholder_count"></strong></td>
                                    <td><span data-udrawsvg_template="image_placeholder_count"></span></td>
                                </tr>
                                <tr>
                                    <td><strong data-i18n="[html]text_count"></strong></td>
                                    <td><span data-udrawsvg_template="text_count"></span></td>
                                </tr>
                                <tr>
                                    <td><strong data-i18n="[html]aspect_ratio"></strong></td>
                                    <td><span data-udrawsvg_template="aspect_ratio"></span></td>
                                </tr>
                                <tr>
                                    <td><strong data-i18n="[html]width"></strong></td>
                                    <td><span data-udrawsvg_template="template_width"></span></td>
                                </tr>
                                <tr>
                                    <td><strong data-i18n="[html]height"></strong></td>
                                    <td><span data-udrawsvg_template="template_height"></span></td>
                                </tr>
                                <tr>
                                    <td><strong data-i18n="[html]number_of_pages"></strong></td>
                                    <td><span data-udrawsvg_template="pages_count"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="spinner_div hidden">
                    <i class="fa fa-pulse fa-spinner fa-4x"></i>
                    <br />
                    <span></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]cancel"></button>
                <button type="button" class="btn btn-success" data-udrawsvg_template="save">
                    <i class="fa fa-pulse fa-spinner hidden"></i>
                    <span data-i18n="[html]save"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade udraw_modal" data-udrawsvg_template="edit_template_modal" tabindex="-1" role="dialog"
     style="top: -9999px; left: -9999px; z-index: -1;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="height: 90vh;">
                <?php uDraw_SVG::include_svg_designer(true); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" data-i18n="[html]cancel"></button>
            </div>
        </div>
    </div>
</div>

<script>
    function __load_settings() {
        RacadSVGDesigner.settings.handler_file = '<?php echo admin_url('admin-ajax.php'); ?>';
        RacadSVGDesigner.settings.local_font_path = '<?php echo wp_make_link_relative(UDRAW_FONTS_URL); ?>';
        RacadSVGDesigner.settings.output_path = '<?php echo wp_make_link_relative($_output_path_url); ?>';
        RacadSVGDesigner.settings.export_path = '<?php echo wp_make_link_relative($_export_path_url); ?>';
        RacadSVGDesigner.settings.upload_path = '<?php echo wp_make_link_relative($_assets_path_url); ?>';
        RacadSVGDesigner.settings.image_placeholder_src = '<?php echo wp_make_link_relative(UDRAW_SVG_URL . '/SVGDesigner/images/add_photo_placeholder.png'); ?>';
        RacadSVGDesigner.settings.update_file = true;
        RacadSVGDesigner.settings.locales_path = '<?php echo wp_make_link_relative(UDRAW_SVG_LOCALE_URL);?>';
        RacadSVGDesigner.settings.mode = 'admin';
        RacadSVGDesigner.settings.session_id = '<?php echo $session_id ?>';
        RacadSVGDesigner.settings.load_private_images = true;
        RacadSVGDesigner.settings.private_image_library_path = '<?php echo UDRAW_CLIPART_URL ?>';
        RacadSVGDesigner.settings.display_layers = true;
        RacadSVGDesigner.settings.display_image_name = true;
        RacadSVGDesigner.settings.embed_images = false;
        RacadSVGDesigner.settings.auto_create_new_doc = false;
        
        RacadSVGDesigner.handler_actions.save = 'udraw_SVGDesigner_save_svg';
        RacadSVGDesigner.handler_actions.load = 'udraw_SVGDesigner_read_svg';
        RacadSVGDesigner.handler_actions.upload_image = 'udraw_SVGDesigner_upload_image';
        RacadSVGDesigner.handler_actions.uploaded_images = 'udraw_SVGDesigner_uploaded_images';
        RacadSVGDesigner.handler_actions.download_image = 'udraw_SVGDesigner_download_image';
        RacadSVGDesigner.handler_actions.export_image = 'udraw_SVGDesigner_export_image';
        RacadSVGDesigner.handler_actions.local_fonts = 'udraw_SVGDesigner_local_fonts';
        RacadSVGDesigner.handler_actions.get_udraw_templates = 'udraw_svg_get_udraw_templates';
        RacadSVGDesigner.handler_actions.import_udraw_template = 'udraw_svg_import_udraw_template';
        RacadSVGDesigner.handler_actions.authenticate_instagram = 'udraw_SVGDesigner_authenticate_instagram';
        RacadSVGDesigner.handler_actions.retrieve_instagram = 'udraw_SVGDesigner_retrieve_instagram';
        RacadSVGDesigner.handler_actions.check_templates = 'udraw_SVGDesigner_get_templates_count';
        RacadSVGDesigner.handler_actions.check_license = 'udraw_SVGDesigner_check_license_key';
        RacadSVGDesigner.handler_actions.upload_pdf_template = 'udraw_svg_upload_pdf_template';
        RacadSVGDesigner.handler_actions.save_page = 'udraw_SVGDesigner_save_page';
        RacadSVGDesigner.handler_actions.create_page = 'udraw_SVGDesigner_create_page';
        RacadSVGDesigner.handler_actions.get_private_images_library = 'udraw_retrieve_clipart';
        RacadSVGDesigner.handler_actions.convert_url_to_base64 = 'udraw_convert_url_to_base64';
    }
    jQuery(document).ready(function($){
        window.use_edit_text_modal = true;
        window.new_session_id = '<?php echo $session_id; ?>';
    });
</script>
<style>
    div.template_preview > div {
        vertical-align: top;
        width: 45%;
        display: inline-block;
    }
    img[data-udrawsvg_template="template_preview"] {
        max-width: 100%;
        max-height: 100%;
    }
    table[data-udrawsvg_template="summary_table"] td {
        padding: 5px;
    }
    div.instructions {
        display: none;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 5px;
    }
    div.instructions.active {
        display: block;
    }
    div.svg_container > svg {
        max-width: 150px;
        max-height: 150px;
    }
    div.admin_container input,
    div.admin_container span {
        vertical-align: top;
    }
    div.admin_container input {
        margin: 0;
    }
    div.upload_file_div button {
        width: 100%;
    }
    
    [data-udrawsvg_template="edit_template_modal"] [data-udrawSVG="SVGDesigner"] {
        width: 100%;
    }
    div.spinner_div {
        text-align: center;
    }
</style>