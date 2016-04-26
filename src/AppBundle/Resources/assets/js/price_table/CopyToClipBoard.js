

var CopyToClipBoard = (function() {

    'use strict';

    // handle click on 'copy to clipboard'

    function CopyToClipBoard(self, jq, event) {

        event.preventDefault();

        // prevent that browser changes scroll position
        var position_scroll = jq(window).scrollTop();

        var textarea = document.querySelector('.copy-to-clipboard-text');

        textarea.select();

        // back to scroll position
        setTimeout(function(){
            jq("html, body").scrollTop(position_scroll);
        }, 1);

        try {
            var successful = document.execCommand('copy');
            if (successful) {
                jq(".tarieventabel_totaalprijs").addClass("tarieventabel_totaalprijs_white");
                window.setTimeout(function() {
                    jq(".tarieventabel_totaalprijs").removeClass("tarieventabel_totaalprijs_white");
                }, 150);
            }
        } catch(err) {
            alert('De kopieerfunctie werkt helaas niet in deze browser.');
        }
    }

    return CopyToClipBoard;

})();

module.exports = CopyToClipBoard;
