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
use AppBundle\Entity\MyStrengthTraining;
use AppBundle\Form\TrainingForm;
use AppBundle\Form\MyTrainingForm;
use Doctrine\Common\Collections\ArrayCollection;

class StrengthController extends Controller
{
	/**
	* @Route(*"/dodaj_trening_siłowy/{category}", name="strength_category",
	*    requirements={
	*        "category": "|fbw|split|push-pull|push-pull-legs|góra-dół|kalistenika"
	*    }
	*)
	*/
	public function showStrengthAction($category = 'kategoria')
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
	* @Route(*"/dodaj_mój_trening_siłowy/", name="my_strength_trainings",
	*)
	*/
	public function showMyStrengthAction()
	{
		$myTrainings = $this->getDoctrine()->getRepository(MyStrengthTraining::class)->findMyTrainings($this->getUser());

		return $this->render('training/my_strength_trainings.html.twig', [
			'myTrainings' => $myTrainings,
		]);
	}

	/**
	* @Route(*"/utwórz_moj_trening", name="create_my_strength_training",
	*)
	*/
	public function createMyTrainingAction(Request $request, SessionInterface $session, $category = 'kategoria', $training = 'training')
	{
		$userId = $this->getUser()->getId();
		$myTrainings = $this->getDoctrine()->getRepository(MyStrengthTraining::class)->findMyTrainings($userId);

		$myStrengthTraining = new MyStrengthTraining();
		$form = $this->createForm(MyTrainingForm::class, $myStrengthTraining);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$myStrengthTraining->setUserId($this->getUser());
			$myTraining = $em->getRepository(StrengthTrainingCategory::class)->find(7);
			dump($myTraining);die;
			$myStrengthTraining->setCategoryId($myTraining);
			$em->persist($myStrengthTraining);
			$em->flush();

			return $this->redirectToRoute('my_strength_training');
		}

		return $this->render('training/add_my_strength_trainings.html.twig', [
			'myTrainings' => $myTrainings,
			'form' => $form->createView(),
		]);
	}

	/**
	* @Route(*"/dodaj_moje_cwiczenia/{training}", name="add_strength_training_exercise",
	*)
	*/
	public function addMyExerciseAction(Request $request, $training = 'training')
	{
		$myStrengtTraining = $this->getDoctrine()
			->getRepository(MyStrengthTraining::class)
			->find($training);

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
	 * @Route("/usuntrening/{id}", name="deleteMyTraining")
	 */
	public function deleteMyTrainingAction($id = 1, SessionInterface $session)
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
	* @Route(*"/utwórz_moje_cwiczenia/{training}", name="create_strength_training_exercise",
	*)
	*/
	public function createMyExerciseAction(Request $request, $training = 'training')
	{
		$myStrengtTraining = $this->getDoctrine()
			->getRepository(MyStrengthTraining::class)
			->find($training);

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
	* @Route(*"/dodaj_cwiczenia/{training}", name="strength_training_exercise",
	*)
	*/
	public function addStrengthTrainingAction(Request $request, SessionInterface $session, $training = 'training')
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
			$session->set('alert', 'alert-success');
			$this->addFlash(
			   'notice',
			   'Trening dodany!'
			);
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
