<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Project;
use AppBundle\Entity\Technology;
use AppBundle\Entity\Event;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Technology2Part
 * 
 * @package Technology2Part
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 * @ORM\Table(name="technology2part")
 * @ORM\Entity(repositoryClass="Technology2PartRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Technology2Part {

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
     *
     * @var 
     * 
     * @ORM\ManyToOne(targetEntity="Technology")
     * @ORM\JoinColumn(name="technology_id", referencedColumnName="id")
     */
    private $technology_id;
    
    /**
     *
     * @var 
     * 
     * @ORM\ManyToOne(targetEntity="Part")
     * @ORM\JoinColumn(name="part_id", referencedColumnName="id")
     */
    private $part_id;
    
     /**
     * 
     * @var \AppBundle\Entity\Event
     * 
     * @ORM\OneToMany(targetEntity="Event", mappedBy="technology2part")
     */
    private $events;
    
    public function __construct() {
        $this->events = new ArrayCollection();
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
     * Set technology_id
     *
     * @param \AppBundle\Entity\Technology $technologyId
     * @return Technology2Part
     */
    public function setTechnologyId(\AppBundle\Entity\Technology $technologyId = null)
    {
        $this->technology_id = $technologyId;

        return $this;
    }

    /**
     * Get technology_id
     *
     * @return \AppBundle\Entity\Technology 
     */
    public function getTechnologyId()
    {
        return $this->technology_id;
    }

    /**
     * Set part_id
     *
     * @param \AppBundle\Entity\Part $partId
     * @return Technology2Part
     */
    public function setPartId(\AppBundle\Entity\Part $partId = null)
    {
        $this->part_id = $partId;

        return $this;
    }

    /**
     * Get part_id
     *
     * @return \AppBundle\Entity\Part 
     */
    public function getPartId()
    {
        return $this->part_id;
    }

    /**
     * Add events
     *
     * @param \AppBundle\Entity\Event $events
     * @return Technology2Part
     */
    public function addEvent(\AppBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \AppBundle\Entity\Event $events
     */
    public function removeEvent(\AppBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
}
