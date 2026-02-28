<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$opposite_events = JoomsportSettings::get('opposite_events',array());
if($opposite_events){
    $opposite_events = json_decode($opposite_events,true);
}

$width = JoomsportSettings::get('teamlogo_height');
$match = $rows;
if (JoomsportSettings::get('partdisplay_awayfirst', 0) == 1) {
    $partic_home = $match->getParticipantHome();
    $partic_away = $match->getParticipantAway();
    $tmp = $rows->lists['m_events_away'];
    $rows->lists['m_events_away'] = $rows->lists['m_events_home'];
    $rows->lists['m_events_home'] = $tmp;
    $tmp = $rows->lists['squard1'];
    $rows->lists['squard1'] = $rows->lists['squard2'];
    $rows->lists['squard2'] = $tmp;
    $tmp = $rows->lists['squard1_res'];
    $rows->lists['squard1_res'] = $rows->lists['squard2_res'];
    $rows->lists['squard2_res'] = $tmp;
} else {
    $partic_home = $match->getParticipantHome();
    $partic_away = $match->getParticipantAway();
}

require 'match_stat' . DIRECTORY_SEPARATOR . 'h2h_blocks.php';
?>
<div class="table-responsive">
    <div class="jsPlayerStatMatchDiv">
        <?php require 'player_stat' . DIRECTORY_SEPARATOR . 'match-view-player-stat.php'; ?>
    </div>

    <?php
    if (count($rows->lists['team_events'])) {
        ?>
        <div class="jsMatchStatMatchDiv">
            <div class="jsMatchStatHeader jscenter">
                <h3>
                    <?php echo __('Match statistic','joomsport-sports-league-results-management'); ?>
                </h3>
            </div>
            <div class="jsTeamStat">
                <div class="jsOverflowHidden">
                    <div class="jstable">
                        <?php
                        for ($intP = 0; $intP < count($rows->lists['team_events']); ++$intP) {
                            $graph_sum = intval($rows->lists['team_events'][$intP]->home_value) + intval($rows->lists['team_events'][$intP]->away_value);

                            if ($graph_sum) {
                                $graph_home = round(100 * intval($rows->lists['team_events'][$intP]->home_value) / $graph_sum);
                                $graph_away = round(100 * intval($rows->lists['team_events'][$intP]->away_value) / $graph_sum);
                                if ($graph_home > $graph_away) {
                            //$graph_home_class = ' jsRed';
                                } else {
                            //$graph_away_class = ' jsRed';
                                }
                            }
                            ?>
                            <div class="jstable-row jsColTeamEvents">
                                <div class="jstable-cell jsCol5">
                                    <div class="teamEventGraph clearfix">
                                        <div class="teamEventGraphHome" style="width:<?php echo $graph_home?>%">
                                            <span><?php echo $rows->lists['team_events'][$intP]->home_value; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="jstable-cell jsCol6">
                                    <div>
                                        <?php 
                                        echo $rows->lists['team_events'][$intP]->objEvent->getEmblem();
                                        echo '<span>'. $rows->lists['team_events'][$intP]->objEvent->getEventName() .'</span>';
                                        ?>
                                    </div>
                                </div>
                                <div class="jstable-cell jsCol5">
                                    <div class="teamEventGraph clearfix">
                                        <div class="teamEventGraphAway" style="width:<?php echo $graph_away?>%">
                                            <span><?php echo $rows->lists['team_events'][$intP]->away_value; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div> 
        <?php
    }
    ?>
    <?php
    if (jsHelper::getADF($rows->lists['ef'])) {
        ?>
        <div class="jsAdditionStatMatchDiv">
            <div class="jsMatchStatHeader jscenter">
                <h3>
                    <?php echo __('Additional information','joomsport-sports-league-results-management'); ?>
                </h3>
            </div>
            <div class="jsMatchExtraFields">
                <?php
                $ef = $rows->lists['ef'];
                if (count($ef)) {
                    foreach ($ef as $key => $value) {
                        if ($value != null) {
                            echo '<div class="jsExtraField">';
                            echo  '<div class="jsLabelEField">'.$key.'</div>';
                            echo  '<div class="jsValueEField">'.$value.'</div>';
                            echo  '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    do_action( 'js_match_prediction', $rows->object->ID);
    ?>
</div>