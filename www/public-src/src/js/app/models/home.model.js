define([
    'backbone'
], function(Backbone) {
    'use strict';

    var HomeModel = Backbone.Model.extend({
        url: function() {
            return '//api.sums.dev/users/';
        },
        parse: function(response) {
            return response.value;
        },
        fetch: function(options) {
            options = options || {};
            options.dataType = options.dataType || 'jsonp';
            return Backbone.Model.prototype.fetch.apply(this, [options]);
        }
    });

    return HomeModel;

});