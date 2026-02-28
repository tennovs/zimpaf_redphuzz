<?php     
    global $post, $wpdb, $woocommerce, $product;
    $udrawSettings = new uDrawSettings();
    $uDrawUtil = new uDrawUtil();
    $_udraw_settings = $udrawSettings->get_settings();
    $table_name = $_udraw_settings['udraw_db_udraw_customer_designs'];

    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
    
    // Save Design Post Back. Only if Saved Page is defined.
    if ($_udraw_settings['udraw_customer_saved_design_page_id'] > 1) {
        if (isset($_POST['udraw_is_saving_for_later'])) {
            $nonce = $_REQUEST['_wpnonce'];
            if( !wp_verify_nonce( $nonce, 'save_udraw_customer_design' )) {
                wp_die('security check failed');
            } else {
                $dt = new DateTime();
                //If not logged in, use session id
                $user_id = 0;
                $username = '';
                if (is_user_logged_in()) {
                    $username = wp_get_current_user()->user_login;
                    $user_id = wp_get_current_user()->ID;
                } else {
                    if (!session_id()) {
                        session_start();
                    }
                    $session_id = session_id();
                    $username = '_'. $session_id .'_';
                }
                $_output_path = UDRAW_STORAGE_DIR . $username . '/output/';
                if (!file_exists($_output_path)) {
                    wp_mkdir_p($_output_path);
                }

                $access_key = (isset($_POST['udraw_save_access_key']) && strlen($_POST['udraw_save_access_key']) > 1) ? $_POST['udraw_save_access_key'] : uniqid('udraw_');
                
                $udraw_saved_customer_design_xml = file_get_contents($_POST['udraw_save_product_data']);
                $udraw_product_data_file = $access_key . '_usdf.xml';
                file_put_contents($_output_path . $udraw_product_data_file, $udraw_saved_customer_design_xml);

                $preview_url = $_POST['udraw_save_product_preview'];
                $preview_file = $access_key . '.png';
                if (startsWith($_POST['udraw_save_product_preview'], 'http')) {
                    $preview_content = file_get_contents($path);
                    $type = pathinfo($preview_url, PATHINFO_EXTENSION);
                    $preview_data = 'data:image/' . $type . ';base64,' . base64_encode($preview_content);
                    //$uDrawUtil->download_file($_POST['udraw_save_product_preview'], $_output_path . $preview_file);
                    file_put_contents($_output_path . $preview_file, $preview_data);
                } else if (startsWith($_POST['udraw_save_product_preview'], 'data:image')) {
                    $preview_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['udraw_save_product_preview']));
                    file_put_contents($_output_path . $preview_file, $preview_data);
                }
                $ifPreviewNotFound = UDRAW_STORAGE_URL . $username . '/output/' . $preview_file; 
                $preview_url = (isset($_POST['udraw_save_product_preview']) && strlen($_POST['udraw_save_product_preview']) > 1) ? $_POST['udraw_save_product_preview'] : $ifPreviewNotFound;
                //$access_key = (isset($_POST['udraw_save_access_key']) && strlen($_POST['udraw_save_access_key']) > 1) ? $_POST['udraw_save_access_key'] : uniqid('udraw_');
                $date_time = $dt->format('Y-m-d H:i:s');
                $date = $dt->format('Y-m-d');
                $args = array(
                    'post_id' => $post->ID,
                    'customer_id' => $user_id,
                    'preview_data' => $preview_url,
                    'design_data' => UDRAW_STORAGE_URL . $username . '/output/' . $udraw_product_data_file,
                    'price_matrix_options' => null,
                    'variation_options' => null
                );
                if (isset($_REQUEST['udraw_price_matrix_selected_by_user']) && strlen($_REQUEST['udraw_price_matrix_selected_by_user']) > 0) {
                    $args['price_matrix_options'] = $_REQUEST['udraw_price_matrix_selected_by_user'] ;
                }
                if (isset($_REQUEST['udraw_selected_variations']) && strlen($_REQUEST['udraw_selected_variations']) > 0) {
                    $args['variation_options'] = $_REQUEST['udraw_selected_variations'];
                }
                if (strlen($_POST['udraw_save_access_key']) > 1) {
                    $args['modify_date'] = $date_time;
                    // update design            
                    $wpdb->update($table_name, 
                        $args,
                        array(
                            'access_key' => $access_key
                        )
                    );
                } else {
                    $args['create_date'] = $date_time;
                    $args['access_key'] = $access_key;
                    $design_name = (isset($_POST['udraw_save_product_design_name']) && strlen($_POST['udraw_save_product_design_name']) > 1) ? $_POST['udraw_save_product_design_name'] : $post->post_title;
                    $args['name'] = $design_name;
                    // insert new design
                    $pm_options_column = 'price_matrix_options';
                    $pm_options_column_dt = $wpdb->get_var("select data_type from information_schema.columns where table_name = '$table_name' and column_name = '$pm_options_column'"); 
                    if ($pm_options_column_dt === 'varchar') {
                        $wpdb->query("ALTER TABLE ${table_name} MODIFY ${pm_options_column} LONGTEXT COLLATE utf8_general_ci NULL");
                    }
                    $wpdb->insert($table_name,
                        $args
                    );
                }
                if (is_user_logged_in()) {
                    // redirct after update/insert to 'my saved designs page'
                    //Only if logged in
                    $pages = get_pages();
                    foreach ($pages as $page) {
                        if ($page->ID == $_udraw_settings['udraw_customer_saved_design_page_id']) {
                            // redirct to saved design page.
                            ?>
                                <script>
                                    window.location.replace("<?php echo get_permalink($page->ID); ?>");
                                </script>
                            <?php                    
                            exit;
                        }
                    }
                } else {
                    //Otherwise display link to product with access_key
                    $url = get_post_permalink($post->ID) . '?udraw_access_key=' . $access_key;
                    ?>     
                        <div class="modal fade udraw_modal" data-udraw="saved_design_modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="email_div">
                                            <span style="font-weight: bold;"><?php _e('Great idea!', 'udraw'); ?></span>
                                            <br/>
                                            <span><?php _e('Enter your email and we will send you a digital copy of your personalized design that you can edit or purchase later.', 'udraw'); ?></span>
                                            <input type="text" data-udraw="email_input" placeholder="test@example.com">
                                            <button type="button" class="btn btn-success" data-udraw="send_link_to_email"><?php _e('Send to Email', 'udraw'); ?></button>
                                            <br/>
                                            <span><?php _e('*Please make sure to Save Your Design again after making any additional changes.', 'udraw'); ?></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" data-dismiss="modal" onClick="javascript: window.location.replace('<?php echo $url ?>');"><?php _e('Continue (page will refresh)', 'udraw'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            jQuery(document).ready(function($){
                                $('[data-udraw="saved_design_modal"]').modal({
                                    backdrop: 'static',
                                    keyboard: false,
                                    show: true
                                });
                                var save_design_modal = $('[data-udraw="saved_design_modal"]');
                                save_design_modal.detach();
                                $('div.modal-backdrop').after(save_design_modal);
                                $('[data-udraw="send_link_to_email"]').on('click', function(){
                                    var email_input = $('[data-udraw="saved_design_modal"] input[data-udraw="email_input"]').val();
                                    if (email_input.length > 0) {
                                        $.ajax({
                                            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                                            type: 'POST',
                                            contentType: "application/x-www-form-urlencoded",
                                            dataType: "json",
                                            data: {
                                                action: 'udraw_send_saved_design_link',
                                                email: email_input,
                                                design_url: '<?php echo $url ?>',
                                                preview_url: '<?php echo $preview_url ?>',
                                                date: '<?php echo $date ?>',
                                                product_id: '<?php echo $post->ID ?>'
                                            },
                                            success: function (result) {
                                                console.log(result);
                                                if (result) {
                                                    window.alert('<?php echo _e('Email sent successfully. Please check your email before exiting this page.', 'udraw'); ?>');
                                                }
                                            },
                                            error: function (error) {
                                                console.log(error);
                                                window.alert('<?php echo _e('An error had occurred. Please try again.', 'udraw'); ?>');
                                            }
                                        });
                                    }
                                });
                            });
                        </script>
                        <style>
                            @media only screen and (min-height: 400px) {
                                [data-udraw="saved_design_modal"] div.modal-dialog {
                                    top: 20%;
                                }
                            }
                            @media only screen and (min-height: 600px) {
                                [data-udraw="saved_design_modal"] div.modal-dialog {
                                    top: 40%;
                                }
                            }
                        </style>
                    <?php
                }
            }
        }
    }
    
    // Remove Saved Design checkdate
    if (isset($_GET['udraw_remove_template'])) {
        if (isset($_GET['udraw_access_key'])) {
            $wpdb->delete( $table_name, array( 'access_key' => $_GET['udraw_access_key'] ) );
            
            // redirct after removing template.
            $pages = get_pages();
            foreach ($pages as $page) {
                if ($page->ID == $_udraw_settings['udraw_customer_saved_design_page_id']) {
                    // redirct to saved design page.
                    ?>
                        <script>
                            window.location.replace("<?php echo get_permalink($page->ID); ?>");
                        </script>
                    <?php                    
                    exit;
                }
            }            
        }
    }
?>

