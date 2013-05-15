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
        $context['_seq'] = twig_ensure_traversable((isset($context["news"]) ? $context["news"] : $this->getContext($context, "news")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["new"]) {
            // line 3
            echo "    <p>";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["new"]) ? $context["new"] : $this->getContext($context, "new")), "date"), "Y-m-d H:i:s"), "html", null, true);
            echo " - <a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["new"]) ? $context["new"] : $this->getContext($context, "new")), "url"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["new"]) ? $context["new"] : $this->getContext($context, "new")), "titre"), "html", null, true);
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
        return array (  48 => 9,  41 => 7,  27 => 3,  72 => 10,  65 => 9,  60 => 8,  56 => 5,  39 => 3,  22 => 2,  118 => 26,  109 => 23,  95 => 18,  63 => 11,  55 => 9,  45 => 6,  24 => 4,  19 => 1,  130 => 50,  127 => 49,  122 => 44,  119 => 43,  113 => 24,  108 => 14,  100 => 6,  97 => 5,  90 => 49,  85 => 15,  83 => 13,  71 => 13,  62 => 28,  58 => 27,  54 => 26,  49 => 24,  38 => 13,  31 => 8,  23 => 1,  47 => 7,  42 => 5,  40 => 17,  105 => 22,  99 => 19,  92 => 17,  76 => 15,  57 => 14,  46 => 7,  43 => 6,  37 => 7,  32 => 4,  29 => 5,);
    }
}
