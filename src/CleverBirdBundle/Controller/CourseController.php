<?php

namespace CleverBirdBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use CleverBirdBundle\Entity\Course;
use CleverBirdBundle\Form\Type\CourseType;

/**
 * Course controller.
 */
class CourseController extends Controller
{
    /**
     * Lists all Course entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('CleverBirdBundle:Course:index.html.twig', [
            'courses' => $this->getRep('CleverBirdBundle:Course')->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * Creates a new Course entity.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setUser($this->getUser());
            $this->save($course);

            return $this->redirectToRoute('course_index');
        }

        return $this->render('CleverBirdBundle:Course:new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Course entity.
     *
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Course $course)
    {
        if (!$course) {
            throw $this->createNotFoundException('This course does not exists!');
        }

        $deleteForm = $this->createDeleteForm($course);

        return $this->render('CleverBirdBundle:Course:show.html.twig', [
            'course' => $course,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @param Request $request
     * @param Course  $course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Course $course)
    {
        $deleteForm = $this->createDeleteForm($course);
        $editForm = $this->createForm(CourseType::class, $course);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->save($course);

            return $this->redirectToRoute('course_index', ['id' => $course->getId()]);
        }

        return $this->render('CleverBirdBundle:Course:edit.html.twig', [
            'course' => $course,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Course entity.
     *
     * @param Request $request
     * @param Course  $course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Course $course)
    {
        $form = $this->createDeleteForm($course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->delete($course);
        }

        return $this->redirectToRoute('course_index');
    }

    /**
     * Creates a form to delete a Course entity.
     *
     * @param Course $course The Course entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Course $course)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('course_delete', ['id' => $course->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
