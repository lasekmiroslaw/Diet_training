<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class UserStrengthTrainingCollection
{
    /**
     * @ORM\OneToOne(targetEntity="UserStrengthTraining", mappedBy="id", cascade={"persist"})
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
     * Get seriesTraining(
     *
     * @return int
     */
    public function getSeriesTraining()
    {
        return $this->seriesTraining;
    }

    public function addSeriesTraining(UserStrengthTraining $seriesTraining)
    {
        $this->seriesTraining->add($seriesTraining);
    }

    public function removeSeriesTraining(UserStrengthTraining $seriesTraining)
    {
        $this->seriesTraining->removeElement($seriesTraining);
    }
}
