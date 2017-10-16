<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Food
 *
 * @ORM\Table(name="food")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodRepository")
 */
class Food
{
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="food")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $categoryId;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nazwa_produktu", type="string", length=55)
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="wartosc_energetyczna_kcal", type="integer")
     */
    protected $calories;

    /**
     * @var string
     *
     * @ORM\Column(name="woda_g", type="decimal", precision=3, scale=1, nullable=true)
     */
    protected $water;

    /**
     * @var string
     *
     * @ORM\Column(name="bialko_ogolem_g", type="decimal", precision=3, scale=1, nullable=true)
     */
    protected $totalProtein;

    /**
     * @var string
     *
     * @ORM\Column(name="bialko_zwierzece_g", type="decimal", precision=3, scale=1, nullable=true)
     */
    protected $animalProtein;

    /**
     * @var string
     *
     * @ORM\Column(name="bialko_roslinne_g", type="decimal", precision=3, scale=1, nullable=true)
     */
    protected $vegetableProtein;

    /**
     * @var string
     *
     * @ORM\Column(name="tluszcz_g", type="decimal", precision=4, scale=1, nullable=true)
     */
    protected $fat;

    /**
     * @var string
     *
     * @ORM\Column(name="weglowodany_g", type="decimal", precision=3, scale=1, nullable=true)
     */
    protected $carbohydrates;

    /**
     * @var int
     *
     * @ORM\Column(name="sod_mg", type="integer", nullable=true)
     */
    protected $sodium;

    /**
     * @var int
     *
     * @ORM\Column(name="potas_mg", type="integer", nullable=true)
     */
    protected $potassium;

    /**
     * @var int
     *
     * @ORM\Column(name="wapn_mg", type="integer", nullable=true)
     */
    protected $calcium;

    /**
     * @var int
     *
     * @ORM\Column(name="fosfor_mg", type="integer", nullable=true)
     */
    protected $phosphor;

    /**
     * @var int
     *
     * @ORM\Column(name="magnez_mg", type="integer", nullable=true)
     */
    protected $magnesium;

    /**
     * @var string
     *
     * @ORM\Column(name="zelazo_mg", type="decimal", precision=3, scale=1, nullable=true)
     */
    protected $iron;

    /**
     * @var string
     *
     * @ORM\Column(name="cynk_mg", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $zinc;

    /**
     * @var string
     *
     * @ORM\Column(name="miedz_mg", type="decimal", precision=3, scale=2, nullable=true)
     */
    protected $copper;

    /**
     * @var string
     *
     * @ORM\Column(name="mangan_mg", type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $manganese;


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
     * @return Food
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

    /**
     * Set calories
     *
     * @param integer $calories
     *
     * @return Food
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * Get calories
     *
     * @return int
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * Set water
     *
     * @param string $water
     *
     * @return Food
     */
    public function setWater($water)
    {
        $this->water = $water;

        return $this;
    }

    /**
     * Get water
     *
     * @return string
     */
    public function getWater()
    {
        return $this->water;
    }

    /**
     * Set totalProtein
     *
     * @param string $totalProtein
     *
     * @return Food
     */
    public function setTotalProtein($totalProtein)
    {
        $this->totalProtein = $totalProtein;

        return $this;
    }

    /**
     * Get totalProtein
     *
     * @return string
     */
    public function getTotalProtein()
    {
        return $this->totalProtein;
    }

    /**
     * Set animalProtein
     *
     * @param string $animalProtein
     *
     * @return Food
     */
    public function setAnimalProtein($animalProtein)
    {
        $this->animalProtein = $animalProtein;

        return $this;
    }

    /**
     * Get animalProtein
     *
     * @return string
     */
    public function getAnimalProtein()
    {
        return $this->animalProtein;
    }

    /**
     * Set vegetableProtein
     *
     * @param string $vegetableProtein
     *
     * @return Food
     */
    public function setVegetableProtein($vegetableProtein)
    {
        $this->vegetableProtein = $vegetableProtein;

        return $this;
    }

    /**
     * Get vegetableProtein
     *
     * @return string
     */
    public function getVegetableProtein()
    {
        return $this->vegetableProtein;
    }

    /**
     * Set fat
     *
     * @param string $fat
     *
     * @return Food
     */
    public function setFat($fat)
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * Get fat
     *
     * @return string
     */
    public function getFat()
    {
        return $this->fat;
    }

    /**
     * Set carbohydrates
     *
     * @param string $carbohydrates
     *
     * @return Food
     */
    public function setCarbohydrates($carbohydrates)
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    /**
     * Get carbohydrates
     *
     * @return string
     */
    public function getCarbohydrates()
    {
        return $this->carbohydrates;
    }

    /**
     * Set sodium
     *
     * @param integer $sodium
     *
     * @return Food
     */
    public function setSodium($sodium)
    {
        $this->sodium = $sodium;

        return $this;
    }

    /**
     * Get sodium
     *
     * @return int
     */
    public function getSodium()
    {
        return $this->sodium;
    }

    /**
     * Set potassium
     *
     * @param integer $potassium
     *
     * @return Food
     */
    public function setPotassium($potassium)
    {
        $this->potassium = $potassium;

        return $this;
    }

    /**
     * Get potassium
     *
     * @return int
     */
    public function getPotassium()
    {
        return $this->potassium;
    }

    /**
     * Set calcium
     *
     * @param integer $calcium
     *
     * @return Food
     */
    public function setCalcium($calcium)
    {
        $this->calcium = $calcium;

        return $this;
    }

    /**
     * Get calcium
     *
     * @return int
     */
    public function getCalcium()
    {
        return $this->calcium;
    }

    /**
     * Set phosphor
     *
     * @param integer $phosphor
     *
     * @return Food
     */
    public function setPhosphor($phosphor)
    {
        $this->phosphor = $phosphor;

        return $this;
    }

    /**
     * Get phosphor
     *
     * @return int
     */
    public function getPhosphor()
    {
        return $this->phosphor;
    }

    /**
     * Set magnesium
     *
     * @param integer $magnesium
     *
     * @return Food
     */
    public function setMagnesium($magnesium)
    {
        $this->magnesium = $magnesium;

        return $this;
    }

    /**
     * Get magnesium
     *
     * @return int
     */
    public function getMagnesium()
    {
        return $this->magnesium;
    }

    /**
     * Set iron
     *
     * @param string $iron
     *
     * @return Food
     */
    public function setIron($iron)
    {
        $this->iron = $iron;

        return $this;
    }

    /**
     * Get iron
     *
     * @return string
     */
    public function getIron()
    {
        return $this->iron;
    }

    /**
     * Set zinc
     *
     * @param string $zinc
     *
     * @return Food
     */
    public function setZinc($zinc)
    {
        $this->zinc = $zinc;

        return $this;
    }

    /**
     * Get zinc
     *
     * @return string
     */
    public function getZinc()
    {
        return $this->zinc;
    }

    /**
     * Set copper
     *
     * @param string $copper
     *
     * @return Food
     */
    public function setCopper($copper)
    {
        $this->copper = $copper;

        return $this;
    }

    /**
     * Get copper
     *
     * @return string
     */
    public function getCopper()
    {
        return $this->copper;
    }

    /**
     * Set manganese
     *
     * @param string $manganese
     *
     * @return Food
     */
    public function setManganese($manganese)
    {
        $this->manganese = $manganese;

        return $this;
    }

    /**
     * Get manganese
     *
     * @return string
     */
    public function getManganese()
    {
        return $this->manganese;
    }
}
