<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Entity\ProfileImage;
use AppBundle\Entity\ChangePassword;
use AppBundle\Form\ImageForm;
use AppBundle\Form\ChangeUserData;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\EmailForm;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends Controller
{
	/**
	* @Route("/zarzadzaj_kontem", name="account")
	*/
	public function manageAccountAction(Request $request, FileUploader $fileUploader, UserPasswordEncoderInterface $passwordEncoder)
	{
		$user = $this->getUser();
		$userId = $user->getId();
		$userDataRepository = $this->getDoctrine()->getRepository(UserData::class);
		$userImageRepository = $this->getDoctrine()->getRepository(ProfileImage::class);

		$userData = $userDataRepository->getUserData($userId);

		try {
			$profileImage = $userImageRepository->findOneByUserId($user->getId());
		} catch (\Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException $e) {
			$profileImage = $userImageRepository->findOneByUserId($user->getId());
			$profileImage->setProfileImage(new File('img/profileImage/blank.png'));
		}

		if(is_null($profileImage))
		{
        	$profileImage = new ProfileImage();
			$profileImage->setProfileImage(new File('img/profileImage/blank.png'));
		}
		
		$newUserData = new UserData();
		$newUserData->setGender($userData->getGender());
		$newUserData->setAge($userData->getAge());
		$newUserData->setUserId($user);

		$dataForm = $this->createForm(ChangeUserData::class, $newUserData);
		$imageForm = $this->createForm(ImageForm::class, $profileImage);
		$changePassword = new ChangePassword();
		$changePassowrdForm = $this->createForm(ChangePasswordType::class, $changePassword);
		$emailForm = $this->createForm(EmailForm::class);

		$dataForm->handleRequest($request);
		$imageForm->handleRequest($request);
		$changePassowrdForm->handleRequest($request);
		$emailForm->handleRequest($request);
		$em = $this->getDoctrine()->getManager();

		if ($imageForm->isSubmitted() && $imageForm->isValid())
		{
			$profileImage->setUserId($user);
			$em->persist($profileImage);
			$em->flush($profileImage);
			return $this->redirectToRoute('account');
		}

		if ($changePassowrdForm->isSubmitted() && $changePassowrdForm->isValid())
		{
			$newPassword = $changePassword->getNewPassword();
			$password = $passwordEncoder->encodePassword($user, $newPassword);
			$user->setPassword($password);
			$em->flush($user);
		}

		if ($dataForm->isSubmitted() && $dataForm->isValid())
		{
			$em->persist($newUserData);
			$em->flush($newUserData);
			return $this->redirectToRoute('account');
		}

		if ($emailForm->isSubmitted() && $emailForm->isValid())
		{
			$user->setEmail($emailForm->getData()['email']);
			$em->flush($user);
		}

		return $this->render('default/account.html.twig', array(
			'email' => $user->getEmail(),
			'userData' => $userData,
			'dataForm' => $dataForm->createView(),
			'imageForm' => $imageForm->createView(),
			'profileImage' => $profileImage,
			'changePasswordForm' => $changePassowrdForm->createView(),
			'emailForm' => $emailForm->createView(),
		));
	}
}
