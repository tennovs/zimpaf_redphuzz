<?php
require_once JOOMSPORT_PATH_INCLUDES . 'moderator' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'joomsport-moderate-helper.php';

class JoomsportModerateACL{
    public static $acl = array(
        'team.edit' => false,
        'team.del' => false,
        'team.add' => false,
        'player.edit' => false,
        'player.del' => false,
        'player.add' => false,
        'match.edit' => false,
        'match.del' => false,
        'match.add' => false,
    );

    public static function parse($name, $ID){
        self::loadACL();
        $return = false;

        $nameFunc = str_replace(".","_", $name);
        if(method_exists('JoomsportModerateACL', $nameFunc)){
            $return = self::$nameFunc($ID);
        }else{
            $return = self::getACL($name);
        }
        return apply_filters($name, $return, $ID);
    }

    public static function loadACL(){

        $user = get_userdata( get_current_user_id() );

        $user_roles = $user->roles;

        if ( !in_array( 'joomsport_moderator', $user_roles, true ) ) {
            return;
        }

        self::setACL("team.add", (bool) JoomsportSettings::get('moder_team_add'));
        self::setACL("team.edit", (bool) JoomsportSettings::get('moder_team_edit'));
        self::setACL("team.del", (bool) JoomsportSettings::get('moder_team_del'));
        self::setACL("player.add", (bool) JoomsportSettings::get('moder_player_add'));
        self::setACL("player.edit", (bool) JoomsportSettings::get('moder_player_edit'));
        self::setACL("player.del", (bool) JoomsportSettings::get('moder_player_del'));
        self::setACL("match.add", (bool) JoomsportSettings::get('moder_match_add'));
        self::setACL("match.edit", (bool) JoomsportSettings::get('moder_match_edit'));
        self::setACL("match.del", (bool) JoomsportSettings::get('moder_match_del'));


    }

    public static function setACL($name, $val){
        self::$acl[$name] = $val;
    }

    public static function getACL($name){
        return isset(self::$acl[$name])?self::$acl[$name]:false;
    }

    public static function team_add($teamID){
        if(!self::$acl["team.add"]){
            return false;
        }
        $teamsMax = (int) JoomsportSettings::get('teams_per_account');

        $teams = JoomsportModerateHelper::getModerTeams();

        if($teamsMax && $teamsMax <= count($teams)){
            return false;
        }

        return true;
    }

    public static function player_add($playerID){
        if(!self::$acl["player.add"]){
            return false;
        }
        $playersMax = (int) JoomsportSettings::get('player_per_account');

        $players = JoomsportModerateHelper::getModerPlayers();

        if($playersMax && $playersMax <= count($players)){
            return false;
        }

        return true;
    }

    public static function match_edit($matchID){
        if(!self::$acl["match.edit"]){
            return false;
        }
        $home_team = get_post_meta( $matchID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $matchID, '_joomsport_away_team', true );

        $teamsObjs = JoomsportModerateHelper::getModerTeams();
        $teams = array();
        for($intA=0;$intA<count($teamsObjs);$intA++){
            $teams[] = $teamsObjs[$intA]->ID;
        }

        if(!in_array($home_team,$teams) && !in_array($away_team,$teams)){
            return false;
        }

        return true;
    }


}


