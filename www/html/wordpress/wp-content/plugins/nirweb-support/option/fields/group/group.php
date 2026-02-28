<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_group' ) ) {
  class CSFTICKET_Field_group extends CSFTICKET_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'                    => 0,
        'min'                    => 0,
        'fields'                 => array(),
        'button_title'           => esc_html__( 'Add New', 'nirweb-support' ),
        'accordion_title_prefix' => '',
        'accordion_title_number' => false,
        'accordion_title_auto'   => true,
      ) );

      $title_prefix = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
      $title_number = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
      $title_auto   = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;

      if ( ! empty( $this->parent ) && preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->parent ) ) {

        echo '<div class="CSFTICKET-notice CSFTICKET-notice-danger">'. esc_html__( 'Error: Nested field id can not be same with another nested field id.', 'nirweb-support' ) .'</div>';

      } else {

        echo $this->field_before();

        echo '<div class="CSFTICKET-cloneable-item CSFTICKET-cloneable-hidden">';

          echo '<div class="CSFTICKET-cloneable-helper">';
          echo '<i class="CSFTICKET-cloneable-sort fas fa-arrows-alt"></i>';
          echo '<i class="CSFTICKET-cloneable-clone far fa-clone"></i>';
          echo '<i class="CSFTICKET-cloneable-remove CSFTICKET-confirm fas fa-times" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'nirweb-support' ) .'"></i>';
          echo '</div>';

          echo '<h4 class="CSFTICKET-cloneable-title">';
          echo '<span class="CSFTICKET-cloneable-text">';
          echo ( $title_number ) ? '<span class="CSFTICKET-cloneable-title-number"></span>' : '';
          echo ( $title_prefix ) ? '<span class="CSFTICKET-cloneable-title-prefix">'. esc_attr( $title_prefix ) .'</span>' : '';
          echo ( $title_auto ) ? '<span class="CSFTICKET-cloneable-value"><span class="CSFTICKET-cloneable-placeholder"></span></span>' : '';
          echo '</span>';
          echo '</h4>';

          echo '<div class="CSFTICKET-cloneable-content">';
          foreach ( $this->field['fields'] as $field ) {

            $field_parent  = $this->parent .'['. $this->field['id'] .']';
            $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

            CSFTICKET::field( $field, $field_default, '_nonce', 'field/group', $field_parent );

          }
          echo '</div>';

        echo '</div>';

        echo '<div class="CSFTICKET-cloneable-wrapper CSFTICKET-data-wrapper" data-title-number="'. esc_attr( $title_number ) .'" data-unique-id="'. esc_attr( $this->unique ) .'" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $value ) {

            $first_id    = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : '';
            $first_value = ( isset( $value[$first_id] ) ) ? $value[$first_id] : '';
            $first_value = ( is_array( $first_value ) ) ? reset( $first_value ) : $first_value;

            echo '<div class="CSFTICKET-cloneable-item">';

              echo '<div class="CSFTICKET-cloneable-helper">';
              echo '<i class="CSFTICKET-cloneable-sort fas fa-arrows-alt"></i>';
              echo '<i class="CSFTICKET-cloneable-clone far fa-clone"></i>';
              echo '<i class="CSFTICKET-cloneable-remove CSFTICKET-confirm fas fa-times" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'nirweb-support' ) .'"></i>';
              echo '</div>';

              echo '<h4 class="CSFTICKET-cloneable-title">';
              echo '<span class="CSFTICKET-cloneable-text">';
              echo ( $title_number ) ? '<span class="CSFTICKET-cloneable-title-number">'. esc_attr( $num+1 ) .'.</span>' : '';
              echo ( $title_prefix ) ? '<span class="CSFTICKET-cloneable-title-prefix">'. esc_attr( $title_prefix ) .'</span>' : '';
              echo ( $title_auto ) ? '<span class="CSFTICKET-cloneable-value">' . esc_attr( $first_value ) .'</span>' : '';
              echo '</span>';
              echo '</h4>';

              echo '<div class="CSFTICKET-cloneable-content">';

              foreach ( $this->field['fields'] as $field ) {

                $field_parent  = $this->parent .'['. $this->field['id'] .']';
                $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
                $field_value  = ( isset( $field['id'] ) && isset( $value[$field['id']] ) ) ? $value[$field['id']] : '';

                CSFTICKET::field( $field, $field_value, $field_unique, 'field/group', $field_parent );

              }

              echo '</div>';

            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="CSFTICKET-cloneable-alert CSFTICKET-cloneable-max">'. esc_html__( 'You can not add more than', 'nirweb-support' ) .' '. esc_attr( $args['max'] ) .'</div>';
        echo '<div class="CSFTICKET-cloneable-alert CSFTICKET-cloneable-min">'. esc_html__( 'You can not remove less than', 'nirweb-support' ) .' '. esc_attr( $args['min'] ) .'</div>';

        echo '<a href="#" class="button button-primary CSFTICKET-cloneable-add">'. wp_kses_post( $args['button_title'] ) .'</a>';

        echo $this->field_after();

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-accordion' ) ) {
        wp_enqueue_script( 'jquery-ui-accordion' );
      }

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}
