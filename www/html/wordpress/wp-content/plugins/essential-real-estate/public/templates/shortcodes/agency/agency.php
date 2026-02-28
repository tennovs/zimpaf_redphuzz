<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$item_amount = $show_paging = $include_heading = $heading_sub_title
	= $heading_title = $heading_text_align = $el_class = '';
extract( shortcode_atts( array(
	'item_amount'       => '6',
	'show_paging'       => '',
	'include_heading'   => '',
	'heading_sub_title' => '',
	'heading_title'     => '',
	'heading_text_align' => 'text-left',
	'el_class'          => ''
), $atts ) );

$agency_item_class = array( 'agency-item mg-bottom-60 sm-mg-bottom-40' );
$wrapper_classes = array(
	'ere-agency clearfix',
	$el_class
);
$keyword = '';$unique=array();
$args = array(
	'number' => ( $item_amount > 0 ) ? $item_amount : - 1,
	'taxonomy'      => 'agency',
	'orderby'        => 'date',
	'offset' => (max(1, get_query_var('paged')) - 1) * $item_amount,
	'order'          => 'DESC',
);

if (isset ($_GET['keyword'])) {
	$keyword = ere_clean(wp_unslash($_GET['keyword']));
	$q1 = get_categories(array(
		'fields' => 'ids',
		'taxonomy'      => 'agency',
		'name__like' => $keyword
	));
	$q2 = get_categories(array(
		'fields' => 'ids',
		'taxonomy'      => 'agency',
		'meta_query' => array(
			array(
				'key'       => 'agency_address',
				'value'     => $keyword,
				'compare'   => 'LIKE'
			)
		)
	));
	$unique = array_unique( array_merge( $q1, $q2 ) );
	if(empty($unique))
	{
		$unique[]=-1;
	}
	$args['include'] = $unique;
}

if (isset($_GET['sortby']) && in_array($_GET['sortby'], array('a_date','d_date','a_name','d_name'))) {
	if ($_GET['sortby'] == 'a_date') {
		$args['orderby'] = 'date';
		$args['order'] = 'ASC';
	} else if ($_GET['sortby'] == 'd_date') {
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
	}else if ($_GET['sortby'] == 'a_name') {
		$args['orderby'] = 'name__like';
		$args['order'] = 'ASC';
	}else if ($_GET['sortby'] == 'd_name') {
		$args['orderby'] = 'name__like';
		$args['order'] = 'DESC';
	}
}
$args = apply_filters('ere_shortcodes_agency_query_args',$args);
$agencies = get_categories($args);

