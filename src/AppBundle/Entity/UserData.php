<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserData
 *
 * @ORM\Table(name="user_data")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserDataRepository")
 */
class UserData
{
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
     private $userId;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="age", type="integer")
     * @Assert\NotBlank(message = "Proszę podać swój wiek")
     * @Assert\Regex(
     *      pattern="/^[0-9]{1,3}$/",
     *      htmlPattern ="/^[0-9]{1,3}$/",
     *      message="Proszę wprowadzić odpowiednią liczbę"
     *)
     */
    private $age;

    /**
     * @ORM\Column(name="weight", type="float")
     * @Assert\NotBlank(message = "Proszę podać swoją wagę")
     * @Assert\Regex(
     *      pattern="/^\d{2,3}([\.,]\d{1,2})?$/",
     *      htmlPattern ="/^\d{2,3}([\.,]\d{1,2})?$/",
     *      message="Proszę wprowadzić odpowiednią liczbę"
     *)
     */
    private $weight;

    /**
     * @ORM\Column(name="height", type="integer")
     * @Assert\NotBlank(message = "Proszę podać swój wzrost")
     * @Assert\Regex(
     *      pattern="/^[0-9]{2,3}$/",
     *      htmlPattern ="/^[0-9]{2,3}$/",
     *      message="Proszę wprowadzić odpowiednią liczbę"
     *)
     */
    private $height;

    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=9)
     */
    private $gender;

    /**
     * @ORM\Column(name="calories", type="integer")
     * @Assert\NotBlank(message = "Proszę podać swoję zapotrzebowanie kaloryczne lub kliknąć w przycisk oblicz")
     * @Assert\Regex(
     *      pattern="/^[0-9]{3,5}$/",
     *      htmlPattern ="/^[0-9]{3,5}$/",
     *      message="Proszę wprowadzić odpowiednią liczbę"
     *)
     */
    private $calories;

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
     * Set age
     *
     * @param integer $age
     *
     * @return UserData
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return UserData
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return UserData
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set activity
     *
     * @param string $activity
     *
     * @return UserData
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return UserData
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set calories
     *
     * @param integer $calories
     *
     * @return UserData
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

	public function setId($id) {
		$this->id = $id;

		return $this;
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
