<?php
    if (is_user_logged_in()) {
        if (!current_user_can('read_udraw_price_matrix')) {
            exit;
        }
    } else {
        exit;
    }
?>

<style>
	.column-preview {
		float: left;
	}
</style>
<div class="wrap" id="manage-price-matrix">
    <h1>
        <i class="fa fa-picture-o"></i><?php _e('View Price Matrix Sets ', 'udraw') ?>
        <a href="admin.php?page=udraw_manage_price_matrix" class="add-new-h2 button-primary"><?php _e('Add New', 'udraw') ?></a>   
    </h1>             

    <?php

    $option = 'per_page';
    $args = array(
        'label' => 'Books',
        'default' => 10,
        'option' => 'books_per_page'
    );
    add_screen_option($option, $args);
    $myListTable = new uDraw_Price_Matrix_Table();
    $myListTable->prepare_items();
    ?>
	<div style="padding-top:10px;">
		<form method="post">
			<input type="hidden" name="page" value="udraw_price_matrix">			
			<?php
            $myListTable->search_box('search', 'search_id');
			$myListTable->display();
			?>
		</form>
	</div>
</div>