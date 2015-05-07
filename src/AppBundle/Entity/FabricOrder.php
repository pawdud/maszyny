<?php

namespace AppBundle\Entity;

use AppBundle\Entity\FabricOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of Order
 * 
 * @package Order
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 * 
 * @ORM\Table(name="fabricorder")
 * @ORM\Entity(repositoryClass="FabricOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class FabricOrder {

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
     * @var int
     * 
     * 
     * @ORM\OneToOne(targetEntity="Fabric2Part")
     * @ORM\JoinColumn(name="fabric2part_id", referencedColumnName="id")
     * */
    private $fabric2part;

    /**
     *
     * @var decimal
     * 
     * @ORM\Column(name="quantity", type="decimal", precision=10, scale=2,  nullable=false)
     */
    private $quantity;

    /**
     *
     * @var type int
     * 
     * @ORM\Column(name="status", type="integer", length=10, nullable=false)
     */
    private $status;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     * @return Order
     */
    public function setQuantity($quantity) {

        $this->quantity = str_replace(',', '.', $quantity);
        return $this;
    }

    /**
     * Get quantity
     *
     * @return string 
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Order
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set id_part
     *
     * @param \AppBundle\Entity\Fabric2Part $idPart
     * @return Order
     */
    public function setFabric2Part(\AppBundle\Entity\Fabric2Part $fabric2part = null) {
        $this->fabric2part = $fabric2part;

        return $this;
    }

    /**
     * Get id_part
     *
     * @return \AppBundle\Entity\Fabric2Part 
     */
    public function getFabric2Part() {
        return $this->fabric2part;
    }

    /**
     * Sprawdza czy ilość jest poprawna     
     * @param ExecutionContextInterface $context
     */
    public function validateQuatnity(ExecutionContextInterface $context) {
        if (!empty($this->quantity)) {
            if (!is_numeric($this->quantity)) {
                $context->buildViolation('Wymagana wartość liczbowa')
                        ->atPath('quantity')
                        ->addViolation();
            } else {
                $decimals = ( (int) $this->quantity != $this->quantity ) ? (strlen($this->quantity) - strpos($this->quantity, '.')) - 1 : 0;

                if ($decimals > $this->unit->getScale()) {
                    $context->buildViolation('Maksymalnie ' . $this->unit->getScale() . ' miejsc po przecinku')
                            ->atPath('quantity')
                            ->addViolation();
                }
            }
        }
    }

}
