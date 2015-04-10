<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FabricUnitType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text', array('label' => 'Nazwa'))        
                ->add('unit', 'text', array('label' => 'Symbol'))        
                ->add('scale', 'choice', array('label' => 'Precyzja', 'choices' => array(
                    0 => '0',
                    1 => '1',
                    2 => '2',
                    3 => '3',
                )));        
    }

    public function getName()
    {
        return 'fabricUnit';
    }
}