<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\CardioCategory;
use AppBundle\Entity\UserCardio;
use AppBundle\Form\UserCardioForm;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrainingController extends Controller
{
	/**
	* @Route("dodaj_trening", name="add_training")
	*/
	public function addTrainingAction()
	{
		$categories = $this->getDoctrine()->getRepository(CardioCategory::class)->findAll();
		return $this->render('training/categories.html.twig', [
			'categories' => $categories,
		]);
	}

	/**
	* @Route(
		*"/dodaj_trening_cardio/{category}", name="cardio_category",
	*    requirements={
	*        "category": "|biegi i spacery|rower|fitness i siłownia|czynności codzienne|gry sportowe|wspinaczka|wodne|inne"
	*    }
	*)
	*/
	public function showCategoriesAction(Request $request, SessionInterface $session, $category = 'kategoria')
	{
		$cardioTrainings = $this->getDoctrine()
			->getRepository(CardioCategory::class)
			->findOneBy(array('name' => $category))
			->getTraining();
		if (!$category) {
			throw $this->createNotFoundException();
		}

		$userCardio = new UserCardio();
		$form = $this->createForm(UserCardioForm::class, $userCardio);
		$form->handleRequest($request);

		if($request->isXmlHttpRequest())
		{
			$trainingArray = $this->getTrainingInfo($cardioTrainings);
			return new JsonResponse($trainingArray);
		}

		return $this->render('training/cardioTrainings.html.twig', [
			'cardioTrainings' => $cardioTrainings,
			'form' => $form->createView(),
		]);
	}

	private function getTrainingInfo($cardioTrainings)
	{
		$request = Request::createFromGlobals();
		$trainingId = $request->get('trainingId');
		$trainingTime = $request->get('trainingTime');
		$training = $cardioTrainings->get($trainingId);
		$name = $training->getName();

		$caloriesper60 = $training->getBurnedCalories();
		$burnedCalories = $this->calculatePerTime($caloriesper60, $trainingTime);

		return $trainingArray = [
			'name' => $name,
			'burnedCalories' => $burnedCalories,
		];
	}

	private function calculatePerTime($caloriesper60, $time)
	{
		$caloriesPerTime = round(($caloriesper60/60) * $time);
		return $caloriesPerTime;
	}
}
