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

    public function returnMeals($userId, $pickedDate, array $meals)
    {
        foreach ($meals as $key => $value) {
            $meal = $this->repository->findMeals($userId, $meals[$key], $pickedDate);
            $meals[$key] = $meal;
        }
        return $meals;
    }
}
