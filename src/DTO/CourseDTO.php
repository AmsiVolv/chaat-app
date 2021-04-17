<?php
declare(strict_types=1);

namespace App\DTO;

/**
 * Class CourseDTO
 * @package App\DTO
 */
class CourseDTO
{
    private int $id;

    private string $subjectCode;
    private ?string $courseTitle;
    private ?string $creditCount;
    private ?string $courseLanguage;
    private ?string $courseLevelAndYearOfStudy;
    private ?string $courseRecommendation;
    private ?string $courseContent;
    private ?string $courseAims;
    private ?string $courseUrl;
    private ?FacultyDTO $faculty;

    /** @var TeacherDTO[]  */
    private array $teacher;

    /** @var CourseSchedulingDTO[] */
    private array $courseScheduling;

    /** @var ReadingDTO[]  */
    private array $readings;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSubjectCode(): string
    {
        return $this->subjectCode;
    }

    public function setSubjectCode(string $subjectCode): self
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    public function getCourseTitle(): ?string
    {
        return $this->courseTitle;
    }

    public function setCourseTitle(?string $courseTitle): self
    {
        $this->courseTitle = $courseTitle;

        return $this;
    }

    public function getCreditCount(): ?string
    {
        return $this->creditCount;
    }

    public function setCreditCount(?string $creditCount): self
    {
        $this->creditCount = $creditCount;

        return $this;
    }

    public function getCourseLanguage(): ?string
    {
        return $this->courseLanguage;
    }

    public function setCourseLanguage(?string $courseLanguage): self
    {
        $this->courseLanguage = $courseLanguage;

        return $this;
    }

    public function getCourseLevelAndYearOfStudy(): ?string
    {
        return $this->courseLevelAndYearOfStudy;
    }

    public function setCourseLevelAndYearOfStudy(?string $courseLevelAndYearOfStudy): self
    {
        $this->courseLevelAndYearOfStudy = $courseLevelAndYearOfStudy;

        return $this;
    }

    public function getCourseRecommendation(): ?string
    {
        return $this->courseRecommendation;
    }

    public function setCourseRecommendation(?string $courseRecommendation): self
    {
        $this->courseRecommendation = $courseRecommendation;

        return $this;
    }

    public function getCourseContent(): ?string
    {
        return $this->courseContent;
    }

    public function setCourseContent(?string $courseContent): self
    {
        $this->courseContent = $courseContent;

        return $this;
    }

    public function getCourseAims(): ?string
    {
        return $this->courseAims;
    }

    public function setCourseAims(?string $courseAims): self
    {
        $this->courseAims = $courseAims;

        return $this;
    }

    public function getCourseUrl(): ?string
    {
        return $this->courseUrl;
    }

    public function setCourseUrl(?string $courseUrl): self
    {
        $this->courseUrl = $courseUrl;

        return $this;
    }

    public function getFaculty(): ?FacultyDTO
    {
        return $this->faculty;
    }

    public function setFaculty(?FacultyDTO $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getTeacher(): array
    {
        return $this->teacher;
    }

    public function setTeacher(array $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getCourseScheduling(): array
    {
        return $this->courseScheduling;
    }

    public function setCourseScheduling(array $courseScheduling): self
    {
        $this->courseScheduling = $courseScheduling;

        return $this;
    }

    public function getReadings(): array
    {
        return $this->readings;
    }

    public function setReadings(array $readings): self
    {
        $this->readings = $readings;

        return $this;
    }
}
