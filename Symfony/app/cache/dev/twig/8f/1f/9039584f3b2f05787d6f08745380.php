<?php

/* SpicySiteBundle:Site:video_mini.html.twig */
class __TwigTemplate_8f1f9039584f3b2f05787d6f08745380 extends Twig_Template
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
        echo "<div class=\"miniApercuVideo\">
    <h2>        
        <p class=\"miniArtisteVideo\">
        ";
        // line 4
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "artistes"));
        $context['_iterated'] = false;
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
        foreach ($context['_seq'] as $context["_key"] => $context["artiste"]) {
            // line 5
            echo "        
            ";
            // line 6
            if ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "last")) {
                // line 7
                echo "            <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artiste", array("id" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "id"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
                echo "</a>
            ";
            } elseif ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "first")) {
                // line 9
                echo "            <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artiste", array("id" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "id"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
                echo "</a> &
            ";
            } else {
                // line 11
                echo "            <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artiste", array("id" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "id"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
                echo "</a> ,
            ";
            }
            // line 13
            echo "        
        ";
            $context['_iterated'] = true;
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        if (!$context['_iterated']) {
            // line 15
            echo "            <p>Inconnu</p>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['artiste'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 17
        echo "        </p>
        <a href=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "slug"))), "html", null, true);
        echo "\">
            <p class=\"miniTitreVideo\">";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "titre"), "html", null, true);
        echo "</p>
        </a>
    </h2>
    <a href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "slug"))), "html", null, true);
        echo "\">
        <!--<div class=\"miniImgYoutube\" style=\"background-image:url(http://img.youtube.com/vi/";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "url"), "html", null, true);
        echo "/2.jpg); \" ></div>-->
        <p class=\"miniImgYoutube\" style=\"background-image:url(http://img.youtube.com/vi/";
        // line 24
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "url"), "html", null, true);
        echo "/0.jpg)\"></p>
        <!--<div class=\"miniImgYoutube\" >
            <img src=\"http://img.youtube.com/vi/";
        // line 26
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "url"), "html", null, true);
        echo "/0.jpg\" border=\"0\" />
        </div>-->
    </a>
</div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:video_mini.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  118 => 26,  109 => 23,  95 => 18,  63 => 11,  55 => 9,  45 => 6,  24 => 4,  19 => 1,  130 => 50,  127 => 49,  122 => 44,  119 => 43,  113 => 24,  108 => 14,  100 => 6,  97 => 5,  90 => 49,  85 => 15,  83 => 43,  71 => 13,  62 => 28,  58 => 27,  54 => 26,  49 => 24,  38 => 13,  31 => 8,  23 => 1,  47 => 7,  42 => 5,  40 => 17,  105 => 22,  99 => 19,  92 => 17,  76 => 15,  57 => 14,  46 => 7,  43 => 6,  37 => 7,  32 => 4,  29 => 5,);
    }
}
