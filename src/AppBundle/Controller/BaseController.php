<?php

namespace AppBundle\Controller;

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
        $this->em->flush();
    }    
    
    protected function ormRemove($entity){
         $this->em->remove($entity);
    }
    
    protected function ormRemoveAndFlush($entity){
       $this->ormRemove($entity);
       $this->ormFlush();
    }

    protected function ormPersistAndFlush($entity){
        $this->ormPersist($entity);
        $this->ormFlush($entity);
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
     * Zwrazca repozytorium użytkowników
     * 
     * @return \AppBunlde\Entity\UserRepository
     */  
    protected function repoUser(){
        return $this->em->getRepository('AppBundle:User');
    }
    
    /**
     * Zwrazca repozytorium projektów
     * 
     * @return \AppBunlde\Entity\ProjectRepository
     */    
    protected function repoProject(){
        return $this->em->getRepository('AppBundle:Project');
    }
    
    /**
     * Zwrazca repozytorium części
     * 
     * @return \AppBunlde\Entity\ProjectRepository
     */    
    protected function repoPart(){
        return $this->em->getRepository('AppBundle:Part');
    }
    
    /**
     * Zwrazca repozytorium materiałów
     * 
     * @return \AppBunlde\Entity\MaterialRepository
     */    
    protected function repoFabric(){
        return $this->em->getRepository('AppBundle:Fabric');
    }
    
    
    public function redirect($url, $flash='', $status = 302)
    {        
        $this->addFlash('__flash__', $flash);
        return parent::redirect($url, $status);
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
