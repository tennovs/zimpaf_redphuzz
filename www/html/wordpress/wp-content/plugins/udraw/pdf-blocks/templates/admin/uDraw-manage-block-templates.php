<?php
    if (is_user_logged_in()) {
        if (!current_user_can('read_udraw_block_templates')) {
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
    #TB_ajaxContent {
        text-align: center !important;
    }
</style>
<div class="wrap" id="manage-designs-page">
    <h1>
        <i class="fa fa-picture-o"></i><?php _e('PDF Templates', 'udraw') ?>
    </h1>                

    <?php

    $option = 'per_page';
    $args = array(
        'label' => 'Books',
        'default' => 10,
        'option' => 'books_per_page'
    );
    add_screen_option($option, $args);
    $myListTable = new uDraw_Block_Templates_Table();
    $myListTable->prepare_items();
    $myListTable->views();
    ?>
	<div style="padding-top:10px;">
		<form method="get">
			<input type="hidden" name="page" value="udraw_global_template">			
			<?php            
			//$myListTable->search_box('search', 'search_id');            
			$myListTable->display();
			?>
		</form>
	</div>
</div>

<?php add_thickbox(); ?>
<div id="template-preview-thickbox" style="display:none;">

</div>

<script>
    
    function PreviewTemplate(imageSrc) {
        var _templatePreview = document.getElementById("template-preview-thickbox");
        _templatePreview.innerHTML = "";
         var imgPreview = document.createElement("img");
        imgPreview.src = imageSrc;
        //imgPreview.setAttribute("width", "100%");
        //imgPreview.style.maxWidth = "590px";
        //imgPreview.style.maxHeight = "540px";
        _templatePreview.appendChild(imgPreview);
    }
    
</script>
    