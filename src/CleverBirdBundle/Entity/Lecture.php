<?php

namespace CleverBirdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CleverBirdBundle\Entity\LectureRepository")
 * @ORM\Cache("NONSTRICT_READ_WRITE")
 */
class Lecture
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default" = "1"})
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default" = "1"})
     */
    protected $accessType;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="lectures")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @ORM\Cache("NONSTRICT_READ_WRITE")
     */
    private $course;

    public function __construct()
    {
        $this->created = new \DateTime();
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
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get creation time.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set creation time.
     *
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setCreated(\DateTime $date)
    {
        $this->created = $date;

        return $this;
    }

    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     *
     * @return $this
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccessType()
    {
        return $this->accessType;
    }

    /**
     * @param int $accessType
     *
     * @return $this
     */
    public function setAccessType($accessType)
    {
        $this->accessType = $accessType;

        return $this;
    }

    /**
     * @param User $user
     */
    public function canEdit(User $user)
    {
        if ($this->getCourse()->getUser() == $user) {
            return true;
        } elseif ($this->getAccessType() == LectureStatuses::ALL_CONTRIBUTE) {
            return true;
        } elseif (
            $this->getAccessType() == LectureStatuses::GROUP_CONTRIBUTE
            && $this->getCourse()->getUser()->getGroups() == $user->getGroups()
        ) {
            return true;
        }

        return false;
    }
}
