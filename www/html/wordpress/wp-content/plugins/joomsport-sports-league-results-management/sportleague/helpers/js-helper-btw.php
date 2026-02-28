<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsHelperBtw
{
    public static function matches($team1, $team2, $homeOnly = 0){
        global $wpdb;
        if($homeOnly){
            $selteam = array('relation' => 'AND',
                array(
                    'key' => '_joomsport_home_team',
                    'value' => $team1
                ),

                array(
                    'key' => '_joomsport_away_team',
                    'value' => $team2
                )
            );
        }else{
            $selteam = array('relation' => 'AND',
                    array(
                        'key' => '_joomsport_home_team',
                        'value' => array($team1,$team2),
                        'compare' => 'IN',
                    ),

                    array(
                        'key' => '_joomsport_away_team',
                        'value' => array($team1,$team2),
                        'compare' => 'IN',
                    )


            );
        }

        add_filter('posts_orderby',array('classJsportgetmatches', 'joomsport_ordermatchbydatetimeDesc'));

        $matches = new WP_Query(array(
                'posts_per_page' => -1,
                'offset'           => 0,
                'post_type'        => 'joomsport_match',
                'post_status'      => 'publish',
                'order'     => 'DESC',

                'meta_query' => array(
                    array('relation' => 'AND',
                        array('key'     => '_joomsport_match_date'),
                        array('key'     => '_joomsport_match_time')),
                        array('key'     => '_joomsport_seasonid'),
                        array(
                            'key' => '_joomsport_match_played',
                            'value' => 1
                        ),
                    $selteam

                )
            )
        );
        remove_filter('posts_orderby',array('classJsportgetmatches', 'joomsport_ordermatchbydatetimeDesc'));

        return $matches->posts;
    }
    public static function getLastMatches($partic_id, $season_id = 0, $place = 0, $limit = 5){
        $limit = 5;
        //place 1-home, 2- away
        $options = array('team_id' => $partic_id, 'season_id' => $season_id, 'place' => $place, 'limit' => $limit, 'played' => 1, 'ordering_dest' => 'desc');

        $obj = new classJsportMatches($options);
        $rows = $obj->getMatchList();
        $matches = array();

        if ($rows['list']) {
            foreach ($rows['list'] as $row) {
                $match = new classJsportMatch($row->ID, false);
                $match->opposite = 0;
                $home_team = get_post_meta( $row->ID, '_joomsport_home_team', true );
                if($partic_id == $home_team){
                    $match->opposite = 1;
                }
                $matches[] = $match->getRowSimple();
            }
        }

        return $matches;
    }

    public static function getPositions($season_id,$team1,$team2){
        global $wpdb;
        $obj = new stdClass();
        $obj->teamHomePosition = self::getPosition($season_id,$team1);
        $obj->teamAwayPosition = self::getPosition($season_id,$team2);

        $obj->maxPosition =  $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->joomsport_season_table} WHERE season_id = {$season_id}");
        return $obj;
    }
    public static function getPosition($season_id,$team_id){
        global $wpdb;
        $res = $wpdb->get_var("SELECT ordering FROM {$wpdb->joomsport_season_table} WHERE season_id = {$season_id} AND participant_id = {$team_id}");
        if($res !== null){
            return intval($res);
        }else{
            return null;
        }
    }

    public static function getPlayedMatches($team_id, $season_id, $place = 0){
        $mArray = array(
            'posts_per_page' => -1,
            'offset'           => 0,
            'post_type'        => 'joomsport_match',
            'post_status'      => 'publish',
        );
        $mArray['meta_query'][] =
            array(
                'key' => '_joomsport_seasonid',
                'value' => $season_id
            );
        $mArray['meta_query'][] =
            array(
                'key' => '_joomsport_match_played',
                'value' => 1
            );

        if($place == '1'){
            $mArray['meta_query'][] = array(
                'key' => '_joomsport_home_team',
                'value' => $team_id
            );
        }elseif($place == '2'){
            $mArray['meta_query'][] = array(
                'key' => '_joomsport_away_team',
                'value' => $team_id
            );
        }else{
            $mArray['meta_query'][] = array('relation' => 'OR',
                array(
                    'key' => '_joomsport_home_team',
                    'value' => $team_id
                ),

                array(
                    'key' => '_joomsport_away_team',
                    'value' => $team_id
                )
            );
        }

        $matches = new WP_Query($mArray);


        return $matches->posts;
    }

    public static function getTeamStat($team_id, $season_id, $matches){
        global $wpdb;
        $events = $wpdb->get_results("SELECT me.e_id, SUM(me.ecount) as cnt "
        ." FROM {$wpdb->joomsport_match_events} as me"
        ." WHERE season_id={$season_id} AND t_id={$team_id}"
        .(count($matches)?" AND match_id IN (".implode(",",$matches).")":"")
        ." GROUP BY me.e_id"
        ,OBJECT_K);
        return $events;
    }

    public static function getTeamMatchStat($is_home, $season_id, $matches){
        $team = $is_home?"mevents1":"mevents2";
        $match_events = array();
        for($intA=0;$intA<count($matches);$intA++){
            $metadata = get_post_meta($matches[$intA],'_joomsport_matchevents',true);

            if($metadata && count($metadata)){
                foreach($metadata as $key=>$value){
                    $match_events[$key] = isset($match_events[$key])?$match_events[$key]:0;
                    $match_events[$key] += isset($value[$team])?intval($value[$team]):0;
                }
            }
        }
        return $match_events;
    }

    public static function sortEvents($events){
        global $wpdb;
        if(!count($events)){return array();}
        $eventsR = $wpdb->get_col("SELECT id "
            ." FROM {$wpdb->joomsport_events}"
            ." WHERE id IN (".implode(",",$events).")"
            ." ORDER BY ordering, player_event desc, e_name");
        return $eventsR;
    }

    public static function getTeamGoals($team_id, $matches){
        $scored=$conceeded=0;
        for($intA=0;$intA<count($matches);$intA++){
            $home_score = get_post_meta( $matches[$intA], '_joomsport_home_score', true );
            $away_score = get_post_meta( $matches[$intA], '_joomsport_away_score', true );
            $home_team = get_post_meta( $matches[$intA], '_joomsport_home_team', true );
            if($home_team == $team_id){
                $scored += $home_score;
                $conceeded += $away_score;
            }else{
                $scored += $away_score;
                $conceeded += $home_score;
            }
        }
        return array("scored"=>$scored, "conceeded"=>$conceeded);
    }

    public static function getColumnsOptions($season_id, $team_id){
        global $wpdb;
        $options =  $wpdb->get_var("SELECT options FROM {$wpdb->joomsport_season_table} WHERE season_id = {$season_id} AND participant_id = {$team_id}");
        return json_decode($options, true);
    }

    public static function getStandingColors($season_id){
        require_once JOOMSPORT_PATH_MODELS.'model-jsport-season.php';
        $obj = new modelJsportSeason($season_id);
        $arr = $obj->getColors();
        return $arr[0];
    }

    public static function showH2HBlock($match_id){
        $season_id  = JoomSportHelperObjects::getMatchSeason($match_id);
        $enbl_block = JoomsportSettings::get('enbl_match_analytics_block',0);
        if(!$enbl_block){return false;};
        if(!$season_id){return false;};
        $m_played = get_post_meta($match_id,'_joomsport_match_played',true);
        if($m_played != 0){ return false; }
        return true;
    }
    public static function showSeasonsRelated($match_id){
        $season_id  = JoomSportHelperObjects::getMatchSeason($match_id);
        $home_team = get_post_meta($match_id,'_joomsport_home_team',true);
        $away_team = get_post_meta($match_id,'_joomsport_away_team',true);

        $pl1 = getPlayedMatches($away_team, $season_id);
    }

    public static function showPositionBlock($match_id){
        global $wpdb;
        $md = get_the_terms($match_id,'joomsport_matchday');
        $mdID = $md[0]->term_id;
        $metas = get_option("taxonomy_{$mdID}_metas");

        $groups = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->joomsport_groups} WHERE s_id = ".intval($metas['season_id']));

        if($groups){
            return false;
        }
        if((!isset($metas['matchday_type']) || !$metas['matchday_type']) && (!isset($metas['is_playoff']) || !$metas['is_playoff'])){
            return true;
        }
        return false;
    }

}
