
var PositionArrows = (function() {

    'use strict';

    // position scroll-arrows

    function PositionArrows(jq) {

        var widthOfTitles = jq(".tarieventabel_titels_links").width();

        // position arrow (left position of left arrow)
        jq(".tarieventabel_pijl_links").css("left",widthOfTitles+"px");

        // .tarieventabel_pijl_boven
        if(jq(".tarieventabel_datumbalk[data-counter=1]").length!==0) {

            // position arrow (top position of both arrows)
            jq(".tarieventabel_pijl_boven").css("top",jq(".tarieventabel_datumbalk[data-counter=1]").position().top+"px");

            // show both arrows
            jq(".tarieventabel_pijl_boven").css("display","block");

        }

        // .tarieventabel_pijl_onder
        if(jq(".tarieventabel_datumbalk[data-counter=2]").length!==0) {

            // position arrow (top position)
            jq(".tarieventabel_pijl_onder").css("top",jq(".tarieventabel_datumbalk[data-counter=2]").position().top+"px");

            // show both arrows
            jq(".tarieventabel_pijl_onder").css("display","block");

        }

    }

    return PositionArrows;

})();

module.exports = PositionArrows;
