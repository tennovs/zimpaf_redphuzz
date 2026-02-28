<?php
/**
 * Core functions
 */
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Get Option
 */
if (!function_exists('ere_get_option')) {
    function ere_get_option($key, $default = '')
    {
        $option = get_option(ERE_OPTIONS_NAME);
        return (isset($option[$key])) ? $option[$key] : $default;
    }
}
/**
 * Get template part (for templates like the shop-loop).
 *
 * ERE_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
if (!function_exists('ere_get_template_part')) {
    function ere_get_template_part($slug, $name = '')
    {
        $template = '';
        if ($name) {
            $template = locate_template(array("{$slug}-{$name}.php", ERE()->template_path() . "{$slug}-{$name}.php"));
        }

        // Get default slug-name.php
        if (!$template && $name && file_exists(ERE_PLUGIN_DIR . "/public/templates/{$slug}-{$name}.php")) {
            $template = ERE_PLUGIN_DIR . "/public/templates/{$slug}-{$name}.php";
        }

        if (!$template) {
            $template = locate_template(array("{$slug}.php", ERE()->template_path() . "{$slug}.php"));
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters('ere_get_template_part', $template, $slug, $name);

        if ($template) {
            load_template($template, false);
        }
    }
}
/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
if (!function_exists('ere_get_template')) {
    function ere_get_template($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }

        $located = ere_locate_template($template_name, $template_path, $default_path);

        if (!file_exists($located)) {
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '2.1');
            return;
        }

        // Allow 3rd party plugin filter template file from their plugin.
        $located = apply_filters('ere_get_template', $located, $template_name, $args, $template_path, $default_path);

        do_action('ere_before_template_part', $template_name, $template_path, $located, $args);

        include($located);

        do_action('ere_after_template_part', $template_name, $template_path, $located, $args);
    }
}
/**
 * Like ere_get_template, but returns the HTML instead of outputting.
 * @see ere_get_template
 * @since 2.5.0
 * @param string $template_name
 */
if (!function_exists('ere_get_template_html')) {
    function ere_get_template_html($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        ob_start();
        ere_get_template($template_name, $args, $template_path, $default_path);
        return ob_get_clean();
    }
}
/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *        yourtheme        /    $template_path    /    $template_name
 *        yourtheme        /    $template_name
 *        $default_path    /    $template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
if (!function_exists('ere_locate_template')) {
    function ere_locate_template($template_name, $template_path = '', $default_path = '')
    {
        if (!$template_path) {
            $template_path = ERE()->template_path();
        }

        if (!$default_path) {
            $default_path = ERE_PLUGIN_DIR . '/public/templates/';
        }

        // Look within passed path within the theme - this is priority.
        $template = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
                $template_name
            )
        );

        // Get default template/
        if (!$template) {
            $template = $default_path . $template_name;
        }

        // Return what we found.
        return apply_filters('ere_locate_template', $template, $template_name, $template_path);
    }
}
/**
 * Check user as agent
 */
if (!function_exists('ere_is_agent')) {
    function ere_is_agent()
    {
        global $current_user;
        wp_get_current_user();
        $user_id = $current_user->ID;
        $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
        if (!empty($agent_id) && (get_post_type($agent_id) == 'agent') && (get_post_status($agent_id) == 'publish')) {
            return true;
        }
        return false;
    }
}
if (!function_exists('ere_allow_submit')){
    function ere_allow_submit(){
        $enable_submit_property_via_frontend = ere_get_option('enable_submit_property_via_frontend', 1);
        $user_can_submit = ere_get_option('user_can_submit', 1);
        $is_agent = ere_is_agent();

        $allow_submit=true;
        if($enable_submit_property_via_frontend!=1)
        {
            $allow_submit=false;
        }
        else{
            if(!$is_agent && $user_can_submit!=1)
            {
                $allow_submit=false;
            }
        }
        return $allow_submit;
    }
}

/**
 * Get page id
 */
if (!function_exists('ere_get_page_id')) {
    function ere_get_page_id($page)
    {
        $page_id = ere_get_option('ere_' . $page . '_page_id');
        if ($page_id) {
            return absint(function_exists('pll_get_post') ? pll_get_post($page_id) : $page_id);
        } else {
            return 0;
        }
    }
}
/**
 * Get permalink
 */
if (!function_exists('ere_get_permalink')) {
    function ere_get_permalink($page)
    {
        if ($page_id = ere_get_page_id($page)) {
            return get_permalink($page_id);
        } else {
            return false;
        }
    }
}

if (!function_exists('ere_get_page_title')) {
	function ere_get_page_title($page)
	{
		if ($page_id = ere_get_page_id($page)) {
			return get_the_title($page_id);
		} else {
			return '';
		}
	}
}

/**
 * Format money
 */
if (!function_exists('ere_get_format_money')) {
	function ere_get_format_money($money = '', $price_unit='',$decimals = 0,$small_sign=false)
	{

		$money = doubleval($money);
		$currency = ere_get_option('currency_sign', esc_html__('$', 'essential-real-estate'));

		$dec_point = ere_get_price_decimal_separator();
		$thousands_sep = ere_get_option('thousand_separator', ',');
		if ($decimals == 0) {
			$decimals = ere_get_option('number_of_decimals', 0);
		}

		$thousand_text_default=esc_html__('thousand', 'essential-real-estate');
		$thousand_text = ere_get_option('thousand_text', $thousand_text_default);

		$million_text_default=esc_html__('million', 'essential-real-estate');
		$million_text= ere_get_option('million_text', $million_text_default);

		$billion_text_default=esc_html__('billion', 'essential-real-estate');
		$billion_text = ere_get_option('billion_text', $billion_text_default);

		$enable_rtl_mode = ere_get_option('enable_rtl_mode', 0);

		$currency_position = ere_get_option('currency_position', 'before');

		$currency_data = apply_filters('ere_currency_data', array(
			'money' => $money,
			'currency' => $currency,
			'dec_point' => $dec_point,
			'thousands_sep' => $thousands_sep,
			'decimals' => $decimals,
			'thousand_text' => $thousand_text,
			'million_text' => $million_text,
			'billion_text' => $billion_text,
			'enable_rtl_mode' => $enable_rtl_mode,
			'currency_position' => $currency_position
		));
		extract($currency_data);

		$formatted_price=$money;

		if($price_unit=='')
		{
			$formatted_price = number_format($money, $decimals, $dec_point, $thousands_sep);
		}
		else
		{
			$price_unit = intval($price_unit);

			$unit_text='';
			switch ($price_unit) {
				case 1000:
					$unit_text= $thousand_text;
					break;
				case 1000000:
					$unit_text= $million_text;
					break;
				case 1000000000:
					$unit_text= $billion_text;
					break;
			}
			if($unit_text!='')
			{
				$formatted_price = number_format($money, $decimals, $dec_point, $thousands_sep);

				if (is_rtl() || ($enable_rtl_mode==1) || isset($_GET['RTL'])) {
					$formatted_price=' '.$unit_text. ' '. $formatted_price;
				}
				else{
					$formatted_price=$formatted_price.' '.$unit_text .' ';
				}
			}
			else
			{
				$formatted_price = number_format($money, $decimals, $dec_point, $thousands_sep);
			}
		}

		if($small_sign==true)
		{
			$currency='<sup>'.$currency.'</sup>';
		}

		if ($currency_position == 'before') {
			$currency =  $currency . $formatted_price;
		} else {
			$currency =  $formatted_price . $currency;
		}
		return apply_filters('ere_format_money',$currency,$money, $price_unit,$decimals,$small_sign) ;
	}
}
/**
 * Get format money search field
 */
