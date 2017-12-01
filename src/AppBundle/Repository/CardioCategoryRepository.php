<?php

namespace AppBundle\Repository;

/**
 * CardioCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CardioCategoryRepository extends \Doctrine\ORM\EntityRepository
{
	public function findOrderedCategories()
	{
		return $this->getEntityManager()
		->createQuery(
			'SELECT c.name
			FROM AppBundle:CardioCategory c
			ORDER BY c.id')
		->getResult();
	}
}
