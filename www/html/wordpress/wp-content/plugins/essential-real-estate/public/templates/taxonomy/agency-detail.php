<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$agency_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$agency_content = get_term_meta( $agency_term->term_id, 'agency_des', true );
$agency_content=wpautop($agency_content);
$agency_address = get_term_meta( $agency_term->term_id, 'agency_address', true );
$agency_map_address = get_term_meta( $agency_term->term_id, 'agency_map_address', true );

$agency_email = get_term_meta( $agency_term->term_id, 'agency_email', true );
$agency_mobile_number = get_term_meta( $agency_term->term_id, 'agency_mobile_number', true );
$agency_fax_number = get_term_meta( $agency_term->term_id, 'agency_fax_number', true );

$agency_licenses = get_term_meta( $agency_term->term_id, 'agency_licenses', true );

$agency_office_number = get_term_meta( $agency_term->term_id, 'agency_office_number', true );
$agency_website_url = get_term_meta( $agency_term->term_id, 'agency_website_url', true );
$agency_vimeo_url = get_term_meta( $agency_term->term_id, 'agency_vimeo_url', true );
$agency_facebook_url = get_term_meta( $agency_term->term_id, 'agency_facebook_url', true );
$agency_twitter_url = get_term_meta( $agency_term->term_id, 'agency_twitter_url', true );
$agency_linkedin_url = get_term_meta( $agency_term->term_id, 'agency_linkedin_url', true );
$agency_pinterest_url = get_term_meta( $agency_term->term_id, 'agency_pinterest_url', true );
$agency_instagram_url = get_term_meta( $agency_term->term_id, 'agency_instagram_url', true );
$agency_skype = get_term_meta( $agency_term->term_id, 'agency_skype', true );
$agency_youtube_url = get_term_meta( $agency_term->term_id, 'agency_youtube_url', true );

$enable_agents_of_agency = ere_get_option( 'enable_agents_of_agency', '1' );
wp_enqueue_script('bootstrap-tabcollapse');
// Query Property of Agency
$args = array(
    'post_type'      => 'agent',
    'post_status'    => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'agency',
            'field'    => 'slug',
            'terms'    => array($agency_term->slug),
            'operator' => 'IN'
        )
    )
);
$agent_data = new WP_Query($args);
$agent_id_arr = $author_id_arr = '';

$agent_id_arr = array();
$author_id_arr = array();

if($agent_data->have_posts()){
    while ($agent_data->have_posts()): $agent_data->the_post();
        $agent_id = get_the_ID();
        if (!empty($agent_id)) {
	        $agent_id_arr[] = $agent_id;

	        $agent_user_id = get_post_meta( $agent_id, ERE_METABOX_PREFIX . 'agent_user_id', true );
	        if (!empty($agent_user_id)) {
		        $author_id_arr[] = $agent_user_id;
	        }
        }
    endwhile;
    wp_reset_postdata();
	$agent_id_arr = join(',',$agent_id_arr);
	$author_id_arr = join(',', $author_id_arr);
}

if(empty($agent_id_arr)) {
    $agent_id_arr = '-1';
}

if(empty($author_id_arr)) {
    $author_id_arr = '-1';
}
$ere_property = new ERE_Property();
$total_property = $ere_property->get_total_properties_by_user($agent_id_arr, $author_id_arr);


$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'taxonomy-agency', ERE_PLUGIN_URL . 'public/assets/js/agent/ere-taxonomy-agency' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'taxonomy-agency', ERE_PLUGIN_URL . 'public/assets/css/taxonomy-agency' . $min_suffix . '.css');
?>
    <div class="agency-single-info row">