if (!function_exists('ere_get_format_money_search_field')) {
    function ere_get_format_money_search_field($money)
    {
        $enable_price_number_short_scale= ere_get_option('enable_price_number_short_scale', 0);
        if($enable_price_number_short_scale==0)
        {
            return ere_get_format_money($money);
        }
        else
        {
            $money = doubleval($money);
            if ($money) {
                $billion=$money/1000000000;
                $million=$money/1000000;
                $thousand=$money/1000;
                $formatted_price=$money;
                $unit_text='';
                if($billion>=1)
                {
                    $unit_text= esc_html__('billion', 'essential-real-estate');
                    $formatted_price=$billion;
                }
                elseif($million>=1)
                {
                    $unit_text= esc_html__('million', 'essential-real-estate');
                    $formatted_price=$million;
                }
                elseif($thousand>=1)
                {
                    $unit_text= esc_html__('thousand', 'essential-real-estate');
                    $formatted_price=$thousand;
                }
                $formatted_price=$formatted_price.' '.$unit_text .' ';
                $enable_rtl_mode = ere_get_option('enable_rtl_mode', 0);
                if (is_rtl() || ($enable_rtl_mode==1) || isset($_GET['RTL'])) {
                    $formatted_price=' '.$unit_text. ' '. $formatted_price;
                }
                $currency = ere_get_option('currency_sign', esc_html__('$', 'essential-real-estate'));
                $currency_position = ere_get_option('currency_position', 'before');
                if ($currency_position == 'before') {
                    return $currency . $formatted_price;
                } else {
                    return $formatted_price . $currency;
                }

            } else {
                $currency = 0;
            }
            return $currency;
        }
    }
}
/**
 * Get format number
 */
function ere_get_format_number($number,$decimals = 0)
{
	if ($number === '') {
		return 0;
	}

	$number_floor = floor($number);

	$dec_point = ere_get_price_decimal_separator();
	$thousands_sep = ere_get_option('thousand_separator', ',');
	$number_floor =  number_format($number_floor, $decimals, $dec_point, $thousands_sep);


	$number_decimal = $number . '';
	$number_decimal_index = strpos($number_decimal, $dec_point);

	if ($number_decimal_index !== false) {
		$number_decimal = substr($number_decimal, $number_decimal_index + 1);
		if ($number_decimal !== '') {
			for ($i = strlen($number_decimal) - 1; $i >= 0; $i--) {
				if ($number_decimal[$i] !== '0') {
					break;
				}
			}
			$number_decimal = substr($number_decimal, 0, $i+1);
		}
	}
	else {
		$number_decimal = '';
	}

	return $number_decimal === '' ? $number_floor : $number_floor . $dec_point . $number_decimal;
}
/**
 * Image resize by url
 */
if (!function_exists('ere_image_resize_url')) {
    function ere_image_resize_url($url, $width = NULL, $height = NULL, $crop = true, $retina = false)
    {

        global $wpdb;

        if (empty($url))
            return new WP_Error('no_image_url', esc_html__('No image URL has been entered.', 'essential-real-estate'), $url);

        if (class_exists('Jetpack') && method_exists('Jetpack', 'get_active_modules') && in_array('photon', Jetpack::get_active_modules())) {
            $args_crop = array(
                'resize' => $width . ',' . $height,
                'crop' => '0,0,' . $width . 'px,' . $height . 'px'
            );
            $url = jetpack_photon_url($url, $args_crop);
        }

        // Get default size from database
        $width = ($width) ? $width : get_option('thumbnail_size_w');
        $height = ($height) ? $height : get_option('thumbnail_size_h');

        // Allow for different retina sizes
        $retina = $retina ? ($retina === true ? 2 : $retina) : 1;

        // Get the image file path
        $file_path = parse_url($url);
        $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
        $wp_upload_folder = wp_upload_dir();
        $wp_upload_folder = $wp_upload_folder['basedir'];
        $file_path = explode('/uploads/', $file_path);
        if (is_array($file_path)) {
            if (count($file_path) > 1) {
                $file_path = $wp_upload_folder . '/' . $file_path[1];
            } elseif (count($file_path) > 0) {
                $file_path = $wp_upload_folder . '/' . $file_path[0];
            } else {
                $file_path = '';
            }
        }

        // Check for Multisite
        if (is_multisite()) {
            global $blog_id;
            $blog_details = get_blog_details($blog_id);
            $file_path = str_replace($blog_details->path . 'files/', '/wp-content/blogs.dir/' . $blog_id . '/files/', $file_path);
        }

        // Destination width and height variables
        $dest_width = $width * $retina;
        $dest_height = $height * $retina;

        // File name suffix (appended to original file name)
        $suffix = "{$dest_width}x{$dest_height}";

        // Some additional info about the image
        $info = pathinfo($file_path);
        $dir = $info['dirname'];
        $ext = $info['extension'];
        $name = wp_basename($file_path, ".$ext");

        if ('bmp' == $ext) {
            return new WP_Error('bmp_mime_type', esc_html__('Image is BMP. Please use either JPG or PNG.', 'essential-real-estate'), $url);
        }

        // Suffix applied to filename
        $suffix = "{$dest_width}x{$dest_height}";

        // Get the destination file name
        $dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";

        if (!file_exists($dest_file_name)) {

            /*
             *  Bail if this image isn't in the Media Library.
             *  We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
             */
            $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE guid='%s'", $url);
            $get_attachment = $wpdb->get_results($query);
            if (!$get_attachment)
                return array('url' => $url, 'width' => $width, 'height' => $height);

            // Load Wordpress Image Editor
            $editor = wp_get_image_editor($file_path);
            if (is_wp_error($editor))
                return array('url' => $url, 'width' => $width, 'height' => $height);

            // Get the original image size
            $size = $editor->get_size();
            $orig_width = $size['width'];
            $orig_height = $size['height'];

            $src_x = $src_y = 0;
            $src_w = $orig_width;
            $src_h = $orig_height;

            if ($crop) {

                $cmp_x = $orig_width / $dest_width;
                $cmp_y = $orig_height / $dest_height;

                // Calculate x or y coordinate, and width or height of source
                if ($cmp_x > $cmp_y) {
                    $src_w = round($orig_width / $cmp_x * $cmp_y);
                    $src_x = round(($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);
                } else if ($cmp_y > $cmp_x) {
                    $src_h = round($orig_height / $cmp_y * $cmp_x);
                    $src_y = round(($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);
                }

            }

            // Time to crop the image!
            $editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);

            // Now let's save the image
            $saved = $editor->save($dest_file_name);

            if (is_a($saved, 'WP_Error')) {
	            $image_array = array(
		            'url' => str_replace(wp_basename($url), wp_basename($dest_file_name), $url),
		            'width' => $dest_width,
		            'height' => $dest_height,
		            'type' => $ext
	            );
            } else {
	            // Get resized image information
	            $resized_url = str_replace(wp_basename($url), wp_basename($saved['path']), $url);
	            $resized_width = $saved['width'];
	            $resized_height = $saved['height'];
	            $resized_type = $saved['mime-type'];

	            // Add the resized dimensions to original image metadata (so we can delete our resized images when the original image is delete from the Media Library)
	            $metadata = wp_get_attachment_metadata($get_attachment[0]->ID);
	            if (isset($metadata['image_meta'])) {
		            $metadata['image_meta']['resized_images'][] = $resized_width . 'x' . $resized_height;
		            wp_update_attachment_metadata($get_attachment[0]->ID, $metadata);
	            }

	            // Create the image array
	            $image_array = array(
		            'url' => $resized_url,
		            'width' => $resized_width,
		            'height' => $resized_height,
		            'type' => $resized_type
	            );
            }

        } else {
            $image_array = array(
                'url' => str_replace(wp_basename($url), wp_basename($dest_file_name), $url),
                'width' => $dest_width,
                'height' => $dest_height,
                'type' => $ext
            );
        }

        // Return image array
        return $image_array;

    }
}
/**
 * Image resize by id
 */
if (!function_exists('ere_image_resize_id')) {
    function ere_image_resize_id($images_id, $width = NULL, $height = NULL, $crop = true, $retina = false)
    {
        $output = '';
        $image_src = wp_get_attachment_image_src($images_id, 'full');
        if (is_array($image_src)) {
            $resize = ere_image_resize_url($image_src[0], $width, $height, $crop, $retina);
            if ($resize != null && is_array($resize)) {
                $output = $resize['url'];
            }
        }
        return $output;
    }
}
add_action( 'delete_attachment', 'ere_delete_resized_images' );
/**
 * Delete resized images
 */
if (!function_exists('ere_delete_resized_images')) {
    function ere_delete_resized_images($post_id)
    {
        // Get attachment image metadata
        $metadata = wp_get_attachment_metadata($post_id);
        if (!$metadata)
            return;

        // Do some bailing if we cannot continue
        if (!isset($metadata['file']) || !isset($metadata['image_meta']['resized_images']))
            return;
        $pathinfo = pathinfo($metadata['file']);
        $resized_images = $metadata['image_meta']['resized_images'];

        // Get Wordpress uploads directory (and bail if it doesn't exist)
        $wp_upload_dir = wp_upload_dir();
        $upload_dir = $wp_upload_dir['basedir'];
        if (!is_dir($upload_dir))
            return;

        // Delete the resized images
        foreach ($resized_images as $dims) {

            // Get the resized images filename
            $file = $upload_dir . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $dims . '.' . $pathinfo['extension'];

            // Delete the resized image
            @unlink($file);
        }
    }
}
/**
 * Get image resize
 */
if (!function_exists('ere_image_resize ')) {
    function ere_image_resize($attach_id = null, $img_url = null, $width = '', $height = '', $crop = false)
    {
        // this is an attachment, so we have the ID
        $image_src = array();
        if ($attach_id) {
            $image_src = wp_get_attachment_image_src($attach_id, 'full');
            $actual_file_path = get_attached_file($attach_id);
            // this is not an attachment, let's use the image url
        } elseif ($img_url) {
            $file_path = parse_url($img_url);
            $actual_file_path = rtrim(ABSPATH, '/') . $file_path['path'];
            $orig_size = getimagesize($actual_file_path);
            $image_src[0] = $img_url;
            $image_src[1] = $orig_size[0];
            $image_src[2] = $orig_size[1];
        }
        if (!empty($actual_file_path)) {
            $file_info = pathinfo($actual_file_path);
            $extension = '.' . $file_info['extension'];

            // the image path without the extension
            $no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];

            $cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;

            // checking if the file size is larger than the target size
            // if it is smaller or the same size, stop right here and return
            if ($image_src[1] > $width || $image_src[2] > $height) {

                // the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
                if (file_exists($cropped_img_path)) {
                    $cropped_img_url = str_replace(basename($image_src[0]), basename($cropped_img_path), $image_src[0]);
                    $vt_image = array(
                        'url' => $cropped_img_url,
                        'width' => $width,
                        'height' => $height,
                    );

                    return $vt_image;
                }

                if (false == $crop) {
                    // calculate the size proportionaly
                    $proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
                    $resized_img_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;

                    // checking if the file already exists
                    if (file_exists($resized_img_path)) {
                        $resized_img_url = str_replace(basename($image_src[0]), basename($resized_img_path), $image_src[0]);

                        $vt_image = array(
                            'url' => $resized_img_url,
                            'width' => $proportional_size[0],
                            'height' => $proportional_size[1],
                        );

                        return $vt_image;
                    }
                }

                // no cache files - let's finally resize it
                $img_editor = wp_get_image_editor($actual_file_path);

                if (is_wp_error($img_editor) || is_wp_error($img_editor->resize($width, $height, $crop))) {
                    return array(
                        'url' => '',
                        'width' => '',
                        'height' => '',
                    );
                }

                $new_img_path = $img_editor->generate_filename();

                if (is_wp_error($img_editor->save($new_img_path))) {
                    return array(
                        'url' => '',
                        'width' => '',
                        'height' => '',
                    );
                }
                if (!is_string($new_img_path)) {
                    return array(
                        'url' => '',
                        'width' => '',
                        'height' => '',
                    );
                }

                $new_img_size = getimagesize($new_img_path);
                $new_img = str_replace(basename($image_src[0]), basename($new_img_path), $image_src[0]);

                // resized output
                $vt_image = array(
                    'url' => $new_img,
                    'width' => $new_img_size[0],
                    'height' => $new_img_size[1],
                );

                return $vt_image;
            }

            // default output - without resizing
            $vt_image = array(
                'url' => $image_src[0],
                'width' => $image_src[1],
                'height' => $image_src[2],
            );

            return $vt_image;
        }

        return false;
    }
}
/**
 * ere_admin_taxonomy_terms
 */
