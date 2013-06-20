<?php

/* SpicySiteBundle:Site:index.html.twig */
class __TwigTemplate_0ce39e34ee7fe077467624c1c8ba47ea extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SpicySiteBundle::layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
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

    // line 3
    public function block_title($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("title", $context, $blocks);
        echo " - Site de promotion des vidéos clips des îles et de la guyane
";
    }

    // line 7
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 8
        echo "    <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("css/pagination.css"), "html", null, true);
        echo "\" type=\"text/css\" />
    ";
        // line 9
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
    <meta name=\"google-site-verification\" content=\"sGYi5z6Hzfo88twISFPkyXzxAQG6JmyphW0UITV9uic\" />
";
    }

    // line 12
    public function block_body($context, array $blocks = array())
    {
        // line 13
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
    <div id=\"lastVideos\" class=\"fond_transparent\">
        <div id=\"lastVideos_header\">
            <div class=\"floatleft\"><h2>Les dernieres vidéos</h2></div>
            <div class=\"floatright\">Recherche...</div>
        </div>
        <div class=\"videos\">
            ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["videos"]) ? $context["videos"] : null));
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
            // line 21
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
        // line 23
        echo "        </div>
    </div>
    <div id=\"videos_topVideos_news_sorties\">
        <div id=\"videos_news\" class=\"floatleft\">
            <div id=\"videos\" class=\"fond_transparent\">
                ";
        // line 28
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("SpicySiteBundle:Site:indexSuite", array("page" => (isset($context["page"]) ? $context["page"] : null))));
        echo "
            </div>
            <div id=\"news\" class=\"fond_transparent\">
                ";
        // line 31
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("SpicySiteBundle:Site:news"));
        echo "
            </div>
        </div>
        <div id=\"topVideos_sorties\" class=\"floatleft\">
            <div id=\"topVideos\" class=\"fond_transparent\">
                ";
        // line 36
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("SpicySiteBundle:Site:retro"));
        echo "
            </div>
            <div id=\"sorties\" class=\"fond_transparent\">
                <h3>Pub</h3>
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
        return array (  125 => 36,  117 => 31,  111 => 28,  104 => 23,  88 => 21,  69 => 20,  58 => 13,  55 => 12,  48 => 9,  43 => 8,  40 => 7,  33 => 4,  30 => 3,);
    }
}
