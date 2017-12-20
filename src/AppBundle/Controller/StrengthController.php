<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\StrengthTrainingCategory;
use AppBundle\Entity\StrengthTraining;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserStrengthExerciseCollection;
use AppBundle\Form\TrainingCollectionForm;

class StrengthController extends Controller
{
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
	* @Route(*"/dodaj_trening_siłowy_cwiczenia/{training}", name="strength_training_exercise",
	*)
	*/
	public function showExercisesAction(Request $request, SessionInterface $session, $training = 'training')
	{
		$myStrengtTraining = $this->getDoctrine()
			->getRepository(StrengthTraining::class)
			->findTraining($training);
		$trainingName = $myStrengtTraining->getName();

		$category = $this->getDoctrine()
			->getRepository(StrengthTrainingCategory::class)
			->find($myStrengtTraining->getCategoryId())
			->getName();

		$exercises = $myStrengtTraining->getExercises();
		$user = $this->getUser();
		$pickedDate = $session->get('pickedDate');
		$em = $this->getDoctrine()->getManager();


	   	$userStrengthTraining = new UserStrengthTrainingCollection();
		foreach ($exercises as $exercise) {
			$UserStrengthExerciseCollection = new UserStrengthExerciseCollection();
			$UserStrengthExerciseCollection->setExerciseId($exercise);
			$userStrengthTraining->addTrainingExercises($UserStrengthExerciseCollection);
		}


		$form = $this->createForm(TrainingCollectionForm::class, $userStrengthTraining);
		$form->handleRequest($request);


		if($form->isSubmitted() && $form->isValid())
		{
			$userStrengthTraining->setTrainingId($myStrengtTraining);
			$userStrengthTraining->setUserId($user);
			$userStrengthTraining->setDate(new \DateTime($pickedDate));

			foreach ($userStrengthTraining->getTrainingExercises() as $userExercise) {
				$userExercise->setTrainingCollectionId($userStrengthTraining);

				foreach($userExercise->getSeriesTraining() as $series) {
					$series->setCollectionId($userExercise);
				}
			}

			$em->persist($userStrengthTraining);
			$em->flush();
			return $this->redirectToRoute('user_trainings');
		}

		return $this->render('training/strength_exercise.html.twig', [
			'category' => $category,
			'training' => $trainingName,
			'exercises' => $exercises,
			'form' => $form->createView(),
		]);
	}

}
