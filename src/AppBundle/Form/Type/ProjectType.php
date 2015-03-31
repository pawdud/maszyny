<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text', array('label' => 'Nazwa'))
                ->add('isDrawing', 'checkbox', array('label' => 'Rysunek'));
    }

    public function getName()
    {
        return 'project';
    }
}