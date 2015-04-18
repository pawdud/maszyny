<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Project;
use AppBundle\Entity\PartRepository;
use AppBundle\Entity\Fabric;
use AppBundle\Entity\Technology;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="part")
 * @ORM\Entity(repositoryClass="PartRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Part extends BaseEntity implements GroupSequenceProviderInterface
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")     
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Fabric2Part", mappedBy="part",  cascade={"persist", "remove"})
     */
    private $fabrics2part;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Technology2Part", mappedBy="part", cascade={"persist", "remove"}) 
     */
    private $technologies2part;

    /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User")
     * 
     */
    private $user;

    /**
     * @var \AppBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Project", inversedBy="parts")
     */
    private $project;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_drawing", type="boolean", nullable=true)
     */
    private $isDrawing;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_completed", type="boolean", nullable=true)
     */
    private $isCompleted;

    public function __construct()
    {
        $this->technologies2part = new ArrayCollection();
        $this->fabrics2part = new ArrayCollection();
    }

    public function addTechnology2Part(Technology2Part $technology2part)
    {
        $this->technologies2part[] = $technology2part;
        return $this;
    }

    public function removeTechnology2Part(Technology2Part $technology2part)
    {
        $this->technologies2part->removeElement($technology2part);
        return $this;
    }

    /**
     * 
     * @return \AppBundle\Entity\Technology2Part $technology2part
     */
    public function getTechnologies2Part()
    {
        return $this->technologies2part;
    }

    public function addFabric2Part(Fabric2Part $fabric2part)
    {
        $this->fabrics2part[] = $fabric2part;
        return $this;
    }

    public function removeFabric2Part(Fabric2Part $fabric2part)
    {
        $this->fabrics2part->removeElement($fabric2part);
        return $this;
    }

    public function getFabrics2Part()
    {
        return $this->fabrics2part;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return Part
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Part
     */
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Part
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     * @return Part
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    public function getIsDrawing()
    {
        return $this->isDrawing;
    }

    public function setIsDrawing($isDrawing)
    {
        $this->isDrawing = $isDrawing;
        return $this;
    }

    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->setGroupSequenceProvider(true);

        $metadata->addPropertyConstraint('name', new Assert\NotBlank(array(
            'groups' => array('add', 'update'),
            'message' => 'Pole wymagane'
        )));
    }

    public function getGroupSequence()
    {
        return array('add', 'update');
    }

}
