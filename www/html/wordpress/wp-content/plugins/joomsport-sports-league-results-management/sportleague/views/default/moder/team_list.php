<?php

$teams = JoomsportModerateHelper::getModerTeams();

if(JoomsportModerateHelper::Can('team.add', 0)){ // can add Team
    ?>
    <div>
        <button id="jsModerNewTeam"><?php echo __('New Team','joomsport-sports-league-results-management');?></button>
    </div>
    <?php
}
?>
<table>

    <?php
    for($intA=0;$intA<count($teams);$intA++){
        $teamObj = new JoomsportModerateTeam($teams[$intA]->ID);
        echo '<tr>';
        echo '<td>';
        if(JoomsportModerateHelper::Can('team.del', $teams[$intA]->ID)){
            echo '<i class="fa fa-trash jsmoderDelTeam" data-id="'.$teams[$intA]->ID.'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '<td>'.get_the_title($teams[$intA]->ID).'</td>';
        echo '<td>';
        if(JoomsportModerateHelper::Can('team.edit', $teams[$intA]->ID)){
            echo '<i class="fa fa-edit jsModerEditTeam" data-id="'.$teams[$intA]->ID.'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '</tr>';

    }
    ?>
</table>