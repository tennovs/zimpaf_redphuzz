<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

?>
<div class="jsmoderContainer">
    <div id="jsmoderMessages"></div>
    <div class="jsmoderFilter"></div>
    <div class="jsmoderTabs">
        <span id="jsmoderTabsTeams">Teams</span>
        <span id="jsmoderTabsPlayers">Players</span>
        <span id="jsmoderTabsMatches">Matches</span>
    </div>
    <div class="jsmoderInner">
        <?php require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'team_list.php';?>
    </div>
    <div id="jsmoderInnerAjax"></div>

</div>
