<?php

/* SpicySiteBundle:Site:retro.html.twig */
class __TwigTemplate_06948e9ff99bdcbc286d817a730980bf extends Twig_Template
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
        echo "
<h3>Rétro de la semaine</h3>
";
        // line 3
        if (array_key_exists("video", $context)) {
            // line 4
            echo "    ";
            $this->env->loadTemplate("SpicySiteBundle:Site:video_mini.html.twig")->display($context);
            // line 5
            echo "    <div class=\"description\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "description"), "html", null, true);
            echo "</div>
";
        } else {
            // line 7
            echo "    pas de resultat
";
        }
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:retro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 7,  28 => 5,  25 => 4,  23 => 3,  48 => 9,  41 => 7,  27 => 3,  83 => 13,  72 => 10,  65 => 9,  60 => 8,  56 => 5,  39 => 3,  22 => 2,  118 => 26,  113 => 24,  109 => 23,  105 => 22,  99 => 19,  95 => 18,  92 => 17,  85 => 15,  71 => 13,  63 => 11,  55 => 9,  45 => 6,  42 => 5,  24 => 4,  19 => 1,  114 => 31,  106 => 26,  100 => 23,  93 => 18,  77 => 16,  58 => 15,  47 => 7,  44 => 7,  37 => 4,  32 => 3,  29 => 2,);
    }
}
