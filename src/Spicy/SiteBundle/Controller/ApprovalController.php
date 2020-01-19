<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spicy\SiteBundle\Entity\Approval;
use Spicy\SiteBundle\Form\ApprovalType;
use Spicy\SiteBundle\Form\ApprovalAdminType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Spicy\SiteBundle\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;
use Spicy\SiteBundle\Services\ApprovalService;
use Spicy\SiteBundle\Services\Tools;
use Spicy\SiteBundle\Services\YoutubeAPI;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

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
    public function indexAction(EntityManagerInterface $em)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }     
        
        $entities = $em->getRepository('SpicySiteBundle:Approval')->findAll();

        return $this->render('SpicySiteBundle:Approval:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Approval entity.
     *
     */
    public function createAction(Request $request, ApprovalService $approvalService, EntityManagerInterface $em, Tools $tools)
    {      
        $entity = $approvalService->getDefaultApproval();
        
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em->persist($entity);
                $em->flush();                           
                
                $tools->sendMail($this->renderView(
                    'SpicySiteBundle:Mail:Approval\admin_approval.html.twig',
                    ['user' => $this->getUser(),'video'=>$entity->getTitle()]
                ));
                
                $this->get('session')->getFlashBag()->add('info','Video soumis à approbation');
                
                return $this->redirect($this->generateUrl('approval_show', array('id' => $entity->getId())));
            }
            catch(\Exception $e)
            {                
                if ($e instanceof UniqueConstraintViolationException) {
                    $this->get('session')->getFlashBag()->add('error','Video déjà soumise');
                }
                else {
                    $this->get('session')->getFlashBag()->add('error',"Erreur d'enregistrement");
                }
            }
        }

        return $this->render('SpicySiteBundle:Approval:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function createAdminAction(ApprovalService $approvalService, YoutubeAPI $youtubeAPI, EntityManagerInterface $em, Request $request)
    {
        $yurl = $request->query->get('youtubeUrl');
        $video = null;
        
        if($yurl)//s'il y a une url youtube
        {
            $video = $youtubeAPI->getByYoutubeId($yurl);
        }       
        
        $entity = $approvalService->getDefaultApproval($video);
        
        $form = $this->createCreateForm($entity, $admin = true);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em->persist($entity);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('info','Video soumis à approbation');
                
                return $this->redirect($this->generateUrl('approval_show', array('id' => $entity->getId())));
            }
            catch(\Exception $e)
            {                
                if ($e instanceof UniqueConstraintViolationException) {
                    $this->get('session')->getFlashBag()->add('error','Video déjà soumise');
                }
                else {
                    $this->get('session')->getFlashBag()->add('error',"Erreur d'enregistrement");
                }                
            }            
        }

        return $this->render('SpicySiteBundle:Approval:approvalAdmin.html.twig', array(
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
    private function createCreateForm(Approval $entity, $admin = false)
    {
        if($admin) {
            $type = ApprovalAdminType::class;
            $url = 'approval_new_admin';
        } else {
            $type = ApprovalType::class;
            $url = 'approval_create';
        }
        
        $form = $this->createForm($type, $entity, array(
            'action' => $this->generateUrl($url),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Approval entity.
     *
     */
    public function newAction(ApprovalService $approvalService)
    {
        $entity = $approvalService->getDefaultApproval();
        
        $form   = $this->createCreateForm($entity);

        return $this->render('SpicySiteBundle:Approval:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param ApprovalService
     * @param YoutubeAPI
     * @param Request
     * 
     * @return Response
     */
    public function newAdminAction(ApprovalService $approvalService, YoutubeAPI $youtubeAPI, Request $request)
    {
        $yurl = $request->query->get('youtubeUrl');

        //s'il y a une url youtube
        if ($yurl) {
            $video = $youtubeAPI->getByYoutubeId($yurl);

            if ($video) {
                $entity = $approvalService->getDefaultApproval($video);
                $form   = $this->createCreateForm($entity, true);

                return $this->render('SpicySiteBundle:Approval:approvalAdmin.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ));
            }
        }

        $this->get('session')->getFlashBag()->add('warning', 'YoutubeUrl vide');

        return $this->redirect($this->generateUrl('spicy_admin_home'));
    }

    /**
     * Finds and displays a Approval entity.
     *
     */
    public function showAction(EntityManagerInterface $em, $id)
    {
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
    public function editAction(EntityManagerInterface $em, $id)
    {
        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Approval entity.');
        }

        $editForm = $this->createEditForm($entity);
//        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SpicySiteBundle:Approval:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
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
        $form = $this->createForm(ApprovalType::class, $entity, array(
            'action' => $this->generateUrl('approval_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    
    /**
     * Edits an existing Approval entity.
     *
     */
    public function updateAction(Request $request, EntityManagerInterface $em, $id)
    {
        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Approval entity.');
        }

//        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('info','Video modifié');

            return $this->redirect($this->generateUrl('approval_show', array('id' => $id)));
        }        

        return $this->render('SpicySiteBundle:Approval:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Deletes a Approval entity.
     *
     */
    public function deleteAction(Request $request, EntityManagerInterface $em, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
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
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Displays a form to edit an existing Approval entity.
     *
     */
    public function disapprovalAction($id, EntityManagerInterface $em)
    {
        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);
        $video = $entity->getTitle();
        $video->setEtat(false);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Approval entity.');
        }        

        $entity->setDisapprovalDate(new \DateTime);
        try{
            $em->flush();
            $this->get('session')->getFlashBag()->add('info','Video désapprouvée');
        }
        catch(\Exception $e)
        {
            $this->get('session')->getFlashBag()->add('error',"Erreur d'enregistrement");
        }

        return $this->redirect($this->generateUrl('approval'));
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function approvalAction(Request $request, EntityManagerInterface $em, $id)
    {        
        $approval = $em->getRepository('SpicySiteBundle:Approval')->find($id);
        $video = $approval->getTitle();
        $video->setEtat(true);
        
        $form = $this->createForm(VideoType::class,$video);    
            
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {  
                $approval->setApprovalDate(new \DateTime);
                $video->setDateVideo(new \DateTime('NOW'));                
                
                try{
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('info','Publication approuvé');
                    $_SESSION['id_video_publish'] = $video->getId();
                    
                    //return $this->redirect($this->generateUrl('mimizik_app_fb_login'));
                    return $this->redirect($this->generateUrl('approval'));
                }
                catch(\Exception $e)
                {
                    $this->get('session')->getFlashBag()->add('error',"Erreur d'enregistrement");
                }                
            }
        }
        
        return $this->render('SpicySiteBundle:Approval:approval.html.twig',array(
            'form' => $form->createView()
        ));
    }
}
