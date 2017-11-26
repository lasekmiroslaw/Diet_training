<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCardio
 *
 * @ORM\Table(name="user_cardio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserCardioRepository")
 */
class UserCardio
{
    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="CardioTraining")
     * @ORM\JoinColumn(name="traingId", referencedColumnName="id")
     */
    private $traingId;

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
     * @ORM\Column(name="burnedCalories", type="integer")
     */
    private $burnedCalories;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $time;

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
     * Set traingId
     *
     * @param integer $traingId
     *
     * @return UserCardio
     */
    public function setTraingId($traingId)
    {
        $this->traingId = $traingId;

        return $this;
    }

    /**
     * Get traingId
     *
     * @return int
     */
    public function getTraingId()
    {
        return $this->traingId;
    }

    /**
     * Set burnedCalories
     *
     * @param integer $burnedCalories
     *
     * @return UserCardio
     */
    public function setBurnedCalories($burnedCalories)
    {
        $this->burnedCalories = $burnedCalories;

        return $this;
    }

    /**
     * Get burnedCalories
     *
     * @return int
     */
    public function getBurnedCalories()
    {
        return $this->burnedCalories;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return UserCardio
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return UserCardio
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserCardio
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
}
