<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterForm;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ReCaptcha\ReCaptcha;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);

        // recaptcha
        $recaptcha = new ReCaptcha('6Lc7eDAUAAAAAL1Qh1BD791rUTAJToBi1Mgkrf8q');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $resp->isSuccess()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            return $this->redirectToRoute('user_confirmation');
        }

        # check if captcha response isn't get throw a message
        if($form->isSubmitted() &&  $form->isValid() && !$resp->isSuccess()){

            $this->addFlash(
               'error',
               'Proszę potwierdzić, że nie jesteś robotem'
           );
        }

        return $this->render(
            'forms/register.html.twig',
            array('form' => $form->createView())
        );
      }

    /**
     * @Route("/confirm", name="user_confirmation")
     */
    public function confirmAction() {
        return $this->render('default/confirm.html.twig');
    }
}
