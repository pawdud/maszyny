<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Part;
use AppBundle\Entity\PartRepository;
use AppBundle\Entity\FabricOrder;
use AppBundle\Entity\FabricOrderRepository;
use AppBundle\Entity\Statusy;
use AppBundle\Form\Type\PartType;
use AppBundle\Entity\Fabric2Part;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PartController extends BaseController {

    /**
     * @Route("/czesci/{id}", name="czesci")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function indexAction(Request $request, Project $project) {
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
    public function treeAction(Request $request, $id, Project $project) {

        $this->setViewData('technologies', $this->repoProject()->getTechnologies($id));




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
    public function addAction(Request $request, Project $project) {
        $viewData = array();
        $part = new Part();
        $form = $this->createForm(new PartType(), $part);




        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $part->setParentId($request->request->all()['parentId']);
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
    public function editAction(Request $request, $part) {
        $form = $this->createForm(new PartType(), $part);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->ormPersistAndFlush($part);

            return $this->redirect($this->generateUrl('czesc_edytuj', array('id' => $part->getId())), 'Zakualizowano część');
        }

        $this->setHeader('Edycja cześci: ' . $part->getName());
        $this->setViewData('form', $form->createView());
        $this->setViewData('part', $part);
        return $this->render('AppBundle:Part:edit.html.twig');
    }

    /**
     * @Route("/czesc/edytuj_technologie/{id}", name="czesc_edytuj_technologie")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function editTechnologyAction(Request $request, $part) {
//        Debug::dump($part->getTechnologies2Part()); exit;


        $this->setViewData('part', $part);
        
        $this->setHeader($part->getName() . ' - technologie');
        
        return $this->render('AppBundle:Part:edit_technology.html.twig');
    }

    /**
     * @Route("/czesc/edytuj_materialy/{id}", name="czesc_edytuj_materialy")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function editMaterialAction(Request $request, $part) {
        $this->setViewData('part', $part);
        $this->setHeader($part->getName() . ' - materiały');        
        return $this->render('AppBundle:Part:edit_fabric.html.twig');
    }

    /**
     * @Route("/czesc/usun/{id}", name="czesc_usun")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function deleteAction(Request $request, $part) {
        $project = $part->getProject();
        $this->ormRemoveAndFlush($part);
        return $this->redirect($this->generateUrl('czesci', array('id' => $project->getId())), 'Usunięto część');
    }

    /**
     * (AJAX) Zarządzanie drzewkiem części
     * @Route("/czesc/drzewko/{parent_id}/{child_id}", name="czesci_drzewko") 
     * @ParamConverter("child", class="AppBundle:Part", options={"id" = "child_id"})
     */
    public function axTree($parent_id, Part $child) {
        $response = array(
            'status' => 'KO',
            'message' => ''
        );
        $child->setParentId($parent_id);
        if ($this->ormFlush($child)) {
            $response['status'] = 'OK';
            $response['message'] = 'Przeniesiono pomyślnie';
            $response['data'] = $this->getPartsJsTreeSource($child->getProject());
        } else {
            $response['message'] = 'Nie udało się przenieść pozycji';
        }
        return new JsonResponse($response);
    }

    /**
     * Dodawanie/Edycja powiązania materiału do części
     * @Route("/czesc/zapisz_material/{id}", name="czesc_zapisz_material")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axFabricSaveAction(Request $request, Part $part) {
        $response = array(
            'status' => 'KO',
            'message' => ''
        );

        $params = isset($request->request->all()['fabric2part']) ? $request->request->all()['fabric2part'] : array();
        $errors = array();

        if (empty($params['fabric_id'])) {
            $errors['fabric_id'] = 'Proszę wybrać materiał';
        }

        if (empty($params['quantity'])) {
            $errors['quantity'] = 'Proszę podać ilość';
        }

        if (empty($errors['fabric_id'])) {
            $fabric = $this->repoFabric()->one(array('id' => $params['fabric_id']));
            if (!$fabric || !$fabric->getId()) {
                $errors['fabric_id'] = 'Nieprawidłowy materiał';
            }
        }

        if ($errors === array()) {

            // Czy takie powiązanie istnieje
            $fabric2Part = $this->repoFabric2Part()->one(array(
                'fabric' => $fabric,
                'part' => $part
            ));
            
            /**
             * znajduje powiązane zapotrzebowanie, jesli istnieje
             */
            $fabricOrder = $this->repoFabricOrder()->findOneBy(array(
                'fabric2part' => $fabric2Part
            ));

            if (!$fabric2Part || !$fabric2Part->getId()) {
                $flash = 'Dodano materiał';
                // Jeśli powiązanie nie istnieje tworzymy je
                $fabric2Part = new Fabric2Part();
                $fabric2Part->setFabric($fabric);
                $fabric2Part->setPart($part);
                $this->em->persist($fabric2Part);

                $fabricOrder = new FabricOrder();
                $fabricOrder->setQuantity($params['quantity']);
                $status0 = $this->repoStatusy()->find(0);
                $fabricOrder->setStatus($status0);
                $fabricOrder->setFabric2Part($fabric2Part);
                $this->em->persist($fabricOrder);
            } else {
                $flash = 'Zaktualizowano materiał';
            }

            $fabricOrder->setQuantity($params['quantity']);

            $fabric2Part->setQuantity($params['quantity']);
            $part->addFabric2Part($fabric2Part);
            $this->em->flush();
            $this->setFlashMsg($flash);
            $response['status'] = 'OK';
        }




        return new JsonResponse($response);
    }

    /**
     * Usuwanie materiału z części
     * @Route("/czesc/usun_material/{id}/{id_fabric2part}", name="czesc_usun_material")
     * @ParamConverter("part", options={"mapping": {"id": "id"}})
     * @ParamConverter("fabric2part", options={"mapping": {"id_fabric2part": "id"}})
     * @ParamConverter("fabricOrder", class="AppBundle:FabricOrder", options={"repository_method" = "findOneByfabric2part", "mapping": {"fabric2part": "id"}})
     */
    public function fabricDeleteAction(Request $request, Part $part, Fabric2Part $fabric2part, FabricOrder $fabricOrder) {
        
        $msg = $this->ormRemoveAndFlush($fabricOrder) ? 'Usunięto materiał' : 'Nie udało się usunąć materiału';
        $msg = $this->ormRemoveAndFlush($fabric2part) ? 'Usunięto materiał' : 'Nie udało się usunąć materiału';
        return $this->redirect($this->generateUrl('czesc_edytuj_materialy', array('id' => $part->getId())), $msg);
    }

    /**
     * (AJAX) Usuwanie procesu technologicznego z  części
     * @Route("/czesc/usun_technologie/{id}", name="czesc_usun_technologie")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axTechnologyDeleteAction(Request $request, $part) {
        $idTechnology = $request->request->get('idTechnology');
        $technology = $this->repoTechnology()->one(array('id' => $idTechnology));
        $part->removeTechnology($technology);
        $this->ormPersistAndFlush($part);
        return new Response();
    }

    /**
     * (AJAX) Dodawanie procesu technologicznego do części
     * @Route("/czesc/dodaj_technologie/{id}", name="czesc_dodaj_technologie")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axTechnologyAddAction(Request $request, $part) {
        $idTechnology = $request->request->get('idTechnology');
        $technology = $this->repoTechnology()->one(array('id' => $idTechnology));
        $part->addTechnology($technology);
        $this->ormPersistAndFlush($part);
        return new Response();
    }

    /**
     * (AJAX) Struktura dzrzewka projektu
     * @Route("/czesc/drzewko_struktura/{id}", name="czesc_drzewko_struktura")
     * @ParamConverter("project", class="AppBundle:Project")
     */
    public function axTreeSourceAction(Request $request, $id, Project $project) {

        $idPartSelected = $request->query->get('idPart', false);
        
        
        $technologyId = $request->query->get('technology_id', false);
        $partsIdsTree = array();
        if($technologyId){
            $partsIds       = $this->repoProject()->getPartsIdByTechnology($id, $technologyId);
            $partsData   = $this->repoProject()->getPartsData($id);
            $partsIdsTree   = array_values($this->repoProject()->getPartsTree($partsIds, $partsData));
        }       
        
        
        $source = $this->getPartsJsTreeSource($project, $partsIdsTree, $idPartSelected);

        return new JsonResponse($source);
    }

    /**
     * (AJAX) Usuwanie części ze struktury projektu
     * @Route("/czesc/ax_usun/{id}", name="czesc_ax_usun")
     * @ParamConverter("part", class="AppBundle:Part")
     */
    public function axDeleteAction(Request $request, Part $part) {
        $response = array(
            'status' => 'KO',
            'message' => ''
        );

        $project = $part->getProject();
        $tree = $this->repoPart()->tree($project->getId(), $part->getId());

        if ($tree === array()) {
            // Część nie posiada dzieci
            // Możemy usuwać tylko elementy najniższego poziomu (te które nie posiadają dzieci)
            if (!$this->ormRemoveAndFlush($part)) {
                $response['message'] = 'Nie udało się usunąć części';
            } else {
                $response['message'] = 'Usunięto część';
                $response['status'] = 'OK';
                $response['data'] = $this->getPartsJsTreeSource($project);
            }
        } else {
            $response['message'] = 'Nie możesz usunąć tej części gdyż zawiera ona inne części.';
        }

        return new JsonResponse($response);
    }

    /**
     * 
     * @param Project $project
     * @return array
     */

    private function getPartsJsTreeSource(Project $project, array $partsIdsTree = array(), $idPartSelected=false)
    {
        $source = array(
            array(
                'key' => 0,
                'title' => $project->getName(),
                'folder' => true,
                'expanded' => true,
                'children' => $this->repoPart()->tree($project->getId(), 0, PartRepository::PARSE_MODE_TREE_FOR_JAVASCRIPT, $partsIdsTree, $idPartSelected))
        );
        return $source;
    }

}
