<?php

namespace Spicy\ITWBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Spicy\ITWBundle\Entity\Interview;
use Spicy\ITWBundle\Form\InterviewType;
use Spicy\SiteBundle\Entity\Artiste;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/", name="itw", methods={"GET"})
     */
    public function indexAction(EntityManagerInterface $em)
    {
        $entities = $em->getRepository(Interview::class)->findAll();

        return $this->render('SpicyITWBundle:Interview:index.html.twig', [
            'entities' => $entities
        ]);
    }

    /**
     * Lists all Interview entities.
     *
     * @Route("/list/{id}", name="itw_list", methods={"GET"})
     */
    public function listAction(EntityManagerInterface $em, Artiste $artiste)
    {
        $entities = $em->getRepository(Interview::class)->findBy(['artiste' => $artiste]);

        return $this->render('SpicyITWBundle:Interview:index.html.twig', [
            'entities' => $entities,
            'artiste' => $artiste
        ]);
    }

    /**
     * Creates a new Interview entity.
     *
     * @Route("/{id}", name="itw_create", methods={"POST"})
     * @ParamConverter("artiste", class="SpicySiteBundle:Artiste")
     */
    public function createAction(EntityManagerInterface $em, Request $request, Artiste $artiste)
    {
        $entity = new Interview();
        $form = $this->createCreateForm($entity, $artiste);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setCreatedAt(new \DateTime('NOW'))
                ->setArtiste($artiste);

            foreach ($entity->getQuestions() as $question) {
                $question->setInterview($entity);
                $em->persist($entity);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('itw_show', array('id' => $entity->getId())));
        }

        return $this->redirect($this->generateUrl('itw_new', ['id' => $artiste->getId()]));
    }

    /**
     * Creates a form to create a Interview entity.
     *
     * @param Interview $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Interview $entity, Artiste $artiste)
    {
        $form = $this->createForm(InterviewType::class, $entity, array(
            'action' => $this->generateUrl('itw_create', ['id' => $artiste->getId()]),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Interview entity.
     *
     * @Route("/new/{id}", name="itw_new", methods={"GET"})
     * @ParamConverter("artiste", class="SpicySiteBundle:Artiste")
     */
    public function newAction(Artiste $artiste = null)
    {
        $entity = new Interview();
        $entity->setArtiste($artiste);

        $form   = $this->createCreateForm($entity, $artiste);

        return $this->render('SpicyITWBundle:Interview:new.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
            'artiste' => $artiste
        ]);
    }

    /**
     * Finds and displays a Interview entity.
     *
     * @Route("/{id}", name="itw_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyITWBundle:Interview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interview entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SpicyITWBundle:Interview:show.html.twig', [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Interview entity.
     *
     * @Route("/{id}/edit", name="itw_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicyITWBundle:Interview')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interview entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('SpicyITWBundle:Interview:edit.html.twig', [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ]);
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
        $form = $this->createForm(InterviewType::class, $entity, array(
            'action' => $this->generateUrl('itw_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Interview entity.
     *
     * @Route("/{id}", name="itw_update", methods={"PUT"})
     */
    public function updateAction(EntityManagerInterface $em, Request $request, $id)
    {
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

        return $this->render('SpicyITWBundle:Interview:edit.html.twig', [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ]);
    }
    /**
     * Deletes a Interview entity.
     *
     * @Route("/{id}", name="itw_delete", methods={"DELETE"})
     */
    public function deleteAction(EntityManagerInterface $em, Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
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
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }
}
