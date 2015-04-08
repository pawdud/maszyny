<?php

namespace AppBundle\Entity;

use AppBundle\Entity\BaseRepository;
use AppBundle\Component\DatesTransformer;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends BaseRepository {

    protected static $alias = 'event';
    protected static $entity = 'AppBundle:Event';

    /**
     * 
     * @param \DateTime $date
     * @return type
     * 
     * wyszukuje eventy z dnia
     */
    public function findAllByDay(\DateTime $date) {
        $start = new \DateTime($date->format('Y-m-d 00:00'));
        $end = new \DateTime($date->format('Y-m-d 23:59:59'));
        return $this->findAllByDates($start, $end);
    }

    /**
     * 
     * @param \DateTime $date
     * @return type
     * 
     * ustala datę poniedziałku i niedzieli z danego tygodnia, w którym zaiwera się data z adresu
     */
    public function findAllByWeek(\DateTime $date) {
        $monday = DatesTransformer::toMonday($date)->setTime(0, 0);
        $sunday = DatesTransformer::toSunday($date)->setTime(23, 59, 59);
        return $this->findAllByDates($monday, $sunday);
    }

    /**
     * 
     * @param \DateTime $date
     * @return type
     * 
     * ustala datę pierwszego i ostatniego dnia miesiąca w którym zawiera się data z adresu 
     */
    public function findAllByMonth(\DateTime $date) {
        $start = DatesTransformer::toFirstMonthDay($date)->setTime(0, 0);
        $end = DatesTransformer::toLastMonthDay($date)->setTime(23, 59, 59);
        return $this->findAllByDates($start, $end);
    }

    /**
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @return 
     * 
     * wyszukuje eventy miedzy dwoma datami
     */
    public function findAllByDates(\DateTime $start, \DateTime $end) {
        $q = $this->getEntityManager()->createQuery("SELECT e
                                     FROM AppBundle:Event e
                                     WHERE e.time_start >= :start AND e.time_start <= :end
                                     ORDER BY e.time_start ASC, e.time_end ASC")
                ->setParameter('start', $start)
                ->setParameter('end', $end);
        return $q->getResult();
    }

}
