<?php
    if (is_user_logged_in()) {
        if (!current_user_can('read_udraw_global_templates')) {
            exit;
        }
    } else {
        exit;
    }

    if (current_user_can('edit_udraw_global_templates')) {
        if (isset($_GET['create_udraw_product'])) {
            $uDrawUtil = new uDrawUtil();
            $json = $uDrawUtil->get_web_contents(UDRAW_DRAW_SERVER_URL .'/api/templates/'. $_GET['create_udraw_product']);
            $udrawProduct = json_decode($json);
            if (strlen($udrawProduct[0]->AccessKey) > 1) {
                // got product.
                global $wpdb;
                $uDrawSettings = new uDrawSettings();
                $_udraw_settings = $uDrawSettings->get_settings();
                $table_name = $_udraw_settings['udraw_db_udraw_templates'];     
                $sql = "SELECT * FROM $table_name WHERE public_key = '". $udrawProduct[0]->AccessKey ."'";
                $results = $wpdb->get_row($sql, ARRAY_A);            
                $dateObj = new DateTime($udrawProduct[0]->LastModified);            
                if (strlen($results["id"]) == 0) {
                    // Create new record in DB.
                    $wpdb->insert($table_name, array(
                            'name' => $udrawProduct[0]->Name,
                            'design' => $udrawProduct[0]->XMLLocation,
                            'preview' => $udrawProduct[0]->PreviewLocation,
                            'pdf' => $udrawProduct[0]->PDFLocation,
                            'create_date' => $dateObj->format('Y-m-d H:i:s'),
                            'create_user' => wp_get_current_user()->user_login,
                            'design_width' => $udrawProduct[0]->Width/72,
                            'design_height' => $udrawProduct[0]->Height/72,
                            'design_pages' => $udrawProduct[0]->Pages,
                            'public_key' => $udrawProduct[0]->AccessKey
                        ));
                }
                $results = $wpdb->get_row($sql, ARRAY_A);
                echo "<script>\n";
                echo "location.href = \"post-new.php?post_type=product&udraw_template_id=". $results["id"] . "&udraw_action=new-product\";";
                echo "</script>\n";
            }
        }
    }
?>


<style>
	.column-preview {
		float: left;
	}
</style>
<div class="wrap" id="manage-designs-page">
    <h1><i class="fa fa-picture-o"></i>Global Templates</h1>

    <?php 
        $option = 'per_page';
        $args = array(
            'label' => 'Books',
            'default' => 10,
            'option' => 'books_per_page'
        );
        add_screen_option($option, $args);
        $myListTable = new uDraw_Public_Templates_Table();
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
        imgPreview.style.maxWidth = "590px";
        imgPreview.style.maxHeight = "540px";
        _templatePreview.appendChild(imgPreview);
    }
    
</script>
    