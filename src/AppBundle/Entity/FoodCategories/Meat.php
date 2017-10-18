<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meat
 *
 * @ORM\Table(name="food_categories_meat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\MeatRepository")
 */
class Meat extends FoodCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
