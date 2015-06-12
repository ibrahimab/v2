(function(jq, Routing, Chalet, undefined) {
    'use strict';

    // setting up scroll button for long pages
    jq(function() {

        // autocomplete
        Chalet.Autocomplete.initialize('[data-role="autocomplete-query"]', 5, '[data-role="autocomplete-results"]');

        // search
        Chalet.Search.initialize();

        // body element cache
        var body = jq('body');

        /**
         * This methods listens for elements that are tagged to be clickable
         * and have a data-url attribute to send the user to
         */
        body.on('click', '[data-role="clickable"]', function(event) {

            event.preventDefault();

            var url = jq(this).data('url');

            if (null !== url) {
                window.location.href = url;
            }
        });

        /**
         * This method listens for changes in the sort select field to re-order
         * all the regions based on the selected value
         */
        body.on('change', '[data-role="sort-regions"]', function() {

            var country = Chalet.get().app.route.params.countrySlug;
            var sort    = jq(this).val();

            window.location.href = Routing.generate('show_country_nl', { countrySlug: country, sort: sort });
        });

        /**
         * This listener allows links to be opened in a new window/tab by using the
         * data-role attribute
         */
        body.on('click', '[data-role="new-window"]', function(event) {

            event.preventDefault();
            window.open(jq(this).attr('href'));
        });

        /**
         * This listener allows links to be opened in a new popup by using
         * the data-role="new-popup" attribute and defining width/height via
         * other data attributes
         */
        body.on('click', '[data-role="new-popup"]', function(event) {

            event.preventDefault();

            var element = jq(this);
            var url     = element.attr('href');
            var width   = element.data('popup-width')  || 300;
            var height  = element.data('popup-height') || 200;

            return window.open(url, 'new-popup', 'height=' + height + ',width=' + width + ',resizable=yes,menubar=no,location=yes', false);
        });

        /**
         * This listener allows for collapsable lists
         */
        var slideDown = function(element) {

            element.slideDown(50, function() {

                var next = jq(this).next('[data-role="long-list-item"]');
                if (next) {
                    slideDown(next);
                }
            });
        };

        var slideUp = function(element) {

            element.slideUp(50, function() {

                var prev = jq(this).prev('[data-role="long-list-item"]');
                if (prev) {
                    slideUp(prev);
                }
            });
        };

        jq('ul[data-role="long-list"] li:nth-child(n+4):not([data-role="toggle-long-list"])').attr('data-role', 'long-list-item');

        body.on('click', '[data-role="toggle-long-list"]', function(event) {

            event.preventDefault();

            var element = jq(this);
            var icon    = element.find('[data-role="toggle-icon-long-list"]');
            var list    = element.parents('ul[data-role="long-list"]');

            if (true === element.data('opened')) {

                element.data('opened', false);
                slideUp(list.find('li[data-role="long-list-item"]:last'));
                icon.removeClass(icon.data('opened-icon')).addClass(icon.data('closed-icon'));

            } else {

                element.data('opened', true);
                slideDown(list.find('li[data-role="long-list-item"]:first'));
                icon.removeClass(icon.data('closed-icon')).addClass(icon.data('opened-icon'));
            }
        });

        /**
         * Survey toggler
         */
        body.on('click', '[data-action="toggle-review"]', function(event) {

            event.preventDefault();

            var element = jq(this);
            var elements = jq('[data-role="review"]');

            elements.not(element).data('review-shown', false);
            elements.find('[data-role="review-text"]').slideUp();
            elements.find('[data-role="review-ratings"]').slideUp();

            if (true !== element.data('review-shown')) {

                element.data('review-shown', true);
                element.find('[data-role="review-text"]').slideDown();
                element.find('[data-role="review-ratings"]').slideDown();

            } else {
                element.data('review-shown', false);
            }
        });

        /**
         * This code handles the scroll to top button on every page
         */
        jq.scrollUp({

            scrollName: 'scroll-icon',
            animation: 'fade',
            scrollImg: {

                active: true,
                type: 'background'
            }
        });

        /**
         * This code handles the destinations map
         * It generates it via the jqvmap jQuery plugin
         * with some modifications to make it work in the new design
         *
         * @TODO: refactor this so this code only gets loaded on destinations page
         */
        if (Chalet.get()['app']['controller'] === 'countries::destinations') {
            var italyMaps = Chalet.Maps.Italy.initialize('[data-role="italy-maps"]');
        }
    });

})(jQuery, Routing, window.Chalet = window.Chalet || {});