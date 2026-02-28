<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$layout_style = $property_type = $property_status = $property_feature = $property_city = $property_state = $property_neighborhood =
$property_label = $property_featured = $item_amount = $image_size = $el_class = '';
extract( shortcode_atts( array(
	'layout_style' => 'navigation-middle',
	'property_type' => '',
	'property_status' => '',
	'property_feature' => '',
	'property_city' => '',
	'property_state' => '',
	'property_neighborhood' => '',
	'property_label' => '',
    'property_featured' => '',
	'item_amount'       => '6',
	'image_size'        => '1200x600',
	'el_class'          => ''
), $atts ) );
$property_item_class         = array( 'property-item' );
$property_content_class      = array( 'property-content' );
$property_content_attributes = array();
$wrapper_attributes = array();
$wrapper_classes = array(
	'ere-property-slider clearfix',
	$layout_style,
	$el_class
);

if($layout_style == 'navigation-middle') {
	$property_content_class[] = 'owl-carousel manual';
	$owl_attributes = array(
		'"items": 1',
		'"dots": false',
		'"nav": true',
		'"autoplay": true',
		'"autoplayTimeout": 5000'
	);
	$property_content_attributes[] = "data-plugin-options='{" . implode( ', ', $owl_attributes ) . "}'";
}

$args = array(
	'posts_per_page' => ( $item_amount > 0 ) ? $item_amount : - 1,
	'post_type'      => 'property',
	'post_status'    => 'publish',
	'orderby'   => array(
		'menu_order'=>'ASC',
		'date' =>'DESC',
	),
);
$featured_toplist = ere_get_option('featured_toplist', 1);
if($featured_toplist!=0)
{
	/*$args['orderby'] = array(
		'menu_order'=>'ASC',
		'meta_value_num' => 'DESC',
		'date' => 'DESC',
	);
	$args['meta_key'] = ERE_METABOX_PREFIX . 'property_featured';*/
	$args['ere_orderby_featured'] = true;
}
if (!empty( $property_type ) || !empty( $property_status ) || !empty( $property_feature ) || !empty( $property_city )
	|| !empty( $property_state ) || !empty( $property_neighborhood ) || !empty( $property_label )) {
	$args['tax_query'] = array();
	if(!empty( $property_type )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-type',
			'field' => 'slug',
			'terms' => explode(',', $property_type),
			'operator' => 'IN'
		);
	}
	if(!empty( $property_status )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-status',
			'field' => 'slug',
			'terms' => explode(',', $property_status),
			'operator' => 'IN'
		);
	}
	if(!empty( $property_feature )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-feature',
			'field' => 'slug',
			'terms' => explode(',', $property_feature),
			'operator' => 'IN'
		);
	}
	if(!empty( $property_city )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-city',
			'field' => 'slug',
			'terms' => explode(',', $property_city),
			'operator' => 'IN'
		);
	}
	if(!empty( $property_state )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-state',
			'field' => 'slug',
			'terms' => explode(',', $property_state),
			'operator' => 'IN'
		);
	}
	if(!empty( $property_neighborhood )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-neighborhood',
			'field' => 'slug',
			'terms' => explode(',', $property_neighborhood),
			'operator' => 'IN'
		);
	}
	if(!empty( $property_label )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-label',
			'field' => 'slug',
			'terms' => explode(',', $property_label),
			'operator' => 'IN'
		);
	}
}

