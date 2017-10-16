<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sweets
 *
 * @ORM\Table(name="food_categories_sweets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\SweetsRepository")
 */
class Sweets extends Food
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
