<?php
class wpdevart_gallery_library {
	
	public static function get_value($key, $default_value=""){
		if (isset($_GET[$key])) {
		  $value = esc_html($_GET[$key]);
		}
		elseif (isset($_POST[$key])) {
		  $value = esc_html($_POST[$key]);
		}
		else {
		  $value = '';
		}
		if (!$value) {
		  $value = $default_value;
		}
		return $value;
	}
	public static function create_table_heading($heading_name,$heading_group){
		?>
			<tr class="<?php echo $heading_group ?> tr_heading"><th colspan="2"><?php echo $heading_name ?></th></tr>
		<?php		
	}
	
	/*###################### Description panel function ##################*/		
	
	public static function generete_standart_description_panel($args){
		?>
			<td class="td_option_description">				
				 <?php if(isset($args['description']) && $args['description'] != "") { ?>
					<span class="wpdevart-info-container">?<span class="wpdevart-info"><?php echo $args['description']; ?></span></span>
				 <?php } ?>
				 <span class="wpdevart-title"><?php echo $args['title']; ?></span>
				  <?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "<span class='pro_feature'>(pro)</span>" : ""; ?>
			</td>
		<?php
	}
	
	/*###################### Simple Input function ##################*/	
	
	public static function simple_input($args){
		?>
		<tr class="<?php echo isset($args['heading_group'])?$args['heading_group']:'' ?> tr_option">
			<?php self::generete_standart_description_panel($args) ?>
			<td class="<?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "wpda_gall_pro_feature" : ""; ?>">
				<input type="<?php echo isset($args['type'])?$args['type']:'text'; ?>" value="<?php echo $args['value'] ?>" name="<?php echo $args['name']; ?>">
				<small><?php echo isset($args['small_text'])?$args['small_text']:''; ?></small>
			</td>
		</tr>
		<?php		
	}
	
	/*###################### Color input function ##################*/		
	
	public static function color_input($args){
		?>
		<tr class="<?php echo isset($args['heading_group'])?$args['heading_group']:'' ?> tr_option">
			<?php self::generete_standart_description_panel($args) ?>
			<td class="wpda_gall_color-picker <?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "wpda_gall_pro_feature" : ""; ?>">
				<input type="text" class="color" value="<?php echo $args['value'] ?>" data-default-color="<?php echo $args["default_value"] ?>" id="<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>">
				<script  type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('#<?php echo $args['name']; ?>').wpColorPicker();
					});
				</script>
			</td>
		</tr>
		<?php		
	}
	
	/*###################### Simple select function ##################*/	
	
	public static function simple_select($args){
		$curent_value=$args['value'];
		?>
		<tr class="<?php echo isset($args['heading_group'])?$args['heading_group']:'' ?> tr_option">
			<?php self::generete_standart_description_panel($args) ?>
			<td  class="<?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "wpda_gall_pro_feature" : ""; ?>">
				<select name="<?php echo $args['name']; ?>">
					<?php foreach($args['values'] as $key => $value){ ?>
						<option value="<?php echo $key ?>" <?php selected($key,$curent_value)  ?>><?php echo $value ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php	
	}
	
	/*###################### Simple check-box function ##################*/			
	
	public static function simple_checkbox($args){
		$curent_value=$args['value'];
		$counter=0;
		?>
		<tr class="<?php echo isset($args['heading_group'])?$args['heading_group']:'' ?> tr_option checkbox_tr">
			<?php self::generete_standart_description_panel($args) ?>
			<td class="td_value <?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "wpda_gall_pro_feature" : ""; ?>">
				<?php foreach($args['values'] as $key => $value){ 
					if($counter%2==0){
						?><div><?php
					}				
				?>
				<span>
					<input <?php if(isset($curent_value[$key])) checked( $key, $curent_value[$key] ); ?> type="checkbox" name="<?php echo $args['name']; ?>[<?php echo "$key" ?>]" id="<?php echo $args['name'].$key; ?>_id" value="<?php echo $key ?>">
					<label for="<?php echo $args['name'].$key; ?>_id"><?php echo $value ?></label>
				</span>
				<?php 
					if(($counter+1)%2==0){
						?></div><?php
					}
					$counter++; 
				}				
				if(($counter)%2!=0){
					?></div><?php
				}
				?>
			</td>
		</tr>
		<?php	
	}
	
	/*###################### Font size select function ##################*/		
	
	public static function simple_select_extend_font_size($args){
		$curent_value=$args['value'];
		?>
		<tr class="<?php echo isset($args['heading_group'])?$args['heading_group']:'' ?> tr_option">
			<?php self::generete_standart_description_panel($args) ?>
			<td class="<?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "wpda_gall_pro_feature" : ""; ?>">
				<select style="font-family: 'Material Icons','FontAwesome',Arial;" name="<?php echo $args['name']; ?>">
					<?php foreach($args['values'] as $key => $value){ ?>
						<option class="<?php echo $value[1] ?>" value="<?php echo $key ?>" <?php selected($key,$curent_value)  ?>><?php echo $value[0] ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php	
	}
	
	/*############  Function for the input range ################*/	
	
	public static function range_input($args){
		$curent_value=$args['value'];
		?>
		<tr class="<?php echo isset($args['heading_group'])?$args['heading_group']:'' ?> tr_option">
			<?php self::generete_standart_description_panel($args) ?>
			<td class="range_option_td <?php echo ((isset($args["pro"]) && $args["pro"] === true)) ? "wpda_gall_pro_feature" : ""; ?>">
				<input oninput="document.getElementById('<?php echo $args['name']; ?>_conect').innerHTML=this.value" type="range" id="<?php echo $args['name']; ?>" name="<?php echo $args['name']; ?>" value="<?php echo $args['value'] ?>" />
                <output id="<?php echo $args['name']; ?>_conect" ><?php echo $args['value'] ?></output>
                <small><?php echo isset($args['small_text'])?$args['small_text']:''; ?></small>
			</td>
		</tr>
		<?php	
	}
	/*Front end function*/
	public static function hex2rgba($color, $opacity = false) {
		$default = 'rgb(0,0,0)';        
		if (empty($color))
			return $default;     
		if ($color[0] == '#')
			$color = substr($color, 1);    
		if (strlen($color) == 6)
			$hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);    
		elseif (strlen($color) == 3)
			$hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);    
		else
			return $default;       
		$rgb = array_map('hexdec', $hex);    
		$opacity = min($opacity, 1);
		$output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';   
		return $output;
	}
}
?>