<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$layout_style = $property_type = $property_status = $property_feature = $property_cities = $property_state =
$property_neighborhood = $property_label = $color_scheme = $item_amount = $include_heading =
$heading_sub_title = $heading_title = $heading_text_align = $property_city = $el_class = $image_size1=$image_size2=$image_size3=$image_size4='';
extract(shortcode_atts(array(
	'layout_style' => 'property-list-two-columns',
	'property_type' => '',
	'property_status' => '',
	'property_feature' => '',
	'property_cities' => '',
	'property_state' => '',
	'property_neighborhood' => '',
	'property_label' => '',
	'color_scheme' => 'color-dark',
	'item_amount' => '6',
	'image_size1'        => '240x180',
	'image_size2'        => '835x320',
	'image_size3'        => '570x320',
	'image_size4'        => '945x605',
	'include_heading' => '',
	'heading_sub_title' => '',
	'heading_title' => '',
	'heading_text_align' => '',
	'property_city' => '',
	'el_class' => ''
), $atts));

$wrapper_attributes = array();
$wrapper_styles = array();
$property_item_class = array();
$property_content_class = array('property-content-wrap');

if (empty($property_cities)) {
	$property_ids = array();
	$args1 = array(
		'posts_per_page' => -1,
		'post_type' => 'property',
		'orderby'   => array(
			'menu_order'=>'ASC',
			'date' =>'DESC',
		),
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => ERE_METABOX_PREFIX . 'property_featured',
				'value' => true,
				'compare' => '=',
			)
		)
	);
	$data = new WP_Query($args1);
	if ($data->have_posts()) :
		while ($data->have_posts()): $data->the_post();
			$property_ids[] = get_the_ID();
		endwhile;
	endif;
	wp_reset_postdata();

	$property_city_all = wp_get_object_terms($property_ids, 'property-city');
	$property_cities = array();
	if (is_array($property_city_all)) {
		foreach ($property_city_all as $property_ct) {
			$property_cities[] = $property_ct->slug;
		}
		$property_cities = join(',', $property_cities);
	}
}
if ($layout_style == 'property-cities-filter') {
	if (!empty($property_cities) && empty($property_city)) {
		$property_city = explode(',', $property_cities)[0];
	}
}
$wrapper_classes = array(
	'ere-property-featured clearfix',
	$layout_style,
	$color_scheme,
	$el_class
);

if ($layout_style == 'property-list-two-columns') {
	$property_content_class[] = 'row';
	$property_item_class[] = 'mg-bottom-30';
	$property_content_class[] = 'columns-2';
	$property_item_class[] = 'ere-item-wrap';
	$property_item_class[] = 'mg-bottom-30';
	$wrapper_classes[] = 'ere-property property-list';
}

