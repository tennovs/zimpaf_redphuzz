<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$property_attachment_arg = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_attachments', false);
$property_attachments = (isset($property_attachment_arg) && is_array($property_attachment_arg) && count($property_attachment_arg) > 0) ? $property_attachment_arg[0] : '';
$property_attachments = explode('|', $property_attachments);
$property_attachments = array_unique($property_attachments);
if ($property_attachment_arg && !empty($property_attachments[0])):?>
    <div class="single-property-element property-attachments">
        <div class="ere-heading-style2">
            <h2><?php esc_html_e('File Attachments', 'essential-real-estate'); ?></h2>
        </div>
        <div class="ere-property-element row">
            <?php
            foreach ($property_attachments as $attach_id):
                $attach_url = wp_get_attachment_url($attach_id);
                $file_type = wp_check_filetype($attach_url);
                $file_type_name = isset($file_type['ext']) ? $file_type['ext'] : '';
                if (!empty($file_type_name)):
                    $thumb_url = ERE_PLUGIN_URL . 'public/assets/images/attachment/attach-' . $file_type_name . '.png';
                    $file_name = basename($attach_url);
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 media-thumb-wrap">
                        <figure class="media-thumb">
                            <img src="<?php echo esc_url($thumb_url); ?>">
                        </figure>
                        <div class="media-info">
                            <strong><?php echo esc_html($file_name) ?></strong>
                            <a target="_blank"
                               href="<?php echo esc_url($attach_url); ?>"><?php esc_html_e('Download', 'essential-real-estate'); ?></a>
                        </div>
                    </div>
                    <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
<?php endif; ?>