<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Profile')) {
    /**
     * Class ERE_Profile
     */
    class ERE_Profile
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
         * Upload profile avatar
         */
        public function profile_image_upload_ajax()
        {
            // Verify Nonce
            $nonce = isset($_REQUEST['nonce']) ? ere_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'ere_allow_upload_nonce')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'essential-real-estate'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['ere_upload_file']; // WPCS: sanitization ok, input var ok.

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

                $ajax_response = array(
                    'success' => true,
                    'url' => $thumbnail_url,
                    'attachment_id' => $attach_id
                );

                echo json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!!', 'essential-real-estate'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Update profile
         */
        public function update_profile_ajax()
        {
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            check_ajax_referer('ere_update_profile_ajax_nonce', 'ere_security_update_profile');

            $user_firstname = $user_lastname = $user_des = $user_position = $user_email = $user_mobile_number = $user_fax_number = $user_company = $user_office_number = $user_office_address = $user_licenses=$user_facebook_url = $user_twitter_url =  $user_linkedin_url = $user_pinterest_url = $user_instagram_url = $user_skype = $user_youtube_url = $user_vimeo_url = $user_website_url = '';
            $profile_pic_id = '';

            // Update first name
            if (!empty($_POST['user_firstname'])) {
                $user_firstname = sanitize_text_field(wp_unslash($_POST['user_firstname']));
                update_user_meta($user_id, 'first_name', $user_firstname);
            } else {
                delete_user_meta($user_id, 'first_name');
            }

            // Update last name
            if (!empty($_POST['user_lastname'])) {
                $user_lastname = sanitize_text_field(wp_unslash($_POST['user_lastname']));
                update_user_meta($user_id, 'last_name', $user_lastname);
            } else {
                delete_user_meta($user_id, 'last_name');
            }

            // Update author_position
            if (!empty($_POST['user_position'])) {
                $user_position = sanitize_text_field(wp_unslash($_POST['user_position']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_position', $user_position);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_position');
            }
            // Update author_fax_number
            if (!empty($_POST['user_fax_number'])) {
                $user_fax_number = sanitize_text_field(wp_unslash($_POST['user_fax_number']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_fax_number', $user_fax_number);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_fax_number');
            }
            // Update author_company
            if (!empty($_POST['user_company'])) {
                $user_company = sanitize_text_field(wp_unslash($_POST['user_company']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_company', $user_company);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_company');
            }
            // Update author_company
            if (!empty($_POST['user_licenses'])) {
                $user_licenses = sanitize_text_field(wp_unslash($_POST['user_licenses']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_licenses', $user_licenses);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_licenses');
            }
            if (!empty($_POST['user_office_address'])) {
                $user_office_address = sanitize_text_field(wp_unslash($_POST['user_office_address']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_address', $user_office_address);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_address');
            }

            // Update Phone
            if (!empty($_POST['user_office_number'])) {
                $user_office_number = sanitize_text_field(wp_unslash($_POST['user_office_number']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_number', $user_office_number);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_number');
            }

            // Update Mobile
            if (!empty($_POST['user_mobile_number'])) {
                $user_mobile_number = sanitize_text_field(wp_unslash($_POST['user_mobile_number']));
                if ( 0 < strlen( trim( preg_replace( '/[\s\#0-9_\-\+\/\(\)\.]/', '', $user_mobile_number ) ) ) ) {
                    echo json_encode(array('success' => false, 'message' => esc_html__('The Mobile phone number you entered is not valid. Please try again.', 'essential-real-estate')));
                    wp_die();
                }
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_mobile_number', $user_mobile_number);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_mobile_number');
            }

            // Update Skype
            if (!empty($_POST['user_skype'])) {
                $user_skype = sanitize_text_field(wp_unslash($_POST['user_skype']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_skype', $user_skype);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_skype');
            }

            // Update facebook
            if (!empty($_POST['user_facebook_url'])) {
                $user_facebook_url = esc_url_raw(wp_unslash($_POST['user_facebook_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_facebook_url', $user_facebook_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_facebook_url');
            }

            // Update twitter
            if (!empty($_POST['user_twitter_url'])) {
                $user_twitter_url = esc_url_raw(wp_unslash($_POST['user_twitter_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_twitter_url', $user_twitter_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_twitter_url');
            }

            // Update linkedin
            if (!empty($_POST['user_linkedin_url'])) {
                $user_linkedin_url = esc_url_raw(wp_unslash($_POST['user_linkedin_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_linkedin_url', $user_linkedin_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_linkedin_url');
            }

            // Update instagram
            if (!empty($_POST['user_instagram_url'])) {
                $user_instagram_url = esc_url_raw(wp_unslash($_POST['user_instagram_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_instagram_url', $user_instagram_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_instagram_url');
            }

            // Update pinterest
            if (!empty($_POST['user_pinterest_url'])) {
                $user_pinterest_url = esc_url_raw(wp_unslash($_POST['user_pinterest_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_pinterest_url', $user_pinterest_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_pinterest_url');
            }

            // Update youtube
            if (!empty($_POST['user_youtube_url'])) {
                $user_youtube_url = esc_url_raw(wp_unslash($_POST['user_youtube_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_youtube_url', $user_youtube_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_youtube_url');
            }

            // Update vimeo
            if (!empty($_POST['user_vimeo_url'])) {
                $user_vimeo_url = esc_url_raw(wp_unslash($_POST['user_vimeo_url']));
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_vimeo_url', $user_vimeo_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_vimeo_url');
            }


            // Update Profile Picture
            if (!empty($_POST['profile_pic'])) {
                $profile_pic_id = sanitize_text_field($_POST['profile_pic']);
                $profile_pic = wp_get_attachment_url($profile_pic_id);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_custom_picture', $profile_pic);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_picture_id', $profile_pic_id);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_custom_picture');
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_picture_id');
            }
            // Update About
            if (!empty($_POST['user_des'])) {
                $user_des = wp_filter_post_kses($_POST['user_des']);
                wp_update_user(array('ID' => $user_id, 'description' => $user_des));
            } else {
                $user_des = '';
                wp_update_user(array('ID' => $user_id, 'description' => $user_des));
            }
            // Update website
            if (!empty($_POST['user_website_url'])) {
                $user_website_url = esc_url_raw(wp_unslash($_POST['user_website_url']));
                wp_update_user(array('ID' => $user_id, 'user_url' => $user_website_url));
            } else {
                $user_website_url = '';
                wp_update_user(array('ID' => $user_id, 'user_url' => $user_website_url));
            }
            // Update email
            if (!empty($_POST['user_email'])) {
                $user_email = sanitize_email(wp_unslash($_POST['user_email']));
                $user_email = is_email($user_email);
                if (!$user_email) {
                    echo json_encode(array('success' => false, 'message' => esc_html__('The Email you entered is not valid. Please try again.', 'essential-real-estate')));
                    wp_die();
                } else {
                    $email_exists = email_exists($user_email);
                    if ($email_exists) {
                        if ($email_exists != $user_id) {
                            echo json_encode(array('success' => false, 'message' => esc_html__('This Email is already used by another user. Please try a different one.', 'essential-real-estate')));
                            wp_die();
                        }
                    } else {
                        $return = wp_update_user(array('ID' => $user_id, 'user_email' => $user_email));
                        if (is_wp_error($return)) {
                            $error = $return->get_error_message();
                            echo esc_html($error);
                            wp_die();
                        }
                    }
                }
            }
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            $user_as_agent = ere_get_option('user_as_agent', 1);
            if ($user_as_agent == 1 && !empty($agent_id) && (get_post_type($agent_id) == 'agent')) {
                if (!empty($user_firstname) || !empty($user_lastname)) {
                    wp_update_post(array(
                        'ID' => $agent_id,
                        'post_title' => $user_firstname . ' ' . $user_lastname,
                        'post_content' => $user_des
                    ));
                }
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_description', $user_des);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_position', $user_position);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_email', $user_email);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_mobile_number', $user_mobile_number);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_fax_number', $user_fax_number);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_company', $user_company);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_number', $user_office_number);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_address', $user_office_address);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_licenses', $user_licenses);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_facebook_url', $user_facebook_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_twitter_url', $user_twitter_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_linkedin_url', $user_linkedin_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_pinterest_url', $user_pinterest_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_instagram_url', $user_instagram_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_skype', $user_skype);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_youtube_url', $user_youtube_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_vimeo_url', $user_vimeo_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_website_url', $user_website_url);
                update_post_meta($agent_id, '_thumbnail_id', $profile_pic_id);
            }
            echo json_encode(array('success' => true, 'message' => esc_html__('Profile updated', 'essential-real-estate')));
            wp_die();
        }

        /**
         * Register user as seller
         */
        public function leave_agent_ajax()
        {
            check_ajax_referer('ere_leave_agent_ajax_nonce', 'ere_security_leave_agent');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            if (!empty($agent_id) && (get_post_type($agent_id) == 'agent')) {
                wp_delete_post($agent_id);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_agent_id', '');
            }
            $ajax_response = array('success' => true, 'message' => esc_html__('Success!', 'essential-real-estate'));
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Register user as seller
         */
        public function register_user_as_agent_ajax()
        {
            check_ajax_referer('ere_become_agent_ajax_nonce', 'ere_security_become_agent');
            $user_as_agent = ere_get_option('user_as_agent', 1);
            if ($user_as_agent == 1) {
                global $current_user;
                wp_get_current_user();
                $user_id = $current_user->ID;
                $full_name = $current_user->user_login;
                $agent_firstname = $current_user->first_name;
                $agent_lastname = $current_user->last_name;
                $agent_description = $current_user->description;
                if (!empty($agent_firstname) || !empty($agent_lastname)) {
                    $full_name = $agent_firstname . ' ' . $agent_lastname;
                }
                $post_status = 'publish';
                $auto_approved_agent = ere_get_option('auto_approved_agent', 1);
                if ($auto_approved_agent != 1) {
                    $post_status = 'pending';
                }
                //Insert Agent
                $agent_id = wp_insert_post(array(
                    'post_title' => $full_name,
                    'post_type' => 'agent',
                    'post_status' => $post_status,
                    'post_content' => $agent_description
                ));
                if ($agent_id > 0) {
                    if ($auto_approved_agent != 1) {
                        $args = array(
                            'agent_name' => $full_name,
                            'agent_url' => get_permalink($agent_id)
                        );
                        $admin_email = get_bloginfo('admin_email');
                        ere_send_email($admin_email, 'admin_mail_approved_agent', $args);
                    }
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_agent_id', $agent_id);
                    $agent_email = $current_user->user_email;
                    $agent_website_url = $current_user->user_url;
                    $agent_mobile_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $user_id);
                    $agent_fax_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_fax_number', $user_id);
                    $agent_company = get_the_author_meta(ERE_METABOX_PREFIX . 'author_company', $user_id);
                    $agent_licenses = get_the_author_meta(ERE_METABOX_PREFIX . 'author_licenses', $user_id);
                    $agent_office_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_number', $user_id);
                    $agent_office_address = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_address', $user_id);
                    $agent_facebook_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_facebook_url', $user_id);
                    $agent_twitter_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_twitter_url', $user_id);
                    $agent_linkedin_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_linkedin_url', $user_id);
                    $agent_pinterest_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_pinterest_url', $user_id);
                    $agent_instagram_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_instagram_url', $user_id);
                    $agent_youtube_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_youtube_url', $user_id);
                    $agent_vimeo_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_vimeo_url', $user_id);
                    $agent_skype = get_the_author_meta(ERE_METABOX_PREFIX . 'author_skype', $user_id);
                    $agent_position = get_the_author_meta(ERE_METABOX_PREFIX . 'author_position', $user_id);
                    $author_picture_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_picture_id', $user_id);

                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_user_id', $user_id);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_description', $agent_description);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_position', $agent_position);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_email', $agent_email);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_mobile_number', $agent_mobile_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_fax_number', $agent_fax_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_company', $agent_company);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_licenses', $agent_licenses);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_number', $agent_office_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_address', $agent_office_address);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_facebook_url', $agent_facebook_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_twitter_url', $agent_twitter_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_linkedin_url', $agent_linkedin_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_pinterest_url', $agent_pinterest_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_instagram_url', $agent_instagram_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_skype', $agent_skype);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_youtube_url', $agent_youtube_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_vimeo_url', $agent_vimeo_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_website_url', $agent_website_url);
                    update_post_meta($agent_id, '_thumbnail_id', $author_picture_id);
                    if ($auto_approved_agent != 1) {
                        $ajax_response = array('success' => true, 'message' => esc_html__('You have successfully registered and is pending approval by an admin!', 'essential-real-estate'));
                    } else {
                        $ajax_response = array('success' => true, 'message' => esc_html__('You have successfully registered!', 'essential-real-estate'));
                    }
                } else {
                    $ajax_response = array('success' => true, 'message' => esc_html__('Failed!', 'essential-real-estate'));
                }
            } else {
                $ajax_response = array('success' => false, 'message' => esc_html__('Failed!', 'essential-real-estate'));
            }
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Change password
         */
        public function change_password_ajax()
        {
            check_ajax_referer('ere_change_password_ajax_nonce', 'ere_security_change_password');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $allowed_html = array();

            $oldpass = isset($_POST['oldpass']) ? ere_clean(wp_unslash($_POST['oldpass'])) : '';
            $newpass = isset($_POST['newpass']) ? ere_clean(wp_unslash($_POST['newpass'])) : '';
            $confirmpass = isset($_POST['confirmpass']) ? ere_clean(wp_slash($_POST['confirmpass'])) : '';

            if ($newpass == '' || $confirmpass == '') {
                echo json_encode(array('success' => false, 'message' => esc_html__('New password or confirm password is blank', 'essential-real-estate')));
                wp_die();
            }
            if ($newpass != $confirmpass) {
                echo json_encode(array('success' => false, 'message' => esc_html__('Passwords do not match', 'essential-real-estate')));
                wp_die();
            }

            $user = get_user_by('id', $user_id);
            if ($user && wp_check_password($oldpass, $user->data->user_pass, $user_id)) {
                wp_set_password($newpass, $user_id);
                echo json_encode(array('success' => true, 'message' => esc_html__('Password Updated', 'essential-real-estate')));
            } else {
                echo json_encode(array('success' => false, 'message' => esc_html__('Old password is not correct', 'essential-real-estate')));
            }
            wp_die();
        }

        public function profile_update($user_id)
        {
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            if (!empty($agent_id)) {
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_user_id', $user_id);
            }
        }

        /**
         * Check package available
         * @param $user_id
         * @return int
         */
        public function user_package_available($user_id)
        {
            $package_id = get_the_author_meta(ERE_METABOX_PREFIX . 'package_id', $user_id);
            if (empty($package_id)) {
                return 0;
            } else {
                $ere_package = new ERE_Package();
                $package_unlimited_time = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_time', true);
                if ($package_unlimited_time == 0) {
                    $expired_date = $ere_package->get_expired_time($package_id, $user_id);
                    $today = time();
                    if ($today > $expired_date) {
                        return -1;
                    }
                }
                $package_num_properties = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_listings', $user_id);
                if ($package_num_properties != -1 && $package_num_properties < 1) {
                    return -2;
                }
            }
            return 1;
        }

        public function custom_user_profile_fields($user)
        {
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user->ID);
            $is_agent=(!empty($agent_id) && (get_post_type($agent_id) == 'agent'));
            $picture_url=get_the_author_meta(ERE_METABOX_PREFIX . 'author_custom_picture', $user->ID);
            if(empty($picture_url)&& $is_agent)
            {
                $picture_url=get_the_post_thumbnail_url($agent_id);
            }
            ?>
            <h3><?php esc_html_e('Profile Info', 'essential-real-estate'); ?></h3>
            <table class="form-table">
                <tbody>
                <tr class="author-custom-picture-wrap">
                    <th><label><?php echo esc_html__('Profile Picture', 'essential-real-estate'); ?></label></th>
                    <td>
                        <img width="96px"
                             src="<?php echo esc_url($picture_url); ?>">
                    </td>
                </tr>
                <tr class="author-mobile-number-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_mobile_number');?>"><?php echo esc_html__('Mobile', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_mobile_number');?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_mobile_number'); ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-fax-number-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_fax_number'); ?>"><?php echo esc_html__('Fax Number', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_fax_number'); ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_fax_number'); ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_fax_number', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-skype-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_skype'); ?>"><?php echo esc_html__('Skype', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_skype'); ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_skype') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_skype', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <?php
                if ($is_agent):?>
                    <tr class="author-company-wrap">
                        <th><label
                                for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_company'); ?>"><?php echo esc_html__('Company Name', 'essential-real-estate'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_company'); ?>"
                                   id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_company') ; ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_company', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author_position-wrap">
                        <th><label
                                for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_position'); ?>"><?php esc_html_e('Position', 'essential-real-estate'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_position'); ?>"
                                   id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_position'); ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_position', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author-office-address-wrap">
                        <th><label
                                for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_office_address'); ?>"><?php echo esc_html__('Office Address', 'essential-real-estate'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_office_address') ; ?>"
                                   id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_office_address'); ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_address', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author-office-number-wrap">
                        <th><label
                                for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_office_number') ; ?>"><?php echo esc_html__('Office Number', 'essential-real-estate'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_office_number'); ?>"
                                   id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_office_number'); ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_number', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr class="author-licenses-wrap">
                        <th><label
                                for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_licenses') ; ?>"><?php echo esc_html__('Licenses', 'essential-real-estate'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_licenses') ; ?>"
                                   id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_licenses') ; ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_licenses', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                <?php endif; ?>
                    <tr class="author-agent-id-wrap">
                        <th><label
                                for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_agent_id') ; ?>"><?php echo esc_html__('Agent Id', 'essential-real-estate'); ?></label>
                        </th>
                        <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_agent_id') ; ?>"
                                   id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_agent_id') ; ?>"
                                   value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user->ID)); ?>"
                                   class="regular-text"></td>
                    </tr>
                </tbody>
            </table>
            <?php
            $paid_submission_type = ere_get_option('paid_submission_type', 'no');
            if ($paid_submission_type == 'per_package'):
                $package_id = get_the_author_meta(ERE_METABOX_PREFIX . 'package_id', $user->ID);
                if ($package_id > 0):
                    $package_remaining_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_listings', $user->ID);
                    $package_featured_remaining_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_featured', $user->ID);
                    if ($package_remaining_listings == -1) {
                        $package_remaining_listings = esc_html__('Unlimited', 'essential-real-estate');
                    }
                    $package_title = get_the_title($package_id);
                    $package_listings = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
                    $package_unlimited_listing = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_listing', true);
                    $package_featured_listings = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_number_featured', true);
                    $ere_package = new ERE_Package();
                    $expired_date = $ere_package->get_expired_date($package_id, $user->ID);
                    ?>
                    <h2><?php echo esc_html__('Package Info', 'essential-real-estate'); ?></h2>
                    <table class="form-table">
                        <tbody>
                        <tr class="user-package-id-wrap">
                            <th><label><?php echo esc_html__('Package Id', 'essential-real-estate'); ?></label></th>
                            <td><?php echo esc_html($package_id); ?></td>
                        </tr>
                        <tr class="user-package-name-wrap">
                            <th><label><?php echo esc_html__('Package Name', 'essential-real-estate'); ?></label></th>
                            <td><?php echo esc_html($package_title); ?></td>
                        </tr>
                        <tr class="user-package-remaining-listings-wrap">
                            <th><label><?php echo esc_html__('Listings Included', 'essential-real-estate'); ?></label>
                            </th>
                            <td><?php if ($package_unlimited_listing == 1) {
                                    echo wp_kses_post($package_remaining_listings);
                                } else {
                                    echo esc_html($package_listings);
                                }
                                ?></td>
                        </tr>
                        <tr class="user-package-remaining-listings-wrap">
                            <th><label><?php echo esc_html__('Listings Remaining', 'essential-real-estate'); ?></label>
                            </th>
                            <td><?php echo esc_html($package_remaining_listings); ?></td>
                        </tr>
                        <tr class="user-package-featured-wrap">
                            <th><label><?php echo esc_html__('Featured Included', 'essential-real-estate'); ?></label>
                            </th>
                            <td><?php echo esc_html($package_featured_listings); ?></td>
                        </tr>
                        <tr class="user-package-remaining-wrap">
                            <th><label><?php echo esc_html__('Featured Remaining', 'essential-real-estate'); ?></label>
                            </th>
                            <td><?php echo esc_html($package_featured_remaining_listings); ?></td>
                        </tr>
                        <tr class="user-package-end-date-wrap">
                            <th><label><?php echo esc_html__('End Date', 'essential-real-estate'); ?></label></th>
                            <td><?php echo esc_html($expired_date); ?></td>
                        </tr>
                        </tbody>
                    </table>
                <?php endif;
            endif; ?>
            <h2><?php echo esc_html__('Social Profiles', 'essential-real-estate'); ?></h2>
            <table class="form-table">
                <tbody>
                <tr class="author-facebook-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_facebook_url') ; ?>"><?php echo esc_html__('Facebook', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_facebook_url') ; ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_facebook_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_facebook_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-twitter-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_twitter_url') ; ?>"><?php echo esc_html__('Twitter', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_twitter_url') ; ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_twitter_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_twitter_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-linkedin-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_linkedin_url') ; ?>"><?php echo esc_html__('LinkedIn', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_linkedin_url') ; ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_linkedin_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_linkedin_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-pinterest-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_pinterest_url'); ?>"><?php echo esc_html__('Pinterest', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_pinterest_url') ; ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_pinterest_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_pinterest_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-instagram-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_instagram_url') ; ?>"><?php echo esc_html__('Instagram', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_instagram_url') ; ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_instagram_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_instagram_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-youtube-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_youtube_url') ; ?>"><?php echo esc_html__('Youtube', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_youtube_url'); ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_youtube_url'); ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_youtube_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                <tr class="author-vimeo-url-wrap">
                    <th><label
                            for="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_vimeo_url'); ?>"><?php echo esc_html__('Vimeo', 'essential-real-estate'); ?></label>
                    </th>
                    <td><input type="text" name="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_vimeo_url') ; ?>"
                               id="<?php echo esc_attr(ERE_METABOX_PREFIX . 'author_vimeo_url') ; ?>"
                               value="<?php echo esc_attr(get_the_author_meta(ERE_METABOX_PREFIX . 'author_vimeo_url', $user->ID)); ?>"
                               class="regular-text"></td>
                </tr>
                </tbody>
            </table>
            <?php
        }

        public function update_custom_user_profile_fields($user_id)
        {
            if (current_user_can('edit_user', $user_id)) {

                $author_mobile_number = isset($_POST[ERE_METABOX_PREFIX . 'author_mobile_number']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_mobile_number'])) : '';
                $author_fax_number = isset($_POST[ERE_METABOX_PREFIX . 'author_fax_number']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_fax_number'])) : '';
                $author_skype = isset($_POST[ERE_METABOX_PREFIX . 'author_skype']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_skype'])) : '';
                $author_facebook_url = isset($_POST[ERE_METABOX_PREFIX . 'author_facebook_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_facebook_url'])) : '';
                $author_twitter_url = isset($_POST[ERE_METABOX_PREFIX . 'author_twitter_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_twitter_url'])) : '';
                $author_linkedin_url = isset($_POST[ERE_METABOX_PREFIX . 'author_linkedin_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_linkedin_url'])) : '';
                $author_pinterest_url = isset($_POST[ERE_METABOX_PREFIX . 'author_pinterest_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_pinterest_url'])) : '';
                $author_instagram_url = isset($_POST[ERE_METABOX_PREFIX . 'author_instagram_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_instagram_url'])) : '';
                $author_youtube_url = isset($_POST[ERE_METABOX_PREFIX . 'author_youtube_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_youtube_url'])) : '';
                $author_vimeo_url = isset($_POST[ERE_METABOX_PREFIX . 'author_vimeo_url']) ? esc_url_raw(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_vimeo_url'])) : '';


                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_mobile_number', $author_mobile_number);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_fax_number', $author_fax_number);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_skype', $author_skype);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_facebook_url', $author_facebook_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_twitter_url', $author_twitter_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_linkedin_url', $author_linkedin_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_pinterest_url', $author_pinterest_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_instagram_url', $author_instagram_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_youtube_url', $author_youtube_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_vimeo_url', $author_vimeo_url);
                $agent_id = isset($_POST[ERE_METABOX_PREFIX . 'author_agent_id']) ? absint(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_agent_id'])) : 0;
                if ($agent_id > 0 && get_post_type($agent_id) == 'agent') {

                    $author_company = isset($_POST[ERE_METABOX_PREFIX . 'author_company']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_company'])) : '';
                    $author_position = isset($_POST[ERE_METABOX_PREFIX . 'author_position']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_position'])) : '';
                    $author_office_address = isset($_POST[ERE_METABOX_PREFIX . 'author_office_address']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_office_address'])) : '';
                    $author_office_number = isset($_POST[ERE_METABOX_PREFIX . 'author_office_number']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_office_number'])) : '';
                    $author_licenses = isset($_POST[ERE_METABOX_PREFIX . 'author_licenses']) ? ere_clean(wp_unslash($_POST[ERE_METABOX_PREFIX . 'author_licenses'])) : '';
                    $description = isset($_POST['description']) ? wp_filter_post_kses($_POST['description']) : '';
                    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
                    $agent_website_url = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';


                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_agent_id', $agent_id);
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_company', $author_company);
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_position', $author_position);
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_address', $author_office_address);
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_number', $author_office_number);
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_licenses', $author_licenses);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_description', $description);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_email', $email);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_website_url', $agent_website_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_position', $author_position);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_mobile_number', $author_mobile_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_fax_number', $author_fax_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_company', $author_company);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_number', $author_office_address);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_address', $author_office_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_licenses', $author_licenses);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_skype', $author_skype);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_facebook_url', $author_facebook_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_twitter_url', $author_twitter_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_linkedin_url', $author_linkedin_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_pinterest_url', $author_pinterest_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_instagram_url', $author_instagram_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_youtube_url', $author_youtube_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_vimeo_url', $author_vimeo_url);
                }
            }
        }
    }
}