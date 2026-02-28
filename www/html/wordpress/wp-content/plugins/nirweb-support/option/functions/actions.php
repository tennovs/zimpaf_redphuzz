<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'CSFTICKET_get_icons' ) ) {
  function CSFTICKET_get_icons() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'CSFTICKET_icon_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'nirweb-support' ) ) );
    }

    ob_start();

    $icon_library = ( apply_filters( 'CSFTICKET_fa4', false ) ) ? 'fa4' : 'fa5';

    CSFTICKET::include_plugin_file( 'fields/icon/'. $icon_library .'-icons.php' );

    $icon_lists = apply_filters( 'CSFTICKET_field_icon_add_icons', CSFTICKET_get_default_icons() );

    if ( ! empty( $icon_lists ) ) {

      foreach ( $icon_lists as $list ) {

        echo ( count( $icon_lists ) >= 2 ) ? '<div class="CSFTICKET-icon-title">'. esc_attr( $list['title'] ) .'</div>' : '';

        foreach ( $list['icons'] as $icon ) {
          echo '<i title="'. esc_attr( $icon ) .'" class="'. esc_attr( $icon ) .'"></i>';
        }

      }

    } else {

      echo '<div class="CSFTICKET-text-error">'. esc_html__( 'No data provided by developer', 'nirweb-support' ) .'</div>';

    }

    $content = ob_get_clean();

    wp_send_json_success( array( 'content' => $content ) );

  }
  add_action( 'wp_ajax_CSFTICKET-get-icons', 'CSFTICKET_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'CSFTICKET_export' ) ) {
  function CSFTICKET_export() {

    $nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'CSFTICKET_backup_nonce' ) ) {
      die( esc_html__( 'Error: Nonce verification has failed. Please try again.', 'nirweb-support' ) );
    }

    if ( empty( $unique ) ) {
      die( esc_html__( 'Error: Options unique id could not valid.', 'nirweb-support' ) );
    }

    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode( get_option( $unique ) );

    die();

  }
  add_action( 'wp_ajax_CSFTICKET-export', 'CSFTICKET_export' );
}

/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'CSFTICKET_import_ajax' ) ) {
  function CSFTICKET_import_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';
    $data   = ( ! empty( $_POST[ 'data' ] ) ) ? wp_kses_post_deep( json_decode( wp_unslash( trim( $_POST[ 'data' ] ) ), true ) ) : array();

    if ( ! wp_verify_nonce( $nonce, 'CSFTICKET_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'nirweb-support' ) ) );
    }

    if ( empty( $unique ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Options unique id could not valid.', 'nirweb-support' ) ) );
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Import data could not valid.', 'nirweb-support' ) ) );
    }

    // Success
    update_option( $unique, $data );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_CSFTICKET-import', 'CSFTICKET_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'CSFTICKET_reset_ajax' ) ) {
  function CSFTICKET_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'CSFTICKET_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'nirweb-support' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_CSFTICKET-reset', 'CSFTICKET_reset_ajax' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'CSFTICKET_chosen_ajax' ) ) {
  function CSFTICKET_chosen_ajax() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $type  = ( ! empty( $_POST[ 'type' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'type' ] ) ) : '';
    $term  = ( ! empty( $_POST[ 'term' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';
    $query = ( ! empty( $_POST[ 'query_args' ] ) ) ? wp_kses_post_deep( $_POST[ 'query_args' ] ) : array();

    if ( ! wp_verify_nonce( $nonce, 'CSFTICKET_chosen_ajax_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'nirweb-support' ) ) );
    }

    if ( empty( $type ) || empty( $term ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Missing request arguments.', 'nirweb-support' ) ) );
    }

    $capability = apply_filters( 'CSFTICKET_chosen_ajax_capability', 'manage_options' );

    if ( ! current_user_can( $capability ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'nirweb-support' ) ) );
    }

    // Success
    $options = CSFTICKET_Fields::field_data( $type, $term, $query );

    wp_send_json_success( $options );

  }
  add_action( 'wp_ajax_CSFTICKET-chosen', 'CSFTICKET_chosen_ajax' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'CSFTICKET_set_icons' ) ) {
  function CSFTICKET_set_icons() {
    ?>
    <div id="CSFTICKET-modal-icon" class="CSFTICKET-modal CSFTICKET-modal-icon">
      <div class="CSFTICKET-modal-table">
        <div class="CSFTICKET-modal-table-cell">
          <div class="CSFTICKET-modal-overlay"></div>
          <div class="CSFTICKET-modal-inner">
            <div class="CSFTICKET-modal-title">
              <?php esc_html_e( 'Add Icon', 'nirweb-support' ); ?>
              <div class="CSFTICKET-modal-close CSFTICKET-icon-close"></div>
            </div>
            <div class="CSFTICKET-modal-header CSFTICKET-text-center">
              <input type="text" placeholder="<?php esc_html_e( 'Search a Icon...', 'nirweb-support' ); ?>" class="CSFTICKET-icon-search" />
            </div>
            <div class="CSFTICKET-modal-content">
              <div class="CSFTICKET-modal-loading"><div class="CSFTICKET-loading"></div></div>
              <div class="CSFTICKET-modal-load"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  add_action( 'admin_footer', 'CSFTICKET_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'CSFTICKET_set_icons' );
}
