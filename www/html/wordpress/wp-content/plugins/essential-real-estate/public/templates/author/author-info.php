<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wp_query;
$current_author = $wp_query->get_queried_object();
$current_author_meta = get_user_meta($current_author->ID);
if (empty($current_author->first_name) && empty($current_author->last_name)) {
	$author_name = $current_author->user_login;
} else {
	$author_name = $current_author->first_name . ' ' . $current_author->last_name;
}

$author_position = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_position']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_position'][0] : '';
$author_description = get_the_author_meta('description', $current_author->ID);
$author_email = get_the_author_meta('user_email', $current_author->ID);
$author_mobile_number = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_mobile_number']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_mobile_number'][0] : '';
$author_fax_number = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_fax_number']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_fax_number'][0] : '';
$author_website_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_website_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_website_url'][0] : '';
$author_facebook_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_facebook_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_facebook_url'][0] : '';
$author_twitter_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_twitter_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_twitter_url'][0] : '';
$author_linkedin_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_linkedin_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_linkedin_url'][0] : '';
$author_pinterest_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_pinterest_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_pinterest_url'][0] : '';
$author_instagram_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_instagram_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_instagram_url'][0] : '';
$author_skype = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_skype']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_skype'][0] : '';
$author_youtube_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_youtube_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_youtube_url'][0] : '';
$author_vimeo_url = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_vimeo_url']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_vimeo_url'][0] : '';
?>
<div class="agent-single">
	<div class="agent-single-inner row">
		<?php
		$user_custom_picture = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_custom_picture']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_custom_picture'][0] : '';
		$author_picture_id = isset($current_author_meta[ERE_METABOX_PREFIX . 'author_picture_id']) ? $current_author_meta[ERE_METABOX_PREFIX . 'author_picture_id'][0] : '';
		$no_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
		$width = get_option('thumbnail_size_w');
		$height = get_option('thumbnail_size_h');
		$default_avatar = ere_get_option('default_user_avatar', '');
		$avatar_src = ere_image_resize_id($author_picture_id, $width, $height, true);
		if ($default_avatar != '') {
			if (is_array($default_avatar) && $default_avatar['url'] != '') {
				$resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
				if ($resize != null && is_array($resize)) {
					$no_avatar_src = $resize['url'];
				}
			}
		}
		?>
		<div class="agent-avatar col-md-3 col-sm-12">
			<img width="<?php echo esc_attr($width) ?>"
				 height="<?php echo esc_attr($height) ?>"
				 src="<?php echo esc_url($avatar_src) ?>"
				 onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
				 alt="<?php echo esc_attr($author_name) ?>"
				 title="<?php echo esc_attr($author_name) ?>">
		</div>
		<div class="agent-content col-md-9 col-sm-12">
			<div class="agent-content-top agent-title">
				<?php if (!empty($author_name)): ?>
					<h2 class="agent-name"><?php echo esc_html($author_name) ?></h2>
				<?php endif; ?>
				<div class="agent-social">
					<?php if (!empty($author_facebook_url)): ?>
						<a title="Facebook" href="<?php echo esc_url($author_facebook_url); ?>">
							<i class="fa fa-facebook"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_twitter_url)): ?>
						<a title="Twitter" href="<?php echo esc_url($author_twitter_url); ?>">
							<i class="fa fa-twitter"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_skype)): ?>
						<a title="Skype" href="skype:<?php echo esc_url($author_skype); ?>?call">
							<i class="fa fa-skype"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_linkedin_url)): ?>
						<a title="Linkedin" href="<?php echo esc_url($author_linkedin_url); ?>">
							<i class="fa fa-linkedin"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_pinterest_url)): ?>
						<a title="Pinterest" href="<?php echo esc_url($author_pinterest_url); ?>">
							<i class="fa fa-pinterest"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_instagram_url)): ?>
						<a title="Instagram" href="<?php echo esc_url($author_instagram_url); ?>">
							<i class="fa fa-instagram"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_youtube_url)): ?>
						<a title="Youtube" href="<?php echo esc_url($author_youtube_url); ?>">
							<i class="fa fa-youtube-play"></i>
						</a>
					<?php endif; ?>
					<?php if (!empty($author_vimeo_url)): ?>
						<a title="Vimeo" href="<?php echo esc_url($author_vimeo_url); ?>">
							<i class="fa fa-vimeo"></i>
						</a>
					<?php endif; ?>
				</div>
				<?php if (!empty($author_position)): ?>
					<span class="fs-16 fw-medium"><?php echo esc_html($author_position) ?></span>
				<?php endif; ?>
			</div>
			<div class="agent-contact agent-info">
				<?php if (!empty($author_email)): ?>
					<span class="agent-contact-info"><i
							class="fa fa-envelope"></i> <?php esc_attr_e('Email:', 'essential-real-estate'); ?>
						<a style="display: inline;" href="mailto:<?php echo esc_attr($author_email) ?>"
						   title="<?php esc_attr_e('Website:', 'essential-real-estate'); ?>">
							<strong>
								<?php echo esc_html($author_email) ?>
							</strong>
						</a>
                    </span>
				<?php endif; ?>
				<?php if (!empty($author_mobile_number)): ?>
					<span class="agent-contact-info"><i class="fa fa-phone"></i>
						<?php esc_attr_e('Phone:', 'essential-real-estate'); ?>
						<strong>
							<?php echo esc_html($author_mobile_number) ?>
						</strong>
                    </span>
				<?php endif; ?>
				<?php if (!empty($author_website_url)): ?>
					<span class="agent-contact-info">
                        <i
							class="fa fa-link"></i>
						<?php esc_attr_e('Website:', 'essential-real-estate'); ?>
						<a style="display: inline;" href="<?php echo esc_url($author_website_url) ?>"
						   title="<?php esc_attr_e('Website:', 'essential-real-estate'); ?>">
							<strong><?php echo esc_url($author_website_url); ?></strong>
						</a>
                    </span>
				<?php endif; ?>
			</div>
			<?php if (!empty($author_description)): ?>
				<div class="agent-description">
					<?php echo wp_kses_post($author_description); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>