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
     * @Assert\Type(
     *     type="numeric",
     *     message="Proszę wprowadzić liczbę"
     * )
     * @Assert\Length(
     *      max = 6 ,
     *      maxMessage = "Za duża ilość"
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
     * @ORM\ManyToOne(targetEntity="Food")
     * @ORM\JoinColumn(name="$productId", referencedColumnName="id")
     */
    private $productId;

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
     * @ORM\Column(name="date", type="datetime")
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
}
