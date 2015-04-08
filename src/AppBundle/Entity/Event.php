<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Entity\Technology2Part;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Event
 * 
 * @package Event
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="EventRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Event {

    /**
     *
     * @var int autoincrement
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")  
     */
    private $id;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="time_start", type="datetime", nullable=false)
     */
    private $time_start;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="time_end", type="datetime", nullable=false)
     */
    private $time_end;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="notice", type="string", length=500, nullable=true)
     */
    private $notice;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")     
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * 
     */
    private $user;

    /**
     *
     * @var \AppBundle\Entity\Technology2Part
     * 
     * @ORM\ManyToOne(targetEntity="technology2part", inversedBy="events")
     * @ORM\JoinColumn(name="technology2part_id", referencedColumnName="id")
     */
    private $technology2part;


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
     * Set time_start
     *
     * @param \DateTime $timeStart
     * @return Event
     */
    public function setTimeStart($timeStart)
    {
        $this->time_start = $timeStart;

        return $this;
    }

    /**
     * Get time_start
     *
     * @return \DateTime 
     */
    public function getTimeStart()
    {
        return $this->time_start;
    }

    /**
     * Set time_end
     *
     * @param \DateTime $timeEnd
     * @return Event
     */
    public function setTimeEnd($timeEnd)
    {
        $this->time_end = $timeEnd;

        return $this;
    }

    /**
     * Get time_end
     *
     * @return \DateTime 
     */
    public function getTimeEnd()
    {
        return $this->time_end;
    }

    /**
     * Set notice
     *
     * @param string $notice
     * @return Event
     */
    public function setNotice($notice)
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * Get notice
     *
     * @return string 
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Event
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
     * Set technology2part
     *
     * @param \AppBundle\Entity\technology2part $technology2part
     * @return Event
     */
    public function setTechnology2part(\AppBundle\Entity\technology2part $technology2part = null)
    {
        $this->technology2part = $technology2part;

        return $this;
    }

    /**
     * Get technology2part
     *
     * @return \AppBundle\Entity\technology2part 
     */
    public function getTechnology2part()
    {
        return $this->technology2part;
    }
}
