<?php
/**
 * @var $total_post
 * @var $taxonomy_title
 * @var $agent_id
 * @var $author_id
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="ere-heading">
                <?php if (is_tax()): ?>
    <h2><?php echo esc_html($taxonomy_title); ?>
        <sub>(<?php echo ere_get_format_number($total_post); ?>)</sub></h2>
<?php elseif (!empty($agent_id) && $agent_id > 0):
    $agent_name = get_the_title($agent_id);
    ?>
    <h2><?php echo esc_html($agent_name); ?>
        <sub>(<?php echo ere_get_format_number($total_post); ?>)</sub></h2>
<?php elseif (!empty($author_id) && $author_id > 0):
    $user_info = get_userdata($author_id);
    if (empty($user_info->first_name) && empty($user_info->last_name)) {
        $agent_name = $user_info->user_login;
    } else {
        $agent_name = $user_info->first_name . ' ' . $user_info->last_name;
    }
    ?>
    <h2><?php echo esc_html($agent_name); ?>
        <sub>(<?php echo ere_get_format_number($total_post); ?>)</sub></h2>
<?php else: ?>
    <h2><?php esc_html_e('Properties', 'essential-real-estate') ?>
        <sub>(<?php echo ere_get_format_number($total_post); ?>)</sub></h2>
<?php endif; ?>
</div>