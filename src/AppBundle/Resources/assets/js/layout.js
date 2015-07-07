window.Chalet = (function(ns, jq, undefined) {
    'use strict';
    
    ns.Layout = {
        
        fix: {
            
            onLoad: function() {
                ns.Layout.blocks();
            },
            
            onResize: function() {
                ns.Layout.blocks();
            }
        },
        
        blocks: function() {
            
            var block          = null;
            var highest        = 0;
            var block_position = 0;
            var row_position   = 0;
            var rows           = {};
            
            jq('[data-role="fix-blocks"] > div.columns').each(function() {
                
                block        = jq(this);
                row_position = block.position().top;

                if (block_position !== row_position) {
                    rows[row_position] = block.height();
                } else {
                    block.height(rows[row_position]);
                }
                
                block_position = row_position;
            });
            
            // show hide-on-load block
            jq('[data-role="fix-blocks"]').removeClass('hide-on-load');
        }
    };
    
    // fix layout on page load
    jq(ns.Layout.fix.onLoad);
    
    // fix layout on resize
    jq(window).resize(ns.Layout.fix.onResize);
    
    return ns;
    
}(window.Chalet = window.Chalet || {}, jQuery));