$min_suffix = ere_get_option( 'enable_min_css', 0 ) == 1 ? '.min' : '';
wp_enqueue_style( ERE_PLUGIN_PREFIX . 'agency');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'agency', ERE_PLUGIN_URL . 'public/templates/shortcodes/agency/assets/js/agency' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-agency-wrap">
	<div class="<?php echo join( ' ', $wrapper_classes ) ?>">
		<?php if ( $include_heading && (!empty( $heading_sub_title ) || !empty( $heading_title ))) :
			$heading_class=$heading_text_align;
			?>
		<div class="ere-heading <?php echo esc_attr( $heading_class ); ?>">
			<?php if ( ! empty( $heading_title ) ): ?>
				<h2><?php echo esc_html( $heading_title ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $heading_sub_title ) ): ?>
				<p><?php echo esc_html( $heading_sub_title ); ?></p>
			<?php endif; ?>
		</div>
		<div class="agency-action-wrap">
			<div class="agency-action">
				<div class="agency-action-item">
					<form method="get" action="<?php echo get_page_link();?>">
						<div class="form-group input-group search-box">
							<input type="text" name="keyword"
																			  value="<?php echo esc_attr($keyword); ?>"
																			  class="form-control"
																			  placeholder="<?php esc_attr_e('Name or Location', 'essential-real-estate'); ?>">
							<span
								class="input-group-btn">
								<button type="submit" class="button"><i
										class="fa fa-search"></i></button></span>
						</div>
					</form>
				</div>
				<div class="sort-agency agency-action-item">
					<span class="sort-by"><?php esc_html_e('Sort By', 'essential-real-estate'); ?></span>
					<ul>
						<li><a data-sortby="a_name" href="<?php
							$pot_link_sortby = add_query_arg(array('sortby' => 'a_name'));
							echo esc_url($pot_link_sortby) ?>"
							   title="<?php esc_attr_e('Name (A to Z)', 'essential-real-estate'); ?>"><?php esc_html_e('Name (A to Z)', 'essential-real-estate'); ?></a>
						</li>
						<li><a data-sortby="d_name" href="<?php
							$pot_link_sortby = add_query_arg(array('sortby' => 'd_name'));
							echo esc_url($pot_link_sortby) ?>"
							   title="<?php esc_attr_e('Name (Z to A)', 'essential-real-estate'); ?>"><?php esc_html_e('Name (Z to A)', 'essential-real-estate'); ?></a>
						</li>
						<li><a data-sortby="a_date" href="<?php
							$pot_link_sortby = add_query_arg(array('sortby' => 'a_date'));
							echo esc_url($pot_link_sortby) ?>"
							   title="<?php esc_attr_e('Date (Old to New)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (Old to New)', 'essential-real-estate'); ?></a>
						</li>
						<li><a data-sortby="d_date" href="<?php
							$pot_link_sortby = add_query_arg(array('sortby' => 'd_date'));
							echo esc_url($pot_link_sortby) ?>"
							   title="<?php esc_attr_e('Date (New to Old)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (New to Old)', 'essential-real-estate'); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="agency-content">
			<?php if ( $agencies ) :
				foreach ($agencies as $agency) :
					$agency_id = $agency->term_id;
					$agency_address = get_term_meta( $agency_id, 'agency_address', true );
					$agency_map_address = get_term_meta( $agency_id, 'agency_map_address', true );

					$agency_email = get_term_meta( $agency_id, 'agency_email', true );
					$agency_mobile_number = get_term_meta( $agency_id, 'agency_mobile_number', true );
					$agency_fax_number = get_term_meta( $agency_id, 'agency_fax_number', true );

					$agency_licenses = get_term_meta( $agency_id, 'agency_licenses', true );

					$agency_office_number = get_term_meta( $agency_id, 'agency_office_number', true );
					$agency_website_url = get_term_meta( $agency_id, 'agency_website_url', true );
					$agency_vimeo_url = get_term_meta( $agency_id, 'agency_vimeo_url', true );
					$agency_facebook_url = get_term_meta( $agency_id, 'agency_facebook_url', true );
					$agency_twitter_url = get_term_meta( $agency_id, 'agency_twitter_url', true );
					$agency_linkedin_url = get_term_meta( $agency_id, 'agency_linkedin_url', true );
					$agency_pinterest_url = get_term_meta( $agency_id, 'agency_pinterest_url', true );
					$agency_instagram_url = get_term_meta( $agency_id, 'agency_instagram_url', true );
					$agency_skype = get_term_meta( $agency_id, 'agency_skype', true );
					$agency_youtube_url = get_term_meta( $agency_id, 'agency_youtube_url', true );

					$logo_src = get_term_meta( $agency_id, 'agency_logo', true );
					if ($logo_src && !empty($logo_src['url'])) {
						$logo_src = $logo_src['url'];
					}

					$agency_link = get_term_link( $agency->slug, 'agency' );
					?>
					<div class="<?php echo join( ' ', $agency_item_class ); ?>">
						<div class="agency-inner pd-bottom-60 sm-pd-bottom-40">
							<?php if ( ! empty( $logo_src ) ): ?>
								<?php list( $width, $height ) = getimagesize( $logo_src ); ?>
								<div class="agency-avatar">
									<a href="<?php echo esc_url( $agency_link ); ?>" title="<?php echo esc_attr( $agency->name ); ?>"><img width="<?php echo esc_attr( $width ) ?>"
									     height="<?php echo esc_attr( $height ) ?>"
									     src="<?php echo esc_url( $logo_src ) ?>" alt="<?php echo esc_attr( $agency->name ); ?>"
									     title="<?php echo esc_attr( $agency->name ); ?>"></a>
								</div>
							<?php endif; ?>
							<div class="agency-item-content">
								<div class="agency-heading agency-element-inline">
									<div>
										<?php if(!empty( $agency->name )): ?>
											<h2 class="agency-title">
												<a href="<?php echo esc_url( $agency_link ); ?>" title="<?php echo esc_attr( $agency->name ); ?>"><?php echo esc_html( $agency->name ); ?></a>
											</h2>
										<?php endif; ?>
										<?php if ( ! empty( $agency_address ) ): ?>
											<div class="agency-position">
												<p title="<?php echo esc_attr( $agency_address ) ?>">
													<span><?php echo esc_html( $agency_address ) ?></span>
												</p>
											</div>
										<?php endif; ?>
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
								<?php
								$excerpt = $agency->description; ?>
								<?php if ( isset( $excerpt ) && ! empty( $excerpt ) ): ?>
									<div class="agency-excerpt fw-normal">
										<p><?php echo esc_html( $excerpt ) ?></p>
									</div>
								<?php endif; ?>
								<div class="agency-info">
									<div class="agency-info-inner fw-normal">
										<?php if (!empty($agency_office_number)): ?>
											<div class="agency-info-item agency-office-number">
												<i class="fa fa-phone"></i><strong class="agency-info-title"> <?php esc_html_e( 'Phone', 'essential-real-estate' ) ?>: </strong>
												<span class="agency-info-value"><?php echo esc_html($agency_office_number) ?></span>
											</div>
										<?php endif; ?>
										<?php if (!empty($agency_mobile_number)):?>
											<div class="agency-info-item agency-mobile-number">
												<i class="fa fa-mobile-phone"></i><strong class="agency-info-title"> <?php esc_html_e( 'Mobile', 'essential-real-estate' ) ?>: </strong>
												<span class="agency-info-value"><?php echo esc_html($agency_mobile_number) ?></span>
											</div>
										<?php endif;?>
										<?php if (!empty($agency_fax_number)):?>
											<div class="agency-info-item agency-fax-number">
												<i class="fa fa-print"></i><strong class="agency-info-title"><?php esc_html_e( 'Fax', 'essential-real-estate' ) ?>: </strong>
												<span class="agency-info-value"><?php echo esc_html($agency_fax_number) ?></span>
											</div>
										<?php endif;?>
										<?php if (!empty($agency_email)): ?>
											<div class="agency-info-item agency-email">
												<i class="fa fa-envelope"></i><strong class="agency-info-title"> <?php esc_html_e( 'Email', 'essential-real-estate' ) ?>: </strong>
												<span class="agency-info-value"><?php echo esc_html($agency_email) ?></span>
											</div>
										<?php endif; ?>
										<?php if (!empty($agency_website_url)): ?>
											<div class="agency-info-item agency-website">
												<i class="fa fa-external-link-square"></i><strong class="agency-info-title"> <?php esc_html_e( 'Website', 'essential-real-estate' ) ?>: </strong>
												<a href="<?php echo esc_url($agency_website_url) ?>" class="agency-info-value"><?php echo esc_url($agency_website_url) ?></a>
											</div>
										<?php endif; ?>
										<?php if(!empty( $agency_licenses )): ?>
											<div class="agency-info-item agency-licenses">
												<i class="fa fa-balance-scale"></i><strong class="agency-info-title"> <?php esc_html_e( 'Licenses', 'essential-real-estate' ); ?>: </strong>
												<span class="agency-info-value"><?php echo esc_html( $agency_licenses ) ?></span>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach;
			else: ?>
				<div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
		<?php
		if ( $show_paging == 'true' ) {?>
			<div class="agency-paging-wrap" data-admin-url="<?php echo ERE_AJAX_URL; ?>"
			     data-items-amount="<?php echo esc_attr( $item_amount ); ?>" >
				<?php
				$args = array(
					'taxonomy'      => 'agency'
				);
				if (isset ($_GET['keyword'])) {
					$args['include'] = $unique;
				}
				$all_agency = get_categories($args);
				$max_num_pages = floor(count( $all_agency ) / $item_amount);
				if(count( $all_agency ) % $item_amount > 0) {
					$max_num_pages++;
				}
				ere_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
				?>
			</div>
		<?php }
		wp_reset_postdata(); ?>
	</div>
</div>