if($property_featured=='true') {
    $args['meta_query'] = array(
        array(
            'key'       => ERE_METABOX_PREFIX.'property_featured',
            'value'     => true,
            'compare'   => '=',
        )
    );
}
$args = apply_filters('ere_shortcodes_property_slider_query_args',$args);
$data = new WP_Query( $args );
$total_post = $data->found_posts;
?>
<div class="ere-property-wrap">
	<div class="<?php echo join( ' ', $wrapper_classes ) ?>" <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( $layout_style == 'navigation-middle' ): ?>
			<div class="<?php echo join( ' ', $property_content_class ) ?>" data-callback="owl_callback" <?php echo implode( ' ', $property_content_attributes ); ?>>
				<?php if ( $data->have_posts() ) :
				$no_image_src= ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
				$default_image=ere_get_option('default_property_image','');
				if (preg_match('/\d+x\d+/', $image_size)) {
					$image_sizes = explode('x', $image_size);
					$width=$image_sizes[0];$height= $image_sizes[1];
					if($default_image!='')
					{
						if(is_array($default_image)&& $default_image['url']!='')
						{
							$resize = ere_image_resize_url($default_image['url'], $width, $height, true);
							if ($resize != null && is_array($resize)) {
								$no_image_src = $resize['url'];
							}
						}
					}
				} else {
					if($default_image!='')
					{
						if(is_array($default_image)&& $default_image['url']!='')
						{
							$no_image_src = $default_image['url'];
						}
					}
				}
				while ( $data->have_posts() ): $data->the_post();
					$property_id=get_the_ID();
					$attach_id  = get_post_thumbnail_id();
					$image_src  = '';
					$width = 540; $height = 320;

					if (preg_match('/\d+x\d+/', $image_size)) {
						$image_sizes = explode('x', $image_size);
						$width=$image_sizes[0];$height= $image_sizes[1];
						$image_src = ere_image_resize_id($attach_id, $width, $height, true);
					} else {
						if (!in_array($image_size, array('full', 'thumbnail'))) {
							$image_size = 'full';
						}
						$image_src = wp_get_attachment_image_src($attach_id, $image_size);
						if ($image_src && !empty($image_src[0])) {
							$image_src = $image_src[0];
						}
						if (!empty($image_src)) {
							list($width, $height) = getimagesize($image_src);
						}
					}

					$excerpt = get_the_excerpt();

					$property_meta_data = get_post_custom( $property_id );

					$price                 = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
					$price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
					$price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
					$price_prefix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_prefix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_prefix' ][0] : '';
					$price_postfix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
					$property_address      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ][0] : '';
					$property_size         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ][0] : '';
					$property_bedrooms     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ][0] : '0';
					$property_bathrooms    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ][0] : '0';

					$property_status = get_the_terms( $property_id, 'property-status' );

					$property_title = get_the_title();
					$property_link = get_the_permalink();
					?>
					<div class="<?php echo join( ' ', $property_item_class ); ?>">
						<div class="property-inner">
							<div class="property-image">
								<img width="<?php echo esc_attr($width) ?>"
									 height="<?php echo esc_attr($height) ?>"
									 src="<?php echo esc_url($image_src) ?>" onerror="this.src = '<?php echo esc_url($no_image_src) ?>';" alt="<?php the_title(); ?>"
									 title="<?php the_title(); ?>">
							</div>
							<div class="block-center container">
								<div class="block-center-inner">
									<div class="property-main-info">
										<div class="property-heading">
											<?php if ( ! empty( $property_title ) ): ?>
												<h2><a href="<?php echo esc_url( $property_link ) ?>" title="<?php the_title() ?>" ><?php the_title(); ?></a></h2>
											<?php endif; ?>
											<div class="property-info-block-inline">
												<div>
													<?php if (!empty( $price ) ): ?>
														<span class="property-price">
															<?php if (!empty($price_prefix)) {
																echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
															} ?>
															<?php echo ere_get_format_money($price_short,$price_unit) ?>
															<?php if (!empty($price_postfix)) {
																echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
															} ?>
														</span>
													<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
														<span class="property-price"><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
													<?php endif; ?>
													<?php
													$property_status = get_the_terms( $property_id, 'property-status' );
													if ( $property_status ) : ?>
														<div class="property-status">
															<?php foreach ( $property_status as $status ) :
																$status_color = get_term_meta($status->term_id, 'property_status_color', true);?>
																<span style="background-color: <?php echo esc_attr($status_color) ?>"><?php echo esc_attr( $status->name ); ?></span>
															<?php endforeach; ?>
														</div>
													<?php endif; ?>
												</div>
												<?php if ( ! empty( $property_address ) ):
													$property_location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
													if($property_location)
													{
														$google_map_address_url = "http://maps.google.com/?q=" . $property_location['address'];
													}
													else
													{
														$google_map_address_url = "http://maps.google.com/?q=" . $property_address;
													}
													?>
													<div class="property-location" title="<?php echo esc_attr( $property_address ) ?>">
														<i class="fa fa-map-marker"></i>
														<a target="_blank"
														   href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_html($property_address) ?></span></a>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="property-info">
										<?php if ( ! empty( $property_size ) ): ?>
											<div class="property-area">
												<span class="fa fa-arrows"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php
														echo ere_get_format_number( $property_size ) ?>
														<span><?php
															$measurement_units = ere_get_measurement_units();
															echo wp_kses_post($measurement_units); ?></span>
													</p>
													<p class="property-info-title"><?php esc_html_e( 'Size', 'essential-real-estate' ); ?></p>
												</div>
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $property_bedrooms ) ): ?>
											<div class="property-bedrooms">
												<span class="fa fa-hotel"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php echo esc_html( $property_bedrooms ) ?></p>
													<p class="property-info-title"><?php
														echo ere_get_number_text($property_bedrooms, esc_html__( 'Bedrooms', 'essential-real-estate' ), esc_html__( 'Bedroom', 'essential-real-estate' ));
														?></p>
												</div>
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $property_bathrooms ) ): ?>
											<div class="property-bathrooms">
												<span class="fa fa-bath"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php echo esc_html( $property_bathrooms ) ?></p>
													<p class="property-info-title"><?php
														echo ere_get_number_text($property_bathrooms, esc_html__( 'Bathrooms', 'essential-real-estate' ), esc_html__( 'Bathroom', 'essential-real-estate' ));
														?></p>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile;
			else: ?>
				<div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
			<?php endif; ?>
			</div>
		<?php else: ?>
			<div class="<?php echo join( ' ', $property_content_class ) ?>">
				<?php if ( $data->have_posts() ) :?>
				<div class="property-content-slider owl-carousel manual ere-carousel-manual">
					<?php
					while ( $data->have_posts() ): $data->the_post();
						$property_id=get_the_ID();
						$attach_id  = get_post_thumbnail_id();
						$image_src  = '';
						$width      = '';
						$height     = '';
						if ( preg_match( '/\d+x\d+/', $image_size ) ) {
							$image_sizes = explode( 'x', $image_size );
							$image_src  = ere_image_resize_id( $attach_id, $image_sizes[0], $image_sizes[1], true );
						} else {
							if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
								$image_size = 'full';
							}
							$image_src = wp_get_attachment_image_src( $attach_id, $image_size );
							if ( $image_src && ! empty( $image_src[0] ) ) {
								$image_src = $image_src[0];
							}
						}
						if(!empty( $image_src )) {
							list( $width, $height ) = getimagesize( $image_src );
						}
						$excerpt = get_the_excerpt();

						$property_meta_data = get_post_custom( $property_id );

						$price                 = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
						$price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
						$price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
						$price_prefix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_prefix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_prefix' ][0] : '';
						$price_postfix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
						$property_address      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ][0] : '';
						$property_size         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ][0] : '';
						$property_bedrooms     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ][0] : '0';
						$property_bathrooms    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ][0] : '0';

						$property_title = get_the_title();
						$property_link = get_the_permalink();
						?>
						<div class="<?php echo join( ' ', $property_item_class ); ?>">
							<div class="property-inner">
								<?php if ( ! empty( $image_src ) ): ?>
									<div class="property-image">
										<img width="<?php echo esc_attr( $width ) ?>"
										     height="<?php echo esc_attr( $height ) ?>"
										     src="<?php echo esc_url( $image_src ) ?>" alt="<?php the_title(); ?>"
										     title="<?php the_title(); ?>">
									</div>
								<?php endif; ?>
								<div class="block-center">
									<div class="block-center-inner">
										<div class="property-main-info">
											<div class="property-heading">
												<?php if ( ! empty( $property_address ) ):
													$property_location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
													if($property_location)
													{
														$google_map_address_url = "http://maps.google.com/?q=" . $property_location['address'];
													}
													else
													{
														$google_map_address_url = "http://maps.google.com/?q=" . $property_address;
													}
													?>
													<div class="property-location" title="<?php echo esc_attr( $property_address ) ?>">
														<i class="fa fa-map-marker"></i>
														<a target="_blank"
														   href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_html($property_address) ?></span></a>
													</div>
												<?php endif; ?>
												<?php if ( ! empty( $property_title ) ): ?>
													<h2><a href="<?php echo esc_url( $property_link ) ?>" title="<?php the_title() ?>" ><?php the_title(); ?></a></h2>
												<?php endif; ?>
												<div class="property-info-block-inline">
													<?php if (!empty( $price ) ): ?>
														<span class="property-price">
															<?php if (!empty($price_prefix)) {
																echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
															} ?>
															<?php echo ere_get_format_money($price_short,$price_unit) ?>
															<?php if (!empty($price_postfix)) {
																echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
															} ?>
														</span>
													<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
														<span class="property-price"><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
													<?php endif; ?>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="property-info">
											<?php if ( ! empty( $property_size ) ): ?>
												<div class="property-area">
													<span class="fa fa-arrows"></span>
													<div class="content-property-info">
														<p class="property-info-value"><?php
															echo ere_get_format_number( $property_size ) ?>
															<span><?php
																$measurement_units = ere_get_measurement_units();
																echo wp_kses_post($measurement_units); ?></span>
														</p>
														<p class="property-info-title"><?php esc_html_e( 'Size', 'essential-real-estate' ); ?></p>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( ! empty( $property_bedrooms ) ): ?>
												<div class="property-bedrooms">
													<span class="fa fa-hotel"></span>
													<div class="content-property-info">
														<p class="property-info-value"><?php echo esc_html( $property_bedrooms ) ?></p>
														<p class="property-info-title"><?php
															echo ere_get_number_text($property_bedrooms, esc_html__( 'Bedrooms', 'essential-real-estate' ), esc_html__( 'Bedroom', 'essential-real-estate' ));
															?></p>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( ! empty( $property_bathrooms ) ): ?>
												<div class="property-bathrooms">
													<span class="fa fa-bath"></span>
													<div class="content-property-info">
														<p class="property-info-value"><?php echo esc_html( $property_bathrooms ) ?></p>
														<p class="property-info-title"><?php
															echo ere_get_number_text($property_bathrooms, esc_html__( 'Bathrooms', 'essential-real-estate' ), esc_html__( 'Bathroom', 'essential-real-estate' ));
															?></p>
													</div>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<div class="container property-slider-image-wrap">
					<div class="property-image-slider owl-carousel manual ere-carousel-manual">
						<?php
						while ( $data->have_posts() ): $data->the_post();
							$attach_id  = get_post_thumbnail_id();
							$image_src  = ere_image_resize_id( $attach_id, 170, 90, true );
							if(!empty( $image_src )) : ?>
								<div class="property-item">
									<img width="170" height="90"
									     src="<?php echo esc_url( $image_src ) ?>"
									     alt="<?php the_title(); ?>"
									     title="<?php the_title(); ?>">
								</div>
							<?php endif;
						endwhile;
						?>
					</div>
				</div>
				<?php else: ?>
					<div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</div>

