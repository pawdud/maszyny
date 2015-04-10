<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PartType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text', array('label' => 'Nazwa'))
                ->add('isDrawing', 'checkbox', array('label' => 'Rysunek'))
                ->add('isCompleted', 'checkbox', array('label' => 'Gotowa'));
               
    }

    public function getName()
    {
        return 'part';
    }
}