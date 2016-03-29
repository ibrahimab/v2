
var Init = (function() {

    'use strict';

    function Init(jq) {

        //
        // initialize pricetable
        //

        var column_width  = jq("tr.tarieventabel_datumbalk_content > td").outerWidth();
        var full_width    = jq("div.tarieventabel_wrapper_rechts").width() + 2;
        var visible_cols  = 5;
        var scroll_to_col = 4;

        if (full_width / column_width < scroll_to_col) {
            visible_cols  = 3;
            scroll_to_col = 2;
        }

        // scroll to correct position, based on date
        var actieve_kolom = parseInt(jq(".tarieventabel_wrapper").data("actieve-kolom"),10);
        if (actieve_kolom >= visible_cols) {
            var position = actieve_kolom * column_width;
            position=position - (scroll_to_col*column_width);
            jq(".tarieventabel_wrapper_rechts").scrollLeft(position);
        }

        // scroll_first_monthyear (empty $_GET["d"], start of season for this accommodation)
        var scroll_first_monthyear = jq(".tarieventabel_wrapper").data("scroll_first_monthyear");
        if(scroll_first_monthyear) {
            var new_actieve_kolom = parseInt(jq("td[data-jaarmaand="+scroll_first_monthyear+"]").data("maand-eerste-kolom"),10);
            var position = new_actieve_kolom * column_width;
            position=position-column_width;
            jq(".tarieventabel_wrapper_rechts").scrollLeft(position);
        }

        // tarieventabel: te verbergen tr's verbergen (laag aantal personen/commissie bij reisagent)
        jq(".tarieventabel_verbergen").hide();

    }

    return Init;

})();

module.exports = Init;
