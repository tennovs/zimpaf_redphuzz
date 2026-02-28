<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once JOOMSPORT_PATH_MODELS.'model-jsport-match.php';
require_once JOOMSPORT_PATH_ENV_CLASSES.'class-jsport-participant.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-venue.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-event.php';
class classJsportMatch
{
    public $id = null;
    public $object = null;
    public $season_id = null;
    public $lists = null;
    public $model = null;
    public $mdId = null;

    public function __construct($id = 0, $calcLists = true, $mdId = null, $seasonId = null)
    {
        $this->id = $id;
        if (!$this->id) {
            $this->id = get_the_ID();
        }
        if (!$this->id) {
            die('ERROR! Match ID not DEFINED');
        }

        $this->mdId = $mdId;

        $obj = $this->model = new modelJsportMatch($this->id, $seasonId);

        $this->loadObject($obj->row);
        $this->loadSeasonID($obj->getSeasonID());

        if ($calcLists) {
            $obj->loadLists();
        }
        $this->lists = $obj->lists;

        $this->lists['seasObj'] = $obj->getSeasonOptions();
        $this->lists['mStatuses'] = $obj->getCustomMatch();
    }

    public function loadObject($row)
    {
        $this->object = $row;
    }

    public function loadSeasonID($season_id)
    {
        $this->season_id = $season_id;
    }

    public function getObject()
    {
        return $this->object;
    }


    public function getTips()
    {
    }

    public function getParticipantHome()
    {

        $obj = new classJsportParticipant($this->season_id);

        if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
            $home_team = get_post_meta( $this->id, '_joomsport_away_team', true );
        }else{
            $home_team = get_post_meta( $this->id, '_joomsport_home_team', true );
            
        }

