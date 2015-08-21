<?php

namespace Base\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BaseAppBundle:Default:index.html.twig', array('name' => $name));
    }
}
