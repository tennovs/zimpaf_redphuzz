<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: notice
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_notice' ) ) {
  class CSFTICKET_Field_notice extends CSFTICKET_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';

      echo ( ! empty( $this->field['content'] ) ) ? '<div class="CSFTICKET-notice CSFTICKET-notice-'. esc_attr( $style ) .'">'. wp_kses_post( $this->field['content'] ) .'</div>' : '';

    }

  }
}
