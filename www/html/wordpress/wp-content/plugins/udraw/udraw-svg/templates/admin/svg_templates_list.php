<h2>
    <?php _e('View Templates', 'udraw_svg') ?>
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
    $uDraw = new uDraw();
    $uDraw_SVG = new uDraw_SVG();
    $udraw_svg_table = new uDraw_SVG_Table();
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $message = '';
        if ($action === 'deleted') {
            $message = 'Template Deleted';
        } else if ($action === 'updated') {
            $message = 'Template Updated';
        } else if ($action === 'added') {
            $message = 'Template Added';
        }
        ?>
            <div id="message" class="updated below-h2"><p><?php _e($message , 'udraw_svg'); ?></p></div>
        <?php
    }
    $udraw_svg_table->prepare_items();
    
    $template_count = $uDraw_SVG->get_udraw_SVG_template_count();
    if ($template_count < 2 || $uDraw->is_udraw_okay()) { ?>
    <a href="#" class="button button-default" data-udrawsvg_template="add_template">
        <i class="fa fa-plus" style="margin-right: 5px;"></i>
        <span><?php _e("Add New", "udraw_svg") ?></span>
    </a>
    <?php } ?>
<form method="post" action="?page=udraw_svg">
    <?php
        $udraw_svg_table->display();
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

<?php require_once(dirname(__FILE__) . '/add_svg_template.php'); ?>