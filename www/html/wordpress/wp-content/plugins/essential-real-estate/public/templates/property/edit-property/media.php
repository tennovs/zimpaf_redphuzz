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
global $property_data,$hide_property_fields,$property_meta_data;
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 property-fields-title">
        <h2><?php esc_html_e( 'Property Media', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-media">
        <label class="media-gallery-title"><?php esc_html_e( 'Photo Gallery', 'essential-real-estate' ); ?></label>
        <div class="ere-property-gallery">
            <div class="media-gallery">
	            <div id="property_gallery_thumbs_container" class="row">
		            <?php
		            $property_images = get_post_meta( $property_data->ID,ERE_METABOX_PREFIX. 'property_images', true );
		            $property_images = explode('|', $property_images);
		            $featured_image_id = get_post_thumbnail_id( $property_data->ID );
		            if($featured_image_id) {
			            $property_images[] = $featured_image_id;
		            }
		            $property_images = array_unique($property_images);
		            if( !empty($property_images[0])) {
			            foreach ($property_images as $attach_id) {
				            $is_featured_image = ($featured_image_id == $attach_id);
				            $featured_icon = ($is_featured_image) ? 'fa-star' : 'fa-star-o';
				            echo '<div class="col-sm-2 media-thumb-wrap __thumb">';
				            echo '<figure class="media-thumb">';
				            echo wp_get_attachment_image($attach_id, 'thumbnail');
				            echo '<div class="media-item-actions">';
				            echo '<a class="icon icon-delete" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($attach_id) . '" href="javascript:void(0)">';
				            echo '<i class="fa fa-trash-o"></i>';
				            echo '</a>';
				            echo '<a class="icon icon-fav icon-featured" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($attach_id) . '" href="javascript:void(0)">';
				            echo '<i class="fa ' . esc_attr($featured_icon) . '"></i>';
				            echo '</a>';
				            echo '<input type="hidden" class="property_image_ids" name="property_image_ids[]" value="' . intval($attach_id) . '">';
				            echo '<span style="display: none;" class="icon icon-loader">';
				            echo '<i class="fa fa-spinner fa-spin"></i>';
				            echo '</span>';
				            echo '</div>';
				            if ($is_featured_image) {
					            echo '<input type="hidden" class="featured-image-id" name="featured_image_id" value="' . intval($attach_id) . '">';
				            }
				            echo '</figure>';
				            echo '</div>';
			            }
		            }
		            ?>
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
		            <?php
		            $property_attachment_arg =  get_post_meta( $property_data->ID,ERE_METABOX_PREFIX. 'property_attachments', false );
		            $property_attachments=(isset($property_attachment_arg) && is_array($property_attachment_arg) && count( $property_attachment_arg ) > 0)? $property_attachment_arg[0]: '';
		            $property_attachments = explode('|', $property_attachments);
		            $property_attachments = array_unique($property_attachments);
		            if($property_attachment_arg && !empty($property_attachments[0])) {
			            foreach ($property_attachments as $attach_id) {
				            $attach_url = wp_get_attachment_url( $attach_id );
				            $file_type          = wp_check_filetype( $attach_url);
				            $file_type_name = isset( $file_type['ext'] ) ? $file_type['ext'] : '';
				            $thumb_url = ERE_PLUGIN_URL . 'public/assets/images/attachment/attach-' . $file_type_name . '.png';
				            $file_name          = basename($attach_url);
				            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 media-thumb-wrap __thumb">';
				            echo '<figure class="media-thumb">';
				            echo '<img src="'. esc_url($thumb_url)  .'">';
				            echo '<a href="'. esc_url($attach_url) .'">'.  $file_name .'</a>';
				            echo '<div class="media-item-actions">';
				            echo '<a class="icon icon-delete" data-property-id="' . esc_attr(intval($property_data->ID))  . '" data-attachment-id="' . esc_attr(intval($attach_id))  . '" href="javascript:void(0)">';
				            echo '<i class="fa fa-trash-o"></i>';
				            echo '</a>';
				            echo '<input type="hidden" class="property_attachment_ids" name="property_attachment_ids[]" value="' . esc_attr(intval($attach_id))  . '">';
				            echo '<span style="display: none;" class="icon icon-loader">';
				            echo '<i class="fa fa-spinner fa-spin"></i>';
				            echo '</span>';
				            echo '</div>';
				            echo '</figure>';
				            echo '</div>';
			            }
		            }
		            ?>
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
                    echo wp_kses_post(sprintf(__('Allowed Extensions: <span class="attachment-file-type">%s</span>','essential-real-estate'),$attachment_file_type));
                    ?></p>
            </div>
            <div id="ere_attachments_errors_log"></div>
        </div>
        <?php endif; ?>
        <div class="property-media-other row">
            <?php if (!in_array("property_video_url", $hide_property_fields)):?>
                <div class="property-video-url col-sm-6">
                    <label for="property_video_url"><?php esc_html_e('Video URL', 'essential-real-estate'); ?></label>
                    <input type="text" class="form-control" name="property_video_url" id="property_video_url"
                           placeholder="<?php esc_attr_e('YouTube, Vimeo', 'essential-real-estate'); ?>"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_video_url'])) {
                               echo esc_attr($property_meta_data[ERE_METABOX_PREFIX . 'property_video_url'][0]);
                           } ?>">
                </div>
            <?php endif; ?>
            <?php if (!in_array("property_image_360", $hide_property_fields)) :
                $property_image_360_arr = get_post_meta( $property_data->ID,ERE_METABOX_PREFIX. 'property_image_360', false );
                $property_image_360_id=(isset($property_image_360_arr) && is_array($property_image_360_arr) && count( $property_image_360_arr ) > 0)? $property_image_360_arr[0]['id']: '';
                $property_image_360_url=(isset($property_image_360_arr) && is_array($property_image_360_arr) && count( $property_image_360_arr ) > 0)? $property_image_360_arr[0]['url']: '';
            ?>
            <div class="property-image-360 col-sm-6">
                <label for="image_360_url"><?php esc_html_e('Image 360', 'essential-real-estate'); ?></label>
                <div id="ere_image_360_plupload_container" class="file-upload-block">
                    <input
                        name="property_image_360_url"
                        type="text"
                        id="image_360_url"
                        class="ere_image_360_url form-control" value="<?php echo esc_url($property_image_360_url); ?>">
                    <button type="button" id="ere_select_images_360" style="position: absolute" title="<?php esc_attr_e('Choose image','essential-real-estate') ?>" class="ere_image360"><i class="fa fa-file-image-o"></i></button>
                    <input type="hidden" class="ere_image_360_id"
                           name="property_image_360_id"
                           value="<?php echo esc_attr($property_image_360_id); ?>" id="ere_image_360_id"/>

                </div>
                <div id="ere_image_360_errors_log"></div>
                <?php if(!empty($property_image_360_url)):?>
                <div id="ere_property_image_360_view" data-plugin-url="<?php echo esc_url(ERE_PLUGIN_URL); ?>">
                    <iframe width="100%" height="200" scrolling="no" allowfullscreen src="<?php echo ERE_PLUGIN_URL."public/assets/packages/vr-view/index.html?image=".esc_url($property_image_360_url); ?>"></iframe>
                </div>
                <?php endif;?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>