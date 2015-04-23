<?php

namespace AppBundle\Entity;

class FabricRepository extends BaseRepository
{

    protected static $alias = 'fabrics';
    protected static $entity = 'AppBundle:Fabric';

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

            $this->qb->andWhere("{$a1}.code LIKE :q OR {$a1}.name LIKE :q ");
            $this->qb->setParameter('q', "%{$value}%");
            return true;
        }

        if ($name == 'category')
        {
            $this->qb->andWhere("{$a1}.category = :category");
            $this->qb->setParameter('category', $value);
            return true;
        }
    }

    protected function setSelectMany(array $crit = array())
    {
        $a1 = self::getAlias();
        $a2 = FabricCategoryRepository::getAlias();
        $this->qb->select($a1, $a2)
                ->innerJoin("{$a1}.category", $a2);
    }

}
