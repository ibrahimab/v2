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
            ENTITY_ACCOMMODATION: 'accommodation'
        },

        input: null,
        limit: 5,
        results: [],
        resultsContainer: null,
        currentTerm: null,
        debounce: 250,
        focused: false,

        url: function(term, limit) {
            return Routing.generate('autocomplete', {term: term, limit: limit});
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

            bind: function() {

                ns.Autocomplete.events.change();
                ns.Autocomplete.events.click();
                ns.Autocomplete.events.clear();
            },

            change: function() {

                jq('body').on('focus', ns.Autocomplete.input.selector, function(event) {

                    var element = jq(this);
                    var term    = element.val();

                    if (term === '') {
                        element.attr('placeholder', element.data('focus-text'));
                    }
                    
                    ns.Autocomplete.focused = true;
                });

                jq('body').on('blur', ns.Autocomplete.input.selector, function(event) {

                    var element = jq(this);
                    var term    = element.val();

                    if (term === '') {
                        element.attr('placeholder', element.data('default-placeholder'));
                    }
                    
                    ns.Autocomplete.focused = true;
                });
                
                jq('body').on('keydown', ns.Autocomplete.input.selector, function(event) {
                    
                    if ([13, 38, 40].indexOf((event.keyCode || event.which)) > -1) {
                        
                        event.preventDefault();
                        ns.Autocomplete.arrows.handle(event);
                        
                        return;
                    }
                });
                
                jq('body').on('keypress', ns.Autocomplete.input.selector, _.debounce(function(event) {

                    if ([13, 38, 40].indexOf((event.keyCode || event.which)) === -1) {

                        event.preventDefault();
                        var term = event.target.value;

                        if (ns.Autocomplete.currentTerm === term || term === '') {

                            if (term === '') {

                                ns.Autocomplete.currentTerm = '';
                                ns.Autocomplete.resultsContainer.empty();
                            }

                            return;
                        }

                        ns.Autocomplete.currentTerm = term;
                        ns.Autocomplete.request(term, ns.Autocomplete.limit);
                    }

                }, ns.Autocomplete.debounce));
            },

            click: function() {

                jq('body').on('click', '[data-role="autocomplete-result"]', function(event) {

                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    event.preventDefault();

                    var element = jq(this);
                    var data    = {

                        entity: element.data('entity'),
                        id:     element.data('id'),
                        label:  element.data('label')
                    };

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
                    
                } else {
                    
                    ns.Autocomplete.arrows.position = 0;
                }
            },
            down: function() {
                
                if ((ns.Autocomplete.arrows.position + 1) <= ns.Autocomplete.arrows.total) {
                    
                    ns.Autocomplete.arrows.position += 1;
                    
                } else {
                    
                    ns.Autocomplete.arrows.position = 0;
                }
            },
            
            enter: function() {
                
                if (ns.Autocomplete.arrows.position > 0) {
                    
                    jq('[data-role="autocomplete-result"]').eq(ns.Autocomplete.arrows.position - 1).trigger('click');
                    jq(ns.Autocomplete.input.selector).blur();
                    ns.Autocomplete.arrows.initialize();
                }
            }
        },

        actions: {

            home: {

                click: function(data) {

                    var link = jq('[data-role="search-simple"]');
                    var uri  = URI(link.attr('href'));
                    uri.search('');

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
                    }


                    link.attr('href', uri.toString());
                    ns.Autocomplete.input.val(data.label);
                    ns.Autocomplete.resultsContainer.hide();
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

        views: {

            render: function() {

                var results = ns.Autocomplete.results;
                var total   = results.length;

                if (total === 0) {

                    ns.Autocomplete.currentTerm = '';
                    ns.Autocomplete.resultsContainer.html('');
                    return;
                }

                var ul      = document.createElement('ul');

                for (var i = 0; i < total; i++) {
                    ul.appendChild(ns.Autocomplete.views.result(results[i]));
                }

                ns.Autocomplete.resultsContainer.html(ul).show();
            },

            result: function(result) {

                var li        = document.createElement('li');
                var entities  = ns.Autocomplete.entities;
                var locale    = ns.get('app')['locale'];
                var name      = (jq.type(result['name']) === 'string' ? result['name'] : result['name'][locale]);
                var tag       = '';

                switch (result.type) {

                    case entities.ENTITY_COUNTRY:

                        li.className = 'country';
                        li.setAttribute('data-entity', entities.ENTITY_COUNTRY);
                        li.setAttribute('data-id', name);

                        tag += '<i class="fi-flag"></i> ';

                    break;

                    case entities.ENTITY_REGION:

                        li.className = 'region';
                        li.setAttribute('data-entity', entities.ENTITY_REGION);
                        li.setAttribute('data-id', name);

                        tag += '<i class="sprite sprite-black-pistes chalets-icon-box"></i> ';

                    break;

                    case entities.ENTITY_PLACE:

                        li.className = 'place';
                        li.setAttribute('data-entity', entities.ENTITY_PLACE);
                        li.setAttribute('data-id', name);

                        tag += '<i class="fi-marker"></i> ';

                    break;

                    case entities.ENTITY_ACCOMMODATION:

                        li.className = 'accommodation';
                        li.setAttribute('data-entity', entities.ENTITY_ACCOMMODATION);
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