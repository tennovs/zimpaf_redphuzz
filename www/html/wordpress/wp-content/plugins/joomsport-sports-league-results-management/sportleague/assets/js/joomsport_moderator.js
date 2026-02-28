jQuery(document).ready(function() {

    jQuery(document).on("click", '#jsModerNewTeam', function(){
        loadTeam(0);
    });
    jQuery(document).on("click", '.jsModerEditTeam', function(){
        var teamID = jQuery(this).attr("data-id");
        loadTeam(teamID);
    });

    function loadTeam(teamId){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_team',
            'teamId': teamId,
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);
        });
    }
    jQuery(document).on("submit", "#formTeamEditFE", function(e){

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = jQuery(this);

        var data = {
            'action': 'joomsport_moder_team_save',
            data: form.serialize(),
        };

        jQuery.post(ajaxurl, data, function(response) {
            resObj = JSON.parse(response);
            if(resObj.error){
                alert(resObj.error);
                jQuery(".jsmoderInner").removeClass("jsSjLoading");
                return;
            }

            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(resObj.data);
        });



    });
    jQuery("#jsmoderTabsTeams").on("click", function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_team_list',
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);
        });
    });
    jQuery("#jsmoderTabsPlayers").on("click", function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_player_list',
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);
        });
    });
    jQuery("#jsmoderTabsMatches").on("click", function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_match_list',
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);
        });
    });

    jQuery(document).on("click", '#jsModerNewPlayer', function(){
        loadPlayer(0);
    });
    jQuery(document).on("click", '.jsModerEditPlayer', function(){
        var playerID = jQuery(this).attr("data-id");
        loadPlayer(playerID);
    });
    function loadPlayer(playerId){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_player',
            'playerId': playerId,
        };

        jQuery.post(ajaxurl, data, function(response) {

            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);
        });
    }
    jQuery(document).on("submit", "#formPlayerEditFE", function(e){

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = jQuery(this);

        var data = {
            'action': 'joomsport_moder_player_save',
            data: form.serialize(),
        };

        jQuery.post(ajaxurl, data, function(response) {
            resObj = JSON.parse(response);
            if(resObj.error){
                alert(resObj.error);
                jQuery(".jsmoderInner").removeClass("jsSjLoading");
                return;
            }

            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(resObj.data);
        });



    });


    jQuery(document).on("click", '.jsModerEditMatch', function(){
        var matchID = jQuery(this).attr("data-id");
        loadMatch(matchID);
    });
    function loadMatch(matchID){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_match',
            'matchID': matchID,
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);

            jQuery("#playerzSub_id").chosen();
        });
    }

    jQuery(document).on("submit", "#formMatchEditFE", function(e){

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = jQuery(this);

        var data = {
            'action': 'joomsport_moder_match_save',
            data: form.serialize(),
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);
        });



    });

    jQuery(document).on("change",'select[name="stb_season_id"]',function() {
        jQuery('#js_team_playersDIV').html("Loading...");
        var data = {
            'action': 'team_seasonrelated',
            'season_id': jQuery('select[name="stb_season_id"]').val(),
            'post_id': jQuery('input[name="teamID"]').val()
        };

        jQuery.post(ajaxurl, data, function (response) {
            var res = jQuery.parseJSON(response);
            if (res.players) {
                jQuery('#js_team_playersDIV').html(res.players);
                jQuery("#stb_players_id").chosen({disable_search_threshold: 10, width: "95%", disable_search: false});
            }
            if (res.bonuses) {
                jQuery('#js_team_bonusesDIV').html(res.bonuses);
            }
            if (res.efassigned) {
                jQuery('#js_team_efassignedDIV').html(res.efassigned);
            }
            //jQuery('#stb_players_id').trigger('liszt:updated');

        });
    });

    jQuery(document).on("click", '#jsModerNewMatch', function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_match_add',
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);

        });
    });

    jQuery(document).on("change", '#MatchADDseasonId', function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_match_add_matchdays',
            'seasonID': jQuery(this).val(),
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery("#jsModerLoadMdays").html(response);
            jQuery("#jsModerLoadMdayMatches").html("");
        });

    });
    jQuery(document).on("change", '#MatchADDmdayId', function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_match_show_matchday',
            'matchdayID': jQuery(this).val(),
        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery("#jsModerLoadMdayMatches").html(response);

        });

    });

    jQuery(document).on("click", '.mgl-moder-add-button', function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var set_home_team = jQuery("#set_home_team").val();
        var set_away_team = jQuery("#set_away_team").val();
        var m_date_foot = jQuery("#m_date_foot").val();
        var m_time_foot = jQuery("#m_time_foot").val();



        var data = {
            'action': 'joomsport_moder_match_new',
            'matchdayID': jQuery("#MatchADDmdayId").val(),
            'homeID': set_home_team,
            'awayID': set_away_team,
            'mDate': m_date_foot,
            'mTime': m_time_foot,
        };

        jQuery.post(ajaxurl, data, function(response) {
            resObj = JSON.parse(response);
            if(resObj.error){
                alert(resObj.error);
                jQuery(".jsmoderInner").removeClass("jsSjLoading");
                return;
            }

            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery("#jsModerLoadMdayMatches").html(resObj.data);

        });

    });

    jQuery(document).on("click", '.jsmoderInner .jsmoderDelTeam', function(){
        if(confirm("Are you sure?")){
            jQuery(".jsmoderInner").addClass("jsSjLoading");
            var teamID = jQuery(this).attr("data-id");
            var elTR = jQuery(this).parents("tr");
            var data = {
                'action': 'joomsport_moder_team_del',
                'teamID': teamID
            };

            jQuery.post(ajaxurl, data, function(response) {
                resObj = JSON.parse(response);
                if(resObj.error){
                    alert(resObj.error);
                    jQuery(".jsmoderInner").removeClass("jsSjLoading");
                    return;
                }

                jQuery(".jsmoderInner").removeClass("jsSjLoading");
                elTR.remove();


            });
        }
    });

    jQuery(document).on("click", '.jsmoderInner .jsmoderDelPlayer', function(){
        if(confirm("Are you sure?")){
            jQuery(".jsmoderInner").addClass("jsSjLoading");
            var playerID = jQuery(this).attr("data-id");
            var elTR = jQuery(this).parents("tr");
            var data = {
                'action': 'joomsport_moder_player_del',
                'playerID': playerID
            };

            jQuery.post(ajaxurl, data, function(response) {
                resObj = JSON.parse(response);
                if(resObj.error){
                    alert(resObj.error);
                    jQuery(".jsmoderInner").removeClass("jsSjLoading");
                    return;
                }

                jQuery(".jsmoderInner").removeClass("jsSjLoading");
                elTR.remove();


            });
        }
    });

    jQuery(document).on("click", '.jsmoderInner .jsmoderDelMatch', function(){
        if(confirm("Are you sure?")){
            jQuery(".jsmoderInner").addClass("jsSjLoading");
            var matchID = jQuery(this).attr("data-id");
            var elTR = jQuery(this).parents("tr");
            var data = {
                'action': 'joomsport_moder_match_del',
                'matchID': matchID
            };

            jQuery.post(ajaxurl, data, function(response) {
                resObj = JSON.parse(response);
                if(resObj.error){
                    alert(resObj.error);
                    jQuery(".jsmoderInner").removeClass("jsSjLoading");
                    return;
                }

                jQuery(".jsmoderInner").removeClass("jsSjLoading");
                elTR.remove();


            });
        }
    });

    jQuery(document).on("change", '.moderSelectFilter', function(){
        jQuery(".jsmoderInner").addClass("jsSjLoading");

        var data = {
            'action': 'joomsport_moder_match_list',
            'filters[seasonID]': jQuery("#moderSeasonFilter").val(),
            'filters[teamID]': jQuery("#moderTeamFilter").val(),

        };

        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".jsmoderInner").removeClass("jsSjLoading");
            jQuery(".jsmoderInner").html(response);

        });

    });






});