$args = array(
	'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
	'post_type' => 'property',
	'orderby'   => array(
		'menu_order'=>'ASC',
		'date' =>'DESC',
	),
	'post_status' => 'publish',
	'meta_query' => array(
		array(
			'key' => ERE_METABOX_PREFIX . 'property_featured',
			'value' => true,
			'compare' => '=',
		)
	)
);
$args['tax_query'] = array();
if (!empty($property_city)) {
	$args['tax_query'][] = array(
		'taxonomy' => 'property-city',
		'field' => 'slug',
		'terms' => array($property_city),
		'operator' => 'IN'
	);
}
if (!empty($property_type) || !empty($property_status) || !empty($property_feature) || !empty($property_cities)
    || !empty($property_state) || !empty($property_neighborhood) || !empty($property_label)
) {
	if (!empty($property_type)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-type',
			'field' => 'slug',
			'terms' => explode(',', $property_type),
			'operator' => 'IN'
		);
	}
	if (!empty($property_status)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-status',
			'field' => 'slug',
			'terms' => explode(',', $property_status),
			'operator' => 'IN'
		);
	}
	if (!empty($property_feature)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-feature',
			'field' => 'slug',
			'terms' => explode(',', $property_feature),
			'operator' => 'IN'
		);
	}
	if (!empty($property_cities) && empty($property_city)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-city',
			'field' => 'slug',
			'terms' => explode(',', $property_cities),
			'operator' => 'IN'
		);
	}
	if (!empty($property_state)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-state',
			'field' => 'slug',
			'terms' => explode(',', $property_state),
			'operator' => 'IN'
		);
	}
	if (!empty($property_neighborhood)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-neighborhood',
			'field' => 'slug',
			'terms' => explode(',', $property_neighborhood),
			'operator' => 'IN'
		);
	}
	if (!empty($property_label)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-label',
			'field' => 'slug',
			'terms' => explode(',', $property_label),
			'operator' => 'IN'
		);
	}
}
$args = apply_filters('ere_shortcodes_property_featured_query_args',$args);
$data = new WP_Query($args);
$total_post = $data->found_posts;
?>
<div class="<?php echo join(' ', $wrapper_classes) ?>" <?php echo implode(' ', $wrapper_attributes); ?>>
	<?php if ($layout_style == 'property-list-two-columns'): ?>
		<?php if ($include_heading) :
			$heading_class=$color_scheme.' '. $heading_text_align;
			?>
			<div class="ere-heading <?php echo esc_attr($heading_class); ?>">
				<?php if (!empty($heading_title)): ?>
					<h2><?php echo esc_html($heading_title); ?></h2>
				<?php endif; ?>
				<?php if (!empty($heading_sub_title)): ?>
					<p><?php echo esc_html($heading_sub_title); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="<?php echo join(' ', $property_content_class); ?>">
			<?php if ($data->have_posts()) :
				$index = 0;
				$width = 240;
				$height = 180;
				$no_image_src = ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
				$default_image = ere_get_option('default_property_image', '');
				$image_size=$image_size1;
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
				while ($data->have_posts()): $data->the_post();
					$property_id=get_the_ID();
					$attach_id = get_post_thumbnail_id();
					$image_src  = '';
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

					$property_meta_data = get_post_custom($property_id);

					$price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
					$price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
					$price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
					$price_prefix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix'][0] : '';
					$price_postfix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix'][0] : '';
					$property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';
					$property_featured = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_featured']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_featured'][0] : '0';

					$property_label = get_the_terms($property_id, 'property-label');
					$property_item_status = get_the_terms($property_id, 'property-status');

					$property_link = get_the_permalink();
					?>
					<div class="<?php echo join(' ', $property_item_class); ?>">
						<div class="property-inner">
							<div class="property-image">
								<img width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>"
								     src="<?php echo esc_url($image_src) ?>"
								     onerror="this.src = '<?php echo esc_url($no_image_src) ?>';"
								     alt="<?php the_title(); ?>"
								     title="<?php the_title(); ?>">

								<div class="property-action block-center">
									<div class="block-center-inner">
										<?php
										/**
										 * ere_property_action hook.
										 *
										 * @hooked property_social_share - 5
										 * @hooked property_favorite - 10
										 * @hooked property_compare - 15
										 */
										do_action('ere_property_action'); ?>
									</div>
									<a class="property-link" href="<?php echo esc_url($property_link); ?>"
									   title="<?php the_title(); ?>"></a>
								</div>
								<?php if ($property_label || $property_featured): ?>
									<div class="property-label property-featured">
										<?php if ($property_featured): ?>
											<p class="label-item">
                                                <span
		                                                class="property-label-bg"><?php esc_html_e('Featured', 'essential-real-estate'); ?>
                                                    <span class="property-arrow"></span></span>
											</p>
										<?php endif; ?>
										<?php if ($property_label): ?>
											<?php foreach ($property_label as $label_item): ?>
												<?php $label_color = get_term_meta($label_item->term_id, 'property_label_color', true); ?>
												<p class="label-item">
														<span class="property-label-bg"
														      style="background-color: <?php echo esc_attr($label_color) ?>"><?php echo esc_html($label_item->name) ?>
                                                            <span class="property-arrow"
                                                                  style="border-left-color: <?php echo esc_attr($label_color) ?>; border-right-color: <?php echo esc_attr($label_color) ?>"></span>
														</span>
												</p>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php if ($property_item_status): ?>
									<div class="property-status">
										<?php foreach ($property_item_status as $status): ?>
											<?php $status_color = get_term_meta($status->term_id, 'property_status_color', true); ?>
											<p class="status-item">
											<span class="property-status-bg"
											      style="background-color: <?php echo esc_attr($status_color) ?>"><?php echo esc_html($status->name) ?>
                                                <span class="property-arrow"
                                                      style="border-left-color: <?php echo esc_attr($status_color) ?>; border-right-color: <?php echo esc_attr($status_color) ?>"></span>
											</span>
											</p>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="property-item-content">
								<h2 class="property-title"><a href="<?php echo esc_url($property_link); ?>"
								                              title="<?php the_title(); ?>"><?php the_title() ?></a>
								</h2>

								<?php if (!empty($price)): ?>
									<div class="property-price">
                                         <span>
                                            <?php if (!empty($price_prefix)) {
	                                            echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
                                            } ?>
                                            <?php echo ere_get_format_money($price_short,$price_unit) ?>
                                            <?php if (!empty($price_postfix)) {
	                                            echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
                                            } ?>
                                        </span>
									</div>
								<?php elseif (ere_get_option('empty_price_text', '') != ''): ?>
									<div class="property-price">
										<span><?php echo ere_get_option('empty_price_text', '') ?></span>
									</div>
								<?php endif; ?>
								<?php if (!empty($property_address)):
									$property_location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
									if($property_location)
									{
										$google_map_address_url = "http://maps.google.com/?q=" . $property_location['address'];
									}
									else
									{
										$google_map_address_url = "http://maps.google.com/?q=" . $property_address;
									}?>
									<div class="property-location" title="<?php echo esc_attr($property_address) ?>">
										<i class="fa fa-map-marker"></i>
										<a target="_blank"
										   href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_html($property_address) ?></span></a>
									</div>
								<?php endif; ?>

								<?php if (isset($excerpt) && !empty($excerpt)): ?>
									<div class="property-excerpt">
										<p><?php echo esc_html($excerpt) ?></p>
									</div>
								<?php endif; ?>
								<div class="property-link-detail">
									<a href="<?php echo esc_url($property_link); ?>" title="<?php the_title(); ?>">
										<span><?php esc_html_e('Details', 'essential-real-estate'); ?></span>
										<i class="fa fa-long-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<?php $index++; ?>
				<?php endwhile;
			else: ?>
				<div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<?php ere_get_template('shortcodes/property-featured/templates/' . $layout_style . '.php',
			array(
				'layout_style' => $layout_style, 'data' => $data,
				'property_type' => $property_type,
				'property_status' => $property_status,
				'property_feature' => $property_feature,
				'property_cities' => $property_cities,
				'property_state' => $property_state,
				'property_neighborhood' => $property_neighborhood,
				'property_label' => $property_label,
				'color_scheme' => $color_scheme,
				'item_amount' => $item_amount,
				'image_size1' => $image_size1,
				'image_size2' => $image_size2,
				'image_size3' => $image_size3,
				'image_size4' => $image_size4,
				'include_heading' => $include_heading,
				'heading_sub_title' => $heading_sub_title,
				'heading_title' => $heading_title,
				'heading_text_align' => $heading_text_align,
				'property_city' => $property_city,
				'el_class' => $el_class,
			)); ?>
	<?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>

