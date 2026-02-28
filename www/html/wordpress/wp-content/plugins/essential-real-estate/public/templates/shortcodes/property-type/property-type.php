<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$property_type = $type_image = $image_size = $el_class = '';
extract( shortcode_atts( array(
	'property_type' => '',
	'type_image' => '',
	'image_size' => 'full',
	'el_class' => ''
), $atts ) );

$property_item_class = array();

$wrapper_classes = array(
	'ere-property-type',
	$el_class
);

$property_type = get_term_by( 'slug', $property_type, 'property-type', 'OBJECT' );
if(!$property_type) return;
$type_name = $property_type->name;
$type_slug = $property_type->slug;
$type_count = $property_type->count;

$image_src = '';
$width = '';
$height = '';

if(!empty( $type_image )) {
	if ( preg_match( '/\d+x\d+/', $image_size ) ) {
		$image_size = explode( 'x', $image_size );
		$image_src  = ere_image_resize_id( $type_image, $image_size[0], $image_size[1], true );
	} else {
		if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
			$image_size = 'full';
		}
		$image_src = wp_get_attachment_image_src( $type_image, $image_size );
		if ( $image_src && ! empty( $image_src ) ) {
			$image_src = $image_src[0];
		}
	}
	if(!empty( $image_src )) {
		list( $width, $height ) = getimagesize( $image_src );
	}
}

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_enqueue_style( ERE_PLUGIN_PREFIX . 'property-type');
?>
<div class="<?php echo join(' ', $wrapper_classes) ?>">
	<div class="property-type-inner">
		<div class="property-type-image">
			<?php if (!empty($type_image)):?>
				<a href="<?php echo esc_url( get_term_link( $type_slug, 'property-type' ) ); ?>" title="<?php echo esc_attr( $type_name ) ?>">
					<img width="<?php echo esc_attr( $width )?>" height="<?php echo esc_attr( $height )?>"
				     src="<?php echo esc_url($image_src) ?>" alt="<?php echo esc_attr( $type_name ) ?>"
				     title="<?php echo esc_attr( $type_name ) ?>">
				</a>
			<?php endif;?>
		</div>
		<div class="property-type-info">
			<div class="property-title">
				<a href="<?php echo esc_url( get_term_link( $type_slug, 'property-type' ) ); ?>" title="<?php echo esc_attr( $type_name ) ?>">
					<?php echo esc_html( $type_name ); ?>
				</a>
			</div>
			<div class="property-count"><span><?php echo esc_attr( $type_count ); ?></span> <?php
				if($type_count=='1')
				{
					esc_html_e('Property','essential-real-estate');
				}
				else{
					esc_html_e('Properties','essential-real-estate');
				}
				?></div>
		</div>
	</div>
</div>
