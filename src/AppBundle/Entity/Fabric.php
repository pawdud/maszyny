<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FabricRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Part
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Part")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

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
     * @param \AppBundle\Entity\Part $user
     * @return Fabric
     */
    public function setUser(\AppBundle\Entity\Part $user = null)
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

    public function getGroupSequence()
    {
        return array('add', 'update');
    }

}
