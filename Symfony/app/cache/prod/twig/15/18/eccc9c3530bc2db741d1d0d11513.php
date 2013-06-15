<?php

/* SpicySiteBundle:Site:news.html.twig */
class __TwigTemplate_1518eccc9c3530bc2db741d1d0d11513 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<h3>News</h3>
";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["news"]) ? $context["news"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["new"]) {
            // line 3
            echo "    <p>";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["new"]) ? $context["new"] : null), "date"), "Y-m-d H:i:s"), "html", null, true);
            echo " - <a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["new"]) ? $context["new"] : null), "url"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["new"]) ? $context["new"] : null), "titre"), "html", null, true);
            echo "</p></a>
    <p></p>

";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 7
            echo "    pas de resultat
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['new'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 9
        echo "
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:news.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 9,  41 => 7,  27 => 3,  83 => 13,  72 => 10,  65 => 9,  60 => 8,  56 => 5,  39 => 3,  22 => 2,  118 => 26,  113 => 24,  109 => 23,  105 => 22,  99 => 19,  95 => 18,  92 => 17,  85 => 15,  71 => 13,  63 => 11,  55 => 9,  45 => 6,  42 => 5,  24 => 4,  19 => 1,  114 => 31,  106 => 26,  100 => 23,  93 => 18,  77 => 16,  58 => 15,  47 => 7,  44 => 7,  37 => 4,  32 => 3,  29 => 2,);
    }
}
