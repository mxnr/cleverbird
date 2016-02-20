<?php

namespace CleverBirdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CleverBirdBundle:Default:index.html.twig');
    }
}
