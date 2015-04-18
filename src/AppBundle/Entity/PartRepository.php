<?php

namespace AppBundle\Entity;

use AppBundle\Utility\Config;
use AppBundle\Entity\BaseRepository;
use Doctrine\Common\Util\Debug;

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

    public function tree($projectId, $parentId = 0, $mode = self::PARSE_MODE_TREE_FOR_JAVASCRIPT)
    {

        $return = array();

        $sql = " SELECT 
                    p.*,
                    u.name as user_name
                FROM part p
                JOIN user u ON u.id = p.user_id
                WHERE 
                    p.project_id = :project_id
                AND
                    p.parent_id  = :parent_id
                   
                ";

        $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($sql);
        $stmt->bindValue(':project_id', $projectId);
        $stmt->bindValue(':parent_id', $parentId);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (is_array($result) && !empty($result))
        {
            foreach ($result as $row)
            {
                $children = $this->tree($projectId, $row['id']);
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
            'expanded' => true,
            'folder' => isset($row['children']),
            'link_details' => Config::instance()->url('czesc_edytuj', array('id' => $row['id'])),
        );

        if (!empty($row['children']))
        {
            $return['children'] = $row['children'];
        }

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
