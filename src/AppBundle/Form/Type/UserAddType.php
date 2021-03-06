<?php

namespace AppBundle\Form\Type;
use AppBundle\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Util\Debug;


/**
 * Dodawanie użytkowników
 */
class UserAddType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', 'text', array('label' => 'Email'))
        ->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Hasła muszą się zgadzać',           
            'required' => true,
            'first_options' => array('label' => 'Hasło'),
            'second_options' => array('label' => 'Powtórz hasło'),
        ))
        ->add('name', 'text', array('label' => 'Imię'))
        ->add('surname', 'text', array('label' => 'Nazwisko'))
        ->add('role', 'choice', array(
            'choices' => User::getRoles(),
            'label' => 'Uprawnienia'
        ));
        
    }

    public function getName()
    {
        return 'userAdd';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver){        
        $resolver->setDefaults(array(
            'validation_groups' => array('add')
        ));
    }
}