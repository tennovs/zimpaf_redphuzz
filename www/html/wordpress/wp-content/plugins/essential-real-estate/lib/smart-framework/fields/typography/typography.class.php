<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Typography')) {
	class GSF_Field_Typography extends GSF_Field
	{
		function enqueue()
		{
			if (isset($this->_setting['color']) && $this->_setting['color']) {
				wp_enqueue_style('wp-color-picker');
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_script('wp-color-picker-alpha');
            }


			wp_enqueue_script(GSF()->assetsHandle('field_typography'), GSF()->helper()->getAssetUrl('fields/typography/assets/typography.min.js'), array(), GSF()->pluginVer(), true);
			wp_enqueue_style(GSF()->assetsHandle('field_typography'), GSF()->helper()->getAssetUrl('fields/typography/assets/typography.min.css'), array(), GSF()->pluginVer());
			wp_localize_script(GSF()->assetsHandle('field_typography'), 'GSF_TYPOGRAPHY_META_DATA', array(
				'activeFonts' => $this->getActiveFonts()
			));
		}

		function renderContent()
		{
			$fonts_active = $this->getActiveFonts();


			$field_value = $this->getFieldValue();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$field_default = $this->getDefault();

			$field_value = wp_parse_args($field_value, $field_default);

			$font_size = $field_value['font_size'];
			$font_size_unit = preg_replace('/[0-9\.]*/', '', $font_size);
			$font_size_value = preg_replace('/em|px|rem/', '', $font_size);

			$current_font = array();
			$list_font_available = array();
			foreach ($fonts_active as $font) {
				if ($font['family'] == $field_value['font_family']) {
					$current_font = $font;
				}

				array_push($list_font_available, $font['family']);
			}
			?>
			<div class="gsf-field-typography-inner gsf-clearfix">
				<div class="gsf-typography-field gsf-typography-family">
					<div class="gsf-typography-label"><?php esc_html_e('Font Family', 'smart-framework'); ?></div>
					<select data-field-control="" placeholder="<?php esc_attr_e('Select Font Family', 'smart-framework'); ?>"
					        class="gsf-typography-font-family"
					        name="<?php $this->theInputName(); ?>[font_family]"
					        data-value="<?php echo esc_attr($field_value['font_family']); ?>">
						<?php if (!in_array($field_value['font_family'], $list_font_available)): ?>
							<option value="<?php echo esc_attr($field_value['font_family']); ?>">
								<?php echo esc_html($field_value['font_family']) ?>
								<?php echo esc_attr__('(Not activated)', 'smart-framework') ?></option>
						<?php endif; ?>
						<?php foreach ($fonts_active as $font): ?>
							<option value="<?php echo esc_attr($font['family']); ?>"
								<?php selected($font['family'], $field_value['font_family']); ?>><?php echo esc_html(isset($font['name']) ? $font['name'] : $font['family']); ?></option>
						<?php endforeach;?>
					</select>
				</div>
				<?php if (isset($this->_setting['font_size']) && $this->_setting['font_size']): ?>
					<div class="gsf-typography-field gsf-typography-size">
						<div class="gsf-typography-label"><?php esc_html_e('Font Size', 'smart-framework'); ?></div>
						<input data-field-control="" type="hidden" class="gsf-typography-size"
						       name="<?php $this->theInputName(); ?>[font_size]"
						       value="<?php echo esc_attr($field_value['font_size']); ?>"/>
						<input type="number" placeholder="<?php esc_attr_e('Font size', 'smart-framework'); ?>"
						       class="gsf-typography-size-value" value="<?php echo esc_attr($font_size_value); ?>" step="0.001"/>
						<select class="gsf-typography-size-unit">
							<option value="px" <?php selected('px', $font_size_unit); ?>>px</option>
							<option value="em" <?php selected('em', $font_size_unit); ?>>em</option>
                            <option value="rem" <?php selected('rem', $font_size_unit); ?>>rem</option>
						</select>
					</div>
				<?php endif;?>
				<?php if (isset($this->_setting['font_variants']) && $this->_setting['font_variants']): ?>
					<div class="gsf-typography-field gsf-typography-weight-style">
						<input data-field-control="" type="hidden" class="gsf-typography-weight"
						       name="<?php $this->theInputName(); ?>[font_weight]"
						       value="<?php echo esc_attr($field_value['font_weight']); ?>"/>
						<input data-field-control="" type="hidden" class="gsf-typography-style"
						       name="<?php $this->theInputName(); ?>[font_style]"
						       value="<?php echo esc_attr($field_value['font_style']); ?>"/>
						<div class="gsf-typography-label"><?php esc_html_e('Font Weight & Style', 'smart-framework'); ?></div>
						<?php $current_variant = $field_value['font_weight'].$field_value['font_style']; ?>
						<select class="gsf-typography-variants">
							<?php if (!isset($current_font['variants']) || !in_array($current_variant, $current_font['variants'])): ?>
								<option value="<?php echo esc_attr($current_variant) ?>">
									<?php echo esc_html($current_variant) ?>
									<?php echo esc_html__('(Missing variant)', 'smart-framework') ?>
								</option>
							<?php endif; ?>
							<?php if (is_array($current_font) && isset($current_font['variants'])): ?>
								<?php foreach ($current_font['variants'] as $variant): ?>
									<option value="<?php echo esc_attr($variant); ?>"
										<?php selected($variant, $current_variant); ?>><?php echo esc_html($variant); ?></option>
								<?php endforeach;?>
							<?php endif; ?>
						</select>
					</div>
				<?php endif;?>
				<?php if (isset($this->_setting['align']) && $this->_setting['align']): ?>
                    <div class="gsf-typography-field">
	                    <?php
	                    $aligns = array(
		                    'inherit' => 'Inherit',
		                    'left'    => 'Left',
		                    'center'  => 'Center',
		                    'right'   => 'Right',
		                    'justify' => 'Justify',
		                    'initial' => 'Initial',
	                    )

	                    ?>
                        <div class="gsf-typography-label"><?php esc_html_e('Align', 'smart-framework'); ?></div>
                        <select data-field-control="" name="<?php $this->theInputName(); ?>[align]">
							<?php foreach ($aligns as $key => $value): ?>
                                <option value="<?php echo esc_attr($key); ?>"
									<?php selected($key, $field_value['align']); ?>><?php echo esc_html($value); ?></option>
							<?php endforeach;?>
                        </select>
                    </div>
				<?php endif;?>


				<?php if (isset($this->_setting['transform']) && $this->_setting['transform']): ?>
                    <div class="gsf-typography-field">
						<?php
						$transforms = array(
							'none'       => 'None',
							'capitalize' => 'Capitalize',
							'lowercase'  => 'Lowercase',
							'uppercase'  => 'Uppercase',
							'initial'    => 'Initial',
							'inherit'    => 'Inherit',
						)

						?>
                        <div class="gsf-typography-label"><?php esc_html_e('Text Transform', 'smart-framework'); ?></div>
                        <select data-field-control="" name="<?php $this->theInputName(); ?>[transform]">
							<?php foreach ($transforms as $key => $value): ?>
                                <option value="<?php echo esc_attr($key); ?>"
									<?php selected($key, $field_value['transform']); ?>><?php echo esc_html($value); ?></option>
							<?php endforeach;?>
                        </select>
                    </div>
				<?php endif;?>

				<?php if (isset($this->_setting['line_height']) && $this->_setting['line_height']): ?>
                    <div class="gsf-typography-field">
                        <div class="gsf-typography-label"><?php esc_html_e('Line Height', 'smart-framework'); ?></div>
                        <input data-field-control="" name="<?php $this->theInputName(); ?>[line_height]" type="number"  step="<?php echo esc_attr(0.01); ?>"
                               class="gsf-typography-line-height-value" value="<?php echo esc_attr($field_value['line_height']); ?>"/>
                    </div>
				<?php endif;?>

				<?php if (isset($this->_setting['letter_spacing']) && $this->_setting['letter_spacing']): ?>
                    <div class="gsf-typography-field">
                        <div class="gsf-typography-label"><?php esc_html_e('Letter Spacing', 'smart-framework'); ?></div>
                        <input data-field-control="" name="<?php $this->theInputName(); ?>[letter_spacing]" type="number"  step="<?php echo esc_attr(0.01); ?>"
                               class="gsf-typography-letter-spacing-value" value="<?php echo esc_attr($field_value['letter_spacing']); ?>"/>
                    </div>
				<?php endif;?>

				<?php if (isset($this->_setting['color']) && $this->_setting['color']): ?>
                    <?php

					$alpha = isset($this->_setting['alpha']) ? $this->_setting['alpha'] : false;
					$validate = array(
						'maxlength' => 7,
						'pattern'   => '^(#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3}))$'
					);
					if ($alpha) {
						$validate = array(
							'maxlength' => 22,
							'pattern'   => '^((#(([a-fA-F0-9]{6})|([a-fA-F0-9]{3})))|(rgba\(\d+,\d+,\d+,\d?(\.\d+)*\)))$'
						);
					}

                    ?>


                    <div class="gsf-typography-field">
                        <div class="gsf-typography-label"><?php esc_html_e('Color', 'smart-framework'); ?></div>

                        <input class="gsf-typography-color"
                                data-field-control=""
		                    <?php echo ($alpha ? 'data-field-no-change="true"' : ''); ?>
                               type="text"
                               maxlength="<?php echo esc_attr($validate['maxlength']); ?>"
                               pattern="<?php echo esc_attr($validate['pattern']); ?>"
		                    <?php echo($alpha ? 'data-alpha="true"' : ''); ?>
                               name="<?php $this->theInputName(); ?>[color]"
                               value="<?php echo esc_attr($field_value['color']); ?>"/>
                    </div>
				<?php endif;?>

			</div>
		<?php
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function getDefault() {
			$fonts_active = $this->getActiveFonts();
			if (count($fonts_active) > 0) {
				$default = array(
					'font_family' => $fonts_active[0]['family'],
					'font_size' => '14',
					'font_weight' => '400',
					'font_style' => '',
                    'align' => '',
                    'transform' => '',
                    'line_height' => '',
                    'letter_spacing' => '',
                    'color' => ''
				);
			}
			else {
				$default = array(
					'font_family' => "Open Sans",
					'font_size' => '14',
					'font_weight' => '400',
					'font_style' => '',
					'align' => '',
					'transform' => '',
                    'line_height' => '',
                    'letter_spacing' => '',
                    'color' => ''
				);
			}
			$field_default = isset($this->_setting['default']) ? $this->_setting['default'] : array();

			return wp_parse_args($field_default, $default);
		}

		private function getActiveFonts() {
			return GSF_Core_Fonts::getInstance()->getActiveFonts();
		}
	}
}