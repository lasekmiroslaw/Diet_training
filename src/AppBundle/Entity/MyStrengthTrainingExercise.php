<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyStrengthTrainingExercise
 *
 * @ORM\Table(name="my_strength_training_exersie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MyStrengthTrainingExerciseRepository")
 */
class MyStrengthTrainingExercise
{
    /**
     * @ORM\ManyToOne(targetEntity="MyStrengthTraining", inversedBy="exercises")
     * @ORM\JoinColumn(name="training_id", referencedColumnName="id")
     */
    private $trainingId;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * @return MyStrengthTrainingExercise
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
     * @return MyStrengthTrainingExercise
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
