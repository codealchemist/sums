define([
    'backbone'
], function(Backbone) {

    var Router = Backbone.Router.extend({
        routes: {
            '/test': 'testRoute'
        },
        initialize: function() {
            Backbone.history.start();
        },
        testRoute: function(path) {
            console.log('--> TEST ROUTE, path: ' + path);
        }
    });

    return Router;
});
