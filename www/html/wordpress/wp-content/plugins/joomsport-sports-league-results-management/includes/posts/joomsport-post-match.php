<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

require_once JOOMSPORT_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-meta-match.php';
class JoomSportPostMatch {
    public function __construct() {

    }
    public static function init(){
        self::register_post_types();
    }
    public static function register_post_types(){
        add_action("admin_init", array("JoomSportPostMatch","admin_init"));
        add_action( 'edit_form_after_title',  array( 'JoomSportPostMatch','match_edit_form_after_title') );
        add_action( 'wp_ajax_getsubsevent', array("JoomSportPostMatch",'getSubEvents') );
        add_action( 'wp_ajax_livematch_score', array("JoomSportPostMatch",'joomsport_livematch_score') );
        
        
        $slug = get_option( 'joomsportslug_joomsport_match', null );
        register_post_type( 'joomsport_match',
                apply_filters( 'joomsport_register_post_type_match',
                        array(
                                'labels'              => array(
                                                'name'               => __( 'Match', 'joomsport-sports-league-results-management' ),
                                                'singular_name'      => __( 'Match', 'joomsport-sports-league-results-management' ),
                                                'menu_name'          => _x( 'Matches', 'Admin menu name Matches', 'joomsport-sports-league-results-management' ),
                                                'add_new'            => __( 'Add Match', 'joomsport-sports-league-results-management' ),
                                                'add_new_item'       => __( 'Add New Match', 'joomsport-sports-league-results-management' ),
                                                'edit'               => __( 'Edit', 'joomsport-sports-league-results-management' ),
                                                'edit_item'          => __( 'Edit Match', 'joomsport-sports-league-results-management' ),
                                                'new_item'           => __( 'New Match', 'joomsport-sports-league-results-management' ),
                                                'view'               => __( 'View Match', 'joomsport-sports-league-results-management' ),
                                                'view_item'          => __( 'View Match', 'joomsport-sports-league-results-management' ),
                                                'search_items'       => __( 'Search Match', 'joomsport-sports-league-results-management' ),
                                                'not_found'          => __( 'No Match found', 'joomsport-sports-league-results-management' ),
                                                'not_found_in_trash' => __( 'No Match found in trash', 'joomsport-sports-league-results-management' ),
                                                'parent'             => __( 'Parent Match', 'joomsport-sports-league-results-management' )
                                        ),
                                'description'         => __( 'This is where you can add new match.', 'joomsport-sports-league-results-management' ),
                                'public'              => true,
                                'show_ui'             => true,
                                'show_in_menu'        => false,
                                'publicly_queryable'  => true,
                                'exclude_from_search' => false,
                                'hierarchical'        => false,
                                'query_var'           => true,
                                'supports'            => array( 'title', 'comments' ),
                                'show_in_nav_menus'   => false,
                                'capability_type' => 'jscp_match',
                                'capabilities' => array(
                                    'edit_post' => 'edit_jscp_match',
                                    'edit_posts' => 'edit_jscp_matchs',
                                    'edit_others_posts' => 'edit_others_jscp_match',
                                    'publish_posts' => 'publish_jscp_match',
                                    'read_post' => 'read_jscp_match',
                                    'delete_post' => 'delete_jscp_match',
                                    'delete_posts' => 'delete_jscp_match'
                                ),
                                'map_meta_cap' => true,
                                'rewrite' => array(
                                    'slug' => $slug?$slug:'joomsport_match'
                                )
                        )
                )
        );


        add_action( 'edit_form_top', array( 'JoomSportPostMatch','match_edit_button_title') );
    }
    public static function match_edit_button_title( ) {
        global $post, $wp_meta_boxes;
        $metadata = wp_get_post_terms($post->ID, 'joomsport_matchday');
        if(isset($metadata[0])){
            if(JoomSportUserRights::isAdmin()){
                $link = "term.php?taxonomy=joomsport_matchday&tag_ID=".$metadata[0]->term_id."&post_type=joomsport_match";
            }else{
                $link = "admin.php?page=joomsport_mday_moder&action=eview&id=".$metadata[0]->term_id;
            }
           echo "<a href='".$link."'><input type='button' class='button' value='<< ".__( 'back to', 'joomsport-sports-league-results-management' )." ".$metadata[0]->name."' /></a>"; 
        }
        
        
    }
    public static function match_edit_form_after_title($post_type){
        global $post, $wp_meta_boxes;

        if($post_type->post_type == 'joomsport_match'){
            
            echo JoomSportMetaMatch::output($post_type);

        }
    

    }
    public static function admin_init(){

        add_meta_box('joomsport_match_score_form_meta_box', __('Score & Points', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_score'), 'joomsport_match', 'joomsportintab_match1', 'default');
        add_meta_box('joomsport_match_about_form_meta_box', __('About match', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_about'), 'joomsport_match', 'joomsportintab_match1', 'default');
        add_meta_box('joomsport_match_general_form_meta_box', __('General', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_general'), 'joomsport_match', 'side', 'default');

        add_meta_box('joomsport_match_playerevents_form_meta_box', __('Player Events', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_playerevents'), 'joomsport_match', 'joomsportintab_match1', 'default');
        
        add_meta_box('joomsport_match_matchevents_form_meta_box', __('Match Statistic', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_mevents'), 'joomsport_match', 'joomsportintab_match1', 'default');
        add_meta_box('joomsport_match_boxscore_form_meta_box', __('Box Score', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_boxscore'), 'joomsport_match', 'joomsportintab_match1', 'default');
        
        add_meta_box('joomsport_match_ef_form_meta_box', __('Extra fields', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_ef'), 'joomsport_match', 'joomsportintab_match1', 'default');
        
        add_meta_box('joomsport_match_squad_form_meta_box', __('Line Up', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_lineup'), 'joomsport_match', 'joomsportintab_match2', 'default');
        add_meta_box('joomsport_match_subs_form_meta_box', __('Substitutes', 'joomsport-sports-league-results-management'), array('JoomSportMetaMatch','js_meta_subs'), 'joomsport_match', 'joomsportintab_match2', 'default');

        add_action( 'save_post',      array( 'JoomSportMetaMatch', 'joomsport_match_save_metabox' ), 10, 2 );
    }
    
    public static function getSubEvents(){
	global $wpdb;
        
	$eventid = isset($_POST['event_id'])?intval($_POST['event_id']):"0";

	$query = "SELECT sub.e_name as name,sub.id"
	. " FROM {$wpdb->joomsport_events} as e"
	. " JOIN {$wpdb->joomsport_events_depending} as de ON de.event_id = e.id"
	. " JOIN {$wpdb->joomsport_events} as sub ON de.subevent_id = sub.id"
	. " WHERE e.id = ".intval($eventid).""
	. " ORDER BY e.e_name";
	$row = $wpdb->get_row($query);
	echo json_encode($row);
	exit();
    }
    
    public static function joomsport_livematch_score(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR . 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR . 'sportleague' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'objects' . DIRECTORY_SEPARATOR . 'class-jsport-match.php';

        $match_id = isset($_POST['match_id'])?intval($_POST['match_id']):"0";
        
        if($match_id){
            $rows = new classJsportMatch($match_id);
            $score1 = get_post_meta($match_id, '_joomsport_home_score', true);
            $score2 = get_post_meta($match_id, '_joomsport_away_score', true);

            ob_start();
            $opposite_events = JoomsportSettings::get('opposite_events',array());
            if($opposite_events){
                $opposite_events = json_decode($opposite_events,true);
            }
            $width = JoomsportSettings::get('teamlogo_height');

            if($rows->lists['m_events_display'] == 1){
                $rows->getPlayerObj($rows->lists['m_events_home']);
                $rows->getPlayerObj($rows->lists['m_events_away']);
            }else{
                $rows->getPlayerObj($rows->lists['m_events_all']);
            }
            $rows->getTeamEvenetObj($rows->lists['team_events']);
            $match = $rows;
            if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
                $partic_home = $match->getParticipantHome();
                $partic_away = $match->getParticipantAway();
                $tmp = $rows->lists['m_events_away'];
                $rows->lists['m_events_away'] = $rows->lists['m_events_home'];
                $rows->lists['m_events_home'] = $tmp;
                $tmp = $rows->lists['squard1'];
                $rows->lists['squard1'] = $rows->lists['squard2'];
                $rows->lists['squard2'] = $tmp;
                $tmp = $rows->lists['squard1_res'];
                $rows->lists['squard1_res'] = $rows->lists['squard2_res'];
                $rows->lists['squard2_res'] = $tmp;
            }else{
                $partic_home = $match->getParticipantHome();
                $partic_away = $match->getParticipantAway();
            }

            try{
                require_once JOOMSPORT_PATH_VIEWS_ELEMENTS . 'player_stat' . DIRECTORY_SEPARATOR . 'match-view-player-stat.php';
            }catch (Exception $e){
                echo $e->getMessage();
            }

            $plstat = ob_get_contents();
            ob_end_clean();

            ob_start();
            if (count($rows->lists['squard1']) || count($rows->lists['squard2'])) {
                $rows->getPlayerObj($rows->lists['squard1']);
                $rows->getPlayerObj($rows->lists['squard2']);
                $rows->getPlayerObj($rows->lists['squard1_res']);
                $rows->getPlayerObj($rows->lists['squard2_res']);
            }
            try{
                require_once JOOMSPORT_PATH_VIEWS_ELEMENTS . 'squad-list.php';
            }catch (Exception $e){
                echo $e->getMessage();
            }
            $plsquad = ob_get_contents();
            ob_end_clean();

            echo json_encode(array("score"=>array($score1,$score2), "plstat"=>$plstat, "plsquad" => $plsquad));
        }
        
        
        exit();

    }
}    