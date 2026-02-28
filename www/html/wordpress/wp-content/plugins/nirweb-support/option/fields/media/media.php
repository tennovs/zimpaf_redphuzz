<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: media
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_media' ) ) {
  class CSFTICKET_Field_media extends CSFTICKET_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'url'          => true,
        'preview'      => true,
        'library'      => array(),
        'button_title' => esc_html__( 'Upload', 'nirweb-support' ),
        'remove_title' => esc_html__( 'Remove', 'nirweb-support' ),
        'preview_size' => 'thumbnail',
      ) );

      $default_values = array(
        'url'         => '',
        'id'          => '',
        'width'       => '',
        'height'      => '',
        'thumbnail'   => '',
        'alt'         => '',
        'title'       => '',
        'description' => ''
      );

      // fallback
      if ( is_numeric( $this->value ) ) {

        $this->value  = array(
          'id'        => $this->value,
          'url'       => wp_get_attachment_url( $this->value ),
          'thumbnail' => wp_get_attachment_image_src( $this->value, 'thumbnail', true )[0],
        );

      }

      $this->value = wp_parse_args( $this->value, $default_values );

      $library     = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
      $library     = ( ! empty( $library ) ) ? implode(',', $library ) : '';
      $preview_src = ( $args['preview_size'] !== 'thumbnail' ) ? $this->value['url'] : $this->value['thumbnail'];
      $hidden_url  = ( empty( $args['url'] ) ) ? ' hidden' : '';
      $hidden_auto = ( empty( $this->value['url'] ) ) ? ' hidden' : '';
      $placeholder = ( empty( $this->field['placeholder'] ) ) ? ' placeholder="'.  esc_html__( 'No media selected', 'nirweb-support' ) .'"' : '';

      echo $this->field_before();

      if ( ! empty( $args['preview'] ) ) {
        echo '<div class="CSFTICKET--preview'. esc_attr( $hidden_auto ) .'">';
        echo '<div class="CSFTICKET-image-preview"><a href="#" class="CSFTICKET--remove fas fa-times"></a><img src="'. esc_url( $preview_src ) .'" class="CSFTICKET--src" /></div>';
        echo '</div>';
      }

      echo '<div class="CSFTICKET--placeholder">';
      echo '<input type="text" name="'. esc_attr( $this->field_name( '[url]' ) ) .'" value="'. esc_attr( $this->value['url'] ) .'" class="CSFTICKET--url'. esc_attr( $hidden_url ) .'" readonly="readonly"'. $this->field_attributes() . $placeholder .' />';
      echo '<a href="#" class="button button-primary CSFTICKET--button" data-library="'. esc_attr( $library ) .'" data-preview-size="'. esc_attr( $args['preview_size'] ) .'">'. wp_kses_post( $args['button_title'] ) .'</a>';
      echo ( empty( $args['preview'] ) ) ? '<a href="#" class="button button-secondary CSFTICKET-warning-primary CSFTICKET--remove'. esc_attr( $hidden_auto ) .'">'. wp_kses_post( $args['remove_title'] ) .'</a>' : '';
      echo '</div>';

      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[id]' ) ) .'" value="'. esc_attr( $this->value['id'] ) .'" class="CSFTICKET--id"/>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[width]' ) ) .'" value="'. esc_attr( $this->value['width'] ) .'" class="CSFTICKET--width"/>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[height]' ) ) .'" value="'. esc_attr( $this->value['height'] ) .'" class="CSFTICKET--height"/>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[thumbnail]' ) ) .'" value="'. esc_attr( $this->value['thumbnail'] ) .'" class="CSFTICKET--thumbnail"/>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[alt]' ) ) .'" value="'. esc_attr( $this->value['alt'] ) .'" class="CSFTICKET--alt"/>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[title]' ) ) .'" value="'. esc_attr( $this->value['title'] ) .'" class="CSFTICKET--title"/>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[description]' ) ) .'" value="'. esc_attr( $this->value['description'] ) .'" class="CSFTICKET--description"/>';

      echo $this->field_after();

    }

  }
}
