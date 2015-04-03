/**
 * Chalet Object
 *
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 */
(function(ns, undefined) {
    // creating new 'strict' namespace to keep global pollution
    // to a minimum. Code inside here can reference the namespace using the 'ns' object
    'use strict';
    
    // @property public
    ns.attributes = {};
    
    // @method import - importing attributes
    ns.import = function(attributes) {
        this.attributes  = attributes;
    };
    
    // getting reference to internal storage
    // for easy access
    ns.get = function() {
        return this.attributes;
    };
    
})(window.Chalet = window.Chalet || {});