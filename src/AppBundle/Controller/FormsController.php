<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserData;
use AppBundle\Form\LoginForm;
use AppBundle\Form\UserDataForm;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\changePassword;
use AppBundle\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormsController extends Controller
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

            $userData = new UserData();
            $form = $this->createForm(UserDataForm::class, $userData);
            $form->handleRequest($request);

            if($request->request->get('age')){

                $age = $_POST['age'];
                $weight = $_POST['weight'];
                $height = $_POST['height'];
                $activity = $_POST['activity'];
                $gender = $_POST['gender'];

                $calories = ceil((66.5 + (13.7*$weight) + (5*$height) - (6.8*$age))*$activity);


                $arr = ['user_calories' => $calories];


                return new JsonResponse($arr);

               }

            return $this->render('forms/data_form.html.twig', array(
                'form' => $form->createView(),
            ));
        }




}
