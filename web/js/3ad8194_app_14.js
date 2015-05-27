(function(jq, Routing) {
    'use strict';
    
    // setting up scroll button for long pages
    jq(function() {
        
        // body element cache
        var body = jq('body');
        
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
         * This listener allows for collapsable lists
         */
        body.on('click', '[data-role="toggle-long-list"]', function(event) {
        
            event.preventDefault();
        
            var element = jq(this);
            var icon    = element.find('[data-role="toggle-icon-long-list"]');
            var list    = element.parents('ul[data-role="long-list"]');
        
            if (true === element.data('opened')) {
            
                element.data('opened', false);
                list.find('li:nth-child(n+4)').not(element).slideUp();
                icon.removeClass(icon.data('opened-icon')).addClass(icon.data('closed-icon'));
            
            } else {
            
                element.data('opened', true);
                list.find('li:nth-child(n+4)').not(element).slideDown();
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
        
        /**
         * This code handles the destinations map
         * It generates it via the jqvmap jQuery plugin
         * with some modifications to make it work in the new design
         * 
         * @TODO: refactor this so this code only gets loaded on destinations page
         */
        var italyMaps         = jq('[data-role="italy-maps"]');
        if (italyMaps.length > 0) {
        
            var disabledRegionIds = Chalet.get()['app']['country']['disabledRegions'];
            var disabledRegions   = {};
            var normalColor       = '#ffd38f';
            var hoverColor        = '#ff9900';
            var pinId             = 230;
            var pins              = {};
            pins['IT-142']        = jq('[data-role="pin-html"][data-pin-id="IT-' + pinId + '"]').html();
        
            for (var i in disabledRegionIds) {
                disabledRegions['IT-' + disabledRegionIds[i]] = '#e0d1cc';
            }
        
            italyMaps.vectorMap({
            
                map: 'it_mill_en',
    			backgroundColor: '#ffffff',
    			borderColor: '#ffffff',
    			color: normalColor,
    			hoverColor: hoverColor,
    			selectedColor: '#ff9900',
    			borderOpacity: 1,
    			enableZoom: false,
                pins: pins,
    			onLabelShow: function(event, label, code) {
                
                    var id         = code.replace('IT-', '');
                    var region     = jq('[data-role="region"][data-region-id="' + id + '"]');
                
                    if (region.length > 0) {
                
                        if (undefined === region.data('region-label')) {
                    
                            var name       = region.data('region-name');
                            var typesCount = region.data('region-types-count');
                
                            region.data('region-label', name + ': ' + typesCount + ' ' + 'vakantiehui' + (typesCount === 1 ? 's' : 'zen'));   
                        }
                
                        label.text(region.data('region-label'));
                    
                    } else {
                        return false;
                    }
    			},
    			onRegionOut: function(event, code, region) {
                
                    jq('[data-role="region"][data-region-id="' + code.replace('IT-', '') + '"]')
                        .find('a')
                        .removeClass('hovered-region');
    			},
                onRegionClick: function(event, code, region) {

                    if (disabledRegions.hasOwnProperty(code)) {
                    
                        event.preventDefault();
                    
                    } else {
                    
                        var id               = code.replace('IT-', '');
                        var region           = jq('[data-role="region"][data-region-id="' + id + '"]');
                        var destination      = region.find('a').attr('href');
                    
                        window.location.href = destination;
                    }
                },
                onRegionOver: function(event, code) {
                
                    if (disabledRegions.hasOwnProperty(code)) {
                
                        italyMaps.css('cursor', 'default');
                        event.preventDefault();
                
                    } else {
                    
                        italyMaps.css('cursor', 'pointer');
                        jq('[data-role="region"][data-region-id="' + code.replace('IT-', '') + '"]')
                            .find('a')
                            .addClass('hovered-region');
                    }
                }
            });

            italyMaps.vectorMap('set', 'colors', disabledRegions);
        
            jq('[data-role="region-list"] a').hover(
            
                function() {
                
                    var region = jq(this).parents('li');
                    var id     = region.data('region-id');
                
                    italyMaps.find('#jqvmap1_IT-' + id).attr('fill', hoverColor);
                },
                function() {
                
                    var region = jq(this).parents('li');
                    var id     = region.data('region-id');

                    italyMaps.find('#jqvmap1_IT-' + id).attr('fill', normalColor);
                }
            );
            
            var gardameer        = jq('[data-role="region"][data-region-id="' + pinId + '"]');
            var gardameerData    = {};
            var customPin        = jq('#jqvmap1_IT-142_pin');
            var customPinTooltip = jq('[data-role="pin-tooltip"]');
            
            if (gardameer.length > 0) {
                
                gardameerData['name']       = gardameer.data('region-name');
                gardameerData['typesCount'] = gardameer.data('region-types-count');
                gardameerData['label']      = (gardameerData['name'] + ': ' + gardameerData['typesCount'] + ' ' + 'vakantiehui' + (gardameerData['typesCount'] === 1 ? 's' : 'zen'));

                customPinTooltip.text(gardameerData['label']);
                customPin.delegate('[data-role="pin-content"]', 'mouseover mouseout', function(event) {
                    
                    if (event.type === 'mouseover') {
                        
                        customPinTooltip.text(gardameerData['label']).show();
                        jq('[data-role="region"][data-region-id="' + pinId + '"]').addClass('hovered-region');
                        
                    } else {
                        
                        customPinTooltip.hide();
                        jq('[data-role="region"][data-region-id="' + pinId + '"]').removeClass('hovered-region');
                    }
                });
                
                customPin.mousemove(function(event) {
                    
                    if (customPinTooltip.is(':visible')) {

                        var left = event.pageX - 15 - customPinTooltip.width();
                        var top  = event.pageY - 15 - customPinTooltip.height();

                        if(left < 0)
                           left = event.pageX + 15;
                        if(top < 0)
                            top = event.pageY + 15;

                        // @TODO:
                        // ibo fixed this issue because of the position: relative; from DMG
                        // still to do: fix this in master repository of jqvmap
                        top  = top - 600;
                        left = left - 100;
                        customPinTooltip.css({
                            
                            left: left,
                            top: top
                      });
                    }
                });
                
                customPin.click(function(event) {
                    
                    var destination      = jq('[data-role="region"][data-region-id="' + pinId + '"]').find('a').attr('href');
                    window.location.href = destination;
                });
            }
        }
    });
    
})(jQuery, Routing);