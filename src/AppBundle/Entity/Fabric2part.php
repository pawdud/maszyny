<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fabric2part
 */
class Fabric2part
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Fabric
     */
    private $fabric;

    /**
     * @var \AppBundle\Entity\Part
     */
    private $part;


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
}
