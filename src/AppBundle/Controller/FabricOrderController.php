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
class FabricOrderController extends BaseController {

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

    /**
     * @Route("/zapotrzebowanie/zmien-status/{id}", name="zmien_status")
     * @ParamConverter("fabricOrder", class="AppBundle:FabricOrder", options={"mapping": {"id": "id"}})
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function changeStatusAction(Request $request, $id) {

        $fabricOrder = $this->repoFabricOrder()->findOneBy(array(
            "id" => $id
        ));

        $fabric2part = $fabricOrder->getFabric2Part();
        $fabric = $fabric2part->getFabric();

        if ($fabric->getQuantity() < $fabricOrder->getQuantity()) {
            return $this->redirect($this->generateUrl('zapotrzebowanie'), 'Za mały stan magazynowy ');
        } else {
            $nowy_stan = $fabric->getQuantity() - $fabricOrder->getQuantity();

            $fabric->setQuantity($nowy_stan);
            $status5 = $this->repoStatusy()->find(5);
            $fabricOrder->setStatus($status5);

            $this->em->flush();

            return $this->redirect($this->generateUrl('zapotrzebowanie'), 'Zmieniono status');
        }
    }

}
