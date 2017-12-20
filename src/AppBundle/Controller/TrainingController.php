<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\CardioCategory;
use AppBundle\Entity\StrengthTrainingCategory;
use AppBundle\Entity\MyStrengthTraining;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserCardio;
use AppBundle\Form\TrainingForm;
use AppBundle\Form\MyTrainingForm;
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

		if(empty($userStrengthTrainings)) {
			$userTrainings = [];
		} else {
			foreach($userStrengthTrainings as $userStrengthTraining) {
				$exercises = $userStrengthTraining->getTrainingExercises();
				$userTrainingName = '-'.$userStrengthTraining->getId().'-'.$userStrengthTraining->getTrainingId()->getName();
				$counter = $exercises->count();

				for($i = 0; $i<$counter; $i++) {
            		$seriesCounter = $exercises[$i]->getSeriesTraining()->count();
					$exerciseName = $exercises[$i]->getExerciseId()->getName();
					$series = $exercises[$i]->getSeriesTraining();
					$userTrainings[$userTrainingName][$exerciseName] = [];

					for($x = 0; $x<$seriesCounter; $x++) {
						array_push($userTrainings[$userTrainingName][$exerciseName], $series[$x]);
					}
				}
			}
		}
		$alert = $session->get('alert');

		return $this->render('training/my_trainings.html.twig', [
			'userTrainings' => $userTrainings,
			'userCardios' => $userCardios,
			'alert' => $alert,
		]);
	}

	/**
	 * @Route("/deleteTraining/{training}/{id}", name="deleteTraining")
	 */
	public function deleteTrainingAction($training = 'trening', $id = 1, SessionInterface $session)
	{
		try
		{
			$em = $this->getDoctrine()->getManager();
			if($training == 'silowy') {
				$itemToDelete = $em->getRepository(UserStrengthTrainingCollection::class)->find($id);
				$em->remove($itemToDelete);
				$em->flush();
			}
			if($training == 'cardio') {
				$itemToDelete = $em->getRepository(UserCardio::class)->find($id);
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
