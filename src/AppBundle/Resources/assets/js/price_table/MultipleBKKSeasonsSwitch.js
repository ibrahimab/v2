
var MultipleBKKSeasonsSwitch = (function() {

    'use strict';

    // switch between additional costs seasons

    function MultipleBKKSeasonsSwitch(jq, seizoen_id) {

        var active_seizoen_id = jq(".tarieventabel_toelichting_active_season").data("active_seizoen_id");

        if (jq(".tarieventabel_toelichting_active_season").length!==0 && active_seizoen_id != seizoen_id) {

            jq("select.tarieventabel_toelichting_switch_seasons").val(seizoen_id);

            var active_season_div = jq(".tarieventabel_toelichting_one_season[data-seizoen_id="+jq("select.tarieventabel_toelichting_switch_seasons").val()+"]");

            var new_height = active_season_div.height();
            jq(".tarieventabel_toelichting_all_seasons").animate({height: new_height}, 400, function() {
                jq(".tarieventabel_toelichting_all_seasons").css("height", "auto");
            });

            jq(".tarieventabel_toelichting_active_season").fadeOut(400, function() {

                jq(".tarieventabel_toelichting_active_season").html( active_season_div.html() );
                jq(".tarieventabel_toelichting_active_season").fadeIn(700);

            });
        }
        active_seizoen_id = seizoen_id;
        jq(".tarieventabel_toelichting_active_season").data("active_seizoen_id", active_seizoen_id);

    }

    return MultipleBKKSeasonsSwitch;

})();

module.exports = MultipleBKKSeasonsSwitch;
