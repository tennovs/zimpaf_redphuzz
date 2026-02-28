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
<?php
$bystageExist = false;
if(count($rows->lists['eventsByStage'])) {
    foreach ($rows->lists['eventsByStage'] as $eventBy) {
        if (count($eventBy["events"])) {
            $bystageExist = true;
        }
    }
}
if (count($rows->lists['eventsNotByStage']) || $bystageExist) {
    ?>
    <div class="jsMatchStatHeader jscenter">
        <h3>
            <?php echo __('Player statistic','joomsport-sports-league-results-management'); ?>
        </h3>
    </div>
    <?php
    if(count($rows->lists['eventsNotByStage'])){
        $rows->getPlayerObj($rows->lists['eventsNotByStage']);
        $eventsB = $rows->lists['eventsNotByStage'];

        $vertical = true;
        foreach ($eventsB as $ev){
            if($eventsB[0]->minutes == 0 || !$eventsB[0]->minutes){
                $vertical = false;
                break;
            }
        }
        if($vertical){
            require 'match-view-player-stat-vertical.php';
        }else{
            require 'match-view-player-stat-horizontal.php';
        }
    }
    if(count($rows->lists['eventsByStage'])){
        foreach($rows->lists['eventsByStage'] as $eventBy){
            if(count($eventBy["events"])){
                $rows->getPlayerObj($eventBy["events"]);

                $eventsB = $eventBy["events"];
                echo '<div class="jsMatchStageTitle">'.$eventBy["stage"]->m_name.'</div>';
                $vertical = true;
                foreach ($eventsB as $ev){
                    if($eventsB[0]->minutes == 0 || !$eventsB[0]->minutes){
                        $vertical = false;
                        break;
                    }
                }
                if($vertical){
                    require 'match-view-player-stat-vertical.php';
                }else{
                    require 'match-view-player-stat-horizontal.php';
                }
            }
        }
    }
}
?>