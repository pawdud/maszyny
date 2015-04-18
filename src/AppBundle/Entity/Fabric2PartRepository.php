<?php

namespace AppBundle\Entity;

use AppBundle\Entity\BaseRepository;

/**
 * TechnologyRepository
 * 
 * @package TechnologyRepository
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 */
class Fabric2PartRepository extends BaseRepository
{

    protected static $alias = 'technology';
    protected static $entity = 'AppBundle:Technology';

    public function customWhere($name, $value)
    {
        $a1 = self::getAlias();
        
        
        if ($name == 'id')
        {
            $this->qb->andWhere("{$a1}.id = :id");
            $this->qb->setParameter('id', $value);
            return true;
        }
        
        if ($name == 'fabric')
        {
            $this->qb->andWhere("{$a1}.fabric = :fabric");
            $this->qb->setParameter('fabric', $value);
            return true;
        }
        
        if ($name == 'part')
        {
            $this->qb->andWhere("{$a1}.part = :part");
            $this->qb->setParameter('part', $value);
            return true;
        }       
        

        if ($name == 'q')
        {

            $this->qb->andWhere("{$a1}.name LIKE :q ");
            $this->qb->setParameter('q', "%{$value}%");
            return true;
        }
    }

}
