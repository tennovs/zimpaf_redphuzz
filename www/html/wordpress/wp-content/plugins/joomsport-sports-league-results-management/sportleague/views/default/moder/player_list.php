<?php

$players = JoomsportModerateHelper::getModerPlayers();

if(JoomsportModerateHelper::Can('player.add', 0)){ // can add Team
    ?>
    <div>
        <button id="jsModerNewPlayer"><?php echo __('New Player','joomsport-sports-league-results-management');?></button>
    </div>
    <?php
}
?>
<table>

    <?php
    for($intA=0;$intA<count($players);$intA++){
        $teamObj = new JoomsportModerateTeam($players[$intA]->ID);
        echo '<tr>';
        echo '<td>';
        if(JoomsportModerateHelper::Can('player.del', $players[$intA]->ID)){
            echo '<i class="fa fa-trash jsmoderDelPlayer" data-id="'.$players[$intA]->ID.'" aria-hidden="true"></i>';

        }
        echo '</td>';
        echo '<td>'.get_the_title($players[$intA]->ID).'</td>';
        echo '<td>';
        if(JoomsportModerateHelper::Can('player.edit', $players[$intA]->ID)){
            echo '<i class="fa fa-edit jsModerEditPlayer" data-id="'.$players[$intA]->ID.'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '</tr>';

    }
    ?>
</table>