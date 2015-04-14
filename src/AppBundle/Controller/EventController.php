<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;
use DateTime;
use AppBundle\Component\DatesTransformer;
use AppBundle\Controller\EventBaseController;

/**
 * Description of EventController
 * 
 * @package EventController
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 */
class EventController extends EventBaseController {

    /**
     * 
     * @return 
     * 
     * ------------------- na razie na potrzeby sprawdzenia czy działa domyślnie user_id jest ustawione na 4
     * 
     * @Route("/kalendarz/{user_id}", name="index_calendar", defaults={
     * "user_id": 4}, requirements={"user_id": "\d+"})
     */
    public function indexAction() {
        return $this->render('AppBundle:Event:index.html.twig');
    }

    /**
     * 
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * 
     * zwraca listę zdarzeń z wybranego dnia
     * 
     * @Route("/kalendarz/{user_id}/dzien/{year}/{month}/{day}", 
     *      name="listByDay", 
     *      defaults={"year": null, "month": null, "day": null},
     *      requirements={"year": "\d+", "month": "\d+", "day": "\d+"})
     */
    public function listByDayAction($user_id, $year, $month, $day) {
        if ($day === null && $month === null && $year === null) {
            $day = new DateTime();
        } else {
            $day = new DateTime("$year-$month-$day");
        }
        $qb = $this->repoEvent()->findAllByDay($day);
        $this->setViewData('events', $this->paginate($qb, 15));
        $this->setViewData('nextUrl', $this->generateNextDayUrl($day));
        $this->setViewData('previousUrl', $this->generatePreviousDayUrl($day));
        $this->setViewData('current', $day);
        return $this->render('AppBundle:Event:listByDay.html.twig');
    }

    /**
     * 
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * 
     * zwraca listę zdarzeń z wybranego tygodnia, w którym zawarta jest data z adresu 
     * 
     * @Route("/kalendarz/{user_id}/tydzien/{year}/{month}/{day}", 
     *      name="listByWeek", 
     *      defaults={"year": null, "month": null, "day": null},
     *      requirements={"year": "\d+", "month": "\d+", "day": "\d+"})
     */
    public function listByWeekAction($user_id, $year, $month, $day) {
        if ($day === null && $month === null && $year === null) {
            $day = new DateTime();
        } else {
            $day = new DateTime("$year-$month-$day");
        }

        $qb = $this->repoEvent()->findAllByWeek($day);
        $this->setViewData('events', $this->paginate($qb, 15));
        $this->setViewData('nextUrl', $this->generateNextWeekUrl($day));
        $this->setViewData('previousUrl', $this->generatePreviousWeekUrl($day));
        $this->setViewData('current', $day);
        return $this->render('AppBundle:Event:listByWeek.html.twig');
    }

    /**
     * 
     * @param integer $year
     * @param integer $month
     * 
     * zwraca listę zdarzeń z wybranego miesiąca, w którym zawarta jest data z adresu 
     * 
     * @Route("/kalendarz/{user_id}/miesiac/{year}/{month}", 
     *      name="listByMonth", 
     *      defaults={"year": null, "month": null},
     *      requirements={"year": "\d+", "month": "\d+"})
     */
    public function listByMonthAction($user_id, $year, $month) {
        if ($month === null && $year === null) {
            $day = new DateTime();
        } else {
            $day = new DateTime("$year-$month-01");
        }
        $qb = $this->repoEvent()->findAllByMonth($day);
         $this->setViewData('nextUrl', $this->generateNextMonthUrl($day));
        $this->setViewData('previousUrl', $this->generatePreviousMonthUrl($day));
        $this->setViewData('current', $day);
        $this->setViewData('events', $this->paginate($qb, 15));
        return $this->render('AppBundle:Event:listByMonth.html.twig');
    }

}
