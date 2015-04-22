<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Util\Debug;

class BaseRepository extends EntityRepository {

    protected static $alias;
    protected static $entity;

    /**
     * Doctrine\ORM\QueryBuilder
     */
    protected $qb = null;
    protected $dqlPartsToReset = array(
        'set', 'where', 'groupBy',
        'having', 'orderBy'
    );

    public function __construct($em, $class) {
        parent::__construct($em, $class);
        $this->qb = $this->createQueryBuilder(self::getAlias());
    }

    public static function getAlias() {
        return static::$alias;
    }

    public function getEntity() {
        return static::$entity;
    }

    public function delete(array $crit = array()) {
        //exit($this->getClassName());        
        $this->reset();
        $this->qb->delete($this->getClassName(), self::getAlias());
        $this->setWhere($crit);
        return $this->qb->getQuery()->execute();
    }

    protected function setWhere(array $crits = array()) {
        foreach ($crits as $k => $v) {
            if (!$this->customWhere($k, $v)) {
                $this->defaultWhere($k, $v);
            }
        }
    }

    protected function customWhere($name, $value) {
        return false;
    }

    protected function defaultWhere($name, $value) {
        $fieldnames = $this->_class->getFieldNames();
        if (
                !is_string($name) ||
                !isset($value['value']) ||
                !in_array($name, $fieldnames)
        ) {
            return false;
        }
        $o = (empty($value['operator'])) ? '=' : $value['operator'];
        $v = $value['value'];
        $name = self::getAlias() . '.' . $name;
        if ($o == '>=') {
            $expr = $this->qb->expr()->gte($name, $v);
        } elseif ($o == '<=') {
            $expr = $this->qb->expr()->lte($name, $v);
        } elseif ($o == '>') {
            $expr = $this->qb->expr()->gt($name, $v);
        } elseif ($o == '<') {
            $expr = $this->qb->expr()->lt($name, $v);
        } elseif ($o == '=') {
            $expr = $this->qb->expr()->eq($name, $v);
        }
        $this->qb->andWhere($expr);
        return true;
    }

    protected function reset() {
        $this->qb->resetDqlParts($this->dqlPartsToReset);
        $this->qb->setParameters(array());
        $this->qb->setFirstResult(null);
        $this->qb->setMaxResults(null);
    }

    public function count() {
        
    }

    public function one(array $crit = array(), $offset = false, $limit = false, $getQb = false) {
        $this->reset();
        $this->setSelectOne($crit);
        $this->setWhere($crit);
        
        if($getQb){
            return clone($this->qb);
        }
        
        
        $row = $this->qb->getQuery()->getOneOrNullResult();
        return $row;
    }

    public function many(array $crit = array(), $offset = false, $limit = false, $getQb = false) {
        $this->reset();
        $this->setSelectMany($crit);
        $this->setWhere($crit);
        
        
        if(!empty($crit['__order__']) && is_array($crit['__order__'])){
            $this->setOrder($crit['__order__']);
        }
        
        $this->setOrder();
        

        if (is_numeric($offset)) {
            $this->qb->setFirstResult($offset);
        }

        if (is_numeric($limit)) {
            $this->qb->setMaxResults($limit);
        }

        if ($getQb) {
            return clone($this->qb);
        }

        $rows = $this->qb->getQuery()->getResult();
        return $rows;
    }

    protected function setSelectMany(array $crit = array()) {
        
    }

    protected function setSelectOne(array $crit = array()) {
        $this->setSelectMany($crit);
    }

    protected function _i(array $crit, $paramName) {
        if (isset($crit[$paramName])) {
            return true;
        }
    }
    
    protected function setOrder(array $order = array()){        
        foreach($order as $sort => $order){
            $this->qb->addOrderBy($sort, $order); 
        }
    }
    

}
