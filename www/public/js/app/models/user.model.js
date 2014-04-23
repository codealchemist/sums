define([
    'backbone'
], function(Backbone) {
    'use strict';

    var UserModel = Backbone.Model.extend({
        idAttribute: '_id',
        defaults: {
            "_id" : null,
            "name" : null,
            "email" : null,
            "password": null,
            "sums" : [],
            "collaboratesOn" : [],
            "createdDate" : null,
            "lastLoginDate" : null,
            "loginToken" : null
        },
        url: '//api.sums.dev/users/',
        parse: function(response) {
            return response.value;
        },
        fetch: function(options) {
            options = options || {};
            options.dataType = options.dataType || 'jsonp';
            return Backbone.Model.prototype.fetch.apply(this, [options]);
        }
    });

    return UserModel;

});