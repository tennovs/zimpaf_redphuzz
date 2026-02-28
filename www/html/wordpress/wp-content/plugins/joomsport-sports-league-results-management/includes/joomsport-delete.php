<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomsportDelete {
    
    public static function init() {
        add_action('delete_post',array('JoomsportDelete','delete_joomsport_post'),10);
        add_action('pre_delete_term', array('JoomsportDelete','delete_joomsport_tournament'),10,2);
        add_action('pre_delete_term', array('JoomsportDelete','delete_joomsport_matchday'),10,2);
        add_action('after-joomsport_matchday-table', array('JoomsportDelete','recalc_seasons_after_matchday_delete'),10);
        //add_action('delete_joomsport_club', array('JoomsportDelete','delete_joomsport_club'));
    }
    public static function recalc_seasons_after_matchday_delete(){
        $seasons = get_option("joomsport_recalc_seasons", array());
        if(count($seasons)){
            foreach($seasons as $season){
                do_action('joomsport_update_standings', $season, array());
                do_action('joomsport_update_playerlist', $season, array());
            }

        }

        update_option("joomsport_recalc_seasons", array());


    }
    public static function delete_joomsport_tournament($term_id, $taxonomy){

        if($taxonomy != 'joomsport_tournament'){
            return;
        }
        $seasons = get_posts(
           array('post_type' => 'joomsport_season',
               'posts_per_page'   => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'joomsport_tournament',
                    'field' => 'term_id',
                    'terms' => $term_id,
                ),
            ),
         ));
        for($intA = 0; $intA < count($seasons); $intA++){
            wp_delete_post($seasons[$intA]->ID);
        }
    }
    public static function delete_joomsport_matchday($term_id, $taxonomy){
        if($taxonomy != 'joomsport_matchday'){
            return;
        }
        $matches = get_posts(
           array('post_type' => 'joomsport_match',
               'posts_per_page'   => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'joomsport_matchday',
                    'field' => 'term_id',
                    'terms' => $term_id,
                ),
            ),
         ));

        for($intA = 0; $intA < count($matches); $intA++){
            wp_delete_post($matches[$intA]->ID);
            JoomsportDelete::deleteMatch($matches[$intA]->ID);
            
        }
        $term_metas = get_option("taxonomy_{$term_id}_metas");

        $seasons = get_option("joomsport_recalc_seasons", array());
        $seasons[] = $term_metas['season_id'];
        $seasons = array_unique($seasons);

        update_option("joomsport_recalc_seasons", $seasons);

        //do_action('joomsport_update_standings', $term_metas['season_id'], array());
        //do_action('joomsport_update_playerlist', $term_metas['season_id'], array());
        
        delete_option("taxonomy_{$term_id}_metas");
    }

    public static function delete_joomsport_post($post_id){
       //global $post_type;
       $post_type = get_post_type($post_id);
       
       switch($post_type){
           case 'joomsport_season':
               self::deleteSeason($post_id);
               break;
           case 'joomsport_match':
               self::deleteMatch($post_id);
               break;
           case 'joomsport_player':
               self::deletePlayer($post_id);
               break;
           case 'joomsport_team':
               self::deleteTeam($post_id);
               break;

           default:
       }
    }
    
    public static function deleteMatch($post_id){
        global $wpdb, $recalced;
        
        //delete events
        $wpdb->delete(
          "{$wpdb->joomsport_match_events}",
          array( 'match_id' => $post_id ),
          array( '%d' )
        );
        //squad
        $wpdb->delete(
          "{$wpdb->joomsport_squad}",
          array( 'match_id' => $post_id ),
          array('%d')
        );
       //box
        $wpdb->delete(
          "{$wpdb->joomsport_box_match}",
          array( 'match_id' => $post_id ),
          array('%d')
        );
        //db
        $wpdb->delete(
            "{$wpdb->joomsport_matches}",
            array( 'postID' => $post_id ),
            array('%d')
        );
        /*if($recalced){
            $home_team = get_post_meta( $post_id, '_joomsport_home_team', true );
            $away_team = get_post_meta( $post_id, '_joomsport_away_team', true );
            var_dump($home_team);
            $season_id = get_post_meta( $post_id, '_joomsport_seasonid', true );//JoomSportHelperObjects::getMatchSeason($post_id);
            echo $season_id;die();
            do_action('joomsport_update_standings', $season_id);
            do_action('joomsport_update_playerlist', $season_id, array($home_team,$away_team));
            $recalced = false;
        }*/
        
        do_action('jsOnMatchDelete', $post_id);
    }

    public static function deletePlayer($post_id){
        global $wpdb;
        //delete events
        $wpdb->delete(
          "{$wpdb->joomsport_match_events}",
          array( 'player_id' => $post_id ),
          array( '%d' )
        );
        //squad
        $wpdb->delete(
          "{$wpdb->joomsport_squad}",
          array( 'player_id' => $post_id ),
          array( '%d' )
        );
        $wpdb->delete(
          "{$wpdb->joomsport_playerlist}",
          array( 'player_id' => $post_id ),
          array( '%d' )
        );
        $wpdb->delete(
          "{$wpdb->joomsport_season_table}",
          array( 'participant_id' => $post_id ),
          array( '%d' )
        );
        $wpdb->delete(
          "{$wpdb->joomsport_box_match}",
          array( 'player_id' => $post_id ),
          array('%d')
        );
    }

    public static function deleteSeason($post_id){
        global $recalced, $wpdb;
        if(get_bloginfo('version') < '4.5.0'){
            $tx = get_terms('joomsport_matchday',array(
                "hide_empty" => false
            ));
        }else{
            $tx = get_terms(array(
                "taxonomy" => "joomsport_matchday",
                "hide_empty" => false,
            ));
        }
        
        for($intA=0; $intA<count($tx); $intA++){
            $metas = get_option("taxonomy_{$tx[$intA]->term_id}_metas");
            if($metas['season_id'] == $post_id){
                wp_delete_term( $tx[$intA]->term_id, 'joomsport_matchday' );
            }
        }
        //delete standings
        $wpdb->delete(
          "{$wpdb->joomsport_season_table}",
          array( 'season_id' => $post_id ),
          array( '%d' )
        );
          //delete player list
          $wpdb->delete(
          "{$wpdb->joomsport_playerlist}",
          array( 'season_id' => $post_id ),
          array( '%d' )
        );
        //delete groups
        $wpdb->delete(
          "{$wpdb->joomsport_groups}",
          array( 's_id' => $post_id ),
          array( '%d' )
        );
        //delete boxscore
        $wpdb->delete(
          "{$wpdb->joomsport_box_match}",
          array( 'season_id' => $post_id ),
          array( '%d' )
        );  
        
        //do_action('joomsport_update_standings', $post_id);
        //do_action('joomsport_update_playerlist', $post_id);
        $recalced = false;
        
    }
    public static function deleteTeam($post_id){
        global $wpdb;
        //delete events
        $wpdb->delete(
          "{$wpdb->joomsport_match_events}",
          array( 't_id' => $post_id ),
          array( '%d' )
        );
        //squad
        $wpdb->delete(
          "{$wpdb->joomsport_squad}",
          array( 'team_id' => $post_id ),
          array( '%d' )
        );
        $wpdb->delete(
          "{$wpdb->joomsport_season_table}",
          array( 'participant_id' => $post_id ),
          array( '%d' )
        );
        $wpdb->delete(
          "{$wpdb->joomsport_box_match}",
          array( 'team_id' => $post_id ),
          array('%d')
        );
        $wpdb->delete(
          "{$wpdb->joomsport_playerlist}",
          array( 'team_id' => $post_id ),
          array( '%d' )
        );
    }

    
}
global $recalced;
$recalced = true;


JoomsportDelete::init();