<?php
$logo_src = '';
$logo = get_term_meta($agency_term->term_id, 'agency_logo', true);
if ($logo && !empty($logo['url'])) {
    $logo_src = $logo['url'];
}
if (!empty($logo_src)) :
    list($width, $height) = getimagesize($logo_src); ?>
    <div class="agency-logo col-md-3 col-sm-5">
        <div class="agency-logo-inner">
            <img width="<?php echo esc_attr($width) ?>"
                 height="<?php echo esc_attr($height) ?>"
                 src="<?php echo esc_url($logo_src) ?>"
                 alt="<?php echo esc_attr($agency_term->name) ?>"
                 title="<?php echo esc_attr($agency_term->name) ?>">
        </div>
    </div>
    <div class="agency-content col-md-5 col-sm-7 xs-pd-top-40">
    <?php else: ?>
    <div class="agency-content col-md-8 col-sm-12">
<?php endif; ?>
    <div class="agency-content-top">
        <?php if (!empty($agency_term->name)): ?>
            <h2 class="agency-title"><?php echo esc_html($agency_term->name) ?></h2>
        <?php endif; ?>
        <?php if (!empty($agency_address)): ?>
            <div class="agency-address">
                <span><?php echo esc_html($agency_address) ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="agency-data">
        <div class="agency-data-item agency-data-property">
                    <span class="agency-data-item-title"><?php esc_html_e('Properties', 'essential-real-estate'); ?>: </span>
            <span><?php echo esc_html($total_property) ?></span>
        </div>
        <div class="agency-data-item agency-data-agent">
            <span class="agency-data-item-title"><?php esc_html_e('Agents', 'essential-real-estate'); ?>: </span>
            <span><?php echo esc_html($agency_term->count) ?></span>
        </div>
        <?php if (!empty($agency_licenses)): ?>
            <div class="agency-data-item agency-data-licenses">
                <span class="agency-data-item-title"><?php esc_html_e('Licenses', 'essential-real-estate'); ?>: </span>
                <span><?php echo esc_html($agency_licenses) ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="agency-contact">
        <div class="agency-info">
            <?php if (!empty($agency_office_number)): ?>
                <div class="agency-info-item agency-office-number">
                    <i class="fa fa-phone"></i><strong class="agency-info-title"><?php esc_html_e('Phone', 'essential-real-estate') ?>: </strong>
                    <span class="agency-info-value"><?php echo esc_attr($agency_office_number) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($agency_mobile_number)): ?>
                <div class="agency-info-item agency-mobile-number">
                    <i class="fa fa-mobile-phone"></i><strong class="agency-info-title"><?php esc_html_e('Mobile', 'essential-real-estate') ?>: </strong>
                    <span class="agency-info-value"><?php echo esc_attr($agency_mobile_number) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($agency_fax_number)): ?>
                <div class="agency-info-item agency-fax-number">
                    <i class="fa fa-print"></i><strong class="agency-info-title"><?php esc_html_e('Fax', 'essential-real-estate') ?>: </strong>
                    <span class="agency-info-value"><?php echo esc_attr($agency_fax_number) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($agency_website_url)): ?>
                <div class="agency-info-item agency-website">
                    <i class="fa fa-external-link-square "></i><strong class="agency-info-title"><?php esc_html_e('Website', 'essential-real-estate') ?>: </strong>
                    <a href="<?php echo esc_url($agency_website_url) ?>"  class="agency-info-value"><?php echo esc_url($agency_website_url) ?></a>
                </div>
            <?php endif; ?>
            <?php if (!empty($agency_email)): ?>
                <div class="agency-info-item agency-email">
                    <i class="fa fa-envelope"></i><strong class="agency-info-title"><?php esc_html_e('Email', 'essential-real-estate') ?>: </strong>
                    <span class="agency-info-value"><?php echo esc_attr($agency_email) ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="agency-social">
        <?php if (!empty($agency_facebook_url)): ?>
            <a title="Facebook" href="<?php echo esc_url($agency_facebook_url); ?>">
                <i class="fa fa-facebook"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_twitter_url)): ?>
            <a title="Twitter" href="<?php echo esc_url($agency_twitter_url); ?>">
                <i class="fa fa-twitter"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_skype)): ?>
            <a title="Skype" href="skype:<?php echo esc_url($agency_skype); ?>?call">
                <i class="fa fa-skype"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_linkedin_url)): ?>
            <a title="Linkedin" href="<?php echo esc_url($agency_linkedin_url); ?>">
                <i class="fa fa-linkedin"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_pinterest_url)): ?>
            <a title="Pinterest" href="<?php echo esc_url($agency_pinterest_url); ?>">
                <i class="fa fa-pinterest"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_instagram_url)): ?>
            <a title="Instagram" href="<?php echo esc_url($agency_instagram_url); ?>">
                <i class="fa fa-instagram"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_youtube_url)): ?>
            <a title="Youtube" href="<?php echo esc_url($agency_youtube_url); ?>">
                <i class="fa fa-youtube-play"></i>
            </a>
        <?php endif; ?>
        <?php if (!empty($agency_vimeo_url)): ?>
            <a title="Vimeo" href="<?php echo esc_url($agency_vimeo_url); ?>">
                <i class="fa fa-vimeo"></i>
            </a>
        <?php endif; ?>
    </div>
    </div>
    <div class="contact-agency col-md-4 col-sm-12 sm-pd-top-40">
        <div class="ere-heading-style2 contact-agency-title">
            <h2><?php esc_html_e('Contact Agency', 'essential-real-estate'); ?></h2>
        </div>
        <form method="post" enctype="multipart/form-data" action="#" id="contact-agent-form">
            <input type="hidden" name="target_email" value="<?php echo esc_attr($agency_email); ?>">

            <div class="form-group">
                <input class="form-control" name="sender_name" type="text"
                       placeholder="<?php esc_attr_e('Full Name', 'essential-real-estate'); ?> *">

                <div
                    class="hidden name-error form-error"><?php esc_html_e('Please enter your Name!', 'essential-real-estate'); ?></div>
            </div>
            <div class="form-group">
                <input class="form-control" name="sender_phone" type="text"
                       placeholder="<?php esc_attr_e('Phone Number', 'essential-real-estate'); ?> *">

                <div
                    class="hidden phone-error form-error"><?php esc_html_e('Please enter your Phone!', 'essential-real-estate'); ?></div>
            </div>
            <div class="form-group">
                <input class="form-control" name="sender_email" type="email"
                       placeholder="<?php esc_attr_e('Email Address', 'essential-real-estate'); ?> *">

                <div class="hidden email-error form-error"
                     data-not-valid="<?php esc_attr_e('Your Email address is not Valid!', 'essential-real-estate') ?>"
                     data-error="<?php esc_attr_e('Please enter your Email!', 'essential-real-estate') ?>"><?php esc_html_e('Please enter your Email!', 'essential-real-estate'); ?></div>
            </div>
            <div class="form-group">
                            <textarea class="form-control" name="sender_msg" rows="5"
                                      placeholder="<?php esc_attr_e('Message', 'essential-real-estate'); ?> *"></textarea>

                <div
                    class="hidden message-error form-error"><?php esc_html_e('Please enter your Message!', 'essential-real-estate'); ?></div>
            </div>
            <?php wp_nonce_field('ere_contact_agent_ajax_nonce', 'ere_security_contact_agent'); ?>
            <input type="hidden" name="action" id="contact_agency_action" value="ere_contact_agent_ajax">
            <?php if (ere_enable_captcha('contact_agency')) {do_action('ere_generate_form_recaptcha');} ?>
            <button type="submit"
                    class="agent-contact-btn btn btn-block"><?php esc_html_e('Submit Request', 'essential-real-estate'); ?></button>
            <div class="form-messages"></div>
        </form>
    </div>
    </div>
