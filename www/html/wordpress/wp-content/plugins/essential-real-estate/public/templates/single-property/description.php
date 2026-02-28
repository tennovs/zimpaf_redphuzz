<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 15/08/2017
 * Time: 08:14 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$content = get_the_content();
if (isset($content) && !empty($content)): ?>
<div class="single-property-element property-description">
    <div class="ere-heading-style2">
        <h2><?php esc_html_e( 'Description', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="ere-property-element">
        <?php the_content(); ?>
    </div>
</div>
<?php endif; ?>