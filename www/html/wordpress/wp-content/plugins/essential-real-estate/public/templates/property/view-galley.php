<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 30/12/2016
 * Time: 8:04 SA
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$property_id=get_the_ID();
$property_gallery = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_images', true);
$total_image=0;
if($property_gallery)
{
    $property_gallery = explode( '|', $property_gallery );
    $total_image=count($property_gallery);
}
?>
<div class="property-view-gallery-wrap" data-toggle="tooltip" title="<?php echo esc_attr(sprintf( __( '(%s) Photos', 'essential-real-estate' ), $total_image)) ; ?>">
    <a data-property-id="<?php the_ID(); ?>"
       href="javascript:void(0)" class="property-view-gallery"><i
            class="fa fa-camera"></i></a>
</div>