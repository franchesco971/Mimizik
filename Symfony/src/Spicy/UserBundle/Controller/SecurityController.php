<?php


namespace Spicy\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Controller\SecurityController as Base;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;

class SecurityController extends Base
{
    const FB_LOGIN=1;
    const GOOGLE_LOGIN=2;

    public function loginModalAction()
    {
        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->container->get('templating')->renderResponse('SpicyUserBundle:Security:login_modal.html.twig',array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
        ));
    }
    
    public function socialLoginAction($type,$id,$name='') 
    {        
//        $em=$this->getDoctrine()->getManager();
//        $user=$em->getRepository('SpicySiteBundle:Video')->
        $userManager = $this->container->get('fos_user.user_manager');
        $user=null;
        
        if($type==self::FB_LOGIN)
        $user=$userManager->findUserBy(['facebookId'=>$id]);
        elseif($type==self::GOOGLE_LOGIN)
        $user=$userManager->findUserBy(['googleId'=>$id]);
        
    var_dump($user);
        
        if(!$user)
        {
            $user=$userManager->createUser();

            if($type==self::FB_LOGIN)
                $user->setFacebookId($id);
            elseif($type==self::GOOGLE_LOGIN)
                $user->setGoogleId($id);

            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $user->setLastLogin(new \DateTime());
            $name=($name)?str_replace(' ', '_', $name).'_'.$id:$id;
            $user->setUsername($name);
            $user->setEmail('temp_'.$id);
            $user->setPassword('temp');

            $userManager->updateUser($user);
        }
        
        $url = $this->container->get('router')->generate('spicy_site_homepage');
        $response = new RedirectResponse($url);
        
        $this->authenticateUser($user, $response);
        
        return $response;
        
    }
    
    public function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}