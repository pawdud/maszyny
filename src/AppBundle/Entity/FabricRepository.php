<?php

namespace AppBundle\Entity;
use AppBundle\Entity\BaseRepository;

class FabricRepository extends BaseRepository
{   
    protected static $alias = 'fabrics';
    protected static $entity = 'AppBundle:Fabric';  
    
    
    public function customWhere($name, $value) {
        $a1 = self::getAlias(); 
        if($name == 'id'){
            $this->qb->andWhere("{$a1}.id = :id");
            $this->qb->setParameter('id', $value);
            return true;
        }
    }
    
//    protected function setFromMany(array $crit = array()){
//        $a1 = ProjectRepository::getAlias();
//        $a2 = ActionRepository::getAlias();
//        $this->qb->select($a1, $a2)
//        ->innerJoin("{$a1}.actions", $a2);
//    }
    
}