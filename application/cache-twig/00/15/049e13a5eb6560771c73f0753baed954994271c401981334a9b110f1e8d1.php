<?php

/* __layouts/public.twig */
class __TwigTemplate_0015049e13a5eb6560771c73f0753baed954994271c401981334a9b110f1e8d1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<!--[if lt IE 7]>      <html class=\"no-js lt-ie9 lt-ie8 lt-ie7\"> <![endif]-->
<!--[if IE 7]>         <html class=\"no-js lt-ie9 lt-ie8\"> <![endif]-->
<!--[if IE 8]>         <html class=\"no-js lt-ie9\"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class=\"no-js\" lang=\"en\"> <!--<![endif]-->
    <head>
        <meta charset=\"utf-8\">
        <!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\"><![endif]-->
        <title>";
        // line 10
        echo twig_escape_filter($this->env, (isset($context["appName"]) ? $context["appName"] : null), "html", null, true);
        echo "</title>
        <meta name=\"description\" content=\"Collaborative totals\">
        <meta name=\"viewport\" content=\"width=device-width\">

        <link rel=\"stylesheet\" href=\"css/backbone-boilerplate.min-0.2.2.css\">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class=\"chromeframe\">You are using an <strong>outdated</strong> browser. Please <a href=\"http://browsehappy.com/\">upgrade your browser</a> or <a href=\"http://www.google.com/chromeframe/?redirect=true\">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <section class=\"main\" role=\"main\">
            ";
        // line 22
        $this->displayBlock('content', $context, $blocks);
        // line 24
        echo "        </section>

        <script type=\"text/javascript\" data-main=\"js/main\" src=\"vendor/requirejs/require.js\"></script>
    </body>
</html>


";
    }

    // line 22
    public function block_content($context, array $blocks = array())
    {
        // line 23
        echo "            ";
    }

    public function getTemplateName()
    {
        return "__layouts/public.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  62 => 23,  59 => 22,  48 => 24,  46 => 22,  31 => 10,  20 => 1,);
    }
}
