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

    public function persistStudent(){
        $student = new Student();
        $student->setAge(25);
        $em = $this->getDoctrine()->getManager();

        $em->persist($student);
        $em->flush();
    }
}
