<table class="mglTable" id="mglMatchDay">
    <thead>
    <tr>
        <th style="width:30px;">
            #
        </th>

        <th>
            <?php echo __('Home', 'joomsport-sports-league-results-management');?>
        </th>
        <th>
            <?php echo __('Score', 'joomsport-sports-league-results-management');?>
        </th>
        <th>
            <?php echo __('Away', 'joomsport-sports-league-results-management');?>
        </th>
        <th>
            <?php echo __('Date', 'joomsport-sports-league-results-management');?>
        </th>
        <th>
            <?php echo __('Time', 'joomsport-sports-league-results-management');?>
        </th>

        <th>

        </th>
    </tr>
    </thead>
    <tbody>
    <?php

    for($intA = 0; $intA < count($matches); $intA ++){
        //var_dump($matches->posts[$intA]);
        //continue;
        $match = $matches[$intA];
        $home_team = get_post_meta( $match->ID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $match->ID, '_joomsport_away_team', true );
        $home_score = get_post_meta( $match->ID, '_joomsport_home_score', true );
        $away_score = get_post_meta( $match->ID, '_joomsport_away_score', true );
        $m_played = get_post_meta( $match->ID, '_joomsport_match_played', true );
        $m_date = get_post_meta( $match->ID, '_joomsport_match_date', true );
        $m_time = get_post_meta( $match->ID, '_joomsport_match_time', true );


        ?>
        <tr>
            <td>
                <?php
                //if(current_user_can('delete_jscp_match', $match->ID)){
                    ?>
                    <i class="fa fa-trash jsmoderDelMatch" data-id="<?php echo $match->ID;?>" aria-hidden="true"></i></a>
                    <?php
                //}
                ?>
                <input type="hidden" name="match_id[]" value="<?php echo $match->ID;?>">
            </td>

            <td><?php echo get_the_title($home_team);?><input type="hidden" name="home_team[]" value="<?php echo $home_team;?>"></td>
            <td nowrap="nowrap">
                <?php
                if($m_played){
                    echo $home_score.":".$away_score;
                }
                ?>

            </td>

            <td><?php echo get_the_title($away_team);?><input type="hidden" name="away_team[]" value="<?php echo $away_team;?>"></td>


            <?php
                echo '<td>';
                echo $m_date;

                echo '</td>';
                echo '<td>'.$m_time.'</td>';


            ?>

            <td>
                <?php //if(JoomSportUserRights::isAdmin() || JoomsportSettings::get('moder_edit_matches_reg', 0)){?>
                    <input type="button" data-id="<?php echo $match->ID?>" class="button jsModerEditMatch" value="<?php echo __('Details', 'joomsport-sports-league-results-management');?>">
                <?php //} ?>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    <?php
    //if(current_user_can('edit_jscp_matchs') && $canAddMatches){
        ?>
        <tfoot>
        <tr>
            <td>

            </td>

            <td>
                <select name="set_home_team"  id="set_home_team">
                    <option value="0"><?php echo __('Select participant', 'joomsport-sports-league-results-management');?></option>
                    <?php
                    if(count($participiants)){
                        foreach ($participiants as $part) {
                            echo '<option value="'.$part->ID.'">'.$part->post_title.'</option>';
                        }
                    }
                    ?>
                </select>
            </td>
            <td nowrap="nowrap">

            </td>

            <td>
                <select name="set_away_team" id="set_away_team">
                    <option value="0"><?php echo __('Select participant', 'joomsport-sports-league-results-management');?></option>
                    <?php
                    if(count($participiants)){
                        foreach ($participiants as $part) {
                            echo '<option value="'.$part->ID.'">'.$part->post_title.'</option>';
                        }
                    }
                    ?>
                </select>
            </td>

            <?php


            echo '<td>';
            echo '<input type="text" placeholder="YY-mm-dd" size="12" class="jsdatefield" name="m_date_foot" id="m_date_foot" value="" />';
            echo '</td>';

            echo '<td><input type="time" placeholder="H:i" name="m_time_foot" size="12" id="m_time_foot" value="" /></td>';

            ?>

            <td>
                <input type="button" class="button mgl-moder-add-button" value="<?php echo __("Add New", 'joomsport-sports-league-results-management');?>" />
            </td>
        </tr>
        </tfoot>
        <?php
    //}
    ?>
</table>
