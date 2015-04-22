<?php

namespace AppBundle\Entity;

use AppBundle\Entity\BaseRepository;
use Doctrine\Common\Util\Debug;

class ProjectRepository extends BaseRepository
{

    protected static $alias = 'u';
    protected static $entity = 'AppBundle:Project';

    public function customWhere($name, $value)
    {
        $a1 = self::getAlias();
        if ($name == 'id')
        {
            $this->qb->andWhere("{$a1}.id = :id");
            $this->qb->setParameter('id', $value);
            return true;
        }
    }

    // Lista wszystkich 
    // array(


    
    public function getPartsData($id){
        
        $return = array();
        
        $sql = " SELECT 
                    pa.id,
                    pa.parent_id
                FROM part pa
                WHERE
                pa.project_id = :id
                ";


        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll();        
        
        
        if(is_array($result) && !empty($result)){            
            foreach($result as $v){
                $return[$v['id']] = array('id' => $v['id'], 'parent_id' => $v['parent_id']);
            }
        }
        
      
        
        return $return;
        
    }
    
    


    public function getPartsTree($ids, $data)
    {
//        Debug::dump($ids);
//        Debug::dump($data);
//        exit;
//        
        
        
        $return = array();


        if (!is_array($ids))
        {
            $ids = array($ids);
        }        

        foreach ($ids as $id)
        {
            $return['_' . $id . '_'] = $id;
            if ($data[$id]['parent_id'])
            {
               $return = array_merge($return, $this->getPartsTree($data[$id]['parent_id'], $data));
            }
        }

        //Debug::dump($return);
        

        return $return;
    }
    
    
    public function getPartsIdByTechnology($projectId, $technologyId){
        $return = array();
        
         $sql = " SELECT 
                    pa.id               
                FROM part pa
                JOIN technology2part t2p ON t2p.part_id = pa.id
                JOIN technology tech ON tech.id = t2p.technology_id
                WHERE 
                    pa.project_id = :project_id
                AND
                    tech.id = :technology_id
                GROUP BY 
                    tech.id
                ";


        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);
        $stmt->bindValue(':project_id', $projectId);
        $stmt->bindValue(':technology_id', $technologyId);
        $stmt->execute();
        $result = $stmt->fetchAll();        
        foreach($result as $v){
            $return[] = $v['id'];
        }
        return $return;
    }
    
    

    public function getTechnologies($id)
    {
        $sql = " SELECT 
                    tech.*
                FROM project pr
                JOIN part pa ON pa.project_id = pr.id
                JOIN technology2part t2p ON t2p.part_id = pa.id
                JOIN technology tech ON tech.id = t2p.technology_id
                WHERE 
                    pr.id = :id
                GROUP BY 
                    tech.id
                ";


        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

//    protected function setFromMany(array $crit = array()){
//        $a1 = ProjectRepository::getAlias();
//        $a2 = ActionRepository::getAlias();
//        $this->qb->select($a1, $a2)
//        ->innerJoin("{$a1}.actions", $a2);
//    }
}
