<?php

namespace CleverBirdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CleverBirdBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Cache("NONSTRICT_READ_WRITE")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $joinDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $courses;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Participants", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $participants;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Group", mappedBy="users")
     */
    protected $groups;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Group", mappedBy="owner")
     */
    protected $owned_groups;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->active = true;
        $this->courses = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->joinDate = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username.
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
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email.
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
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return User
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /**
     * @param $serialized
     *
     * @see \Serializable::serialize()
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }

    /**
     * Gravatar email.
     *
     * @return string
     */
    public function getAvatar()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->getEmail())).'?d=identicon';
    }

    /**
     * @return ArrayCollection
     */
    public function getCourses()
    {
        return $this->courses->getValues();
    }

    /**
     * @param array $courses
     */
    public function setCourses(Course $courses)
    {
        $this->courses[] = $courses;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setActive($isActive)
    {
        $this->active = $isActive;

        return $this;
    }

    /**
     * @return ArrayCollection of Participants
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param Participants $participant
     *
     * @return $this
     */
    public function setParticipants(Participants $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Should be changed to raw mysql check.
     *
     * @param Course $course
     *
     * @return bool
     */
    public function isEnrolled(Course $course)
    {
        foreach ($this->getParticipants()->getValues() as $participant) {
            if ($participant->getCourse() == $course && $participant->getUser() == $this) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \DateTime
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     *
     * @return $this
     */
    public function setGroups(Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    public function hasGroup(Group $group)
    {
        foreach ($this->getGroups()->getValues() as $userGroup) {
            if ($group == $userGroup) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getOwnedGroups()
    {
        return $this->owned_groups;
    }
}
