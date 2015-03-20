<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Part;
use AppBundle\Form\Type\PartType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

class PartController extends BaseController
{

    /**
     * @Route("/czesci/{id}", name="czesci")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function indexAction(Request $request, Project $project)
    {
        $crit = array();
        $qb = $this->repoPart()->many($crit, false, false, true);
        $this->setViewData('parts', $this->paginate($qb, 15));
        $this->setViewData('project', $project);
        return $this->render('AppBundle:Part:index.html.twig');
    }

    /**
     * @Route("/czesc/dodaj/{id}", name="czesc_dodaj")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function addAction(Request $request, Project $project)
    {
        $viewData = array();
        $part = new Part();
        $form = $this->createForm(new PartType(), $part);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $part->setProject($project);
            $part->setUser($this->getUserEntity());
            $this->ormPersistAndFlush($part);
            return $this->redirect($this->generateUrl('czesc_edytuj', array('id' => $part->getId())), 'Dodano część');
        }

        $this->setHeader('Dodawanie części', 'Dodawanie części');
        $this->setViewData('form', $form->createView());
        $this->setViewData('project', $project);
        return $this->render('AppBundle:Part:add.html.twig');
    }

    /**
     * @Route("/czesc/edytuj/{id}", name="czesc_edytuj")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function editAction(Request $request, $part)
    {
        $form = $this->createForm(new PartType(), $part);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->ormPersistAndFlush($part);
          
            return $this->redirect($this->generateUrl('czesc_edytuj', array('id' => $part->getId())), 'Zakualizowano część');
        }

        $this->setHeader('Edycja cześci: ' . $part->getName());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Part:edit.html.twig');
    }
    
    /**
     * @Route("/czesc/usun/{id}", name="czesc_usun")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function deleteAction(Request $request, $part)
    {
        $project = $part->getProject();        
        $this->ormRemoveAndFlush($part);
        return $this->redirect($this->generateUrl('czesci', array('id' => $project->getId())), 'Usunięto część');
    }    
}