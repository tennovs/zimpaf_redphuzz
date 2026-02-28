<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_icon' ) ) {
  class CSFTICKET_Field_icon extends CSFTICKET_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'nirweb-support' ),
        'remove_title' => esc_html__( 'Remove Icon', 'nirweb-support' ),
      ) );

      echo $this->field_before();

      $nonce  = wp_create_nonce( 'CSFTICKET_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="CSFTICKET-icon-select">';
      echo '<span class="CSFTICKET-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></span>';
      echo '<a href="#" class="button button-primary CSFTICKET-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. wp_kses_post( $args['button_title'] ) .'</a>';
      echo '<a href="#" class="button CSFTICKET-warning-primary CSFTICKET-icon-remove'. esc_attr( $hidden ) .'">'. wp_kses_post( $args['remove_title'] ) .'</a>';
      echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="CSFTICKET-icon-value"'. $this->field_attributes() .' />';
      echo '</div>';

      echo $this->field_after();

    }

  }
}
