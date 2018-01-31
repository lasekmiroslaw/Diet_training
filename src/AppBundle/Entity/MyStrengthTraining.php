<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MyStrengthTraining
 *
 * @ORM\Table(name="my_strength_training")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MyStrengthTrainingRepository")
 */
class MyStrengthTraining
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
     * @ORM\OneToMany(targetEntity="MyStrengthTrainingExercise", mappedBy="trainingId", cascade={"persist"}, orphanRemoval=true)
     */
    private $exercises;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=55)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="$userId", referencedColumnName="id")
     */
    private $userId;

    /**
     * @ORM\Column(name="aktywny", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->isActive = true;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }    

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
     * @return MyStrengthTraining
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
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId
     *
     * @param int $userId
     *
     * @return MyStrengthTraining
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Get exercises
     *
     * @return string
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    public function addExercise(MyStrengthTrainingExercise $exercise)
    {
        $this->exercises->add($exercise);
    }

    public function removeExercise(MyStrengthTrainingExercise $exercise)
    {
        $this->exercises->removeElement($exercise);
    }
}
