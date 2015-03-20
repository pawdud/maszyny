<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Doctrine\Common\Util\Debug;

class SecurityController extends BaseController
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        'AppBundle:Security:login.html.twig', array(
                    
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );

        return $this->render('default/index.html.twig');
    }
    
    
    
    /**
     * @Route("/user/add", name="user_add")
     */
    public function addAction(){        
        
        
        $em = $this->getDoctrine()->getManager();
        
        $salt = sha1(uniqid());
        $password = 'koza123';
        
        $factory = $this->get('security.encoder_factory');       
        $user = new \AppBundle\Entity\User();      
        $encoder = $factory->getEncoder($user);
        $hash = $encoder->encodePassword($password, $salt);
        $user->setEmail('pawel.dudka@gazeta.pl');
        $user->setPassword($hash);
        $user->setSalt($salt);
        $user->setRole('ADMIN');
        $em->persist($user);
        $em->flush();
        exit($salt);        
        \Doctrine\Common\Util\Debug::dump($encoder);
           exit(__METHOD__);        
        
        $salt = uniqid();
    }

}