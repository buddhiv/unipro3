<?php

namespace Base\DataAccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BaseDataAccessBundle:Default:index.html.twig', array('name' => $name));
    }

    public function homeAction()
    {
        return $this->render('BaseDataAccessBundle:Default:home.html.twig');
    }

    public function enterJobAction()
    {
        return $this->render('BaseDataAccessBundle:JobViews:enter_job.html.twig');
    }
}
