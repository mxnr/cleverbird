<?php

namespace CleverBirdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if ($this->getUser()) {
            return $this->render('CleverBirdBundle:Default:mycabinet.html.twig');
        } else {
            return $this->render('CleverBirdBundle:Default:index.html.twig');
        }
    }
}