if (!function_exists('ere_admin_taxonomy_terms')) {
    function ere_admin_taxonomy_terms($post_id, $taxonomy, $post_type)
    {

        $terms = get_the_terms($post_id, $taxonomy);

        if (!empty ($terms)) {
            $results = array();
            foreach ($terms as $term) {
                $results[] = sprintf('<a href="%s">%s</a>',
                    esc_url(add_query_arg(array('post_type' => $post_type, $taxonomy => $term->slug), 'edit.php')),
                    esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'display'))
                );
            }
            return join(', ', $results);
        }

        return false;
    }
}
/**
 * Send email
 */
if (!function_exists('ere_send_email')) {
    function ere_send_email($email, $email_type, $args = array())
    {
        global $ere_background_emailer;
        $args['user_lang'] = apply_filters( 'wpml_current_language', NULL );
        $ere_background_emailer->push_to_queue( array( 'email' => $email, 'email_type' =>$email_type, 'args'=>$args) );
    }
}
/**
 * Required field
 */
if (!function_exists('ere_required_field')) {
    function ere_required_field($field)
    {
        $required_fields = ere_get_option('required_fields', array('property_title', 'property_type', 'property_price', 'property_map_address'));
        if (is_array($required_fields) && in_array($field, $required_fields)) {
            return '*';
        }
        return '';
    }
}
/**
 * Required field
 */
if (!function_exists('ere_enable_captcha')) {
    function ere_enable_captcha($form_submit)
    {
        $enable_captcha = ere_get_option('enable_captcha', array());
        if (is_array($enable_captcha) && in_array($form_submit, $enable_captcha)) {
            return true;
        }
        return false;
    }
}
/**
 * get_taxonomy_target_by_id
 */
if (!function_exists('ere_get_taxonomy_target_by_id')) {
    function ere_get_taxonomy_target_by_id( $taxonomy_terms, $target_term_id, $prefix = "")
    {
        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
                if ((is_array($target_term_id) && in_array($term->term_id,$target_term_id)) || ($target_term_id == $term->term_id)) {
                    echo '<option value="' . $term->term_id . '" selected>' . $prefix . $term->name . '</option>';
                } else {
                    echo '<option value="' . $term->term_id . '">' . $prefix . $term->name . '</option>';
                }
            }
        }
    }
}
/**
 * get_taxonomy_target_by_name
 */
if (!function_exists('ere_get_taxonomy_target_by_name')) {
    function ere_get_taxonomy_target_by_name($taxonomy_terms, $target_term_name, $prefix = "")
    {
        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
                if ((is_array($target_term_name) && in_array($term->name,$target_term_name)) || ($target_term_name == $term->name)) {
                    echo '<option value="' . $term->slug . '" selected>' . $prefix . $term->name . '</option>';
                } else {
                    echo '<option value="' . $term->slug . '">' . $prefix . $term->name . '</option>';
                }
            }
        }
    }
}
/**
 * get_taxonomy_by_post_id
 */
