<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin_Property')) {
    /**
     * Class ERE_Admin_Property
     */
    class ERE_Admin_Property
    {
        /**
         * Register custom columns
         * @param $columns
         * @return array
         */
        public function register_custom_column_titles($columns)
        {
            unset($columns['tags']);
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['thumb'] = esc_html__('Image', 'essential-real-estate');
            $columns['title'] = esc_html__('Property Title', 'essential-real-estate');
            $columns['type'] =  esc_html__('Type', 'essential-real-estate');
            $columns['status'] =esc_html__('Status', 'essential-real-estate');
            $columns['price'] = esc_html__('Price', 'essential-real-estate');
            $columns['featured'] = '<span data-tip="'.  esc_html__('Featured?', 'essential-real-estate') .'" class="tips dashicons dashicons-star-filled"></span>';
            $columns['author'] = esc_html__('Author', 'essential-real-estate');
            $columns['viewcount'] = esc_html__('View Count', 'essential-real-estate');
            $new_columns = array();
            $custom_order = array('cb','thumb', 'title', 'type','status','price','featured','author','viewcount','date');
            foreach ($custom_order as $colname){
                $new_columns[$colname] = $columns[$colname];
            }
            return $new_columns;
        }
        /**
         * Display custom column for properties
         * @param $column
         */
        public function display_custom_column($column)
        {
            global $post;
            switch ($column) {
                case 'thumb':
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail', array(
                            'class' => 'attachment-thumbnail attachment-thumbnail-small',
                        ));
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'type':
                    echo ere_admin_taxonomy_terms($post->ID, 'property-type', 'property');
                    break;
                case 'status':
                    echo ere_admin_taxonomy_terms($post->ID, 'property-status', 'property');
                    break;
                case 'price':
                    $price = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'property_price', true);
                    if (!empty($price)) {
                        echo esc_html($price);
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'featured':

	                $featured = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'property_featured', true);
	                if ($featured == 1) {
		                $featured_text = esc_html__('Featured', 'essential-real-estate');
		                $featured_icon = 'tips accent-color dashicons dashicons-star-filled';
	                } else {
		                $featured_text = esc_html__('Not Feature', 'essential-real-estate');
		                $featured_icon = 'tips dashicons dashicons-star-empty';
	                }

                	$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=ere_admin_feature_property&id=' . $post->ID ), 'dip-feature-property' );
	                echo '<a href="' . esc_url( $url ) . '"><i data-tip="' . $featured_text . '" class="' . esc_attr( $featured_icon ) . '"></i></a>';

                    break;
                case 'author' :
                    echo '<a href="' . esc_url(add_query_arg('author', $post->post_author)) . '">' . get_the_author() . '</a>';
                    break;
                case 'viewcount' :
                    $views = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'property_views_count', true);
                    echo  ere_get_format_number($views);
                    break;
            }
        }

        /**
         * @param $actions
         * @param $post
         * @return mixed
         */
        public function modify_list_row_actions( $actions, $post ) {
            // Check for your post type.
            if ( $post->post_type == 'property' ) {
                if (in_array($post->post_status, array('pending','expired')) && current_user_can('publish_propertys', $post->ID)) {
                    $actions['property-approve']='<a href="'.wp_nonce_url(add_query_arg('approve_listing', $post->ID), 'approve_listing').'">'.esc_html__('Approve', 'essential-real-estate').'</a>';
                }
                if (in_array($post->post_status, array('publish', 'pending')) && current_user_can('publish_propertys', $post->ID)) {
                    $actions['property-expired']='<a href="'.wp_nonce_url(add_query_arg('expire_listing', $post->ID), 'expire_listing').'">'.esc_html__('Expire', 'essential-real-estate').'</a>';
                }
                if (in_array($post->post_status, array('publish')) && current_user_can('publish_propertys', $post->ID)) {
                    $actions['property-hidden']='<a href="'.wp_nonce_url(add_query_arg('hidden_listing', $post->ID), 'hidden_listing').'">'.esc_html__('Hide', 'essential-real-estate').'</a>';
                }
                if (in_array($post->post_status, array('hidden')) && current_user_can('publish_propertys', $post->ID)) {
                    $actions['property-show']='<a href="'.wp_nonce_url(add_query_arg('show_listing', $post->ID), 'show_listing').'">'.esc_html__('Show', 'essential-real-estate').'</a>';
                }
            }
            return $actions;
        }
        /**
         * sortable_columns
         * @param $columns
         * @return mixed
         */
        public function sortable_columns($columns)
        {
            $columns['price'] = 'price';
            $columns['featured'] = 'featured';
            $columns['author'] = 'author';
            $columns['post_date'] = 'post_date';
            return $columns;
        }

        /**
         * @param $vars
         * @return array
         */
        public function column_orderby($vars) {
            if ( !is_admin() )
                return $vars;
            if ( isset($vars['orderby']) && 'price' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => ERE_METABOX_PREFIX. 'property_price',
                    'orderby' => 'meta_value_num',
                ));
            }
            if ( isset($vars['orderby']) && 'featured' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => ERE_METABOX_PREFIX. 'property_featured',
                    'orderby' => 'meta_value_num',
                ));
            }
            return $vars;
        }
        /**
         * Modify property slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_slug($existing_slug)
        {
            $property_url_slug = ere_get_option('property_url_slug');
            if ($property_url_slug) {
                return $property_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property type slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_type_slug($existing_slug)
        {
            $property_type_url_slug = ere_get_option('property_type_url_slug');
            if ($property_type_url_slug) {
                return $property_type_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property status slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_status_slug($existing_slug)
        {
            $property_status_url_slug = ere_get_option('property_status_url_slug');
            if ($property_status_url_slug) {
                return $property_status_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property feature slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_feature_slug($existing_slug)
        {
            $property_feature_url_slug = ere_get_option('property_feature_url_slug');
            if ($property_feature_url_slug) {
                return $property_feature_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property city slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_city_slug($existing_slug)
        {
            $property_city_url_slug = ere_get_option('property_city_url_slug');
            if ($property_city_url_slug) {
                return $property_city_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property neighborhood slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_neighborhood_slug($existing_slug)
        {
            $property_neighborhood_url_slug = ere_get_option('property_neighborhood_url_slug');
            if ($property_neighborhood_url_slug) {
                return $property_neighborhood_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property state slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_state_slug($existing_slug)
        {
            $property_state_url_slug = ere_get_option('property_state_url_slug');
            if ($property_state_url_slug) {
                return $property_state_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Modify property lable slug
         * @param $existing_slug
         * @return string
         */
        public function modify_property_label_slug($existing_slug)
        {
            $property_label_url_slug = ere_get_option('property_label_url_slug');
            if ($property_label_url_slug) {
                return $property_label_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Approve_property
         */
        public function approve_property()
        {
            if (!empty($_GET['approve_listing']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'approve_listing') && current_user_can('publish_post', $_GET['approve_listing'])) {
                $post_id = absint($_GET['approve_listing']);
                $listing_data = array(
                    'ID' => $post_id,
                    'post_status' => 'publish'
                );
                wp_update_post($listing_data);

                $author_id = get_post_field('post_author', $post_id);
                $user = get_user_by('id', $author_id);
                $user_email = $user->user_email;

                $args = array(
                    'listing_title' => get_the_title($post_id),
                    'listing_url' => get_permalink($post_id)
                );
                ere_send_email($user_email, 'mail_approved_listing', $args);
                wp_redirect(remove_query_arg('approve_listing', add_query_arg('approve_listing', $post_id, admin_url('edit.php?post_type=property'))));
                exit;
            }
        }

        /**
         * Expire property
         */
        public function expire_property()
        {
            if (!empty($_GET['expire_listing']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'expire_listing') && current_user_can('publish_post', $_GET['expire_listing'])) {
                $post_id = absint($_GET['expire_listing']);
                $listing_data = array(
                    'ID' => $post_id,
                    'post_status' => 'expired'
                );
                wp_update_post($listing_data);

                $author_id = get_post_field('post_author', $post_id);
                $user = get_user_by('id', $author_id);
                $user_email = $user->user_email;

                $args = array(
                    'listing_title' => get_the_title($post_id),
                    'listing_url' => get_permalink($post_id)
                );
                ere_send_email($user_email, 'mail_expired_listing', $args);

                wp_redirect(remove_query_arg('expire_listing', add_query_arg('expire_listing', $post_id, admin_url('edit.php?post_type=property'))));
                exit;
            }
        }
        /**
         * Hidden property
         */
        public function hidden_property()
        {
            if (!empty($_GET['hidden_listing']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'hidden_listing') && current_user_can('publish_post', $_GET['hidden_listing'])) {
                $post_id = absint($_GET['hidden_listing']);
                $listing_data = array(
                    'ID' => $post_id,
                    'post_status' => 'hidden'
                );
                wp_update_post($listing_data);
                wp_redirect(remove_query_arg('hidden_listing', add_query_arg('hidden_listing', $post_id, admin_url('edit.php?post_type=property'))));
                exit;
            }
        }
        /**
         * Show property
         */
        public function show_property()
        {
            if (!empty($_GET['show_listing']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'show_listing') && current_user_can('publish_post', $_GET['show_listing'])) {
                $post_id = absint($_GET['show_listing']);
                $listing_data = array(
                    'ID' => $post_id,
                    'post_status' => 'publish'
                );
                wp_update_post($listing_data);
                wp_redirect(remove_query_arg('show_listing', add_query_arg('show_listing', $post_id, admin_url('edit.php?post_type=property'))));
                exit;
            }
        }
        /**
         * filter_restrict_manage_property
         */
        public function filter_restrict_manage_property() {
            global $typenow;
            $post_type = 'property';
            $property_author = isset($_GET['property_author'])? $_GET['property_author']:'';
            $property_identity = isset($_GET['property_identity'])? $_GET['property_identity']:'';
            if ($typenow == $post_type) {
                $taxonomy_arr  = array('property-status','property-type');
                foreach($taxonomy_arr as $taxonomy){
                    $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                    $info_taxonomy = get_taxonomy($taxonomy);
                    wp_dropdown_categories(array(
                        'show_option_all' => sprintf(esc_html__('All %s','essential-real-estate'),esc_html($info_taxonomy->label)),
                        'taxonomy'        => $taxonomy,
                        'name'            => $taxonomy,
                        'orderby'         => 'name',
                        'selected'        => $selected,
                        'show_count'      => true,
                        'hide_empty'      => false,
                    ));
                }
                ?>
                <input type="text" placeholder="<?php esc_attr_e('Author','essential-real-estate');?>" name="property_author" value="<?php echo esc_attr($property_author);?>">
                <input type="text" placeholder="<?php esc_attr_e('Property ID','essential-real-estate');?>" name="property_identity" value="<?php echo esc_attr($property_identity);?>">
                <?php
            };
        }

        /**
         * property_filter
         * @param $query
         */
        public function property_filter($query) {
            global $pagenow;
            $post_type = 'property';
            $q_vars    = &$query->query_vars;
            if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type)
            {
                $taxonomy_arr  = array('property-status','property-type');
                foreach($taxonomy_arr as $taxonomy) {
                    if (isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
                        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
                        $q_vars[$taxonomy] = $term->slug;
                    }
                }
                if(isset($_GET['property_author']) && $_GET['property_author'] != '')
                {
                    $q_vars['author_name'] = ere_clean(wp_unslash($_GET['property_author']));
                }
                if(isset($_GET['property_identity']) && $_GET['property_identity'] != '')
                {
                    $q_vars['meta_key'] = ERE_METABOX_PREFIX.'property_identity';
                    $q_vars['meta_value'] = ere_clean(wp_unslash($_GET['property_identity']));
                    $q_vars['meta_compare'] = '=';
                }
            }
        }

        /***
         * @param $query
         */
        public function post_types_admin_order( $query ) {
            if (is_admin()) {
                $post_type = isset($query->query['post_type']) ? $query->query['post_type'] : '';
                if ( $post_type == 'property') {
                    if($query->get('orderby') == ''){
                        $query->set('orderby', array('menu_order' => 'ASC', 'date' => 'DESC'));
                    }
                }
            }
        }

        public function feature() {
	        if (!isset($_GET['_wpnonce'])) {
		        return;
	        }
	        if (!wp_verify_nonce($_GET['_wpnonce'], 'dip-feature-property')) {
		        return;
	        }
	        $property_id = isset($_GET['id']) ? $_GET['id'] : 0;

	        $is_featured = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', true);
	        if ($is_featured == 1) {
		        $is_featured = 0;
	        } else {
		        $is_featured = 1;
	        }
	        update_post_meta($property_id,ERE_METABOX_PREFIX . 'property_featured',$is_featured);
	        if ($is_featured == 1) {
		        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured_date',current_time('mysql'));
	        }
	        wp_safe_redirect( wp_get_referer() ? remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) : admin_url( 'edit.php?post_type=property') );
	        die();
        }
    }
}