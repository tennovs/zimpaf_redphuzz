<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$show_count = false;
$show_time = false;
for ($intP = 0; $intP < count($eventsB); ++$intP) {
    if($eventsB[$intP]->ecount != 1){
        $show_count = true;
    }
    if($eventsB[$intP]->minutes_input){
        $show_time = true;
    }
}
?>

<div class="jsMatchStatTeams">
    <div class="clearfix">
        <div class="jsMatchStatHome col-sm-6">
            <table class="jsTblMatchTab firstTeam">
                <thead>
                    <tr>
                        <th></th>
                        <?php if($show_count){?>
                            <th class="js-event-quantity">
                                <?php echo __('Quantity','joomsport-sports-league-results-management'); ?>
                            </th>
                        <?php } ?>
                        <th class="js-event-type">
                            <?php echo __('Event','joomsport-sports-league-results-management'); ?>
                        </th>
                        <?php if($show_time){?>
                            <th class="js-event-time">
                                <?php echo __('Time','joomsport-sports-league-results-management'); ?>
                            </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($intP = 0; $intP < count($eventsB); ++$intP) {
                        $isOpposite = in_array($eventsB[$intP]->e_id, $opposite_events);
                        if($rows->lists['single']){
                            $eventsB[$intP]->t_id = $eventsB[$intP]->player_id;
                        }
                        if ((!$isOpposite && $partic_home->object->ID == $eventsB[$intP]->t_id) || ($isOpposite && $partic_away->object->ID == $eventsB[$intP]->t_id)) {
                            ?>
                            <tr class="jsMatchTRevents">
                                <td class="evPlayerName">
                                    <?php echo $eventsB[$intP]->obj->getName(true);

                                    if ($eventsB[$intP]->plFM) {
                                        $assist_players = '';
                                        $assistArr = explode(",", $eventsB[$intP]->plFM);

                                        for ($intM = 0; $intM < count($assistArr); $intM++) {
                                            if ($intM) {
                                                $assist_players .= ", ";
                                            }
                                            $assist_players .= get_the_title($assistArr[$intM]);
                                        }

                                        echo '<div class="subEvDiv">(' . $eventsB[$intP]->subEn . ': ' . $assist_players . ')</div>';
                                    }
                                    ?>
                                </td>
                                <?php if($show_count){?>
                                    <td>
                                        <?php echo $eventsB[$intP]->ecount; ?>
                                    </td>
                                <?php } ?>
                                <td>
                                    <?php echo $eventsB[$intP]->objEvent->getEmblem(false); ?>
                                </td>
                                <?php if($show_time){?>
                                    <td>
                                        <?php
                                        if ($eventsB[$intP]->minutes_input) {
                                            echo $eventsB[$intP]->minutes_input;

                                            if (strpos($eventsB[$intP]->minutes_input, ':') === false) {
                                                echo "'";
                                            }
                                        } else {
                                            echo $eventsB[$intP]->minutes ? $rows->lists['m_events_home'][$intP]->minutes . "'" : '';
                                        }
                                        ?>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="jsMatchStatAway col-sm-6">
            <table class="jsTblMatchTab">
                <thead>
                    <tr>
                        <?php if($show_time){?>
                            <th class="js-event-time">
                                <?php echo __('Time','joomsport-sports-league-results-management'); ?>
                            </th>
                        <?php } ?>
                        <th class="js-event-type">
                            <?php echo __('Event','joomsport-sports-league-results-management'); ?>
                        </th>
                        <?php if($show_count){?>
                            <th class="js-event-quantity">
                                <?php echo __('Quantity','joomsport-sports-league-results-management'); ?>
                            </th>
                        <?php }?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($intP = 0; $intP < count($eventsB); ++$intP) {
                        if($rows->lists['single']){
                            $eventsB[$intP]->t_id = $eventsB[$intP]->player_id;
                        }
                        if ((!$isOpposite && $partic_away->object->ID == $eventsB[$intP]->t_id) || ($isOpposite && $partic_home->object->ID == $eventsB[$intP]->t_id)) {
                            ?>
                            <tr class="jsMatchTRevents">
                                <?php if($show_time){?>
                                    <td>
                                        <?php
                                        if ($eventsB[$intP]->minutes_input) {
                                            echo $eventsB[$intP]->minutes_input;

                                            if (strpos($eventsB[$intP]->minutes_input, ':') === false) {
                                                echo "'";
                                            }
                                        } else {
                                            echo $eventsB[$intP]->minutes ? $rows->lists['m_events_away'][$intP]->minutes . "'" : '';
                                        }
                                        ?>
                                    </td>
                                    <?php 
                                } 
                                ?>
                                <td>
                                    <?php echo $eventsB[$intP]->objEvent->getEmblem(false); ?>
                                </td>
                                <?php if($show_count){?>
                                    <td><?php echo $eventsB[$intP]->ecount; ?></td>
                                <?php } ?>
                                <td class="evPlayerName">
                                    <?php echo $eventsB[$intP]->obj->getName(true);
                                    if ($eventsB[$intP]->plFM) {
                                        $assist_players = '';
                                        $assistArr = explode(",", $eventsB[$intP]->plFM);

                                        for ($intM = 0; $intM < count($assistArr); $intM++) {
                                            if ($intM) {
                                                $assist_players .= ", ";
                                            }
                                            $assist_players .= get_the_title($assistArr[$intM]);
                                        }

                                        echo '<div class="subEvDiv">(' . $eventsB[$intP]->subEn . ': ' . $assist_players . ')</div>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
