<?php

/* ::layout.html.twig */
class __TwigTemplate_70c2a1364186c751fa1729b56936bda9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">
    <head>
        <title>
           ";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        // line 8
        echo "        </title>
        
        <meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\" />
        <meta name=\"Description\" content=\"site de promotion des vidéos clips des îles\"/> 
        <!--<meta http-equiv=\"Content-Language\" content=\"fr\">-->
        ";
        // line 13
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 17
        echo "        
    </head>
    
    <body>
        <div id=\"page\">
            <div id=\"menu\">
                <div>
                    <img id=\"petit_logo\" src=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("images/petit_logo.png"), "html", null, true);
        echo "\" border=\"0\" alt=\"petit logo\" />
                </div>
                <div class=\"menuTexte\"><a href=\"";
        // line 26
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_homepage"), "html", null, true);
        echo "\">Accueil</a></div>
                <div class=\"menuTexte\"><a href=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_artistes"), "html", null, true);
        echo "\">Artistes</a></div>
                <div class=\"menuTexte\"><a href=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("spicy_site_genres"), "html", null, true);
        echo "\">Genre musicaux</a></div>
                <div class=\"menuTexte\">Contact</div>
                <div class=\"menuTexte\">Crédits</div>
                <div class=\"menuTexte\">fb - twit</div>
            </div>
            <div id=\"header\">
                <div id=\"gros_logo\" class=\"floatleft\"><img src=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("images/gros_logo.png"), "html", null, true);
        echo "\" border=\"0\" alt=\"gros logo\" /></div>
                <div id=\"titre_description\" class=\"floatleft\">
                    <div id=\"titre\"><h1>MiMizik.com <span style=\"color:red\">(En contruction)</span></h1></div>
                    <div id=\"description\">
                        <p>Bienvenue sur le site de promotion des vidéos clips des îles</p>
                    </div>
                </div>
            </div>
            <div id=\"body\">
            ";
        // line 43
        $this->displayBlock('body', $context, $blocks);
        // line 46
        echo "            </div>
        </div>
        <div id=\"fb-root\"></div>        
        ";
        // line 49
        $this->displayBlock('javascripts', $context, $blocks);
        // line 60
        echo "    </body>
</html>";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        // line 6
        echo "                Spicy site - Site de promotion des vidéos clips des îles
            ";
    }

    // line 13
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 14
        echo "            <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("css/style.css"), "html", null, true);
        echo "\" type=\"text/css\" />\t\t
            <link rel=\"stylesheet\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("css/reset.css"), "html", null, true);
        echo "\" type=\"text/css\" />
        ";
    }

    // line 43
    public function block_body($context, array $blocks = array())
    {
        // line 44
        echo "            
            ";
    }

    // line 49
    public function block_javascripts($context, array $blocks = array())
    {
        // line 50
        echo "        <script type=\"text/javascript\">
                (function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = \"//connect.facebook.net/fr_FR/all.js#xfbml=1\";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
        </script>
        ";
    }

    public function getTemplateName()
    {
        return "::layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  130 => 50,  127 => 49,  122 => 44,  119 => 43,  113 => 15,  108 => 14,  100 => 6,  97 => 5,  90 => 49,  85 => 46,  83 => 43,  71 => 34,  62 => 28,  58 => 27,  54 => 26,  49 => 24,  38 => 13,  31 => 8,  23 => 1,  47 => 10,  42 => 9,  40 => 17,  105 => 13,  99 => 22,  92 => 60,  76 => 15,  57 => 14,  46 => 7,  43 => 6,  37 => 7,  32 => 4,  29 => 5,);
    }
}
