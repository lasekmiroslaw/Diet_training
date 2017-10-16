<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * Drinks
 *
 * @ORM\Table(name="food_categories_drinks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\DrinksRepository")
 */
class Drinks extends Food
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
