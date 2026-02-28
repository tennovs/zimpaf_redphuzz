<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $post;
$agent_id = get_the_ID();
$agent_post_meta_data = get_post_custom($agent_id);
$custom_agent_image_size_single = ere_get_option('custom_agent_image_size_single', '270x340');
$agent_name = get_the_title();
$agent_position = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_position']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_position'][0] : '';

$agent_description = apply_filters('ere_description',isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description'][0] : '') ;
$agent_company = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_company']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_company'][0] : '';
$agent_licenses = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_licenses']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_licenses'][0] : '';
$agent_office_address = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address'][0] : '';
$agent_mobile_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number'][0] : '';
$agent_fax_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_fax_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_fax_number'][0] : '';
$agent_office_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_number'][0] : '';
$email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';
$agent_website_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url'][0] : '';

$agent_facebook_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url'][0] : '';
$agent_twitter_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url'][0] : '';
$agent_linkedin_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url'][0] : '';
$agent_pinterest_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url'][0] : '';
$agent_instagram_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url'][0] : '';
$agent_skype = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype'][0] : '';
$agent_youtube_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url'][0] : '';
$agent_vimeo_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url'][0] : '';

$agent_user_id = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_user_id']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_user_id'][0] : '';
$user = get_user_by('id', $agent_user_id);
if (empty($user)) {
    $agent_user_id = 0;
}
$ere_property = new ERE_Property();
$total_property = $ere_property->get_total_properties_by_user($agent_id, $agent_user_id);

?>

