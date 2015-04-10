<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FabricRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Materiały
 * @ORM\Entity(repositoryClass="FabricCategoryRepository")
 * @ORM\Table(name="fabric_category")
 * @ORM\HasLifecycleCallbacks()
 */
class FabricCategory extends BaseEntity implements GroupSequenceProviderInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name;
    
    
     /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

   

    public function getGroupSequence()
    {
        return array('add', 'update');
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('name', new Assert\NotBlank(array(
            'message' => 'Pole wymagane',
        )));
    }

}
