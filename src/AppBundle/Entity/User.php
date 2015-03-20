<?php

namespace AppBundle\Entity;

use AppBundle\Entity\UserRepository;
use Doctrine\ORM\Mapping as ORM;



/**
 * User
 * 
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=false)
     */
    private $password;
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=100, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=false)
     */
    private $role;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=500, nullable=true)
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_updated", type="datetime", nullable=true)
     */
    private $timeUpdated;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_add", type="datetime", nullable=false)
     */
    private $timeAdd;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set timeUpdated
     *
     * @param \DateTime $timeUpdated
     * @return User
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
    
    public function getTimeAdd(){
        return $this->timeAdd;
    }
    
    public function setTimeAdd(\DateTime $timeAdd){
        $this->timeAdd = $timeAdd;
        return $this;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    public function getName(){
       return $this->name;
    }
    
    public function setSurname($surname){
        $this->surname = $surname;
        return $this;
    }
    
    public function getSurname(){
       return $this->surname;
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
     * Ustawia domyślny czas dodania
     * 
     * @ORM\PrePersist()
     */
    public function setDefaultTimeAdd(){       
        if(!$this->timeAdd){
            $this->setTimeAdd(new \DateTime());
        }
    }
}
