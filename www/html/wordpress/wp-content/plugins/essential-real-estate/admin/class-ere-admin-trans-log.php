<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin_Trans_Log')) {
    /**
     * Class ERE_Admin_Trans_Log
     */
    class ERE_Admin_Trans_Log
    {
        /**
         * Register custom columns
         * @param $columns
         * @return array
         */
        public function register_custom_column_titles($columns)
        {
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['title'] = esc_html__('Log', 'essential-real-estate');
            $columns['trans_log_payment_method'] = esc_html__('Payment Method', 'essential-real-estate');
            $columns['trans_log_payment_type'] =esc_html__('Payment Type', 'essential-real-estate');
            $columns['trans_log_price'] =  esc_html__('Money', 'essential-real-estate');
            $columns['trans_log_user_id'] =esc_html__('Buyer', 'essential-real-estate');
            $columns['trans_log_status'] = esc_html__('Status', 'essential-real-estate');
            $columns['date'] = esc_html__('Date', 'essential-real-estate');
            $new_columns = array();
            $custom_order = array('cb', 'title', 'trans_log_payment_method','trans_log_payment_type','trans_log_price','trans_log_user_id','trans_log_status','date');
            foreach ($custom_order as $colname){
                $new_columns[$colname] = $columns[$colname];
            }
            return $new_columns;
        }

        /**
         * sortable_columns
         * @param $columns
         * @return mixed
         */
        public function sortable_columns($columns)
        {
            $columns['title'] = 'title';
            $columns['trans_log_payment_method'] = 'trans_log_payment_method';
            $columns['trans_log_payment_type'] = 'trans_log_payment_type';
            $columns['trans_log_price'] = 'trans_log_price';
            $columns['trans_log_status'] = 'trans_log_status';
            $columns['date'] = 'date';
            return $columns;
        }
        /**
         * @param $vars
         * @return array
         */
        public function column_orderby($vars) {
            if ( !is_admin() )
                return $vars;
            if ( isset($vars['orderby']) && 'trans_log_payment_method' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => ERE_METABOX_PREFIX. 'trans_log_payment_method',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'trans_log_payment_type' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => ERE_METABOX_PREFIX. 'trans_log_payment_type',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'trans_log_price' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => ERE_METABOX_PREFIX. 'trans_log_price',
                    'orderby' => 'meta_value_num',
                ));
            }
            if ( isset($vars['orderby']) && 'trans_log_status' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => ERE_METABOX_PREFIX. 'trans_log_status',
                    'orderby' => 'meta_value_num',
                ));
            }
            return $vars;
        }

        /**
         * Display custom column for trans_log
         * @param $column
         */
        public function display_custom_column($column)
        {
            global $post;
            $trans_log_meta = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'trans_log_meta', true);
            switch ($column) {
                case 'trans_log_payment_method':
                    echo ERE_Invoice::get_invoice_payment_method($trans_log_meta['trans_log_payment_method']);
                    break;
                case 'trans_log_payment_type':
                    echo ERE_Invoice::get_invoice_payment_type($trans_log_meta['trans_log_payment_type']);
                    break;
                case 'trans_log_price':
                    echo esc_html($trans_log_meta['trans_log_item_price']);
                    break;
                case 'trans_log_user_id':
                    $user_info = get_userdata($trans_log_meta['trans_log_user_id']);
                    if($user_info)
                    {
                        echo esc_html($user_info->display_name);
                    }
                    break;
                case 'trans_log_status':
                    $trans_log_status = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'trans_log_status', true);
                    if ($trans_log_status == 1) {
                        echo '<span class="ere-label-blue">' . esc_html__('Succeeded', 'essential-real-estate') . '</span>';
                    } else {
                        echo '<span class="ere-label-red">' . esc_html__('Failed', 'essential-real-estate') . '</span>';
                    }
                    break;
            }
        }
        /**
         * Modify trans_log slug
         * @param $existing_slug
         * @return string
         */
        public function modify_trans_log_slug($existing_slug)
        {
            $trans_log_url_slug = ere_get_option('trans_log_url_slug');
            if ($trans_log_url_slug) {
                return $trans_log_url_slug;
            }
            return $existing_slug;
        }
        /**
         * filter_restrict_manage_invoice
         */
        public function filter_restrict_manage_trans_log() {
            global $typenow;
            $post_type = 'trans_log';

            if ($typenow == $post_type){
                //Invoice Status
                $values=array(
                    'succeeded'=>esc_html__('Succeeded','essential-real-estate'),
                    'failed'=>esc_html__('Failed','essential-real-estate'),
                );
                ?>
                <select name="trans_log_status">
                    <option value=""><?php _e('All Status', 'essential-real-estate'); ?></option>
                    <?php $current_v = isset($_GET['trans_log_status'])? ere_clean(wp_unslash($_GET['trans_log_status'])) :'';
                    foreach ($values as $value => $label) {
                        printf
                        (
                            '<option value="%s"%s>%s</option>',
                            $value,
                            $value == $current_v? ' selected="selected"':'',
                            $label
                        );
                    }
                    ?>
                </select>
                <?php
                //Payment method
                $values=array(
                    'Paypal'=>esc_html__('Paypal','essential-real-estate'),
                    'Stripe'=>esc_html__('Stripe','essential-real-estate'),
                    'Wire_Transfer'=>esc_html__('Wire Transfer','essential-real-estate'),
                    'Free_Package'=>esc_html__('Free Package','essential-real-estate'),
                );
                ?>
                <select name="trans_log_payment_method">
                    <option value=""><?php _e('All Payment Methods', 'essential-real-estate'); ?></option>
                    <?php $current_v = isset($_GET['trans_log_payment_method'])? ere_clean(wp_unslash($_GET['trans_log_payment_method'])) :'';
                    foreach ($values as $value => $label) {
                        printf
                        (
                            '<option value="%s"%s>%s</option>',
                            $value,
                            $value == $current_v? ' selected="selected"':'',
                            $label
                        );
                    }
                    ?>
                </select>
                <?php
                //Payment type
                $values=array(
                    'Package'=>esc_html__('Package','essential-real-estate'),
                    'Listing'=>esc_html__('Listing','essential-real-estate'),
                    'Upgrade_To_Featured'=>esc_html__('Upgrade to Featured','essential-real-estate'),
                    'Listing_With_Featured'=>esc_html__('Listing with Featured','essential-real-estate'),
                );
                ?>
                <select name="trans_log_payment_type">
                    <option value=""><?php _e('All Payment Types', 'essential-real-estate'); ?></option>
                    <?php $current_v = isset($_GET['trans_log_payment_type'])? ere_clean(wp_unslash($_GET['trans_log_payment_type'])) :'';
                    foreach ($values as $value => $label) {
                        printf
                        (
                            '<option value="%s"%s>%s</option>',
                            $value,
                            $value == $current_v? ' selected="selected"':'',
                            $label
                        );
                    }
                    $trans_log_user = isset($_GET['trans_log_user'])? ere_clean(wp_unslash($_GET['trans_log_user'])) :'';
                    ?>
                </select>
                <input type="text" placeholder="<?php esc_attr_e('Buyer','essential-real-estate');?>" name="trans_log_user" value="<?php echo esc_attr($trans_log_user);?>">
            <?php }
        }

        /**
         * invoice_filter
         * @param $query
         */
        public function trans_log_filter($query) {
            global $pagenow;
            $post_type = 'trans_log';
            $q_vars    = &$query->query_vars;$filter_arr=array();
            if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type)
            {
                $trans_log_user = isset($_GET['trans_log_user']) ?  ere_clean(wp_unslash($_GET['trans_log_user'])) : '';
                if($trans_log_user !== '')
                {
                    $user = get_user_by('login',$trans_log_user);
                    $user_id=-1;
                    if($user)
                    {
                        $user_id=$user->ID;
                    }
                    $filter_arr[] = array(
                        'key' =>ERE_METABOX_PREFIX. 'trans_log_user_id',
                        'value' =>  $user_id,
                        'compare' => 'IN',
                    );
                }

                $_trans_log_status = isset($_GET['trans_log_status']) ?  ere_clean(wp_unslash($_GET['trans_log_status'])) : '';

                if($_trans_log_status !== '')
                {
                    $trans_log_status=0;
                    if($_trans_log_status == 'succeeded')
                    {
                        $trans_log_status=1;
                    }
                    $filter_arr[] = array(
                        'key' =>ERE_METABOX_PREFIX. 'trans_log_status',
                        'value' => $trans_log_status,
                        'compare' => '=',
                    );
                }

                $trans_log_payment_method = isset($_GET['trans_log_payment_method']) ? ere_clean(wp_unslash($_GET['trans_log_payment_method'])) : '';

                if($trans_log_payment_method !== '') {
                    $filter_arr[] = array(
                        'key' => ERE_METABOX_PREFIX . 'trans_log_payment_method',
                        'value' => $trans_log_payment_method,
                        'compare' => '=',
                    );
                }

                $trans_log_payment_type = isset($_GET['trans_log_payment_type']) ? ere_clean(wp_unslash($_GET['trans_log_payment_type'])) : '';

                if($trans_log_payment_type !== '') {
                    $filter_arr[] = array(
                        'key' => ERE_METABOX_PREFIX . 'trans_log_payment_type',
                        'value' => $trans_log_payment_type,
                        'compare' => '=',
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
            if ( $post->post_type == 'trans_log' ) {
                unset( $actions[ 'view' ] );
            }
            return $actions;
        }
    }
}