<?php

namespace AppBundle\Entity;
use AppBundle\Entity\BaseRepository;

class UserRepository extends BaseRepository
{   
    protected static $alias = 'u';
    protected static $entity = 'AppBundle:User';  
    
    
    public function customWhere($name, $value) {
        $a1 = self::getAlias(); 
        if($name == 'id'){
            $this->qb->andWhere("{$a1}.id = :id");
            $this->qb->setParameter('id', $value);
            return true;
        }
        if($name == 'q'){
            $this->qb->andWhere("{$a1}.name LIKE :q OR {$a1}.surname LIKE :q OR {$a1}.email LIKE :q");
            $this->qb->setParameter('q', "%{$value}%");
            return true;
        }
        if($name == 'email'){
            $this->qb->andWhere("{$a1}.email = :email");
            $this->qb->setParameter('email', $value);
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