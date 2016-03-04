
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
