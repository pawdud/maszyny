<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FabricOrder;
use AppBundle\Entity\Fabric2PartRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Materiały przyporządkowane do części
 * 
 * @ORM\Table(name="fabric2part")
 * @ORM\Entity(repositoryClass="Fabric2PartRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Fabric2Part {

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
     *
     * @var type 
     * 
     * /**
     * @ORM\OneToMany(targetEntity="FabricOrder", mappedBy="fabric2part")
     * 
     */
    private $fabricOrders;

    public function __construct() {
        $this->fabricOrders = new ArrayCollection();
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
     * Set fabric
     *
     * @param \AppBundle\Entity\Fabric $fabric
     * @return Fabric2part
     */
    public function setFabric(\AppBundle\Entity\Fabric $fabric = null) {
        $this->fabric = $fabric;

        return $this;
    }

    /**
     * Get fabric
     *
     * @return \AppBundle\Entity\Fabric 
     */
    public function getFabric() {
        return $this->fabric;
    }

    /**
     * Set part
     *
     * @param \AppBundle\Entity\Part $part
     * @return Fabric2part
     */
    public function setPart(\AppBundle\Entity\Part $part = null) {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return \AppBundle\Entity\Part 
     */
    public function getPart() {
        return $this->part;
    }

    public function getQuantity() {
        $scale = $this->getFabric()->getUnit()->getScale();
        return number_format($this->quantity, $scale, '.', '');
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }


    /**
     * Add fabricOrders
     *
     * @param \AppBundle\Entity\FabricOrder $fabricOrders
     * @return Fabric2Part
     */
    public function addFabricOrder(\AppBundle\Entity\FabricOrder $fabricOrders)
    {
        $this->fabricOrders[] = $fabricOrders;

        return $this;
    }

    /**
     * Remove fabricOrders
     *
     * @param \AppBundle\Entity\FabricOrder $fabricOrders
     */
    public function removeFabricOrder(\AppBundle\Entity\FabricOrder $fabricOrders)
    {
        $this->fabricOrders->removeElement($fabricOrders);
    }

    /**
     * Get fabricOrders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFabricOrders()
    {
        return $this->fabricOrders;
    }
}
