<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="entity_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email", message="Użytkownik o podanym email już istnieję")
 * @UniqueEntity("username", message="Użytkownik o podanym nicku już istnieję")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Assert\NotBlank(message = "Proszę wprowadzić nazwę użytkownika")
     * @Assert\Length(
     *      min = 4,
     *      max = 25,
     *      minMessage = "Twoja nazwa użytkownika musi zawierać conajmniej 4 znaki",
     *      maxMessage = "Twoja nazwa użytkownika może zawierać maksymalnie 25 znaków"
     * )
     *
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z0-9_-]{4,25}$/",
     *      htmlPattern = "/^[a-zA-Z0-9_-]{4,25}$/",
     *      message="Login może zawierać tylko litery i cyfry oraz znaki - , _"
     *)
     */
    private $username;

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
    private $plainPassword;

    /**
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="email", type="string", length=64, unique=true)
     * @Assert\NotBlank(message = "Proszę wprowadzić adres email")
     * @Assert\Email(
     *     message = "Proszę wprowadzić adres email",
     *     checkMX = true
     * )
     */
    private $email;


    /**
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="isUserData", type="boolean", nullable=true)
     */
    private $userData;

    public function __toString()
    {
        return (string) $this->getUsername();
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function __construct()
    {
        $this->isActive = false;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    //Active/Not active accunt
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ) = unserialize($serialized);
    }

    public function setUserData($userData)
    {
        $this->userData = $userData;
        return $this;
    }

    public function getUserData()
    {
        return $this->userData;
    }
}
