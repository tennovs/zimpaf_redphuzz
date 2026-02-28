<?php

?>
<style>
    .udraw-cat-list ui {
    }
                
    .udraw-cat-list li {
        display: inline;
        float: left;
        padding: 3px;
        border: 1px #D7D7D7 solid;
        max-width: 200px;
        max-height: 267px;
        min-height: 267px;
        margin: 3px;
        text-align: center;
    }

    .udraw-cat-list li table {
        min-height: 267px;
        min-width: 200px;
    }
</style>

<div id="udraw-bootstrap" style="padding-left:30px; padding-bottom:7px;">
    <button id="previous-button-category" class="btn btn-default" style="display:none;"><i class="fa fa-chevron-left"></i>&nbsp;Previous</button>
</div>
<?php

// Generate Top Level Categories
generateCategoriesHtml(0);
$_terms = get_terms( 'product_cat', array ( 'parent' => 0, 'hide_empty' => false ) );
foreach ($_terms as $term) {
    $children = get_term_children( $term->term_id, 'product_cat' );
    if (count($children) > 0) {
        generateCategoriesHtml($term->term_id);
    }
}


function generateCategoriesHtml($parent) {
    // Get all possible categories
    $tax_name = 'product_cat';
    $args = array( 
        'parent' => $parent,
        'hide_empty' => false
    );
    $terms = get_terms( $tax_name, $args );
    
    if ( $terms ){
        if ($parent == 0) {
            echo '<div id="udraw-cat-list-'. $parent . '" class="udraw-cat-list">';
        } else {
            echo '<div id="udraw-cat-list-'. $parent . '" class="udraw-cat-list" style="display:none;">';
        }
        echo '<ul>';
        foreach ( $terms as $term ) {
            $children = get_term_children( $term->term_id, $tax_name );        
    ?>
        <li>                    
            <table>
                <tbody>
                    <tr>
                    <td>
                    <?php if (count($children) > 0) { ?>
                        <a href="#" onclick="shortcode_displaySubCategory('<?php echo $term->term_id; ?>');">
                    <?php } else { ?>
                        <a href="/?product_cat=<?php echo $term->slug ?>">
                    <?php } ?>
                    <?php
            $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
            $image = wp_get_attachment_image( $thumbnail_id );
            if ( $image ) {
                echo $image;
            } else {
                        
                echo '<img class="alignnone size-thumbnail" src="'. UDRAW_PLUGIN_URL . '/assets/includes/no_image.jpg" alt="' . $term->name . '" style="max-width:150px;max-height:150px;" />';
            }
                    ?>                                
                    </a>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <?php if (count($children) > 0) { ?>
                            <a href="#" onclick="shortcode_displaySubCategory('<?php echo $term->term_id; ?>');">
                        <?php } else { ?>
                            <a href="/?product_cat=<?php echo $term->slug ?>">
                        <?php } ?>
                        <h2 style="text-align: center; min-height:50px;"><?php echo $term->name; ?></h2>
                        </a>
                    </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <?php
        }
        echo '</ul>';
        echo '</div>';
    }
}
?>



<script type="text/ecmascript">
    
    jQuery(document).ready(function () {
        jQuery('#previous-button-category').click(function () {
            jQuery('#previous-button-category').hide();
            jQuery('.udraw-cat-list').fadeOut();
            jQuery('#udraw-cat-list-0').fadeIn();            
        });
    });

    function shortcode_displaySubCategory(id) {        
        jQuery('.udraw-cat-list').fadeOut();
        jQuery('#udraw-cat-list-' + id).fadeIn();
        jQuery('#previous-button-category').fadeIn();
    }

</script>
