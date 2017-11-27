<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserStrengthTraining
 *
 * @ORM\Table(name="user_strength_training")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserStrengthTrainingRepository")
 */
class UserStrengthTraining
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
     * @ORM\Column(name="series", type="integer")
     */
    private $series;

    /**
     * @var int
     *
     * @ORM\Column(name="reps", type="integer")
     */
    private $reps;

    /**
     * @var int
     *
     * @ORM\Column(name="kgLoad", type="integer")
     */
    private $kgLoad;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Set series
     *
     * @param integer $series
     *
     * @return UserStrengthTraining
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return int
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set reps
     *
     * @param integer $reps
     *
     * @return UserStrengthTraining
     */
    public function setReps($reps)
    {
        $this->reps = $reps;

        return $this;
    }

    /**
     * Get reps
     *
     * @return int
     */
    public function getReps()
    {
        return $this->reps;
    }

    /**
     * Set kgLoad
     *
     * @param integer $kgLoad
     *
     * @return UserStrengthTraining
     */
    public function setKgLoad($kgLoad)
    {
        $this->kgLoad = $kgLoad;

        return $this;
    }

    /**
     * Get kgLoad
     *
     * @return int
     */
    public function getKgLoad()
    {
        return $this->kgLoad;
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
}
