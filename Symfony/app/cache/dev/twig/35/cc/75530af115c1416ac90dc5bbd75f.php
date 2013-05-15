<?php

/* SpicySiteBundle:Site:showArtiste.html.twig */
class __TwigTemplate_35cc75530af115c1416ac90dc5bbd75f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SpicySiteBundle::layout.html.twig");

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SpicySiteBundle::layout.html.twig";
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
    <h2>";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
        echo "</h2>
    <div>
        <p>Nom : ";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
        echo "</p>
        <p>Description : ";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "description"), "html", null, true);
        echo "</p>
    </div>
    <div>
         ";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["videos"]) ? $context["videos"] : $this->getContext($context, "videos")));
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
        foreach ($context['_seq'] as $context["_key"] => $context["video"]) {
            // line 12
            echo "            ";
            $this->env->loadTemplate("SpicySiteBundle:Site:video_mini.html.twig")->display($context);
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
            // line 14
            echo "            <p>Pas de vidéos de cette artiste</p>
         ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['video'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 16
        echo "    </div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:showArtiste.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 16,  85 => 14,  69 => 12,  51 => 11,  45 => 8,  41 => 7,  36 => 5,  31 => 4,  28 => 3,);
    }
}
