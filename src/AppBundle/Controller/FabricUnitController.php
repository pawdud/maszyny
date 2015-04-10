<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FabricUnit;
use AppBundle\Form\Type\FabricUnitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

/**
 * Jednostki dla materiałów
 */
class FabricUnitController extends BaseController
{

    /**
     * @Route("/materialy_jednostki", name="materialy_jednostki")
     */
    public function indexAction(Request $request)
    {
        $crit = array();
        $qb = $this->repoFabricUnit()->many($crit, false, false, true);
        $this->setViewData('fabricUnits', $this->paginate($qb, 15));

        $this->setHeader('Jednostki dla materiałów');
        return $this->render('AppBundle:FabricUnit:index.html.twig');
    }

    /**
     * @Route("/material_jednostka/dodaj", name="material_jednostka_dodaj")
     */
    public function addAction(Request $request)
    {
        $viewData = array();
        $fabricUnit = new FabricUnit();
        $form = $this->createForm(new FabricUnitType(), $fabricUnit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $this->ormPersistAndFlush($fabricUnit);
            return $this->redirect($this->generateUrl('materialy_jednostki'), 'Dodano jednostkę dla materiałów');
        }

        $this->setHeader('Dodawanie jednostki dla materiałów', 'Dodawanie jednostki dla materiałów');
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:FabricUnit:add.html.twig');
    }

    /**
     * @Route("/material_jednostka/edytuj/{id}", name="material_jednostka_edytuj")
     * @ParamConverter("fabricUnit", class="AppBundle:FabricUnit")
     */
    public function editAction(Request $request, $fabricUnit)
    {
        $form = $this->createForm(new FabricUnitType(), $fabricUnit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->ormPersistAndFlush($fabricUnit);
            return $this->redirect($this->generateUrl('materialy_jednostki'), 'Zakualizowano jednostę dla materiałów');
        }

        $this->setHeader('Edycja kategori materiałów: ' . $fabricUnit->getName());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:FabricUnit:edit.html.twig');
    }

    /**
     * @Route("/material_jednostka/usun/{id}", name="material_jednostka_usun")
     * @ParamConverter("fabricUnit", class="AppBundle:FabricUnit")
     */
    public function deleteAction(Request $request, $fabricUnit)
    {
        $this->ormRemoveAndFlush($fabricUnit);
        return $this->redirect($this->generateUrl('materialy_jednostki'), 'Usunięto jednostę dla materiałów');
    }

}
