window.Chalet = (function(ns, jq, _, undefined) {
    'use strict';

    ns.Search = {

        container: null,

        initialize: function(filters) {

            ns.Search.filters.filters = filters || ns.Search.filters.filters;
            ns.Search.events.bind();
            ns.Search.setContainer();

            var custom = ns.get('app')['filters'] === undefined ? {} : ns.get('app')['filters']['custom'];

            ns.Search.filters.countries      = custom['countries']      || [];
            ns.Search.filters.regions        = custom['regions']        || [];
            ns.Search.filters.places         = custom['places']         || [];
            ns.Search.filters.accommodations = custom['accommodations'] || [];
            ns.Search.filters.bedrooms       = custom['bedrooms']       || null;
            ns.Search.filters.bathrooms      = custom['bathrooms']      || null;
        },

        setContainer: function() {
            ns.Search.container = jq('[data-role="search-container"]');
        },

        events: {

            bind: function() {

                var body = jq('body');
                body.on('click', '[data-role="change-filter"]',   ns.Search.events.change);
                body.on('click', '[data-role="remove-filter"]',   ns.Search.events.remove);
                body.on('click', '[data-role="remove-filters"]',  ns.Search.events.clear);
                body.on('click', '[data-role="paginate-search"]', ns.Search.events.paginate);

                body.on('click', '[data-role="remove-country-filter"]', ns.Search.events.removeCustom.country);
                body.on('click', '[data-role="remove-region-filter"]', ns.Search.events.removeCustom.region);
                body.on('click', '[data-role="remove-place-filter"]', ns.Search.events.removeCustom.place);
                body.on('click', '[data-role="remove-accommodation-filter"]', ns.Search.events.removeCustom.accommodation);

                body.on('change', '[data-role="change-bedrooms"]', ns.Search.events.formChanges.bedrooms);
                body.on('change', '[data-role="change-bathrooms"]', ns.Search.events.formChanges.bathrooms);
                body.on('change', '[data-role="change-weekend"]', ns.Search.events.formChanges.weekend);

                body.on('click', '[data-role="save-search"]', ns.Search.actions.save);

                body.on('change', '[data-role="choose-persons"]', ns.Search.events.formChanges.persons);
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

            removeCustom: {

                country: function(event) {

                    event.preventDefault();
                    var element = jq(this);

                    ns.Search.filters.removeCountry(element.data('id'));
                    ns.Search.actions.search();
                },
                region: function(event) {

                    event.preventDefault();
                    var element = jq(this);

                    ns.Search.filters.removeRegion(element.data('id'));
                    ns.Search.actions.search();
                },
                place: function(event) {

                    event.preventDefault();
                    var element = jq(this);

                    ns.Search.filters.removePlace(element.data('id'));
                    ns.Search.actions.search();
                },
                accommodation: function(event) {

                    event.preventDefault();
                    var element = jq(this);

                    ns.Search.filters.removeAccommodation(element.data('id'));
                    ns.Search.actions.search();
                }
            },

            formChanges: {

                bedrooms: function(event) {

                    event.preventDefault();
                    var val = parseInt(jq(this).val(), 10);

                    if (val === 0) {
                        ns.Search.filters.removeBedrooms();
                    } else {
                        ns.Search.filters.setBedrooms(val);
                    }

                    ns.Search.actions.search();
                },

                bathrooms: function(event) {

                    event.preventDefault();
                    var val = parseInt(jq(this).val(), 10);

                    if (val === 0) {
                        ns.Search.filters.removeBathrooms();
                    } else {
                        ns.Search.filters.setBathrooms(val);
                    }

                    ns.Search.actions.search();
                },

                persons: function(event) {

                    event.preventDefault();
                    var val = parseInt(jq(this).val(), 10);

                    if (val === 0) {
                        ns.Search.filters.removePersons();
                    } else {
                        ns.Search.filters.setPersons(val);
                    }

                    ns.Search.actions.search();
                },

                weekend: function(event) {

                    event.preventDefault();
                    var val = jq(this).val();

                    if (val === '') {
                        ns.Search.filters.removePersons();
                    } else {
                        ns.Search.filters.setWeekend(val);
                    }

                    ns.Search.actions.search();
                }
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

                if (null !== ns.Search.filters.bedrooms) {

                    uri.removeQuery('be');
                    uri.setQuery('be', ns.Search.filters.bedrooms);
                }

                if (null !== ns.Search.filters.bathrooms) {

                    uri.removeQuery('ba');
                    uri.setQuery('ba', ns.Search.filters.bathrooms);
                }

                if (null !== ns.Search.filters.persons) {

                    uri.removeQuery('pe');
                    uri.setQuery('pe', ns.Search.filters.persons);
                }

                if (null !== ns.Search.filters.weekend) {

                    uri.removeQuery('w');
                    uri.setQuery('w', ns.Search.filters.weekend);
                }

                return uri;
            },

            loader: function() {

                ns.Search.container.find('[data-role="search-results-container"]').prepend('<div class="loading" style="height: 100px;"></div>').find('[data-role="search-results"]').hide();
                jq('[data-role="search-filter-box"]').prepend('<div class="loading"></div>');
            },

            save: function(event) {

                event.preventDefault();
                if (false === jq.isEmptyObject(ns.Search.filters.filters)) {

                    var save = ns.Search.actions.url(Routing.generate('save_search'));

                    jq('[data-role="saved-item"]').animate({color: '#d50d3b', borderColor: '#d50d3b' }, 500)
                                                  .delay(1000)
                                                  .animate({color: '#1a3761', borderColor: '#d9d9d9' }, 500);

                    jq.ajax({

                        type: 'post',
                        url: save.toString(),
                        success: function(data) {

                            var count = jq('[data-role="searches-count"]');
                            count.text(parseInt(count.text()) + 1);
                        }
                    });
                }
            },

            search: function(page) {

                ns.Search.actions.loader();

                var url = ns.Search.actions.url(Routing.generate('search_' + ns.get('app')['locale']), page);

                jq.ajax({

                    url: url.toString(),
                    success: function(data) {

                        ns.Search.container.replaceWith(data);
                        ns.Search.setContainer();

                        if (window.history.pushState) {
                            window.history.pushState({path: url}, '', url);
                        }

                        // recalculate checkboxes
                        window.recalculateStyledInput();
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

            bedrooms: null,

            bathrooms: null,

            persons: null,

            weekend: null,

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

                ns.Search.filters.filters        = {};
                ns.Search.filters.countries      = [];
                ns.Search.filters.regions        = [];
                ns.Search.filters.places         = [];
                ns.Search.filters.accommodations = [];
                ns.Search.filters.bedrooms       = null;
                ns.Search.filters.bathrooms      = null;
                ns.Search.filters.persons        = null;
                ns.Search.filters.weekend        = null;
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

                ns.Search.filters.accommodations = _.reject(ns.Search.filters.accommodations, function(item) { return parseInt(item, 10) === parseInt(accommodation, 10); });
                return ns.Search.filters.accommodations;
            },

            setBedrooms: function(bedrooms) {

                ns.Search.filters.bedrooms = bedrooms;
                return ns.Search.filters.bedrooms;
            },

            removeBedrooms: function() {
                ns.Search.filters.bedrooms = null;
            },

            setBathrooms: function(bathrooms) {

                ns.Search.filters.bathrooms = bathrooms;
                return ns.Search.filters.bathrooms;
            },

            removeBathrooms: function() {
                ns.Search.filters.bathrooms = null;
            },

            removePersons: function() {
                ns.Search.filters.persons = null;
            },

            setPersons: function(persons) {
                ns.Search.filters.persons = persons;
            },

            removeWeekend: function() {
                ns.Search.filters.weekend = null;
            },

            setWeekend: function(weekend) {
                ns.Search.filters.weekend = weekend;
            }
        },
    };

    return ns;


}(window.Chalet || {}, jQuery, _));