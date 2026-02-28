<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

class JoomsportShortcodes {

  public static function init() {

    add_shortcode( 'jsStandings', array('JoomsportShortcodes','joomsport_standings') );
    add_shortcode( 'jsMatches', array('JoomsportShortcodes','joomsport_matches') );
    add_shortcode( 'jsPlayerStat', array('JoomsportShortcodes','joomsport_plstat') );
    add_shortcode( 'jsMatchDayStat', array('JoomsportShortcodes','joomsport_mday') );
    add_shortcode( 'jsMatchPlayerList', array('JoomsportShortcodes','joomsport_playerlist') );

    add_filter("mce_external_plugins", array('JoomsportShortcodes',"enqueue_plugin_scripts"));
    add_filter("mce_buttons", array('JoomsportShortcodes',"register_buttons_editor"));

      
  }


  public static function joomsport_standings($attr){

    $args = shortcode_atts( array(
      'id' => 0,
      'group_id' => 0,
      'partic_id' => 0,
      'place' => 0,
      'columns' => '',
      'display_name' => 0,
      'display_legend' => 0,
      ), $attr );
    $legends = null;
    wp_enqueue_style('jscssbtstrp',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/btstrp.css');
    wp_enqueue_style('jscssjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport.css');
    if (is_rtl()) {
      wp_enqueue_style( 'jscssjoomsport-rtl',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport-rtl.css', array('jscssjoomsport'));
    }
    wp_enqueue_style( 'joomsport-moduletable-css', plugins_url('../sportleague/assets/css/mod_js_table.css', __FILE__) );
    wp_enqueue_script('jsjoomsport-standings',plugins_url('../sportleague/assets/js/joomsport_standings.js', __FILE__));

    ob_start();

    require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
    require_once JOOMSPORT_PATH_OBJECTS . 'class-jsport-season.php';
    $seasObj = new classJsportSeason($args['id']);

    if($seasObj->isComplex() == '1'){
      $childrens = $seasObj->getSeasonChildrens();

      if(count($childrens)){
        foreach ($childrens as $ch) {
          $classChild = new classJsportSeason($ch->ID);
          $child = $classChild->getChild();
          $child->calculateTable(true, $args['group_id']);
          $classChild->getLists();
          $row = $classChild;
          if($args["display_legend"]) {
              $legends = $row->lists['legend'];
          }
          $place_display 	= $args['place'];
          $columns_list = array();
          if($args['columns']){
           $columns_list = explode(';', $args['columns']); 
         }
         $yteam_id = $args['partic_id'];
         $s_id = $args['id'];
         $gr_id = $args['group_id'];
         $single = $row->getSingle();
         $row = $row->season;
         
         $display_name = $args["display_name"];

         require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'standings.php';
       }
     }    

   }else{
    $child = $seasObj->getChild();
    $child->calculateTable(true, $args['group_id']);
    $seasObj->getLists();
    $row = $seasObj;
    if($args["display_legend"]) {
        $legends = $row->lists['legend'];
    }
    $place_display 	= $args['place'];
    $columns_list = array();
    if($args['columns']){
     $columns_list = explode(';', $args['columns']); 
   }
   $yteam_id = $args['partic_id'];
   $s_id = $args['id'];
   $gr_id = $args['group_id'];
   $single = $row->getSingle();
   $row = $row->season;
   $display_name = $args["display_name"];

   require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'standings.php';
 }    
 return ob_get_clean();
}


public static function joomsport_matches($attr){

  $args = shortcode_atts( array(
    'id' => 0,
    'group_id' => 0,
    'partic_id' => 0,
    'quantity' => 0,
    'matchtype' => 0,
    'emblems' => 0,
    'venue' => 0,
    'season' => 0,
    'slider' => 0,
    'layout' => 0,
    'groupbymd' => 0,
    'morder' => 0,
    'drange_past' => 0,
    'drange_future' => 0,
    'drange_today' => 0,
    'display_name' => 0,   
    ), $attr );
    wp_enqueue_style('jscssbtstrp',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/btstrp.css');
    wp_enqueue_style('jscssjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport.css');
    if (is_rtl()) {
      wp_enqueue_style( 'jscssjoomsport-rtl',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport-rtl.css', array('jscssjoomsport'));
    }
  wp_enqueue_script('jsjoomsport-carousel',plugins_url('../sportleague/assets/js/jquery.jcarousellite.min.js', __FILE__));
  wp_enqueue_style( 'joomsport-modulescrollmatches-css', plugins_url('../sportleague/assets/css/js_scrollmatches.css', __FILE__) );
  ob_start();
  require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';

  require_once JOOMSPORT_PATH_CLASSES . 'class-jsport-matches.php';
  require_once JOOMSPORT_PATH_OBJECTS . 'class-jsport-match.php';
  require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-season.php';
  require_once JOOMSPORT_PATH_MODELS . 'model-jsport-season.php';

  $options = array();

  if($args['id']){
    $options["season_id"] = $args['id'];
    $obj = new classJsportSeason($args['id']);



    $season_array = array();

    if($obj->isComplex() == '1'){
      $childrens = $obj->getSeasonChildrens();
      if(count($childrens)){
        foreach($childrens as $ch){
          array_push($season_array, $ch->ID);
        }
        $options["season_id"] = $season_array;
      }
    } 

  }

  if($args['partic_id']){
    $options["team_id"] = $args['partic_id'];
  }
  if($args['group_id']){
    $options["group_id"] = $args['group_id'];
  }
  
  if($args['quantity']){
    $options["limit"] = $args['quantity'];
  }
  if($args['matchtype'] == '1'){
    $options["played"] = '0';
  }
  if($args['matchtype'] == '2'){
    $options["played"] = '1';
                //$options["ordering"] = 'm.m_date DESC, m.m_time DESC, m.id DESC';
  }
  if($args['morder'] == '1'){
    $options["ordering_dest"] = 'desc';
  }
    if($args['matchtype'] == '-1'){
        $options["played"] = '-1';
    }
    if(substr($args['matchtype'],0,3) == 'cs_'){
        $match_type = str_replace("cs_","", $args['matchtype']);
        $options["played"] = intval($match_type);
    }
  if($args['drange_past']){
    $options['date_from'] = date("Y-m-d", strtotime("-{$args['drange_past']} day"));
  }elseif($args['drange_today']){
    $options['date_from'] = date("Y-m-d");
  }
  if($args['drange_future']){
    $options['date_to'] = date("Y-m-d", strtotime("+{$args['drange_future']} day"));
  }elseif($args['drange_today']){
    $options['date_to'] = date("Y-m-d");
  }

  if(isset($options['date_to']) && !isset($options['date_from'])){
    $options['date_from'] = date("Y-m-d", strtotime("+1 day"));
  }
  if(!isset($options['date_to']) && isset($options['date_from'])){
    $options['date_to'] = date("Y-m-d", strtotime("-1 day"));
  }

  if(isset($options['date_to']) && isset($options['date_from']) && (!$args['drange_today'])){
    $options['date_exclude'] = date("Y-m-d");
  }

  $obj = new classJsportMatches($options);
  $rows = $obj->getMatchList();


  $matches = array();

  if($rows['list']){
    foreach ($rows['list'] as $row) {
      $match = new classJsportMatch($row->ID, false, $row->mdID, $row->seasonID);
      $matches[] = $match->getRowSimple();
    }
  }
  $list = $matches;
  if(count($list)){
                /*$document		= JFactory::getDocument();
                $document->addStyleSheet(JURI::root() . 'modules/mod_js_scrollmatches/css/js_scrollmatches.css'); 
                $document->addScript(JURI::root() . 'modules/mod_js_scrollmatches/js/jquery.jcarousellite.min.js');
                $baseurl = JUri::base();*/

                $module_id = rand(0, 2000);
                $enbl_slider = $args['slider'];
                $classname = $enbl_slider ? "jsSliderContainer":"jsDefaultContainer";
                $groupbydate = false;
                if($enbl_slider){
                  $curpos = 0;
                  $date = date("Y-m-d");
                  for($intA = 0;$intA < count($matches); $intA++){
                    $mdate  = get_post_meta($matches[$intA]->id,'_joomsport_match_date',true);
                    if(isset($options["ordering_dest"]) && $options["ordering_dest"] == 'desc'){
                      if($mdate > $date){

                        $curpos =  $intA;
                      }
                    }else
                    if($mdate < $date){

                      $curpos =  $intA+1;
                    }
                  }

                    //$curpos = $curpos > 1 ? $curpos : 0;
                }
                $display_name = $args["display_name"];

                echo '<div id="joomsport-container" class="'.$classname.'">';
                  require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'matches.php';
                echo '</div>';
              }


              return ob_get_clean();
            }
    public static function joomsport_plstat($attr){

      $args = shortcode_atts( array(
        'id' => null,
        'group_id' => null,
        'partic_id' => null,
        'event' => null,
        'quantity' => 0,
        'photo' => 0,
        'teamname' => 0,
        'display_name' => 0,
          'display_player_name' => 0,
        ), $attr );
      ob_start();
      wp_enqueue_style('jscssbtstrp',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/btstrp.css');
      wp_enqueue_style('jscssjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport.css');
      if (is_rtl()) {
        wp_enqueue_style( 'jscssjoomsport-rtl',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport-rtl.css', array('jscssjoomsport'));
      }
      wp_enqueue_style( 'joomsport-moduleevents-css', plugins_url('../sportleague/assets/css/mod_js_player.css', __FILE__) );
      wp_enqueue_script('jsjoomsport-carousel',plugins_url('../sportleague/assets/js/jquery.jcarousellite.min.js', __FILE__));

        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
      require_once JOOMSPORT_PATH_ENV_CLASSES.'class-jsport-getplayers.php';
      require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-player.php';
      require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-team.php';
      require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-event.php';
      $options = array();
      $eventid = 'eventid_'.$args['event'];
      $options['season_id'] = $args['seasonid'] = $args['id'];
      $options['team_id'] = $args['partic_id'];
      $options['limit'] = $args['quantity'];
      $options['group_id'] = $args['group_id'];
      $options['ordering'] = $eventid.' DESC';
      $eventObj = new classJsportEvent($args['event']);
      $players = classJsportgetplayers::getPlayersFromTeam($options);
      if(count($players['list'])){
        $display_name = $args["display_name"];
        $display_player_name = $args["display_player_name"];
        require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'players.php';
      }
      return ob_get_clean();
    }

    public static function joomsport_mday($attr){

      $args = shortcode_atts( array(
          'season_id' => 0,
          'matchday_id' => 0,
          'photo' => 0,
          'teamname' => 0,
          'layout' => 0,
          'venue' => 0

        ), $attr );
      ob_start();

      if($args['matchday_id'] == 0){
          //get current matchday
          if(intval($args["season_id"]) > 0){
              $nextMatchQuery = new WP_Query(array(
                      'post_type' => 'joomsport_match',
                      'post_status'      => 'publish',
                      'posts_per_page'   => 1,
                      'no_found_rows' => true,
                      'update_post_meta_cache' => false,
                      'update_post_term_cache' => false,
                      'fields' => 'ID',
                      'meta_key' => '_joomsport_match_date',
                      'orderby' => '_joomsport_match_date',
                      'order' => 'ASC',
                      'meta_query' => array(
                          array(
                              'key' => '_joomsport_seasonid',
                              'value' => intval($args["season_id"])),
                          array(
                              'key' => '_joomsport_match_date',
                              'value' => date("Y-m-d"),
                              'compare' => '>=',)

                      ))
              );

              if($nextMatchQuery->have_posts()){

                  $nextMatch = $nextMatchQuery->posts[0]->ID;

                  $md = get_the_terms($nextMatch,'joomsport_matchday');

                  $args['matchday_id'] = $md[0]->term_id;

              }else{
                  $nextMatchQuery = new WP_Query(array(
                          'post_type' => 'joomsport_match',
                          'post_status'      => 'publish',
                          'posts_per_page'   => 1,
                          'no_found_rows' => true,
                          'update_post_meta_cache' => false,
                          'update_post_term_cache' => false,
                          'fields' => 'ID',
                          'meta_key' => '_joomsport_match_date',
                          'orderby' => '_joomsport_match_date',
                          'order' => 'DESC',
                          'meta_query' => array(
                              array(
                                  'key' => '_joomsport_seasonid',
                                  'value' => intval($args["season_id"]))

                          ))
                  );
                  if($nextMatchQuery->have_posts()){

                      $nextMatch = $nextMatchQuery->posts[0]->ID;

                      $md = get_the_terms($nextMatch,'joomsport_matchday');

                      $args['matchday_id'] = $md[0]->term_id;

                  }
              }
          }
      }

      if(intval($args['matchday_id']) == 0){
          return;
      }

        wp_enqueue_script('jsjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/joomsport_md.js', array('jquery'));

        wp_enqueue_style('jscssbtstrp',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/btstrp.css');
        wp_enqueue_style('jscssjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport.css');
        if (is_rtl()) {
          wp_enqueue_style( 'jscssjoomsport-rtl',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport-rtl.css', array('jscssjoomsport'));
        }
      $term_meta = get_option( "taxonomy_".$args['matchday_id']."_metas");
      require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';

      require_once JOOMSPORT_PATH_CLASSES . 'class-jsport-matches.php';
      require_once JOOMSPORT_PATH_OBJECTS . 'class-jsport-match.php';
      require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-season.php';
      require_once JOOMSPORT_PATH_MODELS . 'model-jsport-season.php';
      if($term_meta['matchday_type'] == '0' || $attr["matchday_id"] == 0){
        $args = array('id' => $term_meta['season_id'],
          'group_id' => 0,
          'partic_id' => 0,
          'quantity' => 0,
          'matchtype' => 0,
          'emblems' => $args['photo'],
          'venue' => $args['venue'],
          'season' => 0,
          'slider' => 0,
          'layout' => $args['layout'],
          'groupbymd' => 0,
          'morder' => 0,
          'md_navigation' => 1,
          'matchday_id' => $args['matchday_id'],
           'teamname' => $args['teamname'] );
        $options["matchday_id"] = $args['matchday_id'];
        $options["season_id"] = $term_meta['season_id'];


        $obj = new classJsportMatches($options);
        $rows = $obj->getMatchList();


        $matches = array();

        if($rows['list']){
          foreach ($rows['list'] as $row) {
            $match = new classJsportMatch($row->ID, false);
            $matches[] = $match->getRowSimple();
          }
        }
        $list = $matches;
        if(count($list)){
        /*$document		= JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'modules/mod_js_scrollmatches/css/js_scrollmatches.css');
        $document->addScript(JURI::root() . 'modules/mod_js_scrollmatches/js/jquery.jcarousellite.min.js');
        $baseurl = JUri::base();*/

        $module_id = rand(0, 2000);
        // $enbl_slider = 0;
        // $classname = $enbl_slider ? "jsSliderContainer":"jsDefaultContainer";

        echo '<div id="joomsport-container" class="shrtMdContainer">';
          require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'matchday.php';
        echo '</div>';
      }
    }else{
          wp_register_script( 'popper-js', plugins_url('../assets/js/popper.min.js', __FILE__), ['jquery'], NULL, true );
          wp_enqueue_script( 'popper-js' );
          wp_enqueue_script('jsbootstrap-js',plugins_url('../assets/js/bootstrap.min.js', __FILE__),array ( 'jquery', 'jquery-ui-tooltip', 'popper-js' ));

          wp_enqueue_script('jsselect2',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/select2.min.js');

      wp_enqueue_script('jsjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/joomsport.js');

      require_once JOOMSPORT_SL_PATH. '/../includes/classes/matchday_types/joomsport-class-matchday-knockout.php';
      $knockObj = new JoomSportClassMatchdayKnockout($args['matchday_id']);
      echo '<div id="joomsport-container" class="jsmodtbl_responsive">';
      echo $knockout_view = $knockObj->getView();
      echo '</div>';
    }

    return ob_get_clean();
  }


  public static function joomsport_playerlist($attr){
    global $wpdb;
    $args = shortcode_atts( array(
      'season_id' => null,
      'team_id' => null,
      'pview' => 0,
      'pgroup' => 0
      ), $attr );
    if(!$args['season_id']){
        return false;
    }
    $ttype = JoomSportHelperObjects::getTournamentType($args['season_id']);

    if(!$args['team_id'] && !$ttype){
      return false;
    }
    ob_start();
    wp_enqueue_style('jscssbtstrp',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/btstrp.css');
    wp_enqueue_style('jscssjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport.css');
    if (is_rtl()) {
      wp_enqueue_style( 'jscssjoomsport-rtl',plugin_dir_url( __FILE__ ).'../sportleague/assets/css/joomsport-rtl.css', array('jscssjoomsport'));
    }
    wp_enqueue_script('jsjoomsport-tbl-sort',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/jquery.tablesorter.min.js');
      wp_enqueue_style('jscssfont',plugins_url('../assets/css/font-awesome.min.css', __FILE__));
      wp_register_script( 'popper-js', plugins_url('../assets/js/popper.min.js', __FILE__), ['jquery'], NULL, true );
      wp_enqueue_script( 'popper-js' );
      wp_enqueue_script('jsbootstrap-js',plugins_url('../assets/js/bootstrap.min.js', __FILE__),array ( 'jquery', 'jquery-ui-tooltip', 'popper-js' ));

      wp_enqueue_script('jsselect2',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/select2.min.js');

    wp_enqueue_script('jsjoomsport',plugin_dir_url( __FILE__ ).'../sportleague/assets/js/joomsport.js');

    require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';

    if($ttype){
        require_once JOOMSPORT_PATH_OBJECTS . 'class-jsport-player.php';
        $players = JoomSportHelperObjects::getParticipiants($args['season_id']);

        $eventsCol = classJsportgetplayers::getPlayersEvents($args['season_id']);

        $ef = classJsportExtrafields::getExtraFieldListTable(0, false);

        for ($intC = 0; $intC < count($players); ++$intC) {
            $row = $players[$intC];

            if(count($eventsCol)){
                foreach ($eventsCol as $keyCol=>$valCol){
                    $query = 'SELECT '.$keyCol
                        .' FROM '.DB_TBL_PLAYER_LIST.' as pl'
                        .' JOIN '.$wpdb->prefix.'posts as p ON p.ID = pl.player_id AND p.post_status = "publish"'
                        .' WHERE 1 = 1 AND pl.player_id IS NOT NULL'
                        .' AND pl.player_id = '.$row->ID
                        .' AND pl.season_id = '.$args['season_id'];
                    $row->{$keyCol} = $wpdb->get_var($query);
                }
            }

           if($row->ID){
                $uGroup = '0';
                $obj = new classJsportPlayer($row->ID, $args['season_id'],false);
                $obj->lists['tblevents'] = $row;

                $players_object = $obj->getRowSimple();

                if (JoomsportSettings::get('played_matches')) {
                    $players_object->played_matches = classJsportgetplayers::getPlayersPlayedMatches($row->player_id, $row->ID, $args['season_id']);
                }

                if(isset($efPlayerNumber) && isset($efPlayerNumber->id)){
                    $players_object->{'ef_'.$efPlayerNumber->id} = classJsportExtrafields::getExtraFieldValue($efPlayerNumber, $row->ID, 0, $args['season_id']);

                }
                if(isset($efplayerCard) && isset($efplayerCard->id)){
                    $players_object->{'ef_'.$efplayerCard->id} = classJsportExtrafields::getExtraFieldValue($efplayerCard, $row->ID, 0, $args['season_id']);

                }


               for ($intB = 0; $intB < count($ef); ++$intB) {

                   $players_object->{'ef_'.$ef[$intB]->id} = classJsportExtrafields::getExtraFieldValue($ef[$intB], $row->ID, 0, $args['season_id']);


               }

                $statplyers[] = $players_object;
                $players_object_gr[$uGroup][] = $players_object;

            }
        }

        $rows = new stdClass();
        $rows->lists['players'] = $players_object_gr;

        $rows->lists['players_Stat'] = $statplyers;
        $rows->lists['events_col'] = $eventsCol;
        $rows->lists['ef_table'] = $ef;

    }else{
        require_once JOOMSPORT_PATH_OBJECTS . 'class-jsport-team.php';
        $obj = new classJsportTeam($args['team_id'], $args['season_id'],false);
        $obj->getPlayers(array('groupBySelect'=>$args['pgroup'], 'playerPhotoTab'=>$args['pview']));
        $rows = $obj->getRow();
    }


    echo '<div id="joomsport-container" class="jsmodpll_responsive">';
    if($args['pview']){
      require JOOMSPORT_PATH_VIEWS . 'elements' . DIRECTORY_SEPARATOR . 'player-list-photo.php';
    }else{
      require JOOMSPORT_PATH_VIEWS . 'elements' . DIRECTORY_SEPARATOR . 'player-list.php';
    }
    echo '</div>';


    return ob_get_clean();
  }

  public static function enqueue_plugin_scripts($plugin_array)
  {
//enqueue TinyMCE plugin script with its ID.
    $plugin_array["joomsport_shortcodes_button"] =  plugin_dir_url(__FILE__) . "../assets/js/shortcodes.js";
    return $plugin_array;
  }
  public static function register_buttons_editor($buttons)
  {
//register buttons with their id.
    array_push($buttons, "joomsport_shortcodes_button");
    return $buttons;
  }


    

}


JoomsportShortcodes::init();