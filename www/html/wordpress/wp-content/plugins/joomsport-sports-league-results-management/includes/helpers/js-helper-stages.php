<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsHelperStages
{
    public static function getStagesManual($seasonID){
        global $wpdb;
        $metadata = get_post_meta($seasonID,'_joomsport_season_stages',true);
        if($metadata && count($metadata)){
            $stages = $wpdb->get_results("SELECT * FROM {$wpdb->joomsport_maps} WHERE ID IN(".implode(",",$metadata).") AND separate_events = '1'");
            return $stages;
        }
        return null;
    }

    public static function getStagesAutomatic($seasonID){
        global $wpdb;
        $metadata = get_post_meta($seasonID,'_joomsport_season_stages',true);
        if($metadata && count($metadata)){
            $stages = $wpdb->get_results("SELECT * FROM {$wpdb->joomsport_maps} WHERE ID IN(".implode(",",$metadata).") AND separate_events = '2' AND time_to > time_from ORDER BY time_from,time_to");
            return $stages;
        }
        return null;
    }

    public static function getEventsByStages($matchID, $stageID){
        global $wpdb;

        $query = "SELECT me.*,me.id as meid,ev.id as id,ev.e_name,(subev.e_id) as subevID, GROUP_CONCAT(CONCAT(subev.t_id,'*',subev.player_id)) as subevPl, evSub.e_name as subEn, GROUP_CONCAT(subev.player_id) as plFM"
            ." FROM  {$wpdb->joomsport_match_events} as me"
            . " JOIN {$wpdb->joomsport_events} as ev ON me.e_id = ev.id AND me.match_id = ".$matchID
            . " LEFT JOIN {$wpdb->joomsport_match_events} as subev ON subev.additional_to = me.id"
            . " LEFT JOIN {$wpdb->joomsport_events} as evSub ON subev.e_id = evSub.id "
            ." WHERE ev.player_event = '1' AND ev.dependson=''"
            ." AND me.stage_id = " . $stageID

            //.' '.(count($pl_list) ? 'AND me.player_id IN('.implode(',', $pl_list).')' : '')
            .' GROUP BY me.id'
            .' ORDER BY me.eordering, CAST(me.minutes AS UNSIGNED)';
        $pevents = $wpdb->get_results($query);

        return $pevents;
    }

    public static function getEventsByTime($matchID, $stageObj){
        global $wpdb;

        $query = "SELECT me.*,me.id as meid,ev.id as id,ev.e_name,(subev.e_id) as subevID, GROUP_CONCAT(CONCAT(subev.t_id,'*',subev.player_id)) as subevPl,evSub.e_name as subEn, GROUP_CONCAT(subev.player_id) as plFM"
            ." FROM  {$wpdb->joomsport_match_events} as me"
            . " JOIN {$wpdb->joomsport_events} as ev ON me.e_id = ev.id AND me.match_id = ".$matchID
            . " LEFT JOIN {$wpdb->joomsport_match_events} as subev ON subev.additional_to = me.id"
            . " LEFT JOIN {$wpdb->joomsport_events} as evSub ON subev.e_id = evSub.id "
            ." WHERE ev.player_event = '1' AND ev.dependson=''"
            ." AND me.stage_id = 0"
            ." AND CAST(me.minutes AS UNSIGNED) >= ".$stageObj->time_from
            ." AND CAST(me.minutes AS UNSIGNED) <= ".$stageObj->time_to

            //.' '.(count($pl_list) ? 'AND me.player_id IN('.implode(',', $pl_list).')' : '')
            .' GROUP BY me.id'
            .' ORDER BY me.eordering, CAST(me.minutes AS UNSIGNED)';
        $pevents = $wpdb->get_results($query);

        return $pevents;
    }
    public static function getNotStageEvents($matchID, $not_in_obj){
        global $wpdb;
        $not_in = array();
        for($intA=0;$intA<count($not_in_obj);$intA++){
            for ($intB=0;$intB<count($not_in_obj[$intA]['events']);$intB++){
                $not_in[] = $not_in_obj[$intA]['events'][$intB]->meid;
            }

        }

        $query = "SELECT me.*,me.id as meid,ev.id as id,ev.e_name,(subev.e_id) as subevID, GROUP_CONCAT(CONCAT(subev.t_id,'*',subev.player_id)) as subevPl,evSub.e_name as subEn, GROUP_CONCAT(subev.player_id) as plFM"
            ." FROM  {$wpdb->joomsport_match_events} as me"
            . " JOIN {$wpdb->joomsport_events} as ev ON me.e_id = ev.id AND me.match_id = ".$matchID
            . " LEFT JOIN {$wpdb->joomsport_match_events} as subev ON subev.additional_to = me.id"
            . " LEFT JOIN {$wpdb->joomsport_events} as evSub ON subev.e_id = evSub.id "
            ." WHERE ev.player_event = '1' AND ev.dependson=''"
            .(count($not_in)?" AND me.id NOT IN (".implode(",",$not_in).")":"")


            //.' '.(count($pl_list) ? 'AND me.player_id IN('.implode(',', $pl_list).')' : '')
            .' GROUP BY me.id'
            .' ORDER BY me.eordering, CAST(me.minutes AS UNSIGNED)';
        $pevents = $wpdb->get_results($query);

        return $pevents;
    }

    public static function getMatchEvents($matchID, $seasonID){
        $eventsByStages = array();
        $auto = self::getStagesAutomatic($seasonID);
        $manual = self::getStagesManual($seasonID);
        if($auto && count($auto)){
            foreach ($auto as $stage){
                $eventsByStages[] = array("events" => self::getEventsByTime($matchID, $stage), "stage" => $stage);
            }
        }
        if($manual && count($manual)){
            foreach ($manual as $stage){
                $eventsByStages[] = array("events" => self::getEventsByStages($matchID, $stage->id), "stage" => $stage);
            }
        }

        return $eventsByStages;

    }

}
