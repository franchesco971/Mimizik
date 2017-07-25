<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Approval;
use Spicy\SiteBundle\Form\ApprovalType;

/**
 * Approval controller.
 *
 */
class ApprovalController extends Controller
{

    /**
     * Lists all Approval entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SpicySiteBundle:Approval')->findAll();

        return $this->render('SpicySiteBundle:Approval:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Approval entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Approval();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('approval_show', array('id' => $entity->getId())));
        }

        return $this->render('SpicySiteBundle:Approval:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Approval entity.
     *
     * @param Approval $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Approval $entity)
    {
        $form = $this->createForm(new ApprovalType(), $entity, array(
            'action' => $this->generateUrl('approval_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Approval entity.
     *
     */
    public function newAction()
    {
        $entity = new Approval();
        $form   = $this->createCreateForm($entity);

        return $this->render('SpicySiteBundle:Approval:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Approval entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Approval entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SpicySiteBundle:Approval:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Approval entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Approval entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SpicySiteBundle:Approval:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Approval entity.
    *
    * @param Approval $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Approval $entity)
    {
        $form = $this->createForm(new ApprovalType(), $entity, array(
            'action' => $this->generateUrl('approval_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Approval entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Approval entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('approval_edit', array('id' => $id)));
        }

        return $this->render('SpicySiteBundle:Approval:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Approval entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Approval entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('approval'));
    }

    /**
     * Creates a form to delete a Approval entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('approval_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
