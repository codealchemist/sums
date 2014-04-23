define([
    'jquery',
    'backbone',
    '../models/user.model',
    'templateregistry'
], function($, Backbone, UserModel, JST) {
    'use strict';
    
    function log(message) {
        console.log('[ REGISTER.VIEW ]--> ' + message);
    }
    log('loaded');

    var RegisterView = Backbone.View.extend({
        el: $('body'),
        template: JST['register.hbs'],
        events: {
            'click #register-button': 'register',
            'click #cancel-button': 'hide'
        },
        
        initialize: function() {
            
        },
        
        render: function() {
            log('render');
            var html = this.template();
            $('body').append(html);
            this.$el = $('#register-view');
            
            var view = this;
            setTimeout(function(){
                view.show();
            }, 500);
        },
        
        /**
         * Registers new user.
         */
        register: function() {
            log('register');
            
            var $form = $('#register-form');
            var data = _.data($form);
            
            //TODO: implement automatic validation based on rules and display errors in the form
            if ( !_.str.trim(data.name) ) {
                alert('ERROR: name cannot be empty.');
                return false;
            }
            if ( !_.str.trim(data.email) ) {
                alert('ERROR: email cannot be empty.');
                return false;
            }
            if (data.password !== data.password2) {
                alert('ERROR: passwords must match!');
                return false;
            }
            
            var user = new UserModel(data);
            user.save(null, {
                success: function(user) {
                    log('SIGNUP OK!');
                    console.log(user);
                },
                error: function(response) {
                    log('SIGNUP ERROR:');
                    console.log(response);
                }
            });
            
            //TODO: construct model and save it
        },
        
        show: function() {
            log('show');
            this.$el.css({"opacity": 1});
            $('#name').focus();
        },
        
        hide: function() {
            log('hide');
            this.$el.css({"opacity": 0});
            this.trigger('hidden');
            
            var view = this;
            setTimeout(function(){
                view.$el.remove();
            }, 1000);
            //TODO: fire hide event which should be listened to in HomeView to show home view again
        }
    });

    return RegisterView;
});
