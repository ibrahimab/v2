
var MultipleBKKSeasonsShowCurrent = (function() {

    'use strict';

    // show current additional costs season

    function MultipleBKKSeasonsShowCurrent(jq) {

        var active_seizoen_id = jq("select.tarieventabel_toelichting_switch_seasons").val();
        var active_season_div = jq(".tarieventabel_toelichting_one_season[data-seizoen_id=" + active_seizoen_id+"]");
        jq(".tarieventabel_toelichting_active_season").html( active_season_div.html() );

        jq(".tarieventabel_toelichting_active_season").data("active_seizoen_id", active_seizoen_id);

    }

    return MultipleBKKSeasonsShowCurrent;

})();

module.exports = MultipleBKKSeasonsShowCurrent;