        if ($home_team > 0) {
            $part = $obj->getParticipiantObj($home_team);
            return $part;
        } else {
            return;
        }
    }
    public function getParticipantAway()
    {
        $obj = new classJsportParticipant($this->season_id);

        if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
            $away_team = get_post_meta( $this->id, '_joomsport_home_team', true );
        }else{
            $away_team = get_post_meta( $this->id, '_joomsport_away_team', true );
        }
        
        if ($away_team > 0) {
            $part = $obj->getParticipiantObj($away_team);

            return $part;
        } else {
            return;
        }
    }

    public function getRow()
    {
        $this->getTitle($this);
        $this->setHeaderOptions();
                //do_action('joomsport_update_standings',$this->season_id, array());
                //do_action('joomsport_update_playerlist',$this->season_id, array());
        return $this;
    }
    public function getRowSimple()
    {
        return $this;
    }

    public function getTabs()
    {
        $tabs = array();
        $intA = 0;



        $this->getTeamEvenetObj($this->lists['team_events']);
        //main tab
        $tabs[$intA]['id'] = 'stab_main';
        $tabs[$intA]['title'] = __('Overview','joomsport-sports-league-results-management');
        $tabs[$intA]['body'] = 'match-view.php';
        $tabs[$intA]['text'] = '';
        $tabs[$intA]['class'] = '';
        $tabs[$intA]['ico'] = 'js-match';
        //about
        $match_descr = get_post_meta($this->id,'_joomsport_match_about',true);
        if ($match_descr) {
            ++$intA;
            $tabs[$intA]['id'] = 'stab_about';
            $tabs[$intA]['title'] = __('About','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = '';
            $tabs[$intA]['text'] = classJsportText::getFormatedText($match_descr);
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-about';
        }
        //box score
        $this->getBoxScoreList();
        if (($this->lists['boxscore_home'] != '') || ($this->lists['boxscore_away'] != '')) {
            ++$intA;
            $tabs[$intA]['id'] = 'stab_boxscore';
            $tabs[$intA]['title'] = __('Box Score','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = 'boxscore.php';
            $tabs[$intA]['text'] = '';
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-boxscr';
        }
        
        
        //squad
        if (count($this->lists['squard1']) || count($this->lists['squard2'])) {
            $this->getPlayerObj($this->lists['squard1']);
            $this->getPlayerObj($this->lists['squard2']);
            $this->getPlayerObj($this->lists['squard1_res']);
            $this->getPlayerObj($this->lists['squard2_res']);
            ++$intA;
            $tabs[$intA]['id'] = 'stab_squad';
            $tabs[$intA]['title'] = __('Lineups','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = 'squad-list.php';
            $tabs[$intA]['text'] = '';
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-squad';
        }
        //photos
        if (count($this->lists['photos'])) {
            ++$intA;
            $tabs[$intA]['id'] = 'stab_photos';
            $tabs[$intA]['title'] = __('Photos','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = 'gallery.php';
            $tabs[$intA]['text'] = '';
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-photo';
        }
        if ( has_filter( 'joomsport_custom_tab_fe' ) ){
            $tabs = apply_filters("joomsport_custom_tab_fe", $this->id, $tabs);
        }
        
        
        return $tabs;
    }

    public function getPlayerObj(&$players)
    {
        $players_object = array();
        $intU = 0;
        
        if ($players) {
            foreach ($players as $row) {
                if (($row->player_id)) {

                    $obj = new classJsportPlayer($row->player_id, $this->season_id, false);

                    if(!isset($obj->object)){
                        unset($players[$intU]);
                    }else{
                        
                        $objEvent = new classJsportEvent($row->id);
                        $players[$intU]->objEvent = $objEvent;
                        $players[$intU]->obj = $obj->getRowSimple();
                        $players_object[] = $players[$intU];
                        ++$intU;
                    }
                }
            }
        }
        $players = $players_object;
        //$this->lists['players'] = $players_object;
    }
    public function getTeamEvenetObj(&$events)
    {
        $events_object = array();
        $intU = 0;
        
        $new_events = array();
        if ($events) {
            
            foreach ($events as $key=>$value) {

                $objEvent = new classJsportEvent($key);
                $tmpEvent = new stdClass();
                $tmpEvent->objEvent = $objEvent;
                if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
                    $tmpEvent->away_value = $value['mevents1'];
                    $tmpEvent->home_value = $value['mevents2'];
                }else{
                    $tmpEvent->home_value = $value['mevents1'];
                    $tmpEvent->away_value = $value['mevents2'];
                }
                
                $new_events[$intU] = $tmpEvent;
                ++$intU;
            }
        }
        $events = $new_events;
        //$this->lists['players'] = $players_object;
    }

    public function getTitle($match)
    {
        $partic_home = $this->getParticipantHome();
        $partic_away = $this->getParticipantAway();

        $title = '';
        if ($partic_home) {
            $title .= $partic_home->getName().' ';
        }
        $title .= jsHelper::getScore($match);
        if ($partic_away) {
            $title .= ' '.$partic_away->getName();
        }
        $this->lists['options']['title'] = '';//$title;
        $this->lists['options']['titleSocial'] = $title;//$title;
        $this->lists['options']['calendar'] = $this->season_id;
        $this->lists['options']['standings'] = $this->season_id;
    }

    public function getLocation($linkable = true)
    {
        if(JoomsportSettings::get('unbl_venue',1)){
            $m_venue = get_post_meta($this->id,'_joomsport_match_venue',true);
            if ($m_venue) {
                $venue = new classJsportVenue($m_venue);
                if(isset($venue->object->post_status) && $venue->object->post_status != 'trash'){
                    return $venue->getName($linkable);
                }
            }
        }
    }
    public function getETLabel($et = true)
    {
        $home_score = get_post_meta( $this->id, '_joomsport_home_score', true );
        $away_score = get_post_meta( $this->id, '_joomsport_away_score', true );
        $home_team = get_post_meta( $this->id, '_joomsport_home_team', true );
        $away_team = get_post_meta( $this->id, '_joomsport_away_team', true );
        $m_played = get_post_meta($this->id,'_joomsport_match_played',true);
        $jmscore = get_post_meta($this->id, '_joomsport_match_jmscore',true);
        $season_options = get_post_meta($this->season_id,'_joomsport_season_point',true);
        $enabla_extra = (isset($season_options['s_enbl_extra']) && $season_options['s_enbl_extra']) ? 1:0;
        if ($m_played != '1') {
            return '';
        }
        
    }

    public function getBonusLabel()
    {
        $jmscore = get_post_meta($this->id, '_joomsport_match_jmscore',true);
        $m_played = get_post_meta($this->id,'_joomsport_match_played',true);
        if ($m_played != '1') {
            return '';
        }
        if(isset($jmscore['bonus1'])){
            if (($jmscore['bonus1'] != '' || $jmscore['bonus2'] != '')) {
                $html = '<div style="text-align:center;" title="'.__('Bonus','joomsport-sports-league-results-management').'">';
                $html .= '<span style="font-size:75%;">'.floatval($jmscore['bonus1']).':</span>';
                $html .= '<span style="font-size:75%;">'.floatval($jmscore['bonus2']).'</span>';
                $html .= '</div>';

                return $html;
            }
        }
        return '';
    }

    
    public function setHeaderOptions()
    {
        //social
        if (JoomsportSettings::get('jsbp_match') == '1') {
            $this->lists['options']['social'] = true;
            //classJsportAddtag::addCustom('og:title', $this->lists['options']['titleSocial']);
            if (isset($this->lists['photos'][0])) {
                $img = $this->lists['photos'][0];
                if (is_file(JOOMSPORT_PATH_IMAGES.$img->filename)) {
                    //classJsportAddtag::addCustom('og:image', JS_LIVE_URL_IMAGES.$img->filename);
                }
            }
            //classJsportAddtag::addCustom('og:description', $this->object->match_descr);
        }
    }
    public function getMdayName(){

        if($this->mdId){
            $tx = jsHelperTermMatchday::getInstance();
            if(isset($tx[$this->mdId])){
                return $tx[$this->mdId]->name;
            }
        }

        $term_list = get_the_terms($this->id, 'joomsport_matchday');
        if(count($term_list)){
            return $term_list[0]->name;
        }
    }
    public function getMdayID(){
        if($this->mdId){
            $tx = jsHelperTermMatchday::getInstance();
            if(isset($tx[$this->mdId])){
                return $tx[$this->mdId]->id;
            }
        }

        $term_list = get_the_terms($this->id, 'joomsport_matchday');
        if(count($term_list)){
            return $term_list[0]->term_id;
        }
    }
    
    
    public function getBoxScoreList(){
        $this->lists['boxscore_home'] = $this->model->getBoxScore();
        $this->lists['boxscore_away'] = $this->model->getBoxScore(false);
    }


    public function h2h(){
        require_once JOOMSPORT_PATH_CLASSES . 'h2h' . DIRECTORY_SEPARATOR . "class_jsport_h2h_all.php";
        require_once JOOMSPORT_PATH_CLASSES . 'h2h' . DIRECTORY_SEPARATOR . "class_jsport_h2h_local.php";
        $partHome = $this->getParticipantHome();
        $partAway = $this->getParticipantAway();
        $h2hAll = new classJsportH2HAll($partHome->object->ID, $partAway->object->ID);
        $this->lists["btwMatchesAll"] = $h2hAll->matchesBtw();

        $h2hLocal = new classJsportH2HLocal($partHome->object->ID, $partAway->object->ID);
        $this->lists["btwMatchesLocal"] = $h2hLocal->matchesBtw();


    }

    public function getLastMatchesH($place = 0){
        $partHome = $this->getParticipantHome();

        return jsHelperBtw::getLastMatches($partHome->object->ID,$this->season_id, $place);

    }
    public function getLastMatchesA($place = 0){
        $partAway = $this->getParticipantAway();

        return jsHelperBtw::getLastMatches($partAway->object->ID,$this->season_id, $place);

    }

}
