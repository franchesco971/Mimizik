<?php

/* SpicySiteBundle:Site:contact.html.twig */
class __TwigTemplate_fe1885e2795596e01934d32ef84a2897 extends Twig_Template
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
    <div id=\"contact\">
        <h2>Contact</h2>
        <p>Pour tous renseignements, promotions et montage vidéo veuillez nous contacter:</p><br/>
        <p>Developpeur : <a href=\"mailto:";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["email_contact"]) ? $context["email_contact"] : null), "html", null, true);
        echo "\">Franchesco971</a></p>
        <p>Graphisme : peach vivi</p>
    </div>
    <div>
        <p>facebook: <a href=\"http://www.facebook.com/mimizikcom\">mimizik</a></p>
        <p>youtube: <a href=\"http://www.facebook.com/mimizikcom\">mimizik</a></p>
    </div>
";
    }

    public function getTemplateName()
    {
        return "SpicySiteBundle:Site:contact.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 8,  31 => 4,  28 => 3,);
    }
}
