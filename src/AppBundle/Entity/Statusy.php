<?php

namespace AppBundle\Entity;

use AppBundle\Entity\StatusyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of Statusy
 * 
 * @package Statusy
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 * @ORM\Table(name="statusy")
 * @ORM\Entity(repositoryClass="StatusyRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Statusy {
    
    /**
     *
     * @var int
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY") 
     * 
     */
    private $id;
    
    /**
     *
     * @var varchar
     * 
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    /**
     * @var \DateTime
     */
    private $timeUpdated;

    /**
     * @var \DateTime
     */
    private $timeAdd;


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
     * Set name
     *
     * @param string $name
     * @return Statusy
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

   
}