if (!function_exists('ere_get_taxonomy_by_post_id')) {
    function ere_get_taxonomy_by_post_id($post_id, $taxonomy_name, $is_target_by_name = false, $show_default_none = true, $multiple = false,$parent = 0, $prefix = '', $target = null)
    {
        $taxonomy_terms = get_categories(
            array(
                'taxonomy'=>$taxonomy_name,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => $parent
            )
        );
        $target_by_name = $target !== null ? $target : ($multiple ? array() : '');
        $target_by_id = $target != null ? $target : ($multiple ? array() : 0);
	    $tax_terms = $target != null ? '' : get_the_terms($post_id, $taxonomy_name);

        if ($is_target_by_name) {
        	if (!empty($tax_terms)) {
                foreach ($tax_terms as $tax_term) {
                	if ($multiple) {
		                $target_by_name[] = $tax_term->name;
	                } else {
		                $target_by_name = $tax_term->name;
		                break;
	                }

                }
            }
            if($show_default_none && $parent === 0) {
                if (empty($target_by_name)) {
                    echo '<option value="" selected>' . esc_html__('None', 'essential-real-estate') . '</option>';
                } else {
                    echo '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
                }
            }

            //ere_get_taxonomy_target_by_name($taxonomy_terms, $target_by_name, $prefix . '&#8212;');

	        if (!empty($taxonomy_terms)) {
		        foreach ($taxonomy_terms as $term) {
			        if (empty($term) || (!isset($term->parent))) {
				        continue;
			        }
			        if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
				        continue;
			        }

			        if ((is_array($target_by_name) && in_array($term->name,$target_by_name)) || ($target_by_name == $term->name)) {
				        echo '<option value="' . $term->slug . '" selected>' . $prefix . $term->name . '</option>';
			        } else {
				        echo '<option value="' . $term->slug . '">' . $prefix . $term->name . '</option>';
			        }

			        ere_get_taxonomy_by_post_id($post_id,$taxonomy_name,$is_target_by_name,$show_default_none,$multiple,$term->term_id, $prefix . '&#8212;',$target_by_name);
		        }
	        }

        } else {
            if (!empty($tax_terms)) {
                foreach ($tax_terms as $tax_term) {
                	if ($multiple) {
		                $target_by_id[] = $tax_term->term_id;
	                } else {
		                $target_by_id = $tax_term->term_id;
		                break;
	                }

                }
            }
            if($show_default_none && $parent === 0)
            {
                if (empty($target_by_id)) {
                    echo '<option value="-1" selected>' . esc_html__('None', 'essential-real-estate') . '</option>';
                } else {
                    echo '<option value="-1">' . esc_html__('None', 'essential-real-estate') . '</option>';
                }
            }
            //ere_get_taxonomy_target_by_id( $taxonomy_terms, $target_by_id);
	        if (!empty($taxonomy_terms)) {
		        foreach ($taxonomy_terms as $term) {
			        if (empty($term) || (!isset($term->parent))) {
				        continue;
			        }
			        if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
				        continue;
			        }

			        if ((is_array($target_by_id) && in_array($term->term_id,$target_by_id)) || ($target_by_id == $term->term_id)) {
				        echo '<option value="' . $term->term_id . '" selected>' . $prefix . $term->name . '</option>';
			        } else {
				        echo '<option value="' . $term->term_id . '">' . $prefix . $term->name . '</option>';
			        }

			        ere_get_taxonomy_by_post_id($post_id,$taxonomy_name,$is_target_by_name,$show_default_none,$multiple,$term->term_id, $prefix . '&#8212;',$target_by_id);
		        }
	        }
        }
    }
}
if (!function_exists('ere_get_taxonomy_slug_by_post_id')) {
    function ere_get_taxonomy_slug_by_post_id($post_id, $taxonomy_name)
    {
        $tax_terms = get_the_terms($post_id, $taxonomy_name);
        if (!empty($tax_terms)) {
            foreach ($tax_terms as $tax_term) {
                return $tax_term->slug;
            }
        }
        return null;
    }
}
/**
 * get_taxonomy
 */
if (!function_exists('ere_get_taxonomy')) {
    function ere_get_taxonomy($taxonomy_name, $value_as_slug = false, $show_default_none = true,$parent = 0, $prefix='')
    {
        $taxonomy_terms = get_categories(
            array(
                'taxonomy'=>$taxonomy_name,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => $parent
            )
        );
        if ($show_default_none && $parent === 0) {
            echo '<option value="" selected>' . esc_html__('None', 'essential-real-estate') . '</option>';
        }
        if (!empty($taxonomy_terms)) {
	        foreach ($taxonomy_terms as $term) {
		        if (empty($term) || (!isset($term->parent))) {
			        continue;
		        }
		        if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
			        continue;
		        }

		        if ($value_as_slug) {
			        echo '<option value="' . $term->slug . '">' .$prefix . $term->name . '</option>';
		        } else {
			        echo '<option value="' . $term->term_id . '">' . $prefix . $term->name . '</option>';
		        }

		        ere_get_taxonomy($taxonomy_name,$value_as_slug,$show_default_none, $term->term_id,$prefix . '&#8212;');


	        }
        }
    }
}
/**
 * get_taxonomy_name_by_post_id
 */
if (!function_exists('ere_get_taxonomy_name_by_post_id')) {
    function ere_get_taxonomy_name_by_post_id($post_id, $taxonomy_name)
    {
        $tax_terms = get_the_terms($post_id, $taxonomy_name);
        $tax_name = '';
        if (!empty($tax_terms)) {
            foreach ($tax_terms as $tax_term) {
                if (is_object($tax_term)) {
                    $tax_name = $tax_term->name;
                }
                break;
            }
        }
        return $tax_name;
    }
}
/**
 * ere_get_taxonomy_slug
 */
if (!function_exists('ere_get_taxonomy_slug')) {
    function ere_get_taxonomy_slug($taxonomy_name, $target_term_slug='',$parent = 0, $prefix='')
    {
        $taxonomy_terms = get_categories(
            array(
                'taxonomy'=>$taxonomy_name,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => $parent
            )
        );

        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
	            if (empty($term) || (!isset($term->parent))) {
		            continue;
	            }
	            if (((int)$term->parent !== (int)$parent) || ($parent === null) || ($term->parent === null)) {
		            continue;
	            }

                if ($target_term_slug == $term->slug) {
                    echo '<option value="' . $term->slug . '" selected>' . $prefix . $term->name . '</option>';
                } else {
                    echo '<option value="' . $term->slug . '">' . $prefix . $term->name . '</option>';
                }

	            ere_get_taxonomy_slug($taxonomy_name,$target_term_slug, $term->term_id,$prefix . '&#8212;');

            }
        }
    }
}
/**
 * ere_get_property_status_search
 */
if (!function_exists('ere_get_property_status_search')) {
    function ere_get_property_status_search()
    {
        $property_status = get_categories(array(
            'taxonomy' => 'property-status',
            'hide_empty' => false,
            'meta_key'=>'property_status_order_number',
            'orderby'=>'meta_value_num',
            'order' => 'ASC'
        ));
        if(count($property_status)==0)
        {
            $property_status = get_categories(array(
                'taxonomy' => 'property-status',
                'hide_empty' =>false,
                'orderby' => 'name',
                'order' => 'ASC',
                'parent' => 0
            ));
        }
        return $property_status;
    }
}
/**
 * ere_get_property_status_default_value
 */
