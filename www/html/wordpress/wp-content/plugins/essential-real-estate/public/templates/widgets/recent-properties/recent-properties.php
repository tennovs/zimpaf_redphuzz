<?php
/**
 * Created by G5Theme.
 * User: ThangLK
 * Date: 21/12/2016
 * Time: 11:00 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$number = (!empty($instance['number'])) ? absint($instance['number']) : 3;
if (!$number)
    $number = 3;

$args = array(
    'post_type' => 'property',
    'ignore_sticky_posts' => true,
    'posts_per_page' => $number,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
);
$filter_by_agent= (!empty($instance['filter_by_agent'])) ? ($instance['filter_by_agent']) : '0';
if ($filter_by_agent==1 && is_single() &&  get_post_type() == 'agent') {
    $agent_id = get_the_ID();
    $agent_post_meta_data = get_post_custom( $agent_id);
    $agent_user_id = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_user_id']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_user_id'][0] : '';
    $user = get_user_by('id', $agent_user_id);
    if (empty($user)) {
        $agent_user_id = 0;
    }
    $args['meta_query'] = array();
    if (!empty($agent_user_id) && !empty($agent_id)) {
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => ERE_METABOX_PREFIX . 'property_agent',
                'value' => explode(',', $agent_id),
                'compare' => 'IN'
            ),
            array(
                'key' => ERE_METABOX_PREFIX . 'property_author',
                'value' => explode(',', $agent_user_id),
                'compare' => 'IN'
            )
        );
    } else {
        if (!empty($agent_user_id)) {
            $args['author'] = $agent_user_id;
        } else if (!empty($agent_id)) {
            $args['meta_query'] = array(
                array(
                    'key' => ERE_METABOX_PREFIX . 'property_agent',
                    'value' => explode(',', $agent_id),
                    'compare' => 'IN'
                )
            );
        }
    }
}
$data = new WP_Query($args);
wp_print_styles(ERE_PLUGIN_PREFIX . 'property');
$owl_attributes = array(
    '"items":1',
    '"dots": true',
    '"nav": false',
    '"autoplay": true',
    '"loop": true',
    '"responsive": {}'
);
$wrapper_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";
?>
<div class="list-recent-properties" >
    <div class="owl-carousel" <?php echo implode(' ', $wrapper_attributes); ?>>
        <?php if ($data->have_posts()):
            while ($data->have_posts()): $data->the_post();
                $attach_id = get_post_thumbnail_id();
                $width = 370;
                $height = 180;
                $image_src = ere_image_resize_id($attach_id, $width, $height, true);
                $no_image_src = ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
                $default_image = ere_get_option('default_property_image', '');
                if ($default_image != '') {
                    if (is_array($default_image) && $default_image['url'] != '') {
                        $resize = ere_image_resize_url($default_image['url'], $width, $height, true);
                        if ($resize != null && is_array($resize)) {
                            $no_image_src = $resize['url'];
                        }
                    }
                }
                $property_link = get_the_permalink();
                $property_id = get_the_ID();
                $property_label = get_the_terms($property_id, 'property-label');
                $price = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', true);
                $price_short = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_short', true);
                $price_unit = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_unit', true);
                $price_prefix = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_prefix', true);
                $price_postfix = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_postfix', true);
                $property_address = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_address', true);

                ?>
                <div class="property-item">
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
                            <?php if ($property_label): ?>
                                <div class="property-label">
                                    <?php foreach ($property_label as $label_item): ?>
                                        <p class="label-item"><span><?php echo esc_attr($label_item->name) ?></span>
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
                                }
                                ?>
                                <div class="property-location" title="<?php echo esc_attr($property_address) ?>">
                                    <i class="fa fa-map-marker"></i>
                                    <a target="_blank"
                                       href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_html($property_address) ?></span></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
        else: ?>
            <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
        <?php endif; ?>
    </div>
    <?php if(isset($instance['link']) && !empty($instance['link'])):?>
    <a class="ere-link-more" href="<?php echo esc_url($instance['link']) ?>"><?php esc_html_e('More...', 'essential-real-estate'); ?></a>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();