<?php $enable_property_of_agency = ere_get_option('enable_property_of_agency', '1'); ?>
    <div class="agency-details-tab">
        <ul class="nav nav-tabs" id="ere-agency-details-tabs">
            <?php if (!empty($agency_content)): ?>
                <li class="active"><a data-toggle="tab"
                                      href="#agency-overview"><?php esc_html_e('Overview', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
            <?php if ($enable_property_of_agency): ?>
                <li<?php if (empty($agency_content)): ?> class="active"<?php endif; ?>><a data-toggle="tab"
                                                                                            href="#agency-properties"><?php esc_html_e('Properties', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
            <?php if (!empty($agency_map_address['location'])): ?>
                <li<?php if (empty($agency_content) && !$enable_property_of_agency): ?> class="active"<?php endif; ?>>
                    <a data-toggle="tab" href="#agency-location"><?php esc_html_e('Location', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content">
            <?php if (!empty($agency_content)): ?>
                <div id="agency-overview" class="tab-pane fade in active">
                    <?php echo wp_kses_post($agency_content); ?>
                </div>
            <?php endif; ?>
            <?php if ($enable_property_of_agency): ?>
                <div id="agency-properties"
                     class="tab-pane fade <?php if (empty($agency_content)): ?> in active<?php endif; ?>">
                    <?php
                    $property_of_agency_layout_style = ere_get_option('property_of_agency_layout_style', 'property-grid');
                    $property_of_agency_items_amount = ere_get_option('property_of_agency_items_amount', 6);
                    $property_of_agency_image_size = ere_get_option('property_of_agency_image_size', '330x180');
                    $property_of_agency_show_paging = ere_get_option('property_of_agency_show_paging', array());

                    $property_of_agency_column_lg = ere_get_option('property_of_agency_column_lg', '3');
                    $property_of_agency_column_md = ere_get_option('property_of_agency_column_md', '3');
                    $property_of_agency_column_sm = ere_get_option('property_of_agency_column_sm', '2');
                    $property_of_agency_column_xs = ere_get_option('property_of_agency_column_xs', '1');
                    $property_of_agency_column_mb = ere_get_option('property_of_agency_column_mb', '1');

                    $property_of_agency_columns_gap = ere_get_option('property_of_agency_columns_gap', 'col-gap-30');

                    if (!is_array($property_of_agency_show_paging)) {
                        $property_of_agency_show_paging = array();
                    }

                    if (in_array("show_paging_property_of_agency", $property_of_agency_show_paging)) {
                        $property_of_agency_show_paging = 'true';
                    } else {
                        $property_of_agency_show_paging = '';
                        $property_of_agency_items_amount = '-1';
                    }

                    $property_agency_shortcode = '[ere_property layout_style = "' . $property_of_agency_layout_style . '"
                                item_amount = "' . $property_of_agency_items_amount . '" columns="' . $property_of_agency_column_lg . '"
                                items_md="' . $property_of_agency_column_md . '"
                                items_sm="' . $property_of_agency_column_sm . '"
                                items_xs="' . $property_of_agency_column_xs . '"
                                items_mb="' . $property_of_agency_column_mb . '"
                                image_size = "' . $property_of_agency_image_size . '"
                                columns_gap = "' . $property_of_agency_columns_gap . '"
                                show_paging = "' . $property_of_agency_show_paging . '"
                                author_id = "' . $author_id_arr . '" agent_id = "' . $agent_id_arr . '"]';
                    echo do_shortcode($property_agency_shortcode);
                    ?>
                </div>
            <?php endif; ?>
            <div id="agency-location"
                 class="tab-pane fade <?php if (empty($agency_content) && !$enable_property_of_agency): ?> in active<?php endif; ?>">
                <?php
                list($lat, $lng) = explode(',', $agency_map_address['location']);
                $map_shortcode = '[ere_property_map map_style="normal"
                            lat="' . $lat . '" lng="' . $lng . '" map_height="400px"]';
                echo do_shortcode($map_shortcode);
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#ere-agency-details-tabs').tabCollapse();
        });
    </script>
<?php
$agency_term_slug=$agency_term->slug;
do_action( 'ere_taxonomy_agency_agents', $agency_term_slug); ?>