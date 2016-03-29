(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

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

},{}],2:[function(require,module,exports){

var ClickBookNow = (function() {

    'use strict';

    // click on small "book now" button

    function ClickBookNow(self, jq, event) {

        event.preventDefault();

        var url = jq(".tarieventabel_wrapper").data("boek-url")+"&d="+$(this).data("week")+"&ap="+$(this).data("aantalpersonen");
        document.location.href=url;

    }

    return ClickBookNow;

})();

module.exports = ClickBookNow;

},{}],3:[function(require,module,exports){

var ClickScrollArrow = (function() {

    'use strict';

    // click on arrows: scroll table

    function ClickScrollArrow(self, jq, event) {

        var actieve_pijl = self;

        // kijken of de animatie al bezig is
        if( jq(".tarieventabel_wrapper_rechts").is(":animated") ) {

            // zo ja: click over 200ms opnieuw starten
            setTimeout(function() {
                actieve_pijl.trigger("click");
            },200);

        } else {

            var leftPos = parseInt(jq(".tarieventabel_wrapper_rechts").scrollLeft(), 10);
            var pijl_links=false;

            if (self.hasClass("tarieventabel_pijl_links")) {
                pijl_links=true;
            }

            var eindpos  = 0;
            var nieuwpos = 0;
            var maxpos   = 0;

            var column_width = jq("tr.tarieventabel_datumbalk_content > td").outerWidth();
            var full_width   = jq("div.tarieventabel_wrapper_rechts").width();

            jq(".tarieventabel_maanden td").each(function() {
                if (jq(this).data("maand-kolom")) {

                    eindpos = parseInt(jq(this).data("maand-kolom"))*column_width;
                    maxpos =  parseInt(jq(this).data("maand-kolom"))*column_width;

                    if(pijl_links) {
                        if(eindpos<leftPos) {
                            nieuwpos=eindpos;
                        }
                    } else {
                        if(nieuwpos==0 && eindpos>leftPos) {
                            nieuwpos=eindpos;
                        }
                    }
                }
            });

            if((pijl_links && nieuwpos==0 && leftPos==0) || (!pijl_links && maxpos<= (leftPos+full_width) )) {
                // bij klikken als eind al is bereikt: rood oplichten
                actieve_pijl.addClass("tarieventabel_pijl_scrollstop").delay(500).queue(function(next) {
                    self.removeClass("tarieventabel_pijl_scrollstop");
                    next();
                });
            } else {
                if((nieuwpos+full_width)>maxpos) {
                    // bij bereiken eind: rood oplichten
                    actieve_pijl.delay(200).queue(function(next) {
                        actieve_pijl.addClass("tarieventabel_pijl_scrollstop").delay(500).queue(function(next2) {
                            jq(this).removeClass("tarieventabel_pijl_scrollstop");
                            next2();
                        });
                        next();
                    });
                }

                // animatie: scrollen tabel in 600ms
                jq(".tarieventabel_wrapper_rechts").animate({scrollLeft: nieuwpos}, 600);
            }
        }
        event.preventDefault();
    }

    return ClickScrollArrow;

})();

module.exports = ClickScrollArrow;

},{}],4:[function(require,module,exports){

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

},{}],5:[function(require,module,exports){


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

},{}],6:[function(require,module,exports){

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

},{}],7:[function(require,module,exports){

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

},{}],8:[function(require,module,exports){

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

},{}],9:[function(require,module,exports){

var ShowTotalAmount = (function() {

    'use strict';

    // show total amount after click on price

    function ShowTotalAmount(self, jq, SymfonyRouting, event) {

        event.preventDefault();
        var new_html;

        // scroll down if necessary
        var position_tarieventabel = parseInt( jq(".tarieventabel_maanden_top").offset().top - 2, 10);
        var position_scroll = parseInt( jq(document).scrollTop(), 10);

        var scroll_time = 800;

        if(position_scroll > position_tarieventabel ) {
            position_tarieventabel = position_scroll;
            scroll_time = 0;
        }

        var url = SymfonyRouting.generate('type_total_price', {
                typeId: jq(".tarieventabel_wrapper").data("type_id"),
                seasonIdInquery: jq(".tarieventabel_wrapper").data("seizoen_id_inquery"),
                date: self.data("week"),
                numberOfPersons: self.parent().data("aantalpersonen")
            }
        );

        if(jq( "div.tarieventabel_totaalprijs_wrapper" ).outerHeight()==0) {

            //
            // no tarieventabel_totaalprijs_wrapper visible yet
            //
            jq("div.tarieventabel_totaalprijs_wrapper").hide();

            jq.when(
                    jq("html, body").animate({scrollTop: position_tarieventabel}, scroll_time, "swing")
                ,
                    jq.getJSON(url, function(data) {
                        if(data.html) {
                            new_html = data.html;
                        }
                    })
            ).done(function() {
                    jq( "div.tarieventabel_totaalprijs_wrapper" ).html(new_html);
                    jq( "div.tarieventabel_totaalprijs_wrapper" ).slideDown("slow", function() {
                });
            });

        } else {
            jq.when(
                    jq("div.tarieventabel_totaalprijs_wrapper").animate({ opacity: 0 })
                ,
                    jq("html, body").animate({scrollTop: position_tarieventabel}, scroll_time, "swing")
                ,
                jq.getJSON(url, function(data) {
                    if(data.html) {
                        new_html = data.html;
                    }
                })
            ).done(function() {
                jq( "div.tarieventabel_totaalprijs" ).replaceWith(new_html);
                jq( "div.tarieventabel_totaalprijs_wrapper" ).animate({ opacity: 1 });
            });
        }

    }

    return ShowTotalAmount;

})();

module.exports = ShowTotalAmount;

},{}],10:[function(require,module,exports){

var ToggleMoreInformation = (function() {

    'use strict';

    // toggle click on "More information" (bkk): slidedown

    function ToggleMoreInformation(self, jq, event) {

        event.preventDefault();

        var bkid = self.data("bkid");

        jq(".bkk_more_information[data-bkid=" + bkid + "]").slideToggle();

    }

    return ToggleMoreInformation;

})();

module.exports = ToggleMoreInformation;

},{}],11:[function(require,module,exports){

var ToggleMoreLessPersons = (function() {

    'use strict';

    // Toggle showing more/less persons
    function click(self, jq, event) {

        event.preventDefault();

        // horizontale scrollpositie onthouden
        var leftPos = parseInt(jq(".tarieventabel_wrapper_rechts").scrollLeft(),10);

        if ( jq(".tarieventabel_verbergen").is(":visible") ) {
            // inklappen
            jq(".tarieventabel_verbergen").fadeOut(700,function(){
                jq(".tarieventabel_verbergen").hide();

                jq(".tarieventabel_toggle_toon_verberg span").html(self.data("default"));

                jq(".tarieventabel_toggle_toon_verberg i").removeClass("icon-chevron-sign-up");
                jq(".tarieventabel_toggle_toon_verberg i").addClass("icon-chevron-sign-down");

            });
        } else {

            // openklappen

            jq(".tarieventabel_verbergen").fadeIn(700,function(){
                jq(".tarieventabel_verbergen").show();
            });

            jq(".tarieventabel_toggle_toon_verberg span").html(self.data("hide"));

            jq(".tarieventabel_toggle_toon_verberg i").removeClass("icon-chevron-sign-down");
            jq(".tarieventabel_toggle_toon_verberg i").addClass("icon-chevron-sign-up");

        }


        // weer scrollen naar oude scrollpositie
        jq(".tarieventabel_wrapper_rechts").scrollLeft(leftPos);

        return false;

    }

    return click;

})();

module.exports = ToggleMoreLessPersons;

},{}],12:[function(require,module,exports){

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

    });

})(jQuery);

},{"./CheckArrowGreyedOut":1,"./ClickBookNow":2,"./ClickScrollArrow":3,"./Init":4,"./MultipleBKKSeasonsCheckSeason":5,"./MultipleBKKSeasonsShowCurrent":6,"./MultipleBKKSeasonsSwitch":7,"./PositionArrows":8,"./ShowTotalAmount":9,"./ToggleMoreInformation":10,"./ToggleMoreLessPersons":11}]},{},[12]);
