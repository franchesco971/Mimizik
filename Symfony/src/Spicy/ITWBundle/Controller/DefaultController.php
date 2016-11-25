<?php

namespace Spicy\ITWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SpicyITWBundle:Default:index.html.twig', array('name' => $name));
    }
}
