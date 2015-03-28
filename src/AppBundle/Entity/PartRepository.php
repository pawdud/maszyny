<?php

namespace AppBundle\Entity;
use AppBundle\Utility\Config;

use AppBundle\Entity\BaseRepository;
use Doctrine\Common\Util\Debug;

class PartRepository extends BaseRepository
{   
    protected static $alias = 'parts';
    protected static $entity = 'AppBundle:Part';  
    
    const PARSE_MODE_TREE_FOR_JAVASCRIPT = 1;    
    
    public function customWhere($name, $value) {
        $a1 = self::getAlias(); 
        if($name == 'id'){
            $this->qb->andWhere("{$a1}.id = :id");
            $this->qb->setParameter('id', $value);
            return true;
        }
    }
    
    
    public function tree($projectId,  $parentId = 0, $mode=self::PARSE_MODE_TREE_FOR_JAVASCRIPT){
        
        $return = array();
        
        $sql = " SELECT 
                *
                FROM part
                WHERE 
                    part.project_id = :project_id
                AND
                    part.parent_id  = :parent_id
                   
                ";
        
        $stmt = $this->getEntityManager()
                      ->getConnection()
                      ->prepare($sql);
        $stmt->bindValue(':project_id', $projectId);
        $stmt->bindValue(':parent_id', $parentId);       
        $stmt->execute();
        
        $result = $stmt->fetchAll();        
        if(is_array($result) && !empty($result)){            
            foreach($result as $row){
                $children = $this->tree($projectId,  $row['id']);
                if(is_array($children) && !empty($children)){
                    $row['children'] = $children;
                }   
                if($mode == self::PARSE_MODE_TREE_FOR_JAVASCRIPT){
                     $return[] = $this->parseRowForJavascript($row);
                }                
            }            
        }            
        
        return $return;
    }
    
    
    
    private function parseRowForJavascript($row){        
        $return = array(
            'title'     => $row['name'],
            'key'       => $row['id'],
            'expanded'  => true,
            'folder'    => isset($row['children']),
            'link_details' => Config::instance()->url('czesc_edytuj', array('id' => $row['id'])),
            
        );
        
        if(!empty($row['children'])){
            $return['children'] = $row['children'];
        }
        
        return $return;
    }
    
    
   
    
    
    
    
    
    
    
    
//    protected function setFromMany(array $crit = array()){
//        $a1 = ProjectRepository::getAlias();
//        $a2 = ActionRepository::getAlias();
//        $this->qb->select($a1, $a2)
//        ->innerJoin("{$a1}.actions", $a2);
//    }
    
}