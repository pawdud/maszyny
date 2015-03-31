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
                ->add('technologies', 'entity', array(
                    'label' => 'Wybierz proces technologiczny',
                    'class' => 'AppBundle:Technology',
                    'property'=> 'name',
                    'expanded' => true,
                    'multiple' => true,
                ));
    }

    public function getName()
    {
        return 'part';
    }
}