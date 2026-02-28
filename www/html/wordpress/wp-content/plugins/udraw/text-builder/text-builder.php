<?php
if (is_user_logged_in()) {
    // Physical Path
    $_output_path = UDRAW_STORAGE_DIR . '_templates_/output/';
    
    // Web Path
    $_output_path_url = UDRAW_STORAGE_URL . '_templates_/output/';
} else {
    exit();
}

if (!file_exists($_output_path)) { wp_mkdir_p($_output_path); }

$template_id = 0;
$key = '';
$name = '';
if (isset($_REQUEST['template_id']) && isset($_REQUEST['key'])) {
    global $wpdb;
    $template_id = $_REQUEST['template_id'];
    $key = $_REQUEST['key'];
    $table = $wpdb->prefix . 'udraw_text_templates';
    $name = $wpdb->get_var("SELECT name FROM $table WHERE ID=$template_id AND public_key='$key'");
}
?>

<div class="wrap">
    <label>Template Name: </label>
    <input type="text" name="template_name" placeholder="Template name" value="<?php echo $name; ?>" />
    <div data-udrawTextBuilder="loading_overlay">
        <i class="fas fa-pulse fa-spinner fa-4x"></i>
    </div>
    <div data-udrawTextBuilder="textBuilder">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <span class="navbar-brand">Text Builder</span>

            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-udrawTextBuilder="settings">Settings</a>
                        <a class="dropdown-item" href="#" data-udrawTextBuilder="save">Save</a>
                    </div>
                </li>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Add
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="javascript: RacadTextBuilder.addText();">Text</a>
                        <!--<a class="dropdown-item" href="#" onclick="javascript: RacadTextBuilder.addText(true);">Curved Text</a>-->
                    </div>
                </li>
            </ul>
        </nav>
        <div class="body_container">
            <div class="canvas_container">
                <canvas data-udrawTextBuilder="canvas" width="500" height="500"></canvas>
            </div>

            <div class="modal_container">
                <div class="modal textbuilder_modal" role="dialog" data-udrawTextBuilder="text_editor">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Text</h6>
                                <div>
                                    <a href="#" class="minimize">
                                        <i class="fas fa-window-minimize"></i>
                                    </a>
                                    <a href="#" class="close">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <textarea data-udrawTextBuilder="text"></textarea>
                                </div>
                                <div class="form-group">
                                    <i class="fas fa-fill"></i>
                                    <input type="hidden" data-udrawTextBuilder="colourpicker" value="#000" />
                                </div>
                                <hr />
                                <div class="form-group row">
                                    <div class="col-6">
                                        <i class="fas fa-paint-brush"></i>
                                        <input type="hidden" data-udrawTextBuilder="stroke_colourpicker" value="#000" />
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-text-width"></i>
                                        <input type="number" data-udrawTextBuilder="stroke_width" value="1" min="0" step="0.1" />
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group row">
                                    <div class="col-6">
                                        <select data-udrawTextBuilder="font_family"></select>
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-font"></i>
                                        <input type="number" step="any" min="0" value="20" data-udrawTextBuilder="fontSize" />
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-text-height"></i>
                                        <input type="number" step="any" value="1" data-udrawTextBuilder="lineHeight" />
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-font"></i>
                                        <i class="fas fa-arrows-alt-h"></i>
                                        <i class="fas fa-bold"></i>
                                        <input type="number" step="any" value="0" data-udrawTextBuilder="charSpacing" style="width: 50%;" />
                                    </div>
                                    <div class="col-6 curvedText">
                                        <i class="fas fa-circle-notch"></i>
                                        <input type="number" step="any" min="1" value="25" data-udrawTextBuilder="radius" />
                                    </div>
                                    <div class="col-6 curvedText">
                                        <a href="#" class="btn btn-outline-secondary" data-udrawTextBuilder="flip_curve">Flip</a>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <a href="#" class="font_style_btn btn btn-outline-secondary" data-fontStyle="bold">
                                        <i class="fas fa-bold"></i>
                                    </a>
                                    <a href="#" class="font_style_btn btn btn-outline-secondary" data-fontStyle="italic">
                                        <i class="fas fa-italic"></i>
                                    </a>
                                    <a href="#" class="font_style_btn btn btn-outline-secondary" data-fontStyle="underline">
                                        <i class="fas fa-underline"></i>
                                    </a>
                                    <a href="#" class="font_style_btn btn btn-outline-secondary" data-fontStyle="strikethrough">
                                        <i class="fas fa-strikethrough"></i>
                                    </a>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <a href="#" class="text_align btn btn-outline-secondary" data-align="left">
                                        <i class="fas fa-align-left"></i>
                                    </a>
                                    <a href="#" class="text_align btn btn-outline-secondary" data-align="center">
                                        <i class="fas fa-align-center"></i>
                                    </a>
                                    <a href="#" class="text_align btn btn-outline-secondary" data-align="right">
                                        <i class="fas fa-align-right"></i>
                                    </a>
                                    <a href="#" class="text_align btn btn-outline-secondary" data-align="justify">
                                        <i class="fas fa-align-justify"></i>
                                    </a>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <a href="#" class="object_align btn btn-outline-secondary" data-align="left" data-orientation="horizontal">
                                        <img src="<?php echo UDRAW_PLUGIN_URL ?>/text-builder/images/align-left.png" />
                                    </a>
                                    <a href="#" class="object_align btn btn-outline-secondary" data-align="middle" data-orientation="horizontal">
                                        <img src="<?php echo UDRAW_PLUGIN_URL ?>/text-builder/images/align-horizontal-middle.png" />
                                    </a>
                                    <a href="#" class="object_align btn btn-outline-secondary" data-align="right" data-orientation="horizontal">
                                        <img src="<?php echo UDRAW_PLUGIN_URL ?>/text-builder/images/align-right.png" />
                                    </a>
                                    <a href="#" class="object_align btn btn-outline-secondary" data-align="top" data-orientation="vertical">
                                        <img src="<?php echo UDRAW_PLUGIN_URL ?>/text-builder/images/align-top.png" />
                                    </a>
                                    <a href="#" class="object_align btn btn-outline-secondary" data-align="middle" data-orientation="vertical">
                                        <img src="<?php echo UDRAW_PLUGIN_URL ?>/text-builder/images/align-vertical-middle.png" />
                                    </a>
                                    <a href="#" class="object_align btn btn-outline-secondary" data-align="bottom" data-orientation="vertical">
                                        <img src="<?php echo UDRAW_PLUGIN_URL ?>/text-builder/images/align-bottom.png" />
                                    </a>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="delete" aria-label="Delete" data-udrawTextBuilder="delete">
                                    <span aria-hidden="true" class="far fa-trash-alt"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal" role="dialog" data-udrawTextBuilder="settings_modal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Settings</h3>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col width">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Width</span>
                                            </div>
                                            <input type="number" class="form-control" placeholder="500" data-udrawTextBuilder="document_width">
                                            <div class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </div>
                                        </div>
                                        <small class="error">Please enter a valid value.</small>
                                    </div>
                                    <div class="col height">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Height</span>
                                            </div>
                                            <input type="number" class="form-control" placeholder="500" data-udrawTextBuilder="document_height">
                                            <div class="input-group-append">
                                                <span class="input-group-text">px</span>
                                            </div>
                                        </div>
                                        <small class="error">Please enter a valid value.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-udrawTextBuilder="apply_settings">Apply Changes</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal textbuilder_modal" role="dialog" data-udrawTextBuilder="layers_modal">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Layers</h6>
                                <div>
                                    <a href="#" class="minimize">
                                        <i class="fas fa-window-minimize"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="modal-body">
                                <ul data-udrawTextBuilder="layers_list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style> 
    [data-udrawTextBuilder="textBuilder"] [data-udrawTextBuilder="settings_modal"] input {
        height: 100%;
    }
