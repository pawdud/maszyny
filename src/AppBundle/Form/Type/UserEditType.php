<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Edycja użytkowników
 */
class UserEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Imię'))
            ->add('surname', 'text', array('label' => 'Nazwisko'));
    }

    public function getName()
    {
        return 'userEdit';
    }
}