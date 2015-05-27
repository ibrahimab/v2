if (typeof Object.create !== 'function') {

    Object.create = function(o, props) {

        function F() {};
        F.prototype = o;
        result = new F();

        if (typeof(props) === 'object') {

            for (prop in props) {

                if (props.hasOwnProperty(prop)) {
                    result[prop] = props[prop].value;
                }
            }
        }

        return result;
    };
}

/**
 * Chalet Object
 *
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 */
(function(Chalet, undefined) {
    // creating new 'strict' namespace to keep global pollution
    // to a minimum. Code inside here can reference the namespace using the 'ns' object
    'use strict';

    // @property public
    Chalet.attributes = {};

    // @method import - importing attributes
    Chalet.import = function(attributes) {
        this.attributes  = attributes;
    };

    // getting reference to internal storage
    // for easy access
    Chalet.get = function() {
        return this.attributes;
    };

})(window.Chalet = window.Chalet || {});