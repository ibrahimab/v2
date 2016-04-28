(function(jq, Routing, Chalet, lc, undefined) {
    'use strict';

    // setting up scroll button for long pages
    jq(function() {

        // social links
        Chalet.Social.initialize();

        // mobile menu child ul collapse/expand
        var collapsibleListItem = jq('div.menu-mobile ul li.has-child-menu a');

        collapsibleListItem.on('click', function(event) {
            jq(collapsibleListItem).parent().find("ul").toggle();
        });

        // manages selectbox seen and saved for mobile
        jq('#seen-and-saved-selectbox').on('change', function () {
            var url = $(this).val(); // get selected value
            if (url) { // require a URL
              window.location = url; // redirect
            }
            return false;
        });

        // removes first child when opening select box
        var firstSAVelement = jq('#seen-and-saved-selectbox').find('option').first();

        jq('#seen-and-saved-selectbox').on('focus', function (e) {
            firstSAVelement.remove();
        }).on('blur', function (e) {

            jq(this).find('option').first().before(firstSAVelement);
        });


        /**
         * fixed header scroll effects
         */
        var body         = jq('body');
        var menu         = jq('div.home-menu ul');
        var stickyHeader = function() {

            if (window.innerWidth <= 641) {

                // mobile does not have sticky header!
                body.removeClass('smaller');
                return;
            }

            var distanceY = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;

            if (distanceY > 10) {

                menu.css('z-index', 0);
                body.addClass('smaller');

            } else {

                menu.css('z-index', 999);
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
         * Book button
         */

        body.on('click', '[data-action="book"]', function(event) {

            var accURL         = new URI(window.location);

            if (accURL.search(true)["w"] && accURL.search(true)["w"] > 0 && accURL.search(true)["pe"] && accURL.search(true)["pe"] > 0) {
                return true;
            } else {

                event.preventDefault();

                var target =  '#price-availability';
                $('html, body').animate({
                    scrollTop: $(target).offset().top - 50
                }, 2000);
                return false;

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
         * This code handles the "scroll to top" button on every page
         */
        jq.scrollUp({

            scrollName: 'scroll-icon',
            animation: 'fade',
            scrollImg: {

                active: true,
                type: 'background'
            }
        });

        // close all tooltips when clicking anywhere on the page
        jq(document).on("click",function(event) {

            // hide all active tooltips
            if (jq(event.target).data('role') != 'ajax-tooltip-button') {
                jq('[data-role=tooltip-wrapper]').hide();
            }

            //remove all clones
            jq( ".chalet-tooltip-content.clone" ).remove();

        });

        body.on('click', '[data-role="ajax-tooltip"]', function(event) {

            event.preventDefault();

            var el      = jq(this);
            var retries = (undefined === el.data('retries') ? 0 : parseInt(el.data('retries'), 10));
            var wrapper = el.find('[data-role=tooltip-wrapper]');

            if (jq(event.target).hasClass("close")) {

                // close button: hide all active tooltips
                jq('[data-role=tooltip-wrapper]').hide();

            } else {
                if( wrapper.is(':visible') ) {

                    if (jq(event.target).hasClass("fi-info")) {

                        // click on opened i-icon: hide all active tooltips
                        jq('[data-role=tooltip-wrapper]').hide();

                    }

                } else {

                    // hide all other active tooltips
                    jq('[data-role=tooltip-wrapper]').hide();


                   if(jq(el).hasClass("table-tooltip") == false) {
                        wrapper.show();
                   }

                }

                el.data('retries', retries + 1);

                if (true !== el.data('cached') && retries < 3) {

                    jq.ajax({

                        type: 'get',
                        url: el.data('url'),
                        success: function(data) {
                            el.data('cached', true).removeClass('loading').find('[data-role="tooltip-content"]').html(data);
                            showTooltip(el);
                        }
                    });
                } else {
                    showTooltip(el);
                }

                //show default or cloned tooltip
                function showTooltip(el) {
                    //move to wrapper, of clone tooltip if tooltip is positoned in table
                    if(jq(el).hasClass("table-tooltip")) {
                        //clone the wrapper
                        var clone = jq(wrapper).clone().addClass("clone").appendTo(".inner-wrap");
                        var position = jq(el).offset();
                        clone.css( "left", position.left - 350);
                        clone.css( "top", position.top);
                        clone.show();
                        scrollToMakeVisible(clone);

                    }else {
                        scrollToMakeVisible(wrapper);
                    }
                }
            }

        });



        // var to prevent unwanted clickthrough to accpage when clicking a tooltip-icon
        var clickthrough_to_accpage = true;

        body.on('mouseenter', '[data-role="ajax-tooltip"]', function(event) {
            clickthrough_to_accpage = false;
        });

        body.on('mouseleave', '[data-role="ajax-tooltip"]', function(event) {
            clickthrough_to_accpage = true;
        });

        body.on('touchstart', '[data-role="ajax-tooltip"]', function(event) {
            clickthrough_to_accpage = false;
        });

        body.on('touchend', '[data-role="ajax-tooltip"]', function(event) {
            clickthrough_to_accpage = true;
        });

        // check if user wants to clickthrough to accpage or is clicking a tooltip-icon
        body.on('click', '[data-role="link-to-accpage"]', function(event) {
            if( clickthrough_to_accpage ) {

                var searchURL         = new URI(window.location);
                var accURL            = new URI(jq(this).attr("href"));
                var sendValues        = [];

                // arrival date (w = week)
                sendValues["w"] = searchURL.search(true)["w"];

                // number of persons (pe = persons)
                sendValues["pe"] = searchURL.search(true)["pe"];

                // current scroll height (scrolly)
                if (jq(window).scrollTop() > 0) {
                    sendValues["scrolly"] = jq(window).scrollTop();
                }

                // current search-and-book url
                sendValues["back"] = window.location.pathname + window.location.search;

                for (var key in sendValues) {
                    if (sendValues.hasOwnProperty(key)) {
                        if (typeof sendValues[key] != 'undefined') {
                            accURL.addQuery(key, sendValues[key]);
                        }
                    }
                }

                accURL.normalizeQuery();

                jq(this).attr("href", accURL);

                return true;

            } else {
                // click on tooltip-icon: no action
                event.preventDefault();
                return false;
            }
        });


        /**
         * function to scroll the screen vertically to make an element that is below the fold visible
         */
        function scrollToMakeVisible(element) {

            var offset = element.offset();
            var top = offset.top;
            var bottom = top + element.outerHeight();
            var bottom_of_screen = jq(window).scrollTop() + window.innerHeight;

            if (bottom>bottom_of_screen) {
                var scroll_to = jq(window).scrollTop() + (bottom - bottom_of_screen) + 10;
                jq('html, body').animate({scrollTop: scroll_to}, 500);
            }
        }

        body.on('click', '[data-role="toggle-filters"]', function(event) {

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

        body.on('click', '[data-role="closable-filter"]', function(event) {

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
         * changing week on type detail page will change the extra options
         */
        body.on('change', '[data-role="extra-options-week"]', function(event) {

            event.preventDefault();

            var uri = URI();

            uri.setQuery('w', jq(this).val());
            window.location.href = uri.toString();
        });

        body.on('click', '[data-role="external-popup"], [data-role="internal-popup"]', function(event) {

            event.preventDefault();

            var element = jq(this);
            window.open(element.data('uri'), '_blank', 'scrollbars=yes,width=' + element.data('width') + ',height=' + element.data('height'));
        });

        /**
         * Dynamic tooltips
         */
        body.on('mouseenter', '[data-tooltip]', function(event) {

            var element = jq(this);

            if (true === element.data('open')) {
                Foundation.libs.tooltip.showTip(element.data('selector'));
            } else {

                event.preventDefault();
            }
        });

        body.on('click', '[data-tooltip]', function() {

            var element = jq(this);
            var cache   = element.data('tooltip-cache') || null;
            var lock    = element.data('tooltip-lock')  || false;
            var dynamic = element.data('tooltip-dynamic') || false;

            if (true === lock) {
                return;
            }

            if (null === cache && true === dynamic) {

                element.data('tooltip-lock', true);

                jq.ajax({

                    url: element.data('tooltip-url'),
                    success: function(content) {

                        element.data('tooltip-cache', content).data('tooltip-lock', false);
                        jq('#' + element.data('selector')).html(content);
                    }
                });
            }
        });

        body.on('click', '[data-action="show-more"]', function(event) {

            event.preventDefault();
            var element = jq('[data-show-more-element="' + jq(this).data('element') + '"]');

            if (true === element.data('opened')) {
                // element.removeClass('shorten-for-mobile').data('opened', false);
                element.animate({height: '100px'}).data('opened', false);
            } else {
                // element.removeClass('shorten-for-mobile').data('opened', true);
                element.animate({height: element.prop('scrollHeight')}).data('opened', true);
            }
        });

        body.on('click', '[data-action="price-calculator-send-mail"]', function(event) {

            event.preventDefault();

            var displaySuccess = function() {

                jq('[data-role="error-message"][data-id="price-calculator"]').hide();
                jq('[data-role="success-message"][data-id="price-calculator"]').show();
                jq('[data-action="price-calculator-send-mail"]').hide();
            };

            var displayError   = function() {

                jq('[data-role="success-message"][data-id="price-calculator"]').hide();
                jq('[data-role="error-message"][data-id="price-calculator"]').show();
                jq('[data-action="price-calculator-send-mail"]').hide();
            };

            jq.ajax({

                type: 'post',
                url:  jq('form[name="step_three"]').attr('action'),
                data: jq('form[name="step_three"]').serialize(),
                success: function(data) {

                    if (data.type === 'success') {
                        displaySuccess();
                    } else {
                        displayError();
                    }
                },
                error: function() {
                    displayError();
                }
            });
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