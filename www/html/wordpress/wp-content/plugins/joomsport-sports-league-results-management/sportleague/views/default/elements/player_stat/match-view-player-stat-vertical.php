<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<table class="jsTblVerticalTimeLine table">
    <tbody>

    <?php
    for($intE=0;$intE<count($eventsB);$intE++){
        $isOpposite = in_array($eventsB[$intE]->e_id,$opposite_events);

        if($rows->lists['single']){
            $eventsB[$intE]->t_id = $eventsB[$intE]->player_id;
        }
        ?>
        <tr>
            <?php
            if((!$isOpposite && $partic_home->object->ID == $eventsB[$intE]->t_id) ||
                ($isOpposite && $partic_away->object->ID == $eventsB[$intE]->t_id)){

                echo '<td class="jsMatchPlayer">';
                echo $eventsB[$intE]->obj->getName(true);

                if($eventsB[$intE]->plFM){
                    $assist_players = '';
                    $assistArr = explode(",", $eventsB[$intE]->plFM);

                    for($intM=0;$intM<count($assistArr);$intM++){
                        if($intM){$assist_players .= ", ";}
                        $assist_players .= get_the_title($assistArr[$intM]);
                    }

                    echo '<div class="subEvDiv">('.$eventsB[$intE]->subEn.': '.$assist_players.')</div>';
                }
                echo '</td>';
            } else {
                echo '<td class="jsMatchPlayer jsHidden">';
                echo '&nbsp;';
                echo '</td>';
            }

            ?>


            <?php
            if((!$isOpposite && $partic_home->object->ID == $eventsB[$intE]->t_id) ||
                ($isOpposite && $partic_away->object->ID == $eventsB[$intE]->t_id)){
                echo '<td class="jsMatchEvent">';

                echo $eventsB[$intE]->objEvent->getEmblem(false);
                echo '</td>';
            } else {
                echo '<td class="jsMatchEvent jsHidden">';
                echo '&nbsp;';
                echo '</td>';
            }

            ?>

            <td class="jstimeevent">
                <?php
                if($eventsB[$intE]->minutes_input){
                    echo $eventsB[$intE]->minutes_input;

                    if(strpos($eventsB[$intE]->minutes_input,':') === false){
                        echo "'";
                    }
                } else {
                    echo $eventsB[$intE]->minutes ? $eventsB[$intE]->minutes."'" : '';
                }
                ?>
            </td>
            <?php
            if((!$isOpposite && $partic_away->object->ID == $eventsB[$intE]->t_id) ||
                ($isOpposite && $partic_home->object->ID == $eventsB[$intE]->t_id)){
                echo '<td class="jsMatchEvent">';
                echo $eventsB[$intE]->objEvent->getEmblem(false);
                echo '</td>';
            } else {
                echo '<td class="jsMatchEvent jsHidden">';
                echo '&nbsp;';
                echo '</td>';
            }
            ?>

            <?php
            if((!$isOpposite && $partic_away->object->ID == $eventsB[$intE]->t_id) ||
                ($isOpposite && $partic_home->object->ID == $eventsB[$intE]->t_id)){
                echo '<td class="jsMatchPlayer">';
                echo $eventsB[$intE]->obj->getName(true);

                if($eventsB[$intE]->plFM){
                    $assist_players = '';
                    $assistArr = explode(",", $eventsB[$intE]->plFM);

                    for($intM=0;$intM<count($assistArr);$intM++){
                        if($intM){$assist_players .= ", ";}
                        $assist_players .= get_the_title($assistArr[$intM]);
                    }

                    echo '<div class="subEvDiv">('.$eventsB[$intE]->subEn.': '.$assist_players.')</div>';
                }
                echo '</td>';
            } else {
                echo '<td class="jsMatchPlayer jsHidden">';
                echo '&nbsp;';
                echo '</td>';
            }
            ?>
        </tr>
        <?php
    }


    ?>
    </tbody>
</table>