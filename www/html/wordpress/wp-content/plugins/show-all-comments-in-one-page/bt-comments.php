<?php
/**
 * Plugin Name: BT Comments
 * Description: Show all comments in one page with filter.
 * Author: biztechc
 * Author URI: https://www.appjetty.com/
 * Version: 7.0.0
 */
add_action('admin_menu', 'bt_comments_create_menu');

function bt_comments_create_menu() {

    //create new top-level menu
    add_menu_page('Show All Comments Settings', 'Show All Comments', 'administrator', 'bt-comments', 'bt_comments_settings_page');

    //call register settings function
    add_action('admin_init', 'register_bt_comments_settings');
}

function register_bt_comments_settings() {
    //register our settings
    register_setting('bt-comments-settings-group', 'bt_post_type');
    register_setting('bt-comments-settings-group', 'bt_pagination');
    register_setting('bt-comments-settings-group', 'bt_comments_per_page');
    register_setting('bt-comments-settings-group', 'bt_exclude_post');
    register_setting('bt-comments-settings-group', 'biztech_sac_avatar');
    register_setting('bt-comments-settings-group', 'biztech_show_date');
    register_setting('bt-comments-settings-group', 'biztech_open_new_tab');
    register_setting('bt-comments-settings-group', 'biztech_comments_order');
    register_setting('bt-comments-settings-group', 'bt_display_filter');
    register_setting('bt-comments-settings-group', 'bt_show_post_link');
    register_setting('bt-comments-settings-group', 'bt_show_comment_link');
}

