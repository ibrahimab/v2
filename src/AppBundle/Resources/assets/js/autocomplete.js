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
                
                jq('body').on('keyup', ns.Autocomplete.input.selector, _.debounce(function(event) {
                    
                    var term = event.target.value;
                    
                    if (ns.Autocomplete.currentTerm === term || term === '') {
                        return;
                    }
                    
                    ns.Autocomplete.currentTerm = term;
                    ns.Autocomplete.request(term, ns.Autocomplete.limit);
                    
                }, ns.Autocomplete.debounce));
            },
            
            click: function() {
                
                jq('body').on('click', '[data-role="autocomplete-result"]', function(event) {
                    
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
            }
        },
        
        actions: {
            
            home: {
                
                click: function(data) {
                    
                    console.log(data);
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
                    ns.Autocomplete.input.val('');
                    ns.Autocomplete.resultsContainer.empty();
                }   
            },
        },
        
        request: function(term, limit) {
            
            jq.ajax({
                
                url: ns.Autocomplete.url(term, limit),
                
                success: function(data) {
                    
                    ns.Autocomplete.results = data;
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
                    
                    ns.Autocomplete.resultsContainer.html('');
                    return;
                }
                    
                var ul      = document.createElement('ul');
                
                for (var i = 0; i < total; i++) {
                    ul.appendChild(ns.Autocomplete.views.result(results[i]));
                }
                
                ns.Autocomplete.resultsContainer.html(ul);
            },
            
            result: function(result) {
                
                var li        = document.createElement('li');
                var entities  = ns.Autocomplete.entities;
                var locale    = ns.get('app')['locale'];
                var name      = (jq.type(result['name']) === 'string' ? result['name'] : result['name'][locale]);

                switch (result.type) {
                    
                    case entities.ENTITY_COUNTRY:
                        
                        li.className = 'country';
                        li.setAttribute('data-entity', entities.ENTITY_COUNTRY);
                        li.setAttribute('data-id', name);
                        
                    break;
                    
                    case entities.ENTITY_REGION:
                        
                        li.className = 'region';
                        li.setAttribute('data-entity', entities.ENTITY_REGION);
                        li.setAttribute('data-id', name);
                        
                    break;
                    
                    case entities.ENTITY_PLACE:
                        
                        li.className = 'place';
                        li.setAttribute('data-entity', entities.ENTITY_PLACE);
                        li.setAttribute('data-id', name);
                        
                    break;
                        
                    case entities.ENTITY_ACCOMMODATION:
                        
                        li.className = 'accommodation';
                        li.setAttribute('data-entity', entities.ENTITY_ACCOMMODATION);
                        li.setAttribute('data-id', result['type_id']);
                        
                    break;
                }
                
                li.setAttribute('data-role', 'autocomplete-result');
                li.setAttribute('data-label', name);
                li.textContent = name;
                
                return li;
            }
        }
    };
    
    return ns;
    
}(window.Chalet || {}, Routing, jQuery, _));