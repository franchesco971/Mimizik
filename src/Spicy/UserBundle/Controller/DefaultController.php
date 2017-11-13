<?php

namespace Spicy\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SpicyUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