function bt_comments_settings_page() {

    // Admin side page options
    $set_bt_post_type = get_option('bt_post_type');

    if ($set_bt_post_type == NULL) {
        $set_bt_post_type = Array('bt' => 'bt');
    }
    $set_bt_pagination = get_option('bt_pagination');
    $set_bt_comments_per_page = get_option('bt_comments_per_page');

    if ($set_bt_comments_per_page == NULL) {
        $set_bt_comments_per_page = 10;
    }
    $set_bt_exclude_post = get_option('bt_exclude_post');
    $set_biztech_sac_avatar = get_option('biztech_sac_avatar');

    $set_biztech_show_date = get_option('biztech_show_date');
    $set_biztech_open_new_tab = get_option('biztech_open_new_tab');
    $set_comments_order = get_option('biztech_comments_order');
    $set_display_filter = get_option('bt_display_filter');
    $show_post_link = get_option('bt_show_post_link');
    $show_comment_link = get_option('bt_show_comment_link');
    ?>
    <div class="wrap">
        <h2>Show All Comments Settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('bt-comments-settings-group'); ?>
            <?php do_settings_sections('bt-comments-settings-group'); ?>
            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><?php _e('Post Type'); ?></th>
                    <td>
                        <fieldset>
                            <?php
                            $post_types = get_post_types('', 'names');

                            unset($post_types['attachment']);
                            unset($post_types['revision']);
                            unset($post_types['nav_menu_item']);

                            foreach ($post_types as $post_type) {
                                $checked = in_array("$post_type", $set_bt_post_type) ? ' checked="checked"' : '';
                                ?>
                                <label><input type="checkbox"  value="<?php echo $post_type; ?>" name="bt_post_type[]" <?php echo $checked; ?>> <span><?php echo $post_type; ?></span></label><br>
                                <?php
                            }
                            ?>   
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Pagination'); ?></th>
                    <td>
                        <fieldset>
                            <?php
                            if ($set_bt_pagination == 'yes') {
                                ?>
                                <label><input type="radio"  value="yes" name="bt_pagination" checked="checked"> <span><?php _e('Yes'); ?></span></label><br>
                                <label><input type="radio"  value="no"  name="bt_pagination"> <span><?php _e('No'); ?></span></label>
                                <?php
                            } else {
                                ?>
                                <label><input type="radio"  value="yes" name="bt_pagination"> <span><?php _e('Yes'); ?></span></label><br>
                                <label><input type="radio"  value="no"  name="bt_pagination" checked="checked"> <span><?php _e('No'); ?></span></label>
                                <?php
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Comments Per Page'); ?></th>
                    <td><input type="number" class="small-text" value="<?php echo $set_bt_comments_per_page; ?>"  min="1" step="1" name="bt_comments_per_page"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Enable post title link?'); ?></th>
                    <td>
                        <fieldset>
                            <label><input type="radio"  value="yes" name="bt_show_post_link" <?php echo ($show_post_link == 'yes' ? 'checked' : ''); ?>> <span><?php _e('Yes'); ?></span></label><br>
                            <label><input type="radio"  value="no"  name="bt_show_post_link" <?php echo ($show_post_link == 'no' ? 'checked' : ''); ?>> <span><?php _e('No'); ?></span></label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Enable go to comment link?'); ?></th>
                    <td>
                        <fieldset>
                            <label><input type="radio"  value="yes" name="bt_show_comment_link" <?php echo ($show_comment_link == 'yes' ? 'checked' : ''); ?>> <span><?php _e('Yes'); ?></span></label><br>
                            <label><input type="radio"  value="no"  name="bt_show_comment_link" <?php echo ($show_comment_link == 'no' ? 'checked' : ''); ?>> <span><?php _e('No'); ?></span></label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Exclude Post Id'); ?></th>
                    <td><input type="text" name="bt_exclude_post" value="<?php echo $set_bt_exclude_post; ?>" /> <?php _e('Exclude post id with comma separated. like 11,22,33'); ?></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Avatar Size'); ?></th>
                    <td><input type="number" class="small-text" value="<?php
                        if ($set_biztech_sac_avatar == NULL) {
                            echo "50";
                        } else {
                            echo $set_biztech_sac_avatar;
                        }
                        ?>" id="biztech_sac_avatar" min="1" step="1" name="biztech_sac_avatar"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Show Comment Date'); ?></th>
                    <td>
                        <fieldset>
                            <?php {
                                if ($set_biztech_show_date == 'on') {
                                    $checked = 'checked=checked';
                                }
                                ?>
                                <label><input type="checkbox" name="biztech_show_date" <?php echo $checked; ?>></label><br>
                                <?php
                                $checked = '';
                            }
                            ?>   
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Open Comment in New TAB'); ?></th>
                    <td>
                        <fieldset>
                            <?php {
                                if ($set_biztech_open_new_tab == 'on') {
                                    $checked = 'checked=checked';
                                }
                                ?>
                                <label><input type="checkbox" name="biztech_open_new_tab" <?php echo $checked; ?>></label><br>
                                <?php
                                $checked = '';
                            }
                            ?>   
                        </fieldset>
                    </td>
                </tr>


                <tr valign="top">
                    <th scope="row"><?php _e('Comments Order'); ?></th>
                    <td>
                        <fieldset>
                            <?php
                            if ($set_comments_order == 'no') {
                                ?>

                                <label><input type="radio"  value="yes" name="biztech_comments_order"> <span><?php _e('Newest comments First'); ?></span></label><br>
                                <label><input type="radio"  value="no"  name="biztech_comments_order" checked="checked"> <span>Oldest comments First</span></label>
                                <?php
                            } else {
                                ?>
                                <label><input type="radio"  value="yes" name="biztech_comments_order" checked="checked"> <span><?php _e('Newest comments First'); ?></span></label><br>
                                <label><input type="radio"  value="no"  name="biztech_comments_order"> <span><?php _e('Oldest comments First'); ?></span></label>

                                <?php
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Display Filter'); ?></th>
                    <td>
                        <fieldset>
                            <?php
                            if ($set_display_filter == 'yes') {
                                ?>
                                <label><input type="radio"  value="yes" name="bt_display_filter" checked="checked"> <span><?php _e('Yes'); ?></span></label><br>
                                <label><input type="radio"  value="no"  name="bt_display_filter"> <span><?php _e('No'); ?></span></label>
                                <?php
                            } else {
                                ?>
                                <label><input type="radio"  value="yes" name="bt_display_filter"> <span><?php _e('Yes'); ?></span></label><br>
                                <label><input type="radio"  value="no"  name="bt_display_filter" checked="checked"> <span><?php _e('No'); ?></span></label>
                                <?php
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>

        </form>
    </div>
    <?php
}

// add shortcode
add_shortcode('bt_comments', 'custom_comments');

function custom_comments($attr) {
    $content = "";
    // set comments settings          
    $page = intval(get_query_var('cpage'));
    if (0 == $page) {
        $page = 1;
        set_query_var('cpage', $page);
    }


    $comments_order = get_option('biztech_comments_order');
    if ($comments_order == 'yes') {
        $order = 'DESC';
    } else {
        $order = 'ASC';
    }

    $pagination = get_option('bt_pagination');
//    if ($pagination == 'yes') {
//        
//        // set_query_var('comments_per_page', $comments_per_page);
//    } else {
//        $comments_per_page = 0;
//    }
    $comments_per_page = get_option('bt_comments_per_page');
    //added by abhisha to make dynamic shortcode for pagination,comments per page,display filter
    $get_atts = shortcode_atts(array(
        'pagination' => $pagination,
        'comments_per_page' => $comments_per_page,
        'display_filter' => get_option('bt_display_filter'),
            ), $attr);
    if ($get_atts['pagination'] == 'yes') {
        set_query_var('comments_per_page', $get_atts['comments_per_page']);
    }
    $display_filter = $get_atts['display_filter'];

    //ended//

    $post_type = get_option('bt_post_type');

    if (isset($_REQUEST['sac_post_types']) && $_REQUEST['sac_post_types'] != null) {

        $post_type = array($_REQUEST['sac_post_types']);
    }

    if ($post_type != NULL) {

        function wpse_121051($clauses, $wpqc) {
            global $wpdb;

            // Remove the comments_clauses filter, we don't need it anymore. 
            remove_filter(current_filter(), __FUNCTION__);

            // Add the multiple post type support.
            if (isset($wpqc->query_vars['post_type'][0])) {

                $join = join("', '", array_map('esc_sql', $wpqc->query_vars['post_type']));

                $from = "$wpdb->posts.post_type = '" . $wpqc->query_vars['post_type'][0] . "'";
                $to = sprintf("$wpdb->posts.post_type IN ( '%s' ) ", $join);

                $clauses['where'] = str_replace($from, $to, $clauses['where']);
            }

            return $clauses;
        }

        add_filter('comments_clauses', 'wpse_121051', 10, 2);
    } else {
        $post_type = array('bt');
    }

    $exclude_post = get_option('bt_exclude_post');
    $exclude_post = explode(',', $exclude_post);

    global $wp_version;
    if ($wp_version >= 4.1) {
        $defaults = array(
            'orderby' => 'comment_date',
            'order' => $order,
            'post_type' => $post_type,
            'status' => 'approve',
            'count' => false,
            'post__not_in' => $exclude_post,
            'date_query' => null
        );

        if (isset($_REQUEST['sac_posts']) && $_REQUEST['sac_posts'] != null) {

            $defaults['post__in'] = array($_REQUEST['sac_posts']);
        } else if (isset($_REQUEST['sac_post_types']) && $_REQUEST['sac_category'] != null && $_REQUEST['sac_post_types'] == 'post') {

            $category_args = array(
                'posts_per_page' => -1,
                'offset' => 0,
                'orderby' => 'title',
                'order' => 'DESC',
                'post_type' => 'post',
                'category' => $_REQUEST['sac_category'],
                'post_status' => 'publish',
                'suppress_filters' => true,
            );

            $category_posts = get_posts($category_args);
            $post__in = array();
            if ($category_posts != null) {

                foreach ($category_posts as $category_post) {

                    $post__in[] = $category_post->ID;
                }
            }
            $defaults['post__in'] = $post__in;
        }

        $comments = get_comments($defaults);
    } else {
        global $wpdb;

        $post_type = implode("','", $post_type);
        $post_type = "'" . $post_type . "'";

        $exclude_post = implode(',', $exclude_post);
        if ($exclude_post == NULL) {
            $exclude_post = 0;
        }

        $getIncludePostId = $wpdb->get_results(
                "
            SELECT * 
            FROM $wpdb->comments as c
            INNER JOIN  $wpdb->posts as p 
                ON c.comment_post_ID = p.ID
            WHERE  p.post_type IN($post_type) AND p.ID NOT IN($exclude_post) AND c.comment_approved = 1 
            ORDER BY c.comment_date $order 
            "
        );

        if (isset($_REQUEST['sac_posts']) && $_REQUEST['sac_posts'] != null) {

            $sac_posts = $_REQUEST['sac_posts'];
            $getIncludePostId = $wpdb->get_results(
                    "
                SELECT * 
                FROM $wpdb->comments as c
                INNER JOIN  $wpdb->posts as p 
                    ON c.comment_post_ID = p.ID
                WHERE  p.post_type IN($post_type) AND p.ID NOT IN($exclude_post) AND p.ID IN($sac_posts) AND c.comment_approved = 1 
                ORDER BY c.comment_date $order 
                "
            );
        } else if (isset($_REQUEST['sac_category']) && $_REQUEST['sac_category'] != null && $_REQUEST['sac_post_types'] == 'post') {

            $sac_category = $_REQUEST['sac_category'];
            $getIncludePostId = $wpdb->get_results(
                    "
                SELECT * 
                FROM $wpdb->comments as c
                INNER JOIN  $wpdb->posts as p 
                    ON c.comment_post_ID = p.ID 
                INNER JOIN $wpdb->term_relationships as tr
                    ON c.comment_post_ID = tr.object_id 
                WHERE  p.post_type IN($post_type) AND p.ID NOT IN($exclude_post) AND tr.term_taxonomy_id = $sac_category AND c.comment_approved = 1 
                ORDER BY c.comment_date $order 
                "
            );
        }

        $comments = $getIncludePostId;
    }

    $content .= '<div class="show-all-comments">';
    if ($display_filter == 'yes') { // if display filter yes then only display filter
        $content .= '<form method="post" class="sac-form" action="' . get_permalink() . '">';

        $content .= '<div class="control">';
        $content .= '<select name="sac_post_types" class="sac-post-types">';
        $content .= '<option value="">' . __('Select Post Type') . '</option>';
        $bt_post_type = get_option('bt_post_type');

        if ($bt_post_type != null) {

            sort($bt_post_type);
            foreach ($bt_post_type as $type) {
                if (isset($_REQUEST['sac_post_types']) && !empty($_REQUEST['sac_post_types'])) {
                    if ($type == $_REQUEST['sac_post_types']) {

                        $content .= '<option value="' . $type . '" selected>' . $type . '</option>';
                    } else {

                        $content .= '<option value="' . $type . '">' . $type . '</option>';
                    }
                } else {
                    $content .= '<option value="' . $type . '">' . $type . '</option>';
                }
            }
        }
        $content .= '</select>';
        $content .= '</div>';

        $content .= '<div class="control">';
        $content .= '<select name="sac_category" class="sac-category" style="display:none;">';
        $content .= '<option value="">' . __('Select Category') . '</option>';
        $categories = get_categories();
        if ($categories != null) {
            foreach ($categories as $post_category) {
                if (isset($_REQUEST['sac_category']) && !empty($_REQUEST['sac_category'])) {
                    if ($post_category->term_id == $_REQUEST['sac_category']) {

                        $content .= '<option value="' . $post_category->term_id . '" selected>' . $post_category->name . '</option>';
                    } else {

                        $content .= '<option value="' . $post_category->term_id . '">' . $post_category->name . '</option>';
                    }
                } else {
                    $content .= '<option value="' . $post_category->term_id . '">' . $post_category->name . '</option>';
                }
            }
        }
        $content .= '</select>';
        $content .= '</div>';

        $content .= '<div class="control">';
        $content .= '<select name="sac_posts" class="sac-posts">';
        $content .= '<option value="">' . __('Select Post') . '</option>';
        $content .= '</select>';
        $content .= '</div>';

        $content .= '<div class="control">';
        $content .= '<input type="submit" value="' . __('Filter') . '" />';
        $content .= '</div>';

        $content .= '</form>';
    }
    if ($comments != null) {

        $parent_comment_count = 0;
        foreach ($comments as $parent_comment) {

            if ($parent_comment->comment_parent == 0) {

                $parent_comment_count ++;
            }
        }

        $query_arg = array(
            'cpage' => '%#%',
        );

        if (isset($_REQUEST['sac_post_types']) && $_REQUEST['sac_post_types'] != null) {

            $query_arg['sac_post_types'] = $_REQUEST['sac_post_types'];
        }

        if (isset($_REQUEST['sac_category']) && $_REQUEST['sac_category'] != null) {

            $query_arg['sac_category'] = $_REQUEST['sac_category'];
        }

        if (isset($_REQUEST['sac_posts']) && $_REQUEST['sac_posts'] != null) {

            $query_arg['sac_posts'] = $_REQUEST['sac_posts'];
        }


        ob_start();
        $content .= "<ul class=custom-comments>";
        wp_list_comments(array(
            'walker' => null,
            'max_depth' => '',
            'style' => 'ul',
            'callback' => 'custom_comments_template',
            'end-callback' => '',
            'type' => 'all',
            'reply_text' => 'Reply',
            'avatar_size' => 32,
            'reverse_top_level' => null,
            'reverse_children' => '',
            'format' => 'HTML5',
            'short_ping' => false
                ), $comments);
        $content .= ob_get_clean();
        $content .= "</ul>";

        $content .= "<div class=custom-navigation>";
        ob_start();
        paginate_comments_links(array(
            'base' => add_query_arg($query_arg),
            'total' => ceil($parent_comment_count / get_query_var('comments_per_page')),
            'current' => $page
        ));
        echo "</div>";
        $content .= ob_get_clean();
    } else {

        $content .= '<div class="not-found">' . __('Comments not found.') . '</div>';
    }

    wp_enqueue_style('sac-style');
    wp_enqueue_script('sac-script');
    $content .= '</div>';

    return $content;
}

add_action('wp_ajax_sac_post_type_call', 'sac_post_type_call_callback');
add_action('wp_ajax_nopriv_sac_post_type_call', 'sac_post_type_call_callback');

function sac_post_type_call_callback() {

    $post_type = $_REQUEST['post_type'];

    $exclude_post = get_option('bt_exclude_post');
    $exclude_post = explode(',', $exclude_post);

    $args = array(
        'posts_per_page' => -1,
        'offset' => 0,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_type' => $post_type,
        'post__not_in' => $exclude_post,
        'post_status' => 'publish',
        'suppress_filters' => true,
    );

    if ($_REQUEST['post_category'] != null && $_REQUEST['post_type'] == 'post') {

        $args['category'] = $_REQUEST['post_category'];
    }

    $posts = get_posts($args);
    ?>
    <option value=""><?php
        _e('Select ');
        echo ucfirst($post_type);
        ?></option>
    <?php
    if ($posts != null) {

        foreach ($posts as $post) {

            if ($post->ID == $_REQUEST['post_id']) {
                ?>
                <option value="<?php echo $post->ID; ?>" selected><?php echo $post->post_title; ?></option>
                <?php
            } else {
                ?>
                <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                <?php
            }
        }
    }

    wp_die();
}

// styles and scripts
add_action('wp_enqueue_scripts', 'sac_wp_enqueue_styles_and_scripts');

function sac_wp_enqueue_styles_and_scripts() {

    // styles
    wp_register_style('sac-style', plugins_url('css/sac-style.css', __FILE__));

    // scripts
    wp_register_script('sac-script', plugins_url('js/sac-script.js', __FILE__));
    wp_enqueue_script('jquery');
    $ajax_url = admin_url('admin-ajax.php') . '?cache=' . time();
    wp_localize_script('sac-script', 'sac_ajax_url', $ajax_url);
    if (isset($_REQUEST['sac_posts'])) {
        $sac_posts = $_REQUEST['sac_posts'];
        wp_localize_script('sac-script', 'sac_posts', $sac_posts);
    }
    $sac_category = '';
    if (isset($_REQUEST['sac_post_types']) && $_REQUEST['sac_post_types'] == 'post') {
        $sac_category = $_REQUEST['sac_category'];
        wp_localize_script('sac-script', 'sac_category', $sac_category);
    }
}

add_filter('pre_option_page_comments', '__return_true');

function custom_comments_template($comment, $args, $depth) {

    // show comments 
    $GLOBALS['comment'] = $comment;
    $getAvatarSize = get_option('biztech_sac_avatar');
    $getdate = get_option('biztech_show_date');
    $open_new_tab = get_option('biztech_open_new_tab');
    $show_post_link = get_option('bt_show_post_link');
    $show_comment_link = get_option('bt_show_comment_link');
    ?>
    <li>
        <div class="avatar-custom"><?php echo get_avatar($comment, $getAvatarSize); ?></div>
        <div class="custom-comment-wrap">
            <h4 class="custom-comment-meta">
                <?php _e('From'); ?> <span class="custom-comment-author"><?php echo $comment->comment_author; ?></span>
                <?php _e('on'); ?> 
                <?php
                if (isset($show_post_link) && $show_post_link == "yes") {
                    ?>
                    <span class = "custom-comment-on-title"><a href = "<?php echo esc_url(get_permalink($comment->comment_post_ID)); ?>" target = "_blank"><?php echo $comment->post_title; ?></a></span>
                    <?php
                } else {
                    ?>
                    <span class = "custom-comment-on-title"><?php echo $comment->post_title; ?></span>
                    <?php
                }
                ?>

            </h4>
            <blockquote><?php echo apply_filters("the_content", $comment->comment_content); ?></blockquote>
            <?php
            if (isset($show_comment_link) && ( $show_comment_link == 'yes' )) {
                if (isset($open_new_tab) && $open_new_tab == 'on') {
                    $new_tab = 'target="_blank"';
                } else {
                    $new_tab = "";
                }
                ?>
                <span class="custom-comment-link"><a href="<?php echo $comment->guid . '#comment-' . $comment->comment_ID; ?>" <?php echo $new_tab; ?>><?php _e('Go to comment'); ?></a></span><br>
                <?php
            }
            ?>            
            <?php
            if (isset($getdate) && $getdate == 'on') {
                ?>
                <span class="custom-comment-link"><?php echo date('Y/m/d \a\t g:i a', strtotime($comment->comment_date)); ?></span>    
                <?php
            }
            ?>
        </div>
    </li>
    <?php
}

register_uninstall_hook(__FILE__, 'bt_comments_uninstall'); // uninstall plug-in

function bt_comments_uninstall() {
    delete_option('bt_post_type');
    delete_option('bt_pagination');
    delete_option('bt_comments_per_page');
    delete_option('bt_exclude_post');
    delete_option('biztech_sac_avatar');
    delete_option('bt_display_filter');
    delete_option('bt_show_comment_link');
}
