<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $post;
$property_gallery = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_images', true);
wp_enqueue_style('owl.carousel');
wp_enqueue_script('owl.carousel');
if ($property_gallery):
    $property_gallery = explode('|', $property_gallery); ?>
    <div class="single-property-element property-gallery-wrap">
        <div class="ere-property-element">
            <div class="single-property-image-main owl-carousel manual ere-carousel-manual">
                <?php
                $gallery_id = 'ere_gallery-' . rand();
                foreach ($property_gallery as $image):
                    $image_src = ere_image_resize_id($image, 870, 420, true);
                    $image_full_src = wp_get_attachment_image_src($image, 'full');
                    if (!empty($image_src)) {
                        ?>
                        <div class="property-gallery-item ere-light-gallery">
                            <img src="<?php echo esc_url($image_src) ?>" alt="<?php the_title(); ?>"
                                 title="<?php the_title(); ?>">
                            <a data-thumb-src="<?php echo esc_url($image_full_src[0]); ?>"
                               data-gallery-id="<?php echo esc_attr($gallery_id); ?>"
                               data-rel="ere_light_gallery" href="<?php echo esc_url($image_full_src[0]); ?>"
                               class="zoomGallery"><i
                                    class="fa fa-expand"></i></a>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>
            <div class="single-property-image-thumb owl-carousel manual ere-carousel-manual">
                <?php
                foreach ($property_gallery as $image):
                    $image_src = ere_image_resize_id($image, 250, 130, true);
                    if (!empty($image_src)) { ?>
                        <div class="property-gallery-item">
                            <img src="<?php echo esc_url($image_src) ?>" alt="<?php the_title(); ?>"
                                 title="<?php the_title(); ?>">
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>