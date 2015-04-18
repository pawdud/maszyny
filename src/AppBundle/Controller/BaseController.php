<?php

namespace AppBundle\Controller;

use AppBundle\Utility\Config;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Util\Debug;

class BaseController extends Controller
{

    protected $viewData = array(
        '__header__' => array(
            'title' => '',
            'name' => '',
            'keywords' => '',
            'description' => ''
        ),
        '__flash__' => ''
    );
    
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    
    protected $em;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        Config::init($container);
        
        $this->init();
    }

    protected function init()
    {        
        $this->em = $this->getDoctrine()->getManager();
        
        $this->viewData['__flash__'] = $this->flashGetFlash('__flash__');
    }
    
    protected function ormPersist($entity){
        $this->em->persist($entity);
    }
    
    protected function ormFlush(){
        try {
            $this->em->flush();
            return true;
        }catch(\Exception $e){
            throw $e;
            exit($e->getMessage());
            return false;
        }
    }    
    
    protected function ormRemove($entity){
         $this->em->remove($entity);
    }
    
    protected function ormRemoveAndFlush($entity){
       $this->ormRemove($entity);
       return $this->ormFlush();
    }

    protected function ormPersistAndFlush($entity){
        $this->ormPersist($entity);
        return $this->ormFlush($entity);
    }   
    
    
    protected function paginate($qb, $limit, $offset = false){
        
        if($offset === false){
            $offset = $this->get('request')->query->get('page', 1);
        }
        
        return $this->get('knp_paginator')->paginate(
              $qb, $offset, $limit   
        );
    }



    /**
     * Zwraca repozytorium użytkowników
     * 
     * @return \AppBundle\Entity\UserRepository
     */  
    protected function repoUser(){
        return $this->em->getRepository('AppBundle:User');
    }
    
    /**
     * Zwraca repozytorium projektów
     * 
     * @return \AppBundle\Entity\ProjectRepository
     */    
    protected function repoProject(){
        return $this->em->getRepository('AppBundle:Project');
    }
    
    /**
     * Zwraca repozytorium części
     * 
     * @return \AppBundle\Entity\PartRepository
     */    
    protected function repoPart(){
        return $this->em->getRepository('AppBundle:Part');
    }
    
    /**
     * Zwraca repozytorium materiałów
     * 
     * @return \AppBundle\Entity\FabricRepository
     */    
    protected function repoFabric2Part(){
        return $this->em->getRepository('AppBundle:Fabric2Part');
    }
    
    /**
     * Zwraca repozytorium powiązania materiałów z częściami
     * 
     * @return \AppBundle\Entity\FabricRepository
     */    
    protected function repoFabric(){
        return $this->em->getRepository('AppBundle:Fabric');
    }
    
    /**
     * Zwraca repozytorium kategorii materiałów
     * 
     * @return \AppBundle\Entity\FabricCategoryRepository
     */    
    protected function repoFabricCategory(){
        return $this->em->getRepository('AppBundle:FabricCategory');
    }
    
    /**
     * Zwraca repozytorium jednostek materiałów
     * 
     * @return \AppBundle\Entity\FabricUnitRepository
     */    
    protected function repoFabricUnit(){
        return $this->em->getRepository('AppBundle:FabricUnit');
    }
    
    /**
     * Zwraca repozytorium technologii
     * 
     * @return \AppBundle\Entity\TechnologyRepository
     */    
    protected function repoTechnology(){
        return $this->em->getRepository('AppBundle:Technology');
    }
    
    /**
     * Zwrazca repozytorium event
     * 
     * @return \AppBunlde\Entity\Event
     */    
    protected function repoEvent(){
        return $this->em->getRepository('AppBundle:Event');
    }
    
    /**
     * Zwrazca repozytorium technology2part
     * 
     * @return \AppBunlde\Entity\Technology2Part
     */    
    protected function repoTechnology2Partt(){
        return $this->em->getRepository('AppBundle:Technology2Part');
    }
        
    public function redirect($url, $flash='', $status = 302)
    {        
        $this->setFlashMsg($flash);
        return parent::redirect($url, $status);
    }
    
    
    public function setFlashMsg($flash){        
        $this->addFlash('__flash__', $flash);
    }
    
    
    
    
    protected function flashGetFlash($key){
        return $this->container->get('session')->getFlashBag()->get('__flash__', array());
    }
    
    
    protected function getUserEntity()
    {
        $user = $this->getUser();
        $repo = $this->repoUser();        
        $entity = $repo->one(array('email' => $user->getUsername()));
        return $entity;        
    }


    protected function setViewData($key, $value)
    {
        $this->viewData[$key] = $value;
    }

    protected function setHeader($title = '', $name = '', $keywords = '', $description = '')
    {
        $this->viewData['__header__']['title'] = $title;
        $this->viewData['__header__']['name'] = $name;
        $this->viewData['__header__']['keywords'] = $keywords;
        $this->viewData['__header__']['description'] = $description;
    }

    public function render($view, array $parameters = array(), \Symfony\Component\HttpFoundation\Response $response = null)
    {
        return parent::render($view, $this->viewData, $response);
    }

}
