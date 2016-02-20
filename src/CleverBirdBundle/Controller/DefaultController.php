<?php

namespace CleverBirdBundle\Controller;

use CleverBirdBundle\Entity\CourseStatuses;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if ($this->getUser()) {
            return $this->render('CleverBirdBundle:Default:mycabinet.html.twig');
        } else {
            return $this->render('CleverBirdBundle:Default:index.html.twig');
        }
    }
    
    
    public function coursesListAction()
    {
        return $this->render(
            '@CleverBird/Default/courses-list.html.twig',
            [
                'courses' => $this->getRep('CleverBirdBundle:Course')
                    ->findBy(
                        [
                            'accessType' => CourseStatuses::FOR_ALL,
                        ],
                        ['id' => 'desc']
                    )
            ]
        );
    }
}
