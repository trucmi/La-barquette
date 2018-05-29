<?php
namespace App\Entity;
use App\Repository\DebtRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;
    /**
     * @ORM\OneToMany(targetEntity="Debt", mappedBy="receiver")
     */
    private $receivables;
    /**
     * @ORM\OneToMany(targetEntity="Debt", mappedBy="giver")
     */
    private $debts;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
    /**
     * @var array
     */
    private $roles;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin = false;
    /**
     * @ORM\Column(type="date")
     */
    public $dateSubscription;
    public function __construct()
    {
        $this->dateSubscription = new \Datetime();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
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
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a `roles` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        if ($this->getisAdmin()) {
            return ['ROLE_ADMIN'];
        }
        return ['ROLE_USER'];
    }
    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }
    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
    /**
     * @return mixed
     */
    public function getisAdmin()
    {
        return $this->isAdmin;
    }
    /**
     * @param mixed $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->setPlainPassword(null);
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
    /**
     * @return mixed
     */
    public function getDateSubscrition()
    {
        return $this->dateSubscrition;
    }
    /**
     * @param mixed $dateSubscrition
     */
    public function setDateSubscrition($dateSubscrition)
    {
        $this->dateSubscrition = $dateSubscrition;
    }
    /**
     * @return mixed
     */
    public function getReceivables()
    {
        return $this->receivables;
    }
    /**
     * @param mixed $receivables
     */
    public function setReceivables($receivables)
    {
        $this->receivables = $receivables;
    }
    /**
     * @return mixed
     */
    public function getDebts()
    {
        return $this->debts;
    }
    /**
     * @param mixed $debts
     */
    public function setDebts($debts)
    {
        $this->debts = $debts;
    }
    public function getStatut()
    {
//dump($this->receivables);die;
        $month = new \DateTime();
        $month->modify('-30 days');
        $year = new \DateTime();
        $year->modify('-365 days');
        $now = new \DateTime();
        if (count($this->receivables) . count($this->debts) < 1 and $this->dateSubscription > $month) {
            return $this->statut = 'noob';
        }
        if (count($this->receivables) < 1 and $this->dateSubscription < $month) {
            return $this->statut = 'radin';
        }
        if (count($this->receivables) > 10) {
            return $this->statut = 'généreux';
        }
        if (count($this->debts) > 3) {
            return $this->statut = 'profiteur';
        }
        //        elseif (count($this->receivables ) < 1 and $this->dateSubscription < $month)
//        {
//            return $this->statut = 'fiable';
//        }
        if (count($this->debts) > 30) {
            return $this->statut = 'fauché';
        }
        foreach ($this->debts as $debt) {
            if (!$debt->getIsArchived() && $debt->getDebtDeadline() < new \DateTime()) {
                return $this->statut = 'crevard';
            }
        }
        //        elseif (count($this->receivables ) < 1 and $this->dateSubscription < $month)
//        {
//            return $this->statut = 'pigeon';
//        }
        if (count($this->receivables) > 50) {
            return $this->statut = 'dieu';
        }
        if (count($this->receivables) > 25 and count($this->debts) > 25 and $this->dateSubscription < $year) {
            return $this->statut = 'el padre';
        }
        else {
            return $this->statut = 'gars sûr';
        }
    }
    public function getAllDebtsByDate()
    {
        $all = array_merge($this->receivables->toArray(), $this->debts->toArray());
        $all = array_filter($all, function (Debt $debt) {
            return !$debt->getisArchived();
        });
        usort($all, function (Debt $a, Debt $b) {
            return $a->getDebtDeadline() > $b->getDebtDeadline() ? 1 : -1;
        });
        return $all;
    }
    public function getAllCurrentDebt()
    {
        $currentDebts = array_merge($this->receivables->toArray(), $this->debts->toArray());
        $currentDebts = array_filter($currentDebts, function (Debt $debt) {
            return !$debt->getisArchived();
        });
        return $currentDebts;
    }
    public function getAllIsArchived()
    {
        $all = array_merge($this->receivables->toArray(), $this->debts->toArray());
        $all = array_filter($all, function(Debt $debt) {
            return $debt->getisArchived();
        });
        return $all;
    }
}