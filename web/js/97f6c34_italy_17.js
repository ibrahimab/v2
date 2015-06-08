window.Chalet            = window.Chalet      || {};
window.Chalet.Maps       = window.Chalet.Maps || {};
window.Chalet.Maps.Italy = (function(ns, maps, italy, jq, undefined) {
    'use strict';

    italy  = {

        Map: null,

        settings: {

            jqvmapElementId: 'jqvmap1',
            mapId:           'it_mill_en',
            mapPrefix:       'IT',
            disabledIds:     {},
            disabledRegions: {},
            backgroundColor: '#ffffff',
            borderColor:     '#ffffff',
            normalColor:     '#ffd38f',
            hoverColor:      '#ff9900',
            selectedColor:   '#ff9900',
            disabledColor:   '#e0d1cc',
            enableZoom:      false,
            borderOpacity:   1,
            customPin:       {

                id:     230,
                code:   142,
                prefix: 'IT',
                html:   ''
            }
        },

        initialize: function(selector) {

            italy.settings['disabledIds'] = ns.get()['app']['country']['disabledRegions'];

            for (var i in this.settings['disabledIds']) {
                italy.settings['disabledRegions'][italy.settings['mapPrefix'] + '-' + italy.settings['disabledIds'][i]] = italy.settings['disabledColor'];
            }

            var customPinData     = italy.settings['customPin'];
            customPinData['html'] = jq('[data-role="pin-html"][data-pin-id="' + customPinData['prefix'] + '-' + customPinData['id'] + '"]').html();

            var customPin = {};
            customPin[customPinData['prefix'] + '-' + customPinData['code']] = customPinData['html'];

            italy.Map = jq(selector);
            italy.Map.vectorMap({

                map:             italy.settings['mapId'],
                backgroundColor: italy.settings['backgroundColor'],
        		borderColor:     '#ffffff',
        		color:           italy.settings['normalColor'],
        		hoverColor:      italy.settings['hoverColor'],
        		selectedColor:   italy.settings['selectedColor'],
        		borderOpacity:   italy.settings['borderOpacity'],
        		enableZoom:      italy.settings['enableZoom'],
                pins:            customPin,
                onLabelShow:     italy.events.onLabelShow,
                onRegionOut:     italy.events.onRegionOut,
                onRegionClick:   italy.events.onRegionClick,
                onRegionOver:    italy.events.onRegionOver
            });

            italy.Map.vectorMap('set', 'colors', italy.settings['disabledRegions']);
            italy.drawCustomPin(customPinData);
            italy.events.onRegionListHover(italy.Map, italy.settings);

            return italy;
        },

        drawCustomPin: function(pin) {

            var settings         = italy.settings;
            var map              = italy.Map;
            var gardameer        = jq('[data-role="region"][data-region-id="' + pin['id'] + '"]');
            var gardameerData    = {};
            var customPin        = jq('#' + settings['jqvmapElementId'] + '_' + pin['prefix'] + '-' + pin['code'] + '_pin');
            var customPinTooltip = jq('[data-role="pin-tooltip"]');

            if (gardameer.length > 0) {

                gardameerData['name']       = gardameer.data('region-name');
                gardameerData['typesCount'] = gardameer.data('region-types-count');
                gardameerData['label']      = (gardameerData['name'] + ': ' + gardameerData['typesCount'] + ' ' + 'vakantiehui' + (gardameerData['typesCount'] === 1 ? 's' : 'zen'));

                customPinTooltip.text(gardameerData['label']);
                customPin.delegate('[data-role="pin-content"]', 'mouseover mouseout', function(event) {

                    if (event.type === 'mouseover') {

                        customPinTooltip.text(gardameerData['label']).show();
                        jq('[data-role="region"][data-region-id="' + pin['id'] + '"]').find('a').addClass('hovered-region');

                    } else {

                        customPinTooltip.hide();
                        jq('[data-role="region"][data-region-id="' + pin['id'] + '"]').find('a').removeClass('hovered-region');
                    }
                });

                customPin.mousemove(function(event) {

                    if (customPinTooltip.is(':visible')) {

                        var left = event.pageX - 15 - jq('[data-role="pin-tooltip"]').width();
                        var top  = event.pageY - 15 - jq('[data-role="pin-tooltip"]').height();

                        if(left < 0)
                           left = event.pageX + 15;
                        if(top < 0)
                            top = event.pageY + 15;

                        // @TODO:
                        // ibo fixed this issue because of the position: relative; from DMG
                        // still to do: fix this in master repository of jqvmap
                        top = top - 525;

                        customPinTooltip.css({
                            left: left,
                            top: top
                      });
                    }
                });

                customPin.click(function(event) {

                    var destination      = jq('[data-role="region"][data-region-id="' + pin['id'] + '"]').find('a').attr('href');
                    window.location.href = destination;
                });
            }
        },
        events: {

            onRegionListHover: function(map, settings) {

                jq('[data-role="region-list"] a').hover(

                    function() {

                        var region = jq(this).parents('li');
                        var id     = region.data('region-id');

                        map.find('#' + settings['jqvmapElementId'] + '_' + settings['mapPrefix'] + '-' + id).attr('fill', settings['hoverColor']);

                        if (id === settings['customPin']['id']) {
                            map.find('#' + settings['jqvmapElementId'] + '_' + settings['mapPrefix'] + '-' + settings['customPin']['code'] + '_pin [data-role="pin-content"]').addClass('hover');
                        }
                    },

                    function() {

                        var region = jq(this).parents('li');
                        var id     = region.data('region-id');

                        map.find('#' + settings['jqvmapElementId'] + '_' + settings['mapPrefix'] + '-' + id).attr('fill', settings['normalColor']);

                        if (id === settings['customPin']['id']) {
                            map.find('#' + settings['jqvmapElementId'] + '_' + settings['mapPrefix'] + '-' + settings['customPin']['code'] + '_pin [data-role="pin-content"]').removeClass('hover');
                        }
                    }
                );
            },

            onLabelShow: function(event, label, code) {

                var id     = code.replace(italy.settings['mapPrefix'] + '-', '');
                var region = jq('[data-role="region"][data-region-id="' + id + '"]');

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

                jq('[data-role="region"][data-region-id="' + code.replace(italy.settings['mapPrefix'] + '-', '') + '"]')
                    .find('a')
                    .removeClass('hovered-region');
            },

            onRegionClick: function(event, code, region) {

                if (italy.settings['disabledRegions'].hasOwnProperty(code)) {

                    event.preventDefault();

                } else {

                    var id               = code.replace(italy.settings['mapPrefix'] + '-', '');
                    var region           = jq('[data-role="region"][data-region-id="' + id + '"]');
                    var destination      = region.find('a').attr('href');

                    window.location.href = destination;
                }
            },

            onRegionOver: function(event, code) {

                if (italy.settings['disabledRegions'].hasOwnProperty(code)) {

                    italy.Map.css('cursor', 'default');
                    event.preventDefault();

                } else {

                    italy.Map.css('cursor', 'pointer');
                    jq('[data-role="region"][data-region-id="' + code.replace(italy.settings['mapPrefix'] + '-', '') + '"]')
                        .find('a')
                        .addClass('hovered-region');
                }
            }
        }
    };
    
    return italy;

})(window.Chalet, window.Chalet.Maps, window.Chalet.Maps.Italy || {}, jQuery);