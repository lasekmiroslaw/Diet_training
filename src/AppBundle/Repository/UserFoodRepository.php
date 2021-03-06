<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * UserFoodRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserFoodRepository extends \Doctrine\ORM\EntityRepository
{
    public function findMeals(Int $userId, String $meal, String $date)
    {
        $mealFood = $this->getEntityManager()
            ->createQuery(
                'SELECT a.name, s.quantity, s.id, s.calories, a.id as productId
				FROM AppBundle:UserFood s
				JOIN s.productId a
				WITH s.productId = a.id
				AND  s.meal = :meal
				AND  s.userId =:userId
				AND  s.date = :pickedDate'
            )->setParameters(array(
                'userId' => $userId,
                'meal' => $meal,
                'pickedDate' => $date,
            ))->getResult();

        $myMealFood = $this->getEntityManager()
            ->createQuery(
                'SELECT m.name, s.quantity, s.id, s.calories, s.date, m.id as productId
				FROM AppBundle:UserFood s
				JOIN s.myProductId m
				WITH s.myProductId = m.id
				AND  s.meal = :meal
				AND  s.userId =:userId
				AND  s.date = :pickedDate'
            )->setParameters(array(
                'userId' => $userId,
                'meal' => $meal,
                'pickedDate' => $date,
            ))->getResult();
        $allFood = array_merge($mealFood, $myMealFood);

        return $allFood;
    }

    public function sumMacroNutrients(Int $userId, String $date)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT SUM(s.calories) as calories,
	                    SUM(s.fat) as fat,
	                    SUM(s.totalProtein) as protein,
	                    SUM(s.carbohydrates) as carbohydrates
				FROM AppBundle:UserFood s
				WHERE s.userId = :id
	            AND s.date = :pickedDate'
            )->setParameters(array(
                'id' => $userId,
                'pickedDate' => $date,
            ))->getResult();
    }

    public function findItemToDelete(User $user, $id)
    {
        return $this->createQueryBuilder('u')
          ->where('u.userId = :user AND u.id = :id')
          ->setParameter('user', $user)
          ->setParameter('id', $id)
          ->getQuery()
          ->getOneOrNullResult();
    }
}
