window.Chalet = (function(ns, Routing, jq, _, undefined) {
    'use strict';

    ns.Autocomplete = {

        types: {

            TYPE_HOME: 'home',
            TYPE_SEARCH_BOOK: 'search-book'
        },

        entities: {

            ENTITY_COUNTRY: 'country',
            ENTITY_REGION: 'region',
            ENTITY_PLACE: 'place',
            ENTITY_ACCOMMODATION: 'accommodation',
            ENTITY_FREESEARCH: 'freesearch'
        },

        input: null,
        limit: 5,
        results: [],
        resultsContainer: null,
        currentTerm: null,
        debounce: 250,

        url: function(term, limit) {
            return Routing.generate('autocomplete', {term: term, limit: limit});
        },

        countUrl: function(params) {
            return Routing.generate('search_count') + '?' + params;
        },

        initialize: function(options) {

            options = jq.extend({

                input: ns.Autocomplete.input,
                limit: ns.Autocomplete.limit,
                resultsContainer: ns.Autocomplete.resultsContainer

            }, options);

            ns.Autocomplete.input            = jq(options.input);
            ns.Autocomplete.limit            = options.limit;
            ns.Autocomplete.resultsContainer = jq(options.resultsContainer);
            ns.Autocomplete.type             = ns.Autocomplete.input.data('type');

            ns.Autocomplete.events.bind();
        },

        events: {

            rebind: function() {

                ns.Autocomplete.resultsContainer = jq(ns.Autocomplete.resultsContainer.selector);
                ns.Autocomplete.input            = jq(ns.Autocomplete.input.selector);
            },

            bind: function() {

                ns.Autocomplete.events.change();
                ns.Autocomplete.events.click();
                ns.Autocomplete.events.clear();
            },

            change: function() {

                jq('body').on('focus', ns.Autocomplete.input.selector, function(event) {

                    var element = jq(this).addClass('active');
                    var term    = element.val();

                    if (term === '') {
                        element.attr('placeholder', element.data('focus-text'));
                    }
                });

                jq('body').on('blur', ns.Autocomplete.input.selector, function(event) {

                    var element = jq(this).removeClass('active');
                    var term    = element.val();

                    if (term === '') {
                        element.attr('placeholder', element.data('default-placeholder'));
                    }
                });

                jq('body').on('keydown', ns.Autocomplete.input.selector, function(event) {

                    // arrows used
                    if ([13, 38, 40].indexOf((event.keyCode || event.which)) > -1) {

                        event.preventDefault();
                        ns.Autocomplete.arrows.handle(event);

                        return;
                    }
                });

                jq('body').on('keydown', ns.Autocomplete.input.selector, _.debounce(function(event) {

                    // other keys used
                    if ([13, 38, 40].indexOf((event.keyCode || event.which)) === -1) {

                        event.preventDefault();
                        var term = event.target.value;

                        if (ns.Autocomplete.currentTerm === term || term === '') {

                            if (term === '') {

                                ns.Autocomplete.currentTerm = '';
                                ns.Autocomplete.resultsContainer.empty();
                                ns.Autocomplete.count('');
                            }

                            return;
                        }

                        ns.Autocomplete.currentTerm = term;
                        ns.Autocomplete.request(term, ns.Autocomplete.limit);
                    }

                }, ns.Autocomplete.debounce));

                jq('body').on('keydown', ns.Autocomplete.input.selector, function(event) {

                    var input = jq(this);
                    input.data('fs', input.val());
                });

                jq('body').on('click', '[data-role="search-simple"]', function(event) {

                    event.preventDefault();

                    var link  = jq(this);
                    var uri   = URI(link.attr('href'));
                    var input = jq(ns.Autocomplete.input.selector);

                    uri.removeQuery('fs');

                    if (input.data('fs') != '' && input.data('fs') != null && input.data('fs') !== undefined) {
                        uri.setQuery('fs', input.data('fs'));
                    }

                    return window.location.href = uri.toString();
                });

                if (ns.Autocomplete.type === ns.Autocomplete.types.TYPE_HOME) {

                    jq('body').on('change', '[data-role="choose-weekend-home"]', function(event) {

                        event.preventDefault();

                        var weekend = event.target.value;
                        ns.Autocomplete.actions.home.weekend(weekend);
                    });

                    jq('body').on('change', '[data-role="choose-persons-home"]', function(event) {

                        event.preventDefault();

                        var persons = event.target.value;
                        ns.Autocomplete.actions.home.persons(persons);
                    });
                }
            },

            click: function() {

                jq('body').on('click', '[data-role="autocomplete-result"]', function(event) {

                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    event.preventDefault();

                    var locale  = ns.get('app')['locale'];
                    var element = jq(this);
                    var data    = {

                        entity: element.data('entity'),
                        id:     element.data('id'),
                        label:  element.data('label')
                    };

                    jq(ns.Autocomplete.input.selector).data('fs', '');

                    switch (ns.Autocomplete.type) {

                        case ns.Autocomplete.types.TYPE_HOME:
                            ns.Autocomplete.actions.home.click(data);
                            break;

                        case ns.Autocomplete.types.TYPE_SEARCH_BOOK:
                            ns.Autocomplete.actions.searchBook.click(data);
                            break;
                    }
                });
            },

            clear: function() {

                jq('body').on('focus', ns.Autocomplete.input.selector, function(event) {
                    ns.Autocomplete.resultsContainer.show();
                });

                jq('body').on('click', '*', function(event) {

                    if (event.target.getAttribute('data-role') !== 'autocomplete-result' && event.target !== ns.Autocomplete.input.get(0)) {
                        ns.Autocomplete.resultsContainer.hide();
                    }
                });

                jq(document).on('keyup', function(event) {

                    if ((event.keyCode || event.which) === 27) {
                        ns.Autocomplete.resultsContainer.hide();
                    }
                });
            }
        },

        arrows: {

            position: 0,
            total: 0,
            event: null,
            initialize: function() {

                ns.Autocomplete.arrows.position = 0;
                ns.Autocomplete.arrows.total    = ns.Autocomplete.results.length;
                ns.Autocomplete.arrows.event    = null;
            },
            handle: function(event) {

                ns.Autocomplete.arrows.event = event;

                switch ((event.keyCode || event.which)) {

                    case 13:
                        ns.Autocomplete.arrows.enter();
                    break;

                    case 38:
                        ns.Autocomplete.arrows.up();
                    break;

                    case 40:
                        ns.Autocomplete.arrows.down();
                    break;
                }

                jq('[data-role="autocomplete-result"]').removeClass('active');

                if (ns.Autocomplete.arrows.position > 0) {
                    jq('[data-role="autocomplete-result"]').eq(ns.Autocomplete.arrows.position - 1).addClass('active');
                }
            },
            up: function() {

                if ((ns.Autocomplete.arrows.position - 1) >= 0) {
                    ns.Autocomplete.arrows.position -= 1;
                }
            },
            down: function() {

                if ((ns.Autocomplete.arrows.position + 1) <= ns.Autocomplete.arrows.total) {
                    ns.Autocomplete.arrows.position += 1;
                }
            },

            enter: function() {

                if (ns.Autocomplete.arrows.position > 0) {

                    jq('[data-role="autocomplete-result"]').eq(ns.Autocomplete.arrows.position - 1).trigger('click');
                    jq(ns.Autocomplete.input.selector).blur();
                    ns.Autocomplete.arrows.initialize();

                } else {

                    var data    = {

                        entity: ns.Autocomplete.entities.ENTITY_FREESEARCH,
                        label:  jq('[data-role="autocomplete-query"]').val()

                    };

                    ns.Autocomplete.actions.searchBook.click(data);

                }
            }
        },

        actions: {

            home: {

                click: function(data) {

                    var link = jq('[data-role="search-simple"]');
                    var uri  = URI(link.attr('href'));

                    uri.removeQuery('c[]');
                    uri.removeQuery('r[]');
                    uri.removeQuery('pl[]');
                    uri.removeQuery('a[]');
                    uri.removeQuery('t[]');

                    switch (data.entity) {

                        case ns.Autocomplete.entities.ENTITY_COUNTRY:

                            uri.setQuery('c[]', data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_REGION:

                            uri.setQuery('r[]', data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_PLACE:

                            uri.setQuery('pl[]', data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_ACCOMMODATION:

                            uri.setQuery('a[]', data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_FREESEARCH:

                            uri.removeQuery('fs');
                            uri.setQuery('fs', data.label);
                            jq(ns.Autocomplete.input.selector).data('fs', data.label);

                            break;
                    }

                    link.attr('href', uri.toString());
                    ns.Autocomplete.input.val(data.label);
                    ns.Autocomplete.resultsContainer.hide();

                    ns.Autocomplete.count(uri.query());
                },

                weekend: function(weekend) {

                    var link = jq('[data-role="search-simple"]');
                    var uri  = URI(link.attr('href'));

                    uri.removeQuery('w');

                    if (weekend != '') {
                        uri.setQuery('w', weekend);
                    }

                    link.attr('href', uri.toString());
                    ns.Autocomplete.count(uri.query());
                },

                persons: function(persons) {

                    var link = jq('[data-role="search-simple"]');
                    var uri  = URI(link.attr('href'));

                    uri.removeQuery('pe');

                    if (persons != '') {
                        uri.setQuery('pe', persons);
                    }

                    link.attr('href', uri.toString());
                    ns.Autocomplete.count(uri.query());
                }
            },

            searchBook: {

                click: function(data) {

                    switch (data.entity) {

                        case ns.Autocomplete.entities.ENTITY_COUNTRY:

                            ns.Search.filters.addCountry(data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_REGION:

                            ns.Search.filters.addRegion(data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_PLACE:

                            ns.Search.filters.addPlace(data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_ACCOMMODATION:

                            ns.Search.filters.addAccommodation(data.id);
                            break;

                        case ns.Autocomplete.entities.ENTITY_FREESEARCH:

                            ns.Search.filters.setFreesearch(data.label);
                            jq(ns.Autocomplete.input.selector).data('fs', data.label);

                            break;
                    }

                    ns.Search.actions.search();
                    ns.Autocomplete.input.val('').attr('placeholder', 'Voeg nog een bestemming toe');
                    ns.Autocomplete.resultsContainer.empty();
                }
            },
        },

        request: function(term, limit) {

            jq.ajax({

                url: ns.Autocomplete.url(term, limit),

                success: function(data) {

                    ns.Autocomplete.results = data;
                    ns.Autocomplete.arrows.initialize();
                    ns.Autocomplete.views.render();
                },

                error: function() {}
            });
        },

        count: function(params) {

            if (ns.Autocomplete.type === ns.Autocomplete.types.TYPE_HOME) {

                ns.Autocomplete.views.showLoader();

                jq.ajax({

                    url: ns.Autocomplete.countUrl(params),
                    success: function(data) {
                        ns.Autocomplete.views.updateCount(data.count);
                    },

                    error: function() {}
                });
            }
        },

        views: {

            render: function() {

                var results = ns.Autocomplete.results;
                var total   = results.length;

                if (total === 0) {

                    ns.Autocomplete.currentTerm = '';
                    ns.Autocomplete.resultsContainer.html('');
                    return;
                }

                var ul = document.createElement('ul');

                for (var i = 0; i < total; i++) {
                    ul.appendChild(ns.Autocomplete.views.result(results[i]));
                }

                ns.Autocomplete.resultsContainer.html(ul).show();
            },

            updateCount: function(count) {

                jq('[data-role="loading-text"]').addClass('hide');
                jq('[data-role="show-results"]').show();
                jq('[data-role="results-count"]').text(count);

                //change results label with singular or plural text
                if(count == 0) {
                    jq('.show-label').hide();
                    jq('.results-label').text(jq('.results-label').data('plural'));
                }else if (count == 1){
                    jq('.results-label').text(jq('.results-label').data('singular'));
                }else {
                    jq('.results-label').text(jq('.results-label').data('plural'));
                    jq('.show-label').show();
                }
            },

            showLoader: function() {

                jq('[data-role="show-results"]').hide();
                jq('[data-role="loading-text"]').removeClass('hide');
            },

            result: function(result) {

                var li        = document.createElement('li');
                var entities  = ns.Autocomplete.entities;
                var locale    = ns.get('app')['locale'];
                var name      = (undefined !== result['name'] ? (jq.type(result['name']) === 'string' ? result['name'] : result['name'][locale]) : '');
                var term      = (undefined !== result['search_term'] ? (jq.type(result['search_term']) === 'string' ? result['search_term'] : result['search_term'][locale]) : '');
                var tag       = '';

                switch (result.type) {

                    case entities.ENTITY_COUNTRY:

                        li.className = 'country';
                        li.setAttribute('data-entity', entities.ENTITY_COUNTRY);
                        li.setAttribute('data-id', term);

                        tag += '<i class="fi-flag"></i> ';

                    break;

                    case entities.ENTITY_REGION:

                        li.className = 'region';
                        li.setAttribute('data-entity', entities.ENTITY_REGION);
                        li.setAttribute('data-id', term);

                        tag += '<i class="sprite sprite-black-pistes chalets-icon-box"></i> ';

                    break;

                    case entities.ENTITY_PLACE:

                        li.className = 'place';
                        li.setAttribute('data-entity', entities.ENTITY_PLACE);
                        li.setAttribute('data-id', term);

                        tag += '<i class="fi-marker"></i> ';

                    break;

                    case entities.ENTITY_ACCOMMODATION:

                        li.className = 'accommodation';
                        li.setAttribute('data-entity', entities.ENTITY_ACCOMMODATION);
                        li.setAttribute('data-id', result['type_id']);

                        tag += '<i class="fi-home"></i> ';

                    break;

                    case entities.ENTITY_FREESEARCH:

                        li.className = 'accommodation';
                        li.setAttribute('data-entity', entities.ENTITY_FREESEARCH);
                        li.setAttribute('data-id', result['type_id']);

                        tag += '<i class="fi-home"></i> ';

                    break;
                }

                var span = document.createElement('span');
                span.textContent = name;

                li.setAttribute('data-role', 'autocomplete-result');
                li.setAttribute('data-label', name);
                li.innerHTML = tag;
                li.appendChild(span);

                return li;
            }
        }
    };

    return ns;

}(window.Chalet || {}, Routing, jQuery, _));