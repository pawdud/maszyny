<?php

namespace AppBundle\Form\Type;
use AppBundle\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Util\Debug;


/**
 * Zmiana hasła użytkownika
 */
class UserPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder        
        ->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Hasła muszą się zgadzać',           
            'required' => true,
            'first_options' => array('label' => 'Hasło'),
            'second_options' => array('label' => 'Powtórz hasło'),
        ));       
        
    }

    public function getName()
    {
        return 'userPassword';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver){        
        $resolver->setDefaults(array(
            'validation_groups' => array('password')
        ));
    }
}