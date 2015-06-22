window.Chalet = (function(ns, jq, _, undefined) {
    'use strict';

    ns.Search = {

        container: null,

        initialize: function(filters) {

            ns.Search.filters.filters = filters || ns.Search.filters.filters;
            ns.Search.events.bind();
            ns.Search.setContainer();
            
            ns.Search.filters.countries      = ns.get('app')['filters']['custom']['countries']      || [];
            ns.Search.filters.regions        = ns.get('app')['filters']['custom']['regions']        || [];
            ns.Search.filters.places         = ns.get('app')['filters']['custom']['places']         || [];
            ns.Search.filters.accommodations = ns.get('app')['filters']['custom']['accommodations'] || [];
        },

        setContainer: function() {
            ns.Search.container = jq('[data-role="search-results"]');
        },

        events: {

            bind: function() {

                var body = jq('body');
                body.on('click', '[data-role="change-filter"]',   ns.Search.events.change);
                body.on('click', '[data-role="remove-filter"]',   ns.Search.events.remove);
                body.on('click', '[data-role="remove-filters"]',  ns.Search.events.clear);
                body.on('click', '[data-role="paginate-search"]', ns.Search.events.paginate);
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
            },

            paginate: function(event) {

                event.preventDefault();
                ns.Search.actions.search(jq(this).data('page'));
            },
        },

        actions: {

            url: function(url, page) {

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

                if (undefined !== page) {
                    uri.setQuery('p', page);
                } else {
                    uri.removeQuery('p');
                }
                
                if (ns.Search.filters.countries.length > 0) {
                    
                    uri.removeQuery('c[]');
                    uri.setQuery('c[]', ns.Search.filters.countries);
                }
                
                if (ns.Search.filters.regions.length > 0) {
                    
                    uri.removeQuery('r[]');
                    uri.setQuery('r[]', ns.Search.filters.regions);
                }
                
                if (ns.Search.filters.places.length > 0) {
                    
                    uri.removeQuery('pl[]');
                    uri.setQuery('pl[]', ns.Search.filters.places);
                }
                
                if (ns.Search.filters.accommodations.length > 0) {
                    
                    uri.removeQuery('a[]');
                    uri.setQuery('a[]', ns.Search.filters.accommodations);
                }

                return uri;
            },

            loader: function() {
                ns.Search.container.prepend('<div class="loading"></div>');
            },

            search: function(page) {

                ns.Search.actions.loader();

                var url = ns.Search.actions.url(Routing.generate('search_' + ns.get('app')['locale']), page);
                console.log(url.toString());
                jq.ajax({

                    url: url.toString(),
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
            
            countries: [],
            
            regions: [],
            
            places: [],
            
            accommodations: [],

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
                            ns.Search.filters.filters[filter].splice(i, 1);
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
            },
            
            addCountry: function(country) {
                
                ns.Search.filters.removeCountry(country);
                ns.Search.filters.countries.push(country);
                return ns.Search.filters.countries;
            },
            
            removeCountry: function(country) {
                
                ns.Search.filters.countries = _.reject(ns.Search.filters.countries, function(item) { return item === country; });
                return ns.Search.filters.countries;
            },
            
            addRegion: function(region) {
                
                ns.Search.filters.removeRegion(region);
                console.log(ns.Search.filters.regions);
                ns.Search.filters.regions.push(region);
                console.log(ns.Search.filters.regions);
                return ns.Search.filters.regions;
            },
            
            removeRegion: function(region) {
                
                ns.Search.filters.regions = _.reject(ns.Search.filters.regions, function(item) { return item === region; });
                return ns.Search.filters.regions;
            },
            
            addPlace: function(place) {
                
                ns.Search.filters.removePlace(place);
                ns.Search.filters.places.push(place);
                return ns.Search.filters.places;
            },
            
            removePlace: function(place) {
                
                ns.Search.filters.places = _.reject(ns.Search.filters.places, function(item) { return item === place; });
                return ns.Search.filters.places;
            },
            
            addAccommodation: function(accommodation) {
                
                ns.Search.filters.removeAccommodation(accommodation);
                ns.Search.filters.accommodations.push(accommodation);
                return ns.Search.filters.accommodations;
            },
            
            removeAccommodation: function(accommodation) {
                
                ns.Search.filters.accommodations = _.reject(ns.Search.filters.accommodations, function(item) { return item === accommodation; });
                return ns.Search.filters.accommodations;
            }
        },
    };

    return ns;


}(window.Chalet || {}, jQuery, _));