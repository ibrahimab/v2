window.Chalet = (function(ns, jq, undefined) {
    'use strict';

    ns.Search = {

        initialize: function(filters) {

            ns.Search.filters.filters = filters;
            ns.Search.events.bind();
        },

        events: {

            bind: function() {

                var body = jq('body');
                body.on('click', '[data-role="change-filter"]', ns.Search.events.change);
            },

            change: function(event) {

                event.preventDefault();
                var element = jq(this);

                if (element.data('action') === 'remove') {

                    ns.Search.filters.remove(element.data('filter'), element.data('filter-value'), element.data('filter-multi') === true);
                    element.data('action', 'add');

                } else {

                    ns.Search.filters.add(element.data('filter'), element.data('filter-value'), element.data('filter-multi') === true);
                    element.data('action', 'remove');
                }
            }
        },

        actions: {

            search: function() {

                var data = window.location.search.replace('?', '');
                // data = ns.Search.filters.active();
                console.log(data);
                console.log(encodeURIComponent.encode(ns.Search.filters.active()));
                jq({

                    url: Routing.generate('search_' + ns.get('app')['locale']),
                    data: data,
                    success: function(data) {
                        console.log(data);
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
                    var total  = values.length;

                    for (var i = 0; i < total; i++) {

                        if (values[i] === value) {
                            ns.Search.filters.filters[filter].splice(i, 1);
                        }
                    }

                    if (ns.Search.filters.filters[filter].length === 0) {
                        ns.Search.filters.filters.splice(filter, 1);
                    }

                } else {
                    ns.Search.filters.filters.splice(filter, 1);
                }
            },

            clear: function() {
                ns.Search.filters.filters = {};
            }
        },
    };

    return ns;


}(window.Chalet || {}, jQuery));