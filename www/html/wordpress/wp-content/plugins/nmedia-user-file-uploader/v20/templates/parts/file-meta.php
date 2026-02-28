<?php
/**
 * File Meta Fields
 * Showing File details after file selected (not uploaded yet)
 */

if( ! defined('ABSPATH' ) ){ exit; } 
?>

<script type="text/html" id="tmpl-ffmwp-file-meta-text">
    <label>{{data.meta.title}}</label>
    <input type="text" 
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}} ffmwp-meta-text-field" 
           placeholder="{{data.meta.placeholder}}" 
           value="{{data.meta.default_value}}"
           required/>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-date">
    <label>{{data.meta.title}}</label>
    <input type="date"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}} ffmwp-meta-date-field" 
           placeholder="{{data.meta.placeholder}}" 
           value="{{data.meta.default_value}}"
           required/>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-select">
    <select 
            name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]"
            class="ffmwp-meta-select-field"
            required>{{data.meta.title}}
			<# _.forEach( data.meta.options, function ( option ) {
				var selected = '';
				if(option == data.default_value){
					selected = 'selected';
			}#>
				<option {{selected}} value="{{option}}">{{option}}</option>
			<# }) #>
    </select>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-checkbox">
<label for="{{data.meta.data_name}}">{{data.meta.title}}</label>
<# _.forEach( data.meta.options, function ( option ) { #>
    <input type="checkbox"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}][]" 
           class="{{data.meta.class}}"  
           value="{{option}}"
           required/>      
<# }) #>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-radio">
<label for="{{data.meta.data_name}}">{{data.meta.title}}</label>
<# _.forEach( data.meta.options, function ( option ) { #>
    <input type="radio"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}}"  
           value="{{option.default_value}}"
           required/>      
<# }) #>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-number">
    <label>{{data.meta.title}}</label>
    <input type="number"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}} ffmwp-meta-number-field" 
           placeholder="{{data.meta.placeholder}}" 
           value="{{data.meta.default_value}}"
           max="{{data.max_values}}"
           min="{{data.min_values}}"
           required/>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-color">
    <label>{{data.meta.title}}</label>
    <input type="color"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}}" 
           value="{{data.meta.default_value}}"
           required/>    
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-email">
    <label>{{data.meta.title}}</label>
    <input type="email"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}} ffmwp-meta-email-field" 
           placeholder="{{data.meta.placeholder}}" 
           value="{{data.meta.default_value}}"
           required/>
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-url">
    <label>{{data.meta.title}}</label>
    <input type="url"
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}} ffmwp-meta-url-field" 
           placeholder="{{data.meta.placeholder}}" 
           value="{{data.meta.default_value}}"
           required/>    
</script>

<script type="text/html" id="tmpl-ffmwp-file-meta-textarea">
    <label>{{data.meta.title}}</label>
    <textarea
           name="uploaded_files[{{data.file_id}}][file_meta][{{data.meta.data_name}}]" 
           class="{{data.meta.class}} ffmwp-meta-textarea-field" 
           placeholder="{{data.meta.placeholder}}"
           value="{{data.meta.default_value}}"
           required></textarea>
</script>