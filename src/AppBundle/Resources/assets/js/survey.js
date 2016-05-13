(function(jq) {
    'use strict';

    jq(function() {

        var i = 0;

        jq('body').on('click', '[data-role="fetch-surveys"]', function(event) {

            event.preventDefault();

            var element = jq(this);
            var fetched = jq('[data-role="fetched-reviews"]');
            var label   = jq('[data-role="fetch-surveys-label"]');
            var icon    = jq('[data-role="fetch-surveys-icon"]');

            if (element.data('all-fetched') === true) {

                fetched.toggle('slideDown');

                if (fetched.is(':visible')) {

                    label.text('Verberg beoordelingen');
                    icon.addClass('close');

                } else {

                    label.text('Toon beoordelingen');
                    icon.removeClass('close');
                }

                return;
            }

            jq.ajax({

                url: Routing.generate('surveys'),
                type: 'get',
                data: {typeId: Chalet.get('app').route.params.typeId, offset: element.data('offset')},
                success: function(data) {

                    if (data.type === 'success') {

                        jq('[data-role="fetched-reviews"]').append('<div id="page-' + i + '" style="display:none;">' + data.html + '</div>');
                        jq('#page-' + i).slideDown();

                        i++;

                        element.data('offset', data.offset);

                        if (data.offset > data.total) {

                            // all surveys has been fetched
                            element.data('all-fetched', true);
                            label.text('Verberg beoordelingen');
                            icon.addClass('close');
                        }
                    }
                }
            });
        });
    });

})(jQuery);