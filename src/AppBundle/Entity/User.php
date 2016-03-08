<?php
/**
 * Created by PhpStorm.
 * User: AwH
 * Date: 26/02/16
 * Time: 08:04
 */


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string salt
     */
    protected $salt;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    public $promotion_year;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="_assoc_user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection $userRoles
     */
    public $userRoles;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->userRoles = new ArrayCollection();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets an array of roles.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }

    /**
     * Gets the user roles.
     *
     * @return ArrayCollection A Doctrine ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Gets the user password.
     *
     * @return string The password.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the user password.
     *
     * @param string $value The password.
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }

    /**
     * Gets the user salt.
     *
     * @return string The salt.
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Sets the user salt.
     *
     * @param string $value The salt.
     */
    public function setSalt($value)
    {
        $this->salt = $value;
    }

    /**
     * Gets the username.
     *
     * @return string The username.
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     * Sets the username.
     *
     * @param string $value The username.
     */
    public function setUsername($value)
    {
        $this->login = $value;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPromotionYear()
    {
        return $this->promotion_year;
    }

    /**
     * @param mixed $promotion_year
     */
    public function setPromotionYear($promotion_year)
    {
        $this->promotion_year = $promotion_year;
    }



    /**
     * Erases the user credentials.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Renvoie l'objet au format serialisé
     * @return string
     */
    public function serialize()
    {
        return \json_encode(array($this->id, $this->login, $this->password, $this->salt, $this->userRoles));
    }

    /**
     * Renseigne les valeurs de l'objet à partir d'une chaine serialisée
     * @param type $serialized
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->login, $this->password, $this->salt, $this->userRoles) = \json_decode($serialized);
    }
}
