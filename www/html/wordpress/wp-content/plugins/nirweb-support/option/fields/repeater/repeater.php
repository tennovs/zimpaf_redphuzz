<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_repeater' ) ) {
  class CSFTICKET_Field_repeater extends CSFTICKET_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'          => 0,
        'min'          => 0,
        'button_title' => '<i class="fas fa-plus-circle"></i>',
      ) );

      $fields    = $this->field['fields'];
      $unique_id = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];

      if ( $this->parent && preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->parent ) ) {

        echo '<div class="CSFTICKET-notice CSFTICKET-notice-danger">'. esc_html__( 'Error: Nested field id can not be same with another nested field id.', 'nirweb-support' ) .'</div>';

      } else {

        echo $this->field_before();

        echo '<div class="CSFTICKET-repeater-item CSFTICKET-repeater-hidden">';
        echo '<div class="CSFTICKET-repeater-content">';
        foreach ( $fields as $field ) {

          $field_parent  = $this->parent .'['. $this->field['id'] .']';
          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

          CSFTICKET::field( $field, $field_default, '_nonce', 'field/repeater', $field_parent );

        }
        echo '</div>';
        echo '<div class="CSFTICKET-repeater-helper">';
        echo '<div class="CSFTICKET-repeater-helper-inner">';
        echo '<i class="CSFTICKET-repeater-sort fas fa-arrows-alt"></i>';
        echo '<i class="CSFTICKET-repeater-clone far fa-clone"></i>';
        echo '<i class="CSFTICKET-repeater-remove CSFTICKET-confirm fas fa-times" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'nirweb-support' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="CSFTICKET-repeater-wrapper CSFTICKET-data-wrapper" data-unique-id="'. esc_attr( $this->unique ) .'" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) && is_array( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            echo '<div class="CSFTICKET-repeater-item">';

            echo '<div class="CSFTICKET-repeater-content">';
            foreach ( $fields as $field ) {

              $field_parent = $this->parent .'['. $this->field['id'] .']';
              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              CSFTICKET::field( $field, $field_value, $field_unique, 'field/repeater', $field_parent );

            }
            echo '</div>';

            echo '<div class="CSFTICKET-repeater-helper">';
            echo '<div class="CSFTICKET-repeater-helper-inner">';
            echo '<i class="CSFTICKET-repeater-sort fas fa-arrows-alt"></i>';
            echo '<i class="CSFTICKET-repeater-clone far fa-clone"></i>';
            echo '<i class="CSFTICKET-repeater-remove CSFTICKET-confirm fas fa-times" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'nirweb-support' ) .'"></i>';
            echo '</div>';
            echo '</div>';

            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="CSFTICKET-repeater-alert CSFTICKET-repeater-max">'. esc_html__( 'You can not add more than', 'nirweb-support' ) .' '. esc_attr( $args['max'] ) .'</div>';
        echo '<div class="CSFTICKET-repeater-alert CSFTICKET-repeater-min">'. esc_html__( 'You can not remove less than', 'nirweb-support' ) .' '. esc_attr( $args['min'] ) .'</div>';

        echo '<a href="#" class="button button-primary CSFTICKET-repeater-add">'. wp_kses_post( $args['button_title'] ) .'</a>';

        echo $this->field_after();

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}
