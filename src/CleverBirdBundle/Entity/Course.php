<?php

namespace CleverBirdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories",indexes={@ORM\Index(name="courses_indexes", columns={"parent_id"})})
 */
class Course
{
    const DRAFT = 1;

    const FOR_ALL = 2;

    const FOR_ME = 3;

    const INVITE = 4;

    const PAiD = 5;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $image;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default" = "1"})
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="parent")
     */
    private $children;

    /**
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Lecture", mappedBy="course")
     */
    protected $lectures;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\Cache("NONSTRICT_READ_WRITE")
     */
    private $user;

    /**
     * @ORM\Cache("NONSTRICT_READ_WRITE")
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="courses")
     * @ORM\JoinTable(name="courses_tags")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $tags;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->created = new \DateTime();
    }

    /**
     * Add children.
     *
     * @param Course $children
     *
     * @return Course
     */
    public function addChild(Course $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children.
     *
     * @param Course $children
     */
    public function removeChild(Course $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param Course $parent
     *
     * @return Course
     */
    public function setParent(Course $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Course
     */
    public function getParent()
    {
        return $this->parent;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array Lecture
     */
    public function getLectures()
    {
        return $this->lectures;
    }

    /**
     * @param array $lectures
     *
     * @return $this
     */
    public function setLectures($lectures)
    {
        $this->lectures[] = $lectures;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function setTags($tag)
    {
        $this->tags[] = $tag;
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
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
