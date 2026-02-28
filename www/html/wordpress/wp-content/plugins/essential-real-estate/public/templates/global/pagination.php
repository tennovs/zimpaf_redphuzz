<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $max_num_pages
 */
if ( $max_num_pages <= 1 ) {
	return;
}
global $wp_rewrite;
global $paged;
if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
else { $paged = 1; }
$pagenum_link = html_entity_decode( get_pagenum_link() );
$query_args   = array();
$url_parts    = explode( '?', $pagenum_link );

if ( isset( $url_parts[1] ) ) {
	wp_parse_str( $url_parts[1], $query_args );
}

$pagenum_link = esc_url(remove_query_arg( array_keys( $query_args ), $pagenum_link ));
$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';
?>
<div class="paging-navigation clearfix">
	<?php echo  paginate_links( apply_filters( 'ere_pagination_args', array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'ere_urlencode', $query_args ),
		'prev_text' => esc_html__('Previous','essential-real-estate') ,
		'next_text' => esc_html__('Next','essential-real-estate'),
	) )); ?>
</div>