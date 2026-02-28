<?php

abstract class classJsportH2H
{
    public $homeID;
    public $awayID;

    public function __construct($team1, $team2){
        $this->homeID = $team1;
        $this->awayID = $team2;
    }
    abstract public function matchesBtw();
    abstract public function statBtw($eventID);
    abstract public function wdlBtw();

    public function checkIDs(){

    }
}