<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FabricType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', 'entity', array(
                    'class'     => 'AppBundle:FabricCategory',
                    'property'  => 'name',
                    'label'     => 'Kategoria'
                ))
                ->add('name', 'text', array('label' => 'Nazwa'))
                ->add('code', 'text', array('label' => 'Kod'))                
                ->add('quantity', 'text', array('label' => 'Stan magazynowy'))
                ->add('unit', 'entity', array(
                    'class' => 'AppBundle:FabricUnit',
                    'property' => 'unit',
                    'label'     => 'Jednostka'
                ));      
        
        
        
    }

    public function getName()
    {
        return 'fabric';
    }
}