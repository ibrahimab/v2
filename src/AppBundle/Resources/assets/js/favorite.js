window.Chalet = (function(ns, jq, _, undefined) {
    'use strict';

    ns.Favorite = {

        add: function(element) {

            element.data('disable', true);
            Chalet.Favorite.animate(element, 'add');

            jq.ajax({

                type: 'post',
                url: Routing.generate('save_favorite', {'typeId': element.data('id')}),
                success: function(data) {

                    element.data('disable', false);
                    Chalet.Favorite.increment();
                }
            });
        },

        remove: function(element) {

            element.data('disable', true);
            Chalet.Favorite.animate(element, 'remove');

            jq.ajax({

                type: 'post',
                url: Routing.generate('remove_favorite', {'typeId': element.data('id')}),
                success: function(data) {

                    element.data('disable', false);
                    Chalet.Favorite.decrement();
                }
            });
        },

        increment: function() {

            var count = jq('[data-role="favorites-count"]');
            count.text(parseInt(count.text()) + 1);
        },

        decrement: function() {

            var count = jq('[data-role="favorites-count"]');
            count.text(parseInt(count.text()) - 1);
        },

        animate: function(element, action) {

            var saved = jq('[data-role="favorite-saved"]');

            saved.animate({color: '#d50d3b', borderColor: '#d50d3b' }, 500)
                 .delay(1000)
                 .animate({color: '#1a3761', borderColor: '#d9d9d9' }, 500);

            if (action === 'add') {
                saved.addClass('active');
            } else {
                saved.removeClass('active');
            }

            element.toggleClass('active');
            element.toggleClass('added');
        }
    };

    return ns;

}(window.Chalet || {}, jQuery));