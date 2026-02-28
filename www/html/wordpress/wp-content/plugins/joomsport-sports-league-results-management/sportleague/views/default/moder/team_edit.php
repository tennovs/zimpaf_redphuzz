<?php
?>
<div>
    <form action="" name="formTeamEditFE" id="formTeamEditFE">
        <div>
            <label><?php echo __('Title','joomsport-sports-league-results-management');?></label>
            <input type="text" value="<?php echo get_the_title($teamID);?>" name="teamName" />
        </div>


        <?php
        JoomSportMetaTeam::js_meta_personal($teamPost);
        //JoomSportMetaTeam::js_meta_about($thisPost);
        JoomSportMetaTeam::js_meta_ef($teamPost);

        ?>
        <div>
            <div style="margin-bottom: 25px;margin-left:10px;">
                <?php
                $results = JoomSportHelperObjects::getParticipiantSeasons($teamID);
                echo __('Select Season', 'joomsport-sports-league-results-management').'&nbsp;&nbsp;';
                if(!empty($results)){
                    echo JoomSportHelperSelectBox::Optgroup('stb_season_id', $results, '');
                    JoomSportMetaTeam::js_meta_players($teamPost);
                }else{
                    echo '<div style="color:red;">'.__('Participant is not assigned to any season.', 'joomsport-sports-league-results-management').'</div>';
                }

                ?>
            </div>
        </div>
        <div>
            <input type="submit" value="<?php echo __('Save','joomsport-sports-league-results-management');?>" />
            <input type="hidden" name="teamID" value="<?php echo $teamID;?>" />
        </div>
    </form>
</div>
