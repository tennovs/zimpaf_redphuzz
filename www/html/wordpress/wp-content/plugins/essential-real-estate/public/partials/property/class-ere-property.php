<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Property')) {
    /**
     * Class ERE_Property
     */
    class ERE_Property
    {
	    /*
		 * loader instances
		 */
	    private static $_instance;

	    public static function getInstance()
	    {
		    if (self::$_instance == null) {
			    self::$_instance = new self();
		    }

		    return self::$_instance;
	    }

	    /**
         * Remove property thumbnail
         */
        public function remove_property_attachment_ajax()
        {
            $nonce = isset($_POST['removeNonce']) ? ere_clean(wp_unslash($_POST['removeNonce'])) : '';
            if (!wp_verify_nonce($nonce, 'property_allow_upload')) {
                $json_response = array(
                    'success' => false,
                    'reason' => esc_html__('Security check fails', 'essential-real-estate')
                );
                echo json_encode($json_response);
                wp_die();
            }
            $success = false;
            if (isset($_POST['property_id']) && isset($_POST['attachment_id'])) {
                $property_id = absint(wp_unslash($_POST['property_id'])) ;
                $type = isset($_POST['type']) ? ere_clean(wp_unslash($_POST['type'])) : '';
                $attachment_id = absint(wp_unslash($_POST['attachment_id']));
                if ($property_id > 0) {
                    if ($type === 'gallery') {
                        delete_post_meta($property_id, ERE_METABOX_PREFIX . 'property_images', $attachment_id);
                    } else {
                        delete_post_meta($property_id, ERE_METABOX_PREFIX . 'property_attachments', $attachment_id);
                    }
                    $success = true;
                }
                if ($attachment_id > 0) {
                    wp_delete_attachment($attachment_id);
                    $success = true;
                }
            }
            $ajax_response = array(
                'success' => $success,
            );
            echo json_encode($ajax_response);
            wp_die();
        }

        public function delete_property_attachments($postid)
        {
            global $post_type;
            if ($post_type == 'property') {
                $media = get_children(array(
                    'post_parent' => $postid,
                    'post_type' => 'attachment'
                ));
                if (!empty($media)) {
                    foreach ($media as $file) {
                        wp_delete_attachment($file->ID);
                    }
                }
                $gallery_ids = get_post_meta($postid, ERE_METABOX_PREFIX . 'property_images', true);
                $gallery_ids = explode('|', $gallery_ids);
                if (!empty($gallery_ids)) {
                    foreach ($gallery_ids as $id) {
                        wp_delete_attachment($id);
                    }
                }

                $attachment_ids = get_post_meta($postid, ERE_METABOX_PREFIX . 'property_attachments', true);
                $attachment_ids = explode('|', $attachment_ids);
                if (!empty($attachment_ids)) {
                    foreach ($attachment_ids as $id) {
                        wp_delete_attachment($id);
                    }
                }
            }
            return;
        }

        public function property_img_upload_ajax()
        {
            $nonce = isset($_REQUEST['nonce']) ? ere_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'property_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'essential-real-estate'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['property_upload_file']; // WPCS: sanitization ok, input var ok.

            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid' => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $thumbnail_url = wp_get_attachment_thumb_url($attach_id);
                $fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

                $ajax_response = array(
                    'success' => true,
                    'url' => $thumbnail_url,
                    'attachment_id' => $attach_id,
                    'full_image' => $fullimage_url[0]
                );
                echo json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'essential-real-estate'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        public function property_attachment_upload_ajax()
        {
            $nonce = isset($_REQUEST['nonce']) ? ere_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'property_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'essential-real-estate'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['property_upload_file'];  // WPCS: sanitization ok, input var ok.
            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);


                $attachment_details = array(
                    'guid' => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $attach_url = wp_get_attachment_url($attach_id);
                $file_type_name = isset($file_type['ext']) ? $file_type['ext'] : '';
                $thumb_url = ERE_PLUGIN_URL . 'public/assets/images/attachment/attach-' . $file_type_name . '.png';

                $ajax_response = array(
                    'success' => true,
                    'url' => $attach_url,
                    'attachment_id' => $attach_id,
                    'thumb_url' => $thumb_url,
                    'file_name' => $file_name
                );
                echo json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Document upload failed!', 'essential-real-estate'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Submit property
         * @param array $new_property
         * @return int|null|WP_Error
         */
        public function submit_property($new_property = array())
        {
            $new_property['post_type'] = 'property';
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $new_property['post_author'] = $user_id;
            $auto_publish = ere_get_option('auto_publish', 1);
            $auto_publish_edited = ere_get_option('auto_publish_edited', 1);
            $paid_submission_type = ere_get_option('paid_submission_type', 'no');

            if (isset($_POST['property_title'])) {
                $new_property['post_title'] = ere_clean(wp_unslash($_POST['property_title']));
            }

            if (isset($_POST['property_des'])) {
                $new_property['post_content'] = wp_filter_post_kses($_POST['property_des']);
            }

            $submit_action = isset($_POST['property_form']) ? ere_clean(wp_unslash($_POST['property_form'])) : '';
            $property_id = 0;
            if ($submit_action == 'submit-property') {
                if ($paid_submission_type == 'per_listing') {
                    $price_per_listing = ere_get_option('price_per_listing', 0);
                    if ($price_per_listing <= 0) {
                        if ($auto_publish == 1) {
                            $new_property['post_status'] = 'publish';
                        } else {
                            $new_property['post_status'] = 'pending';
                        }
                    } else {
                        $new_property['post_status'] = 'draft';
                    }
                } else {
                    if ($auto_publish == 1) {
                        $new_property['post_status'] = 'publish';
                    } else {
                        $new_property['post_status'] = 'pending';
                    }
                }

                $property_id = wp_insert_post($new_property, true);
                if ($property_id > 0) {
                    if ($paid_submission_type == 'per_package') {
                        $package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'package_key', $package_key);
                        $package_num_properties = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_listings', $user_id);
                        if ($package_num_properties - 1 >= 0) {
                            update_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings', $package_num_properties - 1);
                        }
                    }
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 0);
                }
            } else if ($submit_action == 'edit-property') {
                $property_id = absint(wp_unslash($_POST['property_id'])) ;
                $new_property['ID'] = intval($property_id);
                if ($paid_submission_type == 'per_package') {
                    $current_package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
                    $property_package_key = get_post_meta($new_property['ID'], ERE_METABOX_PREFIX . 'package_key', true);
                    $ere_profile = new ERE_Profile();
                    $check_package = $ere_profile->user_package_available($user_id);
                    if (empty($property_package_key) || ($current_package_key != $property_package_key) || ($check_package == -1) || ($check_package == 0)) {
                        return -1;
                    }
                }
                if ($auto_publish_edited != 1) {
                    $new_property['post_status'] = 'pending';
                }

                $property_id = wp_update_post($new_property);

            }
            if ($property_id > 0) {
                $property_price_on_call = isset($_POST['property_price_on_call']) ? 1 : 0;
                update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_on_call', $property_price_on_call);
                if ($property_price_on_call == 1) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_short', '');
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_unit', 1);
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', '');
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_prefix', '');
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_postfix', '');
                } else {
                    if (isset($_POST['property_price_unit'])) {
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_unit', sanitize_text_field(wp_unslash($_POST['property_price_unit'])));
                    } else {
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_unit', 1);
                    }
                    if (isset($_POST['property_price_short'])) {
                        $property_price_short = ere_format_decimal(ere_clean(wp_unslash($_POST['property_price_short'])));
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_short', $property_price_short);
                        if (is_numeric($property_price_short)) {
                            if (isset($_POST['property_price_unit']) && is_numeric($_POST['property_price_unit'])) {
                                $property_price_unit = absint(wp_unslash($_POST['property_price_unit']));
                            } else {
                                $property_price_unit = 1;
                            }
                            $property_price = doubleval($property_price_short) * intval($property_price_unit);
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', $property_price);
                        } else {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', '');
                        }
                    }
                    if (isset($_POST['property_price_prefix'])) {
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_prefix', sanitize_text_field(wp_unslash($_POST['property_price_prefix'])));
                    }

                    if (isset($_POST['property_price_postfix'])) {
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price_postfix', sanitize_text_field(wp_unslash($_POST['property_price_postfix'])));
                    }
                }


                if (isset($_POST['property_size'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_size', sanitize_text_field(wp_unslash($_POST['property_size'])));
                }

                if (isset($_POST['property_land'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_land', sanitize_text_field(wp_unslash($_POST['property_land'])));
                }

                if (isset($_POST['property_rooms'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_rooms', sanitize_text_field(wp_unslash($_POST['property_rooms'])));
                }

                if (isset($_POST['property_bedrooms'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_bedrooms', sanitize_text_field(wp_unslash($_POST['property_bedrooms'])));
                }

                if (isset($_POST['property_bathrooms'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_bathrooms', sanitize_text_field(wp_unslash($_POST['property_bathrooms'])));
                }

                if (isset($_POST['property_garage'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_garage', sanitize_text_field(wp_unslash($_POST['property_garage'])));
                }

                if (isset($_POST['property_garage_size'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_garage_size', sanitize_text_field(wp_unslash($_POST['property_garage_size'])));
                }

                if (isset($_POST['property_year'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_year', sanitize_text_field(wp_unslash($_POST['property_year'])));
                }

                if (isset($_POST['property_video_url'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_video_url', sanitize_text_field(wp_unslash($_POST['property_video_url'])));
                }
                if (isset($_POST['property_identity']) && !empty($_POST['property_identity'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_identity', sanitize_text_field(wp_unslash($_POST['property_identity'])));
                } else {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_identity', $property_id);
                }
                $additional_fields = ere_render_additional_fields();
                if (count($additional_fields) > 0) {
                    foreach ($additional_fields as $key => $field) {
                        if (isset($_POST[$field['id']])) {
                            if ($field['type'] == 'checkbox_list') {
                                $arr = array();
                                foreach ($_POST[$field['id']] as $v) {
                                    $arr[] = $v;
                                }
                                update_post_meta($property_id, $field['id'], $arr);
                            } else {
                                update_post_meta($property_id, $field['id'], sanitize_text_field(wp_unslash($_POST[$field['id']])));
                            }
                        }
                    }
                }
                if (isset($_POST['property_image_360_id']) && isset($_POST['property_image_360_url'])) {
                    $property_image_360 = array(
                        'id' => absint(wp_unslash($_POST['property_image_360_id'])),
                        'url' => esc_url_raw(wp_unslash($_POST['property_image_360_url'])),
                    );
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_image_360', $property_image_360);
                }
                if (isset($_POST['property_image_ids'])) {
                    if (!empty($_POST['property_image_ids']) && is_array($_POST['property_image_ids'])) {
                        $property_image_ids = array();
                        $str_img_ids = '';
                        foreach ($_POST['property_image_ids'] as $property_img_id) {
                            $property_image_ids[] = intval($property_img_id);
                            $str_img_ids .= '|' . intval($property_img_id);
                        }
                        $str_img_ids = substr($str_img_ids, 1);
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_images', $str_img_ids);
                        if (isset($_POST['featured_image_id'])) {
                            $featured_image_id = absint(wp_unslash($_POST['featured_image_id'])) ;
                            if (in_array($featured_image_id, $property_image_ids)) {
                                update_post_meta($property_id, '_thumbnail_id', $featured_image_id);
                                if (!empty($_POST['property_video_url'])) {
                                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_video_image', $featured_image_id);
                                }
                            }
                        } elseif (!empty ($property_image_ids)) {
                            update_post_meta($property_id, '_thumbnail_id', $property_image_ids[0]);
                        }
                    }
                }

                if (isset($_POST['property_attachment_ids'])) {
                    if (!empty($_POST['property_attachment_ids']) && is_array($_POST['property_attachment_ids'])) {
                        $property_attachment_ids = array();
                        $str_attachment_ids = '';
                        foreach ($_POST['property_attachment_ids'] as $property_attachment_id) {
                            $property_attachment_ids[] = intval($property_attachment_id);
                            $str_attachment_ids .= '|' . intval($property_attachment_id);
                        }
                        $str_attachment_ids = substr($str_attachment_ids, 1);
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_attachments', $str_attachment_ids);
                    }
                }

                $property_type = isset($_POST['property_type']) ? array_map('intval',wp_unslash($_POST['property_type'])) : null;
	            wp_set_object_terms($property_id, $property_type, 'property-type');

	            $property_status = isset($_POST['property_status']) ? array_map('intval',wp_unslash($_POST['property_status'])) : null;
	            wp_set_object_terms($property_id, $property_status, 'property-status');

	            $property_label = isset($_POST['property_label']) ? array_map('intval',wp_unslash($_POST['property_label'])) : null;
	            wp_set_object_terms($property_id, $property_label, 'property-label');


                if (isset($_POST['locality'])) {
                    $property_city = wp_filter_kses(wp_unslash($_POST['locality']));
                    wp_set_object_terms($property_id, $property_city, 'property-city');
                } elseif (isset($_POST['property_city'])) {
                    $property_city = wp_filter_kses(wp_unslash($_POST['property_city']));
                    wp_set_object_terms($property_id, $property_city, 'property-city');
                }
                if (isset($_POST['neighborhood'])) {
                    $property_neighborhood = wp_filter_kses(wp_unslash($_POST['neighborhood']));
                    wp_set_object_terms($property_id, $property_neighborhood, 'property-neighborhood');
                } elseif (isset($_POST['property_neighborhood'])) {
                    $property_neighborhood = wp_filter_kses(wp_unslash($_POST['property_neighborhood']));
                    wp_set_object_terms($property_id, $property_neighborhood, 'property-neighborhood');
                }

                if (isset($_POST['administrative_area_level_1'])) {
                    $property_state = wp_filter_kses(wp_unslash($_POST['administrative_area_level_1']));
                    wp_set_object_terms($property_id, $property_state, 'property-state');
                } elseif (isset($_POST['property_state'])) {
                    $property_state = wp_filter_kses(wp_unslash($_POST['property_state']));
                    wp_set_object_terms($property_id, $property_state, 'property-state');
                }

                if (isset($_POST['property_feature'])) {
                    $features_array = array();
                    foreach ($_POST['property_feature'] as $feature_id) {
                        $features_array[] = intval($feature_id);
                    }
                    wp_set_object_terms($property_id, $features_array, 'property-feature');
                }

                if (isset($_POST['floors_enable'])) {
                    $floors_enable = ere_clean(wp_unslash($_POST['floors_enable']));
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'floors_enable', $floors_enable);
                }

                if (isset($_POST[ERE_METABOX_PREFIX . 'floors'])) {
                    $floors_post = ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'floors']));
                    if (!empty($floors_post)) {
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'floors', $floors_post);
                    }
                }

                if (isset($_POST['agent_display_option'])) {
                    $property_agent_display_option = sanitize_text_field(wp_unslash($_POST['agent_display_option']));
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'agent_display_option', $property_agent_display_option);
                    if ($property_agent_display_option == 'other_info') {
                        if (isset($_POST['property_other_contact_name'])) {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_other_contact_name', sanitize_text_field(wp_unslash($_POST['property_other_contact_name'])));
                        }
                        if (isset($_POST['property_other_contact_mail'])) {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_other_contact_mail', sanitize_email(wp_unslash($_POST['property_other_contact_mail'])));
                        }
                        if (isset($_POST['property_other_contact_phone'])) {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_other_contact_phone', sanitize_text_field(wp_unslash($_POST['property_other_contact_phone'])));
                        }
                        if (isset($_POST['property_other_contact_description'])) {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_other_contact_description', wp_filter_post_kses($_POST['property_other_contact_description']));
                        }
                    } else {
                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_author', $user_id);
                    }

                } else {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'agent_display_option', 'author_info');
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_author', $user_id);
                }

                if (isset($_POST['property_map_address'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_address', sanitize_text_field(wp_unslash($_POST['property_map_address'])));
                }

                if ((isset($_POST['lat']) && !empty($_POST['lat'])) && (isset($_POST['lng']) && !empty($_POST['lng']))) {
                    $lat = sanitize_text_field(wp_unslash($_POST['lat']));
                    $lng = sanitize_text_field(wp_unslash($_POST['lng']));
                    $lat_lng = $lat . ',' . $lng;
                    $address = apply_filters('ere_submit_property_map_address',sanitize_text_field(wp_unslash($_POST['property_map_address']))) ;
                    $arr_location = array(
                        'location' => $lat_lng,
                        'address' => $address
                    );
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', $arr_location);
                }
                if (isset($_POST['country_short'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_country', sanitize_text_field(wp_unslash($_POST['country_short'])));
                } elseif (isset($_POST['property_country'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_country', sanitize_text_field(wp_unslash($_POST['property_country'])));
                }
                if (isset($_POST['postal_code'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_zip', sanitize_text_field(wp_unslash($_POST['postal_code'])));
                }

                if (isset($_POST['additional_feature_title']) && isset($_POST['additional_feature_value'])) {
                    $additional_feature_title = ere_clean(wp_unslash($_POST['additional_feature_title'])) ;
                    $additional_feature_value = ere_clean(wp_unslash($_POST['additional_feature_value'])) ;
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_features', count($additional_feature_title));
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_feature_title', $additional_feature_title);
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_feature_value', $additional_feature_value);
                } else {
	                delete_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_features');
	                delete_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_feature_title');
	                delete_post_meta($property_id, ERE_METABOX_PREFIX . 'additional_feature_value');
                }

                if (isset($_POST['private_note'])) {
                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'private_note', sanitize_textarea_field(wp_unslash($_POST['private_note'])));
                }
                return $property_id;
            }

            return null;
        }

        /**
         * True if an the user can edit a property.
         * @param $property_id
         * @return mixed
         */
        public function user_can_edit_property($property_id)
        {
            $can_edit = true;

            if (!is_user_logged_in() || !$property_id) {
                $can_edit = false;
            } else {
                $property = get_post($property_id);

                if (!$property || (absint($property->post_author) !== get_current_user_id() && !current_user_can('edit_post', $property_id))) {
                    $can_edit = false;
                }
            }

            return apply_filters('ere_user_can_edit_property', $can_edit, $property_id);
        }

        /**
         * Get total my properties
         * @param $post_status
         * @return int
         */
        public function get_total_my_properties($post_status)
        {
            $args = array(
                'post_type' => 'property',
                'post_status' => $post_status,
                'author' => get_current_user_id(),
            );
            $properties = new WP_Query($args);
            wp_reset_postdata();
            return $properties->found_posts;
        }

        /**
         * Get total properties by user
         * @param $agent_id
         * @param $user_id
         * @return int
         */
        public function get_total_properties_by_user($agent_id, $user_id)
        {
            $args = array(
                'post_type' => 'property',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => ERE_METABOX_PREFIX . 'property_agent',
                        'value' => explode(',', $agent_id),
                        'compare' => 'IN'
                    ),
                    array(
                        'key' => ERE_METABOX_PREFIX . 'property_author',
                        'value' => explode(',', $user_id),
                        'compare' => 'IN'
                    )
                )
            );
            $properties = new WP_Query($args);
            return $properties->found_posts;
        }

        /**
         * Contact agent
         */
        public function contact_agent_ajax()
        {
            check_ajax_referer('ere_contact_agent_ajax_nonce', 'ere_security_contact_agent');
            $sender_phone = isset($_POST['sender_phone']) ? ere_clean(wp_unslash($_POST['sender_phone'])) : '';

            $target_email = isset($_POST['target_email']) ?  sanitize_email(wp_unslash($_POST['target_email'])) : '';
            $property_url = isset($_POST['property_url']) ?  esc_url_raw(wp_unslash($_POST['property_url'])) : '';
            $target_email = is_email($target_email);
            if (!$target_email) {
                echo json_encode(array('success' => false, 'message' => esc_html__('Target Email address is not properly configured!', 'essential-real-estate')));
                wp_die();
            }
            //recaptcha
            if (ere_enable_captcha('contact_agent') || ere_enable_captcha('contact_agency')) {
                do_action('ere_verify_recaptcha');
            }
            $sender_email = isset($_POST['sender_email']) ? sanitize_email(wp_unslash($_POST['sender_email'])) : '';

            $sender_name = isset($_POST['sender_name']) ?  ere_clean(wp_unslash($_POST['sender_name'])) : '';
            $sender_msg = isset($_POST['sender_msg']) ?  wp_filter_post_kses($_POST['sender_msg']) : '';

            $email_subject = sprintf(esc_html__('New message sent by %s using contact form at %s', 'essential-real-estate'), $sender_name, get_bloginfo('name'));

            $email_body = esc_html__('You have received a message from: ', 'essential-real-estate') . $sender_name . " <br/>";
            if (!empty($sender_phone)) {
                $email_body .= esc_html__('Phone Number : ', 'essential-real-estate') . $sender_phone . " <br/>";
            }
            if (!empty($property_url)) {
                $email_body .= esc_html__('Property Url: ', 'essential-real-estate') . '<a href="' . $property_url . '">' . $property_url . '</a><br/>';
            }
            $email_body .= esc_html__('Additional message is as follows.', 'essential-real-estate') . " <br/>";
            $email_body .= wpautop($sender_msg) . " <br/>";
            $email_body .= sprintf(esc_html__('You can contact %s via email %s', 'essential-real-estate'), $sender_name, $sender_email);

            $header = 'Content-type: text/html; charset=utf-8' . "\r\n";
            $header = apply_filters("ere_contact_agent_mail_header", $header);
            $header .= 'From: ' . $sender_name . " <" . $sender_email . "> \r\n";

            if (wp_mail($target_email, $email_subject, $email_body, $header)) {
                echo json_encode(array('success' => true, 'message' => esc_html__('Message Sent Successfully!', 'essential-real-estate')));
            } else {
                echo json_encode(array('success' => false, 'message' => esc_html__('Server Error: WordPress mail function failed!', 'essential-real-estate')));
            }
            wp_die();
        }

        /**
         * Favorite property
         */
        public function favorite_ajax()
        {
            global $current_user;
            $property_id = isset($_POST['property_id']) ? absint(wp_unslash($_POST['property_id'])) : 0;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $added = $removed = false;
            $ajax_response = '';
            if ($user_id > 0) {
                $my_favorites = get_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', true);

                if (!empty($my_favorites) && (!in_array($property_id, $my_favorites))) {
                    array_push($my_favorites, $property_id);
                    $added = true;
                } else {
                    if (empty($my_favorites)) {
                        $my_favorites = array($property_id);
                        $added = true;
                    } else {
                        //Delete favorite
                        $key = array_search($property_id, $my_favorites);
                        if ($key !== false) {
                            unset($my_favorites[$key]);
                            $removed = true;
                        }
                    }
                }

                update_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', $my_favorites);
                if ($added) {
                    $ajax_response = array('added' => 1, 'message' => esc_html__('Added', 'essential-real-estate'));
                }
                if ($removed) {
                    $ajax_response = array('added' => 0, 'message' => esc_html__('Removed', 'essential-real-estate'));
                }
            } else {
                $ajax_response = array(
                    'added' => -1,
                    'message' => esc_html__('You are not login', 'essential-real-estate')
                );
            }
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * view gallery property
         */
        public function view_gallery_ajax()
        {
            $property_id = isset($_POST['property_id']) ?  absint(wp_unslash($_POST['property_id'])) : 0;
            $property_gallery = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_images', true);
            $ajax_response = '';
            $index = 0;
            if ($property_gallery):
                $property_gallery = explode( '|', $property_gallery );
                foreach ( $property_gallery as $image ):
                    $index++;
                    $image_full_src = wp_get_attachment_image_src($image, 'full');
                    if ( ! empty( $image_full_src ) ) {
                        if ( $index==1 ) {
                            $ajax_response.='<a class="property-view-gallery btn-view-gallery-' . $property_id . '" href="'. esc_url($image_full_src[0]).'"><i class="fa fa-camera"></i></a>';
                        }
                        else{
                            $ajax_response.='<a style="display:none;" href="'. esc_url($image_full_src[0]).'"></a>';
                        }
                    }
                endforeach;
            endif;
            echo json_encode($ajax_response);
            wp_die();
        }
        /**
         * Get total favorite
         * @return int
         */
        public function get_total_favorite()
        {
            $user_id = get_current_user_id();
            $my_favorites = get_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', true);
            if (empty($my_favorites)) {
                $my_favorites = array(0);
            }
            $args = array(
                'post_type' => 'property',
                'post__in' => $my_favorites
            );
            $favorites = new WP_Query($args);
            wp_reset_postdata();
            return $favorites->found_posts;
        }

        /**
         * Print Property
         */
        public function property_print_ajax()
        {
            if (!isset($_POST['property_id']) || !is_numeric($_POST['property_id'])) {
                return;
            }
            $property_id = absint(wp_unslash($_POST['property_id']));
            $isRTL = 'false';
            if (isset($_POST['isRTL'])) {
                $isRTL = $_POST['isRTL'];
            }
            ere_get_template('property/property-print.php', array('property_id' => $property_id, 'isRTL' => $isRTL));
            wp_die();
        }

        /**
         *    set_views_counter
         */
        public function set_views_counter()
        {
            global $post;
            // Is it a single post
            if (is_single() && (get_post_type() == 'property')) {
                // Check if user already visited this page
                $visited_posts = array();
                // Check cookie for list of visited posts
                if (isset($_COOKIE['property_views'])) {
                    // We expect list of comma separated post ids in the cookie
                    $visited_posts = array_map('intval', explode(',', $_COOKIE['property_views']));
                }
                if (in_array($post->ID, $visited_posts)) {
                    // User already visited this post
                    return;
                }
                // The visitor is reading this post first time, so we count
                // Get current view count
                $views = (int)get_post_meta($post->ID, ERE_METABOX_PREFIX . 'property_views_count', true);
                // Increase by one and save
                update_post_meta($post->ID, ERE_METABOX_PREFIX . 'property_views_count', $views + 1);
                // Add post id and set cookie
                $visited_posts[] = $post->ID;
                // Set cookie for one hour, it should be set on all pages se we use / as path
                setcookie('property_views', implode(',', $visited_posts), time() + 3600, '/');
            }
        }

        /**
         * get_total_views
         * @param null $post_id
         * @return int
         */
        public function get_total_views($post_id = null)
        {
            global $post;
            /**
             * If no given post id, then current post
             */
            if (!$post_id && isset($post->ID)) {
                $post_id = $post->ID;
            }
            if (!$post_id) {
                return 0;
            }
            $views = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_views_count', true);
            return intval($views);
        }

        public function get_states_by_country_ajax()
        {
            if (!isset($_POST['country'])) {
                return;
            }
            $country = wp_filter_kses(wp_unslash($_POST['country'])) ;
            $type = isset($_POST['type']) ?  ere_clean(wp_unslash($_POST['type'])) : '';
            if (!empty($country)) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'property-state',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'property_state_country',
                                'value' => $country,
                                'compare' => '=',
                            )
                        )
                    )
                );
            } else {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'property-state',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    )
                );
            }

            $html = '';
            if ($type == 0) {
                $html = '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
            }
            if (!empty($taxonomy_terms)) {
                if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                    }
                }
                else
                {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
                    }
                }
            }
            if ($type == 1) {
                $html .= '<option value="" selected="selected">' . esc_html__('All States', 'essential-real-estate') . '</option>';
            }
            echo wp_kses($html,array(
                'option' => array(
                 'value' => true,
                 'selected' => true
            )));
            wp_die();
        }

        public function get_cities_by_state_ajax()
        {
            if (!isset($_POST['state'])) {
                return;
            }
            $state = wp_filter_kses(wp_unslash($_POST['state']));
            $type = isset($_POST['type']) ? ere_clean(wp_unslash($_POST['type'])) : '';
            if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                $property_state = get_term_by('id', $state, 'property-state');
            }
            else{
                $property_state = get_term_by('slug', $state, 'property-state');
            }

            if (!empty($state) && $property_state) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'property-city',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'property_city_state',
                                'value' => $property_state->term_id,
                                'compare' => '=',
                            )
                        )
                    )
                );
            } else {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'property-city',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    )
                );
            }
            $html = '';
            if ($type == 0) {
                $html = '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
            }
            if (!empty($taxonomy_terms)) {
                if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                    }
                }
                else
                {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
                    }
                }
            }
            if ($type == 1) {
                $html .= '<option value="" selected="selected">' . esc_html__('All Cities', 'essential-real-estate') . '</option>';
            }
            echo wp_kses($html,array(
                'option' => array(
                    'value' => true,
                    'selected' => true
                )));
            wp_die();
        }

        public function get_neighborhoods_by_city_ajax()
        {
            if (!isset($_POST['city'])) {
                return;
            }
            $city = wp_filter_kses(wp_unslash($_POST['city']));
            $type = isset($_POST['type']) ? ere_clean(wp_unslash($_POST['type'])) : '';
            if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                $property_city = get_term_by('id', $city, 'property-city');
            }
            else{
                $property_city = get_term_by('slug', $city, 'property-city');
            }

            if (!empty($city) && $property_city) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'property-neighborhood',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'property_neighborhood_city',
                                'value' => $property_city->term_id,
                                'compare' => '=',
                            )
                        )
                    )
                );
            } else {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'property-neighborhood',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    )
                );
            }

            $html = '';
            if ($type == 0) {
                $html = '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
            }
            if (!empty($taxonomy_terms)) {
                if (isset($_POST['is_slug']) && ($_POST['is_slug']=='0')) {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                    }
                }
                else
                {
                    foreach ($taxonomy_terms as $term) {
                        $html .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
                    }
                }
            }
            if ($type == 1) {
                $html .= '<option value="" selected="selected">' . esc_html__('All Neighborhoods', 'essential-real-estate') . '</option>';
            }
            echo wp_kses($html,array(
                'option' => array(
                    'value' => true,
                    'selected' => true
                )));
            wp_die();
        }

        /**
         * submit review
         */
        public function submit_review_ajax()
        {
            check_ajax_referer('ere_submit_review_ajax_nonce', 'ere_security_submit_review');
            global $wpdb, $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $user = get_user_by('id', $user_id);
            $property_id = isset($_POST['property_id']) ? ere_clean(wp_unslash($_POST['property_id'])) : '';
            $rating_value = isset($_POST['rating']) ? ere_clean(wp_unslash($_POST['rating'])) : '';
            $my_review = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = %d AND comment.user_id = %d  AND meta.meta_key = 'property_rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC",$property_id,$user_id));
            $comment_approved = 1;
            $auto_publish_review_property = ere_get_option( 'review_property_approved_by_admin',0 );
            if ($auto_publish_review_property == 1) {
                $comment_approved = 0;
            }
            if ( $my_review === null ) {
                $data = Array();
                $user = $user->data;
                $data['comment_post_ID'] = $property_id;
                $data['comment_content'] = isset($_POST['message']) ?  wp_filter_post_kses($_POST['message']) : '';
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;
                $data['comment_author'] = $user->user_login;
                $data['comment_author_email'] = $user->user_email;
                $data['comment_author_url'] = $user->user_url;
                $data['user_id'] = $user_id;
                $comment_id = wp_insert_comment($data);

                add_comment_meta($comment_id, 'property_rating', $rating_value);
                if ($comment_approved == 1) {
	                do_action('ere_property_rating_meta',$property_id, $rating_value);
                }
            } else {
                $data = Array();
                $data['comment_ID'] = $my_review->comment_ID;
                $data['comment_post_ID'] = $property_id;
                $data['comment_content'] = isset($_POST['message']) ? wp_filter_post_kses($_POST['message']) : '';
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;

                wp_update_comment($data);
                update_comment_meta($my_review->comment_ID, 'property_rating', $rating_value, $my_review->meta_value);

                if ($comment_approved == 1) {
                    do_action('ere_property_rating_meta',$property_id, $rating_value, false, $my_review->meta_value);
                }
            }
            wp_send_json_success();
        }

        /**
         * @param $property_id
         * @param $rating_value
         * @param bool|true $comment_exist
         * @param int $old_rating_value
         */
        public function rating_meta_filter($property_id, $rating_value, $comment_exist = true, $old_rating_value = 0)
        {
            $property_rating = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_rating', true);
            if ($comment_exist == true) {
                if (is_array($property_rating) && isset($property_rating[$rating_value])) {
                    $property_rating[$rating_value]++;
                } else {
                    $property_rating = Array();
                    $property_rating[1] = 0;
                    $property_rating[2] = 0;
                    $property_rating[3] = 0;
                    $property_rating[4] = 0;
                    $property_rating[5] = 0;
                    $property_rating[$rating_value]++;
                }
            } else {
                $property_rating[$old_rating_value]--;
                $property_rating[$rating_value]++;
            }
            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_rating', $property_rating);
        }

        /**
         * delete review
         * @param $comment_id
         */
        public function delete_review($comment_id)
        {
            global $wpdb;
            $rating_value = get_comment_meta($comment_id, 'property_rating', true);
            if ($rating_value !== '') {
                $comments = $wpdb->get_row($wpdb->prepare("SELECT comment_post_ID as property_ID FROM $wpdb->comments WHERE comment_ID = %d",$comment_id));
                if ($comments !== null) {
	                $property_id = $comments->property_ID;
	                $property_rating = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_rating', true);
	                if (is_array($property_rating) && isset($property_rating[$rating_value])) {
		                $property_rating[$rating_value]--;
		                update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_rating', $property_rating);
	                }
                }

            }
        }

        /**
         * approve review
         * @param $new_status
         * @param $old_status
         * @param $comment
         */
        public function approve_review($new_status, $old_status, $comment)
        {
            if ($old_status != $new_status) {
                $rating_value = get_comment_meta($comment->comment_ID, 'property_rating', true);
                $property_rating = get_post_meta($comment->comment_post_ID, ERE_METABOX_PREFIX . 'property_rating', true);
				if (!is_array($property_rating)) {
					$property_rating = Array();
					$property_rating[1] = 0;
					$property_rating[2] = 0;
					$property_rating[3] = 0;
					$property_rating[4] = 0;
					$property_rating[5] = 0;
				}
                if (($rating_value !== '') && is_array($property_rating) && isset($property_rating[$rating_value])) {
	                if ($new_status == 'approved') {
		                $property_rating[$rating_value]++;
	                } else {
		                $property_rating[$rating_value]--;
	                }
	                if ($property_rating[$rating_value] < 0) {
		                $property_rating[$rating_value] = 0;
	                }
	                update_post_meta($comment->comment_post_ID, ERE_METABOX_PREFIX . 'property_rating', $property_rating);
                }
            }
        }
    }
}