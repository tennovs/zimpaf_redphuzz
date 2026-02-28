<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'ERE_Compare' ) ) {
	/**
	 * Class ERE_Compare
	 */
	class ERE_Compare {
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == null) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Add property to comapre
		 */
		public function compare_add_remove_property_ajax() {
			$property_id    = (int) $_POST['property_id'];
			$max_items      = 4;
			$this::open_session();
			$current_number = ( isset( $_SESSION['ere_compare_properties'] ) && is_array( $_SESSION['ere_compare_properties'] ) ) ? count( $_SESSION['ere_compare_properties'] ) : 0;

			if ( is_array( $_SESSION['ere_compare_properties'] ) && in_array( $property_id, $_SESSION['ere_compare_properties'] ) ) {
				unset( $_SESSION['ere_compare_properties'][ array_search( $property_id, $_SESSION['ere_compare_properties'] ) ] );
			} elseif ( $current_number < $max_items ) {

				$_SESSION['ere_compare_properties'][] = $property_id;
			}

			$_SESSION['ere_compare_properties'] = array_unique( $_SESSION['ere_compare_properties'] );

			$this->show_compare_listings();
			wp_die();
		}

		/*
		 * Open new session
		 */
		public static function open_session() {
			if ( ( function_exists( 'session_status' ) && session_status() !== PHP_SESSION_ACTIVE )
			     || ! session_id() ) {
				session_start();
				if ( ! isset( $_SESSION['ere_compare_starttime'] ) ) {
					$_SESSION['ere_compare_starttime'] = time();
				}
				if ( ! isset( $_SESSION['ere_compare_properties'] ) ) {
					$_SESSION['ere_compare_properties'] = array();
				}
			}
			if ( isset( $_SESSION['ere_compare_starttime'] ) ) {
				if ( (int) $_SESSION['ere_compare_starttime'] > time() + 86400 ) {
					unset( $_SESSION['ere_compare_properties'] );
				}
			}
		}

		/**
		 * output compare basket
		 */
		public function show_compare_listings() {
			$this::open_session();
			?>
			<div id="compare-properties-listings">
				<?php if ( isset( $_SESSION['ere_compare_properties'] ) && count( $_SESSION['ere_compare_properties'] ) ): ?>
					<div class="compare-listing-body">
						<div class="compare-thumb-main row">
							<?php
							$width             = get_option( 'thumbnail_size_w' );
							$height            = get_option( 'thumbnail_size_h' );
							$no_image_src      = ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
							$default_image     = ere_get_option( 'default_property_image', '' );
							if ( $default_image != '' ) {
								if ( is_array( $default_image ) && $default_image['url'] != '' ) {
									$resize = ere_image_resize_url( $default_image['url'], $width, $height, true );
									if ( $resize != null && is_array( $resize ) ) {
										$no_image_src = $resize['url'];
									}
								}
							}
							foreach ( $_SESSION['ere_compare_properties'] as $key ) : ?>
								<?php if ( $key != 0 ) :
									$attach_id = get_post_thumbnail_id( (double) $key );
									$image_src = ere_image_resize_id( $attach_id, $width, $height, true ); ?>
									<div class="compare-thumb compare-property"
									     data-property-id="<?php echo esc_attr( $key ); ?>">
										<img class="compare-property-img" width="<?php echo esc_attr( $width ) ?>"
										     height="<?php echo esc_attr( $height ) ?>"
										     src="<?php echo esc_url( $image_src ) ?>"
										     onerror="this.src = '<?php echo esc_url( $no_image_src ) ?>';">
										<button type="button" class="compare-property-remove"><i
													class="fa fa-times"></i></button>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<button type="button"
						        class="btn btn-primary btn-xs compare-properties-button"><?php esc_html_e( 'Compare', 'essential-real-estate' ); ?></button>
					</div>
					<button type="button" class="btn btn-primary listing-btn"><i class="fa fa-angle-left"></i></button>
				<?php endif; ?>
			</div>
			<?php
		}

		/*
		 * Close session
		 * */
		public function close_session() {
			if ( isset( $_SESSION ) ) {
				session_destroy();
			}
		}

		/**
		 * Compare template
		 */
		public function template_compare_listing() {
			ere_get_template( 'property/compare-listing.php' );
		}
	}
}