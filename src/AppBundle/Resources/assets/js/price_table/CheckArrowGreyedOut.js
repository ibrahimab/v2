
var CheckArrowGreyedOut = (function() {

    'use strict';

    // check of arrows should be greyed out

    function CheckArrowGreyedOut(jq) {

        var eindpos              = 0;
        var leftPos              = parseInt(jq(".tarieventabel_wrapper_rechts").scrollLeft(),10);
        var column_width         = jq("tr.tarieventabel_datumbalk_content > td").outerWidth();
        var full_width           = jq("div.tarieventabel_wrapper_rechts").width() + 2;
        var tarieventabel_maxpos = parseInt(jq(".tarieventabel_toelichting_active_season").data("tarieventabel_maxpos"), 10);

        if (tarieventabel_maxpos == 0) {
            jq(".tarieventabel_maanden td").each(function() {

                if (jq(this).data("maand-kolom")) {

                    eindpos = parseInt(jq(this).data("maand-kolom"))*column_width;
                    tarieventabel_maxpos = parseInt(jq(this).data("maand-kolom"))*column_width;

                }

            });
        }

        var actieve_pijl;
        var pijl_links=false;

        jq(".tarieventabel_pijl").each(function() {

            actieve_pijl = jq(this);

            if (actieve_pijl.hasClass("tarieventabel_pijl_links")) {
                pijl_links=true;
            } else {
                pijl_links=false;
            }

            if ((pijl_links && leftPos==0) || (!pijl_links && (leftPos+full_width)>=tarieventabel_maxpos)) {
                actieve_pijl.addClass("tarieventabel_pijl_scroll_greyed_out");
            } else {
                actieve_pijl.removeClass("tarieventabel_pijl_scroll_greyed_out");
            }

        });

        jq(".tarieventabel_toelichting_active_season").data("tarieventabel_maxpos", tarieventabel_maxpos)

    }

    return CheckArrowGreyedOut;

})();

module.exports = CheckArrowGreyedOut;
