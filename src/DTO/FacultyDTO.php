<?php
declare(strict_types=1);

namespace App\DTO;

/**
 * Class FacultyDTO
 * @package App\DTO
 */
class FacultyDTO
{
    private int $id;
    private string $facultyName;
    private string $logoLink;
    private string $attainmentLink;
    private string $webLink;
    private string $abbreviation;
    private string $period;
    private array $courses;
    private array $studyPrograms;
    private string $studyAdmissionLink;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFacultyName(): string
    {
        return $this->facultyName;
    }

    public function setFacultyName(string $facultyName): self
    {
        $this->facultyName = $facultyName;

        return $this;
    }

    public function getLogoLink(): string
    {
        return $this->logoLink;
    }

    public function setLogoLink(string $logoLink): self
    {
        $this->logoLink = $logoLink;

        return $this;
    }

    public function getAttainmentLink(): string
    {
        return $this->attainmentLink;
    }

    public function setAttainmentLink(string $attainmentLink): self
    {
        $this->attainmentLink = $attainmentLink;

        return $this;
    }

    public function getWebLink(): string
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

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getCourses(): array
    {
        return $this->courses;
    }

    public function setCourses(array $courses): self
    {
        $this->courses = $courses;

        return $this;
    }

    public function getStudyPrograms(): array
    {
        return $this->studyPrograms;
    }

    public function setStudyPrograms(array $studyPrograms): self
    {
        $this->studyPrograms = $studyPrograms;

        return $this;
    }

    public function getStudyAdmissionLink(): string
    {
        return $this->studyAdmissionLink;
    }

    public function setStudyAdmissionLink(string $studyAdmissionLink): self
    {
        $this->studyAdmissionLink = $studyAdmissionLink;

        return $this;
    }


}
