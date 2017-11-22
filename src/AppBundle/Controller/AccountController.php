<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Entity\ProfileImage;
use AppBundle\Form\ImageForm;
use AppBundle\Form\ChangeUserData;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

class AccountController extends Controller
{
	/**
	* @Route("/zarzadzaj_kontem", name="account")
	*/
	public function manageAccountAction(Request $request, FileUploader $fileUploader)
	{
		$user = $this->getUser();
		$email = $user->getEmail();
		$userId = $user->getId();
		$userDataRepository = $this->getDoctrine()->getRepository(UserData::class);
		$userImageRepository = $this->getDoctrine()->getRepository(ProfileImage::class);

		$userData = $userDataRepository->findOneByUserId($userId);

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

		$dataForm = $this->createForm(ChangeUserData::class, $userData);
		$imageForm = $this->createForm(ImageForm::class, $profileImage);

		$dataForm->handleRequest($request);
		$imageForm->handleRequest($request);
		$em = $this->getDoctrine()->getManager();

		if ($imageForm->isSubmitted() && $imageForm->isValid())
		{
			$profileImage->setUserId($user);
			$em->persist($profileImage);
			$em->flush($profileImage);
			return $this->redirectToRoute('account');
		}

		if ($dataForm->isSubmitted() && $dataForm->isValid())
		{
			if($userData->getCalories() != $request->get('calories'))
			{
				$userData = new UserData();
				$em->persist($userData);
			}
			$em->flush($userData);
		}

		return $this->render('default/account.html.twig', array(
			'email' => $email,
			'userData' => $userData,
			'dataForm' => $dataForm->createView(),
			'imageForm' => $imageForm->createView(),
			'profileImage' => $profileImage,
		));
	}
}
