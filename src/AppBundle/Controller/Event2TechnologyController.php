<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventRepository;
use AppBundle\Entity\Technology2Part;
use AppBundle\Entity\Part;
use AppBundle\Form\Type\Event2TechnologyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

class Event2TechnologyController extends BaseController
{
    /**
     * Lista
     * 
     * @Route("/czesc_technologia_zdarzenia/{id}", name="czesc_technologia_zdarzenia")
     * @ParamConverter("technology2part", class="AppBundle:Technology2Part")
     */    
    public function defaultAction(Request $request, Technology2Part $technology2part){
        $crit = array();
        $crit['technology2part'] = $technology2part;
        $crit['__order__'] = array(
            EventRepository::getAlias() . '.timeStart' => 'DESC'
        );
        
        $this->setViewData('events', $this->repoEvent()->many($crit, false, false));
        return $this->render('AppBundle:Event2Technology:default.html.twig');
    }
    
    

    /**
     * Dodawanie
     * 
     * @Route("/czesc_technologia_zdarzenie/dodaj/{id}", name="czesc_technologia_zdarzenie_dodaj")
     * @ParamConverter("technology2part", class="AppBundle:Technology2Part")
     */
    public function addAction(Request $request, Technology2Part $technology2part)
    {
        $event = new Event();        
        $event->setUser($this->getUserEntity());
        $event->setTechnology2Part($technology2part);
        $form = $this->createForm(new Event2TechnologyType(), $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $msg = $this->ormPersistAndFlush($event) ? 'Dodano zdarzenie' : 'Nie udało się dodać zdarzenia';
            $this->setFlashMsg($msg);
            $response = new Response();
            $response->headers->set('ttm-reload', 1);
            return $response;
        }

        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Event2Technology:add.html.twig');
    }

    /**
     * @Route("/czesc_technologia/edytuj/{id}/{id_technology2part}", name="czesc_technologia_edytuj")
     * @ParamConverter("part", class="AppBundle:Part", options={"mapping": {"id": "id"}})
     * @ParamConverter("technology2part", class="AppBundle:Technology2Part", options={"mapping": {"id_technology2part": "id"}})
     */
//    public function editAction(Request $request, Part $part, Technology2Part $technology2part)
//    {               
//        $form = $this->createForm(new Technology2PartEditType(), $technology2part);
//        $form->handleRequest($request);
//        
//        if($form->isSubmitted() && $form->isValid()){  
//            $msg = $this->ormPersistAndFlush($technology2part) ? 'Zaktualizowano technologię' : 'Nie udało się zaktualizować technologi';
//            $this->setFlashMsg($msg);            
//            $response = new Response();
//            $response->headers->set('ttm-reload', 1);     
//            return $response;
//        }
//        $this->setViewData('technology2part', $technology2part);
//        $this->setViewData('form', $form->createView());        
//        return $this->render('AppBundle:Technology2Part:edit.html.twig');
//    }

    /**
     * @Route("/czesc_technologia/usun/{id}/{id_technology2part}", name="czesc_technologia_usun")
     * @ParamConverter("part", class="AppBundle:Part", options={"mapping": {"id": "id"}})
     * @ParamConverter("technology2part", class="AppBundle:Technology2Part", options={"mapping": {"id_technology2part": "id"}})
     */
//    public function deleteAction(Request $request, Part $part, Technology2Part $technology2part)
//    {        
//        $msg = $this->ormRemoveAndFlush($technology2part) ? 'Usunięto technologię' : 'Nie udało się usunąć technologi';        
//        return $this->redirect($this->generateUrl('czesc_edytuj_technologie', array('id' => $part->getId())), $msg);
//    }
}