<div class="single-agent-element agent-single">
    <div class="agent-single-inner row">
        <?php
        $avatar_id = get_post_thumbnail_id($agent_id);
        $avatar_src = '';
        $width = 270;
        $height = 340;
        $no_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
        $default_avatar = ere_get_option('default_user_avatar', '');
        if (preg_match('/\d+x\d+/', $custom_agent_image_size_single)) {
            $image_size = explode('x', $custom_agent_image_size_single);
            $width = $image_size[0];
            $height = $image_size[1];
            $avatar_src = ere_image_resize_id($avatar_id, $width, $height, true);
            if ($default_avatar != '') {
                if (is_array($default_avatar) && $default_avatar['url'] != '') {
                    $resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
                    if ($resize != null && is_array($resize)) {
                        $no_avatar_src = $resize['url'];
                    }
                }
            }
        } else {
            if (!in_array($custom_agent_image_size_single, array('full', 'thumbnail'))) {
                $custom_agent_image_size_single = 'full';
            }
            $avatar_src = wp_get_attachment_image_src($avatar_id, $custom_agent_image_size_single);
            if ($avatar_src && !empty($avatar_src[0])) {
                $avatar_src = $avatar_src[0];
            }
            if (!empty($avatar_src)) {
                list($width, $height) = getimagesize($avatar_src);
            }
            if ($default_avatar != '') {
                if (is_array($default_avatar) && $default_avatar['url'] != '') {
                    $no_avatar_src = $default_avatar['url'];
                }
            }
        }
        ?>
        <div class="agent-avatar text-center col-md-3 col-sm-12">
            <img width="<?php echo esc_attr($width) ?>"
                 height="<?php echo esc_attr($height) ?>"
                 src="<?php echo esc_url($avatar_src) ?>"
                 onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                 alt="<?php echo esc_attr($agent_name) ?>"
                 title="<?php echo esc_attr($agent_name) ?>">
            <?php if ($total_property > 0): ?>
                <a class="btn btn-primary btn-block"
                   href="<?php echo get_post_type_archive_link('property'); ?>?agent_id=<?php echo esc_attr($agent_id); ?>"
                   title="<?php echo esc_attr($agent_name) ?>"><?php esc_html_e('View All Properties', 'essential-real-estate'); ?></a>
            <?php endif; ?>
        </div>
        <div class="agent-content col-md-5 col-sm-12">
            <div class="agent-content-top">
                <?php if (!empty($agent_name)): ?>
                    <h2 class="agent-title"><?php echo esc_html($agent_name) ?></h2>
                <?php endif; ?>
                <div class="agent-social">
                    <?php if (!empty($agent_facebook_url)): ?>
                        <a title="Facebook" href="<?php echo esc_url($agent_facebook_url); ?>">
                            <i class="fa fa-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_twitter_url)): ?>
                        <a title="Twitter" href="<?php echo esc_url($agent_twitter_url); ?>">
                            <i class="fa fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($email)): ?>
                        <a title="Email" href="mailto:<?php echo esc_attr($email); ?>">
                            <i class="fa fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_skype)): ?>
                        <a title="Skype" href="skype:<?php echo esc_url($agent_skype); ?>?call">
                            <i class="fa fa-skype"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_linkedin_url)): ?>
                        <a title="Linkedin" href="<?php echo esc_url($agent_linkedin_url); ?>">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_pinterest_url)): ?>
                        <a title="Pinterest" href="<?php echo esc_url($agent_pinterest_url); ?>">
                            <i class="fa fa-pinterest"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_instagram_url)): ?>
                        <a title="Instagram" href="<?php echo esc_url($agent_instagram_url); ?>">
                            <i class="fa fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_youtube_url)): ?>
                        <a title="Youtube" href="<?php echo esc_url($agent_youtube_url); ?>">
                            <i class="fa fa-youtube-play"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($agent_vimeo_url)): ?>
                        <a title="Vimeo" href="<?php echo esc_url($agent_vimeo_url); ?>">
                            <i class="fa fa-vimeo"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($agent_position)): ?>
                    <span class="agent-position"><?php echo esc_html($agent_position) ?></span>
                <?php endif; ?>
                <span class="agent-number-property">
					<?php printf(_n('%s property', '%s properties', $total_property, 'essential-real-estate'), ere_get_format_number($total_property)); ?>
				</span>
            </div>
            <div class="agent-contact agent-info">
                <?php if (!empty($agent_office_address)): ?>
                    <div><i class="fa fa-map-marker"></i><strong><?php esc_html_e('Address:', 'essential-real-estate'); ?></strong>
							<span><?php echo esc_html($agent_office_address) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($email)): ?>
                    <div><i class="fa fa-envelope"></i><strong><?php esc_html_e('Email:', 'essential-real-estate'); ?></strong>
                        <a style="display: inline;" href="mailto:<?php echo esc_attr($email) ?>"
                           title="<?php esc_attr_e('Website:', 'essential-real-estate'); ?>">
								<span><?php echo esc_html($email) ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($agent_mobile_number)): ?>
                    <div>
                        <i class="fa fa-phone"></i><strong><?php esc_html_e('Phone:', 'essential-real-estate'); ?></strong>
                        <span><?php echo esc_html($agent_mobile_number) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($agent_website_url)): ?>
                    <div>
                        <i class="fa fa-link"></i><strong><?php esc_html_e('Website:', 'essential-real-estate'); ?></strong>
                        <a style="display: inline;" href="<?php echo esc_url($agent_website_url) ?>"
                           title="<?php esc_attr_e('Website:', 'essential-real-estate'); ?>">
                            <span><?php echo esc_url($agent_website_url); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <hr class="mg-top-20">
                <?php
                $agencies =  wp_get_post_terms( $agent_id, 'agency');
                if(count($agencies)>0){?>
                <div class="agent-agency">
                    <strong><?php esc_html_e('Agency:', 'essential-real-estate'); ?></strong>
                <?php
                foreach ($agencies as $agency)
                {
                    echo '<a href="' . esc_url( get_category_link( $agency->term_id ) ) . '">' . esc_html( $agency->name ) . '</a>';
                }
                ?>
                </div>
                <?php
                }
                if (!empty($agent_company)): ?>
                    <div>
                        <strong><?php esc_html_e('Company:', 'essential-real-estate'); ?></strong>
                        <span><?php echo esc_html($agent_company); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($agent_licenses)): ?>
                    <div>
                        <strong><?php esc_html_e('Licenses:', 'essential-real-estate'); ?></strong>
                        <span><?php echo esc_html($agent_licenses); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($agent_office_number)): ?>
                    <div>
                        <strong><?php esc_html_e('Office Number:', 'essential-real-estate'); ?></strong>
                        <span><?php echo esc_html($agent_office_number); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($agent_office_address)): ?>
                    <div>
                        <strong><?php esc_html_e('Office Address:', 'essential-real-estate'); ?></strong>
                        <span><?php echo esc_html($agent_office_address); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="contact-agent col-md-4 col-sm-12">
            <div class="ere-heading-style2 contact-agent-title">
                <h2><?php esc_html_e('Contact', 'essential-real-estate'); ?></h2>
            </div>
            <form action="#" method="POST" id="contact-agent-form">
                <input type="hidden" name="target_email" value="<?php echo esc_attr($email); ?>">

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
                <input type="hidden" name="action" id="contact_agent_action" value="ere_contact_agent_ajax">
                <?php if (ere_enable_captcha('contact_agent')) {
                    do_action('ere_generate_form_recaptcha');
                } ?>
                <button type="submit"
                        class="agent-contact-btn btn btn-block"><?php esc_html_e('Submit Request', 'essential-real-estate'); ?></button>
                <div class="form-messages"></div>
            </form>
        </div>
    </div>
    <?php if (!empty($agent_description)): ?>
        <div class="agent-description">
            <?php echo wp_kses_post($agent_description) ?>
        </div>
    <?php endif; ?>
</div>