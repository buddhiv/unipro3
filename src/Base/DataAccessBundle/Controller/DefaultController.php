<?php

namespace Base\DataAccessBundle\Controller;

use Base\DataAccessBundle\Entity\Student;
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

    public function persistStudent(){
        $student = new Student();
        $student->setAge(25);
        $em = $this->getDoctrine()->getManager();

        $em->persist($student);
        $em->flush();

    }
}
