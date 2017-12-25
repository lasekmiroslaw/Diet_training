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
     * @ORM\OneToMany(targetEntity="MyStrengthTrainingExercise", mappedBy="trainingId", cascade={"persist"})
     */
    private $exercises;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

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
     * Get exercises
     *
     * @return string
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    public function addExercise(StrengthTrainingExercise $exercise)
    {
        $this->exercises->add($exercise);
    }

    public function removeExercise(StrengthTrainingExercise $exercise)
    {
        $this->exercises->removeElement($exercise);
    }
}
