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
use AppBundle\Entity\MyStrengthTraining;
use AppBundle\Entity\UserStrengthTraining;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserStrengthExerciseCollection;
use AppBundle\Form\UserCardioForm;
use AppBundle\Form\ExerciseForm;
use AppBundle\Form\TrainingForm;
use AppBundle\Form\MyTrainingForm;
use AppBundle\Form\UserTrainingForm;
use AppBundle\Form\CollectionForm;
use AppBundle\Form\TrainingCollectionForm;
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

		return $this->render('training/cardio_trainings.html.twig', [
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

		return $this->render('training/strength_trainings.html.twig', [
			'strengthTrainings' => $strengthTrainings,
			'category' => $category,
		]);
	}

	/**
	* @Route(*"/dodaj_moj_trening", name="my_strength_training",
	*)
	*/
	public function showMyTrainingAction(Request $request, SessionInterface $session, $category = 'kategoria', $training = 'training')
	{
		$userId = $this->getUser()->getId();
		$myTrainings = $this->getDoctrine()->getRepository(MyStrengthTraining::class)->findMyTrainings($userId);

		$myStrengthTraining = new MyStrengthTraining();
		$form = $this->createForm(MyTrainingForm::class, $myStrengthTraining);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$myStrengthTraining->setUserId($this->getUser());
			$em = $this->getDoctrine()->getManager();
			$em->persist($myStrengthTraining);
			$em->flush();

			return $this->redirectToRoute('my_strength_training');
		}

		return $this->render('training/my_strength_trainings.html.twig', [
			'myTrainings' => $myTrainings,
			'form' => $form->createView(),
		]);
	}



	/**
	 * @Route("/usuntrening/{id}", name="deleteMyTraining")
	 */
	public function deleteAction($id = 1, SessionInterface $session)
	{
		try
		{
		$em = $this->getDoctrine()->getManager();
		$myTraining = $em->getRepository(MyStrengthTraining::class)->find($id);
		$em->remove($myTraining);
		$em->flush();
		}
		catch(\Doctrine\ORM\ORMInvalidArgumentException $e)
		{
		}
		finally
		{
			return $this->redirectToRoute('my_strength_training');
		}
	}

	/**
	* @Route(*"/dodaj_moje_cwiczenia/{training}", name="my_strength_training_exercise",
	*)
	*/
	public function showMyExerciseAction(Request $request, $training = 'training')
	{
		$myStrengtTraining = $this->getDoctrine()
			->getRepository(MyStrengthTraining::class)
			->find($training);

		if (!$training) {
			throw $this->createNotFoundException();
		}
		$trainingName = $myStrengtTraining->getName();

		$originalExersies = new ArrayCollection();
		foreach($myStrengtTraining->getMyExercises() as $exersise) {
			$originalExersies->add($exersise);
		}

		$form = $this->createForm(TrainingForm::class, $myStrengtTraining);
		$form->handleRequest($request);
		$em = $this->getDoctrine()->getManager();

		if($form->isSubmitted() && $form->isValid())
		{
	        foreach($originalExersies as $exersise) {
	            if ($myStrengtTraining->getMyExercises()->contains($exersise) === false) {
				$em->remove($exersise);
			};
		}

			foreach($myStrengtTraining->getMyExercises() as $exersise) {
				$exersise->setMyTrainingId($myStrengtTraining);
			}

			$em->persist($myStrengtTraining);
			$em->flush();
		}

		return $this->render('training/my_strength_exercise.html.twig', [
			'form' => $form->createView(),
			'training' => $trainingName,
		]);
	}

	/**
	* @Route(*"/dodaj_trening_siłowy_cwiczenia/{category}/{training}", name="strength_training_exercises",
	*)
	*/
	public function showExercisesAction(Request $request, SessionInterface $session, $category = 'kategoria', $training = 'training')
	{
		$myStrengtTraining = $this->getDoctrine()
			->getRepository(StrengthTraining::class)
			->find($training);
		if (!$training) {
			throw $this->createNotFoundException();
		}
		$trainingName = $myStrengtTraining->getName();

		$category = $this->getDoctrine()
			->getRepository(StrengthTrainingCategory::class)
			->find($myStrengtTraining->getCategoryId())
			->getName();

		$exercises = $myStrengtTraining->getExercises();
		$user = $this->getUser();
		$pickedDate = $session->get('pickedDate');
		$em = $this->getDoctrine()->getManager();


		$userStrengthTraining = $this->getDoctrine()->getRepository(UserStrengthTrainingCollection::class)->createQueryBuilder('u')
			->where('u.trainingId = :tId AND u.date = :dateD AND u.userId = :uId')
			->setParameter('tId', $myStrengtTraining->getId())
			->setParameter('dateD', $pickedDate)
			->setParameter('uId', $user->getId())
			->getQuery()
			->getOneOrNullResult();
			if($userStrengthTraining != null) {
				$userStrengthTraining = $userStrengthTraining;
			} else {
				$userStrengthTraining = new UserStrengthTrainingCollection();
				foreach ($exercises as $exercise) {
					$UserStrengthExerciseCollection = new UserStrengthExerciseCollection();
					$UserStrengthExerciseCollection->setExerciseId($exercise);
					$userStrengthTraining->addTrainingExercises($UserStrengthExerciseCollection);
				}
			}

		$form = $this->createForm(TrainingCollectionForm::class, $userStrengthTraining);
		$form->handleRequest($request);


		if($form->isSubmitted() && $form->isValid())
		{
			$userStrengthTraining->setTrainingId($myStrengtTraining);
			$userStrengthTraining->setUserId($user);
			$userStrengthTraining->setDate(new \DateTime($pickedDate));

			foreach ($userStrengthTraining->getTrainingExercises() as $userExercise) {
				// $exercise = $this->getDoctrine()
				// 	->getRepository(StrengthTrainingExercise::class)
				// 	->find($userStrength->getExerciseId());
				// $userStrength->setExerciseId($exercise);
				// $userStrength->setTrainingCollectionId($userStrengthTraining);

				foreach($userExercise->getSeriesTraining() as $series) {
					$series->setCollectionId($userExercise);
				}
			}

			$em->persist($userStrengthTraining);
			$em->flush();
		}

		return $this->render('training/strength_exercise.html.twig', [
			'category' => $category,
			'training' => $trainingName,
			'exercises' => $exercises,
			'form' => $form->createView(),
		]);
	}

	/**
	* @Route(*"/dodaj_trening_siłowy_cwiczenia/{category}/{training}/{exercise}", name="strength_training_exercise",
	*)
	*/
	public function showExerciseAction(Request $request, SessionInterface $session, $category = 'kategoria', $training = 'training', $exercise = 'exercise')
	{
		return $this->render('training/strength_exercise.html.twig', [
			'category' => $category,
			'training' => $trainingName,
			'exercises' => $exercises,
			'form' => $form->createView(),
		]);
	}

}
