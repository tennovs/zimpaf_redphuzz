<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsHelperMatchesDB
{
    protected function __construct() {
        
    }
    
    public static function pullAllMatches()
    {
        global $wpdb;

        $intA = 0;
        do {
            $matchesC = new WP_Query(array(
                    'posts_per_page' => 500*($intA+1),
                    'offset' => 500*$intA,
                    'post_type' => 'joomsport_match',
                    'post_status' => 'publish',
                    'order' => 'DESC',
                )
            );

            if ($matchesC->posts) {
                foreach ($matchesC->posts as $row) {
                    $metas = get_post_meta($row->ID, '', true);

                    $query = "SELECT tt.term_id "
                        ." FROM {$wpdb->term_relationships} as tr"
                        ." JOIN {$wpdb->term_taxonomy} as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id"
                        ." WHERE tr.object_id={$row->ID} LIMIT 1";
                    $mdID = $wpdb->get_var($query);


                    /*$term_list = get_the_terms($row->ID, 'joomsport_matchday');
                    if(count($term_list)){
                        $mdID = $term_list[0]->term_id;
                    }*/


                    if ($mdID) {

                        $duration = 0;
                        if (isset($metas["_joomsport_match_general"][0])) {
                            $metadata = $metas["_joomsport_match_general"][0];
                            if (isset($metadata['match_duration']) && $metadata['match_duration'] != '') {
                                $duration = $metadata['match_duration'];
                            }
                        }
                        $season_id = isset($metas["_joomsport_seasonid"][0]) ? $metas["_joomsport_seasonid"][0] : 0;
                        $homeID = isset($metas["_joomsport_home_team"][0]) ? $metas["_joomsport_home_team"][0] : 0;
                        $awayID = isset($metas["_joomsport_away_team"][0]) ? $metas["_joomsport_away_team"][0] : 0;
                        $status = isset($metas["_joomsport_match_played"][0]) ? $metas["_joomsport_match_played"][0] : 0;

                        $match_date = isset($metas["_joomsport_match_date"][0]) ? $metas["_joomsport_match_date"][0] : "";
                        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $match_date)) {
                            $match_date = '0000-00-00';
                        }

                        $match_time = isset($metas["_joomsport_match_time"][0]) ? $metas["_joomsport_match_time"][0] : "";
                        $score1 = isset($metas["_joomsport_home_score"][0]) ? $metas["_joomsport_home_score"][0] : 0;
                        $score2 = isset($metas["_joomsport_away_score"][0]) ? $metas["_joomsport_away_score"][0] : 0;
                        $group_id = isset($metas["_joomsport_groupID"][0]) ? intval($metas["_joomsport_groupID"][0]) : 0;

                        $query = "INSERT IGNORE INTO {$wpdb->joomsport_matches}(postID,mdID,seasonID,teamHomeID,teamAwayID,groupID,status,date,time,scoreHome,scoreAway,duration)";
                        $query .= " VALUES({$row->ID}," . intval($mdID) . "," . intval($season_id) . "," . intval($homeID) . "," . intval($awayID) . "," . intval($group_id) . "," . intval($status) . ",'" . ($match_date) . "','" . ($match_time) . "'," . intval($score1) . "," . intval($score2) . "," . intval($duration) . ")";
                        $query .= " ON DUPLICATE KEY UPDATE mdID = ".intval($mdID);

                        $wpdb->query($query);
                        if ($wpdb->last_error !== '') :
                            $wpdb->print_error();

                        endif;
                    }


                }

            }
            $intA++;
        }while(count($matchesC->posts) > 0);
    }

    public static function checkMatchesSeason(){
        global $wpdb;


        $cnt = $wpdb->get_var("SELECT COUNT(postID) FROM {$wpdb->joomsport_matches}");
        return $cnt;

    }

    public static function updateMatchDB($matchID){
        global $wpdb;

        $metas = get_post_meta($matchID,'',true);

        $query = "SELECT tt.term_id "
            ." FROM {$wpdb->term_relationships} as tr"
            ." JOIN {$wpdb->term_taxonomy} as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id"
            ." WHERE tr.object_id={$matchID} LIMIT 1";
        $mdID = $wpdb->get_var($query);

        /*
         * $term_list = get_the_terms($matchID, 'joomsport_matchday');
                    if(count($term_list)){
                        $mdID = $term_list[0]->term_id;
                    }
         */

        if ( $mdID ){

            $duration = 0;
            if(isset($metas["_joomsport_match_general"][0])){
                $metadata = $metas["_joomsport_match_general"][0];
                if(isset($metadata['match_duration']) && $metadata['match_duration'] != ''){
                    $duration = $metadata['match_duration'];
                }
            }

            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$metas["_joomsport_match_date"][0])) {
                $metas["_joomsport_match_date"][0] = '0000-00-00';
            }

            $group_id = isset($metas["_joomsport_groupID"][0])?intval($metas["_joomsport_groupID"][0]):0;
            $query = "INSERT INTO {$wpdb->joomsport_matches}(postID,mdID,seasonID,teamHomeID,teamAwayID,groupID,status,date,time,scoreHome,scoreAway,duration)";
            $query .= " VALUES({$matchID},".intval($mdID).",".intval($metas["_joomsport_seasonid"][0]).",".intval($metas["_joomsport_home_team"][0]).",".intval($metas["_joomsport_away_team"][0]).",".intval($group_id).",".intval($metas["_joomsport_match_played"][0]).",'".($metas["_joomsport_match_date"][0])."','".($metas["_joomsport_match_time"][0])."',".intval($metas["_joomsport_home_score"][0]).",".intval($metas["_joomsport_away_score"][0]).",".intval($duration).")";
            $query .= " ON DUPLICATE KEY UPDATE mdID = ".intval($mdID).",seasonID = ".intval($metas["_joomsport_seasonid"][0]).","
                ."teamHomeID = ".intval($metas["_joomsport_home_team"][0]).",teamAwayID = ".intval($metas["_joomsport_away_team"][0]).",groupID = ".intval($group_id).","
                ."status = ".intval($metas["_joomsport_match_played"][0]).",date = '".$metas["_joomsport_match_date"][0]."',time = '".$metas["_joomsport_match_time"][0]."',scoreHome = ".intval($metas["_joomsport_home_score"][0]).",scoreAway = ".intval($metas["_joomsport_away_score"][0]).",duration = ".intval($duration)."";

            $res = $wpdb->query($query);
            if($wpdb->last_error !== '') :
                $wpdb->print_error();

            endif;

        }



    }
}
