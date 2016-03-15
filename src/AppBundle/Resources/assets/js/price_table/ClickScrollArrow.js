
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
