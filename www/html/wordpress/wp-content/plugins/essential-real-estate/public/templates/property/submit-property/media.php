<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:44 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_property_fields;
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e( 'Property Media', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-media">
        <div class="ere-property-gallery">
            <label class="media-gallery-title"><?php esc_html_e( 'Photo Gallery', 'essential-real-estate' ); ?></label>
            <div class="media-gallery">
	            <div id="property_gallery_thumbs_container" class="row">
	            </div>
            </div>
            <div id="ere_gallery_plupload_container" class="media-drag-drop">
                <h4>
                    <i class="fa fa-cloud-upload"></i> <?php esc_html_e('Drag and drop file here', 'essential-real-estate'); ?>
                </h4>
                <h4><?php esc_html_e('or', 'essential-real-estate'); ?></h4>
                <button type="button" id="ere_select_gallery_images"
                        class="btn btn-primary"><?php esc_html_e('Select Images', 'essential-real-estate'); ?></button>
            </div>
            <div id="ere_gallery_errors_log"></div>
        </div>
        <?php if (!in_array("property_attachments", $hide_property_fields)): ?>
        <label class="media-attachments-title"><?php esc_html_e( 'File Attachments', 'essential-real-estate' ); ?></label>
        <div class="ere-property-attachments">
            <div class="media-attachments">
	            <div id="property_attachments_thumbs_container" class="row">
	            </div>
            </div>
            <div id="ere_attachments_plupload_container" class="media-drag-drop">
                <h4>
                    <i class="fa fa-cloud-upload"></i> <?php esc_html_e('Drag and drop file here', 'essential-real-estate'); ?>
                </h4>
                <h4><?php esc_html_e('or', 'essential-real-estate'); ?></h4>
                <button type="button" id="ere_select_file_attachments"
                        class="btn btn-primary"><?php esc_html_e('Select Files', 'essential-real-estate'); ?></button>
                <p><?php
                    $attachment_file_type=ere_get_option('attachment_file_type','pdf,txt,doc,docx');
                    echo  wp_kses_post(sprintf(__('Allowed Extensions: <span class="attachment-file-type">%s</span>','essential-real-estate'),$attachment_file_type));
                    ?></p>
            </div>
            <div id="ere_attachments_errors_log"></div>
        </div>
        <?php endif; ?>
        <div class="property-media-other row">
            <?php if (!in_array("property_video_url", $hide_property_fields)): ?>
            <div class="property-video-url col-sm-6">
                <label for="property_video_url"><?php esc_html_e('Video URL', 'essential-real-estate'); ?></label>
                <input type="text" class="form-control" name="property_video_url" id="property_video_url"
                       placeholder="<?php esc_attr_e('YouTube, Vimeo', 'essential-real-estate'); ?>">
            </div>
            <?php endif; ?>
            <?php if (!in_array("property_image_360", $hide_property_fields)) : ?>
            <div class="property-image-360 col-sm-6">
                <label for="image_360_url"><?php esc_html_e('Image 360', 'essential-real-estate'); ?></label>
                <div id="ere_image_360_plupload_container" class="file-upload-block">
                    <input
                        name="property_image_360_url"
                        type="text"
                        id="image_360_url"
                        class="ere_image_360_url form-control" value="">
                    <button type="button" id="ere_select_images_360" style="position: absolute" title="<?php esc_attr_e('Choose image','essential-real-estate') ?>" class="ere_image360"><i class="fa fa-file-image-o"></i></button>
                    <input type="hidden" class="ere_image_360_id"
                           name="property_image_360_id"
                           value="" id="ere_image_360_id"/>
                </div>
                <div id="ere_image_360_errors_log"></div>
                <div id="ere_property_image_360_view" data-plugin-url="<?php echo esc_url(ERE_PLUGIN_URL); ?>">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>