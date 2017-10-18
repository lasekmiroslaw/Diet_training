<?php

namespace AppBundle\Entity\FoodCategories;

use Doctrine\ORM\Mapping as ORM;

/**
 * FoodCategory
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategories\FoodCategoryRepository")
 */
class FoodCategory
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Food", mappedBy="subcategoryId")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="subcategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $categoryId;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=55)
     */
    protected $name;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FoodCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
