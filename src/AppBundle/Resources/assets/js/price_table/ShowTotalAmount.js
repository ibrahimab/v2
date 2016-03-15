
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
