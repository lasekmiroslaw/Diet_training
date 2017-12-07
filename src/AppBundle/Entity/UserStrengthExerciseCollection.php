<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserStrengthExerciseCollection
 *
 * @ORM\Table(name="user_strength_exercise_collection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserStrengthExerciseCollectionRepository")
 */
class UserStrengthExerciseCollection
{
    /**
     * @ORM\OneToMany(targetEntity="UserStrengthTraining", mappedBy="collectionId", cascade={"persist"})
     */
    private $seriesTraining;

    public function __construct()
    {
        $this->seriesTraining = new ArrayCollection();
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="StrengthTrainingExercise")
     * @ORM\JoinColumn(name="exerciseId", referencedColumnName="id")
     */
    private $exerciseId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="UserStrengthTrainingCollection")
     * @ORM\JoinColumn(name=$collectionId", referencedColumnName="id")
     */
    private $collectionId;

    /**
     * Set exerciseId
     *
     * @param integer $exerciseId
     *
     * @return UserStrengthTraining
     */
    public function setExerciseId($exerciseId)
    {
        $this->exerciseId = $exerciseId;

        return $this;
    }

    /**
     * Get exerciseId
     *
     * @return int
     */
    public function getExerciseId()
    {
        return $this->exerciseId;
    }


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

    /**
     * Get seriesTraining(
     *
     * @return int
     */
    public function getSeriesTraining()
    {
        return $this->seriesTraining;
    }

    public function addSeriesTraining(UserStrengthTraining $training)
    {
        $this->seriesTraining->add($training);
    }

    public function removeSeriesTraining(UserStrengthTraining $training)
    {
        $this->seriesTraining->removeElement($training);
    }
}
