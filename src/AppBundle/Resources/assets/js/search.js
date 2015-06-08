var window.Chalet = (function(ns, jq, undefined) {
    'use strict';
    
    ns.search = {
        
        initialize: function(filters) {
            ns.filters.filters = filters;
        },
        
        events: {
            
            bind: function() {
                
            }
        },
        
        filters: {
            
            filters: [],
            
            active: function() {
                return ns.filters.filters;
            },
            
            add: function(filter, value) {
                ns.filters.filters[filter] = value;
            },
            
            remove: function(filter) {
                delete ns.filters.filters[filter];
            },
            
            clear: function() {
                ns.filters.filters = [];
            }
        },
    };
    
    return ns;
    
}(window.Chalet || {}, jQuery));