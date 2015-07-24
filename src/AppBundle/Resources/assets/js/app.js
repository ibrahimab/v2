(function(jq, Routing, Chalet, lc, undefined) {
    'use strict';

    // setting up scroll button for long pages
    jq(function() {

        /**
         * fixed header scroll effects
         */
        var stickyHeader = function() {

            if (window.innerWidth <= 641) {

                // mobile does not have sticky header!
                jq('body').removeClass('smaller');
                return;
            }

            var distanceY = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            var body      = jq('body');

            if (distanceY > 10) {
                body.addClass('smaller');
            } else {
                body.removeClass('smaller');
            }
        };

        jq(window).on('scroll', stickyHeader);
        stickyHeader();

        // autocomplete
        Chalet.Autocomplete.initialize({

            input: '[data-role="autocomplete-query"]',
            limit: 5,
            resultsContainer: '[data-role="autocomplete-results"]'
        });

        // search
        var filters = Chalet.get('app')['filters'];
        Chalet.Search.initialize(jq.extend({}, filters === undefined ? {} : filters['normal']));

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
         * FAQ events
         */
        body.on('click', '[data-role="faq-question"]', function(event) {

            event.preventDefault();
            var element = jq(this);
            var toggle  = jq('[data-role="faq-toggle"]');

            jq('[data-role="faq-question"]').not(element).siblings('[data-role="faq-answer"]:visible').slideUp();
            element.siblings('[data-role="faq-answer"]:hidden').slideDown();


            toggle.data('toggle', 'open').text(toggle.data('toggle-label-open'));
        });

        body.on('click', '[data-role="faq-toggle"]', function(event) {

            event.preventDefault();

            var element = jq(this);

            if (element.data('toggle') === 'open') {

                jq('[data-role="faq-answer"]').slideDown();
                element.data('toggle', 'close').text(element.data('toggle-label-close'));

            } else {

                jq('[data-role="faq-answer"]').slideUp();
                element.data('toggle', 'open').text(element.data('toggle-label-open'));
            }
        });

        /**
         * Chat button
         */
        body.on('click', '[data-role="chat-button"]', function(event) {

            event.preventDefault();

            if (undefined !== LC_API) {
                LC_API.open_chat_window();
            }
        });

        /**
         * Save favorite
         */

        body.on('click', '[data-role="add-favorite"]', function(event) {

            event.preventDefault();
            var element = jq(this);

            if (true !== element.data('disable')) {
                Chalet.Favorite.add(element);
            }
        });

        body.on('click', '[data-role="remove-favorite"]', function(event) {

            event.preventDefault();
            var element = jq(this);

            if (true !== element.data('disable')) {
                Chalet.Favorite.remove(element);
            }
        });

        /**
         * Fancybox
         */
        jq('[data-role="fancybox"]').fancybox({
            helpers : {
                media: true
            }
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

        body.on('click', '[data-role="ajax-tooltip"]', function(event) {

            event.preventDefault();

            var el      = jq(this);
            var retries = (undefined === el.data('retries') ? 0 : parseInt(el.data('retries'), 10));


            if( el.find('[data-role=tooltip-wrapper]').is(':visible') ) {

                // hide clicked tooltip
                el.find('[data-role=tooltip-wrapper]').hide();
            } else {

                // hide all other active tooltips
                jq('[data-role=tooltip-wrapper]').hide();

                // show clicked tooltip
                el.find('[data-role=tooltip-wrapper]').show();
            }

            el.data('retries', retries + 1);

            if (true !== el.data('cached') && retries < 3) {

                jq.ajax({

                    type: 'get',
                    url: Routing.generate('additional_costs_type', {typeId: el.data('id'), seasonId: el.data('season')}),
                    success: function(data) {

                        el.data('cached', true).removeClass('loading').find('[data-role="tooltip-content"]').html(data);
                    }
                });
            }
        });

        jq('body').on('click', '[data-role="toggle-filters"]', function(event) {

            event.preventDefault();

            var el = jq(this);

            if (el.data('status') === 'closed') {

                el.data('status', 'open').removeClass('closed');

                jq('[data-role="closable-filter"]').data('status', 'open').siblings('.fields').slideDown();
                jq('[data-role="closable-filter"]').removeClass('closed');

            } else {

                el.data('status', 'closed').addClass('closed');

                jq('[data-role="closable-filter"]').data('status', 'closed').siblings('.fields').slideUp();
                jq('[data-role="closable-filter"]').addClass('closed');
            }

        });

        jq('body').on('click', '[data-role="closable-filter"]', function(event) {

            event.preventDefault();

            var el = jq(this);

            if (el.data('status') === 'closed') {

                el.data('status', 'open').siblings('.fields').slideDown();
                el.removeClass('closed');

            } else {

                el.data('status', 'closed').siblings('.fields').slideUp();
                el.addClass('closed');
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

        // display hide-on-load blocks
        jq('.hide-on-load').removeClass('hide-on-load');
    });

})(jQuery, Routing, window.Chalet = window.Chalet || {});