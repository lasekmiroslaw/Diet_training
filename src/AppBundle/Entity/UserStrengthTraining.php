<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToOne(targetEntity="UserStrengthExerciseCollection", inversedBy="seriesTraining")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     */
    private $collectionId;

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
     * @ORM\Column(name="kgLoad", type="float")
     * @Assert\Regex(
     *      pattern="/^\d{1,3}([\.,]\d{1,2})?$/",
     *      htmlPattern ="/^\d{1,3}([\.,]\d{1,2})?$/",
     *      message="Proszę wprowadzić odpowiednią liczbę"
     *)
     */
    private $kgLoad;

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
     * Set collectionId
     *
     * @param integer $CollectionId
     *
     * @return UserStrengthTraining
     */
    public function setUserExerciseId($CollectionId)
    {
        $this->collectionId = $CollectionId;

        return $this;
    }

    /**
     * Get collectionId
     *
     * @return int
     */
    public function geCollectionId()
    {
        return $this->collectionId;
    }

}