</style>

<script>
    jQuery(document).ready(function($){            
        RacadTextBuilder.settings.handler_file      = '<?php echo admin_url('admin-ajax.php'); ?>';
        RacadTextBuilder.settings.output_path       = '<?php echo $_output_path_url; ?>';
        RacadTextBuilder.settings.unique_id         = '<?php echo $key; ?>';
        RacadTextBuilder.settings.template_id       =  <?php echo $template_id; ?>;
        RacadTextBuilder.settings.local_font_path   = '<?php echo wp_make_link_relative(UDRAW_FONTS_URL); ?>';
        RacadTextBuilder.settings.use_local_fonts   = true;
        
        RacadTextBuilder.handler_actions.save           = 'udraw_text_templates_save';
        RacadTextBuilder.handler_actions.load           = 'udraw_text_templates_load';
        RacadTextBuilder.handler_actions.load_fonts     = 'udraw_text_templates_load_fonts';
        RacadTextBuilder.handler_actions.load_fonts_css = 'udraw_text_templates_load_fonts_css';
        
        $('[data-udrawTextBuilder="textBuilder"]').on('json_saved', function(e){
            let response = e.response;
            let template_name = $('[name="template_name"]').val();
            let template_id = RacadTextBuilder.settings.template_id;
            $.ajax({
                method: 'POST',
                dataType: "json",
                url: RacadTextBuilder.settings.handler_file,
                data: {
                    action          : 'udraw_text_templates_save_db',
                    data_path       : response.data_path,
                    preview_path    : response.preview_path,
                    template_name   : template_name,
                    unique_id       : response.unique_id,
                    id              : template_id
                },
                success: function (response) {
                    let status = 'added';
                    if (template_id) {
                        status = 'updated';
                    }
                    location.href = `?page=udraw_text_template&udraw_action=${status}`;
                },
                error: function (error) {
                    console.error(error);
                }
            })
        });
    });
</script>