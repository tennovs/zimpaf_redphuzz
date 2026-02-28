<div class="textarea-wrap element"  data-require-element="<?php if(isset($require_element)){ echo esc_attr($require_element);} ?>"
     data-require-element-id="<?php if(isset($require_element_id)){ echo esc_attr($require_element_id);} ?>"
     data-require-compare="<?php if(isset($require_compare)){ echo esc_attr($require_compare);} ?>"
     data-require-values="<?php if(isset($require_values)){ echo esc_attr($require_values);} ?>"
>
    <label
        for="<?php echo esc_attr($field_name); ?>"> <?php echo esc_html($field_title); ?> :</label>
    <textarea rows="8" class="widefat" data-section-id="<?php echo isset($section_id) ? esc_attr($section_id) : '0' ?>"
              id="<?php echo esc_attr($field_output_id); ?>"
              name="<?php echo esc_attr($field_output_name); ?>"
              type="text"><?php if(isset($field_value)){ echo esc_html($field_value) ;}?></textarea>
</div>
