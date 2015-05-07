<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Technology2Part;
use AppBundle\Entity\Part;
use AppBundle\Form\Type\Technology2PartAddType;
use AppBundle\Form\Type\Technology2PartEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

class Technology2PartController extends BaseController
{

    /**
     * Przypisywanie technologii do części
     * 
     * @Route("/czesc_technologia/dodaj/{id}", name="czesc_technologia_dodaj")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function addAction(Request $request, Part $part)
    {
        $this->setViewData('technologies', $this->repoTechnology()->many());
        $selectedTechnologies = $request->request->get('technologies', array());
        
        if (count($selectedTechnologies))
        {
            foreach ($selectedTechnologies as $k => $v)
            {
                $technology = $this->repoTechnology()->one(array('id' => $k));
                $technology2Part = new Technology2Part();
                $technology2Part->setTechnology($technology);
                $technology2Part->setPart($part);
                $this->ormPersistAndFlush($technology2Part);
            }

            $this->setFlashMsg('Dodano technologie');
            $response = new Response();
            $response->headers->set('ttm-reload', 1);
            return $response;
        }

        return $this->render('AppBundle:Technology2Part:add.html.twig');
    }

    /**
     * @Route("/czesc_technologia/dodaj/{id}", name="czesc_technologia_dodaj")
     * @ParamConverter("part", class="AppBundle:Part")
     */
//    public function addAction(Request $request, Part $part)
//    {
//        
//        $technology2part = new Technology2Part();
//        $technology2part->setPart($part);                
//        $form = $this->createForm(new Technology2PartAddType(), $technology2part);
//        
//        $form->handleRequest($request);
//        
//        if($form->isSubmitted() && $form->isValid()){              
//            $msg = $this->ormPersistAndFlush($technology2part) ? 'Dodano technologię' : 'Nie udało się dodać technologi';
//            $this->setFlashMsg($msg);
//            
//            $response = new Response();
//            $response->headers->set('ttm-reload', 1);            
//            return $response;
//        }       
//        
//        $this->setViewData('form', $form->createView());        
//        return $this->render('AppBundle:Technology2Part:add.html.twig');
//    }

    /**
     * @Route("/czesc_technologia/edytuj/{id}/{id_technology2part}", name="czesc_technologia_edytuj")
     * @ParamConverter("part", class="AppBundle:Part", options={"mapping": {"id": "id"}})
     * @ParamConverter("technology2part", class="AppBundle:Technology2Part", options={"mapping": {"id_technology2part": "id"}})
     */
    public function editAction(Request $request, Part $part, Technology2Part $technology2part)
    {
        $form = $this->createForm(new Technology2PartEditType(), $technology2part);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $msg = $this->ormPersistAndFlush($technology2part) ? 'Zaktualizowano technologię' : 'Nie udało się zaktualizować technologi';
            $this->setFlashMsg($msg);
            $response = new Response();
            $response->headers->set('ttm-reload', 1);
            return $response;
        }
        $this->setViewData('technology2part', $technology2part);
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Technology2Part:edit.html.twig');
    }

    /**
     * @Route("/czesc_technologia/usun/{id}/{id_technology2part}", name="czesc_technologia_usun")
     * @ParamConverter("part", class="AppBundle:Part", options={"mapping": {"id": "id"}})
     * @ParamConverter("technology2part", class="AppBundle:Technology2Part", options={"mapping": {"id_technology2part": "id"}})
     */
    public function deleteAction(Request $request, Part $part, Technology2Part $technology2part)
    {
        $msg = $this->ormRemoveAndFlush($technology2part) ? 'Usunięto technologię' : 'Nie udało się usunąć technologi';
        return $this->redirect($this->generateUrl('czesc_edytuj_technologie', array('id' => $part->getId())), $msg);
    }

}
