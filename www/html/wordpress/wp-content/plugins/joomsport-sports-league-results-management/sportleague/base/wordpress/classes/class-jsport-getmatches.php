<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classJsportgetmatches
{
    public static function getMatches($options)
    {

        $result_array = array();

        if ($options) {
            extract($options);
        }
        global $jsDatabase;

        


        //if(isset($season_id) && !is_array($season_id) && $season_id > 0){
            //$res = jsHelperMatchesDB::checkMatchesSeason($season_id);
            //if($res > 0){
                return classJsportgetmatches::getMatchesFromDB($options);
            //}
       //}

        $aSeasons = jsHelperPublishedSeasons::getInstance();
        $seasonsArray = array();
        foreach($aSeasons as $aSeason){
            $seasonsArray[] = $aSeason->ID;
        }

        if (!isset($ordering)) {
            $ordering = 'md.ordering, m.m_date, m.m_time';
        }
        
        if (isset($ordering_dest) && $ordering_dest == 'desc'){
            $orderfunc = 'joomsport_ordermatchbydatetimeDesc';
        }else{
            $orderfunc = 'joomsport_ordermatchbydatetime';
        } 
        
        $mArray = array(
            'posts_per_page' => isset($team_id)?-1:((isset($limit) && $limit != 0)?$limit:-1),
            'offset'           => isset($team_id)?0:(isset($offset)?$offset:0),
            'post_type'        => 'joomsport_match',
            'post_status'      => 'publish',
            //'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'fields' => 'ID'
            );
        
        
        if(isset($matchday_id) && $matchday_id){
            $mArray['tax_query'] = array(
                array(
                'taxonomy' => 'joomsport_matchday',
                'field' => 'term_id',
                'terms' => $matchday_id)
            );
        }
        if(isset($season_id) && is_array($season_id)){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_seasonid',
                'value' => $season_id,
                'compare' => 'IN'    
            );
        }else if(isset($season_id) && $season_id > 0){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_seasonid',
                'value' => $season_id
            );


        }else{
            if(count($seasonsArray)){
                $mArray['meta_query'][] = 
                    array(
                    'key' => '_joomsport_seasonid',
                    'value' => $seasonsArray,
                    'compare' => 'IN'    
                );
            }else{
                $mArray['meta_query'][] = 
                    array(
                    'key' => '_joomsport_seasonid',
                    'value' => '-1',
                    'compare' => '='    
                );
            }
            
            
        }
        $mArray['meta_query'][] = array(
                    'key'     => '_joomsport_match_date',
                );
        $mArray['meta_query'][] = array(
                    'key'     => '_joomsport_match_time',
                    
                );
        if(isset($played)){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_match_played',
                'value' => $played
            );
        }
        
        
        if(isset($date_from)){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_match_date',
                'value' => $date_from,
                'compare' => '>='    
            );
        }
        if(isset($date_exclude)){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_match_date',
                'value' => $date_exclude,
                'compare' => '!='    
            );
        }
        if(isset($date_to)){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_match_date',
                'value' => $date_to,
                'compare' => '<='    
            );
        }
        
        if(isset($group_id) && $group_id){
            $mArray['meta_query'][] = 
                array(
                'key' => '_joomsport_groupID',
                'value' => $group_id,
                'compare' => '='    
            );
        }

        add_filter('posts_orderby',array('classJsportgetmatches', $orderfunc));
        //var_dump($mArray);
        $matchesAA = jsHelperSeasonMatches::getInstance($mArray);
        
        //$matches = new WP_Query($mArray);
        $matches = $matchesAA->posts;
        
       
        
        remove_filter('posts_orderby',array('classJsportgetmatches', $orderfunc));
        
        if(!isset($team_id)){
            /*$mArray['posts_per_page'] = -1;
            $mArray['offset'] = 0;

            $matches_count = new WP_Query($mArray);

            $matches_count = ($matches_count->post_count);*/
            $matches_count = $matchesAA->found_posts;
        }else{
            $matches_count = $matchesAA->post_count;
        }

        if(isset($team_id) && $matches){
            $matches_id = array();
            
            foreach($matches as $m){
                $matches_id[] = $m->ID;
            }
            
            if(count($matches_id)){
                if(isset($place) && $place == '1'){
                    $selteam = array(
                    'key' => '_joomsport_home_team',
                    'value' => $team_id
                    );
                }elseif(isset($place) && $place == '2'){
                    $selteam = array(
                    'key' => '_joomsport_away_team',
                    'value' => $team_id
                    );
                }else{
                    $selteam = array('relation' => 'OR',
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
                add_filter('posts_orderby',array('classJsportgetmatches', $orderfunc));
                $matches = new WP_Query(array(
                    'posts_per_page' => (isset($limit) && $limit != 0)?$limit:-1,
                    'offset'           => isset($offset)?$offset:0,
                    'post_type'        => 'joomsport_match',
                    'post_status'      => 'publish',
                    'order'     => 'DESC',
                        //'no_found_rows' => true,
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                        'fields' => 'ID',

                    'post__in'          =>     $matches_id,
                    'meta_query' => array(
                        array('relation' => 'AND',
                        array('key'     => '_joomsport_seasonid'),
                        array('key'     => '_joomsport_match_date'),
                        array('key'     => '_joomsport_match_time')),
                        $selteam

                        )
                    )
                );
                //var_dump($matches);
                remove_filter('posts_orderby',array('classJsportgetmatches', $orderfunc));
                $matches_count = ($matches->found_posts);
                $matches = $matches->posts;

                /*$matchesC = new WP_Query(array(
                    'posts_per_page' => -1,
                    'offset'           => 0,
                    'post_type'        => 'joomsport_match',
                    'post_status'      => 'publish',
                    'order'     => 'DESC',

                    'post__in'          =>     $matches_id,
                    'meta_query' => array(
                        array('relation' => 'AND',
                        array('key'     => '_joomsport_seasonid'),
                        array('key'     => '_joomsport_match_date'),
                        array('key'     => '_joomsport_match_time')),
                        $selteam

                        )
                    )
                );
                var_dump($matches_count);
                $matches_count = ($matchesC->post_count);
                //$matches_count = ($matches->found_posts);*/
            }
        }


        $result_array['list'] = $matches;
        $result_array['count'] = $matches_count;

        return $result_array;
    }
    public static function  joomsport_ordermatchbydatetime($orderby) {
        global $wpdb;
        return str_replace($wpdb->prefix.'posts.post_date',$wpdb->prefix.'postmeta.meta_value,  mt1.meta_value,mt2.meta_value, '.$wpdb->prefix.'posts.ID, '.$wpdb->prefix.'posts.post_date ', $orderby);

   }
   public static function  joomsport_ordermatchbydatetimeDesc($orderby) {
        global $wpdb;
        return str_replace($wpdb->prefix.'posts.post_date',$wpdb->prefix.'postmeta.meta_value desc,  mt1.meta_value desc,mt2.meta_value desc, '.$wpdb->prefix.'posts.post_date', $orderby);

   }

    public static function getMatchesFromDB($options){
        global $wpdb;
        $result_array = array();

        if ($options) {
            extract($options);
        }


        $aSeasons = jsHelperPublishedSeasons::getInstance();
        $seasonsArray = array();
        foreach($aSeasons as $aSeason){
            $seasonsArray[] = $aSeason->ID;
        }


        if (isset($ordering_dest) && $ordering_dest == 'desc'){
            $orderby = 'm.date desc,m.time desc,m.postID';
        }else{
            $orderby = 'm.date,m.time,m.postID';
        }


        $queryTeam = '';
        if((isset($team_id) && intval($team_id))){
            if(isset($place) && $place == '1'){
                $queryTeam = " AND m.teamHomeID = ".$team_id;
            }elseif(isset($place) && $place == '2'){
                $queryTeam = " AND m.teamAwayID = ".$team_id;
            }else{
                $queryTeam = " AND (m.teamHomeID = ".$team_id." OR m.teamAwayID = ".$team_id.")";
            }

        }

        $querySeason = '';
        if(isset($season_id) && is_array($season_id)){
            $querySeason = " AND m.seasonID IN (".implode(",",$season_id).")";
        }else if(isset($season_id) && $season_id > 0){
            $querySeason = " AND m.seasonID = {$season_id}";
        }else{
            if(count($seasonsArray)){
                $querySeason = " AND m.seasonID IN (".implode(",",$seasonsArray).")";

            }else{
                $querySeason = " AND m.seasonID = -1";

            }


        }

        $limitSql = (isset($limit) && $limit?" LIMIT ".((isset($offset)&&intval($offset))?$offset.",":"")." {$limit}":"");

        $query = "SELECT m.postID AS ID, mdID, seasonID"
            . " FROM {$wpdb->joomsport_matches} as m"
            . " JOIN {$wpdb->posts} as p ON p.ID = m.postID"
            ." WHERE p.post_status='publish'"
            .((isset($matchday_id) && $matchday_id)?" AND m.mdID = ".intval($matchday_id):"")
            .((isset($group_id) && $group_id)?" AND m.groupID = ".intval($group_id):"")
            .((isset($played))?" AND m.status = ".intval($played):"")
            .((isset($date_from) && $date_from)?" AND m.date >= '".($date_from)."'":"")
            .((isset($date_to) && $date_to)?" AND m.date <= '".($date_to)."'":"")
            .((isset($date_exclude) && $date_exclude)?" AND m.date != '".($date_exclude)."'":"")
            .$queryTeam
            .$querySeason
            ." ORDER BY {$orderby}";

        $list = $wpdb->get_results($query.$limitSql);
        $list_count = $wpdb->get_results($query);

        $result_array["list"] = $list;
        $result_array["count"] = count($list_count);
        return $result_array;
    }
}
