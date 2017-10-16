<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * Spices
 *
 * @ORM\Table(name="food_categories_spices")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\SpicesRepository")
 */
class Spices extends Food
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
