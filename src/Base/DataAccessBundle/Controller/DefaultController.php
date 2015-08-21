<?php

namespace Base\DataAccessBundle\Controller;

use Base\DataAccessBundle\Entity\Job;
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
        $job = new Job();
        $form = $this->createFormBuilder($job)
            ->add('category', 'text')
            ->add('description', 'text')
            ->add('date', 'date')
            ->add('budget', 'text')
            ->add('save', 'submit')
            ->getForm();

        return $this->render('BaseDataAccessBundle:JobViews:enter_job.html.twig', array(
            'form' => $form->createView()
        ));

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            return $this->redirectToRoute('task_success');
        }
    }

    public function successAction(){
        return $this->render('BaseDataAccessBundle:Default:job_success.html.twig')
    }

    public function persistStudent(){
        $student = new Student();
        $student->setAge(25);
        $em = $this->getDoctrine()->getManager();

        $em->persist($student);
        $em->flush();
    }
}
