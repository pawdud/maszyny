<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Doctrine\Common\Util\Debug;
/**
 * Klasa bazowa encji
 *
 */
class BaseEntity
{

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_updated", type="datetime", nullable=true)
     */
    protected $timeUpdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_add", type="datetime", nullable=false)
     */
    protected $timeAdd;   

   
    /**
     * Set timeUpdated
     *
     * @param \DateTime $timeUpdated
     * @return Project
     */
    public function setTimeUpdated($timeUpdated)
    {
        $this->timeUpdated = $timeUpdated;

        return $this;
    }

    /**
     * Get timeUpdated
     *
     * @return \DateTime 
     */
    public function getTimeUpdated()
    {
        return $this->timeUpdated;
    }

    /**
     * Set timeAdd
     *
     * @param \DateTime $timeAdd
     * @return Project
     */
    public function setTimeAdd($timeAdd)
    {
        $this->timeAdd = $timeAdd;

        return $this;
    }

    /**
     * Get timeAdd
     *
     * @return \DateTime 
     */
    public function getTimeAdd()
    {
        return $this->timeAdd;
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
     * Get user
     * @ORM\PrePersist()
     * @return \AppBundle\Entity\User 
     */
    public function setDefaultTimeAdd()
    {

        if (!$this->timeAdd)
        {
            $this->timeAdd = new \DateTime();
        }        
      
    }
    
    /**    
     * @ORM\PreUpdate()
     */
    public function setDefaultTimeUpdated()
    {       

        if (!$this->timeUpdated)
        {
            $this->timeUpdated = new \DateTime();
        }
    }

}