
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
