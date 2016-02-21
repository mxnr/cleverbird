<?php

namespace CleverBirdBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use CleverBirdBundle\Entity\Group;
use CleverBirdBundle\Form\Type\GroupType;

/**
 * Group controller.
 *
 */
class GroupController extends Controller
{
    /**
     * Lists all Group entities.
     *
     */
    public function indexAction()
    {
        return $this->render('CleverBirdBundle:Group:index.html.twig', [
            'groups' => $this->getRep('CleverBirdBundle:Group')->findBy(['owner' => $this->getUser()]),
        ]);
    }

    /**
     * Creates a new Group entity.
     *
     */
    public function newAction(Request $request)
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group->setOwner($this->getUser())
                ->setUsers($this->getUser());
            $this->save($group);
            return $this->redirectToRoute('group_index');
        }

        return $this->render('@CleverBird/Group/new.html.twig', [
            'Group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Group entity.
     *
     */
    public function showAction(Group $group)
    {
        if (!$group) {
            throw $this->createNotFoundException('This course does not exists!');
        }

        $deleteForm = $this->createDeleteForm($group);

        return $this->render('CleverBirdBundle:Group:show.html.twig', [
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Group $group
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function joinAction(Group $group)
    {
        if ($this->getUser()->hasGroup($group)) {
            throw $this->createAccessDeniedException();
        }

        $group->setUsers($this->getUser());
        $this->save($group);
        return $this->redirectToRoute('group_show', ['id' => $group->getId()]);
    }

    /**
     * Displays a form to edit an existing Group entity.
     *
     */
    public function editAction(Request $request, Group $group)
    {
        if (!$this->getUser()->hasGroup($group)) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($group);
        $editForm = $this->createForm(GroupType::class, $group);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->save($group);
            return $this->redirectToRoute('group_index');
        }

        return $this->render('CleverBirdBundle:Group:edit.html.twig', [
            'Group' => $group,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Group entity.
     *
     */
    public function deleteAction(Request $request, Group $group)
    {
        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute('group_index');
    }

    /**
     * Creates a form to delete a Group entity.
     *
     * @param Group $group The Group entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Group $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('group_delete', ['id' => $group->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
