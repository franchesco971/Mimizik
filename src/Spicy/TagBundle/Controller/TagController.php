<?php

namespace Spicy\TagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SpicyTagBundle:Default:index.html.twig', array('name' => $name));
    }
}
