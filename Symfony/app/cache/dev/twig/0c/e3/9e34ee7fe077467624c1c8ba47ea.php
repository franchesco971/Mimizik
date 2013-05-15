<?php

/* SpicySiteBundle:Site:index.html.twig */
class __TwigTemplate_0ce39e34ee7fe077467624c1c8ba47ea extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SpicySiteBundle::layout.html.twig");

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
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

    // line 2
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 3
        echo "    <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("css/pagination.css"), "html", null, true);
        echo "\" type=\"text/css\" />
    ";
        // line 4
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
";
    }

    // line 6
    public function block_body($context, array $blocks = array())
    {
        // line 7
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
    <div id=\"lastVideos\" class=\"fond_transparent\">
        <div id=\"lastVideos_header\">
            <div class=\"floatleft\">Les dernieres vidéos</div>
            <div class=\"floatright\">Recherche...</div>
        </div>
        <div class=\"videos\">
            ";
        // line 14
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["videos"]) ? $context["videos"] : $this->getContext($context, "videos")));
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
            echo "         
                ";
            // line 15
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
        // line 17
        echo "        </div>
    </div>
    <div id=\"videos_topVideos_news_sorties\">
        <div id=\"videos_news\" class=\"floatleft\">
            <div id=\"videos\" class=\"fond_transparent\">
                ";
        // line 22
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("SpicySiteBundle:Site:indexSuite", array("page" => (isset($context["page"]) ? $context["page"] : $this->getContext($context, "page")))));
        echo "
            </div>
            <div id=\"news\" class=\"fond_transparent\">
                ";
        // line 25
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("SpicySiteBundle:Site:news"));
        echo "
            </div>
        </div>
        <div id=\"topVideos_sorties\" class=\"floatleft\">
            <div id=\"topVideos\" class=\"fond_transparent\">
                <h3>Top vidéos</h3>
            </div>
            <div id=\"sorties\" class=\"fond_transparent\">
                <h3>Sorties</h3>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 25,  99 => 22,  92 => 17,  76 => 15,  57 => 14,  46 => 7,  43 => 6,  37 => 4,  32 => 3,  29 => 2,);
    }
}
