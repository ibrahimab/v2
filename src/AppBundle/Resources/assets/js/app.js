(function(jq, Routing) {
    'use strict';
    
    /**
     * This method listens for changes in the sort select field to re-order
     * all the regions based on the selected value
     */
    jq('body').on('change', '[data-role="sort-regions"]', function() {
        
        var country = Chalet.get().app.route.params.countrySlug;
        var sort    = jq(this).val();
        
        window.location.href = Routing.generate('show_country_nl', { countrySlug: country, sort: sort });
    });
    
    /**
     * This listener allows links to be opened in a new window/tab by using the
     * data-role attribute
     */
    jq('body').on('click', '[data-role="new-window"]', function(event) {
        
        event.preventDefault();
        window.open(jq(this).attr('href'));
    });
    
    jq('body').on('click', '[data-role="toggle-long-list"]', function(event) {
        
        event.preventDefault();
        
        var element = jq(this);
        var icon    = element.find('[data-role="toggle-icon-long-list"]');
        var list    = element.parents('ul[data-role="long-list"]');
        
        if (true === element.data('opened')) {
            
            element.data('opened', false);
            list.find('li:nth-child(n+4)').not(element).slideUp();
            icon.removeClass(icon.data('opened-icon')).addClass(icon.data('closed-icon'));
            
        } else {
            
            element.data('opened', true);
            list.find('li:nth-child(n+4)').not(element).slideDown();
            icon.removeClass(icon.data('closed-icon')).addClass(icon.data('opened-icon'));
        }
    });
    
})(jQuery, Routing);