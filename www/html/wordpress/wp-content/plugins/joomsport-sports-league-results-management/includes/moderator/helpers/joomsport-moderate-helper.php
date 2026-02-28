<?php
require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'joomsport-moderate-acl.php';
class JoomsportModerateHelper{

    public static function getModerTeams(){
        $teams = new WP_Query(array(
            'post_type' => 'joomsport_team',
            'posts_per_page'   => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'=>'_joomsport_team_moderator',
                    'value'=>get_current_user_id(),
                    'compare'=>'=',
                )
            )
        ));

        return $teams->posts;
    }
    public static function getModerPlayers(){
        $players = new WP_Query(array(
            'post_type' => 'joomsport_player',
            'posts_per_page'   => -1,
            'post_status' => 'publish',
            'author' => get_current_user_id(),

        ));

        return $players->posts;
    }

    public static function getModerMatches($filters){
        global $wpdb;
        $teamIDs = array();
        $matches = array();
        $teamsPosts = self::getModerTeams();
        if(count($teamsPosts)){
            foreach ($teamsPosts as $team){
                $teamIDs[] = $team->ID;
            }
        }
        if(isset($filters["teamID"]) && $filters["teamID"] && in_array($filters["teamID"],$teamIDs)){
            $teamIDs = array($filters["teamID"]);
        }
        if(count($teamIDs)){
            $query = "SELECT m.postID AS ID, mdID, seasonID"
                . " FROM {$wpdb->joomsport_matches} as m"
                . " JOIN {$wpdb->posts} as p ON p.ID = m.postID"
                ." WHERE p.post_status='publish'"
                .((isset($played))?" AND m.status = ".intval($played):"")
                .((isset($date_from) && $date_from)?" AND m.date >= '".($date_from)."'":"")
                .((isset($date_to) && $date_to)?" AND m.date <= '".($date_to)."'":"")
                .((isset($date_exclude) && $date_exclude)?" AND m.date != '".($date_exclude)."'":"")
                ." AND (m.teamHomeID IN (".implode($teamIDs, ",").") OR m.teamAwayID IN (".implode($teamIDs, ",")."))"
                .((isset($filters["seasonID"]) && $filters["seasonID"])?" AND m.seasonID = {$filters["seasonID"]}":"")
                ." ORDER BY m.date,m.time,m.postID";

            $matchesList = $wpdb->get_results($query);
            if(count($matchesList)){
                foreach ($matchesList as $row) {

                    $match = new classJsportMatch($row->ID, false);
                    $matches[] = $match->getRowSimple();
                }
            }


        }

        return $matches;
    }

    public static function getSeasonsParticipated(){
        $results = array();
        //$posts_array = jsHelperPublishedSeasons::getInstance();
        global $wpdb;
        $teamsObjs = self::getModerTeams();
        $teams = array();
        for($intA=0;$intA<count($teamsObjs);$intA++){
            $teams[] = $teamsObjs[$intA]->ID;
        }

        if(count($teams)){
            $query = 'SELECT p.*  FROM '.$wpdb->joomsport_season_table.' as j'
                . ' JOIN '.$wpdb->posts.' as p ON p.ID = j.season_id '
                .' WHERE p.post_status = "publish"'
                .' AND j.participant_id IN ('.implode(",", $teams).')'
                .' GROUP BY j.season_id';

            $posts_array = $wpdb->get_results($query);

            for($intA=0;$intA<count($posts_array);$intA++){
                //$metadata = get_post_meta($posts_array[$intA]->ID,'_joomsport_season_participiants',true);
                //$term_list = wp_get_post_terms($posts_array[$intA]->ID, 'joomsport_tournament', array("fields" => "all"));
                $term_list = get_the_terms($posts_array[$intA]->ID, 'joomsport_tournament');
                if(count($term_list)){
                    //$term_meta = get_option( "taxonomy_".$term_list[0]->term_id."_metas");
                    //if(in_array($participiant_id, $metadata)){
                    $std = new stdClass();
                    $std->name = esc_attr($posts_array[$intA]->post_title);
                    $std->id = $posts_array[$intA]->ID;
                    if(!isset($results[$term_list[0]->name])){
                        $results[esc_attr($term_list[0]->name)] = array();
                    }
                    array_push($results[esc_attr($term_list[0]->name)], $std);
                    //}
                }
            }
        }

        //var_dump($results);
        return $results;
    }

    public static function getMdMatches($matchdayID){
        global $wpdb;
        $teamIDs = array();
        $matches = array();
        $teamsPosts = self::getModerTeams();
        if(count($teamsPosts)){
            foreach ($teamsPosts as $team){
                $teamIDs[] = $team->ID;
            }
        }
        if(count($teamIDs)) {
            $query = "SELECT m.postID AS ID, mdID, seasonID"
                . " FROM {$wpdb->joomsport_matches} as m"
                . " JOIN {$wpdb->posts} as p ON p.ID = m.postID"
                . " WHERE p.post_status='publish'"
                . ((isset($matchdayID) && $matchdayID) ? " AND m.mdID = " . intval($matchdayID) : "")
                . " AND (m.teamHomeID IN (" . (implode(',', $teamIDs)) . ") OR m.teamAwayID IN (" . (implode(',', $teamIDs)) . "))"
                . " ORDER BY m.date,m.time,m.postID";
            $matches = $wpdb->get_results($query);
        }
        return $matches;
    }


    public static function Can($task, $itemID){
        return JoomsportModerateACL::parse($task, $itemID);
    }
}
