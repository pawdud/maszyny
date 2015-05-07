<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FabricOrder;
use AppBundle\Form\Type\FabricOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

/**
 * Description of OrderController
 * 
 * @package OrderController
 * @author Tomasz Ruchała; projektowaniestronsacz.pl
 * 
 * @version v. 1.0
 * @license MIT
 * 
 */
class OrderController extends BaseController {

    /**
     * @Route("/zapotrzebowanie", name="zapotrzebowanie")
     */
    public function indexAction(Request $request) {
        $crit = array();
        $search = $request->query->get('search', array());
        if (!empty($search['q'])) {
            $crit['q'] = $search['q'];
        }

        $qb = $this->repoFabricOrder()->many($crit, false, false, true);
        $this->setViewData('orders', $this->paginate($qb, 15));
        $this->setViewData('search', $search);
        return $this->render('AppBundle:FabricOrder:index.html.twig');
    }

}
