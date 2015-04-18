<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
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

    protected function generateNextDayUrl(\DateTime $date, User $current_user) {
        $nextDayDate = DatesTransformer::nextDay($date);
        $nextDayUrl = $this->container->get('router')->generate('listByDay', array(
            'user_id' => $current_user->getId(),
            'year' => $nextDayDate->format('Y'),
            'month' => $nextDayDate->format('m'),
            'day' => $nextDayDate->format('d')
        ));
        return $nextDayUrl;
    }

    protected function generatePreviousDayUrl(\DateTime $date, User $current_user) {
        $previousDayDate = DatesTransformer::previousDay($date);
        $previousDayUrl = $this->container->get('router')->generate('listByDay', array(
            'user_id' => $current_user->getId(),
            'year' => $previousDayDate->format('Y'),
            'month' => $previousDayDate->format('m'),
            'day' => $previousDayDate->format('d')
        ));
        return $previousDayUrl;
    }

    protected function generateNextWeekUrl(\DateTime $date, User $current_user) {
        $nextWeekDate = DatesTransformer::nextWeek($date);
        $nextWeekUrl = $this->container->get('router')->generate('listByWeek', array(
            'user_id' => $current_user->getId(),
            'year' => $nextWeekDate->format('Y'),
            'month' => $nextWeekDate->format('m'),
            'day' => $nextWeekDate->format('d')
        ));
        return $nextWeekUrl;
    }
   
    protected function generatePreviousWeekUrl(\DateTime $date, User $current_user) {
        $previousWeekDate = DatesTransformer::previousWeek($date);
        $previousWeekUrl = $this->container->get('router')->generate('listByWeek', array(
            'user_id' => $current_user->getId(),
            'year' => $previousWeekDate->format('Y'),
            'month' => $previousWeekDate->format('m'),
            'day' => $previousWeekDate->format('d')
        ));
        return $previousWeekUrl;
    }
  
    protected function generatePreviousMonthUrl(\DateTime $date, User $current_user) {
        $previousMonthDate = DatesTransformer::previousMonth($date);
        $previousMonthUrl = $this->container->get('router')->generate('listByMonth', array(
            'user_id' => $current_user->getId(),
            'year' => $previousMonthDate->format('Y'),
            'month' => $previousMonthDate->format('m'),
            
        ));
        return $previousMonthUrl;
    }
   
    protected function generateNextMonthUrl(\DateTime $date, User $current_user) {
        $nextMonthDate = DatesTransformer::nextMonth($date);
        $nextMonthUrl = $this->container->get('router')->generate('listByMonth', array(
            'user_id' => $current_user->getId(),
            'year' => $nextMonthDate->format('Y'),
            'month' => $nextMonthDate->format('m'),
            
        ));
        return $nextMonthUrl;
    }

}
