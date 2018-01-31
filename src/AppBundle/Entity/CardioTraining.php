<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CardioTraining
 *
 * @ORM\Table(name="cardio_training")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardioTrainingRepository")
 */
class CardioTraining
{
    /**
     * @ORM\ManyToOne(targetEntity="CardioCategory", inversedBy="training")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $categoryId;

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
     * @var int
     *
     * @ORM\Column(name="burnedCalories", type="integer")
     */
    private $burnedCalories;

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
     * @return CardioTraining
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
     * Set burnedCalories
     *
     * @param integer $burnedCalories
     *
     * @return CardioTraining
     */
    public function setBurnedCalories($burnedCalories)
    {
        $this->burnedCalories = $burnedCalories;

        return $this;
    }

    /**
     * Get burnedCalories
     *
     * @return int
     */
    public function getBurnedCalories()
    {
        return $this->burnedCalories;
    }
}
