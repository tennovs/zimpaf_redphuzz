<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsHelperMatchStatus
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $jsDatabase;
            $query = 'SELECT *'
                .' FROM '.$jsDatabase->db->joomsport_match_statuses;

            static::$instance = $jsDatabase->select($query);
        }
        return static::$instance;
    }
}

class jsHelperPublishedSeasons
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            $argsSeasons = array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'orderby'          => 'title',
                'order'            => 'ASC',
                'post_type'        => 'joomsport_season',
                'post_status'      => 'publish',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'fields' => 'ID'
            );
            $seasons = new WP_Query($argsSeasons);
            static::$instance = $seasons->posts;

            
        }
        return static::$instance;
    }
}
class jsHelperAllTeamPosts
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {

        if (!isset(static::$instance)) {
            $argsSeasons = array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'post_type'        => 'joomsport_team',
                'post_status'      => 'publish',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'fields' => 'ID'
            );
            $tms = new WP_Query($argsSeasons);
            $tm = $tms->posts;
            $teams = array();
            
            for($intA=0;$intA<count($tm);$intA++){
                $teams[$tm[$intA]->ID] = $tm[$intA];
            }
            
            static::$instance = $teams;

            
        }
        return static::$instance;
    }
}

class jsHelperAllPlayersPosts
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {

        if (!isset(static::$instance)) {
            $argsSeasons = array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'post_type'        => 'joomsport_player',
                'post_status'      => 'publish',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'fields' => 'ID'
            );
            $tms = new WP_Query($argsSeasons);
            $tm = $tms->posts;

            $teams = array();

            for($intA=0;$intA<count($tm);$intA++){
                $teams[$tm[$intA]->ID] = $tm[$intA];
            }
            
            static::$instance = $teams;

            
        }
        return static::$instance;
    }
}

class jsHelperSeasonMatches
{
    protected static $instance = array();
    
    protected function __construct() {
        
    }
    
    public static function getInstance($args)
    {
        if(isset(static::$instance)){
            for($intA=0;$intA<count(static::$instance);$intA++){
                $Tmp = static::$instance[$intA];
                
                if($Tmp["args"] == $args){
                    return $Tmp["matches"];
                }
                
            }
        }
        //var_dump($args);

        $matches = new WP_Query($args);
        $nArray = array("args" => $args, "matches" => $matches);
        static::$instance[] = $nArray;
        
        return $matches;
        
    }
}

class jsHelperSeasonPartic
{
    protected static $instance = array();
    
    protected function __construct() {
        
    }
    
    public static function getInstance($season_id, $participiants, $t_single)
    {
        if(!isset(static::$instance[$season_id])){
            static::$instance[$season_id] = get_posts(array(
                'post_type' => $t_single?'joomsport_player':'joomsport_team',
                'include' => $participiants,
                'orderby' => 'title',
                'order' => 'ASC')
            );
        }

        
        return static::$instance[$season_id];
        
    }
}

class jsHelperTeamEvents
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $wpdb;
            static::$instance = $wpdb->get_results("SELECT * FROM {$wpdb->joomsport_events} WHERE player_event='0' ORDER BY ordering");
            

            
        }
        return static::$instance;
    }
}

class jsHelperEventsResType
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $jsDatabase;
            $query = 'SELECT result_type as value, id as name FROM '.$jsDatabase->db->joomsport_events."";
            static::$instance = $jsDatabase->selectKeyPair($query);
            
        }
        return static::$instance;
    }
}
class jsHelperEventsArr
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $jsDatabase;
            $query = 'SELECT * FROM '.$jsDatabase->db->joomsport_events."";
            $evs = $jsDatabase->select($query);
            $arr = array();
            for($intA=0;$intA<count($evs);$intA++){
                $arr[$evs[$intA]->id] = $evs[$intA];
            }
            static::$instance = $arr;
            
        }
        return static::$instance;
    }
}

class jsHelperEventsSelvar
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $jsDatabase;
            $query = 'SELECT sel_value,id FROM '.$jsDatabase->db->joomsport_ef_select;
            $evs = $jsDatabase->select($query);
            $arr = array();
            for($intA=0;$intA<count($evs);$intA++){
                $arr[$evs[$intA]->id] = $evs[$intA]->sel_value;
            }
            static::$instance = $arr;
            
        }
        return static::$instance;
    }
}

class jsHelperBoxScore
{
    protected static $instance = null;
    
    protected function __construct() {
        
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $wpdb;
            $cBox = $wpdb->get_results('SELECT * FROM '.$wpdb->joomsport_box) ;
            $arr = array();
            for($intA=0;$intA<count($cBox);$intA++){
                $arr[$cBox[$intA]->id] = $cBox[$intA];
            }
            static::$instance = $arr;
            
        }
        return static::$instance;
    }
}

class jsHelperTermMatchday
{
    protected static $instance = null;

    protected function __construct() {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            if(get_bloginfo('version') < '4.5.0'){
                $tx = get_terms('joomsport_matchday',array(
                    "hide_empty" => false
                ));
            }else{
                $tx = get_terms(array(
                    "taxonomy" => "joomsport_matchday",
                    "hide_empty" => false,
                    'orderby' => "id",
                ));
            }
            $mdays = array();
            for($intA=0;$intA<count($tx);$intA++){
                $term_meta = get_option( "taxonomy_".$tx[$intA]->term_id."_metas");

                $tmp = new stdClass();
                $tmp->id = $tx[$intA]->term_id;
                $tmp->name = $tx[$intA]->name;
                $tmp->matchday_type = isset($term_meta['matchday_type'])?$term_meta['matchday_type']:0;
                $tmp->season_id = isset($term_meta['season_id'])?$term_meta['season_id']:0;
                $tmp->is_playoff = isset($term_meta['is_playoff'])?$term_meta['is_playoff']:0;
                $mdays[$tx[$intA]->term_id] = $tmp;



            }

            static::$instance = $mdays;
        }
        return static::$instance;
    }

}
class jsHelperTermTourn
{
    protected static $instance = null;

    protected function __construct() {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            if(get_bloginfo('version') < '4.5.0'){
                $tx = get_terms('joomsport_tournament',array(
                    "hide_empty" => false
                ));
            }else{
                $tx = get_terms(array(
                    "taxonomy" => "joomsport_tournament",
                    "hide_empty" => false
                ));
            }
            $mdays = array();
            for($intA=0;$intA<count($tx);$intA++){
                $term_meta = get_option( "taxonomy_".$tx[$intA]->term_id."_metas");

                $tmp = new stdClass();
                $tmp->id = $tx[$intA]->term_id;
                $tmp->name = $tx[$intA]->name;
                $tmp->matchday_type = isset($term_meta['matchday_type'])?$term_meta['matchday_type']:0;
                $tmp->season_id = isset($term_meta['season_id'])?$term_meta['season_id']:0;
                $tmp->is_playoff = isset($term_meta['is_playoff'])?$term_meta['is_playoff']:0;
                $mdays[$tx[$intA]->term_id] = $tmp;



            }

            static::$instance = $mdays;
        }
        return static::$instance;
    }

}
class jsHelperHighlightPlayers
{
    protected static $instance = null;

    protected function __construct() {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            global $wpdb;
            $players = array();
            $yteams = JoomsportSettings::get('yteams',array());
            if(count($yteams)){
                $players = $wpdb->get_col("SELECT player_id FROM {$wpdb->joomsport_playerlist} WHERE team_id IN (".implode(",",$yteams).") GROUP BY player_id");
            }
            static::$instance = $players;

        }
        return static::$instance;
    }
}