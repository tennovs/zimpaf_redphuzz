<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
$wp_template_path = get_template_directory();
if(file_exists($wp_template_path . '/joomsport/includes/demo/joomsport-demo.php')){
    include_once( $wp_template_path . '/joomsport/includes/demo/joomsport-demo.php' );
} else {
    include_once( JOOMSPORT_PATH . 'includes/demo/joomsport-demo.php' );
}

class JoomSportSetupDemo {

    public static function init(){
        global $pagenow;
        
        add_action( 'admin_menu', array('JoomSportSetupDemo', 'create_setup_page') );
        
        if($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'joomsport_setup'){
            if(isset($_POST['js_demotype'])){
                JoomSportDemoData::installDemoData(intval($_POST['js_demotype']));
            }
        }
    }

    public static function create_setup_page() {
        add_dashboard_page('JoomSport', '', 'manage_options', 'joomsport_setup', array('JoomSportSetupDemo', 'showSetupPage'));
    }

    public static function showSetupPage(){
        $lists_radio = array();
        $lists_radio[] = JoomSportHelperSelectBox::addOption(0, __('Single sport','joomsport-sports-league-results-management'));
        $lists_radio[] = JoomSportHelperSelectBox::addOption(1, __('Team sport','joomsport-sports-league-results-management'));
        $lists_radio[] = JoomSportHelperSelectBox::addOption(2, __('Both','joomsport-sports-league-results-management'));
        ?>
        <div class="jsportWizzardDiv">

            <form method="post" action="">
                <div class="jsportWizzardDivInner">
                    <h1><?php echo __('JoomSport setup wizard', 'joomsport-sports-league-results-management');?></h1>
                    <div class="jsportWizzardDivCenter">
                        <label><?php echo __('Select your sport type', 'joomsport-sports-league-results-management');?></label>
                        <div style="margin-left:90px;">
                            <?php echo JoomSportHelperSelectBox::Radio('js_demotype', $lists_radio,1)?>
                        </div>
                    </div>
                    <div>
                        <br />
                        <div class="jsportWizzardDivCenter">
                            <label><?php echo __('Install basic demo data', 'joomsport-sports-league-results-management');?></label>
                        </div>
                        
                        <fieldset>
                            <?php echo __("The following items are included",'joomsport-sports-league-results-management')?>:<br /><br />
                            <?php 
                            
                            echo " - ".__("Leagues",'joomsport-sports-league-results-management');
                            echo "<br />";
                            echo " - ".__("Seasons",'joomsport-sports-league-results-management');
                            echo "<br />";
                            echo " - ".__("Teams and Players (depending on sport)",'joomsport-sports-league-results-management');
                            echo "<br />";
                            echo " - ".__("Round robin Matchdays with Matches",'joomsport-sports-league-results-management');
                            echo "<br />";
                            echo " - ".__("Basic statistic like Player and Match Event stats",'joomsport-sports-league-results-management');
                            echo "<br />";
                            echo " - ".__("Box score stats",'joomsport-sports-league-results-management');
                            echo "<br />";
                            ?>
                        </fieldset>
                        
                    </div>
                    <div style="padding-top:25px;">
                        <div style="display: inline-block">
                            <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=joomsport_tournament&post_type=joomsport_season' ) ;?>">
                                <input type="button" class="button button-large" value="<?php echo __('No, start empty database', 'joomsport-sports-league-results-management');?>" />
                            </a>
                        </div>
                        <div style="display: inline-block; float: right;">
                            <input type="submit" class="button button-primary button-large" value="<?php echo __('Yes, install basic demo data', 'joomsport-sports-league-results-management');?>" />
                        </div>
                    </div>
                </div>   
            </form>    
        </div>    
        <?php
    }
}

add_action( 'init', array( 'JoomSportSetupDemo', 'init' ), 5);
add_action( 'wp_ajax_joomsport_demo_ttype', array("JoomSportSetupDemo",'setTournType') );