if (!function_exists('ere_get_property_status_default_value')) {
    function ere_get_property_status_default_value()
    {
        $property_status = ere_get_property_status_search();
        $property_status_arr = array();
        if ($property_status) {
            foreach ($property_status as $property_stt) {
                $property_status_arr[] = $property_stt->slug;
            }
        }
        $status_default='';
        if(is_array($property_status_arr) && count($property_status_arr)>0)
        {
            $status_default=$property_status_arr[0];
        }
        return $status_default;
    }
}
/**
 * ere_get_property_status_search_slug
 */
if (!function_exists('ere_get_property_status_search_slug')) {
    function ere_get_property_status_search_slug($target_term_slug='',$prefix='')
    {
        $property_status = ere_get_property_status_search();
        if (!empty($property_status)) {
            foreach ($property_status as $term) {
                if ($target_term_slug == $term->slug) {
                    echo '<option value="' . $term->slug . '" selected>' . $prefix . $term->name . '</option>';
                } else {
                    echo '<option value="' . $term->slug . '">' . $prefix . $term->name . '</option>';
                }
            }
        }
    }
}
/**
 * Get countries
 */
if (!function_exists('ere_get_countries')) {
    function ere_get_countries()
    {
        $countries = array(
            'AF' => esc_html__('Afghanistan', 'essential-real-estate'),
            'AX' => esc_html__('Aland Islands', 'essential-real-estate'),
            'AL' => esc_html__('Albania', 'essential-real-estate'),
            'DZ' => esc_html__('Algeria', 'essential-real-estate'),
            'AS' => esc_html__('American Samoa', 'essential-real-estate'),
            'AD' => esc_html__('Andorra', 'essential-real-estate'),
            'AO' => esc_html__('Angola', 'essential-real-estate'),
            'AI' => esc_html__('Anguilla', 'essential-real-estate'),
            'AQ' => esc_html__('Antarctica', 'essential-real-estate'),
            'AG' => esc_html__('Antigua and Barbuda', 'essential-real-estate'),
            'AR' => esc_html__('Argentina', 'essential-real-estate'),
            'AM' => esc_html__('Armenia', 'essential-real-estate'),
            'AW' => esc_html__('Aruba', 'essential-real-estate'),
            'AU' => esc_html__('Australia', 'essential-real-estate'),
            'AT' => esc_html__('Austria', 'essential-real-estate'),
            'AZ' => esc_html__('Azerbaijan', 'essential-real-estate'),
            'BS' => esc_html__('Bahamas the', 'essential-real-estate'),
            'BH' => esc_html__('Bahrain', 'essential-real-estate'),
            'BD' => esc_html__('Bangladesh', 'essential-real-estate'),
            'BB' => esc_html__('Barbados', 'essential-real-estate'),
            'BY' => esc_html__('Belarus', 'essential-real-estate'),
            'BE' => esc_html__('Belgium', 'essential-real-estate'),
            'BZ' => esc_html__('Belize', 'essential-real-estate'),
            'BJ' => esc_html__('Benin', 'essential-real-estate'),
            'BM' => esc_html__('Bermuda', 'essential-real-estate'),
            'BT' => esc_html__('Bhutan', 'essential-real-estate'),
            'BO' => esc_html__('Bolivia', 'essential-real-estate'),
            'BA' => esc_html__('Bosnia and Herzegovina', 'essential-real-estate'),
            'BW' => esc_html__('Botswana', 'essential-real-estate'),
            'BV' => esc_html__('Bouvet Island (Bouvetoya)', 'essential-real-estate'),
            'BR' => esc_html__('Brazil', 'essential-real-estate'),
            'IO' => esc_html__('British Indian Ocean Territory (Chagos Archipelago)', 'essential-real-estate'),
            'VG' => esc_html__('British Virgin Islands', 'essential-real-estate'),
            'BN' => esc_html__('Brunei Darussalam', 'essential-real-estate'),
            'BG' => esc_html__('Bulgaria', 'essential-real-estate'),
            'BF' => esc_html__('Burkina Faso', 'essential-real-estate'),
            'BI' => esc_html__('Burundi', 'essential-real-estate'),
            'KH' => esc_html__('Cambodia', 'essential-real-estate'),
            'CM' => esc_html__('Cameroon', 'essential-real-estate'),
            'CA' => esc_html__('Canada', 'essential-real-estate'),
            'CV' => esc_html__('Cape Verde', 'essential-real-estate'),
            'KY' => esc_html__('Cayman Islands', 'essential-real-estate'),
            'CF' => esc_html__('Central African Republic', 'essential-real-estate'),
            'TD' => esc_html__('Chad', 'essential-real-estate'),
            'CL' => esc_html__('Chile', 'essential-real-estate'),
            'CN' => esc_html__('China', 'essential-real-estate'),
            'CX' => esc_html__('Christmas Island', 'essential-real-estate'),
            'CC' => esc_html__('Cocos (Keeling) Islands', 'essential-real-estate'),
            'CO' => esc_html__('Colombia', 'essential-real-estate'),
            'KM' => esc_html__('Comoros the', 'essential-real-estate'),
            'CD' => esc_html__('Congo', 'essential-real-estate'),
            'CG' => esc_html__('Congo the', 'essential-real-estate'),
            'CK' => esc_html__('Cook Islands', 'essential-real-estate'),
            'CR' => esc_html__('Costa Rica', 'essential-real-estate'),
            'CI' => esc_html__("Cote d'Ivoire", 'essential-real-estate'),
            'HR' => esc_html__('Croatia', 'essential-real-estate'),
            'CU' => esc_html__('Cuba', 'essential-real-estate'),
            'CY' => esc_html__('Cyprus', 'essential-real-estate'),
            'CZ' => esc_html__('Czech Republic', 'essential-real-estate'),
            'DK' => esc_html__('Denmark', 'essential-real-estate'),
            'DJ' => esc_html__('Djibouti', 'essential-real-estate'),
            'DM' => esc_html__('Dominica', 'essential-real-estate'),
            'DO' => esc_html__('Dominican Republic', 'essential-real-estate'),
            'EC' => esc_html__('Ecuador', 'essential-real-estate'),
            'EG' => esc_html__('Egypt', 'essential-real-estate'),
            'SV' => esc_html__('El Salvador', 'essential-real-estate'),
            'GQ' => esc_html__('Equatorial Guinea', 'essential-real-estate'),
            'ER' => esc_html__('Eritrea', 'essential-real-estate'),
            'EE' => esc_html__('Estonia', 'essential-real-estate'),
            'ET' => esc_html__('Ethiopia', 'essential-real-estate'),
            'FO' => esc_html__('Faroe Islands', 'essential-real-estate'),
            'FK' => esc_html__('Falkland Islands (Malvinas)', 'essential-real-estate'),
            'FJ' => esc_html__('Fiji the Fiji Islands', 'essential-real-estate'),
            'FI' => esc_html__('Finland', 'essential-real-estate'),
            'FR' => esc_html__('France', 'essential-real-estate'),
            'GF' => esc_html__('French Guiana', 'essential-real-estate'),
            'PF' => esc_html__('French Polynesia', 'essential-real-estate'),
            'TF' => esc_html__('French Southern Territories', 'essential-real-estate'),
            'GA' => esc_html__('Gabon', 'essential-real-estate'),
            'GM' => esc_html__('Gambia the', 'essential-real-estate'),
            'GE' => esc_html__('Georgia', 'essential-real-estate'),
            'DE' => esc_html__('Germany', 'essential-real-estate'),
            'GH' => esc_html__('Ghana', 'essential-real-estate'),
            'GI' => esc_html__('Gibraltar', 'essential-real-estate'),
            'GR' => esc_html__('Greece', 'essential-real-estate'),
            'GL' => esc_html__('Greenland', 'essential-real-estate'),
            'GD' => esc_html__('Grenada', 'essential-real-estate'),
            'GP' => esc_html__('Guadeloupe', 'essential-real-estate'),
            'GU' => esc_html__('Guam', 'essential-real-estate'),
            'GT' => esc_html__('Guatemala', 'essential-real-estate'),
            'GG' => esc_html__('Guernsey', 'essential-real-estate'),
            'GN' => esc_html__('Guinea', 'essential-real-estate'),
            'GW' => esc_html__('Guinea-Bissau', 'essential-real-estate'),
            'GY' => esc_html__('Guyana', 'essential-real-estate'),
            'HT' => esc_html__('Haiti', 'essential-real-estate'),
            'HM' => esc_html__('Heard Island and McDonald Islands', 'essential-real-estate'),
            'VA' => esc_html__('Holy See (Vatican City State)', 'essential-real-estate'),
            'HN' => esc_html__('Honduras', 'essential-real-estate'),
            'HK' => esc_html__('Hong Kong', 'essential-real-estate'),
            'HU' => esc_html__('Hungary', 'essential-real-estate'),
            'IS' => esc_html__('Iceland', 'essential-real-estate'),
            'IN' => esc_html__('India', 'essential-real-estate'),
            'ID' => esc_html__('Indonesia', 'essential-real-estate'),
            'IR' => esc_html__('Iran', 'essential-real-estate'),
            'IQ' => esc_html__('Iraq', 'essential-real-estate'),
            'IE' => esc_html__('Ireland', 'essential-real-estate'),
            'IM' => esc_html__('Isle of Man', 'essential-real-estate'),
            'IL' => esc_html__('Israel', 'essential-real-estate'),
            'IT' => esc_html__('Italy', 'essential-real-estate'),
            'JM' => esc_html__('Jamaica', 'essential-real-estate'),
            'JP' => esc_html__('Japan', 'essential-real-estate'),
            'JE' => esc_html__('Jersey', 'essential-real-estate'),
            'JO' => esc_html__('Jordan', 'essential-real-estate'),
            'KZ' => esc_html__('Kazakhstan', 'essential-real-estate'),
            'KE' => esc_html__('Kenya', 'essential-real-estate'),
            'KI' => esc_html__('Kiribati', 'essential-real-estate'),
            'KP' => esc_html__('Korea', 'essential-real-estate'),
            'KR' => esc_html__('Korea', 'essential-real-estate'),
            'KW' => esc_html__('Kuwait', 'essential-real-estate'),
            'KG' => esc_html__('Kyrgyz Republic', 'essential-real-estate'),
            'LA' => esc_html__('Lao', 'essential-real-estate'),
            'LV' => esc_html__('Latvia', 'essential-real-estate'),
            'LB' => esc_html__('Lebanon', 'essential-real-estate'),
            'LS' => esc_html__('Lesotho', 'essential-real-estate'),
            'LR' => esc_html__('Liberia', 'essential-real-estate'),
            'LY' => esc_html__('Libyan Arab Jamahiriya', 'essential-real-estate'),
            'LI' => esc_html__('Liechtenstein', 'essential-real-estate'),
            'LT' => esc_html__('Lithuania', 'essential-real-estate'),
            'LU' => esc_html__('Luxembourg', 'essential-real-estate'),
            'MO' => esc_html__('Macao', 'essential-real-estate'),
            'MK' => esc_html__('Macedonia', 'essential-real-estate'),
            'MG' => esc_html__('Madagascar', 'essential-real-estate'),
            'MW' => esc_html__('Malawi', 'essential-real-estate'),
            'MY' => esc_html__('Malaysia', 'essential-real-estate'),
            'MV' => esc_html__('Maldives', 'essential-real-estate'),
            'ML' => esc_html__('Mali', 'essential-real-estate'),
            'MT' => esc_html__('Malta', 'essential-real-estate'),
            'MH' => esc_html__('Marshall Islands', 'essential-real-estate'),
            'MQ' => esc_html__('Martinique', 'essential-real-estate'),
            'MR' => esc_html__('Mauritania', 'essential-real-estate'),
            'MU' => esc_html__('Mauritius', 'essential-real-estate'),
            'YT' => esc_html__('Mayotte', 'essential-real-estate'),
            'MX' => esc_html__('Mexico', 'essential-real-estate'),
            'FM' => esc_html__('Micronesia', 'essential-real-estate'),
            'MD' => esc_html__('Moldova', 'essential-real-estate'),
            'MC' => esc_html__('Monaco', 'essential-real-estate'),
            'MN' => esc_html__('Mongolia', 'essential-real-estate'),
            'ME' => esc_html__('Montenegro', 'essential-real-estate'),
            'MS' => esc_html__('Montserrat', 'essential-real-estate'),
            'MA' => esc_html__('Morocco', 'essential-real-estate'),
            'MZ' => esc_html__('Mozambique', 'essential-real-estate'),
            'MM' => esc_html__('Myanmar', 'essential-real-estate'),
            'NA' => esc_html__('Namibia', 'essential-real-estate'),
            'NR' => esc_html__('Nauru', 'essential-real-estate'),
            'NP' => esc_html__('Nepal', 'essential-real-estate'),
            'AN' => esc_html__('Netherlands Antilles', 'essential-real-estate'),
            'NL' => esc_html__('Netherlands the', 'essential-real-estate'),
            'NC' => esc_html__('New Caledonia', 'essential-real-estate'),
            'NZ' => esc_html__('New Zealand', 'essential-real-estate'),
            'NI' => esc_html__('Nicaragua', 'essential-real-estate'),
            'NE' => esc_html__('Niger', 'essential-real-estate'),
            'NG' => esc_html__('Nigeria', 'essential-real-estate'),
            'NU' => esc_html__('Niue', 'essential-real-estate'),
            'NF' => esc_html__('Norfolk Island', 'essential-real-estate'),
            'MP' => esc_html__('Northern Mariana Islands', 'essential-real-estate'),
            'NO' => esc_html__('Norway', 'essential-real-estate'),
            'OM' => esc_html__('Oman', 'essential-real-estate'),
            'PK' => esc_html__('Pakistan', 'essential-real-estate'),
            'PW' => esc_html__('Palau', 'essential-real-estate'),
            'PS' => esc_html__('Palestinian Territory', 'essential-real-estate'),
            'PA' => esc_html__('Panama', 'essential-real-estate'),
            'PG' => esc_html__('Papua New Guinea', 'essential-real-estate'),
            'PY' => esc_html__('Paraguay', 'essential-real-estate'),
            'PE' => esc_html__('Peru', 'essential-real-estate'),
            'PH' => esc_html__('Philippines', 'essential-real-estate'),
            'PN' => esc_html__('Pitcairn Islands', 'essential-real-estate'),
            'PL' => esc_html__('Poland', 'essential-real-estate'),
            'PT' => esc_html__('Portugal, Portuguese Republic', 'essential-real-estate'),
            'PR' => esc_html__('Puerto Rico', 'essential-real-estate'),
            'QA' => esc_html__('Qatar', 'essential-real-estate'),
            'RE' => esc_html__('Reunion', 'essential-real-estate'),
            'RO' => esc_html__('Romania', 'essential-real-estate'),
            'RU' => esc_html__('Russian Federation', 'essential-real-estate'),
            'RW' => esc_html__('Rwanda', 'essential-real-estate'),
            'BL' => esc_html__('Saint Barthelemy', 'essential-real-estate'),
            'SH' => esc_html__('Saint Helena', 'essential-real-estate'),
            'KN' => esc_html__('Saint Kitts and Nevis', 'essential-real-estate'),
            'LC' => esc_html__('Saint Lucia', 'essential-real-estate'),
            'MF' => esc_html__('Saint Martin', 'essential-real-estate'),
            'PM' => esc_html__('Saint Pierre and Miquelon', 'essential-real-estate'),
            'VC' => esc_html__('Saint Vincent and the Grenadines', 'essential-real-estate'),
            'WS' => esc_html__('Samoa', 'essential-real-estate'),
            'SM' => esc_html__('San Marino', 'essential-real-estate'),
            'ST' => esc_html__('Sao Tome and Principe', 'essential-real-estate'),
            'SA' => esc_html__('Saudi Arabia', 'essential-real-estate'),
            'SN' => esc_html__('Senegal', 'essential-real-estate'),
            'RS' => esc_html__('Serbia', 'essential-real-estate'),
            'SC' => esc_html__('Seychelles', 'essential-real-estate'),
            'SL' => esc_html__('Sierra Leone', 'essential-real-estate'),
            'SG' => esc_html__('Singapore', 'essential-real-estate'),
            'SK' => esc_html__('Slovakia (Slovak Republic)', 'essential-real-estate'),
            'SI' => esc_html__('Slovenia', 'essential-real-estate'),
            'SB' => esc_html__('Solomon Islands', 'essential-real-estate'),
            'SO' => esc_html__('Somalia, Somali Republic', 'essential-real-estate'),
            'ZA' => esc_html__('South Africa', 'essential-real-estate'),
            'GS' => esc_html__('South Georgia and the South Sandwich Islands', 'essential-real-estate'),
            'ES' => esc_html__('Spain', 'essential-real-estate'),
            'LK' => esc_html__('Sri Lanka', 'essential-real-estate'),
            'SD' => esc_html__('Sudan', 'essential-real-estate'),
            'SR' => esc_html__('Suriname', 'essential-real-estate'),
            'SJ' => esc_html__('Svalbard & Jan Mayen Islands', 'essential-real-estate'),
            'SZ' => esc_html__('Swaziland', 'essential-real-estate'),
            'SE' => esc_html__('Sweden', 'essential-real-estate'),
            'CH' => esc_html__('Switzerland, Swiss Confederation', 'essential-real-estate'),
            'SY' => esc_html__('Syrian Arab Republic', 'essential-real-estate'),
            'TW' => esc_html__('Taiwan', 'essential-real-estate'),
            'TJ' => esc_html__('Tajikistan', 'essential-real-estate'),
            'TZ' => esc_html__('Tanzania', 'essential-real-estate'),
            'TH' => esc_html__('Thailand', 'essential-real-estate'),
            'TL' => esc_html__('Timor-Leste', 'essential-real-estate'),
            'TG' => esc_html__('Togo', 'essential-real-estate'),
            'TK' => esc_html__('Tokelau', 'essential-real-estate'),
            'TO' => esc_html__('Tonga', 'essential-real-estate'),
            'TT' => esc_html__('Trinidad and Tobago', 'essential-real-estate'),
            'TN' => esc_html__('Tunisia', 'essential-real-estate'),
            'TR' => esc_html__('Turkey', 'essential-real-estate'),
            'TM' => esc_html__('Turkmenistan', 'essential-real-estate'),
            'TC' => esc_html__('Turks and Caicos Islands', 'essential-real-estate'),
            'TV' => esc_html__('Tuvalu', 'essential-real-estate'),
            'UG' => esc_html__('Uganda', 'essential-real-estate'),
            'UA' => esc_html__('Ukraine', 'essential-real-estate'),
            'AE' => esc_html__('United Arab Emirates', 'essential-real-estate'),
            'GB' => esc_html__('United Kingdom', 'essential-real-estate'),
            'US' => esc_html__('United States', 'essential-real-estate'),
            'UM' => esc_html__('United States Minor Outlying Islands', 'essential-real-estate'),
            'VI' => esc_html__('United States Virgin Islands', 'essential-real-estate'),
            'UY' => esc_html__('Uruguay, Eastern Republic of', 'essential-real-estate'),
            'UZ' => esc_html__('Uzbekistan', 'essential-real-estate'),
            'VU' => esc_html__('Vanuatu', 'essential-real-estate'),
            'VE' => esc_html__('Venezuela', 'essential-real-estate'),
            'VN' => esc_html__('Vietnam', 'essential-real-estate'),
            'WF' => esc_html__('Wallis and Futuna', 'essential-real-estate'),
            'EH' => esc_html__('Western Sahara', 'essential-real-estate'),
            'YE' => esc_html__('Yemen', 'essential-real-estate'),
            'ZM' => esc_html__('Zambia', 'essential-real-estate'),
            'ZW' => esc_html__('Zimbabwe', 'essential-real-estate'),
	        'SS' => esc_html__('South Sudan','essential-real-estate')
        );
        return $countries;
    }
}

