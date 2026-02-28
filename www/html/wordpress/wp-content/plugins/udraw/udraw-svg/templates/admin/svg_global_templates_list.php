<h2>
    <?php _e('View Global Templates', 'udraw_svg') ?>
</h2>
<?php
    if (is_user_logged_in()) {
        if (!current_user_can('read_udraw_templates')) {
            exit;
        }
    } else {
        exit;
    }
    global $wpdb;
    
    function make_uniqid_folder_id ($base_dir) {
        $rand_id = uniqid();
        if (!file_exists($base_dir . $rand_id)) {
            wp_mkdir_p($base_dir . $rand_id);
            return $rand_id;
        } else {
            return $this->make_uniqid_folder_id($base_dir);
        }
    }
    
    function copy_design ($design) {
        $uDrawUtil = new uDrawUtil();
        $design_json = json_decode($uDrawUtil->get_web_contents($design->DesignLocation));
        $pages = $design_json->pages;
        $new_json = array();
        $_output_path = UDRAW_STORAGE_DIR . '/_templates_/output/';
        $_output_path_url = UDRAW_STORAGE_URL . '/_templates_/output/';
        
        $session_id = make_uniqid_folder_id($_output_path);
        for ($i = 0; $i < count($pages); $i++) {
            $design_file_src = $pages[$i]->design_file;
            $preview_url_src = $pages[$i]->preview_url;
            
            $new_design_file = $session_id . '/' . $session_id . '_page_' . $i . '.svg';
            $new_preview_url = $session_id . '/' . $session_id . '_page_' . $i . '.png';
            
            $uDrawUtil->download_file($design_file_src, $_output_path . $new_design_file);
            $uDrawUtil->download_file($preview_url_src, $_output_path . $new_preview_url);
            
            $new_page = array(
                'design_file' => $_output_path_url . $new_design_file,
                'preview_url' => $_output_path_url . $new_preview_url,
                'label' => 'Page ' . ($i + 1)
            );
            array_push($new_json, $new_page);
        }
        
        $contents = array(
            'session_id' => $session_id,
            'pages' => $new_json
        );
        $result = file_put_contents($_output_path . $session_id . '/' . $session_id . '.json', stripslashes(json_encode($contents)));
        
        return $_output_path_url . $session_id . '/' . $session_id . '.json';
    }
    
    if (current_user_can('edit_udraw_global_templates')) {
        if (isset($_GET['create_udraw_svg_product'])) {
            $uDrawUtil = new uDrawUtil();
            $template_url = UDRAW_DRAW_SERVER_URL .'/api/svg/'. $_GET['create_udraw_svg_product'];
            $json = $uDrawUtil->get_web_contents($template_url);
            $design = json_decode($json)[0];
            //Create a copy of the files and store it locally
            $json_file = copy_design($design);
            
            if ($design && strlen($design->AccessKey) > 1) {
                // got product.
                global $wpdb;
                $table_name = $wpdb->prefix . 'udraw_svg_templates';     
                $sql = "SELECT * FROM $table_name WHERE access_key = '". 'udraw_SVG_' . $design->AccessKey ."'";
                $template_copy = $wpdb->get_row($sql, ARRAY_A);            
                $dateObj = new DateTime($design->LastModified);
                //Check that there isn't a copy of this template in db already
                if (strlen($template_copy["id"]) == 0) {
                    // Create new record in DB.
                    $wpdb->insert($table_name, array(
                        'name' => $design->Name,
                        'design_path' => $json_file,
                        'preview' => $design->PreviewLocation,
                        'date' => $dateObj->format('Y-m-d H:i:s'),
                        'create_user_id' => get_current_user_id(),
                        'access_key' => 'udraw_SVG_' . $design->AccessKey
                    ));
                }
                $results = $wpdb->get_row($sql, ARRAY_A);
                echo "<script>\n";
                echo "location.href = \"post-new.php?post_type=product&udraw_svg_template_id=". $results["ID"] . "&udraw_svg_action=new-product\";";
                echo "</script>\n";
            }
        }
    }
    
    $option = 'per_page';
    $args = array(
        'label' => 'Templates',
        'default' => 10,
        'option' => 'templates_per_page'
    );
    
    $udraw_svg_global_table = new uDraw_SVG_Global_Templates_Table();
    $udraw_svg_global_table->prepare_items();
    $udraw_svg_global_table->views();
?>
<form method="get">
    <input type="hidden" name="page" value="udraw_svg_global_templates">
    <?php
        $udraw_svg_global_table->display();
    ?>
</form>
<style>
    a[data-udrawSVG="add_template"] {
        vertical-align: baseline; 
        margin-left: 5px;
    }
    img.preview_thumbnail {
        max-width: 150px;
        max-height: 150px;
    }
</style>