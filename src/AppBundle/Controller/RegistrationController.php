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
        if ($this->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);

        $recaptcha = new ReCaptcha('6Lc7eDAUAAAAAL1Qh1BD791rUTAJToBi1Mgkrf8q');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $resp->isSuccess()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $databaseUser = $this->getDoctrine()->getManager();
            $databaseUser->persist($user);
            $databaseUser->flush();

            $encodedEmail = base64_encode($user->getEmail());

            return $this->redirectToRoute('registration_email', array('email' => $encodedEmail));
        }

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
     * @Route("/register_email/{email}", name="registration_email")
     */
    public function emailAction($email, \Swift_Mailer $mailer)
    {
        $activateUrl = $this->get('router')->generate('user_activation', array(
              'email' => $email
          ));
        $decodedEmail = base64_decode($email);
        $message = (new \Swift_Message('Witamy w dieta i trening! Potwierdź swoją rejestrację!'))
            ->setFrom('lasekdeveloper@gmail.com')
            ->setTo($decodedEmail)
            ->setBody(
                $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                    'emails/registration.html.twig', array('activeLink' => $activateUrl)
                ),
                'text/html'
            );

        $mailer->send($message);

        // or, you can also fetch the mailer service this way
        // $this->get('mailer')->send($message);

        return $this->render('default/confirm.html.twig');
    }

    /**
     * @Route("/activate{email}", name="user_activation")
     */
    public function activationAction($email) {
        $decodedEmail = base64_decode($email);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByEmail($decodedEmail);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for email '.$decodedEmail
            );
        }

        $user->setIsActive(1);
        $em->flush();
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/confirm", name="user_confirmation")
     */
    public function confirmAction() {
        return $this->render('default/confirm.html.twig');
    }



}
