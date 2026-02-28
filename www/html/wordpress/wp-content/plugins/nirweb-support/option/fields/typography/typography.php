<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: typography
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSFTICKET_Field_typography' ) ) {
  class CSFTICKET_Field_typography extends CSFTICKET_Fields {

    public $chosen = false;

    public $value  = array();

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      echo $this->field_before();

      $args                  = wp_parse_args( $this->field, array(
        'font_family'        => true,
        'font_weight'        => true,
        'font_style'         => true,
        'font_size'          => true,
        'line_height'        => true,
        'letter_spacing'     => true,
        'text_align'         => true,
        'text_transform'     => true,
        'color'              => true,
        'chosen'             => true,
        'preview'            => true,
        'subset'             => true,
        'multi_subset'       => false,
        'extra_styles'       => false,
        'backup_font_family' => false,
        'font_variant'       => false,
        'word_spacing'       => false,
        'text_decoration'    => false,
        'custom_style'       => false,
        'exclude'            => '',
        'unit'               => 'px',
        'line_height_unit'   => '',
        'preview_text'       => 'The quick brown fox jumps over the lazy dog',
      ) );

      $default_value         = array(
        'font-family'        => '',
        'font-weight'        => '',
        'font-style'         => '',
        'font-variant'       => '',
        'font-size'          => '',
        'line-height'        => '',
        'letter-spacing'     => '',
        'word-spacing'       => '',
        'text-align'         => '',
        'text-transform'     => '',
        'text-decoration'    => '',
        'backup-font-family' => '',
        'color'              => '',
        'custom-style'       => '',
        'type'               => '',
        'subset'             => '',
        'extra-styles'       => array(),
      );

      $default_value    = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
      $this->value      = wp_parse_args( $this->value, $default_value );
      $this->chosen     = $args['chosen'];
      $chosen_class     = ( $this->chosen ) ? ' CSFTICKET--chosen' : '';
      $line_height_unit = ( ! empty( $args['line_height_unit'] ) ) ? $args['line_height_unit'] : $args['unit'];

      echo '<div class="CSFTICKET--typography'. esc_attr( $chosen_class ) .'" data-unit="'. esc_attr( $args['unit'] ) .'" data-line-height-unit="'. esc_attr( $line_height_unit ) .'" data-exclude="'. esc_attr( $args['exclude'] ) .'">';

        echo '<div class="CSFTICKET--blocks CSFTICKET--blocks-selects">';

          //
          // Font Family
          if ( ! empty( $args['font_family'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Font Family', 'nirweb-support' ) .'</div>';
            echo $this->create_select( array( $this->value['font-family'] => $this->value['font-family'] ), 'font-family', esc_html__( 'Select a font', 'nirweb-support' ) );
            echo '</div>';
          }

          //
          // Backup Font Family
          if ( ! empty( $args['backup_font_family'] ) ) {
            echo '<div class="CSFTICKET--block CSFTICKET--block-backup-font-family hidden">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Backup Font Family', 'nirweb-support' ) .'</div>';
            echo $this->create_select( apply_filters( 'CSFTICKET_field_typography_backup_font_family', array(
              'Arial, Helvetica, sans-serif',
              "'Arial Black', Gadget, sans-serif",
              "'Comic Sans MS', cursive, sans-serif",
              'Impact, Charcoal, sans-serif',
              "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
              'Tahoma, Geneva, sans-serif',
              "'Trebuchet MS', Helvetica, sans-serif'",
              'Verdana, Geneva, sans-serif',
              "'Courier New', Courier, monospace",
              "'Lucida Console', Monaco, monospace",
              'Georgia, serif',
              'Palatino Linotype'
            ) ), 'backup-font-family', esc_html__( 'Default', 'nirweb-support' ) );
            echo '</div>';
          }

          //
          // Font Style and Extra Style Select
          if ( ! empty( $args['font_weight'] ) || ! empty( $args['font_style'] ) ) {

            //
            // Font Style Select
            echo '<div class="CSFTICKET--block CSFTICKET--block-font-style hidden">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Font Style', 'nirweb-support') .'</div>';
            echo '<select class="CSFTICKET--font-style-select" data-placeholder="Default">';
            echo '<option value="">'. ( ! $this->chosen ? esc_html__( 'Default', 'nirweb-support' ) : '' ) .'</option>';
            if ( ! empty( $this->value['font-weight'] ) || ! empty( $this->value['font-style'] ) ) {
              echo '<option value="'. esc_attr( strtolower( $this->value['font-weight'] . $this->value['font-style'] ) ) .'" selected></option>';
            }
            echo '</select>';
            echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[font-weight]' ) ) .'" class="CSFTICKET--font-weight" value="'. esc_attr( $this->value['font-weight'] ) .'" />';
            echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[font-style]' ) ) .'" class="CSFTICKET--font-style" value="'. esc_attr( $this->value['font-style'] ) .'" />';

            //
            // Extra Font Style Select
            if ( ! empty( $args['extra_styles'] ) ) {
              echo '<div class="CSFTICKET--block-extra-styles hidden">';
              echo ( ! $this->chosen ) ? '<div class="CSFTICKET--title">'. esc_html__( 'Load Extra Styles', 'nirweb-support' ) .'</div>' : '';
              $placeholder = ( $this->chosen ) ? esc_html__( 'Load Extra Styles', 'nirweb-support' ) : esc_html__( 'Default', 'nirweb-support' );
              echo $this->create_select( $this->value['extra-styles'], 'extra-styles', $placeholder, true );
              echo '</div>';
            }

            echo '</div>';

          }

          //
          // Subset
          if ( ! empty( $args['subset'] ) ) {
            echo '<div class="CSFTICKET--block CSFTICKET--block-subset hidden">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Subset', 'nirweb-support' ) .'</div>';
            $subset = ( is_array( $this->value['subset'] ) ) ? $this->value['subset'] : array_filter( (array) $this->value['subset'] );
            echo $this->create_select( $subset, 'subset', esc_html__( 'Default', 'nirweb-support' ), $args['multi_subset'] );
            echo '</div>';
          }

          //
          // Text Align
          if ( ! empty( $args['text_align'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Text Align', 'nirweb-support' ) .'</div>';
            echo $this->create_select( array(
              'inherit' => esc_html__( 'Inherit', 'nirweb-support' ),
              'left'    => esc_html__( 'Left', 'nirweb-support' ),
              'center'  => esc_html__( 'Center', 'nirweb-support' ),
              'right'   => esc_html__( 'Right', 'nirweb-support' ),
              'justify' => esc_html__( 'Justify', 'nirweb-support' ),
              'initial' => esc_html__( 'Initial', 'nirweb-support' )
            ), 'text-align', esc_html__( 'Default', 'nirweb-support' ) );
            echo '</div>';
          }

          //
          // Font Variant
          if ( ! empty( $args['font_variant'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Font Variant', 'nirweb-support' ) .'</div>';
            echo $this->create_select( array(
              'normal'         => esc_html__( 'Normal', 'nirweb-support' ),
              'small-caps'     => esc_html__( 'Small Caps', 'nirweb-support' ),
              'all-small-caps' => esc_html__( 'All Small Caps', 'nirweb-support' )
            ), 'font-variant', esc_html__( 'Default', 'nirweb-support' ) );
            echo '</div>';
          }

          //
          // Text Transform
          if ( ! empty( $args['text_transform'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Text Transform', 'nirweb-support' ) .'</div>';
            echo $this->create_select( array(
              'none'       => esc_html__( 'None', 'nirweb-support' ),
              'capitalize' => esc_html__( 'Capitalize', 'nirweb-support' ),
              'uppercase'  => esc_html__( 'Uppercase', 'nirweb-support' ),
              'lowercase'  => esc_html__( 'Lowercase', 'nirweb-support' )
            ), 'text-transform', esc_html__( 'Default', 'nirweb-support' ) );
            echo '</div>';
          }

          //
          // Text Decoration
          if ( ! empty( $args['text_decoration'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Text Decoration', 'nirweb-support' ) .'</div>';
            echo $this->create_select( array(
              'none'               => esc_html__( 'None', 'nirweb-support' ),
              'underline'          => esc_html__( 'Solid', 'nirweb-support' ),
              'underline double'   => esc_html__( 'Double', 'nirweb-support' ),
              'underline dotted'   => esc_html__( 'Dotted', 'nirweb-support' ),
              'underline dashed'   => esc_html__( 'Dashed', 'nirweb-support' ),
              'underline wavy'     => esc_html__( 'Wavy', 'nirweb-support' ),
              'underline overline' => esc_html__( 'Overline', 'nirweb-support' ),
              'line-through'       => esc_html__( 'Line-through', 'nirweb-support' )
            ), 'text-decoration', esc_html__( 'Default', 'nirweb-support' ) );
            echo '</div>';
          }

        echo '</div>';

        echo '<div class="CSFTICKET--blocks CSFTICKET--blocks-inputs">';

          //
          // Font Size
          if ( ! empty( $args['font_size'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Font Size', 'nirweb-support' ) .'</div>';
            echo '<div class="CSFTICKET--input-wrap">';
            echo '<input type="number" name="'. esc_attr( $this->field_name( '[font-size]' ) ) .'" class="CSFTICKET--font-size CSFTICKET--input CSFTICKET-input-number" value="'. esc_attr( $this->value['font-size'] ) .'" />';
            echo '<span class="CSFTICKET--unit">'. esc_attr( $args['unit'] ) .'</span>';
            echo '</div>';
            echo '</div>';
          }

          //
          // Line Height
          if ( ! empty( $args['line_height'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Line Height', 'nirweb-support' ) .'</div>';
            echo '<div class="CSFTICKET--input-wrap">';
            echo '<input type="number" name="'. esc_attr( $this->field_name( '[line-height]' ) ) .'" class="CSFTICKET--line-height CSFTICKET--input CSFTICKET-input-number" value="'. esc_attr( $this->value['line-height'] ) .'" />';
            echo '<span class="CSFTICKET--unit">'. esc_attr( $line_height_unit ) .'</span>';
            echo '</div>';
            echo '</div>';
          }

          //
          // Letter Spacing
          if ( ! empty( $args['letter_spacing'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Letter Spacing', 'nirweb-support' ) .'</div>';
            echo '<div class="CSFTICKET--input-wrap">';
            echo '<input type="number" name="'. esc_attr( $this->field_name( '[letter-spacing]' ) ) .'" class="CSFTICKET--letter-spacing CSFTICKET--input CSFTICKET-input-number" value="'. esc_attr( $this->value['letter-spacing'] ) .'" />';
            echo '<span class="CSFTICKET--unit">'. esc_attr( $args['unit'] ) .'</span>';
            echo '</div>';
            echo '</div>';
          }

          //
          // Word Spacing
          if ( ! empty( $args['word_spacing'] ) ) {
            echo '<div class="CSFTICKET--block">';
            echo '<div class="CSFTICKET--title">'. esc_html__( 'Word Spacing', 'nirweb-support' ) .'</div>';
            echo '<div class="CSFTICKET--input-wrap">';
            echo '<input type="number" name="'. esc_attr( $this->field_name( '[word-spacing]' ) ) .'" class="CSFTICKET--word-spacing CSFTICKET--input CSFTICKET-input-number" value="'. esc_attr( $this->value['word-spacing'] ) .'" />';
            echo '<span class="CSFTICKET--unit">'. esc_attr( $args['unit'] ) .'</span>';
            echo '</div>';
            echo '</div>';
          }

        echo '</div>';

        //
        // Font Color
        if ( ! empty( $args['color'] ) ) {
          $default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="'. esc_attr( $default_value['color'] ) .'"' : '';
          echo '<div class="CSFTICKET--block CSFTICKET--block-font-color">';
          echo '<div class="CSFTICKET--title">'. esc_html__( 'Font Color', 'nirweb-support' ) .'</div>';
          echo '<div class="CSFTICKET-field-color">';
          echo '<input type="text" name="'. esc_attr( $this->field_name( '[color]' ) ) .'" class="CSFTICKET-color CSFTICKET--color" value="'. esc_attr( $this->value['color'] ) .'"'. $default_color_attr .' />';
          echo '</div>';
          echo '</div>';
        }

        //
        // Custom style
        if ( ! empty( $args['custom_style'] ) ) {
          echo '<div class="CSFTICKET--block CSFTICKET--block-custom-style">';
          echo '<div class="CSFTICKET--title">'. esc_html__( 'Custom Style', 'nirweb-support' ) .'</div>';
          echo '<textarea name="'. esc_attr( $this->field_name( '[custom-style]' ) ) .'" class="CSFTICKET--custom-style">'. esc_attr( $this->value['custom-style'] ) .'</textarea>';
          echo '</div>';
        }

        //
        // Preview
        $always_preview = ( $args['preview'] !== 'always' ) ? ' hidden' : '';

        if ( ! empty( $args['preview'] ) ) {
          echo '<div class="CSFTICKET--block CSFTICKET--block-preview'. esc_attr( $always_preview ) .'">';
          echo '<div class="CSFTICKET--toggle fas fa-toggle-off"></div>';
          echo '<div class="CSFTICKET--preview">'. esc_attr( $args['preview_text'] ) .'</div>';
          echo '</div>';
        }

        echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[type]' ) ) .'" class="CSFTICKET--type" value="'. esc_attr( $this->value['type'] ) .'" />';
        echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[unit]' ) ) .'" class="CSFTICKET--unit-save" value="'. esc_attr( $args['unit'] ) .'" />';

      echo '</div>';

      echo $this->field_after();

    }

    public function create_select( $options, $name, $placeholder = '', $is_multiple = false ) {

      $multiple_name = ( $is_multiple ) ? '[]' : '';
      $multiple_attr = ( $is_multiple ) ? ' multiple data-multiple="true"' : '';
      $chosen_rtl    = ( $this->chosen && is_rtl() ) ? ' chosen-rtl' : '';

      $output  = '<select name="'. esc_attr( $this->field_name( '['. $name .']'. $multiple_name ) ) .'" class="CSFTICKET--'. esc_attr( $name ) . esc_attr( $chosen_rtl ) .'" data-placeholder="'. esc_attr( $placeholder ) .'"'. $multiple_attr .'>';
      $output .= ( ! empty( $placeholder ) ) ? '<option value="">'. esc_attr( ( ! $this->chosen ) ? $placeholder : '' ) .'</option>' : '';

      if ( ! empty( $options ) ) {
        foreach ( $options as $option_key => $option_value ) {
          if ( $is_multiple ) {
            $selected = ( in_array( $option_value, $this->value[$name] ) ) ? ' selected' : '';
            $output .= '<option value="'. esc_attr( $option_value ) .'"'. esc_attr( $selected ).'>'. esc_attr( $option_value ) .'</option>';
          } else {
            $option_key = ( is_numeric( $option_key ) ) ? $option_value : $option_key;
            $selected = ( $option_key === $this->value[$name] ) ? ' selected' : '';
            $output .= '<option value="'. esc_attr( $option_key ) .'"'. esc_attr( $selected ).'>'. esc_attr( $option_value ) .'</option>';
          }
        }
      }

      $output .= '</select>';

      return $output;

    }

    public function enqueue() {

      if ( ! wp_script_is( 'CSFTICKET-webfontloader' ) ) {

        CSFTICKET::include_plugin_file( 'fields/typography/google-fonts.php' );

        wp_enqueue_script( 'CSFTICKET-webfontloader', 'https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.min.js', array( 'CSFTICKET' ), '1.6.28', true );

        $webfonts = array();

        $customwebfonts = apply_filters( 'CSFTICKET_field_typography_customwebfonts', array() );

        if ( ! empty( $customwebfonts ) ) {
          $webfonts['custom'] = array(
            'label' => esc_html__( 'Custom Web Fonts', 'nirweb-support' ),
            'fonts' => $customwebfonts
          );
        }

        $webfonts['safe'] = array(
          'label' => esc_html__( 'Safe Web Fonts', 'nirweb-support' ),
          'fonts' => apply_filters( 'CSFTICKET_field_typography_safewebfonts', array(
            'Arial',
            'Arial Black',
            'Helvetica',
            'Times New Roman',
            'Courier New',
            'Tahoma',
            'Verdana',
            'Impact',
            'Trebuchet MS',
            'Comic Sans MS',
            'Lucida Console',
            'Lucida Sans Unicode',
            'Georgia, serif',
            'Palatino Linotype'
          )
        ) );

        $webfonts['google'] = array(
          'label' => esc_html__( 'Google Web Fonts', 'nirweb-support' ),
          'fonts' => apply_filters( 'CSFTICKET_field_typography_googlewebfonts', CSFTICKET_get_google_fonts()
        ) );

        $defaultstyles = apply_filters( 'CSFTICKET_field_typography_defaultstyles', array( 'normal', 'italic', '700', '700italic' ) );

        $googlestyles = apply_filters( 'CSFTICKET_field_typography_googlestyles', array(
          '100'       => 'Thin 100',
          '100italic' => 'Thin 100 Italic',
          '200'       => 'Extra-Light 200',
          '200italic' => 'Extra-Light 200 Italic',
          '300'       => 'Light 300',
          '300italic' => 'Light 300 Italic',
          'normal'    => 'Normal 400',
          'italic'    => 'Normal 400 Italic',
          '500'       => 'Medium 500',
          '500italic' => 'Medium 500 Italic',
          '600'       => 'Semi-Bold 600',
          '600italic' => 'Semi-Bold 600 Italic',
          '700'       => 'Bold 700',
          '700italic' => 'Bold 700 Italic',
          '800'       => 'Extra-Bold 800',
          '800italic' => 'Extra-Bold 800 Italic',
          '900'       => 'Black 900',
          '900italic' => 'Black 900 Italic'
        ) );

        $webfonts = apply_filters( 'CSFTICKET_field_typography_webfonts', $webfonts );

        wp_localize_script( 'CSFTICKET', 'CSFTICKET_typography_json', array(
          'webfonts'      => $webfonts,
          'defaultstyles' => $defaultstyles,
          'googlestyles'  => $googlestyles
        ) );

      }

    }

    public function enqueue_google_fonts() {

      $value     = $this->value;
      $families  = array();
      $is_google = false;

      if ( ! empty( $this->value['type'] ) ) {
        $is_google = ( $this->value['type'] === 'google' ) ? true : false;
      } else {
        CSFTICKET::include_plugin_file( 'fields/typography/google-fonts.php' );
        $is_google = ( array_key_exists( $this->value['font-family'], CSFTICKET_get_google_fonts() ) ) ? true : false;
      }

      if ( $is_google ) {

        // set style
        $font_weight = ( ! empty( $value['font-weight'] ) ) ? $value['font-weight'] : '';
        $font_style  = ( ! empty( $value['font-style'] ) ) ? $value['font-style'] : '';

        if ( $font_weight || $font_style ) {
          $style = $font_weight . $font_style;
          $families['style'][$style] = $style;
        }

        // set extra styles
        if ( ! empty( $value['extra-styles'] ) ) {
          foreach ( $value['extra-styles'] as $extra_style ) {
            $families['style'][$extra_style] = $extra_style;
          }
        }

        // set subsets
        if ( ! empty( $value['subset'] ) ) {
          $value['subset'] = ( is_array( $value['subset'] ) ) ? $value['subset'] : array_filter( (array) $value['subset'] );
          foreach ( $value['subset'] as $subset ) {
            $families['subset'][$subset] = $subset;
          }
        }

        $all_styles  = ( ! empty( $families['style'] ) ) ? ':'. implode( ',', $families['style'] ) : '';
        $all_subsets = ( ! empty( $families['subset'] ) ) ? ':'. implode( ',', $families['subset'] ) : '';

        $families = $this->value['font-family'] . str_replace( array( 'normal', 'italic' ), array( 'n', 'i' ), $all_styles ) . $all_subsets;

        $this->parent->typographies[] = $families;

        return $families;

      }

      return false;

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      $font_family   = ( ! empty( $this->value['font-family'] ) ) ? $this->value['font-family'] : '';
      $backup_family = ( ! empty( $this->value['backup-font-family'] ) ) ? ', '. $this->value['backup-font-family'] : '';

      if ( $font_family ) {
        $output .= 'font-family:"'. $font_family .'"'. $backup_family . $important .';';
      }

      // Common font properties
      $properties = array(
        'color',
        'font-weight',
        'font-style',
        'font-variant',
        'text-align',
        'text-transform',
        'text-decoration',
      );

      foreach ( $properties as $property ) {
        if ( isset( $this->value[$property] ) && $this->value[$property] !== '' ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      $properties = array(
        'font-size',
        'line-height',
        'letter-spacing',
        'word-spacing',
      );

      $unit = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : '';
      $line_height_unit = ( ! empty( $this->value['line_height_unit'] ) ) ? $this->value['line_height_unit'] : $unit;

      foreach ( $properties as $property ) {
        if ( isset( $this->value[$property] ) && $this->value[$property] !== '' ) {
          $unit = ( $property === 'line-height' ) ? $line_height_unit : $unit;
          $output .= $property .':'. $this->value[$property] . $unit . $important .';';
        }
      }

      $custom_style = ( ! empty( $this->value['custom-style'] ) ) ? $this->value['custom-style'] : '';

      if ( $output ) {
        $output = $element .'{'. $output . $custom_style .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
