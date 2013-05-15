<?php

/* SpicySiteBundle:Admin:index.html.twig */
class __TwigTemplate_815a679ba4b05631410399caf249cd92 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SpicySiteBundle::layout_admin.html.twig");

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SpicySiteBundle::layout_admin.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
    <p><a href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_admin_home_video"), "html", null, true);
        echo "\">Vidéo</a></p>
    <p><a href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_admin_home_artiste"), "html", null, true);
        echo "\">Artiste</a></p>
    <p><a href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_admin_home_genre_musical"), "html", null, true);
        echo "\">Genre musical</a></p>
    <p><a href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_admin_home_type_video"), "html", null, true);
        echo "\">Type de video</a></p>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Admin:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 8,  44 => 7,  40 => 6,  36 => 5,  31 => 4,  28 => 3,);
    }
}
