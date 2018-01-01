<?php

namespace AppBundle\Repository;

/**
 * StrengthTrainingCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StrengthTrainingCategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOrderedCategories()
    {
        return $this->getEntityManager()
        ->createQuery(
            'SELECT s.name
		    FROM AppBundle:StrengthTrainingCategory s
		    ORDER BY s.id'
        )
        ->getResult();
    }
}
