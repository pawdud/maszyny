<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\Type\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

class ProjectController extends BaseController
{

    /**
     * 
     * @Route("/", name="homepage")
     * @Route("/projekty", name="projekty")
     */
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', array());

        $crit = array();

        if (!empty($search['q']))
        {
            $crit['q'] = $search['q'];
        }

        $qb = $this->repoProject()->many($crit, false, false, true);
        $this->setViewData('projects', $this->paginate($qb, 15));
        $this->setViewData('search', $search);
        $this->setHeader('Projekty');

        return $this->render('AppBundle:Project:index.html.twig');
    }

    /**
     * @Route("/projekt/dodaj", name="projekt_dodaj")
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $viewData = array();
        $project = new Project();
        $form = $this->createForm(new ProjectType(), $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $project->setUser($this->getUserEntity());
            $this->ormPersistAndFlush($project);
            return $this->redirect($this->generateUrl('projekty'), 'Dodano projekt');
        }

        $this->setHeader('Dodawanie projektu', 'Dodawanie projektu');
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Project:add.html.twig');
    }

    /**
     * @Route("/projekt/edytuj/{id}", name="projekt_edytuj")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function editAction(Request $request, $project)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(new ProjectType(), $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->ormPersistAndFlush($project);

            return $this->redirect($this->generateUrl('projekty'), 'Zakualizowano projekt');
        }

        $this->setHeader('Edycja projektu: ' . $project->getName());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:Project:edit.html.twig');
    }

    /**
     * @Route("/projekt/usun/{id}", name="projekt_usun")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function deleteAction(Request $request, $project)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->ormRemoveAndFlush($project);
        return $this->redirect($this->generateUrl('projekty'), 'Usunięto projekt');
    }

}
