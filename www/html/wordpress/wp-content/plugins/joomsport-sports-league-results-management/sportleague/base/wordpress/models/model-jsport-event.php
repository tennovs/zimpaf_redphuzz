<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class modelJsportEvent
{
    public $event_id = null;
    public $lists = null;
    private $row = null;

    public function __construct($id)
    {
        $this->event_id = $id;

        if (!$this->event_id) {
            die('ERROR! Event ID not DEFINED');
        }
        $this->loadObject();
    }
    private function loadObject()
    {
        global $jsDatabase;
        $arr = jsHelperEventsArr::getInstance();
        if(isset($arr[$this->event_id])){
            $this->row = $arr[$this->event_id];
        }else{
            $this->row = null;
        }
    }
    public function getRow()
    {
        return $this->row;
    }
}
