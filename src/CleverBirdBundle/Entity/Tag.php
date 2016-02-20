<?php

namespace CleverBirdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CleverBirdBundle\Entity\TagRepository")
 * @ORM\Cache("NONSTRICT_READ_WRITE")
 */
class Tag
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Course", mappedBy="tags")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     * @ORM\Cache("NONSTRICT_READ_WRITE")
     */
    protected $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @param Course $courses
     */
    public function setCourses(Course $courses)
    {
        $this->courses[] = $courses;

        return $this;
    }
}
