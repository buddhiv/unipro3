<?php
/**
 * Created by PhpStorm.
 * User: Buddhi
 * Date: 8/21/15
 * Time: 11:57 PM
 */

namespace Base\AppBundle\Controller;


class TestController extends BaseController{

    public function testAction(){
        return $this->render('BaseAppBundle:Test:test.html.twig');
    }

} 