<?php

if ( ! defined( 'ABSPATH' ) ){
    exit;
}

function arplite_my_function_admin_bar(){ return false; }
add_filter( 'show_admin_bar' , 'arplite_my_function_admin_bar');
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php bloginfo('name'); ?></title>

<?php 
global $arplite_pricingtable;

$arplite_pricingtable->set_front_css();
$arplite_pricingtable->set_front_js();

$upload_main_url 	= ARPLITE_PRICINGTABLE_UPLOAD_URL.'/css';

wp_print_scripts();

$tbl_preview = ( isset( $_REQUEST['home_view'] ) && '1' == $_REQUEST['home_view'] ) ? 2 : 1;

do_action('arplite_front_inline_css', $table_id, $tbl_preview);

?>
</head>

<body class="arp_body_content">
<?php 

require_once ARPLITE_PRICINGTABLE_DIR.'/core/views/arprice_front.php';

$pricetable_name = '';

if(isset($_REQUEST['home_view']) && $_REQUEST['home_view'] == '1') {
    $contents = arplite_get_pricing_table_string( $table_id, $pricetable_name, 2 );
} else {
    $contents = arplite_get_pricing_table_string( $table_id, $pricetable_name, 1 );
}


$contents = apply_filters('arplite_predisplay_pricingtable', $contents, $table_id);
				
echo $contents;

?>
<?php 
if( isset( $opts ) )
{
	$googlemap = 0;
	if( $opts['columns'] )
	{
		foreach( $opts['columns'] as $columns )
		{
			$html_content	= $columns['arp_header_shortcode'];
			if( preg_match('/arp_googlemap/', $html_content) )
				$googlemap = 1;														
		}	
	}
}
do_action('wp_footer');
?>
</body>

</html>