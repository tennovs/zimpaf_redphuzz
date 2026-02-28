<?php
require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';

class JoomsportModerateActions{
    public static function init(){
        //team
        add_action( 'wp_ajax_joomsport_moder_team', array("JoomsportModerateActions",'joomsport_moder_team') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_team', array("JoomsportModerateActions",'joomsport_moder_team') );
        add_action( 'wp_ajax_joomsport_moder_team_save', array("JoomsportModerateActions",'joomsport_moder_team_save') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_team_save', array("JoomsportModerateActions",'joomsport_moder_team_save') );
        add_action( 'wp_ajax_joomsport_moder_team_list', array("JoomsportModerateActions",'joomsport_moder_team_list') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_team_list', array("JoomsportModerateActions",'joomsport_moder_team_list') );
        add_action( 'wp_ajax_joomsport_moder_team_del', array("JoomsportModerateActions",'joomsport_moder_team_del') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_team_del', array("JoomsportModerateActions",'joomsport_moder_team_del') );
        //player
        add_action( 'wp_ajax_joomsport_moder_player_list', array("JoomsportModerateActions",'joomsport_moder_player_list') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_player_list', array("JoomsportModerateActions",'joomsport_moder_player_list') );
        add_action( 'wp_ajax_joomsport_moder_player', array("JoomsportModerateActions",'joomsport_moder_player') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_player', array("JoomsportModerateActions",'joomsport_moder_player') );
        add_action( 'wp_ajax_joomsport_moder_player_save', array("JoomsportModerateActions",'joomsport_moder_player_save') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_player_save', array("JoomsportModerateActions",'joomsport_moder_player_save') );
        add_action( 'wp_ajax_joomsport_moder_player_del', array("JoomsportModerateActions",'joomsport_moder_player_del') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_player_del', array("JoomsportModerateActions",'joomsport_moder_player_del') );

        //match
        add_action( 'wp_ajax_joomsport_moder_match', array("JoomsportModerateActions",'joomsport_moder_match') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match', array("JoomsportModerateActions",'joomsport_moder_match') );
        add_action( 'wp_ajax_joomsport_moder_match_list', array("JoomsportModerateActions",'joomsport_moder_match_list') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_list', array("JoomsportModerateActions",'joomsport_moder_match_list') );
        add_action( 'wp_ajax_joomsport_moder_match_save', array("JoomsportModerateActions",'joomsport_moder_match_save') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_save', array("JoomsportModerateActions",'joomsport_moder_match_save') );

        add_action( 'wp_ajax_joomsport_moder_match_add', array("JoomsportModerateActions",'joomsport_moder_match_add') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_add', array("JoomsportModerateActions",'joomsport_moder_match_add') );

        add_action( 'wp_ajax_joomsport_moder_match_add_matchdays', array("JoomsportModerateActions",'joomsport_moder_match_add_matchdays') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_add_matchdays', array("JoomsportModerateActions",'joomsport_moder_match_add_matchdays') );

        add_action( 'wp_ajax_joomsport_moder_match_show_matchday', array("JoomsportModerateActions",'joomsport_moder_match_show_matchday') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_show_matchday', array("JoomsportModerateActions",'joomsport_moder_match_show_matchday') );
        add_action( 'wp_ajax_joomsport_moder_match_new', array("JoomsportModerateActions",'joomsport_moder_match_new') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_new', array("JoomsportModerateActions",'joomsport_moder_match_new') );

        add_action( 'wp_ajax_joomsport_moder_match_del', array("JoomsportModerateActions",'joomsport_moder_match_del') );
        add_action( 'wp_ajax_nopriv_joomsport_moder_match_del', array("JoomsportModerateActions",'joomsport_moder_match_del') );



    }

    public static function joomsport_moder_team(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        ob_start();
        $teamID = isset($_REQUEST["teamId"])?intval($_REQUEST["teamId"]):0;
        $teamPost = get_post($teamID);
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'team_edit.php';
        echo ob_get_clean();
        die();
    }
    public static function joomsport_moder_team_save(){
        require_once JOOMSPORT_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-meta-team.php';
        $postData = sanitize_text_field($_POST['data']);
        parse_str(($postData), $output);
        $result = array("error"=>1,"data"=>'');

        ob_start();
        $teamID = intval($output["teamID"]);
        $teamName = sanitize_text_field($output["teamName"]);

        if(!$teamName){
            $result["error"] = __("Team name is empty", 'joomsport-sports-league-results-management');
            echo json_encode($result);
            die();
        }


        if($teamID && JoomsportModerateHelper::Can('team.edit', $teamID)){
            $my_post = array(
                'ID'            => $teamID,
                'post_title' => wp_strip_all_tags( $teamName ),
            );
            wp_update_post( $my_post );
        }
        if(JoomsportModerateHelper::Can('team.add', $teamID) && !$teamID){
            $arr = array(
                'post_type' => 'joomsport_team',
                'post_title' => wp_strip_all_tags( $teamName ),
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => get_current_user_id()
            );
            $teamID = wp_insert_post( $arr );
            add_post_meta($teamID, '_joomsport_team_moderator', get_current_user_id());
        }
        if((intval($output["teamID"]) &&JoomsportModerateHelper::Can('team.edit', $teamID))
        || (!intval($output["teamID"]) && JoomsportModerateHelper::Can('team.add', $teamID))){


            JoomSportMetaTeam::saveMetaPersonal($teamID, $output);
            //JoomSportMetaTeam::saveMetaAbout($teamID);

            JoomSportMetaTeam::saveMetaEF($teamID, $output);

            JoomSportMetaTeam::saveMetaPlayers($teamID, $output);
        }
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'joomsport-moderate-team.php';
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'team_list.php';


        $result["error"] = $result["error"] == '1'?'':$result["error"];

        $result["data"] = ob_get_clean();

        echo json_encode($result);

        die();
    }

    public static function joomsport_moder_team_list(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'joomsport-moderate-team.php';
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'team_list.php';
        die();
    }

    public static function joomsport_moder_player_list(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'joomsport-moderate-team.php';
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'player_list.php';
        die();
    }
    public static function joomsport_moder_player(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        ob_start();
        require_once JOOMSPORT_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-meta-player.php';
        $playerID = isset($_REQUEST["playerId"])?intval($_REQUEST["playerId"]):0;
        $playerPost = get_post($playerID);
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'player_edit.php';
        echo ob_get_clean();
        die();
    }
    public static function joomsport_moder_player_save(){
        require_once JOOMSPORT_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-meta-player.php';
        $postData = sanitize_text_field($_POST['data']);
        parse_str($postData, $output);
        $result = array("error"=>1,"data"=>'');

        ob_start();

        $playerID = intval($output["playerID"]);
        $playerTitle = sanitize_text_field($output["personal"]["first_name"]);
        $playerTitle .= " ".sanitize_text_field($output["personal"]["last_name"]);

        if(!sanitize_text_field($output["personal"]["first_name"]) || !sanitize_text_field($output["personal"]["last_name"])){
            $result["error"] = __("First name or Last name is empty", 'joomsport-sports-league-results-management');
            echo json_encode($result);
            die();
        }

        if($playerID && JoomsportModerateHelper::Can('player.edit', $playerID)){
            $my_post = array(
                'ID'            => $playerID,
                'post_title' => wp_strip_all_tags( $playerTitle ),
            );
            wp_update_post( $my_post );
        }

        if(JoomsportModerateHelper::Can('player.add', $playerID) && !$playerID){
            $arr = array(
                'post_type' => 'joomsport_player',
                'post_title' => wp_strip_all_tags( $playerTitle ),
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => get_current_user_id()
            );
            $playerID = wp_insert_post( $arr );

        }
        if(( intval($output["playerID"]) && JoomsportModerateHelper::Can('player.edit', $playerID))
        || (!intval($output["playerID"]) && JoomsportModerateHelper::Can('player.add', $playerID))){


            JoomSportMetaPlayer::saveMetaPersonal($playerID, $output);
            //JoomSportMetaTeam::saveMetaAbout($teamID);

            JoomSportMetaPlayer::saveMetaEF($playerID, $output);


        }
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'joomsport-moderate-team.php';
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'player_list.php';

        $result["error"] = $result["error"] == '1'?'':$result["error"];

        $result["data"] = ob_get_clean();

        echo json_encode($result);
        die();
    }

    public static function joomsport_moder_match_list(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'joomsport-moderate-team.php';
        require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';
        $filters = isset($_REQUEST["filters"])?(array_map( 'sanitize_text_field', $_REQUEST["filters"] )):array();

        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'match_list.php';
        die();
    }

    public static function joomsport_moder_match(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';

        ob_start();
        $matchID = isset($_REQUEST["matchID"])?intval($_REQUEST["matchID"]):0;
        $teamPost = get_post($matchID);
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'match_edit.php';
        echo ob_get_clean();
        die();
    }
    public static function joomsport_moder_match_save(){
        require_once JOOMSPORT_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-meta-match.php';
        $postData = sanitize_text_field($_POST['data']);
        parse_str($postData, $output);

        $matchID = intval($output["matchID"]);


        if(JoomsportModerateHelper::Can('match.edit', $matchID)){


            JoomSportMetaMatch::saveMetaScore($matchID, $output);
            //JoomSportMetaMatch::saveMetaAbout($matchID);
            JoomSportMetaMatch::saveMetaGeneral($matchID, $output);

            JoomSportMetaMatch::saveMetaPlayerEvents($matchID, $output);
            JoomSportMetaMatch::saveMetaMatchEvents($matchID, $output);

            JoomSportMetaMatch::saveMetaEF($matchID, $output);
            JoomSportMetaMatch::saveMetaLineup($matchID, $output);
            JoomSportMetaMatch::saveMetaSubs($matchID, $output);


        }
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'match_list.php';
        die();
    }

    public static function joomsport_moder_match_add(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';
        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'match_add.php';
        die();
    }

    public static function joomsport_moder_match_add_matchdays(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_ENV_CLASSES.'class-jsport-getmdays.php';
        ob_start();
        $seasonID = isset($_REQUEST["seasonID"])?intval($_REQUEST["seasonID"]):0;
        $mdoptions = array();
        $mdoptions['season_id'] = $seasonID;
        $mdoptions['ordering'] = 'md.ordering, md.m_name, md.id';

        $mdays = classJsportgetmdays::getMdays($mdoptions);

        if(count($mdays)){
            echo '<select name="MatchADDmdayId" id="MatchADDmdayId">';
            echo '<option value="0">'.__("Select Matchday", "joomsport-sports-league-results-management").'</option>';
            for($intA=0;$intA<count($mdays);$intA++){
                echo '<option value="'.$mdays[$intA]->id.'">'.$mdays[$intA]->m_name.'</option>';
            }
            echo '</select>';
        }

        echo ob_get_clean();
        die();
    }

    public static function joomsport_moder_match_show_matchday(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        ob_start();
        $matchdayID = isset($_REQUEST["matchdayID"])?intval($_REQUEST["matchdayID"]):0;

        $metas = get_option("taxonomy_{$matchdayID}_metas");
        $seasonID = $metas['season_id'];
        if(!$seasonID){
            return '';
        }
        $participiants = JoomSportHelperObjects::getParticipiants($seasonID);

        $matches = JoomsportModerateHelper::getMdMatches($matchdayID);

        require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'matchday_matches.php';

        echo ob_get_clean();
        die();
    }

    public static function joomsport_moder_match_new(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';

        $result = array("error"=>1,"data"=>'');

        ob_start();
        $teamsObjs = JoomsportModerateHelper::getModerTeams();
        $teams = array();
        for($intA=0;$intA<count($teamsObjs);$intA++){
            $teams[] = $teamsObjs[$intA]->ID;
        }



        $matchdayID = isset($_REQUEST["matchdayID"])?intval($_REQUEST["matchdayID"]):0;
        $metas = get_option("taxonomy_{$matchdayID}_metas");
        $seasonID = $metas['season_id'];
        if(!$seasonID){
            $result["error"] = __("Error", 'joomsport-sports-league-results-management');
        }

        $homeID = isset($_REQUEST["homeID"])?intval($_REQUEST["homeID"]):0;
        $awayID = isset($_REQUEST["awayID"])?intval($_REQUEST["awayID"]):0;
        $mDate = isset($_REQUEST["mDate"])?sanitize_text_field($_REQUEST["mDate"]):'';
        $mTime = isset($_REQUEST["mTime"])?sanitize_text_field($_REQUEST["mTime"]):'';

        if(!$homeID || !$awayID){
            $result["error"] = __("Please select team", 'joomsport-sports-league-results-management');
        }
        if(!in_array($homeID,$teams) && !in_array($awayID,$teams)){
            $result["error"] = __("Please select team you can moderate", 'joomsport-sports-league-results-management');
        }
        if($homeID == $awayID){
            $result["error"] = __("Please select another team", 'joomsport-sports-league-results-management');
        }
        if(!JoomsportModerateHelper::Can('match.add', 0)){
            $result["error"] = __("You don't have permissions", 'joomsport-sports-league-results-management');
        }
        if($result["error"] == '1') {
            $title = get_the_title($homeID) . (empty(JoomsportSettings::get('jsconf_home_away_separator_vs')) ? ' vs ' : ' ' . JoomsportSettings::get('jsconf_home_away_separator_vs') . ' ') . get_the_title($awayID);
            $arr = array(
                'post_type' => 'joomsport_match',
                'post_title' => wp_strip_all_tags($title),
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => get_current_user_id()
            );

            $post_id = wp_insert_post($arr);

            if ($post_id) {
                update_post_meta($post_id, '_joomsport_home_team', intval($homeID));
                update_post_meta($post_id, '_joomsport_away_team', intval($awayID));
                update_post_meta($post_id, '_joomsport_home_score', 0);
                update_post_meta($post_id, '_joomsport_away_score', 0);
                //update_post_meta($post_id, '_joomsport_groupID', $groupID);
                update_post_meta($post_id, '_joomsport_seasonid', $seasonID);

                update_post_meta($post_id, '_joomsport_match_played', 0);

                update_post_meta($post_id, '_joomsport_match_date', $mDate);

                update_post_meta($post_id, '_joomsport_match_time', $mTime);
                update_post_meta($post_id, '_joomsport_match_venue', 0);

                wp_set_post_terms($post_id, array((int)$matchdayID), 'joomsport_matchday');

                do_action("joomsport_pull_match", $post_id);

                $participiants = JoomSportHelperObjects::getParticipiants($seasonID);

                $matches = JoomsportModerateHelper::getMdMatches($matchdayID);

                require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'matchday_matches.php';


            }
        }

        $result["error"] = $result["error"] == '1'?'':$result["error"];

        $result["data"] = ob_get_clean();

        echo json_encode($result);
        die();
    }

    public static function joomsport_moder_team_del(){
        $result = array("error"=>1,"data"=>'');
        $teamID = isset($_REQUEST["teamID"])?intval($_REQUEST["teamID"]):0;
        ob_start();
        $teamsObjs = JoomsportModerateHelper::getModerTeams();
        $teams = array();
        for($intA=0;$intA<count($teamsObjs);$intA++){
            $teams[] = $teamsObjs[$intA]->ID;
        }
        if(!in_array($teamID,$teams) && !$teamID){
            $result["error"] = __("You can't delete selected team", 'joomsport-sports-league-results-management');
        }
        if(!JoomsportModerateHelper::Can('team.del', $teamID)){
            $result["error"] = __("You don't have permissions", 'joomsport-sports-league-results-management');
        }
        if($result["error"] == '1') {
            if (!wp_delete_post($teamID)) {
                $result["error"] = __("Couldn't delete team", 'joomsport-sports-league-results-management');
            }
        }


        $result["error"] = $result["error"] == '1'?'':$result["error"];

        echo json_encode($result);
        die();
    }

    public static function joomsport_moder_player_del(){
        $result = array("error"=>1,"data"=>'');
        $playerID = isset($_REQUEST["playerID"])?intval($_REQUEST["playerID"]):0;
        ob_start();
        $teamsObjs = JoomsportModerateHelper::getModerPlayers();
        $teams = array();
        for($intA=0;$intA<count($teamsObjs);$intA++){
            $teams[] = $teamsObjs[$intA]->ID;
        }
        if(!in_array($playerID,$teams) && !$playerID){
            $result["error"] = __("You can't delete selected player", 'joomsport-sports-league-results-management');
        }
        if(!JoomsportModerateHelper::Can('player.add', $playerID)){
            $result["error"] = __("You don't have permissions", 'joomsport-sports-league-results-management');
        }
        if($result["error"] == '1') {
            if (!wp_delete_post($playerID)) {
                $result["error"] = __("Couldn't delete player", 'joomsport-sports-league-results-management');
            }
        }

        $result["error"] = $result["error"] == '1'?'':$result["error"];

        echo json_encode($result);
        die();
    }

    public static function joomsport_moder_match_del(){
        $result = array("error"=>1,"data"=>'');
        $matchID = isset($_REQUEST["matchID"])?intval($_REQUEST["matchID"]):0;
        ob_start();
        $teamsObjs = JoomsportModerateHelper::getModerTeams();
        $teams = array();
        for($intA=0;$intA<count($teamsObjs);$intA++){
            $teams[] = $teamsObjs[$intA]->ID;
        }
        $home_team = get_post_meta( $matchID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $matchID, '_joomsport_away_team', true );

        if(!in_array($home_team,$teams) && !in_array($away_team,$teams)){
            $result["error"] = __("You can't delete selected match", 'joomsport-sports-league-results-management');
        }

        if(!JoomsportModerateHelper::Can('match.del', $matchID)){
            $result["error"] = __("You don't have permissions", 'joomsport-sports-league-results-management');
        }

        if($result["error"] == '1') {
            if (!wp_delete_post($matchID)) {
                $result["error"] = __("Couldn't delete match", 'joomsport-sports-league-results-management');
            }
        }


        $result["error"] = $result["error"] == '1'?'':$result["error"];

        echo json_encode($result);
        die();
    }



}

add_action( 'init', array( 'JoomsportModerateActions', 'init' ), 4);

