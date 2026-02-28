<?php
if (is_user_logged_in()) {
	if (!current_user_can('edit_udraw_templates')) {
        exit;
    }
} else {
    exit;
}

global $wpdb;

$udrawSettings = new uDrawSettings();
$_udraw_settings = $udrawSettings->get_settings();

$uDraw = new uDraw();

if (!defined('UDRAW_DESIGNER_IMG_PATH')) {
    define('UDRAW_DESIGNER_IMG_PATH', plugins_url('includes/img/', __FILE__));
}

if (!defined('UDRAW_DESIGNER_INCLUDE_PATH')) {
    define('UDRAW_DESIGNER_INCLUDE_PATH', plugins_url('includes/', __FILE__));
}

$_asset_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/assets/';
$_output_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/output/';
$_pattern_path = UDRAW_STORAGE_DIR . wp_get_current_user()->user_login . '/patterns/';

if (!file_exists($_asset_path)) { wp_mkdir_p($_asset_path); }
if (!file_exists($_output_path)) { wp_mkdir_p($_output_path); }
if (!file_exists($_pattern_path)) { wp_mkdir_p($_pattern_path); }

$_asset_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/assets/';
$_output_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/output/';
$_pattern_path_url = UDRAW_STORAGE_URL . wp_get_current_user()->user_login . '/patterns/';

$_template_id = '';
if (isset($_GET['template_id'])) {
    $_template_id = $_GET['template_id'];
}
$user_session_id = uniqid();

?>
<div id="designer-wrapper">
    <div id="udraw-bootstrap" data-udraw="uDrawBootstrap">
        <?php if (!$uDraw->is_udraw_valid()) { ?>
            <div class="error settings-error" role="alert" style="width: 98%; border-left: 4px solid #FF0000; background: #FFD5D5; height: 34px; font-size: larger; padding-top: 15px;"><strong>Please Note:</strong> You have reached the maximum allowed templates. <a href="admin.php?page=edit_udraw_settings&tab=activation">Upgrade now</a> to full version!</div>
        <?php } ?>
        <div class="input-group col-6" style="padding: 0;">    
            <div class="input-group-prepend"> 
                <span class="input-group-text"><?php _e('Template Name', 'udraw') ?> </span>
            </div>
            <input type="text" id="udraw_template_name_txt" name="udraw_template_name" value="New Template" class="form-control">
        </div>

        <?php include_once(UDRAW_PLUGIN_DIR . '/designer/bootstrap-default/designer-template-wrapper.php'); ?> 

    </div>
</div>
<style>
    .modal {max-width: none !important;}
    body.modal-open {
        overflow: auto!important;
    }
