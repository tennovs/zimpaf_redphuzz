<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$jmscore = get_post_meta($rows->id, '_joomsport_match_jmscore',true);
$m_venue = get_post_meta($rows->id,'_joomsport_match_venue',true);
?>
<div id="jsMatchViewID">
    <div class="jsMatchResultSection">
        <div class="jsMatchHeader clearfix">
            <div class="col-xs-4 col-sm-5">
                <div class="matchdtime row">
                    <?php
                    $m_date = get_post_meta($rows->id,'_joomsport_match_date',true);
                    $m_time = get_post_meta($rows->id,'_joomsport_match_time',true);
                    if ($m_date && $m_date != '0000-00-00') {
                        echo '<img src="'.JOOMSPORT_LIVE_ASSETS.'images/calendar-date.png" alt="" />';
                        if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $m_date)) {
                            echo '<span>'. classJsportDate::getDate($m_date, $m_time) .'</span>';
                        } else {
                            echo '<span>'. $m_date .'</span>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-xs-4 col-sm-2 jscenter">
                <div>
                    <?php
                    if(JoomsportSettings::get('enbl_mdnameonmatch',1)) {
                        echo '<div class="jsmatchday"><span>' . $rows->getMdayName() . '</span></div>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-xs-4 col-sm-5">
                <div class="matchvenue row">
                    <?php
                    if ($m_venue) {
                        if($rows->getLocation()){
                            echo '<div><span>'.$rows->getLocation().'</span></div>';
                            echo '<img src="'.JOOMSPORT_LIVE_ASSETS.'images/location.png" />';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="jsMatchResults">
            <?php 
            $width = JoomsportSettings::get('set_emblemhgonmatch', 60);
            $match = $rows;
            $partic_home = $match->getParticipantHome();
            $partic_away = $match->getParticipantAway();
            ?>
            
            <div class="row">
                <div class="jsMatchTeam jsMatchHomeTeam col-xs-6 col-sm-5 col-md-4">
                    <div class="row">
                        <div class="jsMatchEmbl jscenter col-md-5">
                            <?php echo $partic_home ? ($partic_home->getEmblem(true, 0, 'emblInline', $width)) : ''; ?>
                        </div>
                        <div class="jsMatchPartName col-md-7">
                            <div class="row">
                                <span>
                                    <?php echo ($partic_home) ? ($partic_home->getName(true)) : ''; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="jsMatchTeam jsMatchAwayTeam col-xs-6 col-sm-5 col-sm-offset-2 col-md-4 col-md-push-4">
                    <div class="row">
                        <div class="jsMatchEmbl jscenter col-md-5 col-md-push-7">
                            <?php echo $partic_away ? ($partic_away->getEmblem(true, 0, 'emblInline', $width)) : ''; ?>
                        </div>
                        <div class="jsMatchPartName col-md-7 col-md-pull-5">
                            <div class="row">
                                <span>
                                    <?php echo ($partic_away) ? ($partic_away->getName(true)) : ''; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="jsMatchScore col-xs-12 col-md-4 col-md-pull-4">
                    <?php if (isset($jmscore['is_extra']) && $jmscore['is_extra']) {
                        ?>
                        <div class="jsMatchExtraTime">
                            <?php
                            if(isset($jmscore['aet1'])){
                                echo '<span class="aetSmDivScoreH">'.$jmscore['aet1'].'</span>';
                            }
                            ?>
                            <img  src="<?php echo JOOMSPORT_LIVE_ASSETS?>images/extra-t.png" alt="<?php echo __('Won in extra time','joomsport-sports-league-results-management');?>" title="<?php echo __('Won in extra time','joomsport-sports-league-results-management');?>" />
                            <?php
                            if(isset($jmscore['aet2'])){
                                echo '<span class="aetSmDivScoreA">'.$jmscore['aet2'].'</span>';
                            }
                            ?>
                        </div>
                        <?php
                    } ?>
                    <?php echo jsHelper::getScoreBigM($match); ?>
                </div>
            </div>

            <!-- MAPS -->
            <?php
            if ($rows->lists['maps'] && count($rows->lists['maps'])) {
                echo jsHelper::getMap($rows->lists['maps']);
            }
            ?>
        </div>
    </div>
    <?php apply_filters("joomsport_custom_votes", $match->id);?>
    <div class="jsMatchContentSection clearfix">
        <?php
        $tabs = $rows->getTabs();
        jsHelperTabs::draw($tabs, $rows);
        ?>
    </div>
</div>