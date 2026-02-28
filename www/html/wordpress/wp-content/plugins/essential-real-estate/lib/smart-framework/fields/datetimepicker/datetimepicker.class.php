<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'GSF_Field_DateTimePicker' ) ) {
	class GSF_Field_DateTimePicker extends GSF_Field {
		public function enqueue() {
			wp_enqueue_script( 'datetimepicker', GSF()->helper()->getAssetUrl( 'fields/datetimepicker/assets/jquery.datetimepicker.full.min.js' ), array(), '1.3.4', true );
			wp_enqueue_style( 'datetimepicker', GSF()->helper()->getAssetUrl( 'fields/datetimepicker/assets/jquery.datetimepicker.min.css' ), array(), '1.3.4' );
			wp_enqueue_script( GSF()->assetsHandle( 'field-datetimepicker'), GSF()->helper()->getAssetUrl( 'fields/datetimepicker/assets/datetimepicker.min.js' ), array(), GSF()->pluginVer(), true );
			wp_localize_script(GSF()->assetsHandle( 'field-datetimepicker'), 'gsf_datetimepicker_variable', array(
				'locale' => get_locale()
			));
		}

		public function renderContent() {
			$field_value = $this->getFieldValue();
			$opt_default = array(
			);
			$option      = isset( $this->_setting['js_options'] ) ? $this->_setting['js_options'] : array();
			$option      = wp_parse_args( $option, $opt_default );
			?>
			<div class="gsf-field-text-inner">
				<input autocomplete="off" type="text" class="gsf-date-time-picker"
				       data-options="<?php echo esc_attr( json_encode( $option ) ) ?>" data-field-control
				       name="<?php $this->theInputName(); ?>" value="<?php echo esc_attr( $field_value ) ?>"/>
			</div>
			<?php
		}
	}
}