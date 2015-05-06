<?php

namespace AppBundle\Entity;

use AppBundle\Utility\Config;
use AppBundle\Entity\BaseRepository;
use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\Connection;

/**
 * Części
 */
class PartRepository extends BaseRepository
{

    protected static $alias = 'parts';
    protected static $entity = 'AppBundle:Part';

    const PARSE_MODE_TREE_FOR_JAVASCRIPT = 1;

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
    

    public function tree($projectId, $parentId = 0, $mode = self::PARSE_MODE_TREE_FOR_JAVASCRIPT, array $partsIdsTree = array(), $idPartSelected=false)
    {
        
         //Debug::dump($partsIdsTree); exit;

        $return = array();

        $sql = " SELECT 
                    p.*,
                    u.name as user_name
                FROM part p
                JOIN user u ON u.id = p.user_id
                WHERE 
                    p.project_id = ?
                AND
                    p.parent_id  = ?
                   
                ";
        
        
        $parameters = array($projectId, $parentId);        
        $types = array(\PDO::PARAM_INT, \PDO::PARAM_INT);
        
        
        if($partsIdsTree){
            $sql .= "AND p.id IN (?) ";
            $parameters[] = $partsIdsTree;
            $types[] = Connection::PARAM_STR_ARRAY;
        }
        
        
        
        
        $result = $this->getEntityManager()
                ->getConnection()
                ->executeQuery($sql, $parameters, $types)
                ->fetchAll();
        
        
        if (is_array($result) && !empty($result))
        {
            foreach ($result as $row)
            {
                if($row['id'] === $idPartSelected){
                   $row['selected'] = true; 
                }else{
                   $row['selected'] = false;
                }
                
                $row['technologies'] = $this->getTechnologies($row['id']);
                $row['fabrics']      = $this->getFabrics($row['id']);
                
                

                $children = $this->tree($projectId, $row['id'], $mode, $partsIdsTree, $idPartSelected);
                if (is_array($children) && !empty($children))
                {
                    $row['children'] = $children;
                }
                if ($mode == self::PARSE_MODE_TREE_FOR_JAVASCRIPT)
                {
                    $return[] = $this->parseRowForJavascript($row);
                }
            }
        }

        return $return;
    }

    private function parseRowForJavascript($row)
    {
        $return = array(
            'title' => $row['name'],
            'key' => $row['id'],
            'is_drawing' => (bool) $row['is_drawing'],
            'is_completed' => (bool) $row['is_completed'],
            'user_name' => $row['user_name'],
            'selected' => $row['selected'],
            'expanded' => true,
            'folder' => isset($row['children']),
            'link_details' => Config::instance()->url('czesc_edytuj', array('id' => $row['id'])),
            'link_details_fabric' => Config::instance()->url('czesc_edytuj_materialy', array('id' => $row['id'])),
            'link_details_technology' => Config::instance()->url('czesc_edytuj_technologie', array('id' => $row['id']))            
        );

        if (!empty($row['children']))
        {
            $return['children'] = $row['children'];
        }

        if (!empty($row['technologies']))
        {
            foreach($row['technologies'] as & $v){
                $v['is_completed'] = (bool) $v['is_completed'];
            }
            $return['technologies'] = $row['technologies'];
        }
        
        if (!empty($row['fabrics']))
        {            
            $return['fabrics'] = $row['fabrics'];
        }

        return $return;
    }

    /**
     * 
     * @param integer $id id części
     */
    private function getTechnologies($id)
    {
        $sql = " SELECT 
                   t.*,
                   t2p.is_completed
                FROM technology t
                JOIN technology2part t2p ON t2p.technology_id = t.id
                WHERE
                    t2p.part_id = :id
        ";

        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);


        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $return = $stmt->fetchAll();
        
//        Debug::dump($return);

        return $return;
    }
    
    /**
     * 
     * @param integer $id id części
     */
    private function getFabrics($id)
    {
        $sql = " SELECT 
                   fa.*,
                   f2p.quantity
                FROM fabric fa
                JOIN fabric2part f2p ON f2p.fabric_id = fa.id
                WHERE
                    f2p.part_id = :id
        ";

        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);


        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $return = $stmt->fetchAll();
        
//        Debug::dump($return);

        return $return;
    }
    
    
    

    protected function setSelectMany(array $crit = array())
    {
        $a1 = self::getAlias();
        $a2 = Fabric2PartRepository::getAlias();
        $this->qb->select($a1, $a2)
                ->from(self::$entity, $a1)
                ->leftJoin('{$a1}.fabrics2part', $a2);
    }

}
