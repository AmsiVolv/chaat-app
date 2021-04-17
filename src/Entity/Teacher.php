<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;
use ReflectionProperty;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $name;

    /** @ORM\Column(type="string", nullable=true) */
    private ?string $externalId;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $email;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $officeNumber;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $phoneNumber;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $teacherUri;

    /** @ORM\ManyToMany(targetEntity=Course::class, mappedBy="teacher") */
    private $courses;

    public function __construct(string $name, string $teacherUri)
    {
        $this->name = $name;
        $this->teacherUri = $teacherUri;
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getOfficeNumber(): ?string
    {
        return $this->officeNumber;
    }

    public function setOfficeNumber(string $officeNumber): self
    {
        $this->officeNumber = $officeNumber;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getTeacherUri(): ?string
    {
        return $this->teacherUri;
    }

    public function setTeacherUri(?string $teacherUri): self
    {
        $this->teacherUri = $teacherUri;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->addTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            $course->removeTeacher($this);
        }

        return $this;
    }

    /**
     * @return ReflectionProperty[]
     */
    public function getKeys(): array
    {
        return [
            'name',
            'email',
            'officeNumber',
            'phoneNumber',
            'teacherUri',
        ];
    }

    public static function getPrimaryKeys(): array
    {
        return [
            'id',
            'name',
            'email',
            'officeNumber',
            'phoneNumber',
            'teacherUri',
        ];
    }
}
