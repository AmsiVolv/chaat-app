<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\StudyProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudyProgramRepository::class)
 */
class StudyProgram
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Faculty::class, inversedBy="studyPrograms")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Faculty $faculty;

    /**
     * @ORM\ManyToOne(targetEntity=StudyProgramLanguage::class, inversedBy="studyPrograms")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?StudyProgramLanguage $studyProgramLanguage;

    /** @ORM\ManyToOne(targetEntity=StudyProgramFormType::class, inversedBy="studyPrograms") */
    private ?StudyProgramFormType $studyProgramForm;

    /** @ORM\ManyToMany(targetEntity=StudyProgramAim::class, inversedBy="studyPrograms") */
    private $aims;

    /** @ORM\Column(type="string", length=255) */
    private string $externalId;

    /** @ORM\Column(type="string", length=255) */
    private string $studyProgramTitle;

    /** @ORM\Column(type="string", length=255) */
    private $link;

    /** @ORM\Column(type="integer", nullable=true) */
    private ?int $studyProgramCapacity;

    public function __construct()
    {
        $this->aims = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStudyProgramLanguage(): ?StudyProgramLanguage
    {
        return $this->studyProgramLanguage;
    }

    public function setStudyProgramLanguage(?StudyProgramLanguage $studyProgramLanguage): self
    {
        $this->studyProgramLanguage = $studyProgramLanguage;

        return $this;
    }

    public function getStudyProgramForm(): ?StudyProgramFormType
    {
        return $this->studyProgramForm;
    }

    public function setStudyProgramForm(?StudyProgramFormType $studyProgramForm): self
    {
        $this->studyProgramForm = $studyProgramForm;

        return $this;
    }

    /**
     * @return Collection|StudyProgramAim[]
     */
    public function getAims(): Collection
    {
        return $this->aims;
    }

    public function addAim(StudyProgramAim $aim): self
    {
        if (!$this->aims->contains($aim)) {
            $this->aims[] = $aim;
        }

        return $this;
    }

    public function removeAim(StudyProgramAim $aim): self
    {
        $this->aims->removeElement($aim);

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getStudyProgramTitle(): string
    {
        return $this->studyProgramTitle;
    }

    public function setStudyProgramTitle(string $studyProgramTitle): self
    {
        $this->studyProgramTitle = $studyProgramTitle;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getStudyProgramCapacity(): ?int
    {
        return $this->studyProgramCapacity;
    }

    public function setStudyProgramCapacity(?int $studyProgramCapacity): self
    {
        $this->studyProgramCapacity = $studyProgramCapacity;

        return $this;
    }
}
