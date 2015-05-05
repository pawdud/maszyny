<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Util\Debug;

class CalendarController extends BaseController
{

    /**
     * @Route("/kalendarz", name="kalendarz")
     */
    public function indexAction()
    {
        
        
        
        
        
        
        
        
        
        
        
        return $this->render('AppBundle:Calendar:index.html.twig');
    }

}
