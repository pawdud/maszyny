<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Technologia użyte do wytwarzania części (Edycja)
 */
class Technology2PartEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isCompleted', 'checkbox', array(                    
                    'label'     => 'Wykonana'
                ));
    }

    public function getName()
    {
        return 'technology2PartAdd';
    }
}