<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$property_id=get_the_ID();
$property_meta_data = get_post_custom($property_id);
$agent_display_option = isset($property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ][0] : '';
if ( $agent_display_option != 'no'):
?>
<div class="single-property-element property-contact-agent">
	<div class="ere-heading-style2">
		<h2><?php esc_html_e( 'Contact', 'essential-real-estate' ); ?></h2>
	</div>
	<div class="ere-property-element">
	<?php
	$property_agent       = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ][0] : '';
	$agent_type = '';$user_id=0;
	if ( $agent_display_option == 'author_info' || ( $agent_display_option == 'other_info') || ( $agent_display_option == 'agent_info' && ! empty( $property_agent ) ) ): ?>
		<div class="agent-info row">
			<?php
			$email = $avatar_src = $agent_link = $agent_name = $agent_position = $agent_facebook_url = $agent_twitter_url =
			$agent_linkedin_url = $agent_pinterest_url = $agent_skype =
			$agent_youtube_url = $agent_vimeo_url = $agent_mobile_number = $agent_office_address = $agent_website_url = $agent_description = '';
			if ( $agent_display_option != 'other_info' ) {
				$width = 270; $height = 340;
				$no_avatar_src= ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
				$default_avatar=ere_get_option('default_user_avatar','');
				if($default_avatar!='')
				{
					if(is_array($default_avatar)&& $default_avatar['url']!='')
					{
						$resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
						if ($resize != null && is_array($resize)) {
							$no_avatar_src = $resize['url'];
						}
					}
				}

				$property_agent_status = get_post_status($property_agent);
				if ($property_agent_status !== 'publish') {
					$agent_display_option = 'author_info';
				}

				if( $agent_display_option == 'author_info') {
					global $post;
					$user_id = $post->post_author;
					$email = get_userdata( $user_id )->user_email;
					$user_info      = get_userdata( $user_id );
					// Show Property Author Info (Get info via User. Apply for User, Agent, Seller)
					$author_picture_id = get_the_author_meta( ERE_METABOX_PREFIX . 'author_picture_id', $user_id );
					$avatar_src = ere_image_resize_id($author_picture_id, $width, $height, true);


					if(empty($user_info->first_name) && empty($user_info->last_name))
					{
						$agent_name=$user_info->user_login;
					}
					else
					{
						$agent_name     = $user_info->first_name . ' ' . $user_info->last_name;
					}
					$agent_facebook_url   = get_the_author_meta( ERE_METABOX_PREFIX . 'author_facebook_url', $user_id );
					$agent_twitter_url    = get_the_author_meta( ERE_METABOX_PREFIX . 'author_twitter_url', $user_id );
					$agent_linkedin_url   = get_the_author_meta( ERE_METABOX_PREFIX . 'author_linkedin_url', $user_id );
					$agent_pinterest_url  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_pinterest_url', $user_id );
					$agent_instagram_url  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_instagram_url', $user_id );
					$agent_skype          = get_the_author_meta( ERE_METABOX_PREFIX . 'author_skype', $user_id );
					$agent_youtube_url    = get_the_author_meta( ERE_METABOX_PREFIX . 'author_youtube_url', $user_id );
					$agent_vimeo_url      = get_the_author_meta( ERE_METABOX_PREFIX . 'author_vimeo_url', $user_id );

					$agent_mobile_number  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_mobile_number', $user_id );
					$agent_office_address = get_the_author_meta( ERE_METABOX_PREFIX . 'author_office_address', $user_id );
					$agent_website_url    = get_the_author_meta( 'user_url', $user_id );

					$author_agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
					$agent_status = get_post_status($author_agent_id);
					if ($agent_status == 'publish') {
						$agent_position = esc_html__( 'Property Agent', 'essential-real-estate' );
						$agent_type = esc_html__( 'Agent', 'essential-real-estate' );
						$agent_link = get_the_permalink($author_agent_id);
					} else {
						$agent_position = esc_html__( 'Property Seller', 'essential-real-estate' );
						$agent_type = esc_html__( 'Seller', 'essential-real-estate' );
						$agent_link = get_author_posts_url($user_id);
					}
				} else {
					$agent_post_meta_data = get_post_custom( $property_agent);
					$email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';
					$agent_name     = get_the_title($property_agent);
					$avatar_id = get_post_thumbnail_id($property_agent);
					$avatar_src = ere_image_resize_id($avatar_id, $width, $height, true);

					$agent_facebook_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url'][0] : '';
					$agent_twitter_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url'][0] : '';
					$agent_linkedin_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url'][0] : '';
					$agent_pinterest_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url'][0] : '';
					$agent_instagram_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url'][0] : '';
					$agent_skype = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype'][0] : '';
					$agent_youtube_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url'][0] : '';
					$agent_vimeo_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url'][0] : '';

					$agent_mobile_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number'][0] : '';
					$agent_office_address = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address'][0] : '';
					$agent_website_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url'][0] : '';

					$agent_position = esc_html__( 'Property Agent', 'essential-real-estate' );
					$agent_type = esc_html__( 'Agent', 'essential-real-estate' );
					$agent_link     = get_the_permalink( $property_agent );
				}
			} elseif ( $agent_display_option == 'other_info' ) {
				$email = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_mail' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_mail' ][0] : '';
				$agent_name = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ][0] : '';
				$agent_mobile_number = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_phone' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_phone' ][0] : '';
				$agent_description = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_description' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_description' ][0] : '';
			}
			?>
			<?php if ( $agent_display_option != 'other_info' ):?>
			<div class="agent-avatar col-md-6 col-sm-12 col-xs-12">
				<?php if ( ! empty( $agent_link ) ): ?>
					<a title="<?php echo esc_attr( $agent_name ) ?>" href="<?php echo esc_url( $agent_link ) ?>">
						<img
							 src="<?php echo esc_url($avatar_src) ?>"
							 onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
							 alt="<?php echo esc_attr($agent_name) ?>"
							 title="<?php echo esc_attr($agent_name) ?>">
					</a>
				<?php else:?>
					<img
						 src="<?php echo esc_url($avatar_src) ?>"
						 onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
						 alt="<?php echo esc_attr($agent_name) ?>"
						 title="<?php echo esc_attr($agent_name) ?>">
				<?php endif; ?>
			</div>
			<div class="agent-content col-md-6 col-sm-12 col-xs-12">
				<div class="agent-heading">
					<?php if ( ! empty( $agent_name ) ): ?>
						<h4><?php if ( ! empty( $agent_link ) ): ?><a title="<?php echo esc_attr( $agent_name ) ?>" href="<?php echo esc_url( $agent_link ) ?>"><?php endif; ?><?php echo esc_attr( $agent_name ) ?><?php if ( ! empty( $agent_link ) ): ?></a><?php endif; ?></h4>
					<?php endif; ?>
					<?php if ( ! empty( $agent_position ) ): ?>
						<span><?php echo esc_html( $agent_position ) ?></span>
					<?php endif; ?>
				</div>
				<div class="agent-social">
					<?php if ( ! empty( $agent_facebook_url ) ): ?>
						<a title="Facebook" href="<?php echo esc_url( $agent_facebook_url ); ?>">
							<i class="fa fa-facebook"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_twitter_url ) ): ?>
						<a title="Twitter" href="<?php echo esc_url( $agent_twitter_url ); ?>">
							<i class="fa fa-twitter"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_skype ) ): ?>
						<a title="Skype" href="skype:<?php echo esc_attr( $agent_skype ); ?>?chat">
							<i class="fa fa-skype"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_linkedin_url ) ): ?>
						<a title="Linkedin" href="<?php echo esc_url( $agent_linkedin_url ); ?>">
							<i class="fa fa-linkedin"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_pinterest_url ) ): ?>
						<a title="Pinterest" href="<?php echo esc_url( $agent_pinterest_url ); ?>">
							<i class="fa fa-pinterest"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_instagram_url ) ): ?>
						<a title="Instagram" href="<?php echo esc_url( $agent_instagram_url ); ?>">
							<i class="fa fa-instagram"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_youtube_url ) ): ?>
						<a title="Youtube" href="<?php echo esc_url( $agent_youtube_url ); ?>">
							<i class="fa fa-youtube-play"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_vimeo_url ) ): ?>
						<a title="Vimeo" href="<?php echo esc_url( $agent_vimeo_url ); ?>">
							<i class="fa fa-vimeo"></i>
						</a>
					<?php endif; ?>
				</div>
				<div class="agent-info-contact">
					<?php if ( ! empty( $agent_office_address ) ): ?>
						<div class="agent-address">
							<i class="fa fa-map-marker"></i>
							<span><?php echo esc_html( $agent_office_address ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $agent_mobile_number ) ): ?>
						<div class="agent-mobile">
							<i class="fa fa-phone"></i>
							<span><?php echo esc_html( $agent_mobile_number ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $email ) ): ?>
						<div class="agent-email">
							<i class="fa fa-envelope"></i>
							<span><?php echo esc_html( $email ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $agent_website_url ) ): ?>
						<div class="agent-website">
							<i class="fa fa-link"></i>
							<a href="<?php echo esc_url( $agent_website_url ); ?>"><?php echo esc_html( $agent_website_url ); ?></a>
						</div>
					<?php endif; ?>
				</div>
				<?php if(!empty( $agent_description )): ?>
					<div class="description">
						<p><?php echo wp_kses_post( $agent_description ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $property_agent ) ): ?>
					<a class="btn btn-primary" href="<?php echo get_post_type_archive_link( 'property' ); ?>?agent_id=<?php echo esc_attr($property_agent) ?>" title="<?php echo esc_attr( $agent_name ) ?>"><?php esc_html_e( 'Other Properties', 'essential-real-estate' ); ?></a>
				<?php else:?>
					<a class="btn btn-primary" href="<?php echo get_post_type_archive_link( 'property' ); ?>?user_id=<?php echo esc_attr($user_id) ?>" title="<?php echo esc_attr( $agent_name ) ?>"><?php esc_html_e( 'Other Properties', 'essential-real-estate' ); ?></a>
				<?php endif; ?>
			</div>
			<?php else:?>
				<div class="agent-content col-md-12 col-sm-12 col-xs-12">
					<div class="agent-heading">
						<?php if ( ! empty( $agent_name ) ): ?>
							<h4><span><?php esc_html_e('Name: ','essential-real-estate') ?></span><?php echo esc_attr( $agent_name ) ?></h4>
						<?php endif; ?>
					</div>
					<div class="agent-info-contact">
						<?php if ( ! empty( $agent_mobile_number ) ): ?>
							<div class="agent-mobile">
								<i class="fa fa-phone"></i>
								<span><?php echo esc_html( $agent_mobile_number ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $email ) ): ?>
							<div class="agent-email">
								<i class="fa fa-envelope"></i>
								<span><?php echo esc_html( $email ); ?></span>
							</div>
						<?php endif; ?>
					</div>
					<?php if(!empty( $agent_description )): ?>
						<div class="description">
							<p><?php echo wp_kses_post( $agent_description ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			<?php endif;?>
		</div>
		<?php if ( ! empty( $email ) ): ?>
		<div class="contact-agent">
			<form action="#" method="POST" id="contact-agent-form" class="row">
					<input type="hidden" name="target_email" value="<?php echo esc_attr( $email ); ?>">
					<input type="hidden" name="property_url" value="<?php echo get_permalink(); ?>">
					<div class="col-sm-4">
						<div class="form-group">
							<input class="form-control" name="sender_name" type="text"
								   placeholder="<?php esc_attr_e( 'Full Name', 'essential-real-estate' ); ?> *">
							<div
								class="hidden name-error form-error"><?php esc_html_e( 'Please enter your Name!', 'essential-real-estate' ); ?></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<input class="form-control" name="sender_phone" type="text"
								   placeholder="<?php esc_attr_e( 'Phone Number', 'essential-real-estate' ); ?> *">
							<div
								class="hidden phone-error form-error"><?php esc_html_e( 'Please enter your Phone!', 'essential-real-estate' ); ?></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<input class="form-control" name="sender_email" type="email"
								   placeholder="<?php esc_attr_e( 'Email Address', 'essential-real-estate' ); ?> *">
							<div class="hidden email-error form-error"
								 data-not-valid="<?php esc_attr_e( 'Your Email address is not Valid!', 'essential-real-estate' ) ?>"
								 data-error="<?php esc_attr_e( 'Please enter your Email!', 'essential-real-estate' ) ?>"><?php esc_html_e( 'Please enter your Email!', 'essential-real-estate' ); ?></div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<textarea class="form-control" name="sender_msg" rows="4"
									  placeholder="<?php esc_attr_e( 'Message', 'essential-real-estate' ); ?> *"><?php $title=get_the_title(); echo sprintf(esc_html__( 'Hello, I am interested in [%s]', 'essential-real-estate' ), esc_html($title)) ?></textarea>
							<div
								class="hidden message-error form-error"><?php esc_html_e( 'Please enter your Message!', 'essential-real-estate' ); ?></div>
						</div>
					</div>
					<div class="col-sm-6">
						<?php if (ere_enable_captcha('contact_agent')) {do_action('ere_generate_form_recaptcha');} ?>
					</div>
					<div class="col-sm-6 text-right">
						<?php wp_nonce_field('ere_contact_agent_ajax_nonce', 'ere_security_contact_agent'); ?>
						<input type="hidden" name="action" id="contact_agent_with_property_url_action" value="ere_contact_agent_ajax">
						<button type="submit"
								class="agent-contact-btn btn"><?php esc_html_e( 'Submit Request', 'essential-real-estate' ); ?></button>
						<div class="form-messages"></div>
					</div>
				</form>
		</div>
		<?php endif; ?>
	<?php endif; ?>
	</div>
</div>
<?php endif;