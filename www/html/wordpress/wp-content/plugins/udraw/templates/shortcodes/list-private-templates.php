<?php
global $woocommerce;
$uDraw = new uDraw();


$current_user = wp_get_current_user();
if ( !($current_user instanceof WP_User) ) {
    return;
}

$loop = $uDraw->get_udraw_private_templates($current_user->ID);
            
woocommerce_product_loop_start();
woocommerce_product_subcategories();
?>
    <style>
        ul.products li.product {
            width: 21%;
        }
    </style>
<?php            
while ( $loop->have_posts() ) : $loop->the_post();
    if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
        $product = wc_get_product();
        if ($product) {
            $product_id = $product->get_id();
        }
    } else {
        $product = get_product();
        if ($product) {
            $product_id = $product->id;
        }
    }
    if ($product) {
        if (get_post_meta($product_id, '_udraw_is_private_product', true) == "yes") {
            require(UDRAW_PLUGIN_DIR . '/templates/frontend/uDraw-private-product.php');
        }
    }
endwhile;

$uDraw_SVG = new uDraw_SVG();
$svg_loop = $uDraw_SVG->get_private_templates($current_user->ID);

while ( $svg_loop->have_posts() ) : $svg_loop->the_post();
    if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
        $product = wc_get_product();
        if ($product) {
            $product_id = $product->get_id();
        }
    } else {
        $product = get_product();
        if ($product) {
            $product_id = $product->id;
        }
    }
    if ($product) {
        $is_svg_private_product = (metadata_exists('post', $product_id, '_udraw_SVG_private_product')) ? 
            get_post_meta($product_id, '_udraw_SVG_private_product', true) : false;
        if ($is_svg_private_product) {
            require(UDRAW_PLUGIN_DIR . '/templates/frontend/uDraw-private-product.php');
        }
    }
endwhile;

woocommerce_product_loop_end();         
wp_reset_query();                        

?>