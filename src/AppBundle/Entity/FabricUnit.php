<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FabricRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Materiały - jednostki
 * @ORM\Entity(repositoryClass="FabricUnitRepository")
 * @ORM\Table(name="fabric_unit")
 * @ORM\HasLifecycleCallbacks()
 */
class FabricUnit extends BaseEntity implements GroupSequenceProviderInterface
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
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=10, nullable=false)
     */
    private $unit;

    /**
     * @var integer
     *
     * @ORM\Column(name="scale", type="integer", length=1, nullable=false)
     */
    private $scale;

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

    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    public function getScale()
    {
        return $this->scale;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    public function getUnit()
    {
        return $this->unit;
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
        
        $metadata->addPropertyConstraint('scale', new Assert\NotBlank(array(
            'message' => 'Pole wymagane',
        )));
        
        $metadata->addPropertyConstraint('unit', new Assert\NotBlank(array(
            'message' => 'Pole wymagane',
        )));
    }

}
