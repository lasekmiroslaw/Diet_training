<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StrengthTrainingCategory
 *
 * @ORM\Table(name="strength_training_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StrengthTrainingCategoryRepository")
 */
class StrengthTrainingCategory
{
    /**
     * @ORM\OneToMany(targetEntity="StrengthTraining", mappedBy="categoryId")
     */
    private $training;

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
     * @ORM\Column(name="name", type="string", length=60, unique=true)
     */
    private $name;

    public function __construct()
    {
        $this->training = new ArrayCollection();
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
     * @return StrengthTrainingCategory
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
     * Get training
     *
     * @return string
     */
    public function getTraining()
    {
        return $this->training;
    }
}
