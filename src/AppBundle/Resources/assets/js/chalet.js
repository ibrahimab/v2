/**
 * Chalet Object
 *
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 */
window.Chalet = (function(ns, undefined) {
    // creating new 'strict' namespace to keep global pollution
    // to a minimum. Code inside here can reference the namespace using the 'ns' object
    'use strict';

    // @property public
    ns.attributes = {};

    // @method import - importing attributes
    ns.import = function(attributes) {
        ns.attributes  = attributes;
    };

    // getting reference to internal storage
    // for easy access
    ns.get = function(attribute, _default) {
        
        if (undefined === attribute) {
            return ns.attributes;
        }
        
        if (undefined === ns.attributes[attribute] && undefined !== _default) {
            return _default;
        }
        
        if (undefined === ns.attributes[attribute] && undefined === _default) {
            throw new Error('Undefined attribute requested');
        }
        
        return ns.attributes[attribute];
    };
    
    return ns;

}(window.Chalet || {}));