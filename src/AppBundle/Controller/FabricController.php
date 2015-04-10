<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fabric;
use AppBundle\Form\Type\FabricType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

/**
 * Materaiły
 */
class FabricController extends BaseController
{

    /**
     * @Route("/materialy", name="materialy")
     */
    public function indexAction(Request $request)
    {
        $crit = array();
        $qb = $this->repoFabric()->many($crit, false, false, true);
        $this->setViewData('fabrics', $this->paginate($qb, 15));
        return $this->render('AppBundle:Fabric:index.html.twig');
    }

    /**
     * @Route("/material/dodaj", name="material_dodaj")
     */
    public function addAction(Request $request)
    {
        $viewData = array();
        $fabric = new Fabric();
        $form = $this->createForm(new FabricType(), $fabric);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $fabric->setUser($this->getUserEntity());
            $this->ormPersistAndFlush($fabric);
            return $this->redirect($this->generateUrl('material_edytuj', array('id' => $fabric->getId())), 'Dodano materiał');
        }

        $this->setHeader('Dodawanie materiału', 'Dodawanie materiału');
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Fabric:add.html.twig');
    }

    /**
     * @Route("/material/edytuj/{id}", name="material_edytuj")
     * @ParamConverter("fabric", class="AppBundle:Fabric")
     */
    public function editAction(Request $request, $fabric)
    {
        $form = $this->createForm(new FabricType(), $fabric);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->ormPersistAndFlush($fabric);

            return $this->redirect($this->generateUrl('material_edytuj', array('id' => $fabric->getId())), 'Zakualizowano material');
        }

        $this->setHeader('Edycja materiału: ' . $fabric->getName());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Fabric:edit.html.twig');
    }

    /**
     * @Route("/material/usun/{id}", name="material_usun")
     * @ParamConverter("fabric", class="AppBundle:Fabric")
     */
    public function deleteAction(Request $request, $fabric)
    {
        $this->ormRemoveAndFlush($fabric);
        return $this->redirect($this->generateUrl('materialy'), 'Usunięto material');
    }

    /**
     * @Route("/material/szukaj", name="material_szukaj")
     * 
     */
    public function axSearchAction(Request $request)
    {
        $return = array();
        $term = $request->query->get('term');

        $fabrics = $this->repoFabric()->many(
                array('q' => $term), 0, 10
        );

        if (is_array($fabrics) && !empty($fabrics))
        {
            foreach ($fabrics as $fabric)
            {
                $return[] = array(
                    'id' => $fabric->getId(),
                    'value' => $fabric->getName() . ' [' . $fabric->getCode() . ']',
                    'label' => $fabric->getName() . ' [' . $fabric->getCode() . ']',
                );
            }
        }
        return new JsonResponse($return);
    }

}
