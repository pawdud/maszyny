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
class TechnologyRepository extends BaseRepository
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

        if ($name == 'q')
        {

            $this->qb->andWhere("{$a1}.name LIKE :q ");
            $this->qb->setParameter('q', "%{$value}%");
            return true;
        }
    }

}
