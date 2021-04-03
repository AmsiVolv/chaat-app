<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\StudyProgramLanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudyProgramLanguageRepository::class)
 */
class StudyProgramLanguage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string", length=255) */
    private string $language;

    /** @ORM\OneToMany(targetEntity=StudyProgram::class, mappedBy="studyProgramLanguage") */
    private $studyPrograms;

    public function __construct(string $language)
    {
        $this->language = $language;
        $this->studyPrograms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection|StudyProgram[]
     */
    public function getStudyPrograms(): Collection
    {
        return $this->studyPrograms;
    }

    public function addStudyProgram(StudyProgram $studyProgram): self
    {
        if (!$this->studyPrograms->contains($studyProgram)) {
            $this->studyPrograms[] = $studyProgram;
            $studyProgram->setStudyProgramLanguage($this);
        }

        return $this;
    }

    public function removeStudyProgram(StudyProgram $studyProgram): self
    {
        if ($this->studyPrograms->removeElement($studyProgram)) {
            // set the owning side to null (unless already changed)
            if ($studyProgram->getStudyProgramLanguage() === $this) {
                $studyProgram->setStudyProgramLanguage(null);
            }
        }

        return $this;
    }
}
