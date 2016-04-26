
(function(jq) {

    'use strict';

    var Init                          = require('./Init');
    var PositionArrows                = require('./PositionArrows');
    var ToggleMoreLessPersons         = require('./ToggleMoreLessPersons');
    var ShowTotalAmount               = require('./ShowTotalAmount');
    var ClickBookNow                  = require('./ClickBookNow');
    var ClickScrollArrow              = require('./ClickScrollArrow');
    var ToggleMoreInformation         = require('./ToggleMoreInformation');
    var MultipleBKKSeasonsShowCurrent = require('./MultipleBKKSeasonsShowCurrent');
    var MultipleBKKSeasonsSwitch      = require('./MultipleBKKSeasonsSwitch');
    var MultipleBKKSeasonsCheckSeason = require('./MultipleBKKSeasonsCheckSeason');
    var CheckArrowGreyedOut           = require('./CheckArrowGreyedOut');
    var CopyToClipBoard               = require('./CopyToClipBoard');

    jq(document).ready(function() {

        // initialize pricetable
        if (jq(".tarieventabel_wrapper").length!==0) {

            // init
            Init(jq);

            // position scroll-arrows
            PositionArrows(jq);
        }

        // when resizing
        jq( window ).resize(function() {

            // position scroll-arrows
            PositionArrows(jq);

            // check of arrows should be greyed out
            CheckArrowGreyedOut(jq);

        });

        // toggle showing more/less persons
        jq(".tarieventabel_toggle_toon_verberg a").click(function(event) {
            ToggleMoreLessPersons(jq(this), jq, event);
        });

        // show total amount after click on price
        jq("td.tarieventabel_tarieven_beschikbaar").click(function(event) {
            ShowTotalAmount(jq(this), jq, Routing, event);
        });

        // click on small "book now" button
        jq(document).on("click", ".tarieventabel_totaalprijs button", function(event) {
            ClickBookNow(jq(this), jq, event);
        });

        // click on arrows: scroll table
        jq(document).on("click", ".tarieventabel_pijl", function(event) {
            ClickScrollArrow(jq(this), jq, event);
        });

        // scroll table
        $(".tarieventabel_wrapper_rechts").scroll(function() {

            // check of arrows should be greyed out
            CheckArrowGreyedOut(jq);

            // check which bijkomendekosten-season has to be shown (based on scroll position)
            MultipleBKKSeasonsCheckSeason(jq, MultipleBKKSeasonsSwitch);

        });

        // toggle click on "More information" (bkk): slidedown
        jq(document).on("click", ".bkk_toggle_more_information",  function(event) {
            ToggleMoreInformation(jq(this), jq, event);
        });

        // handle multiple seasons of bijkomendekosten
        if (jq(".tarieventabel_toelichting_active_season").length!==0) {

            // show current additional costs season
            MultipleBKKSeasonsShowCurrent(jq);

            // switch between additional costs seasons
            jq("select.tarieventabel_toelichting_switch_seasons").change(function(event) {
                MultipleBKKSeasonsSwitch(jq, jq(this).val());
            });
        }

        // click on 'copy to clipboard'
        $(document).on("click", ".tarieventabel_totaalprijs .copy-to-clipboard-icon", function(event) {
            CopyToClipBoard(jq(this), jq, event);
        });
    });

})(jQuery);
