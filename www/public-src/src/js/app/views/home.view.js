define([
    'jquery',
    'backbone',
    '../views/register.view'
], function($, Backbone, RegisterView) {
    'use strict';
    
    function log(message) {
        console.log('[ HOME.VIEW ]--> ' + message);
    }
    log('loaded');

    var HomeView = Backbone.View.extend({
        el: $('#home-view'), 
        events: {
            'click #show-register-button': 'showRegister',
            'click #login-button': 'login',
            'click #new-sum-button': 'newSum'
        },
        
        initializa: function() {
            
        },
        
        showRegister: function() {
            log('showRegister');
            
            var view = new RegisterView();
            view.render();
            
            //listen for view remove event
            var homeView = this;
            view.on('hidden', function(){
                log('RegisterView hidden');
                homeView.show();
            });
            
            this.hide();
        },
                
        hide: function() {
            this.$el.css({"opacity": 0});
        },
                
        show: function() {
            this.$el.css({"opacity": 1});
        },
        
        login: function() {
            log('login');
        },
                
        newSum: function() {
            log('newSum');
        }
    });

    return HomeView;
});
