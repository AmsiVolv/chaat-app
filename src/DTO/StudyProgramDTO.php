<?php
declare(strict_types=1);

namespace App\DTO;

/**
 * Class StudyProgramDTO
 * @package App\DTO
 */
class StudyProgramDTO
{
    private int $id;
    private ?string $studyProgramLanguage;
    private ?string $studyProgramForm;
    private array $aims;
    private string $externalId;
    private string $studyProgramTitle;
    private string $link;
    private ?int $studyProgramCapacity;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getStudyProgramLanguage(): ?string
    {
        return $this->studyProgramLanguage;
    }

    public function setStudyProgramLanguage(?string $studyProgramLanguage): self
    {
        $this->studyProgramLanguage = $studyProgramLanguage;

        return $this;
    }

    public function getStudyProgramForm(): ?string
    {
        return $this->studyProgramForm;
    }

    public function setStudyProgramForm(?string $studyProgramForm): self
    {
        $this->studyProgramForm = $studyProgramForm;

        return $this;
    }

    public function getAims(): array
    {
        return $this->aims;
    }

    public function setAims(array $aims): self
    {
        $this->aims = $aims;

        return $this;
    }

    public function getExternalId(): string
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

    public function getLink(): string
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
