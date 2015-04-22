<?php

namespace AppBundle\Entity;

use AppBundle\Entity\PartRepository;
use AppBundle\Entity\Part;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Technology
 * 
 * @package Technology
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 * @ORM\Table(name="technology")
 * @ORM\Entity(repositoryClass="TechnologyRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Technology extends BaseEntity implements GroupSequenceProviderInterface {

    /**
     *
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

    
    public function __construct() {
        
    }
    
    /**
     * 
     * @param \AppBundle\Entity\Part $part
     * @return \AppBundle\Entity\Technology
     */
    public function addParts(Part $part) {
        $this->parts = $part;
        return $this;
    }
    
    /**
     * 
     * @param \AppBundle\Entity\Part $part
     * @return \AppBundle\Entity\Technology
     */
    public function removeParts(Part $part) {
        $this->parts->removeElement($part);
        return $this;
    }
    
    /**
     * 
     * @return \AppBundle\Entity\Part $part
     */
    public function getParts() {
        return $this->parts;
    }

        public function getGroupSequence() {
        return array('add', 'update');
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Technology
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) {

        $metadata->addPropertyConstraint('name', new Assert\NotBlank(array(
            'message' => 'Pole wymagane',
        )));
    }

}
