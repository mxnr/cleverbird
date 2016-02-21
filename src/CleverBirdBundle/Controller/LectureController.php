<?php

namespace CleverBirdBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use CleverBirdBundle\Entity\Lecture;
use CleverBirdBundle\Entity\Course;
use CleverBirdBundle\Form\Type\LectureType;

/**
 * Lecture controller.
 */
class LectureController extends Controller
{
    /**
     * @var Course
     */
    private $course;

    /**
     * Finds and displays a Lecture entity.
     *
     * @param Lecture $lecture
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Lecture $lecture)
    {
        if (!$lecture) {
            throw $this->createNotFoundException('This lecture does not exists!');
        }

        $course = $this->getCourse();
        $deleteForm = $this->createDeleteForm($lecture, $course);

        $next = $this->getRep('CleverBirdBundle:Lecture')
            ->getNextLecture($lecture->getId());
        $prev = $this->getRep('CleverBirdBundle:Lecture')
            ->getPrevLecture($lecture->getId());

        return $this->render('@CleverBird/Lecture/show.html.twig', [
            'next' => $next,
            'prev' => $prev,
            'course' => $course,
            'lecture' => $lecture,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $course = $this->getCourse();
        if ($course->getUser() != $this->getUser()) {
            throw $this->createAccessDeniedException('Only owner of the Course can add new lectures');
        }

        $lecture = new Lecture();
        $form = $this->createForm(LectureType::class, $lecture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lecture->setCourse($course);
            $this->save($lecture);

            $this->addFlash(
                'success',
                sprintf('New lecture "%s" successfully created!', $lecture->getTitle())
            );

            return $this->redirectToRoute(
                'course_lecture_show',
                [
                    'id' => $lecture->getId(),
                    'courseId' => $course->getId(),
                ]
            );
        }

        return $this->render('@CleverBird/Lecture/new.html.twig', [
            'course' => $course,
            'lecture' => $lecture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Lecture entity.
     *
     * @param Request $request
     * @param Lecture $lecture
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Lecture $lecture)
    {
        if (!$lecture->canEdit($this->getUser())) {
            throw $this->createAccessDeniedException();
        }

        $course = $this->getCourse();
        $deleteForm = $this->createDeleteForm($lecture, $course);
        $editForm = $this->createForm(LectureType::class, $lecture);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->save($lecture);

            $this->addFlash(
                'success',
                sprintf('Lecture %s successfully edited!', $lecture->getTitle())
            );

            return $this->redirectToRoute(
                'course_lecture_edit',
                [
                    'id' => $lecture->getId(),
                    'courseId' => $course->getId(),
                ]
            );
        }

        return $this->render('@CleverBird/Lecture/edit.html.twig', [
            'course' => $course,
            'lecture' => $lecture,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Lecture entity.
     *
     * @param Request $request
     * @param Lecture $lecture
     */
    public function deleteAction(Request $request, Lecture $lecture)
    {
        if ($lecture->getCourse()->getUser() != $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $course = $this->getCourse();
        $form = $this->createDeleteForm($lecture, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->delete($lecture);

            $this->addFlash(
                'success',
                sprintf('Lecture "%s" successfully deleted!', $lecture->getTitle())
            );
        }

        return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
    }

    /**
     * Creates a form to delete a Lecture entity.
     *
     * @param Lecture $lecture
     * @param Course  $course
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Lecture $lecture, Course $course)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'course_lecture_delete',
                    [
                        'id' => $lecture->getId(),
                        'courseId' => $course->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @return Course
     */
    private function getCourse()
    {
        if (!$this->course) {
            $courseId = $this->get('request_stack')->getCurrentRequest()->attributes->getInt('courseId');
            $this->course = $this->getRep('CleverBirdBundle:Course')->findOneById($courseId);

            if (!$this->course) {
                throw $this->createNotFoundException('This course does not exists!');
            }
        }

        return $this->course;
    }
}
