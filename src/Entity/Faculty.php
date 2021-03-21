<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\FacultyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacultyRepository::class)
 */
class Faculty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $facultyName;

    /** @ORM\Column(type="string", length=255) */
    private string $logoLink;

    /** @ORM\Column(type="string", length=255) */
    private string $attainmentLink;

    /** @ORM\Column(type="string", length=255) */
    private string $webLink;

    /** @ORM\Column(type="string", length=255) */
    private string $abbreviation;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $period;

    /** @ORM\OneToMany(targetEntity=Course::class, mappedBy="faculty") */
    private $courses;

    public function __construct(
        string $facultyName,
        string $logoLink,
        string $attainmentLink,
        string $abbreviation,
        string $webLink = '',
        string $period = '',
    ) {
        $this->facultyName = $facultyName;
        $this->logoLink = $logoLink;
        $this->attainmentLink = $attainmentLink;
        $this->webLink = $webLink;
        $this->period = $period;
        $this->abbreviation = $abbreviation;
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacultyName(): ?string
    {
        return $this->facultyName;
    }

    public function setFacultyName(string $facultyName): self
    {
        $this->facultyName = $facultyName;

        return $this;
    }

    public function getLogoLink(): ?string
    {
        return $this->logoLink;
    }

    public function setLogoLink(string $logoLink): self
    {
        $this->logoLink = $logoLink;

        return $this;
    }

    public function getAttainmentLink(): ?string
    {
        return $this->attainmentLink;
    }

    public function setAttainmentLink(string $attainmentLink): self
    {
        $this->attainmentLink = $attainmentLink;

        return $this;
    }

    public function getWebLink(): ?string
    {
        return $this->webLink;
    }

    public function setWebLink(string $webLink): self
    {
        $this->webLink = $webLink;

        return $this;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(?string $period): self
    {
        $this->period = $period;

        return $this;
    }

//    /**
//     * @return Collection|Course[]
//     */
//    public function getCourses(): Collection
//    {
//        return $this->courses;
//    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setFaculty($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getFaculty() === $this) {
                $course->setFaculty(null);
            }
        }

        return $this;
    }
}
