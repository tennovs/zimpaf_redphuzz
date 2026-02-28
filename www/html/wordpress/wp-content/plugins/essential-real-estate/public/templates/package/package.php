<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$paid_submission_type = ere_get_option('paid_submission_type','no');
$allow_submit=ere_allow_submit();
if ((!$allow_submit)||($paid_submission_type != 'per_package'))
{
    echo ere_get_template_html('global/access-denied.php',array('type'=>'not_permission'));
    return;
}
?>
<div class="ere-package-wrap">
    <div class="ere-heading">
        <h2><?php esc_html_e('Listing Packages', 'essential-real-estate') ?></h2>
        <p><?php esc_html_e('Please select a listing package', 'essential-real-estate') ?></p>
    </div>
    <div class="row">
        <?php
        $args = array(
            'post_type' => 'package',
            'posts_per_page' => -1,
            'orderby'=> 'meta_value_num',
            'meta_key'=> ERE_METABOX_PREFIX . 'package_order_display',
            'order'=> 'ASC',
            'meta_query' => array(
                array(
                    'key' => ERE_METABOX_PREFIX . 'package_visible',
                    'value' => '1',
                    'compare' => '=',
                )
            )
        );
        $data = new WP_Query($args);
        $total_records = $data->found_posts;
        if ($total_records == 4) {
            $css_class = 'col-md-3 col-sm-6';
        } else if ($total_records == 3) {
            $css_class = 'col-md-4 col-sm-6';
        } else if ($total_records == 2) {
            $css_class = 'col-md-4 col-sm-6';
        } else if ($total_records == 1) {
            $css_class = 'col-md-4 col-sm-12';
        } else {
            $css_class = 'col-md-3 col-sm-6';
        }
        while ($data->have_posts()): $data->the_post();
            $package_id=get_the_ID();
            $package_time_unit = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_time_unit', true);
            $package_period = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_period', true);
            $package_num_properties = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
            $package_free = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_free', true);
            if($package_free==1)
            {
                $package_price=0;
            }
            else
            {
                $package_price = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_price', true);
            }
            $package_unlimited_listing = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_listing', true);
            $package_unlimited_time = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_time', true);
            $package_num_featured_listings = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_featured', true);
            $package_featured = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_featured', true);

            if ($package_period > 1) {
                $package_time_unit .= 's';
            }
            if ($package_featured == 1) {
                $is_featured = ' active';
            } else {
                $is_featured = '';
            }
            $payment_link = ere_get_permalink('payment');
            $payment_process_link = add_query_arg('package_id', $package_id, $payment_link);
            ?>
            <div class="<?php echo esc_attr($css_class); ?>">
                <div class="ere-package-item panel panel-default <?php echo esc_attr($is_featured); ?>">
                    <div class="ere-package-title panel-heading"><?php the_title(); ?></div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h2 class="ere-package-price">
                                <?php
                                if($package_price>0)
                                {
                                    echo ere_get_format_money($package_price,'',0,true);
                                }
                                else
                                {
                                    esc_html_e('Free','essential-real-estate');
                                }
                                ?>
                            </h2>
                        </li>
                        <li class="list-group-item">
                            <span class="badge">
                                <?php if ($package_unlimited_time == 1) {
                                        esc_html_e('Never Expires', 'essential-real-estate');
                                    } else {
                                        echo esc_html($package_period) . ' ' . ERE_Package::get_time_unit($package_time_unit);
                                    }
                                ?>
                            </span>
                            <?php esc_html_e('Expiration Date', 'essential-real-estate'); ?>
                        </li>
                        <li class="list-group-item"><span class="badge">
                                    <?php if ($package_unlimited_listing == 1) {
                                        esc_html_e('Unlimited', 'essential-real-estate');
                                    } else {
                                        echo esc_html($package_num_properties);
                                    } ?>
                                </span><?php esc_html_e('Property Listing', 'essential-real-estate'); ?></li>
                        <li class="list-group-item"><span
                                class="badge"><?php echo esc_html($package_num_featured_listings); ?></span><?php esc_html_e('Featured Listings', 'essential-real-estate') ?>
                        </li>
                        <li class="list-group-item ere-package-choose">
                            <a href="<?php echo esc_url($payment_process_link); ?>"
                               class="btn btn-primary"><?php esc_html_e('Choose', 'essential-real-estate'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>