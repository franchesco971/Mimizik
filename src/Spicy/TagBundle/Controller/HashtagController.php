<?php

namespace Spicy\TagBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Spicy\TagBundle\Entity\Hashtag;
use Spicy\TagBundle\Form\HashtagType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Hashtag controller.
 *
 */
class HashtagController extends Controller
{
    /**
     * Lists all Hashtag entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SpicyTagBundle:Hashtag')->findAll();

        return $this->render('SpicyTagBundle:Hashtag:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Hashtag entity.
     *
     */
    // public function createAction(Request $request)
    // {
    //     $entity  = new Hashtag();
    //     $form = $this->createForm(HashtagType::class, $entity);
    //     $form->handleRequest($request);

    //     if ($form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($entity);
    //         $em->flush();

    //         return $this->redirect($this->generateUrl('admin_hashtag_show', array('id' => $entity->getId())));
    //     }

    //     return $this->render('SpicyTagBundle:Hashtag:new.html.twig', array(
    //         'entity' => $entity,
    //         'form'   => $form->createView(),
    //     ));
    // }

    public function create_modalAction(EntityManagerInterface $em, Request $request)
    {
        $entity  = new Hashtag();
        $refererUrl = $request->headers->get("referer");
        $form = $this->createForm(HashtagType::class, $entity);
        $form->handleRequest($request);        
                        
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            if(!$refererUrl) {
                throw new \Exception("test");
            }

            return $this->redirect($refererUrl);
        }       
        
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Erreur dans la creation du tag'
        );
        
        return $this->redirect($refererUrl);
    }

    public function create_modal_updateAction(Request $request, EntityManagerInterface $em, $id)
    {
        $entity  = new Hashtag();
        $form = $this->createForm(HashtagType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
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
     */
    // public function newAction()
    // {
    //     $entity = new Hashtag();
    //     $form   = $this->createForm(HashtagType::class, $entity);

    //     return $this->render('SpicyTagBundle:Hashtag:new.html.twig', array(
    //         'entity' => $entity,
    //         'form'   => $form->createView(),
    //     ));
    // }
    
    public function new_modalAction()
    {
        $entity = new Hashtag();
        $form   = $this->createForm(HashtagType::class, $entity);

        return $this->render('SpicyTagBundle:Hashtag:new_modal.html.twig',array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function new_modal_updateAction($id)
    {
        $entity = new Hashtag();
        $form   = $this->createForm(HashtagType::class, $entity);

        return $this->render('SpicyTagBundle:Hashtag:new_modal_update.html.twig',array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'idArtiste'=>$id
        ));
    }

    /**
     * Finds and displays a Hashtag entity.
     *
     */
    // public function showAction($id)
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Hashtag entity.');
    //     }

    //     $deleteForm = $this->createDeleteForm($id);

    //     return $this->render('SpicyTagBundle:Hashtag:show.html.twig', array(
    //         'tag'      => $entity,
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }
    
    /**
     * 
     * @param type $tag
     * @param type $page
     * @return type
     * @throws BadRequestHttpException
     * @throws type
     */
    public function showTagAction(EntityManagerInterface $em, $tag, $page)
    {
        if($page == '__id__') {
            throw $this->createNotFoundException('Unable to find Hashtag entity.');
        }
        
        $nbSuggestion = $this->container->getParameter('nbSuggestion');

        $entity = $em->getRepository('SpicyTagBundle:Hashtag')->findOneByLibelle($tag);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hashtag entity.');
        }
        
        $artistes = $em
                ->getRepository('SpicySiteBundle:Artiste')
                ->getByTag($entity->getId());
        
        $videos = $em
                ->getRepository('SpicySiteBundle:Video')
                ->getByTag($entity->getId(), $page, $nbSuggestion);
        
        return $this->render('SpicyTagBundle:Hashtag:showTag.html.twig',array(
            'tag' => $entity,
            'artistes' => $artistes,
            'videos' => $videos,
            'page' => $page
        ));
    }

    /**
     * Displays a form to edit an existing Hashtag entity.
     *
     */
    // public function editAction($id)
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Hashtag entity.');
    //     }

    //     $editForm = $this->createForm(HashtagType::class, $entity);
    //     $deleteForm = $this->createDeleteForm($id);

    //     return $this->render('SpicyTagBundle:Hashtag:edit.html.twig', array(
    //         'entity'      => $entity,
    //         'edit_form'   => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    // /**
    //  * Edits an existing Hashtag entity.
    //  *
    //  */
    // public function updateAction(Request $request, $id)
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $entity = $em->getRepository('SpicyTagBundle:Hashtag')->find($id);

    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Hashtag entity.');
    //     }

    //     $deleteForm = $this->createDeleteForm($id);
    //     $editForm = $this->createForm(HashtagType::class, $entity);
    //     $editForm->handleRequest($request);

    //     if ($editForm->isValid()) {
    //         $em->persist($entity);
    //         $em->flush();

    //         return $this->redirect($this->generateUrl('admin_hashtag_edit', array('id' => $id)));
    //     }

    //     return $this->render('SpicyTagBundle:Hashtag:edit.html.twig', array(
    //         'entity'      => $entity,
    //         'edit_form'   => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    /**
     * Deletes a Hashtag entity.
     *
     */
    public function deleteAction(EntityManagerInterface $em, Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
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
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
