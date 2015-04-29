(function(jq, Routing, Chalet, undefined) {
    'use strict';

    // setting up scroll button for long pages
    jq(function() {

        // body element cache
        var body = jq('body');

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
         * This listener allows for collapsable lists
         */
        body.on('click', '[data-role="toggle-long-list"]', function(event) {

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
        console.log(Chalet.get());
        if (Chalet.get()['app']['controller'] === 'countries::destinations') {
            var italyMaps = Chalet.Maps.Italy.initialize('[data-role="italy-maps"]');
        }

        /**
         * Render a google maps element using just data-role="google-maps"
         */
        // var map = Object.create({}, Maps);
        // console.log(map)
    });

})(jQuery, Routing, window.Chalet = window.Chalet || {});