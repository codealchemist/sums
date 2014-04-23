define(['handlebars'], function(Handlebars) {

this["JST"] = this["JST"] || {};

this["JST"]["loggedin.hbs"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"\">\n    <span class=\"\">"
    + escapeExpression(((stack1 = ((stack1 = (depth0 && depth0.user)),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>\n    <input id=\"logout-button\" type=\"button\" value=\"logout\" />\n</div>";
  return buffer;
  });

this["JST"]["login.hbs"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  


  return "<div class=\"\">\n    <input id=\"email\" type=\"text\" placeholder=\"email\" />\n    <input id=\"password\" type=\"password\" placeholder=\"password\" />\n    <input id=\"login-button\" type=\"button\" value=\"login\" />\n</div>";
  });

this["JST"]["register.hbs"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  


  return "<section id=\"register-view\" class=\"panel panel-default smooth\">\n    <div class=\"panel-heading\">\n        <h3 class=\"panel-title\">Register</h3>\n    </div>\n    \n    <div class=\"panel-body\">\n        <form id=\"register-form\" class=\"navbar-form navbar-left expand\">\n            <div class=\"form-group expand\">\n                <div class=\"input-group input-group-lg expand\">\n                    <span class=\"input-group-addon\">Name</span>\n                    <input id=\"name\" name=\"name\" type=\"text\" class=\"form-control\" placeholder=\"For example: John Malkovich\">\n                </div>\n                <div class=\"input-group input-group-lg expand\">\n                    <span class=\"input-group-addon\">Email</span>\n                    <input id=\"email\" name=\"email\" type=\"email\" class=\"form-control\" placeholder=\"Your email address\">\n                </div>\n                <div class=\"input-group input-group-lg expand\">\n                    <span class=\"input-group-addon\">Password</span>\n                    <input id=\"password\" name=\"password\" type=\"password\" class=\"form-control\" placeholder=\"Password\">\n                </div>\n                <div class=\"input-group input-group-lg expand\">\n                    <span class=\"input-group-addon\">Password</span>\n                    <input id=\"password2\" name=\"password2\" type=\"password\" class=\"form-control\" placeholder=\"Please, repeat your password here\">\n                </div>\n            </div>\n        </form>\n        \n        <button id=\"cancel-button\" type=\"submit\" class=\"btn btn-default\">Cancel</button>\n        <button id=\"register-button\" type=\"submit\" class=\"btn btn-primary\">Register</button>\n    </div>\n</section>\n";
  });

return this["JST"];

});