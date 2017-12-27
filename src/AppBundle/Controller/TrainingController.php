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

		if(empty($userStrengthTrainings)) {
			$userTrainings = [];
		} else {
			foreach($userStrengthTrainings as $userStrengthTraining) {
				$exercises = $userStrengthTraining->getTrainingExercises();
				if(!empty($userStrengthTraining->getTrainingId())) {
					$userTrainingName = '-'.$userStrengthTraining->getId().'-'.$userStrengthTraining->getTrainingId()->getName();
				}
				if(!empty($userStrengthTraining->getMyTrainingId())) {
					$userTrainingName = '-'.$userStrengthTraining->getId().'-'.$userStrengthTraining->getMyTrainingId()->getName();
				}
				$counter = $exercises->count();

				for($i = 0; $i<$counter; $i++) {
					if(!empty($exercises[$i]->getExerciseId())) {
						$exerciseName = $exercises[$i]->getExerciseId()->getName();
					}
					if(!empty($exercises[$i]->getMyExerciseId())) {
						$exerciseName = $exercises[$i]->getMyExerciseId()->getName();
					}

            		$seriesCounter = $exercises[$i]->getSeriesTraining()->count();
					$series = $exercises[$i]->getSeriesTraining();
					$userTrainings[$userTrainingName][$exerciseName] = [];

					for($x = 0; $x<$seriesCounter; $x++) {
						array_push($userTrainings[$userTrainingName][$exerciseName], $series[$x]);
					}
				}
			}
		}
		// dump($userTrainings);die;
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
