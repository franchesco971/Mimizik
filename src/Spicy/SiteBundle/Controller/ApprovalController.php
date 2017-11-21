<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Approval;
use Spicy\SiteBundle\Form\ApprovalType;
use Spicy\SiteBundle\Form\ApprovalAdminType;
use Spicy\SiteBundle\Entity\Video;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Spicy\SiteBundle\Form\VideoType;

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
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        
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
        $em = $this->getDoctrine()->getManager();
        $approvalService = $this->get('mimizik.approval.service');        
        $entity = $approvalService->getDefaultApproval();
        
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $tools = $this->container->get('mimizik.tools');
                $em->persist($entity);
                $em->flush();                           
                
                $tools->sendMail($this->renderView(
                    'SpicySiteBundle:Mail:Approval\admin_approval.html.twig',
                    ['user' => $this->getUser(),'video'=>$entity->getVideo()]
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
    public function createAdminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $approvalService = $this->get('mimizik.approval.service');
        $youtubeAPI = $this->container->get('mimizik.youtube.api');
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
                $tools = $this->container->get('mimizik.tools');
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
            $type = new ApprovalAdminType();
            $url = 'approval_new_admin';
        } else {
            $type = new ApprovalType();
            $url = 'approval_create';
        }
        
        $form = $this->createForm($type, $entity, array(
            'action' => $this->generateUrl($url),
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
        $approvalService = $this->get('mimizik.approval.service');
        $entity = $approvalService->getDefaultApproval();
        
        $form   = $this->createCreateForm($entity);

        return $this->render('SpicySiteBundle:Approval:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    
    public function newAdminAction(Request $request)
    {
        $approvalService = $this->get('mimizik.approval.service');
        $youtubeAPI = $this->container->get('mimizik.youtube.api');
        $yurl = $request->query->get('youtubeUrl');
        
        if($yurl)//s'il y a une url youtube
        {
            $video = $youtubeAPI->getByYoutubeId($yurl);
        }
        $entity = $approvalService->getDefaultApproval($video);
        
        $form   = $this->createCreateForm($entity, $admin = true);

        return $this->render('SpicySiteBundle:Approval:approvalAdmin.html.twig', array(
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
    
    /**
     * Displays a form to edit an existing Approval entity.
     *
     */
    public function disapprovalAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SpicySiteBundle:Approval')->find($id);

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
    
    public function approvalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        
        $approval= $em->getRepository('SpicySiteBundle:Approval')->find($id);
        $video=$approval->getTitle();
        $video->setEtat(true);
        
        $form= $this->createForm(new VideoType,$video);    
            
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {  
                $approval->setApprovalDate(new \DateTime);                
                
                try{
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('info','Publication approuvé');
                    $_SESSION['id_video_publish']=$video->getId();
                    
                    return $this->redirect($this->generateUrl('mimizik_app_fb_login'));
                }
                catch(\Exception $e)
                {
                    $this->get('session')->getFlashBag()->add('error',"Erreur d'enregistrement");
                }                
            }
        }
        
        return $this->render('SpicySiteBundle:Approval:approval.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function testAction() {
        $test='rien';
        $em = $this->getDoctrine()->getManager(); 
        
        $tools = $this->container->get('mimizik.tools');
        $video = $em->getRepository('SpicySiteBundle:Video')->find(3698);
                
        $this->get('session')->getFlashBag()->add('info','Video soumis à approbation');
        
        try{
            $tools->sendMail($this->renderView(
                'SpicySiteBundle:Mail:Approval\admin_approval.html.twig',
                ['user' => $this->getUser(),'video'=>$video]
            ));
            
            
            /*$tools->sendMail("<p>Bonjour Admin,</p>"
                . "<p>".$this->getUser()->getUsername()." a soumis la vidéo : "                
                . $tools->getArtistsNames($video->getArtistes())." - "
                . $video->getTitre()."</p>"
                . "<p><a href='https://www.youtube.com/watch?v=".$video->getUrl()."'>Voir</a></p>"
                . "<p><a href='".$this->generateUrl('approval')."'>Liste des Vidéos</a></p>");*/
        }
        catch (\Swift_SwiftException $e)
        {
            //dump($e);
//            $test=$e;
            var_dump($e);
        }
        
        return $this->render('SpicySiteBundle:Mail:Approval\admin_approval.html.twig',
                ['user' => $this->getUser(),'video'=>$video]);
    }
}
