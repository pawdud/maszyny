<?php

namespace AppBundle\Security;

use AppBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;

class UserProvider implements UserProviderInterface
{

    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
       
        $rUser = $this->em->getRepository('AppBundle:User');
       
        
//        exit($username);
     
        $eUser = $rUser->one(array('email' => $username));        
        
        
        if($eUser && $eUser->getId() !== null){
            
//            var_dump($eUser->getSalt()); exit;
            
            return new User($eUser->getEmail(), $eUser->getPassword(), $eUser->getSalt(), array($eUser->getRole()));            
        }


        throw new UsernameNotFoundException(
        sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User)
        {
            throw new UnsupportedUserException(
            sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        var_dump($class); exit;
        return $class === 'AppBundle\Security\User';
    }

}
