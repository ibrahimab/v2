
var ClickOptionRequest = (function() {

    'use strict';

    // click on small "request option" button

    function ClickOptionRequest(self, jq, event) {

        event.preventDefault();

        var url = new URI(self.data('url'));
        url.setQuery('tid', self.data('tid'));
        url.setQuery('d', self.data('week'));
        url.setQuery('ap', self.data('aantalpersonen'));
        url.setQuery('back', new URI().toString());

        document.location.href=url;

    }

    return ClickOptionRequest;

})();

module.exports = ClickOptionRequest;
