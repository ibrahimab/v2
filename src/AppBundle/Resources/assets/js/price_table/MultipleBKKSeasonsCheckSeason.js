

var MultipleBKKSeasonsCheckSeason = (function() {

    'use strict';

    // check which bijkomendekosten-season has to be shown (based on scroll position)

    function MultipleBKKSeasonsCheckSeason(jq, MultipleBKKSeasonsSwitch) {

        if (jq(".tarieventabel_datadiv").length!==0) {

            var column_width = jq("tr.tarieventabel_datumbalk_content > td").outerWidth();
            var leftPos = parseInt(jq(".tarieventabel_wrapper_rechts").scrollLeft(),10);

            if (jq(".tarieventabel_datadiv").data("begin_seizoen")) {
                var seizoenwissel = (parseInt(jq(".tarieventabel_datadiv").data("begin_seizoen"))-5) * column_width;

                if (leftPos > seizoenwissel) {
                        MultipleBKKSeasonsSwitch(jq, jq(".tarieventabel_datadiv").data("last_seizoen_id"));
                } else {
                        MultipleBKKSeasonsSwitch(jq, jq(".tarieventabel_datadiv").data("first_seizoen_id"));
                }
            }
        }
    }

    return MultipleBKKSeasonsCheckSeason;

})();

module.exports = MultipleBKKSeasonsCheckSeason;
