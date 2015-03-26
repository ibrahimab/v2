(function(jq, Routing) {
    'use strict';
    
    /**
     * This method listens for changes in the sort select field to re-order
     * all the regions based on the selected value
     */
    jq('body').on('change', '[data-role="sort-regions"]', function() {
        
        var path = window.location.pathname.split('/');
        var country = path[3];
        var sort = jq(this).val();
        
        window.location.href = Routing.generate('show_country_nl', { countrySlug: country, sort: sort });
    });
    
})(jQuery, Routing);