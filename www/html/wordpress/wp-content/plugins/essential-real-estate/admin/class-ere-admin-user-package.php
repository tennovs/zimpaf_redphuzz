<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin_User_Package')) {
    /**
     * Class ERE_Admin_User_Package
     */
    class ERE_Admin_User_Package
    {
        /**
         * Register custom columns
         * @param $columns
         * @return array
         */
        public function register_custom_column_titles($columns)
        {
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['title'] =  esc_html__('Title', 'essential-real-estate');
            $columns['user_id'] =esc_html__('Buyer', 'essential-real-estate');
            $columns['package'] = esc_html__('Package', 'essential-real-estate');
            $columns['num_listings'] = esc_html__('Number Listings', 'essential-real-estate');
            $columns['num_featured'] = esc_html__('Number Featured', 'essential-real-estate');
            $columns['activate_date'] = esc_html__('Activate Date', 'essential-real-estate');
            $columns['expire_date'] = esc_html__('Expiry Date', 'essential-real-estate');
            $new_columns = array();
            $custom_order = array('cb', 'title', 'user_id','package','num_listings','num_featured','activate_date','expire_date');
            foreach ($custom_order as $colname){
                $new_columns[$colname] = $columns[$colname];
            }
            return $new_columns;
        }

        /**
         * Display custom column for agent package
         * @param $column
         */
        public function display_custom_column($column)
        {
            global $post;
            $postID = $post->ID;
            $package_user_id = get_post_meta($postID, ERE_METABOX_PREFIX . 'package_user_id', true);
            $package_id = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_id', true);
            $package_available_listings = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
            $package_featured_available_listings = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_number_featured', true);
            $package_activate_date = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_activate_date', true);
            $package_name = get_the_title($package_id);
            $user_info = get_userdata($package_user_id);
            $ere_package = new ERE_Package();
            $expired_date = $ere_package->get_expired_date($package_id, $package_user_id);
            switch ($column) {
                case 'user_id':
                    if($user_info)
                    {
                        echo esc_html($user_info->display_name);
                    }
                    break;
                case 'package':
                    echo esc_html($package_name);
                    break;

                case 'num_listings':
                    if($package_available_listings==-1)
                    {
                        esc_html_e('Unlimited','essential-real-estate');
                    }
                    else
                    {
                        echo esc_html($package_available_listings);
                    }

                    break;

                case 'num_featured':
                    echo esc_html($package_featured_available_listings);
                    break;

                case 'activate_date':
                    echo esc_html($package_activate_date);
                    break;

                case 'expire_date':
                    echo esc_html($expired_date);
                    break;
            }
        }
        /**
         * Modify agent package slug
         * @param $existing_slug
         * @return string
         */
        public function modify_user_package_slug($existing_slug)
        {
            $user_package_url_slug = ere_get_option('user_package_url_slug');
            if ($user_package_url_slug) {
                return $user_package_url_slug;
            }
            return $existing_slug;
        }

        /**
         * filter_restrict_manage_user_package
         */
        public function filter_restrict_manage_user_package() {
            global $typenow;
            $post_type = 'user_package';
            $package_user = isset($_GET['package_user'])? ere_clean(wp_unslash($_GET['package_user'])) :'';
            if ($typenow == $post_type){?>
                <input type="text" placeholder="<?php esc_attr_e('Buyer','essential-real-estate');?>" name="package_user" value="<?php echo esc_attr($package_user);?>">
            <?php }
        }

        /**
         * user_package_filter
         * @param $query
         */
        public function user_package_filter($query) {
            global $pagenow;
            $post_type = 'user_package';
            $q_vars    = &$query->query_vars;$filter_arr=array();
            if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type)
            {
                $package_user = isset($_GET['package_user']) ? ere_clean(wp_unslash($_GET['package_user'])) : '';
                if($package_user !== '')
                {
                    $user = get_user_by('login',$package_user);
                    $user_id=-1;
                    if($user)
                    {
                        $user_id=$user->ID;
                    }
                    $filter_arr[] = array(
                        'key' =>ERE_METABOX_PREFIX. 'package_user_id',
                        'value' =>  $user_id,
                        'compare' => 'IN',
                    );
                }
                if (! empty($filter_arr) ) {
                    $q_vars['meta_query'] = $filter_arr;
                }
            }
        }

        /**
         * @param $actions
         * @param $post
         * @return mixed
         */
        public function modify_list_row_actions( $actions, $post ) {
            // Check for your post type.
            if ( $post->post_type == 'user_package' ) {
                unset( $actions[ 'view' ] );
            }
            return $actions;
        }

	    public function delete_user_package($post_ID) {
		    $user_id = get_post_meta($post_ID,ERE_METABOX_PREFIX . 'package_user_id',true);
		    delete_post_meta($post_ID,ERE_METABOX_PREFIX . 'package_user_id');
		    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_listings');
		    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'package_number_featured');
		    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'package_activate_date');
		    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'package_id');
		    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'package_key');
		    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'free_package');
	    }
    }
}