<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\PreparatoryCoursesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PreparatoryCoursesRepository::class)
 */
class PreparatoryCourses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $subjectTitle;

    /** @ORM\Column(type="string", length=255) */
    private string $subjectLink;

    /** @ORM\Column(type="string", length=255) */
    private string $preparatoryCourseScope;

    /** @ORM\Column(type="string", length=255) */
    private string $preparatoryCourseDate;

    /** @ORM\Column(type="string", length=255) */
    private string $preparatoryCoursePrice;

    /** @ORM\Column(type="string", length=255) */
    private string $preparatoryCourseContactPersonEmail;

    /** @ORM\Column(type="string", length=255) */
    private string $preparatoryCourseContactPersonName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectTitle(): ?string
    {
        return $this->subjectTitle;
    }

    public function setSubjectTitle(string $subjectTitle): self
    {
        $this->subjectTitle = $subjectTitle;

        return $this;
    }

    public function getSubjectLink(): ?string
    {
        return $this->subjectLink;
    }

    public function setSubjectLink(string $subjectLink): self
    {
        $this->subjectLink = $subjectLink;

        return $this;
    }

    public function getPreparatoryCourseScope(): ?string
    {
        return $this->preparatoryCourseScope;
    }

    public function setPreparatoryCourseScope(string $preparatoryCourseScope): self
    {
        $this->preparatoryCourseScope = $preparatoryCourseScope;

        return $this;
    }

    public function getPreparatoryCourseDate(): ?string
    {
        return $this->preparatoryCourseDate;
    }

    public function setPreparatoryCourseDate(string $preparatoryCourseDate): self
    {
        $this->preparatoryCourseDate = $preparatoryCourseDate;

        return $this;
    }

    public function getPreparatoryCoursePrice(): ?string
    {
        return $this->preparatoryCoursePrice;
    }

    public function setPreparatoryCoursePrice(string $preparatoryCoursePrice): self
    {
        $this->preparatoryCoursePrice = $preparatoryCoursePrice;

        return $this;
    }

    public function getPreparatoryCourseContactPersonEmail(): ?string
    {
        return $this->preparatoryCourseContactPersonEmail;
    }

    public function setPreparatoryCourseContactPerson(string $preparatoryCourseContactPersonEmail): self
    {
        $this->preparatoryCourseContactPersonEmail = $preparatoryCourseContactPersonEmail;

        return $this;
    }

    public function getPreparatoryCourseContactPersonName(): ?string
    {
        return $this->preparatoryCourseContactPersonName;
    }

    public function setPreparatoryCourseContactPersonName(string $preparatoryCourseContactPersonName): self
    {
        $this->preparatoryCourseContactPersonName = $preparatoryCourseContactPersonName;

        return $this;
    }
}
