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
 *
 * @ORM\Table(name="fabric", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="FabricRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Fabric extends BaseEntity implements GroupSequenceProviderInterface
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
     * @ORM\Column(name="code", type="string", length=100, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="decimal", precision=10, scale=2,  nullable=false)
     */
    private $quantity;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")     
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * 
     */
    private $user;
    
    
    /**
     * @var \AppBundle\Entity\FabricCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FabricCategory")     
     * @ORM\JoinColumn(name="fabric_category_id", referencedColumnName="id")
     * 
     */
    private $category;
    
    /**
     * @var \AppBundle\Entity\FabricUnit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FabricUnit")     
     * @ORM\JoinColumn(name="fabric_unit_id", referencedColumnName="id")
     * 
     */
    private $unit;

    /**
     * Set name
     *
     * @param string $name
     * @return Fabric
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
     * @return Fabric
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Part 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set code
     *
     * @param string $name
     * @return Fabric
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set quantity
     *
     * @param string $name
     * @return Fabric
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getGroupSequence()
    {
        return array('add', 'update');
    }
    
    
    public function getCategory(){
        return $this->category;
    }
    
    public function setCategory($category){
        $this->category = $category;
        return $this;
    }
    
    public function getUnit(){
        return $this->unit;
    }
    
    public function setUnit($unit){
        $this->unit = $unit;
        return $this;
    }
    
    

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {       

        $metadata->addPropertyConstraint('name', new Assert\NotBlank(array(            
            'message' => 'Pole wymagane',
        )));
        
        $metadata->addPropertyConstraint('code', new Assert\NotBlank(array(            
            'message' => 'Pole wymagane',
        )));
        
        $metadata->addPropertyConstraint('quantity', new Assert\NotBlank(array(            
            'message' => 'Pole wymagane',
        )));
        
        // Kod musi być unikalny
        $metadata->addConstraint(new UniqueEntity(array(
            'fields'  => 'code',
            'message'  => 'Kod musi być unikalny',
        )));
    }

}
