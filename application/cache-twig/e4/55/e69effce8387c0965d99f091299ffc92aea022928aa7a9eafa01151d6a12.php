<?php

/* index.twig */
class __TwigTemplate_e455e69effce8387c0965d99f091299ffc92aea022928aa7a9eafa01151d6a12 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("__layouts/public.twig");

        $this->blocks = array(
        );
    }

    protected function doGetParent(array $context)
    {
        return "__layouts/public.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array ();
    }
}
