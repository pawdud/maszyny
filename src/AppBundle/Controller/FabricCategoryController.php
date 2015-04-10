<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FabricCategory;
use AppBundle\Form\Type\FabricCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

/**
 * Kategorie dla meteriałów
 */
class FabricCategoryController extends BaseController
{

    /**
     * @Route("/materialy_kategorie", name="materialy_kategorie")
     */
    public function indexAction(Request $request)
    {
        $crit = array();
        $qb = $this->repoFabricCategory()->many($crit, false, false, true);
        $this->setViewData('fabricCategories', $this->paginate($qb, 15));
        
        $this->setHeader('Kategorie materiałów');
        
        return $this->render('AppBundle:FabricCategory:index.html.twig');
    }

    /**
     * @Route("/material_kategoria/dodaj", name="material_kategoria_dodaj")
     */
    public function addAction(Request $request)
    {
        $viewData = array();
        $fabricCategory = new FabricCategory();
        $form = $this->createForm(new FabricCategoryType(), $fabricCategory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            
            $this->ormPersistAndFlush($fabricCategory);
            return $this->redirect($this->generateUrl('materialy_kategorie'), 'Dodano kategorie materiałów');
        }

        $this->setHeader('Dodawanie kategorii dla materiałów', 'Dodawanie kategorii dla materiałów');
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:FabricCategory:add.html.twig');
    }

    /**
     * @Route("/material_kategoria/edytuj/{id}", name="material_kategoria_edytuj")
     * @ParamConverter("fabricCategory", class="AppBundle:FabricCategory")
     */
    public function editAction(Request $request, $fabricCategory)
    {
        $form = $this->createForm(new FabricCategoryType(), $fabricCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->ormPersistAndFlush($fabricCategory);
            return $this->redirect($this->generateUrl('materialy_kategorie'), 'Zakualizowano kategorię materiałów');
        }

        $this->setHeader('Edycja kategori materiałów: ' . $fabricCategory->getName());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:FabricCategory:edit.html.twig');
    }

    /**
     * @Route("/material_kategoria/usun/{id}", name="material_kategoria_usun")
     * @ParamConverter("fabricCategory", class="AppBundle:FabricCategory")
     */
    public function deleteAction(Request $request, $fabricCategory)
    {
        $this->ormRemoveAndFlush($fabricCategory);
        return $this->redirect($this->generateUrl('materialy_kategorie'), 'Usunięto material');
    }
}