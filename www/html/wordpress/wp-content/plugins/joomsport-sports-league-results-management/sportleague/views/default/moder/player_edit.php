<?php
?>
<div>
    <form action="" name="formPlayerEditFE" id="formPlayerEditFE">

        <?php
        JoomSportMetaPlayer::js_meta_personal($playerPost);
        //JoomSportMetaTeam::js_meta_about($thisPost);
        JoomSportMetaPlayer::js_meta_ef($playerPost);

        ?>

        <div>
            <input type="submit" value="<?php echo __('Save','joomsport-sports-league-results-management');?>" />
            <input type="hidden" name="playerID" value="<?php echo $playerID?>" />
        </div>
    </form>
</div>
