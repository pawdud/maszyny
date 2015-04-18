<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Technologia użyte do wytwarzania części dodawanie
 */
class Technology2PartAddType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('technology', 'entity', array(
                    'class'     => 'AppBundle:Technology',
                    'property'  => 'name',
                    'label'     => 'Technologia'
                ))
                ->add('isCompleted', 'checkbox', array(                    
                    'label'     => 'Wykonana'
                ));
    }

    public function getName()
    {
        return 'technology2PartAdd';
    }
}