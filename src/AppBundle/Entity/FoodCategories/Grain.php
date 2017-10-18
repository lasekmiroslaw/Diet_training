<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grain
 *
 * @ORM\Table(name="food_categories_grain")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\GrainRepository")
 */
class Grain extends FoodCategory
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
