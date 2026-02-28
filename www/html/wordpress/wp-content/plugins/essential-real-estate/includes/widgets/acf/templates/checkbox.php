<div class="checkbox-wrap" data-require-element="<?php if(isset($require_element)){ echo esc_attr($require_element);} ?>"
     data-require-element-id="<?php if(isset($require_element_id)){ echo esc_attr($require_element_id);} ?>"
     data-require-compare="<?php if(isset($require_compare)){ echo esc_attr($require_compare);} ?>"
     data-require-values="<?php if(isset($require_values)){ echo esc_attr($require_values);} ?>">
    <label
        for="<?php echo esc_attr($field_output_name); ?>"><?php echo esc_html($field_title); ?>:</label>
    <input class="checkbox" type="checkbox" id="<?php echo esc_attr($field_output_id); ?>"
           name="<?php echo esc_attr($field_output_name); ?>"
           value="<?php if(isset($field_value) && $field_value!=''){ echo '1' ;}else{echo '0';}  ?>"
           <?php if(isset($field_value) && $field_value=='1'){ echo 'checked';} ?>
        />
</div>