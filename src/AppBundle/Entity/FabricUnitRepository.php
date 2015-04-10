<?php

namespace AppBundle\Entity;

use AppBundle\Entity\BaseRepository;

class FabricUnitRepository extends BaseRepository
{

    protected static $alias = 'fabrics_units';
    protected static $entity = 'AppBundle:FabricUnit';

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

            $this->qb->andWhere("{$a1}.name LIKE :q");
            $this->qb->setParameter('q', "%{$value}%");
            return true;
        }
    }

}
