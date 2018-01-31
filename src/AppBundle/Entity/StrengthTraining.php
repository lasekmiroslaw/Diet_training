<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StrengthTraining
 *
 * @ORM\Table(name="strength_training")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StrengthTrainingRepository")
 */
class StrengthTraining
{
    /**
     * @ORM\ManyToOne(targetEntity="StrengthTrainingCategory", inversedBy="training")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $categoryId;

    /**
     * @ORM\OneToMany(targetEntity="StrengthTrainingExercise", mappedBy="trainingId", cascade={"persist"})
     */
    private $exercises;

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

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
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
     * @return StrengthTraining
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
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return StrengthTraining
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
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
