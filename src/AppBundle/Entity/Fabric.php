<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FabricRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
        
        
        $this->quantity = str_replace(',', '.', $quantity);

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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * Sprawdza czy ilość jest poprawna     
     * @param ExecutionContextInterface $context
     */
    public function validateQuatnity(ExecutionContextInterface $context)
    {
        if (!empty($this->quantity))
        {
            if (!is_numeric($this->quantity))
            {
                $context->buildViolation('Wymagana wartość liczbowa')
                        ->atPath('quantity')
                        ->addViolation();
            }else{
                $decimals = ( (int) $this->quantity != $this->quantity ) ? (strlen($this->quantity) - strpos($this->quantity, '.')) - 1 : 0;
                
                if($decimals > $this->unit->getScale()){
                     $context->buildViolation('Maksymalnie ' . $this->unit->getScale() . ' miejsc po przecinku')
                        ->atPath('quantity')
                        ->addViolation();
                }
            }
            
        }
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
            'fields' => 'code',
            'message' => 'Kod musi być unikalny',
        )));

        // Walidacja ilości
        $metadata->addConstraint(new Assert\Callback('validateQuatnity'));
    }

}
