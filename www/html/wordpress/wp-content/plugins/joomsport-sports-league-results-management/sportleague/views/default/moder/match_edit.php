<?php
?>
<div>
    <form action="" name="formMatchEditFE" id="formMatchEditFE">


        <?php
        JoomSportMetaMatch::js_meta_score($teamPost);
        JoomSportMetaMatch::js_meta_general($teamPost);
        //JoomSportMetaTeam::js_meta_about($thisPost);
        JoomSportMetaMatch::js_meta_playerevents($teamPost);
        JoomSportMetaMatch::js_meta_mevents($teamPost);
        JoomSportMetaMatch::js_meta_ef($teamPost);

        JoomSportMetaMatch::js_meta_lineup($teamPost);
        JoomSportMetaMatch::js_meta_subs($teamPost);

        ?>

        <div>
            <input type="submit" value="<?php echo __('Save','joomsport-sports-league-results-management');?>" />
            <input type="hidden" name="matchID" value="<?php echo $matchID;?>" />
        </div>
    </form>
</div>
