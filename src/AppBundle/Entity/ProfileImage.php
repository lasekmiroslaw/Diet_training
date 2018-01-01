<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProfileImage
 *
 * @ORM\Table(name="profile_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileImageRepository")
 */
class ProfileImage
{
    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", options={"default": "blank.png"})
     * @Assert\Image(
     *     minWidth = 128,
     *     maxWidth = 1600,
     *     minHeight = 128,
     *     maxHeight = 1600
     *)
     */
    private $profileImage;

    // public function __construct($userId, $profileImage)
    // {
    // 	$this->userId = $userId;
    // 	$hits->$profileImage = $profileImage;
    // }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getProfileImage()
    {
        return $this->profileImage;
    }

    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;

        return $this;
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
