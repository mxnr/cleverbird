<?php

namespace CleverBirdBundle\Controller;

use CleverBirdBundle\Entity\CourseStatuses;
use CleverBirdBundle\Entity\User;

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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function coursesListAction($light)
    {
        return $this->render(
            '@CleverBird/Default/courses-list.'.($light ? 'light' : 'html').'.twig',
            [
                'courses' => $this->getRep('CleverBirdBundle:Course')
                    ->findBy(
                        [
                            'accessType' => CourseStatuses::FOR_ALL,
                        ],
                        ['id' => 'desc']
                    ),
            ]
        );
    }

    /**
     * @param $showMy
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function calendarAction($showMy)
    {
        $color = function ($str) {
            $code = dechex(crc32($str));
            $code = substr($code, 0, 6);

            return sprintf('#%s', $code);
        };

        if ($showMy) {
            $courses = [];
            if ($this->getUser()->getParticipants()) {
                foreach ($this->getUser()->getParticipants() as $participant) {
                    $courses[] = $participant->getCourse();
                }
            }
        } else {
            $courses = $this->getRep('CleverBirdBundle:Course')->findAll();
        }

        $data = [];
        if ($courses) {
            foreach ($courses as $course) {
                $data[] = [
                    'title' => $course->getName(),
                    'start' => $course->getStartDate()->format('Y-m-d'),
                    'end' => $course->getEndDate()->format('Y-m-d'),
                    'url' => $this->get('router')->generate('course_show', ['id' => $course->getId()]),
                    'backgroundColor' => $color($course->getId()),
                ];
            }
        }

        return $this->render(
            'CleverBirdBundle:Default:calendar.html.twig',
            [
                'courses' => $data,
            ]
        );
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(User $profile)
    {
        if (!$profile) {
            throw $this->createNotFoundException('This user does not exists!');
        }

        return $this->render(
            'CleverBirdBundle:Default:profile.html.twig',
            [
                'profile' => $profile,
            ]
        );
    }
}
