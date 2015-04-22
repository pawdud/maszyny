<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Harmonogram pracy nad technologią przypisaną danej części
 */
class Event2TechnologyType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('timeStart', 'datetime', array(
            'label' => 'Czas rozpoczęcia',
            'minutes' => array(
                0, 15, 30, 45
            ),
            'data' => new \DateTime()
        ))
        ->add('timeEnd', 'datetime', array(
            'label' => 'Czas zakończenia',
            'minutes' => array(
                0, 15, 30, 45
            ),
            'data' => new \DateTime()
         ))
        ->add('notice', 'textarea', array(
            'label' => 'Notatka'
        ));

    }

    public function getName()
    {
        return 'event2technology';
    }
}