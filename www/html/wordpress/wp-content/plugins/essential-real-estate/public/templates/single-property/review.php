<?php
global $wpdb;
wp_enqueue_script('star-rating');
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$property_id = get_the_ID();
$property_rating = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_rating', true);
$rating = $total_reviews = $total_stars = 0;

$comments_query = "SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = $property_id AND meta.meta_key = 'property_rating' AND meta.comment_id = comment.comment_ID AND ( comment.comment_approved = 1 OR comment.user_id = $user_id )";
$get_comments = $wpdb->get_results($comments_query);
$my_review = $wpdb->get_row("SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = $property_id AND comment.user_id = $user_id  AND meta.meta_key = 'property_rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC");

if (!is_null($get_comments)) {
    foreach ($get_comments as $comment) {
        if ($comment->comment_approved == 1) {
            $total_reviews++;
            $total_stars += $comment->meta_value;
        }
    }
    if ($total_reviews != 0) {
        $rating = ($total_stars / $total_reviews);
    }
}
?>
<div class="single-property-element property-reviews">
    <div class="ere-heading-style2">
        <h2><?php esc_html_e('Ratings & Reviews', 'essential-real-estate'); ?></h2>
    </div>
    <div class="ere-property-element">
        <div class="aggregate-rating" data-score="<?php echo round($rating, 2); ?>" itemscope
             itemtype="<?php echo ere_server_protocol(); ?>schema.org/AggregateRating">
            <div class="ratings-summary">
                <span class="ratings-average" itemprop="ratingValue"><?php echo round($rating, 2); ?></span>
                <input class="rating ere-show-rating-only" name="rating" value="<?php echo esc_attr($rating); ?>"
                       type="text" data-size="sm">
                <span class="ratings-count"
                      itemprop="reviewCount"><?php printf(_n('%s Review', '%s Reviews', $total_reviews, 'essential-real-estate'), $total_reviews); ?></span>
            </div>
            <div class="overall-rating">
                <ul class="reviews-box">
                    <?php
                    for ($i = 5; $i >= 1; $i--):?>
                        <li>
                            <span class="label"><?php echo esc_html($i); ?></span>
                            <span class="item-list">
                                <?php if ($total_reviews > 0) { ?>
                                    <span
                                        style="width: <?php echo esc_attr($property_rating[$i] > 0 && $total_reviews > 0 ? (round(($property_rating[$i] / $total_reviews) * 100, 2)) : 0) ; ?>%"></span>
                                <?php } else { ?>
                                    <span style="width: 0%"></span>
                                <?php } ?>
                            </span>
                            <span
                                class="label"><?php echo esc_attr(isset($property_rating[$i]) && $property_rating[$i] > 0 && $total_reviews > 0 ? (round(($property_rating[$i] / $total_reviews) * 100, 2)) : 0); ?>
                                %</span>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <h4 class="reviews-count"><?php printf(_n('%s Review', '%s Reviews', $total_reviews, 'essential-real-estate'), $total_reviews); ?></h4>
        <ul class="reviews-list">
            <?php if (!is_null($get_comments)) {
                foreach ($get_comments as $comment) {
                    $user_custom_picture = get_the_author_meta(ERE_METABOX_PREFIX . 'author_custom_picture', $comment->user_id);
                    $author_picture_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_picture_id', $comment->user_id);
                    $no_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
                    $width = 80;
                    $height = 80;
                    $default_avatar = ere_get_option('default_user_avatar', '');
                    if ($default_avatar != '') {
                        if (is_array($default_avatar) && $default_avatar['url'] != '') {
                            $resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
                            if ($resize != null && is_array($resize)) {
                                $no_avatar_src = $resize['url'];
                            }
                        }
                    }
                    $user_link = get_author_posts_url($comment->user_id);
                    ?>
                    <li class="media" itemscope itemtype="<?php echo ere_server_protocol(); ?>schema.org/Review">
                        <div class="media-left" itemprop="author" itemscope
                             itemtype="<?php echo ere_server_protocol(); ?>schema.org/Person">
                            <figure>
                                <?php
                                if (!empty($author_picture_id)) {
                                    $author_picture_id = intval($author_picture_id);
                                    if ($author_picture_id) {
                                        $avatar_src = ere_image_resize_id($author_picture_id, $width, $height, true);
                                        ?><a href="<?php echo esc_url($user_link); ?>">
                                        <img src="<?php echo esc_url($avatar_src); ?>"
                                             onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                                             alt="<?php the_author_meta('display_name', $comment->user_id); ?>"></a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo esc_url($user_link); ?>">
                                        <img src="<?php echo esc_url($user_custom_picture); ?>"
                                             onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
                                             alt="<?php the_author_meta('display_name', $comment->user_id); ?>"></a>
                                    <?php
                                }
                                ?>
                            </figure>
                        </div>
                        <div class="media-body" itemprop="reviewBody">
                            <h4 class="media-heading"><a
                                    href="<?php echo esc_url($user_link); ?>"><?php the_author_meta('display_name', $comment->user_id); ?></a>
                            </h4>

                            <div class="">
                                <span
                                    class="review-date"><?php echo ere_get_comment_time($comment->comment_id); ?></span>
                            <span class="rating-wrap">
                                    <input class="rating ere-show-rating-only"
                                           value="<?php echo esc_attr($comment->meta_value); ?>" type="text"
                                           data-size="xs">
                            </span>
                            </div>
                            <p class="review-content"> <?php echo esc_html($comment->comment_content); ?> </p>
                            <?php if ($comment->comment_approved == 0) { ?>
                                <span
                                    class="waiting-for-approval"> <?php esc_html_e('Waiting for approval', 'essential-real-estate'); ?> </span>
                            <?php } ?>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <div class="add-new-review">
            <?php
            if (!is_user_logged_in()) {
                echo '<h4 class="review-title"><a class="login-for-review" href="#" data-toggle="modal" data-target="#ere_signin_modal">' . esc_html__('Login for Review', 'essential-real-estate') . '</a></h4>';
            } else {
                ?>
                <h4 class="review-title"> <?php esc_html_e('Write a Review', 'essential-real-estate'); ?> </h4>
                <?php
                if (is_null($my_review)) {
                    ?>
                    <form method="post" action="#">
                        <div class="form-group">
                            <label class="sr-only"
                                   for="property_rating"> <?php esc_html_e('Write a Review', 'essential-real-estate'); ?> </label>
                            <input id="property_rating" name="rating" value="5" type="text" data-size="md"
                                   class="rating ere-rating">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="5" name="message"
                                      placeholder="<?php esc_attr_e('Your review', 'essential-real-estate'); ?>"></textarea>
                        </div>
                        <button type="submit"
                                class="ere-submit-property-rating btn btn-default"><?php esc_html_e('Submit Review', 'essential-real-estate'); ?></button>
                        <?php wp_nonce_field('ere_submit_review_ajax_nonce', 'ere_security_submit_review'); ?>
                        <input type="hidden" name="action" value="ere_property_submit_review_ajax">
                        <input type="hidden" name="property_id" value="<?php the_ID(); ?>">
                    </form>
                    <?php
                } else {
                    ?>
                    <form method="post" action="#">
                        <div class="form-group">
                            <label class="sr-only"
                                   for="property_rating"> <?php esc_html_e('Rate This Property', 'essential-real-estate'); ?> </label>
                            <input id="property_rating" name="rating"
                                   value="<?php echo esc_attr($my_review->meta_value); ?>" type="text" data-size="md"
                                   class="rating ere-rating">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="5" name="message"
                                      placeholder="<?php esc_attr_e('Your review', 'essential-real-estate'); ?>"><?php echo esc_html($my_review->comment_content); ?></textarea>
                        </div>
                        <button type="submit"
                                class="ere-submit-property-rating btn btn-default"><?php esc_html_e('Update Review', 'essential-real-estate'); ?></button>
                        <?php wp_nonce_field('ere_submit_review_ajax_nonce', 'ere_security_submit_review'); ?>
                        <input type="hidden" name="action" value="ere_property_submit_review_ajax">
                        <input type="hidden" name="property_id" value="<?php the_ID(); ?>">
                    </form>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $('.ere-rating').rating({
                step: 1,
                showClear: false,
                showCaption: false
            });
            $('.ere-show-rating-only').rating({disabled: true, showClear: false, showCaption: false});
            $('.ere-submit-property-rating').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var $form = $this.parents('form');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo ERE_AJAX_URL ?>',
                    data: $form.serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $this.children('i').remove();
                        $this.append('<i class="fa-left fa fa-spinner fa-spin"></i>');
                    },
                    success: function () {
                        window.location.reload();
                    },
                    complete: function () {
                        $this.children('i').removeClass('fa fa-spinner fa-spin');
                        $this.children('i').addClass('fa fa-check');
                    }
                });

            });
        });
    </script>
</div>