<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Fabric2PartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Materiały przyporządkowane do części
 * 
 * @ORM\Table(name="fabric2part")
 * @ORM\Entity(repositoryClass="Fabric2PartRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Fabric2Part
{

   /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")     
     */
    private $id;

    /**     
     * 
     * @ORM\ManyToOne(targetEntity="Fabric")
     * @ORM\JoinColumn(name="fabric_id", referencedColumnName="id")
     */
    private $fabric;

    /**     
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Part", inversedBy="fabrics2part")
     * @ORM\JoinColumn(name="part_id", referencedColumnName="id")
     */
    private $part;

    /**
     * @ORM\Column(name="quantity", type="decimal", nullable=true)
     * @var float
     */
    private $quantity;

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
     * Set fabric
     *
     * @param \AppBundle\Entity\Fabric $fabric
     * @return Fabric2part
     */
    public function setFabric(\AppBundle\Entity\Fabric $fabric = null)
    {
        $this->fabric = $fabric;

        return $this;
    }

    /**
     * Get fabric
     *
     * @return \AppBundle\Entity\Fabric 
     */
    public function getFabric()
    {
        return $this->fabric;
    }

    /**
     * Set part
     *
     * @param \AppBundle\Entity\Part $part
     * @return Fabric2part
     */
    public function setPart(\AppBundle\Entity\Part $part = null)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return \AppBundle\Entity\Part 
     */
    public function getPart()
    {
        return $this->part;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

}
