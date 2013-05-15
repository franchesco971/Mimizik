<?php

/* SpicySiteBundle:Site:indexSuite.html.twig */
class __TwigTemplate_64325c5f9e5a5bbaf3e70dc9b481b8a0 extends Twig_Template
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
        echo "<h3>Les autres vidéos</h3>
";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["autresVideos"]) ? $context["autresVideos"] : $this->getContext($context, "autresVideos")));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["video"]) {
            // line 3
            echo "    ";
            $this->env->loadTemplate("SpicySiteBundle:Site:video_mini.html.twig")->display($context);
            echo "    
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['video'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 5
        echo "<div class=\"pagination\">
    <ul>
      ";
        // line 8
        echo "      ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nombrePage"]) ? $context["nombrePage"] : $this->getContext($context, "nombrePage"))));
        foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
            // line 9
            echo "        <li";
            if (((isset($context["p"]) ? $context["p"] : $this->getContext($context, "p")) == (isset($context["page"]) ? $context["page"] : $this->getContext($context, "page")))) {
                echo " class=\"active\"";
            }
            echo ">
          <a href=\"";
            // line 10
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_homepage", array("page" => (isset($context["p"]) ? $context["p"] : $this->getContext($context, "p")))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["p"]) ? $context["p"] : $this->getContext($context, "p")), "html", null, true);
            echo "</a>
        </li>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 13
        echo "    </ul>
</div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:indexSuite.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 10,  65 => 9,  60 => 8,  56 => 5,  39 => 3,  22 => 2,  118 => 26,  109 => 23,  95 => 18,  63 => 11,  55 => 9,  45 => 6,  24 => 4,  19 => 1,  130 => 50,  127 => 49,  122 => 44,  119 => 43,  113 => 24,  108 => 14,  100 => 6,  97 => 5,  90 => 49,  85 => 15,  83 => 13,  71 => 13,  62 => 28,  58 => 27,  54 => 26,  49 => 24,  38 => 13,  31 => 8,  23 => 1,  47 => 7,  42 => 5,  40 => 17,  105 => 22,  99 => 19,  92 => 17,  76 => 15,  57 => 14,  46 => 7,  43 => 6,  37 => 7,  32 => 4,  29 => 5,);
    }
}
