<?php
require_once 'class_jsport_h2h.php';
require_once JOOMSPORT_PATH_SL_HELPERS . "js-helper-btw.php";

class classJsportH2HAll extends classJsportH2H
{
    public $homeID;
    public $awayID;

    public function __construct($team1, $team2){
        $this->homeID = $team1;
        $this->awayID = $team2;
    }
    public function matchesBtw(){
        return jsHelperBtw::matches($this->homeID, $this->awayID);
    }
    public function statBtw($eventID){

    }
    public function wdlBtw(){

    }
}