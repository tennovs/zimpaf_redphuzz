<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 30/12/2016
 * Time: 8:04 SA
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$social_sharing    = ere_get_option( 'social_sharing', array() );
$sharing_facebook = $sharing_twitter = $sharing_google = $sharing_linkedin = $sharing_tumblr = $sharing_pinterest = $sharing_whatsup = '';
if(is_array( $social_sharing ) && count( $social_sharing ) > 0) {
    $sharing_facebook  = in_array( 'facebook', $social_sharing );
    $sharing_twitter   = in_array( 'twitter', $social_sharing );
    $sharing_google    = in_array( 'google', $social_sharing );
    $sharing_linkedin  = in_array( 'linkedin', $social_sharing );
    $sharing_tumblr    = in_array( 'tumblr', $social_sharing );
    $sharing_pinterest = in_array( 'pinterest', $social_sharing );
	$sharing_whatsup = in_array( 'whatsup', $social_sharing );
}
if ( ! $sharing_facebook && ! $sharing_twitter && ! $sharing_google && ! $sharing_linkedin && ! $sharing_tumblr && ! $sharing_pinterest && !$sharing_whatsup ) {
    return;
}
?>
<div class="social-share">
    <div class="social-share-hover">
        <i class="fa fa-share-alt" aria-hidden="true"></i>
        <div class="social-share-list">
            <div class="list-social-icon clearfix">
                <?php if ( $sharing_facebook == 1 ) :?>
                    <a  target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink())?>">
                        <i class="fa fa-facebook"></i>
                    </a>
                <?php endif; ?>

                <?php if ( $sharing_twitter == 1 ) : ?>
                    <a href="javascript: window.open('http://twitter.com/share?text=<?php echo urlencode(get_the_title())?>&url=<?php echo urlencode( get_permalink()) ?>','_blank', 'width=900, height=450')">
                        <i class="fa fa-twitter"></i>
                    </a>
                <?php endif; ?>

                <?php if ( $sharing_linkedin == 1 ): ?>
                    <a href="javascript: window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink()) ?>&title=<?php echo urlencode(get_the_title())?>','_blank', 'width=500, height=450')">
                        <i class="fa fa-linkedin"></i>
                    </a>
                <?php endif; ?>

                <?php if ( $sharing_tumblr == 1 ) : ?>
                    <a href="javascript: window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode( get_permalink()) ?>&name=<?php echo  urlencode( get_the_title()); ?>','_blank', 'width=500, height=450')">
                        <i class="fa fa-tumblr"></i>
                    </a>
                <?php endif; ?>

                <?php if ( $sharing_pinterest == 1 ) : ?>
                    <?php
	                    $_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	                    $_img_src = '';
                        if (is_array($_img) && isset($_img[0])) {
                        	$_img_src = $_img[0];
                        }
                    ?>
                    <a href="javascript: window.open('http://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink()) ?>&media=<?php echo esc_attr($_img_src)?>&description=<?php echo urlencode( get_the_title()) ?>','_blank', 'width=900, height=450')">
                        <i class="fa fa-pinterest"></i>
                    </a>
                <?php endif; ?>
	            <?php if ($sharing_whatsup == 1): ?>
		            <a target="_blank" href="https://wa.me/?text=<?php echo urlencode( get_permalink())?>"><i class="fa fa-whatsapp"></i></a>
	            <?php endif; ?>
            </div>
        </div>
    </div>
</div>