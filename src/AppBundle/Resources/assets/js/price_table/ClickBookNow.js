
var ClickBookNow = (function() {

    'use strict';

    // click on small "book now" button

    function ClickBookNow(self, jq, event) {

        event.preventDefault();

        var url = jq('[data-action="book"]').data("book-url")+"&d="+self.data("week")+"&ap="+self.data("aantalpersonen");
        document.location.href=url;

    }

    return ClickBookNow;

})();

module.exports = ClickBookNow;
