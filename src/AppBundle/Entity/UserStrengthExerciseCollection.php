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
     * @ORM\OneToMany(targetEntity="UserStrengthTraining", mappedBy="collectionId", cascade={"persist"}, orphanRemoval=true)
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="StrengthTrainingExercise")
     * @ORM\JoinColumn(name="exerciseId", referencedColumnName="id")
     */
    private $exerciseId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="MyStrengthTrainingExercise")
     * @ORM\JoinColumn(name="myExerciseId", referencedColumnName="id")
     */
    private $myExerciseId;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="UserStrengthTrainingCollection", inversedBy="trainingExercises")
     * @ORM\JoinColumn(name="trainingCollectionId", referencedColumnName="id")
     */
    private $trainingCollectionId;

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
     * Set myExerciseId
     *
     * @param integer $myExerciseId
     *
     * @return UserStrengthTraining
     */
    public function setMyExerciseId($myExerciseId)
    {
        $this->myExerciseId = $myExerciseId;

        return $this;
    }

    /**
     * Get myExerciseId
     *
     * @return int
     */
    public function getMyExerciseId()
    {
        return $this->myExerciseId;
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
     * Set exerciseId
     *
     * @param integer $trainingCollectionId
     *
     * @return UserStrengthTraining
     */
    public function setTrainingCollectionId($trainingCollectionId)
    {
        $this->trainingCollectionId = $trainingCollectionId;

        return $this;
    }

    /**
     * Get trainingCollectionId
     *
     * @return int
     */
    public function getTrainingCollectionId()
    {
        return $this->trainingCollectionId;
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
