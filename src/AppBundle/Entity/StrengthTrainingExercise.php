<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StrengthTrainingExercise
 *
 * @ORM\Table(name="strength_training_exercise")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StrengthTrainingExerciseRepository")
 */
class StrengthTrainingExercise
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="StrengthTraining", inversedBy="exercises")
     * @ORM\JoinColumn(name="training_id", referencedColumnName="id")
     */
    private $trainingId;


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
     * @return StrengthTrainingExercise
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
     * Set trainingId
     *
     * @param integer $trainingId
     *
     * @return StrengthTrainingExercise
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
}
