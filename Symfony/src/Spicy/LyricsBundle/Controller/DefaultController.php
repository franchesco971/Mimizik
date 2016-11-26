<?php

namespace Spicy\LyricsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SpicyLyricsBundle:Default:index.html.twig', array('name' => $name));
    }
}
