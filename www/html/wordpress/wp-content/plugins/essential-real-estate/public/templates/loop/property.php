<?php
/**
 * @var $custom_property_image_size
 * @var $property_item_class
 * @var $property_image_class
 * @var $property_item_content_class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$property_id=get_the_ID();
$attach_id = get_post_thumbnail_id();
$no_image_src= ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
$default_image=ere_get_option('default_property_image','');
$width = 330; $height = 180;
if (preg_match('/\d+x\d+/', $custom_property_image_size)) {
    $image_sizes = explode('x', $custom_property_image_size);
    $width=$image_sizes[0];$height= $image_sizes[1];
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
} else {
    if (!in_array($custom_property_image_size, array('full', 'thumbnail'))) {
        $custom_property_image_size = 'full';
    }
    $image_src = wp_get_attachment_image_src($attach_id, $custom_property_image_size);
    if ($image_src && !empty($image_src[0])) {
        $image_src = $image_src[0];
    }
    if (!empty($image_src)) {
        list($width, $height) = getimagesize($image_src);
    }
    if($default_image!='')
    {
        if(is_array($default_image)&& $default_image['url']!='')
        {
            $no_image_src = $default_image['url'];
        }
    }
}
$property_meta_data = get_post_custom($property_id);
$excerpt = get_the_excerpt();
$price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
$price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
$price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
$price_prefix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix'][0] : '';
$price_postfix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix'][0] : '';
$property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';
$property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
$property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '0';
$property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '0';
$property_featured = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_featured']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_featured'][0] : '0';

// Get Agent name
$agent_display_option = isset($property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option']) ? $property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option'][0] : '';
$property_agent = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_agent']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_agent'][0] : '';
$agent_name = $agent_link = '';
if ($agent_display_option == 'author_info') {
    global $post;
    $user_id = $post->post_author;
    $user_info = get_userdata($user_id);

    if(empty($user_info->first_name) && empty($user_info->last_name))
    {
        $agent_name=$user_info->user_login;
    }
    else
    {
        $agent_name     = $user_info->first_name . ' ' . $user_info->last_name;
    }

    $author_agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
	$agent_status = get_post_status($author_agent_id);
	if ($agent_status == 'publish') {
		$agent_link = get_the_permalink($author_agent_id);
	} else {
		$agent_link = get_author_posts_url($user_id);
	}

} elseif ($agent_display_option == 'other_info') {
    $agent_name = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name'][0] : '';
} elseif ($agent_display_option == 'agent_info' && !empty($property_agent)) {
    $agent_name = get_the_title($property_agent);
    $agent_link = get_the_permalink($property_agent);
}

$property_item_type = get_the_terms($property_id, 'property-type');
$property_item_label = get_the_terms($property_id, 'property-label');
$property_item_status = get_the_terms($property_id, 'property-status');

$property_link = get_the_permalink();
if($property_featured)
{
    $property_item_class[] = 'ere-property-featured';
}
if (!isset($property_image_class)) {
	$property_image_class = array();
}
$property_image_class[] = 'property-image';


if (!isset($property_item_content_class)) {
	$property_item_content_class = array();
}

$property_item_content_class[] = 'property-item-content';


?>
<div class="<?php echo join(' ', $property_item_class); ?>">
    <div class="property-inner">
        <div class="<?php echo join(' ', $property_image_class); ?>">
                <img width="<?php echo esc_attr($width) ?>"
                     height="<?php echo esc_attr($height) ?>"
                     src="<?php echo esc_url($image_src) ?>" onerror="this.src = '<?php echo esc_url($no_image_src) ?>';" alt="<?php the_title(); ?>"
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
                <?php if ($property_item_label || $property_featured): ?>
                    <div class="property-label property-featured">
                        <?php if ($property_featured): ?>
                            <p class="label-item">
                                <span
                                    class="property-label-bg"><?php esc_html_e('Featured', 'essential-real-estate'); ?>
                                    <span class="property-arrow"></span></span>
                            </p>
                        <?php endif; ?>
                        <?php if ($property_item_label): ?>
                            <?php foreach ($property_item_label as $label_item): ?>
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
        <div class="<?php echo join(' ', $property_item_content_class); ?>">
            <div class="property-heading">
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
            </div>
            <?php if (!empty($property_address)):
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
                <div class="property-location" title="<?php echo esc_attr($property_address) ?>">
                    <i class="fa fa-map-marker"></i>
                    <a target="_blank"
                       href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_attr($property_address) ?></span></a>
                </div>
            <?php endif; ?>
            <div class="property-element-inline">
                <?php if ($property_item_type): ?>
                    <div class="property-type-list">
                        <i class="fa fa-tag"></i>
                        <?php foreach ($property_item_type as $type): ?>
                            <a href="<?php echo esc_url(get_term_link($type->slug, 'property-type')); ?>"
                               title="<?php echo esc_attr($type->name); ?>"><span><?php echo esc_html($type->name); ?> </span></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($agent_name)): ?>
                    <div class="property-agent">
                        <?php echo !empty($agent_link) ? ('<a href="' . esc_url($agent_link) . '" title="' . esc_attr($agent_name) . '">') : ''; ?>
                        <i class="fa fa-user"></i>
                        <span><?php echo esc_html($agent_name) ?></span>
                        <?php echo !empty($agent_link) ? ('</a>') : ''; ?>
                    </div>
                <?php endif; ?>
                <div
                    class="property-date">
                    <i class="fa fa-calendar"></i>
                    <?php
                    $get_the_time=get_the_time('U');
                    $current_time=current_time('timestamp');
                    $human_time_diff=human_time_diff($get_the_time, $current_time);
                    printf(_x(' %s ago', '%s = human-readable time difference', 'essential-real-estate'), $human_time_diff); ?></div>
            </div>
            <?php if (isset($excerpt) && !empty($excerpt)): ?>
                <div class="property-excerpt">
                    <p><?php echo esc_html($excerpt) ?></p>
                </div>
            <?php endif; ?>
            <div class="property-info">
                <div class="property-info-inner">
                    <?php if (!empty($property_size)): ?>
                        <div class="property-area">
                            <div class="property-area-inner property-info-item-tooltip" data-toggle="tooltip"
                                 title="<?php esc_attr_e('Size', 'essential-real-estate'); ?>">
                                <span class="fa fa-arrows"></span>
	                            <span class="property-info-value"><?php
                                    $measurement_units = ere_get_measurement_units();
                                    echo wp_kses_post(sprintf( '%s %s',ere_get_format_number($property_size), $measurement_units)); ?>
		                                            </span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($property_bedrooms)): ?>
                        <div class="property-bedrooms">
                            <div class="property-bedrooms-inner property-info-item-tooltip" data-toggle="tooltip"
                                 title="<?php printf( _n( '%s Bedroom', '%s Bedrooms', $property_bedrooms, 'essential-real-estate' ), $property_bedrooms ); ?>">
                                <span class="fa fa-hotel"></span>
                                <span class="property-info-value"><?php echo esc_html($property_bedrooms) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($property_bathrooms)): ?>
                        <div class="property-bathrooms">
                            <div class="property-bathrooms-inner property-info-item-tooltip" data-toggle="tooltip"
                                 title="<?php printf( _n( '%s Bathroom', '%s Bathrooms', $property_bathrooms, 'essential-real-estate' ), $property_bathrooms ); ?>">
                                <span class="fa fa-bath"></span>
                                <span class="property-info-value"><?php echo esc_html($property_bathrooms) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>