<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserStrengthTrainingCollection
 *
 * @ORM\Table(name="user_strength_training_collection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserStrengthExerciseCollectionRepository")
 */
class UserStrengthTrainingCollection
{
    /**
     * @ORM\OneToMany(targetEntity="UserStrengthExerciseCollection", mappedBy="trainingCollectionId", cascade={"persist"})
     */
    private $trainingExercises;

    public function __construct()
    {
        $this->trainingExercises = new ArrayCollection();
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

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
     * @ORM\ManyToOne(targetEntity="StrengthTraining")
     * @ORM\JoinColumn(name="trainingId", referencedColumnName="id")
     */
    private $trainingId;

    /**
     * Set trainingId
     *
     * @param integer $trainingId
     *
     * @return UserStrengthTraining
     */
    public function setTrainingId($trainingId)
    {
        $this->trainingId = $trainingId;

        return $this;
    }

    /**
     * Get trainingId
     *
     * @return int
     */
    public function getTrainingId()
    {
        return $this->trainingId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserStrengthTraining
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return UserStrengthTraining
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

    public function setTrainingExercises($trainingExercise)
    {
        $this->trainingExercises = $trainingExercise;
    }

    /**
     * Get seriesTraining(
     *
     * @return int
     */
    public function getTrainingExercises()
    {
        return $this->trainingExercises;
    }

    public function addTrainingExercises($trainingExercise)
    {
        $this->trainingExercises->add($trainingExercise);
    }

    public function removeTrainingExercises($trainingExercise)
    {
        $this->trainingExercises->removeElement($trainingExercise);
    }
}
