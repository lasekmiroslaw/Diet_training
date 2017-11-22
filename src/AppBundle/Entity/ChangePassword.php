<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
    protected $oldPassword;

    /**
     * @Assert\NotBlank(message = "Proszę wprowadzić hasło")
     * @Assert\Length(max=4096)
     * @Assert\Length(
     *      min = 6,
     *      max = 64,
     *      minMessage = "Twoje hasło musi zawierać conajmniej 6 znaków",
     *      maxMessage = "Twoje hasło może zawierać maksymalnie 64 znaki"
     * )
     *
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z0-9_-]{6,64}$/",
     *      htmlPattern = "/^[a-zA-Z0-9_-]{6,64}$/",
     *      message="Hasło może zawierać tylko litery i cyfry oraz oraz znaki - , _"
     *)
     */
    protected $newPassword;

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}
