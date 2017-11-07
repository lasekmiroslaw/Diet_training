<?php

namespace AppBundle\Entity;

class PickedDate
{
    private $pickedDate;

    /**
     * Set pickedDate
     *
     * @param \DateTime $pickedDate
     *
     * @return PickedDate
     */
    public function setPickedDate($pickedDate)
    {
        $this->pickedDate = $pickedDate;

        return $this;
    }

    /**
     * Get pickedDate
     *
     * @return \DateTime
     */
    public function getPickedDate()
    {
        return $this->pickedDate;
    }
}
