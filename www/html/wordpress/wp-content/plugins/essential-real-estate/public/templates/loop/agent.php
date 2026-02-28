<?php
/**
 * @var $gf_item_wrap
 * @var $agent_layout_style
 * @var $custom_agent_image_size
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$agent_id = get_the_ID();
$agent_name = get_the_title();
$agent_link = get_the_permalink();

$agent_post_meta_data = get_post_custom($agent_id);

$agent_description = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description'][0] : '';
$email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';

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
$avatar_id = get_post_thumbnail_id($agent_id);
$width = 270;
$height = 340;
$no_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
$default_avatar = ere_get_option('default_user_avatar', '');

if (preg_match('/\d+x\d+/', $custom_agent_image_size)) {
    $image_sizes = explode('x', $custom_agent_image_size);
    $width=$image_sizes[0];$height= $image_sizes[1];
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
    if (!in_array($custom_agent_image_size, array('full', 'thumbnail'))) {
        $custom_agent_image_size = 'full';
    }
    $avatar_src = wp_get_attachment_image_src($avatar_id, $custom_agent_image_size);
    if ($avatar_src && !empty($avatar_src[0])) {
        $avatar_src = $avatar_src[0];
    }
    if (!empty($avatar_src)) {
        list($width, $height) = getimagesize($avatar_src);
    }
    if($default_avatar!='')
    {
        if(is_array($default_avatar)&& $default_avatar['url']!='')
        {
            $no_avatar_src = $default_avatar['url'];
        }
    }
}
?>
<div class="agent-item <?php echo esc_attr($gf_item_wrap) ?>">
    <div class="agent-item-inner">
        <div class="agent-avatar">
            <a
                title="<?php echo esc_attr($agent_name) ?>"
                href="<?php echo esc_url($agent_link) ?>"><img width="<?php echo esc_attr($width) ?>"
                                                               height="<?php echo esc_attr($height) ?>"
                                                               src="<?php echo esc_url($avatar_src) ?>"
                                                               onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                                                               alt="<?php echo esc_attr($agent_name) ?>"
                                                               title="<?php echo esc_attr($agent_name) ?>"></a>
        </div>
        <div class="agent-content">
            <div class="agent-info">
                <?php if (!empty($agent_name)): ?>
                    <h2 class="agent-name"><a
                            title="<?php echo esc_attr($agent_name) ?>"
                            href="<?php echo esc_url($agent_link) ?>"><?php echo esc_attr($agent_name) ?></a>
                    </h2>
                <?php endif; ?>
                <span class="agent-total-properties"><?php
                    $total_property = $ere_property->get_total_properties_by_user($agent_id, $agent_user_id);
                    printf( _n( '%s property', '%s properties', $total_property, 'essential-real-estate' ), ere_get_format_number($total_property ));
                    ?></span>
                <?php if (!empty($agent_description)): ?>
                    <p class="agent-description"><?php echo wp_kses_post($agent_description) ?></p>
                <?php endif; ?>
            </div>
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
        </div>
    </div>
</div>