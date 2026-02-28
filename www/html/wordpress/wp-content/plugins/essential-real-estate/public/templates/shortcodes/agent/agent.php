<?php
/**
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$agency = $layout_style = $item_amount = $items = $image_size = $show_paging = $dots = $nav = $nav_position = $autoplay = $autoplaytimeout = $loop =
$items_md = $items_sm = $items_xs = $items_mb = $paged = $post_not_in = $el_class = '';

extract( shortcode_atts( array(
	'agency'          => '',
	'layout_style'    => 'agent-slider',
	'item_amount'     => '12',
	'items'           => '4',
	'image_size'      => '270x340',
	'show_paging'     => '',
	'dots'            => '',
	'nav'             => 'true',
	'nav_position'    => 'center',
	'autoplay'        => 'true',
	'autoplaytimeout' => '1000',
	'loop'            => '',
	'items_md'        => '3',
	'items_sm'        => '2',
	'items_xs'        => '2',
	'items_mb'        => '1',
	'post_not_in'     => '',
	'el_class'        => '',
	'paged'           => '1'
), $atts ) );

$wrapper_attributes = array();
$wrapper_styles     = array();

$wrapper_classes = array(
	'ere-agent',
	$layout_style,
	$el_class
);

$gf_item_wrap = '';

if ( $layout_style == 'agent-slider' ) {
	$wrapper_classes[] = 'owl-carousel manual';
	$show_paging       = 'false';

	if ( $nav ) {
		$wrapper_classes[] = 'owl-nav-' . $nav_position;
	}

	$owl_responsive_attributes = array();
	// Mobile <= 480px
	$owl_responsive_attributes[] = '"0" : {"items" : ' . $items_mb . ', "margin": 0}';

	// Extra small devices ( < 768px)
	$owl_responsive_attributes[] = '"481" : {"items" : ' . $items_xs . ', "margin": 30}';

	// Small devices Tablets ( < 992px)
	$owl_responsive_attributes[] = '"768" : {"items" : ' . $items_sm . ', "margin": 30}';

	// Medium devices ( < 1199px)
	$owl_responsive_attributes[] = '"992" : {"items" : ' . $items_md . ', "margin": 30}';

	// Medium devices ( > 1199px)
	$owl_responsive_attributes[] = '"1200" : {"items" : ' . $items . ', "margin": 30}';

	$owl_attributes       = array(
		'"dots": ' . ( $dots ? 'true' : 'false' ),
		'"nav": ' . ( $nav ? 'true' : 'false' ),
		'"autoplay": ' . ( $autoplay ? 'true' : 'false' ),
		'"autoplayTimeout": ' . ($autoplaytimeout ? $autoplaytimeout : 1000),
		'"loop": ' . ( $loop ? 'true' : 'false' ),
		'"responsive": {' . implode( ', ', $owl_responsive_attributes ) . '}'
	);
	$wrapper_attributes[] = "data-plugin-options='{" . implode( ', ', $owl_attributes ) . "}'";
}
if ( $layout_style == 'agent-grid' ) {
	$gf_item_wrap      = 'ere-item-wrap';
	$wrapper_classes[] = 'row columns-' . $items . ' columns-md-' . $items_md . ' columns-sm-' . $items_sm . ' columns-xs-' . $items_xs . ' columns-mb-' . $items_mb . '';
}
$posts_per_page = $item_amount ? $item_amount : - 1;

$args = array(
	'post_type'      => 'agent',
	'paged'          => $paged,
	'posts_per_page' => $posts_per_page,
	'orderby'        => array(
		'menu_order' => 'ASC',
		'date'       => 'DESC',
	),
	'post_status'    => 'publish',
	'post__not_in'   => array( $post_not_in )
);

if ( $agency != '' ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'agency',
			'field'    => 'slug',
			'terms'    => explode( ',', $agency ),
			'operator' => 'IN'
		)
	);
}
$args = apply_filters('ere_shortcodes_agent_query_args',$args);
$data = new WP_Query( $args );

$min_suffix = ere_get_option( 'enable_min_css', 0 ) == 1 ? '.min' : '';
wp_enqueue_style( ERE_PLUGIN_PREFIX . 'agent' );

$min_suffix_js = ere_get_option( 'enable_min_js', 0 ) == 1 ? '.min' : '';
wp_enqueue_script( ERE_PLUGIN_PREFIX . 'owl_carousel', ERE_PLUGIN_URL . 'public/assets/js/ere-carousel' . $min_suffix_js . '.js', array( 'jquery' ), ERE_PLUGIN_VER, true );
wp_enqueue_script( ERE_PLUGIN_PREFIX . 'agent', ERE_PLUGIN_URL . 'public/templates/shortcodes/agent/assets/js/agent' . $min_suffix_js . '.js', array( 'jquery' ), ERE_PLUGIN_VER, true );

?>
	<div class="ere-agent-wrap">
		<?php if ( $data->have_posts() ): ?>
			<div class="<?php echo join( ' ', $wrapper_classes ) ?>" <?php echo implode( ' ', $wrapper_attributes ); ?>>
				<?php
				$no_avatar_src  = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
				$default_avatar = ere_get_option( 'default_user_avatar', '' );
				if ( preg_match( '/\d+x\d+/', $image_size ) ) {
					$image_sizes = explode( 'x', $image_size );
					$width       = $image_sizes[0];
					$height      = $image_sizes[1];
					if ( $default_avatar != '' ) {
						if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
							$resize = ere_image_resize_url( $default_avatar['url'], $width, $height, true );
							if ( $resize != null && is_array( $resize ) ) {
								$no_avatar_src = $resize['url'];
							}
						}
					}
				} else {
					if ( $default_avatar != '' ) {
						if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
							$no_avatar_src = $default_avatar['url'];
						}
					}
				}
				while ( $data->have_posts() ): $data->the_post();
					$agent_id   = get_the_ID();
					$agent_name = get_the_title();
					$agent_link = get_the_permalink();

					$agent_post_meta_data = get_post_custom( $agent_id );

					$agent_position      = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_position' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_position' ][0] : '';
					$agent_description   = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_description' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_description' ][0] : '';
					$email               = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_email' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_email' ][0] : '';
					$agent_facebook_url  = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_facebook_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_facebook_url' ][0] : '';
					$agent_twitter_url   = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_twitter_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_twitter_url' ][0] : '';
					$agent_linkedin_url  = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_linkedin_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_linkedin_url' ][0] : '';
					$agent_pinterest_url = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_pinterest_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_pinterest_url' ][0] : '';
					$agent_instagram_url = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_instagram_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_instagram_url' ][0] : '';
					$agent_skype         = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_skype' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_skype' ][0] : '';
					$agent_youtube_url   = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_youtube_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_youtube_url' ][0] : '';
					$agent_vimeo_url     = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_vimeo_url' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_vimeo_url' ][0] : '';
					$agent_user_id       = isset( $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_user_id' ] ) ? $agent_post_meta_data[ ERE_METABOX_PREFIX . 'agent_user_id' ][0] : '';
					$user                = get_user_by( 'id', $agent_user_id );
					if ( empty( $user ) ) {
						$agent_user_id = 0;
					}
					$avatar_id  = get_post_thumbnail_id( $agent_id );
					$avatar_src = $default_avatar_src = '';
					$item_class = '';
					$width      = 270;
					$height     = 340;
					if ( preg_match( '/\d+x\d+/', $image_size ) ) {
						$image_sizes = explode( 'x', $image_size );
						$width       = $image_sizes[0];
						$height      = $image_sizes[1];
						$avatar_src  = ere_image_resize_id( $avatar_id, $width, $height, true );
					} else {
						if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
							$image_size = 'full';
						}
						$avatar_src = wp_get_attachment_image_src( $avatar_id, $image_size );
						if ( $avatar_src && ! empty( $avatar_src[0] ) ) {
							$avatar_src = $avatar_src[0];
						}
						if ( ! empty( $avatar_src ) ) {
							list( $width, $height ) = getimagesize( $avatar_src );
						}
					}
					?>
					<div class="agent-item <?php echo esc_attr( $gf_item_wrap ) ?>">
						<div class="agent-item-inner">
							<div class="agent-avatar">
								<a title="<?php echo esc_attr( $agent_name ) ?>"
								   href="<?php echo esc_url( $agent_link ) ?>"><img
											width="<?php echo esc_attr( $width ) ?>"
											height="<?php echo esc_attr( $height ) ?>"
											onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
											src="<?php echo esc_url( $avatar_src ) ?>"
											alt="<?php echo esc_attr( $agent_name ) ?>"
											title="<?php echo esc_attr( $agent_name ) ?>"></a>
							</div>
							<div class="agent-content">
								<div class="agent-info">
									<?php if ( ! empty( $agent_name ) ): ?>
										<h2 class="agent-name"><a title="<?php echo esc_attr( $agent_name ) ?>"
										                          href="<?php echo esc_url( $agent_link ) ?>"><?php echo esc_html( $agent_name ) ?></a>
										</h2>
									<?php endif; ?>
									<span><?php
										$ere_property   = new ERE_Property();
										$total_property = $ere_property->get_total_properties_by_user( $agent_id, $agent_user_id );
										printf( _n( '%s property', '%s properties', $total_property, 'essential-real-estate' ), ere_get_format_number( $total_property ) );
										?></span>
									<?php if ( ! empty( $agent_description ) && ( $layout_style == 'agent-list' ) ): ?>
										<p><?php echo wp_kses_post( $agent_description ) ?></p>
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
									<?php if ( ! empty( $email ) ): ?>
										<a title="Email" href="mailto:<?php echo esc_attr( $email ); ?>">
											<i class="fa fa-envelope"></i>
										</a>
									<?php endif; ?>
									<?php if ( ! empty( $agent_skype ) ): ?>
										<a title="Skype" href="skype:<?php echo esc_url( $agent_skype ); ?>?call">
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
							</div>
						</div>
					</div>
				<?php
				endwhile;

				if ( $show_paging == 'true' ) {
					?>
					<div class="agent-paging-wrap" data-admin-url="<?php echo ERE_AJAX_URL; ?>"
					     data-layout="<?php echo esc_attr( $layout_style ); ?>"
					     data-item-amount="<?php echo esc_attr( $item_amount ); ?>"
					     data-image-size="<?php echo esc_attr( $image_size ); ?>"
					     data-items="<?php echo esc_attr( $items ); ?>"
					     data-show-paging="<?php echo esc_attr( $show_paging ); ?>"
					     data-post-not-in="<?php echo esc_attr( $post_not_in ); ?>">
						<?php $max_num_pages = $data->max_num_pages;
						set_query_var( 'paged', $paged );
						ere_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
						?>
					</div>
				<?php } ?>
			</div>
		<?php else: ?>
			<div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
		<?php endif; ?>
	</div>
<?php
wp_reset_postdata();