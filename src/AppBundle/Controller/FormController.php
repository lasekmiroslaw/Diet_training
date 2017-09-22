<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use AppBundle\Form\UserDataForm;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\changePassword;
use AppBundle\Form\ChangePasswordType;

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

     /**
      * @Route("/change_password", name="change_password")
      */
      public function changePasswordAction(Request $request)
       {

         $changePasswordModel = new ChangePassword();
         $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             // perform some action,
             // such as encoding with MessageDigestPasswordEncoder and persist
             return $this->redirect($this->generateUrl('homepage'));
         }

         return $this->render(':security:changepassword.html.twig', array(
             'form' => $form->createView(),
         ));
       }

       /**
        * @Route("/data_form", name="data_form")
        */
       public function userDataAction(Request $request, AuthenticationUtils $authUtils)
        {

            $user = new User();
            $form = $this->createForm(UserDataForm::class, $user);
            $form->handleRequest($request);
            return $this->render('forms/data_form.html.twig', array(
                'form' => $form->createView(),
            ));
        }
}
