<?php

/* SpicySiteBundle:Site:fluxVideos.html.twig */
class __TwigTemplate_37412afbcf0f16ae9c88a3625ec0cf8e extends Twig_Template
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
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<feed xmlns=\"http://www.w3.org/2005/Atom\">
    <title>Mimizik - Site de promotion des vidéos clips des îles</title>
    <updated>";
        // line 4
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y-m-d\\TH:i:s"), "html", null, true);
        echo "+01:00</updated>
    <id>http://www.mimizik.com/</id>
    <author>
        <name>Franchesco971</name>
    </author>
    <link rel=\"alternate\" type=\"text/html\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
        echo "\"/>
    <link rel = \"self\" href = \"";
        // line 10
        echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_flux_videos"), "html", null, true);
        echo "\" />
        
    ";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["videos"]) ? $context["videos"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["video"]) {
            echo " 
    <entry>
        <title>";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "getNomArtistes"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "titre"), "html", null, true);
            echo "</title>
        <updated>";
            // line 15
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "dateVideo"), "Y-m-d\\TH:i:s"), "html", null, true);
            echo "+01:00</updated>
        <id>";
            // line 16
            echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "slug"))), "html", null, true);
            echo "</id>
        <link rel = \"alternate\" type=\"text/html\" href = \"";
            // line 17
            echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "slug"))), "html", null, true);
            echo "\" />
        <url>";
            // line 18
            echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "slug"))), "html", null, true);
            echo "</url>
        <content type = \"xhtml\" src=\"";
            // line 19
            echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "slug"))), "html", null, true);
            echo "\">
            <div xmlns=\"http://www.w3.org/1999/xhtml\">
                <a href=\"";
            // line 21
            echo twig_escape_filter($this->env, (isset($context["website_name"]) ? $context["website_name"] : null), "html", null, true);
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "slug"))), "html", null, true);
            echo "\">
                    <img src=\"http://img.youtube.com/vi/";
            // line 22
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "url"), "html", null, true);
            echo "/0.jpg\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "titre"), "html", null, true);
            echo "\" />
                </a>
                <p>";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : null), "description"), "html", null, true);
            echo "</p>
            </div>
        </content>
        <author>franchesco971</author>
    </entry>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['video'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 30
        echo "</feed>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:fluxVideos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  104 => 30,  92 => 24,  85 => 22,  80 => 21,  74 => 19,  69 => 18,  64 => 17,  59 => 16,  55 => 15,  49 => 14,  42 => 12,  36 => 10,  32 => 9,  24 => 4,  19 => 1,);
    }
}
