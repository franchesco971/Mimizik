<?php

/* SpicySiteBundle:Site:indexArtiste.html.twig */
class __TwigTemplate_35e0c0cc3f7a636fa1ccc2cc03841b59 extends Twig_Template
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

    // line 3
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 4
        echo "    <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("css/pagination.css"), "html", null, true);
        echo "\" type=\"text/css\" />
    ";
        // line 5
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
";
    }

    // line 8
    public function block_body($context, array $blocks = array())
    {
        // line 9
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
    <div id=\"artiste\" class=\"fond_transparent\">
        <h1>Tes artistes pr&eacute;f&eacute;r&eacute;s</h1>

        <div>
            <table border=\"1\" class=\"data\">
                <tr><th>Nom</th><th>Description</th></tr>
            ";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["artistes"]) ? $context["artistes"] : null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["artiste"]) {
            // line 17
            echo "                <tr>
                    <td><a href=\"";
            // line 18
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artiste_slug", array("id" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : null), "id"), "slug" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : null), "slug"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : null), "libelle"), "html", null, true);
            echo "</a></td>
                    <td>";
            // line 19
            echo twig_escape_filter($this->env, twig_truncate_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : null), "description"), 20, true, "..."), "html", null, true);
            echo "</td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 22
            echo "                <tr colspan=\"2\"><td>Pas d'artistes</td></tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['artiste'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 24
        echo "            </table>
        </div>
        <div class=\"pagination\">
            <ul>
              ";
        // line 28
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nombrePage"]) ? $context["nombrePage"] : null)));
        foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
            // line 29
            echo "                <li";
            if (((isset($context["p"]) ? $context["p"] : null) == (isset($context["page"]) ? $context["page"] : null))) {
                echo " class=\"active\"";
            }
            echo ">
                  <a href=\"";
            // line 30
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artistes", array("page" => (isset($context["p"]) ? $context["p"] : null))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["p"]) ? $context["p"] : null), "html", null, true);
            echo "</a>
                </li>
              ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 33
        echo "            </ul>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:indexArtiste.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 33,  103 => 30,  96 => 29,  92 => 28,  86 => 24,  79 => 22,  71 => 19,  65 => 18,  62 => 17,  57 => 16,  46 => 9,  43 => 8,  37 => 5,  32 => 4,  29 => 3,);
    }
}
