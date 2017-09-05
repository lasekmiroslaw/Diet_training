<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FormController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
     {

         $user = new User();
         $form = $this->createForm(LoginForm::class, $user);
         // get the login error if there is one
         $error = $authUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authUtils->getLastUsername();

         return $this->render('forms/login.html.twig', array(
             'last_username' => $lastUsername,
             'error'         => $error,
             'form' => $form->createView(),
         ));
     }
}
