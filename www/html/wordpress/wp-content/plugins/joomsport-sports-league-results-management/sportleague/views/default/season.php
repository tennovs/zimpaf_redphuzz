<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$term_list = get_the_terms($rows->object->ID, 'joomsport_tournament');
$descr = '';
if(count($term_list)){
    $descr = $term_list[0]->description;
}
?>
<div class="seasonTable">
    <?php if (isset($extra_fields) && $extra_fields) { ?>
        <div class="jsOverflowHidden" style="padding:0px 15px;">
            <?php
            $class = '';
            $extra_fields = jsHelper::getADF($rows->lists['ef']);
            if ($extra_fields) {
                $class = 'well well-sm';
            } else {
                ?>
                <div class="rmpadd" style="padding-right:0px;padding-left:15px;">
                    <?php echo $descr; ?>
                </div>
                <?php
            }
            ?>
            <div class="<?php echo $class;?> pt10 extrafldcn">
                <?php echo $extra_fields; ?>
            </div>
            <?php
            if ($descr && $extra_fields) {
                echo '<div class="col-xs-12 rmpadd" style="padding-right:0px;">';
                echo $descr;
                echo '</div>';
            }
            ?>
        </div>
        <?php 
    } 
    ?>
    <div>
        <?php
        //require_once JOOMSPORT_PATH_VIEWS_ELEMENTS . 'table-group.php';
        $tabs = $rows->getTabs();
        jsHelperTabs::draw($tabs, $rows);
        ?>
    </div>
    <div>
        <div>
            <?php
            if (isset($rows->season->lists['playoffs'])) {
                echo jsHelper::getMatches($rows->season->lists['playoffs']);
            }
            ?>
        </div>
    </div>
    <div class="jsClear"></div>

    <?php if (JoomsportSettings::get('jsbrand_on',1) == 1):?>
        <br />
        <div id="copy" class="copyright">
            <?php
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
            if ( is_plugin_active( 'statorium-api/statorium-api.php' ) ){
                echo 'Data by <a href="https://statorium.com">Statorium</a> soccer API';
            }else{
                echo 'powered by <a href="https://joomsport.com">JoomSport: WordPress sports plugin</a>';
            }
            ?>
        </div>
    <?php endif;?>

    <div class="jsClear"></div>
</div>
