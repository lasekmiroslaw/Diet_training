<?php

namespace AppBundle\Service;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\UserFood;
use Doctrine\ORM\EntityManagerInterface;

class MealGenerator
{
	private $repository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->repository = $entityManager->getRepository(UserFood::class);
	}

	public function returnMeals($userId, $pickedDate) {

		$meal = [
			'breakfast' => 'sniadanie',
			'lunch' => 'lunch',
			'dinner' => 'obiad',
			'supper' => 'kolacja',
			'snacks' => 'przekaski',
			'other' => 'inne',
		];

		foreach ($meal as $key => $value) {
			$$key = $this->repository->findMeals($userId, $meal[$key], $pickedDate);
			$meals[$key] = $$key;
		}
		return $meals;
	}
}
