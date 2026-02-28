<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once JOOMSPORT_PATH_ENV_CLASSES.'class-jsport-getplayers.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-player.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-team.php';
require_once JOOMSPORT_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-dlists.php';
class classJsportPlayerlist
{
    public $season_id = null;
    public $lists = null;

    public function __construct($season_id = null)
    {
        $this->season_id = $season_id;
        if (!$this->season_id) {
            if(classJsportRequest::get('sid') != ''){
                $this->season_id = (int) classJsportRequest::get('sid');
            }else{
                $this->season_id = get_the_ID();
            }
        }
        $this->loadObject();
        $this->lists['options']['title'] = __('Player List','joomsport-sports-league-results-management');
        $this->setHeaderOptions();
    }

    private function loadObject()
    {
        global $jsDatabase;
        $options['season_id'] = $this->season_id;

        $link = classJsportLink::playerlist($this->season_id);
        if (classJsportRequest::get('sortf')) {
            $link .= '&sortf='.classJsportRequest::get('sortf');
            $link .= '&sortd='.classJsportRequest::get('sortd');
        }
        $pagination = new classJsportPagination($link);
        $options['limit'] = $pagination->getLimit();
        $options['offset'] = $pagination->getOffset();
        //
        $plorder = JoomsportSettings::get('pllistpage_order');
        if($plorder){
            $plorder = explode('_', $plorder);
            if(isset($plorder[1])){
                if($plorder[1] == '2'){
                    $options['ordering'] = 'eventid_'.intval($plorder[0]).' desc';
                }elseif($plorder[1] == '1'){
                    $ordering_ef = intval($plorder[0]);
                    if(isset($ordering_ef) && $ordering_ef){
                        $sql = "SELECT field_type"
                            . " FROM {$jsDatabase->db->joomsport_ef} WHERE id={$ordering_ef}";
                        $ef_type = $jsDatabase->selectValue($sql);
                        $this->ef_sort_id = $ordering_ef;
                    }
                    if($ef_type == 3){
                        $sql = "SELECT id as name, eordering as value"
                            . " FROM {$jsDatabase->db->joomsport_ef_select} WHERE fid={$ordering_ef}";
                        $ef_ordering = $jsDatabase->select($sql);
                        $ef_ordering_assocc = array();
                        for($intZ=0;$intZ<count($ef_ordering);$intZ++){
                            $ef_ordering_assocc[$ef_ordering[$intZ]->name] = $ef_ordering[$intZ]->value;
                        }
                        
                    }
                    
                }
            }
        }
        //
        if (classJsportRequest::get('sortf')) {
            $typeAD = in_array(classJsportRequest::get('sortd'), array("ASC","DESC"))?classJsportRequest::get('sortd'):"ASC";
            $options['ordering'] = str_replace(" ","",classJsportRequest::get('sortf')).' '.$typeAD;
        }

        $players = classJsportgetplayers::getPlayersFromTeam($options);
        $pagination->setPages($players['count']);
        $this->lists['pagination'] = $pagination;

        $players = $players['list'];
        $players_object = array();

        if ($players) {
            $count_players = count($players);
            $this->lists['ef_table'] = $ef = classJsportExtrafields::getExtraFieldListTable(0,false);
            for ($intC = 0; $intC < $count_players; ++$intC) {
                $row = $players[$intC];
                
                $obj = new classJsportPlayer($row->player_id, $this->season_id,false);
                $obj->lists['tblevents'] = $row;
                
                $players_object[$intC] = $obj->getRowSimple();
                $players_object[$intC]->teamID = $players[$intC]->team_id;
                for ($intB = 0; $intB < count($ef); ++$intB) {
                    $players_object[$intC]->{'ef_'.$ef[$intB]->id} = classJsportExtrafields::getExtraFieldValue($ef[$intB], $row->player_id, 0, $this->season_id);
                    if(isset($ef_type) && $ef_type == 3){
                        $orderValue = -1;
                        $meta = get_post_meta($row->player_id,'_joomsport_player_ef',true);
                        $meta_s = get_post_meta($row->player_id,'_joomsport_player_ef_'.$this->season_id,true);
                        if(isset($meta[$ef[$intB]->id])){
                            if(isset($ef_ordering_assocc[$meta[$ef[$intB]->id]])){
                                $orderValue = $ef_ordering_assocc[$meta[$ef[$intB]->id]];
                            }
                        }elseif(isset($meta_s[$ef[$intB]->id])){
                            if(isset($ef_ordering_assocc[$meta_s[$ef[$intB]->id]])){
                                $orderValue = $ef_ordering_assocc[$meta_s[$ef[$intB]->id]];
                            }
                        }
                        $players_object[$intC]->{'ef0_'.$ef[$intB]->id} = $orderValue;

                    }
                }
                
            }
            
            if(isset($ef_type) && !classJsportRequest::get('sortf')){
                
                $this->ef_type = $ef_type;
                
                if(count($players_object)){
                    usort($players_object, array($this,'sortPlayers'));
                }
                
            }
            
        }
        $this->lists['players'] = $this->lists['players_Stat'] = $players_object;
        
        //events
        $this->lists['events_col'] = classJsportgetplayers::getPlayersEvents($this->season_id);
    }
    public function sortPlayers($a,$b){
        if($this->ef_type == '3'){
            if ($a->{'ef0_'.$this->ef_sort_id} == $b->{'ef0_'.$this->ef_sort_id}) {
                return 0;
            }
            return ($a->{'ef0_'.$this->ef_sort_id} < $b->{'ef0_'.$this->ef_sort_id}) ? -1 : 1;
        }else{
            if ($a->{'ef_'.$this->ef_sort_id} == $b->{'ef_'.$this->ef_sort_id}) {
                return 0;
            }
            return ($a->{'ef_'.$this->ef_sort_id} < $b->{'ef_'.$this->ef_sort_id}) ? -1 : 1;
        }
        
        
    }
    public function getRow()
    {
        return $this;
    }
    public function setHeaderOptions()
    {
        if ($this->season_id) {
            $this->lists['options']['standings'] = $this->season_id;
            $this->lists['options']['calendar'] = $this->season_id;
        }
        $this->lists['options']['tourn'] = classJsportDlists::getSeasonsPlayerList($this->season_id);
    }
}
