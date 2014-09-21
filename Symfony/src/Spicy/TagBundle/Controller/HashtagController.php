<?php

namespace Spicy\TagBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spicy\TagBundle\Entity\Hashtag;
use Spicy\TagBundle\Form\HashtagType;

/**
 * Hashtag controller.
 *
 * @Route("/admin/hashtag")
 */
class HashtagController extends Controller
{
    /**
     * Lists all Hashtag entities.
     *
     * @Route("/", name="admin_hashtag")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SpicyTagBundle:Hashtag')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Hashtag entity.
     *
     * @Route("/", name="admin_hashtag_create")
     * @Method("POST")
     * @Template("SpicyTagBundle:Hashtag:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Hashtag();
        $form = $this->createForm(new HashtagType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_hashtag_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    public function create_modalAction(Request $request)
    {
        $entity  = new Hashtag();
        $form = $this->createForm(new HashtagType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('spicy_admin_add_artiste'));
        }
        
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Erreur dans la creation du tag'
        );
        
        return $this->redirect($this->generateUrl('spicy_admin_add_artiste'));
    }
    
    public function create_modal_updateAction(Request $request,$id)
    {
        $entity  = new Hashtag();
        $form = $this->createForm(new HashtagType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('spicy_admin_update_artiste', array('id' => $id)));
        }
        
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Erreur dans la creation du tag'
        );
        
        return $this->redirect($this->generateUrl('spicy_admin_update_artiste', array('id' => $id)));
    }

    /**
     * Displays a form to create a new Hashtag entity.
     *
     * @Route("/new", name="admin_hashtag_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Hashtag();
        $form   = $this->createForm(new HashtagType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    public function new_modalAction()
    {
        $entity = new Hashtag();
        $form   = $this->createForm(new HashtagType(), $entity);

        return $this->render('SpicyTagBundle:Hashtag:new_modal.html.twig',array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
    public function new_modal_updateAction($id)
    {
        $entity = new Hashtag();
        $form   = $this->createForm(new HashtagType(), $entity);

        return $this->render('SpicyTagBundle:Hashtag:new_modal_update.html.twig',array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'idArtiste'=>$id
        ));
    }

    /**
     * Finds and displays a Hashtag entity.
     *
     * @Route("/{id}", name="admin_hashtag_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hashtag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Hashtag entity.
     *
     * @Route("/{id}/edit", name="admin_hashtag_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hashtag entity.');
        }

        $editForm = $this->createForm(new HashtagType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Hashtag entity.
     *
     * @Route("/{id}", name="admin_hashtag_update")
     * @Method("PUT")
     * @Template("SpicyTagBundle:Hashtag:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hashtag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new HashtagType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_hashtag_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Hashtag entity.
     *
     * @Route("/{id}", name="admin_hashtag_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Hashtag entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_hashtag'));
    }

    /**
     * Creates a form to delete a Hashtag entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
