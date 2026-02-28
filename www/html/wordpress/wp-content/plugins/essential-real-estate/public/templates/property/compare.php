<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $hide_compare_fields;

$hide_compare_fields = ere_get_option('hide_compare_fields', array());
$additional_fields = ere_render_additional_fields();
if (!is_array($hide_compare_fields)) {
	$hide_compare_fields = array();
}
ERE_Compare::open_session();
$property_ids = $_SESSION['ere_compare_properties'];
if (!empty($property_ids)) {
	$property_ids = array_diff($property_ids, ["0"]);
	$args = array(
		'post_type' => 'property',
		'post__in' => $property_ids,
		'post_status' => 'publish',
		'orderby' => 'post__in',
		'posts_per_page' => sizeof( $property_ids )
	);
	$data = New WP_Query($args);

	$property_item = $types = $status = $year = $size = $rooms= $bedrooms = $bathrooms = $garage = $garage_size = $land = $additional='';
	$empty_field='<td class="check-no"><i class="fa fa-minus"></i></td>';
	if ($data->have_posts()): while ($data->have_posts()): $data->the_post();
		$property_id=get_the_ID();
		$property_meta_data = get_post_custom($property_id);

		$property_types = get_the_terms($property_id, 'property-type');
		$property_type_arr = array();
		if ($property_types) {
			foreach ($property_types as $property_type) {
				$property_type_arr[] = $property_type->name;
			}
		}

		$property_status = get_the_terms($property_id, 'property-status');
		$property_status_arr = array();
		if ($property_status) {
			foreach ($property_status as $s) {
				$property_status_arr[] = $s->name;
			}
		}

		$property_label = get_the_terms($property_id, 'property-label');
		$property_label_arr = array();
		if ($property_label) {
			foreach ($property_label as $label) {
				$property_label_arr[] = $label->name;
			}
		}

		$property_year = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_year']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_year'][0] : '';
		$property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
		$property_rooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_rooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_rooms'][0] : '';
		$property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '';
		$property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '';
		$property_garage = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0] : '';
		$property_garage_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'][0] : '';
		$property_land = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_land']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_land'][0] : '';

		$attach_id = get_post_thumbnail_id();
		$width = 330; $height = 180;
		$no_image_src= ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
		$default_image=ere_get_option('default_property_image','');
		$image_src = ere_image_resize_id($attach_id, $width, $height, true);
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
		$price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
		$price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
		$price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
		$price_prefix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix'][0] : '';
		$price_postfix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix'][0] : '';

		$property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';

		$property_link = get_the_permalink();
		$measurement_units = ere_get_measurement_units();
		$property_item .= '<th><div class="property-inner">';
		$no_image_src=''.$no_image_src.'';
		$property_item .= '<div class="property-image-wrap">
								<a href="' . $property_link . '" title="' . get_the_title() . '"></a>
								<img src="'. esc_url($image_src) .'" class="ere-property-image" alt="' . get_the_title() . '" title="' . get_the_title() . '">
							</div>';
		if (!empty($property_label)) {
			$property_item .= '<div class="property-label">';
			foreach ($property_label as $label_item):
				if (!empty($property_label)) {
					$label_color = get_term_meta($label_item->term_id, 'property_label_color', true);
					$property_item .= '<p class="label-item">
											<span class="property-label-bg" style="background-color: '. esc_attr($label_color).' !important;">
												' . $label_item->name . '
												<span class="property-arrow" style="border-left-color: '. esc_attr($label_color) .' !important; border-right-color: '. esc_attr($label_color).' !important;"></span>
											</span>
										</p>';
				}
			endforeach;
			$property_item .= '</div>';
		}
		$property_price='';
		if (!empty($price))
		{
			if (!empty($price_prefix)) {
				$property_price='<span class="property-price-prefix">' . $price_prefix . ' </span>';
			}
			$property_price.=ere_get_format_money( $price_short,$price_unit );
			if (!empty($price_postfix)) {
				$property_price.='<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
			}
		}
		else
		{
			$property_price=ere_get_option('empty_price_text', '');
		}

		$property_item .= '<div class="property-item-content">
								<h2 class="property-title"><a href="' . $property_link . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h2>
								<div class="property-info">
									'.$property_price.'
									<div class="property-location" title="' . $property_address . '">
										<i class="fa fa-map-marker"></i>
										<span>' . $property_address . '</span>
									</div>
								</div>
							</div>';
		$property_item .= '</div></th>';
		if (!in_array("property_type", $hide_compare_fields)) {
			if (!empty($property_types)) {
				$types .= '<td>' . join(', ', $property_type_arr) . '</td>';
			} else {
				$types .= $empty_field;
			}
		}
		if (!in_array("property_status", $hide_compare_fields)) {
			if (!empty($property_status)) {
				$status .= '<td>' . join(', ', $property_status_arr) . '</td>';
			} else {
				$status .= $empty_field;
			}
		}
		if (!in_array("property_year", $hide_compare_fields)) {
			if (!empty($property_year)) {
				$year .= '<td>' . $property_year . '</td>';
			} else {
				$year .= $empty_field;
			}
		}
		if (!in_array("property_size", $hide_compare_fields)) {
			if (!empty($property_size)) {
				$size .= '<td>' . wp_kses_post(sprintf( '%s %s',ere_get_format_number($property_size), $measurement_units)) . '</td>';
			} else {
				$size .= $empty_field;
			}
		}
		if (!in_array("property_rooms", $hide_compare_fields)) {
			if (!empty($property_rooms)) {
				$rooms .= '<td>' . $property_rooms . '</td>';
			} else {
				$rooms .= $empty_field;
			}
		}
		if (!in_array("property_bedrooms", $hide_compare_fields)) {
			if (!empty($property_bedrooms)) {
				$bedrooms .= '<td>' . $property_bedrooms . '</td>';
			} else {
				$bedrooms .= $empty_field;
			}
		}
		if (!in_array("property_bathrooms", $hide_compare_fields)) {
			if (!empty($property_bathrooms)) {
				$bathrooms .= '<td>' . $property_bathrooms . '</td>';
			} else {
				$bathrooms .= $empty_field;
			}
		}
		if (!in_array("property_garage", $hide_compare_fields)) {
			if (!empty($property_garage)) {
				$garage .= '<td>' . $property_garage . '</td>';
			} else {
				$garage .= $empty_field;
			}
		}
		if (!in_array("property_garage_size", $hide_compare_fields)) {
			if (!empty($property_garage_size)) {
				$garage_size .= '<td>' . sprintf( '%s %s',$property_garage_size, $measurement_units) . '</td>';
			} else {
				$garage_size .= $empty_field;
			}
		}
		if (!in_array("property_land", $hide_compare_fields)) {
			if (!empty($property_land)) {
				$measurement_units_land_area = ere_get_measurement_units_land_area();
				$land .= '<td>' . wp_kses_post(sprintf( '%s %s',ere_get_format_number($property_land), $measurement_units_land_area)) . '</td>';
			} else {
				$land .= $empty_field;
			}
		}
	endwhile; endif;
	?>
	<div class="row">
		<div class="compare-table-wrap col-sm-12">
			<table class="compare-tables table-striped">
				<thead>
				<tr>
					<th class="title-list-check"></th>
					<?php echo wp_kses_post($property_item); ?>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($types)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Type', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($types); ?>
					</tr>
				<?php } ?>

				<?php if (!empty($status)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Status', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($status); ?>
					</tr>
				<?php } ?>
				<?php if (!empty($size)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Size', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($size); ?>
					</tr>
				<?php } ?>
				<?php if (!empty($land)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Land Area', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($land); ?>
					</tr>
				<?php } ?>
				<?php if (!empty($rooms)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Rooms', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($rooms); ?>
					</tr>
				<?php } ?>
				<?php if (!empty($bedrooms)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Bedrooms', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($bedrooms); ?>
					</tr>
				<?php } ?>

				<?php if (!empty($bathrooms)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Bathrooms', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($bathrooms); ?>
					</tr>
				<?php } ?>

				<?php if (!empty($garage)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Garages', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($garage); ?>
					</tr>
				<?php } ?>

				<?php if (!empty($garage_size)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Garages Size', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($garage_size); ?>
					</tr>
				<?php } ?>
				<?php if (!empty($year)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Year Built', 'essential-real-estate'); ?></td>
						<?php echo wp_kses_post($year); ?>
					</tr>
				<?php } ?>
				<?php
				$all_property_feature = get_categories(array(
					'hide_empty' => 0,
					'taxonomy'  => 'property-feature'
				));
				$compare_terms = array();
				foreach ($property_ids as $post_id) {
					$compare_terms[$post_id] = wp_get_post_terms($post_id, 'property-feature', array('fields' => 'ids'));
				}
				foreach ($all_property_feature as $feature)
				{
					?>
					<tr>
						<td class="title-list-check"><?php echo esc_html($feature->name); ?></td>
						<?php
						foreach ($property_ids as $post_id)
						{
							if (in_array($feature->term_id, $compare_terms[$post_id]))
							{
								echo '<td><div class="check-yes"><i class="fa fa-check"></i></div></td>';
							}
							else
							{
								echo '<td><div class="check-no"><i class="fa fa-minus"></i></div></td>';
							}
						}
						?>
					</tr>
					<?php
				}
				if(count($additional_fields)>0){
					foreach ($additional_fields as $key => $field){
						$additional.='<tr>';
						$additional.='<td class="title-list-check">'.esc_html($field['title']).'</td>';
						foreach ($property_ids as $post_id)
						{
							$property_field= get_post_meta($post_id, $field['id'], true);
							if(!empty($property_field)) {
								if ($field['type'] == 'checkbox_list') {
									$text = '';
									if (count($property_field) > 0) {
										foreach ($property_field as $value => $v) {
											$text .= $v . ', ';
										}
									}
									$text = rtrim($text, ', ');
									$additional.='<td>'. esc_html($text).'</td>';
								} else {
									$additional.='<td>'.  esc_html($property_field).'</td>';
								}

							}
							else{
								$additional.=$empty_field;
							};
						}
						$additional.='</tr>';
					}
				}
				if(!empty($additional))
				{
					echo wp_kses_post($additional);
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(window).on('load',function() {
			jQuery('.ere-property-image').each(function(){
				var image = jQuery(this);
				if(image.context.naturalWidth == 0 || image.readyState == 'uninitialized'){
					image.attr('src','<?php echo esc_url($no_image_src);?>');
				}
			});
		});
	</script>
<?php
	wp_reset_postdata();
} else {?>
	<div class="item-not-found"><?php esc_html_e('No item compare', 'essential-real-estate'); ?></div>
<?php } ?>