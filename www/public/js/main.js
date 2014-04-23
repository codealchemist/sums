require.config({
    paths: {
        'backbone': '../vendor/backbone/backbone',
        'jquery': '../vendor/jquery/dist/jquery.min',
        'underscore': '../vendor/lodash/lodash.compat',
        'underscore.string': '../vendor/underscore.string/dist/underscore.string.min',
        'modernizr': '../vendor/modernizr/modernizr',
        'handlebars': '../vendor/handlebars/handlebars',
        'amplify': '../vendor/amplify/src/amplify',
        'bootstrap': '../vendor/bootstrap/bootstrap',
        'templateregistry': 'app/templates'
    },
    shim: {
        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        'modernizr': {
            exports: 'Modernizr'
        },
        'handlebars': {
            exports: 'Handlebars'
        },
        'amplify': {
            deps: ['jquery'],
            exports: 'amplify'
        },
        'bootstrap': {
            deps: ['jquery'],
            exports: 'bootstrap'
        },
        'underscore.string': {
            deps: ['underscore']
        }
    },
    waitSeconds: 30 //default = 7
});

require([
    'jquery',
    'app',
    'modernizr',
    'bootstrap',
    'underscore.string'
], function($, App) {
    'use strict';
    $('document').ready(function(){
        App.init();
    });
});
