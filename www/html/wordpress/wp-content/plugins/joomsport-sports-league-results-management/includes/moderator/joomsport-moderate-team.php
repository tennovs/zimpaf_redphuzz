<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomsportModerateTeam{
    private $ID;
    public function __construct($ID)
    {
        $this->ID = $ID;
    }

    public function addTeam($teamName){
        $arr = array(
            'post_type' => 'joomsport_team',
            'post_title' => wp_strip_all_tags( $teamName ),
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => get_current_user_id()
        );
        $this->ID = wp_insert_post( $arr );
    }

    public function addTeamModerator(){

    }

    public function save(){

    }

    public function saveTitle(){

    }
    public function saveEF(){

    }
    public function canEdit(){
        return true;
    }
    public function canRemove(){
        return true;
    }

}