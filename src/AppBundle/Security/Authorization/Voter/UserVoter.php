<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Util\Debug;

class UserVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
   
    
    
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }


    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::DELETE,          
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\User';
        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }
 
    public function vote(TokenInterface $token, $user, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($user))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Dozwolony jest tylko jeden atrybut'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $loggedUser = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$loggedUser instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }        
        
        $security = $this->container->get('security.authorization_checker');
          

        switch($attribute) {
            case self::DELETE:                
                if($security->isGranted('ROLE_ADMIN') &&  $loggedUser->getId() !== $user->getId()){
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;                
           
            default :
                if($security->isGranted('ROLE_ADMIN') || $loggedUser->getId() === $user->getId()){
                    return VoterInterface::ACCESS_GRANTED;
                }
        }

        return VoterInterface::ACCESS_DENIED;
    }
}

