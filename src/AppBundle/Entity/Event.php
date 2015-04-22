<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Entity\Technology2Part;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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
    private $timeStart;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="time_end", type="datetime", nullable=false)
     */
    private $timeEnd;

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
     * @ORM\ManyToOne(targetEntity="Technology2Part", inversedBy="events")
     * 
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
        $this->timeStart = $timeStart;

        return $this;
    }

    /**
     * Get time_start
     *
     * @return \DateTime 
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * Set time_end
     *
     * @param \DateTime $timeEnd
     * @return Event
     */
    public function setTimeEnd($timeEnd)
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    /**
     * Get time_end
     *
     * @return \DateTime 
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
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
    public function setTechnology2Part(Technology2Part $technology2part = null)
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
    
    
    public function validateDateRange(ExecutionContextInterface $context){
        if($this->timeEnd instanceof \DateTime && $this->timeStart instanceof \DateTime){
            if($this->timeStart >= $this->timeEnd){
               $context
                       ->buildViolation('Czas zakończenia musi być większa od czasu rozpoczęcia')
                       ->addViolation();
            }            
        }
    }


    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {       
        $metadata->addPropertyConstraint('timeStart', new Assert\DateTime(array(
            'message' => 'Nieprawidłowa data'
        )));
        
        $metadata->addPropertyConstraint('timeEnd', new Assert\DateTime(array(
            'message' => 'Nieprawidłowa data'
        )));
        
        
        
        // Walidacja ilości
        $metadata->addConstraint(new Assert\Callback('validateDateRange'));
    }
    
    
    
    
    
}
