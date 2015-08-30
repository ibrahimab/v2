window.Chalet = (function(ns, jq, _, undefined) {
    'use strict';

    ns.Social = {

        initialize: function() {
            ns.Social.events.bind();
        },

        events: {

            bind: function() {

                ns.Social.events.facebook();
                ns.Social.events.twitter();
            },

            facebook: function() {

                jq('body').on('click', '[data-role="social-share"][data-social-source="facebook"]', function(event) {

                    event.preventDefault();
                    ns.Social.share.facebook(jq(this).data('social-url'));
                });
            },

            twitter: function() {

                jq('body').on('click', '[data-role="share"][data-social-social="twitter"]', function(event) {

                    event.preventDefault();

                    var element = jq(this);
                    ns.Social.share.twitter(element.data('social-url'), element.data('social-via'), element.data('social-tweet'));
                });
            }
        },

        share: {

            facebook: function(url) {
                return ns.Social.popup('https://www.facebook.com/sharer.php?u=' + url, 600, 330);
            },

            twitter: function(url, via, tweet) {
                return ns.Social.popup('https://twitter.com/share?url=' + url + '&text=' + tweet);
            }
        },

        popup: function(url, width, height) {
            return window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=' + height + ',width=' + width);
        }
    };

    return ns;

}(window.Chalet || {}, jQuery, _));