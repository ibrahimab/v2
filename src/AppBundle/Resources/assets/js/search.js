window.Chalet = (function(ns, jq, undefined) {
    'use strict';

    ns.Search = {

        container: null,

        initialize: function(filters) {

            ns.Search.filters.filters = filters || ns.Search.filters.filters;
            ns.Search.events.bind();
            ns.Search.setContainer();
        },

        setContainer: function() {
            ns.Search.container = jq('[data-role="search-results"]');
        },

        events: {

            bind: function() {

                var body = jq('body');
                body.on('click', '[data-role="change-filter"]', ns.Search.events.change);
                body.on('click', '[data-role="remove-filter"]', ns.Search.events.remove);
                body.on('click', '[data-role="remove-filters"]', ns.Search.events.clear);
            },

            change: function(event) {

                event.preventDefault();
                var element = jq(this);

                if (element.data('action') === 'remove') {

                    ns.Search.filters.remove(element.data('filter'), element.data('filter-value'), element.data('filter-multi') === true);
                    element.data('action', 'add').attr('data-action', 'add');

                } else {

                    ns.Search.filters.add(element.data('filter'), element.data('filter-value'), element.data('filter-multi') === true);
                    element.data('action', 'remove').attr('data-action', 'remove');

                    if (true !== element.data('filter-multi')) {
                        jq('[data-filter="' + element.data('filter') + '"]').not(element).data('action', 'add').attr('data-action', 'add');
                    }
                }

                ns.Search.actions.search();
            },

            remove: function(event) {

                event.preventDefault();
                var element = jq(this);

                jq('[data-role="change-filter"][data-filter="' + element.data('filter') + '"][data-filter-value="' + element.data('filter-value') + '"][data-action="remove"]').trigger('click');
                ns.Search.actions.search();
            },

            clear: function(event) {

                event.preventDefault();

                ns.Search.filters.clear();
                ns.Search.actions.search();

                // resetting all the input fields
                resetStyledInput();
                jq('[data-role="change-filter"]').data('action', 'add').attr('data-action', 'add');
            }
        },

        actions: {

            url: function(url) {

                var filters = ns.Search.filters.active();
                var total   = filters.length;
                var uri     = URI(url);

                // remove all the filters from url
                for (var i = 1; i <= 10; i++) {

                    for (var j = 0; j <= 10; j++) {
                        uri.removeQuery('f[' + i + '][' + j + ']');
                    }

                    uri.removeQuery('f[' + i + ']');
                }

                for (var i in filters) {

                    if (filters.hasOwnProperty(i)) {

                        if (jq.isArray(filters[i])) {

                            var total = filters[i].length;

                            for (var j = 0; j < total; j++) {
                                uri.setQuery('f[' + i + '][' + j + ']', filters[i][j]);
                            }

                        } else {
                            uri.setQuery('f[' + i + ']', filters[i]);
                        }
                    }
                }

                uri.removeQuery('p');

                return uri;
            },

            loader: function() {
                ns.Search.container.prepend('<div class="loading"></div>');
            },

            search: function() {

                ns.Search.actions.loader();
                var url = ns.Search.actions.url(Routing.generate('search_' + ns.get('app')['locale']));

                jq.ajax({

                    url: url.toString(),
                    data: url.query(),
                    success: function(data) {

                        ns.Search.container.replaceWith(data);
                        ns.Search.setContainer();

                        if (window.history.pushState) {
                            window.history.pushState({path: url}, '', url);
                        }

                        var save = ns.Search.actions.url(Routing.generate('save_search_' + ns.get('app')['locale']));
                        jq('[data-role="save-search"]').attr('href', save.toString());
                    }
                });
            }
        },

        filters: {

            filters: {},

            active: function() {
                return ns.Search.filters.filters;
            },

            add: function(filter, value, multi) {

                if (multi === true) {

                    if (undefined === ns.Search.filters.filters[filter]) {
                        ns.Search.filters.filters[filter] = [];
                    }

                    ns.Search.filters.filters[filter].push(value);

                } else {
                    ns.Search.filters.filters[filter] = value;
                }
            },

            remove: function(filter, value, multi) {

                if (multi === true) {

                    var values = ns.Search.filters.filters[filter];
                    if (undefined === values) {
                        return;
                    }

                    var total  = values.length;

                    for (var i = 0; i < total; i++) {

                        if (values[i] === value) {
                            console.log('b', ns.Search.filters.filters[filter]);
                            ns.Search.filters.filters[filter].splice(i, 1);
                            console.log('a', ns.Search.filters.filters[filter]);
                        }
                    }

                    if (ns.Search.filters.filters[filter].length === 0) {
                        delete ns.Search.filters.filters[filter];
                    }

                } else {
                    delete ns.Search.filters.filters[filter];
                }
            },

            clear: function() {
                ns.Search.filters.filters = {};
            }
        },
    };

    return ns;


}(window.Chalet || {}, jQuery));