if (!function_exists('ere_get_selected_countries')) {
    function ere_get_selected_countries()
    {
        $countries=ere_get_countries();
        $countries_selected = get_option( 'country_list' );
        if(!empty($countries_selected) && is_array($countries_selected))
        {
            $results=array();
            foreach($countries_selected as $country){
                foreach($countries as $key => $value)
                {
                    if($country===$key)
                    {
                        $results[$key]=$value;
                    }
                }
            }
            return $results;
        }
        else
        {
            return $countries;
        }
    }
}
/**
 * Get countries by code
 */
if (!function_exists('ere_get_country_by_code')) {
    function ere_get_country_by_code($code)
    {
        $countries = ere_get_countries();
        foreach ($countries as $key => $val) {
            if ($key == $code) return $val;
        }
        return null;
    }
}
/**
 * Get countries by name
 */
if (!function_exists('ere_get_code_country_by_name')) {
    function ere_get_code_country_by_name($name)
    {
        $countries = ere_get_countries();
        $country_code = array_search($name, $countries);
        return $country_code;
    }
}
if (!function_exists('ere_clean_double_val')) {
    function ere_clean_double_val($string)
    {
        $string = preg_replace('/&#36;/', '', $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $string = preg_replace('/\D/', '', $string);
        return $string;
    }
}
/**
 * Get measurement units
 */
if (!function_exists('ere_get_measurement_units')) {
    function ere_get_measurement_units()
    {
        $measurement_units = ere_get_option('measurement_units', 'SqFt');
        if ($measurement_units == 'custom') {
            return ere_get_option('custom_measurement_units', 'SqFt');
        }
        else
        {
	        if ($measurement_units === 'm2') {
		        $measurement_units = 'm<sup>2</sup>';
	        }
            return $measurement_units;
        }
    }
}
if (!function_exists('ere_get_measurement_units_land_area')) {
    function ere_get_measurement_units_land_area()
    {
        $measurement_units = ere_get_option('measurement_units_land_area', '');
        if(empty($measurement_units))
        {
            $measurement_units = ere_get_measurement_units();
        }
        if ($measurement_units == 'custom') {
            return ere_get_option('custom_measurement_units_land_area', 'SqFt');
        }
        else
        {
	        if ($measurement_units === 'm2') {
		        $measurement_units = 'm<sup>2</sup>';
	        }
            return $measurement_units;
        }
    }
}
if (!function_exists('ere_render_additional_fields')) {
    function ere_render_additional_fields()
    {
        $meta_prefix = ERE_METABOX_PREFIX;
        $form_fields = ere_get_option('additional_fields');
        $configs = array();
        if ($form_fields && is_array($form_fields)) {
            foreach ($form_fields as $key => $field) {
                if(!empty($field['label']))
                {
                    $type = $field['field_type'];
                    $id = isset($field['id']) && !empty($field['id'])
	                    ? $field['id']
	                    : sanitize_title($field['label']);

                    if (in_array($id,array('property_price_short','property_price_prefix','property_price_postfix','property_price_on_call','property_size','property_land','property_rooms','property_bedrooms','property_bathrooms','property_garage','property_garage_size','property_year','property_identity','additional_features','property_featured','property_address','property_zip','property_location','floors_enable','floors','property_images','property_attachments','property_video_url','property_video_image','property_virtual_tour_type','property_virtual_tour','property_image_360','agent_display_option','property_agent','property_other_contact_name','property_other_contact_mail','property_other_contact_phone','property_other_contact_description','private_note'))) {
                        $id = 'additional_detail__' . $id;
                    }

                    $config = array(
                        'title' => $field['label'],
                        'id' => $meta_prefix . $id,
                        'type' => $type
                    );
                    $first_opt = '';
                    switch ($type) {
                        case 'checkbox_list':
                        case 'select':
                        case 'radio':
                            $options = array();
                            $options_arr = isset($field['select_choices']) ? $field['select_choices'] : '';
                            $options_arr = str_replace("\r\n", "\n", $options_arr);
                            $options_arr = str_replace("\r", "\n", $options_arr);
                            $options_arr = explode("\n", $options_arr);
                            $first_opt = !empty($options_arr) ? $options_arr[0] : '';
                            foreach ($options_arr as $opt_value) {
                                $options[$opt_value] = $opt_value;
                            }

                            $config['options'] = $options;
                            break;
                    }
                    if (in_array($type, array('select', 'radio'))) {
                        $config['default'] = $first_opt;
                    }

                    $configs[] = $config;
                }
            }
        }
        return $configs;
    }
}

if (!function_exists('ere_get_search_additional_fields')) {
	function ere_get_search_additional_fields() {
		$additional_fields = ere_get_option('additional_fields');
		$search_builtIn_fields =  ere_get_search_builtIn_fields();
		$configs = array();
		if ($additional_fields && is_array($additional_fields)) {
			foreach ($additional_fields as $key => $field) {
				if ((isset($field['label']) && !empty($field['label'])) && (isset($field['is_search']) && $field['is_search'] === 'on')) {
					$id = isset($field['id']) && !empty($field['id'])
						? $field['id']
						: sanitize_title($field['label']);
					if (in_array($id,$search_builtIn_fields)) {
						$id = 'additional_detail__' . $id;
					}

					$configs[$id] = $field['label'];
				}
			}
		}
		return $configs;
	}
}

if (!function_exists('ere_get_search_additional_field')) {
	function ere_get_search_additional_field($key) {
		$additional_fields = ere_get_option('additional_fields');
		$search_builtIn_fields =  ere_get_search_builtIn_fields();
		if ($additional_fields && is_array($additional_fields)) {
			foreach ($additional_fields as $k => $field) {
				$id = isset($field['id']) && !empty($field['id'])
					? $field['id']
					: sanitize_title($field['label']);

				if (in_array($id,$search_builtIn_fields)) {
					$id = 'additional_detail__' . $id;
				}

				if (($id === $key) && (isset($field['is_search']) && $field['is_search'] === 'on')) {
					$field['id'] = $id;
					return $field;
				}
			}
		}
		return false;
	}
}

if(!function_exists('ere_get_search_builtIn_fields')) {
	function ere_get_search_builtIn_fields() {
		return apply_filters('ere_search_builtIn_fields',array(
			'keyword',
			'property_status',
			'property_type',
			'property_title',
			'property_address',
			'property_country',
			'property_state',
			'property_city',
			'property_neighborhood',
			'property_rooms',
			'property_bedrooms',
			'property_bathrooms',
			'property_price',
			'property_size',
			'property_land',
			'property_label',
			'property_garage',
			'property_identity',
			'property_feature',
		));
	}
}

if (!function_exists('ere_get_search_form_fields_config')) {
	function ere_get_search_form_fields_config() {
		$config =  array(
			'keyword' => esc_html__('Keyword', 'essential-real-estate'),
			'property_status' => esc_html__('Status', 'essential-real-estate'),
			'property_type' => esc_html__('Type', 'essential-real-estate'),
			'property_title' => esc_html__('Title', 'essential-real-estate'),
			'property_address' => esc_html__('Address', 'essential-real-estate'),
			'property_country' => esc_html__('Country', 'essential-real-estate'),
			'property_state' => esc_html__('Province / State', 'essential-real-estate'),
			'property_city' => esc_html__('City / Town', 'essential-real-estate'),
			'property_neighborhood' => esc_html__('Neighborhood', 'essential-real-estate'),
			'property_rooms' => esc_html__('Rooms', 'essential-real-estate'),
			'property_bedrooms' => esc_html__('Bedrooms', 'essential-real-estate'),
			'property_bathrooms' => esc_html__('Bathrooms', 'essential-real-estate'),
			'property_price' => esc_html__('Price', 'essential-real-estate'),
			'property_size' => esc_html__('Size', 'essential-real-estate'),
			'property_land' => esc_html__('Land Area', 'essential-real-estate'),
			'property_label' => esc_html__('Label', 'essential-real-estate'),
			'property_garage' => esc_html__('Garage', 'essential-real-estate'),
			'property_identity' => esc_html__('Property ID', 'essential-real-estate'),
			'property_feature' => esc_html__('Other Features', 'essential-real-estate'),
		);
		$additional_fields = ere_get_search_additional_fields();
		$config = wp_parse_args($additional_fields,$config);
		return apply_filters('get_search_form_fields_config',$config);
	}
}



if ( ! function_exists('ere_server_protocol') ) {

    function ere_server_protocol() {
        if ( is_ssl() ) {
            return 'https://';
        }
        return 'http://';
    }

}
if ( ! function_exists('ere_get_comment_time') ) {
    function ere_get_comment_time($comment_id = 0)
    {
        return sprintf(
            _x('%s ago', 'Human-readable time', 'essential-real-estate'),
            human_time_diff(
                get_comment_date('U', $comment_id),
                current_time('timestamp')
            )
        );
    }
}
if (!function_exists('ere_get_number_text')) {
    function ere_get_number_text($number, $many_text, $singular_text) {
        if($number != 1) {
            return $many_text;
        } else {
            return $singular_text;
        }
    }
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
if (!function_exists('ere_clean')) {
    function ere_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'ere_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
}

if (!function_exists('ere_sort_by_order_callback')) {
	function ere_sort_by_order_callback( $a, $b ) {
		if ( ! isset( $a['priority'] ) ) {
			$a['priority'] = 100;
		}
		if ( ! isset( $b['priority'] ) ) {
			$b['priority'] = 100;
		}

		return $a['priority'] === $b['priority'] ? 0 : ( $a['priority'] > $b['priority'] ? 1 : - 1 );
	}
}

if (!function_exists('ere_urlencode')) {
	function ere_urlencode( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'urlencode', $var );
		} else {
			return urlencode($var);
		}
	}
}

if (!function_exists('ere_string_end_with')) {
	function ere_string_end_with( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}
}

if (!function_exists('ere_custom_load_textdomain')) {
	add_action('plugin_loaded', 'ere_custom_load_textdomain', 1);
	function ere_custom_load_textdomain() {
		if (is_admin()) {
			$script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';

			if ((ere_string_end_with($script_name, '/wp-admin/post-new.php') && (isset($_REQUEST['post_type']) && ($_REQUEST['post_type'] === 'property')))
				|| (ere_string_end_with($script_name, '/wp-admin/post.php') && isset($_REQUEST['post']) && (isset($_REQUEST['action']) && ($_REQUEST['action'] === 'edit')))) {
				global $sitepress;
				if (isset($sitepress) && $sitepress) {
					$current_lang = $sitepress->get_current_language();
					$current_locale = $sitepress->locale_utils->get_locale($current_lang);
					$text_domain = 'admin_texts_ere_options';

					$mo_file = WP_CONTENT_DIR . "/languages/wpml/{$text_domain}-{$current_locale}.mo";
					if (is_readable($mo_file)) {
						load_textdomain( $text_domain, $mo_file);
					}
				}
			}

		}
	}
}


