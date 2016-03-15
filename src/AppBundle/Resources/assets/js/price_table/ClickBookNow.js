
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
