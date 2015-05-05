<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserAddType;
use AppBundle\Form\Type\UserEditType;
use AppBundle\Form\Type\UserPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Util\Debug;

/**
 * Użytkownicy
 */
class UserConroller extends BaseController
{

    /**
     * @Route("/uzytkownicy", name="uzytkownicy")
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Brak upawnień');
        
        $search = $request->query->get('search', array());

        $crit = array();

        if (!empty($search['q']))
        {
            $crit['q'] = $search['q'];
        }
        $qb = $this->repoUser()->many($crit, false, false, true);
        $this->setViewData('users', $this->paginate($qb, 15));
        $this->setViewData('search', $search);
        return $this->render('AppBundle:User:index.html.twig');
    }

    /**
     * Dodawanie
     * 
     * @Route("/uzytkownik/dodaj", name="uzytkownik_dodaj")
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Brak uprawnień');
        
        $viewData = array();
        $user = new User();
        $form = $this->createForm(new UserAddType(), $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // Kodowanie hasła            
            list($hashedPassword, $salt) = $this->encodePassword($form->getData()->getPassword());
            $user->setPassword($hashedPassword);
            $user->setSalt($salt);
            // Zapisywanie użytkownika
            $this->ormPersistAndFlush($user);
            return $this->redirect($this->generateUrl('uzytkownicy', array('id' => $user->getId())), 'Dodano użytkownika');
        }

        $this->setHeader('Dodawanie użytkownika', 'Dodawanie użytkownika');
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:User:add.html.twig');
    }

    /**
     * Edycja
     * 
     * @Route("/uzytkownik/edytuj/{id}", name="uzytkownik_edytuj")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function editAction(Request $request, $user)
    {
        $this->denyAccessUnlessGranted('edit', $user, 'Brak uprawnień');

        $form = $this->createForm(new UserEditType(), $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            list($hashedPassword, $salt) = $this->encodePassword($form->getData()->getPassword());
            $user->setPassword($hashedPassword);
            $user->setSalt($salt);
            $this->ormPersistAndFlush($user);

            return $this->redirect($this->generateUrl('uzytkownicy'), 'Zaktualizowano dane użytkownika: ' . $user->getEmail());
        }

        $this->setHeader('Edycja uzytkownika: ' . $user->getEmail());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:User:edit.html.twig');
    }

    /**
     * Zmiana hasła
     * 
     * @Route("/uzytkownik/haslo/{id}", name="uzytkownik_haslo")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function passwordAction(Request $request, $user)
    {
        $this->denyAccessUnlessGranted('edit', $user, 'Brak uprawnień');


        $form = $this->createForm(new UserPasswordType(), $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            list($hashedPassword, $salt) = $this->encodePassword($form->getData()->getPassword());
            $user->setPassword($hashedPassword);
            $user->setSalt($salt);
            $this->ormPersistAndFlush($user);
            return $this->redirect($this->generateUrl('uzytkownicy', array('id' => $user->getId())), 'Zmieniono hasło użytkownika: ' . $user->getEmail());
        }

        $this->setHeader('Zmiana hasła użytkownika: ' . $user->getEmail(), 'Zmiana hasła użytkownika: ' . $user->getEmail());
        $this->setViewData('form', $form->createView());
        return $this->render('AppBundle:User:password.html.twig');
    }

    /**
     * Usuwanie
     * @Route("/uzytkownik/usun/{id}", name="uzytkownik_usun")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function deleteAction(Request $request, $user)
    {
        $this->denyAccessUnlessGranted('delete', $user, 'Brak uprawnień');
        $this->ormRemoveAndFlush($user);
        return $this->redirect($this->generateUrl('uzytkownicy'), 'Usunięto użytkownika:' . $user->getEmail());
    }

    /**
     * 
     * Kodowanie hasła
     * 
     * @param type $password
     * @return array(
     *  0 => $hashedPassword,
     *  1 => $salt
     * )
     */
    private function encodePassword($password)
    {
        $encoder = $this->get('security.encoder_factory')->getEncoder(new \AppBundle\Entity\User());
        $salt = sha1(uniqid());
        $hashedPassword = $encoder->encodePassword($password, $salt);
        return array(
            0 => $hashedPassword,
            1 => $salt,
        );
    }

}
