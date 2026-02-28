<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
?>
<div id="joomsport-container">
    <div class="table-responsive">
        <table class="table">
            <?php
            if(count($players['list'])){
                ?>
                <thead>
                    <tr>
                        <th class="jsalignleft"><?php echo __('Player','joomsport-sports-league-results-management');?></th>

                        <?php if($args['teamname']){?>
                            <th class="jsaligncenter"><?php echo __('Team','joomsport-sports-league-results-management');?></th>
                        <?php } ?>
                        <th class="jsaligncenter"><?php echo $eventObj->getEventName();?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($players['list'] as $player){
                        echo '<tr>';
                        $playerObj = new classJsportPlayer($player->player_id,$args['seasonid'],false);
                        if($args['photo']){
                            echo '<td><div class="jsDivLineEmbl">' . $playerObj->getEmblem(true, 0, '');
                            echo jsHelper::nameHTML($playerObj->getName(true,0,$display_player_name)) . '</div></td>';
                        } else {
                            echo '<td>' .jsHelper::nameHTML($playerObj->getName(true,0,$display_player_name)) . '</td>';
                        }
                        
                        if($args['teamname']){
                            $teamObj = new classJsportTeam($player->team_id,$args['seasonid'],false);
                            echo '<td  class="jsDivLineTeam jsaligncenter">' . jsHelper::nameHTML($teamObj->getName(true,0,$display_name)) . '</td>';
                        }
                        echo '<td class="jsaligncenter">' .$player->{$eventid} .'</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>