<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Part;
use AppBundle\Form\Type\PartType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @Route("/czesci/drzewo/{id}", name="czesci_drzewo")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function treeAction(Request $request, Project $project)
    {

//        $source = array(
//            array(
//                'key' => 0,
//                'title' => $project->getName(),
//                'folder' => true,
//                'expanded' => true,
//                'children' => $this->repoPart()->tree($project->getId(), 0))
//        );
//        Debug::dump($source, 20); exit;

        $this->setViewData('project', $project);
        $this->setViewData('sourceUrl', $this->generateUrl('czesc_drzewko_struktura', array('id' => $project->getId()), UrlGeneratorInterface::ABSOLUTE_URL));
        $this->setHeader('Pojekt: ' . $project->getName() . ' - struktura', $project->getName());
        
        
//        $this->setViewData('source', \json_encode($source));
        // Dostosowanie trzewka na potrzeby pluginu 'fancytree'
//        $crit = array();
//        $qb = $this->repoPart()->many($crit, false, false, true);
//        $this->setViewData('parts', $this->paginate($qb, 15));
//        $this->setViewData('project', $project);
        return $this->render('AppBundle:Part:tree.html.twig');
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
        $this->setViewData('part', $part);
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

    /**
     * (AJAX) Zarządzanie drzewkiem części
     * @Route("/czesc/drzewko/{parent_id}/{child_id}", name="czesci_drzewko") 
     * @ParamConverter("child", class="AppBundle:Part", options={"id" = "child_id"})
     */
    public function axTree($parent_id, Part $child)
    {
        $response = array(
            'status' => 'KO',
            'message' => ''
        );
        $child->setParentId($parent_id);
        if ($this->ormFlush($child))
        {
            $response['status'] = 'OK';
            $response['message'] = 'Przeniesiono pomyślnie';
            $response['data']   = $this->getPartsJsTreeSource($child->getProject());
        } else
        {
            $response['message'] = 'Nie udało się przenieść pozycji';
        }
        return new JsonResponse($response);
    }

    /**
     * (AJAX) Usuwanie materiału z części
     * @Route("/czesc/usun_material/{id}", name="czesc_usun_material")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axFabricDeleteAction(Request $request, $part)
    {
        $idFabric = $request->request->get('idFabric');
        $fabric = $this->repoFabric()->one(array('id' => $idFabric));
        $part->removeFabric($fabric);
        $this->ormPersistAndFlush($part);
        return new Response();
    }

    /**
     * (AJAX) Dodawanie materiału do części
     * @Route("/czesc/dodaj_material/{id}", name="czesc_dodaj_material")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axFabricAddAction(Request $request, $part)
    {
        $idFabric = $request->request->get('idFabric');

        $fabric = $this->repoFabric()->one(array('id' => $idFabric));
        $part->addFabric($fabric);
        $this->ormPersistAndFlush($part);
        return new Response();
    }

    /**
     * (AJAX) Struktura dzrzewka projektu
     * @Route("/czesc/drzewko_struktura/{id}", name="czesc_drzewko_struktura")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function axTreeSourceAction(Project $project)
    {
        $source = $this->getPartsJsTreeSource($project);
        return new JsonResponse($source);
    }

    /**
     * (AJAX) Usuwanie części ze struktury projektu
     * @Route("/czesc/ax_usun/{id}", name="czesc_ax_usun")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axDeleteAction(Request $request, Part $part)
    {
        $response = array(
            'status' => 'KO',
            'message' => ''
        );

        $project = $part->getProject();
        $tree = $this->repoPart()->tree($project->getId(), $part->getId());

        if ($tree === array())
        {
            // Część nie posiada dzieci
            // Możemy usuwać tylko elementy najniższego poziomu (te które nie posiadają dzieci)
            if (!$this->ormRemoveAndFlush($part))
            {
                $response['message'] = 'Nie udało się usunąć części';
            } else
            {
                $response['message'] = 'Usunięto część';
                $response['status'] = 'OK';
                $response['data'] = $this->getPartsJsTreeSource($project);
            }
        } else
        {
            $response['message'] = 'Nie możesz usunąć tej części gdyż zawiera ona inne części.';
        }

        return new JsonResponse($response);
    }

    /**
     * 
     * @param Project $project
     * @return array
     */
    private function getPartsJsTreeSource(Project $project)
    {
        $source = array(
            array(
                'key' => 0,
                'title' => $project->getName(),
                'folder' => true,
                'expanded' => true,
                'children' => $this->repoPart()->tree($project->getId(), 0))
        );
        return $source;
    }

}
