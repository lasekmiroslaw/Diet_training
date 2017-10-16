<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dairy
 *
 * @ORM\Table(name="food_categories_dairy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\DairyRepository")
 */
class Dairy
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

