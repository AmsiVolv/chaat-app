<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;
use ReflectionProperty;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $subjectCode;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $courseTitle;

    /** @ORM\Column(type="string", nullable=true) */
    private ?string $creditCount;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $courseLanguage;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $courseLevelAndYearOfStudy;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $courseRecommendation;

    /** @ORM\Column(type="text", nullable=true) */
    private ?string $courseContent;

    /** @ORM\Column(type="text", nullable=true) */
    private ?string $courseAims;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $courseUrl;

    /** @ORM\ManyToMany(targetEntity=Reading::class, mappedBy="course") */
    private $readings;

    /** @ORM\OneToMany(targetEntity=CourseSheduling::class, mappedBy="course") */
    private $courseScheduling;

    /** @ORM\ManyToOne(targetEntity=Faculty::class, inversedBy="courses") */
    private ?Faculty $faculty;

    /** @ORM\ManyToMany(targetEntity=Teacher::class, inversedBy="courses") */
    private $teacher;

    public function __construct(
        string $subjectCode,
        string $courseTitle,
        string $courseUrl
    ) {
        $this->subjectCode = $subjectCode;
        $this->courseTitle = $courseTitle;
        $this->courseUrl = $courseUrl;
        $this->readings = new ArrayCollection();
        $this->courseScheduling = new ArrayCollection();
        $this->teacher = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectCode(): ?string
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

    public function setCourseTitle(string $courseTitle): self
    {
        $this->courseTitle = $courseTitle;

        return $this;
    }

    public function getCreditCount(): ?string
    {
        return $this->creditCount;
    }

    public function setCreditCount(string $creditCount): self
    {
        $this->creditCount = $creditCount;

        return $this;
    }

    public function getCourseLanguage(): ?string
    {
        return $this->courseLanguage;
    }

    public function setCourseLanguage(string $courseLanguage): self
    {
        $this->courseLanguage = $courseLanguage;

        return $this;
    }

    public function getCourseLevelAndYearOfStudy(): ?string
    {
        return $this->courseLevelAndYearOfStudy;
    }

    public function setCourseLevelAndYearOfStudy(string $courseLevelAndYearOfStudy): self
    {
        $this->courseLevelAndYearOfStudy = $courseLevelAndYearOfStudy;

        return $this;
    }

    public function getCourseRecommendation(): ?string
    {
        return $this->courseRecommendation;
    }

    public function setCourseRecommendation(string $courseRecommendation): self
    {
        $this->courseRecommendation = $courseRecommendation;

        return $this;
    }

    public function getCourseAims(): ?string
    {
        return $this->courseAims;
    }

    public function setCourseAims(string $courseAims): self
    {
        $this->courseAims = $courseAims;

        return $this;
    }

    public function getCourseContent(): ?string
    {
        return $this->courseContent;
    }

    public function setCourseContent(string $courseContent): self
    {
        $this->courseContent = $courseContent;

        return $this;
    }

    /**
     * @return Collection|Reading[]
     */
    public function getReadings(): Collection
    {
        return $this->readings;
    }

    public function addReading(Reading $reading): self
    {
        if (!$this->readings->contains($reading)) {
            $this->readings[] = $reading;
            $reading->addCurse($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): self
    {
        if ($this->readings->removeElement($reading)) {
            $reading->removeSubject($this);
        }

        return $this;
    }

    /**
     * @return Collection|CourseSheduling[]
     */
    public function getCourseScheduling(): Collection
    {
        return $this->courseScheduling;
    }

    public function addCourseSheduling(CourseSheduling $courseSheduling): self
    {
        if (!$this->courseScheduling->contains($courseSheduling)) {
            $this->courseScheduling[] = $courseSheduling;
            $courseSheduling->setCourse($this);
        }

        return $this;
    }

    public function removeCourseSheduling(CourseSheduling $courseSheduling): self
    {
        if ($this->courseScheduling->removeElement($courseSheduling)) {
            // set the owning side to null (unless already changed)
            if ($courseSheduling->getCourse() === $this) {
                $courseSheduling->setCourse(null);
            }
        }

        return $this;
    }

    public function getFaculty(): ?Faculty
    {
        return $this->faculty;
    }

    public function setFaculty(?Faculty $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * @return Collection|Teacher[]
     */
    public function getTeacher(): Collection
    {
        return $this->teacher;
    }

    public function addTeacher(Teacher $teacher): self
    {
        if (!$this->teacher->contains($teacher)) {
            $this->teacher[] = $teacher;
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): self
    {
        $this->teacher->removeElement($teacher);

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

    /**
     * @return ReflectionProperty[]
     */
    public function getKeys(): array
    {
        return  [
            'subjectCode',
            'courseTitle',
            'creditCount',
            'courseLanguage',
            'courseLevelAndYearOfStudy',
            'courseRecommendation',
            'courseContent',
            'courseAims',
            'courseUrl',
            'readings',
            'courseScheduling',
            'teacher',
        ];
    }
}
