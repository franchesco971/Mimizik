<?php

/* SpicySiteBundle:Site:show.html.twig */
class __TwigTemplate_f9037aefccc558e55df54c386149dcce extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SpicySiteBundle::layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
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
    public function block_title($context, array $blocks = array())
    {
        // line 3
        $this->displayParentBlock("title", $context, $blocks);
        echo " : ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "getNomArtistes"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "titre"), "html", null, true);
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

    <div id=\"video\">
        <div>
            
            <h2 >
                <span class=\"artisteVideo\">
                ";
        // line 14
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "artistes"));
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
            // line 15
            echo "                    <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artiste", array("id" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
            echo "</a>    
                    ";
            // line 16
            if ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "last")) {
                // line 17
                echo "
                    ";
            } elseif ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "first")) {
                // line 18
                echo " & ";
            } else {
                echo " , ";
            }
            // line 19
            echo "                ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['artiste'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 20
        echo "                </span>
                  - 
                <a href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "slug"))), "html", null, true);
        echo "\">
                    ";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "titre"), "html", null, true);
        echo "
                </a>
            </h2>
            
        </div>
\t<!--<iframe width=\"560\" height=\"315\" src=\"http://www.youtube.com/embed/<?php echo \$video->url() ?>?autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>-->
\t<iframe width=\"900\" height=\"506\" src=\"http://www.youtube.com/embed/";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "url"), "html", null, true);
        echo "?autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>
\t<!--<form name=\"n\" action=\"post\">
\t\t<p>
\t\t\t<label for=\"textfield2\">Commentaire</label><br>
\t\t\t<input type=\"text\" name=\"contenu\" id=\"contenu\">
\t\t</p>
\t\t<p> 
\t\t\t<input type=\"submit\" name=\"Submit\" value=\"Commenter\">
\t\t</p>
\t</form>-->
        <div id=\"fb_infos\" class=\"fond_transparent_blanc\">
            <div class=\"fb-like\" data-href=\"http://";
        // line 40
        echo twig_escape_filter($this->env, (isset($context["domaine_name"]) ? $context["domaine_name"] : $this->getContext($context, "domaine_name")), "html", null, true);
        echo "/";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_video_slug", array("id" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "id"), "slug" => $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "slug"))), "html", null, true);
        echo "\" data-send=\"true\" data-width=\"450\" data-show-faces=\"true\"></div>
            <div class=\"infos\">
                <p >Artistes: 
                    ";
        // line 43
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "artistes"));
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
            // line 44
            echo "                    <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artiste", array("id" => $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["artiste"]) ? $context["artiste"] : $this->getContext($context, "artiste")), "libelle"), "html", null, true);
            echo "</a>    
                    ";
            // line 45
            if ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "last")) {
                // line 46
                echo "
                    ";
            } elseif ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "first")) {
                // line 47
                echo " & ";
            } else {
                echo " , ";
            }
            // line 48
            echo "                ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['artiste'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 49
        echo "                </p>
                <p>Genres: 
                    ";
        // line 52
        echo "                    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "genreMusicaux"));
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
        foreach ($context['_seq'] as $context["_key"] => $context["genre"]) {
            // line 53
            echo "                    <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_genre", array("id" => $this->getAttribute((isset($context["genre"]) ? $context["genre"] : $this->getContext($context, "genre")), "id"), "slug" => $this->getAttribute((isset($context["genre"]) ? $context["genre"] : $this->getContext($context, "genre")), "slug"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["genre"]) ? $context["genre"] : $this->getContext($context, "genre")), "libelle"), "html", null, true);
            echo "</a>    
                    ";
            // line 54
            if ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "last")) {
                // line 55
                echo "
                    ";
            } elseif ($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "first")) {
                // line 56
                echo " & ";
            } else {
                echo " , ";
            }
            // line 57
            echo "                ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['genre'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 58
        echo "                </p>
                <p>Types de video: ";
        // line 59
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["video"]) ? $context["video"] : $this->getContext($context, "video")), "getNomTypes"), "html", null, true);
        echo "</p>
            </div>
        </div>
        
\t<div class=\"fb-comments fond_transparent_blanc\" data-href=\"http://";
        // line 63
        echo twig_escape_filter($this->env, (isset($context["domaine_name"]) ? $context["domaine_name"] : $this->getContext($context, "domaine_name")), "html", null, true);
        echo "\" data-width=\"470\" data-num-posts=\"2\"></div>
</div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:show.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  257 => 63,  250 => 59,  247 => 58,  233 => 57,  228 => 56,  224 => 55,  222 => 54,  215 => 53,  197 => 52,  193 => 49,  179 => 48,  174 => 47,  170 => 46,  168 => 45,  161 => 44,  144 => 43,  136 => 40,  122 => 29,  113 => 23,  109 => 22,  105 => 20,  91 => 19,  86 => 18,  82 => 17,  80 => 16,  73 => 15,  56 => 14,  45 => 7,  42 => 6,  32 => 3,  29 => 2,);
    }
}
