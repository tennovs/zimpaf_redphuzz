<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Admin_Setup_Metaboxes')) {
    /**
     * Class ERE_Admin_Setup_Metaboxes
     */
    class ERE_Admin_Setup_Metaboxes
    {
        /**
         * Meta boxes setup
         */
        public function meta_boxes_setup()
        {
            global $typenow;
            if ($typenow == 'user_package') {
                add_action('add_meta_boxes', array($this, 'render_user_package_meta_boxes'));
            }
            if ($typenow == 'invoice') {
                add_action('add_meta_boxes', array($this, 'render_invoice_meta_boxes'));
                add_action('save_post', array($this, 'save_invoices_metaboxes'), 10, 2);
            }
            if ($typenow == 'trans_log') {
                add_action('add_meta_boxes', array($this, 'render_trans_log_meta_boxes'));
            }
            if ($typenow == 'property') {
                add_action('add_meta_boxes', array($this, 'render_property_meta_boxes'));
                add_action('save_post', array($this, 'save_property_metaboxes'), 10, 2);
            }

        }

        /**
         * Render agent package meta boxes
         */
        public function render_user_package_meta_boxes()
        {
            add_meta_box(
                ERE_METABOX_PREFIX . 'user_package_metaboxes',
                esc_html__('Package Details', 'essential-real-estate'),
                array($this, 'user_package_meta'),
                array('user_package'),
                'normal',
                'default'
            );
        }

        /**
         * Agent package meta
         * @param $object
         */
        public function user_package_meta($object)
        {
            $postID = $object->ID;
            $package_user_id = get_post_meta($postID, ERE_METABOX_PREFIX . 'package_user_id', true);
            $package_id = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_id', true);
            $package_number_listings = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_number_listings', true);
            $package_number_featured = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_number_featured', true);
            $package_activate_date = get_user_meta($package_user_id, ERE_METABOX_PREFIX . 'package_activate_date', true);
            $package_name = get_the_title($package_id);
            $user_info = get_userdata($package_user_id);
            $ere_package = new ERE_Package();
            $expired_date = $ere_package->get_expired_date($package_id, $package_user_id);
            ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label><?php esc_html_e('Buyer:', 'essential-real-estate'); ?></label></th>
                    <td><strong><?php if($user_info) echo esc_html($user_info->display_name); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php esc_html_e('Package:', 'essential-real-estate'); ?></label></th>
                    <td><strong><?php echo esc_html($package_name); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php esc_html_e('Number Listings:', 'essential-real-estate'); ?></label>
                    </th>
                    <td><strong><?php echo esc_html($package_number_listings); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php esc_html_e('Number Featured Listings:', 'essential-real-estate'); ?></label>
                    </th>
                    <td><strong><?php echo esc_html($package_number_featured); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php esc_html_e('Activate Date:', 'essential-real-estate'); ?></label></th>
                    <td><strong><?php echo esc_html($package_activate_date); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php esc_html_e('Expire Date:', 'essential-real-estate'); ?></label></th>
                    <td><strong><?php echo esc_html($expired_date); ?></strong>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        }

        /**
         * Render invoice meta boxes
         */
        public function render_invoice_meta_boxes()
        {
            add_meta_box(
                ERE_METABOX_PREFIX . 'invoice_metaboxes',
                esc_html__('Invoice Details', 'essential-real-estate'),
                array($this, 'invoice_meta'),
                array('invoice'),
                'normal',
                'default'
            );

            add_meta_box(
                ERE_METABOX_PREFIX . 'invoice_payment_status',
                esc_html__('Payment Status', 'essential-real-estate'),
                array($this, 'invoice_payment_status'),
                array('invoice'),
                'side',
                'high'
            );
        }

        /**
         * Invoice meta
         * @param $object
         */
        public function invoice_meta($object)
        {
            $ere_invoice = new ERE_Invoice();
            $ere_meta = $ere_invoice->get_invoice_meta($object->ID);
            ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php esc_html_e('Invoice ID:', 'essential-real-estate'); ?></th>
                    <td><strong><?php echo intval($object->ID); ?></strong></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Payment Method:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php echo ERE_Invoice::get_invoice_payment_method($ere_meta['invoice_payment_method']); ?>
                        </strong>
                    </td>
                </tr>
                <?php if (($ere_meta['invoice_payment_method'] == 'Stripe') || ($ere_meta['invoice_payment_method'] == 'Paypal')): ?>
                    <tr>
                        <th scope="row"><?php esc_html_e('PaymentID (PayPal,Stripe):', 'essential-real-estate'); ?></th>
                        <td>
                            <strong>
                                <?php echo esc_html($ere_meta['trans_payment_id']); ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('PayerID (PayPal,Stripe):', 'essential-real-estate'); ?></th>
                        <td>
                            <strong>
                                <?php echo esc_html($ere_meta['trans_payer_id']); ?>
                            </strong>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th scope="row"><?php esc_html_e('Payment Type:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong><?php echo ERE_Invoice::get_invoice_payment_type($ere_meta['invoice_payment_type']); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php

                        if ($ere_meta['invoice_payment_type'] == 'Package') {
                            esc_html_e('Package ID:', 'essential-real-estate');
                        } else {
                            esc_html_e('Property ID:', 'essential-real-estate');
                        }
                        ?>
                    </th>
                    <td>
                        <strong><?php echo esc_html($ere_meta['invoice_item_id']); ?></strong>
                        <?php
                        if ($ere_meta['invoice_payment_type'] == 'Package') {
                            ?>
                            <a href="<?php echo get_edit_post_link($ere_meta['invoice_item_id']) ?>"><?php esc_html_e('(Edit)', 'essential-real-estate'); ?></a>
                            <?php
                        } else {
                            if (current_user_can('read_property', $ere_meta['invoice_item_id'])) {
                                ?>
                                <a href="<?php echo get_permalink($ere_meta['invoice_item_id']) ?>"><?php esc_html_e('(View)', 'essential-real-estate'); ?></a>
                                <?php
                            }
                            if (current_user_can('edit_property', $ere_meta['invoice_item_id'])) {
                                ?>
                                <a href="<?php echo get_edit_post_link($ere_meta['invoice_item_id']) ?>"><?php esc_html_e('(Edit)', 'essential-real-estate'); ?></a>
                                <?php
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Item Price:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong><?php
                            $item_price = ere_get_format_money($ere_meta['invoice_item_price']);
                            echo esc_html($item_price);
                            ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Purchase Date:', 'essential-real-estate'); ?>
                    </th>
                    <td>
                        <strong><?php echo esc_html($ere_meta['invoice_purchase_date']); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Buyer Name:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php
                            $user_info = get_userdata($ere_meta['invoice_user_id']);
                            if (current_user_can('edit_users') && $user_info) {
                                echo '<a href="' . get_edit_user_link($ere_meta['invoice_user_id']) . '">' . esc_attr($user_info->display_name) . '</a>';
                            } else {
                                if($user_info) echo esc_html($user_info->display_name);
                            }
                            ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Buyer Mobile:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php
                            $agent_mobile_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $ere_meta['invoice_user_id']);
                            echo esc_html($agent_mobile_number);
                            ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Buyer Email:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php if($user_info) echo esc_html($user_info->user_email); ?>
                        </strong>
                    </td>
                </tr>
	             <?php do_action('ere_admin_invoice_meta',$ere_meta) ?>
                </tbody>
            </table>
            <?php
        }

        /**
         * Render invoice meta boxes
         */
        public function render_trans_log_meta_boxes()
        {
            add_meta_box(
                ERE_METABOX_PREFIX . 'trans_log_metaboxes',
                esc_html__('Transaction Log Details', 'essential-real-estate'),
                array($this, 'trans_log_meta'),
                array('trans_log'),
                'normal',
                'default'
            );
        }

        /**
         * Invoice meta
         * @param $object
         */
        public function trans_log_meta($object)
        {
            $ere_trans_log = new ERE_Trans_Log();
            $ere_meta = $ere_trans_log->get_trans_log_meta($object->ID);
            ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php esc_html_e('Transaction Status:', 'essential-real-estate'); ?></th>
                    <td><strong><?php
                            $trans_log_status = get_post_meta($object->ID, ERE_METABOX_PREFIX . 'trans_log_status', true);
                            if ($trans_log_status == 1) {
                                esc_html_e('Succeeded', 'essential-real-estate');
                            } else {
                                esc_html_e('Failed', 'essential-real-estate');
                            }
                            ?></strong></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Log ID:', 'essential-real-estate'); ?></th>
                    <td><strong><?php echo intval($object->ID); ?></strong></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Payment Method:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php echo ERE_Invoice::get_invoice_payment_method($ere_meta['trans_log_payment_method']); ?>
                        </strong>
                    </td>
                </tr>
                <?php if (($ere_meta['trans_log_payment_method'] == 'Stripe') || ($ere_meta['trans_log_payment_method'] == 'Paypal')): ?>
                    <tr>
                        <th scope="row"><?php esc_html_e('PaymentID (PayPal,Stripe):', 'essential-real-estate'); ?></th>
                        <td>
                            <strong>
                                <?php echo esc_html($ere_meta['trans_payment_id']); ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('PayerID (PayPal,Stripe):', 'essential-real-estate'); ?></th>
                        <td>
                            <strong>
                                <?php echo esc_html($ere_meta['trans_payer_id']); ?>
                            </strong>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th scope="row"><?php esc_html_e('Payment Type:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong><?php echo ERE_Invoice::get_invoice_payment_type($ere_meta['trans_log_payment_type']); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php

                        if ($ere_meta['trans_log_payment_type'] == 'Package') {
                            esc_html_e('Package ID:', 'essential-real-estate');
                        } else {
                            esc_html_e('Property ID:', 'essential-real-estate');
                        }
                        ?>
                    </th>
                    <td>
                        <strong><?php echo esc_html($ere_meta['trans_log_item_id']); ?></strong>
                        <?php
                        if ($ere_meta['trans_log_payment_type'] == 'Package') {
                            ?>
                            <a href="<?php echo get_edit_post_link($ere_meta['trans_log_item_id']) ?>"><?php esc_html_e('(Edit)', 'essential-real-estate'); ?></a>
                            <?php
                        } else {
                            if (current_user_can('read_property', $ere_meta['trans_log_item_id'])) {
                                ?>
                                <a href="<?php echo get_permalink($ere_meta['trans_log_item_id']) ?>"><?php esc_html_e('(View)', 'essential-real-estate'); ?></a>
                                <?php
                            }
                            if (current_user_can('edit_property', $ere_meta['trans_log_item_id'])) {
                                ?>
                                <a href="<?php echo get_edit_post_link($ere_meta['trans_log_item_id']) ?>"><?php esc_html_e('(Edit)', 'essential-real-estate'); ?></a>
                                <?php
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Item Price:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong><?php
                            $item_price = ere_get_format_money($ere_meta['trans_log_item_price']);
                            echo esc_html($item_price);
                            ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Purchase Date:', 'essential-real-estate'); ?>
                    </th>
                    <td>
                        <strong><?php echo esc_html($ere_meta['trans_log_purchase_date']); ?></strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Buyer Name:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php
                            $user_info = get_userdata($ere_meta['trans_log_user_id']);
                            if ($user_info) {
                                if (current_user_can('edit_users')) {
                                    echo '<a href="' . get_edit_user_link($ere_meta['trans_log_user_id']) . '">' . esc_attr($user_info->display_name) . '</a>';
                                } else {
                                    echo esc_html($user_info->display_name);
                                }
                            }
                            ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Buyer Mobile:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php
                            $agent_mobile_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $ere_meta['trans_log_user_id']);
                            echo esc_html($agent_mobile_number);
                            ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Buyer Email:', 'essential-real-estate'); ?></th>
                    <td>
                        <strong>
                            <?php
                            if ($user_info) {
                                echo esc_html($user_info->user_email);
                            }
                            ?>
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        }

        /**
         * Invoice payment status
         * @param $object
         */
        public function invoice_payment_status($object)
        {
            wp_nonce_field(plugin_basename(__FILE__), 'ere_invoice_nonce_field');
            $payment_status = get_post_meta($object->ID, ERE_METABOX_PREFIX . 'invoice_payment_status', true);
            ?>
            <div class="ere_meta_control custom_sidebar_js">
                <?php
                if ($payment_status == 0) {
                    echo '<span class="ere-label-red notice inline notice-warning notice-alt">' . esc_html__('Not Paid', 'essential-real-estate') . '</span>';
                } else {
                    echo '<span class="ere-label-blue notice inline notice-success notice-alt">' . esc_html__('Paid', 'essential-real-estate') . '</span>';
                }
                if ($payment_status == 0) {
                    ?>
                    <div class="ere-set-item-paid">
                        <input type="checkbox" id="ere[ere_payment_status]" name="ere[ere_payment_status]"
                               value="0"/>
                        <label class="ere-label-blue"
                               for="ere[ere_payment_status]"><?php esc_html_e('Set item paid', 'essential-real-estate'); ?></label>
                    </div>
                <?php }
                ?>
            </div>
            <?php
        }

        /**
         * Save invoices metaboxes
         * @param $post_id
         * @param $post
         * @return bool
         */
        public function save_invoices_metaboxes($post_id, $post)
        {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return false;
            if (!isset($_POST['ere_invoice_nonce_field']) || !wp_verify_nonce($_POST['ere_invoice_nonce_field'], plugin_basename(__FILE__))) {
                return false;
            }
            if ($post->post_type == 'invoice' && isset($_POST['ere'])) {
                $post_type = get_post_type_object($post->post_type);
                if (!current_user_can($post_type->cap->edit_post, $post_id))
                    return false;
                if (isset($_POST['ere']['ere_payment_status'])) {
                    $ere_invoice = new ERE_Invoice();
                    $ere_meta = $ere_invoice->get_invoice_meta($post_id);
                    $user_id = $ere_meta['invoice_user_id'];
                    $user = get_user_by('id', $user_id);
                    $user_email = $user->user_email;
                    if ($ere_meta['invoice_payment_type'] == 'Package') {
                        $package_id = $ere_meta['invoice_item_id'];
                        $ere_package = new ERE_Package();
                        $ere_package->insert_user_package($user_id, $package_id);
                        update_post_meta($post_id, ERE_METABOX_PREFIX . 'invoice_payment_status', 1);
                        $args = array(
                        		'invoice_no' => $post_id,
	                            'total_price' =>  ere_get_format_money($ere_meta['invoice_item_price'])
                        );
                        ere_send_email($user_email, 'mail_activated_package', $args);
                    } else {
                        $property_id = $ere_meta['invoice_item_id'];
                        if ($ere_meta['invoice_payment_type'] == 'Listing') {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'payment_status', 'paid');
                            wp_update_post(array(
                                'ID' => $property_id,
                                'post_status' => 'publish',
                                'post_date' => current_time('mysql'),
                                'post_date_gmt' => current_time('mysql'),
                            ));
                            ere_send_email($user_email, 'mail_activated_listing');
                        } else if ($ere_meta['invoice_payment_type'] == 'Upgrade_To_Featured') {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
	                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured_date',current_time('mysql'));
                        } else if ($ere_meta['invoice_payment_type'] == 'Listing_With_Featured') {
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'payment_status', 'paid');
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
	                        update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured_date',current_time('mysql'));
                            wp_update_post(array(
                                'ID' => $property_id,
                                'post_status' => 'publish',
                                'post_date' => current_time('mysql'),
                                'post_date_gmt' => current_time('mysql'),
                            ));
                            ere_send_email($user_email, 'mail_activated_listing');
                        }
                        update_post_meta($post_id, ERE_METABOX_PREFIX . 'invoice_payment_status', 1);

                    }
                }
            }
            return true;
        }

        /**
         * Save property metaboxes
         * @param $post_id
         * @return bool
         */
        public function save_property_metaboxes($post_id)
        {
            if (!is_admin()) return false;
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return false;
            $agent_display_option = get_post_meta($post_id, ERE_METABOX_PREFIX . 'agent_display_option', true);
            if (isset($agent_display_option) && ('author_info' == $agent_display_option)) {
                $post_author = get_post_field('post_author', $post_id);
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_author', $post_author);
            } else {
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_author', '');
            }
            if ($agent_display_option != 'agent_info') {
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_agent', '');
            }
            $property_identity = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_identity', true);
            if (empty($property_identity)) {
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_identity', $post_id);
            }
            $property_price_on_call = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_on_call', true);
            if($property_price_on_call=='1')
            {
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_short', '');
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price', '');
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_unit', 1);
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_prefix', '' );
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_postfix', '');
            }
            else{
                $enable_price_unit=ere_get_option('enable_price_unit', '1');
                if($enable_price_unit=='0')
                {
                    update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_unit', 1);
                }
                $property_price_short = ere_format_decimal(get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_short', true));
                $property_price_unit = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_unit', true);
                if (!empty($property_price_short) && is_numeric($property_price_short)) {
                    if (!empty($property_price_unit) && is_numeric($property_price_unit) && intval($property_price_unit)>1) {
                        $property_price=doubleval($property_price_short)*intval($property_price_unit);
                    }
                    else
                    {
                        $property_price=doubleval($property_price_short);
                    }
                }
                else{
                    $property_price='';
                }
	            update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_short', $property_price_short);
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price', $property_price);
            }
            if (isset($_POST['ere']['ere_property_country'])) {
                $country= ere_clean(wp_unslash($_POST['ere']['ere_property_country']))  ;
                update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_country', $country);
            }
            return true;
        }

        /**
         * Render property paid meta boxes
         */
        public function render_property_meta_boxes()
        {
            add_meta_box(
                ERE_METABOX_PREFIX . 'property_country',
                esc_html__('Country', 'essential-real-estate'),
                array($this, 'property_country'),
                'property',
                'side');

            $paid_submission_type = ere_get_option('paid_submission_type', 'no');
            if($paid_submission_type == 'per_listing')
            {
                add_meta_box(
                    ERE_METABOX_PREFIX . 'paid_submission',
                    esc_html__('Paid Submission', 'essential-real-estate'),
                    array($this, 'paid_submission'),
                    'property',
                    'side',
                    'high');
            }
        }

        /**
         * Render paid submission status
         * @param $object
         */
        public function paid_submission($object)
        {
            $payment_status = get_post_meta($object->ID, ERE_METABOX_PREFIX . 'payment_status', true);
            if ($payment_status == 'paid') {
                echo wp_kses_post(__('Payment Status: <span class="ere-label-blue">Paid</span>', 'essential-real-estate'));
            } else {
                $price_per_listing = ere_get_option('price_per_listing',0);
                if($price_per_listing>0)
                {
                    echo wp_kses_post(__('Payment Status: <span class="ere-label-red">Not Paid</span>', 'essential-real-estate'));
                }
            }
            ?>
            <div class="ere_meta_control custom_sidebar_js">
                <p><?php esc_html_e('View Invoice: ', 'essential-real-estate'); $ere_admin_invoice = new ERE_Admin_Invoice();
                $ere_admin_invoice->get_invoices_by_property($object->ID); ?>
                </p>
            </div>
            <?php
        }

        /**
         * Render Country
         * @param $object
         */
        public function property_country($object)
        {
            $property_country = get_post_meta($object->ID, ERE_METABOX_PREFIX . 'property_country', true);
            $default_country = ere_get_option('default_country', 'US');
            if(empty($property_country))
            {
                $property_country=$default_country;
            }
            ?>
            <div id="property-country-<?php echo esc_attr($property_country); ?>" class="selectdiv ere-property-select-meta-box-wrap">
                <select id="ere[ere_property_country]" name="ere[ere_property_country]" class="widefat ere-property-country-ajax">
                    <?php
                    $countries = ere_get_selected_countries();
                    foreach ($countries as $key => $country):
                        echo '<option ' . selected($property_country, $key, false) . ' value="' . $key . '">' . $country . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <?php
        }
    }
}