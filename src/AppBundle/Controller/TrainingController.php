<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\CardioCategory;
use AppBundle\Entity\StrengthTrainingCategory;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserCardio;


class TrainingController extends Controller
{
	/**
	* @Route("dodaj_trening", name="add_training")
	*/
	public function addTrainingAction()
	{
		$cardioCategories = $this->getDoctrine()->getRepository(CardioCategory::class)->findOrderedCategories();
		$strengthCategories = $this->getDoctrine()->getRepository(StrengthTrainingCategory::class)->findOrderedCategories();

		return $this->render('training/categories.html.twig', [
			'cardioCategories' => $cardioCategories,
			'strengthCategories' => $strengthCategories,
		]);
	}

	/**
	* @Route(*"/moj_trening", name="user_trainings",
	*)
	*/
	public function showTrainingsAction(Request $request, SessionInterface $session)
	{
		$user = $this->getUser();
		$pickedDate = $session->get('pickedDate');
		$userStrengthTrainings = $this->getDoctrine()
			->getRepository(UserStrengthTrainingCollection::class)
			->loadUserStrengthTrainings($pickedDate, $user);
		$userCardios = $this->getDoctrine()
			->getRepository(UserCardio::class)
			->loadUserCardios($pickedDate, $user);

		$alert = $session->get('alert');

		return $this->render('training/my_trainings.html.twig', [
			'userTrainings' => $userStrengthTrainings,
			'userCardios' => $userCardios,
			'alert' => $alert,
		]);
	}

	/**
	 * @Route("/usun/{training}/{id}", name="deleteTraining")
	 */
	public function deleteTrainingAction($training = 'trening', $id = 1, SessionInterface $session)
	{
		try
		{
			$user = $this->getUser();
			$em = $this->getDoctrine()->getManager();
			if($training == 'silowy') {
				$itemToDelete = $em->getRepository(UserStrengthTrainingCollection::class)->findItemToDelete($user, $id);
				$em->remove($itemToDelete);
				$em->flush();
			}
			if($training == 'cardio') {
				$itemToDelete = $em->getRepository(UserCardio::class)->findItemToDelete($user, $id);
				$em->remove($itemToDelete);
				$em->flush();
			}

			$session->set('alert', 'alert-danger');
			$this->addFlash(
			   'notice',
			   'Trening usunięty!'
			);
		}
		catch(\Doctrine\ORM\ORMInvalidArgumentException $e)
		{
		}
		finally
		{
			return $this->redirectToRoute('user_trainings');
		}
	}

}
