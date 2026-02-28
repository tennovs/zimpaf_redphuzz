<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

?>
<td class="jsMatchDate">
    <?php echo $match_date;?>
</td>
<td class="jsMatchTeamLogo">
    <?php
    if(is_object($LMpartic)){
        echo ($LMpartic->getEmblem());
    }
    ?>
</td>
<td class="jsMatchTeamName">
    <?php
    if(is_object($LMpartic)){
        echo  jsHelper::nameHTML($LMpartic->getName(true));
    }
    ?>
</td>
<td class="jsMatchPlayedStatus">
    <?php echo jsHelper::JsFormViewElement($lMatch, $partic_home->object->ID);?>
</td>
<td class="jsMatchPlayedScore">
    <?php echo jsHelper::getScore($lMatch, '');?>
</td>
<td class="jsMatchPlace">
    <?php echo $lMatch->opposite?'<i class="fa fa-home" title="Home" aria-hidden="true"></i>':'<i class="fa fa-plane" title="Away" aria-hidden="true"></i>';?>
</td>