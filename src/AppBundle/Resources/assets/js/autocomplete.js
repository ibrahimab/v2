window.Chalet = (function(ns, Routing, jq, _, undefined) {
    'use strict';
    
    ns.Autocomplete = {
        
        input: null,
        limit: 5,
        results: [],
        resultsContainer: null,
        currentTerm: null,
        debounce: 250,
        kinds: {
            
            KIND_COUNTRY: 'country',
            KIND_REGION:  'region',
            KIND_PLACE:   'place'
        },
        
        url: function(term, limit) {
            return Routing.generate('autocomplete', {term: term, limit: limit});
        },
    
        initialize: function(input, limit, resultsContainer) {
        
            ns.Autocomplete.input            = jq(input);
            ns.Autocomplete.limit            = limit;
            ns.Autocomplete.resultsContainer = jq(resultsContainer);
            
            ns.Autocomplete.events.bind(); 
        },
        
        events: {
            
            bind: function() {
                
                ns.Autocomplete.events.change();
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
            
            clear: function() {}
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
                var a         = document.createElement('a');
                var span      = document.createElement('span');
                
                var kinds     = ns.Autocomplete.kinds;
                var className = '';
                var locale    = ns.get('app')['locale'];
                var name      = '';
                
                if (jq.type(result['name']) === 'string') {
                    name = result['name'];
                } else {
                    name = result['name'][locale];
                }
                
                switch (result.type) {
                    
                    case kinds.KIND_COUNTRY:
                        
                        a.setAttribute('data-role', 'autocomplete-result');
                        a.setAttribute('data-entity', 'country');
                        a.setAttribute('data-id', name);
                        
                        li.className = 'country';
                        
                    break;
                    
                    case kinds.KIND_REGION:
                        
                        a.setAttribute('data-role', 'autocomplete-result');
                        a.setAttribute('data-entity', 'region');
                        a.setAttribute('data-id', name);
                        
                        li.className = 'region';
                        
                    break;
                    
                    case kinds.KIND_PLACE:
                        
                        a.setAttribute('data-role', 'autocomplete-result');
                        a.setAttribute('data-entity', 'place');
                        a.setAttribute('data-id', name);
                        
                        li.className = 'place';
                        
                    break;
                        
                    case kinds.KIND_ACCOMMODATON:
                        
                        a.setAttribute('data-role', 'autocomplete-result');
                        a.setAttribute('data-entity', 'accommodation');
                        a.setAttribute('data-id', result['id']);
                        
                        li.className = 'accommodation';
                        
                    break;
                }
                
                // append all the nodes
                span.textContent = name;
                a.appendChild(span);
                li.appendChild(a);
                
                return li;
            }
        }
    };
    
    return ns;
    
}(window.Chalet || {}, Routing, jQuery, _));