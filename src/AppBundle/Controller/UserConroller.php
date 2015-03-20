<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserAddType;
use AppBundle\Form\Type\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

class UserConroller extends BaseController
{

    /**
     * @Route("/uzytkownicy", name="uzytkownicy")
     */
    public function indexAction(Request $request)
    {
        $crit = array();
        $qb = $this->repoUser()->many($crit, false, false, true);
        $this->setViewData('users', $this->paginate($qb, 15));
        return $this->render('AppBundle:User:index.html.twig');
    }

    /**
     * Dodawanie
     * 
     * @Route("/uzytkownik/dodaj", name="uzytkownik_dodaj")
     */
    public function addAction(Request $request)
    {
        $viewData = array();
        $user = new User();
        $form = $this->createForm(new UserAddType(), $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Kodowanie hasła
            $encoder = $this->get('security.encoder_factory')->getEncoder(new \AppBundle\Entity\User());
            $salt = sha1(uniqid());
            $hashedPassword = $encoder->encodePassword($form->getData()->getPassword(), $salt);
            $user->setPassword($hashedPassword);
            $user->setSalt($salt);
            $user->setRole('ADMIN');
            // Zapisywanie użytkownika
            $this->ormPersistAndFlush($user);
            return $this->redirect($this->generateUrl('uzytkownicy', array('id' => $user->getId())), 'Dodano użytkownika');
        }

        $this->setHeader('Dodawanie użytkownika', 'Dodawanie użytkownika');
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:User:add.html.twig');
    }

    /**
     * @Route("/uzytkownik/edytuj/{id}", name="uzytkownik_edytuj")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function editAction(Request $request, $user)
    {
        $form = $this->createForm(new UserEditType(), $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->ormPersistAndFlush($user);
            return $this->redirect($this->generateUrl('uzytkownik_edytuj', array('id' => $user->getId())), 'Zakualizowano użytkownika');
        }

        $this->setHeader('Edycja uzytkownika: ' . $user->getEmail());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:User:edit.html.twig');
    }

    /**
     * @Route("/uzytkownik/usun/{id}", name="uzytkownik_usun")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function deleteAction(Request $request, $user)
    {
        $this->ormRemoveAndFlush($user);
        return $this->redirect($this->generateUrl('uzytkownicy'), 'Usunięto użytkownika');
    }

}
