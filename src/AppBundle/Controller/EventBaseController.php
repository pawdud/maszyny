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
use AppBundle\Controller\BaseController;

/**
 * Description of EventBaseController
 * 
 * @package EventBaseController
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 */
class EventBaseController extends BaseController {

    protected function generateNextDayUrl(\DateTime $date) {
        $nextDayDate = DatesTransformer::nextDay($date);
        $nextDayUrl = $this->container->get('router')->generate('listByDay', array(
            'user_id' => 4,
            'year' => $nextDayDate->format('Y'),
            'month' => $nextDayDate->format('m'),
            'day' => $nextDayDate->format('d')
        ));
        return $nextDayUrl;
    }

    protected function generatePreviousDayUrl(\DateTime $date) {
        $previousDayDate = DatesTransformer::previousDay($date);
        $previousDayUrl = $this->container->get('router')->generate('listByDay', array(
            'user_id' => 4,
            'year' => $previousDayDate->format('Y'),
            'month' => $previousDayDate->format('m'),
            'day' => $previousDayDate->format('d')
        ));
        return $previousDayUrl;
    }

    protected function generateNextWeekUrl(\DateTime $date) {
        $nextWeekDate = DatesTransformer::nextWeek($date);
        $nextWeekUrl = $this->container->get('router')->generate('listByWeek', array(
            'user_id' => 4,
            'year' => $nextWeekDate->format('Y'),
            'month' => $nextWeekDate->format('m'),
            'day' => $nextWeekDate->format('d')
        ));
        return $nextWeekUrl;
    }
   
    protected function generatePreviousWeekUrl(\DateTime $date) {
        $previousWeekDate = DatesTransformer::previousWeek($date);
        $previousWeekUrl = $this->container->get('router')->generate('listByWeek', array(
            'user_id' => 4,
            'year' => $previousWeekDate->format('Y'),
            'month' => $previousWeekDate->format('m'),
            'day' => $previousWeekDate->format('d')
        ));
        return $previousWeekUrl;
    }
  
    protected function generatePreviousMonthUrl(\DateTime $date) {
        $previousMonthDate = DatesTransformer::previousMonth($date);
        $previousMonthUrl = $this->container->get('router')->generate('listByMonth', array(
            'user_id' => 4,
            'year' => $previousMonthDate->format('Y'),
            'month' => $previousMonthDate->format('m'),
            
        ));
        return $previousMonthUrl;
    }
   
    protected function generateNextMonthUrl(\DateTime $date) {
        $nextMonthDate = DatesTransformer::nextMonth($date);
        $nextMonthUrl = $this->container->get('router')->generate('listByMonth', array(
            'user_id' => 4,
            'year' => $nextMonthDate->format('Y'),
            'month' => $nextMonthDate->format('m'),
            
        ));
        return $nextMonthUrl;
    }

}
