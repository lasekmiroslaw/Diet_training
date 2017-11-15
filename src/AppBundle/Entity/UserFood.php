<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserFood
 *
 * @ORM\Table(name="user_food")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserFoodRepository")
 */
class UserFood
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="decimal", precision=6, scale=1)
     * @Assert\NotBlank(message = "Proszę wprowadzić ilość")
     * @Assert\Regex(
     *      pattern="/^[1-9][0-9]{0,5}([\.,][0-9]{1,2})?$/",
     *      htmlPattern ="/^[1-9][0-9]{0,5}([\.,][0-9]{1,2})?$/",
     *      message="Podaj prawidłową ilość"
     *)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="meal", type="string", length=55)
     */
    private $meal;

    /**
     * @var int
     *
     * @ORM\Column(name="calories", type="integer")
     */
    private $calories;

    /**
     * @var string
     *
     * @ORM\Column(name="protein", type="decimal", precision=3, scale=1, nullable=true)
     */
    private $totalProtein;

    /**
     * @var string
     *
     * @ORM\Column(name="fat", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $fat;

    /**
     * @var string
     *
     * @ORM\Column(name="carbohydrates", type="decimal", precision=3, scale=1, nullable=true)
     */
    private $carbohydrates;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Food")
     * @ORM\JoinColumn(name="$productId", referencedColumnName="id")
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="MyFood")
     * @ORM\JoinColumn(name="$myProductId", referencedColumnName="id")
     */
    private $myProductId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="$userId", referencedColumnName="id")
     */
    private $userId;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\Date()
     */
    private $date;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return UserFood
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set meal
     *
     * @param string $meal
     *
     * @return UserFood
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;

        return $this;
    }

    /**
     * Get meal
     *
     * @return string
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return UserFood
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return UserFood
     */
    public function setMyProductId($myProductId)
    {
        $this->myProductId = $myProductId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getMyProductId()
    {
        return $this->myProductId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserFood
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return UserFood
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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

}
