<?php

namespace Spicy\ITWBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Spicy\ITWBundle\Entity\Interview;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\ITWBundle\Form\InterviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Interview controller.
 *
 * @Route("/admin/itw")
 */
class InterviewController extends Controller
{

    /**
     * Lists all Interview entities.
     *
     * @Route("/", name="itw")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SpicyITWBundle:Interview')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all Interview entities.
     *
     * @Route("/list/{id}", name="itw_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction(Artiste $artiste)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SpicyITWBundle:Interview')->findBy(['artiste'=>$artiste]);
        
        return $this->render('SpicyITWBundle:Interview:index.html.twig', [
            'entities' => $entities,
            'artiste' => $artiste
        ]);
    }
    
    /**
     * Creates a new Interview entity.
     *
     * @Route("/{id}", name="itw_create")
     * @Method("POST")
     * @Template("SpicyITWBundle:Interview:new.html.twig")
     */
    public function createAction(Request $request, Artiste $artiste)
    {
        $entity = new Interview();
        $form = $this->createCreateForm($entity, $artiste);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setCreatedAt(new \DateTime('NOW'))
                    ->setArtiste($artiste);
            
            $em = $this->getDoctrine()->getManager();
            
            foreach ($entity->getQuestions() as $question) {
                $question->setInterview($entity);
                $em->persist($entity);
            }
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('itw_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Interview entity.
     *
     * @param Interview $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Interview $entity,Artiste $artiste)
    {
        $form = $this->createForm(new InterviewType(), $entity, array(
            'action' => $this->generateUrl('itw_create',['id' => $artiste->getId()]),
            'method' => 'POST',
        ));

//        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Interview entity.
     *
     * @Route("/new/{id}", name="itw_new")
     * @ParamConverter("artiste", class="SpicySiteBundle:Artiste", isOptional="true")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Artiste $artiste)
    {
        $entity = new Interview();
        $entity->setArtiste($artiste);
        
        $form   = $this->createCreateForm($entity, $artiste);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'artiste' => $artiste
        );
    }

    /**
     * Finds and displays a Interview entity.
     *
     * @Route("/{id}", name="itw_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyITWBundle:Interview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interview entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Interview entity.
     *
     * @Route("/{id}/edit", name="itw_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyITWBundle:Interview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interview entity.');
        }

        $editForm = $this->createEditForm($entity);
//        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Interview entity.
    *
    * @param Interview $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Interview $entity)
    {
        $form = $this->createForm(new InterviewType(), $entity, array(
            'action' => $this->generateUrl('itw_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

//        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    
    /**
     * Edits an existing Interview entity.
     *
     * @Route("/{id}", name="itw_update")
     * @Method("PUT")
     * @Template("SpicyITWBundle:Interview:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyITWBundle:Interview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interview entity.');
        }

//        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($entity->getQuestions() as $question) {
                $question->setInterview($entity);
                $em->persist($question);
            }
            
            $em->flush();

            return $this->redirect($this->generateUrl('itw_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Interview entity.
     *
     * @Route("/{id}", name="itw_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SpicyITWBundle:Interview')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Interview entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('itw'));
    }

    /**
     * Creates a form to delete a Interview entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itw_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
