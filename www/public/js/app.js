define([
    'jquery',
    'app/views/home.view',
    'app/router'
], function($, HomeView, Router){
    function log(message) {
        console.log('[ APP ]--> ' + message);
    }
    log('loaded');
    
    /**
     * Adds "plugins" to default libs.
     * 
     */
    function plugins() {
        log('plugins');
        
        /**
         * Returns form data as object.
         * Returns false if $element is not a jquery form object.
         * 
         * @author Alberto Miranda <b3rt.js@gmail.com>
         * @param {object} $element jquery form object
         * @returns {object|Boolean}
         */
        _.data = function($element) {
            if (!$element.is('form')) return false;
            
            var obj = {};
            _.each($element.serializeArray(), function(item){ 
                obj[item.name] = item.value; 
            });
            return obj;
        };
    }
    
    /**
     * App initialization.
     * Creates home view, attach base events and add app routes.
     */
    function init() {
       log('init');
       plugins();
       var router = new Router();
       var view = new HomeView();
       view.show();
       return view;
    }
    
    //public interface
    return {
        init: init
    };
});