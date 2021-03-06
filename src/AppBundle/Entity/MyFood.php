<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MyFood
 *
 * @ORM\Table(name="my_food")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MyFoodRepository")
 */
class MyFood extends BaseFood
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
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="$userId", referencedColumnName="id")
     */
    private $userId;

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

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
