<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\CardioCategory;
use AppBundle\Entity\StrengthTrainingCategory;
use AppBundle\Entity\StrengthTraining;
use AppBundle\Entity\StrengthTrainingExercise;
use AppBundle\Entity\UserCardio;
use AppBundle\Entity\CardioTraining;
use AppBundle\Form\UserCardioForm;
use AppBundle\Form\ExerciseForm;
use AppBundle\Form\TrainingForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;

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
	* @Route(*"/dodaj_trening_cardio/{category}", name="cardio_category",
	*    requirements={
	*        "category": "|biegi i spacery|rower|fitness i siłownia|czynności codzienne|gry sportowe|wspinaczka|wodne|inne"
	*    }
	*)
	*/
	public function showCardioAction(Request $request, SessionInterface $session, $category = 'kategoria')
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

		if($form->isSubmitted() && $form->isValid())
		{
			$this->flushUserCardio($form, $userCardio, $session);
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
		$trainnigRealId = $training->getId();

		$caloriesper60 = $training->getBurnedCalories();
		$burnedCalories = $this->calculatePerTime($caloriesper60, $trainingTime);

		return $trainingArray = [
			'name' => $name,
			'burnedCalories' => $burnedCalories,
			'trainingId' => $trainnigRealId,
		];
	}

	private function calculatePerTime($caloriesper60, $time)
	{
		$caloriesPerTime = round(($caloriesper60/60) * $time);
		return $caloriesPerTime;
	}

	private function convertToMinutes(array $time)
	{
		$minute = $time['hour']*60 + $time['minute'];
		return $minute;
	}

	private function flushUserCardio($form, UserCardio $userCardio, SessionInterface $session)
	{
		$time = $this->convertToMinutes($form->get('time')->getData());
		$userCardio->setTime($time);

		$pickedDate = $session->get('pickedDate');
		$userCardio->setDate(new \DateTime($pickedDate));

		$userCardio->setUserId($this->getUser());

		$trainingId = $form->get('trainingId')->getData();
		$training = $this->getDoctrine()->getRepository(CardioTraining::class)->find($trainingId);
		$userCardio->setTrainingId($training);

		$dbuserCardio = $this->getDoctrine()->getManager();
		$dbuserCardio->persist($userCardio);
		$dbuserCardio->flush();
	}

	/**
	* @Route(*"/dodaj_trening_siłowy/{category}", name="strength_category",
	*    requirements={
	*        "category": "|fbw|split|push-pull|push-pull-legs|góra-dół|kalistenika|własny"
	*    }
	*)
	*/
	public function showStrengthAction(Request $request, SessionInterface $session, $category = 'kategoria')
	{
		$strengthTrainings = $this->getDoctrine()
			->getRepository(StrengthTrainingCategory::class)
			->findOneBy(array('name' => $category))
			->getTraining();
		if (!$category) {
			throw $this->createNotFoundException();
		}

		return $this->render('training/strengthTrainings.html.twig', [
			'strengthTrainings' => $strengthTrainings,
			'category' => $category,
		]);
	}

	/**
	* @Route(*"/dodaj_trening_siłowy_cwiczenia/{category}/{training}", name="strength_training_exercise",
	*)
	*/
	public function showExerciseAction(Request $request, SessionInterface $session, $category = 'kategoria', $training = 'training')
	{
		$strengtTraining = $this->getDoctrine()
			->getRepository(StrengthTraining::class)
			->find($training);
		if (!$training) {
			throw $this->createNotFoundException();
		}
		$trainingName = $strengtTraining->getName();

		$category = $this->getDoctrine()
			->getRepository(StrengthTrainingCategory::class)
			->find($strengtTraining->getCategoryId())
			->getName();

		$originalExersies = new ArrayCollection();
		foreach($strengtTraining->getExercises() as $exersise) {
			$originalExersies->add($exersise);
		}

		$form = $this->createForm(TrainingForm::class, $strengtTraining);
		$form->handleRequest($request);
		$em = $this->getDoctrine()->getManager();

		if($form->isValid())
		{
	        foreach($originalExersies as $exersise) {
	            if ($strengtTraining->getExercises()->contains($exersise) === false) {
				$em->remove($exersise);
			};
		}
			foreach($strengtTraining->getExercises() as $exersise) {
				$exersise->setTrainingId($strengtTraining);
			}

			$em->persist($strengtTraining);
			$em->flush();
		}

		return $this->render('training/strengthExercise.html.twig', [
			'category' => $category,
			'form' => $form->createView(),
			'training' => $trainingName,
		]);

	}

}