</style>
<script type="text/javascript">
	
    jQuery('#udraw-save-design-btn').click(function () {
        RacadDesigner.Pages.save(true);
        RacadDesigner.HideDesigner();
        RacadDesigner.SaveDesignXML('save');
    });
	
	function __init_udraw(settings) {
	    settings.assetPath = '<?php echo wp_make_link_relative($_asset_path_url); ?>';
	    settings.outputPath = '<?php echo wp_make_link_relative($_output_path_url); ?>';
        settings.handlerFile = ajaxurl;
	    settings.localImagePath = '<?php echo wp_make_link_relative($_asset_path_url); ?>';
	    settings.localPatternsPath = '<?php echo wp_make_link_relative($_pattern_path_url); ?>';
	    settings.localImageDisplayThumbs = true;
	    settings.displayWizard = true;
	    settings.isTemplate = true;
	    settings.relativeImagePath = '<?php echo wp_make_link_relative(UDRAW_DESIGNER_IMG_PATH); ?>';
	    settings.relativeIncludePath = '<?php echo wp_make_link_relative(UDRAW_DESIGNER_INCLUDE_PATH); ?>';
	    settings.virtualAppPath = '';
	    settings.useLocalFonts = true;
	    settings.localFontPath = '<?php echo wp_make_link_relative(UDRAW_FONTS_URL); ?>';
	    settings.privateTemplateApi = ajaxurl + '?action=udraw_get_templates';
	    settings.language = '<?php echo $_udraw_settings['udraw_designer_language']; ?>';
        settings.activationKey = '<?php echo uDraw::get_udraw_activation_key()?>';
        settings.contentUploadPath = '<?php echo wp_make_link_relative(content_url()."/uploads/")?>';
        settings.localesPath = '<?php echo (file_exists(UDRAW_LANGUAGES_DIR.'udraw-'.$_udraw_settings['udraw_designer_language'].'.txt')) ? wp_make_link_relative(UDRAW_LANGUAGES_URL) : wp_make_link_relative(UDRAW_DESIGNER_INCLUDE_PATH.'locales/');  ?>';
        settings.displayOrientation = '<?php echo $_udraw_settings['udraw_designer_display_orientation']; ?>';
        settings.locale = '<?php echo get_locale() ?>';
        <?php			
        if (isset($_GET['template_id'])) {
            global $wpdb;
            $table_name = $_udraw_settings['udraw_db_udraw_templates'];
            $designFile = $wpdb->get_var($wpdb->prepare("SELECT design FROM $table_name WHERE id = %d", $_GET['template_id']));
            $templateName = $wpdb->get_var($wpdb->prepare("SELECT name FROM $table_name WHERE id = %d", $_GET['template_id']));
            if (!empty($designFile)) {
                ?>
                settings.designMode = 'update';
                settings.designFile = '<?php echo $designFile; ?>';
                <?php $templateName = preg_replace('/\'/', '\\\'', $templateName); ?>
                jQuery('#udraw_template_name_txt').val('<?php echo $templateName; ?>');
                <?php
            }
        } ?>
        
        <?php if ($_udraw_settings['designer_disable_image_filters']) { ?>
            settings.disableImageFilters = true;
        <?php } ?>
        
        <?php if ($_udraw_settings['designer_disable_image_fill']) { ?>
            settings.disableImageFill = true;
        <?php } ?>

        <?php if ($_udraw_settings['designer_enable_optimize_large_images']) { ?>
	    settings.enableOptimizedLargeImages = true;
	    <?php } ?>

        <?php if ($_udraw_settings['designer_enable_local_clipart']) { ?>
            settings.privateClipArtPath = '<?php echo wp_make_link_relative(UDRAW_CLIPART_URL); ?>';
        <?php }
        if ($_udraw_settings['designer_enable_facebook_functions']) { ?>
            settings.FBappID = '<?php echo $_udraw_settings['designer_facebook_app_id']?>';
        <?php } else { ?>
            settings.FBappID = '';
        <?php } ?>
        <?php if ($_udraw_settings['designer_enable_instagram_functions']) { ?>
            settings.instagramClientID = '<?php echo $_udraw_settings['designer_instagram_client_id']?>';
        <?php } else { ?>
            settings.instagramClientID = '';
	    <?php } ?>

        <?php if (strlen($_udraw_settings['udraw_designer_global_template_key']) > 0) { ?>
        settings.defaultCategoryApi = 'https://draw.racadtech.com/api/category/<?php echo $_udraw_settings['udraw_designer_global_template_key']; ?>';
	    settings.defaultTemplateApi = 'https://draw.racadtech.com/api/templates/<?php echo $_udraw_settings['udraw_designer_global_template_key']; ?>';
	    <?php } ?>

		return settings;		
	}

    function __init_handler_actions(actions) {
        actions.localFontsList = 'udraw_designer_local_fonts_list';
        actions.localFontsCSS = 'udraw_designer_local_fonts_css';
        actions.localFontsCSSBase64 = 'udraw_designer_local_fonts_css_base64';
        actions.upload = 'udraw_designer_upload';
        actions.save = 'udraw_designer_save';
        actions.finalize = 'udraw_designer_finalize';
        actions.exportPDF = 'udraw_designer_export_pdf';
        actions.asset = 'udraw_designer_asset';
        actions.localImages = 'udraw_designer_local_images';
        actions.removeImage = 'udraw_designer_remove_image';
        actions.localPatterns = 'udraw_designer_local_patterns';
        actions.uploadPatterns = 'udraw_designer_upload_patterns';
        actions.savePageData = 'udraw_designer_save_page_data';
        actions.compileDesignData = 'udraw_designer_compile_design_data';
        actions.authenticate_instagram = 'udraw_designer_authenticate_instagram';
        actions.retrieve_instagram = 'udraw_designer_retrieve_instagram';
        actions.authenticate_flickr = 'udraw_designer_authenticate_flickr';
        actions.flickr_get = 'udraw_designer_flickr_get';
        actions.download_image = 'udraw_designer_download_image';
        actions.export_preview_image = 'udraw_designer_export_preview_image';
        actions.exportImage = 'udraw_designer_export_image';
        actions.textTemplates = 'udraw_designer_get_text_templates';
        actions.get_private_images_library = 'udraw_retrieve_clipart';

        return actions;
    }
    
    function __get_app_id(){
        RacadDesigner.settings.FBappID = '<?php echo $_udraw_settings['designer_facebook_app_id']?>';
    }
	
    function _SaveDesign_Response(data) {
        if (data.errorMessage.length < 2) {
            var response = data.PDFdocument + "," + data.XMLDocument + "," + data.Preview;
            var oPath = RacadDesigner.settings.outputPath;
            var templateName = jQuery('#udraw_template_name_txt').val();
            var templateId = '<?php echo $_template_id; ?>';
            var docWidth = RacadDesigner.documentSize.width/72;
            var docHeight = RacadDesigner.documentSize.height/72;
            var docPages = RacadDesigner.Pages.list.length;
            var request = ajaxurl + '?action=udraw_create_template&response=' + response + '&template_name=' + Base64.encode(templateName) + '&output_path=' + oPath + '&template_id=' + templateId + '&doc_width=' + docWidth + '&doc_height=' + docHeight + '&doc_pages=' + docPages;
            jQuery.get(request, function (data) {
                if (window.location.search !== '?page=udraw_add_template') {
                    window.location.reload();
                } else {
                    window.location = 'admin.php?page=udraw&udraw=add';
                }
            });
        } else {
            // Handle Error Here.
            alert('There was an error saving your design\r\n' + data.errorMessage);
        }
    }
    function _SaveDesignandClose_Response(data) {
        if (data.errorMessage.length < 2) {
            var response = data.PDFdocument + "," + data.XMLDocument + "," + data.Preview;
            var oPath = RacadDesigner.settings.outputPath;
            var templateName = jQuery('#udraw_template_name_txt').val();
            var templateId = '<?php echo $_template_id; ?>';
            var docWidth = RacadDesigner.documentSize.width/72;
            var docHeight = RacadDesigner.documentSize.height/72;
            var docPages = RacadDesigner.Pages.list.length;
            var request = ajaxurl + '?action=udraw_create_template&response=' + response + '&template_name=' + Base64.encode(templateName) + '&output_path=' + oPath + '&template_id=' + templateId + '&doc_width=' + docWidth + '&doc_height=' + docHeight + '&doc_pages=' + docPages;
            jQuery.get(request, function (data) {
                window.location = 'admin.php?page=udraw&udraw=add';
            });
        } else {
            // Handle Error Here.
            alert('There was an error saving your design\r\n' + data.errorMessage);
        }
    }
</script>  
